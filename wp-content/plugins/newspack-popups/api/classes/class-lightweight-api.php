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
		'prompts'                        => [],
	];

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		if ( $this->is_a_web_crawler() ) {
			$this->error( 'invalid_referer' );
		}
		if ( ! $this->verify_referer() ) {
			$this->error( 'invalid_referer' );
		}
		$this->debug = [
			'read_query_count'       => 0,
			'write_query_count'      => 0,
			'delete_query_count'     => 0,
			'cache_count'            => 0,
			'read_empty_transients'  => 0,
			'write_read_query_count' => 0,
			'start_time'             => microtime( true ),
			'end_time'               => null,
			'duration'               => null,
			'suppression'            => [],
		];
	}

	/**
	 * Is the request coming from a common web crawler?
	 */
	public function is_a_web_crawler() {
		if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__HTTP_USER_AGENT__
			return false;
		}
		$user_agent = $_SERVER['HTTP_USER_AGENT']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__HTTP_USER_AGENT__
		// https://www.keycdn.com/blog/web-crawlers.
		$common_web_crawlers_user_agents = [
			'Googlebot',
			'Bingbot',
			'Slurp',
			'DuckDuckBot',
			'Baiduspider',
			'YandexBot',
			'facebot',
			'ia_archiver',
		];
		foreach ( $common_web_crawlers_user_agents as $crawler_agent ) {
			if ( stristr( $user_agent, $crawler_agent ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Verify referer is valid.
	 */
	public function verify_referer() {
		$http_referer = ! empty( $_SERVER['HTTP_REFERER'] ) ? parse_url( $_SERVER['HTTP_REFERER'] , PHP_URL_HOST ) : null; // phpcs:ignore
		$valid_referers = [
			$http_referer,
		];
		$server_name = ! empty( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : null; // phpcs:ignore
		// Enable requests from AMP Cache.
		if ( preg_match( '/\.cdn\.ampproject\.org/', $http_referer ) ) {
			return true;
		}
		return ! empty( $http_referer ) && ! empty( $server_name ) && in_array( strtolower( $server_name ), $valid_referers, true );
	}

	/**
	 * LEGACY FORMAT - one transient per reader per prompt.
	 * Get transient name.
	 *
	 * @param string $client_id Client ID.
	 * @param string $popup_id Popup ID.
	 */
	public function get_transient_name_legacy( $client_id, $popup_id = null ) {
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
		if ( isset( $_REQUEST['debug'] ) || ( defined( 'NEWSPACK_POPUPS_DEBUG' ) && NEWSPACK_POPUPS_DEBUG ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
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
		// Exiting in test env would report the test as passed.
		if ( defined( 'IS_TEST_ENV' ) && IS_TEST_ENV ) {
			return;
		}

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
		$name       = '_transient_' . $name;
		$table_name = Segmentation::get_transients_table_name();
		$value      = wp_cache_get( $name, 'newspack-popups' );

		if ( false === $value ) {
			$this->debug['read_query_count'] += 1;

			$value = $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM `$table_name` WHERE option_name = %s LIMIT 1", $name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			if ( $value ) {
				// Transient found; cache value.
				wp_cache_set( $name, $value, 'newspack-popups' );
			} else {
				// No transient and no DB entry found.
				return null;
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
		$table_name       = Segmentation::get_transients_table_name();
		$name             = '_transient_' . $name;
		$serialized_value = maybe_serialize( $value );
		wp_cache_set( $name, $serialized_value, 'newspack-popups' );
		$result           = $wpdb->query( $wpdb->prepare( "INSERT INTO `$table_name` (`option_name`, `option_value`, `date`) VALUES (%s, %s, current_timestamp()) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `date` = VALUES(`date`)", $name, $serialized_value ) ); // phpcs:ignore

		$this->debug['write_query_count'] += 1;
	}

	/**
	 * Delete transient.
	 *
	 * @param string $name The transient's name.
	 */
	public function delete_transient( $name ) {
		global $wpdb;
		$name       = '_transient_' . $name;
		$table_name = Segmentation::get_transients_table_name();

		wp_cache_delete( $name );
		$wpdb->delete( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$table_name,
			[ 'option_name' => $name ]
		);

		$this->debug['delete_query_count'] += 1;
	}

	/**
	 * LEGACY FORMAT - one row per reader per prompt.
	 * Retrieve prompt data.
	 *
	 * @param string $client_id Client ID.
	 * @param string $campaign_id Prompt ID.
	 * @return object Prompt data.
	 */
	public function get_campaign_data_legacy( $client_id, $campaign_id ) {
		return $this->get_transient( $this->get_transient_name_legacy( $client_id, $campaign_id ) );
	}

	/**
	 * Retrieve prompt data.
	 *
	 * @param string $client_id Client ID.
	 * @param string $campaign_id Prompt ID.
	 * @return object Prompt data.
	 */
	public function get_campaign_data( $client_id, $campaign_id ) {
		$prompt = [];
		$data   = $this->get_transient( $client_id );

		if ( $data && isset( $data['prompts'] ) && isset( $data['prompts'][ $campaign_id ] ) ) {
			// NEW FORMAT - one row per reader.
			$prompt = $data['prompts'][ $campaign_id ];
		} else {
			// LEGACY FORMAT - one row per reader per prompt.
			$prompt = $this->get_campaign_data_legacy( $client_id, $campaign_id );
			$this->delete_transient( $this->get_transient_name_legacy( $client_id, $campaign_id ) ); // Clean up legacy data.
		}

		return [
			'count'            => ! empty( $prompt['count'] ) ? (int) $prompt['count'] : 0,
			'last_viewed'      => ! empty( $prompt['last_viewed'] ) ? (int) $prompt['last_viewed'] : 0,
			// Primarily caused by permanent dismissal, but also by email signup
			// (on a newsletter prompt) or a UTM param suppression.
			'suppress_forever' => ! empty( $prompt['suppress_forever'] ) ? (int) $prompt['suppress_forever'] : false,
		];
	}

	/**
	 * Retrieve client data.
	 *
	 * @param string $client_id Client ID.
	 * @param bool   $do_not_rebuild Whether to rebuild cache if not found.
	 */
	public function get_client_data( $client_id, $do_not_rebuild = false ) {
		$data = $this->get_transient( $client_id );

		// If no client data found, try the legacy transient name.
		if ( empty( $data ) ) {
			$data = $this->get_transient( $this->get_transient_name_legacy( $client_id ) );
			$this->delete_transient( $this->get_transient_name_legacy( $client_id ) );
		}

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

		// If there is no relevant client data in the events table, do not save any data.
		if ( 0 === count( $client_post_read_events ) ) {
			return $this->client_data_blueprint;
		}

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

		return $this->get_transient( $client_id );
	}

	/**
	 * Retrieve all clients' data.
	 *
	 * @return array All clients' data.
	 */
	public function get_all_clients_data() {
		global $wpdb;
		$events_table_name = Segmentation::get_events_table_name();

		// Results are limited to the 1000 most recent rows for performance reasons.
		$all_client_ids_rows = $wpdb->get_results( "SELECT DISTINCT client_id,id FROM $events_table_name ORDER BY id DESC LIMIT 1000" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
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
		$existing_client_data = $this->get_client_data( $client_id, true );

		// Update prompts data: new data should replace existing data.
		if ( isset( $client_data_update['prompts'] ) && ! empty( $existing_client_data['prompts'] ) ) {
			$client_data_update['prompts']   = array_replace_recursive(
				$existing_client_data['prompts'],
				$client_data_update['prompts']
			);
			$existing_client_data['prompts'] = []; // Unset old client data to avoid data duplication on array_merge_recursive below.
		}

		// Update client data: merge data so we don't lose historical read, subscription, or donation data.
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
			$client_id,
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
