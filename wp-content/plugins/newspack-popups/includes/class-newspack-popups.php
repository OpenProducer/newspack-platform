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

	const NEWSPACK_POPUPS_CPT                   = 'newspack_popups_cpt';
	const NEWSPACK_POPUPS_SITEWIDE_DEFAULT      = 'newspack_popups_sitewide_default';
	const NEWSPACK_POPUPS_TAXONOMY              = 'newspack_popups_taxonomy';
	const NEWSPACK_POPUPS_ACTIVE_CAMPAIGN_GROUP = 'newspack_popups_active_campaign_group';
	const NEWSPACK_POPUP_PREVIEW_QUERY_PARAM    = 'newspack_popups_preview_id';

	const LIGHTWEIGHT_API_CONFIG_FILE_PATH_LEGACY = WP_CONTENT_DIR . '/../newspack-popups-config.php';
	const LIGHTWEIGHT_API_CONFIG_FILE_PATH        = WP_CONTENT_DIR . '/newspack-popups-config.php';

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
		add_action( 'admin_init', [ __CLASS__, 'create_lightweight_api_config' ] );
		add_action( 'admin_notices', [ __CLASS__, 'api_config_missing_notice' ] );
		add_action( 'init', [ __CLASS__, 'register_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'init', [ __CLASS__, 'register_taxonomy' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_filter( 'display_post_states', [ __CLASS__, 'display_post_states' ], 10, 2 );
		add_action( 'rest_api_init', [ __CLASS__, 'rest_api_init' ] );
		add_action( 'save_post_' . self::NEWSPACK_POPUPS_CPT, [ __CLASS__, 'popup_default_fields' ], 10, 3 );

		add_filter( 'show_admin_bar', [ __CLASS__, 'show_admin_bar' ], 10, 2 ); // phpcs:ignore WordPressVIPMinimum.UserExperience.AdminBarRemoval.RemovalDetected

		include_once dirname( __FILE__ ) . '/class-newspack-popups-model.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-inserter.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-api.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-settings.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-segmentation.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-parse-logs.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-donations.php';
		include_once dirname( __FILE__ ) . '/class-newspack-popups-view-as.php';
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
			'taxonomies'   => [ 'category', 'post_tag' ],
			'menu_icon'    => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjYTBhNWFhIiBkPSJNMTEuOTkgMTguNTRsLTcuMzctNS43M0wzIDE0LjA3bDkgNyA5LTctMS42My0xLjI3LTcuMzggNS43NHpNMTIgMTZsNy4zNi01LjczTDIxIDlsLTktNy05IDcgMS42MyAxLjI3TDEyIDE2eiIvPjwvc3ZnPgo=',
		];
		\register_post_type( self::NEWSPACK_POPUPS_CPT, $cpt_args );
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'trigger_type',
			[
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'dismiss_text_alignment',
			[
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
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
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
				'show_in_rest'   => true,
				'type'           => 'boolean',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);

		\register_meta(
			'post',
			'selected_segment_id',
			[
				'object_subtype' => self::NEWSPACK_POPUPS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
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
	 * Register Campaigns taxonomy.
	 */
	public static function register_taxonomy() {
		$taxonomy_args = [
			'labels'        => [
				'name'          => __( 'Campaign Groups', 'newspack-popups' ),
				'singular_name' => __( 'Campaign Group', 'newspack-popups' ),
				'add_new_item'  => __( 'Add Campaign Group', 'newspack-popups' ),
			],
			'hierarchical'  => true,
			'public'        => false,
			'rewrite'       => false, // phpcs:ignore Squiz.PHP.CommentedOutCode.Found [ 'hierarchical' => true, 'slug' => $prefix . '/category' ]
			'show_in_menu'  => false,
			'show_in_rest'  => true,
			'show_tagcloud' => false,
			'show_ui'       => true,
		];

		register_taxonomy( self::NEWSPACK_POPUPS_TAXONOMY, [ self::NEWSPACK_POPUPS_CPT ], $taxonomy_args );

		// Better safe than sorry: https://developer.wordpress.org/reference/functions/register_taxonomy/#more-information.
		register_taxonomy_for_object_type( self::NEWSPACK_POPUPS_TAXONOMY, [ self::NEWSPACK_POPUPS_CPT ] );
	}

	/**
	 * Get preview post permalink.
	 */
	public static function preview_post_permalink() {
		$query        = new WP_Query(
			[
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'has_password'   => false,
				'orderby'        => 'post_date',
				'order'          => 'DESC',
				'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'OR',
					[
						'key'     => 'newspack_popups_has_disabled_popups',
						'compare' => 'NOT EXISTS',
					],
					[
						'key'   => 'newspack_popups_has_disabled_popups',
						'value' => '',
					],
				],
			]
		);
		$recent_posts = $query->get_posts();
		return $recent_posts && count( $recent_posts ) > 0 ? get_the_permalink( $recent_posts[0] ) : '';
	}

	/**
	 * Load up common JS/CSS for wizards.
	 */
	public static function enqueue_block_editor_assets() {
		$screen = get_current_screen();

		if ( self::NEWSPACK_POPUPS_CPT !== $screen->post_type ) {
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

		\wp_localize_script(
			'newspack-popups',
			'newspack_popups_data',
			[
				'preview_post' => self::preview_post_permalink(),
				'segments'     => Newspack_Popups_Segmentation::get_segments(),
				'taxonomy'     => self::NEWSPACK_POPUPS_TAXONOMY,
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
		if ( self::NEWSPACK_POPUPS_CPT !== $post->post_type ) {
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
	 * Should admin bar be shown.
	 *
	 * @return boolean Whether admin bar should be shown.
	 */
	public static function show_admin_bar() {
		return ! self::is_preview_request();
	}

	/**
	 * Is it a preview request – a single popup preview or using "view as" feature.
	 *
	 * @return boolean Whether it's a preview request.
	 */
	public static function is_preview_request() {
		$view_as_spec = Newspack_Popups_View_As::viewing_as_spec();
		return self::previewed_popup_id() || false !== $view_as_spec;
	}

	/**
	 * Get previewed popup id from the URL.
	 *
	 * @return number|null Popup id, if found in the URL
	 */
	public static function previewed_popup_id() {
		return filter_input( INPUT_GET, self::NEWSPACK_POPUP_PREVIEW_QUERY_PARAM, FILTER_SANITIZE_STRING );
	}

	/**
	 * Add newspack_popups_is_sitewide_default to Popup object.
	 */
	public static function rest_api_init() {
		register_rest_field(
			[ self::NEWSPACK_POPUPS_CPT ],
			'newspack_popups_is_sitewide_default',
			[
				'get_callback'    => function( $post ) {
					return absint( $post['id'] ) === absint( get_option( self::NEWSPACK_POPUPS_SITEWIDE_DEFAULT, null ) );
				},
				'update_callback' => function ( $value, $post ) {
					if ( $value ) {
						return Newspack_Popups_Model::set_sitewide_popup( $post->ID );
					} else {
						return Newspack_Popups_Model::unset_sitewide_popup( $post->ID );
					}
				},
			]
		);
	}

	/**
	 * Get the default dismiss text.
	 */
	public static function get_default_dismiss_text() {
		return __( "I'm not interested", 'newspack' );
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
		$type      = isset( $_GET['placement'] ) ? sanitize_text_field( $_GET['placement'] ) : null; //phpcs:ignore
		$frequency = 'daily';

		switch ( $type ) {
			case 'overlay-center':
				$placement = 'center';
				break;
			case 'overlay-top':
				$placement = 'top';
				break;
			case 'overlay-bottom':
				$placement = 'bottom';
				break;
			case 'above-header':
				$placement = 'above_header';
				$frequency = 'always';
				break;
			case 'manual':
				$placement = 'inline';
				$frequency = 'manual';
				break;
			default:
				$placement = 'inline';
				$frequency = 'always';
				break;
		}

		switch ( $type ) {
			case 'overlay-center':
			case 'overlay-top':
			case 'overlay-bottom':
				$dismiss_text = self::get_default_dismiss_text();
				$trigger_type = 'time';
				break;
			case 'above-header':
			case 'manual':
			default:
				$dismiss_text = null;
				$trigger_type = 'scroll';
				break;
		}

		update_post_meta( $post_id, 'background_color', '#FFFFFF' );
		update_post_meta( $post_id, 'display_title', false );
		update_post_meta( $post_id, 'dismiss_text', $dismiss_text );
		update_post_meta( $post_id, 'frequency', $frequency );
		update_post_meta( $post_id, 'overlay_color', '#000000' );
		update_post_meta( $post_id, 'overlay_opacity', 30 );
		update_post_meta( $post_id, 'placement', $placement );
		update_post_meta( $post_id, 'trigger_type', $trigger_type );
		update_post_meta( $post_id, 'trigger_delay', 3 );
		update_post_meta( $post_id, 'trigger_scroll_progress', 30 );
		update_post_meta( $post_id, 'utm_suppression', '' );
		update_post_meta( $post_id, 'selected_segment_id', '' );
	}

	/**
	 * Create the config file for the API, unless it exists.
	 */
	public static function create_lightweight_api_config() {
		// Don't create a config file if not on Newspack's Atomic platform, or if there is a file already.
		if (
			! ( defined( 'ATOMIC_SITE_ID' ) && ATOMIC_SITE_ID ) ||
			( file_exists( self::LIGHTWEIGHT_API_CONFIG_FILE_PATH_LEGACY ) || file_exists( self::LIGHTWEIGHT_API_CONFIG_FILE_PATH ) )
		) {
			return;
		}
		global $wpdb;
		$new_config_file = file_put_contents( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents -- VIP will have to create a config manually
			self::LIGHTWEIGHT_API_CONFIG_FILE_PATH,
			'<?php' .
			// Insert these only if they are defined, but not in the as environment variables.
			// This way only variables which are already declared in wp-config.php should be inserted in this config file.
			( ! getenv( 'DB_CHARSET' ) && defined( 'DB_CHARSET' ) ? "\ndefine( 'DB_CHARSET', '" . DB_CHARSET . "' );" : '' ) .
			( ! getenv( 'WP_CACHE_KEY_SALT' ) && defined( 'WP_CACHE_KEY_SALT' ) ? "\ndefine( 'WP_CACHE_KEY_SALT', '" . WP_CACHE_KEY_SALT . "' );" : '' ) .
			"\ndefine( 'DB_PREFIX', '" . $wpdb->prefix . "' );" .
			"\n"
		);
		if ( $new_config_file ) {
			error_log( 'Created the config file: ' . self::LIGHTWEIGHT_API_CONFIG_FILE_PATH ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}

	/**
	 * Add an admin notice if config is missing.
	 */
	public static function api_config_missing_notice() {
		if (
			file_exists( self::LIGHTWEIGHT_API_CONFIG_FILE_PATH_LEGACY ) ||
			file_exists( self::LIGHTWEIGHT_API_CONFIG_FILE_PATH )
		) {
			return;
		}
		?>
			<div class="notice notice-error">
				<p>
					<?php _e( 'Newspack Campaigns requires a custom configuration file, which is missing. Please create this file following instructions found ', 'newspack-popups' ); ?>
					<a href="https://github.com/Automattic/newspack-popups/blob/master/api/README.md">
						<?php _e( 'here.', 'newspack-popups' ); ?>
					</a>
				</p>
			</div>
		<?php
	}
}
Newspack_Popups::instance();
