<?php

/*
 * Radio Station Data Endpoints
 * Author: Tony Hayes
 * @Since: 2.3.0
 */

// === API Discovery ===
// - Add Data API Header Link
// - Add Data API Discovery Link
// - Add Data API to RSD List
// === Data Functions ===
// - Get Station Data
// - Add Station Data
// - Get Broadcast Data
// - Get Shows Data
// - Get Genres Data
// === REST Routes ===
// - Register Rest Routes
// - Get Route URLs
// - Station Route
// - Current Broadcast Route
// - Show Schedule Route
// - Show List Route
// - Genre List Route
// - Language List Route
// === Feeds ===
// - Add Feeds
// - Add Feed Links to Data
// - Radio Endpoints Feed
// - Station Feed
// - Current Broadcast Feed
// - Show Schedule Feed
// - Show List Feed
// - Genre List Feed
// - Language List Feed
// - Not Found Feed Error
// - Format Data to XML
// - Convert Array to XML


// ---------------------
// === API Discovery ===
// ---------------------

// ------------------------
// Add Data API Header Link
// ------------------------
add_action( 'template_redirect', 'radio_station_api_link_header', 11, 0 );
function radio_station_api_link_header() {
	if ( headers_sent() ) {
		return;
	}
	$api_url = radio_station_get_api_url();
	$header = 'Link: <' . esc_url_raw( $api_url ) . '>; rel="' . RADIO_STATION_API_DOCS_URL . '"';
	$header = apply_filters( 'radio_station_api_discovery_header', $header );
	if ( $header ) {
		header( $header, false );
	}
}

// ---------------------------
// Add Data API Discovery Link
// ---------------------------
add_action( 'wp_head', 'radio_station_api_discovery_link' );
function radio_station_api_discovery_link() {
	$api_url = radio_station_get_api_url();
	$link = "<link rel='" . RADIO_STATION_API_DOCS_URL . "' href='" . esc_url( $api_url ) . "' />";
	$link = apply_filters( 'radio_station_api_discovery_link', $link );
	if ( $link ) {
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo $link;
	}
}

// ------------------------
// Add Data API to RSD List
// ------------------------
add_action( 'xmlrpc_rsd_apis', 'radio_station_api_discovery_rsd' );
function radio_station_api_discovery_rsd() {
	$api_url = radio_station_get_api_url();
	$link = '<api name="RadioStation" blogID="1" preferred="false" apiLink="' . esc_url( $api_url ) . '" />';
	$link = apply_filters( 'radio_station_api_discovery_rsd', $link );
	if ( $link ) {
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo $link;
	}
}


// ----------------------
// === Data Functions ===
// ----------------------

// --------------
// Add Query Vars
// --------------
// add_filter( 'query_vars', 'radio_station_add_feed_query_vars' );
function radio_station_add_feed_query_vars( $query_vars ) {

	// --- set feed query vars ---
	$vars = array( 'weekday', 'show', 'genre', 'language', 'format' );

	// --- add query vars ---
	foreach ( $vars as $var ) {
		if ( !in_array( $var, $query_vars ) ) {
	    	$query_vars[] = $var;
	    }
    }
    
    return $query_vars;
}

// ----------------
// Get Station Data
// ----------------
function radio_station_get_station_data() {

	// --- get radio timezone ---
	$timezone = radio_station_get_setting( 'timezone_location' );
	if ( !$timezone || ( '' == $timezone ) ) {
		$timezone = get_option( 'timezone_string' );
		if ( false !== strpos( $timezone, 'Etc/GMT' ) ) {
			$timezone = '';
		}
		if ( '' == $timezone ) {
			$timezone = 'UTC' . get_option( 'gmt_offset' );
		}
	}

	// --- get station data ---
	$stream_url = radio_station_get_stream_url();
	$station_url = radio_station_get_station_url();
	$schedule_url = radio_station_get_schedule_url();
	$language = radio_station_get_language();

	$now = strtotime( current_time( 'mysql' ) );
	$date_time = date( 'Y-m-d H:i:s', $now );

	// --- set station data array ---
	$station_data = array(
		'timezone'     => $timezone,
		'stream_url'   => $stream_url,
		'station_url'  => $station_url,
		'schedule_url' => $schedule_url,
		'language'     => $language['slug'],
		'timestamp'    => $now,
		'date_time'    => $date_time,
		'success'      => true,
	);
	$station_data = apply_filters( 'radio_station_station_data', $station_data );

	return $station_data;
}

// ----------------
// Add Station Data
// ----------------
function radio_station_add_station_data( $data ) {
	$station_data = radio_station_get_station_data();
	$data = array_merge( $data, $station_data );
	return $data;
}

// ------------------
// Get Broadcast Data
// ------------------
function radio_station_get_broadcast_data() {

	// --- get broadcast info ---
	$current_show = radio_station_get_current_show();
	print_r( $current_show );
	$current_show = radio_station_convert_show_shift( $current_show );
	print_r( $current_show );
	$next_show = radio_station_get_next_show();
	print_r( $next_show );
	$next_show = radio_station_convert_show_shift( $next_show );
	print_r( $nextt_show );

	// TODO: maybe get now playing playlist ?
	// $current_playlist = radio_station_current_playlist();

	// --- return current and next show info ---
	$broadcast = array(
		'current_show' => $current_show,
		'next_show'    => $next_show,
		// 'current_playlist' => $current_playlist,
	);
	$broadcast = apply_filters( 'radio_station_broadcast_data', $broadcast );

	return $broadcast;
}

// --------------
// Get Shows Data
// --------------
function radio_station_get_shows_data( $show = false ) {

	$shows = array();
	if ( $show ) {
		if ( strstr( $show, ',' ) ) {
			$show_ids = explode( ',', $show );
			foreach ( $show_ids as $show ) {
				$show = sanitize_title( $show );
				$show = radio_station_get_show( $show );
				$show = radio_station_get_show_data_meta( $show, true );
				$show = radio_station_convert_show_shifts( $show );
				$shows[] = $show;
			}
		} else {
			$show = sanitize_title( $show );
			$show = radio_station_get_show( $show );
			$show = radio_station_get_show_data_meta( $show, true );
			$show = radio_station_convert_show_shifts( $show );
			$shows = array( $show );
		}
	} else {
		$shows = radio_station_get_shows();
		if ( count( $shows ) > 0 ) {
			foreach ( $shows as $i => $show ) {
				$show = radio_station_get_show_data_meta( $show );
				$show = radio_station_convert_show_shifts( $show );
				$shows[$i] = $show;
			}
		}
	}
	$shows = apply_filters( 'radio_station_shows_data', $shows );

	return $shows;
}

// ---------------
// Get Genres Data
// ---------------
function radio_station_get_genres_data( $genre = false ) {

	// -- get genre or genres ---
	$genres = array();
	if ( $genre ) {
		if ( strstr( $genre, ',' ) ) {
			$genre_ids = explode( ',', $genre );
			foreach ( $genre_ids as $genre ) {
				$genre = sanitize_title( $genre );
				$genre = radio_station_get_genre( $genre );
				$genres[] = $genre;
			}
		} else {
			$genre = sanitize_title( $genre );
			$genres = radio_station_get_genre( $genre );
		}
	} else {
		$genres = radio_station_get_genres();
	}

	// --- loop genres to get shows ---
	if ( count( $genres ) > 0 ) {
		foreach ( $genres as $name => $genre ) {
			$shows = radio_station_get_genre_shows( $genre['slug'] );
			$genres[$name]['shows'] = array();
			$genres[$name]['show_count'] = 0;
			if ( is_object( $shows ) && property_exists( $shows, 'posts' )
			     && is_array( $shows->posts ) && ( count( $shows->posts ) > 0 ) ) {
				$genres[$name]['show_count'] = count( $shows->posts );
				foreach ( $shows->posts as $show ) {
					$show = radio_station_get_show_data_meta( $show );
					$show = radio_station_convert_show_shifts( $show );
					$genres[$name]['shows'][] = $show;
				}
			}
		}
	}
	$genres = apply_filters( 'radio_station_genres_data', $genres );

	return $genres;
}

// ------------------
// Get Languages Data
// ------------------
function radio_station_get_languages_data( $language = false ) {

	// -- get language or languages ---
	$languages_data = array();
	if ( $language ) {
		if ( strstr( $language, ',' ) ) {
			$language_ids = explode( ',', $language );
			foreach ( $language_ids as $language ) {
				$language = sanitize_title( $language );
				$language_data = radio_station_get_language( $language );
				if ( $language_data ) {
					$languages_data[$language] = $language_data;
				}
			}
		} else {
			$language = sanitize_title( $language );
			$language_data = radio_station_get_language( $language );
			$languages_data[$language] = $language_data;
		}
	} else {
		// --- get main site language ---
		$main_language = radio_station_get_language();
		$languages_data = array( $main_language['slug'] => $main_language );

		// --- get all assigned language terms ---
		$args = array( 'taxonomy' => RADIO_STATION_LANGUAGES_SLUG, 'hide_empty' => true );
		$terms = get_terms( $args );
		if ( count( $terms ) > 0 ) {
			$all_langs = radio_station_get_languages();
			foreach ( $terms as $term ) {
				$languages_data[$term->slug] = array(
					'id'          => $term->id,
					'slug'        => $term->slug,
					'name'        => $term->name,
					'description' => $term->description,
					'url'         => get_term_link( $term->id, RADIO_STATION_LANGUAGES_SLUG ),
				);
			}
		}
	}

	// --- loop languages to get shows ---
	if ( count( $languages_data ) > 0 ) {
		foreach ( $languages_data as $slug => $lang ) {
			$shows = radio_station_get_language_shows( $slug );
			$languages[$slug]['shows'] = array();
			$languages[$slug]['show_count'] = 0;
			if ( is_object( $shows ) && property_exists( $shows, 'posts' )
			     && is_array( $shows->posts ) && ( count( $shows->posts ) > 0 ) ) {
				$languages[$slug]['show_count'] = count( $shows->posts );
				foreach ( $shows->posts as $show ) {
					$show = radio_station_get_show_data_meta( $show );
					$show = radio_station_convert_show_shifts( $show );
					$languages[$slug]['shows'][] = $show;
				}
			}
		}
	}

	$languages_data = apply_filters( 'radio_station_languages_data', $languages_data, $language );

	return $languages_data;
}


// -------------------
// === REST Routes ===
// -------------------

// --------------------
// Register Rest Routes
// --------------------
add_action( 'rest_api_init', 'radio_station_register_rest_routes' );
function radio_station_register_rest_routes() {

	// --- check rest routes are enabled ---
	$enabled = radio_station_get_setting( 'enable_data_routes' );
	if ( 'yes' != $enabled ) {
		return;
	}

	// --- filter route slugs ---
	// (can disable individual routes by returning false from filters)
	$base = apply_filters( 'radio_station_route_slug_base', 'radio' );
	$station = apply_filters( 'radio_station_route_slug_station', 'station' );
	$broadcast = apply_filters( 'radio_station_route_slug_broadcast', 'broadcast' );
	$schedule = apply_filters( 'radio_station_route_slug_schedule', 'schedule' );
	$shows = apply_filters( 'radio_station_route_slug_shows', 'shows' );
	$genres = apply_filters( 'radio_station_route_slug_genres', 'genres' );
	$languages = apply_filters( 'radio_station_route_slug_languages', 'languages' );

	// --- set request method ---
	$args = array( 'methods' => 'GET' );

	// --- Station Route ---
	// default URL: /wp-json/radio/station/
	if ( $station ) {
		$args['callback'] = 'radio_station_route_station';
		register_rest_route( $base, '/' . $station . '/', $args );
	}

	// --- Show Broadcast Route ---
	// default URL: /wp-json/radio/broadcast/
	if ( $broadcast ) {
		$args['callback'] = 'radio_station_route_broadcast';
		register_rest_route( $base, '/' . $broadcast . '/', $args );
	}

	// --- Master Schedule Route ---
	// default URL: /wp-json/radio/schedule/
	// (?P<weekday>\d+)
	if ( $schedule ) {
		$args['callback'] = 'radio_station_route_schedule';
		// TODO: maybe add endpoint parameters (eg. weekday for schedule) ?
		// ref: https://stackoverflow.com/q/53126137/5240159
		// $args['args'] => array(
		//	'weekday' => array(
		//		'validate_callback' => function($param, $request, $key) {
		//			return is_numeric( $param );
		//		}
		//	),
		// ),
		register_rest_route( $base, '/' . $schedule . '/', $args );
		// unset( $args['args'] );
	}

	// --- Show List Route ---
	// default URL: /wp-json/radio/shows/
	$args['callback'] = 'radio_station_route_shows';
	if ( $shows ) {
		register_rest_route( $base, '/' . $shows . '/', $args );
	}

	// --- Show Genre List Route ---
	// default URL: /wp-json/radio/genres/
	$args['callback'] = 'radio_station_route_genres';
	if ( $genres ) {
		register_rest_route( $base, '/' . $genres . '/', $args );
	}

	// --- Language List Route ---
	// default URL: /wp-json/radio/languages/
	$args['callback'] = 'radio_station_route_languages';
	if ( $languages ) {
		register_rest_route( $base, '/' . $languages . '/', $args );
	}

}

// --------------
// Get Route URLs
// --------------
function radio_station_get_route_urls() {

	// --- get and add route links ---
	$routes = array();
	$station = radio_station_get_route_url( 'station' );
	if ( $station ) {
		$routes['station'] = $station;
	}
	$broadcast = radio_station_get_route_url( 'broadcast' );
	if ( $broadcast ) {
		$routes['broadcast'] = $broadcast;
	}
	$schedule = radio_station_get_route_url( 'schedule' );
	if ( $schedule ) {
		$routes['schedule'] = $schedule;
	}
	$shows = radio_station_get_route_url( 'shows' );
	if ( $shows ) {
		$routes['shows'] = $shows;
	}
	$genres = radio_station_get_route_url( 'genres' );
	if ( $genres ) {
		$routes['genres'] = $genres;
	}
	$languages = radio_station_get_route_url( 'languages' );
	if ( $languages ) {
		$routes['languages'] = $languages;
	}

	// --- maybe get and add pro route links ---
	$routes = apply_filters( 'radio_station_route_urls', $routes );

	return $routes;
}

// ------------------
// Radio Route Filter
// ------------------
// note: handled different to other routes as this is the base /radio route
add_filter( 'rest_request_after_callbacks', 'radio_station_route_radio', 11, 3 );
function radio_station_route_radio( $response, $handler, $request ) {

	if ( !is_wp_error( $response ) ) {
		$base = apply_filters( 'radio_station_route_slug_base', 'radio' );
		$route = $request->get_route();
		if ( '/' . $base == $route ) {
			$data = $response->data;
			$date['success'] = true;
			$data['endpoints'] = radio_station_get_route_urls();
			$response->data = $data;
		}
	}

	return $response;
}

// -------------
// Station Route
// -------------
// (combined data from all routes)
function radio_station_route_station( $request ) {

	$station = array();
	$broadcast = radio_station_get_broadcast_data();
	$station['broadcast'] = $broadcast;
	$schedule = radio_station_get_current_schedule();
	$schedule = radio_station_convert_schedule_shifts( $schedule );
	$station['schedule'] = $schedule;
	$shows_data = radio_station_get_shows_data();
	$station['shows'] = $shows_data;
	$genres_data = radio_station_get_genres_data();
	$station['genres'] = $genres_data;
	$languages_data = radio_station_get_languages_data();
	$station['languages'] = $languages_data;
	$station = radio_station_add_station_data( $station );
	$station['endpoints'] = radio_station_get_route_urls();
	$station = apply_filters( 'radio_station_route_station', $station, $request );

	return $station;
}

// -----------------------
// Current Broadcast Route
// -----------------------
function radio_station_route_broadcast( $request ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- get broadcast data ---
	$broadcast_data = radio_station_get_broadcast_data();
	if ( RADIO_STATION_DEBUG ) {
		echo "Broadcast: " . print_r( $broadcast_data, true );
	}

	// --- set broadcast output ---	
	$broadcast = array( 'broadcast' => $broadcast_data );
	$broadcast = radio_station_add_station_data( $broadcast );
	$broadcast['endpoints'] = radio_station_get_route_urls();
	$broadcast = apply_filters( 'radio_station_route_broadcast', $broadcast, $request );

	return $broadcast;
}

// -------------------
// Show Schedule Route
// -------------------
function radio_station_route_schedule( $request ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- get current schedule ---
	$schedule_data = radio_station_get_current_schedule();
	$schedule_data = radio_station_convert_schedule_shifts( $schedule_data );

	// --- check for weekday query ---
	$weekdays = array();
	$weekday = $singular = $multiple = false;
	if ( isset( $_GET['weekday'] ) ) {
	
		$weekday = $_GET['weekday'];
	
		if ( strstr( $_GET['weekday'], ',' ) ) {
			$multiple = true;
			$weekdays = explode( ',', $weekday );
		} else {
			$singular = true;
			$weekdays = array( $weekday );
		}

		// --- remove all shifts not on specified weekdays ---
		foreach ( $weekdays as $i => $day ) {
			$weekdays[$i] = strtolower( trim( $day ) );
		}
		if ( count( $schedule_data ) > 0 ) {
			foreach ( $schedule_data as $day => $shifts ) {
				if ( !in_array( strtolower( $day ), $weekdays ) ) {
					unset( $schedule_data[$day] );
				}
			}
		}
	}

	if ( RADIO_STATION_DEBUG ) {
		echo "Weekday: " . $weekday . PHP_EOL;
		echo "Weekdays: " . print_r( $weekdays, true ) . PHP_EOL;
		echo "Schedule: " . print_r( $schedule_data, true );
	}

	// --- set schedule output ---
	$schedule = array( 'schedule' => $schedule_data );
	$schedule = radio_station_add_station_data( $schedule );
	$schedule['endpoints'] = radio_station_get_route_urls();
	$schedule = apply_filters( 'radio_station_route_schedule', $schedule, $request );

	return $schedule;
}

// ---------------
// Show List Route
// ---------------
function radio_station_route_shows( $request ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- get show query parameter ---
	$show = $singular = $multiple = false;
	if ( isset( $_GET['show'] ) ) {
		$show = $_GET['show'];
		if ( strstr( $show, ',' ) ) {
			$multiple = true;
		} else {
			$singular = true;
		}
	}

	// --- get show list data ---
	$shows_data = radio_station_get_shows_data( $show );
	if ( RADIO_STATION_DEBUG ) {
		echo "Show: " . $show . PHP_EOL;
		echo "Shows: " . print_r( $shows_data, true );
	}

	// --- maybe return route error ---
	if ( count( $shows_data ) === 0 ) {
		if ( $singular ) {
			$code = 'show_not_found';
			$message = 'Requested Show was not found.';
		} elseif ( $multiple ) {
			$code = 'shows_not_found';
			$message = 'No Requested Shows were found.';
		} else {
			$code = 'no_shows';
			$message = 'No Shows were found.';
		}

		return new WP_Error( $code, $message, array( 'status' => 404 ) );
	}

	// --- return show list ---
	$show_list = array( 'shows' => $shows_data );
	$show_list = radio_station_add_station_data( $show_list );
	$show_list['endpoints'] = radio_station_get_route_urls();
	$show_list = apply_filters( 'radio_station_route_shows', $show_list, $request );

	return $show_list;
}

// ----------------
// Genre List Route
// ----------------
function radio_station_route_genres( $request ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- get genre query parameter ---
	$genre = $singular = $multiple = false;
	if ( isset( $_GET['genre'] ) ) {
		$genre = $_GET['genre'];
		if ( strstr( $genre, ',' ) ) {
			$multiple = true;
		} else {
			$singular = true;
		}
	}

	// --- get genre list data ---
	$genres_data = radio_station_get_genres_data( $genre );
	if ( RADIO_STATION_DEBUG ) {
		echo "Genre: " . $genre . PHP_EOL;
		echo "Genres: " . print_r( $genres_data, true );
	}

	// --- maybe return route error ---
	if ( count( $genres_data ) === 0 ) {
		if ( $singular ) {
			$code = 'genre_not_found';
			$message = 'Requested Genre was not found.';
		} elseif ( $multiple ) {
			$code = 'genres_not_found';
			$message = 'No Requested Genres were found.';
		} else {
			$code = 'no_genres';
			$message = 'No Genres were found.';
		}

		return new WP_Error( $code, $message, array( 'status' => 404 ) );
	}

	// --- return genre list ---
	$genre_list = array( 'genres' => $genres_data );
	$genre_list = radio_station_add_station_data( $genre_list );
	$genre_list['endpoints'] = radio_station_get_route_urls();
	$genre_list = apply_filters( 'radio_station_route_genres', $genre_list, $request );

	return $genre_list;
}

// -------------------
// Language List Route
// -------------------
function radio_station_route_languages( $request ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- get language query parameter ---
	$language = $singular = $multiple = false;
	if ( isset( $_GET['language'] ) ) {
		$language = $_GET['language'];
		if ( strstr( $language, ',' ) ) {
			$multiple = true;
		} else {
			$singular = true;
		}
	}

	// --- get language list data ---
	$languages_data = radio_station_get_languages_data( $language );
	if ( RADIO_STATION_DEBUG ) {
		echo "Language: " . $language . PHP_EOL;
		echo "Languages: " . print_r( $languages_data, true );
	}

	// --- maybe return route error ---
	if ( count( $languages_data ) === 0 ) {
		if ( $singular ) {
			$code = 'language_not_found';
			$message = 'Requested Language was not found.';
		} elseif ( $multiple ) {
			$code = 'languages_not_found';
			$message = 'No Requested Languages were found.';
		} else {
			$code = 'no_languages';
			$message = 'No Languages were found.';
		}

		return new WP_Error( $code, $message, array( 'status' => 404 ) );
	}

	// --- return language list ---
	$language_list = array( 'languages' => $languages_data );
	$language_list = radio_station_add_station_data( $language_list );
	$language_list['endpoints'] = radio_station_get_route_urls();
	$language_list = apply_filters( 'radio_station_route_languages', $language_list, $request );

	return $language_list;
}

// ------------------------
// Check for Genre Callback
// ------------------------
// function radio_station_check_genre( $genre ) {
//	$term = get_term_by( 'slug', $genre, RADIO_STATION_GENRES_SLUG );
//	if ( $term ) {return true;}
//	$term = get_term_by( 'name', $genre, RADIO_STATION_GENRES_SLUG );
//	if ( $term ) {return true;}
//	return false;
// }


// =============
// --- Feeds ---
// =============

// --------
// Add Feed
// --------
// (modified version of WordPress add_feed function)
function radio_station_add_feed( $feedname, $function ) {

	// note: removed as this is overwriting normal page slugs...
	// so /feed/schedule/ overwrites /schedule/ - which is no good!
	// global $wp_rewrite;
	// if ( ! in_array( $feedname, $wp_rewrite->feeds ) ) {
	//     $wp_rewrite->feeds[] = $feedname;
	// }

	$hook = 'do_feed_' . $feedname;
	remove_action( $hook, $hook );
	add_action( $hook, $function, 10, 2 );

	return $hook;
}

// ---------
// Add Feeds
// ---------
add_action( 'init', 'radio_station_add_feeds', 11 );
function radio_station_add_feeds() {

	// --- check feeds are enabled ---
	$enabled = radio_station_get_setting( 'enable_data_feeds' );
	if ( 'yes' != $enabled ) {
		return;
	}

	// --- filter feed slugs ---
	$base = apply_filters( 'radio_station_feed_slug_base', 'radio' );
	$station = apply_filters( 'radio_station_feed_slug_station', 'station' );
	$broadcast = apply_filters( 'radio_station_feed_slug_broadcast', 'broadcast' );
	$schedule = apply_filters( 'radio_station_feed_slug_schedule', 'schedule' );
	$shows = apply_filters( 'radio_station_feed_slug_shows', 'shows' );
	$genres = apply_filters( 'radio_station_feed_slug_genres', 'genres' );
	$languages = apply_filters( 'radio_station_feed_slug_languages', 'languages' );

	// --- add feeds ---
	if ( $base ) {
		radio_station_add_feed( $base, 'radio_station_feed_radio' );
	}
	if ( $station ) {
		radio_station_add_feed( $station, 'radio_station_feed_station' );
	}
	if ( $broadcast ) {
		radio_station_add_feed( $broadcast, 'radio_station_feed_broadcast' );
	}
	if ( $schedule ) {
		radio_station_add_feed( $schedule, 'radio_station_feed_schedule' );
	}
	if ( $shows ) {
		radio_station_add_feed( $shows, 'radio_station_feed_shows' );
	}
	if ( $genres ) {
		radio_station_add_feed( $genres, 'radio_station_feed_genres' );
	}
	if ( $languages ) {
		radio_station_add_feed( $languages, 'radio_station_feed_languages' );
	}

	// --- add single feed rewrite rule ---
	// (without risking overriding standard permalink slugs)
	// https://wordpress.stackexchange.com/questions/351576/add-feed-rewrite-overwriting-standard-permalinks/351603#351603
	$feeds = array( $base, $station, $broadcast, $schedule, $shows, $genres, $languages );
	$feeds = apply_filters( 'radio_station_feed_slugs', $feeds );
	foreach ( $feeds as $i => $feed ) {
		if ( !$feed ) {
			unset( $feeds[$i] );
		}
	}
	$feedstring = implode( '|', $feeds );
	$baserule = '^feed/' . $base . '/?$';
	$feedrule = '^feed/' . $base . '/(' . $feedstring . ')/?$';
	add_rewrite_rule( $baserule, 'index.php?feed=' . $base, 'top' );
	add_rewrite_rule( $feedrule, 'index.php?feed=$matches[1]', 'top' );

	// --- check if feeds are registered ---
	$rewrite_rules = get_option( 'rewrite_rules' );
	if ( !array_key_exists( $baserule, $rewrite_rules )
	  || !array_key_exists( $feedrule, $rewrite_rules ) ) {
		flush_rewrite_rules( false );
	}
	
}

// -------------
// Get Feed URLs
// -------------
function radio_station_get_feed_urls() {

	// --- get all feed URLs ---
	$feeds = array();
	$station = radio_station_get_feed_url( 'station' );
	if ( $station ) {
		$feeds['station'] = $station;
	}
	$broadcast = radio_station_get_feed_url( 'broadcast' );
	if ( $broadcast ) {
		$feeds['broadcast'] = $broadcast;
	}
	$schedule = radio_station_get_feed_url( 'schedule' );
	if ( $schedule ) {
		$feeds['schedule'] = $schedule;
	}
	$shows = radio_station_get_feed_url( 'shows' );
	if ( $shows ) {
		$feeds['shows'] = $shows;
	}
	$genres = radio_station_get_feed_url( 'genres' );
	if ( $genres ) {
		$feeds['genres'] = $genres;
	}
	$languages = radio_station_get_feed_url( 'languages' );
	if ( $languages ) {
		$feeds['languages'] = $languages;
	}

	// --- filter and return ---
	$feeds = apply_filters( 'radio_station_feed_urls', $feeds );

	return $feeds;
}

// --------------------
// Radio Endpoints Feed
// --------------------
function radio_station_feed_radio( $comment_feed, $feed_name ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	$base = apply_filters( 'radio_station_feed_slug_base', 'radio' );
	$radio = array( 'success' => true );
	$radio['namespace'] = $base;
	$radio['endpoints'] = radio_station_get_feed_urls();
	
	// --- reflect route format used in REST API ---
	$routes = array();
	foreach ( $radio['endpoints'] as $endpoint => $url ) {
		$key = '/' . $base . '/' . $endpoint;
		$routes[$key] = array(
			'namespace'	=> $base,
			'methods'	=> array ( 'GET' ),
			// 'endpoints' => array(),
			// 'url'		=> $url,
			'_links' => array(
				'self' => $url,
			),
		);
	}
	$radio['routes'] = $routes;
	$radio = apply_filters( 'radio_station_feed_radio', $radio );

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	//	echo radio_station_format_xml( $radio );
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $radio );
	// }
}

// ------------
// Station Feed
// ------------
// (combined data from all feeds)
function radio_station_feed_station( $comment_feed, $feed_name ) {

	$data = array();
	$broadcast = radio_station_get_broadcast_data();
	$station['broadcast'] = $broadcast;
	$schedule = radio_station_get_current_schedule();
	$schedule = radio_station_convert_schedule_shifts( $schedule );
	$station['schedule'] = $schedule;
	$shows_data = radio_station_get_shows_data();
	$station['shows'] = $shows_data;
	$genres_data = radio_station_get_genres_data();
	$station['genres'] = $genres_data;
	$languages_data = radio_station_get_languages_data();
	$station['languages'] = $languages_data;
	$station = radio_station_add_station_data( $station );
	$station['endpoints'] = radio_station_get_feed_urls();
	$station = apply_filters( 'radio_station_feed_station', $station );

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	//	echo radio_station_format_xml( $station );
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $station );
	// }
}

// ----------------------
// Current Broadcast Feed
// ----------------------
function radio_station_feed_broadcast( $comment_feed, $feed_name ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	$broadcast = radio_station_get_broadcast_data();
	if ( RADIO_STATION_DEBUG ) {
		echo "Broadcast: " . print_r( $broadcast, true );
	}
	
	$broadcast = array( 'broadcast', $broadcast );
	$broadcast = radio_station_add_station_data( $broadcast );
	$broadcast['endpoints'] = radio_station_get_feed_urls();
	$broadcast = apply_filters( 'radio_station_feed_broadcast', $broadcast );

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	//	echo radio_station_format_xml( $broadcast );
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $broadcast );
	// }
}

// ------------------
// Show Schedule Feed
// ------------------
function radio_station_feed_schedule( $comment_feed, $feed_name ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- get current schedule ---
	$schedule_data = radio_station_get_current_schedule();
	$schedule_data = radio_station_convert_schedule_shifts( $schedule_data );

	// --- check for weekday query ---
	$weekdays = array();
	$weekday = $singular = $multiple = false;
	if ( isset( $_GET['weekday'] ) ) {
	
		$weekday = $_GET['weekday'];
	
		if ( strstr( $_GET['weekday'], ',' ) ) {
			$multiple = true;
			$weekdays = explode( ',', $weekday );
		} else {
			$singular = true;
			$weekdays = array( $weekday );
		}

		// --- remove all shifts not on specified weekdays ---
		foreach ( $weekdays as $i => $day ) {
			$weekdays[$i] = strtolower( trim( $day ) );
		}
		if ( count( $schedule_data ) > 0 ) {
			foreach ( $schedule_data as $day => $shifts ) {
				if ( !in_array( strtolower( $day ), $weekdays ) ) {
					unset( $schedule_data[$day] );
				}
			}
		}
	}

	if ( RADIO_STATION_DEBUG ) {
		echo "Weekday: " . $weekday . PHP_EOL;
		echo "Weekdays: " . print_r( $weekdays, true ) . PHP_EOL;
		echo "Schedule: " . print_r( $schedule_data, true );
	}

	// --- set schedule output ---
	$schedule = array( 'schedule' => $schedule_data );
	$schedule = radio_station_add_station_data( $schedule );
	$schedule['endpoints'] = radio_station_get_feed_urls();
	$schedule = apply_filters( 'radio_station_feed_schedule', $schedule );

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	//	echo radio_station_format_xml( $schedule );
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $schedule );
	// }
}

// --------------
// Show List Feed
// --------------
function radio_station_feed_shows( $comment_feed, $feed_name ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- check for show query ---
	$show = $singular = $multiple = false;
	if ( isset( $_GET['show'] ) ) {
		$show = $_GET['show'];
		if ( strstr( $show, ',' ) ) {
			$multiple = true;
		} else {
			$singular = true;
		}
	}

	// --- get show list data ---
	$shows_data = radio_station_get_shows_data( $show );

	// --- maybe output feed error message ---
	if ( count( $shows_data ) === 0 ) {
		if ( $singular ) {
			$details = __( 'Requested Show was not found.', 'radio-station' );
		} elseif ( $multiple ) {
			$details = __( 'No Requested Shows were found.', 'radio-station' );
		} else {
			$details = __( 'No Shows were found.', 'radio-station' );
		}
		radio_station_feed_not_found( $details );

		return;
	}

	$show_list = array( 'shows' => $shows_data );
	if ( RADIO_STATION_DEBUG ) {
		echo "Show: " . $show . PHP_EOL;
		echo "Shows: " . print_r( $show_list, true );
	}

	// --- output encoded show list ---
	$show_list = radio_station_add_station_data( $show_list );
	$show_list['endpoints'] = radio_station_get_feed_urls();
	$show_list = apply_filters( 'radio_station_feed_shows', $show_list );

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	echo radio_station_format_xml( $show_list );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $show_list );
	// }
}

// ---------------
// Genre List Feed
// ---------------
function radio_station_feed_genres( $comment_feed, $feed_name ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- check for genre query ---
	$genre = $singular = $multiple = false;
	if ( isset( $_GET['genre'] ) ) {
		$genre = $_GET['genre'];
		if ( strstr( $genre, ',' ) ) {
			$multiple = true;
		} else {
			$singular = true;
		}
	}

	// --- get genre list data ---
	$genres_data = radio_station_get_genres_data( $genre );

	// --- maybe output feed error message ---
	if ( count( $genres_data ) === 0 ) {
		if ( $singular ) {
			$details = __( 'Requested Genre was not found.', 'radio-station' );
		} elseif ( $multiple ) {
			$details = __( 'No Requested Genres were found.', 'radio-station' );
		} else {
			$details = __( 'No Genres were found.', 'radio-station' );
		}
		radio_station_feed_not_found( $details );

		return;
	}

	// --- output encoded genre list ---
	$genre_list = array( 'genres' => $genres_data );
	if ( RADIO_STATION_DEBUG ) {
		echo "Genre: " . $genre . PHP_EOL;
		echo "Genres: " . print_r( $genre_list, true );
	}

	$genre_list = radio_station_add_station_data( $genre_list );
	$genre_list['endpoints'] = radio_station_get_feed_urls();
	$genre_list = apply_filters( 'radio_station_feed_genres', $genre_list );

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	//	echo radio_station_format_xml( $genre_list );
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $genre_list );
	// }
}

// -------------------
// Languages List Feed
// -------------------
function radio_station_feed_languages( $comment_feed, $feed_name ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	// --- check for single language query ---
	$language = $singular = $multiple = false;
	if ( isset( $_GET['language'] ) ) {
		$language = $_GET['language'];
		if ( strstr( $language, ',' ) ) {
			$multiple = true;
		} else {
			$singular = true;
		}
	}

	// --- get genre list data ---
	$languages_data = radio_station_get_languages_data( $language );

	// --- maybe output feed error message ---
	if ( count( $languages_data ) === 0 ) {
		if ( $singular ) {
			$details = __( 'Requested Language was not found.', 'radio-station' );
		} elseif ( $multiple ) {
			$details = __( 'No Requested Languages were found.', 'radio-station' );
		} else {
			$details = __( 'No Languages were found.', 'radio-station' );
		}
		radio_station_feed_not_found( $details );

		return;
	}

	// --- output encoded language list ---
	$language_list = array( 'languages' => $languages_data );
	if ( RADIO_STATION_DEBUG ) {
		echo "Language: " . $language;
		echo "Languages: " . print_r( $language_list, true );
	}

	// --- set languages list output ---
	$language_list = radio_station_add_station_data( $language_list );
	$language_list['endpoints'] = radio_station_get_feed_urls();
	$language_list = apply_filters( 'radio_station_feed_languages', $language_list );

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	//	echo radio_station_format_xml( $language_list );
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $language_list );
	// }
}

// --------------------
// Not Found Feed Error
// --------------------
function radio_station_feed_not_found( $details ) {

	if ( RADIO_STATION_DEBUG ) {
		header( 'Content-Type: text/plain' );
	}

	$error = array(
		'success' => false,
		'errors'  => array(
			'status'  => 404,
			'code'    => 404,
			'title'   => __( 'Error 404 Not Found', 'radio-station' ),
			'message' => __( 'The requested data could not be found.', 'radio-station' ),
			'detail'  => $details,
		),
	);

	// if ( isset( $_GET['format'] ) && ( 'xml' == $_GET['format'] ) ) {
	//	header( 'Content-Type: application/rss+xml' );
	//	// phpcs:ignore WordPress.Security.OutputNotEscaped
	//	echo radio_station_format_xml( $error );
	// } else {
		header( 'Content-Type: application/json' );
		// phpcs:ignore WordPress.Security.OutputNotEscaped
		echo json_encode( $error );
	// }
}

// ------------------
// Format Data to XML
// ------------------
function radio_station_format_xml( $data ) {

	$xml = new SimpleXMLElement( '<station/>' );
	$xml = radio_station_array_to_xml( $xml, $data );
	$output = $xml->asXML();

	$dom = new DOMDocument();
	// $dom->formatOutput = true;
	$dom->loadXML( $output );
	$output = $dom->saveXML();

	return $output;
}

// --------------------
// Convert Array to XML
// --------------------
// TODO: fix unworking array to XML conversion?
function radio_station_array_to_xml( SimpleXMLElement $object, $data ) {
	
	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) ) {
			$newobject = $object->addChild( $key );
			radio_station_array_to_xml( $newobject, $value );
		} else {
			$object->addChild( $key, htmlspecialchars( $value ) );
		}
	}
}

