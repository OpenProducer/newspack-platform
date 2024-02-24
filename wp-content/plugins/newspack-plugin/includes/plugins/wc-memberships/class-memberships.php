<?php
/**
 * WooCommerce Memberships integration class.
 *
 * @package Newspack
 */

namespace Newspack;

use Newspack\Memberships\Metering;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Memberships {

	const GATE_CPT = 'np_memberships_gate';

	/**
	 * Whether the gate has been rendered in this execution.
	 *
	 * @var boolean
	 */
	private static $gate_rendered = false;

	/**
	 * Membership statuses that should grant access to restricted content.
	 * See: https://woocommerce.com/document/woocommerce-memberships-user-memberships/#section-4
	 *
	 * @var array
	 */
	public static $active_statuses = [ 'active', 'complimentary', 'free-trial', 'pending' ];

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
		add_filter( 'wc_memberships_notice_html', [ __CLASS__, 'wc_memberships_notice_html' ], 100, 4 );
		add_filter( 'wc_memberships_restricted_content_excerpt', [ __CLASS__, 'wc_memberships_excerpt' ], 100, 3 );
		add_filter( 'wc_memberships_message_excerpt_apply_the_content_filter', '__return_false' );
		add_action( 'wp_footer', [ __CLASS__, 'render_overlay_gate' ], 1 );
		add_action( 'wp_footer', [ __CLASS__, 'render_js' ] );
		add_filter( 'newspack_popups_assess_has_disabled_popups', [ __CLASS__, 'disable_popups' ] );
		add_filter( 'newspack_reader_activity_article_view', [ __CLASS__, 'suppress_article_view_activity' ], 100 );
		add_filter( 'user_has_cap', [ __CLASS__, 'user_has_cap' ], 10, 3 );

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

		include __DIR__ . '/class-block-patterns.php';
		include __DIR__ . '/class-metering.php';
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
		// Bail if Woo Memberships is not active.
		if ( ! class_exists( 'WC_Memberships' ) ) {
			return false;
		}
		\register_post_type(
			self::GATE_CPT,
			[
				'label'        => __( 'Memberships Gate', 'newspack' ),
				'labels'       => [
					'item_published'         => __( 'Memberships Gate published.', 'newspack' ),
					'item_reverted_to_draft' => __( 'Memberships Gate reverted to draft.', 'newspack' ),
					'item_updated'           => __( 'Memberships Gate updated.', 'newspack' ),
					'new_item'               => __( 'New Memberships Gate', 'newspack' ),
					'edit_item'              => __( 'Edit Memberships Gate', 'newspack' ),
					'view_item'              => __( 'View Memberships Gate', 'newspack' ),
				],
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => false,
				'show_in_rest' => true,
				'supports'     => [ 'editor', 'custom-fields', 'revisions' ],
			]
		);
	}

	/**
	 * Register gate meta.
	 */
	public static function register_meta() {
		// Bail if Woo Memberships is not active.
		if ( ! class_exists( 'WC_Memberships' ) ) {
			return false;
		}
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
					'show_in_rest'   => true,
					'type'           => $config['type'],
					'default'        => $config['default'],
					'single'         => true,
				]
			);
		}
	}

	/**
	 * Redirect the custom gate CPT to the Memberships wizard
	 */
	public static function redirect_cpt() {
		global $pagenow;
		if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && self::GATE_CPT === $_GET['post_type'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			\wp_safe_redirect( \admin_url( 'admin.php?page=newspack-engagement-wizard' ) );
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
		$handle = 'newspack-memberships-gate';
		\wp_enqueue_script(
			$handle,
			Newspack::plugin_url() . '/dist/memberships-gate.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/memberships-gate.js' ),
			true
		);
		\wp_script_add_data( $handle, 'async', true );
		\wp_enqueue_style(
			$handle,
			Newspack::plugin_url() . '/dist/memberships-gate.css',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/memberships-gate.css' )
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
			'newspack-memberships-gate',
			Newspack::plugin_url() . '/dist/memberships-gate-editor.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/memberships-gate-editor.js' ),
			true
		);
		\wp_localize_script(
			'newspack-memberships-gate',
			'newspack_memberships_gate',
			[
				'has_campaigns' => class_exists( 'Newspack_Popups' ),
				'plans'         => self::get_plans(),
				'gate_plans'    => self::get_gate_plans( get_the_ID() ),
				'edit_gate_url' => self::get_edit_gate_url(),
			]
		);

		\wp_enqueue_style(
			'newspack-memberships-gate',
			Newspack::plugin_url() . '/dist/memberships-gate-editor.css',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/memberships-gate-editor.css' )
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
		$gate_post_id = (int) \get_option( 'newspack_memberships_gate_post_id' );
		if ( is_singular() ) {
			$post_id = $post_id ? $post_id : get_queried_object_id();
		}
		if ( ! empty( $post_id ) ) {
			$plans = self::get_restricted_post_plans( $post_id );
			$gates = array_map( [ __CLASS__, 'get_plan_gate_id' ], $plans );
			$gates = array_values( array_filter( $gates ) );
			foreach ( $gates as $gate_id ) {
				if ( 'publish' === get_post_status( $gate_id ) ) {
					return $gate_id;
				}
			}
		}
		return $gate_post_id ? $gate_post_id : false;
	}

	/**
	 * Get the gate post object for the given plan.
	 *
	 * @param int $plan_id Plan ID.
	 *
	 * @return int|false Gate post ID or false if not found.
	 */
	private static function get_plan_gate_id( $plan_id ) {
		$gates = get_posts(
			[
				'post_type'      => self::GATE_CPT,
				'post_status'    => [ 'publish', 'draft', 'trash', 'pending', 'future' ],
				'posts_per_page' => -1,
			]
		);
		foreach ( $gates as $gate ) {
			$plans = get_post_meta( $gate->ID, 'plans', true );
			if ( is_array( $plans ) && ! empty( $plans ) && in_array( $plan_id, $plans ) ) {
				return $gate->ID;
			}
		}
		return false;
	}

	/**
	 * Get the gate plans names.
	 *
	 * @param int $gate_id Gate post ID.
	 *
	 * @return string[] Plan names keyed by plan ID.
	 */
	private static function get_gate_plans( $gate_id ) {
		if ( ! function_exists( 'wc_memberships_get_membership_plan' ) ) {
			return [];
		}
		$ids = get_post_meta( $gate_id, 'plans', true );
		if ( empty( $ids ) || ! is_array( $ids ) ) {
			return [];
		}
		$plans = [];
		foreach ( $ids as $id ) {
			$plan = wc_memberships_get_membership_plan( $id );
			if ( $plan ) {
				$plans[ $id ] = $plan->get_name();
			}
		}
		return $plans;
	}

	/**
	 * Get all plans and their respective gate ID if available.
	 *
	 * @return array
	 */
	public static function get_plans() {
		if ( ! function_exists( 'wc_memberships_get_membership_plans' ) ) {
			return [];
		}
		$membership_plans = wc_memberships_get_membership_plans();
		$plans            = [];
		foreach ( $membership_plans as $plan ) {
			$plan_id = $plan->get_id();
			$plans[] = [
				'id'          => $plan_id,
				'name'        => $plan->get_name(),
				'gate_id'     => self::get_plan_gate_id( $plan_id ),
				'gate_status' => get_post_status( self::get_plan_gate_id( $plan_id ) ),
				'plan_url'    => get_edit_post_link( $plan_id ),
			];
		}
		return $plans;
	}

	/**
	 * Get the current setting of the "Require memberships in all plans" option.
	 *
	 * @return boolean
	 */
	public static function get_require_all_plans_setting() {
		return \get_option( 'newspack_memberships_require_all_plans', false );
	}

	/**
	 * Set the "Require memberships in all plans" option.
	 *
	 * @param boolean $require False to require membership in any plan restricting content (default)
	 *                         or true to require membership in all plans restricting content.
	 *
	 * @return boolean
	 */
	public static function set_require_all_plans_setting( $require = false ) {
		return \update_option( 'newspack_memberships_require_all_plans', $require );
	}

	/**
	 * Whether the current user is a member of the given plan.
	 *
	 * @param int $plan_id Plan ID.
	 *
	 * @return bool
	 */
	private static function current_user_has_plan( $plan_id ) {
		if ( ! \is_user_logged_in() ) {
			return false;
		}
		if ( ! function_exists( 'wc_memberships_is_user_active_or_delayed_member' ) ) {
			return false;
		}
		return \wc_memberships_is_user_active_or_delayed_member( \get_current_user_id(), $plan_id );
	}

	/**
	 * Get the plans that are currently restricting the given post.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return int[] Array of plan IDs.
	 */
	private static function get_restricted_post_plans( $post_id ) {
		if ( ! class_exists( 'WC_Memberships' ) ) {
			return [];
		}
		$rules = wc_memberships()->get_rules_instance()->get_post_content_restriction_rules( $post_id );
		if ( ! $rules || empty( $rules ) ) {
			return [];
		}
		$plans = [];
		foreach ( $rules as $rule ) {
			$plan_id = $rule->get_membership_plan_id();
			if ( ! empty( $plan_id ) && ! self::current_user_has_plan( $plan_id ) ) {
				$plans[] = $plan_id;
			}
		}
		return $plans;
	}

	/**
	 * Whether the gate is available.
	 *
	 * @return bool
	 */
	public static function has_gate() {
		if ( ! class_exists( 'WC_Memberships' ) ) {
			return false;
		}
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
	 * Whether the post is restricted for the current user.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return bool
	 */
	public static function is_post_restricted( $post_id = null ) {
		if ( ! class_exists( 'WC_Memberships' ) ) {
			return false;
		}
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		if ( ! function_exists( 'wc_memberships_is_post_content_restricted' ) || ! \wc_memberships_is_post_content_restricted( $post_id ) ) {
			return false;
		}
		return ! is_user_logged_in() || ! current_user_can( 'wc_memberships_view_restricted_post_content', $post_id ); // phpcs:ignore WordPress.WP.Capabilities.Unknown
	}

	/**
	 * Get the URL for editing the custom gate.
	 *
	 * @param int|false $plan_id Plan ID.
	 *
	 * @return string
	 */
	public static function get_edit_gate_url( $plan_id = false ) {
		$action = 'newspack_edit_memberships_gate';
		$url    = \add_query_arg( '_wpnonce', \wp_create_nonce( $action ), \admin_url( 'admin.php?action=' . $action ) );
		return str_replace( \site_url(), '', $url );
	}

	/**
	 * Create a post for the custom gate.
	 */
	public static function handle_edit_gate() {
		if ( ! isset( $_GET['action'] ) || 'newspack_edit_memberships_gate' !== $_GET['action'] ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'newspack_edit_memberships_gate' );
		$plan_id      = isset( $_GET['plan_id'] ) ? \absint( $_GET['plan_id'] ) : false;
		$gate_post_id = $plan_id ? self::get_plan_gate_id( $plan_id ) : self::get_gate_post_id();
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
			$post_title = __( 'Memberships Gate', 'newspack' );
			if ( $plan_id ) {
				$plan = \wc_memberships_get_membership_plan( $plan_id );
				if ( $plan ) {
					$post_title = sprintf(
						// Translators: %s is the plan name.
						__( '%s Gate', 'newspack' ),
						$plan->get_name()
					);
				}
			}
			$gate_post_id = \wp_insert_post(
				[
					'post_title'   => $post_title,
					'post_type'    => self::GATE_CPT,
					'post_status'  => 'draft',
					'post_content' => '<!-- wp:paragraph --><p>' . __( 'This post is only available to members.', 'newspack' ) . '</p><!-- /wp:paragraph -->',
				]
			);
			if ( is_wp_error( $gate_post_id ) ) {
				\wp_die( esc_html( $gate_post_id->get_error_message() ) );
			}
			// If not a plan-specific gate, set as primary gate.
			if ( ! $plan_id ) {
				self::set_gate_post_id( $gate_post_id );
			} else {
				\update_post_meta( $gate_post_id, 'plans', [ $plan_id ] );
			}
			\wp_safe_redirect( \admin_url( 'post.php?post=' . $gate_post_id . '&action=edit' ) );
			exit;
		}
	}

	/**
	 * Get the inline gate content for rendering.
	 *
	 * @return string
	 */
	public static function get_inline_gate_content() {
		$gate_post_id = self::get_gate_post_id();
		$style        = \get_post_meta( $gate_post_id, 'style', true );
		if ( 'inline' !== $style ) {
			return '';
		}
		$gate = \apply_filters( 'newspack_gate_content', \get_the_content( null, null, \get_post( $gate_post_id ) ), $gate_post_id );

		// Add clearfix to the gate.
		$gate = '<div style=\'content:"";clear:both;display:table;\'></div>' . $gate;

		// Apply inline fade.
		if ( \get_post_meta( $gate_post_id, 'inline_fade', true ) ) {
			$gate = '<div style="pointer-events: none; height: 10em; margin-top: -10em; width: 100%; position: absolute; background: linear-gradient(180deg, rgba(255,255,255,0) 14%, rgba(255,255,255,1) 76%);"></div>' . $gate;
		}

		// Wrap gate in a div for styling.
		$gate = '<div class="newspack-memberships__gate newspack-memberships__inline-gate">' . $gate . '</div>';
		return $gate;
	}

	/**
	 * Filter WooCommerce Memberships' notice HTML.
	 *
	 * @param string $notice Notice HTML.
	 * @param string $message_body original message content.
	 * @param string $message_code message code.
	 * @param array  $message_args associative array of message arguments.
	 */
	public static function wc_memberships_notice_html( $notice, $message_body, $message_code, $message_args ) {
		// If the gate is not available, don't mess with the notice.
		if ( ! self::has_gate() ) {
			return $notice;
		}
		// Don't show gate unless attached to a specific post.
		if ( empty( $message_args['post'] ) ) {
			return '';
		}
		// If rendering the content in a loop, don't render the gate.
		if ( get_queried_object_id() !== get_the_ID() ) {
			return '';
		}
		self::$gate_rendered = true;
		return self::get_inline_gate_content();
	}

	/**
	 * Filter WooCommerce Memberships' generated excerpt for restricted content.
	 *
	 * @param string $excerpt      Excerpt.
	 * @param object $post         Post object.
	 * @param string $message_code Message code.
	 *
	 * @return string
	 */
	public static function wc_memberships_excerpt( $excerpt, $post, $message_code ) {
		// If the gate is not available, don't mess with the excerpt.
		if ( ! self::has_gate() ) {
			return $excerpt;
		}
		// If rendering the content in a loop, don't truncate the excerpt.
		if ( get_queried_object_id() !== $post->ID ) {
			return $excerpt;
		}
		return self::get_restricted_post_excerpt( $post );
	}

	/**
	 * Get the post excerpt to be displayed in the gate.
	 *
	 * @param WP_Post $post Post object.
	 *
	 * @return string
	 */
	public static function get_restricted_post_excerpt( $post ) {
		$gate_post_id = self::get_gate_post_id();

		$content = $post->post_content;

		$style = \get_post_meta( $gate_post_id, 'style', true );

		$use_more_tag = get_post_meta( $gate_post_id, 'use_more_tag', true );
		// Use <!--more--> as threshold if it exists.
		if ( $use_more_tag && strpos( $content, '<!--more-->' ) ) {
			$content = explode( '<!--more-->', $content )[0];
		} else {
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
		global $post;
		$_post = $post;
		$post  = \get_post( $gate_post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );
		$position = \get_post_meta( $gate_post_id, 'overlay_position', true );
		$size     = \get_post_meta( $gate_post_id, 'overlay_size', true );
		?>
		<div class="newspack-memberships__gate newspack-memberships__overlay-gate" style="display:none;" data-position="<?php echo \esc_attr( $position ); ?>" data-size="<?php echo \esc_attr( $size ); ?>">
			<div class="newspack-memberships__overlay-gate__container">
				<div class="newspack-memberships__overlay-gate__content">
					<?php echo \apply_filters( 'newspack_gate_content', \get_the_content( null, null, $gate_post_id ) );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
		<?php
		self::$gate_rendered = true;
		wp_reset_postdata();
		$post = $_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Render footer JS.
	 *
	 * If the gate was rendered, reload the page after 2 seconds in case RAS
	 * detects a new reader. This allows the membership purchase to unlock the
	 * content.
	 */
	public static function render_js() {
		if ( ! self::$gate_rendered ) {
			return;
		}
		?>
		<script type="text/javascript">
			window.newspackRAS = window.newspackRAS || [];
			window.newspackRAS.push( function( ras ) {
				ras.on( 'reader', function( ev ) {
					if ( ev.detail.authenticated ) {
						if ( ras.overlays.get().length ) {
							ras.on( 'overlay', function( ev ) {
								if ( ! ev.detail.overlays.length ) {
									window.location.reload();
								}
							} );
						} else {
							setTimeout( function() {
								window.location.reload();
							}, 2000 );
						}
					}
				} );
			} );
		</script>
		<?php
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
	 * Check if the passed in caps contain a positive 'manage_woocommerce' capability.
	 * Copied from the WooCommerce Memberships plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param array $caps Capabilities.
	 * @return bool
	 */
	private static function can_manage_woocommerce( $caps ) {
		return isset( $caps['manage_woocommerce'] ) && $caps['manage_woocommerce'];
	}

	/**
	 * Checks if a user has a certain capability.
	 * Overrides behvavior from the WooCommerce Memberships plugin to decide whether to show restricted content.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 *
	 * @param array $all_caps All capabilities.
	 * @param array $caps Capabilities.
	 * @param array $args Capability arguments.
	 * @return array Filtered capabilities.
	 */
	public static function user_has_cap( $all_caps, $caps, $args ) {
		// Bail if Woo Memberships is not active.
		if ( ! class_exists( 'WC_Memberships' ) || ! function_exists( 'wc_memberships' ) ) {
			return $all_caps;
		}

		if ( ! empty( $caps ) ) {
			foreach ( $caps as $cap ) {

				switch ( $cap ) {
					case 'wc_memberships_view_restricted_post_content':
						if ( self::can_manage_woocommerce( $all_caps ) ) {
							$all_caps[ $cap ] = true;
							break;
						}

						// Allow user who can edit posts (by default: editors, authors, contributors).
						if ( isset( $all_caps['edit_posts'] ) && true === $all_caps['edit_posts'] ) {
							$all_caps[ $cap ] = true;
							break;
						}

						$user_id = (int) $args[1];
						$post_id = (int) $args[2];

						if ( wc_memberships()->get_restrictions_instance()->is_post_public( $post_id ) ) {
							$all_caps[ $cap ] = true;
							break;
						}

						$rules            = wc_memberships()->get_rules_instance()->get_post_content_restriction_rules( $post_id );
						$all_caps[ $cap ] = self::user_has_content_access_from_rules( $user_id, $rules, $post_id );

						break;

					case 'wc_memberships_access_all_restricted_content':
					case 'wc_memberships_view_restricted_product':
					case 'wc_memberships_purchase_restricted_product':
					case 'wc_memberships_view_restricted_product_taxonomy_term':
					case 'wc_memberships_view_delayed_product_taxonomy_term':
					case 'wc_memberships_view_restricted_taxonomy_term':
					case 'wc_memberships_view_restricted_taxonomy':
					case 'wc_memberships_view_restricted_post_type':
					case 'wc_memberships_view_delayed_post_type':
					case 'wc_memberships_view_delayed_taxonomy':
					case 'wc_memberships_view_delayed_taxonomy_term':
					case 'wc_memberships_view_delayed_post_content':
					case 'wc_memberships_view_delayed_product':
						// Allow user who can edit posts (by default: editors, authors, contributors).
						if ( isset( $all_caps['edit_posts'] ) && true === $all_caps['edit_posts'] ) {
							$all_caps[ $cap ] = true;
							break;
						}
						break;
				}
			}
		}

		return $all_caps;
	}

	/**
	 * Checks if a user has content access from rules.
	 * Overrides behvavior from the WooCommerce Memberships plugin to decide whether to show restricted content.
	 * Default behavior matches the WooCommerce Memberships plugin: if a user matches ANY applicable membership
	 * plan rules, they are granted access to the content.
	 *
	 * Custom behavior: If the "Require membership in all plans" option is enabled in the Engagement wizard,
	 * then a user must match ALL applicable membership plan rules before being granted access to the content.
	 *
	 * @since 1.9.0
	 *
	 * @param int                                    $user_id WP_User ID.
	 * @param \WC_Memberships_Membership_Plan_Rule[] $rules array of rules to search access from.
	 * @param int                                    $object_id Optional object ID to check access for (defaults to null).
	 * @return bool returns true if there are no rules at all (users always have access).
	 */
	private static function user_has_content_access_from_rules( $user_id, array $rules, $object_id = null ) {
		// Return true if there are no rules at all (users always have access).
		if ( empty( $rules ) ) {
			return true;
		}

		$require_all_plans = self::get_require_all_plans_setting();
		$has_access        = false;

		foreach ( $rules as $rule ) {

			// If no object ID is provided, then we are looking at rules that apply to whole post types or taxonomies.
			// In this case, rules that apply to specific objects should be skipped.
			if ( empty( $object_id ) && $rule->has_objects() ) {
				continue;
			}

			if ( wc_memberships_is_user_active_or_delayed_member( $user_id, $rule->get_membership_plan_id() ) ) {
				$has_access = true;
				if ( ! $require_all_plans ) {
					break;
				}
			} elseif ( $require_all_plans ) {
				$has_access = false;
				break;
			}
		}

		return $has_access;
	}
}
Memberships::init();
