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

	const GATE_CPT = 'np_memberships_gate';

	/**
	 * The rendered gate post ID.
	 *
	 * @var int|false
	 */
	private static $gate_post_id = false;

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
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_post_type' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'admin_init', [ __CLASS__, 'redirect_cpt' ] );
		add_action( 'admin_init', [ __CLASS__, 'handle_edit_gate' ] );
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
		if ( ! $query->is_main_query() ) {
			return;
		}
		if ( self::has_rendered() ) {
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
		if ( ! self::has_gate() ) {
			return;
		}
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

		$content .= self::get_inline_gate_content();

		$post->post_content   = $content;
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
			add_filter( 'newspack_gate_content', '_restore_wpautop_hook', $priority + 1 );
		}

		return $output;
	}

	/**
	 * Register post type for custom gate.
	 */
	public static function register_post_type() {
		$icon = sprintf(
			'data:image/svg+xml;base64,%s',
			base64_encode( Newspack_UI_Icons::get_svg( 'key' ) )
		);

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
				'show_in_menu' => true,
				'show_in_rest' => true,
				'menu_icon'    => $icon,
				'supports'     => [ 'editor', 'custom-fields', 'revisions', 'title' ],
				'taxonomies'   => [ 'category', 'post_tag' ],
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
			'access_rules'       => [
				'type'         => 'array',
				'default'      => [],
				'single'       => true,
				'show_in_rest' => [
					'schema' => [
						'items' => [
							'type'       => 'object',
							'properties' => [
								'slug'  => [
									'type' => 'string',
								],
								'value' => [
									'type' => 'mixed',
								],
							],
						],
					],
				],
			],
			'post_types'         => [
				'type'         => 'array',
				'default'      => [ 'post' ],
				'single'       => true,
				'show_in_rest' => [
					'schema' => [
						'items' => [
							'type' => 'string',
						],
					],
				],
			],
			'gate_priority'      => [
				'type'    => 'integer',
				'default' => 0,
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
	 * Get the post types that can be restricted.
	 */
	public static function get_available_post_types() {
		$available_post_types = array_values(
			array_map(
				function( $post_type ) {
					return [
						'name'  => $post_type->name,
						'label' => $post_type->label,
					];
				},
				get_post_types(
					[
						'public'       => true,
						'show_in_rest' => true,
						'_builtin'     => false,
					],
					'objects'
				)
			)
		);

		return apply_filters(
			'newspack_content_gate_supported_post_types',
			array_merge(
				[
					[
						'name'  => 'post',
						'label' => 'Posts',
					],
					[
						'name'  => 'page',
						'label' => 'Pages',
					],
				],
				$available_post_types
			)
		);
	}

	/**
	 * Redirect the custom gate CPT to the Content Gating wizard
	 */
	public static function redirect_cpt() {
		global $pagenow;
		if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && self::GATE_CPT === $_GET['post_type'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			\wp_safe_redirect( \admin_url( 'admin.php?page=newspack-audience#/content-gating' ) );
			exit;
		}
	}

	/**
	 * Enqueue frontend scripts and styles for gated content.
	 */
	public static function enqueue_scripts() {
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
		if ( self::GATE_CPT !== get_post_type() ) {
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
				'has_campaigns'      => class_exists( 'Newspack_Popups' ),
				'edit_gate_url'      => self::get_edit_gate_url(),
				'plans'              => Memberships::get_plans(),
				'gate_plans'         => Memberships::get_gate_plans( get_the_ID() ),
				'edit_plan_gate_url' => Memberships::get_edit_plan_gate_url(),
				'post_types'         => self::get_available_post_types(),
				'access_rules'       => Access_Rules::get_access_rules(),
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
	 * Set the post ID of the custom gate.
	 *
	 * @param int $post_id Post ID.
	 */
	public static function set_gate_post_id( $post_id ) {
		\update_option( 'newspack_memberships_gate_post_id', $post_id );
	}

	/**
	 * Get the post ID of the custom gate.
	 *
	 * @param int $post_id Post ID to find gate for.
	 *
	 * @return int|false Post ID or false if not set.
	 */
	public static function get_gate_post_id( $post_id = null ) {
		$gate_post_id = intval( self::$gate_post_id ? self::$gate_post_id : \get_option( 'newspack_memberships_gate_post_id' ) );
		if ( ! $gate_post_id ) {
			$gate_post_id = false;
		}

		/**
		 * Filters the gate post ID.
		 *
		 * @param int $gate_post_id Gate post ID.
		 * @param int $post_id Post ID.
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
		 * If the post is restricted by a content gate, return the gate post ID.
		 *
		 * @param int|bool $restricted_by  If restricted, the gate post ID. False if not restricted.
		 * @param int  $post_id            Post ID.
		 */
		$restricted_by = apply_filters( 'newspack_is_post_restricted', false, $post_id );
		if ( $restricted_by && is_int( $restricted_by ) ) {
			self::$gate_post_id = $restricted_by;
		}
		return $restricted_by;
	}

	/**
	 * Get the URL for editing the custom gate.
	 *
	 * @param int|false $gate_id Gate ID.
	 *
	 * @return string
	 */
	public static function get_edit_gate_url( $gate_id = false ) {
		$action = 'newspack_edit_content_gate';
		$url    = \add_query_arg( '_wpnonce', \wp_create_nonce( $action ), \admin_url( 'admin.php?action=' . $action ) );
		if ( $gate_id ) {
			$url = \add_query_arg( 'gate_id', $gate_id, $url );
		}
		return str_replace( \site_url(), '', $url );
	}

	/**
	 * Handle editing the content gate.
	 */
	public static function handle_edit_gate() {
		if ( ! isset( $_GET['action'] ) || 'newspack_edit_content_gate' !== $_GET['action'] ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'newspack_edit_content_gate' );

		$gate_post_id = self::get_gate_post_id();
		$is_primary   = true;

		if ( isset( $_GET['gate_post_id'] ) ) {
			$gate_post_id = absint( $_GET['gate_post_id'] );
			$is_primary   = false;
			if ( ! $gate_post_id || ! get_post( $gate_post_id ) ) {
				\wp_die( esc_html( __( 'Invalid gate post ID.', 'newspack' ) ) );
			}
		}

		if ( $gate_post_id && get_post( $gate_post_id ) ) {
			// Untrash post if it's in the trash.
			if ( 'trash' === get_post_status( $gate_post_id ) ) {
				\wp_untrash_post( $gate_post_id );
			}
			// Gate found, edit it.
			\wp_safe_redirect( \admin_url( 'post.php?post=' . $gate_post_id . '&action=edit' ) );
			exit;
		} else {
			// Gate not found, create it.
			$post_title   = __( 'Content Gate', 'newspack' );
			$gate_post_id = self::create_gate( $post_title );
			if ( is_wp_error( $gate_post_id ) ) {
				\wp_die( esc_html( $gate_post_id->get_error_message() ) );
			}
			if ( $is_primary ) {
				self::set_gate_post_id( $gate_post_id );
			}
			\wp_safe_redirect( \admin_url( 'post.php?post=' . $gate_post_id . '&action=edit' ) );
			exit;
		}
	}

	/**
	 * Create a new gate post.
	 *
	 * @param string $title Optional gate title. Defaults to 'Content Gate'.
	 */
	public static function create_gate( $title = '' ) {
		$all_gates = self::get_gates();
		$id        = \wp_insert_post(
			[
				'post_title'   => $title,
				'post_type'    => self::GATE_CPT,
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
		if ( \get_post_meta( $gate_post_id, 'inline_fade', true ) ) {
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
			$content = apply_filters( 'newspack_gate_content', $content );
			$count = (int) get_post_meta( $gate_post_id, 'visible_paragraphs', true );
			// Split into paragraphs.
			$content = explode( '</p>', $content );
			// Extract the first $x paragraphs only.
			$content = array_slice( $content, 0, $count ?? 2 );
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
			'description'   => $post->post_excerpt,
			'metering'      => Metering::get_metering_settings( $post->ID ),
			'priority'      => (int) get_post_meta( $post->ID, 'gate_priority', true ),
			'access_rules'  => Access_Rules::get_post_access_rules( $post->ID ),
			'content_rules' => [],
		];
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

		// Update title and description.
		wp_update_post(
			[
				'ID'           => $id,
				'post_title'   => $gate['title'],
				'post_excerpt' => $gate['description'],
				'meta_input'   => [
					'gate_priority' => $gate['priority'],
				],
			]
		);

		// Update metering settings.
		Metering::update_metering_settings( $id, $gate['metering'] );

		// Update access rules.
		Access_Rules::update_post_access_rules( $id, $gate['access_rules'] );

		// TODO: Update content rules.

		return self::get_gate( $id );
	}

	/**
	 * Get all gates.
	 */
	public static function get_gates() {
		$posts = get_posts(
			[
				'post_type'      => self::GATE_CPT,
				'post_status'    => [ 'publish', 'draft', 'trash', 'pending', 'future' ],
				'posts_per_page' => -1,
			]
		);
		$gates = array_map( [ __CLASS__, 'get_gate' ], wp_list_pluck( $posts, 'ID' ) );
		usort(
			$gates,
			function( $a, $b ) {
				return $a['priority'] <=> $b['priority'];
			}
		);
		return $gates;
	}
}
Content_Gate::init();
