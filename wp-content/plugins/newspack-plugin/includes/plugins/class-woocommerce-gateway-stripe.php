<?php
/**
 * WooCommerce Gateway Stripe integration class.
 * https://wordpress.org/plugins/woocommerce-gateway-stripe
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class WooCommerce_Gateway_Stripe {
	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'wc_stripe_settings', [ __CLASS__, 'disable_express_checkout_by_default' ] );
		add_filter( 'pre_update_option_woocommerce_stripe_settings', [ __CLASS__, 'disable_link_by_default' ], 11, 2 );

		add_filter( 'wc_stripe_generate_payment_request', [ __CLASS__, 'add_payment_request_metadata' ], 10, 2 );
		add_filter( 'wc_stripe_intent_metadata', [ __CLASS__, 'add_intent_metadata' ], 10, 2 );
	}

	/**
	 * Add metadata to a Stripe transaction.
	 *
	 * @param array    $post_data Payment request data.
	 * @param WC_Order $order Order being processed.
	 */
	public static function add_payment_request_metadata( $post_data, $order ) {
		if ( isset( $post_data['metadata'] ) ) {
			$post_data['metadata'] = self::add_intent_metadata(
				$post_data['metadata'],
				$order
			);
		}
		return $post_data;
	}

	/**
	 * Add metadata to a Stripe transaction.
	 *
	 * @param array    $metadata Array of keyed metadata values.
	 * @param WC_Order $order Order being processed.
	 *
	 * @return array Array of keyed metadata values.
	 */
	public static function add_intent_metadata( $metadata, $order ) {
		// Skip orders with multiple products.
		if ( $order->get_item_count() > 1 ) {
			return $metadata;
		}

		$order_item = array_values( $order->get_items() )[0];
		if ( ! $order_item ) {
			return $metadata;
		}
		$product_id = $order_item->get_product_id();

		// Product name.
		$metadata['Product'] = $order_item->get_name();

		// Transaction type.
		$metadata['Transaction Type'] = 'Regular Purchase';
		if ( Donations::is_donation_product( $product_id ) ) {
			$metadata['Transaction Type'] = 'Donation';
		}
		if ( function_exists( 'wcs_order_contains_parent' ) && \wcs_order_contains_parent( $order ) ) {
			$metadata['Transaction Type'] = 'Subscription';
		}
		if ( function_exists( 'wcs_order_contains_subscription' ) && \wcs_order_contains_subscription( $order, 'renewal' ) ) {
			$metadata['Transaction Type'] = 'Subscription Renewal';
		}

		// Membership type (name of the membership plan associated with the product ID).
		$plan = null;
		// Try to get the plan name from the `woocommerce-memberships-for-teams` plugin.
		if (
			method_exists( '\SkyVerge\WooCommerce\Memberships\Teams\Product', 'get_membership_plan_id' ) &&
			function_exists( 'wc_memberships_get_membership_plan' )
		) {
			$plan_id = \SkyVerge\WooCommerce\Memberships\Teams\Product::get_membership_plan_id( wc_get_product( $product_id ) );
			if ( $plan_id ) {
				$plan = \wc_memberships_get_membership_plan( $plan_id );
			}
		}
		// Otherwise, get the plan name from the `woocommerce-memberships` plugin.
		if ( ! $plan && function_exists( 'wc_memberships_get_membership_plans' ) ) {
			$plans = array_filter(
				\wc_memberships_get_membership_plans(),
				function( $plan ) use ( $product_id ) {
					$product_ids = $plan->get_product_ids();
					return in_array( $product_id, $product_ids );
				}
			);
			if ( ! empty( $plans ) ) {
				$plan = array_values( $plans )[0];
			}
		}
		if ( $plan ) {
			$metadata['Membership Type'] = $plan->get_name();
		}

		// Add subscription data.
		if ( function_exists( 'wcs_get_subscriptions_for_order' ) && function_exists( 'wcs_order_contains_renewal' ) ) {
			$related_subscriptions = \wcs_get_subscriptions_for_order( $order, [ 'order_type' => 'any' ] );
			if ( ! empty( $related_subscriptions ) ) {
				// In theory, there should be just one subscription per renewal.
				$subscription = reset( $related_subscriptions );
				// Add subscription ID to any renewal.
				$metadata['subscription_id'] = $subscription->get_id();
				// `subscription_status` is redundant with `Transaction Type` for legacy reasons.
				if ( \wcs_order_contains_renewal( $order ) ) {
					$metadata['subscription_status'] = 'renewed';
				} else {
					$metadata['subscription_status'] = 'created';
				}
			}
		}

		return $metadata;
	}

	/**
	 * Disable Stripe Express Checkout feature flag.
	 *
	 * @param array $flag Stripe Express Checkout feature flag.
	 * @param array $old_settings Old Stripe Express Checkout feature flag.
	 * @return string
	 */
	public static function disable_express_checkout_feature_flag( $flag, $old_settings ) {
		/**
		 * If the Stripe Express Checkout feature flag is empty, it means this is a new install.
		 * Save the settings as 'no' to prevent the Stripe Express Checkout from being enabled.
		 */
		if ( empty( $old_settings ) && 'yes' === $flag ) {
			$flag = 'no';
		}
		return $flag;
	}

	/**
	 * Disable Apple/Google Pay by default.
	 *
	 * @param array $settings Stripe settings config.
	 * @return array
	 */
	public static function disable_express_checkout_by_default( $settings ) {
		if ( isset( $settings['payment_request']['default'] ) && 'yes' === $settings['payment_request']['default'] ) {
			$settings['payment_request']['default'] = 'no';
		}
		return $settings;
	}

	/**
	 * Disable Stripe Link by default. This is a workaround for undoing enabling link by default for new installs.
	 *
	 * @param string $settings The new value of the option.
	 * @param string $old_settings The old value of the option.
	 */
	public static function disable_link_by_default( $settings, $old_settings ) {
		if (
			! isset( $old_settings['upe_checkout_experience_accepted_payments'] ) && // Old setting is empty, so this is a new install.
			isset( $settings['upe_checkout_experience_accepted_payments'] ) && // New setting has been set.
			is_array( $settings['upe_checkout_experience_accepted_payments'] ) && // New setting is an array.
			in_array( 'link', $settings['upe_checkout_experience_accepted_payments'], true ) // New setting contains 'link'.
		) {
			$settings['upe_checkout_experience_accepted_payments'] = array_diff( $settings['upe_checkout_experience_accepted_payments'], [ 'link' ] );
		}
		return $settings;
	}
}
WooCommerce_Gateway_Stripe::init();
