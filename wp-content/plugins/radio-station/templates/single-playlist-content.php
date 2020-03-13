<?php

// --- link show playlists (top) ---
$post_id = get_the_ID();
$related_show = get_post_meta( $post_id, 'playlist_show_id', true );
if ( $related_show ) {
	$show = get_post( $related_show );
	$permalink = get_permalink( $show->ID );
	$show_link = '<a href="' . esc_url( $permalink ) . '">' . $show->post_title . '</a>';
	$before = __( 'Playlist for Show', 'radio-station' ) . ': ' . $show_link . '<br>';
	$before = apply_filters( 'radio_station_link_playlist_to_show_before', $before, $post, $show );
	echo $before;
}

// --- output the playlist post content ---
echo '<br>';
the_content();
		
// --- get the playlist data ---
$playlist = get_post_meta( $post_id, 'playlist', true );

if ( $playlist ) {
    ?>
	<div class="myplaylist-playlist-entries">
		<table>
		<tr>
			<th>#</th>
			<th><?php esc_html_e( 'Artist', 'radio-station' ); ?></th>
			<th><?php esc_html_e( 'Song', 'radio-station' ); ?></th>
			<th><?php esc_html_e( 'Album', 'radio-station' ); ?></th>
			<th><?php esc_html_e( 'Record Label', 'radio-station' ); ?></th>
			<th><?php esc_html_e( 'Comments', 'radio-station' ); ?></th>
		</tr>
		<?php
        $count = 1;
		foreach ( $playlist as $entry ) {
			if ( 'played' === $entry['playlist_entry_status'] ) {
                $new_class = '';
				if ( isset( $entry['playlist_entry_new'] ) && 'on' === $entry['playlist_entry_new'] ) {
					$new_class = 'class="new"';}
				?>
				<tr <?php echo esc_attr( $new_class ); ?>>
					<td><?php echo esc_attr( $count ); ?></td>
					<td><?php echo esc_html( $entry['playlist_entry_artist'] ); ?></td>
					<td><?php echo esc_html( $entry['playlist_entry_song'] ); ?></td>
					<td><?php echo esc_html( $entry['playlist_entry_album'] ); ?></td>
					<td><?php echo esc_html( $entry['playlist_entry_label'] ); ?></td>
					<td><?php echo esc_html( $entry['playlist_entry_comments'] ); ?></td>
				</tr>
			<?php
			$count++;
			}
			?>
		<?php } ?>
		</table>
	</div>
	<?php } else { ?>
	<div class="myplaylist-no-entries">
		<?php esc_html_e( 'No entries for this Playlist', 'radio-station' ); ?>
	</div>
<?php } 

// --- link show playlists (bottom) ---
if ( $related_show ) {
	$show_playlists_link = '<a href="' . esc_url( $permalink ) . '#show-playlists">&larr; ' . __( 'All Playlists for Show', 'radio-station' ) . ': ' . $show->post_title . '</a>';
	$after = '<br>' . $show_playlists_link;
	$after = apply_filters( 'radio_station_link_playlist_to_show_before', $after, $post, $show );
	echo $after;
}
