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

		// Save basic data about reader revenue platform.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug,
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'platform'                   => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => [ $this, 'api_validate_platform' ],
					],
					'nrh_organization_id'        => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
					'nrh_salesforce_campaign_id' => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
				],
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
					'newsletter_list_id' => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'fee_multiplier'     => [
						'sanitize_callback' => 'Newspack\newspack_clean',
						'validate_callback' => function ( $value ) {
							if ( (float) $value > 10 ) {
								return new WP_Error(
									'newspack_invalid_param',
									__( 'Fee multiplier must be smaller than 10.', 'newspack' )
								);
							};
							return true;
						},
					],
					'fee_static'         => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'location_code'      => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
				],
			]
		);

		// Update Donations settings.
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

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-donations-wizard/donation/',
			[
				'methods'             => \WP_REST_Server::READABLE,
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
		return \rest_ensure_response( $this->fetch_all_data() );
	}

	/**
	 * Save top-level data.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Boolean success.
	 */
	public function api_update( $request ) {
		$params   = $request->get_params();
		$platform = $params['platform'];
		Donations::set_platform_slug( $platform );
		if ( 'nrh' === $platform && isset( $params['nrh_organization_id'] ) ) {
			$nrh_config = [
				'nrh_organization_id' => $params['nrh_organization_id'],
			];
			if ( isset( $params['nrh_salesforce_campaign_id'] ) ) {
				$nrh_config['nrh_salesforce_campaign_id'] = $params['nrh_salesforce_campaign_id'];
			}
			update_option( NEWSPACK_NRH_CONFIG, $nrh_config );
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
	 * Handler for setting Stripe settings.
	 *
	 * @param object $settings Stripe settings.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function update_stripe_settings( $settings ) {
		$is_platform_wc = Donations::is_platform_wc();
		if ( $is_platform_wc ) {
			$required_plugins_installed = $this->check_required_plugins_installed();
			if ( is_wp_error( $required_plugins_installed ) ) {
				return rest_ensure_response( $required_plugins_installed );
			}
		}

		$args = wp_parse_args( $settings, Stripe_Connection::get_default_stripe_data() );
		// For WC, Stripe has to be enabled explicitly.
		if ( $is_platform_wc ? $args['enabled'] : true ) {
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

		Stripe_Connection::update_stripe_data( $args );
		return $this->fetch_all_data();
	}

	/**
	 * Save Stripe settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response.
	 */
	public function api_update_stripe_settings( $request ) {
		$params = $request->get_params();
		$result = $this->update_stripe_settings( $params );
		return \rest_ensure_response( $result );
	}

	/**
	 * Handler for setting the donation settings.
	 *
	 * @param object $settings Donation settings.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function update_donation_settings( $settings ) {
		$donations_response = Donations::set_donation_settings( $settings );
		if ( is_wp_error( $donations_response ) ) {
			return rest_ensure_response( $donations_response );
		}
		return \rest_ensure_response( $this->fetch_all_data() );
	}

	/**
	 * API endpoint for setting the donation settings.
	 *
	 * @param WP_REST_Request $request Request containing settings.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function api_update_donation_settings( $request ) {
		return $this->update_donation_settings( $request->get_params() );
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
	 * Fetch all data needed to render the Wizard
	 *
	 * @return Array
	 */
	public function fetch_all_data() {
		$platform                 = Donations::get_platform_slug();
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );
		$wc_installed             = $wc_configuration_manager->is_active();

		// Stipe data is used by both WC and Stripe platforms.
		$stripe_data                            = Stripe_Connection::get_stripe_data();
		$stripe_data['can_use_stripe_platform'] = Donations::can_use_stripe_platform();

		$args = [
			'country_state_fields' => newspack_get_countries(),
			'currency_fields'      => newspack_get_currencies_options(),
			'location_data'        => [],
			'stripe_data'          => $stripe_data,
			'donation_data'        => Donations::get_donation_settings(),
			'donation_page'        => Donations::get_donation_page_info(),
			'salesforce_settings'  => [],
			'platform_data'        => [
				'platform' => $platform,
			],
			'is_ssl'               => is_ssl(),
		];
		if ( 'wc' === $platform && $wc_installed ) {
			$plugin_status    = true;
			$managed_plugins  = Plugin_Manager::get_managed_plugins();
			$required_plugins = [
				'woocommerce',
				'woocommerce-gateway-stripe',
				'woocommerce-name-your-price',
				'woocommerce-subscriptions',
			];
			foreach ( $required_plugins as $required_plugin ) {
				if ( 'active' !== $managed_plugins[ $required_plugin ]['Status'] ) {
					$plugin_status = false;
				}
			}
			$args = wp_parse_args(
				[
					// A more complete list, with states for each country.
					'country_state_fields' => $wc_configuration_manager->country_state_fields(),
					'location_data'        => $wc_configuration_manager->location_data(),
					'salesforce_settings'  => Salesforce::get_salesforce_settings(),
					'plugin_status'        => $plugin_status,
				],
				$args
			);
		} elseif ( Donations::is_platform_nrh() ) {
			$nrh_config            = get_option( NEWSPACK_NRH_CONFIG, [] );
			$args['platform_data'] = wp_parse_args( $nrh_config, $args['platform_data'] );
		} elseif ( Donations::is_platform_stripe() ) {
			$args['stripe_data']['webhooks']         = Stripe_Connection::list_webhooks();
			$args['stripe_data']['connection_error'] = Stripe_Connection::get_connection_error();
		}
		return $args;
	}

	/**
	 * API endpoint for getting donation settings.
	 *
	 * @return WP_REST_Response containing info.
	 */
	public function api_get_donation_settings() {
		if ( Donations::is_platform_wc() ) {
			$required_plugins_installed = $this->check_required_plugins_installed();
			if ( is_wp_error( $required_plugins_installed ) ) {
				return rest_ensure_response( $required_plugins_installed );
			}
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
		\wp_register_script(
			'newspack-reader-revenue-wizard',
			Newspack::plugin_url() . '/dist/readerRevenue.js',
			$this->get_script_dependencies(),
			NEWSPACK_PLUGIN_VERSION,
			true
		);
		\wp_localize_script(
			'newspack-reader-revenue-wizard',
			'newspack_reader_revenue',
			[
				'emails'                  => Reader_Revenue_Emails::get_emails(),
				'salesforce_redirect_url' => Salesforce::get_redirect_url(),
			]
		);
		\wp_enqueue_script( 'newspack-reader-revenue-wizard' );
	}

	/**
	 * Validate platform ID.
	 *
	 * @param mixed $value A param value.
	 * @return bool
	 */
	public function api_validate_platform( $value ) {
		return in_array( $value, [ 'nrh', 'wc', 'stripe' ] );
	}
}
