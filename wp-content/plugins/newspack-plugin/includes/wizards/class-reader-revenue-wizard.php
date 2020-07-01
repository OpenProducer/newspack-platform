<?php
/**
 * Newspack's Reader Revenue Wizard
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
class Reader_Revenue_Wizard extends Wizard {

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-reader-revenue-wizard';

	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Reader Revenue', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Generate revenue from your customers.', 'newspack' );
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
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {

		// Get all data required to render the Wizard.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug,
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_fetch' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Save location info.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/location/',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_location' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'countrystate' => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
					'address1'     => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
					'address2'     => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'city'         => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
					'postcode'     => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
					'currency'     => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
				],
			]
		);

		// Save Stripe info.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/stripe/',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_stripe_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'enabled'            => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
					'testMode'           => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
					'publishableKey'     => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'secretKey'          => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'testPublishableKey' => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'testSecretKey'      => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
				],
			]
		);

		// Save a subscription.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/donations/',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_donation_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'image'               => [
						'sanitize_callback' => 'absint',
					],
					'name'                => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'suggestedAmount'     => [
						'sanitize_callback' => 'wc_format_decimal',
					],
					'suggestedAmountLow'  => [
						'sanitize_callback' => 'wc_format_decimal',
					],
					'suggestedAmountHigh' => [
						'sanitize_callback' => 'wc_format_decimal',
					],
					'tiered'              => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
				],
			]
		);

		// Save Salesforce settings.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/salesforce/',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_salesforce_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'client_id'     => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'client_secret' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'access_token'  => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'refresh_token' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);

		// Validate Salesforce client_id and client_secret credentials.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/salesforce/validate',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_validate_salesforce_creds' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Get access and refresh tokens from Salesforce.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/salesforce/tokens',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_get_salesforce_tokens' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'client_id'     => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'client_secret' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'redirect_uri'  => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);

		// Check validity of refresh token with Salesforce.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/salesforce/introspect',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_check_salesforce_token' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-donations-wizard/donation/',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'api_get_donation_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

	}

	/**
	 * Get all Wizard Data
	 *
	 * @return WP_REST_Response containing ad units info.
	 */
	public function api_fetch() {
		$required_plugins_installed = $this->check_required_plugins_installed();
		if ( is_wp_error( $required_plugins_installed ) ) {
			return rest_ensure_response( $required_plugins_installed );
		}
		return \rest_ensure_response( $this->fetch_all_data() );
	}

	/**
	 * Save location info.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Boolean success.
	 */
	public function api_update_location( $request ) {
		$required_plugins_installed = $this->check_required_plugins_installed();
		if ( is_wp_error( $required_plugins_installed ) ) {
			return rest_ensure_response( $required_plugins_installed );
		}
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );

		$params = $request->get_params();

		$defaults = [
			'countrystate' => '',
			'address1'     => '',
			'address2'     => '',
			'city'         => '',
			'postcode'     => '',
			'currency'     => '',
		];

		$args = wp_parse_args( $params, $defaults );
		$wc_configuration_manager->update_location( $args );

		// @todo when is the best time to do this?
		$wc_configuration_manager->set_smart_defaults();

		return \rest_ensure_response( $this->fetch_all_data() );
	}

	/**
	 * Save Stripe settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Boolean success.
	 */
	public function api_update_stripe_settings( $request ) {
		$required_plugins_installed = $this->check_required_plugins_installed();
		if ( is_wp_error( $required_plugins_installed ) ) {
			return rest_ensure_response( $required_plugins_installed );
		}
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );

		$params = $request->get_params();

		$defaults = [
			'enabled'            => false,
			'testMode'           => false,
			'publishableKey'     => '',
			'secretKey'          => '',
			'testPublishableKey' => '',
			'testSecretKey'      => '',
		];
		$args     = wp_parse_args( $params, $defaults );

		// If Stripe is enabled, make sure the API key fields are non-empty.
		if ( $args['enabled'] ) {
			if ( $args['testMode'] && ( ! $this->api_validate_not_empty( $args['testPublishableKey'] ) || ! $this->api_validate_not_empty( $args['testSecretKey'] ) ) ) {
				return new WP_Error(
					'newspack_missing_required_field',
					esc_html__( 'Test Publishable Key and Test Secret Key are required to use Stripe in test mode.', 'newspack' ),
					[
						'status' => 400,
						'level'  => 'notice',
					]
				);
			} elseif ( ! $args['testMode'] && ( ! $this->api_validate_not_empty( $args['publishableKey'] ) || ! $this->api_validate_not_empty( $args['secretKey'] ) ) ) {
				return new WP_Error(
					'newspack_missing_required_field',
					esc_html__( 'Publishable Key and Secret Key are required to use Stripe.', 'newspack' ),
					[
						'status' => 400,
						'level'  => 'notice',
					]
				);
			}
		}
		$wc_configuration_manager->update_stripe_settings( $args );

		// @todo when is the best time to do this?
		$wc_configuration_manager->set_smart_defaults();

		return \rest_ensure_response( $this->fetch_all_data() );
	}

	/**
	 * API endpoint for setting the donation settings.
	 *
	 * @param WP_REST_Request $request Request containing settings.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function api_update_donation_settings( $request ) {
		$required_plugins_installed = $this->check_required_plugins_installed();
		if ( is_wp_error( $required_plugins_installed ) ) {
			return rest_ensure_response( $required_plugins_installed );
		}

		$donations_response = Donations::set_donation_settings( $request->get_params() );
		if ( is_wp_error( $donations_response ) ) {
			return rest_ensure_response( $donations_response );
		}
		return \rest_ensure_response( $this->fetch_all_data() );
	}

	/**
	 * API endpoint for setting Salesforce settings.
	 *
	 * @param WP_REST_Request $request Request containing settings.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function api_update_salesforce_settings( $request ) {
		$salesforce_response = Salesforce::set_salesforce_settings( $request->get_params() );
		if ( is_wp_error( $salesforce_response ) ) {
			return rest_ensure_response( $salesforce_response );
		}
		return \rest_ensure_response( $this->fetch_all_data() );
	}

	/**
	 * API endpoint for validating Salesforce client id/secret credentials.
	 *
	 * @param WP_REST_Request $request Request containing settings.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function api_validate_salesforce_creds( $request ) {
		$args  = $request->get_params();
		$valid = false;

		// Must have a valid API key and secret.
		if ( ! empty( $args['client_id'] ) && ! empty( $args['client_secret'] ) ) {
			$url = 'https://login.salesforce.com/services/oauth2/authorize?response_type=code&' . http_build_query(
				[
					'client_id'     => $args['client_id'],
					'client_secret' => $args['client_secret'],
					'redirect_uri'  => $args['redirect_uri'],
				]
			);

			$response = wp_safe_remote_get( $url );
			$valid    = wp_remote_retrieve_response_code( $response ) === 200;
		}

		return \rest_ensure_response( $valid );
	}

	/**
	 * API endpoint for getting Salesforce API tokens.
	 *
	 * @param WP_REST_Request $request Request containing settings.
	 * @throws \Exception Error message.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function api_get_salesforce_tokens( $request ) {
		$args                = $request->get_params();
		$salesforce_settings = Salesforce::get_salesforce_settings();

		// Must have a valid API key and secret.
		if ( empty( $salesforce_settings['client_id'] ) || empty( $salesforce_settings['client_secret'] ) ) {
			throw new \Exception( 'Invalid Consumer Key or Secret.' );
		}

		// Hit Salesforce OAuth endpoint to request API tokens.
		$salesforce_response = wp_safe_remote_post(
			'https://login.salesforce.com/services/oauth2/token?' . http_build_query(
				array(
					'client_id'     => $salesforce_settings['client_id'],
					'client_secret' => $salesforce_settings['client_secret'],
					'code'          => $args['code'],
					'redirect_uri'  => $args['redirect_uri'],
					'grant_type'    => 'authorization_code',
					'format'        => 'json',
				)
			)
		);

		$response_body = json_decode( $salesforce_response['body'] );

		if ( ! empty( $response_body->access_token ) && ! empty( $response_body->refresh_token ) ) {
			$update_args = wp_parse_args( $response_body, $salesforce_settings );

			// Save tokens.
			$update_response = Salesforce::set_salesforce_settings( $update_args );
			if ( is_wp_error( $update_response ) ) {
				return \rest_ensure_response( $update_response );
			}

			return \rest_ensure_response( $update_args );
		}
	}

	/**
	 * API endpoint for checking validity of a Salesforce refresh token.
	 *
	 * @throws \Exception Error message.
	 * @return WP_REST_Response with the active status.
	 */
	public function api_check_salesforce_token() {
		$salesforce_settings = Salesforce::get_salesforce_settings();

		// Must have a valid API key and secret.
		if (
			empty( $salesforce_settings['client_id'] ) ||
			empty( $salesforce_settings['client_secret'] ) ||
			empty( $salesforce_settings['refresh_token'] )
		) {
			throw new \Exception( 'Invalid Consumer Key, Secret, or Refresh Token.' );
		}

		// Hit Salesforce OAuth endpoint to introspect refresh token.
		$salesforce_response = wp_safe_remote_post(
			'https://login.salesforce.com/services/oauth2/introspect?' . http_build_query(
				array(
					'client_id'     => $salesforce_settings['client_id'],
					'client_secret' => $salesforce_settings['client_secret'],
					'token'         => $salesforce_settings['refresh_token'],
				)
			)
		);

		$response_body = json_decode( $salesforce_response['body'] );

		return \rest_ensure_response( $response_body );
	}

	/**
	 * Fetch all data needed to render the Wizard
	 *
	 * @return Array
	 */
	protected function fetch_all_data() {
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
		return [
			'country_state_fields' => $wc_configuration_manager->country_state_fields(),
			'currency_fields'      => $wc_configuration_manager->currency_fields(),
			'location_data'        => $wc_configuration_manager->location_data(),
			'stripe_data'          => $wc_configuration_manager->stripe_data(),
			'donation_data'        => Donations::get_donation_settings(),
			'donation_page'        => Donations::get_donation_page_info(),
			'salesforce_settings'  => Salesforce::get_salesforce_settings(),
		];
	}

	/**
	 * API endpoint for getting donation settings.
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_get_donation_settings() {
		$required_plugins_installed = $this->check_required_plugins_installed();
		if ( is_wp_error( $required_plugins_installed ) ) {
			return rest_ensure_response( $required_plugins_installed );
		}

		return rest_ensure_response( Donations::get_donation_settings() );
	}

	/**
	 * Check whether WooCommerce is installed and active.
	 *
	 * @return bool | WP_Error True on success, WP_Error on failure.
	 */
	protected function check_required_plugins_installed() {
		if ( ! function_exists( 'WC' ) ) {
			return new WP_Error(
				'newspack_missing_required_plugin',
				esc_html__( 'The WooCommerce plugin is not installed and activated. Install and/or activate it to access this feature.', 'newspack' ),
				[
					'status' => 400,
					'level'  => 'fatal',
				]
			);
		}

		return true;
	}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();
		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}
		\wp_enqueue_media();
		\wp_enqueue_script(
			'newspack-reader-revenue-wizard',
			Newspack::plugin_url() . '/dist/readerRevenue.js',
			$this->get_script_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/readerRevenue.js' ),
			true
		);

		\wp_register_style(
			'newspack-reader-revenue-wizard',
			Newspack::plugin_url() . '/dist/readerRevenue.css',
			$this->get_style_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/readerRevenue.css' )
		);
		\wp_style_add_data( 'newspack-reader-revenue-wizard', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-reader-revenue-wizard' );
	}
}
