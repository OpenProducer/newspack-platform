<?php
/**
 * Newspack "My Account" customizations v1.x.x.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Newspack "My Account" customizations v1.x.x.
 */
class My_Account_UI_V1 {
	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_filter( 'page_template', [ __CLASS__, 'page_template' ] );
		\add_filter( 'body_class', [ __CLASS__, 'add_body_class' ] );
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ], 11 );
		\add_filter( 'wc_get_template', [ __CLASS__, 'wc_get_template' ], 10, 5 );
		\add_filter( 'woocommerce_account_menu_items', [ __CLASS__, 'my_account_menu_items' ], 1001 );
	}

	/**
	 * Render My Account pages with a no-header/no-footer page template.
	 *
	 * @param string $template The template.
	 * @return string The template file path.
	 */
	public static function page_template( $template ) {
		if ( function_exists( 'is_account_page' ) && \is_account_page() && \is_user_logged_in() ) {
			return __DIR__ . '/templates/v1/my-account.php';
		}
		return $template;
	}

	/**
	 * Add a body class to the My Account page.
	 *
	 * @param array $classes The body classes.
	 * @return array The body classes.
	 */
	public static function add_body_class( $classes ) {
		if ( function_exists( 'is_account_page' ) && \is_account_page() ) {
			$classes[] = 'newspack-ui';
			$classes[] = 'newspack-my-account';
			$classes[] = 'newspack-my-account--v1';
			if ( ! \is_user_logged_in() ) {
				$classes[] = 'newspack-my-account--logged-out';
			} else {
				$classes[] = 'newspack-my-account--logged-in';
			}
		}
		return $classes;
	}

	/**
	 * Enqueue assets.
	 */
	public static function enqueue_assets() {
		if ( function_exists( 'is_account_page' ) && \is_account_page() ) {
			\wp_enqueue_script(
				'my-account-v1',
				\Newspack\Newspack::plugin_url() . '/dist/my-account-v1.js',
				[ 'my-account' ],
				NEWSPACK_PLUGIN_VERSION,
				true
			);

			// Dequeue styles from the Newspack theme first, for a fresh start.
			\wp_dequeue_style( 'newspack-woocommerce-style' );
			\wp_enqueue_style(
				'my-account-v1',
				\Newspack\Newspack::plugin_url() . '/dist/my-account-v1.css',
				[],
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
			case 'myaccount/navigation.php':
				return __DIR__ . '/templates/v1/navigation.php';
			default:
				return $template;
		}
	}

	/**
	 * Modify nav menu items.
	 *
	 * @param array $items Menu items.
	 * @return array Modified menu items.
	 */
	public static function my_account_menu_items( $items ) {
		// Remove logout menu item (to be replaced in our custom template).
		unset( $items['customer-logout'] );
		return $items;
	}
}
My_Account_UI_V1::init();
