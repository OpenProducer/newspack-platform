<?php
/**
 * Newspack Popups API
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * API endpoints
 */
final class Newspack_Popups_API {

	const NEWSPACK_POPUPS_VIEW_LIMIT = 1;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
	}

	/**
	 * Register the 'reader' endpoint used by amp-access.
	 */
	public function register_api_endpoints() {
		\register_rest_route(
			'newspack-popups/v1',
			'reader',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'reader_get_endpoint' ],
				'permission_callback' => '__return_true',
			]
		);
		\register_rest_route(
			'newspack-popups/v1',
			'reader',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'reader_post_endpoint' ],
				'permission_callback' => '__return_true',
			]
		);
		\register_rest_route(
			'newspack-popups/v1',
			'sitewide_default/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'set_sitewide_default_endpoint' ],
				'permission_callback' => [ $this, 'permission_callback' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-popups/v1',
			'sitewide_default/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::DELETABLE,
				'callback'            => [ $this, 'unset_sitewide_default_endpoint' ],
				'permission_callback' => [ $this, 'permission_callback' ],
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
		\register_rest_route(
			'newspack-popups/v1',
			'settings',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'update_settings' ],
				'permission_callback' => [ $this, 'permission_callback' ],
				'args'                => [
					'option_name'  => [
						'validate_callback' => [ __CLASS__, 'validate_settings_option_name' ],
						'sanitize_callback' => 'esc_attr',
					],
					'option_value' => [
						'sanitize_callback' => 'esc_attr',
					],
				],
			]
		);
	}

	/**
	 * Validate settings option key.
	 *
	 * @param String $key Meta key.
	 */
	public static function validate_settings_option_name( $key ) {
		return in_array( $key, array_keys( \Newspack_Popups_Settings::get_settings() ) );
	}

	/**
	 * Permission callback for authenticated requests.
	 *
	 * @return boolean if user can edit stuff.
	 */
	public static function permission_callback() {
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
	 * Handler for API settings update endpoint.
	 *
	 * @param WP_REST_Request $request Request object.
	 */
	public static function update_settings( $request ) {
		if ( update_option( $request['option_name'], $request['option_value'] ) ) {
			return \Newspack_Popups_Settings::get_settings();
		} else {
			return new \WP_Error(
				'newspack_popups_settings_error',
				esc_html__( 'Error updating the settings.', 'newspack' )
			);
		}
	}

	/**
	 * Handle GET requests to the reader endpoint.
	 *
	 * @param WP_REST_Request $request amp-access request.
	 * @return WP_REST_Response with info about reader.
	 */
	public function reader_get_endpoint( $request ) {
		$popup_id = isset( $request['popup_id'] ) ? $request['popup_id'] : false;

		if ( $this->is_preview_request( $request ) ) {
			$popup = Newspack_Popups_Model::retrieve_preview_popup( $popup_id );
		} else {
			$popup = Newspack_Popups_Model::retrieve_popup_by_id( $popup_id );
		}

		$response = [
			'currentViews' => 0,
			'displayPopup' => false,
		];

		$transient_name = $this->get_popup_data_transient_name( $request );
		if ( ! $transient_name ) {
			return rest_ensure_response( $response );
		}
		$data = get_transient( $transient_name );
		if ( false === $data ) {
			$data = [
				'count' => 0,
			];
		}

		$response['currentViews'] = (int) $data['count'];

		$frequency = $popup['options']['frequency'];
		$placement = $popup['options']['placement'];
		if ( 'inline' !== $placement && 'always' === $frequency ) {
			$frequency = 'once';
		}

		$utm_suppression       = ! empty( $popup['options']['utm_suppression'] ) ? urldecode( $popup['options']['utm_suppression'] ) : null;
		$current_views         = ! empty( $response['currentViews'] ) ? (int) $response['currentViews'] : 0;
		$suppress_forever      = ! empty( $data['suppress_forever'] ) ? (int) $data['suppress_forever'] : false;
		$mailing_list_status   = ! empty( $data['mailing_list_status'] ) ? (int) $data['mailing_list_status'] : false;
		$last_view             = ! empty( $data['time'] ) ? (int) $data['time'] : 0;
		$response['frequency'] = $frequency;
		switch ( $frequency ) {
			case 'daily':
				$response['displayPopup'] = $last_view < strtotime( '-1 day' );
				break;
			case 'once':
				$response['displayPopup'] = $current_views < 1;
				break;
			case 'test':
			case 'always':
				$response['displayPopup'] = true;
				break;
			case 'never':
			default:
				$response['displayPopup'] = false;
				break;
		}
		$referer_url = filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING );

		// Suppressing based on UTM Source parameter in the URL.
		// If the visitor came from a campaign with suppressed utm_source, then it should not be displayed.
		if ( $utm_suppression ) {
			if ( ! empty( $referer_url ) && stripos( urldecode( $referer_url ), 'utm_source=' . $utm_suppression ) ) {
				$response['displayPopup'] = false;
				$this->set_utm_source_transient( $utm_suppression );
			}

			$utm_source_transient = $this->get_utm_source_transient();
			if ( ! empty( $utm_source_transient[ $utm_suppression ] ) ) {
				$response['displayPopup'] = false;
			}
		}

		// Suppressing based on UTM Medium parameter in the URL. If:
		// - the visitor came from email,
		// - the suppress_newsletter_campaigns setting is on,
		// - the pop-up has a newsletter form,
		// then it should not be displayed.
		$settings              = \Newspack_Popups_Settings::get_settings();
		$has_utm_medium_in_url = ! empty( $referer_url ) && stripos( $referer_url, 'utm_medium=email' );
		if (
			( $has_utm_medium_in_url || $this->get_utm_medium_transient() ) &&
			$settings['suppress_newsletter_campaigns'] &&
			\Newspack_Popups_Model::has_newsletter_prompt( $popup )
		) {
			$response['displayPopup'] = false;
			$this->set_utm_medium_transient();
		}

		// Suppressing because user has dismissed the popup permanently, or signed up to the newsletter.
		if ( $suppress_forever || $mailing_list_status ) {
			$response['displayPopup'] = false;
		}

		// Suppressing a newsletter campaign if any newsletter campaign was dismissed.
		$is_suppressing_newsletter_popups = get_transient( $this->get_newsletter_campaigns_suppression_transient_name( $request ), true );
		$is_newsletter_popup              = \Newspack_Popups_Model::has_newsletter_prompt( $popup );
		$settings                         = \Newspack_Popups_Settings::get_settings();
		if ( $settings['suppress_all_newsletter_campaigns_if_one_dismissed'] && $is_suppressing_newsletter_popups && $is_newsletter_popup ) {
			$response['displayPopup'] = false;
		}

		// Unsuppressing for previews and test popups.
		if ( $this->is_preview_request( $request ) || 'test' === $frequency ) {
			$response['displayPopup'] = true;
		};

		return rest_ensure_response( $response );
	}

	/**
	 * Detect a popup preview request.
	 *
	 * @param  WP_REST_Request $request a request.
	 * @return Boolean
	 */
	public function is_preview_request( $request ) {
		return Newspack_Popups::previewed_popup_id( $request->get_header( 'referer' ) );
	}

	/**
	 * Handle POST requests to the reader endpoint.
	 *
	 * @param WP_REST_Request $request amp-access request.
	 * @return WP_REST_Response with updated info about reader.
	 */
	public function reader_post_endpoint( $request ) {
		$transient_name = $this->get_popup_data_transient_name( $request );
		if ( $transient_name && ! $this->is_preview_request( $request ) ) {
			$data = get_transient( $transient_name );
			if ( false === $data ) {
				$data = [
					'count' => 0,
				];
			}
			$data['count'] = (int) $data['count'] + 1;
			$data['time']  = time();
			if ( $request['suppress_forever'] ) {
				$popup_id = isset( $request['popup_id'] ) ? $request['popup_id'] : false;
				if ( $popup_id ) {
					$popup               = \Newspack_Popups_Model::retrieve_popup_by_id( $popup_id );
					$is_newsletter_popup = \Newspack_Popups_Model::has_newsletter_prompt( $popup );
					if ( $is_newsletter_popup ) {
						$suppression_transient_name = $this->get_newsletter_campaigns_suppression_transient_name( $request );
						$this->update_cache( $suppression_transient_name, true );
					}
				}

				$data['suppress_forever'] = true;
			}
			if ( $this->get_mailing_list_status( $request ) ) {
				$data['mailing_list_status'] = true;
			}
			$email_address = $this->get_email_address( $request );
			if ( $email_address ) {
				do_action(
					'newspack_popups_mailing_list_subscription',
					[
						'email' => $email_address,
					]
				);
			}
			$this->update_cache( $transient_name, $data );
		}
		return $this->reader_get_endpoint( $request );
	}

	/**
	 * Update the cache after setting a transient.
	 *
	 * @param string $transient_name The transient name.
	 * @param mixed  $data The transient data.
	 */
	public function update_cache( $transient_name, $data ) {
		$full_name = '_transient_' . $transient_name;
		wp_cache_set( $full_name, maybe_serialize( $data ), 'newspack-popups' );
		set_transient( $transient_name, $data, 0 );
	}

	/**
	 * Set sitewide default Popup
	 *
	 * @param WP_REST_Request $request Request object.
	 */
	public function set_sitewide_default_endpoint( $request ) {
		$response = Newspack_Popups_Model::set_sitewide_popup( $request['id'] );
		return is_wp_error( $response ) ? $response : [ 'success' => true ];
	}

	/**
	 * Unset sitewide default Popup (if it is the specified post)
	 *
	 * @param WP_REST_Request $request Request object.
	 */
	public function unset_sitewide_default_endpoint( $request ) {
		$response = Newspack_Popups_Model::unset_sitewide_popup( $request['id'] );
		return is_wp_error( $response ) ? $response : [ 'success' => true ];
	}

	/**
	 * Get transient name.
	 *
	 * @param WP_REST_Request $request amp-access request.
	 * @return string Transient id.
	 */
	public function get_popup_data_transient_name( $request ) {
		$reader_id = isset( $request['rid'] ) ? esc_attr( $request['rid'] ) : false;
		if ( ! $reader_id ) {
			$reader_id = $this->get_reader_id();
		}
		$popup_id = isset( $request['popup_id'] ) ? $request['popup_id'] : false;
		$url      = isset( $request['url'] ) ? esc_url_raw( urldecode( $request['url'] ) ) : false;
		if ( ! $popup_id && ! $url ) {
			$body     = json_decode( $request->get_body(), true );
			$popup_id = isset( $body['popup_id'] ) ? $body['popup_id'] : false;
			$url      = isset( $body['url'] ) ? esc_url_raw( urldecode( $body['url'] ) ) : false;
		}
		if ( $reader_id && $url && $popup_id ) {
			return $reader_id . '-' . $popup_id . '-popup';
		}
		return false;
	}

	/**
	 * Get transient name for newsletter campaigns suppression feature.
	 *
	 * @param WP_REST_Request $request amp-access request.
	 * @return string Transient id.
	 */
	public function get_newsletter_campaigns_suppression_transient_name( $request ) {
		$reader_id = isset( $request['rid'] ) ? esc_attr( $request['rid'] ) : false;
		if ( ! $reader_id ) {
			$reader_id = $this->get_reader_id();
		}
		if ( $reader_id ) {
			return $reader_id . '-newsletter-campaign-suppression';
		}
		return false;
	}

	/**
	 * Get mailing list status.
	 *
	 * @param WP_REST_Request $request amp-access request.
	 * @return string Mailing list status.
	 */
	public function get_mailing_list_status( $request ) {
		$mailing_list_status = isset( $request['mailing_list_status'] ) ? esc_attr( $request['mailing_list_status'] ) : false;
		if ( ! $mailing_list_status ) {
			$body                = json_decode( $request->get_body(), true );
			$mailing_list_status = isset( $body['mailing_list_status'] ) ? $body['mailing_list_status'] : false;
		}
		return 'subscribed' === $mailing_list_status;
	}

	/**
	 * Get email address.
	 *
	 * @param WP_REST_Request $request amp-access request.
	 * @return string Email address.
	 */
	public function get_email_address( $request ) {
		$email_address = isset( $request['email'] ) ? esc_attr( $request['email'] ) : false;
		if ( ! $email_address ) {
			$body          = json_decode( $request->get_body(), true );
			$email_address = isset( $body['email'] ) ? esc_attr( $body['email'] ) : false;
		}
		return $email_address;
	}

	/**
	 * Set the utm_source suppression-related transient.
	 *
	 * @param string $utm_source utm_source param.
	 */
	public function set_utm_source_transient( $utm_source ) {
		if ( ! empty( $utm_source ) ) {
			$transient_name = self::get_suppression_data_transient_name( 'utm_source' );
			if ( $transient_name ) {
				$transient                = self::get_utm_source_transient();
				$transient[ $utm_source ] = true;
				$this->update_cache( $transient_name, $transient );
			}
		}
	}


	/**
	 * Set the utm_medium suppression-related transient.
	 */
	public function set_utm_medium_transient() {
		$transient_name = self::get_suppression_data_transient_name( 'utm_medium' );
		if ( $transient_name ) {
			$this->update_cache( $transient_name, true );
		}
	}

	/**
	 * Assess utm_source transient name
	 *
	 * @return array UTM Source Transient.
	 */
	public function get_utm_source_transient() {
		$transient_name = self::get_suppression_data_transient_name( 'utm_source' );
		if ( $transient_name ) {
			$transient = get_transient( $transient_name );
		}
		return $transient ? $transient : [];
	}

	/**
	 * Assess utm_medium transient name
	 *
	 * @return boolean UTM Medium Transient.
	 */
	public function get_utm_medium_transient() {
		$transient_name = self::get_suppression_data_transient_name( 'utm_medium' );
		if ( $transient_name ) {
			$transient = get_transient( $transient_name );
		}
		return $transient ? $transient : false;
	}

	/**
	 * Get suppression-related transient value
	 *
	 * @param string $prefix transient prefix.
	 */
	public function get_suppression_data_transient_name( $prefix ) {
		$reader_id = $this->get_reader_id();
		if ( $reader_id ) {
			return $prefix . '-' . $reader_id;
		}
		return null;
	}

	/**
	 * Get AMP-Access cookie
	 */
	public function get_reader_id() {
		// TODO: Is retrieving the amp-access cookie the best way to get READER_ID outside the context of amp-access?
		return isset( $_COOKIE['amp-access'] ) ? esc_attr( $_COOKIE['amp-access'] ) : false; // phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}
}
$newspack_popups_api = new Newspack_Popups_API();
