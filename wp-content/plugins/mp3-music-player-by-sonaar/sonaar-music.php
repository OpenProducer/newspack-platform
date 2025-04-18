<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              sonaar.io
 * @since             1.0.0
 * @package           Sonaar_Music
 *
 * @wordpress-plugin
 * Plugin Name:       MP3 Audio Player by Sonaar
 * Plugin URI:        https://sonaar.io/mp3-audio-player-pro/?utm_source=Sonaar+Music+Free+Plugin&utm_medium=plugin
 * Description:       The most popular and complete Music & Podcast Player for WordPress.
 * Version:           5.9.5
 * Author:            Sonaar Music
 * Author URI:        https://sonaar.io/?utm_source=Sonaar%20Music%20Free%20Plugin&utm_medium=plugin
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sonaar-music
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('SRMP3_VERSION', '5.9.5'); // important to avoid cache issues on update
define('SRMP3_PRO_MIN_VERSION', '5.9.5'); // Minimum pro version required
if ( !defined( 'SRMP3_DIR_PATH' ) ) {
    define( 'SRMP3_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !class_exists( 'Sonaar_Music' )) {

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-sonaar-music.php';
	
	register_activation_hook(__FILE__,  'srmp3_activate');
	
	function srmp3_activate() {
        add_option('srmp3_free_wizard_redirect', true);
    }
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */


	function srmp3_set_template( $template ){
		//Add option for plugin to turn this off? If so just return $template
		//Check if the taxonomy/single is being viewed 
		if( is_archive() && is_tax('podcast-show') || is_archive() && is_tax('playlist-category'))
			return srmp3_template_path('taxonomy-show');

		if ( is_single() && SR_PLAYLIST_CPT === get_queried_object()->post_type)
			return srmp3_template_path('single-album');

		return $template;
	}

	function srmp3_template_path( $fileName ){
		if(file_exists(get_stylesheet_directory().'/mp3-music-player-by-sonaar/'.$fileName.'.php')){
			return get_stylesheet_directory().'/mp3-music-player-by-sonaar/'.$fileName.'.php';
		}
		return dirname( __FILE__ ) . '/templates/'.$fileName.'.php';
	}

	
	

	function srmp3_register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location( 'playlist' );
	}

	function srmp3_create_customfeed() {
		load_template( plugin_dir_path( __FILE__ ) .'templates/podcast-feed.php');
	}
	
	function srmp3_custom_feed_rewrite($wp_rewrite) {
		$feed_rules = array(
			'feed/(.+)' => 'index.php?feed=' . $wp_rewrite->preg_index(1),
		);
		$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
	}

	function srmp3_feed_content_type( $content_type = '', $type = '' ) {
		if ( apply_filters( 'sonaar_feed_slug', 'podcast' ) === $type ) {
			$content_type = 'text/xml';
		}
		return $content_type;
	}

	if ( Sonaar_Music::get_option('player_type', 'srmp3_settings_general') == 'podcast' ){
		$sr_disable_rss = (Sonaar_Music::get_option('podcast_setting_rssfeed_disable', 'srmp3_settings_general') === "true") ? true : false;
		if( !$sr_disable_rss ){
			if( Sonaar_Music::get_option('podcast_setting_rssfeed_redirect', 'srmp3_settings_general') === "true" && Sonaar_Music::get_option('podcast_setting_rssfeed_slug', 'srmp3_settings_general') != ''){
				$podcast_feed_slug = Sonaar_Music::get_option('podcast_setting_rssfeed_slug', 'srmp3_settings_general');
			}else{
				$podcast_feed_slug = 'podcast'; //default
			}
			add_action( 'do_feed_'.$podcast_feed_slug, 'srmp3_create_customfeed', 10, 1); //do_feed_{$feed} will set {$feed} as /feed/{$feed} url
			add_filter( 'generate_rewrite_rules',  'srmp3_custom_feed_rewrite');
			add_filter( 'feed_content_type',  'srmp3_feed_content_type', 10, 2 );
		}
	}
	
	add_filter( 'template_include', 'srmp3_set_template');
	add_action( 'elementor/theme/register_locations', 'srmp3_register_elementor_locations' );

	
	function run_sonaar_music() {
		$plugin = new Sonaar_Music();
		$plugin->run();
	}
	
	run_sonaar_music();

}

add_action('wp_ajax_import_srmp3_elementor_template', 'import_srmp3_elementor_template');
add_action('wp_ajax_load_post_by_ajax', 'load_post_by_ajax_callback');
add_action('wp_ajax_nopriv_load_post_by_ajax', 'load_post_by_ajax_callback');
 
function load_post_by_ajax_callback() {
    check_ajax_referer('sonaar_music_ajax_nonce', 'nonce');
	$stringValue = sanitize_text_field($_POST['store-id']);

	/*Validation*/
    $validatedCharacters = ['a','0','1','2','3','4','5','6','7','8','9','-'];
	$validation = true;
	for ($i=0; $i<strlen($stringValue); $i++) {
		if( ! in_array($stringValue[$i], $validatedCharacters)){
			$validation = false;
		}
	}
	if(!$validation){
		wp_die();
	}

	$storeID = explode('-', $stringValue); 

	/* $storeID = array[track number, store number]
	$storeID[track number] == "a" when store is from the "avalable now" section
	*/
	if( $storeID[0] == 'a' ){   //store from the "available now" section
		$response = get_post_meta(sanitize_text_field($_POST['id']), 'alb_store_list', true );
		echo  json_encode(do_shortcode(nl2br($response[$storeID[1]]['store-content'])));
	}else{ //store from the tracklist
		$response = get_post_meta(sanitize_text_field($_POST['id']), 'alb_tracklist', true );
		$response = json_encode(do_shortcode(nl2br( $response[$storeID[0]]['song_store_list'][$storeID[1]]['store-content'])));
		if (strpos($response, '\/wp-admin\/admin-ajax.php')) { //Replace the right post url
			$postPermalink = nl2br(str_replace(home_url(), '', get_permalink($_POST['id']))); 	
			$response = str_replace('\/wp-admin\/admin-ajax.php', $postPermalink, $response); 
		}
		
	
		echo   $response   ;
	}
	 wp_die();
}


add_action('wp_ajax_load_track_note_ajax', 'load_track_note_ajax_callback');
add_action('wp_ajax_nopriv_load_track_note_ajax', 'load_track_note_ajax_callback'); 
 
function load_track_note_ajax_callback() {
	check_ajax_referer('sonaar_music_ajax_nonce', 'nonce');
	
	if($_POST['track-desc-postcontent'] == '1'){
		$postobj = get_post(sanitize_text_field($_POST['post-id']));
		$description = sanitize_text_field($postobj->post_content);
	}else{
		$postobj = get_post_meta(sanitize_text_field($_POST['post-id']), 'alb_tracklist', true );
		$description = $postobj[sanitize_text_field($_POST['track-position'])]['track_description'];
	}
	echo wp_json_encode( '<div class="srp_note_title">' . sanitize_text_field(stripslashes($_POST['track-title'])) . '</div>'. $description );
	
	wp_die();
}

add_action('wp_ajax_load_lyrics_ajax', 'load_lyrics_ajax_callback');
add_action('wp_ajax_nopriv_load_lyrics_ajax', 'load_lyrics_ajax_callback');
function load_lyrics_ajax_callback() {
    check_ajax_referer('sonaar_music_ajax_nonce', 'nonce');
	$ttml_content = get_post_meta(sanitize_text_field($_POST['post-id']), 'sr_sonaar_tts_post_ttml', true); // coming from TTS Plugin
    $postmeta = get_post_meta(sanitize_text_field($_POST['post-id']), 'alb_tracklist', true);

    if (isset($postmeta[sanitize_text_field($_POST['track-position'])]['track_lyrics']) || $ttml_content) {
        $ttml_content = ($ttml_content) ? $ttml_content : $postmeta[sanitize_text_field($_POST['track-position'])]['track_lyrics'];
        $response = wp_remote_get($ttml_content, array('sslverify' => false));

        if (is_wp_error($response)) {
            $error_response = array(
                'error' => 'Failed to retrieve lyrics. Error: ' . $response->get_error_message()
            );
            echo wp_json_encode($error_response);
        } else {
			$body = wp_remote_retrieve_body($response);
			//$response_code = wp_remote_retrieve_response_code($response);
			//error_log('Lyrics Response Code: ' . $response_code);
            echo wp_json_encode($body);
        }
    } else {
        // Return a JSON response indicating that the key is not set
        $error_response = array(
            'error' => 'The key "track_lyrics" is not set or is undefined.'
        );
        echo wp_json_encode($error_response);
    }

    wp_die();
}

