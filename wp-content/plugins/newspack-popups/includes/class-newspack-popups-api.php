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
		add_action( 'wp_head', [ $this, 'get_utm_source' ] );
	}

	/**
	 * Register the 'reader' endpoint used by amp-access.
	 */
	public function register_api_endpoints() {
		\register_rest_route(
			'newspack-popups/v1',
			'reader',
			[
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'reader_get_endpoint' ],
			]
		);
		\register_rest_route(
			'newspack-popups/v1',
			'reader',
			[
				'methods'  => \WP_REST_Server::CREATABLE,
				'callback' => [ $this, 'reader_post_endpoint' ],
			]
		);
		\register_rest_route(
			'newspack-popups/v1',
			'sitewide_default/(?P<id>\d+)',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ $this, 'set_sitewide_default_endpoint' ],
				'permission_callback' => function() {
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
				},
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
				'permission_callback' => function() {
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
				},
				'args'                => [
					'id' => [
						'sanitize_callback' => 'absint',
					],
				],
			]
		);
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

		$transient_name = $this->get_transient_name( $request );
		if ( ! $transient_name ) {
			return rest_ensure_response( $response );
		}
		$data = get_transient( $transient_name );

		$response['currentViews'] = (int) $data['count'];

		$frequency = $popup['options']['frequency'];
		$placement = $popup['options']['placement'];
		if ( 'inline' !== $placement && 'always' === $frequency ) {
			$frequency = 'once';
		}

		$utm_suppression       = ! empty( $popup['options']['utm_suppression'] ) ? $popup['options']['utm_suppression'] : null;
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
		if ( $utm_suppression ) {
			$referer = filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING );
			if ( ! empty( $referer ) && stripos( $referer, 'utm_source=' . $utm_suppression ) ) {
				$response['displayPopup'] = false;
			}

			$utm_source_transient_name = $this->get_utm_source_transient_name();
			$utm_source_transient      = $this->get_utm_source_transient( $utm_source_transient_name );
			if ( ! empty( $utm_source_transient[ $utm_suppression ] ) ) {
				$response['displayPopup'] = false;
			}
		}
		if ( $suppress_forever || $mailing_list_status ) {
			$response['displayPopup'] = false;
		}

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
		$transient_name = $this->get_transient_name( $request );
		if ( $transient_name && ! $this->is_preview_request( $request ) ) {
			$data          = get_transient( $transient_name );
			$data['count'] = (int) $data['count'] + 1;
			$data['time']  = time();
			if ( $request['suppress_forever'] ) {
				$data['suppress_forever'] = true;
			}
			if ( $this->get_mailing_list_status( $request ) ) {
				$data['mailing_list_status'] = true;
			}
			set_transient( $transient_name, $data, 0 );
		}
		return $this->reader_get_endpoint( $request );
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
	public function get_transient_name( $request ) {
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
	 * Assess utm_source value
	 */
	public function get_utm_source() {
		$utm_source = filter_input( INPUT_GET, 'utm_source', FILTER_SANITIZE_STRING );
		if ( ! empty( $utm_source ) ) {
			$transient_name = self::get_utm_source_transient_name();
			if ( $transient_name ) {
				$transient = self::get_utm_source_transient( $transient_name );

				$transient[ $utm_source ] = true;
				set_transient( $transient_name, $transient, 0 );
			}
		}
	}

	/**
	 * Assess utm_source transient name
	 *
	 * @param string $transient_name Transient name.
	 * @return array UTM Source Transient.
	 */
	public function get_utm_source_transient( $transient_name ) {
		if ( $transient_name ) {
			$transient = get_transient( $transient_name );
		}
		return $transient ? $transient : [];
	}

	/**
	 * Get utm_source transient value
	 */
	public function get_utm_source_transient_name() {
		$reader_id = $this->get_reader_id();
		if ( $reader_id ) {
			return 'utm_source-' . $reader_id;
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
