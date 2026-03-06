<?php
/**
 * Integrations management class
 *
 * @package Newspack
 */

namespace Newspack\Reader_Activation;

defined( 'ABSPATH' ) || exit;

/**
 * Integrations Management Class.
 *
 * Manages registration, enabling/disabling, and retrieval of integrations.
 */
class Integrations {
	/**
	 * Registered integrations.
	 *
	 * @var Integration[]
	 */
	private static $integrations = [];

	/**
	 * Whether integrations have been registered.
	 *
	 * @var bool
	 */
	private static $integrations_registered = false;

	/**
	 * Option name for storing enabled integrations.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'newspack_reader_activation_enabled_integrations';

	/**
	 * Initialize integrations system.
	 */
	public static function init() {
		// Include required files.
		require_once __DIR__ . '/integrations/class-integration.php';

		add_action( 'init', [ __CLASS__, 'register_integrations' ], 5 );
	}

	/**
	 * Register integrations.
	 */
	public static function register_integrations() {
		// Native integrations.
		self::register( new Integrations\ESP() );

		// Hook for other plugins/code to register their integrations.
		do_action( 'newspack_reader_activation_register_integrations' );

		// hardcode ESP integration as enabled for now.
		self::enable( 'esp' );

		// Mark integrations as registered.
		self::$integrations_registered = true;
	}

	/**
	 * Register a new integration.
	 *
	 * @param Integration $integration The integration instance to register.
	 *
	 * @return bool True if registered successfully, false if already registered.
	 */
	public static function register( $integration ) {
		if ( ! $integration instanceof Integration ) {
			return false;
		}

		$id = $integration->get_id();

		if ( isset( self::$integrations[ $id ] ) ) {
			return false;
		}

		self::$integrations[ $id ] = $integration;

		return true;
	}

	/**
	 * Enable an integration.
	 *
	 * @param string $integration_id The integration ID to enable.
	 *
	 * @return bool True if enabled successfully, false otherwise.
	 */
	public static function enable( $integration_id ) {
		if ( ! isset( self::$integrations[ $integration_id ] ) ) {
			return false;
		}

		$enabled = self::get_enabled_integration_ids();

		if ( in_array( $integration_id, $enabled, true ) ) {
			return true;
		}

		$enabled[] = $integration_id;

		return update_option( self::OPTION_NAME, $enabled );
	}

	/**
	 * Disable an integration.
	 *
	 * @param string $integration_id The integration ID to disable.
	 *
	 * @return bool True if disabled successfully, false otherwise.
	 */
	public static function disable( $integration_id ) {
		$enabled = self::get_enabled_integration_ids();

		$key = array_search( $integration_id, $enabled, true );

		if ( false === $key ) {
			return true;
		}

		unset( $enabled[ $key ] );

		return update_option( self::OPTION_NAME, array_values( $enabled ) );
	}

	/**
	 * Get all available integrations.
	 *
	 * @return Integration[] Array of all registered integration instances.
	 */
	public static function get_available_integrations() {
		return self::$integrations;
	}

	/**
	 * Get active integrations.
	 *
	 * @return Integration[] Array of enabled integration instances.
	 */
	public static function get_active_integrations() {
		$enabled_ids = self::get_enabled_integration_ids();
		$active      = [];

		foreach ( $enabled_ids as $id ) {
			if ( isset( self::$integrations[ $id ] ) ) {
				$active[ $id ] = self::$integrations[ $id ];
			}
		}

		return $active;
	}

	/**
	 * Get a specific integration by ID.
	 *
	 * @param string $integration_id The integration ID.
	 *
	 * @return Integration|null The integration instance or null if not found.
	 */
	public static function get_integration( $integration_id ) {
		return self::$integrations[ $integration_id ] ?? null;
	}

	/**
	 * Check if an integration is enabled.
	 *
	 * @param string $integration_id The integration ID.
	 *
	 * @return bool True if enabled, false otherwise.
	 */
	public static function is_enabled( $integration_id ) {
		$enabled_ids = self::get_enabled_integration_ids();
		return in_array( $integration_id, $enabled_ids, true );
	}

	/**
	 * Get enabled integration IDs from option.
	 *
	 * @return array Array of enabled integration IDs.
	 */
	private static function get_enabled_integration_ids() {
		$enabled = get_option( self::OPTION_NAME, [] );

		if ( ! is_array( $enabled ) ) {
			return [];
		}

		return $enabled;
	}

	/**
	 * Check if integrations have been registered.
	 *
	 * @return bool True if integrations have been registered, false otherwise.
	 */
	public static function are_integrations_registered() {
		return self::$integrations_registered;
	}
}
