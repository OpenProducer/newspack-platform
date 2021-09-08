<?php
/**
 * Newspack Segmentation Plugin
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/../api/segmentation/class-segmentation.php';

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
	 * Insert multiple rows in one DB transaction.
	 *
	 * @param string $table_name Table name.
	 * @param array  $rows Rows to insert.
	 * @param array  $column_names Names of the columns.
	 * @param array  $placeholder Row placeholder for wpdb->prepare (e.g. `(%s, %s)`).
	 */
	public static function bulk_db_insert( $table_name, $rows, $column_names, $placeholder ) {
		if ( 0 === count( $rows ) ) {
			return;
		}

		global $wpdb;

		$column_names = implode( ', ', $column_names );
		$query        = "INSERT INTO $table_name ($column_names) VALUES ";
		$query       .= implode(
			', ',
			array_map(
				function( $row ) use ( $placeholder ) {
					return $placeholder;
				},
				$rows
			)
		);

		// Flatten the rows two-dimensional array.
		$values = array_reduce(
			$rows,
			function( $acc, $arr ) {
				return array_merge( $acc, $arr );
			},
			[]
		);

		$sql = $wpdb->prepare( "$query ", $values ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare

		// Update the event if a the duplicate has a different date.
		$sql .= ' ON DUPLICATE KEY UPDATE created_at = VALUES(created_at)';

		$wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
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

			$lines       = array_unique( $lines );
			$events_rows = [];

			foreach ( $lines as $line ) {
				$result     = explode( ';', $line );
				$event_type = $result[0];
				$client_id  = $result[1];
				$date       = $result[2];
				$post_id    = $result[3];
				$categories = $result[4];

				$events_rows[] = [ $event_type, $client_id, $date, $post_id, $categories ];
			}

			try {
				self::bulk_db_insert(
					Segmentation::get_events_table_name(),
					$events_rows,
					[
						'type',
						'client_id',
						'created_at',
						'post_id',
						'category_ids',
					],
					'( %s, %s, %s, %s, %s )'
				);
			} catch ( \Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
				// An error will be thrown for rows violating the UNIQUE constraint.
			}

			flock( $log_file, LOCK_UN ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_flock

			// Clear the log file.
			file_put_contents( Segmentation::get_log_file_path(), '' ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
			fclose( $log_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
		}
	}
}
Newspack_Popups_Parse_Logs::instance();
