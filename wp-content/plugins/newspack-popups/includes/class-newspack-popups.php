<?php
/**
 * Newspack Popups set up
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Popups Class.
 */
final class Newspack_Popups {

	const NEWSPACK_PLUGINS_CPT             = 'newspack_popups_cpt';
	const NEWSPACK_POPUPS_SITEWIDE_DEFAULT = 'newspack_popups_sitewide_default';

	const NEWSPACK_POPUP_PREVIEW_QUERY_PARAM = 'newspack_popups_preview_id';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Popups
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Ads Instance.
	 * Ensures only one instance of Newspack Ads is loaded or can be loaded.
	 *
	 * @return Newspack Ads - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_filter( 'display_post_states', [ __CLASS__, 'display_post_states' ], 10, 2 );
		add_action( 'rest_api_init', [ __CLASS__, 'rest_api_init' ] );
		add_action( 'save_post_newspack_popups_cpt', [ __CLASS__, 'popup_default_fields' ], 10, 3 );

		if ( filter_input( INPUT_GET, 'newspack_popups_preview_id', FILTER_SANITIZE_STRING ) ) {
			add_filter( 'show_admin_bar', [ __CLASS__, 'hide_admin_bar_for_preview' ], 10, 2 );
		}

		include_once dirname( __FILE__ ) . '/class-newspack-popups-model.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-inserter.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-api.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-settings.php';
	}

	/**
	 * Register the custom post type.
	 */
	public static function register_cpt() {
		$labels = [
			'name'               => _x( 'Campaigns', 'post type general name', 'newspack-popups' ),
			'singular_name'      => _x( 'Campaign', 'post type singular name', 'newspack-popups' ),
			'menu_name'          => _x( 'Campaigns', 'admin menu', 'newspack-popups' ),
			'name_admin_bar'     => _x( 'Campaign', 'add new on admin bar', 'newspack-popups' ),
			'add_new'            => _x( 'Add New', 'popup', 'newspack-popups' ),
			'add_new_item'       => __( 'Add New Campaign', 'newspack-popups' ),
			'new_item'           => __( 'New Campaign', 'newspack-popups' ),
			'edit_item'          => __( 'Edit Campaign', 'newspack-popups' ),
			'view_item'          => __( 'View Campaign', 'newspack-popups' ),
			'all_items'          => __( 'All Campaigns', 'newspack-popups' ),
			'search_items'       => __( 'Search Campaigns', 'newspack-popups' ),
			'parent_item_colon'  => __( 'Parent Campaigns:', 'newspack-popups' ),
			'not_found'          => __( 'No Campaigns found.', 'newspack-popups' ),
			'not_found_in_trash' => __( 'No Campaigns found in Trash.', 'newspack-popups' ),
		];

		$cpt_args = [
			'labels'       => $labels,
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'supports'     => [ 'editor', 'title', 'custom-fields' ],
			'taxonomies'   => [ 'category' ],
			'menu_icon'    => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjYTBhNWFhIiBkPSJNMTEuOTkgMTguNTRsLTcuMzctNS43M0wzIDE0LjA3bDkgNyA5LTctMS42My0xLjI3LTcuMzggNS43NHpNMTIgMTZsNy4zNi01LjczTDIxIDlsLTktNy05IDcgMS42MyAxLjI3TDEyIDE2eiIvPjwvc3ZnPgo=',
		];
		\register_post_type( self::NEWSPACK_PLUGINS_CPT, $cpt_args );
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'trigger_type',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'trigger_scroll_progress',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'trigger_delay',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'frequency',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'placement',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'utm_suppression',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'background_color',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'overlay_color',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'overlay_opacity',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'dismiss_text',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'display_title',
			[
				'object_subtype' => self::NEWSPACK_PLUGINS_CPT,
				'show_in_rest'   => true,
				'type'           => 'boolean',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		// Meta field for all post types.
		\register_meta(
			'post',
			'newspack_popups_has_disabled_popups',
			[
				'show_in_rest'  => true,
				'type'          => 'boolean',
				'single'        => true,
				'auth_callback' => '__return_true',
			]
		);
	}

	/**
	 * Load up common JS/CSS for wizards.
	 */
	public static function enqueue_block_editor_assets() {
		$screen = get_current_screen();

		if ( self::NEWSPACK_PLUGINS_CPT !== $screen->post_type ) {
			if ( 'page' !== $screen->post_type || 'post' !== $screen->post_type ) {
				// Script for global settings.
				\wp_enqueue_script(
					'newspack-popups',
					plugins_url( '../dist/documentSettings.js', __FILE__ ),
					[],
					filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/documentSettings.js' ),
					true
				);
			}

			return;
		}

		\wp_enqueue_script(
			'newspack-popups',
			plugins_url( '../dist/editor.js', __FILE__ ),
			[ 'wp-components' ],
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/editor.js' ),
			true
		);
		$recent_posts = wp_get_recent_posts(
			[
				'numberposts' => 1,
				'post_status' => 'publish',
			],
			OBJECT
		);
		$preview_post = count( $recent_posts ) > 0 ? get_the_permalink( $recent_posts[0] ) : '';

		\wp_localize_script(
			'newspack-popups',
			'newspack_popups_data',
			[
				'preview_post' => $preview_post,
			]
		);
		\wp_enqueue_style(
			'newspack-popups-editor',
			plugins_url( '../dist/editor.css', __FILE__ ),
			null,
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/editor.css' )
		);
	}

	/**
	 * Display popup states by the pop-ups.
	 *
	 * @param array   $post_states An array of post display states.
	 * @param WP_Post $post        The current post object.
	 */
	public static function display_post_states( $post_states, $post ) {
		if ( self::NEWSPACK_PLUGINS_CPT !== $post->post_type ) {
			return $post_states;
		}
		$post_status_object = get_post_status_object( $post->post_status );
		$is_inline          = get_post_meta( $post->ID, 'placement', true ) == 'inline';
		if ( $is_inline ) {
			$post_states[ $post_status_object->name ] = __( 'Inline', 'newspack-popups' );
		} elseif ( absint( get_option( self::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null ) ) === absint( $post->ID ) ) {
			$post_states[ $post_status_object->name ] = __( 'Sitewide Default', 'newspack-popups' );
		}
		return $post_states;
	}

	/**
	 * Hide admin bar if previewing the popup.
	 *
	 * @return boolean Whether admin bar should be hidden
	 */
	public static function hide_admin_bar_for_preview() {
		return ! self::previewed_popup_id();
	}

	/**
	 * Get previewed popup id from the URL.
	 *
	 * @param string $url URL, if available.
	 * @return number|null Popup id, if found in the URL
	 */
	public static function previewed_popup_id( $url = null ) {
		if ( $url ) {
			$query_params = [];
			$parsed_url   = wp_parse_url( $url );
			parse_str(
				isset( $parsed_url['query'] ) ? $parsed_url['query'] : '',
				$query_params
			);
			$param = self::NEWSPACK_POPUP_PREVIEW_QUERY_PARAM;
			return isset( $query_params[ $param ] ) ? $query_params[ $param ] : false;
		} else {
			return filter_input( INPUT_GET, self::NEWSPACK_POPUP_PREVIEW_QUERY_PARAM, FILTER_SANITIZE_STRING );
		}
	}

	/**
	 * Add newspack_popups_is_sitewide_default to Popup object.
	 */
	public static function rest_api_init() {
		register_rest_field(
			[ self::NEWSPACK_PLUGINS_CPT ],
			'newspack_popups_is_sitewide_default',
			[
				'get_callback' => function( $post ) {
					return absint( $post['id'] ) === absint( get_option( self::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null ) );
				},
				'schema'       => [
					'context' => [
						'edit',
					],
					'type'    => 'array',
				],
			]
		);
	}


	/**
	 * Set default fields when Pop-up is created.
	 *
	 * @param int     $post_id ID of post being saved.
	 * @param WP_POST $post The post being saved.
	 * @param bool    $update True if this is an update, false if a newly created post.
	 */
	public static function popup_default_fields( $post_id, $post, $update ) {
		// Set meta only if this is a newly created post.
		if ( $update ) {
			return;
		}
		$placement = isset( $_GET['placement'] ) && 'inline' === sanitize_text_field( $_GET['placement'] ) ? 'inline' : 'center'; //phpcs:ignore

		update_post_meta( $post_id, 'background_color', '#FFFFFF' );
		update_post_meta( $post_id, 'display_title', false );
		update_post_meta( $post_id, 'dismiss_text', __( "I'm not interested", 'newspack' ) );
		update_post_meta( $post_id, 'frequency', 'test' );
		update_post_meta( $post_id, 'overlay_color', '#000000' );
		update_post_meta( $post_id, 'overlay_opacity', 30 );
		update_post_meta( $post_id, 'placement', $placement );
		update_post_meta( $post_id, 'trigger_type', 'time' );
		update_post_meta( $post_id, 'trigger_delay', 3 );
		update_post_meta( $post_id, 'trigger_scroll_progress', 30 );
		update_post_meta( $post_id, 'utm_suppression', '' );
	}
}
Newspack_Popups::instance();
