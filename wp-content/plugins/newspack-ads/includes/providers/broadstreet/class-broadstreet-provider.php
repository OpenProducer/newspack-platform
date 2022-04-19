<?php
/**
 * Newspack Ads Broadstreet Provider.
 *
 * @package Newspack
 */

namespace Newspack_Ads\Providers;

use Newspack_Ads\Providers\Provider;

defined( 'ABSPATH' ) || exit;

/**
 * Broadstreet.
 */
final class Broadstreet_Provider extends Provider {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->provider_id   = 'broadstreet';
		$this->provider_name = 'Broadstreet';
	}

	/**
	 * Whether Broadstreet plugin is installed.
	 *
	 * @return bool Whether Broadstreet plugin is installed.
	 */
	private static function is_plugin_active() {
		return class_exists( '\Broadstreet' ) && class_exists( '\Broadstreet_Utility' );
	}

	/**
	 * Whether the provider is enabled and ready to be used.
	 *
	 * @return bool Whether the provider is enabled and ready to be used.
	 */
	public function is_active() {
		if ( ! self::is_plugin_active() ) {
			return false;
		}
		if ( method_exists( '\Broadstreet_Utility', 'getApiKey' ) && ! \Broadstreet_Utility::getApiKey() ) {
			return false;
		}
		if ( method_exists( '\Broadstreet_Utility', 'getNetworkId' ) && ! \Broadstreet_Utility::getNetworkId() ) {
			return false;
		}
		return true;
	}

	/**
	 * The provider available units for placement.
	 *
	 * @return array[
	 *  'name'  => string,
	 *  'value' => string,
	 *  'sizes' => array[]
	 * ] The provider available units for placement.
	 */
	public function get_units() {
		if ( ! self::is_plugin_active() || ! method_exists( '\Broadstreet_Utility', 'getZoneCache' ) ) {
			return [];
		}
		$zones = \Broadstreet_Utility::getZoneCache();
		return array_map(
			function( $zone ) {
				$unit = [
					'name'  => $zone->name,
					'value' => (string) $zone->id,
					'sizes' => [],
				];
				if ( isset( $zone->width ) && isset( $zone->height ) ) {
					$unit['sizes'][] = [
						$zone->width,
						$zone->height,
					];
				}
				return $unit;
			},
			array_values( $zones )
		);
	}

	/**
	 * The ad code for rendering.
	 *
	 * @param string $unit_id        The unit ID.
	 * @param string $placement_key  Optional placement key.
	 * @param string $hook_key       Optional hook key, if the placement has multiple hooks.
	 * @param array  $placement_data Optional placement data.
	 *
	 * @return string $ad_code The ad code for rendering.
	 */
	public function get_ad_code( $unit_id, $placement_key = '', $hook_key = '', $placement_data = [] ) {
		if ( ! self::is_plugin_active() || ! method_exists( '\Broadstreet_Utility', 'getZoneCode' ) ) {
			return '';
		}
		$attrs = [
			'layout' => 'fixed', // Apply fixed layout for AMP ads.
		];
		return \Broadstreet_Utility::getZoneCode( $unit_id, $attrs );
	}
}
