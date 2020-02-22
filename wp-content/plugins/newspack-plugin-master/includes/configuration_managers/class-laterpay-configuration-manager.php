<?php
/**
 * LaterPay plugin configuration manager.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/configuration_managers/class-configuration-manager.php';

/**
 * Provide an interface for configuring and querying the configuration of LaterPay.
 */
class LaterPay_Configuration_Manager extends Configuration_Manager {

	/**
	 * The slug of the plugin.
	 *
	 * @var string
	 */
	public $slug = 'laterpay';

	/**
	 * Configure LaterPay for Newspack use.
	 *
	 * @return bool || WP_Error Return true if successful, or WP_Error if not.
	 */
	public function configure() {
		$active = $this->is_active();

		if ( ! $active || is_wp_error( $active ) ) {
			return $active;
		}

		if ( class_exists( 'LaterPay_Controller_Admin_Appearance' ) ) {

			$primary_color   = '#01a99d';
			$secondary_color = '#01766e';

			if ( function_exists( 'newspack_get_primary_color' ) ) {
				$primary_color   = newspack_get_primary_color();
				$secondary_color = newspack_get_secondary_color();
			}

			if ( 'default' !== get_theme_mod( 'theme_colors', 'default' ) ) {
				$primary_color   = get_theme_mod( 'primary_color_hex', $primary_color );
				$secondary_color = get_theme_mod( 'secondary_color_hex', $secondary_color );
			}

			update_option( 'laterpay_main_color', $primary_color );
			update_option( 'laterpay_hover_color', $secondary_color );

		}
		$this->set_newspack_has_configured( true );
		return true;
	}
}
