<?php
/**
 * Newspack user profile management.
 *
 * @package Newspack
 */

namespace Newspack;

use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . 'includes/configuration_managers/class-configuration-managers.php';
/**
 * Manages user profile data.
 */
class Profile {

	/**
	 * Profile fields.
	 *
	 * @var $profile_fields
	 */
	protected static $profile_fields = [];

	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Fields to fetch from WP SEO (Yoast) plugin.
	 *
	 * @var array
	 */
	protected static $wpseo_fields = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
		add_action( 'init', [ $this, 'set_profile_fields' ] );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function set_profile_fields() {
		$site_icon = get_site_icon_url();

		self::$wpseo_fields = [
			[
				'key'         => 'facebook_site',
				'label'       => __( 'Facebook Page', 'newspack' ),
				'placeholder' => __( 'https://facebook.com/page', 'newspack' ),
			],
			[
				'key'         => 'twitter_site',
				'label'       => __( 'Twitter Username', 'newspack' ),
				'placeholder' => __( 'username', 'newspack' ),
			],
			[
				'key'         => 'instagram_url',
				'label'       => __( 'Instagram', 'newspack' ),
				'placeholder' => __( 'https://instagram.com/user', 'newspack' ),
			],
			[
				'key'         => 'linkedin_url',
				'label'       => __( 'Linkedin', 'newspack' ),
				'placeholder' => __( 'https://linkedin.com/user', 'newspack' ),
			],
			[
				'key'         => 'youtube_url',
				'label'       => __( 'YouTube', 'newspack' ),
				'placeholder' => __( 'https://youtube.com/c/channel', 'newspack' ),
			],
			[
				'key'         => 'pinterest_url',
				'label'       => __( 'Pinterest', 'newspack' ),
				'placeholder' => __( 'https://pinterest.com/user', 'newspack' ),
			],
		];

		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
		$wc_location_data         = $wc_configuration_manager->location_data();
		self::$profile_fields     = [
			[
				'name'    => 'site_icon',
				'value'   => ! empty( $site_icon ) ? [
					'url' => $site_icon,
					'id'  => get_option( 'site_icon' ),
				] : false,
				'updater' => function( $value ) {
					update_option( 'site_icon', isset( $value['id'] ) ? $value['id'] : '' );
					update_option( 'sitelogo', isset( $value['id'] ) ? $value['id'] : '' );
				},
			],
			[
				'name'    => 'site_title',
				'value'   => get_option( 'blogname' ),
				'updater' => function( $value ) {
					update_option( 'blogname', $value );
				},
			],
			[
				'name'    => 'tagline',
				'value'   => get_option( 'blogdescription' ),
				'updater' => function( $value ) {
					update_option( 'blogdescription', $value );
				},
			],
			[
				'name'    => 'countrystate',
				'value'   => $wc_location_data['countrystate'],
				'updater' => function( $value ) {
					$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
					$wc_configuration_manager->update_location( [ 'countrystate' => $value ] );
				},
			],
			[
				'name'    => 'currency',
				'value'   => $wc_location_data['currency'],
				'updater' => function( $value ) {
					$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
					$wc_configuration_manager->update_location( [ 'currency' => $value ] );
				},
			],
		];

		$wpseo_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'wordpress_seo' );
		foreach ( self::$wpseo_fields as $field ) {
			self::$profile_fields[] = [
				'name'    => $field['key'],
				'value'   => $wpseo_manager->get_option( $field['key'], '' ),
				'updater' => function( $value ) use ( $field ) {
					$wpseo_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'wordpress_seo' );
					$wpseo_manager->set_option( $field['key'], $value );
				},
			];
		}
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
		foreach ( self::$profile_fields as $field ) {
			$profile[ $field['name'] ] = $field['value'];
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
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
		$response                 = [
			'profile'      => $this->newspack_get_profile(),
			'currencies'   => $wc_configuration_manager->currency_fields(),
			'countries'    => $wc_configuration_manager->country_state_fields(),
			'wpseo_fields' => self::$wpseo_fields,
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
		foreach ( self::$profile_fields as $field ) {
			$updater = $field['updater'];
			$updater( $updates[ $field['name'] ] );
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
