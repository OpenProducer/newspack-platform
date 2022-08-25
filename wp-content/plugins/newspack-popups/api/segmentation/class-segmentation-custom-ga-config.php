<?php
/**
 * Newspack Campaigns custom GA config.
 *
 * @package Newspack
 */

/**
 * Extend the base Lightweight_API class.
 */
require_once dirname( __FILE__ ) . '/../classes/class-lightweight-api.php';
require_once dirname( __FILE__ ) . '/../segmentation/class-segmentation.php';
require_once dirname( __FILE__ ) . '/../campaigns/class-campaign-data-utils.php';

/**
 * GET endpoint to create custom GA Config for AMP Analytics.
 */
class Segmentation_Custom_GA_Config extends Lightweight_API {
	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		parent::__construct();
		$this->response = $this->get_custom_analytics_configuration( $_REQUEST ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$this->respond();
	}

	/**
	 * Get custom Analytics config, with segmentation-related custom dimensions assigned.
	 * The pageviews will be reported using this configuration, so it's important
	 * to include the custom dimensions set up by the Newspack Plugin, too.
	 *
	 * @param Request $request Request object.
	 */
	public function get_custom_analytics_configuration( $request ) {
		$client_id   = $request['client_id'];
		$ga_settings = maybe_unserialize( $this->get_option( 'googlesitekit_analytics_settings' ) );
		if ( ! $client_id || ! $ga_settings || ! isset( $ga_settings['propertyID'] ) ) {
			return [];
		}

		$custom_dimensions = json_decode( $request['custom_dimensions'] );

		// Tracking ID from Site Kit.
		$gtag_id = $ga_settings['propertyID'];

		$custom_dimensions_values = [];

		$api                 = new Lightweight_API();
		$subscription_events = $api->get_reader_events( $client_id, 'subscription' );
		$donation_events     = $api->get_reader_events( $client_id, 'donation' );

		foreach ( $custom_dimensions as $custom_dimension ) {
			// Strip the `ga:` prefix from gaID.
			$dimension_id = substr( $custom_dimension->gaID, 3 ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			switch ( $custom_dimension->role ) {
				case Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_READER_FREQUENCY:
					$read_count = Campaign_Data_Utils::get_post_view_count( $api->get_reader( $client_id ) );
					// Tiers mimick NCI's – https://news-consumer-insights.appspot.com.
					$read_count_tier = 'casual';
					if ( $read_count > 1 && $read_count <= 14 ) {
						$read_count_tier = 'loyal';
					} elseif ( $read_count > 14 ) {
						$read_count_tier = 'brand_lover';
					}
					$custom_dimensions_values[ $dimension_id ] = $read_count_tier;
					break;
				case Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_IS_SUBSCRIBER:
					$custom_dimensions_values[ $dimension_id ] = Campaign_Data_Utils::is_subscriber( $subscription_events ) ? 'true' : 'false';
					break;
				case Segmentation::CUSTOM_DIMENSIONS_OPTION_NAME_IS_DONOR:
					$custom_dimensions_values[ $dimension_id ] = Campaign_Data_Utils::is_donor( $donation_events ) ? 'true' : 'false';
					break;
			}
		}

		$custom_dimensions_existing_values = (array) json_decode( $request['custom_dimensions_existing_values'] );

		// This is an AMP Analytics-compliant configuration, which on non-AMP pages will be
		// processed by this plugin's amp-analytics polyfill (src/view).
		return [
			'vars'            => [
				'gtag_id' => $gtag_id,
				'config'  => [
					$gtag_id => array_merge(
						[
							'groups' => 'default',
						],
						$custom_dimensions_values,
						$custom_dimensions_existing_values
					),
				],
			],
			'optoutElementId' => '__gaOptOutExtension',
		];
	}
}
new Segmentation_Custom_GA_Config();
