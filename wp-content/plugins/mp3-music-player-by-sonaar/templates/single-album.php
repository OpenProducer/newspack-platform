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
	$classes[] = 'sr_track_inline_cta_bt__yes';
	return $classes;
}

if (get_post_meta( $post->ID, 'post_player_type', true ) != 'default' && get_post_meta( $post->ID, 'post_player_type', true ) != ''){
	$player_layout = get_post_meta( $post->ID, 'post_player_type', true );
}else{
	$player_layout = ( !null == Sonaar_Music::get_option('player_widget_type', 'srmp3_settings_general') ) ? Sonaar_Music::get_option('player_widget_type', 'srmp3_settings_general') : 'skin_float_tracklist';
}

get_header();

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
?>

		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();?>
			<div class="sr-container">
				<div class="sr-boxed">
					<?php
					if ( !($post->post_password && post_password_required()) ){
						if( !null == Sonaar_Music::get_option('sr_single_post_use_custom_shortcode', 'srmp3_settings_widget_player') && Sonaar_Music::get_option('sr_single_post_use_custom_shortcode', 'srmp3_settings_widget_player') == 'true' && Sonaar_Music::get_option('sr_single_post_shortcode', 'srmp3_settings_widget_player') ){
							echo do_shortcode( Sonaar_Music::get_option('sr_single_post_shortcode', 'srmp3_settings_widget_player') );
						}else{
							$iron_sonaar_atts = array(
								'player_layout' => $player_layout,
								'albums' => array($post->ID),
								'show_playlist' => true,
								'force_cta_singlepost' => 'false',
								'one_track_boxed_hide_tracklist' => true,
								'show_album_market' => true,
								'show_track_market' => true,
								'post_link' => false,
								'adaptive_colors' => (!null == Sonaar_Music::get_option('sr_single_post_use_dynamic_ai', 'srmp3_settings_widget_player') && Sonaar_Music::get_option('sr_single_post_use_dynamic_ai', 'srmp3_settings_widget_player') == 'true') ? 'true' : false,
								'sticky_player' => (!null == Sonaar_Music::get_option('use_sticky_cpt', 'srmp3_settings_sticky_player') && Sonaar_Music::get_option('use_sticky_cpt', 'srmp3_settings_sticky_player') == 'true') ? 'true' : false,
								//'hide_trackdesc' => (!null == Sonaar_Music::get_option('hide_trackdesc', 'srmp3_settings_widget_player') && Sonaar_Music::get_option('hide_trackdesc', 'srmp3_settings_widget_player') == 'true') ? 'true' : false,
							);
							the_widget('Sonaar_Music_Widget', $iron_sonaar_atts, array( 'before_widget'=>'<article class="iron_widget_radio">', 'after_widget'=>'</article>', 'widget_id'=>'srp_single_player'));
						}
					}
					the_content();?>
				</div>
			</div>

			<?php
			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			//get_template_part( 'content', get_post_format() );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			// End the loop.
		endwhile;
		?>
<?php } ?>
<?php get_footer(); ?>