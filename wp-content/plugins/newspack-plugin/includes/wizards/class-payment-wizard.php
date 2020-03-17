<?php
/**
 * Newspack's Payment Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error, \WP_Query;
use Automattic\Jetpack\Connection\Client;
use Jetpack_Options;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Interface for managing payments for Newspack hosted plan.
 */
class Payment_Wizard extends Wizard {

	const NEWSPACK_STRIPE_CUSTOMER     = '_newspack_hosted_stripe_customer';
	const NEWSPACK_STRIPE_SUBSCRIPTION = '_newspack_hosted_stripe_subscription';

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-payment-wizard';

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
		add_action( 'init', [ $this, 'retrieve_subscription' ] );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {

		// Get data about Stripe customer/subscription.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-payment-wizard/',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_stripe_data' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Create a Stripe checkout session, return information needed to redirect to it.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-payment-wizard/checkout',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_get_stripe_checkout_id' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
	}

	/**
	 * Get Stripe data, if available.
	 */
	public function api_get_stripe_data() {
		if ( ! class_exists( 'Jetpack_Options' ) || ! class_exists( 'Automattic\Jetpack\Connection\Client' ) ) {
			return new WP_Error( 'jetpack_not_present', __( 'This feature requires Jetpack to be installed, activated, and enabled', 'newspack' ) );
		}
		$path   = '/newspack/get_stripe_data/';
		$args   = [ 'method' => 'POST' ];
		$params = [
			'customer_id'     => sanitize_text_field( get_option( self::NEWSPACK_STRIPE_CUSTOMER, '' ) ),
			'subscription_id' => sanitize_text_field( get_option( self::NEWSPACK_STRIPE_SUBSCRIPTION, '' ) ),
			'mode'            => self::stripe_mode(),
		];
		$result = Client::wpcom_json_api_request_as_blog(
			$path,
			'1.1',
			$args,
			$params
		);

		$body = json_decode( wp_remote_retrieve_body( $result ), true );
		if ( isset( $body['error'] ) ) {
			return new WP_Error( $body['error'], $body['message'] );
		}
		if ( ! isset( $body['data'] ) ) {
			return new WP_Error( 'newspack_payment_error', __( 'Newspack payment error. Please contact support', 'newspack' ) );
		}
		return \rest_ensure_response( $body['data'] );
	}

	/**
	 * Get Stripe Checkout ID.
	 */
	public function api_get_stripe_checkout_id() {
		if ( ! class_exists( 'Jetpack_Options' ) || ! class_exists( 'Automattic\Jetpack\Connection\Client' ) ) {
			return new WP_Error( 'jetpack_not_present', __( 'This feature requires Jetpack to be installed, activated, and enabled', 'newspack' ) );
		}
		$path   = '/newspack/stripe_checkout_id';
		$args   = [ 'method' => 'POST' ];
		$user   = wp_get_current_user();
		$params = [
			'customer_id'     => sanitize_text_field( get_option( self::NEWSPACK_STRIPE_CUSTOMER, '' ) ),
			'subscription_id' => sanitize_text_field( get_option( self::NEWSPACK_STRIPE_SUBSCRIPTION, '' ) ),
			'base_url'        => get_admin_url( null, 'admin.php' ) . '?page=newspack-payment-wizard',
			'stripe_plan_id'  => self::stripe_plan(),
			'user_email'      => $user->user_email,
			'mode'            => self::stripe_mode(),
		];
		$result = Client::wpcom_json_api_request_as_blog(
			$path,
			'1.1',
			$args,
			$params
		);

		$body = json_decode( wp_remote_retrieve_body( $result ), true );
		if ( isset( $body['error'] ) ) {
			return new WP_Error( $body['error'], $body['message'] );
		}
		if ( ! isset( $body['data'] ) ) {
			return new WP_Error( 'newspack_payment_error', __( 'Newspack payment error. Please contact support', 'newspack' ) );
		}
		return \rest_ensure_response( $body['data'] );
	}

	/**
	 * After Stripe Checkout event, retrieve information about the transation by session ID and store customer and subscription in the options table.
	 */
	public function retrieve_subscription() {
		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}
		$session_id = filter_input( INPUT_GET, 'session_id', FILTER_SANITIZE_STRING );
		if ( ! $session_id ) {
			return;
		}
		if ( ! class_exists( 'Jetpack_Options' ) || ! class_exists( 'Automattic\Jetpack\Connection\Client' ) ) {
			return new WP_Error( 'jetpack_not_present', __( 'This feature requires Jetpack to be installed, activated, and enabled', 'newspack' ) );
		}
		$path   = '/newspack/retrieve_subscription';
		$args   = [ 'method' => 'POST' ];
		$params = [
			'session_id' => $session_id,
			'mode'       => self::stripe_mode(),
		];
		$result = Client::wpcom_json_api_request_as_blog(
			$path,
			'1.1',
			$args,
			$params
		);

		$session_result = json_decode( wp_remote_retrieve_body( $result ), true );
		$data           = isset( $session_result['data'] ) ? $session_result['data'] : array();
		if ( isset( $data['customer_id'], $data['subscription_id'] ) && $data['customer_id'] && $data['subscription_id'] ) {
			update_option( self::NEWSPACK_STRIPE_CUSTOMER, sanitize_text_field( $data['customer_id'] ) );
			update_option( self::NEWSPACK_STRIPE_SUBSCRIPTION, sanitize_text_field( $data['subscription_id'] ) );
		}
		wp_safe_redirect( get_admin_url( null, 'admin.php' ) . '?page=newspack-payment-wizard' );
		exit;
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Managed Newspack', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Manage payment information for Newspack hosted plan.', 'newspack' );
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
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}

		\wp_enqueue_script(
			'newspack-payment-wizard',
			Newspack::plugin_url() . '/dist/payment.js',
			$this->get_script_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/payment.js' ),
			true
		);
	}

	/**
	 * Check if necessary constants are configured.
	 *
	 * @return boolean True if all Stripe constants are defined.
	 */
	public static function stripe_plan() {
		return ( defined( 'NEWSPACK_STRIPE_PLAN' ) && NEWSPACK_STRIPE_PLAN ) ? NEWSPACK_STRIPE_PLAN : false;
	}

	/**
	 * Return Stripe plan.
	 *
	 * @return bool True if all necessary variables are present.
	 */
	public static function configured() {
		return self::stripe_plan();
	}

	/**
	 * Which Stripe mode to use (live|test).
	 *
	 * @return string Stripe mode (live|test).
	 */
	public static function stripe_mode() {
		return ( defined( 'NEWSPACK_STRIPE_MODE' ) && 'live' === NEWSPACK_STRIPE_MODE ) ? 'live' : 'test';
	}
}
