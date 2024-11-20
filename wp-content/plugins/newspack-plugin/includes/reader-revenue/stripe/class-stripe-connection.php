<?php
/**
 * All things Stripe.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * All things Stripe.
 */
class Stripe_Connection {
	/**
	 * Is a value not empty?
	 *
	 * @param mixed $value Value to check.
	 */
	private static function is_value_non_empty( $value ) {
		if ( 'boolean' === gettype( $value ) ) {
			return true;
		} else {
			return ! empty( $value );
		}
	}

	/**
	 * Get Stripe data.
	 */
	public static function get_stripe_data() {
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
		$stripe_data              = $wc_configuration_manager->stripe_data();
		if ( ! $stripe_data ) {
			return $stripe_data;
		}

		$location_code = 'US';
		$currency      = 'USD';

		$wc_country = get_option( 'woocommerce_default_country', false );
		if ( $wc_country ) {
			// Remove region code.
			$wc_country = explode( ':', $wc_country )[0];
		}

		$valid_country_codes = wp_list_pluck( newspack_get_countries(), 'value' );
		if ( $wc_country && in_array( $wc_country, $valid_country_codes ) ) {
			$location_code = $wc_country;
		}
		$wc_currency      = get_option( 'woocommerce_currency', false );
		$valid_currencies = wp_list_pluck( newspack_get_currencies_options(), 'value' );
		if ( $wc_currency && in_array( $wc_currency, $valid_currencies ) ) {
			$currency = $wc_currency;
		}
		$stripe_data = array_merge(
			$stripe_data,
			[
				'currency'                    => $currency,
				'location_code'               => $location_code,
				'newsletter_list_id'          => '',
				'allow_covering_fees'         => get_option( 'newspack_donations_allow_covering_fees', true ),
				'allow_covering_fees_default' => get_option( 'newspack_donations_allow_covering_fees_default', false ),
				'allow_covering_fees_label'   => get_option( 'newspack_donations_allow_covering_fees_label', '' ),
			]
		);

		// Read data from the legacy option.
		$legacy_stripe_data = array_filter(
			get_option( 'newspack_stripe_data', [] ),
			[ __CLASS__, 'is_value_non_empty' ]
		);
		$stripe_data        = array_merge( $legacy_stripe_data, $stripe_data );

		if ( isset( $stripe_data['testMode'] ) ) {
			$stripe_data['usedPublishableKey'] = $stripe_data['testMode'] ? $stripe_data['testPublishableKey'] : $stripe_data['publishableKey'];
			$stripe_data['usedSecretKey']      = $stripe_data['testMode'] ? $stripe_data['testSecretKey'] : $stripe_data['secretKey'];
		} else {
			$stripe_data['usedPublishableKey'] = '';
			$stripe_data['usedSecretKey']      = '';
		}
		$stripe_data['fee_multiplier'] = get_option( 'newspack_blocks_donate_fee_multiplier', '2.9' );
		$stripe_data['fee_static']     = get_option( 'newspack_blocks_donate_fee_static', '0.3' );
		return $stripe_data;
	}

	/**
	 * Update Stripe data.
	 *
	 * @param object $updated_stripe_data Updated Stripe data to be saved.
	 */
	public static function update_stripe_data( $updated_stripe_data ) {
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
		$wc_configuration_manager->update_wc_stripe_settings( $updated_stripe_data );

		if ( isset( $updated_stripe_data['currency'] ) ) {
			update_option( 'woocommerce_currency', $updated_stripe_data['currency'] );
		}
		if ( isset( $updated_stripe_data['location_code'] ) ) {
			update_option( 'woocommerce_default_country', $updated_stripe_data['location_code'] );
		}
		if ( isset( $updated_stripe_data['fee_multiplier'] ) ) {
			update_option( 'newspack_blocks_donate_fee_multiplier', $updated_stripe_data['fee_multiplier'] );
		}
		if ( isset( $updated_stripe_data['fee_static'] ) ) {
			update_option( 'newspack_blocks_donate_fee_static', $updated_stripe_data['fee_static'] );
		}
	}
}
