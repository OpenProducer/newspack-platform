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

	const GATE_CPT = 'np_content_gate';

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
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'admin_init', [ __CLASS__, 'redirect_cpt' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
		add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_block_editor_assets' ] );
		add_action( 'wp_footer', [ __CLASS__, 'render_overlay_gate' ], 1 );
		add_filter( 'newspack_popups_assess_has_disabled_popups', [ __CLASS__, 'disable_popups' ] );
		add_filter( 'newspack_reader_activity_article_view', [ __CLASS__, 'suppress_article_view_activity' ], 100 );

		add_action( 'the_post', [ __CLASS__, 'restrict_post' ], 10, 2 );

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

		$post->post_content   = $content . self::get_inline_gate_content();
		$post->post_excerpt   = $content;
		$post->comment_status = 'closed';
		$post->comment_count  = 0;
		self::mark_gate_as_rendered();
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
				'supports'     => [ 'editor', 'custom-fields', 'revisions', 'title' ],
			]
		);
	}

	/**
	 * Register gate meta.
	 */
	public static function register_meta() {
		$meta = [
			'style'              => [
				'type'    => 'string',
				'default' => 'inline',
			],
			'inline_fade'        => [
				'type'    => 'boolean',
				'default' => true,
			],
			'use_more_tag'       => [
				'type'    => 'boolean',
				'default' => true,
			],
			'visible_paragraphs' => [
				'type'    => 'integer',
				'default' => 2,
			],
			'overlay_position'   => [
				'type'    => 'string',
				'default' => 'center',
			],
			'overlay_size'       => [
				'type'    => 'string',
				'default' => 'medium',
			],
		];

		foreach ( $meta as $key => $config ) {
			\register_meta(
				'post',
				$key,
				[
					'object_subtype' => self::GATE_CPT,
					'show_in_rest'   => $config['show_in_rest'] ?? true,
					'type'           => $config['type'],
					'default'        => $config['default'],
					'single'         => true,
				]
			);
		}
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
	 * Enqueue block editor assets.
	 */
	public static function enqueue_block_editor_assets() {
		if ( ! in_array( get_post_type(), self::get_gate_post_types(), true ) ) {
			return;
		}
		\wp_enqueue_script(
			'newspack-content-gate',
			Newspack::plugin_url() . '/dist/content-gate-editor.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/content-gate-editor.js' ),
			true
		);
		\wp_localize_script(
			'newspack-content-gate',
			'newspack_content_gate',
			[
				'has_campaigns' => class_exists( 'Newspack_Popups' ),
			]
		);

		\wp_enqueue_style(
			'newspack-content-gate',
			Newspack::plugin_url() . '/dist/content-gate-editor.css',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/content-gate-editor.css' )
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
			if ( $gate['metering']['enabled'] ) {
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
	 */
	public static function create_gate( $title = '', $post_type = self::GATE_CPT ) {
		$all_gates = self::get_gates();
		$id        = \wp_insert_post(
			[
				'post_title'   => $title,
				'post_type'    => $post_type,
				'post_status'  => 'draft',
				'post_content' => '<!-- wp:paragraph --><p>' . __( 'This post is only available to members.', 'newspack' ) . '</p><!-- /wp:paragraph -->',
				'meta_input'   => [
					'gate_priority' => count( $all_gates ),
				],
			]
		);
		if ( is_wp_error( $id ) ) {
			return new \WP_Error( 'newspack_content_gate_create_gate_error', $id->get_error_message() );
		}
		return $id;
	}

	/**
	 * Get the inline gate content.
	 */
	public static function get_inline_gate_content() {
		$gate_post_id = self::get_gate_post_id();
		$style        = \get_post_meta( $gate_post_id, 'style', true );
		if ( 'inline' !== $style ) {
			return '';
		}
		$gate = \get_the_content( null, false, \get_post( $gate_post_id ) );

		// Add clearfix to the gate.
		$gate = '<div style=\'content:"";clear:both;display:table;\'></div>' . $gate;

		// Apply inline fade.
		$visible_paragraphs = self::get_visible_paragraphs( $gate_post_id );
		if ( $visible_paragraphs > 0 && \get_post_meta( $gate_post_id, 'inline_fade', true ) ) {
			$gate = '<div style="pointer-events: none; height: 10em; margin-top: -10em; width: 100%; position: absolute; background: linear-gradient(180deg, rgba(255,255,255,0) 14%, rgba(255,255,255,1) 76%);"></div>' . $gate;
		}

		// Wrap gate in a div for styling.
		$gate = '<div class="newspack-content-gate__gate newspack-content-gate__inline-gate">' . $gate . '</div>';
		return $gate;
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
	 * Get the number of visible paragraphs for the gate.
	 *
	 * @param int $gate_post_id Gate post ID.
	 *
	 * @return int
	 */
	protected static function get_visible_paragraphs( $gate_post_id ) {
		$visible_paragraphs = \get_post_meta( $gate_post_id, 'visible_paragraphs', true );
		return '' === $visible_paragraphs ? 2 : max( 0, (int) $visible_paragraphs );
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

		$gate_post_id = self::get_gate_post_id();

		$content = $post->post_content;

		$style = \get_post_meta( $gate_post_id, 'style', true );

		$use_more_tag = get_post_meta( $gate_post_id, 'use_more_tag', true );
		// Use <!--more--> as threshold if it exists.
		if ( $use_more_tag && strpos( $content, '<!--more-->' ) ) {
			$content = apply_filters( 'newspack_gate_content', explode( '<!--more-->', $content )[0] );
		} else {
			$count = self::get_visible_paragraphs( $gate_post_id );
			if ( 0 === $count ) {
				return '';
			}

			$content = apply_filters( 'newspack_gate_content', $content );
			// Split into paragraphs.
			$content = explode( '</p>', $content );
			// Extract the first $x paragraphs only.
			$content = array_slice( $content, 0, $count );
			if ( 'overlay' === $style ) {
				// Append ellipsis to the last paragraph.
				$content[ count( $content ) - 1 ] .= ' [&hellip;]';
			}
			// Rejoin the paragraphs into a single string again.
			$content = \force_balance_tags( \wp_kses_post( implode( '</p>', $content ) . '</p>' ) );
		}
		return $content;
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
		$gate_post_id = self::get_gate_post_id();
		$style        = \get_post_meta( $gate_post_id, 'style', true );
		if ( 'overlay' !== $style ) {
			return;
		}
		self::$is_gated = true;

		global $post;
		$_post = $post;
		$post  = \get_post( $gate_post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );
		$position = \get_post_meta( $gate_post_id, 'overlay_position', true );
		$size     = \get_post_meta( $gate_post_id, 'overlay_size', true );
		?>
		<div class="newspack-content-gate__gate newspack-content-gate__overlay-gate" style="display:none;" data-position="<?php echo \esc_attr( $position ); ?>" data-size="<?php echo \esc_attr( $size ); ?>">
			<div class="newspack-content-gate__overlay-gate__container">
				<div class="newspack-content-gate__overlay-gate__content">
					<?php echo \apply_filters( 'newspack_gate_content', \get_the_content( null, null, $gate_post_id ) );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
		<?php
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
			'metering'      => Metering::get_metering_settings( $post->ID ),
			'priority'      => (int) get_post_meta( $post->ID, 'gate_priority', true ),
			'access_rules'  => Access_Rules::get_post_access_rules( $post->ID ),
			'content_rules' => self::get_post_content_rules( $post->ID ),
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
		} elseif ( 'metering' === $key ) {
			Metering::update_metering_settings( $id, $value );
			return self::get_gate( $id );
		} elseif ( 'access_rules' === $key ) {
			Access_Rules::update_post_access_rules( $id, $value );
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

		// Update metering settings.
		Metering::update_metering_settings( $id, $gate['metering'] );

		// Update access rules.
		Access_Rules::update_post_access_rules( $id, $gate['access_rules'] );

		// Update content rules.
		self::update_post_content_rules( $id, $gate['content_rules'] );

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
