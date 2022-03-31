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
	const AUTH_DATA_META_NAME  = '_newspack_google_oauth';
	const CSRF_TOKEN_NAMESPACE = 'google';

	const REQUIRED_SCOPES = [
		'https://www.googleapis.com/auth/userinfo.email', // User's email address.
		'https://www.googleapis.com/auth/dfp', // Google Ad Manager.
		'https://www.googleapis.com/auth/analytics',
		'https://www.googleapis.com/auth/analytics.edit',
	];

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
				'permission_callback' => [ __CLASS__, 'permissions_check' ],
			]
		);
		// Start Google OAuth2 flow.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google/start',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_start' ],
				'permission_callback' => [ __CLASS__, 'permissions_check' ],
			]
		);
		// Save Google OAuth2 details.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google/finish',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_save_details' ],
				'permission_callback' => [ __CLASS__, 'permissions_check' ],
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
		// Revoke Google OAuth2 details.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google/revoke',
			[
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_revoke' ],
				'permission_callback' => [ __CLASS__, 'permissions_check' ],
			]
		);
	}

	/**
	 * Check capabilities for using API.
	 *
	 * @codeCoverageIgnore
	 * @return bool|WP_Error
	 */
	public static function permissions_check() {
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
	 * Save OAuth2 credentials for the current user.
	 *
	 * @param object $tokens Tokens.
	 * @return bool True if credentials were saved.
	 */
	private static function save_auth_credentials( $tokens ) {
		$tokens           = (array) $tokens;
		$saved_csrf_token = OAuth::retrieve_csrf_token( self::CSRF_TOKEN_NAMESPACE );

		if ( $tokens['csrf_token'] !== $saved_csrf_token ) {
			return new \WP_Error( 'newspack_google_oauth', __( 'Session token mismatch.', 'newspack' ) );
		}

		$auth                 = self::get_google_auth_saved_data();
		$auth['access_token'] = $tokens['access_token'];
		$auth['expires_at']   = $tokens['expires_at'];
		if ( isset( $tokens['refresh_token'] ) ) {
			$auth['refresh_token'] = $tokens['refresh_token'];
		}
		self::remove_credentials();
		return add_option( self::AUTH_DATA_META_NAME, $auth );
	}

	/**
	 * Create params to obtain a URL for a redirection to Google consent page.
	 */
	public static function get_google_auth_url_params() {
		return [
			'csrf_token'     => OAuth::generate_csrf_token( self::CSRF_TOKEN_NAMESPACE ),
			'scope'          => implode( ' ', self::REQUIRED_SCOPES ),
			'redirect_after' => admin_url( 'admin.php?page=newspack-connections-wizard' ),
		];
	}

	/**
	 * Start the Google OAuth2 flow, which will use WPCOM as a proxy to issue credentials.
	 *
	 * @return WP_REST_Response Response with the URL.
	 */
	public static function api_google_auth_start() {
		try {
			$url    = OAuth::authenticate_proxy_url(
				'google',
				'/wp-json/newspack-oauth-proxy/v1/start',
				self::get_google_auth_url_params()
			);
			$result = wp_safe_remote_get( $url );
			if ( is_wp_error( $result ) ) {
				return $result;
			}
			if ( 200 !== $result['response']['code'] ) {
				$error_text  = __( 'Request failed.', 'newspack' );
				$parsed_data = json_decode( $result['body'] );
				if ( property_exists( $parsed_data, 'message' ) ) {
					$error_text = $parsed_data->message;
				}
				return new WP_Error(
					'newspack_google_oauth',
					$error_text
				);
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
	 * Revoke credentials of current user.
	 *
	 * @return WP_REST_Response Response.
	 */
	public static function api_google_auth_revoke() {
		$auth_data = self::get_google_auth_saved_data();
		if ( ! isset( $auth_data['access_token'] ) ) {
			return new \WP_Error( 'newspack_google_oauth', __( 'Missing token for user.', 'newspack' ) );
		}
		if ( isset( $auth_data['refresh_token'] ) ) {
			$token = $auth_data['refresh_token'];
		} else {
			$token = $auth_data['access_token'];
		}

		$result = \wp_safe_remote_post(
			add_query_arg( [ 'token' => $token ], 'https://oauth2.googleapis.com/revoke' )
		);
		if ( 200 === $result['response']['code'] ) {
			self::remove_credentials();
			return \rest_ensure_response( [ 'status' => 'ok' ] );
		} else {
			return new \WP_Error( 'newspack_google_oauth', __( 'Could not save auth data for user.', 'newspack' ) );
		}
	}

	/**
	 * Get Google authentication status.
	 */
	public static function api_google_auth_status() {
		$response = [
			'user_basic_info' => false,
		];
		if ( false === self::is_oauth_configured() ) {
			return \rest_ensure_response( $response );
		}
		$user_info_data = self::authenticated_user_basic_information();
		if ( is_wp_error( $user_info_data ) ) {
			return $user_info_data;
		}
		return \rest_ensure_response(
			[
				'user_basic_info' => $user_info_data,
			]
		);
	}

	/**
	 * Get Google authentication details.
	 */
	public static function get_google_auth_saved_data() {
		$is_permitted = self::permissions_check();
		if ( true !== $is_permitted ) {
			return false;
		}
		$auth_data = get_option( self::AUTH_DATA_META_NAME, false );
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
			$token_info     = json_decode( wp_remote_retrieve_body( $token_info_response ) );
			$granted_scopes = explode( ' ', $token_info->scope );
			$missing_scopes = array_diff( self::REQUIRED_SCOPES, $granted_scopes );
			if ( 0 < count( $missing_scopes ) ) {
				return new \WP_Error( 'newspack_google_oauth', __( 'Newspack can’t access all necessary data because you haven’t granted all permissions requested during setup. Please reconnect your Google account.', 'newspack' ) );
			}

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
					'email'             => $user_info->email,
					'has_refresh_token' => null !== $oauth2_credentials->getRefreshToken(),
				];
			}
		} else {
			return new \WP_Error( 'newspack_google_oauth', __( 'Invalid Google credentials. Please reconnect.', 'newspack' ) );
		}

		return false;
	}

	/**
	 * Get OAuth2 Credentials.
	 * If refresh token is available, refresh credentials.
	 *
	 * @return OAuth2|bool The credentials, or false of the user has not authenticated or credentials are not usable.
	 */
	public static function get_oauth2_credentials() {
		$auth_data = self::get_google_auth_saved_data();
		if ( ! isset( $auth_data['access_token'] ) ) {
			Logger::log( 'Access token is not set, OAuth credentials will not be returned.' );
			return false;
		}
		$is_expired = time() > $auth_data['expires_at'];

		if ( $is_expired && isset( $auth_data['refresh_token'] ) ) {
			Logger::log( 'Refreshing the token…' );
			// Refresh the access token.
			try {
				$url    = OAuth::authenticate_proxy_url(
					'google',
					'/wp-json/newspack-oauth-proxy/v1/refresh-token',
					[
						'refresh_token' => $auth_data['refresh_token'],
						'csrf_token'    => OAuth::generate_csrf_token( self::CSRF_TOKEN_NAMESPACE ),
					]
				);
				$result = wp_safe_remote_get( $url );
				if ( is_wp_error( $result ) ) {
					Logger::log( 'Token refresh resulted in error: ' . $result->get_error_message() );
					return false;
				}
				if ( 200 !== $result['response']['code'] ) {
					Logger::log( 'Token refresh response is not 200: ' . $result['response']['code'] );
					return false;
				}
				$response_body = json_decode( $result['body'] );

				if ( isset( $response_body->access_token ) ) {
					Logger::log( 'Refreshed the token.' );
					self::save_auth_credentials( $response_body );
					$auth_data = self::get_google_auth_saved_data();
				} else {
					Logger::log( 'Access token missing from the response.' );
				}
			} catch ( \Exception $e ) {
				Logger::log( 'Token refreshing failed due to error: ' . $e->getMessage() );
				return false;
			}
		}

		$oauth_object = new OAuth2( [] );
		$oauth_object->setAccessToken( $auth_data['access_token'] );
		if ( isset( $auth_data['refresh_token'] ) ) {
			$oauth_object->setRefreshToken( $auth_data['refresh_token'] );
		}
		return $oauth_object;
	}

	/**
	 * Remove saved credentials.
	 */
	public static function remove_credentials() {
		delete_option( self::AUTH_DATA_META_NAME );
	}

	/**
	 * Is OAuth configured?
	 */
	public static function is_oauth_configured() {
		return OAuth::is_proxy_configured( 'google' );
	}
}
new Google_OAuth();
