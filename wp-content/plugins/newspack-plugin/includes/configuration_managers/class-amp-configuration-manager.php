<?php
/**
 * AMP plugin configuration manager.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/configuration_managers/class-configuration-manager.php';

/**
 * Provide an interface for configuring and querying the configuration of AMP.
 */
class AMP_Configuration_Manager extends Configuration_Manager {

	/**
	 * The slug of the plugin.
	 *
	 * @var string
	 */
	public $slug = 'amp';

	/**
	 * Check if AMP plugin is in Standard mode.
	 *
	 * @return bool || WP_Error Return true if successful, or WP_Error if not.
	 */
	public function is_standard_mode() {
		if ( ! $this->is_configured() ) {
			return false;
		}
		return \AMP_Theme_Support::STANDARD_MODE_SLUG === \AMP_Options_Manager::get_option( 'theme_support' );
	}

	/**
	 * Configure AMP for Newspack use.
	 *
	 * @return bool || WP_Error Return true if successful, or WP_Error if not.
	 */
	public function configure() {
		$active = $this->is_active();
		if ( ! $active || is_wp_error( $active ) ) {
			return $active;
		}
		if ( class_exists( 'AMP_Options_Manager', 'AMP_Theme_Support' ) ) {
			\AMP_Options_Manager::update_option( 'theme_support', \AMP_Theme_Support::STANDARD_MODE_SLUG );
		}
		$this->set_newspack_has_configured( true );
		return true;
	}

	/**
	 * Is AMP installed and connected?
	 *
	 * @return bool Plugin ready state.
	 */
	public function is_configured() {
		if ( $this->is_active() && class_exists( 'AMP_Theme_Support', 'AMP_Options_Manager' ) ) {
			return true;
		}
		return false;
	}
}
