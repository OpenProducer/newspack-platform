<?php

/*
* Support functions for shortcodes and widgets
* Author: Nikki Blight
* Since: 2.0.14
*/

// === Legacy Functions ===
// - Current Schedule
// - Convert Time
// - Convert to 24 Hour
// - Get Current DJ
// - Get Next DJ
// - Get Now Playing
// - Get Overrides
// - Get Show Blog Posts
// - Shorten String
// === Passthrough Functions ===


// ------------------------
// === Legacy Functions ===
// ------------------------
// (still used in legacy shortcodes, templates and widgets)
// note: to be gradually deprecated as they are replaced

// --- get only the currently relevant schedule ---
// (used in DJ Widget)
function radio_station_current_schedule( $scheds = array() ) {

	$now = current_time( 'timestamp' );
	$current = array();

	foreach ( $scheds as $sched ) {

		// 2.3.0: added check is shift is disabled
		if ( !isset( $sched['disabled'] ) || ( 'yes' != $sched['disabled'] ) ) {

			if ( date( 'l', $now ) !== $sched['day'] ) {
				continue;
			}

			$start = strtotime( date( 'Y-m-d', $now ) . $sched['start_hour'] . ':' . $sched['start_min'] . ' ' . $sched['start_meridian'] );

			if ( 'pm' === $sched['start_meridian'] && 'am' === $sched['end_meridian'] ) {
				// check for shows that run overnight into the next morning
				$end = strtotime( date( 'Y-m-d', ( $now + 86400 ) ) . $sched['end_hour'] . ':' . $sched['end_min'] . ' ' . $sched['end_meridian'] );
			} else {
				$end = strtotime( date( 'Y-m-d', $now ) . $sched['end_hour'] . ':' . $sched['end_min'] . ' ' . $sched['end_meridian'] );
			}

			// a show cannot end before it begins... if it does, it ends the following day.
			if ( $end <= $start ) {
				$end = $end + 86400;
			}

			// compare to the current timestamp
			if ( ( $start <= $now ) && ( $end >= $now ) ) {
				$current = $sched;
			} else {
				continue;
			}
		}
	}

	return $current;
}

// --- convert shift times to 24-hour and timestamp formats for comparisons ---
function radio_station_convert_time( $time = array() ) {

	if ( empty( $time ) ) {
		return false;
	}

	$now = strtotime( current_time( 'mysql' ) );
	$cur_date = date( 'Y-m-d', $now );
	$tom_date = date( 'Y-m-d', ( $now + 86400 ) ); // get the date for tomorrow

	// --- convert to 24 hour time ---
	$time = radio_station_convert_schedule_to_24hour( $time );

	// --- get a timestamp for the schedule start and end ---
	$time['start_timestamp'] = strtotime( $cur_date . ' ' . $time['start_hour'] . ':' . $time['start_min'] );

	if ( 'pm' === $time['start_meridian'] && 'am' === $time['end_meridian'] ) {
		// check for shows that run overnight into the next morning
		$time['end_timestamp'] = strtotime( $tom_date . ' ' . $time['end_hour'] . ':' . $time['end_min'] );
	} else {
		$time['end_timestamp'] = strtotime( $cur_date . ' ' . $time['end_hour'] . ':' . $time['end_min'] );
	}

	// a show cannot end before it begins... if it does, it ends the following day.
	if ( $time['end_timestamp'] <= $time['start_timestamp'] ) {
		$time['end_timestamp'] = $time['end_timestamp'] + 86400;
	}

	return $time;
}

// --- convert a shift to 24 hour time for display ---
// TODO: replace all use of this function with new radio_station_convert_shift_time
function radio_station_convert_schedule_to_24hour( $sched = array() ) {

	if ( empty( $sched ) ) {
		return false;
	}

	if ( 'pm' === $sched['start_meridian'] && 12 !== (int) $sched['start_hour'] ) {
		$sched['start_hour'] = $sched['start_hour'] + 12;
	}
	if ( 'am' === $sched['start_meridian'] && $sched['start_hour'] < 10 ) {
		$sched['start_hour'] = '0' . $sched['start_hour'];
	}
	if ( 'am' === $sched['start_meridian'] && 12 === (int) $sched['start_hour'] ) {
		$sched['start_hour'] = '00';
	}

	if ( 'pm' === $sched['end_meridian'] && 12 !== (int) $sched['end_hour'] ) {
		$sched['end_hour'] = $sched['end_hour'] + 12;
	}
	if ( 'am' === $sched['end_meridian'] && $sched['end_hour'] < 10 ) {
		$sched['end_hour'] = '0' . $sched['end_hour'];
	}
	if ( 'am' === $sched['end_meridian'] && 12 === (int) $sched['end_hour'] ) {
		$sched['end_hour'] = '00';
	}

	return $sched;
}

// --- fetch the current DJ(s) on-air --
// used in DJ Widget, dj-widget shortcode
function radio_station_dj_get_current() {

	// --- first check to see if there are any shift overrides ---
	$check = radio_station_master_get_overrides( true );
	if ( $check ) {
		$shows = array(
			'all'  => $check,
			'type' => 'override',
		);
		return $shows;
	}

	global $wpdb;

	// --- get the current time and day ---
	$now = strtotime( current_time( 'mysql' ) );
	$cur_day = date( 'l', $now );

	// --- query for active shows only ---
	$show_shifts = $wpdb->get_results(
		"SELECT meta.post_id, meta.meta_value FROM {$wpdb->postmeta} AS meta
		JOIN {$wpdb->postmeta} AS metab
			ON meta.post_id = metab.post_id
		JOIN {$wpdb->posts} as posts
			ON posts.ID = meta.post_id
		WHERE meta.meta_key = 'show_sched' AND
			posts.post_status = 'publish' AND
			(
				metab.meta_key = 'show_active' AND
				metab.meta_value = 'on'
			)"
	);

	$show_ids = array();
	foreach ( $show_shifts as $shift ) {
		$shift->meta_value = maybe_unserialize( $shift->meta_value );

		// if a show has no shifts, unserialize() will return false instead of an empty array... fix that to prevent errors in the foreach loop.
		if ( !is_array( $shift->meta_value ) ) {
			$shift->meta_value = array();
		}

		foreach ( $shift->meta_value as $time ) {

			// 2.3.0: added check if shift has been disabled
			if ( !isset( $time['disabled'] ) || ( 'yes' != $time['disabled'] ) ) {

				// check if the shift is for the current day.  If it's not, skip it
				if ( $time['day'] === $cur_day ) {
					// format the time so that it is more easily compared
					$time = radio_station_convert_time( $time );

					// compare to the current timestamp
					if ( ( $time['start_timestamp'] <= $now ) && ( $time['end_timestamp'] >= $now ) ) {
						$show_ids[] = $shift->post_id;
					}
				}

				// we need to make a special allowance for shows that run from one day into the next
				if ( date( 'w', strtotime( $time['day'] ) ) + 1 == date( 'w', strtotime( $cur_day ) ) ) {

					$time = radio_station_convert_time( $time );
					// because station_convert_time assumes that the show STARTS on the current day,
					// when, in this case, it ends on the current day, we have to subtract 1 day from the timestamps
					$time['start_timestamp'] = $time['start_timestamp'] - 86400;
					$time['end_timestamp'] = $time['end_timestamp'] - 86400;

					// compare to the current timestamp
					if ( ( $time['start_timestamp'] <= $now ) && ( $time['end_timestamp'] >= $now ) ) {
						$show_ids[] = $shift->post_id;
					}
				}
			}
		}
	}

	$shows = array();
	foreach ( $show_ids as $id ) {
		$shows['all'][] = get_post( $id );
	}
	$shows['type'] = 'shows';

	return $shows;
}

// --- get the next DJ or DJs scheduled to be on air based on the current time ---
// used in DJ Upcoming Widget, dj-coming-up-widget shortcode
function radio_station_dj_get_next( $limit = 1 ) {

	global $wpdb;

	// get the various times/dates we need
	$cur_day = date( 'l', strtotime( current_time( 'mysql' ) ) );
	$cur_day_num = date( 'N', strtotime( current_time( 'mysql' ) ) );
	$cur_date = date( 'Y-m-d', strtotime( current_time( 'mysql' ) ) );
	$now = strtotime( current_time( 'mysql' ) );
	$shows = array();

	// first check to see if there are any shift overrides
	$check = radio_station_master_get_overrides();
	$overrides = array();

	if ( $check ) {

		foreach ( $check as $i => $p ) {

			$p['sched'] = radio_station_convert_time( $p['sched'] );

			// compare to the current timestamp
			if ( ( $p['sched']['start_timestamp'] <= $now ) && ( $p['sched']['end_timestamp'] >= $now ) ) {
				//show is on now, so we don't need it listed under upcoming
				//$overrides[$p['sched']['start_timestamp'].'|'.$p['sched']['end_timestamp']] = $p;
				unset( $check[$i] );
			} elseif ( ( $p['sched']['start_timestamp'] > $now ) && ( $p['sched']['end_timestamp'] > $now ) ) {
				// show is on later today
				$overrides[$p['sched']['start_timestamp'] . '|' . $p['sched']['end_timestamp']] = $p;
			} else {
				// show is already over and we don't need it
				unset( $check[$i] );
			}
		}

		// sort the overrides by start time
		ksort( $overrides );
	}

	// Fetch all schedules... we only want active shows
	$show_shifts = $wpdb->get_results(
		"SELECT meta.post_id, meta.meta_value
		FROM {$wpdb->postmeta} AS meta
		JOIN {$wpdb->postmeta} AS metab
			ON meta.post_id = metab.post_id
		JOIN {$wpdb->posts} as posts
			ON posts.ID = meta.post_id
		WHERE meta.meta_key = 'show_sched' AND
			posts.post_status = 'publish' AND
			(
				metab.meta_key = 'show_active' AND
				metab.meta_value = 'on'
			)"
	);

	$show_ids = $encore_ids = array();

	foreach ( $show_shifts as $shift ) {

		$shift->meta_value = maybe_unserialize( $shift->meta_value );

		// if a show has no shifts, unserialize() will return false instead of an empty array...
		// fix that to prevent errors in the foreach loop.
		if ( !is_array( $shift->meta_value ) ) {
			$shift->meta_value = array();
		}

		$encore_ids = array();
		$days = array(
			'Sunday'    => 7,
			'Monday'    => 1,
			'Tuesday'   => 2,
			'Wednesday' => 3,
			'Thursday'  => 4,
			'Friday'    => 5,
			'Saturday'  => 6,
		);
		foreach ( $shift->meta_value as $time ) {

			// 2.3.0: added check is shift is disabled
			if ( !isset( $time['disabled'] ) || ( 'yes' != $time['disabled'] ) ) {

				if ( $time['day'] === $cur_day ) {
					$cur_shift = strtotime( $cur_date . ' ' . $time['start_hour'] . ':' . $time['start_min'] . ':00 ' . $time['start_meridian'] );
					$end_shift = strtotime( $cur_date . ' ' . $time['end_hour'] . ':' . $time['end_min'] . ':00 ' . $time['end_meridian'] );
				} else {
					if ( $cur_day_num < $days[$time['day']] ) {
						$day_diff = $days[$time['day']] - $cur_day_num;
						$cur_shift = strtotime( $cur_date . ' ' . $time['start_hour'] . ':' . $time['start_min'] . ':00 ' . $time['start_meridian'] ) + ( $day_diff * 86400 );
						$end_shift = strtotime( $cur_date . ' ' . $time['end_hour'] . ':' . $time['end_min'] . ':00 ' . $time['end_meridian'] ) + ( $day_diff * 86400 );
					} else {
						$day_diff = $cur_day_num + $days[$time['day']] + 1;
						$cur_shift = strtotime( $cur_date . ' ' . $time['start_hour'] . ':' . $time['start_min'] . ':00 ' . $time['start_meridian'] ) + ( $day_diff * 86400 );
						$end_shift = strtotime( $cur_date . ' ' . $time['end_hour'] . ':' . $time['end_min'] . ':00 ' . $time['end_meridian'] ) + ( $day_diff * 86400 );
					}
				}

				// if the shift occurs later than the current time, we want it
				if ( $cur_shift >= $now ) {
					$show_ids[$cur_shift . '|' . $end_shift] = $shift->post_id;
					// 2.2.4: set encore ID array to pass back
					if ( isset( $time['encore'] ) && 'on' === $time['encore'] ) {
						$encore_ids[$cur_shift . '|' . $end_shift] = $shift->post_id;
					}
				}
			}
		}
	}

	// sort the shows by start time
	ksort( $show_ids );

	// merge in the overrides array
	foreach ( $show_ids as $s => $id ) {
		foreach ( $overrides as $o => $info ) {
			$stime = explode( '|', $s );
			$otime = explode( '|', $o );

			if ( $otime[0] <= $stime[1] ) { //check if an override starts before a show ends
				if ( $otime[1] > $stime[0] ) { //and it ends after the show begins (so we're not pulling overrides that are already over based on current time)
					unset( $show_ids[$s] ); // this show is overriden... drop it
				}
			}
		}
	}

	// Fallback function if the PHP Server does not have the array_replace function (i.e. prior to PHP 5.3)
	if ( !function_exists( 'array_replace' ) ) {

		function array_replace() {
			$array = array();
			$n = func_num_args();

			while ( $n -- > 0 ) {
				$array += func_get_arg( $n );
			}

			return $array;
		}
	}

	$combined = array_replace( $show_ids, $overrides );
	ksort( $combined );

	// grab the number of shows from the list the user wants to display
	$combined = array_slice( $combined, 0, $limit, true );

	// fetch detailed show information
	foreach ( $combined as $timestamp => $id ) {
		if ( !is_array( $id ) ) {
			$shows['all'][$timestamp] = get_post( $id );
		} else {
			$id['type'] = 'override';
			$shows['all'][$timestamp] = $id;
		}
	}
	$shows['type'] = 'shows';
	// 2.2.4: set encore IDs to pass back
	$shows['encore'] = $encore_ids;

	// return the information
	return $shows;
}

// --- get the most recently entered song ---
// (used in DJ Widget, dj-widget shortcode, Playlist Widget, now-playing shortcode)
function radio_station_get_now_playing() {

	// --- get the currently playing show ---
	// 2.3.0: added to prevent playlist/show mismatch!
	$current_show = radio_station_get_current_show();
	if ( !$current_show ) {
		return false;
	}
	if ( isset( $current_show['override'] ) && $current_show['override'] ) {
		$playlist = apply_filters( 'radio_station_override_now_playing', false, $current_show['override'] );
		return $playlist;
	}
	$show_id = $current_show['show']['id'];
	$shifts = $current_show['shifts'];
	$playlist = array();

	// --- grab the most recent playlist for the current show ---
	$args = array(
		'numberposts' => 1,
		'offset'      => 0,
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'post_type'   => RADIO_STATION_PLAYLIST_SLUG,
		'post_status' => 'publish',
		'meta_query'  => array(
			array(
				'key'		=> 'playlist_show_id',
				'value'		=> $show_id,
				'compare'	=> '=',
			),
		),
	);
	$playlist_posts = get_posts( $args );
	$playlist['query'] = $args;
	$playlist['posts'] = $playlist_posts;

	// TODO: check for playlist linked to this shift or date?

	if ( $playlist_posts && is_array( $playlist_posts ) && ( count( $playlist_posts ) > 0 ) ) {

		// --- fetch the tracks for the playlist ---
		// 2.3.0: added singular argument to true
		$playlist_post = $playlist_posts[0];
		$tracks = get_post_meta( $playlist_post->ID, 'playlist', true );

		if ( $tracks && is_array( $tracks ) && ( count( $tracks ) > 0 ) ) {

			// --- split off tracks marked as queued ---
			$queued = $played = array();
			foreach ( $tracks as $i => $track ) {
				if ( 'queued' == $track['playlist_entry_status'] ) {
					$queued[] = $track;
				} elseif ( 'played' == $track['playlist_entry_status'] ) {
					$played[] = $track;
				}
			}

			// --- get the track list for display  ---
			// 2.3.0: return full playlist instead of just last song
			$playlist['tracks'] = $tracks;
			$playlist['queued'] = $queued;
			$playlist['played'] = $played;
			$playlist['latest'] = array_pop( $tracks );

			// --- add show and playlist data ---
			// 2.3.0: add IDs and URLs instead of just playlist URL
			$playlist['show'] = $show_id;
			$playlist['show_url'] = get_permalink( $show_id );
			$playlist['playlist'] = $playlist_post->ID;
			$playlist['playlist_url'] = get_permalink( $playlist_post->ID );

		}
	}

	// --- filter and return tracks ---
	$playlist = apply_filters( 'radio_station_show_now_playing', $playlist, $show_id );

	return $playlist;
}

// --- get any schedule overrides for today's date ---
// (used in radio_station_dj_get_current, radio_station_dj_get_next)
// If currenthour is true, only overrides that are in effect NOW will be returned
// 2.3.0: add date argument to allow getting specific data overrides
function radio_station_master_get_overrides( $currenthour = false, $date = false ) {

	global $wpdb;

	$now = strtotime( current_time( 'mysql' ) );
	// 2.3.0: check if date argument supplied
	if ( !$date ) {
		$date = date( 'Y-m-d', $now );
	}
	$sql_date = $wpdb->esc_like( $date );
	$sql_date = '%' . $sql_date . '%';
	$show_shifts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT meta.post_id
			FROM {$wpdb->postmeta} AS meta
			WHERE meta_key = 'show_override_sched' AND
				meta_value LIKE %s",
			$sql_date
		)
	);

	$scheds = array();
	if ( $show_shifts ) {
		foreach ( $show_shifts as $shift ) {

			$next_sched = get_post_meta( $shift->post_id, 'show_override_sched', false );
			$time = $next_sched[0];

			if ( $currenthour ) {

				// --- convert to 24 hour time ---
				$check = $time;
				$time = radio_station_convert_time( $time );

				// --- compare to the current timestamp ---
				if ( ( $time['start_timestamp'] <= $now ) && ( $time['end_timestamp'] >= $now ) ) {
					$title = get_the_title( $shift->post_id );
					$scheds[] = array(
						'post_id' => $shift->post_id,
						'title'   => $title,
						'sched'   => $time,
					);
				} else {
					continue;
				}
			} else {
				$title = get_the_title( $shift->post_id );
				$sched = get_post_meta( $shift->post_id, 'show_override_sched', false );
				$scheds[] = array(
					'post_id' => $shift->post_id,
					'title'   => $title,
					'sched'   => $sched[0],
				);
			}
		}
	}

	return $scheds;
}

// --- fetch all blog posts for a show's DJs ---
// [Deprecated] replaced and no longer used
// 2.3.0: added backwards compatible unprefixed function for templates
function radio_station_myplaylist_get_posts_for_show( $show_id = null, $title = '', $limit = 10 ) {

	global $wpdb;

	// do not return anything if we don't have a show
	if ( !$show_id ) {
		return false;
	}

	$fetch_posts = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT meta.post_id
			FROM {$wpdb->postmeta} AS meta
			WHERE meta.meta_key = 'post_showblog_id' AND
  			meta.meta_value = %d",
			$show_id
		)
	);

	$blog_array = array();
	$blogposts = array();
	foreach ( $fetch_posts as $f ) {
		$blog_array[] = $f->post_id;
	}

	if ( $blog_array ) {

		// 2.2.8: fix to implode blog array to string
		// 2.3.0: allow for getting without limit
		$query = $wpdb->prepare(
			"SELECT posts.ID, posts.post_title
			FROM {$wpdb->posts} AS posts
			WHERE posts.ID IN(%s) AND
				posts.post_status = 'publish'
			ORDER BY posts.post_date DESC",
			implode( ',', $blog_array )
		);
		if ( $limit > 0 ) {
			$query .= $wpdb->prepare( " LIMIT %d", $limit );
		}
		$blogposts = $wpdb->get_results( $query );
	}

	$output = '';

	$output .= '<div id="myplaylist-blog-posts">';
	$output .= '<h3>' . $title . '</h3>';
	$output .= '<ul class="myplaylist-post-list">';
	foreach ( $blogposts as $p ) {
		$output .= '<li><a href="' . get_permalink( $p->ID ) . '">' . $p->post_title . '</a></li>';
	}
	$output .= '</ul>';
	$output .= '</div>';

	// if the blog archive page has been created, add a link to the archive for this show
	$page = $wpdb->get_results(
		"SELECT meta.post_id
		FROM {$wpdb->postmeta} AS meta
		WHERE meta.meta_key = '_wp_page_template' AND
			meta.meta_value = 'show-blog-archive-template.php'
		LIMIT 1"
	);

	if ( $page ) {
		$blog_archive = get_permalink( $page[0]->post_id );
		$params = array( 'show_id' => $show_id );
		$blog_archive = add_query_arg( $params, $blog_archive );

		$output .= '<a href="' . esc_url( $blog_archive ) . '">' . esc_html( __( 'More Show Blog Posts', 'radio-station' ) ) . '</a>';
	}

	return $output;
}

// --- shorten a string to a set number of words ---
function radio_station_shorten_string( $string, $limit ) {

	$shortened = $string;
	$array = explode( ' ', $string );

	if ( count( $array ) <= $limit ) {
		// --- already at or under the limit ---
		$shortened = $string;
	} else {
		// --- over the word limit so trim ---
		array_splice( $array, $limit );
		$shortened = implode( ' ', $array ) . ' ...';
	}

	return $shortened;
}


// -----------------------------
// === Passthrough Functions ===
// -----------------------------
// (trigger deprecated notices but run prefixed functions)
add_action( 'plugins_loaded', 'radio_station_legacy_functions' );
function radio_station_legacy_functions() {

	function radio_station_deprecated_function( $function, $replacement, $version ) {
		if ( WP_DEBUG && apply_filters( 'deprecated_function_trigger_error', true ) ) {
			$error = defined( 'E_USER_DEPRECATED' ) ? E_USER_DEPRECATED : E_USER_WARNING;
			if ( function_exists( '__' ) ) {
				/* translators: 1: PHP function name, 2: Version number, 3: Alternative function name. */
				trigger_error( sprintf( __( '%1$s is <strong>deprecated</strong> since Radio Station version %2$s! Use %3$s or check documentation for updated functionality.' ), $function, $version, $replacement ), $error );
			} else {
				trigger_error( sprintf( '%1$s is <strong>deprecated</strong> since Radio Station version %2$s! Use %3$s or check documentation for updated functionality.', $function, $version, $replacement ), $error );
			}
		}
    }
    
	if ( !function_exists( 'station_current_schedule' ) ) {
		function station_current_schedule( $scheds = array() ) {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_current_schedule', '2.2.0' );
			return radio_station_current_schedule( $scheds );
		}
	}

	if ( !function_exists( 'station_convert_time' ) ) {
		function station_convert_time( $time = array() ) {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_convert_time', '2.2.0' );
			return radio_station_convert_time( $time );
		}
	}
	
	if ( !function_exists( 'station_convert_schedule_to_24hour' ) ) {
		function station_convert_schedule_to_24hour( $sched = array() ) {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_convert_schedule_to_24hour', '2.2.0' );
			return radio_station_convert_schedule_to_24hour( $sched );
		}
	}

	if ( !function_exists( 'dj_get_current' ) ) {
		function dj_get_current() {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_dj_get_current', '2.2.0' );
			return radio_station_dj_get_current();
		}
	}

	if ( !function_exists( 'dj_get_next' ) ) {
		function dj_get_next( $limit = 1 ) {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_dj_get_next', '2.2.0' );
			return radio_station_dj_get_next( $limit );
		}
	}

	if ( !function_exists( 'myplaylist_get_now_playing' ) ) {
		function myplaylist_get_now_playing() {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_get_now_playing', '2.2.0' );
			return radio_station_get_now_playing();
		}
	}
	
	if ( !function_exists( 'master_get_overrides' ) ) {
		function master_get_overrides( $currenthour = false, $date = false ) {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_master_get_overrides', '2.2.0' );
			return radio_station_master_get_overrides( $currenthour, $date );
		}
	}

	if ( !function_exists( 'myplaylist_get_posts_for_show' ) ) {
		function myplaylist_get_posts_for_show( $show_id = null, $title = '', $limit = 10 ) {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_myplaylist_get_posts_for_show', '2.2.0' );
			return radio_station_myplaylist_get_posts_for_show( $show_id, $title, $limit );
		}
	}

	if ( !function_exists( 'station_shorten_string' ) ) {
		function station_shorten_string( $string, $limit ) {
			radio_station_deprecated_function( __FUNCTION__, 'radio_station_shorten_string', '2.2.0' );
			return radio_station_shorten_string( $string, $limit );
		}
	}
}
