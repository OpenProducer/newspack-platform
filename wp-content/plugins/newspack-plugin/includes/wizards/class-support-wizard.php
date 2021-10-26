<?php
/**
 * Newspack's Support Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;
use Newspack\WPCOM_OAuth;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Interface for support for Newspack customers.
 */
class Support_Wizard extends Wizard {
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
	}

	/**
	 * Create a support ticket.
	 *
	 * @param WP_REST_Request $request The request.
	 */
	public function api_create_support_ticket( $request ) {
		if ( empty( $request['subject'] ) ) {
			return new WP_Error( 'newspack_invalid_support', __( 'Please provide a subject.', 'newspack' ) );
		}
		if ( empty( $request['message'] ) ) {
			return new WP_Error( 'newspack_invalid_support', __( 'Please provide a message.', 'newspack' ) );
		}

		try {
			$wpcom_user_data = WPCOM_OAuth::perform_wpcom_api_request( 'rest/v1.1/me' );
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
				'name'  => $wpcom_user_data->display_name,
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

		try {
			$response = self::perform_wpcom_api_request(
				'rest/v1.1/newspack/ticket/new',
				[
					'request' => $support_request,
					'user_id' => $wpcom_user_data->ID,
				]
			);
		} catch ( \Exception $e ) {
			return new WP_Error(
				'newspack_support_error',
				$e->getMessage()
			);
		}

		if ( is_wp_error( $response ) ) {
			$message = __( 'Something went wrong.', 'newspack' );
			if ( self::support_email() ) {
				$message = __( 'Something went wrong. Please contact us directly at ', 'newspack' ) . '<a href="mailto:' . self::support_email() . '">' . self::support_email() . '</a>';
			}
			return new WP_Error(
				'newspack_invalid_support',
				$message
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
		$access_token = get_user_meta( get_current_user_id(), WPCOM_OAuth::NEWSPACK_WPCOM_ACCESS_TOKEN, true );
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
	 * @param string $endpoint Endpoint.
	 * @param bool   $payload Payload to send.
	 * @throws \Exception Error message.
	 */
	public static function perform_wpcom_api_request( $endpoint, $payload = false ) {
		$access_token = self::get_access_token();
		if ( is_wp_error( $access_token ) ) {
			return $access_token;
		}
		$url  = 'https://public-api.wordpress.com/' . $endpoint;
		$args = array(
			'headers' => [
				'Authorization' => 'Bearer ' . $access_token,
			],
		);
		if ( $payload ) {
			$args['body'] = wp_json_encode( $payload );
			$response     = wp_safe_remote_post( $url, $args );
		} else {
			$response = wp_safe_remote_get( $url, $args );
		}
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
			$user_data    = WPCOM_OAuth::perform_wpcom_api_request( 'rest/v1.1/me' );
			$support_data = WPCOM_OAuth::perform_wpcom_api_request( 'wpcom/v2/support-history?email=' . $user_data->email );
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
		return \esc_html__( 'Contact customer support', 'newspack' );
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

		wp_localize_script(
			'newspack-support-wizard',
			'newspack_support_data',
			array(
				'API_URL'       => self::support_api_url(),
				'IS_PRE_LAUNCH' => false !== self::is_pre_launch(),
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
	 * Check if wizard is configured and should be displayed.
	 *
	 * @return bool True if necessary variables are present.
	 */
	public static function configured() {
		return WPCOM_OAuth::wpcom_client_id() && self::support_api_url() && self::support_email();
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
