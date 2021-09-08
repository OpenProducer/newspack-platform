<?php
/**
 * Newspack Popups Segmentation Report
 *
 * @package Newspack
 */

/**
 * Manages reporting data for segmentation features.
 */
class Segmentation_Report {
	/**
	 * Handle visitor.
	 *
	 * @param object $payload a payload.
	 */
	public static function log_single_visit( $payload ) {
		if ( $payload['is_post'] ) {
			// Add line to log file.
			$line = implode(
				';',
				[
					'post_read',
					$payload['clientId'],
					isset( $payload['date'] ) ? $payload['date'] : gmdate( 'Y-m-d H:i:s', time() ),
					$payload['post_id'],
					$payload['categories'],
				]
			);

			file_put_contents( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents
				Segmentation::get_log_file_path(),
				$line . PHP_EOL,
				FILE_APPEND
			);
		}
	}
}
