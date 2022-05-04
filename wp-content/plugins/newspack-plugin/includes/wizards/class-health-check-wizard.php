<?php
/**
 * Health Check Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error, \WP_Query;
use Newspack\Plugin_Manager;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Easy interface for setting up general store info.
 */
class Health_Check_Wizard extends Wizard {

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-health-check-wizard';

	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'activate_plugins';

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Health Check', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Verify and improve the health of the site.', 'newspack' );
	}

	/**
	 * Get the duration of this wizard.
	 *
	 * @return string A description of the expected duration (e.g. '10 minutes').
	 */
	public function get_length() {
		return esc_html__( '10 minutes', 'newspack' );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug,
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_health_data' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/repair/(?P<configuration>[\a-z]+)/',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_repair_configuration' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'configuration' => [
						'sanitize_callback' => [ $this, 'sanitize_configuration' ],
					],
				],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/unsupported_plugins',
			[
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'api_delete_unsupported_plugins' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
	}

	/**
	 * Repair service API callback.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response containing ad units info.
	 */
	public function api_repair_configuration( $request ) {
		$configuration = $request['configuration'];
		switch ( $configuration ) {
			case 'amp':
				$amp_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'amp' );
				$amp_manager->configure();
				break;
		}
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Get all data needed  to render the Wizard.
	 */
	public function api_get_health_data() {
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Delete all unsupported plugins
	 */
	public function api_delete_unsupported_plugins() {
		$unsupported_plugins = Plugin_Manager::get_unsupported_plugins();
		foreach ( $unsupported_plugins as $slug => $data ) {
			Plugin_Manager::deactivate( $slug );
		}
		return \rest_ensure_response( $this->retrieve_data() );
	}

	/**
	 * Retrieve all advertising data.
	 *
	 * @return array Advertising data.
	 */
	public static function retrieve_data() {
		$amp_manager     = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'amp' );
		$jetpack_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'jetpack' );
		$sitekit_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'google-site-kit' );

		return array(
			'unsupported_plugins'  => Plugin_Manager::get_unsupported_plugins(),
			'missing_plugins'      => Plugin_Manager::get_missing_plugins(),
			'configuration_status' => [
				'amp'     => $amp_manager->is_standard_mode(),
				'jetpack' => $jetpack_manager->is_configured(),
				'sitekit' => $sitekit_manager->is_configured(),
			],
		);
	}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}

		\wp_enqueue_script(
			'newspack-health-check-wizard',
			Newspack::plugin_url() . '/dist/health-check.js',
			[ 'wp-components', 'wp-api-fetch' ],
			NEWSPACK_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Sanitize the configuration name.
	 *
	 * @param string $configuration The configuration name.
	 * @return string
	 */
	public function sanitize_configuration( $configuration ) {
		$configuration        = sanitize_title( $configuration );
		$valid_configurations = [ 'amp' ];
		return in_array( $configuration, $valid_configurations ) ? $configuration : null;
	}
}
