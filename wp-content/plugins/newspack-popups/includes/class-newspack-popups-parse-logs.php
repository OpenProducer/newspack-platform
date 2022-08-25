<?php
/**
 * Newspack Segmentation Plugin
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/../api/segmentation/class-segmentation.php';
require_once dirname( __FILE__ ) . '/../api/classes/class-lightweight-api.php';

/**
 * Main Newspack Segmentation Plugin Class.
 */
final class Newspack_Popups_Parse_Logs {
	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Popups_Parse_Logs
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Segmentation Plugin Instance.
	 * Ensures only one instance of Newspack Segmentation Plugin Instance is loaded or can be loaded.
	 *
	 * @return Newspack Segmentation Plugin Instance - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( file_exists( Segmentation::get_log_file_path() ) ) {
			add_filter( 'cron_schedules', [ __CLASS__, 'add_cron_interval' ] ); // phpcs:ignore WordPress.WP.CronInterval.CronSchedulesInterval
			add_action( 'newspack_popups_segmentation_cron_hook', [ __CLASS__, 'parse_events_logs' ] );
			if ( ! wp_next_scheduled( 'newspack_popups_segmentation_cron_hook' ) ) {
				wp_schedule_event( time(), 'every_quarter_hour', 'newspack_popups_segmentation_cron_hook' );
			}
		}
	}

	/**
	 * Add a CRON interval of one minute.
	 *
	 * @param object $schedules The schedules.
	 */
	public static function add_cron_interval( $schedules ) {
		$schedules['every_quarter_hour'] = [
			'interval' => 60 * 15,
			'display'  => esc_html__( 'Every Quarter-Hour' ),
		];
		return $schedules;
	}

	/**
	 * Parse the log file, write data to the DB, and remove the file.
	 */
	public static function parse_events_logs() {
		global $wpdb;

		if ( ! file_exists( Segmentation::get_log_file_path() ) ) {
			return;
		}

		$log_file = fopen( Segmentation::get_log_file_path(), 'r+' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen

		if ( flock( $log_file, LOCK_EX ) ) { // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_flock
			$lines = [];

			while ( ! feof( $log_file ) ) {
				$line = trim( fgets( $log_file ) );
				if ( ! empty( $line ) ) {
					$lines[] = $line;
				}
			}

			$lines  = array_unique( $lines );
			$events = [];

			foreach ( $lines as $line ) {
				$result = explode( '|', $line );
				if ( isset( $result[1] ) ) {
					$client_id    = $result[0];
					$date_created = $result[1];
					$type         = $result[2];
					$context      = $result[3];
					$value        = $result[4];
				} else {
					// Handle legacy format.
					$result       = explode( ';', $line );
					$client_id    = $result[1];
					$date_created = $result[2];
					$type         = 'view';
					$context      = 'post';
					$value        = wp_json_encode(
						[
							'post_id'    => (int) $result[3],
							'categories' => $result[4],
						]
					);
				}

				$events[] = [
					'client_id'    => $client_id,
					'date_created' => $date_created,
					'type'         => $type,
					'context'      => $context,
					'value'        => $value,
				];
			}

			try {
				$api = new Lightweight_Api();
				$api->bulk_db_insert( $events );
			} catch ( \Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
				error_log( $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}

			flock( $log_file, LOCK_UN ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_flock

			// Clear the log file.
			file_put_contents( Segmentation::get_log_file_path(), '' ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
			fclose( $log_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
		}
	}
}
Newspack_Popups_Parse_Logs::instance();
