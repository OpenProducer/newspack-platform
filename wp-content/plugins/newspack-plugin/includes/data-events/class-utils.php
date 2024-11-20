<?php
/**
 * Newspack Data Events Utils.
 *
 * @package Newspack
 */

namespace Newspack\Data_Events;

/**
 * Main Class.
 */
final class Utils {
	/**
	 * Get order data.
	 *
	 * @param int  $order_id Order ID.
	 * @param bool $process_donations_only Whether to process only donation orders.
	 *
	 * @return array|null
	 */
	public static function get_order_data( $order_id, $process_donations_only = false ) {
		$order = \wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}
		if ( ! \Newspack\Reader_Activation\Sync\WooCommerce::should_sync_order( $order ) ) {
			return;
		}

		// Donation orders always have just a single product, but other orders can have more than one.
		$product_id = \Newspack\Donations::get_order_donation_product_id( $order_id );
		if ( ! $process_donations_only && ! $product_id ) {
			$product_id = array_values( \Newspack\WooCommerce_Connection::get_products_for_order( $order_id ) );
		}
		if ( ! $product_id ) {
			return;
		}
		$recurrence = get_post_meta( $product_id, '_subscription_period', true );
		$is_renewal = function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order );
		$subscriptions = [];
		$subscription_id = '';
		if ( function_exists( 'wcs_get_subscriptions_for_order' ) ) {
			$subscriptions = array_values( wcs_get_subscriptions_for_order( $order, [ 'order_type' => [ 'parent', 'renewal' ] ] ) );
		}
		if ( count( $subscriptions ) > 0 ) {
			$subscription_id = is_array( $subscriptions ) && ! empty( $subscriptions ) && is_a( $subscriptions[0], 'WC_Subscription' ) ? $subscriptions[0]->get_id() : null;
		}

		return [
			'user_id'         => $order->get_customer_id(),
			'email'           => $order->get_billing_email(),
			'amount'          => (float) $order->get_total(),
			'currency'        => $order->get_currency(),
			'recurrence'      => empty( $recurrence ) ? 'once' : $recurrence,
			'platform'        => \Newspack\Donations::get_platform_slug(),
			'referer'         => $order->get_meta( '_newspack_referer' ),
			'popup_id'        => $order->get_meta( '_newspack_popup_id' ),
			'is_renewal'      => $is_renewal,
			'subscription_id' => $subscription_id,
			'platform_data'   => [
				'order_id'   => $order_id,
				'product_id' => $product_id,
				'client_id'  => $order->get_meta( NEWSPACK_CLIENT_ID_COOKIE_NAME ),
			],
			'user_first_name' => $order->get_billing_first_name(),
			'user_last_name'  => $order->get_billing_last_name(),
		];
	}

	/**
	 * Get recurring donation data.
	 *
	 * @param WC_Subscription $subscription Subscription which is a recurring donation.
	 */
	public static function get_recurring_donation_data( $subscription ) {
		$product_id = \Newspack\Donations::get_order_donation_product_id( $subscription->get_id() );
		if ( ! $product_id ) {
			return;
		}
		$recurrence = get_post_meta( $product_id, '_subscription_period', true );
		return [
			'user_id'         => $subscription->get_customer_id(),
			'email'           => $subscription->get_billing_email(),
			'subscription_id' => $subscription->get_id(),
			'amount'          => (float) $subscription->get_total(),
			'currency'        => $subscription->get_currency(),
			'recurrence'      => empty( $recurrence ) ? 'once' : $recurrence,
			'platform'        => \Newspack\Donations::get_platform_slug(),
			'user_first_name' => $subscription->get_billing_first_name(),
			'user_last_name'  => $subscription->get_billing_last_name(),
		];
	}
}
