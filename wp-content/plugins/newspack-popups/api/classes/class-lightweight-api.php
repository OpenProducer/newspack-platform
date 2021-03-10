<?php
/**
 * Newspack Campaigns lightweight API
 *
 * @package Newspack
 * @phpcs:disable WordPress.DB.RestrictedClasses.mysql__PDO
 */

/**
 * API endpoints
 */
class Lightweight_API {

	/**
	 * Response object.
	 *
	 * @var response
	 */
	public $response = [];

	/**
	 * Debugging info.
	 *
	 * @var debug
	 */
	public $debug;

	/**
	 * Default client data.
	 *
	 * @var client_data_blueprint
	 */
	private $client_data_blueprint = [
		'suppressed_newsletter_campaign' => false,
		'posts_read'                     => [],
		'donations'                      => [],
		'email_subscriptions'            => [],
		'user_id'                        => false,
	];

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		if ( ! $this->verify_referer() ) {
			$this->error( 'invalid_referer' );
		}
		$this->debug = [
			'read_query_count'       => 0,
			'write_query_count'      => 0,
			'cache_count'            => 0,
			'read_empty_transients'  => 0,
			'write_empty_transients' => 0,
			'write_read_query_count' => 0,
			'start_time'             => microtime( true ),
			'end_time'               => null,
			'duration'               => null,
		];
	}

	/**
	 * Verify referer is valid.
	 */
	public function verify_referer() {
		$http_referer = ! empty( $_SERVER['HTTP_REFERER'] ) ? parse_url( $_SERVER['HTTP_REFERER'] , PHP_URL_HOST ) : null; // phpcs:ignore
		$valid_referers = [
			$http_referer,
		];
		$http_host = ! empty( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : null; // phpcs:ignore
		// Enable requests from AMP Cache.
		if ( preg_match( '/\.cdn\.ampproject\.org/', $http_referer ) ) {
			return true;
		}
		return ! empty( $http_referer ) && ! empty( $http_host ) && in_array( strtolower( $http_host ), $valid_referers, true );
	}

	/**
	 * Get transient name.
	 *
	 * @param string $client_id Client ID.
	 * @param string $popup_id Popup ID.
	 */
	public function get_transient_name( $client_id, $popup_id = null ) {
		if ( null === $popup_id ) {
			// For data about popups in general.
			return sprintf( '%s-popups', $client_id );
		}
		return sprintf( '%s-%s-popup', $client_id, $popup_id );
	}

	/**
	 * Complete the API and print response.
	 *
	 * @codeCoverageIgnore
	 */
	public function respond() {
		if ( defined( 'IS_TEST_ENV' ) && IS_TEST_ENV ) {
			return;
		}
		$this->debug['end_time'] = microtime( true );
		$this->debug['duration'] = $this->debug['end_time'] - $this->debug['start_time'];
		if ( defined( 'NEWSPACK_POPUPS_DEBUG' ) && NEWSPACK_POPUPS_DEBUG ) {
			$this->response['debug'] = $this->debug;
		}
		header( 'Access-Control-Allow-Origin: https://' . parse_url( $_SERVER['HTTP_REFERER'] )['host'], false ); // phpcs:ignore
		header( 'Access-Control-Allow-Credentials: true', false );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		http_response_code( 200 );
		print json_encode( $this->response ); // phpcs:ignore
		exit;
	}

	/**
	 * Return a 400 code error.
	 *
	 * @param string $code The error code.
	 */
	public function error( $code ) {
		http_response_code( 400 );
		print json_encode( [ 'error' => $code ] ); // phpcs:ignore
		exit;
	}

	/**
	 * Get data from transient.
	 *
	 * @param string $name The transient's name.
	 */
	public function get_transient( $name ) {
		global $wpdb;
		$name = '_transient_' . $name;

		$value = wp_cache_get( $name, 'newspack-popups' );
		if ( -1 === $value ) {
			$this->debug['read_empty_transients'] += 1;
			$this->debug['cache_count']           += 1;
			return null;
		} elseif ( false === $value ) {
			$this->debug['read_query_count'] += 1;
			$value                            = $this->get_option( $name );
			if ( $value ) {
				wp_cache_set( $name, $value, 'newspack-popups' );
			} else {
				$this->debug['write_empty_transients'] += 1;
				wp_cache_set( $name, -1, 'newspack-popups' );
			}
		} else {
			$this->debug['cache_count'] += 1;
		}
		return maybe_unserialize( $value );
	}

	/**
	 * Upsert transient.
	 *
	 * @param string $name The transient's name.
	 * @param string $value THe transient's value.
	 */
	public function set_transient( $name, $value ) {
		global $wpdb;
		$name             = '_transient_' . $name;
		$serialized_value = maybe_serialize( $value );
		$autoload         = 'no';
		wp_cache_set( $name, $serialized_value, 'newspack-popups' );
		$result           = $wpdb->query( $wpdb->prepare( "INSERT INTO `$wpdb->options` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)", $name, $serialized_value, $autoload ) ); // phpcs:ignore

		$this->debug['write_query_count'] += 1;
	}

	/**
	 * Retrieve prompt data.
	 *
	 * @param string $client_id Client ID.
	 * @param string $campaign_id Prompt ID.
	 * @return object Prompt data.
	 */
	public function get_campaign_data( $client_id, $campaign_id ) {
		$data = $this->get_transient( $this->get_transient_name( $client_id, $campaign_id ) );
		return [
			'count'            => ! empty( $data['count'] ) ? (int) $data['count'] : 0,
			'last_viewed'      => ! empty( $data['last_viewed'] ) ? (int) $data['last_viewed'] : 0,
			// Primarily caused by permanent dismissal, but also by email signup
			// (on a newsletter prompt) or a UTM param suppression.
			'suppress_forever' => ! empty( $data['suppress_forever'] ) ? (int) $data['suppress_forever'] : false,
		];
	}

	/**
	 * Save prompt data.
	 *
	 * @param string $client_id Client ID.
	 * @param string $campaign_id Prompt ID.
	 * @param string $campaign_data Prompt data.
	 */
	public function save_campaign_data( $client_id, $campaign_id, $campaign_data ) {
		return $this->set_transient( $this->get_transient_name( $client_id, $campaign_id ), $campaign_data );
	}

	/**
	 * Retrieve client data.
	 *
	 * @param string $client_id Client ID.
	 * @param bool   $do_not_rebuild Whether to rebuild cache if not found.
	 */
	public function get_client_data( $client_id, $do_not_rebuild = false ) {
		$data = $this->get_transient( $this->get_transient_name( $client_id ) );
		if ( $data ) {
			// Handle legacy data which might not have some default keys.
			return array_merge(
				$this->client_data_blueprint,
				$data
			);
		}
		if ( $do_not_rebuild ) {
			return $this->client_data_blueprint;
		}

		// Rebuild cache, it might've been purged or it's the first time.
		global $wpdb;
		$events_table_name       = Segmentation::get_events_table_name();
		$client_post_read_events = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare( "SELECT * FROM $events_table_name WHERE client_id = %s AND type = 'post_read'", $client_id ) // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		);

		$this->save_client_data(
			$client_id,
			[
				'posts_read' => array_map(
					function ( $item ) {
						return [
							'post_id'      => $item->post_id,
							'category_ids' => $item->category_ids,
							'created_at'   => $item->created_at,
						];
					},
					$client_post_read_events
				),
			]
		);

		return $this->get_transient( $this->get_transient_name( $client_id ) );
	}

	/**
	 * Retrieve all clients' data.
	 *
	 * @return array All clients' data.
	 */
	public function get_all_clients_data() {
		global $wpdb;
		$events_table_name   = Segmentation::get_events_table_name();
		$all_client_ids_rows = $wpdb->get_results( "SELECT DISTINCT client_id FROM $events_table_name" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$api                 = new Lightweight_API();
		return array_reduce(
			$all_client_ids_rows,
			function ( $acc, $row ) use ( $api ) {
				// Disregard client data created during previewing.
				if ( false === strpos( $row->client_id, 'preview' ) ) {
					$acc[] = $api->get_client_data( $row->client_id, true );
				}
				return $acc;
			},
			[]
		);
	}

	/**
	 * Save client data.
	 *
	 * @param string $client_id Client ID.
	 * @param string $client_data_update Client data.
	 */
	public function save_client_data( $client_id, $client_data_update ) {
		$existing_client_data                       = $this->get_client_data( $client_id, true );
		$updated_client_data                        = array_merge_recursive(
			$existing_client_data,
			$client_data_update
		);
		$updated_client_data['posts_read']          = array_unique( $updated_client_data['posts_read'], SORT_REGULAR );
		$updated_client_data['email_subscriptions'] = array_unique( $updated_client_data['email_subscriptions'], SORT_REGULAR );
		$updated_client_data['donations']           = array_unique( $updated_client_data['donations'], SORT_REGULAR );
		if ( isset( $client_data_update['user_id'] ) ) {
			$updated_client_data['user_id'] = $client_data_update['user_id'];
		}
		return $this->set_transient(
			$this->get_transient_name( $client_id ),
			$updated_client_data
		);
	}

	/**
	 * Get request param.
	 *
	 * @param string $param Param name.
	 * @param object $request A POST request.
	 */
	public function get_request_param( $param, $request ) {
		$value = isset( $request[ $param ] ) ? $request[ $param ] : false; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return $value;
	}

	/**
	 * Get POST request payload.
	 */
	public function get_post_payload() {
		$payload = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( null == $payload ) {
			// A POST request made by amp-analytics has to be parsed in this way.
			// $_POST contains the payload if the request has FormData.
			$payload = file_get_contents( 'php://input' ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsRemoteFile
			if ( ! empty( $payload ) ) {
				$payload = (array) json_decode( $payload );
			}
		}
		if ( null == $payload ) {
			// Of all else fails, look for payload in query string.
			return $_GET; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
		}
		return $payload;
	}

	/**
	 * Get option.
	 *
	 * @param string $name Option name.
	 */
	public function get_option( $name ) {
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	}
}
