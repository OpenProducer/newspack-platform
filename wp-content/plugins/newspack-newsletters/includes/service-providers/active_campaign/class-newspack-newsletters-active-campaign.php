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
	 * Cached fields.
	 *
	 * @var array
	 */
	private $fields = null;

	/**
	 * Cached lists.
	 *
	 * @var array
	 */
	private $lists = null;

	/**
	 * Cached contact data.
	 *
	 * @var array
	 */
	private $contact_data = [];

	/**
	 * Whether the provider has support to tags and tags based Subscription Lists.
	 *
	 * @var boolean
	 */
	public static $support_tags = true;
	
	/**
	 * Provider name.
	 *
	 * @var string
	 */
	public $name = 'ActiveCampaign';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->service    = 'active_campaign';
		$this->controller = new Newspack_Newsletters_Active_Campaign_Controller( $this );

		add_action( 'save_post_' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT, [ $this, 'save' ], 10, 3 );
		add_action( 'wp_trash_post', [ $this, 'trash' ], 10, 1 );

		add_action( 'newspack_newsletters_subscription_lists_metabox_after_tag', [ $this, 'lists_metabox_notice' ] );

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
		$response = json_decode( $response['body'], true );
		if ( isset( $response['errors'] ) && ! empty( $response['errors'] ) ) {
			$errors = new WP_Error();
			foreach ( $response['errors'] as $error ) {
				$errors->add( $error['code'], $error['title'] );
			}
			return $errors;
		}
		return $response;
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
	 * Retrieve the ESP's tag ID from its name
	 *
	 * @param string  $tag_name The tag.
	 * @param boolean $create_if_not_found Whether to create a new tag if not found. Default to true.
	 * @param string  $list_id The List ID. Not needed for Active Campaign.
	 * @return int|WP_Error The tag ID on success. WP_Error on failure.
	 */
	public function get_tag_id( $tag_name, $create_if_not_found = true, $list_id = null ) {
		$tag_name = (string) $tag_name;
		$search   = $this->api_v3_request(
			'tags',
			'GET',
			[
				'query' => [
					'search' => $tag_name,
				],
			]
		);
		if ( ! empty( $search['tags'] ) ) {
			foreach ( $search['tags'] as $found_tag ) {
				if ( ! empty( $found_tag['tag'] ) && $tag_name === $found_tag['tag'] ) {
					return (int) $found_tag['id'];
				}
			}
		}

		// Tag was not found.
		if ( ! $create_if_not_found ) {
			return new WP_Error(
				'newspack_newsletter_tag_not_found'
			);
		}

		$created = $this->create_tag( $tag_name );

		return (int) $created['id'];
	}

	/**

	 * Retrieve the ESP's tag name from its ID
	 *
	 * @param int    $tag_id The tag ID.
	 * @param string $list_id The List ID.
	 * @return string|WP_Error The tag name on success. WP_Error on failure.
	 */
	public function get_tag_by_id( $tag_id, $list_id = null ) {
		$search = $this->api_v3_request(
			sprintf( 'tags/%d', $tag_id )
		);
		if ( ! empty( $search['tag'] ) && ! empty( $search['tag']['tag'] ) ) {
			return $search['tag']['tag'];
		}
		return new WP_Error(
			'newspack_newsletter_tag_not_found'
		);
	}

	/**
	 * Get the IDs of the tags associated with a contact.
	 *
	 * @param string $email The contact email.
	 * @return array|WP_Error The tag IDs on success. WP_Error on failure.
	 */
	public function get_contact_tags_ids( $email ) {
		$contact_data = $this->get_contact_data( $email );
		if ( is_wp_error( $contact_data ) ) {
			return $contact_data;
		}
		$result = $this->api_v3_request(
			sprintf( 'contacts/%d/contactTags', $contact_data['id'] ),
			'GET'
		);

		return array_values(
			array_map(
				function( $tag ) {
					return (int) $tag['tag'];
				},
				$result['contactTags']
			)
		);

	}

	/**
	 * Create a Tag on the provider
	 *
	 * @param string $tag The Tag name.
	 * @param string $list_id The List ID. Not needed for Active Campaign.
	 * @return array|WP_Error The tag representation with at least 'id' and 'name' keys on succes. WP_Error on failure.
	 */
	public function create_tag( $tag, $list_id = null ) {
		$tag_info = [
			'tag' => [
				'tag'         => $tag,
				'tagType'     => 'contact',
				'description' => 'Created by Newspack Newsletters to manage subscription lists',
			],
		];

		$created = $this->api_v3_request(
			'tags',
			'POST',
			[
				'body' => wp_json_encode( $tag_info ),
			]
		);
		if ( is_array( $created ) && ! empty( $created['tag'] ) && ! empty( $created['tag']['id'] ) ) {
			$created['tag']['name'] = $created['tag']['tag'];
			return $created['tag'];
		}
		return new WP_Error(
			'newspack_newsletters_error_creating_tag',
			! empty( $created['error'] ) ? $created['error'] : ''
		);
	}

	/**
	 * Add a tag to a contact
	 *
	 * @param string     $email The contact email.
	 * @param string|int $tag The tag ID retrieved with get_tag_id() or the the tag string.
	 * @param string     $list_id The List ID. Not needed for Active Campaign.
	 * @return true|WP_Error
	 */
	public function add_tag_to_contact( $email, $tag, $list_id = null ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
		}
		if ( ! is_integer( $tag ) ) {
			$tag = $this->get_tag_id( (string) $tag );
			if ( is_wp_error( $tag ) ) {
				return $tag;
			}
		}

		$contact_tag = [
			'contactTag' => [
				'contact' => (int) $existing_contact['id'],
				'tag'     => $tag,
			],
		];

		$created = $this->api_v3_request(
			'contactTags',
			'POST',
			[
				'body' => wp_json_encode( $contact_tag ),
			]
		);

		if ( is_array( $created ) && ! empty( $created['contacts'] ) ) {
			return true;
		}

		return new WP_Error(
			'newspack_newsletter_error_adding_tag_to_contact',
			! empty( $created['message'] ) ? $created['message'] : ''
		);

	}

	/**
	 * Remove a tag from a contact
	 *
	 * @param string     $email The contact email.
	 * @param string|int $tag The tag ID retrieved with get_tag_id() or the the tag string.
	 * @param string     $list_id The List ID. Not needed for Active Campaign.
	 * @return true|WP_Error
	 */
	public function remove_tag_from_contact( $email, $tag, $list_id = null ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
		}
		if ( ! is_integer( $tag ) ) {
			$tag = $this->get_tag_id( (string) $tag, false );
			if ( is_wp_error( $tag ) ) {
				return $tag;
			}
		}

		$contact_tag_id = $this->get_contact_tag_id( $email, $tag );

		if ( is_wp_error( $contact_tag_id ) ) {
			return $contact_tag_id;
		}

		$deleted = $this->api_v3_request(
			sprintf( 'contactTags/%d', $contact_tag_id ),
			'DELETE'
		);

		if ( is_array( $deleted ) && empty( $deleted ) ) {
			return true;
		}

		return new WP_Error(
			'newspack_newsletter_error_removing_tag_from_contact',
			! empty( $deleted['message'] ) ? $deleted['message'] : ''
		);

	}

	/**
	 * Get the ContactTag relationship ID from the provider
	 *
	 * @param string $email The contact email.
	 * @param int    $tag_id The Tag ID retrieved with get_tag_id.
	 * @return int|WP_Error The ID on success. WP_Error on failure.
	 */
	private function get_contact_tag_id( $email, $tag_id ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
		}

		$contact_tags = $this->api_v3_request(
			sprintf( 'contacts/%d/contactTags', (int) $existing_contact['id'] ),
			'GET'
		);

		if ( is_array( $contact_tags ) && ! empty( $contact_tags['contactTags'] ) ) {
			foreach ( $contact_tags['contactTags'] as $contact_tag ) {
				if ( (int) $tag_id === (int) $contact_tag['tag'] ) {
					return (int) $contact_tag['id'];
				}
			}
		}

		return new WP_Error(
			'newspack_newsletter_error_fetching_contact_tags'
		);
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
	 * @return array|WP_Error List of existing lists or error.
	 */
	public function get_lists() {
		if ( null !== $this->lists ) {
			return $this->lists;
		}
		$lists = $this->api_v1_request( 'list_list', 'GET', [ 'query' => [ 'ids' => 'all' ] ] );
		if ( is_wp_error( $lists ) ) {
			return $lists;
		}
		// Remove result metadata.
		unset( $lists['result_code'] );
		unset( $lists['result_message'] );
		unset( $lists['result_output'] );
		$this->lists = array_values( $lists );
		return $this->lists;
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
			return $sync_result;
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
		if ( is_wp_error( $test_result ) ) {
			return new WP_Error(
				'newspack_newsletters_active_campaign_test',
				sprintf( 'Sending test campaign failed: %s', $test_result->get_error_message() )
			);
		}
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
	 * Get data type for a given field.
	 *
	 * @param string $field_name The field name.
	 *
	 * @return int Data type ID.
	 */
	private static function get_metadata_type( $field_name ) {
		switch ( $field_name ) {
			case 'NP_Registration Date':
			case 'NP_Last Payment Date':
			case 'NP_Next Payment Date':
			case 'NP_Current Subscription End Date':
			case 'NP_Current Subscription Start Date':
				return 'date';
			default:
				return 'text';
		}
	}

	/**
	 * Add contact to a list or update an existing contact.
	 *
	 * @param array        $contact      {
	 *          Contact data.
	 *
	 *    @type string   $email    Contact email address.
	 *    @type string   $name     Contact name. Optional.
	 *    @type string[] $metadata Contact additional metadata. Optional.
	 *    @type string[] $tags     Contact tags. Optional.
	 * }
	 * @param string|false $list_id      List to add the contact to.
	 *
	 * @return array|WP_Error Contact data if the contact was added or error if failed.
	 */
	public function add_contact( $contact, $list_id = false ) {
		if ( ! isset( $contact['metadata'] ) ) {
			$contact['metadata'] = [];
		}
		$action  = 'contact_add';
		$payload = [
			'email' => $contact['email'],
		];

		$has_list_id = false !== $list_id;
		if ( $has_list_id ) {
			$payload[ 'p[' . $list_id . ']' ]      = $list_id;
			$payload[ 'status[' . $list_id . ']' ] = 1;
		}
		$existing_contact = $this->get_contact_data( $contact['email'] );
		if ( is_wp_error( $existing_contact ) ) {
			// Is a new contact.
			$existing_contact = false;
		} else {
			$action               = 'contact_edit';
			$payload['id']        = $existing_contact['id'];
			$payload['overwrite'] = 0;
		}

		if ( isset( $contact['name'] ) && ! empty( $contact['name'] ) ) {
			$name_fragments = explode( ' ', $contact['name'], 2 );
			$payload        = array_merge(
				$payload,
				[
					'first_name' => $name_fragments[0],
					'last_name'  => isset( $name_fragments[1] ) ? $name_fragments[1] : '',
				]
			);
		}

		/** Register metadata fields. */
		if ( ! empty( $contact['metadata'] ) ) {
			$existing_fields = $this->get_all_contact_fields();
			foreach ( $contact['metadata'] as $field_title => $value ) {
				$field_perstag = strtoupper( str_replace( '-', '_', sanitize_title( $field_title ) ) );
				/** For optimization, don't add the field if it already exists. */
				if ( is_wp_error( $existing_fields ) || false === array_search( $field_perstag, array_column( $existing_fields, 'perstag' ) ) ) {
					$field_res = $this->api_v3_request(
						'fields',
						'POST',
						[
							'body' => wp_json_encode(
								[
									'field' => [
										'title'   => $field_title,
										'type'    => self::get_metadata_type( $field_title ),
										'perstag' => $field_perstag,
										'visible' => 1,
									],
								]
							),
						]
					);
					/** Set list relation. */
					$this->api_v3_request(
						'fieldRels',
						'POST',
						[
							'body' => wp_json_encode(
								[
									'fieldRel' => [
										'field' => $field_res['field']['id'],
										'relid' => 0,
									],
								]
							),
						]
					);
				}
				$payload[ 'field[%' . $field_perstag . '%,0]' ] = (string) $value; // Per ESP documentation, "leave 0 as is".
			}
		}
		$result = $this->api_v1_request(
			$action,
			'POST',
			[
				'body' => $payload,
			]
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// On success, clear cached contact data to make sure we get updated data next time we need.
		$this->clear_contact_data( $contact['email'] );

		return [ 'id' => $result['subscriber_id'] ];
	}

	/**
	 * Delete contact from all lists given its email.
	 *
	 * @param string $email Email address.
	 *
	 * @return bool|WP_Error True if the contact was deleted, error if failed.
	 */
	public function delete_contact( $email ) {
		$contact = $this->get_contact_data( $email );
		if ( is_wp_error( $contact ) ) {
			return $contact;
		}
		$result = $this->api_v1_request( 'contact_delete', 'GET', [ 'query' => [ 'id' => $contact['id'] ] ] );
		return is_wp_error( $result ) ? $result : true;
	}

	/**
	 * Get the lists a contact is subscribed to.
	 *
	 * @param string $email The contact email.
	 *
	 * @return string[] Contact subscribed lists IDs.
	 */
	public function get_contact_lists( $email ) {
		$contact = $this->get_contact_data( $email );
		if ( is_wp_error( $contact ) ) {
			return [];
		}
		$contact_lists = $this->api_v3_request( 'contacts/' . $contact['id'] . '/contactLists' );
		if ( is_wp_error( $contact_lists ) || ! isset( $contact_lists['contactLists'] ) ) {
			return [];
		}
		$lists = [];
		foreach ( $contact_lists['contactLists'] as $list ) {
			if ( isset( $list['status'] ) && 1 === absint( $list['status'] ) ) {
				$lists[] = $list['list'];
			}
		}
		return $lists;
	}

	/**
	 * Update a contact lists subscription.
	 *
	 * @param string   $email           Contact email address.
	 * @param string[] $lists_to_add    Array of list IDs to subscribe the contact to.
	 * @param string[] $lists_to_remove Array of list IDs to remove the contact from.
	 *
	 * @return true|WP_Error True if the contact was updated or error.
	 */
	public function update_contact_lists( $email, $lists_to_add = [], $lists_to_remove = [] ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			/** Create contact */
			// Call Newspack_Newsletters_Subscription's method (not the provider's directly),
			// so the appropriate hooks are called.
			$contact_data = Newspack_Newsletters_Subscription::add_contact( [ 'email' => $email ] );
			if ( is_wp_error( $contact_data ) ) {
				return $contact_data;
			}
			$contact_id = $contact_data['id'];
		} else {
			$contact_id = $existing_contact['id'];
			/** Set status to "2" (unsubscribed) for lists to remove. */
			foreach ( $lists_to_remove as $list ) {
				$result = $this->api_v3_request(
					'contactLists',
					'POST',
					[
						'body' => wp_json_encode(
							[
								'contactList' => [
									'list'    => $list,
									'contact' => $contact_id,
									'status'  => 2,
								],
							]
						),
					]
				);
				if ( is_wp_error( $result ) ) {
					return $result;
				}
			}
		}
		/** Set status to "1" (subscribed) for lists to add. */
		foreach ( $lists_to_add as $list ) {
			$result = $this->api_v3_request(
				'contactLists',
				'POST',
				[
					'body' => wp_json_encode(
						[
							'contactList' => [
								'list'     => $list,
								'contact'  => $contact_id,
								'status'   => 1,
								'sourceid' => 4,
							],
						]
					),
				]
			);
			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}
		return true;
	}

	/**
	 * Update a contact tags.
	 *
	 * @param string   $email          Contact email address.
	 * @param string[] $tags_to_add    Array of tags to add to the contact.
	 * @param string[] $tags_to_remove Array of tags to remove from the contact.
	 *
	 * @return true|WP_Error True if the contact was updated or error.
	 */
	public function update_contact_tags( $email, $tags_to_add = [], $tags_to_remove = [] ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
		}
		foreach ( $tags_to_add as $tag_add ) {
			$this->add_tag_to_contact( $email, $tag_add );
		}
		foreach ( $tags_to_remove as $tag_remove ) {
			$this->remove_tag_from_contact( $email, $tag_remove );
		}
		return true;
	}

	/**
	 * Get the list of contact metadata fields.
	 *
	 * @param number $offset Offset for pagination.
	 */
	private function get_contact_fields( $offset ) {
		return $this->api_v3_request(
			'fields',
			'GET',
			[
				'query' => [
					'limit'  => 100,
					'offset' => $offset,
				],
			]
		);
	}

	/**
	 * Get the list of all available contact metadata fields.
	 *
	 * @param number $offset Offset for pagination.
	 */
	private function get_all_contact_fields( $offset = 0 ) {
		$response = $this->get_contact_fields( $offset );
		if ( \is_wp_error( $response ) ) {
			return $response;
		}
		$result     = $response['fields'];
		$new_offset = count( $result ) + $offset;
		if ( $new_offset < $response['meta']['total'] ) {
			$result = array_merge( $result, $this->get_all_contact_fields( $new_offset ) );
		}
		return $result;
	}

	/**
	 * Get contact data by email.
	 *
	 * @param string $email Email address.
	 * @param bool   $return_details Fetch full contact data.
	 *
	 * @return array|WP_Error Response or error if contact was not found.
	 */
	public function get_contact_data( $email, $return_details = false ) {
		if ( isset( $this->contact_data[ $email ] ) ) {
			$result = $this->contact_data[ $email ];
		} else {
			$result                       = $this->api_v3_request( 'contacts', 'GET', [ 'query' => [ 'email' => urlencode( $email ) ] ] );
			$this->contact_data[ $email ] = $result;
		}
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		if ( ! isset( $result['contacts'], $result['contacts'][0] ) ) {
			return new WP_Error( 'newspack_newsletters', __( 'No contact data found.' ) );
		}
		$contact_data = $result['contacts'][0];
		if ( $return_details ) {
			$contact_fields = $this->get_all_contact_fields();
			if ( \is_wp_error( $contact_fields ) ) {
				return $contact_fields;
			}
			$fields_perstag_by_id = array_reduce(
				$contact_fields,
				function( $acc, $field ) {
					$acc[ $field['id'] ] = $field['perstag'];
					return $acc;
				},
				[]
			);
			$contact_result       = $this->api_v3_request( 'contacts/' . $contact_data['id'], 'GET' );
			if ( \is_wp_error( $contact_result ) ) {
				return $contact_result;
			}
			$contact_fields           = array_reduce(
				$contact_result['fieldValues'],
				function( $acc, $field ) use ( $fields_perstag_by_id ) {
					if ( isset( $field['value'] ) && isset( $fields_perstag_by_id[ $field['field'] ] ) ) {
						$acc[ $fields_perstag_by_id[ $field['field'] ] ] = $field['value'];
					}
					return $acc;
				},
				[]
			);
			$contact_data['metadata'] = $contact_fields;
		}
		return $contact_data;
	}

	/**
	 * Clears cached Contact data
	 *
	 * @param string $email The contact email.
	 * @return void
	 */
	public function clear_contact_data( $email ) {
		if ( isset( $this->contact_data[ $email ] ) ) {
			unset( $this->contact_data[ $email ] );
		}
	}

	/**
	 * Get the provider specific labels
	 *
	 * This allows us to make reference to provider specific features in the way the user is used to see them in the provider's UI
	 *
	 * @return array
	 */
	public static function get_labels() {
		return array_merge(
			parent::get_labels(),
			[
				'name' => 'Active Campaign',
			]
		);
	}

	/**
	 * Add a notice to the Subscription Lists metabox letting the user know that they have to manually create the Segment
	 *
	 * @param array $settings The List settings.
	 * @return void
	 */
	public function lists_metabox_notice( $settings ) {
		if ( $settings['tag_name'] ) {
			?>
			<p class="subscription-list-warning">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %1$s and %2$s are opening and closing link tag to Active Campaign documentation. */
						__( 'Note for Active Campaign: You need to manually create a segment using the above tag to be able to send campaigns to this list. %1$sLearn more%2$s', 'newspack-newsletters' ),
						'<a href="https://help.activecampaign.com/hc/en-us/articles/221483407-How-to-create-segments-in-ActiveCampaign" target="_blank">',
						'</a>'
					),
					[
						'a' => [
							'href'   => [],
							'target' => [],
						],
					]
				);
				?>
			</p>
			<?php
		}
	}
}
