<?php
/**
 * WooCommerce Memberships Metering.
 *
 * @package Newspack
 */

namespace Newspack\Memberships;

use Newspack\Newspack;
use Newspack\Memberships;

/**
 * WooCommerce Memberships Metering class.
 */
class Metering {

	const METERING_META_KEY = 'np_memberships_metering';

	/**
	 * Article view activity to be handled by frontend metering.
	 *
	 * @var array|null
	 */
	private static $article_view = null;

	/**
	 * Cache of the user's metering status for posts.
	 *
	 * @var boolean[] Map of post IDs to booleans.
	 */
	private static $logged_in_metering_cache = [];

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'wp', [ __CLASS__, 'handle_restriction' ], 11 );
		add_action( 'wp_footer', [ __CLASS__, 'enqueue_scripts' ] );
		add_filter( 'newspack_reader_activity_article_view', [ __CLASS__, 'get_article_view' ], 20 );
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
			'metering'                  => [
				'type'    => 'boolean',
				'default' => false,
			],
			'metering_anonymous_count'  => [
				'type'    => 'integer',
				'default' => 0,
			],
			'metering_registered_count' => [
				'type'    => 'integer',
				'default' => 0,
			],
			'metering_period'           => [
				'type'    => 'string',
				'default' => 'week',
			],
		];
		foreach ( $meta as $key => $config ) {
			\register_meta(
				'post',
				$key,
				[
					'object_subtype' => Memberships::GATE_CPT,
					'show_in_rest'   => true,
					'type'           => $config['type'],
					'default'        => $config['default'],
					'single'         => true,
				]
			);
		}
	}

	/**
	 * Enqueue frontend scripts and styles for gated content.
	 */
	public static function enqueue_scripts() {
		if ( ! Memberships::has_gate() ) {
			return;
		}
		if ( ! \is_singular() || ! Memberships::is_post_restricted() || ! self::is_frontend_metering() ) {
			return;
		}
		$gate_post_id = Memberships::get_gate_post_id();
		$handle       = 'newspack-memberships-gate-metering';
		\wp_enqueue_script(
			$handle,
			Newspack::plugin_url() . '/dist/memberships-gate-metering.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/memberships-gate-metering.js' ),
			true
		);
		\wp_localize_script(
			$handle,
			'newspack_metering_settings',
			[
				'visible_paragraphs' => \get_post_meta( $gate_post_id, 'visible_paragraphs', true ),
				'use_more_tag'       => \get_post_meta( $gate_post_id, 'use_more_tag', true ),
				'count'              => \get_post_meta( $gate_post_id, 'metering_anonymous_count', true ),
				'period'             => \get_post_meta( $gate_post_id, 'metering_period', true ),
				'gate_id'            => $gate_post_id,
				'post_id'            => get_the_ID(),
				'article_view'       => self::$article_view,
				'excerpt'            => Memberships::get_restricted_post_excerpt( get_post() ),
			]
		);
	}

	/**
	 * Custom handling of content restriction when using metering.
	 */
	public static function handle_restriction() {
		if ( ! class_exists( 'WC_Memberships' ) ) {
			return;
		}
		if ( ! \is_singular() || ! Memberships::is_post_restricted() ) {
			return;
		}

		// Remove the default restriction handler from 'SkyVerge\WooCommerce\Memberships\Restrictions\Posts::restrict_post'.
		if ( self::is_metering() ) {
			$restriction_instance = \wc_memberships()->get_restrictions_instance()->get_posts_restrictions_instance();
			\remove_action( 'the_post', spl_object_hash( $restriction_instance ) . 'restrict_post', 0 );
		}

		// Add inline gate to the footer so it can be handled by the frontend.
		if ( self::is_frontend_metering() ) {
			\add_action(
				'wp_footer',
				function() {
					Memberships::mark_gate_as_rendered();
					echo '<div style="display:none">' . Memberships::get_inline_gate_content() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				},
				1
			);
		}
	}

	/**
	 * Get the metering expiration time for the given date.
	 *
	 * @return int Timestamp of the expiration time.
	 */
	private static function get_expiration_time() {
		$period = \get_post_meta( Memberships::get_gate_post_id(), 'metering_period', true );
		switch ( $period ) {
			case 'day':
				return strtotime( 'tomorrow' );
			case 'week':
				return strtotime( 'next monday' );
			case 'month':
				return mktime( 0, 0, 0, gmdate( 'n' ) + 1, 1 );
			default:
				return 0;
		}
	}

	/**
	 * Whether to use the frontend metering strategy.
	 *
	 * @return bool
	 */
	public static function is_frontend_metering() {
		// Frotend metering strategy should only be applied for anonymous readers.
		if ( \is_user_logged_in() ) {
			return false;
		}

		// Bail if not in a singular restricted post with available gate.
		if ( ! \is_singular() || ! Memberships::has_gate() || ! Memberships::is_post_restricted() ) {
			return false;
		}

		$gate_post_id    = Memberships::get_gate_post_id();
		$metering        = \get_post_meta( $gate_post_id, 'metering', true );
		$anonymous_count = \get_post_meta( $gate_post_id, 'metering_anonymous_count', true );

		$is_frontend_metering = $metering && ! empty( $anonymous_count );

		/**
		 * Filters whether to use the frontend metering strategy.
		 *
		 * @param bool $is_frontend_metering Whether to use the frontend metering strategy.
		 */
		return apply_filters( 'newspack_memberships_is_frontend_metering', $is_frontend_metering );
	}

	/**
	 * Whether to allow content rendering through metering for logged in users.
	 *
	 * @param int $post_id Optional post ID. Default is the current post.
	 *
	 * @return bool
	 */
	public static function is_logged_in_metering_allowed( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// Metering back-end strategy is only for logged-in users.
		if ( ! \is_user_logged_in() ) {
			return false;
		}

		$gate_post_id = Memberships::get_gate_post_id();
		$metering     = \get_post_meta( $gate_post_id, 'metering', true );

		// Bail if metering is not enabled.
		if ( ! $metering ) {
			return false;
		}

		// Return cached value if available.
		if ( isset( self::$logged_in_metering_cache[ $post_id ] ) ) {
			return self::$logged_in_metering_cache[ $post_id ];
		}

		$user_meta_key = self::METERING_META_KEY . '_' . $gate_post_id;

		$updated_user_data  = false;
		$user_metering_data = \get_user_meta( get_current_user_id(), $user_meta_key, true );
		if ( ! is_array( $user_metering_data ) ) {
			$user_metering_data = [];
		}

		$user_expiration = isset( $user_metering_data['expiration'] ) ? $user_metering_data['expiration'] : 0;

		$current_expiration = self::get_expiration_time();
		if ( $user_expiration !== $current_expiration ) {
			// Clear content if expired.
			if ( $user_expiration < $current_expiration ) {
				$user_metering_data['content'] = [];
			}
			// Reset expiration.
			$user_metering_data['expiration'] = $current_expiration;
			$updated_user_data                = true;
		}

		$count = (int) \get_post_meta( $gate_post_id, 'metering_registered_count', true );

		$limited          = count( $user_metering_data['content'] ) >= $count;
		$accessed_content = in_array( $post_id, $user_metering_data['content'], true );
		if ( ! $limited && ! $accessed_content ) {
			$user_metering_data['content'][] = $post_id;
			$updated_user_data               = true;
		}

		if ( $updated_user_data ) {
			\update_user_meta( get_current_user_id(), $user_meta_key, $user_metering_data );
		}

		// Allowed if the content has been accessed or the metering limit has not been reached.
		$allowed = $accessed_content || ! $limited;

		/**
		 * Filters whether to allow content rendering through metering for logged in user.
		 *
		 * @param bool $is_logged_in_metering_allowed Whether to allow content rendering through metering for logged in user
		 * @param int  $post_id                       Post ID.
		 */
		self::$logged_in_metering_cache[ $post_id ] = apply_filters( 'newspack_memberships_is_logged_in_metering_allowed', $allowed, $post_id );

		return self::$logged_in_metering_cache[ $post_id ];
	}

	/**
	 * Whether the content should be allowed to render. If it's frontend metered,
	 * it will be handled by the frontend metering strategy.
	 *
	 * @return bool
	 */
	public static function is_metering() {
		return self::is_frontend_metering() || self::is_logged_in_metering_allowed();
	}

	/**
	 * Store the article view activity push for use in the frontend metering
	 * strategy.
	 *
	 * @param array $activity Activity data.
	 *
	 * @return array
	 */
	public static function get_article_view( $activity ) {
		self::$article_view = $activity;
		return $activity;
	}
}
Metering::init();
