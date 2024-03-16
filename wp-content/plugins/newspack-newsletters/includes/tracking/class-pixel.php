<?php
/**
 * Newspack Newsletters Tracking Pixel.
 *
 * @package Newspack
 */

namespace Newspack_Newsletters\Tracking;

/**
 * Tracking Pixel Class.
 */
final class Pixel {
	const QUERY_VAR = 'np_newsletters_pixel';

	/**
	 * Maximum number of lines to process from the log file at a time.
	 */
	const MAX_LINES = 100;

	/**
	 * Store whether the tracking pixel has been added to the newsletter.
	 *
	 * @var bool[] Whether the tracking pixel has been by newsletter ID.
	 */
	protected static $pixel_added = [];

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		\add_action( 'newspack_newsletters_editor_mjml_body', [ __CLASS__, 'add_tracking_pixel' ], 100 );
		\add_action( 'init', [ __CLASS__, 'rewrite_rule' ] );
		\add_filter( 'redirect_canonical', [ __CLASS__, 'redirect_canonical' ], 10, 2 );
		\add_filter( 'query_vars', [ __CLASS__, 'query_vars' ] );
		\add_action( 'init', [ __CLASS__, 'render' ], 2, 0 ); // Run on priority 2 to allow Data Events and ActionScheduler to initialize first.
		\add_action( 'template_redirect', [ __CLASS__, 'render' ] );

		/**
		 * Replace the default approach, which processes each request in the pixel
		 * hit, to an approach that processes requests in batches by cycling log
		 * files.
		 *
		 * The new pixel URL appends the tracking data to a log file, and then the
		 * log file is processed in batches by the `newspack_newsletters_tracking_pixel_process_log`
		 *
		 * Unhooking the following restores the original approach.
		 */
		\add_action( 'wp', [ __CLASS__, 'schedule_log_processing' ] );
		\add_action( 'newspack_newsletters_tracking_pixel_process_log', [ __CLASS__, 'process_logs' ] );
		\add_filter( 'newspack_newsletters_tracking_pixel_url', [ __CLASS__, 'log_pixel_url' ], 10, 4 );
	}

	/**
	 * Add tracking pixel.
	 *
	 * @param WP_Post $post Post object.
	 */
	public static function add_tracking_pixel( $post ) {
		if ( ! empty( self::$pixel_added[ $post->ID ] ) ) {
			return;
		}
		if ( ! Admin::is_tracking_pixel_enabled() ) {
			return;
		}
		printf(
			'<mj-raw><img src="%s" width="1" height="1" alt="" style="display: block; width: 1px; height: 1px; border: none; margin: 0; padding: 0;" /></mj-raw>',
			esc_url( self::get_pixel_url( $post->ID ) )
		);
		self::$pixel_added[ $post->ID ] = true;
	}

	/**
	 * Add rewrite rule for tracking pixel.
	 *
	 * Backwards compatibility for old tracking URLs.
	 */
	public static function rewrite_rule() {
		\add_rewrite_rule( 'np-newsletters.gif', 'index.php?' . self::QUERY_VAR . '=1', 'top' );
		\add_rewrite_tag( '%' . self::QUERY_VAR . '%', '1' );
		$check_option_name = 'newspack_newsletters_tracking_pixel_has_rewrite_rule';
		if ( ! \get_option( $check_option_name ) ) {
			\flush_rewrite_rules(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
			\add_option( $check_option_name, true );
		}
	}

	/**
	 * Disable canonical redirect for tracking pixel.
	 *
	 * @param string $redirect_url  Redirect URL.
	 * @param string $requested_url Requested URL.
	 *
	 * @return string|false
	 */
	public static function redirect_canonical( $redirect_url, $requested_url ) {
		if ( ! get_query_var( self::QUERY_VAR ) ) {
			return $redirect_url;
		}
		return false;
	}

	/**
	 * Add query vars.
	 *
	 * @param array $vars Query vars.
	 *
	 * @return array
	 */
	public static function query_vars( $vars = [] ) {
		$vars[] = self::QUERY_VAR;
		return $vars;
	}

	/**
	 * Get the tracking pixel URL.
	 *
	 * @param int $post_id ID of the newsletter.
	 */
	public static function get_pixel_url( $post_id ) {
		$tracking_id = \get_post_meta( $post_id, 'tracking_id', true );
		if ( ! $tracking_id ) {
			$tracking_id = \wp_generate_password( 32, false );
			\update_post_meta( $post_id, 'tracking_id', $tracking_id );
		}
		$url = \add_query_arg(
			[
				self::QUERY_VAR => 1,
				'id'            => $post_id,
				'tid'           => $tracking_id,
				'em'            => Utils::get_email_address_tag(),
			],
			\home_url()
		);
		/**
		 * Filters the pixel URL.
		 *
		 * @param string $url         Pixel URL.
		 * @param int    $post_id     ID of the newsletter.
		 * @param string $tracking_id Tracking ID.
		 * @param string $email_tag   The ESP email address tag.
		 */
		return apply_filters( 'newspack_newsletters_tracking_pixel_url', $url, $post_id, $tracking_id, Utils::get_email_address_tag() );
	}

	/**
	 * Track pixel seen.
	 *
	 * @param int    $newsletter_id ID of the newsletter.
	 * @param string $tracking_id   Tracking ID.
	 * @param string $email_address Email address of the recipient.
	 *
	 * @return void
	 */
	public static function track_seen( $newsletter_id, $tracking_id, $email_address ) {
		$newsletter_tracking_id = \get_post_meta( $newsletter_id, 'tracking_id', true );

		// Bail if tracking ID mismatch.
		if ( $newsletter_tracking_id !== $tracking_id ) {
			return;
		}

		$pixel_seen = \get_post_meta( $newsletter_id, 'tracking_pixel_seen', true );
		if ( ! $pixel_seen ) {
			$pixel_seen = 0;
		}
		$pixel_seen++;
		\update_post_meta( $newsletter_id, 'tracking_pixel_seen', $pixel_seen );

		/**
		 * Fires when the tracking pixel is seen and valid.
		 *
		 * @param int    $newsletter_id ID of the newsletter.
		 * @param string $email_address Email address of the recipient.
		 */
		\do_action( 'newspack_newsletters_tracking_pixel_seen', $newsletter_id, $email_address );
	}

	/**
	 * Render the tracking pixel.
	 */
	public static function render() {
		if ( ! \get_query_var( self::QUERY_VAR ) && ! isset( $_GET[ self::QUERY_VAR ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		// Disable caching.
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__HTTP_USER_AGENT__
		// Skip bots.
		if (
			! empty( $_SERVER['HTTP_USER_AGENT'] ) &&
			preg_match( '/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'] )
		) {
			exit;
		}
		// Skip prefetching and previews.
		if (
			! empty( $_SERVER['HTTP_X_PURPOSE'] ) &&
			in_array( $_SERVER['HTTP_X_PURPOSE'], [ 'preview', 'instant' ], true )
		) {
			exit;
		}
		if ( ! empty( $_SERVER['HTTP_X_MOZ'] ) && 'prefetch' === $_SERVER['HTTP_X_MOZ'] ) {
			exit;
		}
		// Skip Google Image Pre-Fetch.
		if (
			! empty( $_SERVER['HTTP_USER_AGENT'] ) &&
			'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246 Mozilla/5.0' === $_SERVER['HTTP_USER_AGENT']
		) {
			exit;
		}
		// phpcs:enable

		// Set the appropriate content type header.
		header( 'Content-Type: image/gif' );
		// Output a transparent 1x1 pixel image.
		echo base64_decode( 'R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$newsletter_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		$tracking_id   = isset( $_GET['tid'] ) ? \sanitize_text_field( $_GET['tid'] ) : 0;
		$email_address = isset( $_GET['em'] ) ? \sanitize_email( $_GET['em'] ) : '';
		// phpcs:enable

		if ( ! $newsletter_id || ! $tracking_id || ! $email_address ) {
			exit;
		}

		self::track_seen( $newsletter_id, $tracking_id, $email_address );
		exit;
	}

	/**
	 * Schedule log processing.
	 */
	public static function schedule_log_processing() {
		if ( ! \wp_next_scheduled( 'newspack_newsletters_tracking_pixel_process_log' ) ) {
			\wp_schedule_single_event( time() + 60, 'newspack_newsletters_tracking_pixel_process_log' );
		}
	}

	/**
	 * Log pixel URL.
	 *
	 * @param string $url         Pixel URL.
	 * @param int    $post_id     ID of the newsletter.
	 * @param string $tracking_id Tracking ID.
	 * @param string $email_tag   Email address of the recipient.
	 *
	 * @return string
	 */
	public static function log_pixel_url( $url, $post_id, $tracking_id, $email_tag ) {
		return \add_query_arg(
			[
				'id'  => $post_id,
				'tid' => $tracking_id,
				'em'  => $email_tag,
			],
			\content_url( '/np-newsletters-pixel.php' )
		);
	}

	/**
	 * Sanitize and track an item from the log.
	 *
	 * @param string $item The item to sanitize and track.
	 */
	public static function sanitize_and_track_item( $item ) {
		$item = explode( '|', $item );
		if ( 3 !== count( $item ) ) {
			return;
		}

		// Values must be sanitized as they are stored in the logs without sanitization.
		$newsletter_id = isset( $item[0] ) ? intval( $item[0] ) : 0;
		$tracking_id   = isset( $item[1] ) ? \sanitize_text_field( $item[1] ) : 0;
		$email_address = isset( $item[2] ) ? \sanitize_email( $item[2] ) : '';
		if ( ! $newsletter_id || ! $tracking_id || ! $email_address ) {
			return;
		}

		self::track_seen( $newsletter_id, $tracking_id, $email_address );
	}

	/**
	 * Process logs.
	 */
	public static function process_logs() {
		$current_log_file = \get_option( 'newspack_newsletters_tracking_pixel_log_file' );

		if ( $current_log_file && file_exists( $current_log_file ) ) {
			// Read the tracking data from the log file. Process in batches to avoid memory issues.
			$handle    = fopen( $current_log_file, 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
			$file_end  = false;
			$pos       = 0;

			while ( ! $file_end ) {
				$file_size = filesize( $current_log_file );
				$lines     = 0;

				// Process a chunk of lines from the file.
				while ( $lines < self::MAX_LINES ) {
					// Process the tracking data.
					$item = trim( fgets( $handle ) );

					// If we've reached the end of the chunk or file, stop the loop.
					if ( ! $item || feof( $handle ) ) {
						break;
					}

					self::sanitize_and_track_item( $item );
					$lines++;
				}

				// If we've reached the end of the file, stop the loop.
				if ( feof( $handle ) ) {
					$file_end = true;
					break;
				}

				// Get the current position in the file after processing the chunk.
				$pos = ftell( $handle );

				// If there are more lines to process, truncate the file for the next chunk.
				$truncated_contents = fread( $handle, $file_size - $pos ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fread

				// Reopen the file in write mode.
				fclose( $handle );
				$handle = fopen( $current_log_file, 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
				fputs( $handle, $truncated_contents ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fputs

				// Close and reopen truncated file in read mode.
				fclose( $handle );
				$handle = fopen( $current_log_file, 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
				rewind( $handle );
			}

			// Remove the log file after processing.
			fclose( $handle );
			unlink( $current_log_file, null ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
		}

		// Generate a new log file.
		$log_dir       = \wp_get_upload_dir()['path'];
		$log_file_path = tempnam( $log_dir, 'newspack_newsletters_pixel_log_' ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_tempnam

		// Create the pixel.php file with the new log file path.
		$pixel_code = '<?php
		// Skip bots.
		if ( ! empty( $_SERVER["HTTP_USER_AGENT"] ) && preg_match( \'/bot|crawl|slurp|spider|mediapartners/i\', $_SERVER["HTTP_USER_AGENT"] )
		) {
			exit;
		}
		// Skip prefetching and previews.
		if ( ! empty( $_SERVER["HTTP_X_PURPOSE"] ) && in_array( $_SERVER["HTTP_X_PURPOSE"], [ "preview", "instant" ], true ) ) {
			exit;
		}
		if ( ! empty( $_SERVER["HTTP_X_MOZ"] ) && "prefetch" === $_SERVER["HTTP_X_MOZ"] ) {
			exit;
		}
		if ( ! empty( $_SERVER["HTTP_USER_AGENT"] ) && "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246 Mozilla/5.0" === $_SERVER["HTTP_USER_AGENT"] ) {
			exit;
		}
		if ( ! isset( $_GET["id"] ) || ! isset( $_GET["tid"] ) || ! isset( $_GET["em"] ) ) {
			exit;
		}
		$file = "' . $log_file_path . '";
		$id = $_GET["id"];
		$tid = $_GET["tid"];
		$email_address = $_GET["em"];
		file_put_contents( $file, $id . "|" . $tid . "|" . $email_address . PHP_EOL, FILE_APPEND );
		header( "Cache-Control: no-cache, no-store, must-revalidate" );
		header( "Pragma: no-cache" );
		header( "Expires: 0" );
		header( "Content-Type: image/gif" );
		echo base64_decode( "R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=" );
		?>';

		// Save the updated np-newsletters-pixel.php file.
		file_put_contents( WP_CONTENT_DIR . '/np-newsletters-pixel.php', $pixel_code ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents

		// Update the log file path option.
		update_option( 'newspack_newsletters_tracking_pixel_log_file', $log_file_path );
	}
}
Pixel::init();
