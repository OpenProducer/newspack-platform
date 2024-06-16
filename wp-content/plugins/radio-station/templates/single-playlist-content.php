<?php

// ---------------------
// === Radio Station ===
// ---------------------
// -- Single Playlist --
// -- Content Template -
// ---------------------

// --- link show playlists (top) ---
$post_id = get_the_ID();
$related_show = get_post_meta( $post_id, 'playlist_show_id', true );
// 2.5.0: get allowed HTMl for filtered content
$allowed = radio_station_allowed_html( 'content', 'playlist' );
if ( $related_show ) {
	$show = get_post( $related_show );
	$permalink = get_permalink( $show->ID );
	$show_link = '<a href="' . esc_url( $permalink ) . '">' . $show->post_title . '</a>';
	$before = __( 'Playlist for Show', 'radio-station' ) . ': ' . $show_link . '<br>';
	$before = apply_filters( 'radio_station_link_playlist_to_show_before', $before, $post, $show );
	// 2.5.0: uses wp_kses on before section
	echo wp_kses( $before, $allowed );
}

// --- output the playlist post content ---
echo '<br>';
the_content();

// --- get the playlist data ---
$playlist = get_post_meta( $post_id, 'playlist', true );

if ( $playlist ) {

	// 2.4.0.3: added check for played tracks
	$found = $found_album = $found_label = $found_comments = false;
	foreach ( $playlist as $i => $track ) {
		if ( 'played' == $track['playlist_entry_status'] ) {
			$found = true;
		}
		// 2.5.8: add check for album, label and comments columns
		if ( '' != $track['playlist_entry_label'] ) {
			$found_album = true;
		}	
		if ( '' != $track['playlist_entry_label'] ) {
			$found_label = true;
		}
		if ( '' != $track['playlist_entry_comments'] ) {
			$found_comments = true;
		}
	}

	if ( $found ) {

		echo '<div class="myplaylist-playlist-entries">' . "\n";
			echo '<table>' . "\n";
			echo '<tr>' . "\n";
				echo '<th>#</th>' . "\n";
				echo '<th>' . esc_html( __( 'Artist', 'radio-station' ) ) . '</th>' . "\n";
				echo '<th>' . esc_html( __( 'Song', 'radio-station' ) ) . '</th>' . "\n";
				if ( $found_album ) {
					echo '<th>' . esc_html( __( 'Album', 'radio-station' ) ) . '</th>' . "\n";
				}
				if ( $found_label ) {
					echo '<th>' . esc_html( __( 'Label', 'radio-station' ) ) . '</th>' . "\n";
				}
				if ( $found_comments ) {
					echo '<th>' . esc_html( __( 'Comments', 'radio-station' ) ) . '</th>' . "\n";
				}
			echo '</tr>' . "\n";

			$count = 1;
			foreach ( $playlist as $entry ) {
				if ( 'played' === $entry['playlist_entry_status'] ) {

					// 2.4.0.3: remove new class it behavior changed
					// $new_class = '';
					// if ( isset( $entry['playlist_entry_new'] ) && 'on' === $entry['playlist_entry_new'] ) {
					// 	$new_class = 'class="new"';
					// }

					echo '<tr>' . "\n";
						echo '<td>' . esc_attr( $count ) . '</td>' . "\n";
						echo '<td>' . esc_html( $entry['playlist_entry_artist'] ) . '</td>' . "\n";
						echo '<td>' . esc_html( $entry['playlist_entry_song'] ) . '</td>' . "\n";
						if ( $found_album ) {
							echo '<td>' . esc_html( $entry['playlist_entry_album'] ) . '</td>' . "\n";
						}
						if ( $found_label ) {
							echo '<td>' . esc_html( $entry['playlist_entry_label'] ) . '</td>' . "\n";
						}
						if ( $found_comments ) {
							echo '<td>' . esc_html( $entry['playlist_entry_comments'] ) . '</td>' . "\n";
						}
					echo '</tr>' . "\n";
					$count++;
				}
			}
			echo '</table>' . "\n";
		echo '</div>' . "\n";

	}  else {

		// 2.4.0.3: added text to indicate no played tracks
		echo '<div class="myplaylist-no-entries">' . "\n";
			echo esc_html( __( 'No played tracks found for this Playlist yet.', 'radio-station' ) );
		echo '</div>' . "\n";
	}

} else {

	// --- not playlist entries message ---
	echo '<div class="myplaylist-no-entries">' . "\n";
		echo esc_html( __( 'No entries found for this Playlist', 'radio-station' ) ) . "\n";
	echo '</div>' . "\n";

}

// --- link show playlists (bottom) ---
if ( $related_show ) {
	$show_playlists_link = '<a href="' . esc_url( $permalink ) . '#show-playlists">&larr; ' . __( 'All Playlists for Show', 'radio-station' ) . ': ' . $show->post_title . '</a>';
	$after = '<br>' . $show_playlists_link . "\n";
	// 2.4.0.3: fix filter name to after not before
	$after = apply_filters( 'radio_station_link_playlist_to_show_after', $after, $post, $show );
	// 2.5.0: uses wp_kses on after section
	echo wp_kses( $after, $allowed );
}
