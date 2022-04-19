<?php
/**
 * Connection with WooCommerce's features.
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Connection with WooCommerce's features.
 */
class WooCommerce_Connection {
	/**
	 * Add a donation transaction to WooCommerce.
	 *
	 * @param object $order_data Order data.
	 */
	public static function create_transaction( $order_data ) {
		Logger::log( 'Creating order' );

		$order     = wc_create_order( [ 'status' => 'completed' ] );
		$frequency = $order_data['frequency'];

		$product_id = Donations::get_donation_product( $frequency );
		if ( false === $product_id ) {
			return new WP_Error( 'newspack_woocommerce', __( 'Missing donation product ID.', 'newspack' ) );
		}

		$item = new \WC_Order_Item_Product();
		$item->set_product( wc_get_product( $product_id ) );
		$item->set_total( $order_data['amount'] );
		$item->set_subtotal( $order_data['amount'] );

		$order->add_item( $item );
		$order->calculate_totals();
		$order->set_currency( $order_data['currency'] );
		$order->set_date_created( $order_data['date'] );
		$order->set_billing_email( $order_data['email'] );
		$order->set_billing_first_name( $order_data['name'] );

		// Add notes to order.
		if ( 'once' === $frequency ) {
			$order->add_order_note( __( 'One-time Newspack donation.', 'newspack' ) );
		} else {
			/* translators: %s - donation frequency */
			$order->add_order_note( sprintf( __( 'Newspack donation with frequency: %s. The subscription will be handled directly in Stripe, not via WooCommerce.', 'newspack' ), $frequency ) );
		}
		if ( $order_data['subscribed'] ) {
			$order->add_order_note( __( 'Donor has opted-in to your newsletter.', 'newspack' ) );
		}

		// Metadata for woocommerce-gateway-stripe plugin.
		$order->add_meta_data( '_payment_method', 'stripe' );
		$order->add_meta_data( '_payment_method_title', __( 'Stripe via Newspack', 'newspack' ) );
		$order->add_meta_data( '_transaction_id', $order_data['stripe_id'] );
		$order->add_meta_data( '_stripe_customer_id', $order_data['stripe_customer_id'] );
		$order->add_meta_data( '_stripe_charge_captured', 'yes' );
		$order->add_meta_data( '_stripe_fee', $order_data['stripe_fee'] );
		$order->add_meta_data( '_stripe_net', $order_data['stripe_net'] );
		$order->add_meta_data( '_stripe_currency', $order_data['currency'] );

		if ( ! empty( $order_data['client_id'] ) ) {
			$order->add_meta_data( 'newspack-cid', $order_data['client_id'] );
		}

		if ( ! empty( $order_data['user_id'] ) ) {
			$order->set_customer_id( $order_data['user_id'] );
		}

		$order->set_created_via( 'newspack-stripe' );
		$order->save();
		return $order->get_id();
	}
}
