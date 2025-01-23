<?php
/**
 * Newspack's Analytics Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use Google\Site_Kit_Dependencies\Google\Service\Analytics as Google_Service_Analytics;
use Google\Site_Kit_Dependencies\Google\Service\Analytics\CustomDimension as Google_Service_Analytics_CustomDimension;

use WP_Error, WP_Query;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Easy interface for setting up general store info.
 */
class Analytics_Wizard extends Wizard {

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-analytics-wizard';

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
		parent::__construct();
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Analytics', 'newspack' );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/analytics/ga4-credentials',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_set_ga4_credentials' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'measurement_id'              => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'validate_measurement_id' ],
					],
					'measurement_protocol_secret' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Validates the Measurement ID
	 *
	 * @param string $value The value to validate.
	 * @return bool
	 */
	public function validate_measurement_id( $value ) {
		return is_string( $value ) && strpos( $value, 'G-' ) === 0;
	}

	/**
	 * Gets the credentials for the GA4 API.
	 *
	 * @return array
	 */
	public static function get_ga4_credentials() {
		$measurement_protocol_secret = get_option( 'ga4_measurement_protocol_secret', '' );
		$measurement_id              = get_option( 'ga4_measurement_id', '' );
		return compact( 'measurement_protocol_secret', 'measurement_id' );
	}

	/**
	 * Updates the GA4 crendetials
	 *
	 * @param WP_REST_Request $request The REST request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function api_set_ga4_credentials( $request ) {
		$measurement_id              = $request->get_param( 'measurement_id' );
		$measurement_protocol_secret = $request->get_param( 'measurement_protocol_secret' );

		if ( ! $measurement_id || ! $measurement_protocol_secret ) {
			return new \WP_Error(
				'newspack_analytics_wizard_invalid_params',
				\esc_html__( 'Invalid parameters.', 'newspack' ),
				[ 'status' => 400 ]
			);
		}

		update_option( 'ga4_measurement_id', $measurement_id );
		update_option( 'ga4_measurement_protocol_secret', $measurement_protocol_secret );

		return rest_ensure_response( $this->get_ga4_credentials() );
	}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) !== $this->slug ) {
			return;
		}

		\wp_enqueue_script(
			'newspack-analytics-wizard',
			Newspack::plugin_url() . '/dist/analytics.js',
			[ 'wp-components', 'wp-api-fetch' ],
			NEWSPACK_PLUGIN_VERSION,
			true
		);

		\wp_localize_script(
			'newspack-analytics-wizard',
			'newspack_analytics_wizard_data',
			[
				'ga4_credentials' => $this->get_ga4_credentials(),
			]
		);

		\wp_register_style(
			'newspack-analytics-wizard',
			Newspack::plugin_url() . '/dist/analytics.css',
			$this->get_style_dependencies(),
			NEWSPACK_PLUGIN_VERSION
		);
		\wp_style_add_data( 'newspack-analytics-wizard', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-analytics-wizard' );
	}
}
