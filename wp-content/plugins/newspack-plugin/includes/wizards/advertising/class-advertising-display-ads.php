<?php
/**
 * Newspack's Advertising Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use WP_Error;

use Newspack_Ads\Providers\GAM_Model;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Easy interface for setting up general store info.
 */
class Advertising_Display_Ads extends Wizard {

	const NEWSPACK_ADVERTISING_SERVICE_PREFIX = '_newspack_advertising_service_';

	const SERVICE_ACCOUNT_CREDENTIALS_OPTION_NAME = '_newspack_ads_gam_credentials';

	// Legacy network code manually inserted.
	const OPTION_NAME_LEGACY_NETWORK_CODE = '_newspack_ads_service_google_ad_manager_network_code';

	// GAM network code pulled from user credentials.
	const OPTION_NAME_GAM_NETWORK_CODE = '_newspack_ads_gam_network_code';

	/**
	 * The slug of this wizard.
	 *
	 * Note: `newspack-ads-display-ads` (vs. `newspack-advertising-display-ads`) is intentional to avoid
	 * Ad blockers from blocking the Advertising menu item.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-ads-display-ads';

	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Supported services.
	 *
	 * @var array
	 */
	protected $services = array(
		'google_ad_manager' => array(
			'label' => 'Google Ad Manager',
		),
	);

	/**
	 * The parent menu item name.
	 *
	 * @var string
	 */
	public $parent_menu = 'newspack-ads-display-ads';

	/**
	 * Order relative to the Newspack Dashboard menu item.
	 *
	 * @var int
	 */
	public $parent_menu_order = 4;

	/**
	 * Use a high priorty so that the Advertising parent menu will be created
	 * prior to submenu items being added.
	 *
	 * @var int.
	 */
	protected $admin_menu_priority = 1;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return esc_html__( 'Advertising', 'newspack-plugin' );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {

		// Get all Newspack advertising data.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'api_get_advertising' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
			)
		);

		// Enable one service.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/service/(?P<service>[\a-z]+)',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'api_enable_service' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
				'args'                => array(
					'service' => array(
						'sanitize_callback' => array( $this, 'sanitize_service' ),
					),
				),
			)
		);

		// Disable one service.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/service/(?P<service>[\a-z]+)',
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'api_disable_service' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
				'args'                => array(
					'service' => array(
						'sanitize_callback' => array( $this, 'sanitize_service' ),
					),
				),
			)
		);

		// Update GAM credentials.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/credentials',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'api_update_gam_credentials' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
				'args'                => array(
					'onboarding'  => array(
						'type'              => 'boolean',
						'sanitize_callback' => 'rest_sanitize_boolean',
						'default'           => false,
					),
					'credentials' => array(
						'type'       => 'object',
						'properties' => array(
							'type'                        => array(
								'required' => true,
								'type'     => 'string',
							),
							'project_id'                  => array(
								'required' => true,
								'type'     => 'string',
							),
							'private_key_id'              => array(
								'required' => true,
								'type'     => 'string',
							),
							'private_key'                 => array(
								'required' => true,
								'type'     => 'string',
							),
							'client_email'                => array(
								'required' => true,
								'type'     => 'string',
							),
							'client_id'                   => array(
								'required' => true,
								'type'     => 'string',
							),
							'auth_uri'                    => array(
								'required' => true,
								'type'     => 'string',
							),
							'token_uri'                   => array(
								'required' => true,
								'type'     => 'string',
							),
							'auth_provider_x509_cert_url' => array(
								'required' => true,
								'type'     => 'string',
							),
							'client_x509_cert_url'        => array(
								'required' => true,
								'type'     => 'string',
							),
						),
					),
				),
			)
		);

		// Remove GAM credentials.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/credentials',
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'api_remove_gam_credentials' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
			)
		);

		// Save a ad unit.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/ad_unit/(?P<id>\d+)',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'api_update_adunit' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
				'args'                => array(
					'id'         => array(
						'sanitize_callback' => 'absint',
					),
					'name'       => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
					'sizes'      => array(
						'sanitize_callback' => array( $this, 'sanitize_sizes' ),
					),
					'fluid'      => array(
						'sanitize_callback' => 'rest_sanitize_boolean',
					),
					'ad_service' => array(
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);

		// Delete a ad unit.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/ad_unit/(?P<id>\d+)',
			array(
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'api_delete_adunit' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
				'args'                => array(
					'id' => array(
						'sanitize_callback' => 'absint',
					),
				),
			)
		);

		// Update network code.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/network_code',
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'api_update_network_code' ),
				'permission_callback' => array( $this, 'api_permissions_check' ),
				'args'                => array(
					'network_code' => array(
						'sanitize_callback' => function ( $value ) {
							$raw_codes       = explode( ',', $value );
							$sanitized_codes = array_reduce(
								$raw_codes,
								function ( $acc, $code ) {
									$sanitized_code = absint( trim( $code ) );
									if ( ! empty( $sanitized_code ) ) {
										$acc[] = $sanitized_code;
									}
									return $acc;
								},
								array()
							);

							return implode( ',', $sanitized_codes );
						},
					),
					'is_gam'       => array(
						'sanitize_callback' => 'rest_sanitize_boolean',
						'default'           => false,
					),
				),
			)
		);

		// Create the Media Kit page.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/media-kit',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'api_create_media_kit_page' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Unpublish the Media Kit page.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/billboard/media-kit',
			[
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'api_unpublish_media_kit_page' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
	}

	/**
	 * Update network code.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing ad units info.
	 */
	public function api_update_network_code( $request ) {
		// Update GAM or legacy network code.
		$option_name = $request['is_gam'] ? GAM_Model::OPTION_NAME_GAM_NETWORK_CODE : GAM_Model::OPTION_NAME_LEGACY_NETWORK_CODE;
		update_option( $option_name, $request['network_code'] );
		return \rest_ensure_response( array() );
	}

	/**
	 * Get advertising data.
	 *
	 * @return WP_REST_Response containing ad units info.
	 */
	public function api_get_advertising() {
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Enable one service
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing ad units info.
	 */
	public function api_enable_service( $request ) {
		$service = $request['service'];
		update_option( self::NEWSPACK_ADVERTISING_SERVICE_PREFIX . $service, true );
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Disable one service
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing ad units info.
	 */
	public function api_disable_service( $request ) {
		$service = $request['service'];
		update_option( self::NEWSPACK_ADVERTISING_SERVICE_PREFIX . $service, false );
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Update GAM credentials.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing current GAM status.
	 */
	public function api_update_gam_credentials( $request ) {
		$params = $request->get_params();
		update_option( self::SERVICE_ACCOUNT_CREDENTIALS_OPTION_NAME, $params['credentials'] );
		if ( isset( $params['onboarding'] ) && $params['onboarding'] ) {
			return \rest_ensure_response( true );
		}
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Remove GAM credentials.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing current GAM status.
	 */
	public function api_remove_gam_credentials( $request ) {
		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );
		$response              = $configuration_manager->remove_gam_credentials();

		if ( \is_wp_error( $response ) ) {
			return \rest_ensure_response( $response );
		}
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Update or create an ad unit.
	 *
	 * @param WP_REST_Request $request Ad unit info.
	 * @return WP_REST_Response Updated ad unit info.
	 */
	public function api_update_adunit( $request ) {
		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );

		$params = $request->get_params();
		$adunit = array(
			'id'         => 0,
			'name'       => '',
			'sizes'      => array(),
			'ad_service' => '',
		);
		$args   = \wp_parse_args( $params, $adunit );
		// Update and existing or add a new ad unit.
		$adunit = ( 0 === $args['id'] )
			? $configuration_manager->add_ad_unit( $args )
			: $configuration_manager->update_ad_unit( $args );

		if ( \is_wp_error( $adunit ) ) {
			return \rest_ensure_response( $adunit );
		}

		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Delete an ad unit.
	 *
	 * @param WP_REST_Request $request Request with ID of ad unit to delete.
	 * @return WP_REST_Response Boolean Delete success.
	 */
	public function api_delete_adunit( $request ) {
		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );

		$params = $request->get_params();
		$id     = $params['id'];

		$configuration_manager->delete_ad_unit( $id );

		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Create the Media Kit page.
	 */
	public static function api_create_media_kit_page() {
		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );
		$edit_url = $configuration_manager->get_media_kit_page_edit_url();
		if ( ! $edit_url ) {
			$configuration_manager->create_media_kit_page();
		}
		return \rest_ensure_response(
			[
				'edit_url'    => $configuration_manager->get_media_kit_page_edit_url(),
				'page_status' => $configuration_manager->get_media_kit_page_status(),
			]
		);
	}

	/**
	 * Unpublish (revert to draft) the Media Kit page.
	 */
	public static function api_unpublish_media_kit_page() {
		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );
		$page_id = $configuration_manager->get_media_kit_page_id();
		if ( $page_id ) {
			$update = wp_update_post(
				[
					'ID'          => $page_id,
					'post_status' => 'draft',
				]
			);
			if ( $update === 0 || is_wp_error( $update ) ) {
				return rest_ensure_response( new WP_Error( 'update_failed', __( 'Failed to update page status.', 'newspack' ) ) );
			}
		}
		return \rest_ensure_response(
			[
				'edit_url'    => $configuration_manager->get_media_kit_page_edit_url(),
				'page_status' => $configuration_manager->get_media_kit_page_status(),
			]
		);
	}

	/**
	 * Retrieve all advertising data.
	 *
	 * @return array Advertising data.
	 */
	private function retrieve_data() {
		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );

		$services = $this->get_services();
		$error    = false;
		try {
			$ad_units = $configuration_manager->get_ad_units();
		} catch ( \Exception $error ) {
			$message = $error->getMessage();
			$error   = new WP_Error( 'newspack_ad_units', $message ? $message : __( 'Ad Units failed to fetch.', 'newspack-plugin' ) );
		}

		if ( \is_wp_error( $ad_units ) ) {
			$error = $ad_units;
		}

		return array(
			'services' => $services,
			'ad_units' => \is_wp_error( $ad_units ) ? array() : $ad_units,
			'error'    => $error,
		);
	}

	/**
	 * Retrieve state and information for each service.
	 *
	 * @return array Information about services.
	 */
	private function get_services() {
		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );

		$services = array();
		foreach ( $this->services as $service => $data ) {
			$services[ $service ] = array(
				'label'     => $data['label'],
				'enabled'   => $configuration_manager->is_service_enabled( $service ),
				'available' => true,
			);
		}

		// Verify GAM connection and run initial setup.
		$gam_connection_status = $configuration_manager->get_gam_connection_status();
		if ( \is_wp_error( $gam_connection_status ) ) {
			$error_type = $gam_connection_status->get_error_code();
			if ( 'newspack_ads_gam_api_fatal_error' === $error_type ) {
				$services['google_ad_manager']['available'] = false;
			}
			$services['google_ad_manager']['status']['error'] = $gam_connection_status->get_error_message();
		} else {
			$services['google_ad_manager']['status']             = $gam_connection_status;
			$services['google_ad_manager']['available_networks'] = $configuration_manager->get_gam_available_networks();
			if ( true === $gam_connection_status['connected'] && ! isset( $gam_connection_status['error'] ) ) {
				$services['google_ad_manager']['network_code'] = $gam_connection_status['network_code'];
				$gam_setup_results                             = $configuration_manager->setup_gam();
				if ( ! \is_wp_error( $gam_setup_results ) ) {
					$services['google_ad_manager']['created_targeting_keys'] = $gam_setup_results['created_targeting_keys'];
				} else {
					$services['google_ad_manager']['status']['error'] = $gam_setup_results->get_error_message();
				}
			}
		}

		return $services;
	}

	/**
	 * Sanitize the service name.
	 *
	 * @param string $service The service name.
	 * @return string
	 */
	public function sanitize_service( $service ) {
		return sanitize_title( $service );
	}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) !== $this->slug ) {
			return;
		}

		wp_enqueue_script(
			$this->slug,
			Newspack::plugin_url() . '/dist/billboard.js',
			$this->get_script_dependencies(),
			NEWSPACK_PLUGIN_VERSION,
			true
		);

		wp_register_style(
			$this->slug,
			Newspack::plugin_url() . '/dist/billboard.css',
			$this->get_style_dependencies(),
			NEWSPACK_PLUGIN_VERSION
		);
		wp_style_add_data( $this->slug, 'rtl', 'replace' );
		wp_enqueue_style( $this->slug );

		$configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-ads' );
		wp_localize_script(
			$this->slug,
			'newspack_ads_wizard',
			array(
				'iab_sizes'               => function_exists( '\Newspack_Ads\get_iab_sizes' ) ? \Newspack_Ads\get_iab_sizes() : array(),
				'media_kit_page_edit_url' => $configuration_manager->get_media_kit_page_edit_url(),
				'media_kit_page_status'   => $configuration_manager->get_media_kit_page_status(),
				'can_connect_google'      => OAuth::is_proxy_configured( 'google' ),
			)
		);
	}

	/**
	 * Sanitize array of ad unit sizes.
	 *
	 * @param array $sizes Array of sizes to sanitize.
	 * @return array Sanitized array.
	 */
	public static function sanitize_sizes( $sizes ) {
		$sizes     = is_array( $sizes ) ? $sizes : array();
		$sanitized = array();
		foreach ( $sizes as $size ) {
			$size    = is_array( $size ) && 2 === count( $size ) ? $size : array( 0, 0 );
			$size[0] = absint( $size[0] );
			$size[1] = absint( $size[1] );

			$sanitized[] = $size;
		}
		return $sanitized;
	}

	/**
	 * Add a parent menu for all the Advertising wizards (Ads, Sponsors Plugin CPT + Settings tab),
	 * and a first menu item too.
	 */
	public function add_page() {
		$icon = 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false" fill="none"><path d="M19 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5ZM19.5 17C19.5 17.1326 19.4473 17.2598 19.3536 17.3536C19.2598 17.4473 19.1326 17.5 19 17.5H5C4.86739 17.5 4.74021 17.4473 4.64645 17.3536C4.55268 17.2598 4.5 17.1326 4.5 17V7C4.5 6.86739 4.55268 6.74021 4.64645 6.64645C4.74021 6.55268 4.86739 6.5 5 6.5H19C19.1326 6.5 19.2598 6.55268 19.3536 6.64645C19.4473 6.74021 19.5 6.86739 19.5 7V17ZM16.007 9.736C15.568 9.50644 15.0783 9.3909 14.583 9.4H12.487V15H14.583C15.116 15 15.591 14.89 16.007 14.668C16.423 14.444 16.75 14.124 16.987 13.708C17.224 13.289 17.343 12.787 17.343 12.2C17.343 11.613 17.224 11.112 16.987 10.696C16.7603 10.2877 16.4198 9.95421 16.007 9.736ZM16.039 13.208C15.916 13.491 15.734 13.708 15.491 13.86C15.248 14.012 14.946 14.088 14.583 14.088H13.511V10.312H14.583C14.946 10.312 15.248 10.388 15.491 10.54C15.734 10.692 15.916 10.91 16.039 11.192C16.162 11.475 16.223 11.811 16.223 12.2C16.223 12.589 16.162 12.925 16.039 13.208ZM8.387 9.4L6.395 15H7.387L7.854 13.672H9.889L10.355 15H11.347L9.355 9.4H8.387ZM8.168 12.776L8.871 10.776L9.574 12.776H8.168Z"></path></svg>' );
		add_menu_page(
			$this->get_name(),
			$this->get_name(),
			$this->capability,
			$this->slug,
			array( $this, 'render_wizard' ),
			$icon
		);
		add_submenu_page(
			$this->slug,
			__( 'Advertising / Display Ads', 'newspack-plugin' ),
			__( 'Display Ads', 'newspack-plugin' ),
			$this->capability,
			$this->slug,
			array( $this, 'render_wizard' )
		);
	}
}
