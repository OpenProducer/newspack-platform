<?php
/**
 * The template for displaying all single playlist
 *
 *
 */
if ( function_exists( 'run_sonaar_music_pro' ) ){
	add_filter( 'body_class', 'srp_body_class' );
}
function srp_body_class( $classes ) {
	$classes[] = 'sr_taxonomy-show sr_track_inline_cta_bt__yes';
	return $classes;
}
get_header();
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
?>
	<div class="sr-container">
		<div class="sr-boxed">
			<?php
				if( !null == Sonaar_Music::get_option('sr_single_post_use_custom_shortcode', 'srmp3_settings_widget_player') && Sonaar_Music::get_option('sr_single_post_use_custom_shortcode', 'srmp3_settings_widget_player') == 'true' && Sonaar_Music::get_option('sr_single_post_shortcode', 'srmp3_settings_widget_player') ){
					$shortcode = Sonaar_Music::get_option('sr_single_post_shortcode', 'srmp3_settings_widget_player');
					$current_category_id = get_queried_object()->term_id;
					$shortcode = preg_replace(
						'/(\[sonaar_audioplayer[^\]]*)\]/', 
						'$1 category="' . esc_attr($current_category_id) . '" ]', 
						$shortcode, 
						1
					);
					echo do_shortcode( $shortcode );
				}else{
					$iron_sonaar_atts = array(
						'category'=> get_queried_object()->term_id,
						'player_layout'=> ( !null == Sonaar_Music::get_option('player_widget_type', 'srmp3_settings_general') ) ? Sonaar_Music::get_option('player_widget_type', 'srmp3_settings_general') : 'skin_float_tracklist',
						'track_artwork'=> true,
						'show_playlist' => true,
						'show_album_market' => true,
						'show_track_market' => true,
						'post_link' => false,
						'sticky_player' => (!null == Sonaar_Music::get_option('use_sticky_cpt', 'srmp3_settings_sticky_player') && Sonaar_Music::get_option('use_sticky_cpt', 'srmp3_settings_sticky_player') == 'true') ? 'true' : false,
					);
					the_widget('Sonaar_Music_Widget', $iron_sonaar_atts, array( 'before_widget'=>'<article class="iron_widget_radio">', 'after_widget'=>'</article>', 'widget_id'=>'srp_archive_player'));
				}
			?>
		</div>
	</div>
<?php }
get_footer(); ?>