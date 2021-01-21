<?php
/**
 * Newspack user profile management.
 *
 * @package Newspack
 */

namespace Newspack;

use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

define( 'NEWSPACK_PROFILE_OPTION_PREFIX', '_newspack_profile_' );

/**
 * Manages user profile data.
 */
class Profile {

	/**
	 * List of fieldnames
	 *
	 * @var $fieldnames
	 */
	protected static $fieldnames = [
		'country',
		'address',
		'address2',
		'city',
		'state',
		'zip',
		'currency',
	];

	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
	}

	/**
	 * Retrieve one option, prefixing the option name for Newspack profile.
	 *
	 * @param string $key The unprefixed option name.
	 * @return string The option value.
	 */
	public static function newspack_get_option( $key ) {
		return get_option( NEWSPACK_PROFILE_OPTION_PREFIX . $key, '' );
	}

	/**
	 * Update one option, prefixing the option name for Newspack profile.
	 *
	 * @param string $key The unprefixed option name.
	 * @param string $value The option value.
	 */
	public static function newspack_update_option( $key, $value ) {
		update_option( NEWSPACK_PROFILE_OPTION_PREFIX . $key, $value );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		// Get profile data.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/profile/',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_profile' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Update profile data.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/profile/',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_profile' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => array(
					'profile' => array(
						'required' => true,
						'type'     => 'object',
					),
				),
			]
		);
	}

	/**
	 * Get complete user profile.
	 */
	public function newspack_get_profile() {
		$profile = [];
		foreach ( self::$fieldnames as $fieldname ) {
			$profile[ $fieldname ] = self::newspack_get_option( $fieldname );
		}
		return $profile;
	}

	/**
	 * Retrieve complete profile.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return object|WP_Error
	 */
	public function api_get_profile( $request ) {
		$response = [
			'profile'    => $this->newspack_get_profile(),
			'currencies' => newspack_select_prepare( newspack_currencies() ),
			'countries'  => newspack_select_prepare( newspack_countries() ),
		];
		return rest_ensure_response( $response );
	}

	/**
	 * Check capabilities for using API.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return object|WP_Error
	 */
	public function api_update_profile( $request ) {

		$updates = $request['profile'];
		foreach ( self::$fieldnames as $fieldname ) {
			if ( isset( $updates[ $fieldname ] ) ) {
				self::newspack_update_option( $fieldname, $updates[ $fieldname ] );
			}
		}
		$response = [
			'profile' => $this->newspack_get_profile(),
		];
		return rest_ensure_response( $response );
	}

	/**
	 * Check capabilities for using API.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public function api_permissions_check( $request ) {
		if ( ! current_user_can( $this->capability ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

}
new Profile();
