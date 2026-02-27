<?php
/**
 * Newspack Content Gate.
 *
 * @package Newspack
 */

namespace Newspack;

use Newspack\Metering;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Content_Gate {

	use Content_Gate_Layout;

	const GATE_CPT = 'np_content_gate';

	const GATE_LAYOUT_CPT = 'np_gate_layout';

	/**
	 * Whether the gate has been rendered in this execution.
	 *
	 * @var boolean
	 */
	private static $gate_rendered = false;

	/**
	 * Whether the gate is being rendered.
	 *
	 * @var boolean
	 */
	private static $is_gated = false;

	/**
	 * Valid gate post statuses.
	 *
	 * @var array
	 */
	public static $valid_gate_post_statuses = [ 'publish', 'draft', 'pending', 'future', 'private', 'trash' ];

	/**
	 * Restricted content per post ID.
	 *
	 * @var string[]
	 */
	private static $restricted_content = [];

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'admin_init', [ __CLASS__, 'redirect_cpt' ] );
		add_action( 'admin_init', [ __CLASS__, 'handle_edit_gate_layout' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ __CLASS__, 'render_overlay_gate' ], 1 );
		add_action( 'before_delete_post', [ __CLASS__, 'delete_gate_layouts' ], 10, 2 );
		add_filter( 'newspack_popups_assess_has_disabled_popups', [ __CLASS__, 'disable_popups' ] );
		add_filter( 'newspack_reader_activity_article_view', [ __CLASS__, 'suppress_article_view_activity' ], 100 );

		add_action( 'the_post', [ __CLASS__, 'restrict_post' ], 10, 2 );
		add_filter( 'the_content', [ __CLASS__, 'handle_restricted_content' ], PHP_INT_MAX );

		/** Add gate content filters to mimic 'the_content'. See 'wp-includes/default-filters.php' for reference. */
		add_filter( 'newspack_gate_content', 'capital_P_dangit', 11 );
		add_filter( 'newspack_gate_content', [ __CLASS__, 'do_blocks' ], 9 ); // Custom implementation of do_blocks().
		add_filter( 'newspack_gate_content', 'wptexturize' );
		add_filter( 'newspack_gate_content', 'convert_smilies', 20 );
		add_filter( 'newspack_gate_content', 'wpautop' );
		add_filter( 'newspack_gate_content', 'shortcode_unautop' );
		add_filter( 'newspack_gate_content', 'prepend_attachment' );
		add_filter( 'newspack_gate_content', 'wp_filter_content_tags' );
		add_filter( 'newspack_gate_content', 'wp_replace_insecure_home_url' );
		add_filter( 'newspack_gate_content', 'do_shortcode', 11 ); // AFTER wpautop().

		include __DIR__ . '/class-access-rules.php';
		include __DIR__ . '/class-content-restriction-control.php';
		include __DIR__ . '/class-block-patterns.php';
		include __DIR__ . '/class-metering.php';
		include __DIR__ . '/class-metering-countdown.php';
		include __DIR__ . '/content-gifting/class-content-gifting.php';
		include __DIR__ . '/class-ip-access-rule.php';
	}

	/**
	 * Whether the first-party Newspack feature is enabled.
	 *
	 * @return bool
	 */
	public static function is_newspack_feature_enabled() {
		return defined( 'NEWSPACK_CONTENT_GATES' ) && NEWSPACK_CONTENT_GATES;
	}

	/**
	 * Restrict the post.
	 *
	 * @param \WP_Post  $post Post object.
	 * @param \WP_Query $query Query object.
	 */
	public static function restrict_post( $post, $query ) {
		if ( self::has_rendered() ) {
			return;
		}
		if ( ! $query->is_main_query() ) {
			return;
		}
		if ( ! is_singular() ) {
			return;
		}
		if ( get_queried_object_id() !== $post->ID ) {
			return;
		}
		// Don't apply our restriction strategy if Woo Memberships is active.
		if ( Memberships::is_active() ) {
			return;
		}
		// Never restrict posts in the admin.
		if ( is_admin() ) {
			return;
		}
		// Never in Privacy Policy page.
		if ( is_privacy_policy() ) {
			return;
		}
		// Never in My Account pages.
		if ( function_exists( 'is_account_page' ) && is_account_page() ) {
			return;
		}
		// Never in Terms and Conditions page.
		if ( function_exists( 'wc_terms_and_conditions_page_id' ) && $post->ID === wc_terms_and_conditions_page_id() ) {
			return;
		}
		// Never in WooCommerce cart page.
		if ( function_exists( 'is_cart' ) && is_cart() ) {
			return;
		}
		// Never in WooCommerce checkout page.
		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			return;
		}
		// Never on Accessibility Statement page.
		if ( $post->ID === get_theme_mod( 'accessibility_statement_page_id' ) ) {
			return;
		}
		// If no other restrictions apply.
		if ( ! self::is_post_restricted( $post->ID ) ) {
			return;
		}
		if (
			/**
			 * Filters whether to restrict the post.
			 *
			 * @param bool $restrict Whether to restrict the post.
			 * @param int $post_id Post ID.
			 */
			! apply_filters( 'newspack_content_gate_restrict_post', true, $post->ID )
		) {
			return;
		}

		self::$is_gated = true;

		$content = self::get_restricted_post_excerpt( $post );

		$post->post_content   = $content . self::get_inline_gate_html();
		$post->post_excerpt   = $content;
		$post->comment_status = 'closed';
		$post->comment_count  = 0;

		self::$restricted_content[ $post->ID ] = $post->post_content;

		self::mark_gate_as_rendered();
	}

	/**
	 * Handle restricted post content filtering.
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public static function handle_restricted_content( $content ) {
		if ( ! isset( self::$restricted_content[ get_the_ID() ] ) ) {
			return $content;
		}
		return self::$restricted_content[ get_the_ID() ];
	}

	/**
	 * Get whether the gate is being rendered.
	 *
	 * @return bool
	 */
	public static function is_gated() {
		return self::$is_gated;
	}

	/**
	 * Parses dynamic blocks out of `post_content` and re-renders them.
	 *
	 * This is a copy of `do_blocks()` from `wp-includes/blocks.php` but with
	 * a different filter name for the `wpautop` filter handling.
	 *
	 * @param string $content Post content.
	 *
	 * @return string Updated post content.
	 */
	public static function do_blocks( $content ) {
		$blocks = parse_blocks( $content );
		$output = '';

		foreach ( $blocks as $block ) {
			$output .= render_block( $block );
		}

		// If there are blocks in this content, we shouldn't run wpautop() on it later.
		$priority = has_filter( 'newspack_gate_content', 'wpautop' );
		if ( false !== $priority && doing_filter( 'newspack_gate_content' ) && has_blocks( $content ) ) {
			remove_filter( 'newspack_gate_content', 'wpautop', $priority );
			add_filter( 'newspack_gate_content', [ __CLASS__, 'restore_wpautop_hook' ], $priority + 1 );
		}

		return $output;
	}

	/**
	 * _restore_wpautop_hook filter, but for the newspack_gate_content filter instead of the_content
	 *
	 * @param string $content Content.
	 * @return string
	 */
	public static function restore_wpautop_hook( $content ) {
		$current_priority = has_filter( 'newspack_gate_content', [ __CLASS__, 'restore_wpautop_hook' ] );

		add_filter( 'newspack_gate_content', 'wpautop', $current_priority - 1 );
		remove_filter( 'newspack_gate_content', [ __CLASS__, 'restore_wpautop_hook' ], $current_priority );

		return $content;
	}

	/**
	 * Get all gate post types.
	 *
	 * @return array Array of gate post types.
	 */
	public static function get_gate_post_types() {
		$cpts = [ self::GATE_CPT ];
		if ( Memberships::is_active() ) {
			$cpts[] = Memberships::GATE_CPT;
		}
		return $cpts;
	}

	/**
	 * Register post type for custom gate.
	 */
	public static function register_post_type() {
		// Register the main gate post type.
		\register_post_type(
			self::GATE_CPT,
			[
				'label'        => __( 'Content Gate', 'newspack' ),
				'labels'       => [
					'item_published'         => __( 'Content Gate published.', 'newspack' ),
					'item_reverted_to_draft' => __( 'Content Gate reverted to draft.', 'newspack' ),
					'item_updated'           => __( 'Content Gate updated.', 'newspack' ),
					'new_item'               => __( 'New Content Gate', 'newspack' ),
					'edit_item'              => __( 'Edit Content Gate', 'newspack' ),
					'view_item'              => __( 'View Content Gate', 'newspack' ),
				],
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => false,
				'show_in_rest' => true,
				'supports'     => [ 'title', 'custom-fields', 'revisions' ],
			]
		);
		// Register the layout post type.
		self::register_layout_post_type( self::GATE_LAYOUT_CPT, __( 'Content Gate Layout', 'newspack' ) );
	}

	/**
	 * Redirect the custom gate CPT to the Content Gating wizard
	 */
	public static function redirect_cpt() {
		if ( ! self::is_newspack_feature_enabled() ) {
			return;
		}
		global $pagenow;
		if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && self::GATE_CPT === $_GET['post_type'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$redirect = \admin_url( 'admin.php?page=newspack-audience#/content-gating' );
			\wp_safe_redirect( $redirect );
			exit;
		}
	}

	/**
	 * Enqueue content banner assets.
	 */
	public static function enqueue_content_banner_assets() {
		if ( Content_Gifting::should_enqueue_assets() || Metering_Countdown::is_enabled() ) {
			$asset = require dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/content-banner.asset.php';

			// Ensure the content gate metering script is enqueued first.
			if ( is_singular() && self::has_gate() && self::is_post_restricted() && Metering::is_frontend_metering() ) {
				$asset['dependencies'][] = 'newspack-content-gate-metering';
			}
			wp_enqueue_script( 'newspack-content-banner', Newspack::plugin_url() . '/dist/content-banner.js', $asset['dependencies'], NEWSPACK_PLUGIN_VERSION, true );
			wp_enqueue_style( 'newspack-content-banner', Newspack::plugin_url() . '/dist/content-banner.css', [], NEWSPACK_PLUGIN_VERSION );
		}
	}

	/**
	 * Enqueue frontend scripts and styles for gated content.
	 */
	public static function enqueue_scripts() {
		self::enqueue_content_banner_assets();

		if ( ! self::has_gate() ) {
			return;
		}
		if ( ! is_singular() || ! self::is_post_restricted() ) {
			return;
		}
		$handle = 'newspack-content-gate';
		\wp_enqueue_script(
			$handle,
			Newspack::plugin_url() . '/dist/content-gate.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/content-gate.js' ),
			true
		);
		\wp_script_add_data( $handle, 'async', true );
		\wp_localize_script(
			$handle,
			'newspack_content_gate',
			[
				'metadata' => self::get_gate_metadata(),
			]
		);
		\wp_enqueue_style(
			$handle,
			Newspack::plugin_url() . '/dist/content-gate.css',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/content-gate.css' )
		);
	}

	/**
	 * Get the post ID of the custom gate.
	 *
	 * @param int $post_id Post ID to find gate for.
	 *
	 * @return int|false Post ID or false if not set.
	 */
	public static function get_gate_post_id( $post_id = null ) {
		$gate_post_id = Memberships::is_active() ? Memberships::get_gate_post_id( $post_id ) : Content_Restriction_Control::get_gate_post_id( $post_id );

		/**
		 * Filters the gate post ID.
		 *
		 * @param int $gate_post_id Gate post ID.
		 * @param int $post_id      Post ID.
		 */
		return apply_filters( 'newspack_content_gate_post_id', $gate_post_id, $post_id );
	}

	/**
	 * Get the gate layout ID for the post.
	 *
	 * @param int $post_id Post ID. If not given, uses the current post ID.
	 *
	 * @return int|false
	 */
	public static function get_gate_layout_id( $post_id = null ) {
		$gate_layout_id = Memberships::is_active() ? Memberships::get_gate_post_id( $post_id ) : Content_Restriction_Control::get_gate_layout_id( $post_id );

		/**
		 * Filters the gate layout ID.
		 *
		 * @param int $gate_layout_id Gate layout ID.
		 * @param int $post_id      Post ID.
		 */
		return apply_filters( 'newspack_content_gate_layout_id', $gate_layout_id, $post_id );
	}

	/**
	 * Get gate metadata to be used for analytics purposes.
	 *
	 * @return array {
	 *   The gate metadata.
	 *
	 *   @type int    $gate_post_id The gate post ID.
	 *   @type array  $gate_blocks  Names of unique blocks in the gate post.
	 * }
	 */
	public static function get_gate_metadata() {
		$post_id = self::get_gate_post_id();
		return [
			'gate_post_id' => $post_id,
			'logged_in'    => \is_user_logged_in() ? 'yes' : 'no',
		];
	}

	/**
	 * Whether the gate is available.
	 *
	 * @return bool
	 */
	public static function has_gate() {
		$post_id = self::get_gate_post_id();
		return $post_id && 'publish' === get_post_status( $post_id );
	}

	/**
	 * Whether any gates of the given type has metering enabled.
	 *
	 * @param string $post_type Post type.
	 *
	 * @return bool
	 */
	public static function is_metering_enabled( $post_type = self::GATE_CPT ) {
		$gates = self::get_gates( $post_type );
		foreach ( $gates as $gate ) {
			if ( isset( $gate['metering'] ) && ! empty( $gate['metering']['enabled'] ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Public method for marking the gate as rendered.
	 */
	public static function mark_gate_as_rendered() {
		self::$gate_rendered = true;
	}

	/**
	 * Whether the gate has rendered.
	 */
	public static function has_rendered() {
		return self::$gate_rendered;
	}

	/**
	 * Whether the post has restrictions
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool
	 */
	public static function post_has_restrictions( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();

		// TODO: Content Gate content rules check.

		/**
		 * Filters whether the post has restrictions.
		 *
		 * @param bool $has_restrictions Whether the post has restrictions.
		 * @param int  $post_id          Post ID.
		 */
		return apply_filters( 'newspack_post_has_restrictions', false, $post_id );
	}

	/**
	 * Whether the post is restricted for the current user.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return int|bool Gate ID restricting the post, false if not restricted, or true if restricted by a Woo Memberships plan.
	 */
	public static function is_post_restricted( $post_id = null ) {
		$post_id = $post_id ? $post_id : get_the_ID();

		/**
		 * Filters whether the post is restricted for the current user.
		 *
		 * @param bool $restricted_by Whether the post is restricted.
		 * @param int  $post_id       Post ID.
		 */
		return apply_filters( 'newspack_is_post_restricted', false, $post_id );
	}

	/**
	 * Create a new gate post.
	 *
	 * @param string $title     Optional gate title. Defaults to 'Content Gate'.
	 * @param string $post_type Optional post type. Defaults to self::GATE_CPT.
	 *
	 * @return int|\WP_Error The gate post ID or error if not created.
	 */
	public static function create_gate( $title = '', $post_type = self::GATE_CPT ) {
		$all_gates = self::get_gates();
		$gate_id   = \wp_insert_post(
			[
				'post_title'   => $title,
				'post_type'    => $post_type,
				'post_status'  => 'draft',
				'post_content' => self::get_default_gate_content(),
				'meta_input'   => [
					'gate_priority' => count( $all_gates ),
				],
			],
			true // Return WP_Error on failure.
		);

		if ( is_wp_error( $gate_id ) ) {
			return $gate_id;
		}

		// Create default layouts for registration and custom_access modes.
		$registration_content   = self::get_block_pattern_content( 'registration-card' );
		$registration_layout_id = self::create_gate_layout(
			__( 'Registration Access Layout', 'newspack' ),
			$registration_content
		);
		if ( ! is_wp_error( $registration_layout_id ) ) {
			self::update_registration_settings( $gate_id, [ 'gate_layout_id' => $registration_layout_id ] );
		}

		$custom_access_layout_id = self::create_gate_layout(
			__( 'Paid Access Layout', 'newspack' )
		);
		if ( ! is_wp_error( $custom_access_layout_id ) ) {
			self::update_custom_access_settings( $gate_id, [ 'gate_layout_id' => $custom_access_layout_id ] );
		}

		return $gate_id;
	}

	/**
	 * Delete gate layouts when a gate is permanently deleted.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 */
	public static function delete_gate_layouts( $post_id, $post ) {
		if ( self::GATE_CPT !== $post->post_type ) {
			return;
		}

		$gate = self::get_gate( $post_id );
		if ( is_wp_error( $gate ) ) {
			return;
		}

		// Delete registration layout if it exists.
		if ( ! empty( $gate['registration']['gate_layout_id'] ) ) {
			\wp_delete_post( $gate['registration']['gate_layout_id'], true );
		}

		// Delete custom access layout if it exists.
		if ( ! empty( $gate['custom_access']['gate_layout_id'] ) ) {
			\wp_delete_post( $gate['custom_access']['gate_layout_id'], true );
		}
	}

	/**
	 * Create a new gate layout post.
	 *
	 * @param string $title   Optional gate layout title. Defaults to 'Content Gate Layout'.
	 * @param string $content Optional post content. Defaults to a simple paragraph.
	 *
	 * @return int|\WP_Error The gate layout post ID or error if not created.
	 */
	public static function create_gate_layout( $title = '', $content = '' ) {
		if ( empty( $title ) ) {
			$title = __( 'Content Gate Layout', 'newspack' );
		}
		if ( empty( $content ) ) {
			$content = self::get_default_gate_content();
		}
		return \wp_insert_post(
			[
				'post_title'   => $title,
				'post_type'    => self::GATE_LAYOUT_CPT,
				'post_content' => $content,
			],
			true // Return WP_Error on failure.
		);
	}

	/**
	 * Get block pattern content by slug.
	 *
	 * @param string $pattern_slug The pattern slug (e.g., 'registration-wall').
	 *
	 * @return string The pattern content, or empty string if not found.
	 */
	public static function get_block_pattern_content( $pattern_slug ) {
		$patterns_dir = realpath( __DIR__ . '/block-patterns' );
		if ( ! $patterns_dir ) {
			return '';
		}

		$path = realpath( $patterns_dir . '/' . $pattern_slug . '.php' );

		// Ensure the resolved path is within the block-patterns directory to prevent directory traversal.
		if ( ! $path || strpos( $path, $patterns_dir . DIRECTORY_SEPARATOR ) !== 0 ) {
			return '';
		}

		ob_start();
		require $path;
		return ob_get_clean();
	}

	/**
	 * Get edit gate layout URL.
	 *
	 * @param int|false    $gate_id   Gate ID or false if not set.
	 * @param string|false $gate_mode Gate mode or false if not set.
	 *
	 * @return string Edit gate layout URL.
	 */
	public static function get_edit_gate_layout_url( $gate_id = false, $gate_mode = false ) {
		$action = 'newspack_edit_gate_layout';
		$url    = add_query_arg( '_wpnonce', \wp_create_nonce( $action ), \admin_url( 'admin.php?action=' . $action ) );
		if ( $gate_id ) {
			$url = add_query_arg( 'gate_id', $gate_id, $url );
		}
		if ( $gate_mode ) {
			$url = add_query_arg( 'gate_mode', $gate_mode, $url );
		}
		return \wp_make_link_relative( $url );
	}

	/**
	 * Handle edit gate layout.
	 */
	public static function handle_edit_gate_layout() {
		if ( ! isset( $_GET['action'] ) || 'newspack_edit_gate_layout' !== $_GET['action'] ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'newspack_edit_gate_layout' );

		$gate_id = isset( $_GET['gate_id'] ) ? \absint( $_GET['gate_id'] ) : false;
		if ( ! $gate_id ) {
			\wp_die( esc_html( __( 'Gate ID is required.', 'newspack' ) ) );
		}

		$gate_mode = isset( $_GET['gate_mode'] ) ? \sanitize_text_field( $_GET['gate_mode'] ) : false;
		if ( ! $gate_mode ) {
			\wp_die( esc_html( __( 'Gate mode is required.', 'newspack' ) ) );
		}

		$gate = self::get_gate( $gate_id );
		if ( ! $gate ) {
			\wp_die( esc_html( __( 'Gate not found.', 'newspack' ) ) );
		}

		$gate_layout_id            = 0;
		$gate_layout_default_title = __( 'Content Gate Layout', 'newspack' );

		if ( 'registration' === $gate_mode ) {
			$gate_layout_id = $gate['registration']['gate_layout_id'];
			$gate_layout_default_title = __( 'Registration Access Layout', 'newspack' );
		} elseif ( 'custom_access' === $gate_mode ) {
			$gate_layout_id = $gate['custom_access']['gate_layout_id'];
			$gate_layout_default_title = __( 'Paid Access Layout', 'newspack' );
		} else {
			\wp_die( esc_html( __( 'Invalid gate mode.', 'newspack' ) ) );
		}

		$gate_layout = get_post( $gate_layout_id );
		if ( $gate_layout ) {
			if ( 'trash' === get_post_status( $gate_layout_id ) ) {
				\wp_untrash_post( $gate_layout_id );
			}
			\wp_safe_redirect( \get_edit_post_link( $gate_layout_id, 'edit' ) );
			exit;
		} else {
			// Use registration pattern for registration mode, default content for custom_access.
			$gate_layout_content = 'registration' === $gate_mode ? self::get_block_pattern_content( 'registration-card' ) : '';
			$gate_layout_id      = self::create_gate_layout( $gate_layout_default_title, $gate_layout_content );
			if ( is_wp_error( $gate_layout_id ) ) {
				\wp_die( esc_html( $gate_layout_id->get_error_message() ) );
			}
			$gate[ $gate_mode ]['gate_layout_id'] = $gate_layout_id;
			self::update_gate_settings( $gate_id, $gate );
			\wp_safe_redirect( \get_edit_post_link( $gate_layout_id, 'edit' ) );
			exit;
		}
	}

	/**
	 * Get the inline gate content.
	 */
	public static function get_inline_gate_content() {
		return self::get_inline_gate_content_for_post( self::get_gate_layout_id() );
	}

	/**
	 * Get the inline gate HTML for rendering.
	 *
	 * @return string
	 */
	public static function get_inline_gate_html() {
		return apply_filters( 'newspack_gate_content', self::get_inline_gate_content() );
	}

	/**
	 * Get the post excerpt to be displayed in the gate.
	 *
	 * @param \WP_Post $post Post object.
	 *
	 * @return string
	 */
	public static function get_restricted_post_excerpt( $post ) {
		self::$is_gated = true;
		return self::get_restricted_post_excerpt_for_gate( $post, self::get_gate_layout_id() );
	}

	/**
	 * Render the overlay gate.
	 */
	public static function render_overlay_gate() {
		if ( ! self::has_gate() ) {
			return;
		}
		if (
			/**
			 * Filters whether the overlay gate can be rendered.
			 *
			 * @param bool $can_render Whether the overlay gate can be rendered.
			 */
			! apply_filters( 'newspack_can_render_overlay_gate', true )
		) {
			return;
		}
		// Only render overlay gate for a restricted singular content.
		if ( ! is_singular() || ! self::is_post_restricted() ) {
			return;
		}
		// Bail if metering allows rendering the content.
		if ( ! Metering::is_frontend_metering() && Metering::is_logged_in_metering_allowed() ) {
			return;
		}
		$gate_layout_id = self::get_gate_layout_id();
		$style          = \get_post_meta( $gate_layout_id, 'style', true );
		if ( 'overlay' !== $style ) {
			return;
		}
		self::$is_gated = true;

		global $post;
		$_post = $post;
		$post  = \get_post( $gate_layout_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );

		self::render_overlay_gate_html( $gate_layout_id );

		self::mark_gate_as_rendered();
		wp_reset_postdata();
		$post = $_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Disable popups if rendering a restricted post.
	 *
	 * @param bool $disabled Whether popups are disabled.
	 *
	 * @return bool
	 */
	public static function disable_popups( $disabled ) {
		if (
			is_singular() &&
			self::has_gate() &&
			self::is_post_restricted() &&
			! Metering::is_metering()
		) {
			return true;
		}
		return $disabled;
	}

	/**
	 * Suppress 'article_view' reader activity on locked posts.
	 *
	 * @param array $activity Activity.
	 */
	public static function suppress_article_view_activity( $activity ) {
		if ( Metering::is_frontend_metering() || ( self::is_post_restricted() && ! Metering::is_logged_in_metering_allowed() ) ) {
			return false;
		}
		return $activity;
	}

	/**
	 * Get registration settings for a gate.
	 *
	 * @param int $gate_id Gate ID.
	 *
	 * @return array Registration settings.
	 */
	public static function get_registration_settings( $gate_id ) {
		$registration = \get_post_meta( $gate_id, 'registration', true );
		if ( empty( $registration ) ) {
			$registration = [];
		}

		$default_metering = [
			'enabled' => false,
			'count'   => 0,
			'period'  => 'month',
		];

		return [
			'active'               => isset( $registration['active'] ) ? (bool) $registration['active'] : false,
			'metering'             => isset( $registration['metering'] ) ? $registration['metering'] : $default_metering,
			'require_verification' => isset( $registration['require_verification'] ) ? (bool) $registration['require_verification'] : false,
			'gate_layout_id'       => isset( $registration['gate_layout_id'] ) ? (int) $registration['gate_layout_id'] : 0,
		];
	}

	/**
	 * Update registration settings for a gate.
	 *
	 * @param int   $gate_id  Gate ID.
	 * @param array $settings Registration settings.
	 *
	 * @return void
	 */
	public static function update_registration_settings( $gate_id, $settings ) {
		$registration = get_post_meta( $gate_id, 'registration', true );
		if ( $registration ) {
			$settings = wp_parse_args( $settings, $registration );
		}
		\update_post_meta( $gate_id, 'registration', $settings );
	}

	/**
	 * Get custom access settings for a gate.
	 *
	 * @param int $gate_id Gate ID.
	 *
	 * @return array Custom access settings.
	 */
	public static function get_custom_access_settings( $gate_id ) {
		$custom_access = \get_post_meta( $gate_id, 'custom_access', true );
		if ( empty( $custom_access ) ) {
			$custom_access = [];
		}

		$access_rules = isset( $custom_access['access_rules'] ) ? $custom_access['access_rules'] : [];

		// Normalize legacy flat rules to grouped format.
		$access_rules = Access_Rules::normalize_rules( $access_rules );

		$default_metering = [
			'enabled' => false,
			'count'   => 0,
			'period'  => 'month',
		];

		return [
			'active'         => isset( $custom_access['active'] ) ? (bool) $custom_access['active'] : false,
			'metering'       => isset( $custom_access['metering'] ) ? $custom_access['metering'] : $default_metering,
			'access_rules'   => $access_rules,
			'gate_layout_id' => isset( $custom_access['gate_layout_id'] ) ? (int) $custom_access['gate_layout_id'] : 0,
		];
	}

	/**
	 * Update custom access settings for a gate.
	 *
	 * @param int   $gate_id  Gate ID.
	 * @param array $settings Custom access settings.
	 *
	 * @return void
	 */
	public static function update_custom_access_settings( $gate_id, $settings ) {
		$custom_access = get_post_meta( $gate_id, 'custom_access', true );
		if ( $custom_access ) {
			$settings = wp_parse_args( $settings, $custom_access );
		}
		\update_post_meta( $gate_id, 'custom_access', $settings );
	}

	/**
	 * Get gate.
	 *
	 * @param int $id Gate ID.
	 *
	 * @return array|\WP_Error The gate or error if not found.
	 */
	public static function get_gate( $id ) {
		$post = get_post( $id );
		if ( ! $post ) {
			return new \WP_Error( 'newspack_content_gate_not_found', __( 'Gate not found.', 'newspack' ) );
		}

		return [
			'id'            => $post->ID,
			'status'        => $post->post_status,
			'title'         => $post->post_title,
			'priority'      => (int) get_post_meta( $post->ID, 'gate_priority', true ),
			'content_rules' => self::get_post_content_rules( $post->ID ),
			'registration'  => self::get_registration_settings( $post->ID ),
			'custom_access' => self::get_custom_access_settings( $post->ID ),
		];
	}

	/**
	 * Get the content rules.
	 *
	 * @return array The content rules.
	 */
	public static function get_content_rules() {
		$content_rules = [
			'post_types' => [
				'name'    => __( 'Post Types', 'newspack-plugin' ),
				'options' => Content_Restriction_Control::get_available_post_types(),
				'default' => [ 'post' ],
			],
		];
		$available_taxonomies = Content_Restriction_Control::get_available_taxonomies();
		foreach ( $available_taxonomies as $taxonomy ) {
			$content_rules[ $taxonomy['slug'] ] = [
				'name'    => $taxonomy['label'],
				'default' => [],
			];
		}

		return $content_rules;
	}

	/**
	 * Get the content rules for a post.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array The content rules.
	 */
	public static function get_post_content_rules( $post_id ) {
		$rules = \get_post_meta( $post_id, 'content_rules', true );
		return $rules ? $rules : [];
	}

	/**
	 * Update content rules for bypassing a content gate.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $rules   Array of post content rules.
	 *
	 * @return void
	 */
	public static function update_post_content_rules( $post_id, $rules ) {
		\update_post_meta( $post_id, 'content_rules', $rules );
	}

	/**
	 * Update single gate setting
	 *
	 * @param int    $id    Gate ID.
	 * @param string $key   Gate setting key.
	 * @param mixed  $value Gate setting value.
	 *
	 * @return array|\WP_Error
	 */
	public static function update_gate_setting( $id, $key, $value ) {
		$post = get_post( $id );
		if ( ! $post ) {
			return new \WP_Error( 'newspack_content_gate_not_found', __( 'Gate not found.', 'newspack' ) );
		}

		$update = [];

		if ( 'title' === $key ) {
			$update['post_title'] = $value;
		} elseif ( 'description' === $key ) {
			$update['post_excerpt'] = $value;
		} elseif ( 'gate_priority' === $key ) {
			$update['meta_input'] = [
				'gate_priority' => (int) $value,
			];
		} elseif ( 'content_rules' === $key ) {
			self::update_post_content_rules( $id, $value );
			return self::get_gate( $id );
		} elseif ( 'registration' === $key ) {
			self::update_registration_settings( $id, $value );
			return self::get_gate( $id );
		} elseif ( 'custom_access' === $key ) {
			self::update_custom_access_settings( $id, $value );
			return self::get_gate( $id );
		} else {
			return new \WP_Error( 'newspack_content_gate_invalid_key', __( 'Invalid gate setting key.', 'newspack' ) );
		}

		// Update title and description.
		wp_update_post(
			array_merge(
				[
					'ID' => $id,
				],
				$update
			)
		);

		return self::get_gate( $id );
	}

	/**
	 * Update gate settings
	 *
	 * @param int   $id   Gate ID.
	 * @param array $gate Gate settings.
	 *
	 * @return array|\WP_Error
	 */
	public static function update_gate_settings( $id, $gate ) {
		$post = get_post( $id );
		if ( ! $post ) {
			return new \WP_Error( 'newspack_content_gate_not_found', __( 'Gate not found.', 'newspack' ) );
		}

		// Update title, priority, and status.
		wp_update_post(
			[
				'ID'          => $id,
				'post_title'  => $gate['title'],
				'post_status' => isset( $gate['status'] ) ? $gate['status'] : $post->post_status,
				'meta_input'  => [
					'gate_priority' => $gate['priority'],
				],
			]
		);

		// Update content rules.
		if ( isset( $gate['content_rules'] ) ) {
			self::update_post_content_rules( $id, $gate['content_rules'] );
		}

		// Update registration settings.
		if ( isset( $gate['registration'] ) ) {
			self::update_registration_settings( $id, $gate['registration'] );
		}

		// Update custom access settings.
		if ( isset( $gate['custom_access'] ) ) {
			self::update_custom_access_settings( $id, $gate['custom_access'] );
		}

		return self::get_gate( $id );
	}

	/**
	 * Get the valid gate post statuses.
	 *
	 * @return array
	 */
	public static function get_post_statuses() {
		/**
		 * Filters the valid post statuses for content gates.
		 *
		 * @param array $valid_post_statuses Valid gate post statuses.
		 */
		return apply_filters( 'newspack_content_gate_valid_post_statuses', self::$valid_gate_post_statuses );
	}

	/**
	 * Get all gates.
	 *
	 * @param string          $post_type Post type.
	 * @param string|string[] $post_status Post status or array of statuses to fetch.
	 *
	 * @return array Array of content gates.
	 */
	public static function get_gates( $post_type = self::GATE_CPT, $post_status = null ) {
		$posts = get_posts(
			[
				'post_type'      => $post_type,
				'post_status'    => $post_status ? $post_status : self::get_post_statuses(),
				'posts_per_page' => -1,
			]
		);
		$gates = array_map( [ __CLASS__, 'get_gate' ], wp_list_pluck( $posts, 'ID' ) );
		if ( $post_type === self::GATE_CPT ) {
			usort(
				$gates,
				function( $a, $b ) {
					return $a['priority'] <=> $b['priority'];
				}
			);
		}
		return $gates;
	}

	/**
	 * Get an array of tier-eligible subscription product options, formatted for select controls.
	 *
	 * @return array Array of subscription product options.
	 *              [
	 *                  'label' => Product Name,
	 *                  'value' => product_id,
	 *              ]
	 */
	public static function get_purchasable_product_options() {
		return array_map(
			function( $product ) {
				return [
					'label' => $product->get_name(),
					'value' => (int) $product->get_id(),
				];
			},
			Subscriptions_Tiers::get_tier_eligible_products( [ 'grouped','subscription', 'variable-subscription' ] )
		);
	}
}
Content_Gate::init();
