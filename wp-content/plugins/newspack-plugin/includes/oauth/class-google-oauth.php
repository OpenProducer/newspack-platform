<?php
/**
 * Newspack's Google OAuth2 handling.
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;
use Google\Auth\OAuth2;
use Google\Auth\Credentials\UserRefreshCredentials;

defined( 'ABSPATH' ) || exit;

/**
 * Google OAuth2 flow.
 */
class Google_OAuth {
	const AUTH_DATA_USERMETA_NAME        = '_newspack_google_oauth';
	const CSRF_TOKEN_TRANSIENT_NAME_BASE = '_newspack_google_oauth_csrf_';

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ __CLASS__, 'register_api_endpoints' ] );
	}

	/**
	 * Register the endpoints.
	 *
	 * @codeCoverageIgnore
	 */
	public static function register_api_endpoints() {
		// Get Google OAuth2 auth status.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_status' ],
				'permission_callback' => [ __CLASS__, 'api_permissions_check' ],
			]
		);
		// Start Google OAuth2 flow.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google/start',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_start' ],
				'permission_callback' => [ __CLASS__, 'api_permissions_check' ],
			]
		);
		// Save Google OAuth2 details.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google/finish',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_save_details' ],
				'permission_callback' => [ __CLASS__, 'api_permissions_check' ],
				'args'                => [
					'access_token'  => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'refresh_token' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'csrf_token'    => [
						'sanitize_callback' => 'sanitize_text_field',
					],
					'expires_at'    => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Check capabilities for using API.
	 *
	 * @codeCoverageIgnore
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) || ! self::is_oauth_configured() ) {
			return new \WP_Error(
				'newspack_rest_forbidden',
				esc_html__( 'You cannot use this resource.', 'newspack' ),
				[
					'status' => 403,
				]
			);
		}
		return true;
	}

	/**
	 * Generate a CSRF token and save it as transient.
	 *
	 * @return string CSRF token.
	 */
	private static function generate_csrf_token() {
		$csrf_token     = sha1( openssl_random_pseudo_bytes( 1024 ) );
		$transient_name = self::CSRF_TOKEN_TRANSIENT_NAME_BASE . get_current_user_id();
		set_transient( $transient_name, $csrf_token, 60 );
		return $csrf_token;
	}

	/**
	 * Save OAuth2 credentials for the current user.
	 *
	 * @param object $tokens Tokens.
	 * @return bool True if credentials were saved.
	 */
	private static function save_auth_credentials( $tokens ) {
		$tokens = (array) $tokens;

		$csrf_token_transient_name = self::CSRF_TOKEN_TRANSIENT_NAME_BASE . get_current_user_id();
		$saved_csrf_token          = get_transient( $csrf_token_transient_name );

		if ( $tokens['csrf_token'] !== $saved_csrf_token ) {
			return new \WP_Error( 'newspack_google_oauth', __( 'Session token mismatch.', 'newspack' ) );
		}

		$auth                 = self::get_google_auth_saved_data();
		$auth['access_token'] = $tokens['access_token'];
		$auth['expires_at']   = $tokens['expires_at'];
		if ( isset( $tokens['refresh_token'] ) ) {
			$auth['refresh_token'] = $tokens['refresh_token'];
		}
		return update_user_meta( get_current_user_id(), self::AUTH_DATA_USERMETA_NAME, $auth );
	}

	/**
	 * Create params to obtain a URL for a redirection to Google consent page.
	 */
	public static function get_google_auth_url_params() {
		$scopes         = [
			'https://www.googleapis.com/auth/userinfo.email', // User's email address.
			'https://www.googleapis.com/auth/analytics.edit', // Google Analytics.
			'https://www.googleapis.com/auth/dfp', // Google Ad Manager.
		];
		$redirect_after = admin_url( 'admin.php?page=newspack' );

		return [
			'csrf_token'     => self::generate_csrf_token(),
			'scope'          => implode( ' ', $scopes ),
			'redirect_after' => $redirect_after,
		];
	}

	/**
	 * Get the OAuth proxy URL.
	 *
	 * @param string $path Path to append to base URL.
	 */
	private static function get_oauth_proxy_url( $path = '' ) {
		if ( ! defined( 'NEWSPACK_GOOGLE_OAUTH_PROXY' ) ) {
			return false;
		}
		return add_query_arg(
			[
				'wpcom_access_token' => urlencode( WPCOM_OAuth::get_access_token() ),
			],
			NEWSPACK_GOOGLE_OAUTH_PROXY . $path
		);
	}

	/**
	 * Start the Google OAuth2 flow, which will use WPCOM as a proxy to issue credentials.
	 *
	 * @return WP_REST_Response Response with the URL.
	 */
	public static function api_google_auth_start() {
		$proxy_url = self::get_oauth_proxy_url( '/wp-json/newspack-oauth-proxy/v1/start' );
		try {
			$query_args = self::get_google_auth_url_params();
			$url        = add_query_arg( $query_args, $proxy_url );
			$result     = wp_safe_remote_get( $url );
			if ( is_wp_error( $result ) ) {
				return $result;
			}
			if ( 200 !== $result['response']['code'] ) {
				return wp_send_json_error( json_decode( $result['body'] )->data->message, 400 );
			}
			$response_body = json_decode( $result['body'] );
			return \rest_ensure_response( $response_body->url );
		} catch ( \Exception $e ) {
			return new WP_Error(
				'newspack_google_oauth',
				$e->getMessage()
			);
		}
	}

	/**
	 * Save credentials in user meta.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response Response.
	 */
	public static function api_google_auth_save_details( $request ) {
		$auth_save_data = [
			'access_token' => $request['access_token'],
			'csrf_token'   => $request['csrf_token'],
			'expires_at'   => $request['expires_at'],
		];
		if ( isset( $request['refresh_token'] ) ) {
			$auth_save_data['refresh_token'] = $request['refresh_token'];
		}
		if ( self::save_auth_credentials( $auth_save_data ) ) {
			return \rest_ensure_response( [ 'status' => 'ok' ] );
		} else {
			return new \WP_Error( 'newspack_google_oauth', __( 'Could not save auth data for user.', 'newspack' ) );
		}
	}

	/**
	 * Get Google authentication status.
	 */
	public static function api_google_auth_status() {
		$response        = [
			'user_basic_info' => false,
			'can_google_auth' => false,
		];
		$can_google_auth = self::is_oauth_configured();
		if ( false === $can_google_auth ) {
			return \rest_ensure_response( $response );
		}
		return \rest_ensure_response(
			[
				'user_basic_info' => self::authenticated_user_basic_information(),
				'can_google_auth' => $can_google_auth,
			]
		);
	}

	/**
	 * Get Google authentication details.
	 */
	public static function get_google_auth_saved_data() {
		$auth_data = get_user_meta( get_current_user_id(), self::AUTH_DATA_USERMETA_NAME, true );
		if ( $auth_data ) {
			return $auth_data;
		}
		return [];
	}

	/**
	 * Authenticated user's basic information.
	 *
	 * @return object|bool Basic information, or false if unauthorised.
	 */
	private static function authenticated_user_basic_information() {
		$oauth2_credentials = self::get_oauth2_credentials();
		if ( false === $oauth2_credentials ) {
			return false;
		}

		$access_token = $oauth2_credentials->getAccessToken();

		// Validate access token.
		$token_info_response = wp_safe_remote_get(
			add_query_arg(
				'access_token',
				$access_token,
				'https://www.googleapis.com/oauth2/v1/tokeninfo'
			)
		);

		if ( 200 === wp_remote_retrieve_response_code( $token_info_response ) ) {
			$user_info_response = wp_safe_remote_get(
				add_query_arg(
					'access_token',
					$access_token,
					'https://www.googleapis.com/oauth2/v2/userinfo'
				)
			);
			if ( 200 === wp_remote_retrieve_response_code( $user_info_response ) ) {
				$user_info = json_decode( $user_info_response['body'] );
				return [
					'email' => $user_info->email,
				];
			}
		} else {
			// Credentials are invalid, remove them.
			self::remove_credentials();
		}

		return false;
	}

	/**
	 * Get OAuth2 Credentials.
	 * If refresh token is available, refresh credentials.
	 * Otherwise, return credentials based on access token.
	 * The difference is that the latter can expire and the user will be forced to authorise again.
	 * The refresh token will be issued only upon first authorisation with the app - if the same app
	 * is used for authorisation on another site, only access token will be issued.
	 * More at https://stackoverflow.com/a/10857806/3772847.
	 *
	 * @return OAuth2|bool The credentials, or false of the user has not authenticated or credentials are not usable.
	 */
	public static function get_oauth2_credentials() {
		$auth_data = self::get_google_auth_saved_data();
		if ( ! isset( $auth_data['access_token'] ) ) {
			return false;
		}
		$is_expired = time() > $auth_data['expires_at'];

		if ( $is_expired && isset( $auth_data['refresh_token'] ) ) {
			// Refresh the access token.
			try {
				$proxy_url = self::get_oauth_proxy_url( '/wp-json/newspack-oauth-proxy/v1/refresh-token' );
				$url       = add_query_arg(
					[
						'refresh_token' => $auth_data['refresh_token'],
						'csrf_token'    => self::generate_csrf_token(),
					],
					$proxy_url
				);
				$result    = wp_safe_remote_get( $url );
				if ( 200 !== $result['response']['code'] ) {
					return wp_send_json_error( json_decode( $result['body'] )->data->message, 400 );
				}
				$response_body = json_decode( $result['body'] );

				if ( isset( $response_body->access_token ) ) {
					self::save_auth_credentials( $response_body );
					$auth_data = self::get_google_auth_saved_data();
				}
			} catch ( \Exception $e ) {
				// Credentials might be broken, remove them.
				self::remove_credentials();
				return false;
			}
		}

		$oauth_object = new OAuth2( [] );
		$oauth_object->setAccessToken( $auth_data['access_token'] );
		return $oauth_object;
	}

	/**
	 * Remove saved credentials.
	 */
	public static function remove_credentials() {
		delete_user_meta( get_current_user_id(), self::AUTH_DATA_USERMETA_NAME );
	}

	/**
	 * Is OAuth2 configured for this instance?
	 */
	private static function is_oauth_configured() {
		$wpcom_access_token = WPCOM_OAuth::get_access_token();
		if ( is_wp_error( $wpcom_access_token ) ) {
			return false;
		}
		return false !== self::get_oauth_proxy_url();
	}
}
new Google_OAuth();
