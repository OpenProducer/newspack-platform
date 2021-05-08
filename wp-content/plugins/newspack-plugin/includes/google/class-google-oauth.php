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
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ __CLASS__, 'register_api_endpoints' ] );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public static function register_api_endpoints() {
		// Get Google OAuth2 auth URL.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google/get-url',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_get_url' ],
				'permission_callback' => [ __CLASS__, 'api_permissions_check' ],
			]
		);
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
		// Save Google OAuth2 details.
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/oauth/google',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_google_auth_save_details' ],
				'permission_callback' => [ __CLASS__, 'api_permissions_check' ],
				'args'                => [
					'auth_code' => [
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Check capabilities for using API.
	 *
	 * @param WP_REST_Request $request API request object.
	 * @return bool|WP_Error
	 */
	public static function api_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
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
	 * Create OAuth2 handler.
	 *
	 * @param bool $create_csrf_token Whether to add a CSRF token in state, and save it.
	 * @return WP_REST_Response Response.
	 */
	private static function create_google_oauth2( $create_csrf_token = false ) {
		$scopes       = [
			'https://www.googleapis.com/auth/userinfo.email', // User's email address.
			'https://www.googleapis.com/auth/analytics.edit', // Google Analytics.
			'https://www.googleapis.com/auth/dfp', // Google Ad Manager.
		];
		$redirect_uri = admin_url( 'admin.php?page=newspack' );

		$parameters = [
			'authorizationUri'   => 'https://accounts.google.com/o/oauth2/v2/auth',
			'tokenCredentialUri' => 'https://www.googleapis.com/oauth2/v4/token',
			'redirectUri'        => $redirect_uri,
			'clientId'           => self::get_client_id(),
			'clientSecret'       => self::get_client_secret(),
			'scope'              => implode( ' ', $scopes ),
		];

		// Save a CSRF token.
		if ( $create_csrf_token ) {
			$csrf_token     = sha1( openssl_random_pseudo_bytes( 1024 ) );
			$transient_name = self::CSRF_TOKEN_TRANSIENT_NAME_BASE . get_current_user_id();
			set_transient( $transient_name, $csrf_token, 60 );
			$parameters['state'] = $csrf_token;
		}

		return new OAuth2( $parameters );
	}

	/**
	 * Request Google OAuth2 URL. The user will visit URL to provide consent,
	 * then they will be redirected back to the site.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response Response with the URL.
	 */
	public static function api_google_auth_get_url( $request ) {
		$params        = $request->get_params();
		$auth_code     = isset( $params['code'] ) ? $params['code'] : false;
		$google_oauth2 = self::create_google_oauth2( true );

		$url = $google_oauth2->buildFullAuthorizationUri(
			[
				'access_type' => 'offline',
			]
		);
		return \rest_ensure_response( $url->__toString() );
	}

	/**
	 * Save authentication details in user meta.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response Response.
	 */
	public static function api_google_auth_save_details( $request ) {
		$auth_code = $request['auth_code'];
		$state     = $request['state'];

		$transient_name   = self::CSRF_TOKEN_TRANSIENT_NAME_BASE . get_current_user_id();
		$saved_csrf_token = get_transient( $transient_name );

		if ( $state !== $saved_csrf_token ) {
			return new \WP_Error( 'newspack_google_oauth', __( 'Session token mismatch.', 'newspack' ) );
		}

		$google_oauth2 = self::create_google_oauth2();
		$google_oauth2->setCode( $auth_code );
		$auth_data = $google_oauth2->fetchAuthToken();

		$user_id = get_current_user_id();
		$auth    = [
			'access_token' => $auth_data['access_token'],
		];
		if ( isset( $auth_data['refresh_token'] ) ) {
			$auth['refresh_token'] = $auth_data['refresh_token'];
		}
		if ( update_user_meta( $user_id, self::AUTH_DATA_USERMETA_NAME, $auth ) ) {
			return \rest_ensure_response(
				[
					'status' => 'ok',
				]
			);
		} else {
			return new \WP_Error( 'newspack_google_oauth', __( 'Could not save auth data for user.', 'newspack' ) );
		}
	}

	/**
	 * Get Google authentication status.
	 */
	public static function api_google_auth_status() {
		return \rest_ensure_response(
			[
				'user_basic_info' => self::authenticated_user_basic_information(),
				'can_google_auth' => self::is_oauth_configured(),
			]
		);
	}

	/**
	 * Get Google authentication details.
	 */
	private static function get_google_auth_saved_data() {
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

		if ( $oauth2_credentials instanceof OAuth2 ) {
			// These are non-refreshable credentials, we just have the access token stored.
			$access_token = $oauth2_credentials->getAccessToken();
		} elseif ( $oauth2_credentials instanceof UserRefreshCredentials ) {
			// These credentials are refreshable, let's request a new access token.
			try {
				$token_response = $oauth2_credentials->fetchAuthToken();
			} catch ( \Exception $e ) {
				// Credentials might be broken, remove them.
				delete_user_meta( get_current_user_id(), self::AUTH_DATA_USERMETA_NAME );
				return false;
			}

			if ( ! isset( $token_response['access_token'] ) ) {
				return false;
			}

			$access_token = $token_response['access_token'];
		}

		// Validate access token.
		$token_info_response = wp_safe_remote_get(
			add_query_arg(
				'access_token',
				$access_token,
				'https://www.googleapis.com/oauth2/v1/tokeninfo'
			)
		);

		if ( 200 === $token_info_response['response']['code'] ) {
			$user_info_response = wp_safe_remote_get(
				add_query_arg(
					'access_token',
					$access_token,
					'https://www.googleapis.com/oauth2/v2/userinfo'
				)
			);
			if ( 200 === $user_info_response['response']['code'] ) {
				$user_info = json_decode( $user_info_response['body'] );
				return [
					'email' => $user_info->email,
				];
			}
		}

		return false;
	}

	/**
	 * Get OAuth2 Credentials.
	 * If refresh token is available, generate refreshable credentials.
	 * Otherwise, credentials based on auth token.
	 * The difference is that the latter can expire and the user will be forced to authorise again.
	 * The refresh token will be issued only upon first authorisation with the app - if the same app
	 * is used for authorisation on another site, only access token will be issued.
	 * More at https://stackoverflow.com/a/10857806/3772847.
	 *
	 * @return UserRefreshCredentials|OAuth2|bool The credentials, or false of the user has not authenticated.
	 */
	public static function get_oauth2_credentials() {
		$auth_data = self::get_google_auth_saved_data();
		if ( ! isset( $auth_data['access_token'] ) ) {
			return false;
		}
		if ( isset( $auth_data['refresh_token'] ) ) {
			// Generate a refreshable OAuth2 credential for authentication.
			// https://googleapis.github.io/google-auth-library-php/master/Google/Auth/Credentials/UserRefreshCredentials.html.
			return new UserRefreshCredentials(
				null,
				[
					'client_id'     => self::get_client_id(),
					'client_secret' => self::get_client_secret(),
					'refresh_token' => $auth_data['refresh_token'],
				]
			);
		} else {
			$google_oauth2 = self::create_google_oauth2();
			$google_oauth2->setAccessToken( $auth_data['access_token'] );
			return $google_oauth2;
		}
	}

	/**
	 * Get OAuth2 Client ID.
	 */
	private static function get_client_id() {
		return defined( 'NEWSPACK_GOOGLE_OAUTH_CLIENT_ID' ) ? NEWSPACK_GOOGLE_OAUTH_CLIENT_ID : false;
	}

	/**
	 * Get OAuth2 Client Secret.
	 */
	private static function get_client_secret() {
		return defined( 'NEWSPACK_GOOGLE_OAUTH_CLIENT_SECRET' ) ? NEWSPACK_GOOGLE_OAUTH_CLIENT_SECRET : false;
	}

	/**
	 * Is OAuth2 configured for this instance?
	 */
	private static function is_oauth_configured() {
		return false !== self::get_client_secret() && false !== self::get_client_id();
	}
}
new Google_OAuth();
