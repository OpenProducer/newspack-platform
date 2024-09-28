<?php
/**
 * CLI tools for the woocommerce support
 *
 * @package Newspack
 */

namespace Newspack;

use WP_CLI;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Order UTM class.
 */
class WooCommerce_Cli {

	/**
	 * Lists the subscriptions that needs to be fixed by the fix_subscriptions_missing_fee command.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Accepted values: table, csv, json, count, yaml. Default: table
	 *
	 * @param array $args Args.
	 * @param array $assoc_args Assoc args.
	 */
	public function list_subscriptions_missing_fee( $args, $assoc_args ) {

		$format = isset( $assoc_args['format'] ) ? $assoc_args['format'] : 'table';

		$subscriptions = $this->get_all_old_subscriptions();

		$subscriptions = array_filter(
			$subscriptions,
			function( $subscription ) {
				return empty( $subscription->get_fees() );
			}
		);

		if ( empty( $subscriptions ) ) {
			WP_CLI::success( 'No subscriptions missing fees found.' );
			return;
		}

		WP_CLI::success( 'Subscriptions missing fees:' );
		$this->output_subscriptions( $subscriptions, $format );
	}

	/**
	 * Lists the subscriptions that were already fixed by the fix_subscriptions_missing_fee command.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Accepted values: table, csv, json, count, yaml. Default: table
	 *
	 * @param array $args Args.
	 * @param array $assoc_args Assoc args.
	 */
	public function list_subscriptions_missing_fee_fixed( $args, $assoc_args ) {

		$format = isset( $assoc_args['format'] ) ? $assoc_args['format'] : 'table';

		$subscriptions = $this->get_all_old_subscriptions();

		$subscriptions = array_filter(
			$subscriptions,
			function( $subscription ) {
				return ! empty( $subscription->get_fees() );
			}
		);

		if ( empty( $subscriptions ) ) {
			WP_CLI::success( 'No subscriptions missing fees found.' );
			return;
		}

		WP_CLI::success( 'Fixed subscriptions that had missing fees:' );
		$this->output_subscriptions( $subscriptions, $format );
	}

	/**
	 * Updates the subscriptions that had the Stripe cover fee option added in the old way.
	 *
	 * Will look for all subcriptions that had fees added in the old way and fix them.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : If set, no changes will be made.
	 *
	 * @param [type] $args Args.
	 * @param [type] $assoc_args Assoc args.
	 */
	public function fix_subscriptions_missing_fee( $args, $assoc_args ) {

		$dry_run = isset( $assoc_args['dry-run'] ) && $assoc_args['dry-run'];

		if ( ! $dry_run ) {
			WP_CLI::line( 'This command will modify the database.' );
			WP_CLI::line( 'Consider running it with --dry-run first to see what it will do.' );
			WP_CLI::confirm( 'Are you sure you want to continue?', $assoc_args );
		}

		$subscriptions = $this->get_all_old_subscriptions();

		$subscriptions = array_filter(
			$subscriptions,
			function( $subscription ) {
				return empty( $subscription->get_fees() );
			}
		);

		if ( empty( $subscriptions ) ) {
			WP_CLI::success( 'No subscriptions missing fees found.' );
			return;
		}

		foreach ( $subscriptions as $subscription ) {

			WP_CLI::success( 'Fixing subscription #' . $subscription->get_id() );
			WP_CLI::log( 'Subscription total is ' . $subscription->get_total() );

			$fee_value = WooCommerce_Cover_Fees::get_fee_value( $subscription->get_total() );
			WP_CLI::log( 'Fee value will be: ' . $fee_value );

			$fee_display_value = WooCommerce_Cover_Fees::get_fee_display_value( $subscription->get_total() );
			WP_CLI::log( 'Fee display value will be: ' . $fee_display_value );

			$new_total = WooCommerce_Cover_Fees::get_total_with_fee( $subscription->get_total() );
			WP_CLI::log( 'Subscription new total will be: ' . $new_total );

			if ( $dry_run ) {
				WP_CLI::warning( 'Dry run, not saving.' );
				continue;
			}

			$fee_name = sprintf(
				// Translators: %s is the fee percentage.
				__( 'Transaction fee (%s)', 'newspack-plugin' ),
				$fee_display_value
			);

			$fee = (object) [
				'name'     => $fee_name,
				'amount'   => $fee_value,
				'tax'      => '',
				'taxable'  => false,
				'tax_data' => '',
			];

			$subscription->add_fee( $fee );
			$subscription->add_order_note( 'Subscription fee fixed and added via script' );
			update_post_meta( $subscription->get_id(), '_newspack_fixed_subscription_fees', 1 );
			$subscription->calculate_totals( false );

			WP_CLI::success( 'Subscription #' . $subscription->get_id() . ' fixed.' );
			WP_CLI::log( '' );
		}
	}

	/**
	 * Outputs a list of subscription in CLI
	 *
	 * @param WC_Subscription[] $subscriptions The subscriptions.
	 * @param string            $format The output format.
	 * @return void
	 */
	private function output_subscriptions( $subscriptions, $format = 'table' ) {
		$subscriptions = array_map(
			function( $subscription ) {
				$user  = $subscription->get_user();
				$email = $user instanceof \WP_User ? $user->user_email : 'guest';
				return [
					'id'           => $subscription->get_id(),
					'date_created' => $subscription->get_date_created()->__toString(),
					'amount'       => $subscription->get_total(),
					'user_email'   => $email,
				];
			},
			$subscriptions
		);

		WP_CLI\Utils\format_items( $format, $subscriptions, [ 'id', 'amount', 'user_email', 'date_created' ] );
		WP_CLI::log( count( $subscriptions ) . ' subscriptions found.' );
	}

	/**
	 * Get all subscriptions that had the Stripe cover fee option added in the old way.
	 *
	 * We look at the order notes, and not the subscription meta, because there was a bug where the meta was not stored sometimes.
	 *
	 * @return ?WP_Subscription[] The subscriptions.
	 */
	private function get_all_old_subscriptions() {
		global $wpdb;

		// phpcs:ignore
		$parent_order_ids = $wpdb->get_col(
			"SELECT comment_post_ID FROM {$wpdb->comments} WHERE comment_content LIKE '%transaction fee. The total amount will be updated.'"
		);

		$subscriptions = [];
		$ids           = [];

		foreach ( $parent_order_ids as $parent_order_id ) {
			$subs = wcs_get_subscriptions_for_order( $parent_order_id );
			if ( is_array( $subs ) && ! empty( $subs ) ) {
				$sub = array_shift( $subs );
				if ( ! in_array( $sub->get_id(), $ids, true ) ) {
					$subscriptions[] = $sub;
					$ids[]           = $sub->get_id();
				}
			}
		}

		return $subscriptions;
	}
}
