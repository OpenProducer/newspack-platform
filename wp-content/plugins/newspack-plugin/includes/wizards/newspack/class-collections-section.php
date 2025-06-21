<?php
/**
 * Collections Section Object.
 *
 * @package Newspack
 */

namespace Newspack\Wizards\Newspack;

use Newspack\Optional_Modules;
use WP_REST_Server;

/**
 * Collections Section Object.
 *
 * @package Newspack\Wizards\Newspack
 */
class Collections_Section {
	/**
	 * Containing wizard slug.
	 *
	 * @var string
	 */
	protected $wizard_slug = 'newspack-settings';

	/**
	 * Register Wizard Section specific endpoints.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->wizard_slug . '/collections',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ __CLASS__, 'api_get_settings' ],
					'permission_callback' => [ $this, 'api_permissions_check' ],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ __CLASS__, 'api_update_settings' ],
					'permission_callback' => [ $this, 'api_permissions_check' ],
					'args'                => [
						Optional_Modules::MODULE_ENABLED_PREFIX . 'collections' => [
							'required'          => true,
							'sanitize_callback' => 'rest_sanitize_boolean',
						],
					],
				],
			]
		);
	}

	/**
	 * Get settings.
	 */
	public static function api_get_settings() {
		return Optional_Modules::get_settings();
	}

	/**
	 * Update settings.
	 *
	 * @param \WP_REST_Request $request Full details about the request.
	 * @return array
	 */
	public static function api_update_settings( $request ) {
		$settings = Optional_Modules::get_settings();
		$settings[ Optional_Modules::MODULE_ENABLED_PREFIX . 'collections' ] = $request->get_param( Optional_Modules::MODULE_ENABLED_PREFIX . 'collections' );
		update_option( Optional_Modules::OPTION_NAME, $settings );
		return Optional_Modules::get_settings();
	}

	/**
	 * Permissions check for the API.
	 *
	 * @return bool
	 */
	public function api_permissions_check() {
		return current_user_can( 'manage_options' );
	}
}

// Register the routes on rest_api_init.
add_action( 'rest_api_init', [ new \Newspack\Wizards\Newspack\Collections_Section(), 'register_rest_routes' ] );
