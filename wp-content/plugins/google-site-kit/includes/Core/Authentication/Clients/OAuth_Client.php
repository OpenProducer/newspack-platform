<?php
/**
 * Class Google\Site_Kit\Core\Authentication\Clients\OAuth_Client
 *
 * @package   Google\Site_Kit
 * @copyright 2019 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Core\Authentication\Clients;

use Exception;
use Google\Site_Kit\Context;
use Google\Site_Kit\Core\Authentication\Credentials;
use Google\Site_Kit\Core\Authentication\Google_Proxy;
use Google\Site_Kit\Core\Authentication\Profile;
use Google\Site_Kit\Core\Authentication\Verification;
use Google\Site_Kit\Core\Authentication\Exception\Google_Proxy_Code_Exception;
use Google\Site_Kit\Core\Storage\Encrypted_Options;
use Google\Site_Kit\Core\Storage\Encrypted_User_Options;
use Google\Site_Kit\Core\Storage\Options;
use Google\Site_Kit\Core\Storage\User_Options;
use Google\Site_Kit\Modules\Search_Console;
use Google\Site_Kit_Dependencies\Google_Service_PeopleService;
use WP_HTTP_Proxy;

/**
 * Class for connecting to Google APIs via OAuth.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class OAuth_Client {

	const OPTION_ACCESS_TOKEN            = 'googlesitekit_access_token';
	const OPTION_ACCESS_TOKEN_EXPIRES_IN = 'googlesitekit_access_token_expires_in';
	const OPTION_ACCESS_TOKEN_CREATED    = 'googlesitekit_access_token_created_at';
	const OPTION_REFRESH_TOKEN           = 'googlesitekit_refresh_token';
	const OPTION_REDIRECT_URL            = 'googlesitekit_redirect_url';
	const OPTION_AUTH_SCOPES             = 'googlesitekit_auth_scopes';
	const OPTION_ERROR_CODE              = 'googlesitekit_error_code';
	const OPTION_PROXY_ACCESS_CODE       = 'googlesitekit_proxy_access_code';

	/**
	 * Plugin context.
	 *
	 * @since 1.0.0
	 * @var Context
	 */
	private $context;

	/**
	 * Options instance
	 *
	 * @since 1.0.0
	 * @var Options
	 */
	private $options;

	/**
	 * User_Options instance
	 *
	 * @since 1.0.0
	 * @var User_Options
	 */
	private $user_options;

	/**
	 * Encrypted_Options instance
	 *
	 * @since 1.0.0
	 * @var Encrypted_Options
	 */
	private $encrypted_options;

	/**
	 * Encrypted_User_Options instance
	 *
	 * @since 1.0.0
	 * @var Encrypted_User_Options
	 */
	private $encrypted_user_options;

	/**
	 * OAuth credentials instance.
	 *
	 * @since 1.0.0
	 * @var Credentials
	 */
	private $credentials;

	/**
	 * Google_Proxy instance.
	 *
	 * @since 1.1.2
	 * @var Google_Proxy
	 */
	private $google_proxy;

	/**
	 * Google Client object.
	 *
	 * @since 1.0.0
	 * @since 1.2.0 Now always a Google_Site_Kit_Client.
	 * @var Google_Site_Kit_Client
	 */
	private $google_client;

	/**
	 * Profile instance.
	 *
	 * @since 1.1.4
	 * @var Profile
	 */
	private $profile;

	/**
	 * WP_HTTP_Proxy instance.
	 *
	 * @since 1.2.0
	 * @var WP_HTTP_Proxy
	 */
	private $http_proxy;

	/**
	 * Access token for communication with Google APIs, for temporary storage.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $access_token = '';

	/**
	 * Refresh token to refresh access token, for temporary storage.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $refresh_token = '';

	/**
	 * OAuth2 client credentials data, for temporary storage.
	 *
	 * @since 1.0.0
	 * @var object|null
	 */
	private $client_credentials = false;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Context       $context      Plugin context.
	 * @param Options       $options      Optional. Option API instance. Default is a new instance.
	 * @param User_Options  $user_options Optional. User Option API instance. Default is a new instance.
	 * @param Credentials   $credentials  Optional. Credentials instance. Default is a new instance from $options.
	 * @param Google_Proxy  $google_proxy Optional. Google proxy instance. Default is a new instance.
	 * @param Profile       $profile      Optional. Profile instance. Default is a new instance.
	 * @param WP_HTTP_Proxy $http_proxy   Optional. WP_HTTP_Proxy instance. Default is a new instance.
	 */
	public function __construct(
		Context $context,
		Options $options = null,
		User_Options $user_options = null,
		Credentials $credentials = null,
		Google_Proxy $google_proxy = null,
		Profile $profile = null,
		WP_HTTP_Proxy $http_proxy = null
	) {
		$this->context                = $context;
		$this->options                = $options ?: new Options( $this->context );
		$this->user_options           = $user_options ?: new User_Options( $this->context );
		$this->encrypted_options      = new Encrypted_Options( $this->options );
		$this->encrypted_user_options = new Encrypted_User_Options( $this->user_options );
		$this->credentials            = $credentials ?: new Credentials( $this->encrypted_options );
		$this->google_proxy           = $google_proxy ?: new Google_Proxy( $this->context );
		$this->profile                = $profile ?: new Profile( $this->user_options );
		$this->http_proxy             = $http_proxy ?: new WP_HTTP_Proxy();
	}

	/**
	 * Gets the Google client object.
	 *
	 * @since 1.0.0
	 * @since 1.2.0 Now always returns a Google_Site_Kit_Client.
	 *
	 * @return Google_Site_Kit_Client Google client object.
	 */
	public function get_client() {
		if ( ! $this->google_client instanceof Google_Site_Kit_Client ) {
			$this->google_client = $this->setup_client();
		}

		return $this->google_client;
	}

	/**
	 * Sets up a fresh Google client instance.
	 *
	 * @since 1.2.0
	 *
	 * @return Google_Site_Kit_Client|Google_Site_Kit_Proxy_Client
	 */
	private function setup_client() {
		if ( $this->using_proxy() ) {
			$client = new Google_Site_Kit_Proxy_Client(
				array( 'proxy_base_path' => $this->google_proxy->url() )
			);
		} else {
			$client = new Google_Site_Kit_Client();
		}

		$application_name = 'wordpress/google-site-kit/' . GOOGLESITEKIT_VERSION;
		// The application name is included in the Google client's user-agent for requests to Google APIs.
		$client->setApplicationName( $application_name );
		// Override the default user-agent for the Guzzle client. This is used for oauth/token requests.
		// By default this header uses the generic Guzzle client's user-agent and includes
		// Guzzle, cURL, and PHP versions as it is normally shared.
		// In our case however, the client is namespaced to be used by Site Kit only.
		$http_client = $client->getHttpClient();
		$http_client->setDefaultOption( 'headers/User-Agent', $application_name );

		// Configure the Google_Client's HTTP client to use to use the same HTTP proxy as WordPress HTTP, if set.
		if ( $this->http_proxy->is_enabled() ) {
			if ( $this->http_proxy->use_authentication() ) {
				// The "Authorization" header is used to authenticate the end request; use the dedicated proxy header.
				$http_client->setDefaultOption(
					'headers/Proxy-Authorization',
					'Basic ' . base64_encode( $this->http_proxy->authentication() )
				);
			}

			$http_client->setDefaultOption( 'proxy', $this->http_proxy->host() . ':' . $this->http_proxy->port() );
			$ssl_verify = $http_client->getDefaultOption( 'verify' );
			// Allow SSL verification to be filtered, as is often necessary with HTTP proxies.
			$http_client->setDefaultOption(
				'verify',
				/** This filter is documented in wp-includes/class-http.php */
				apply_filters( 'https_ssl_verify', $ssl_verify, null )
			);
		}

		// Return unconfigured client if credentials not yet set.
		$client_credentials = $this->get_client_credentials();
		if ( ! $client_credentials ) {
			return $client;
		}

		try {
			$client->setAuthConfig( (array) $client_credentials->web );
		} catch ( Exception $e ) {
			return $client;
		}

		// Offline access so we can access the refresh token even when the user is logged out.
		$client->setAccessType( 'offline' );
		$client->setPrompt( 'consent' );
		$client->setRedirectUri( $this->get_redirect_uri() );
		$client->setScopes( $this->get_required_scopes() );
		$client->prepareScopes();

		// This is called when the client refreshes the access token on-the-fly.
		$client->setTokenCallback(
			function( $cache_key, $access_token ) use ( $client ) {
				$expires_in = HOUR_IN_SECONDS; // Sane default, Google OAuth tokens are typically valid for an hour.
				$created    = 0; // This will be replaced with the current timestamp when saving.

				// Try looking up the real values if possible.
				$token = $client->getAccessToken();
				if ( isset( $token['access_token'], $token['expires_in'], $token['created'] ) && $access_token === $token['access_token'] ) {
					$expires_in = $token['expires_in'];
					$created    = $token['created'];
				}

				$this->set_access_token( $access_token, $expires_in, $created );
			}
		);

		// This is called when refreshing the access token on-the-fly fails.
		$client->setTokenExceptionCallback(
			function( Exception $e ) {
				$this->handle_fetch_token_exception( $e );
			}
		);

		if ( $this->profile->has() ) {
			$client->setLoginHint( $this->profile->get()['email'] );
		}

		$access_token = $this->get_access_token();

		// Return unconfigured client if access token not yet set.
		if ( empty( $access_token ) ) {
			return $client;
		}

		$client->setAccessToken(
			array(
				'access_token'  => $access_token,
				'expires_in'    => $this->user_options->get( self::OPTION_ACCESS_TOKEN_EXPIRES_IN ),
				'created'       => $this->user_options->get( self::OPTION_ACCESS_TOKEN_CREATED ),
				'refresh_token' => $this->get_refresh_token(),
			)
		);

		return $client;
	}

	/**
	 * Refreshes the access token.
	 *
	 * While this method can be used to explicitly refresh the current access token, the preferred way
	 * should be to rely on the Google_Site_Kit_Client to do that automatically whenever the current access token
	 * has expired.
	 *
	 * @since 1.0.0
	 */
	public function refresh_token() {
		$refresh_token = $this->get_refresh_token();
		if ( empty( $refresh_token ) ) {
			$this->revoke_token();
			$this->user_options->set( self::OPTION_ERROR_CODE, 'refresh_token_not_exist' );
			return;
		}

		// Stop if google_client not initialized yet.
		if ( ! $this->google_client instanceof Google_Site_Kit_Client ) {
			return;
		}

		try {
			$token_response = $this->google_client->fetchAccessTokenWithRefreshToken( $refresh_token );
		} catch ( \Exception $e ) {
			$this->handle_fetch_token_exception( $e );
			return;
		}

		if ( ! isset( $token_response['access_token'] ) ) {
			$this->user_options->set( self::OPTION_ERROR_CODE, 'access_token_not_received' );
			return;
		}

		$this->set_access_token(
			$token_response['access_token'],
			isset( $token_response['expires_in'] ) ? $token_response['expires_in'] : '',
			isset( $token_response['created'] ) ? $token_response['created'] : 0
		);
	}

	/**
	 * Revokes the access token.
	 *
	 * @since 1.0.0
	 */
	public function revoke_token() {
		try {
			$this->get_client()->revokeToken();
		} catch ( \Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement
			// No special handling, we just need to make sure this goes through.
		}

		$this->delete_token();
	}

	/**
	 * Gets the list of currently required Google OAuth scopes.
	 *
	 * @since 1.0.0
	 * @see https://developers.google.com/identity/protocols/googlescopes
	 *
	 * @return array List of Google OAuth scopes.
	 */
	public function get_required_scopes() {
		/**
		 * Filters the list of required Google OAuth scopes.
		 *
		 * See all Google oauth scopes here: https://developers.google.com/identity/protocols/googlescopes
		 *
		 * @since 1.0.0
		 *
		 * @param array $scopes List of scopes.
		 */
		$scopes = (array) apply_filters( 'googlesitekit_auth_scopes', array() );

		return array_unique(
			array_merge(
				// Default scopes that are always required.
				array(
					'openid',
					'https://www.googleapis.com/auth/userinfo.profile',
					'https://www.googleapis.com/auth/userinfo.email',
				),
				$scopes
			)
		);
	}

	/**
	 * Gets the list of currently granted Google OAuth scopes for the current user.
	 *
	 * @since 1.0.0
	 * @see https://developers.google.com/identity/protocols/googlescopes
	 *
	 * @return array List of Google OAuth scopes.
	 */
	public function get_granted_scopes() {
		return array_values( (array) $this->user_options->get( self::OPTION_AUTH_SCOPES ) );
	}

	/**
	 * Sets the list of currently granted Google OAuth scopes for the current user.
	 *
	 * @since 1.0.0
	 * @see https://developers.google.com/identity/protocols/googlescopes
	 *
	 * @param array $scopes List of Google OAuth scopes.
	 * @return bool True on success, false on failure.
	 */
	public function set_granted_scopes( $scopes ) {
		$scopes = array_filter( $scopes, 'is_string' );

		return $this->user_options->set( self::OPTION_AUTH_SCOPES, $scopes );
	}

	/**
	 * Gets the current user's OAuth access token.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool Access token if it exists, false otherwise.
	 */
	public function get_access_token() {
		if ( ! empty( $this->access_token ) ) {
			return $this->access_token;
		}

		$access_token = $this->encrypted_user_options->get( self::OPTION_ACCESS_TOKEN );

		if ( ! $access_token ) {
			return false;
		}

		$this->access_token = $access_token;

		return $this->access_token;
	}

	/**
	 * Sets the current user's OAuth access token.
	 *
	 * @since 1.0.0
	 *
	 * @param string $access_token New access token.
	 * @param int    $expires_in   TTL of the access token in seconds.
	 * @param int    $created      Optional. Timestamp when the token was created, in GMT. Default is the current time.
	 * @return bool True on success, false on failure.
	 */
	public function set_access_token( $access_token, $expires_in, $created = 0 ) {
		// Bail early if nothing change.
		if ( $this->get_access_token() === $access_token ) {
			return true;
		}

		$this->access_token = $access_token;

		// If not provided, assume current GMT time.
		if ( empty( $created ) ) {
			$created = time();
		}

		$this->user_options->set( self::OPTION_ACCESS_TOKEN_EXPIRES_IN, $expires_in );
		$this->user_options->set( self::OPTION_ACCESS_TOKEN_CREATED, $created );

		return $this->encrypted_user_options->set( self::OPTION_ACCESS_TOKEN, $this->access_token );
	}

	/**
	 * Gets the current user's OAuth refresh token.
	 *
	 * @since 1.0.0
	 *
	 * @return string|bool Refresh token if it exists, false otherwise.
	 */
	public function get_refresh_token() {
		if ( ! empty( $this->refresh_token ) ) {
			return $this->refresh_token;
		}

		$refresh_token = $this->encrypted_user_options->get( self::OPTION_REFRESH_TOKEN );

		if ( ! $refresh_token ) {
			return false;
		}

		$this->refresh_token = $refresh_token;

		return $this->refresh_token;
	}

	/**
	 * Sets the current user's OAuth refresh token.
	 *
	 * @since 1.0.0
	 *
	 * @param string $refresh_token New refresh token.
	 * @return bool True on success, false on failure.
	 */
	public function set_refresh_token( $refresh_token ) {
		// Bail early if nothing change.
		if ( $this->get_refresh_token() === $refresh_token ) {
			return true;
		}

		$this->refresh_token = $refresh_token;

		return $this->encrypted_user_options->set( self::OPTION_REFRESH_TOKEN, $this->refresh_token );
	}

	/**
	 * Gets the authentication URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $redirect_url Redirect URL after authentication.
	 * @return string Authentication URL.
	 */
	public function get_authentication_url( $redirect_url = '' ) {
		if ( empty( $redirect_url ) ) {
			$redirect_url = $this->context->admin_url( 'splash' );
		}

		$redirect_url = add_query_arg( array( 'notification' => 'authentication_success' ), $redirect_url );
		// Ensure we remove error query string.
		$redirect_url = remove_query_arg( 'error', $redirect_url );

		$this->user_options->set( self::OPTION_REDIRECT_URL, $redirect_url );

		// Ensure the latest required scopes are requested.
		$this->get_client()->setScopes( $this->get_required_scopes() );

		return $this->get_client()->createAuthUrl();
	}

	/**
	 * Redirects the current user to the Google OAuth consent screen, or processes a response from that consent
	 * screen if present.
	 *
	 * @since 1.0.0
	 */
	public function authorize_user() {
		$code       = $this->context->input()->filter( INPUT_GET, 'code', FILTER_SANITIZE_STRING );
		$error_code = $this->context->input()->filter( INPUT_GET, 'error', FILTER_SANITIZE_STRING );
		// If the OAuth redirects with an error code, handle it.
		if ( ! empty( $error_code ) ) {
			$this->user_options->set( self::OPTION_ERROR_CODE, $error_code );
			wp_safe_redirect( admin_url() );
			exit();
		}

		if ( ! $this->credentials->has() ) {
			$this->user_options->set( self::OPTION_ERROR_CODE, 'oauth_credentials_not_exist' );
			wp_safe_redirect( admin_url() );
			exit();
		}

		try {
			$token_response = $this->get_client()->fetchAccessTokenWithAuthCode( $code );
		} catch ( Google_Proxy_Code_Exception $e ) {
			// Redirect back to proxy immediately with the access code.
			wp_safe_redirect( $this->get_proxy_setup_url( $e->getAccessCode(), $e->getMessage() ) );
			exit();
		} catch ( Exception $e ) {
			$this->handle_fetch_token_exception( $e );
			wp_safe_redirect( admin_url() );
			exit();
		}

		if ( ! isset( $token_response['access_token'] ) ) {
			$this->user_options->set( self::OPTION_ERROR_CODE, 'access_token_not_received' );
			wp_safe_redirect( admin_url() );
			exit();
		}

		$this->set_access_token(
			$token_response['access_token'],
			isset( $token_response['expires_in'] ) ? $token_response['expires_in'] : '',
			isset( $token_response['created'] ) ? $token_response['created'] : 0
		);

		// Update the site refresh token.
		$refresh_token = $this->get_client()->getRefreshToken();
		$this->set_refresh_token( $refresh_token );

		// Update granted scopes.
		if ( isset( $token_response['scope'] ) ) {
			$scopes = explode( ' ', sanitize_text_field( $token_response['scope'] ) );
		} elseif ( $this->context->input()->filter( INPUT_GET, 'scope' ) ) {
			$scope  = $this->context->input()->filter( INPUT_GET, 'scope', FILTER_SANITIZE_STRING );
			$scopes = explode( ' ', $scope );
		} else {
			$scopes = $this->get_required_scopes();
		}
		$scopes = array_filter(
			$scopes,
			function( $scope ) {
				if ( ! is_string( $scope ) ) {
					return false;
				}
				if ( in_array( $scope, array( 'openid', 'profile', 'email' ), true ) ) {
					return true;
				}
				return 0 === strpos( $scope, 'https://www.googleapis.com/auth/' );
			}
		);
		$this->set_granted_scopes( $scopes );

		$this->refresh_profile_data();

		// TODO: In the future, once the old authentication mechanism no longer exists, this check can be removed.
		// For now the below action should only fire for the proxy despite not clarifying that in the hook name.
		if ( $this->using_proxy() ) {
			/**
			 * Fires when the current user has just been authorized to access Google APIs.
			 *
			 * In other words, this action fires whenever Site Kit has just obtained a new set of access token and
			 * refresh token for the current user, which may happen to set up the initial connection or to request
			 * access to further scopes.
			 *
			 * @since 1.3.0
			 * @since 1.6.0 The $token_response parameter was added.
			 *
			 * @param array $token_response Token response data.
			 */
			do_action( 'googlesitekit_authorize_user', $token_response );
		}

		$redirect_url = $this->user_options->get( self::OPTION_REDIRECT_URL );

		if ( $redirect_url ) {
			$parts  = wp_parse_url( $redirect_url );
			$reauth = strpos( $parts['query'], 'reAuth=true' );
			if ( false === $reauth ) {
				$redirect_url = add_query_arg( array( 'notification' => 'authentication_success' ), $redirect_url );
			}
			$this->user_options->delete( self::OPTION_REDIRECT_URL );
		} else {
			// No redirect_url is set, use default page.
			$redirect_url = $this->context->admin_url( 'splash', array( 'notification' => 'authentication_success' ) );
		}

		wp_safe_redirect( $redirect_url );
		exit();
	}

	/**
	 * Fetches and updates the user profile data for the currently authenticated Google account.
	 *
	 * @since 1.1.4
	 */
	private function refresh_profile_data() {
		try {
			$people_service = new Google_Service_PeopleService( $this->get_client() );
			$response       = $people_service->people->get( 'people/me', array( 'personFields' => 'emailAddresses,photos' ) );

			if ( isset( $response['emailAddresses'][0]['value'], $response['photos'][0]['url'] ) ) {
				$this->profile->set(
					array(
						'email' => $response['emailAddresses'][0]['value'],
						'photo' => $response['photos'][0]['url'],
					)
				);
			}
		} catch ( Exception $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
			// This request is unlikely to fail and isn't critical as Site Kit will fallback to the current WP user
			// if no Profile data exists. Don't do anything for now.
		}
	}

	/**
	 * Determines whether the authentication proxy is used.
	 *
	 * In order to streamline the setup and authentication flow, the plugin uses a proxy mechanism based on an external
	 * service. This can be overridden by providing actual GCP credentials with the {@see 'googlesitekit_oauth_secret'}
	 * filter.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if proxy authentication is used, false otherwise.
	 */
	public function using_proxy() {
		$credentials = $this->get_client_credentials();

		// If no credentials yet, assume true.
		if ( ! is_object( $credentials ) || empty( $credentials->web->client_id ) ) {
			return true;
		}

		// If proxy credentials, return true.
		if ( false !== strpos( $credentials->web->client_id, '.apps.sitekit.withgoogle.com' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the setup URL to the authentication proxy.
	 *
	 * @since 1.0.0
	 * @since 1.1.2 Added googlesitekit_proxy_setup_url_params filter.
	 *
	 * @param string $access_code Optional. Temporary access code for an undelegated access token. Default empty string.
	 * @param string $error_code  Optional. Error code, if the user should be redirected because of an error. Default empty string.
	 * @return string URL to the setup page on the authentication proxy.
	 */
	public function get_proxy_setup_url( $access_code = '', $error_code = '' ) {
		$query_params = array(
			'scope'    => rawurlencode( implode( ' ', $this->get_required_scopes() ) ),
			'supports' => rawurlencode( implode( ' ', $this->get_proxy_setup_supports() ) ),
			'nonce'    => rawurlencode( wp_create_nonce( Google_Proxy::ACTION_SETUP ) ),
		);

		if ( ! empty( $access_code ) ) {
			$query_params['code'] = $access_code;
		}

		if ( $this->credentials->has() ) {
			$query_params['site_id'] = $this->credentials->get()['oauth2_client_id'];
		}

		/**
		 * Filters parameters included in proxy setup URL.
		 *
		 * @since 1.1.2
		 *
		 * @param string $access_code Temporary access code for an undelegated access token.
		 * @param string $error_code  Error code, if the user should be redirected because of an error.
		 */
		$query_params = apply_filters( 'googlesitekit_proxy_setup_url_params', $query_params, $access_code, $error_code );

		// If no site identification information is present, we need to provide details for a new site.
		if ( empty( $query_params['site_id'] ) && empty( $query_params['site_code'] ) ) {
			$site_fields  = array_map( 'rawurlencode', $this->google_proxy->get_site_fields() );
			$query_params = array_merge( $query_params, $site_fields );
		}

		return add_query_arg( $query_params, $this->google_proxy->url( Google_Proxy::SETUP_URI ) );
	}

	/**
	 * Gets the list of features to declare support for when setting up with the proxy.
	 *
	 * @since 1.1.0
	 * @since 1.1.2 Added 'credentials_retrieval'
	 * @since 1.2.0 Added 'short_verification_token' (Supported as of 1.0.1)
	 * @return array Array of supported features.
	 */
	private function get_proxy_setup_supports() {
		return array_filter(
			array(
				'credentials_retrieval',
				'short_verification_token',
				$this->supports_file_verification() ? 'file_verification' : false,
			)
		);
	}

	/**
	 * Checks if the site supports file-based site verification.
	 *
	 * The site must be a root install, with no path in the home URL
	 * to be able to serve the verification response properly.
	 *
	 * @since 1.1.0
	 * @see \WP_Rewrite::rewrite_rules for robots.txt
	 *
	 * @return bool
	 */
	private function supports_file_verification() {
		$home_path = wp_parse_url( home_url(), PHP_URL_PATH );

		return ( ! $home_path || '/' === $home_path );
	}

	/**
	 * Returns the permissions URL to the authentication proxy.
	 *
	 * This only returns a URL if the user already has an access token set.
	 *
	 * @since 1.0.0
	 *
	 * @return string URL to the permissions page on the authentication proxy on success,
	 *                or empty string on failure.
	 */
	public function get_proxy_permissions_url() {
		$access_token = $this->get_access_token();
		if ( empty( $access_token ) ) {
			return '';
		}

		$query_args = array( 'token' => $access_token );

		$credentials = $this->get_client_credentials();
		if ( is_object( $credentials ) && ! empty( $credentials->web->client_id ) ) {
			$query_args['site_id'] = $credentials->web->client_id;
		}

		return add_query_arg( $query_args, $this->google_proxy->url( Google_Proxy::PERMISSIONS_URI ) );
	}

	/**
	 * Converts the given error code to a user-facing message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $error_code Error code.
	 * @return string Error message.
	 */
	public function get_error_message( $error_code ) {
		switch ( $error_code ) {
			case 'oauth_credentials_not_exist':
				return __( 'Unable to authenticate Site Kit, as no client credentials exist.', 'google-site-kit' );
			case 'refresh_token_not_exist':
				return __( 'Unable to refresh access token, as no refresh token exists.', 'google-site-kit' );
			case 'cannot_log_in':
				return __( 'Internal error that the Google login redirect failed.', 'google-site-kit' );
			case 'invalid_code':
				return __( 'Unable to receive access token because of an empty authorization code.', 'google-site-kit' );
			case 'access_token_not_received':
				return __( 'Unable to receive access token because of an unknown error.', 'google-site-kit' );
			case 'access_denied':
				return __( 'The Site Kit setup was interrupted because you did not grant the necessary permissions.', 'google-site-kit' );
			// The following messages are based on https://tools.ietf.org/html/rfc6749#section-5.2.
			case 'invalid_request':
				return __( 'Unable to receive access token because of an invalid OAuth request.', 'google-site-kit' );
			case 'invalid_client':
				return __( 'Unable to receive access token because of an invalid client.', 'google-site-kit' );
			case 'invalid_grant':
				return __( 'Unable to receive access token because of an invalid authorization code or refresh token.', 'google-site-kit' );
			case 'unauthorized_client':
				return __( 'Unable to receive access token because of an unauthorized client.', 'google-site-kit' );
			case 'unsupported_grant_type':
				return __( 'Unable to receive access token because of an unsupported grant type.', 'google-site-kit' );
			default:
				/* translators: %s: error code from API */
				return sprintf( __( 'Unknown Error (code: %s).', 'google-site-kit' ), $error_code );
		}
	}

	/**
	 * Handles an exception thrown when fetching an access token.
	 *
	 * @since 1.2.0
	 *
	 * @param Exception $e Exception thrown.
	 */
	private function handle_fetch_token_exception( Exception $e ) {
		$error_code = $e->getMessage();

		// Revoke and delete user connection data on 'invalid_grant'.
		// This typically happens during refresh if the refresh token is invalid or expired.
		if ( 'invalid_grant' === $error_code ) {
			$this->revoke_token();
		}

		$this->user_options->set( self::OPTION_ERROR_CODE, $error_code );
		if ( $e instanceof Google_Proxy_Code_Exception ) {
			$this->user_options->set( self::OPTION_PROXY_ACCESS_CODE, $e->getAccessCode() );
		}
	}

	/**
	 * Deletes the current user's token and all associated data.
	 *
	 * @since 1.0.3
	 */
	private function delete_token() {
		$this->user_options->delete( self::OPTION_ACCESS_TOKEN );
		$this->user_options->delete( self::OPTION_ACCESS_TOKEN_EXPIRES_IN );
		$this->user_options->delete( self::OPTION_ACCESS_TOKEN_CREATED );
		$this->user_options->delete( self::OPTION_REFRESH_TOKEN );
		$this->user_options->delete( self::OPTION_REDIRECT_URL );
		$this->user_options->delete( self::OPTION_AUTH_SCOPES );
		$this->user_options->delete( self::OPTION_ERROR_CODE );
		$this->user_options->delete( self::OPTION_PROXY_ACCESS_CODE );
	}

	/**
	 * Gets the OAuth redirect URI that listens to the callback request.
	 *
	 * @since 1.0.0
	 *
	 * @return string OAuth redirect URI.
	 */
	private function get_redirect_uri() {
		return add_query_arg( 'oauth2callback', '1', untrailingslashit( home_url() ) );
	}

	/**
	 * Retrieves the OAuth credentials object.
	 *
	 * @since 1.0.0
	 *
	 * @return object|null Credentials object with `web` property, or null if no credentials available.
	 */
	private function get_client_credentials() {
		if ( false !== $this->client_credentials ) {
			return $this->client_credentials;
		}

		if ( ! $this->credentials->has() ) {
			return null;
		}

		$credentials = $this->credentials->get();

		$this->client_credentials = (object) array(
			'web' => (object) array(
				'client_id'                   => $credentials['oauth2_client_id'],
				'client_secret'               => $credentials['oauth2_client_secret'],
				'auth_uri'                    => 'https://accounts.google.com/o/oauth2/auth',
				'token_uri'                   => 'https://oauth2.googleapis.com/token',
				'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
				'redirect_uris'               => array( $this->get_redirect_uri() ),
			),
		);

		return $this->client_credentials;
	}
}
