<?php
/**
 * Service Provider: Mailchimp Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Newsletters Class.
 */
abstract class Newspack_Newsletters_Service_Provider implements Newspack_Newsletters_ESP_API_Interface, Newspack_Newsletters_WP_Hookable_Interface {

	const BASE_NAMESPACE = 'newspack-newsletters/v1/';

	/**
	 * The controller.
	 *
	 * @var \WP_REST_Controller.
	 */
	private $controller;

	/**
	 * Name of the service.
	 *
	 * @var string
	 */
	public $service;

	/**
	 * Instances of descendant service provider classes.
	 *
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( $this->controller && $this->controller instanceof \WP_REST_Controller ) {
			add_action( 'rest_api_init', [ $this->controller, 'register_routes' ] );
		}
	}

	/**
	 * Manage singleton instances of all descendant service provider classes.
	 */
	public static function instance() {
		if ( empty( self::$instances[ static::class ] ) ) {
			self::$instances[ static::class ] = new static();
		}
		return self::$instances[ static::class ];
	}

	/**
	 * Check capabilities for using the API for authoring tasks.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public function api_authoring_permissions_check( $request ) {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack-newsletters' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}
}
