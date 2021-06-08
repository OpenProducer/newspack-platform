<?php
/**
 * Tests OAuth features.
 *
 * @package Newspack\Tests
 */

use Newspack\WPCOM_OAuth;
use Newspack\Google_OAuth;
use Newspack\Google_Services_Connection;

/**
 * Tests OAuth features.
 */
class Newspack_Test_OAuth extends WP_UnitTestCase {
	public function setUp() { // phpcs:ignore Squiz.Commenting.FunctionComment.Missing
		$user_id = $this->factory->user->create( [ 'role' => 'administrator' ] );
		wp_set_current_user( $user_id );
	}

	/**
	 * WPCOM OAuth.
	 */
	public function test_wpcom_oauth() {
		$token      = 'abc123';
		$expires_in = 1399;
		WPCOM_OAuth::api_save_wpcom_access_token(
			[
				'access_token' => $token,
				'expires_in'   => $expires_in,
			]
		);
		self::assertEquals(
			WPCOM_OAuth::get_access_token(),
			$token,
			'The token is saved.'
		);
	}

	/**
	 * Google OAuth (via WPCOM proxy) flow.
	 */
	public function test_google_oauth() {
		/**
		 * First step is redirecting the user to the OAuth consent screen.
		 * The final URL will be constructed by the WPCOM endpoint.
		 */
		$consent_page_params = Google_OAuth::get_google_auth_url_params();
		$csrf_token          = $consent_page_params['csrf_token'];
		self::assertEquals(
			$consent_page_params,
			[
				'service'        => 'google',
				'scope'          => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/analytics.edit https://www.googleapis.com/auth/dfp',
				'redirect_after' => 'http://example.org/wp-admin/admin.php?page=newspack',
				'csrf_token'     => $csrf_token,
			],
			'The consent page request params are as expected.'
		);

		/**
		 * After the user consents, they will be redirected to another WPCOM endpoint.
		 * WPCOM proxy will obtain credentials and redirect the user back to their site.
		 */
		$proxy_response = [
			'access_token'  => 'access-token-123',
			'refresh_token' => 'refresh-token-123',
			'csrf_token'    => $csrf_token,
			'expires_at'    => time() + 3600,
		];
		Google_OAuth::api_google_auth_save_details( $proxy_response );
		$auth_data = Google_OAuth::get_google_auth_saved_data();
		self::assertEquals(
			$auth_data,
			[
				'access_token'  => $proxy_response['access_token'],
				'refresh_token' => $proxy_response['refresh_token'],
				'expires_at'    => $proxy_response['expires_at'],
			],
			'The saved credentials are as expected.'
		);

		/**
		 * A OAuth2 object, as defined in Google's google/auth library, is exposed for
		 * easy interaction with Google PHP libraries.
		 */
		$oauth2_object = Google_Services_Connection::get_oauth2_credentials();
		self::assertEquals(
			$oauth2_object->getAccessToken(),
			$proxy_response['access_token'],
			'The OAuth2 object returns the access token.'
		);

		/**
		 * Credentials can be removed.
		 */
		Google_OAuth::remove_credentials();
		$auth_data = Google_OAuth::get_google_auth_saved_data();
		self::assertEquals(
			$auth_data,
			[],
			'Credentials are empty after removal.'
		);
		self::assertEquals(
			Google_Services_Connection::get_oauth2_credentials(),
			false,
			'OAuth2 object getter return false after credentials are removed.'
		);
	}
}
