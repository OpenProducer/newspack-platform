<?php
/**
 * Class Google\Site_Kit\Core\Util\Auto_Updates
 *
 * @package   Google\Site_Kit\Core\Util
 * @copyright 2022 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Core\Util;

/**
 * Utility class for auto-updates settings.
 *
 * @since 1.93.0
 * @access private
 * @ignore
 */
class Auto_Updates {

	/**
	 * Auto updated forced enabled.
	 *
	 * @since 1.93.0
	 * @var true
	 */
	const AUTO_UPDATE_FORCED_ENABLED = true;

	/**
	 * Auto updated forced disabled.
	 *
	 * @since 1.93.0
	 * @var false
	 */
	const AUTO_UPDATE_FORCED_DISABLED = false;

	/**
	 * Auto updated not forced.
	 *
	 * @since 1.93.0
	 * @var false
	 */
	const AUTO_UPDATE_NOT_FORCED = null;

	/**
	 * Checks whether plugin auto-updates are enabled for the site.
	 *
	 * @since 1.93.0
	 *
	 * @return bool `false` if auto-updates are disabled, `true` otherwise.
	 */
	public static function is_plugin_autoupdates_enabled() {
		if ( self::AUTO_UPDATE_FORCED_DISABLED === self::sitekit_forced_autoupdates_status() ) {
			return false;
		}

		if ( function_exists( 'wp_is_auto_update_enabled_for_type' ) ) {
			return wp_is_auto_update_enabled_for_type( 'plugin' );
		}

		return false;
	}

	/**
	 * Check whether the site has auto updates enabled for Site Kit.
	 *
	 * @since 1.93.0
	 *
	 * @return bool `true` if auto updates are enabled, otherwise `false`.
	 */
	public static function is_sitekit_autoupdates_enabled() {
		if ( self::AUTO_UPDATE_FORCED_ENABLED === self::sitekit_forced_autoupdates_status() ) {
			return true;
		}

		if ( self::AUTO_UPDATE_FORCED_DISABLED === self::sitekit_forced_autoupdates_status() ) {
			return false;
		}

		$enabled_auto_updates = (array) get_site_option( 'auto_update_plugins', array() );

		if ( ! $enabled_auto_updates ) {
			return false;
		}

		// Check if the Site Kit is in the list of auto-updated plugins.
		return in_array( GOOGLESITEKIT_PLUGIN_BASENAME, $enabled_auto_updates, true );
	}

	/**
	 * Checks whether auto-updates are forced for Site Kit.
	 *
	 * @since 1.93.0
	 *
	 * @return bool|null
	 */
	public static function sitekit_forced_autoupdates_status() {
		if ( ! function_exists( 'wp_is_auto_update_forced_for_item' ) ) {
			return self::AUTO_UPDATE_NOT_FORCED;
		}

		$sitekit_plugin_data = get_plugin_data( GOOGLESITEKIT_PLUGIN_MAIN_FILE );

		$is_auto_update_forced_for_sitekit = wp_is_auto_update_forced_for_item( 'plugin', null, (object) $sitekit_plugin_data );

		if ( true === $is_auto_update_forced_for_sitekit ) {
			return self::AUTO_UPDATE_FORCED_ENABLED;
		}

		if ( false === $is_auto_update_forced_for_sitekit ) {
			return self::AUTO_UPDATE_FORCED_DISABLED;
		}

		return self::AUTO_UPDATE_NOT_FORCED;
	}
}
