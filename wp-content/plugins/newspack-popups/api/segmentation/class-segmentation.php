<?php
/**
 * Newspack Popups Segmentation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Manages Segmentation.
 */
class Segmentation {
	/**
	 * Get client's read posts.
	 *
	 * @param string $client_id Client ID.
	 */
	public static function get_client_read_posts( $client_id ) {
		global $wpdb;
		$events_table_name = self::get_events_table_name();
		$clients_events    = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare( "SELECT * FROM $events_table_name WHERE client_id = %s AND type = 'post_read'", $client_id ) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);
		return array_map(
			function ( $item ) {
				return [
					'post_id'      => $item->post_id,
					'category_ids' => $item->category_ids,
				];
			},
			$clients_events
		);
	}

	/**
	 * Get log file path.
	 */
	public static function get_log_file_path() {
		global $table_prefix;
		return get_temp_dir() . $table_prefix . 'newspack-popups-events.log';
	}

	/**
	 * Get events table name.
	 */
	public static function get_events_table_name() {
		global $wpdb;
		return $wpdb->prefix . 'newspack_campaigns_events';
	}
}
