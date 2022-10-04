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
	const CREATED_VIA_NAME = 'newspack-stripe';

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_action( 'admin_init', [ __CLASS__, 'disable_woocommerce_setup' ] );

		// WC Subscriptions hooks in and creates subscription at priority 100, so use priority 101.
		\add_action( 'woocommerce_checkout_order_processed', [ __CLASS__, 'sync_reader_on_order_complete' ], 101 );

		\add_action( 'wp_login', [ __CLASS__, 'sync_reader_on_customer_login' ], 10, 2 );
	}

	/**
	 * Check whether everything is set up to enable customer syncing to ESP.
	 *
	 * @return bool True if enabled. False if not.
	 */
	protected static function can_sync_customers() {
		return Reader_Activation::is_enabled() && class_exists( 'WC_Customer' ) && function_exists( 'wcs_get_users_subscriptions' );
	}

	/**
	 * Hide WooCommerce's setup task list. Newspack does the setup behind the scenes.
	 */
	public static function disable_woocommerce_setup() {
		if ( class_exists( '\Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists' ) ) {
			$task_list = \Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists::get_list( 'setup' );
			if ( $task_list ) {
				$task_list->hide();
			}
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
	 * Sync a reader's info to the ESP when they make an order.
	 *
	 * @param int $order_id Order post ID.
	 */
	public static function sync_reader_on_order_complete( $order_id ) {
		if ( ! self::can_sync_customers() ) {
			return;
		}

		$order = new \WC_Order( $order_id );
		if ( ! $order->get_customer_id() ) {
			return;
		}

		self::sync_reader_from_order( $order );
	}

	/**
	 * Sync a reader's info to the ESP when they log in.
	 *
	 * @param string  $user_login User's login name.
	 * @param WP_User $user User object.
	 */
	public static function sync_reader_on_customer_login( $user_login, $user ) {
		if ( ! self::can_sync_customers() ) {
			return;
		}

		$customer = new \WC_Customer( $user->ID );

		// If user is not a Woo customer, don't need to sync them.
		if ( ! $customer->get_order_count() ) {
			return;
		}

		self::sync_reader_from_order( $customer->get_last_order() );
	}

	/**
	 * Sync a customer to the ESP from an order.
	 *
	 * @param WC_Order $order Order object.
	 */
	public static function sync_reader_from_order( $order ) {
		if ( ! self::can_sync_customers() ) {
			return;
		}

		if ( self::CREATED_VIA_NAME === $order->get_created_via() ) {
			// Only sync orders not created via the Stripe integration.
			return;
		}

		$metadata_keys = Newspack_Newsletters::$metadata_keys;
		$user_id       = $order->get_customer_id();
		if ( ! $user_id ) {
			return;
		}

		$customer = new \WC_Customer( $user_id );
		$metadata = [
			'registration_method' => 'woocommerce-order',
		];

		$metadata[ $metadata_keys['account'] ]           = $order->get_customer_id();
		$metadata[ $metadata_keys['registration_date'] ] = $customer->get_date_created()->date( Newspack_Newsletters::METADATA_DATE_FORMAT );
		$metadata[ $metadata_keys['payment_page'] ]      = \wc_get_checkout_url();

		$order_subscriptions = wcs_get_subscriptions_for_order( $order->get_id() );

		// One-time donation.
		if ( empty( $order_subscriptions ) ) {
			$metadata[ $metadata_keys['membership_status'] ] = 'Donor';
			$metadata[ $metadata_keys['total_paid'] ]        = (float) $customer->get_total_spent() ? $customer->get_total_spent() : $order->get_total();
			$metadata[ $metadata_keys['product_name'] ]      = '';
			$order_items                                     = $order->get_items();
			if ( $order_items ) {
				$metadata[ $metadata_keys['product_name'] ] = reset( $order_items )->get_name();
			}
			$metadata[ $metadata_keys['last_payment_amount'] ] = $order->get_total();
			$metadata[ $metadata_keys['last_payment_date'] ]   = $order->get_date_paid()->date( Newspack_Newsletters::METADATA_DATE_FORMAT );

			// Subscription donation.
		} else {
			$current_subscription = reset( $order_subscriptions );

			$metadata[ $metadata_keys['membership_status'] ] = 'Donor';
			if ( 'active' === $current_subscription->get_status() || 'pending' === $current_subscription->get_status() ) {
				if ( 'month' === $current_subscription->get_billing_period() ) {
					$metadata[ $metadata_keys['membership_status'] ] = 'Monthly Donor';
				}

				if ( 'year' === $current_subscription->get_billing_period() ) {
					$metadata[ $metadata_keys['membership_status'] ] = 'Yearly Donor';
				}
			} else {
				if ( 'month' === $current_subscription->get_billing_period() ) {
					$metadata[ $metadata_keys['membership_status'] ] = 'Ex-Monthly Donor';
				}

				if ( 'year' === $current_subscription->get_billing_period() ) {
					$metadata[ $metadata_keys['membership_status'] ] = 'Ex-Yearly Donor';
				}
			}

			$metadata[ $metadata_keys['sub_start_date'] ]      = $current_subscription->get_date( 'start' );
			$metadata[ $metadata_keys['sub_end_date'] ]        = $current_subscription->get_date( 'end' ) ? $current_subscription->get_date( 'end' ) : '';
			$metadata[ $metadata_keys['billing_cycle'] ]       = $current_subscription->get_billing_period();
			$metadata[ $metadata_keys['recurring_payment'] ]   = $current_subscription->get_total();
			$metadata[ $metadata_keys['last_payment_date'] ]   = $current_subscription->get_date( 'last_order_date_paid' ) ? $current_subscription->get_date( 'last_order_date_paid' ) : gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT );
			$metadata[ $metadata_keys['last_payment_amount'] ] = $current_subscription->get_total();

			// When a WC Subscription is terminated, the next payment date is set to 0. We don't want to sync that – the next payment date should remain as it was
			// in the event of cancellation.
			$next_payment_date = $current_subscription->get_date( 'next_payment' );
			if ( $next_payment_date ) {
				$metadata[ $metadata_keys['next_payment_date'] ] = $next_payment_date;
			}

			$metadata[ $metadata_keys['total_paid'] ]   = (float) $customer->get_total_spent() ? $customer->get_total_spent() : $current_subscription->get_total();
			$metadata[ $metadata_keys['product_name'] ] = '';
			if ( $current_subscription ) {
				$subscription_order_items = $current_subscription->get_items();
				if ( $subscription_order_items ) {
					$metadata[ $metadata_keys['product_name'] ] = reset( $subscription_order_items )->get_name();
				}
			}
		}

		$first_name = $order->get_billing_first_name();
		$last_name  = $order->get_billing_last_name();
		$contact    = [
			'email'    => $order->get_billing_email(),
			'name'     => "$first_name $last_name",
			'metadata' => $metadata,
		];
		\Newspack_Newsletters_Subscription::add_contact( $contact );
	}

	/**
	 * If the site is using woocommerce-memberships, create a new user and a
	 * membership, if the donation type calls for it.
	 *
	 * @param string $email_address Email address.
	 * @param string $full_name Full name.
	 * @param string $frequency Donation frequency.
	 * @param array  $metadata Donor metadata.
	 */
	public static function set_up_membership( $email_address, $full_name, $frequency, $metadata = [] ) {
		if ( ! class_exists( 'WC_Memberships_Membership_Plans' ) ) {
			return;
		}
		Logger::log( 'Creating a membership' );
		$wc_memberships_membership_plans = new \WC_Memberships_Membership_Plans();
		$should_create_account           = false;
		$membership_plans                = $wc_memberships_membership_plans->get_membership_plans();
		$order_items                     = [ self::get_donation_order_item( $frequency ) ];
		foreach ( $membership_plans as $plan ) {
			$access_granting_product_ids = \wc_memberships_get_order_access_granting_product_ids( $plan, '', $order_items );
			if ( ! empty( $access_granting_product_ids ) ) {
				$should_create_account = true;
				break;
			}
		}
		if ( $should_create_account ) {
			if ( Reader_Activation::is_enabled() ) {
				$metadata = array_merge( $metadata, [ 'registration_method' => 'woocommerce-memberships' ] );
				$user_id  = Reader_Activation::register_reader( $email_address, $full_name, true, $metadata );
				return $user_id;
			}

			Logger::log( 'This order will result in a membership, creating account for user.' );
			$user_login = \sanitize_title( $full_name );
			$user_id    = \wc_create_new_customer( $email_address, $user_login, '', [ 'display_name' => $full_name ] );

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}

			// Log the new user in.
			\wp_set_current_user( $user_id, $user_login );
			\wp_set_auth_cookie( $user_id );

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

		$frequency = $order_data['frequency'];

		$item = self::get_donation_order_item( $frequency, $order_data['amount'] );
		if ( false === $item ) {
			return new \WP_Error( 'newspack_woocommerce', __( 'Missing donation product.', 'newspack' ) );
		}

		$order = wc_create_order();
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
		$order->set_payment_method( 'stripe' );
		$order->set_payment_method_title( __( 'Stripe via Newspack', 'newspack' ) );
		$order->set_transaction_id( $order_data['stripe_id'] );
		$order->add_meta_data( '_stripe_customer_id', $order_data['stripe_customer_id'] );
		$order->add_meta_data( '_stripe_charge_captured', 'yes' );
		$order->add_meta_data( '_stripe_fee', $order_data['stripe_fee'] );
		$order->add_meta_data( '_stripe_net', $order_data['stripe_net'] );
		$order->add_meta_data( '_stripe_currency', $order_data['currency'] );

		if ( ! empty( $order_data['client_id'] ) ) {
			/**
			 * When a new order is created that can be associated with a client ID,
			 * fire an action with the client ID and the relevant order info.
			 *
			 * @param WC_Order    $order Donation order.
			 * @param string      $client_id Client ID.
			 * @param string|null $newsletter_email If the user signed up for a newsletter as part of the transaction, the subscribed email address. Otherwise, null.
			 */
			do_action( 'newspack_new_donation_woocommerce', $order, $order_data['client_id'] );
		}

		$has_user_id = ! empty( $order_data['user_id'] );
		if ( $has_user_id ) {
			$order->set_customer_id( $order_data['user_id'] );
		}

		$order->set_created_via( self::CREATED_VIA_NAME );
		$order->set_status( 'completed' );
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
