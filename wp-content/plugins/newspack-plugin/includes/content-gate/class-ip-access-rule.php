<?php
/**
 * Content Gate IP Access Rule.
 *
 * @package Newspack
 */

namespace Newspack\Content_Gate;

/**
 * IP Access Rule class.
 */
class IP_Access_Rule {

	/**
	 * The name of the cookie used to bypass cache and allow server side IP checking.
	 */
	const COOKIE_NAME = 'wp_nocache_ip';

	/**
	 * The endpoint for institutional access.
	 */
	const ENDPOINT = 'institutional-access';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'add_rewrite_rule' ] );
		add_action( 'template_redirect', [ __CLASS__, 'handle_redirect' ] );
	}

	/**
	 * Register the rewrite rule for the institutional access endpoint.
	 */
	public static function add_rewrite_rule() {
		add_rewrite_rule( '^' . self::ENDPOINT . '/?$', 'index.php?' . self::ENDPOINT . '=1', 'top' );
		add_rewrite_tag( '%' . self::ENDPOINT . '%', '1' );

		$option_key = 'newspack_ip_access_rule_flushed';
		if ( ! get_option( $option_key ) ) {
			flush_rewrite_rules(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
			update_option( $option_key, true );
		}
	}

	/**
	 * Handle the institutional access redirect.
	 */
	public static function handle_redirect() {
		if ( ! get_query_var( self::ENDPOINT ) ) {
			return;
		}

		// Never cache this page.
		if ( function_exists( 'batcache_cancel' ) ) {
			batcache_cancel();
		}
		nocache_headers();

		/**
		 * Filter whether the current IP is valid for content gate access.
		 *
		 * @param bool $valid_ip Whether the IP is valid. Default false.
		 */
		$valid_ip = apply_filters( 'newspack_content_gate_check_ip', false );

		if ( $valid_ip ) {
			setcookie( self::COOKIE_NAME, '1', time() + DAY_IN_SECONDS, '/', COOKIEPATH, COOKIE_DOMAIN ); // phpcs:ignore
		}

		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
}
IP_Access_Rule::init();
