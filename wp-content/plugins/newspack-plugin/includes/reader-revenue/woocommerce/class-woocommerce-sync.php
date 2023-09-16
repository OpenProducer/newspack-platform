<?php
/**
 * WP CLI scripts for managing WooCommerce Reader Revenue data.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Stripe Syncs.
 */
class WooCommerce_Sync {
	// User roles that a customer can have.
	const CUSTOMER_ROLES = [ 'customer', 'subscriber' ];

	/**
	 * The final results object.
	 *
	 * @var array
	 * @codeCoverageIgnore
	 */
	private static $results = [
		'processed' => 0,
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
		 * Add CLI commands.
		 */
	public static function wp_cli() {
		if ( ! defined( 'WP_CLI' ) ) {
			return;
		}

		\WP_CLI::add_command(
			'newspack woo resync',
			[ __CLASS__, 'resync_woo_contacts' ],
			[
				'shortdesc' => __( 'Resync customer and transaction data to the connected ESP.', 'newspack' ),
				'synopsis'  => [
					[
						'type'     => 'flag',
						'name'     => 'dry-run',
						'optional' => true,
					],
					[
						'type'     => 'flag',
						'name'     => 'migrated-subscriptions',
						'optional' => true,
					],
					[
						'type'     => 'flag',
						'name'     => 'subscription-ids',
						'default'  => false,
						'optional' => true,
					],
					[
						'type'     => 'flag',
						'name'     => 'user-ids',
						'default'  => false,
						'optional' => true,
					],
					[
						'type'     => 'flag',
						'name'     => 'order-ids',
						'default'  => false,
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

	/**
	 * CLI command for resyncing contact data from WooCommerce customers to the connected ESP.
	 *
	 * @param array $args Positional args.
	 * @param array $assoc_args Associative args.
	 */
	public static function resync_woo_contacts( $args, $assoc_args ) {
		$is_dry_run       = ! empty( $assoc_args['dry-run'] );
		$migrated_only    = ! empty( $assoc_args['migrated-subscriptions'] );
		$subscription_ids = ! empty( $assoc_args['subscription-ids'] ) ? explode( ',', $assoc_args['subscription-ids'] ) : false;
		$user_ids         = ! empty( $assoc_args['user-ids'] ) ? explode( ',', $assoc_args['user-ids'] ) : false;
		$order_ids        = ! empty( $assoc_args['order-ids'] ) ? explode( ',', $assoc_args['order-ids'] ) : false;
		$batch_size       = ! empty( $assoc_args['batch-size'] ) ? intval( $assoc_args['batch-size'] ) : 10;

		\WP_CLI::log(
			'

Running WooCommerce-to-ESP contact resync...

		'
		);

		// If resyncing only migrated subscriptions.
		if ( $migrated_only ) {
			$subscription_ids = Stripe_Sync::get_migrated_subscriptions( $batch_size );
			$batches          = 0;
		}

		if ( ! empty( $subscription_ids ) ) {
			\WP_CLI::log( __( 'Migrating by subscription ID...', 'newspack' ) );

			while ( ! empty( $subscription_ids ) ) {
				$subscription_id = array_shift( $subscription_ids );
				$subscription    = new \WC_Subscription( $subscription_id );

				if ( \is_wp_error( $subscription ) ) {
					\WP_CLI::log(
						sprintf(
							// Translators: %d is the subscription ID arg passed to the script.
							__( 'No subscription with ID %d. Skipping.', 'newspack' ),
							$subscription_id
						)
					);

					continue;
				}

				self::resync_contact( $subscription->get_customer_id(), $is_dry_run );

				// Get the next batch.
				if ( $migrated_only && empty( $subscription_ids ) ) {
					$batches ++;
					$subscription_ids = Stripe_Sync::get_migrated_subscriptions( $batch_size, $batches * $batch_size );
				}
			}
		}

		// If user-ids flag is passed, resync those users.
		if ( ! empty( $user_ids ) ) {
			\WP_CLI::log( __( 'Migrating by customers user ID...', 'newspack' ) );
			foreach ( $user_ids as $user_id ) {
				self::resync_contact( $user_id, $is_dry_run );
			}
		}

		// If order-ids flag is passed, resync contacts for those orders.
		if ( ! empty( $order_ids ) ) {
			\WP_CLI::log( __( 'Migrating by order ID...', 'newspack' ) );
			foreach ( $order_ids as $order_id ) {
				$order = new \WC_Order( $order_id );

				if ( \is_wp_error( $order ) ) {
					\WP_CLI::log(
						sprintf(
							// Translators: %d is the order ID arg passed to the script.
							__( 'No order with ID %d. Skipping.', 'newspack' ),
							$order_id
						)
					);

					continue;
				}
				
				self::resync_contact( $order->get_customer_id(), $is_dry_run );
			}
		}

		// Default behavior: resync all customers and subscribers.
		if ( empty( $user_ids ) && empty( $order_ids ) && empty( $subscription_ids ) && ! $migrated_only ) {
			\WP_CLI::log( __( 'Migrating all customers...', 'newspack' ) );
			$user_ids = self::get_batch_of_customers( $batch_size );
			$batches  = 0;

			while ( $user_ids ) {
				$user_id = array_shift( $user_ids );

				self::resync_contact( $user_id, $is_dry_run );

				// Get the next batch.
				if ( empty( $user_ids ) ) {
					$batches ++;
					$user_ids = self::get_batch_of_customers( $batch_size, $batches * $batch_size );
				}
			}
		}

		\WP_CLI::success(
			sprintf(
				// Translators: total number of resynced contacts.
				__(
					'

Done! Resynced %d contacts.

		',
					'newspack'
				),
				self::$results['processed']
			)
		);
	}

	/**
	 * Given a WP user ID for a Woo customer, resync that customer's contact data in the connected ESP.
	 * 
	 * @param int  $user_id WP user ID for the customer.
	 * @param bool $is_dry_run True if a dry run.
	 */
	public static function resync_contact( $user_id, $is_dry_run = false ) {
		$result = false;
		$user   = \get_userdata( $user_id );

		if ( ! $user || empty( array_intersect( $user->roles, self::CUSTOMER_ROLES ) ) ) {
			\WP_CLI::log(
				sprintf(
				// Translators: %d is the user ID arg passed to the script.
					__( 'No customer user with ID %d. Skipping.', 'newspack' ),
					$user_id
				)
			);

			return $result;
		}

		$customer = new \WC_Customer( $user_id );
		if ( ! $customer->get_id() ) {
			\WP_CLI::log(
				sprintf(
				// Translators: %d is the user ID arg passed to the script.
					__( 'Customer with ID %d does not exist. Skipping.', 'newspack' ),
					$user_id
				)
			);

			return $result;
		}

		if ( ! $customer->get_order_count() ) {
			$contact = WooCommerce_Connection::get_contact_from_customer( $customer );
			$result  = ! $is_dry_run ? \Newspack_Newsletters_Subscription::add_contact( $contact ) : true;
		} else {
			$result = ! $is_dry_run ? WooCommerce_Connection::sync_reader_from_order( $customer->get_last_order(), false ) : true;
		}

		if ( $result && ! \is_wp_error( $result ) ) {
			\WP_CLI::log(
				sprintf(
					// Translators: %1$s is the resync status and %2$s is the contact's email address.
					__( '%1$s contact data for %2$s.', 'newspack' ),
					$is_dry_run ? __( 'Would resync', 'newspack' ) : __( 'Resynced', 'newspack' ),
					$customer->get_email()
				)
			);
			self::$results['processed'] ++;
		}

		if ( \is_wp_error( $result ) ) {
			\WP_CLI::error(
				sprintf(
					// Translators: $1$s is the contact's email address. %2$s is the error message.
					__( 'Error resyncing contact info for %1$s. %2$s' ),
					$customer->get_email(),
					$result->get_error_message()
				)
			);
		}

		return $result;
	}

	/**
	 * Get a batch of customer IDs.
	 * 
	 * @param int $batch_size Number of customers to get.
	 * @param int $offset     Number to skip.
	 * 
	 * @return array|false Array of customer IDs, or false if no more to fetch.
	 */
	public static function get_batch_of_customers( $batch_size, $offset = 0 ) {
		$query = new \WP_User_Query(
			[
				'fields'   => 'ID',
				'number'   => $batch_size,
				'offset'   => $offset,
				'order'    => 'DESC',
				'orderby'  => 'registered',
				'role__in' => self::CUSTOMER_ROLES,
			]
		);

		$results = $query->get_results();

		return ! empty( $results ) ? $results : false;
	}
}
WooCommerce_Sync::init();
