<?php
/**
 * Newspack Newsletter Author
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use \DrewM\MailChimp\MailChimp;

/**
 * Main Newspack Newsletters Class.
 */
final class Newspack_Newsletters {

	const NEWSPACK_NEWSLETTERS_CPT = 'newspack_nl_cpt';

	/**
	 * Supported fonts.
	 *
	 * @var array
	 */
	public static $supported_fonts = [
		'Arial, Helvetica, sans-serif',
		'Tahoma, sans-serif',
		'Trebuchet MS, sans-serif',
		'Verdana, sans-serif',
		'Georgia, serif',
		'Palatino, serif',
		'Times New Roman, serif',
		'Courier, monospace',
	];

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Newsletters
	 */
	protected static $instance = null;

	/**
	 * Instance of the service provider class.
	 *
	 * @var Newspack_Newsletters_Service_Provider
	 */
	protected static $provider = null;

	/**
	 * Main Newspack Newsletter Author Instance.
	 * Ensures only one instance of Newspack Author Instance is loaded or can be loaded.
	 *
	 * @return Newspack Author Instance - Main instance.
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
		add_action( 'init', [ __CLASS__, 'register_blocks' ] );
		add_action( 'rest_api_init', [ __CLASS__, 'rest_api_init' ] );
		add_action( 'default_title', [ __CLASS__, 'default_title' ], 10, 2 );
		add_action( 'wp_head', [ __CLASS__, 'public_newsletter_custom_style' ], 10, 2 );
		add_filter( 'display_post_states', [ __CLASS__, 'display_post_states' ], 10, 2 );
		add_action( 'pre_get_posts', [ __CLASS__, 'maybe_display_public_archive_posts' ] );
		add_action( 'template_redirect', [ __CLASS__, 'maybe_display_public_post' ] );
		add_filter( 'post_row_actions', [ __CLASS__, 'display_view_or_preview_link_in_admin' ] );
		add_filter( 'newspack_newsletters_assess_has_disabled_popups', [ __CLASS__, 'disable_campaigns_for_newsletters' ], 11 );
		add_filter( 'jetpack_relatedposts_filter_options', [ __CLASS__, 'disable_jetpack_related_posts' ] );
		add_action( 'save_post_' . self::NEWSPACK_NEWSLETTERS_CPT, [ __CLASS__, 'save' ], 10, 3 );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'branding_scripts' ] );
		add_action( 'pre_get_posts', [ __CLASS__, 'adjust_wp_query_for_public_newsletters' ] );
		self::set_service_provider( self::service_provider() );

		$needs_nag = is_admin() && ! self::is_service_provider_configured() && ! get_option( 'newspack_newsletters_activation_nag_viewed', false );
		if ( $needs_nag ) {
			add_action( 'admin_notices', [ __CLASS__, 'activation_nag' ] );
			add_action( 'admin_enqueue_scripts', [ __CLASS__, 'activation_nag_dismissal_script' ] );
			add_action( 'wp_ajax_newspack_newsletters_activation_nag_dismissal', [ __CLASS__, 'activation_nag_dismissal_ajax' ] );
		}
	}

	/**
	 * Register custom fields.
	 *
	 * @param string $service_provider Service provider slug.
	 */
	private static function set_service_provider( $service_provider ) {
		update_option( 'newspack_newsletters_service_provider', $service_provider );
		switch ( $service_provider ) {
			case 'mailchimp':
				self::$provider = Newspack_Newsletters_Mailchimp::instance();
				break;
			case 'constant_contact':
				self::$provider = Newspack_Newsletters_Constant_Contact::instance();
				break;
			case 'campaign_monitor':
				self::$provider = Newspack_Newsletters_Campaign_Monitor::instance();
				break;
		}
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'mc_campaign_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		/**
		 * This meta field is used only in the editor.
		 */
		\register_meta(
			'post',
			'newsletterData',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'type'                 => 'object',
						'context'              => [ 'edit' ],
						'additionalProperties' => true,
						'properties'           => [],
					],
				],
				'type'           => 'object',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		/**
		 * This meta field is used only in the editor.
		 */
		\register_meta(
			'post',
			'newsletterValidationErrors',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'type'    => 'array',
						'context' => [ 'edit' ],
						'items'   => [
							'type' => 'string',
						],
					],
				],
				'type'           => 'array',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'cc_campaign_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'mc_list_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'cm_list_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'cm_segment_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'cm_send_mode',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'cm_from_name',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'cm_from_email',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'cm_preview_text',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'template_id',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'integer',
				'single'         => true,
				'auth_callback'  => '__return_true',
				'default'        => -1,
			]
		);
		\register_meta(
			'post',
			'font_header',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'font_body',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'background_color',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'preview_text',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'diable_ads',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'boolean',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
		\register_meta(
			'post',
			'is_public',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_CPT,
				'show_in_rest'   => [
					'schema' => [
						'context' => [ 'edit' ],
					],
				],
				'type'           => 'boolean',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
	}

	/**
	 * Set default layout.
	 * This can be removed once WP 5.5 adoption is sufficient.
	 *
	 * @param string  $post_id Numeric ID of the campaign.
	 * @param WP_Post $post The complete post object.
	 * @param boolean $update Whether this is an existing post being updated or not.
	 */
	public static function save( $post_id, $post, $update ) {
		if ( ! $update ) {
			update_post_meta( $post_id, 'template_id', -1 );
		}
	}

	/**
	 * Register the custom post type.
	 */
	public static function register_cpt() {
		$public_slug = get_option( 'newspack_newsletters_public_posts_slug', 'newsletter' );

		// Prevent empty slug value.
		if ( empty( $public_slug ) ) {
			$public_slug = 'newsletter';
		}

		$labels = [
			'name'               => _x( 'Newsletters', 'post type general name', 'newspack-newsletters' ),
			'singular_name'      => _x( 'Newsletter', 'post type singular name', 'newspack-newsletters' ),
			'menu_name'          => _x( 'Newsletters', 'admin menu', 'newspack-newsletters' ),
			'name_admin_bar'     => _x( 'Newsletter', 'add new on admin bar', 'newspack-newsletters' ),
			'add_new'            => _x( 'Add New', 'popup', 'newspack-newsletters' ),
			'add_new_item'       => __( 'Add New Newsletter', 'newspack-newsletters' ),
			'new_item'           => __( 'New Newsletter', 'newspack-newsletters' ),
			'edit_item'          => __( 'Edit Newsletter', 'newspack-newsletters' ),
			'view_item'          => __( 'View Newsletter', 'newspack-newsletters' ),
			'all_items'          => __( 'All Newsletters', 'newspack-newsletters' ),
			'search_items'       => __( 'Search Newsletters', 'newspack-newsletters' ),
			'parent_item_colon'  => __( 'Parent Newsletters:', 'newspack-newsletters' ),
			'not_found'          => __( 'No newsletters found.', 'newspack-newsletters' ),
			'not_found_in_trash' => __( 'No newsletters found in Trash.', 'newspack-newsletters' ),
		];

		$cpt_args = [
			'has_archive'      => $public_slug,
			'labels'           => $labels,
			'public'           => true,
			'public_queryable' => true,
			'query_var'        => true,
			'rewrite'          => [ 'slug' => $public_slug ],
			'show_ui'          => true,
			'show_in_rest'     => true,
			'supports'         => [ 'author', 'editor', 'title', 'custom-fields', 'newspack_blocks', 'revisions' ],
			'taxonomies'       => [ 'category', 'post_tag' ],
			'menu_icon'        => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjI0Ij48cGF0aCB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGQ9Ik0yMS45OSA4YzAtLjcyLS4zNy0xLjM1LS45NC0xLjdMMTIgMSAyLjk1IDYuM0MyLjM4IDYuNjUgMiA3LjI4IDIgOHYxMGMwIDEuMS45IDIgMiAyaDE2YzEuMSAwIDItLjkgMi0ybC0uMDEtMTB6TTEyIDEzTDMuNzQgNy44NCAxMiAzbDguMjYgNC44NEwxMiAxM3oiIGZpbGw9IiNhMGE1YWEiLz48L3N2Zz4K',
		];
		\register_post_type( self::NEWSPACK_NEWSLETTERS_CPT, $cpt_args );
	}

	/**
	 * Register blocks server-side for front-end rendering.
	 */
	public static function register_blocks() {
		register_block_type(
			'newspack-newsletters/posts-inserter',
			[
				'render_callback' => [ __CLASS__, 'render_posts_inserter_block' ],
			]
		);
	}

	/**
	 * Server-side render callback for Posts Inserter block.
	 *
	 * @param array $attributes Block attributes.
	 * @return string HTML of block content to render.
	 */
	public static function render_posts_inserter_block( $attributes ) {
		$markup = '';

		if ( empty( $attributes['innerBlocksToInsert'] ) || ! is_array( $attributes['innerBlocksToInsert'] ) ) {
			return $markup;
		}

		foreach ( $attributes['innerBlocksToInsert'] as $inner_block ) {
			$markup .= $inner_block['innerHTML'];
		}

		return wp_kses_post( $markup );
	}

	/**
	 * Filter post states in admin posts list.
	 *
	 * @param array   $post_states An array of post display states.
	 * @param WP_Post $post        The current post object.
	 * @return array The filtered $post_states array.
	 */
	public static function display_post_states( $post_states, $post ) {
		if ( self::NEWSPACK_NEWSLETTERS_CPT !== $post->post_type ) {
			return $post_states;
		}

		$post_status = get_post_status_object( $post->post_status );
		$is_sent     = 'publish' === $post_status->name;
		$is_public   = get_post_meta( $post->ID, 'is_public', true );

		if ( $is_sent ) {
			$sent_date = get_the_time( 'U', $post );
			$time_diff = time() - $sent_date;
			$sent_date = human_time_diff( $sent_date, time() );

			// Show relative date if sent within the past 24 hours.
			if ( $time_diff < 86400 ) {
				/* translators: Relative time stamp of sent/published date */
				$post_states[ $post_status->name ] = sprintf( __( 'Sent %1$s ago', 'newspack-newsletters' ), $sent_date );
			} else {
				/* translators:  Absolute time stamp of sent/published date */
				$post_states[ $post_status->name ] = sprintf( __( 'Sent %1$s', 'newspack-newsletters' ), get_the_time( get_option( 'date_format' ), $post ) );
			}

			if ( $is_public ) {
				$post_states[ $post_status->name ] .= __( ' | Published as a post', 'newspack-newsletters' );
			}
		}

		return $post_states;
	}

	/**
	 * Allow newsletter posts to appear in archive pages, but only if set to be public.
	 *
	 * @param array $query The WP query object.
	 */
	public static function maybe_display_public_archive_posts( $query ) {
		// Only run on the main front-end query for post category and tag archives, or newsletter CPT archives.
		if (
			is_admin() ||
			! $query->is_main_query() ||
			(
				! is_category() &&
				! is_tag() &&
				! is_post_type_archive( self::NEWSPACK_NEWSLETTERS_CPT )
			)
		) {
			return;
		}

		// Allow Newsletter posts to appear in post category and tag archives.
		if ( empty( $query->get( 'post_type' ) ) ) {
			$query->set( 'post_type', [ 'post', self::NEWSPACK_NEWSLETTERS_CPT ] );
		}

		// Filter out non-public Newsletter posts.
		if ( is_post_type_archive( self::NEWSPACK_NEWSLETTERS_CPT ) || self::NEWSPACK_NEWSLETTERS_CPT === get_post_type() ) {
			$meta_query = $query->get( 'meta_query' );

			if ( empty( $meta_query ) || ! is_array( $meta_query ) ) {
				$meta_query = [];
			}

			$meta_query[] = [
				'key'          => 'is_public',
				'value'        => '1',
				'meta_compare' => '=',
			];


			$query->set( 'meta_query', $meta_query );
		}
	}

	/**
	 * Decide whether this newsletter should be publicly viewable as a post.
	 * Triggers a 404 if the current page is a single Newsletter and not marked public.
	 */
	public static function maybe_display_public_post() {
		if (
			current_user_can( 'edit_others_posts' ) ||
			! is_singular( self::NEWSPACK_NEWSLETTERS_CPT )
		) {
			return;
		}

		$is_public = get_post_meta( get_the_ID(), 'is_public', true );

		// If not marked public, make it a 404 to non-logged-in users.
		if ( empty( $is_public ) ) {
			global $wp_query;

			// Replace document title with 'Page not found'.
			add_filter(
				'wpseo_title',
				function( $title ) {
					return str_replace( get_the_title(), __( 'Page not found', 'newspack-newsletters' ), $title );
				}
			);

			status_header( 404 );
			nocache_headers();
			include get_query_template( '404' );
			die();
		}
	}

	/**
	 * Make "View" links say "Preview" if the newsletter is not marked as public.
	 *
	 * @param array $actions Array of action links to be shown in admin posts list.
	 * @return array Filtered array of action links.
	 */
	public static function display_view_or_preview_link_in_admin( $actions ) {
		if ( 'publish' !== get_post_status() || self::NEWSPACK_NEWSLETTERS_CPT !== get_post_type() ) {
			return $actions;
		}

		$is_public = get_post_meta( get_the_ID(), 'is_public', true );

		if ( empty( $is_public ) && isset( $actions['view'] ) ) {
			$actions['view'] = '<a href="' . esc_url( get_the_permalink() ) . '" rel="bookmark" aria-label="View ' . esc_attr( get_the_title() ) . '">Preview</a>';
		}

		return $actions;
	}

	/**
	 * Disable Newspack Campaigns on Newsletter posts.
	 *
	 * @param array $disabled Disabled status to filter.
	 * @return array|boolean Unfiltered disabled status, or true to disable.
	 */
	public static function disable_campaigns_for_newsletters( $disabled ) {
		if ( self::NEWSPACK_NEWSLETTERS_CPT === get_post_type() ) {
			return true;
		}

		return $disabled;
	}

	/**
	 * Disable Jetpack Related Posts on Newsletter posts.
	 *
	 * @param array $options Options array for Jetpack Related Posts.
	 * @return array Filtered options array.
	 */
	public static function disable_jetpack_related_posts( $options ) {
		if (
			self::NEWSPACK_NEWSLETTERS_CPT === get_post_type() &&
			! empty( get_option( 'newspack_newsletters_disable_related_posts' ) )
		) {
			$options['enabled'] = false;
		}

		return $options;
	}

	/**
	 * Add newspack_popups_is_sitewide_default to Popup object.
	 */
	public static function rest_api_init() {
		\register_rest_route(
			'newspack-newsletters/v1',
			'layouts',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_get_layouts' ],
				'permission_callback' => [ __CLASS__, 'api_authoring_permissions_check' ],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'settings',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_get_settings' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'settings',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_settings' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
				'args'                => [
					'mailchimp_api_key'   => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'mjml_application_id' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'mjml_api_secret'     => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'post-meta/(?P<id>[\a-z]+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_post_meta' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
				'args'                => [
					'id'    => [
						'validate_callback' => [ __CLASS__, 'validate_newsletter_id' ],
						'sanitize_callback' => 'absint',
					],
					'key'   => [
						'validate_callback' => [ __CLASS__, 'validate_newsletter_post_meta_key' ],
						'sanitize_callback' => 'sanitize_text_field',
					],
					'value' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-newsletters/v1',
			'color-palette',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_set_color_palette' ],
				'permission_callback' => [ __CLASS__, 'api_administration_permissions_check' ],
			]
		);
	}

	/**
	 * The default color palette lives in the editor frontend and is not
	 * retrievable on the backend. The workaround is to set it as an option
	 * so that it's available to the email renderer.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_color_palette( $request ) {
		update_option( 'newspack_newsletters_color_palette', $request->get_body() );
		return \rest_ensure_response( [] );
	}

	/**
	 * Set post meta.
	 * The save_post action fires before post meta is updated.
	 * This causes newsletters to be synced to the ESP before recent changes to custom fields have been recorded,
	 * which leads to incorrect rendering. This is addressed through custom endpoints to update the  fields
	 * as soon as they are changed in the editor, so that the changes are available the next time sync to ESP occurs.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_post_meta( $request ) {
		$id    = $request['id'];
		$key   = $request['key'];
		$value = $request['value'];
		update_post_meta( $id, $key, $value );
		return [];
	}

	/**
	 * Validate ID is a Newsletter post type.
	 *
	 * @param int $id Post ID.
	 */
	public static function validate_newsletter_id( $id ) {
		return self::NEWSPACK_NEWSLETTERS_CPT === get_post_type( $id );
	}

	/**
	 * Validate meta key.
	 *
	 * @param String $key Meta key.
	 */
	public static function validate_newsletter_post_meta_key( $key ) {
		return in_array(
			$key,
			[
				'font_header',
				'font_body',
				'background_color',
				'preview_text',
				'diable_ads',
				'cm_list_id',
				'cm_segment_id',
				'cm_send_mode',
				'cm_from_name',
				'cm_from_email',
				'cm_preview_text',
			]
		);
	}

	/**
	 * Retrieve Layouts.
	 */
	public static function api_get_layouts() {
		$layouts_query = new WP_Query(
			array(
				'post_type'      => Newspack_Newsletters_Layouts::NEWSPACK_NEWSLETTERS_LAYOUT_CPT,
				'posts_per_page' => -1,
			)
		);
		$user_layouts  = array_map(
			function ( $post ) {
				$post->meta = [
					'background_color' => get_post_meta( $post->ID, 'background_color', true ),
					'font_body'        => get_post_meta( $post->ID, 'font_body', true ),
					'font_header'      => get_post_meta( $post->ID, 'font_header', true ),
				];
				return $post;
			},
			$layouts_query->get_posts()
		);
		$layouts       = array_merge(
			$user_layouts,
			Newspack_Newsletters_Layouts::get_default_layouts(),
			apply_filters( 'newspack_newsletters_templates', [] )
		);

		return \rest_ensure_response( $layouts );
	}

	/**
	 * Retrieve service API settings for API endpoints.
	 */
	public static function api_get_settings() {
		return \rest_ensure_response( self::api_settings() );
	}

	/**
	 * Set API settings.
	 *
	 * @param WP_REST_Request $request API request object.
	 */
	public static function api_set_settings( $request ) {
		$service_provider = $request['service_provider'];
		$credentials      = $request['credentials'];
		$mjml_api_key     = $request['mjml_api_key'];
		$mjml_api_secret  = $request['mjml_api_secret'];
		$wp_error         = new WP_Error();

		// Service Provider slug.
		if ( empty( $service_provider ) ) {
			$wp_error->add(
				'newspack_newsletters_no_service_provider',
				__( 'Please select a newsletter service provider.', 'newspack-newsletters' )
			);
		} else {
			self::set_service_provider( $service_provider );
		}

		// Service Provider credentials.
		if ( empty( $credentials ) ) {
			$wp_error->add(
				'newspack_newsletters_invalid_keys',
				__( 'Please input credentials.', 'newspack-newsletters' )
			);
		} else {
			$status = self::$provider->set_api_credentials( $credentials );
			if ( is_wp_error( $status ) ) {
				foreach ( $status->errors as $code => $message ) {
					$wp_error->add( $code, implode( ' ', $message ) );
				}
			}
		}

		// MJML credentials.
		if ( empty( $mjml_api_key ) || empty( $mjml_api_secret ) ) {
			$wp_error->add(
				'newspack_newsletters_invalid_keys_mjml',
				__( 'Please input MJML application ID and secret key.', 'newspack-newsletters' )
			);
		} else {
			$credentials = "$mjml_api_key:$mjml_api_secret";
			$url         = 'https://api.mjml.io/v1/render';
			$mjml_test   = wp_remote_post(
				$url,
				[
					'body'    => wp_json_encode(
						[
							'mjml' => '<h1>test</h1>',
						]
					),
					'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( $credentials ),
					),
					'timeout' => 10, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
				]
			);
			if ( 200 === $mjml_test['response']['code'] ) {
				update_option( 'newspack_newsletters_mjml_api_key', $mjml_api_key );
				update_option( 'newspack_newsletters_mjml_api_secret', $mjml_api_secret );
			} else {
				$wp_error->add(
					'newspack_newsletters_invalid_keys_mjml',
					__( 'Please input valid MJML application ID and secret key.', 'newspack-newsletters' )
				);
			}
		}

		return $wp_error->has_errors() ? $wp_error : self::api_get_settings();
	}

	/**
	 * Retrieve settings.
	 */
	public static function api_settings() {
		$mjml_api_key     = get_option( 'newspack_newsletters_mjml_api_key', false );
		$mjml_api_secret  = get_option( 'newspack_newsletters_mjml_api_secret', false );
		$service_provider = self::service_provider();
		$response         = [
			'service_provider' => $service_provider ? $service_provider : '',
			'status'           => false,
			'mjml_api_key'     => $mjml_api_key ? $mjml_api_key : '',
			'mjml_api_secret'  => $mjml_api_secret ? $mjml_api_secret : '',
		];

		if ( ! self::$provider && get_option( 'newspack_newsletters_mailchimp_api_key', false ) ) {
			// Legacy – Mailchimp provider set before multi-provider handling was set up.
			self::set_service_provider( 'mailchimp' );
		}

		if ( self::$provider ) {
			$response['credentials'] = self::$provider->api_credentials();
		}

		if ( self::$provider && self::$provider->has_api_credentials() && $mjml_api_key && $mjml_api_secret ) {
			$response['status'] = true;
		}

		return $response;
	}

	/**
	 * Are all the needed API credentials available?
	 *
	 * @return bool Whether all API credentials are set.
	 */
	public static function is_service_provider_configured() {
		$settings = self::api_settings();
		return $settings['status'];
	}

	/**
	 * Check capabilities for using the API for administration tasks.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_administration_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack-newsletters' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

	/**
	 * Check capabilities for using the API for authoring tasks.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_authoring_permissions_check( $request ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack-newsletters' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

	/**
	 * Set initial title of newsletter.
	 *
	 * @param string  $post_title Post title.
	 * @param WP_Post $post Post.
	 * @return string Title.
	 */
	public static function default_title( $post_title, $post ) {
		if ( self::NEWSPACK_NEWSLETTERS_CPT === get_post_type( $post ) ) {
			$post_title = gmdate( get_option( 'date_format' ) );
		}
		return $post_title;
	}

	/**
	 * Handle custom Newsletter styling when viewing the newsletter as a public post.
	 */
	public static function public_newsletter_custom_style() {
		if ( ! is_single() ) {
			return;
		}
		$post = get_post();
		if ( $post && self::NEWSPACK_NEWSLETTERS_CPT === $post->post_type ) {
			$font_header      = get_post_meta( $post->ID, 'font_header', true );
			$font_body        = get_post_meta( $post->ID, 'font_body', true );
			$background_color = get_post_meta( $post->ID, 'background_color', true );
			?>
				<style>
					.main-content {
						background-color: <?php echo esc_attr( $background_color ); ?>;
						font-family: <?php echo esc_attr( $font_body ); ?>;
					}
					.main-content h1,
					.main-content h2,
					.main-content h3,
					.main-content h4,
					.main-content h5,
					.main-content h6 {
						font-family: <?php echo esc_attr( $font_header ); ?>;
					}
					<?php if ( $background_color ) : ?>
						.entry-content {
							padding: 0 32px;;
						}
					<?php endif; ?>
				</style>
			<?php
		}
	}

	/**
	 * Activation Nag
	 */

	/**
	 * Add admin notice if API credentials are unset.
	 */
	public static function activation_nag() {
		$screen = get_current_screen();
		if ( 'settings_page_newspack-newsletters-settings-admin' === $screen->base || self::NEWSPACK_NEWSLETTERS_CPT === $screen->post_type ) {
			return;
		}
		$url = admin_url( 'edit.php?post_type=' . self::NEWSPACK_NEWSLETTERS_CPT . '&page=newspack-newsletters-settings-admin' );
		?>
		<div class="notice notice-info is-dismissible newspack-newsletters-notification-nag">
			<p>
				<?php
					echo wp_kses_post(
						sprintf(
							// translators: urge users to input their API credentials on settings page.
							__( 'Thank you for activating Newspack Newsletters. Please <a href="%s">head to settings</a> to set up your API credentials.', 'newspack-newsletters' ),
							$url
						)
					);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Enqueue style to handle Newspack branding.
	 */
	public static function branding_scripts() {
		$screen = get_current_screen();
		if (
			self::NEWSPACK_NEWSLETTERS_CPT !== $screen->post_type &&
			Newspack_Newsletters_Ads::NEWSPACK_NEWSLETTERS_ADS_CPT !== $screen->post_type
		) {
			return;
		}

		$script = 'newspack-newsletters-branding_scripts';
		wp_enqueue_script(
			$script,
			plugins_url( '../dist/branding.js', __FILE__ ),
			[ 'jquery' ],
			'1.0',
			false
		);
		wp_enqueue_style(
			$script,
			plugins_url( '../dist/branding.css', __FILE__ ),
			[],
			'1.0',
			'screen'
		);
	}

	/**
	 * Enqueue script to handle activation nag dismissal.
	 */
	public static function activation_nag_dismissal_script() {
		$script = 'newspack-newsletters-activation_nag_dismissal';
		wp_register_script(
			$script,
			plugins_url( '../dist/admin.js', __FILE__ ),
			[ 'jquery' ],
			'1.0',
			false
		);
		wp_localize_script(
			$script,
			'newspack_newsletters_activation_nag_dismissal_params',
			[
				'ajaxurl' => get_admin_url() . 'admin-ajax.php',
			]
		);
		wp_enqueue_script( $script );
	}

	/**
	 * AJAX callback after nag has been dismissed.
	 */
	public static function activation_nag_dismissal_ajax() {
		update_option( 'newspack_newsletters_activation_nag_viewed', true );
	}

	/**
	 * Is wp-config debug flag set.
	 *
	 * @return boolean Is debug mode on?
	 */
	public static function debug_mode() {
		return defined( 'NEWSPACK_NEWSLETTERS_DEBUG_MODE' ) ? NEWSPACK_NEWSLETTERS_DEBUG_MODE : false;
	}

	/**
	 * Which Email Service Provider should be used.
	 *
	 * @return string Name of the Email Service Provider.
	 */
	public static function service_provider() {
		return get_option( 'newspack_newsletters_service_provider', false );
	}

	/**
	 * Add meta query elements to check if Newsletter is public.
	 *
	 * @param object $query The query.
	 * @return object $query The query.
	 */
	public static function adjust_wp_query_for_public_newsletters( $query ) {
		if ( is_admin() ) {
			return;
		}
		$post_type = $query->get( 'post_type', '' );
		if ( self::NEWSPACK_NEWSLETTERS_CPT === $post_type || ( is_array( $post_type ) && in_array( self::NEWSPACK_NEWSLETTERS_CPT, $post_type ) ) ) {
			$meta_query   = $query->get( 'meta_query', [] ); // phpcs:ignore WordPressVIPMinimum.Hooks.PreGetPosts.PreGetPosts
			$meta_query[] = [
				'relation' => 'OR',
				[
					'key'     => 'is_public',
					'value'   => true,
					'compare' => '=',
				],
				[
					'key'     => 'is_public',
					'compare' => 'NOT EXISTS',
				],
			];
			$query->set( 'meta_query', $meta_query ); // phpcs:ignore WordPressVIPMinimum.Hooks.PreGetPosts.PreGetPosts
		}
	}
}
Newspack_Newsletters::instance();
