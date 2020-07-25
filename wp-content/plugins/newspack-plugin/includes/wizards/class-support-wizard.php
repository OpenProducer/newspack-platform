<?php
/**
 * Newspack's Support Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Interface for support for Newspack customers.
 */
class Support_Wizard extends Wizard {
	const NEWSPACK_WPCOM_ACCESS_TOKEN = '_newspack_wpcom_access_token';
	const NEWSPACK_WPCOM_EXPIRES_IN   = '_newspack_wpcom_expires_in';

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-support-wizard';

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
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {
		// Create a support ticket.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-support-wizard/ticket',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'api_create_support_ticket' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Get support history - tickets and chats.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-support-wizard/support-history',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_support_history' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Handle access token from WPCOM.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-support-wizard/wpcom_access_token',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'api_wpcom_access_token' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);

		// Validate WPCOM access token.
		register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/wizard/newspack-support-wizard/validate-access-token',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'api_wpcom_validate_access_token' ],
				'permission_callback' => [ $this, 'api_permissions_check' ],
			]
		);
	}

	/**
	 * Retrieve WPCOM access token.
	 */
	public static function get_wpcom_access_token() {
		return get_user_meta( get_current_user_id(), self::NEWSPACK_WPCOM_ACCESS_TOKEN, true );
	}

	/**
	 * Validate WPCOM credentials.
	 */
	public function api_wpcom_validate_access_token() {
		$access_token = self::get_wpcom_access_token();
		$client_id    = self::wpcom_client_id();
		$response     = wp_safe_remote_get(
			'https://public-api.wordpress.com/oauth2/token-info?' . http_build_query(
				array(
					'client_id' => $client_id,
					'token'     => $access_token,
				)
			)
		);
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		if ( 200 !== $response['response']['code'] ) {
			return new WP_Error( 'invalid_wpcom_token', __( 'Invalid WPCOM token.', 'newspack' ) );
		}
		return $response;
	}

	/**
	 * Save WPCOM credentials.
	 *
	 * @param WP_REST_Request $request The request.
	 */
	public function api_wpcom_access_token( $request ) {
		if ( isset( $request['access_token'], $request['expires_in'] ) ) {
			$user_id = get_current_user_id();
			update_user_meta( $user_id, self::NEWSPACK_WPCOM_ACCESS_TOKEN, sanitize_text_field( $request['access_token'] ) );
			update_user_meta( $user_id, self::NEWSPACK_WPCOM_EXPIRES_IN, sanitize_text_field( $request['expires_in'] ) );
			return \rest_ensure_response(
				array(
					'status' => 'saved',
				)
			);
		} else {
			return new WP_Error( 'missing_parameters', __( 'Missing parameters in request.', 'newspack' ) );
		}
	}

	/**
	 * Create a support ticket.
	 *
	 * @param WP_REST_Request $request The request.
	 */
	public function api_create_support_ticket( $request ) {
		if ( empty( $request['subject'] ) ) {
			return new WP_Error( 'newspack_invalid_support', __( 'Please provide a subject.' ) );
		}
		if ( empty( $request['message'] ) ) {
			return new WP_Error( 'newspack_invalid_support', __( 'Please provide a message.' ) );
		}

		try {
			$wpcom_user_data = self::perform_wpcom_api_request( 'rest/v1.1/me' );
		} catch ( \Exception $e ) {
			return new WP_Error(
				'newspack_support_error',
				$e->getMessage()
			);
		}

		$user      = wp_get_current_user();
		$full_name = $user->first_name . ' ' . $user->last_name;
		if ( ' ' == $full_name ) {
			$full_name = $user->data->display_name;
		}

		$message = '<b>Newspack site:</b> <a href="' . site_url() . '">' . site_url() . '</a><br/>
		<b>Site name:</b> ' . get_bloginfo( 'name' ) . '<br/>
		<b>Theme:</b> ' . wp_get_theme()->get( 'Name' ) . '<br/><br/>
		<b>Message:</b> ' . $request['message'] . '<br/><br/>
		<i>' . sprintf( 'Sent from %s on %s', home_url(), gmdate( 'c', time() ) ) . ' UTC</i>';

		$subject_sufffix = self::is_pre_launch() ? 'Pre-launch' : 'Support';

		$support_request = array(
			'requester' => array(
				'name'  => $full_name,
				'email' => $wpcom_user_data->email,
			),
			'subject'   => '[Newspack ' . $subject_sufffix . '] ' . $request['subject'],
			'comment'   => array(
				'html_body' => $message,
				'uploads'   => $request['uploads'],
			),
		);

		if ( isset( $request['priority'] ) ) {
			$support_request['priority'] = $request['priority'];
		}

		$request_body = wp_json_encode(
			array(
				'request' => $support_request,
			)
		);

		$response = wp_safe_remote_post(
			self::support_api_url() . '/requests.json',
			array(
				'body'    => $request_body,
				'headers' => array(
					'Content-Type' => 'application/json',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'newspack_invalid_support',
				__( 'Something went wrong. Please contact us directly at ' ) . '<a href="mailto:' . self::support_email() . '">' . self::support_email() . '</a>'
			);
		} else {
			return \rest_ensure_response(
				array(
					'status' => 'created',
				)
			);
		}
	}

	/**
	 * Get WPCOM access token.
	 */
	public static function get_access_token() {
		$access_token = get_user_meta( get_current_user_id(), self::NEWSPACK_WPCOM_ACCESS_TOKEN, true );
		if ( ! $access_token ) {
			return new WP_Error(
				'newspack_support_error',
				__( 'Missing WPCOM access token.', 'newspack' )
			);
		}
		return $access_token;
	}

	/**
	 * Perform WPCOM API Request.
	 *
	 * @param string $endpoint endpoint.
	 * @throws \Exception Error message.
	 */
	public static function perform_wpcom_api_request( $endpoint ) {
		$access_token = self::get_access_token();
		if ( is_wp_error( $access_token ) ) {
			return $access_token;
		}
		$response = wp_safe_remote_get(
			'https://public-api.wordpress.com/' . $endpoint,
			array(
				'headers' => [
					'Authorization' => 'Bearer ' . $access_token,
				],
			)
		);
		if ( is_wp_error( $response ) ) {
			throw new \Exception( $response->get_error_message() );
		}
		$response_body = json_decode( $response['body'] );
		if ( $response['response']['code'] >= 300 ) {
			throw new \Exception( $response['response']['message'] );
		}
		return $response_body;
	}


	/**
	 * List support history.
	 *
	 * @param WP_REST_Request $request Request object.
	 */
	public static function api_support_history( $request ) {
		try {
			$user_data    = self::perform_wpcom_api_request( 'rest/v1.1/me' );
			$support_data = self::perform_wpcom_api_request( 'wpcom/v2/support-history?email=' . $user_data->email );
			return $support_data->data;
		} catch ( \Exception $e ) {
			return new WP_Error(
				'newspack_support_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Support', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Contact suppport.', 'newspack' );
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
	 * Enqueue Support Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}

		wp_register_script(
			'newspack-support-wizard',
			Newspack::plugin_url() . '/dist/support.js',
			$this->get_script_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/support.js' ),
			true
		);

		$client_id    = self::wpcom_client_id();
		$redirect_uri = admin_url() . 'admin.php?page=' . $this->slug;
		$access_token = self::get_access_token();
		$access_token = $access_token ? $access_token : '';
		if ( is_wp_error( $access_token ) ) {
			$access_token = '';
		}
		wp_localize_script(
			'newspack-support-wizard',
			'newspack_support_data',
			array(
				'API_URL'            => self::support_api_url(),
				'WPCOM_AUTH_URL'     => 'https://public-api.wordpress.com/oauth2/authorize?client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&response_type=token&scope=global',
				'WPCOM_ACCESS_TOKEN' => $access_token,
				'IS_PRE_LAUNCH'      => false !== self::is_pre_launch(),
			)
		);
		wp_enqueue_script( 'newspack-support-wizard' );

		\wp_register_style(
			'newspack-support-wizard',
			Newspack::plugin_url() . '/dist/support.css',
			$this->get_style_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/support.css' )
		);
		\wp_style_add_data( 'newspack-support-wizard', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-support-wizard' );
	}

	/**
	 * Return support API URL from environment variable.
	 *
	 * @return string support API URL.
	 */
	public static function support_api_url() {
		return ( defined( 'NEWSPACK_SUPPORT_API_URL' ) && NEWSPACK_SUPPORT_API_URL ) ? NEWSPACK_SUPPORT_API_URL : false;
	}

	/**
	 * Return support email address from environment variable.
	 *
	 * @return string support email address.
	 */
	public static function support_email() {
		return ( defined( 'NEWSPACK_SUPPORT_EMAIL' ) && NEWSPACK_SUPPORT_EMAIL ) ? NEWSPACK_SUPPORT_EMAIL : false;
	}

	/**
	 * Return client id of WPCOM auth app.
	 *
	 * @return string client id.
	 */
	public static function wpcom_client_id() {
		return ( defined( 'NEWSPACK_WPCOM_CLIENT_ID' ) && NEWSPACK_WPCOM_CLIENT_ID ) ? NEWSPACK_WPCOM_CLIENT_ID : false;
	}

	/**
	 * Check if wizard is configured and should be displayed.
	 *
	 * @return bool True if necessary variables are present.
	 */
	public static function configured() {
		return self::support_api_url() && self::support_email() && self::wpcom_client_id();
	}

	/**
	 * Return pre-launch tickets information..
	 *
	 * @return boolean if the instance should handle tickets as pre-launch tickets.
	 */
	public static function is_pre_launch() {
		return defined( 'NEWSPACK_SUPPORT_IS_PRE_LAUNCH' ) && NEWSPACK_SUPPORT_IS_PRE_LAUNCH;
	}
}
