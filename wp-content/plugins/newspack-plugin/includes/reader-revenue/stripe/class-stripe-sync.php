<?php
/**
 * Stripe Syncs.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Stripe Syncs.
 */
class Stripe_Sync {
	/**
	 * The final results object.
	 *
	 * @var array
	 * @codeCoverageIgnore
	 */
	private static $results = [
		'processed'                                => 0,
		'wc_created'                               => 0,
		'wc_found'                                 => 0,
		'wc_orders_updated'                        => 0,
		'wc_orders_created'                        => 0,
		'wc_stripe_subscription_charges_processed' => 0,
		'wc_stripe_one_time_charges_processed'     => 0,
		'wc_skipped_customers'                     => 0,
	];

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_action( 'init', [ __CLASS__, 'wp_cli' ] );
	}

	/**
	 * Process a Stripe customer.
	 *
	 * @param array $customer Customer object.
	 * @param array $args Arguments.
	 */
	private static function sync_to_wc( $customer, $args ) {
		$email_address = $customer->email;
		$is_dry_run    = false !== $args['dry-run'];

		$all_invoices = Stripe_Connection::get_customer_transactions( $customer->id, false, false, 'invoice' );
		if ( \is_wp_error( $all_invoices ) ) {
			\WP_CLI::warning( __( 'Error fetching customer invoices: ', 'newspack' ) . $all_invoices->get_error_message() );
			return;
		}
		$processed_charges_ids   = [];
		$wc_transaction_payloads = [];
		foreach ( $all_invoices as $invoice ) {
			if ( ! $invoice->charge ) {
				\WP_CLI::warning( __( 'Missing charge ID on invoice ', 'newspack' ) . ' ' . $invoice->id );
				continue;
			}
			$charge = Stripe_Connection::get_charge_by_id( $invoice->charge );
			if ( \is_wp_error( $charge ) ) {
				\WP_CLI::warning( __( 'Error retrieving charge of ', 'newspack' ) . ' ' . $email_address . ': ' . $charge->get_error_message() );
				continue;
			}
			// Construct the payment object expected by the Stripe_Connection::create_wc_transaction_payload method.
			$stripe_payment = [
				'id'      => $charge->id,
				'created' => $charge->created,
			];

			$processed_charges_ids[] = $invoice->charge;

			$stripe_payment['amount']              = $charge->amount;
			$stripe_payment['currency']            = $charge->currency;
			$stripe_payment['balance_transaction'] = $charge->balance_transaction;
			$stripe_payment['frequency']           = Stripe_Connection::get_frequency_from_invoice( $invoice );

			if ( $invoice->subscription ) {
				$stripe_payment['billing_reason'] = $invoice->billing_reason;
				$stripe_payment['subscription']   = $invoice->subscription;
				self::$results['wc_stripe_subscription_charges_processed']++;
			} else {
				self::$results['wc_stripe_one_time_charges_processed']++;
			}
			$wc_transaction_payloads[] = Stripe_Connection::create_wc_transaction_payload( $customer, $stripe_payment );
		}

		$all_charges = Stripe_Connection::get_customer_transactions( $customer->id );
		if ( \is_wp_error( $all_charges ) ) {
			\WP_CLI::warning( __( 'Error fetching customer charges: ', 'newspack' ) . $all_charges->get_error_message() );
			return;
		}
		// Skip charges created by WC and those already processed from invoices.
		$charges = array_filter(
			$all_charges,
			function( $charge ) use ( $processed_charges_ids ) {
				return ! isset( $charge->metadata->order_id ) && ! in_array( $charge->id, $processed_charges_ids );
			}
		);

		if ( empty( $charges ) && empty( $wc_transaction_payloads ) ) {
			self::$results['wc_skipped_customers']++;
			return;
		}

		$wp_user = get_user_by( 'email', $email_address );
		$user_id = false;
		if ( $wp_user ) {
			$user_id = $wp_user->ID;
			self::$results['wc_found']++;
		} else {
			// Create the WC Customer and update past orders (lookup is done by email).
			$full_name  = $customer->name;
			$user_login = \sanitize_title( $full_name );
			if ( username_exists( $user_login ) ) {
				$user_login = $user_login . '-' . uniqid();
			}
			if ( ! $is_dry_run ) {
				$user_id = \wc_create_new_customer( $email_address, $user_login, '', [ 'display_name' => $full_name ] );
				if ( is_wp_error( $user_id ) ) {
					\WP_CLI::warning( __( 'Error processing customer', 'newspack' ) . ' ' . $email_address . ': ' . $user_id->get_error_message() );
					return;
				}
				$linked_orders_count                 = \wc_update_new_customer_past_orders( $user_id );
				self::$results['wc_orders_updated'] += $linked_orders_count;
				// translators: Customer email, linked orders count.
				\WP_CLI::success( sprintf( __( 'Created WC Customer with email: %1$s and linked %2$d order(s) to them.', 'newspack' ), $email_address, $linked_orders_count ) );
			}
			self::$results['wc_created']++;
		}

		foreach ( $charges as $charge ) {
			$wc_transaction_payloads[] = Stripe_Connection::create_wc_transaction_payload( $customer, $charge );
			self::$results['wc_stripe_one_time_charges_processed']++;
		}

		foreach ( $wc_transaction_payloads as $transation_payload ) {
			$charge_id                     = $transation_payload['stripe_id'];
			$transation_payload['user_id'] = $user_id;
			// Find the order associated with this charge.
			$found_order = \WC_Stripe_Helper::get_order_by_charge_id( $charge_id );
			if ( $found_order ) {
				$order_customer_id = $found_order->get_customer_id();
				if ( ! $order_customer_id ) {
					// The order and the customer exist, but the order is not linked to the customer. Link them.
					if ( ! $is_dry_run ) {
						$found_order->set_customer_id( $user_id );
						$found_order->save();
						// translators: Order ID.
						\WP_CLI::success( sprintf( __( 'Updated WC order: %d.', 'newspack' ), $found_order->get_id() ) );
					}
					self::$results['wc_orders_updated'] ++;
				}
			} else {
				// This is a charge without an order. Create a new order.
				if ( ! $is_dry_run ) {
					$order_id = WooCommerce_Connection::create_transaction( $transation_payload );
					// translators: Order ID.
					\WP_CLI::success( sprintf( __( 'Created WC order: %d.', 'newspack' ), $order_id ) );
				}
				self::$results['wc_orders_created'] ++;
			}
		}
	}

	/**
	 * Sync Stripe customers to ESP.
	 *
	 * @param array $customer Customer object.
	 * @param array $args Arguments.
	 */
	private static function sync_to_esp( $customer, $args ) {
		$email_address = $customer->email;
		$is_dry_run    = false !== $args['dry-run'];
		$contact       = [
			'email' => $email_address,
			'name'  => $customer->name,
		];

		$metadata_keys = Newspack_Newsletters::$metadata_keys;
		$metadata      = [
			$metadata_keys['total_paid'] => Stripe_Connection::get_customer_ltv( $customer->id ),
		];

		$last_payments = Stripe_Connection::get_customer_transactions( $customer->id, false, 1 );
		if ( \is_wp_error( $last_payments ) ) {
			\WP_CLI::warning( __( 'Error fetching customer charges: ', 'newspack' ) . $last_payments->get_error_message() );
			return;
		}

		if ( ! empty( $last_payments ) ) {
			$payment           = $last_payments[0];
			$amount_normalised = Stripe_Connection::normalise_amount( $payment['amount'], $payment['currency'] );
			$frequency         = Stripe_Connection::get_frequency_of_payment( $payment );
			$metadata[ $metadata_keys['last_payment_date'] ]   = gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $payment['created'] );
			$metadata[ $metadata_keys['last_payment_amount'] ] = $amount_normalised;
			if ( 'once' !== $frequency ) {
				$metadata[ $metadata_keys['billing_cycle'] ] = $frequency;
			}
			$membership_status                               = Stripe_Connection::get_membership_status_field_value( $frequency );
			$metadata[ $metadata_keys['membership_status'] ] = $membership_status;

			if ( Donations::is_woocommerce_suite_active() ) {
				$wc_product_id = Donations::get_donation_product( $frequency );
				try {
					$metadata[ $metadata_keys['product_name'] ] = \wc_get_product( $wc_product_id )->get_name();
				} catch ( \Throwable $th ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
					// Fail silently.
				}
			}

			$subscription = Stripe_Connection::get_subscription_from_payment( $payment );
			if ( $subscription ) {
				$recurring_related_metadata = Stripe_Connection::create_recurring_payment_metadata(
					$frequency,
					$payment['amount'],
					$payment['currency'],
					$subscription['start_date']
				);
				$metadata                   = array_merge( $recurring_related_metadata, $metadata );
				if ( 'active' !== $subscription['status'] ) {
					$metadata[ $metadata_keys['membership_status'] ] = 'Ex-' . $membership_status;
				}
				if ( $subscription['ended_at'] ) {
					$metadata[ $metadata_keys['sub_end_date'] ] = gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $subscription['ended_at'] );
				} else {
					unset( $metadata[ $metadata_keys['sub_end_date'] ] );
				}
			}
		}

		$wp_user = get_user_by( 'email', $email_address );
		if ( $wp_user ) {
			$metadata[ $metadata_keys['account'] ]           = $wp_user->ID;
			$metadata[ $metadata_keys['registration_date'] ] = date_format( date_create( $wp_user->data->user_registered ), Newspack_Newsletters::METADATA_DATE_FORMAT );
		}

		$contact['metadata'] = $metadata;

		if ( ! $is_dry_run ) {
			// This method is idempotent, so it's safe to call it even if the contact already exists.
			\Newspack_Newsletters_Subscription::add_contact( $contact );
		}
	}

	/**
	 * Fetch Stripe customers.
	 *
	 * @param array  $args Arguments.
	 * @param string $last_id Customer ID.
	 */
	private static function process_all_stripe_customers( $args, $last_id = false ) {
		$stripe = Stripe_Connection::get_stripe_client();
		try {
			$params = [ 'limit' => $args['batch-size'] ];
			if ( $last_id ) {
				$params['starting_after'] = $last_id;
			}
			$response = $stripe->customers->all( $params );

			// translators: Number of customers processed.
			\WP_CLI::log( sprintf( __( 'Processing a batch of %d Stripe customers.', 'newspack' ), count( $response['data'] ) ) );
			foreach ( $response['data'] as $customer ) {
				if ( $args['sync-to-esp'] ) {
					self::sync_to_esp( $customer, $args );
				} elseif ( $args['sync-to-wc'] ) {
					self::sync_to_wc( $customer, $args );
				}
				self::$results['processed']++;
			}

			if ( $response['has_more'] ) {
				$last_id             = $response['data'][ count( $response['data'] ) - 1 ]->id;
				$intermediate_result = self::process_all_stripe_customers( $args, $last_id );
				if ( \is_wp_error( $intermediate_result ) ) {
					\WP_CLI::error( $intermediate_result->get_error_message() );
				}
			}
		} catch ( \Throwable $e ) {
			return new \WP_Error( 'stripe_newspack', __( 'Could not process all customers:', 'newspack' ) . ' ' . $e->getMessage() );
		}
	}

	/**
	 * CLI command for data backfill from Stripe to either WC or an ESP.
	 *
	 * @param array $args Positional args.
	 * @param array $assoc_args Associative args.
	 */
	public static function data_backfill( $args, $assoc_args ) {
		$default_args = [
			'batch-size'  => 10,
			'sync-to-wc'  => true, // Sync data to WooCommerce.
			'sync-to-esp' => false, // Sync data to the ESP.
			'dry-run'     => false,
		];
		$passed_args  = array_merge( $default_args, $assoc_args );
		if ( false !== $passed_args['dry-run'] ) {
			\WP_CLI::warning( __( 'This is a dry run, no changes will be made.', 'newspack' ) );
		}

		if ( $passed_args['sync-to-wc'] ) {
			if ( ! class_exists( 'WC_Stripe_Helper' ) || ! method_exists( 'WC_Stripe_Helper', 'get_order_by_charge_id' ) ) {
				\WP_CLI::error( __( 'WC Stripe Gateway plugin has to be active.', 'newspack' ) );
				return;
			}

			if ( ! function_exists( 'wc_create_new_customer' ) ) {
				\WP_CLI::error( __( 'WooCommerce plugin has to be active.', 'newspack' ) );
				return;
			}
			if ( ! Donations::is_platform_stripe() ) {
				\WP_CLI::error( __( 'Reader Revenue platform has to be set to Stripe.', 'newspack' ) );
				return;
			}
		}

		if ( $passed_args['sync-to-esp'] ) {
			if ( ! method_exists( '\Newspack_Newsletters_Subscription', 'add_contact' ) ) {
				\WP_CLI::error( __( 'Newspack Newsletters has to be active.', 'newspack' ) );
				return;
			}
		}

		$result = self::process_all_stripe_customers( $passed_args );

		if ( \is_wp_error( $result ) ) {
			\WP_CLI::error( $result->get_error_message() );
		}

		// translators: Number of Stripe customers processed.
		\WP_CLI::success( sprintf( __( 'Processed %d Stripe customers.', 'newspack' ), self::$results['processed'] ) );

		if ( $passed_args['sync-to-wc'] ) {
			// translators: Number of customers found.
			\WP_CLI::success( sprintf( __( 'Found %d WC customers linked to Stripe customers.', 'newspack' ), self::$results['wc_found'] ) );
			// translators: Number of customers created.
			\WP_CLI::success( sprintf( __( 'Created %d WC customers from Stripe customers.', 'newspack' ), self::$results['wc_created'] ) );
			// translators: Number of subscription charges processed.
			\WP_CLI::success( sprintf( __( 'Processed %d subscription-related Stripe charges.', 'newspack' ), self::$results['wc_stripe_subscription_charges_processed'] ) );
			// translators: Number of one-time charges processed.
			\WP_CLI::success( sprintf( __( 'Processed %d one-time Stripe charges.', 'newspack' ), self::$results['wc_stripe_one_time_charges_processed'] ) );
			// translators: Number of orders updated.
			\WP_CLI::success( sprintf( __( 'Updated %d WC orders linked to Stripe charges.', 'newspack' ), self::$results['wc_orders_updated'] ) );
			// translators: Number of orders created.
			\WP_CLI::success( sprintf( __( 'Created %d WC orders from Stripe charges.', 'newspack' ), self::$results['wc_orders_created'] ) );
			// translators: Number of Stripe customers skipped.
			\WP_CLI::success( sprintf( __( 'Skipped %d Stripe customers.', 'newspack' ), self::$results['wc_skipped_customers'] ) );
		}
	}

	/**
	 * CLI command for migrating Stripe Subscriptions from Stripe Connect to a regular Stripe account.
	 *
	 * @param array $args Positional args.
	 * @param array $assoc_args Associative args.
	 */
	public static function sync_stripe_connect_to_stripe( $args, $assoc_args ) {
		$is_dry_run     = ! empty( $assoc_args['dry-run'] );
		$force_override = ! empty( $assoc_args['force'] );
		$batch_size     = ! empty( $assoc_args['batch-size'] ) ? intval( $assoc_args['batch-size'] ) : 10;

		$customers = self::get_batch_of_customers_for_stripe_connect_to_stripe( $batch_size );
		while ( $customers ) {
			$customer = array_shift( $customers );

			self::process_customer_for_stripe_connect_to_stripe(
				$customer,
				[
					'dry_run'                     => $is_dry_run,
					'force_subscription_override' => $force_override,
				]
			);

			// Get the next batch.
			if ( empty( $customers ) ) {
				$customers = self::get_batch_of_customers_for_stripe_connect_to_stripe( $batch_size, $customer->id );
			}
		}

		\WP_CLI::success( 'Finished processing.' );
	}

	/**
	 * Get a batch of customers for the Stripe-Connect-to-Stripe CLI tool.
	 *
	 * @param int    $limit Number of customers to fetch.
	 * @param string $last_customer_id Stripe ID of customer to get results after, essentially the offset.
	 * @return array Array of Stripe customers.
	 */
	protected static function get_batch_of_customers_for_stripe_connect_to_stripe( $limit, $last_customer_id = false ) {
		$stripe = Stripe_Connection::get_stripe_client();
		try {
			$params = [
				'limit'  => $limit,
				'expand' => [
					'data.subscriptions',
				],
			];

			if ( $last_customer_id ) {
				$params['starting_after'] = $last_customer_id;
			}

			return $stripe->customers->all( $params )['data'];
		} catch ( \Throwable $e ) {
			\WP_CLI::error( sprintf( 'Could not process all customers: %s', $e->getMessage() ) );
		}
	}

	/**
	 * Process one customer's Stripe subscriptions and migrate them to Newspack Stripe subscriptions.
	 *
	 * @param Stripe_Customer $customer Stripe customer object.
	 * @param array           $args Params to control migration behavior.
	 */
	protected static function process_customer_for_stripe_connect_to_stripe( $customer, $args ) {
		$dry_run                     = ! empty( $args['dry_run'] );
		$force_subscription_override = ! empty( $args['force_subscription_override'] );

		$stripe        = Stripe_Connection::get_stripe_client();
		$stripe_prices = Stripe_Connection::get_donation_prices();

		\WP_CLI::log( sprintf( 'Processing customer: %s', $customer->email ) );
		if ( empty( $customer->subscriptions ) || empty( $customer->subscriptions->data ) ) {
			\WP_CLI::log( '  - No subscriptions found for customer. Skipping. A future version of this tool will handle the case where we want to turn one-time Stripe payments into Stripe subscriptions.' );
			return;
		}

		foreach ( $customer->subscriptions['data'] as $existing_subscription ) {
			\WP_CLI::log( sprintf( '  - Processing subscription: %s', $existing_subscription->id ) );

			// Skip subscription if it's already migrated.
			if ( ! empty( $existing_subscription->metadata['subscription_migrated_to_newspack'] && ! $force_subscription_override ) ) {
				\WP_CLI::log( sprintf( '  - Subscription already migrated to Newspack on %s. Skipping.', $existing_subscription->metadata['subscription_migrated_to_newspack'] ) );
				continue;
			}

			$new_subscription_items = [];

			foreach ( $existing_subscription->items->data as $existing_subscription_item ) {
				// Quantity is used here as a fallback because Simplified Donate Block uses quantity * 1 cent to do a variable price subscription.
				$existing_subscription_item_price = ! empty( $existing_subscription_item->price->unit_amount ) ? $existing_subscription_item->price->unit_amount : $existing_subscription_item->quantity;

				$frequency                = $existing_subscription_item->price->recurring->interval;
				$new_subscription_items[] = [
					'price'    => $stripe_prices[ $frequency ]['id'],
					'quantity' => $existing_subscription_item_price,
				];

				\WP_CLI::log( sprintf( '    * Found subscription item: $%s/%s', $existing_subscription_item_price / 100, $frequency ) );
			}

			if ( $dry_run ) {
				\WP_CLI::log( sprintf( '    * Would have created subscription with next renewal at %s', gmdate( 'Y-m-d', $existing_subscription->current_period_end ) ) );
				return;

			}

			// Create new subscriptions.
			try {
				$subscription = $stripe->subscriptions->create(
					[
						'customer'             => $customer->id,
						'items'                => $new_subscription_items,
						'payment_behavior'     => 'allow_incomplete',
						'billing_cycle_anchor' => $existing_subscription->current_period_end,
						'trial_end'            => $existing_subscription->current_period_end,
						'metadata'             => [
							'subscription_migrated_to_newspack' => gmdate( 'c' ),
						],
					]
				);
				\WP_CLI::log( sprintf( '    * Created subscription: %s with next renewal at %s', $subscription->id, gmdate( 'Y-m-d', $existing_subscription->current_period_end ) ) );
			} catch ( \Throwable $e ) {
				\WP_CLI::error( sprintf( 'Failed to create subscription: %s', $e->getMessage() ) );
			}

			try {
				$stripe->subscriptions->cancel( $existing_subscription->id );
				\WP_CLI::log( sprintf( '    * Cancelled old subscription: %s', $existing_subscription->id ) );
			} catch ( \Throwable $e ) {
				\WP_CLI::error( sprintf( 'Failed to cancel subscription: %s', $e->getMessage() ) );
			}
		}
	}

	/**
	 * Add CLI commands.
	 */
	public static function wp_cli() {
		if ( ! defined( 'WP_CLI' ) ) {
			return;
		}

		\WP_CLI::add_command(
			'newspack stripe sync-customers',
			[ __CLASS__, 'data_backfill' ],
			[
				'shortdesc' => __( 'Backfill WC Customers from Stripe database.', 'newspack' ),
			]
		);

		\WP_CLI::add_command(
			'newspack stripe sync-stripe-connect-to-stripe',
			[ __CLASS__, 'sync_stripe_connect_to_stripe' ],
			[
				'shortdesc' => __( 'Migrate customers from Stripe Connect to Stripe', 'newspack' ),
				'synopsis'  => [
					[
						'type'     => 'flag',
						'name'     => 'dry-run',
						'optional' => true,
					],
					[
						'type'     => 'flag',
						'name'     => 'force',
						'optional' => true,
					],
					[
						'type'     => 'flag',
						'name'     => 'batch-size',
						'default'  => 10,
						'optional' => true,
					],
				],
			]
		);
	}
}

Stripe_Sync::init();

