<?php
/**
 * Logger.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Logger.
 */
class Logger {
	/**
	 * A logger.
	 *
	 * @param any    $payload The payload to log.
	 * @param string $header Log message header.
	 */
	public static function log( $payload, $header = 'NEWSPACK' ) {
		if ( ! defined( 'NEWSPACK_LOG_LEVEL' ) || 0 >= (int) NEWSPACK_LOG_LEVEL ) {
			return;
		}
		$caller = null;

		// Add information about the caller function, if log level is > 1.
		if ( 1 < NEWSPACK_LOG_LEVEL ) {
			try {
				$backtrace = debug_backtrace(); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
				if ( 2 < count( $backtrace ) ) {
					$caller_frame = $backtrace[1];
					if ( stripos( $caller_frame['class'], 'Logger' ) !== false ) {
						// Logger was called by another *Logger class, let's move the backtrace one level up.
						if ( isset( $backtrace[2] ) ) {
							$caller_frame = $backtrace[2];
						}
					}
					$caller = $caller_frame['class'] . $caller_frame['type'] . $caller_frame['function'];
				}
			} catch ( \Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
				// Fail silently.
			}
		}

		$message       = 'string' === gettype( $payload ) ? $payload : wp_json_encode( $payload, JSON_PRETTY_PRINT );
		$caller_prefix = $caller ? "[$caller]" : '';

		error_log( '[' . $header . ']' . $caller_prefix . ': ' . $message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}
}
