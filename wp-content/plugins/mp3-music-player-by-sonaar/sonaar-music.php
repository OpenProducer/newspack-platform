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
 * Version:           5.15
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

define('SRMP3_VERSION', '5.15'); // important to avoid cache issues on update
define('SRMP3_PRO_MIN_VERSION', '5.15'); // Minimum pro version required
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
		/*
		 * Keep resolved block templates intact so playlists and their taxonomies
		 * can use templates created with the Gutenberg Site Editor.
		 */
		$block_template_canvas = ABSPATH . WPINC . '/template-canvas.php';
		if ( wp_normalize_path( $template ) === wp_normalize_path( $block_template_canvas ) ) {
			return $template;
		}

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

/**
 * Sanitize the HTML rendered inside a store popup.
 *
 * Store popups support formatted content, shortcodes, and embedded media. The
 * iframe attributes below preserve common video/audio embeds while KSES removes
 * executable tags, event handlers, and unsafe URL protocols.
 *
 * @param mixed $content         Popup content.
 * @param bool  $allow_style_tag Whether trusted shortcode output may keep style elements.
 * @return string Sanitized popup content.
 */
function srmp3_sanitize_store_popup_content( $content, $allow_style_tag = false ) {
	if ( ! is_string( $content ) ) {
		return '';
	}

	$style_blocks = array();
	if ( ! $allow_style_tag ) {
		// Do not turn user-supplied CSS into visible text when KSES removes its wrapper.
		$content = preg_replace( '#<style\b[^>]*>.*?(?:</style\s*>|$)#is', '', $content );
	} else {
		// Protect trusted shortcode CSS from KSES entity encoding, then restore it below.
		$content = preg_replace_callback(
			'#<style\b[^>]*>(.*?)</style\s*>#is',
			function ( $matches ) use ( &$style_blocks ) {
				$placeholder = 'SRMP3STYLEBLOCK' . count( $style_blocks ) . 'PLACEHOLDER';
				$style_blocks[ $placeholder ] = '<style>' . $matches[1] . '</style>';
				return $placeholder;
			},
			$content
		);
	}

	$allowed_html = wp_kses_allowed_html( 'post' );
	// The karaoke player reads TTML timing from paragraphs, not executable SVG.
	$allowed_html['p']['begin'] = true;
	$allowed_html['p']['end'] = true;
	// Preserve declarative forms (including shortcode output), never inline scripts.
	$form_attributes = array(
		'form' => array( 'action', 'method', 'enctype', 'accept-charset', 'autocomplete', 'name', 'target', 'novalidate' ),
		'input' => array( 'type', 'name', 'value', 'placeholder', 'required', 'disabled', 'readonly', 'checked', 'multiple', 'min', 'max', 'step', 'minlength', 'maxlength', 'pattern', 'size', 'accept', 'autocomplete' ),
		'select' => array( 'name', 'required', 'disabled', 'multiple', 'size', 'autocomplete' ),
		'option' => array( 'value', 'selected', 'disabled', 'label' ),
		'optgroup' => array( 'label', 'disabled' ),
		'textarea' => array( 'name', 'rows', 'cols', 'placeholder', 'required', 'disabled', 'readonly', 'minlength', 'maxlength', 'wrap', 'autocomplete' ),
		'label' => array( 'for' ),
		'fieldset' => array( 'disabled', 'name' ),
		'legend' => array(),
	);
	foreach ( $form_attributes as $tag => $attributes ) {
		$attributes = array_merge( $attributes, array( 'id', 'class', 'style', 'title', 'role', 'tabindex', 'data-*', 'aria-label', 'aria-labelledby', 'aria-describedby', 'aria-required', 'aria-invalid', 'aria-hidden', 'aria-live' ) );
		$allowed_html[ $tag ] = array_merge( isset( $allowed_html[ $tag ] ) ? $allowed_html[ $tag ] : array(), array_fill_keys( $attributes, true ) );
	}
	$allowed_html['iframe'] = array(
		'allow'             => true,
		'allowfullscreen'   => true,
		'class'             => true,
		'frameborder'       => true,
		'height'            => true,
		'loading'           => true,
		'referrerpolicy'    => true,
		'sandbox'           => true,
		'src'               => true,
		'style'             => true,
		'title'             => true,
		'width'             => true,
	);
	/**
	 * Filters the HTML allowed in store popup content.
	 *
	 * @param array $allowed_html Allowed HTML tags and attributes.
	 */
	$allowed_html = apply_filters( 'srmp3_store_popup_allowed_html', $allowed_html );

	$content = wp_kses( $content, $allowed_html );

	return $allow_style_tag && ! empty( $style_blocks ) ? strtr( $content, $style_blocks ) : $content;
}

function load_post_by_ajax_callback() {
	check_ajax_referer( 'sonaar_music_ajax_nonce', 'nonce' );

	$post_id_raw = isset( $_POST['id'] ) && is_string( $_POST['id'] ) ? wp_unslash( $_POST['id'] ) : '';
	$store_id = isset( $_POST['store-id'] ) && is_string( $_POST['store-id'] ) ? wp_unslash( $_POST['store-id'] ) : '';
	$post_id = filter_var( $post_id_raw, FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );

	if ( ! preg_match( '/\A[1-9][0-9]*\z/', $post_id_raw ) || false === $post_id || ! preg_match( '/\A(a|0|[1-9][0-9]*)-(0|[1-9][0-9]*)\z/', $store_id, $store_matches ) ) {
		wp_die( '', '', array( 'response' => 400 ) );
	}
	foreach ( array( $store_matches[1], $store_matches[2] ) as $index ) {
		if ( 'a' !== $index && false === filter_var( $index, FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0 ) ) ) ) {
			wp_die( '', '', array( 'response' => 400 ) );
		}
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		wp_die( '', '', array( 'response' => 404 ) );
	}

	// Anonymous visitors may load popup content only from public, published, non-protected posts.
	$post_type_object     = get_post_type_object( $post->post_type );
	$is_publicly_viewable = is_post_type_viewable( $post_type_object );
	$can_preview          = current_user_can( 'edit_post', $post_id );
	if ( ( ! $is_publicly_viewable || 'publish' !== $post->post_status || post_password_required( $post ) ) && ! $can_preview ) {
		wp_die( '', '', array( 'response' => 403 ) );
	}

	$store_id_parts = explode( '-', $store_id, 2 );
	$track_index    = $store_id_parts[0];
	$store_index    = absint( $store_id_parts[1] );
	$popup_content  = '';

	if ( 'a' === $track_index ) {
		$store_list = get_post_meta( $post_id, 'alb_store_list', true );
		if ( is_array( $store_list ) && isset( $store_list[ $store_index ]['store-content'] ) ) {
			$popup_content = $store_list[ $store_index ]['store-content'];
		}
	} else {
		$track_list  = get_post_meta( $post_id, 'alb_tracklist', true );
		$track_index = absint( $track_index );
		if ( is_array( $track_list ) && isset( $track_list[ $track_index ]['song_store_list'][ $store_index ]['store-content'] ) ) {
			$popup_content = $track_list[ $track_index ]['song_store_list'][ $store_index ]['store-content'];
		}
	}

	// Sanitize before and after shortcode expansion to protect stored and generated HTML.
	$popup_content = srmp3_sanitize_store_popup_content( $popup_content );
	$popup_content = do_shortcode( nl2br( $popup_content ) );
	$popup_content = srmp3_sanitize_store_popup_content( $popup_content, true );

	// Preserve explicit URLs, especially form actions and shortcode AJAX endpoints.
	$response = wp_json_encode( $popup_content );

	if ( false === $response ) {
		wp_die( '', '', array( 'response' => 500 ) );
	}

	echo $response; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-encoded, KSES-sanitized HTML.
	wp_die();
}


add_action('wp_ajax_load_track_note_ajax', 'load_track_note_ajax_callback');
add_action('wp_ajax_nopriv_load_track_note_ajax', 'load_track_note_ajax_callback'); 

function load_track_note_ajax_callback() {
	check_ajax_referer('sonaar_music_ajax_nonce', 'nonce');

	$post_id = absint($_POST['post-id']);
	if (!$post_id) {
		wp_send_json_error('Invalid post ID');
	}

	if (!empty($_POST['track-desc-postcontent']) && $_POST['track-desc-postcontent'] == '1') {

		$postobj = get_post($post_id);
		if (!$postobj) {
			wp_send_json_error('Post not found');
		}

		if ($postobj->post_status !== 'publish') {
			if (!is_user_logged_in() || !current_user_can('read_post', $post_id)) {
				wp_send_json_error('Permission denied');
			}
		}

		$description = wp_kses_post($postobj->post_content);

	} else {

		$tracks = get_post_meta($post_id, 'alb_tracklist', true);
		$track_position = absint($_POST['track-position']);

		if (!isset($tracks[$track_position]['track_description'])) {
			wp_send_json_error('Track not found');
		}

		$description = wp_kses_post($tracks[$track_position]['track_description']);
	}

	echo wp_json_encode(
		'<div class="srp_note_title">' .
		sanitize_text_field(stripslashes($_POST['track-title'])) .
		'</div>' .
		$description
	);

	wp_die();
}

add_action('wp_ajax_load_lyrics_ajax', 'load_lyrics_ajax_callback');
add_action('wp_ajax_nopriv_load_lyrics_ajax', 'load_lyrics_ajax_callback');
function load_lyrics_ajax_callback() {
    check_ajax_referer('sonaar_music_ajax_nonce', 'nonce'); 

    $post_id = absint($_POST['post-id']);
    $track_position = isset($_POST['track-position']) ? absint($_POST['track-position']) : null;

    $ttml_content = get_post_meta($post_id, 'sr_sonaar_tts_post_ttml', true);
    $postmeta = get_post_meta($post_id, 'alb_tracklist', true);

    if (($track_position !== null && isset($postmeta[$track_position]['track_lyrics'])) || $ttml_content) {
        $ttml_content = $ttml_content ?: $postmeta[$track_position]['track_lyrics'];

        // SSRF Vérification
        $allowed_hosts = [ parse_url(home_url(), PHP_URL_HOST) ];
        $ttml_host = parse_url($ttml_content, PHP_URL_HOST);

        if (!in_array($ttml_host, $allowed_hosts)) {
            echo wp_json_encode(array('error' => 'External URLs not allowed'));
            wp_die();
        }

        $response = wp_safe_remote_get($ttml_content, array('sslverify' => true));

        if (is_wp_error($response)) {
            echo wp_json_encode(array('error' => $response->get_error_message()));
        } else {
            $body = wp_remote_retrieve_body($response);
            echo wp_json_encode($body); 
        }

    } else {
        echo wp_json_encode(array('error' => 'The key "track_lyrics" is not set or is undefined.'));
    }

    wp_die();
}


