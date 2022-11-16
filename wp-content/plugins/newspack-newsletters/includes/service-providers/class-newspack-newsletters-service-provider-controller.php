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

	/**
	 * Prepare and return the API response.
	 *
	 * @param array|WP_Error|mixed $response Data to return.
	 */
	public static function get_api_response( $response ) {
		if ( is_wp_error( $response ) ) {
			$response->add_data( [ 'status' => 400 ] );
		}
		return \rest_ensure_response( $response );
	}

	/**
	 * Update user's default email addresses for test email
	 *
	 * @param array $emails Email addresses.
	 * @return bool Whether the value has been updated or not
	 */
	public function update_user_test_emails( $emails ) {
		$user_id   = get_current_user_id();
		$user_info = get_userdata( $user_id );
		if ( 1 === count( $emails ) && $user_info->user_email === $emails[0] ) {
			return false;
		}
		return (bool) update_user_meta( $user_id, 'newspack_nl_test_emails', $emails );
	}
}
