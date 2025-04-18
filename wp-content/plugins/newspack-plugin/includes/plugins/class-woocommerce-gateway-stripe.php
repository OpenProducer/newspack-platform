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
		add_filter( 'wc_stripe_intent_metadata', [ __CLASS__, 'add_transaction_metadata' ], 10, 2 );
	}

	/**
	 * Add metadata to a Stripe transaction.
	 *
	 * @param array    $metadata Array of keyed metadata values.
	 * @param WC_Order $order Order being processed.
	 *
	 * @return array Array of keyed metadata values.
	 */
	public static function add_transaction_metadata( $metadata, $order ) {
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
			$related_subscriptions = \wcs_get_subscriptions_for_order( $order );
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
}
WooCommerce_Gateway_Stripe::init();
