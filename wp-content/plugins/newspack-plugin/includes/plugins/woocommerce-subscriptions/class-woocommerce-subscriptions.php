<?php
/**
 * WooCommerce Subscriptions Integration class.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class WooCommerce_Subscriptions {
	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_action( 'plugins_loaded', [ __CLASS__, 'woocommerce_subscriptions_integration_init' ] );
		add_filter( 'woocommerce_subscriptions_product_limited_for_user', [ __CLASS__, 'maybe_limit_subscription_product_for_user' ], 10, 3 );
		add_filter( 'woocommerce_subscriptions_product_trial_length', [ __CLASS__, 'limit_free_trials_to_one_per_user' ], 10, 2 );
	}

	/**
	 * Initialize WooCommerce Subscriptions Integration.
	 */
	public static function woocommerce_subscriptions_integration_init() {
		include_once __DIR__ . '/class-on-hold-duration.php';
		include_once __DIR__ . '/class-renewal.php';
		include_once __DIR__ . '/class-subscriptions-meta.php';
		include_once __DIR__ . '/class-subscriptions-confirmation.php';
		include_once __DIR__ . '/class-subscriptions-tiers.php';

		On_Hold_Duration::init();
		Renewal::init();
		Subscriptions_Meta::init();
		Subscriptions_Confirmation::init();
	}


	/**
	 * Check if WooCommerce Subscriptions is active.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return function_exists( 'WC' ) && class_exists( 'WC_Subscriptions' );
	}

	/**
	 * Check if WooCommerce Subscriptions Integration is enabled.
	 *
	 * True if:
	 * - WooCommerce Subscriptions is active and,
	 * - Reader Activation is enabled and,
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		$is_enabled = self::is_active() && Reader_Activation::is_enabled();
		/**
		 * Filters whether subscriptions expiration is enabled.
		 *
		 * @param bool $is_enabled
		 */
		return apply_filters( 'newspack_subscriptions_expiration_enabled', $is_enabled );
	}

	/**
	 * Get the label for a frequency.
	 *
	 * @param string   $frequency Frequency.
	 * @param int|null $interval  Optional interval. If not provided, the interval
	 *                            can be extracted from the frequency string.
	 *                            E.g. 'month_2' -> 2.
	 *
	 * @return string
	 */
	public static function get_frequency_label( $frequency, $interval = null ) {
		$parts    = explode( '_', $frequency );
		$period   = $parts[0] ?? '';
		$interval = $interval ?? ( isset( $parts[1] ) ? (int) $parts[1] : 1 );
		$interval = $interval > 0 ? $interval : 1;

		$single_labels = [
			'day'   => __( 'Daily', 'newspack-plugin' ),
			'week'  => __( 'Weekly', 'newspack-plugin' ),
			'month' => __( 'Monthly', 'newspack-plugin' ),
			'year'  => __( 'Yearly', 'newspack-plugin' ),
		];

		// phpcs:disable WordPress.WP.I18n.MissingTranslatorsComment
		$multiple_templates = [
			'day'   => __( '%s Days', 'newspack-plugin' ),
			'week'  => __( '%s Weeks', 'newspack-plugin' ),
			'month' => __( '%s Months', 'newspack-plugin' ),
			'year'  => __( '%s Years', 'newspack-plugin' ),
		];
		// phpcs:enable

		if ( 1 === $interval ) {
			$label = $single_labels[ $period ] ?? ucfirst( $period );
		} elseif ( isset( $multiple_templates[ $period ] ) ) {
				$label = sprintf(
					$multiple_templates[ $period ],
					number_format_i18n( $interval )
				);
		} else {
			$label = sprintf(
				// translators: 1: Subscription interval. 2: Subscription period.
				__( '%1$s %2$ss', 'newspack-plugin' ),
				number_format_i18n( $interval ),
				ucfirst( $period )
			);
		}

		/**
		 * Filters the frequency label.
		 *
		 * @param string $label     Frequency label.
		 * @param string $frequency Frequency.
		 */
		return apply_filters( 'newspack_subscriptions_frequency_label', $label, $frequency );
	}

	/**
	 * Maybe limit the subscription product for user. If the product is limited to one active
	 * subscription per user, treat on-hold, pending, and pending-cancel statuses as active.
	 *
	 * @param bool           $is_limited_for_user Whether the subscription product is limited for user.
	 * @param int|WC_Product $product A WC_Product object or the ID of a product.
	 * @param int            $user_id The user ID.
	 */
	public static function maybe_limit_subscription_product_for_user( $is_limited_for_user, $product, $user_id ) {
		$product_limitation = \wcs_get_product_limitation( $product );
		if ( ! $is_limited_for_user && 'active' === $product_limitation ) {
			$is_limited_for_user = \wcs_user_has_subscription( $user_id, $product->get_id(), [ 'active', 'on-hold', 'pending', 'pending-cancel' ] );
		}

		// Use custom error messaging if available.
		if ( $is_limited_for_user && method_exists( 'Newspack_Blocks\Modal_Checkout', 'get_subscription_limited_message' ) && method_exists( 'Newspack_Blocks\Modal_Checkout', 'get_subscription_limited_message_any' ) ) {
			$callback = 'active' === $product_limitation ? 'get_subscription_limited_message' : 'get_subscription_limited_message_any';
			add_filter( 'woocommerce_cart_item_removed_message', [ 'Newspack_Blocks\Modal_Checkout', $callback ] );
		}
		return $is_limited_for_user;
	}

	/**
	 * Limit free trial purchases to one per user. If the user already has a subscription of any status,
	 * return 0 to force no free trial period during checkout for this product.
	 *
	 * @param int        $trial_length The trial length.
	 * @param WC_Product $product The product.
	 * @return int The trial length.
	 */
	public static function limit_free_trials_to_one_per_user( $trial_length, $product ) {
		$user_id = get_current_user_id();

		// If not logged in, try to get the user ID from the billing email.
		if ( ! $user_id && method_exists( 'Newspack_Blocks\Modal_Checkout', 'get_user_id_from_email' ) ) {
			$user_id = \Newspack_Blocks\Modal_Checkout::get_user_id_from_email();
		}
		if ( $trial_length && $user_id && $product && $product->is_type( [ 'subscription', 'subscription_variation', 'variable-subscription' ] ) ) {
			$user_subscriptions = array_values( \wcs_get_users_subscriptions( $user_id ) );
			foreach ( $user_subscriptions as $subscription ) {
				if ( $subscription->has_product( $product->get_id() ) ) {
					return 0;
				}
			}
		}

		return $trial_length;
	}
}
WooCommerce_Subscriptions::init();
