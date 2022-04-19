<?php
/**
 * Newspack Ads Providers
 *
 * @package Newspack
 */

namespace Newspack_Ads;

use Newspack_Ads\Settings;

use Newspack_Ads\Providers\Provider;
use Newspack_Ads\Providers\GAM_Provider;
use Newspack_Ads\Providers\Broadstreet_Provider;

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Ads Providers
 */
final class Providers {

	/**
	 * Default provider id.
	 */
	const DEFAULT_PROVIDER = 'gam';

	/**
	 * List of registered providers.
	 *
	 * @var Provider[] Associative array of registered providers keyed by their ID.
	 */
	protected static $providers = [];

	/**
	 * Cache of active providers data.
	 *
	 * @var array[] Serialised providers with their units.
	 */
	protected static $active_providers_data = null;

	/**
	 * Initialize providers.
	 */
	public static function init() {
		self::register_provider( new GAM_Provider() );
		self::register_provider( new Broadstreet_Provider() );
		add_action( 'rest_api_init', [ __CLASS__, 'register_api_endpoints' ] );
	}

	/**
	 * Register the endpoints needed to fetch providers data.
	 */
	public static function register_api_endpoints() {
		register_rest_route(
			Settings::API_NAMESPACE,
			'/providers',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_get_providers' ],
				'permission_callback' => [ 'Newspack_Ads\Settings', 'api_permissions_check' ],
			]
		);
	}

	/**
	 * API method to retrieve active providers and its available units.
	 *
	 * @return WP_REST_Response containing the configured placements.
	 */
	public static function api_get_providers() {
		$active_providers = self::get_active_providers();
		$data             = array_map(
			function( $provider ) {
				return array_merge( self::get_serialised_provider( $provider ), [ 'units' => $provider->get_units() ] );
			},
			array_values( $active_providers )
		);
		return rest_ensure_response( $data );
	}

	/**
	 * Register a new provider.
	 *
	 * @param Provider $provider The provider to register.
	 *
	 * @return Provider|WP_Error The registered provider or error.
	 */
	public static function register_provider( Provider $provider ) {
		if ( ! is_subclass_of( $provider, 'Newspack_Ads\Providers\Provider' ) ) {
			return new \WP_Error( 'newspack_ads_invalid_provider', __( 'Invalid provider.', 'newspack-ads' ) );
		}
		self::$providers[ $provider->get_provider_id() ] = $provider;
		return $provider;
	}

	/**
	 * Get registered providers.
	 *
	 * @return Provider[] Associative array of registered providers keyed by their ID.
	 */
	public static function get_providers() {
		return self::$providers;
	}

	/**
	 * Get a serialised provider data.
	 *
	 * @param Provider $provider The provider to serialise.
	 *
	 * @return array Associative array with provider data.
	 */
	public static function get_serialised_provider( Provider $provider ) {
		return [
			'id'     => $provider->get_provider_id(),
			'name'   => $provider->get_provider_name(),
			'active' => $provider->is_active(),
		];
	}

	/**
	 * Get active providers.
	 *
	 * @return Provider[] Associative array of active providers keyed by their ID.
	 */
	public static function get_active_providers() {
		$active_providers = [];
		foreach ( self::get_providers() as $provider_id => $provider ) {
			if ( $provider->is_active() ) {
				$active_providers[ $provider_id ] = $provider;
			}
		}
		return $active_providers;
	}

	/**
	 * Get active providers with their units.
	 *
	 * @return array[] Serialised providers with their units.
	 */
	public static function get_active_providers_data() {
		if ( empty( self::$active_providers_data ) ) {
			$active_providers            = self::get_active_providers();
			self::$active_providers_data = array_map(
				function( Provider $provider ) {
					return self::get_provider_data( $provider->get_provider_id() );
				},
				array_values( $active_providers )
			);
		}
		return self::$active_providers_data;
	}

	/**
	 * Get provider data by its ID.
	 *
	 * @param string $provider_id The provider ID.
	 *
	 * @return array|null Associative array of provider data or null if not found.
	 */
	public static function get_provider_data( $provider_id ) {
		$provider = self::get_provider( $provider_id );
		if ( ! $provider ) {
			return null;
		}
		return array_merge( self::get_serialised_provider( $provider ), [ 'units' => $provider->get_units() ] );  
	}

	/**
	 * Get a provider unit data.
	 *
	 * @param string $provider_id The provider ID.
	 * @param string $unit_value The unit value.
	 *
	 * @return array|null Unit data or null if not found.
	 */
	public static function get_provider_unit_data( $provider_id, $unit_value ) {
		$provider = self::get_provider_data( $provider_id );
		if ( empty( $provider ) ) {
			return null;
		}
		$ad_unit_idx = array_search( $unit_value, array_column( $provider['units'], 'value' ) );
		if ( false === $ad_unit_idx ) {
			return null;
		}
		return $provider['units'][ $ad_unit_idx ];
	}

	/**
	 * Get a provider given its ID.
	 *
	 * @param string $provider_id The provider ID.
	 *
	 * @return Provider|false The provider or false if not found.
	 */
	public static function get_provider( $provider_id ) {
		$providers = self::get_providers();
		if ( ! isset( $providers[ $provider_id ] ) ) {
			return false;
		}
		return self::$providers[ $provider_id ];
	}

	/**
	 * Whether a registered provider is active.
	 *
	 * @param string $provider_id The provider ID.
	 *
	 * @return bool Whether the provider is active.
	 */
	public static function is_provider_active( $provider_id ) {
		$provider = self::get_provider( $provider_id );
		return $provider && $provider->is_active();
	}

	/**
	 * Render the ad code for the given placement.
	 *
	 * @param string $unit_id        The unit ID.
	 * @param string $provider_id    The provider ID.
	 * @param string $placement_key  The placement key.
	 * @param string $hook_key       The hook key, if the placement has multiple hooks.
	 * @param array  $placement_data The placement data.
	 */
	public static function render_placement_ad_code( $unit_id, $provider_id, $placement_key, $hook_key, $placement_data ) {
		$provider_id = isset( $provider_id ) && $provider_id ? $provider_id : self::DEFAULT_PROVIDER;
		$provider    = self::get_provider( $provider_id );
		if ( ! $provider ) {
			return;
		}
		$provider->render_code( $unit_id, $placement_key, $hook_key, $placement_data );
	}
}
Providers::init();
