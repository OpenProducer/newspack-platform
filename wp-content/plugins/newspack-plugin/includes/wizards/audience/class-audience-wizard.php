<?php
/**
 * Audience Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use Newspack\{
	Newsletters,
	Reader_Activation
};
use Newspack_Newsletters_Subscription;
use WP_Error, WP_REST_Request, WP_REST_Response, WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * Audience Wizard.
 */
class Audience_Wizard extends Wizard {

	/**
	 * Option to skip campaign setup.
	 *
	 * @var string
	 */
	const SKIP_CAMPAIGN_SETUP_OPTION = '_newspack_ras_skip_campaign_setup';

	/**
	 * Admin page slug.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-audience';

	/**
	 * The parent menu item name.
	 *
	 * @var string
	 */
	public $parent_menu = 'newspack-audience';

	/**
	 * Parent menu order relative to the Newspack Dashboard menu item.
	 *
	 * @var int
	 */
	public $parent_menu_order = 1;

	/**
	 * Audience Configuration Constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );

		// Determine active menu items.
		add_filter( 'parent_file', [ $this, 'parent_file' ] );
		add_filter( 'submenu_file', [ $this, 'submenu_file' ] );
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return Reader_Activation::is_enabled() ?
			esc_html__( 'Audience Management / Configuration', 'newspack-plugin' ) :
			esc_html__( 'Audience Management / Setup', 'newspack-plugin' );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		if ( ! $this->is_wizard_page() ) {
			return;
		}
		parent::enqueue_scripts_and_styles();
		$salesforce_settings = Salesforce::get_salesforce_settings();
		$data = [
			'has_memberships'         => class_exists( 'WC_Memberships' ),
			'reader_activation_url'   => admin_url( 'admin.php?page=newspack-audience#/' ),
			'esp_metadata_fields'     => Reader_Activation\Sync\Metadata::get_default_fields(),
			'can_use_salesforce'      => ! empty( $salesforce_settings['client_id'] ),
			'salesforce_redirect_url' => Salesforce::get_redirect_url(),
		];

		if ( method_exists( 'Newspack\Newsletters\Subscription_Lists', 'get_add_new_url' ) ) {
			$data['new_subscription_lists_url'] = Newsletters\Subscription_Lists::get_add_new_url();
		}

		if ( method_exists( 'Newspack_Newsletters_Subscription', 'get_lists' ) ) {
			$data['available_newsletter_lists'] = Newspack_Newsletters_Subscription::get_lists();
		}

		$newspack_popups = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'newspack-popups' );

		if ( $newspack_popups->is_configured() ) {
			$data['preview_query_keys'] = $newspack_popups->preview_query_keys();
			$data['preview_post']       = $newspack_popups->preview_post();
			$data['preview_archive']    = $newspack_popups->preview_archive();
		}

		$data['is_skipped_campaign_setup'] = Reader_Activation::is_skipped( 'ras_campaign' );

		wp_enqueue_script( 'newspack-wizards' );

		wp_localize_script(
			'newspack-wizards',
			'newspackAudience',
			$data
		);
	}

	/**
	 * Add Audience top-level and Configuration subpage to the /wp-admin menu.
	 */
	public function add_page() {
		// svg source - https://wphelpers.dev/icons/people
		// SVG generated via https://boxy-svg.com/ with path width/height 20px.
		$icon = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg viewBox="20 20 24 24" xmlns="http://www.w3.org/2000/svg">
  <path fill="none" stroke="none" d="M 36.242 29.578 C 37.176 29.578 37.759 28.568 37.292 27.76 C 37.075 27.385 36.675 27.154 36.242 27.154 C 35.309 27.154 34.726 28.164 35.193 28.972 C 35.41 29.347 35.81 29.578 36.242 29.578 Z M 36.242 31.396 C 38.576 31.396 40.033 28.872 38.867 26.851 C 38.325 25.913 37.325 25.336 36.242 25.336 C 33.909 25.336 32.452 27.861 33.618 29.881 C 34.16 30.819 35.16 31.396 36.242 31.396 Z M 33.515 38.669 L 33.515 36.245 C 33.515 34.404 32.023 32.912 30.182 32.912 L 25.333 32.912 C 23.492 32.912 22 34.404 22 36.245 L 22 38.669 L 23.818 38.669 L 23.818 36.245 C 23.818 35.409 24.497 34.73 25.333 34.73 L 30.182 34.73 C 31.018 34.73 31.697 35.409 31.697 36.245 L 31.697 38.669 L 33.515 38.669 Z M 42 36.245 L 42 38.669 L 40.182 38.669 L 40.182 36.245 C 40.182 35.409 39.503 34.73 38.667 34.73 L 35.636 34.73 L 35.636 32.912 L 38.667 32.912 C 40.508 32.912 42 34.404 42 36.245 Z M 28.97 28.366 C 28.97 29.299 27.96 29.882 27.152 29.416 C 26.777 29.199 26.545 28.799 26.545 28.366 C 26.545 27.433 27.555 26.85 28.364 27.316 C 28.738 27.533 28.97 27.933 28.97 28.366 Z M 30.788 28.366 C 30.788 30.699 28.263 32.156 26.242 30.99 C 25.304 30.448 24.727 29.448 24.727 28.366 C 24.727 26.033 27.252 24.576 29.273 25.742 C 30.211 26.284 30.788 27.284 30.788 28.366 Z"/>
</svg>'
		);
		add_menu_page(
			$this->get_name(),
			__( 'Audience', 'newspack-plugin' ),
			$this->capability,
			$this->slug,
			[ $this, 'render_wizard' ],
			$icon
		);
		add_submenu_page(
			$this->slug,
			$this->get_name(),
			Reader_Activation::is_enabled() ?
				__( 'Configuration', 'newspack-plugin' ) :
				__( 'Setup', 'newspack-plugin' ),
			$this->capability,
			$this->slug,
			[ $this, 'render_wizard' ]
		);
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/audience-management',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_reader_activation_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/audience-management',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_reader_activation_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/audience-management/activate',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_activate_reader_activation' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/audience-management/emails/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'api_reset_reader_activation_email' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/audience-management/skip',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_skip_prerequisite' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'prerequisite' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'skip'         => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
				],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/content-gating',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_content_gating_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/content-gating',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_content_gating_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Get Salesforce settings.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/salesforce',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_salesforce_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Save Salesforce settings.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/salesforce',
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

		// Get payment settings data.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/payment',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_payment_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Save basic data about reader revenue platform.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/payment',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_payment_settings' ],
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
					'nrh_custom_domain'          => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'nrh_salesforce_campaign_id' => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
					'donor_landing_page'         => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
				],
			]
		);

		// Get billing fields info.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/billing-fields',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_billing_fields' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Update billing fields info.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/billing-fields',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_billing_fields' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'billing_fields' => [
						'sanitize_callback' => [ $this, 'sanitize_string_array' ],
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
				],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/checkout-configuration',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ Reader_Activation::class, 'get_checkout_configuration' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/checkout-configuration',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_checkout_configuration' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'billing_fields' => [
						'sanitize_callback' => [ $this, 'sanitize_string_array' ],
						'validate_callback' => [ $this, 'api_validate_not_empty' ],
					],
				],
			]
		);

		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/subscription-settings',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_subscription_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/subscription-settings',
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_subscription_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Save Stripe info.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/payment/stripe/',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_stripe_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'activate'      => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
					'enabled'       => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
					'location_code' => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
				],
			]
		);

		// Save payment gatway info.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/' . $this->slug . '/payment/gateway/',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'api_update_gateway_settings' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
				'args'                => [
					'activate' => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
					'enabled'  => [
						'sanitize_callback' => 'Newspack\newspack_string_to_bool',
					],
					'slug'     => [
						'sanitize_callback' => 'Newspack\newspack_clean',
					],
				],
			]
		);
	}

	/**
	 * Get reader activation settings.
	 *
	 * @return WP_REST_Response
	 */
	public function api_get_reader_activation_settings() {
		return rest_ensure_response(
			[
				'config'               => Reader_Activation::get_settings(),
				'prerequisites_status' => Reader_Activation::get_prerequisites_status(),
				'memberships'          => self::get_memberships_settings(),
				'can_esp_sync'         => Reader_Activation\ESP_Sync::can_esp_sync( true ),
			]
		);
	}

	/**
	 * Update reader activation settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function api_update_reader_activation_settings( $request ) {
		$args = $request->get_params();
		foreach ( $args as $key => $value ) {
			Reader_Activation::update_setting( $key, $value );
		}

		return rest_ensure_response(
			[
				'config'               => Reader_Activation::get_settings(),
				'prerequisites_status' => Reader_Activation::get_prerequisites_status(),
				'memberships'          => self::get_memberships_settings(),
				'can_esp_sync'         => Reader_Activation\ESP_Sync::can_esp_sync( true ),
			]
		);
	}

	/**
	 * Reset reader activation email template.
	 * We acheive this by trashing the email template post.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function api_reset_reader_activation_email( $request ) {
		$params = $request->get_params();
		$id     = $params['id'];
		$email  = get_post( $id );

		if ( $email === null || $email->post_type !== Emails::POST_TYPE ) {
			return new WP_Error(
				'newspack_reset_reader_activation_email_invalid_arg',
				esc_html__( 'Invalid argument: no email template matches the provided id.', 'newspack-plugin' ),
				[
					'status' => 400,
					'level'  => 'notice',
				]
			);
		}

		if ( ! \wp_trash_post( $id ) ) {
			return new WP_Error(
				'newspack_reset_reader_activation_email_reset_failed',
				esc_html__( 'Reset failed: unable to reset email template.', 'newspack-plugin' ),
				[
					'status' => 400,
					'level'  => 'notice',
				]
			);
		}

		return rest_ensure_response(
			Emails::get_emails( Reader_Activation::is_enabled() ? [] : array_values( Reader_Revenue_Emails::EMAIL_TYPES ), false )
		);
	}

	/**
	 * Activate reader activation and publish RAS prompts/segments.
	 *
	 * @param WP_REST_Request $request WP Rest Request object.
	 * @return WP_REST_Response
	 */
	public function api_activate_reader_activation( WP_REST_Request $request ) {
		$skip_activation = $request->get_param( 'skip_activation' ) ?? false;
		$response = $skip_activation ? true : Reader_Activation::activate();

		if ( is_wp_error( $response ) ) {
			return new WP_REST_Response( [ 'message' => $response->get_error_message() ], 400 );
		}

		if ( true === $response ) {
			Reader_Activation::update_setting( 'enabled', true );
		}

		return rest_ensure_response( $response );
	}

	/**
	 * Activate reader activation and publish RAS prompts/segments.
	 *
	 * @param WP_REST_Request $request WP Rest Request object.
	 * @return WP_REST_Response
	 */
	public function api_skip_prerequisite( WP_REST_Request $request ) {
		$preqrequisite       = $request->get_param( 'prerequisite' );
		$skip                = $request->get_param( 'skip' );
		$skip_campaign_setup = Reader_Activation::skip( $preqrequisite, $skip );
		if ( ! $skip_campaign_setup ) {
			return new WP_REST_Response( [ 'message' => __( 'Error skipping prerequisite.', 'newspack-plugin' ) ], 400 );
		}

		return rest_ensure_response(
			[
				'config'               => Reader_Activation::get_settings(),
				'prerequisites_status' => Reader_Activation::get_prerequisites_status(),
				'memberships'          => self::get_memberships_settings(),
				'can_esp_sync'         => Reader_Activation\ESP_Sync::can_esp_sync( true ),
			]
		);
	}

	/**
	 * Get content gating settings.
	 *
	 * @return WP_REST_Response
	 */
	public function api_get_content_gating_settings() {
		return rest_ensure_response( self::get_memberships_settings() );
	}

	/**
	 * Update content gating settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function api_update_content_gating_settings( $request ) {
		$args = $request->get_params();
		if ( isset( $args['require_all_plans'] ) ) {
			Memberships::set_require_all_plans_setting( (bool) $args['require_all_plans'] );
		}
		if ( isset( $args['show_on_subscription_tab'] ) ) {
			Memberships::set_show_on_subscription_tab_setting( (bool) $args['show_on_subscription_tab'] );
		}
		return rest_ensure_response( self::get_memberships_settings() );
	}

	/**
	 * API endpoint to get Salesforce settings.
	 *
	 * @return WP_REST_Response with Salesforce settings.
	 */
	public function api_get_salesforce_settings() {
		return \rest_ensure_response( Salesforce::get_salesforce_settings() );
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
		return \rest_ensure_response( Salesforce::get_salesforce_settings() );
	}

	/**
	 * Validate platform ID.
	 *
	 * @param mixed $value A param value.
	 * @return bool
	 */
	public function api_validate_platform( $value ) {
		return in_array( $value, [ 'nrh', 'wc', 'other' ] );
	}

	/**
	 * Get payment settings.
	 *
	 * @return WP_REST_Response containing ad units info.
	 */
	public function api_get_payment_settings() {
		return \rest_ensure_response( $this->get_payment_data() );
	}

	/**
	 * Sanitize payment billing fields.
	 *
	 * @param mixed $value A param value.
	 *
	 * @return array
	 */
	public function sanitize_string_array( $value ) {
		return is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : [];
	}

	/**
	 * Set payment settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Boolean success.
	 */
	public function api_update_payment_settings( $request ) {
		$params = $request->get_params();

		Donations::set_platform_slug( $params['platform'] );

		// Update NRH settings.
		if ( Donations::is_platform_nrh() ) {
			NRH::update_settings( $params );
		}

		// Ensure that any Reader Revenue settings changed while the platform wasn't WC are persisted to WC products.
		if ( Donations::is_platform_wc() ) {
			Donations::update_donation_product( Donations::get_donation_settings() );
		}

		return \rest_ensure_response( $this->get_payment_data() );
	}

	/**
	 * API callback to get billing fields.
	 *
	 * @return WP_REST_Response Response.
	 */
	public function api_get_billing_fields() {
		return \rest_ensure_response( $this->get_billing_fields() );
	}

	/**
	 * Update billing fields.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response.
	 */
	public function api_update_billing_fields( $request ) {
		$params = $request->get_params();
		Donations::update_billing_fields( $params['billing_fields'] );
		return \rest_ensure_response( $this->get_billing_fields() );
	}

	/**
	 * API callback to update checkout configuration.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response.
	 */
	public function api_update_checkout_configuration( WP_REST_Request $request ): WP_REST_Response {
		$params = $request->get_params();
		$checkout_options = [
			[ Reader_Activation::OPTIONS_PREFIX . 'woocommerce_registration_required', $params['woocommerce_registration_required'] ],
			[ Reader_Activation::OPTIONS_PREFIX . 'woocommerce_post_checkout_success_text', $params['woocommerce_post_checkout_success_text'] ],
			[ Reader_Activation::OPTIONS_PREFIX . 'woocommerce_checkout_privacy_policy_text', $params['woocommerce_checkout_privacy_policy_text'] ],
			[ Reader_Activation::OPTIONS_PREFIX . 'woocommerce_post_checkout_registration_success_text', $params['woocommerce_post_checkout_registration_success_text'] ],
		];
		foreach ( $checkout_options as $option ) {
			[ $key, $value ] = $option;
			update_option( $key, $value );
		}
		return rest_ensure_response( Reader_Activation::get_checkout_configuration() );
	}

	/**
	 * Get billing fields data.
	 */
	public function get_billing_fields() {
		$wc_installed = 'active' === Plugin_Manager::get_managed_plugin_status( 'woocommerce' );

		$available_billing_fields = [];
		$order_notes_field = [];

		if ( $wc_installed && Donations::is_platform_wc() ) {
			$checkout        = new \WC_Checkout();
			$fields          = $checkout->get_checkout_fields();
			if ( ! empty( $fields['order']['order_comments'] ) ) {
				$order_notes_field = $fields['order']['order_comments'];
			}
			if ( ! empty( $fields['billing'] ) ) {
				$available_billing_fields = $fields['billing'];
			}
		}
		return [
			'available_billing_fields' => $available_billing_fields,
			'billing_fields'           => Donations::get_billing_fields(),
			'order_notes_field'        => $order_notes_field,
		];
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
	 * Handler for setting Stripe settings.
	 *
	 * @param object $settings Stripe settings.
	 * @return WP_REST_Response with the latest settings.
	 */
	public function update_stripe_settings( $settings ) {
		if ( ! empty( $settings['activate'] ) ) {
			// If activating the Stripe Gateway plugin, let's enable it.
			$settings = [ 'enabled' => true ];
		}
		$result = Stripe_Connection::update_stripe_data( $settings );
		if ( \is_wp_error( $result ) ) {
			return $result;
		}

		return $this->get_payment_data();
	}

	/**
	 * Save WooPayments settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response.
	 */
	public function api_update_gateway_settings( $request ) {
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );

		$params = $request->get_params();
		if ( ! isset( $params['slug'] ) ) {
			return \rest_ensure_response(
				new WP_Error( 'newspack_invalid_param', __( 'Gateway slug is required.', 'newspack' ) )
			);
		}
		$slug = $params['slug'];
		unset( $params['slug'] );
		$result = $wc_configuration_manager->update_gateway_settings( $slug, $params );
		return \rest_ensure_response( $result );
	}

	/**
	 * Get payment data for the wizard.
	 *
	 * @return Array
	 */
	public function get_payment_data() {
		$platform                 = Donations::get_platform_slug();
		$wc_configuration_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'woocommerce' );

		$args = [
			'payment_gateways' => [
				'stripe'               => Stripe_Connection::get_stripe_data(),
				'woocommerce_payments' => $wc_configuration_manager->gateway_data( 'woocommerce_payments' ),
				'ppcp-gateway'         => $wc_configuration_manager->gateway_data( 'ppcp-gateway' ),
			],
			'platform_data'    => [
				'platform' => $platform,
			],
			'is_ssl'           => is_ssl(),
			'errors'           => [],
		];
		if ( 'wc' === $platform ) {
			$plugin_status    = true;
			$managed_plugins  = Plugin_Manager::get_managed_plugins();
			$required_plugins = [
				'woocommerce',
				'woocommerce-subscriptions',
			];
			foreach ( $required_plugins as $required_plugin ) {
				if ( 'active' !== $managed_plugins[ $required_plugin ]['Status'] ) {
					$plugin_status = false;
				}
			}
			$args = wp_parse_args(
				[
					'plugin_status' => $plugin_status,
				],
				$args
			);
		} elseif ( Donations::is_platform_nrh() ) {
			$nrh_config            = NRH::get_settings();
			$args['platform_data'] = wp_parse_args( $nrh_config, $args['platform_data'] );
		}
		return $args;
	}

	/**
	 * Get memberships settings.
	 *
	 * @return array
	 */
	private static function get_memberships_settings() {
		return [
			'edit_gate_url'            => Memberships::get_edit_gate_url(),
			'gate_status'              => get_post_status( Memberships::get_gate_post_id() ),
			'plans'                    => Memberships::get_plans(),
			'require_all_plans'        => Memberships::get_require_all_plans_setting(),
			'show_on_subscription_tab' => Memberships::get_show_on_subscription_tab_setting(),
		];
	}

	/**
	 * Parent file filter. Used to determine active menu items.
	 *
	 * @param string $parent_file Parent file to be overridden.
	 * @return string
	 */
	public function parent_file( $parent_file ) {
		global $pagenow, $typenow;

		$cpts = [
			Memberships::GATE_CPT,
			Emails::POST_TYPE,
		];

		if ( in_array( $pagenow, [ 'post.php', 'post-new.php' ] ) && in_array( $typenow, $cpts ) ) {
			return $this->slug;
		}

		return $parent_file;
	}

	/**
	 * Submenu file filter. Used to determine active submenu items.
	 *
	 * @param string $submenu_file Submenu file to be overridden.
	 * @return string
	 */
	public function submenu_file( $submenu_file ) {
		global $pagenow, $typenow;

		$cpts = [
			Memberships::GATE_CPT,
			Emails::POST_TYPE,
		];

		if ( in_array( $pagenow, [ 'post.php', 'post-new.php' ] ) && in_array( $typenow, $cpts ) ) {
			return $this->slug;
		}

		return $submenu_file;
	}

	/**
	 * Get subscription settings.
	 *
	 * @return WP_REST_Response Response with the settings.
	 */
	public function api_get_subscription_settings() {
		$settings = [
			'woocommerce_enable_subscription_confirmation' => get_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_enable_subscription_confirmation', false ),
			'woocommerce_subscription_confirmation_text'   => get_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_subscription_confirmation_text', Reader_Activation::get_subscription_confirmation_text() ),
			'woocommerce_enable_terms_confirmation'        => get_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_enable_terms_confirmation', false ),
			'woocommerce_terms_confirmation_text'          => get_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_terms_confirmation_text', Reader_Activation::get_terms_confirmation_text() ),
			'woocommerce_terms_confirmation_url'           => get_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_terms_confirmation_url', Reader_Activation::get_terms_confirmation_url() ),
		];
		return rest_ensure_response( $settings );
	}

	/**
	 * Update subscription settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response with the updated settings.
	 */
	public function api_update_subscription_settings( $request ) {
		$wc_installed = 'active' === Plugin_Manager::get_managed_plugin_status( 'woocommerce' );
		if ( ! $wc_installed ) {
			return $this->api_get_subscription_settings();
		}

		$params = $request->get_params();
		if ( isset( $params['woocommerce_enable_subscription_confirmation'] ) ) {
			update_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_enable_subscription_confirmation', (bool) $params['woocommerce_enable_subscription_confirmation'] );
		}

		if ( isset( $params['woocommerce_subscription_confirmation_text'] ) ) {
			update_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_subscription_confirmation_text', sanitize_text_field( $params['woocommerce_subscription_confirmation_text'] ) );
		}

		if ( isset( $params['woocommerce_enable_terms_confirmation'] ) ) {
			update_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_enable_terms_confirmation', (bool) $params['woocommerce_enable_terms_confirmation'] );
		}

		if ( isset( $params['woocommerce_terms_confirmation_text'] ) ) {
			update_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_terms_confirmation_text', sanitize_text_field( $params['woocommerce_terms_confirmation_text'] ) );
		}

		if ( isset( $params['woocommerce_terms_confirmation_url'] ) ) {
			update_option( \Newspack\Reader_Activation::OPTIONS_PREFIX . 'woocommerce_terms_confirmation_url', sanitize_url( $params['woocommerce_terms_confirmation_url'] ) );
		}

		return $this->api_get_subscription_settings();
	}
}
