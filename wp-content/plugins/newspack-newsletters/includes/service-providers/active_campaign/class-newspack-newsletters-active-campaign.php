<?php
/**
 * Service Provider: ActiveCampaign Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * ActiveCampaign ESP Class.
 */
final class Newspack_Newsletters_Active_Campaign extends \Newspack_Newsletters_Service_Provider {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->service    = 'active_campaign';
		$this->controller = new Newspack_Newsletters_Active_Campaign_Controller( $this );

		add_action( 'save_post_' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT, [ $this, 'save' ], 10, 3 );
		add_action( 'wp_trash_post', [ $this, 'trash' ], 10, 1 );

		parent::__construct( $this );
	}

	/**
	 * Perform v3 API request.
	 *
	 * @param string $resource Resource path.
	 * @param string $method   HTTP method.
	 * @param array  $options  Request options.
	 *
	 * @return object|WP_Error The API response body or WP_Error.
	 */
	private function api_v3_request( $resource, $method = 'GET', $options = [] ) {
		if ( ! $this->has_api_credentials() ) {
			return new \WP_Error(
				'newspack_newsletters_active_campaign_api_credentials_missing',
				__( 'Active Campaign API credentials are missing.', 'newspack-newsletters' )
			);
		}
		$credentials = $this->api_credentials();
		$api_path    = '/api/3/';
		$query       = isset( $options['query'] ) ? $options['query'] : [];
		$url         = add_query_arg(
			$query,
			rtrim( $credentials['url'], '/' ) . $api_path . $resource
		);
		$args        = [
			'method'  => $method,
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
				'api-token'    => $credentials['key'],
			],
		];
		$response    = wp_safe_remote_request( $url, $args + $options );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		return json_decode( $response['body'], true );
	}

	/**
	 * Perform v1 API request.
	 *
	 * @param string $action  API Action.
	 * @param string $method  HTTP method.
	 * @param array  $options Request options.
	 *
	 * @return array|WP_Error The API response body or WP_Error.
	 */
	private function api_v1_request( $action, $method = 'GET', $options = [] ) {
		if ( ! $this->has_api_credentials() ) {
			return new \WP_Error(
				'newspack_newsletters_active_campaign_api_credentials_missing',
				__( 'ActiveCampaign API credentials are missing.', 'newspack-newsletters' )
			);
		}
		$credentials   = $this->api_credentials();
		$params        = [
			'api_key'    => $credentials['key'],
			'api_action' => $action,
			'api_output' => 'json',
		];
		$api_path      = '/admin/api.php';
		$options_query = [];
		if ( isset( $options['query'] ) ) {
			$options_query = $options['query'];
			unset( $options['query'] );
		}
		$content_type = 'application/json';
		$url          = rtrim( $credentials['url'], '/' ) . $api_path;
		$body         = null;
		$params       = wp_parse_args( $options_query, $params );
		if ( 'POST' === $method ) {
			$content_type = 'application/x-www-form-urlencoded';
			$body         = wp_parse_args(
				isset( $options['body'] ) ? $options['body'] : [],
				$params
			);
		} else {
			$url = add_query_arg( $params, $url );
		}
		$args     = [
			'method'  => $method,
			'headers' => [
				'Content-Type' => $content_type,
				'Accept'       => 'application/json',
			],
			'body'    => $body,
		];
		$response = wp_safe_remote_request( $url, $args + $options );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$body = json_decode( $response['body'], true );
		if ( 1 !== $body['result_code'] ) {
			return new \WP_Error(
				'newspack_newsletters_active_campaign_api_error',
				$body['result_message']
			);
		}
		return $body;
	}

	/**
	 * Get API credentials for service provider.
	 *
	 * @return array Stored API credentials for the service provider.
	 */
	public function api_credentials() {
		return [
			'url' => get_option( 'newspack_newsletters_active_campaign_url' ),
			'key' => get_option( 'newspack_newsletters_active_campaign_key' ),
		];
	}

	/**
	 * Check if provider has all necessary credentials set.
	 *
	 * @return Boolean Result.
	 */
	public function has_api_credentials() {
		$credentials = $this->api_credentials();
		return ! empty( $credentials['url'] ) && ! empty( $credentials['key'] );
	}

	/**
	 * Set the API credentials for the service provider.
	 *
	 * @param array $credentials API credentials.
	 */
	public function set_api_credentials( $credentials ) {
		if ( empty( $credentials['url'] ) || empty( $credentials['key'] ) ) {
			return new WP_Error(
				'newspack_newsletters_invalid_keys',
				__( 'Please input ActiveCampaign API URL and Key.', 'newspack-newsletters' )
			);
		} else {
			$updated_url = update_option( 'newspack_newsletters_active_campaign_url', $credentials['url'] );
			$updated_key = update_option( 'newspack_newsletters_active_campaign_key', $credentials['key'] );
			return $updated_url && $updated_key;
		}
	}

	/**
	 * Get lists.
	 *
	 * @return array|WP_Error List os existing lists or error.
	 */
	public function get_lists() {
		$lists = $this->api_v1_request( 'list_list', 'GET', [ 'query' => [ 'ids' => 'all' ] ] );
		if ( is_wp_error( $lists ) ) {
			return $lists;
		}
		// Remove result metadata.
		unset( $lists['result_code'] );
		unset( $lists['result_message'] );
		unset( $lists['result_output'] );
		return array_values( $lists );
	}

	/**
	 * Get segments.
	 *
	 * @return array|WP_Error List os existing segments or error.
	 */
	public function get_segments() {
		$limit  = 100;
		$offset = 0;
		$result = $this->api_v3_request(
			'segments',
			'GET',
			[
				'query' => [
					'limit'  => $limit,
					'offset' => $offset,
				],
			]
		);
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		$segments = $result['segments'];
		$total    = $result['meta']['total'];
		while ( $total > $offset + $limit ) {
			$offset = $offset + $limit;
			$result = $this->api_v3_request(
				'segments',
				'GET',
				[
					'query' => [
						'limit'  => $limit,
						'offset' => $offset,
					],
				]
			);
			if ( is_wp_error( $result ) ) {
				return $result;
			}
			$segments = array_merge( $segments, $result['segments'] );
		}
		return $segments;
	}

	/**
	 * List method not used in this ESP, but required by parent class.
	 *
	 * @param string $post_id The post ID.
	 * @param string $list_id The list ID.
	 */
	public function list( $post_id, $list_id ) {
		return null;
	}

	/**
	 * Retrieve a campaign.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @return array|WP_Error API Response or error.
	 */
	public function retrieve( $post_id ) {
		if ( ! $this->has_api_credentials() ) {
			return [];
		}
		$transient       = sprintf( 'newspack_newsletters_error_%s_%s', $post_id, get_current_user_id() );
		$persisted_error = get_transient( $transient );
		if ( $persisted_error ) {
			delete_transient( $transient );
			return new WP_Error(
				'newspack_newsletters_active_campaign_error',
				$persisted_error
			);
		}
		$lists = $this->get_lists();
		if ( is_wp_error( $lists ) ) {
			return $lists;
		}
		$segments = $this->get_segments();
		if ( is_wp_error( $segments ) ) {
			return $segments;
		}
		$campaign_id = get_post_meta( $post_id, 'ac_campaign_id', true );
		$from_name   = get_post_meta( $post_id, 'ac_from_name', true );
		$from_email  = get_post_meta( $post_id, 'ac_from_email', true );
		$list_id     = get_post_meta( $post_id, 'ac_list_id', true );
		$segment_id  = get_post_meta( $post_id, 'ac_segment_id', true );
		$result      = [
			'campaign'    => (bool) $campaign_id, // Whether campaign exists, to satisfy the JS API.
			'campaign_id' => $campaign_id,
			'from_name'   => $from_name,
			'from_email'  => $from_email,
			'list_id'     => $list_id,
			'segment_id'  => $segment_id,
			'lists'       => $lists,
			'segments'    => $segments,
		];
		if ( ! $campaign_id ) {
			$sync_result = $this->sync( get_post( $post_id ) );
			if ( ! is_wp_error( $sync_result ) ) {
				$result = wp_parse_args(
					$sync_result,
					$result
				);
			}
		}
		return $result;
	}

	/**
	 * Sender method not used in this ESP, but required by parent class.
	 *
	 * @param string $post_id    Numeric ID of the campaign.
	 * @param string $from_name  Sender name.
	 * @param string $from_email Sender email address.
	 */
	public function sender( $post_id, $from_name, $from_email ) {
		return null;
	}

	/**
	 * Send test email or emails.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @param array   $emails Array of email addresses to send to.
	 * @return array|WP_Error API Response or error.
	 */
	public function test( $post_id, $emails ) {
		if ( ! $this->has_api_credentials() ) {
			return new \WP_Error(
				'newspack_newsletters_active_campaign_api_credentials_missing',
				__( 'ActiveCampaign API credentials are missing.', 'newspack-newsletters' )
			);
		}
		/** Clear existing test campaigns for this post. */
		$test_campaigns = get_post_meta( $post_id, 'ac_test_campaign' );
		if ( ! empty( $test_campaigns ) ) {
			foreach ( $test_campaigns as $test_campaign_id ) {
				$delete_res = $this->delete_campaign( $test_campaign_id, true );
				if ( ! is_wp_error( $delete_res ) ) {
					delete_post_meta( $post_id, 'ac_test_campaign', $test_campaign_id );
				}
			}
		}
		$post        = get_post( $post_id );
		$sync_result = $this->sync( $post );
		if ( is_wp_error( $sync_result ) ) {
			return \rest_ensure_response( $sync_result );
		}
		/** Create disposable campaign for sending a test. */
		$campaign_name = sprintf( 'Test for %s', $this->get_campaign_name( $post ) );
		$campaign      = $this->create_campaign( get_post( $post_id ), $campaign_name );
		if ( is_wp_error( $campaign ) ) {
			return $campaign;
		}
		add_post_meta( $post_id, 'ac_test_campaign', $campaign['id'] );
		$test_result = $this->api_v1_request(
			'campaign_send',
			'GET',
			[
				'query' => [
					'type'       => 'html',
					'action'     => 'test',
					'campaignid' => $campaign['id'],
					'messageid'  => $sync_result['message_id'],
					'email'      => implode( ',', $emails ),
				],
			]
		);
		return [
			'message' => sprintf(
				// translators: %s are comma-separated emails.
				__( 'ActiveCampaign test message sent successfully to %s.', 'newspack-newsletters' ),
				implode( ', ', $emails )
			),
			'result'  => $test_result,
		];
	}

	/**
	 * Get campaign name.
	 *
	 * @param WP_Post $post Post object.
	 * @return String Campaign name.
	 */
	private function get_campaign_name( $post ) {
		return sprintf( 'Newspack Newsletter (%d)', $post->ID );
	}

	/**
	 * Synchronize post with corresponding ESP campaign.
	 *
	 * @param WP_Post $post Post to synchronize.
	 *
	 * @return array|WP_Error Campaign data or error.
	 */
	public function sync( $post ) {
		if ( ! $this->has_api_credentials() ) {
			return new \WP_Error(
				'newspack_newsletters_active_campaign_api_credentials_missing',
				__( 'ActiveCampaign API credentials are missing.', 'newspack-newsletters' )
			);
		}
		if ( empty( $post->post_title ) ) {
			return new WP_Error(
				'newspack_newsletter_error',
				__( 'The newsletter subject cannot be empty.', 'newspack-newsletters' )
			);
		}

		$from_name  = get_post_meta( $post->ID, 'ac_from_name', true );
		$from_email = get_post_meta( $post->ID, 'ac_from_email', true );
		$list_id    = get_post_meta( $post->ID, 'ac_list_id', true );
		$is_public  = get_post_meta( $post->ID, 'is_public', true );
		$message_id = get_post_meta( $post->ID, 'ac_message_id', true );

		$renderer = new Newspack_Newsletters_Renderer();
		$content  = $renderer->retrieve_email_html( $post );

		$message_action = 'message_add';
		$message_data   = [];

		if ( $message_id ) {
			$message = $this->api_v1_request( 'message_view', 'GET', [ 'query' => [ 'id' => $message_id ] ] );
			if ( is_wp_error( $message ) ) {
				return $message;
			}
			$message_action     = 'message_edit';
			$message_data['id'] = $message['id'];

			// If sender data is not available locally, update from ESP.
			if ( ! $from_name || ! $from_email ) {
				$from_name  = $message['fromname'];
				$from_email = $message['fromemail'];
				update_post_meta( $post->ID, 'ac_from_name', $from_name );
				update_post_meta( $post->ID, 'ac_from_email', $from_email );
			}
		} else {
			// Validate required meta if campaign and message are not yet created.
			if ( empty( $from_name ) || empty( $from_email ) ) {
				return new \WP_Error(
					'newspack_newsletters_active_campaign_invalid_sender',
					__( 'Please input sender name and email address.', 'newspack-newsletters' )
				);
			}
			if ( empty( $list_id ) ) {
				return new \WP_Error(
					'newspack_newsletters_active_campaign_invalid_list',
					__( 'Please select a list.', 'newspack-newsletters' )
				);
			}
		}

		$message_data = wp_parse_args(
			[
				'format'              => 'html',
				'htmlconstructor'     => 'editor',
				'html'                => $content,
				'p[' . $list_id . ']' => 1,
				'fromemail'           => $from_email,
				'fromname'            => $from_name,
				'subject'             => $post->post_title,
			],
			$message_data
		);

		$message = $this->api_v1_request( $message_action, 'POST', [ 'body' => $message_data ] );
		if ( is_wp_error( $message ) ) {
			return $message;
		}

		update_post_meta( $post->ID, 'ac_message_id', $message['id'] );

		return [
			'campaign'   => true, // Satisfy JS API.
			'message_id' => $message['id'],
			'list_id'    => $list_id,
			'from_email' => $from_email,
			'from_name'  => $from_name,
		];
	}

	/**
	 * Create a campaign for the given post.
	 *
	 * @param WP_Post $post          Post to create campaign for.
	 * @param string  $campaign_name Optional custom title for this campaign.
	 *
	 * @return array|WP_Error Campaign data or error.
	 */
	private function create_campaign( $post, $campaign_name = '' ) {
		$sync_result = $this->sync( $post );
		if ( is_wp_error( $sync_result ) ) {
			return $sync_result;
		}
		$segment_id = get_post_meta( $post->ID, 'ac_segment_id', true );
		$is_public  = get_post_meta( $post->ID, 'is_public', true );
		if ( empty( $campaign_name ) ) {
			$campaign_name = $this->get_campaign_name( $post );
		}
		$campaign_data = [
			'type'                                  => 'single',
			'status'                                => 0, // 0 = Draft; 1 = Scheduled.
			'public'                                => (int) $is_public,
			'name'                                  => $campaign_name,
			'fromname'                              => $sync_result['from_name'],
			'fromemail'                             => $sync_result['from_email'],
			'segmentid'                             => $segment_id ?? 0, // 0 = No segment.
			'p[' . $sync_result['list_id'] . ']'    => $sync_result['list_id'],
			'm[' . $sync_result['message_id'] . ']' => 100, // 100 = 100% of contacts will receive this.
		];
		return $this->api_v1_request( 'campaign_create', 'POST', [ 'body' => $campaign_data ] );
	}

	/**
	 * Delete a campaign.
	 *
	 * @param int  $campaign_id The Campaign ID.
	 * @param bool $force       Whether to delete the campaign regardless of its status.
	 *
	 * @return array|WP_Error API response data or error.
	 */
	private function delete_campaign( $campaign_id, $force = false ) {
		$campaigns = $this->api_v1_request( 'campaign_list', 'GET', [ 'query' => [ 'ids' => $campaign_id ] ] );
		if ( is_wp_error( $campaigns ) ) {
			return $campaigns;
		}
		$deletable_statuses = [
			'0', // Draft.
			'1', // Scheduled.
			'6', // Disabled.
		];
		if ( true !== $force && ! in_array( $campaigns[0]['status'], $deletable_statuses ) ) {
			return new \WP_Error(
				'newspack_newsletters_active_campaign_campaign_not_deletable',
				__( 'Campaign is not deletable.', 'newspack-newsletters' )
			);
		}
		return $this->api_v1_request( 'campaign_delete', 'GET', [ 'query' => [ 'id' => $campaign_id ] ] );
	}

	/**
	 * Update ESP campaign after post save.
	 *
	 * @param string  $post_id Numeric ID of the campaign.
	 * @param WP_Post $post The complete post object.
	 * @param boolean $update Whether this is an existing post being updated or not.
	 */
	public function save( $post_id, $post, $update ) {
		$status = get_post_status( $post_id );
		if ( 'trash' === $status ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$this->sync( $post );
	}

	/**
	 * Send a campaign.
	 *
	 * @param WP_Post $post Post to send.
	 *
	 * @return true|WP_Error True if the campaign was sent or error if failed.
	 */
	public function send( $post ) {
		$post_id = $post->ID;

		$error = null;

		/** Clean up existing campaign. */
		$campaign_id = get_post_meta( $post_id, 'ac_campaign_id', true );
		if ( $campaign_id ) {
			$this->delete_campaign( $campaign_id, true );
		}
		/** Clean up existing test campaigns. */
		$test_campaigns = get_post_meta( $post_id, 'ac_test_campaign' );
		if ( ! empty( $test_campaigns ) ) {
			foreach ( $test_campaigns as $test_campaign_id ) {
				$delete_res = $this->delete_campaign( $test_campaign_id, true );
				if ( ! is_wp_error( $delete_res ) ) {
					delete_post_meta( $post_id, 'ac_test_campaign', $test_campaign_id );
				}
			}
		}
		/** Create new campaign for sending. */
		$campaign = $this->create_campaign( $post );
		if ( is_wp_error( $campaign ) ) {
			return $campaign;
		}
		update_post_meta( $post_id, 'ac_campaign_id', $campaign['id'] );
		$campaign_id = $campaign['id'];
		$send_result = $this->api_v1_request(
			'campaign_status',
			'GET',
			[
				'query' => [
					'id'     => $campaign_id,
					'status' => 1, // 0 = draft, 1 = scheduled, 2 = sending, 3 = paused, 4 = stopped, 5 = completed.
				],
			]
		);
		if ( is_wp_error( $send_result ) ) {
			return $send_result;
		}

		return true;
	}

	/**
	 * After Newsletter post is deleted, clean up by deleting corresponding ESP campaign.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 */
	public function trash( $post_id ) {
		if ( Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT !== get_post_type( $post_id ) ) {
			return;
		}
		$campaign_id = get_post_meta( $post_id, 'ac_campaign_id', true );
		$message_id  = get_post_meta( $post_id, 'ac_message_id', true );
		if ( $campaign_id ) {
			$this->delete_campaign( $campaign_id );
		}
		if ( $message_id ) {
			$message = $this->api_v1_request( 'message_view', 'GET', [ 'query' => [ 'id' => $message_id ] ] );
			if ( ! is_wp_error( $message ) ) {
				$this->api_v1_request( 'campaign_delete', 'GET', [ 'query' => [ 'id' => $message_id ] ] );
			}
		}
	}

	/**
	 * Add contact to a list.
	 *
	 * @param array  $contact Contact data.
	 * @param string $list_id List ID.
	 *
	 * @return array|WP_Error API response or error.
	 */
	public function add_contact( $contact, $list_id ) {
		$name_fragments = explode( ' ', $contact['name'], 2 );
		return $this->api_v1_request(
			'contact_add',
			'POST',
			[
				'body' => [
					'p[' . $list_id . ']' => 1,
					'email'               => $contact['email'],
					'first_name'          => $name_fragments[0],
					'last_name'           => isset( $name_fragments[1] ) ? $name_fragments[1] : '',
				],
			]
		);
	}
}
