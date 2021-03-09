<?php
/**
 * Service Provider Controller: general API shared by all ESP services.
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * General API shared by all ESP services.
 */
abstract class Newspack_Newsletters_Service_Provider_Controller extends \WP_REST_Controller {

	/**
	 * The service provider class.
	 *
	 * @var Newspack_Newsletters_Service_Provider $service_provider
	 */
	private $service_provider;

	/**
	 * Newspack_Newsletters_Service_Provider_Controller constructor.
	 *
	 * @param \Newspack_Newsletters_Service_Provider $service_provider Logic general to all ESP Services.
	 */
	public function __construct( $service_provider ) {
		$this->service_provider = $service_provider;
	}

	/**
	 * Endpoints common to all ESP Service Providers.
	 */
	public function register_routes() {
		// Currently empty. Add endpoints common to all the ESP Service Providers.
	}
}
