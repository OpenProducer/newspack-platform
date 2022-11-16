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
	const CREATED_VIA_NAME                = 'newspack-stripe';
	const SUBSCRIPTION_STRIPE_ID_META_KEY = 'newspack-stripe-subscription-id';

	const DISABLED_SUBSCRIPTION_STATUSES = [
		'active',
		'pending',
		'on-hold',
		'pending-cancel',
		'cancelled',
		'expired',
		'trash',
		'deleted',
		'new-payment-method',
		'switched',
	];

	private static $created_membership_id; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_action( 'admin_init', [ __CLASS__, 'disable_woocommerce_setup' ] );

		// WooCommerce Subscriptions.
		\add_action( 'add_meta_boxes', [ __CLASS__, 'remove_subscriptions_schedule_meta_box' ], 45 );
		\add_filter( 'wc_order_is_editable', [ __CLASS__, 'make_syncd_subscriptions_uneditable' ], 10, 2 );
		\add_filter( 'woocommerce_order_actions', [ __CLASS__, 'remove_syncd_subscriptions_order_actions' ], 11, 1 );
		foreach ( self::DISABLED_SUBSCRIPTION_STATUSES as $status_name ) {
			\add_filter( 'woocommerce_can_subscription_be_updated_to_' . $status_name, [ __CLASS__, 'disable_subscription_status_updates' ], 11, 2 );
		}

		// WooCommerce Memberships.
		\add_action( 'wc_memberships_user_membership_created', [ __CLASS__, 'wc_membership_created' ], 10, 2 );

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
	 * If a membership is created during the request, save its ID.
	 *
	 * @param \WC_Memberships_Membership_Plan $membership_plan the plan that user was granted access to.
	 * @param array                           $args array of User Membership arguments.
	 */
	public static function wc_membership_created( $membership_plan, $args ) {
		self::$created_membership_id = $args['user_membership_id'];
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
		$item->set_product( \wc_get_product( $product_id ) );
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
			$order_date_paid                                   = $order->get_date_paid();
			if ( null !== $order_date_paid ) {
				$metadata[ $metadata_keys['last_payment_date'] ] = $order_date_paid->date( Newspack_Newsletters::METADATA_DATE_FORMAT );
			}

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
	 * Find order by Stripe transaction ID.
	 *
	 * @param string $transaction_id Transaction ID.
	 */
	private static function find_order_by_transaction_id( $transaction_id ) {
		global $wpdb;
		return $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s;", '_transaction_id', $transaction_id ), // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			ARRAY_A
		);
	}

	/**
	 * Get a WC Subscription object by Stripe Subscription ID.
	 *
	 * @param string $stripe_subscription_id Stripe Subscription ID.
	 * @return WC_Subscription|false Subscription object or false.
	 */
	private static function get_subscription_by_stripe_subscription_id( $stripe_subscription_id ) {
		if ( ! function_exists( 'wcs_get_subscription' ) ) {
			return false;
		}
		global $wpdb;
		$query           = $wpdb->prepare(
			'SELECT post_id FROM `wp_postmeta` WHERE `meta_key` = %s AND `meta_value` = %s',
			self::SUBSCRIPTION_STRIPE_ID_META_KEY,
			$stripe_subscription_id
		);
		$subscription_id           = $wpdb->get_var( $query ); // phpcs:ignore
		if ( $subscription_id ) {
			return \wcs_get_subscription( $subscription_id );
		} else {
			Logger::log( 'Error: could not find WC subscription by Stripe id: ' . $stripe_subscription_id );
			return false;
		}
	}

	/**
	 * Add data to an order or subscription.
	 *
	 * @param WC_Order $order Order object. Can be a subscription or an order.
	 * @param array    $order_data Order data.
	 */
	private static function add_universal_order_data( $order, $order_data ) {
		$order->set_currency( $order_data['currency'] );
		$order->set_date_created( $order_data['date'] );
		$order->set_billing_email( $order_data['email'] );
		$order->set_billing_first_name( $order_data['name'] );

		if ( $order_data['subscribed'] ) {
			$order->add_order_note( __( 'Donor has opted-in to your newsletter.', 'newspack' ) );
		}

		if ( ! empty( $order_data['client_id'] ) ) {
			$order->add_meta_data( NEWSPACK_CLIENT_ID_COOKIE_NAME, $order_data['client_id'] );
		}

		if ( ! empty( $order_data['user_id'] ) ) {
			$order->set_customer_id( $order_data['user_id'] );
		}

		$order->set_created_via( self::CREATED_VIA_NAME );
	}

	/**
	 * Create a WooCommerce order.
	 *
	 * @param array         $order_data Order data.
	 * @param WC_Order_Item $item Order item.
	 */
	private static function create_order( $order_data, $item ) {
		$frequency = $order_data['frequency'];
		$order     = \wc_create_order();

		self::add_universal_order_data( $order, $order_data );

		// Add notes to the order.
		if ( 'once' === $frequency ) {
			$order->add_order_note( __( 'One-time Newspack donation.', 'newspack' ) );
		} else {
			/* translators: %s - donation frequency */
			$order->add_order_note( sprintf( __( 'Newspack donation with frequency: %s.', 'newspack' ), $frequency ) );
		}

		$order->add_item( $item );
		$order->calculate_totals();
		$order->set_status( 'completed' );

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
			 */
			do_action( 'newspack_new_donation_woocommerce', $order, $order_data['client_id'] );
		}

		$order->save();

		Logger::log( 'Created WC order with id: ' . $order->get_id() );

		return $order;
	}

	/**
	 * Convert timestamp to a date string.
	 *
	 * @param int $timestamp Timestamp.
	 */
	private static function convert_timestamp_to_date( $timestamp ) {
		if ( 0 === $timestamp ) {
			return 0;
		}
		return gmdate( 'Y-m-d H:i:s', $timestamp );
	}

	/**
	 * Add a donation transaction to WooCommerce.
	 *
	 * @param object $order_data Order data.
	 */
	public static function create_transaction( $order_data ) {
		$transaction_id = $order_data['stripe_id'];

		$found_order = self::find_order_by_transaction_id( $transaction_id );
		if ( ! empty( $found_order ) ) {
			Logger::log( 'NOT creating an order, it was already synced.' );
			return;
		}

		$frequency              = $order_data['frequency'];
		$stripe_subscription_id = $order_data['stripe_subscription_id'];

		// Match the Stripe product to WC product.
		$item = self::get_donation_order_item( $frequency, $order_data['amount'] );
		if ( false === $item ) {
			return new \WP_Error( 'newspack_woocommerce', __( 'Missing donation product.', 'newspack' ) );
		}

		$subscription_status = 'none';

		if (
			Donations::is_recurring( $frequency )
			&& function_exists( 'wcs_create_subscription' ) // WC Subscriptions plugin is active.
			&& isset( $order_data['stripe_invoice_billing_reason'] )
			&& isset( $order_data['stripe_subscription_id'] ) && is_string( $order_data['stripe_subscription_id'] )
			&& isset( $order_data['user_id'] ) // Subscription can't be created without a user.
		) {
			if ( 'subscription_create' === $order_data['stripe_invoice_billing_reason'] ) {
				$subscription_status = 'created';
			} elseif ( 'subscription_cycle' === $order_data['stripe_invoice_billing_reason'] ) {
				$subscription_status = 'renewed';
			}
		}

		if ( 'renewed' === $subscription_status ) {
			/**
			 * Handle WooCommerce Subscriptions - subscription renewal. This will create a new order
			 * and associate it with a subscription.
			 */
			$subscription = self::get_subscription_by_stripe_subscription_id( $stripe_subscription_id );
			if ( $subscription ) {
				$order = \wcs_create_renewal_order( $subscription );
				self::add_universal_order_data( $order, $order_data );
				$order->set_status( 'completed' );
				$order->save();
				Logger::log( 'Updated WC subscription with id: ' . $subscription->get_id() . ' with a new order of id: ' . $order->get_id() );
			} else {
				// Linked subscription not found, just create an order.
				$order = self::create_order( $order_data, $item );
			}
		} else {
			/**
			 * Create an order for a one-time or subscription-creation donation.
			 */
			$order = self::create_order( $order_data, $item );

			/**
			 * Handle WooCommerce Subscriptions - new subscription.
			 */
			if ( 'created' === $subscription_status ) {
				$subscription = \wcs_create_subscription(
					[
						'start_date'       => self::convert_timestamp_to_date( $order_data['date'] ),
						'order_id'         => $order->get_id(),
						'billing_period'   => $frequency,
						'billing_interval' => 1, // Every billing period (not e.g. every *second* month).
					]
				);

				if ( is_wp_error( $subscription ) ) {
					Logger::log( 'Error creating WC subscription: ' . $subscription->get_error_message() );
				} else {
					self::add_universal_order_data( $subscription, $order_data );
					/* translators: %s - donation frequency */
					$subscription->add_order_note( sprintf( __( 'Newspack subscription with frequency: %s. The recurring payment and the subscription will be handled in Stripe, so you\'ll see "Manual renewal" as the payment method in WooCommerce.', 'newspack' ), $frequency ) );
					$subscription->update_status( 'active' ); // Settings status via method (not in wcs_create_subscription), to make WCS recalculate dates.
					$subscription->add_item( $item );
					$subscription->calculate_totals();
					$subscription->save();
					$subscription_id = $subscription->get_id();

					update_post_meta( $subscription_id, self::SUBSCRIPTION_STRIPE_ID_META_KEY, $stripe_subscription_id );
					Logger::log( 'Created WC subscription with id: ' . $subscription_id );

					if ( class_exists( 'WC_Memberships_Integration_Subscriptions_User_Membership' ) && self::$created_membership_id ) {
						Logger::log( 'Linking membership ' . self::$created_membership_id . ' to subscription ' . $subscription_id );
						$subscription_membership = new \WC_Memberships_Integration_Subscriptions_User_Membership( self::$created_membership_id );
						$subscription_membership->set_subscription_id( $subscription_id );
					}
				}
			}
		}

		/**
		 * Handle WooCommerce Memberships.
		 */
		if ( class_exists( 'WC_Memberships_Membership_Plans' ) ) {
			$wc_memberships_membership_plans = new \WC_Memberships_Membership_Plans();
			// If the order items are tied to a membership plan, a membership will be created.
			$wc_memberships_membership_plans->grant_access_to_membership_from_order( $order );
		}

		return $order->get_id();
	}

	/**
	 * Cancel a subscription in WooCommerce.
	 *
	 * @param string $stripe_subscription_id Stripe subscription ID.
	 * @param int    $end_date Timestamp of when to cancel the subscription.
	 *
	 * @return int Number of remaining active subscriptions for the user.
	 */
	public static function end_subscription( $stripe_subscription_id, $end_date ) {
		$subscription = self::get_subscription_by_stripe_subscription_id( $stripe_subscription_id );
		$active_subs  = 0;
		if ( $subscription ) {
			$wc_user_id = $subscription->get_user_id();
			$subscription->delete_date( 'next_payment' );
			$subscription->update_dates(
				[
					'end' => self::convert_timestamp_to_date( $end_date ),
				]
			);
			$subscription->set_status( 'cancelled' );
			$subscription->save();
			Logger::log( 'Cancelled WC subscription with id: ' . $subscription->get_id() );

			if ( $wc_user_id && function_exists( 'wcs_get_users_subscriptions' ) ) {
				$all_subs = \wcs_get_users_subscriptions( $wc_user_id );

				foreach ( $all_subs as $sub ) {
					if ( 'active' === $sub->get_status() ) {
						$active_subs ++;
					}
				}
			}
		}

		return $active_subs;
	}

	/**
	 * Set status to pending cancellation.
	 *
	 * @param string $stripe_subscription_id Stripe subscription ID.
	 * @param int    $cancelled_at Timestamp of when the subscription cancellation happened.
	 * @param int    $end Timestamp of when to cancel the subscription.
	 */
	public static function set_pending_cancellation_subscription( $stripe_subscription_id, $cancelled_at, $end ) {
		$subscription = self::get_subscription_by_stripe_subscription_id( $stripe_subscription_id );
		if ( $subscription ) {
			$subscription->delete_date( 'next_payment' );
			self::update_subscription_dates(
				$stripe_subscription_id,
				[
					'cancelled' => $cancelled_at,
					'end'       => $end,
				]
			);
			Logger::log( 'Set to pending cancallation: WC subscription with id ' . $subscription->get_id() . ' by Stripe id: ' . $stripe_subscription_id );
		}
	}

	/**
	 * Re-activate a subscription.
	 *
	 * @param string $stripe_subscription_id Stripe subscription ID.
	 */
	public static function reactivate_subscription( $stripe_subscription_id ) {
		$subscription = self::get_subscription_by_stripe_subscription_id( $stripe_subscription_id );
		if ( $subscription ) {
			$subscription->set_status( 'active' );
			self::update_subscription_dates(
				$stripe_subscription_id,
				[ 'end' => 0 ]
			);
			$subscription->save();
			Logger::log( 'Reactivated WC subscription with id ' . $subscription->get_id() . ' by Stripe id: ' . $stripe_subscription_id );
		}
	}

	/**
	 * Schedule a subscriptions for cancellation.
	 *
	 * @param string $stripe_subscription_id Stripe subscription ID.
	 * @param array  $dates Dates to update.
	 */
	private static function update_subscription_dates( $stripe_subscription_id, $dates ) {
		$subscription = self::get_subscription_by_stripe_subscription_id( $stripe_subscription_id );
		if ( $subscription ) {
			foreach ( $dates as $key => $value ) {
				$dates[ $key ] = self::convert_timestamp_to_date( $value );
			}
			$subscription->update_dates( $dates );
			Logger::log( 'Updated dates of WC subscription with id ' . $subscription->get_id() . ' by Stripe id: ' . $stripe_subscription_id );
		}
	}

	/**
	 * Put a WC subscription on hold.
	 *
	 * The $resumes_at parameter is not used, as WC Subscriptions seems to have a different concept
	 * of subscription pausing – there's no re-activation date prop and instead a "suspension count" is used.
	 *
	 * @param string $stripe_subscription_id Stripe subscription ID.
	 * @param int    $resumes_at Dates at which to resume the subscription. Currently unused.
	 */
	public static function put_subscription_on_hold( $stripe_subscription_id, $resumes_at ) {
		$subscription = self::get_subscription_by_stripe_subscription_id( $stripe_subscription_id );
		if ( $subscription ) {
			$subscription->set_status( 'on-hold' );
			$subscription->save();
			Logger::log( 'Putting on hold WC subscription with id: ' . $subscription->get_id() . ' by Stripe id: ' . $stripe_subscription_id );
		}
	}

	/**
	 * Is this subscription sync'd with Stripe?
	 *
	 * @param \WC_Subscription|int $subscription The subscription object or post ID.
	 */
	private static function is_synchronised_with_stripe( $subscription ) {
		if ( is_numeric( $subscription ) ) {
			$stripe_subscription_id = get_post_meta( $subscription, self::SUBSCRIPTION_STRIPE_ID_META_KEY, true );
		} else {
			$stripe_subscription_id = $subscription->get_meta( self::SUBSCRIPTION_STRIPE_ID_META_KEY );
		}
		return boolval( $stripe_subscription_id );
	}

	/**
	 * Remove subscription-related meta boxes if the subscription is sync'd with Stripe.
	 * This is because some subscription variables should not be editable here.
	 */
	public static function remove_subscriptions_schedule_meta_box() {
		global $post_ID;
		if ( self::is_synchronised_with_stripe( $post_ID ) ) {
			remove_meta_box( 'woocommerce-subscription-schedule', 'shop_subscription', 'side' );
		}
	}

	/**
	 * Disable editing of subscriptions which are sync'd with Stripe.
	 *
	 * @param bool             $is_editable Whether the subscription is editable.
	 * @param \WC_Subscription $subscription The subscription object.
	 */
	public static function make_syncd_subscriptions_uneditable( $is_editable, $subscription ) {
		if ( self::is_synchronised_with_stripe( $subscription ) ) {
			return false;
		}
		return $is_editable;
	}

	/**
	 * Remove WC Subscriptions order actions for the sync'd subscription.
	 *
	 * @param array $actions An array of available actions.
	 */
	public static function remove_syncd_subscriptions_order_actions( $actions ) {
		global $theorder;
		if ( self::is_synchronised_with_stripe( $theorder ) ) {
			foreach ( $actions as $key => $value ) {
				if ( strpos( $key, 'wcs_' ) === 0 ) {
					unset( $actions[ $key ] );
				}
			}
		}
		return $actions;
	}

	/**
	 * Disable an extra status update (e.g. new-payment-method) for sync'd subscriptions.
	 *
	 * @param bool             $can_update Whether the status can be updated.
	 * @param \WC_Subscription $subscription The subscription object.
	 */
	public static function disable_subscription_status_updates( $can_update, $subscription ) {
		if ( self::is_synchronised_with_stripe( $subscription ) ) {
			return false;
		}
		return $can_update;
	}
}

WooCommerce_Connection::init();
