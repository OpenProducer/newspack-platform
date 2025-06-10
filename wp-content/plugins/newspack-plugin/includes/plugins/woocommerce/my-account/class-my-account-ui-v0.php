<?php
/**
 * Connection with WooCommerce's features.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Connection with WooCommerce's "My Account" page.
 */
class My_Account_UI_V0 {
	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
		\add_filter( 'wc_get_template', [ __CLASS__, 'wc_get_template' ], 10, 5 );
	}

	/**
	 * Enqueue assets.
	 */
	public static function enqueue_assets() {
		if ( function_exists( 'is_account_page' ) && \is_account_page() ) {
			\wp_enqueue_style(
				'my-account-v0',
				\Newspack\Newspack::plugin_url() . '/dist/my-account-v0.css',
				[ 'my-account' ],
				NEWSPACK_PLUGIN_VERSION
			);
		}
	}

	/**
	 * WC's page templates hijacking.
	 *
	 * @param string $template      Template path.
	 * @param string $template_name Template name.
	 */
	public static function wc_get_template( $template, $template_name ) {
		switch ( $template_name ) {
			case 'myaccount/form-login.php':
				if ( isset( $_GET[ WooCommerce_My_Account::AFTER_ACCOUNT_DELETION_PARAM ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					return __DIR__ . '/templates/after-delete-account.php';
				}
				return $template;
			case 'myaccount/form-edit-account.php':
				if ( isset( $_GET[ WooCommerce_My_Account::DELETE_ACCOUNT_FORM ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					return __DIR__ . '/templates/delete-account.php';
				}
				return __DIR__ . '/templates/edit-account.php';
			default:
				return $template;
		}
	}
}

My_Account_UI_V0::init();
