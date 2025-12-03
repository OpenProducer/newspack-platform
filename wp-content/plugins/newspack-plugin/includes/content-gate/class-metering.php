<?php
/**
 * WooCommerce Content Gate Metering.
 *
 * @package Newspack
 */

namespace Newspack;

/**
 * WooCommerce Content Gate Metering class.
 */
class Metering {

	const METERING_META_KEY = 'np_content_metering';

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
		add_filter( 'newspack_content_gate_restrict_post', [ __CLASS__, 'restrict_post' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'wp_footer', [ __CLASS__, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ __CLASS__, 'render_frontend_metering_gate' ] );
		add_filter( 'newspack_reader_activity_article_view', [ __CLASS__, 'get_article_view' ], 20 );
	}

	/**
	 * Whether to restrict the post.
	 *
	 * @param bool $restrict Whether to restrict the post.
	 *
	 * @return bool
	 */
	public static function restrict_post( $restrict ) {
		if ( $restrict && self::is_metering() ) {
			return false;
		}
		return $restrict;
	}

	/**
	 * Render the frontend metering gate.
	 */
	public static function render_frontend_metering_gate() {
		if ( ! \is_singular() || ! Content_Gate::is_post_restricted() || ! self::is_frontend_metering() ) {
			return;
		}
		Content_Gate::mark_gate_as_rendered();
		echo '<div style="display:none">' . Content_Gate::get_inline_gate_html() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Register gate meta.
	 */
	public static function register_meta() {
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
					'object_subtype' => Content_Gate::GATE_CPT,
					'show_in_rest'   => true,
					'type'           => $config['type'],
					'default'        => $config['default'],
					'single'         => true,
				]
			);
		}
	}

	/**
	 * Get metering settings for a gate.
	 *
	 * @param int $gate_id Gate ID.
	 *
	 * @return array Metering settings.
	 */
	public static function get_metering_settings( $gate_id ) {
		return [
			'enabled'          => (bool) \get_post_meta( $gate_id, 'metering', true ),
			'anonymous_count'  => \get_post_meta( $gate_id, 'metering_anonymous_count', true ),
			'registered_count' => \get_post_meta( $gate_id, 'metering_registered_count', true ),
			'period'           => \get_post_meta( $gate_id, 'metering_period', true ),
		];
	}

	/**
	 * Update metering settings for a gate.
	 *
	 * @param int   $gate_id  Gate ID.
	 * @param array $settings Metering settings.
	 *
	 * @return void
	 */
	public static function update_metering_settings( $gate_id, $settings ) {
		\update_post_meta( $gate_id, 'metering', $settings['enabled'] );
		\update_post_meta( $gate_id, 'metering_anonymous_count', $settings['anonymous_count'] );
		\update_post_meta( $gate_id, 'metering_registered_count', $settings['registered_count'] );
		\update_post_meta( $gate_id, 'metering_period', $settings['period'] );
	}

	/**
	 * Enqueue frontend scripts and styles for gated content.
	 */
	public static function enqueue_scripts() {
		if ( ! Content_Gate::has_gate() ) {
			return;
		}
		if ( ! \is_singular() || ! Content_Gate::is_post_restricted() || ! self::is_frontend_metering() ) {
			return;
		}
		$gate_post_id = Content_Gate::get_gate_post_id();
		$handle       = 'newspack-content-gate-metering';
		\wp_enqueue_script(
			$handle,
			Newspack::plugin_url() . '/dist/content-gate-metering.js',
			[],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/content-gate-metering.js' ),
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
				'excerpt'            => apply_filters( 'newspack_gate_content', Content_Gate::get_restricted_post_excerpt( get_post() ) ),
			]
		);
	}

	/**
	 * Get the metering expiration time for the given date.
	 *
	 * @return int Timestamp of the expiration time.
	 */
	private static function get_expiration_time() {
		$period = \get_post_meta( Content_Gate::get_gate_post_id(), 'metering_period', true );
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
	 * Whether the post has metering enabled.
	 *
	 * @param int|null $post_id Post ID. Default is the current post.
	 *
	 * @return bool
	 */
	public static function has_metering( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$gate_post_id = Content_Gate::get_gate_post_id( $post_id );
		$metering     = \get_post_meta( $gate_post_id, 'metering', true );
		if ( ! $metering ) {
			return false;
		}
		$anonymous_count  = \get_post_meta( $gate_post_id, 'metering_anonymous_count', true );
		$registered_count = \get_post_meta( $gate_post_id, 'metering_registered_count', true );
		if ( ! $anonymous_count && ! $registered_count ) {
			return false;
		}
		return true;
	}

	/**
	 * Whether to use the frontend metering strategy.
	 *
	 * @return bool
	 */
	public static function is_frontend_metering() {
		/**
		 * This filter documented in the `is_metering` method.
		 */
		$short_circuit = apply_filters( 'newspack_content_gate_metering_short_circuit', null );
		if ( null !== $short_circuit ) {
			return false;
		}

		// Frontend metering strategy should only be applied for anonymous readers.
		if ( \is_user_logged_in() ) {
			return false;
		}

		// Bail if not in a singular restricted post with available gate.
		if ( ! \is_singular() || ! Content_Gate::has_gate() || ! Content_Gate::is_post_restricted() ) {
			return false;
		}

		$gate_post_id    = Content_Gate::get_gate_post_id();
		$metering        = \get_post_meta( $gate_post_id, 'metering', true );
		$anonymous_count = \get_post_meta( $gate_post_id, 'metering_anonymous_count', true );

		$is_frontend_metering = $metering && ! empty( $anonymous_count );

		/**
		 * Filters whether to use the frontend metering strategy.
		 *
		 * @param bool $is_frontend_metering Whether to use the frontend metering strategy.
		 */
		return apply_filters( 'newspack_content_gate_is_frontend_metering', $is_frontend_metering );
	}

	/**
	 * Whether to allow content rendering through metering for logged in users.
	 *
	 * @param int $post_id Optional post ID. Default is the current post.
	 *
	 * @return bool
	 */
	public static function is_logged_in_metering_allowed( $post_id = null ) {
		/**
		 * This filter documented in the `is_metering` method.
		 */
		$short_circuit = apply_filters( 'newspack_content_gate_metering_short_circuit', null );
		if ( null !== $short_circuit ) {
			return false;
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// Metering back-end strategy is only for logged-in users.
		if ( ! \is_user_logged_in() ) {
			return false;
		}

		$gate_post_id = Content_Gate::get_gate_post_id();
		$metering     = \get_post_meta( $gate_post_id, 'metering', true );
		$priority     = \get_post_meta( $gate_post_id, 'gate_priority', true );

		// Bail if metering is not enabled.
		if ( ! $metering ) {
			return false;
		}

		// Return cached value if available.
		if ( isset( self::$logged_in_metering_cache[ $post_id ] ) ) {
			return self::$logged_in_metering_cache[ $post_id ];
		}

		// Aggregate metering by gate priority, if available.
		$suffix = Content_Gate::is_newspack_feature_enabled() && $priority ? $priority : $gate_post_id;
		$user_meta_key = self::METERING_META_KEY . '_' . $suffix;

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
		self::$logged_in_metering_cache[ $post_id ] = apply_filters( 'newspack_content_gate_is_logged_in_metering_allowed', $allowed, $post_id );

		return self::$logged_in_metering_cache[ $post_id ];
	}

	/**
	 * Whether the content should be allowed to render. If it's frontend metered,
	 * it will be handled by the frontend metering strategy.
	 *
	 * @return bool
	 */
	public static function is_metering() {
		/**
		 * Short-circuit the metering check. Anything other than null
		 * will prevent the metering logic from running.
		 *
		 * The `is_logged_in_metering_allowed` method also updates the user meta
		 * to track the content that's been allowed to access. This short-circuit
		 * prevents this from running if we want the entire metering feature to be
		 * skipped.
		 *
		 * @param mixed $short_circuit Short-circuit value. Default is null.
		 *
		 * @return mixed Short-circuit value.
		 */
		$short_circuit = apply_filters( 'newspack_content_gate_metering_short_circuit', null );
		if ( null !== $short_circuit ) {
			return false;
		}

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

	/**
	 * Get the metering period for a post.
	 *
	 * @param int|null $post_id Post ID. Default is current post.
	 *
	 * @return string Metered period (day, week, month).
	 */
	public static function get_metering_period( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$gate_post_id = Content_Gate::get_gate_post_id( $post_id );
		return \get_post_meta( $gate_post_id, 'metering_period', true );
	}

	/**
	 * Get number of metered views for the current user.
	 *
	 * @return int Number of metered views.
	 */
	public static function get_current_user_metered_views() {
		if ( ! is_user_logged_in() ) {
			return 0;
		}

		$gate_post_id  = Content_Gate::get_gate_post_id();
		$meta_key      = self::METERING_META_KEY . '_' . $gate_post_id;
		$metering_data = \get_user_meta( get_current_user_id(), $meta_key, true );
		if ( ! is_array( $metering_data ) || ! isset( $metering_data['content'] ) ) {
			return 0;
		}
		return count( $metering_data['content'] );
	}

	/**
	 * Get total number of metered views for current post.
	 *
	 * @param boolean $is_logged_in Whether to check for logged-in or anonymous users. Default is false (anonymous).
	 *
	 * @return int|boolean Total number of metered views if metering is enabled, otherwise false.
	 */
	public static function get_total_metered_views( $is_logged_in = false ) {
		$gate_post_id = Content_Gate::get_gate_post_id( get_the_ID() );
		if ( ! $gate_post_id ) {
			return false;
		}
		if ( ! $is_logged_in ) {
			return (int) \get_post_meta( $gate_post_id, 'metering_anonymous_count', true );
		}
		return (int) \get_post_meta( $gate_post_id, 'metering_registered_count', true );
	}
}
Metering::init();
