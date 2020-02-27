<?php
/**
 * Newspack Pop-ups Configuration Manager
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/configuration_managers/class-configuration-manager.php';

/**
 * Provide an interface for configuring and querying the configuration of Newspack Pop-ups.
 */
class Newspack_Popups_Configuration_Manager extends Configuration_Manager {

	/**
	 * The slug of the plugin.
	 *
	 * @var string
	 */
	public $slug = 'newspack-popups';

	/**
	 * Get whether the Newspack Popups plugin is active and set up.
	 *
	 * @return bool Whether Newspack Popups is installed and activated.
	 */
	public function is_configured() {
		return class_exists( 'Newspack_Popups_Model' );
	}

	/**
	 * Retrieve all Pop-up CPTs
	 *
	 * @return array All Pop-ups
	 */
	public function get_popups() {
		return $this->is_configured() ?
			\Newspack_Popups_Model::retrieve_popups() :
			$this->unconfigured_error();
	}

	/**
	 * Set the sitewide Popup.
	 *
	 * @param integer $id ID of sitewide popup.
	 */
	public function set_sitewide_popup( $id ) {
		return $this->is_configured() ?
			\Newspack_Popups_Model::set_sitewide_popup( $id ) :
			$this->unconfigured_error();
	}

	/**
	 * Unset the sitewide Popup.
	 *
	 * @param integer $id ID of sitewide popup.
	 */
	public function unset_sitewide_popup( $id ) {
		return $this->is_configured() ?
			\Newspack_Popups_Model::unset_sitewide_popup( $id ) :
			$this->unconfigured_error();
	}

	/**
	 * Set categories for a Popup.
	 *
	 * @param integer $id ID of sitewide popup.
	 * @param array   $categories Array of categories to be set.
	 */
	public function set_popup_categories( $id, $categories ) {
		return $this->is_configured() ?
			\Newspack_Popups_Model::set_popup_categories( $id, $categories ) :
			$this->unconfigured_error();
	}

	/**
	 * Configure Newspack Popups for Newspack use.
	 *
	 * @return bool || WP_Error Return true if successful, or WP_Error if not.
	 */
	public function configure() {
		return true;
	}

	/**
	 * Error to return if the plugin is not installed and activated.
	 *
	 * @return WP_Error
	 */
	private function unconfigured_error() {
		return new \WP_Error(
			'newspack_missing_required_plugin',
			esc_html__( 'The Newspack Popups plugin is not installed and activated. Install and/or activate it to access this feature.', 'newspack' ),
			[
				'status' => 400,
				'level'  => 'fatal',
			]
		);
	}
}
