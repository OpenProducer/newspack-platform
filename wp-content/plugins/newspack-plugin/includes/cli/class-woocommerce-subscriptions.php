<?php
/**
 * WooCommerce Subscriptions Integration CLI commands.
 *
 * @package Newspack
 */

namespace Newspack\CLI;

use WP_CLI;
use Newspack\Woocommerce_Subscriptions as WooCommerce_Subscriptions_Integration;
use Newspack\On_Hold_Duration;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Subscriptions Integration CLI commands.
 */
class WooCommerce_Subscriptions {
	/**
	 * Flag for live mode.
	 *
	 * @var bool
	 */
	private static $live = false;

	/**
	 * Flag for verbose output.
	 *
	 * @var bool
	 */
	private static $verbose = false;

	/**
	 * Subscription ids to process.
	 *
	 * @var bool|array
	 */
	private static $ids = false;

	/**
	 * Migrate status of on-hold WooCommerce subscriptions that have failed all payment retries to expired.
	 *
	 * ## OPTIONS
	 *
	 * [--live]
	 * : Run the command in live mode, updating the subscriptions.
	 *
	 * [--verbose]
	 * : Produce more output.
	 *
	 * [--ids]
	 * : Comma-separated list of subscription IDs. If provided, only ubscriptions with these IDs will be processed.
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Assoc arguments.
	 *
	 * @return void
	 */
	public function migrate_expired_subscriptions( $args, $assoc_args ) {
		WP_CLI::line( '' );
		if ( ! WooCommerce_Subscriptions_Integration::is_enabled() ) {
			WP_CLI::error( 'WooCommerce Subscriptions Integration is not enabled.' );
			WP_CLI::line( '' );
			return;
		}
		self::$ids     = isset( $assoc_args['ids'] ) ? explode( ',', $assoc_args['ids'] ) : false;
		self::$live    = isset( $assoc_args['live'] ) ? true : false;
		self::$verbose = isset( $assoc_args['verbose'] ) ? true : false;
		$scheduled     = 0;
		$updated       = 0;
		$page          = 1;
		$per_page      = 25;
		$subscriptions = self::get_subscriptions( $page );
		if ( empty( $subscriptions ) ) {
			WP_CLI::success( 'No on-hold subscriptions to process.' );
			WP_CLI::line( '' );
			return;
		}
		WP_CLI::line( 'Processing subscriptions in ' . ( self::$live ? 'live' : 'dry run' ) . ' mode...' );
		WP_CLI::line( '' );
		while ( ! empty( $subscriptions ) ) {
			foreach ( $subscriptions as $subscription ) {
				$id = $subscription->get_id();
				if ( self::$verbose ) {
					WP_CLI::line( 'Processing subscription ' . $id . '...' );
				}
				// A pending retry indicates the subscription is awaiting payment retry.
				if ( $subscription->get_date( 'payment_retry' ) > 0 ) {
					if ( self::$verbose ) {
						WP_CLI::line( 'Subscription is awaiting payment retry. Moving to next subscription...' );
						WP_CLI::line( '' );
					}
					continue;
				}
				$renewal_order = $subscription->get_last_order(
					'all',
					[ 'renewal' ],
					[
						'completed',
						'processing',
						'refunded',
					]
				);
				// No failed or pending renewal orders indicates the subscription was likely manually placed on hold.
				if ( empty( $renewal_order ) ) {
					if ( self::$verbose ) {
						WP_CLI::line( 'Subscription has no pending renewal orders. Moving to next subscription...' );
						WP_CLI::line( '' );
					}
					continue;
				}
				$last_retry = \WCS_Retry_Manager::store()->get_last_retry_for_order( wcs_get_objects_property( $renewal_order, 'id' ) );
				// No retries indicates the subscription was likely manually placed on hold.
				if ( empty( $last_retry ) ) {
					if ( self::$verbose ) {
						WP_CLI::line( 'No retries scheduled. Moving to next subscription...' );
						WP_CLI::line( '' );
					}
					continue;
				}
				// A non failed status indicates the retry was either manually cancelled
				// or was successful at one point but likely placed on hold for some other reason.
				if ( 'failed' !== $last_retry->get_status() ) {
					if ( self::$verbose ) {
						WP_CLI::line( 'Last retry does not have a failed status. Moving to next subscription...' );
						WP_CLI::line( '' );
					}
					continue;
				} else {
					if ( $subscription->is_manual() || ! $subscription->payment_method_supports( 'subscription_date_changes' ) ) {
						if ( self::$verbose ) {
							WP_CLI::line( 'Subscription does not support retries. Moving to next subscription...' );
							WP_CLI::line( '' );
						}
						continue;
					}
					$retry_date       = $last_retry->get_date();
					$on_hold_duration = On_Hold_Duration::get_on_hold_duration();
					// If the retry date is within the on-hold duration, schedule a final retry.
					if ( wcs_date_to_time( $retry_date ) + ( $on_hold_duration * DAY_IN_SECONDS ) > time() ) {
						if ( self::$verbose ) {
							WP_CLI::line( 'Retry date is within the on-hold duration. Scheduling final retry...' );
						}
						if ( self::$live ) {
							// Retry rules can only be applied when payment attempt flag is set.
							add_filter( 'wcs_is_scheduled_payment_attempt', '__return_true' );
							\WCS_Retry_Manager::maybe_apply_retry_rule( $subscription, $renewal_order );
							remove_filter( 'wcs_is_scheduled_payment_attempt', '__return_true' );
							if ( 0 === $subscription->get_date( 'payment_retry' ) ) {
								if ( self::$verbose ) {
									WP_CLI::error( 'Failed to schedule payment retry. Moving to next subscription...' );
									WP_CLI::line( '' );
								}
								continue;
							} else {
								$subscription->add_order_note(
									__( 'Final payment retry scheduled by Newspack CLI command.', 'newspack-plugin' )
								);
								$subscription->update_meta_data( '_newspack_cli_retry_scheduled', true );
								$subscription->save();
							}
						}
						++$scheduled;
					} else {
						// Otherwise, if the retry date is past the on-hold duration, update the subscription status to expired.
						if ( self::$verbose ) {
							WP_CLI::line( 'Updating subscription status to expired...' );
						}
						if ( self::$live ) {
							$subscription->update_status( 'expired', __( 'Subscription status updated by Newspack CLI command.', 'newspack-plugin' ) );
							$subscription->set_end_date( $retry_date );
							$subscription->update_meta_data( '_newspack_cli_status_updated', true );
							$subscription->save();
						}
						++$updated;
					}
				}
				if ( self::$verbose ) {
					WP_CLI::line( 'Finished processing subscription ' . $id );
					WP_CLI::line( '' );
				}
			}
			$subscriptions = self::get_subscriptions( ++$page );
		}
		WP_CLI::success( 'Finished processing subscriptions. ' . $updated . ' subscriptions updated. ' . $scheduled . ' retries scheduled.' );
		if ( ! self::$live ) {
			WP_CLI::warning( 'Dry run. Use --live flag to process live subscriptions.' );
		}
		WP_CLI::line( '' );
	}

	/**
	 * Get subscriptions to process.
	 *
	 * @param int $page Page number.
	 *
	 * @return array
	 */
	private static function get_subscriptions( $page = 1 ) {
		$subscriptions = [];
		if ( false !== self::$ids ) {
			while ( ! empty( self::$ids ) ) {
				$id = array_shift( self::$ids );
				if ( ! is_numeric( $id ) ) {
					continue;
				}
				$subscription = wcs_get_subscription( $id );
				if ( $subscription ) {
					$subscriptions[] = $subscription;
				}
			}
		} else {
			$subscriptions = wcs_get_subscriptions(
				[
					'paged'                  => $page,
					'subscriptions_per_page' => 25,
					'subscription_status'    => 'on-hold',
				]
			);
		}
		return $subscriptions;
	}
}
