<?php
/**
 * Newspack Ads Configuration Manager
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/configuration_managers/class-configuration-manager.php';

/**
 * Provide an interface for configuring and querying the configuration of Newspack Ads.
 */
class Newspack_Ads_Configuration_Manager extends Configuration_Manager {

	/**
	 * The slug of the plugin.
	 *
	 * @var string
	 */
	public $slug = 'newspack-ads';

	/**
	 * Get whether the Site Kit plugin is active and set up.
	 *
	 * @return bool Whether Newspack Ads is installed and activated.
	 */
	public function is_configured() {
		return class_exists( 'Newspack_Ads_Model' );
	}

	/**
	 * Get Network Code for ad service.
	 *
	 * @param string $service The service to retrieve.
	 * @return string | WP_Error Array of ad units or WP_Error if Newspack Ads isn't installed and activated.
	 */
	public function get_network_code( $service ) {
		return $this->is_configured() ?
			\Newspack_Ads_Model::get_network_code( $service ) :
			$this->unconfigured_error();
	}

	/**
	 * Create/update header code for ad service.
	 *
	 * @param string $service The service to retrieve.
	 * @param string $network_code The code.
	 * @return bool | WP_Error Array of ad units or WP_Error if Newspack Ads isn't installed and activated.
	 */
	public function set_network_code( $service, $network_code ) {
		return $this->is_configured() ?
			\Newspack_Ads_Model::set_network_code( $service, $network_code ) :
			$this->unconfigured_error();
	}

	/**
	 * Get the ad units from our saved option.
	 *
	 * @return array | WP_Error Array of ad units or WP_Error if Newspack Ads isn't installed and activated.
	 */
	public function get_ad_units() {
		return $this->is_configured() ?
			\Newspack_Ads_Model::get_ad_units() :
			$this->unconfigured_error();
	}

	/**
	 * Get a single ad unit.
	 *
	 * @param number $id The id of the ad unit to retrieve.
	 * @param string $placement The id of the placement region.
	 * @return array | WP_Error Returns ad unit or error if the plugin is not active or the ad unit doesn't exist.
	 */
	public function get_ad_unit( $id, $placement = null ) {
		return $this->is_configured() ?
			\Newspack_Ads_Model::get_ad_unit( $id, $placement ) :
			$this->unconfigured_error();
	}

	/**
	 * Add a new ad unit.
	 *
	 * @param array $ad_unit The new ad unit info to add.
	 * @return array | WP_Error Returns new ad unit or error if the plugin is not active or the ad unit exists already.
	 */
	public function add_ad_unit( $ad_unit ) {
		return $this->is_configured() ?
			\Newspack_Ads_Model::add_ad_unit( $ad_unit ) :
			$this->unconfigured_error();
	}

	/**
	 * Update an ad unit
	 *
	 * @param array $ad_unit The updated ad unit.
	 * @return array | WP_Error Returns updated ad unit or error if the plugin is not active or the ad unit doesn't exist.
	 */
	public function update_ad_unit( $ad_unit ) {
		return $this->is_configured() ?
			\Newspack_Ads_Model::update_ad_unit( $ad_unit ) :
			$this->unconfigured_error();
	}

	/**
	 * Delete an ad unit
	 *
	 * @param integer $id The id of the ad unit to delete.
	 * @return bool | WP_Error Returns true if deletion is successful, of error if the plugin is not active or the ad unit doesn't exist.
	 */
	public function delete_ad_unit( $id ) {
		return $this->is_configured() ?
			\Newspack_Ads_Model::delete_ad_unit( $id ) :
			$this->unconfigured_error();
	}

	/**
	 * Check whether the current screen should show ads.
	 *
	 * @return bool Returns true if ads should be shown.
	 */
	public function should_show_ads() {
		return $this->is_configured() && function_exists( 'newspack_ads_should_show_ads' ) ? newspack_ads_should_show_ads() : false;
	}

	/**
	 * Error to return if the plugin is not installed and activated.
	 *
	 * @return WP_Error
	 */
	private function unconfigured_error() {
		return new \WP_Error(
			'newspack_missing_required_plugin',
			esc_html__( 'The Newspack Ads plugin is not installed and activated. Install and/or activate it to access this feature.', 'newspack' ),
			[
				'status' => 400,
				'level'  => 'fatal',
			]
		);
	}

	/**
	 * Check if a service is enabled.
	 *
	 * @param string $service Service name.
	 * @return bool Is the service enabled.
	 */
	public function is_service_enabled( $service ) {
		return get_option( Advertising_Wizard::NEWSPACK_ADVERTISING_SERVICE_PREFIX . $service, false );
	}

	/**
	 * Configure Newspack Ads for Newspack use.
	 *
	 * @return bool || WP_Error Return true if successful, or WP_Error if not.
	 */
	public function configure() {
		return true;
	}
}
