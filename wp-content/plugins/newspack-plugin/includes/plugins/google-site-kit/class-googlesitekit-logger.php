<?php
/**
 * Google Site Kit Logger class.
 *
 * @package Newspack
 */

namespace Newspack;

use Google\Site_Kit\Core\Authentication\Has_Connected_Admins;
use Google\Site_Kit\Core\Authentication\Disconnected_Reason;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class GoogleSiteKit_Logger {
	/**
	 * The hook name for site kit disconnection logger cron job.
	 */
	const CRON_HOOK = 'newspack_googlesitekit_disconnection_logger';

	/**
	 * The log code for disconnections.
	 */
	const LOG_CODE_DISCONNECTED = 'newspack_googlesitekit_disconnected';

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		if ( GoogleSiteKit::is_active() ) {
			add_action( 'admin_init', [ __CLASS__, 'cron_init' ] );
			add_action( 'delete_option_' . self::get_sitekit_ga4_has_connected_admin_option_name(), [ __CLASS__, 'log_disconnected_admins' ] );
			add_filter( 'update_user_metadata', [ __CLASS__, 'maybe_log_disconnected_reason' ], 10, 5 );
			add_action( self::CRON_HOOK, [ __CLASS__, 'handle_cron_event' ] );
		}
	}

	/**
	 * Schedule cron job to check for site kit connection. If the connection is lost we log it.
	 */
	public static function cron_init() {
		register_deactivation_hook( NEWSPACK_PLUGIN_FILE, [ __CLASS__, 'cron_deactivate' ] );

		if ( defined( 'NEWSPACK_CRON_DISABLE' ) && is_array( NEWSPACK_CRON_DISABLE ) && in_array( self::CRON_HOOK, NEWSPACK_CRON_DISABLE, true ) ) {
			self::cron_deactivate();
		} elseif ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time(), 'hourly', self::CRON_HOOK );
		}
	}

	/**
	 * Deactivate the cron job.
	 */
	public static function cron_deactivate() {
		wp_clear_scheduled_hook( self::CRON_HOOK );
	}


	/**
	 * Get the name of the option under which Site Kit's GA4 has connected admin flag is stored.
	 */
	private static function get_sitekit_ga4_has_connected_admin_option_name() {
		if ( class_exists( 'Google\Site_Kit\Core\Authentication\Has_Connected_Admins' ) ) {
			return Has_Connected_Admins::OPTION;
		}
		return 'googlesitekit_has_connected_admins';
	}

	/**
	 * Get the name of the option under which Site Kit's disconnected reason is stored.
	 */
	private static function get_sitekit_ga4_disconnected_reason_option_name() {
		if ( class_exists( 'Google\Site_Kit\Core\Authentication\Disconnected_Reason' ) ) {
			return Disconnected_Reason::OPTION;
		}
		return 'googlesitekit_disconnected_reason';
	}

	/**
	 * Logs disconnect reason when user meta update is triggered.
	 *
	 * @param bool   $check      Whether to update metadata.
	 * @param int    $object_id  Object ID.
	 * @param string $meta_key   Meta key.
	 * @param mixed  $meta_value Meta value.
	 * @param mixed  $prev_value Previous meta value.
	 */
	public static function maybe_log_disconnected_reason( $check, $object_id, $meta_key, $meta_value, $prev_value ) {
		if (
			// The meta key will have the database prefixed so we need to use str_contains.
			str_contains( $meta_key, self::get_sitekit_ga4_disconnected_reason_option_name() ) &&
			$meta_value !== $prev_value
		) {
			self::log(
				self::LOG_CODE_DISCONNECTED,
				'Google Site Kit has been disconnected with reason ' . $meta_value
			);
		}
		return $check;
	}

	/**
	 * Logs when cron event runs and all admins are disconnected.
	 */
	public static function handle_cron_event() {
		if ( ! get_option( self::get_sitekit_ga4_has_connected_admin_option_name(), false ) ) {
			self::log( self::LOG_CODE_DISCONNECTED, 'No active Google Site Kit connections found', false, 3 );
		}
	}

	/**
	 * Log when all admins are disconnected.
	 */
	public static function log_disconnected_admins() {
		self::log( self::LOG_CODE_DISCONNECTED, 'Google Site Kit has been disconnected for all admins' );
	}

	/**
	 * Main site kit logger.
	 *
	 * @param string $code      The code for the log.
	 * @param string $message   The message to log. Optional.
	 * @param bool   $backtrace Whether to include a backtrace.
	 * @param int    $log_level The log level.
	 */
	private static function log( $code, $message, $backtrace = true, $log_level = 2 ) {
		$data = [
			'file'       => $code,
			'user_email' => wp_get_current_user()->user_email,
		];
		if ( $backtrace ) {
			$e                 = new \Exception();
			$data['backtrace'] = $e->getTraceAsString();
		}
		Logger::newspack_log( $code, $message, $data, 'error', $log_level );
	}
}
GoogleSiteKit_Logger::init();
