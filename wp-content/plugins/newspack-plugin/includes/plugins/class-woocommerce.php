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
		add_action( 'wp_loaded', [ __CLASS__, 'disable_wc_author_archive_override' ] );
		add_filter( 'woocommerce_rest_prepare_shop_order_object', [ __CLASS__, 'modify_shop_order_wc_rest_api_payload' ] );
	}

	/**
	 * Prevent WC from redirecting to shop page from author archives of users who are customers (wc_disable_author_archives_for_customers).
	 */
	public static function disable_wc_author_archive_override() {
		remove_action( 'template_redirect', 'wc_disable_author_archives_for_customers', 10 );
	}

	/**
	 * Remove Newspack internal-use meta data from /order REST API response.
	 * These might cause confusion and should not be used by third parties.
	 *
	 * @param \WP_REST_Response $response The response object.
	 * @return WP_REST_Response
	 */
	public static function modify_shop_order_wc_rest_api_payload( $response ) {
		$data = $response->get_data();
		if ( ! isset( $data['meta_data'] ) ) {
			return $response;
		}
		foreach ( $data['meta_data'] as $key => $value ) {
			if ( ! isset( $value->get_data()['value'] ) || ! is_string( $value->get_data()['value'] ) ) {
				continue;
			}
			if ( stripos( $value->get_data()['key'], '_newspack' ) === 0 ) {
				unset( $data['meta_data'][ $key ] );
			}
		}
		$response->set_data( $data );
		return $response;
	}
}
WooCommerce::init();
