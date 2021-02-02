<?php
/**
 * Entry point for the admin side of the WP Plugin.
 *
 * @author  Federico Ramirez
 * @since   0.0.0
 * @package Apple_News
 */

global $post;
// Include dependencies.
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-post-sync.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-index-page.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-bulk-export-page.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-notice.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-meta-boxes.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-async.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-sections.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-themes.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-preview.php';
require_once plugin_dir_path( __FILE__ ) . 'class-admin-apple-json.php';
// REST Includes.
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-delete.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-get-published-state.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-get-settings.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-modify-post.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-publish.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-sections.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-update.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/REST/apple-news-user-can-publish.php';

/**
 * Entry-point class for the plugin.
 */
class Admin_Apple_News extends Apple_News {

	/**
	 * Current settings.
	 *
	 * @var Settings
	 */
	public static $settings;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register hooks.
		add_action( 'admin_print_styles-toplevel_page_apple_news_index', array( $this, 'plugin_styles' ) );

		/**
		 * Admin_Settings builds the settings page for the plugin. Besides setting
		 * it up, let's get the settings getter and setter object and save it into
		 * $settings.
		 */
		$admin_settings = new Admin_Apple_Settings();
		self::$settings = $admin_settings->fetch_settings();

		// Initialize notice messaging utility.
		add_action( 'admin_enqueue_scripts', 'Admin_Apple_Notice::register_assets' );
		add_action( 'admin_notices', 'Admin_Apple_Notice::show' );
		add_action( 'wp_ajax_apple_news_dismiss_notice', 'Admin_Apple_Notice::wp_ajax_dismiss_notice' );

		// Set up main page.
		new Admin_Apple_Index_Page( self::$settings );

		// Set up all sub pages.
		new Admin_Apple_Bulk_Export_Page( self::$settings );

		// Set up posts syncing if enabled in the settings.
		new Admin_Apple_Post_Sync( self::$settings );

		// Set up the publish meta box if enabled in the settings.
		new Admin_Apple_Meta_Boxes( self::$settings );

		// Set up asynchronous publishing features.
		new Admin_Apple_Async( self::$settings );

		// Add section support.
		new Admin_Apple_Sections( self::$settings );

		// Add theme support.
		new Admin_Apple_Themes();

		// Add preview support.
		new Admin_Apple_Preview();

		// Add JSON customization support.
		new Admin_Apple_JSON();

		// Enhancements if the block editor is available.
		if ( apple_news_block_editor_is_active() ) {
			$post_types = self::$settings->post_types;

			// Define custom postmeta fields to register.
			$postmeta = [
				'apple_news_api_created_at'     => [],
				'apple_news_api_id'             => [],
				'apple_news_api_modified_at'    => [],
				'apple_news_api_revision'       => [],
				'apple_news_api_share_url'      => [],
				'apple_news_coverimage'         => [
					'type' => 'integer',
				],
				'apple_news_coverimage_caption' => [],
				'apple_news_is_hidden'          => [
					'type' => 'boolean',
				],
				'apple_news_is_paid'            => [
					'type' => 'boolean',
				],
				'apple_news_is_preview'         => [
					'type' => 'boolean',
				],
				'apple_news_is_sponsored'       => [
					'type' => 'boolean',
				],
				'apple_news_maturity_rating'    => [],
				'apple_news_pullquote'          => [],
				'apple_news_pullquote_position' => [],
				'apple_news_sections'           => [
					'sanitize_callback' => 'apple_news_sanitize_selected_sections',
					'show_in_rest'      => [
						'prepare_callback' => 'apple_news_json_encode',
					],
				],
			];

			// Loop over postmeta fields and register each.
			foreach ( $postmeta as $meta_key => $options ) {
				apple_news_register_meta_helper( 'post', $post_types, $meta_key, $options );
			}

			add_action(
				'rest_api_init',
				function() {
					register_rest_field(
						'post',
						'apple_news_notices',
						[
							'get_callback' => [ 'Admin_Apple_Notice', 'get_if_allowed' ],
						]
					);
				}
			);
		}
	}

	/**
	 * A function to display an error message.
	 *
	 * @param string $message The message to display.
	 *
	 * @since 1.2.5
	 * @access public
	 */
	public static function show_error( $message ) {
		if ( apple_news_block_editor_is_active_for_post() ) {
			Admin_Apple_Notice::error( $message );
		} else {
			echo '<div class="apple-news-notice apple-news-notice-error" role="alert"><p>'
				. esc_html( $message )
				. '</p></div>';
		}
	}

	/**
	 * Implements certain plugin styles inline.
	 *
	 * @access public
	 */
	public function plugin_styles() {
		// Styles are tiny, for now just embed them.
		echo '<style type="text/css">';
		echo '.wp-list-table .column-sync { width: 15%; }';
		echo '.wp-list-table .column-updated_at { width: 15%; }';
		// Clipboard fix.
		echo '.row-actions.is-active { visibility: visible }';
		echo '</style>';
	}

	/**
	 * Get post status.
	 *
	 * @param int $post_id The ID of the post to look up.
	 * @return string
	 */
	public static function get_post_status( $post_id ) {
		$key   = 'apple_news_post_state_' . $post_id;
		$state = get_transient( $key );
		if ( false === $state ) {
			// Get the state from the API.
			// If this causes an error, display that message instead of the state.
			try {
				$action = new Apple_Actions\Index\Get( self::$settings, $post_id );
				$state  = $action->get_data( 'state', __( 'N/A', 'apple-news' ) );
			} catch ( \Apple_Push_API\Request\Request_Exception $e ) {
				$state = $e->getMessage();
			}

			$cache_expiration = ( 'LIVE' === $state || 'TAKEN_DOWN' === $state ) ? 3600 : 60;
			set_transient( $key, $state, apply_filters( 'apple_news_post_status_cache_expiration', $cache_expiration, $state ) );
		}

		return $state;
	}
}
