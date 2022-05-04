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
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		add_action( 'admin_init', [ __CLASS__, 'disable_woocommerce_setup' ] );
	}

	/**
	 * Hide WooCommerce's setup task list. Newspack does the setup behind the scenes.
	 */
	public static function disable_woocommerce_setup() {
		if ( class_exists( '\Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists' ) ) {
			$task_list = \Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists::get_list( 'setup' );
			$task_list->hide();
		}
	}

	/**
	 * Get WC's Order Item related to a donation frequency.
	 *
	 * @param string $frequency Donation frequency.
	 * @param number $amount Donation amount.
	 */
	private static function get_donation_order_item( $frequency, $amount = 0 ) {
		$product_id = Donations::get_donation_product( $frequency );
		if ( false === $product_id ) {
			return false;
		}

		$item = new \WC_Order_Item_Product();
		$item->set_product( wc_get_product( $product_id ) );
		$item->set_total( $amount );
		$item->set_subtotal( $amount );
		return $item;
	}

	/**
	 * If the site is using woocommerce-memberships, create a new user and a
	 * membership, if the donation type calls for it.
	 *
	 * @param string $email_address Email address.
	 * @param string $full_name Full name.
	 * @param string $frequency Donation frequency.
	 */
	public static function set_up_membership( $email_address, $full_name, $frequency ) {
		if ( ! class_exists( 'WC_Memberships_Membership_Plans' ) ) {
			return;
		}
		Logger::log( 'Creating a membership' );
		$wc_memberships_membership_plans = new \WC_Memberships_Membership_Plans();
		$should_create_account           = false;
		$membership_plans                = $wc_memberships_membership_plans->get_membership_plans();
		$order_items                     = [ self::get_donation_order_item( $frequency ) ];
		foreach ( $membership_plans as $plan ) {
			$access_granting_product_ids = wc_memberships_get_order_access_granting_product_ids( $plan, '', $order_items );
			if ( ! empty( $access_granting_product_ids ) ) {
				$should_create_account = true;
				break;
			}
		}
		if ( $should_create_account ) {
			Logger::log( 'This order will result in a membership, creating account for user.' );
			$user_login = sanitize_title( $full_name );
			$user_id    = wc_create_new_customer( $email_address, $user_login, '', [ 'display_name' => $full_name ] );
			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}

			// Log the new user in.
			wp_set_current_user( $user_id, $user_login );
			wp_set_auth_cookie( $user_id );
			return $user_id;
		}
	}

	/**
	 * Add a donation transaction to WooCommerce.
	 *
	 * @param object $order_data Order data.
	 */
	public static function create_transaction( $order_data ) {
		Logger::log( 'Creating an order' );

		$order     = wc_create_order( [ 'status' => 'completed' ] );
		$frequency = $order_data['frequency'];

		$item = self::get_donation_order_item( $frequency, $order_data['amount'] );
		if ( false === $item ) {
			return new WP_Error( 'newspack_woocommerce', __( 'Missing donation product.', 'newspack' ) );
		}

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

		$has_user_id = ! empty( $order_data['user_id'] );
		if ( $has_user_id ) {
			$order->set_customer_id( $order_data['user_id'] );
		}

		$order->set_created_via( 'newspack-stripe' );
		$order->save();

		if ( class_exists( 'WC_Memberships_Membership_Plans' ) ) {
			$wc_memberships_membership_plans = new \WC_Memberships_Membership_Plans();
			// If the order items are tied to a membership plan, a membership will be created.
			$wc_memberships_membership_plans->grant_access_to_membership_from_order( $order );
		}

		return $order->get_id();
	}
}

WooCommerce_Connection::init();
