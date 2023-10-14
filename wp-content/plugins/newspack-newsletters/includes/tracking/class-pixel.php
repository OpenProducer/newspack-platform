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
		return $url;
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
}
Pixel::init();
