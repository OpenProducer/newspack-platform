<?php
/**
 * Performance Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Easy interface for setting up general store info.
 */
class Performance_Wizard extends Wizard {
	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-performance-wizard';

	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'install_plugins';

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
		return esc_html__( 'Performance', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return esc_html__( 'Set up and maintain Progressive Web App functionality.', 'newspack' );
	}

	/**
	 * Get the expected duration of this wizard.
	 *
	 * @return string The wizard length.
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
			'/wizard/performance/',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/performance/',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'api_update_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
	}

	/**
	 * Handle GET endpoint.
	 */
	public function api_get_settings() {
		return rest_ensure_response( $this->get_settings() );
	}

	/**
	 * Handle POST endpoint.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 */
	public function api_update_settings( $request ) {
		if ( empty( $request['settings'] ) ) {
			return new WP_Error( 'newspack_invalid_update', __( 'Invalid update' ) );
		}

		$settings = $request['settings'];
		if ( isset( $settings['site_icon'] ) ) {
			$height = isset( $settings['site_icon']['height'] ) ? $settings['site_icon']['height'] : 0;
			$width  = isset( $settings['site_icon']['width'] ) ? $settings['site_icon']['width'] : 0;
			if ( $height !== $width ) {
				return new WP_Error(
					'newspack_site_icon_not_square',
					__( 'Site icon images must be square.' ),
					[
						'status' => 400,
						'level'  => 'notice',
					]
				);
			}
			PWA::update_site_icon( $settings['site_icon'] );
		}

		return rest_ensure_response( $this->get_settings() );
	}

	/**
	 * Get all settings
	 *
	 * @return array of settings info.
	 */
	public function get_settings() {
		$configured = PWA::check_configured();
		$return     = [
			'site_icon' => PWA::get_site_icon(),
		];

		if ( is_wp_error( $configured ) ) {
			$return['configured'] = false;
			$return['error']      = $configured->get_error_message();
		} else {
			$return['configured'] = true;
		}

		return $return;
	}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();
		wp_enqueue_media();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}

		wp_enqueue_script(
			'newspack-performance-wizard',
			Newspack::plugin_url() . '/dist/performance.js',
			$this->get_script_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/performance.js' ),
			true
		);
	}
}
