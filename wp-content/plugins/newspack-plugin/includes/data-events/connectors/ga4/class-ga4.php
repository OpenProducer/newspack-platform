<?php
/**
 * Newspack Data Events Ga4 Connector
 *
 * @package Newspack
 */

namespace Newspack\Data_Events\Connectors;

require_once 'class-event.php';

use Newspack\Data_Events;
use Newspack\Data_Events\Connectors\GA4\Event;
use Newspack\Data_Events\Popups as Popups_Events;
use Newspack\Logger;
use Newspack\Reader_Activation;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class GA4 {

	/**
	 * The events being watched.
	 *
	 * @var array
	 */
	public static $watched_events = [
		'reader_logged_in',
		'reader_registered',
	];

	/**
	 * Initialize the class and registers the handlers
	 *
	 * @return void
	 */
	public static function init() {
		Data_Events::register_handler( [ __CLASS__, 'global_handler' ] );
		add_filter( 'newspack_data_events_dispatch_body', [ __CLASS__, 'filter_event_body' ], 10, 2 );
	}

	/**
	 * Global handler for the Data Events API.
	 *
	 * @param string $event_name The event name.
	 * @param int    $timestamp Timestamp of the event.
	 * @param array  $data      Data associated with the event.
	 * @param int    $user_id   ID of the client that triggered the event. It's a RAS ID.
	 *
	 * @throws \Exception If the event is invalid.
	 * @return void
	 */
	public static function global_handler( $event_name, $timestamp, $data, $user_id ) {
		if ( ! in_array( $event_name, self::$watched_events, true ) ) {
			return;
		}

		$params    = $data['ga_params'];
		$client_id = $data['ga_client_id'];

		if ( empty( $client_id ) ) {
			throw new \Exception( 'Missing client ID' );
		}

		if ( method_exists( __CLASS__, 'handle_' . $event_name ) ) {
			$params = call_user_func( [ __CLASS__, 'handle_' . $event_name ], $params, $data );

			if ( ! Event::validate_name( $event_name ) ) {
				throw new \Exception( 'Invalid event name' );
			}

			foreach ( $params as $param_name => $param_value ) {
				if ( ! Event::validate_name( $param_name ) ) {
					unset( $params[ $param_name ] );
					self::log( sprintf( 'Parameter %s has an invalid name. It was removed from the %s event.', $param_name, $event_name ) );
				}

				if ( ! Event::validate_param_value( $param_value ) ) {
					unset( $params[ $param_name ] );
					self::log( sprintf( 'Parameter %s has an invalid value. It was removed from the %s event.', $param_name, $event_name ) );
				}
			}

			if ( count( $params ) > Event::MAX_PARAMS ) {
				$discarded_params = array_slice( $params, Event::MAX_PARAMS );
				$params           = array_slice( $params, 0, Event::MAX_PARAMS );
				self::log( sprintf( 'Event %s has too many parameters. Only the first 25 were kept.', $event_name, Event::MAX_PARAMS ) );
				foreach ( array_keys( $discarded_params ) as $d_param_name ) {
					self::log( sprintf( 'Discarded parameter: %s', $d_param_name ) );
				}
			}

			$event = new Event( $event_name, $params );
			self::send_event( $event, $client_id, $timestamp, $user_id );

		} else {
			throw new \Exception( 'Event handler method not found' );
		}

	}

	/**
	 * Filters the event body before dispatching it.
	 *
	 * @param array  $body The event body.
	 * @param string $event_name The event name.
	 * @return array
	 */
	public static function filter_event_body( $body, $event_name ) {
		if ( ! in_array( $event_name, self::$watched_events, true ) ) {
			return $body;
		}

		$body['data']['ga_client_id'] = self::extract_cid_from_cookies();

		// Default params added to all events will go here.
		$body['data']['ga_params'] = [
			'logged_in' => is_user_logged_in() ? 'yes' : 'no',
		];

		$session_id = self::extract_sid_from_cookies();
		if ( $session_id ) {
			$body['data']['ga_params']['ga_session_id'] = $session_id;
		}

		if ( is_user_logged_in() ) {
			$current_user                           = wp_get_current_user();
			$body['data']['ga_params']['is_reader'] = Reader_Activation::is_user_reader( $current_user ) ? 'yes' : 'no';
		}

		return $body;

	}

	/**
	 * Handler for the reader_logged_in event.
	 *
	 * @param int   $params The GA4 event parameters.
	 * @param array $data      Data associated with the Data Events api event.
	 *
	 * @return array $params The final version of the GA4 event params that will be sent to GA.
	 */
	public static function handle_reader_logged_in( $params, $data ) {
		return $params;
	}

	/**
	 * Handler for the reader_registered event.
	 *
	 * @param int   $params The GA4 event parameters.
	 * @param array $data      Data associated with the Data Events api event.
	 *
	 * @return array $params The final version of the GA4 event params that will be sent to GA.
	 */
	public static function handle_reader_registered( $params, $data ) {
		$params['registration_method'] = $data['metadata']['registration_method'] ?? '';
		if ( ! empty( $data['metadata']['newspack_popup_id'] ) ) {
			$params = array_merge( $params, Popups_Events::get_popup_metadata( $data['metadata']['newspack_popup_id'] ) );
		}
		if ( ! empty( $data['metadata']['referer'] ) ) {
			$params['referer'] = substr( $data['metadata']['referer'], 0, 100 );
		}
		return $params;
	}

	/**
	 * Gets the credentials for the GA4 API.
	 *
	 * @return array
	 */
	private static function get_credentials() {
		$api_secret     = get_option( 'ga4_api_secret' );
		$measurement_id = get_option( 'ga4_measurement_id' );
		return compact( 'api_secret', 'measurement_id' );
	}

	/**
	 * Gets the API URL for GA4
	 *
	 * @return WP_Error|string
	 */
	public static function get_api_url() {
		$credentials    = self::get_credentials();
		$api_secret     = $credentials['api_secret'];
		$measurement_id = $credentials['measurement_id'];
		if ( ! $api_secret || ! $measurement_id ) {
			return new WP_Error( 'missing_credentials', 'Missing GA4 API credentials.' );
		}
		return add_query_arg(
			[
				'api_secret'     => $api_secret,
				'measurement_id' => $measurement_id,
			],
			'https://www.google-analytics.com/mp/collect'
		);
	}

	/**
	 * Sends an event to GA4.
	 *
	 * @param Event  $event     The event object.
	 * @param string $client_id The GA client ID obtained from the cookie.
	 * @param int    $timestamp The timestamp of the event.
	 * @param string $user_id   User identifier. Use Reader_Activation::get_client_id().
	 *
	 * @throws \Exception If the credentials are missing.
	 * @return void
	 */
	private static function send_event( Event $event, $client_id, $timestamp, $user_id = null ) {

		$url = self::get_api_url();

		if ( is_wp_error( $url ) ) {
			throw new \Exception( $url->get_error_message() );
		}

		$timestamp_micros = (int) $timestamp * 1000000;

		$payload = [
			'client_id'        => $client_id,
			'timestamp_micros' => $timestamp_micros,
			'events'           => [
				$event->to_array(),
			],
		];

		if ( $user_id ) {
			$payload['user_id'] = $user_id;
		}

		wp_remote_post(
			$url,
			[
				'body' => wp_json_encode( $payload ),
			]
		);

		self::log( sprintf( 'Event sent - %s - Client ID: %s', $event->get_name(), $client_id ) );

	}

	/**
	 * Extracts the Client ID from the _ga cookie
	 *
	 * @return ?string
	 */
	private static function extract_cid_from_cookies() {
		if ( isset( $_COOKIE['_ga'] ) ) {
			$cookie_pieces = explode( '.', $_COOKIE['_ga'], 3 ); // phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( 1 === count( $cookie_pieces ) ) {
				$cid = reset( $cookie_pieces );
			} else {
				list( $version, $domain_depth, $cid ) = $cookie_pieces;
			}
			return $cid;
		}
	}

	/**
	 * Extracts the Session ID from the _ga_{container} cookie
	 *
	 * If the cookie is not found, it will be created
	 *
	 * @return ?string
	 */
	private static function extract_sid_from_cookies() {
		foreach ( $_COOKIE as $key => $value ) { //phpcs:ignore
			if ( strpos( $key, '_ga_' ) === 0 && strpos( $value, 'GS1.' ) === 0 ) {
				$cookie_pieces = explode( '.', $value );
				if ( ! empty( $cookie_pieces[2] ) ) {
					return $cookie_pieces[2];
				}
			}
		}
	}

	/**
	 * Log a message.
	 *
	 * @param string $message Message to log.
	 */
	private static function log( $message ) {
		Logger::log( $message, 'NEWSPACK-DATA-EVENTS-GA4' );
	}
}

if ( defined( 'NEWSPACK_EXPERIMENTAL_GA4_EVENTS' ) && NEWSPACK_EXPERIMENTAL_GA4_EVENTS ) {
	add_action( 'plugins_loaded', array( 'Newspack\Data_Events\Connectors\GA4', 'init' ) );
}
