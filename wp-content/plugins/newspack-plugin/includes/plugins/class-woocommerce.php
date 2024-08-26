<?php
/**
 * WooCommerce integration class.
 * https://wordpress.org/plugins/woocommerce
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class WooCommerce {
	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_action( 'wp_loaded', [ __CLASS__,'disable_wc_author_archive_override' ] );
	}

	/**
	 * Prevent WC from redirecting to shop page from author archives of users who are customers (wc_disable_author_archives_for_customers).
	 */
	public static function disable_wc_author_archive_override() {
		remove_action( 'template_redirect', 'wc_disable_author_archives_for_customers', 10 );
	}
}
WooCommerce::init();
