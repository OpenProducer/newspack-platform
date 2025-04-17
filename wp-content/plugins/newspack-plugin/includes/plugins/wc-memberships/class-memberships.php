<?php
/**
 * WooCommerce Memberships integration class.
 *
 * @package Newspack
 */

namespace Newspack;

use Newspack\Logger;
use Newspack\Memberships\Metering;
use Newspack\Reader_Activation;
use Newspack\WooCommerce_Connection;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Memberships {

	const GATE_CPT = 'np_memberships_gate';
	const SKIP_RESTRICTION_IN_RSS_OPTION_NAME = 'newspack_skip_content_restriction_in_rss_feeds';

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
		add_filter( 'wc_memberships_admin_screen_ids', [ __CLASS__, 'admin_screens' ] );
		add_filter( 'wc_memberships_general_settings', [ __CLASS__, 'wc_memberships_general_settings' ] );
		add_filter( 'wc_memberships_is_post_public', [ __CLASS__, 'wc_memberships_is_post_public' ] );
		add_action( 'wc_memberships_user_membership_actions', [ __CLASS__, 'user_membership_meta_box_actions' ], 1, 2 );
		add_action( 'admin_init', [ __CLASS__, 'handle_reevaluation_request' ] );
		add_action( 'wp_footer', [ __CLASS__, 'render_overlay_gate' ], 1 );
		add_action( 'wp_footer', [ __CLASS__, 'render_js' ] );
		add_filter( 'newspack_popups_assess_has_disabled_popups', [ __CLASS__, 'disable_popups' ] );
		add_filter( 'newspack_reader_activity_article_view', [ __CLASS__, 'suppress_article_view_activity' ], 100 );
		add_filter( 'user_has_cap', [ __CLASS__, 'user_has_cap' ], 10, 3 );
		add_action( 'wp', [ __CLASS__, 'remove_unnecessary_content_restriction' ], 11 );
		add_filter( 'body_class', [ __CLASS__, 'add_body_class' ] );

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
		include __DIR__ . '/class-import-export.php';
	}

	/**
	 * Check if Memberships is available.
	 */
	public static function is_active() {
		return class_exists( 'WC_Memberships' ) && function_exists( 'wc_memberships' );
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
		$handle = 'newspack-memberships-gate';
		\wp_enqueue_script(
			$handle,
			Newspack::plugin_url() . '/dist/memberships-gate.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/memberships-gate.js' ),
			true
		);
		\wp_script_add_data( $handle, 'async', true );
		\wp_localize_script(
			$handle,
			'newspack_memberships_gate',
			[
				'metadata' => self::get_gate_metadata(),
			]
		);
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
	 * Recursively get the unique block names from the post content.
	 *
	 * @param array $blocks The blocks.
	 *
	 * @return array
	 */
	private static function get_block_names_recursive( $blocks ) {
		$block_names = [];
		foreach ( $blocks as $block ) {
			if ( ! empty( $block['blockName'] ) ) {
				$block_names[] = $block['blockName'];
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$block_names = array_merge( $block_names, self::get_block_names_recursive( $block['innerBlocks'] ) );
			}
		}
		return array_unique( $block_names );
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
		$blocks  = self::get_block_names_recursive( parse_blocks( get_post_field( 'post_content', $post_id ) ) );
		return [
			'gate_post_id'                => $post_id,
			'gate_has_donation_block'     => in_array( 'newspack-blocks/donate', $blocks ) ? 'yes' : 'no',
			'gate_has_registration_block' => in_array( 'newspack/reader-registration', $blocks ) ? 'yes' : 'no',
			'gate_has_checkout_button'    => in_array( 'newspack-blocks/checkout-button', $blocks ) ? 'yes' : 'no',
		];
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
		if ( ! self::is_active() || ! function_exists( 'wc_memberships_get_membership_plan' ) ) {
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
		if ( ! self::is_active() || ! function_exists( 'wc_memberships_get_membership_plans' ) ) {
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
	 * Get the current setting of the "Display memberships on the subscriptions tab" option.
	 *
	 * @return boolean
	 */
	public static function get_show_on_subscription_tab_setting() {
		return \get_option( 'newspack_memberships_show_on_subscription_tab', false );
	}

	/**
	 * Set the "Display memberships on the subscriptions tab" option.
	 *
	 * @param boolean $show False to show memberships without subscriptions on the subscriptions tab (default)
	 *                      or true to display those memberships on the subscriptions tab..
	 *
	 * @return boolean
	 */
	public static function set_show_on_subscription_tab_setting( $show = false ) {
		return \update_option( 'newspack_memberships_show_on_subscription_tab', $show );
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
		if ( ! self::is_active() || ! function_exists( 'wc_memberships_is_user_active_or_delayed_member' ) ) {
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
		if ( ! self::is_active() || ! function_exists( 'wc_memberships_is_post_content_restricted' ) || ! \wc_memberships_is_post_content_restricted( $post_id ) ) {
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
		// Membership notices are only displayed on products with discounts from plans. The is_product() check makes sure that still works as normal.
		if ( ! self::has_gate() || is_product() ) {
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
		// Products with discounts from plans also display this excerpt; the is_product() check makes sure that still works as normal.
		if ( ! self::has_gate() || is_product() ) {
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
	 * If the gate was rendered, reload the page after a new reader is detected.
	 * This allows the membership purchase to unlock the content.
	 */
	public static function render_js() {
		if ( ! self::$gate_rendered ) {
			return;
		}
		?>
		<script type="text/javascript">
			window.newspackRAS = window.newspackRAS || [];
			window.newspackRAS.push( function( ras ) {
				let hasReader = false;
				ras.on( 'overlay', function( ev ) {
					// When an overlay was closed, and there's a reader,
					// reload the window, but allow other JS – which might have
					// triggered another overlay – to be executed (setTimeout hack).
					if ( ! ras.overlays.get().length && hasReader ) {
						setTimeout( () => {
							if ( ! ras.overlays.get().length ) {
								window.location.reload();
							}
						}, 1 )
					}
				})
				ras.on( 'reader', function( ev ) {
					if ( ev.detail.authenticated && ! window?.newspackReaderActivation?.getPendingCheckout() ) {
						hasReader = true;
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
		if ( ! did_action( 'wp' ) ) {
			return $all_caps;
		}

		// Bail if Woo Memberships is not active or if this is a product.
		if ( ! self::is_active() || is_product() ) {
			return $all_caps;
		}

		if ( ! empty( $caps ) ) {
			foreach ( $caps as $cap ) {

				switch ( $cap ) {
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

						if ( ! isset( $args[1] ) || ! isset( $args[2] ) ) {
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

					case 'wc_memberships_view_delayed_product':
						// Allow users who can edit posts (by default: editors, authors, contributors).
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
		$has_subscription  = false;

		foreach ( $rules as $rule ) {
			$membership_plan_id = $rule->get_membership_plan_id();
			$has_subscription   = ! empty( self::get_user_subscription_for_membership_plan( $user_id, $membership_plan_id ) );

			// If no object ID is provided, then we are looking at rules that apply to whole post types or taxonomies.
			// In this case, rules that apply to specific objects should be skipped.
			if ( empty( $object_id ) && $rule->has_objects() ) {
				continue;
			}

			if ( $has_subscription || wc_memberships_is_user_active_or_delayed_member( $user_id, $rule->get_membership_plan_id() ) ) {
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

	/**
	 * Remove content restriction on the front page and archives, to increase performance.
	 * The only thing Memberships would really do on these pages is add a "You need a membership"-type message in excerpts.
	 */
	public static function remove_unnecessary_content_restriction() {
		if ( ( is_front_page() || is_archive() ) && function_exists( 'wc_memberships' ) ) {
			$memberships = wc_memberships();
			$restrictions_instance = $memberships->get_restrictions_instance();
			$posts_restrictions_instance = $restrictions_instance->get_posts_restrictions_instance();
			remove_action( 'the_post', [ $posts_restrictions_instance, 'restrict_post' ], 0 );
			remove_filter( 'the_content', [ $posts_restrictions_instance, 'handle_restricted_post_content_filtering' ], 999 );
			remove_action( 'loop_start', [ $posts_restrictions_instance, 'display_restricted_taxonomy_term_notice' ], 1 );
		}
	}

	/**
	 * Admin meta boxes handling.
	 *
	 * @param array $screen_ids associative array organized by context.
	 */
	public static function admin_screens( $screen_ids ) {
		$unrestrictable_post_types = [ 'partner_rss_feed' ];
		$screen_ids['meta_boxes'] = array_filter(
			$screen_ids['meta_boxes'],
			function( $screen_id ) use ( $unrestrictable_post_types ) {
				$allow_restrictions = true;
				foreach ( $unrestrictable_post_types as $post_type ) {
					// Use strpos instead of full string match, because each CPT get two items in this array:
					// the `<CPT>` and `edit-<CPT>`.
					if ( strpos( $screen_id, $post_type ) !== false ) {
						$allow_restrictions = false;
					}
				}
				return $allow_restrictions;
			}
		);
		return $screen_ids;
	}

	/**
	 * Check if the content should be restricted by WooCommerce Memberships.
	 *
	 * @param bool $is_public whether the post is public (default false unless explicitly marked as public by an admin).
	 */
	public static function wc_memberships_is_post_public( $is_public ) {
		if ( is_feed() && 'yes' === get_option( self::SKIP_RESTRICTION_IN_RSS_OPTION_NAME ) ) {
			return true;
		}
		return $is_public;
	}

	/**
	 * Add a setting to skip content restrictions in RSS feeds.
	 *
	 * @param array $settings associative array of the plugin settings.
	 */
	public static function wc_memberships_general_settings( $settings ) {
		$setting = [
			'type'    => 'checkbox',
			'id'      => self::SKIP_RESTRICTION_IN_RSS_OPTION_NAME,
			'name'    => __( 'Skip content restriction in RSS feeds', 'newspack-plugin' ),
			'desc'    =>
				'<span class="show-if-hide-content-only-restriction-mode">' . __( 'If enabled, full content will be available in RSS feeds.', 'newspack-plugin' ) . '</span>',
			'default' => 'no',
		];

		$position_of_show_excerpts_setting = array_search( 'wc_memberships_show_excerpts', array_column( $settings, 'id' ) );
		return array_slice( $settings, 0, $position_of_show_excerpts_setting, true ) +
			[ $setting['id'] => $setting ] +
			array_slice( $settings, $position_of_show_excerpts_setting, null, true );
	}

	/**
	 * Add relevant body CSS classnames.
	 *
	 * @param array $classes Array of body class names.
	 */
	public static function add_body_class( $classes ) {
		// If a user has a paid membership, add a body class.
		if ( ! function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
			return $classes;
		}

		$user_active_memberships = \wc_memberships_get_user_active_memberships();
		foreach ( $user_active_memberships as $membership ) {
			$plan = $membership->plan;
			if ( $plan ) {
				$plan_products = $membership->get_plan()->get_product_ids();
				$classes[] = 'is-member-' . $plan->slug;
				$paid_plan_classname = 'is-paid-plan-member';
				if ( ! empty( $plan_products ) && ! in_array( $paid_plan_classname, $classes ) ) {
					$classes[] = $paid_plan_classname;
				}
			}
		}
		return $classes;
	}

	/**
	 * Does the given user have an active subscription with the product required by the given membership plan?
	 *
	 * @param int $user_id User ID.
	 * @param int $membership_plan_id Membership plan ID.
	 *
	 * @return int Subscription ID if the user has an active subscription with the required product. False if the user does not have the required subscription. Null if the membership plan doesn't require a subscription.
	 */
	public static function get_user_subscription_for_membership_plan( $user_id, $membership_plan_id ) {
		$integrations = wc_memberships()->get_integrations_instance();
		$integration  = $integrations ? $integrations->get_subscriptions_instance() : null;
		if ( ! $integration || ! $integration->has_membership_plan_subscription( $membership_plan_id ) ) {
			return null;
		}

		$subscription_plan  = new \WC_Memberships_Integration_Subscriptions_Membership_Plan( $membership_plan_id );
		$required_products  = $subscription_plan->get_subscription_product_ids();
		$user_subscriptions = WooCommerce_Connection::get_active_subscriptions_for_user( $user_id, $required_products );
		if ( empty( $user_subscriptions ) ) {
			return false;
		}

		return (int) reset( $user_subscriptions );
	}

	/**
	 * Handle reevaluation request, triggered by the User Membership meta box action.
	 * Membership and subscription can get unlinked, this will help the administrator
	 * resync the membership status after relinking the subscription.
	 */
	public static function handle_reevaluation_request() {
		if ( isset( $_GET['reevaluate'] ) && isset( $_GET['post'] ) && function_exists( 'wcs_get_subscription' ) && function_exists( 'wc_memberships_get_user_membership' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$subscription = \wcs_get_subscription( absint( $_GET['reevaluate'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$membership = \wc_memberships_get_user_membership( absint( $_GET['post'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( $subscription instanceof \WC_Subscription && $membership ) {
				$integrations = wc_memberships()->get_integrations_instance();
				$integration  = $integrations ? $integrations->get_subscriptions_instance() : null;
				if ( $integration ) {
					$has_same_status = $integration->has_subscription_same_status( $subscription, $membership );
					if ( ! $has_same_status ) {
						$integration->update_related_membership_status(
							$subscription,
							$membership,
							$subscription->get_status()
						);
					}
				}

				wp_safe_redirect( remove_query_arg( 'reevaluate' ) );
				exit;
			}
		}
	}

	/**
	 * Adds User Membership meta box actions.
	 *
	 * @param array $actions associative array.
	 * @param int   $user_membership_id \WC_Membership_User_Membership post ID.
	 * @return array
	 */
	public static function user_membership_meta_box_actions( $actions, $user_membership_id ) {
		$integration  = wc_memberships()->get_integrations_instance()->get_subscriptions_instance();
		$subscription = $integration ? $integration->get_subscription_from_membership( $user_membership_id ) : null;

		if ( $subscription instanceof \WC_Subscription ) {
				$actions = array_merge(
					[
						'reevaluate' => [
							'link'              => admin_url( 'post.php?post=' . $user_membership_id . '&action=edit&reevaluate=' . $subscription->get_id() ),
							'text'              => __( 'Reevaluate status', 'newspack-plugin' ),
							'custom_attributes' => [
								'title' => __( 'Reevaluate status based on subscription status.', 'newspack-plugin' ),
							],
						],
					],
					$actions
				);
		}
		return $actions;
	}
}
Memberships::init();
