<?php
/**
 * Publish to Apple News: \Apple_Exporter\Builders\Advertising_Settings class
 *
 * @package Apple_News
 * @subpackage Apple_Exporter\Builders
 */

namespace Apple_Exporter\Builders;

/**
 * Builds advertising settings for the JSON export.
 *
 * @since 0.4.0
 */
class Advertising_Settings extends Builder {

	/**
	 * Build the component.
	 *
	 * @access protected
	 */
	protected function build() {
		$advertising_settings = [];

		// Get advertising settings from the theme.
		$theme                = \Apple_Exporter\Theme::get_used();
		$enable_advertisement = $theme->get_value( 'enable_advertisement' );
		$ad_frequency         = intval( $theme->get_value( 'ad_frequency' ) );

		if ( 'yes' === $enable_advertisement && $ad_frequency > 0 ) {

			// Build basic advertisement configuration settings.
			$advertising_settings = [
				'bannerType'        => 'any',
				'distanceFromMedia' => '10vh',
				'enabled'           => true,
				'frequency'         => $ad_frequency,
			];

			// Add the ad margin, if defined.
			$ad_margin = intval( $theme->get_value( 'ad_margin' ) );
			if ( ! empty( $ad_margin ) ) {
				$advertising_settings['layout'] = [
					'margin' => $ad_margin,
				];
			}
		}

		/**
		 * Filters the advertisement settings.
		 *
		 * @since 0.4.0
		 *
		 * @param array $advertising_settings The advertising settings to be modified.
		 */
		$advertising_settings = apply_filters( 'apple_news_advertising_settings', $advertising_settings, $this->content_id() );

		// If there are no advertising settings, bail out.
		if ( empty( $advertising_settings ) ) {
			return [];
		}

		// Wrap the advertising settings in the advertisement key.
		return [
			'advertisement' => $advertising_settings,
		];
	}
}
