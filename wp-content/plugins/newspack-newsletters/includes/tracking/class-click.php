<?php
/**
 * Newspack Newsletters Click-Tracking.
 *
 * @package Newspack
 */

namespace Newspack_Newsletters\Tracking;

/**
 * Tracking Click-Tracking Class.
 */
final class Click {
	const QUERY_VAR = 'np_newsletters_click';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		\add_action( 'init', [ __CLASS__, 'rewrite_rule' ] );
		\add_filter( 'query_vars', [ __CLASS__, 'query_vars' ] );
		\add_action( 'template_redirect', [ __CLASS__, 'handle_click' ] );
		\add_filter( 'newspack_newsletters_process_link', [ __CLASS__, 'process_link' ], 10, 3 );
	}

	/**
	 * Add rewrite rule for tracking url.
	 */
	public static function rewrite_rule() {
		\add_rewrite_rule( 'np-newsletters-click', 'index.php?' . self::QUERY_VAR . '=1', 'top' );
		\add_rewrite_tag( '%' . self::QUERY_VAR . '%', '1' );
		$check_option_name = 'newspack_newsletters_tracking_click_has_rewrite_rule';
		if ( ! \get_option( $check_option_name ) ) {
			\flush_rewrite_rules(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
			\add_option( $check_option_name, true );
		}
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
	 * Get tracking URL.
	 *
	 * @return string
	 */
	public static function get_tracking_url() {
		return \home_url( 'np-newsletters-click' );
	}

	/**
	 * Get proxied URL.
	 *
	 * @param int    $newsletter_id Newsletter ID.
	 * @param string $url           Destination URL.
	 *
	 * @return string Proxied URL.
	 */
	public static function get_proxied_url( $newsletter_id, $url ) {
		return add_query_arg(
			[
				'id'  => $newsletter_id,
				'url' => urlencode( $url ),
				'em'  => Utils::get_email_address_tag(),
			],
			self::get_tracking_url()
		);
	}

	/**
	 * Process link.
	 *
	 * @param string   $url           Processed URL.
	 * @param string   $original_url  Original URL.
	 * @param \WP_Post $post          Newsletter post object.
	 *
	 * @return string
	 */
	public static function process_link( $url, $original_url, $post ) {
		if ( ! Admin::is_tracking_click_enabled() ) {
			return $url;
		}
		if ( ! $post ) {
			return $url;
		}
		return self::get_proxied_url( $post->ID, $url );
	}

	/**
	 * Track click.
	 *
	 * @param int    $newsletter_id Newsletter ID.
	 * @param string $email_address Email address.
	 * @param string $url           Destination URL.
	 *
	 * @return void
	 */
	public static function track_click( $newsletter_id, $email_address, $url ) {
		if ( ! $newsletter_id || ! $email_address ) {
			return;
		}

		$clicks = \get_post_meta( $newsletter_id, 'tracking_clicks', true );
		if ( ! $clicks ) {
			$clicks = 0;
		}
		$clicks++;
		\update_post_meta( $newsletter_id, 'tracking_clicks', $clicks );

		/**
		 * Fires when a click is tracked.
		 *
		 * @param int    $newsletter_id Newsletter ID.
		 * @param string $url           Destination URL.
		 */
		do_action( 'newspack_newsletters_tracking_click', $newsletter_id, $email_address, $url );
	}

	/**
	 * Handle proxied URL click and redirect to destination.
	 */
	public static function handle_click() {
		if ( ! \get_query_var( self::QUERY_VAR ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$newsletter_id = \intval( $_GET['id'] ?? 0 );
		$email_address = \sanitize_email( $_GET['em'] ?? '' );
		$url           = \sanitize_text_field( \wp_unslash( $_GET['url'] ?? '' ) );
		// phpcs:enable

		if ( ! $url || ! \wp_http_validate_url( $url ) ) {
			\wp_die( 'Invalid URL' );
			exit;
		}

		self::track_click( $newsletter_id, $email_address, $url );

		\wp_redirect( $url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
		exit;
	}
}
Click::init();
