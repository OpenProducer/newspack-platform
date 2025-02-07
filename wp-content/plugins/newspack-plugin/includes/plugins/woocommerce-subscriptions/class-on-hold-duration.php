<?php
/**
 * WooCommerce Subscriptions On-Hold Duration class.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class On_Hold_Duration {
	/**
	 * The hook name for the manual subscription expiration scheduled action.
	 */
	const AS_HOOK = 'newspack_expire_manual_subscription';

	/**
	 * The group name for the manual subscription expiration scheduled action.
	 */
	const AS_GROUP = 'newspack_expire_manual_subscription';

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		if ( ! WooCommerce_Subscriptions::is_enabled() ) {
			return;
		}

		add_filter( 'woocommerce_subscription_settings', [ __CLASS__, 'add_on_hold_duration_setting' ], 11, 1 );
		add_filter( 'wcs_default_retry_rules', [ __CLASS__, 'maybe_apply_on_hold_duration_rule' ], 99, 1 );
		add_action( 'woocommerce_generated_manual_renewal_order', [ __CLASS__, 'maybe_schedule_expiration' ], 10, 2 );
		add_action( 'woocommerce_subscription_renewal_payment_complete', [ __CLASS__, 'maybe_unschedule_expiration_on_manual_renewal' ], 10, 2 );
		add_action( self::AS_HOOK, [ __CLASS__, 'handle_scheduled_action' ] );
	}

	/**
	 * Add on-hold duration setting.
	 *
	 * @param array $settings Subscription settings.
	 *
	 * @return array
	 */
	public static function add_on_hold_duration_setting( $settings ) {
		if ( WooCommerce_Subscriptions::is_active() ) {
			return array_merge(
				$settings,
				[
					[
						'name' => __( 'Newspack Subscriptions Settings', 'newspack-plugin' ),
						'type' => 'title',
						'desc' => __( 'Subscriptions settings added by Newspack.', 'newspack-plugin' ),
						'id'   => 'newspack_subscriptions_options',
					],
					[
						'name'              => __( 'On-hold Duration', 'newspack-plugin' ),
						'id'                => 'newspack_subscriptions_on_hold_duration',
						'css'               => 'max-width:80px;',
						'value'             => self::get_on_hold_duration(),
						'type'              => 'number',
						'custom_attributes' => array(
							'min'  => 0,
							'step' => 1,
						),
						'desc'              => sprintf(
							// Translators: %s is a line break.
							__(
								'Set the number of days a subscription remains in the On-Hold status after all automatic payment retries have failed.%sDuring this period, subscribers can update their payment details to restore the subscription at their current price. Once this time expires, the subscription will automatically transition to expired.',
								'newspack-plugin'
							),
							'<br>'
						),
					],
					[
						'type' => 'sectionend',
						'id'   => 'newspack_subscriptions_options',
					],
				]
			);
		}
		return $settings;
	}

	/**
	 * Get on-hold duration. Defaults to 30.
	 *
	 * @return int
	 */
	public static function get_on_hold_duration() {
		return absint( get_option( 'newspack_subscriptions_on_hold_duration', 30 ) );
	}

	/**
	 * Conditionally adds on-hold duration rule to retry rules.
	 *
	 * @param array $retry_rules Subscriptions retry rules.
	 */
	public static function maybe_apply_on_hold_duration_rule( $retry_rules ) {
		if ( WooCommerce_Subscriptions::is_active() && count( $retry_rules ) > 0 ) {
			$on_hold_duration = self::get_on_hold_duration();
			if ( 0 < $on_hold_duration ) {
				$retry_rules[] = [
					'retry_after_interval'            => $on_hold_duration * DAY_IN_SECONDS,
					'status_to_apply_to_order'        => 'pending',
					'status_to_apply_to_subscription' => 'on-hold',
				];
			}
			$retry_rules[] = [
				'retry_after_interval'            => 0,
				'status_to_apply_to_order'        => 'failed',
				'status_to_apply_to_subscription' => 'expired',
			];
		}
		return $retry_rules;
	}

	/**
	 * Conditionally schedule expiration action.
	 *
	 * @param int    $order_id     Order ID.
	 * @param object $subscription Subscription object.
	 */
	public static function maybe_schedule_expiration( $order_id, $subscription ) {
		if ( 'on-hold' === $subscription->get_status() ) {
			$default_grace_period = 7 * DAY_IN_SECONDS; // 7 days, the number of days the retry system normally waits before marking a subscription as expired.
			$on_hold_duration     = self::get_on_hold_duration() * DAY_IN_SECONDS;
			$timestamp            = $subscription->get_time( 'next_payment' ) + $default_grace_period + $on_hold_duration;
			as_schedule_single_action( $timestamp, self::AS_HOOK, [ $subscription->get_id() ], self::AS_GROUP );
		}
	}

	/**
	 * Unschedule expiration if scheduled when manual subscription is renewed.
	 *
	 * @param \WC_Subscription $subscription The Subscription.
	 * @param \WC_Order        $order        The order.
	 */
	public static function maybe_unschedule_expiration_on_manual_renewal( $subscription, $order ) {
		if ( false !== as_has_scheduled_action( self::AS_HOOK, [ $subscription->get_id() ], self::AS_GROUP ) ) {
			as_unschedule_action( self::AS_HOOK, [ $subscription->get_id() ], self::AS_GROUP );
		}
	}

	/**
	 * Handle expiration scheduled action.
	 *
	 * @param int $subscription_id Subscription ID.
	 */
	public static function handle_scheduled_action( $subscription_id ) {
		$subscription = wcs_get_subscription( $subscription_id );
		if ( $subscription && 'on-hold' === $subscription->get_status() ) {
			$subscription->update_status( 'expired' );
		}
	}
}
