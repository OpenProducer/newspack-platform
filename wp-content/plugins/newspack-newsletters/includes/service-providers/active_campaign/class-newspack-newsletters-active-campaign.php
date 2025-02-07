<?php
/**
 * Service Provider: ActiveCampaign Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use Newspack\Newsletters\Send_Lists;
use Newspack\Newsletters\Send_List;

/**
 * ActiveCampaign ESP Class.
 */
final class Newspack_Newsletters_Active_Campaign extends \Newspack_Newsletters_Service_Provider {

	/**
	 * Provider name.
	 *
	 * @var string
	 */
	public $name = 'ActiveCampaign';

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
	 * Cached segments.
	 *
	 * @var array
	 */
	private $segments = null;

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
	public static $support_local_lists = true;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->service    = 'active_campaign';
		$this->controller = new Newspack_Newsletters_Active_Campaign_Controller( $this );

		add_action( 'updated_post_meta', [ $this, 'save' ], 10, 4 );
		add_action( 'wp_trash_post', [ $this, 'trash' ], 10, 1 );

		add_action( 'newspack_newsletters_subscription_lists_metabox_after_tag', [ $this, 'lists_metabox_notice' ] );

		parent::__construct( $this );
	}

	/**
	 * Get configuration for conditional tag support.
	 *
	 * @return array
	 */
	public static function get_conditional_tag_support() {
		return [
			'support_url' => 'https://help.activecampaign.com/hc/en-us/articles/220358207-Use-Conditional-Content',
			'example'     => [
				'before' => '%IF in_array(\'Interested in cameras\', $TAGS)%',
				'after'  => '%/IF%',
			],
		];
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
	public function api_v3_request( $resource, $method = 'GET', $options = [] ) {
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
			'timeout' => 45, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
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
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$response_message = wp_remote_retrieve_response_message( $response );

		if ( 400 < $response_code || ! empty( $response_body['errors'] ) ) {
			$errors = new WP_Error();
			if ( isset( $response_body['errors'] ) && is_array( $response_body['errors'] ) ) {
				foreach ( $response_body['errors'] as $error ) {
					$errors->add( $error['code'] ?? 'error', $error['title'] );
				}
			} elseif ( ! empty( $response_message ) ) {
				$errors->add( $response_code, $response_message );
			}

			return $errors;
		}
		return $response_body;
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
	public function api_v1_request( $action, $method = 'GET', $options = [] ) {
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
			'timeout' => 45, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			'headers' => [
				'Content-Type' => $content_type,
				'Accept'       => 'application/json',
				'API-TOKEN'    => $credentials['key'],
			],
			'body'    => $body,
		];
		$response = wp_safe_remote_request( $url, $args + $options );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$body = json_decode( $response['body'], true );
		if ( 1 !== $body['result_code'] ) {
			$message = ! empty( $body['result_message'] ) ? $body['result_message'] : __( 'An error occurred while communicating with ActiveCampaign.', 'newspack-newsletters' );
			return new \WP_Error(
				'newspack_newsletters_active_campaign_api_error',
				$message
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
				if ( ! empty( $found_tag['tag'] ) && strtolower( $tag_name ) === strtolower( $found_tag['tag'] ) ) {
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

		if ( is_wp_error( $created ) ) {
			return $created;
		}

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
				function ( $tag ) {
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
	 * Updates a Tag name on the provider
	 *
	 * @param string|int $tag_id The tag ID.
	 * @param string     $tag The Tag new name.
	 * @param string     $list_id The List ID. Not needed for Active Campaign.
	 * @return array|WP_Error The tag representation with at least 'id' and 'name' keys on succes. WP_Error on failure.
	 */
	public function update_tag( $tag_id, $tag, $list_id = null ) {
		$tag_info = [
			'tag' => [
				'tag'         => $tag,
				'tagType'     => 'contact',
				'description' => 'Created by Newspack Newsletters to manage subscription lists',
			],
		];

		$created = $this->api_v3_request(
			sprintf( 'tags/%d', $tag_id ),
			'PUT',
			[
				'body' => wp_json_encode( $tag_info ),
			]
		);
		if ( is_array( $created ) && ! empty( $created['tag'] ) && ! empty( $created['tag']['id'] ) ) {
			$created['tag']['name'] = $created['tag']['tag'];
			return $created['tag'];
		}
		return new WP_Error(
			'newspack_newsletters_error_updating_tag',
			! empty( $created['error'] ) ? $created['error'] : ''
		);
	}

	/**
	 * Add a tag to a contact
	 *
	 * @param string     $email The contact email.
	 * @param string|int $tag The tag ID.
	 * @param string     $list_id The List ID. Not needed for Active Campaign.
	 * @return true|WP_Error
	 */
	public function add_tag_to_contact( $email, $tag, $list_id = null ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
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
	 * @param string|int $tag The tag ID.
	 * @param string     $list_id The List ID. Not needed for Active Campaign.
	 * @return true|WP_Error
	 */
	public function remove_tag_from_contact( $email, $tag, $list_id = null ) {
		$existing_contact = $this->get_contact_data( $email );
		if ( is_wp_error( $existing_contact ) ) {
			return $existing_contact;
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
	 * @param array $args Query args to pass to the lists_lists endpoint.
	 *                    For supported args, see: https://www.activecampaign.com/api/example.php?call=list_list.
	 *
	 * @return array|WP_Error List of existing lists or error.
	 */
	public function get_lists( $args = [] ) {
		if ( null !== $this->lists ) {
			if ( ! empty( $args['ids'] ) ) {
				return array_values(
					array_filter(
						$this->lists,
						function ( $list ) use ( $args ) {
							return Send_Lists::matches_id( $args['ids'], $list['id'] );
						}
					)
				);
			}
			if ( ! empty( $args['filters[name]'] ) ) {
				return array_values(
					array_filter(
						$this->lists,
						function ( $list ) use ( $args ) {
							return Send_Lists::matches_search( $args['filters[name]'], [ $list['name'] ] );
						}
					)
				);
			}
			return $this->lists;
		}
		if ( empty( $args['ids'] ) && empty( $args['filters[name]'] ) ) {
			$args['ids'] = 'all';
		}
		$lists = $this->api_v1_request( 'list_list', 'GET', [ 'query' => $args ] );
		if ( is_wp_error( $lists ) ) {
			return $lists;
		}
		// Remove result metadata.
		unset( $lists['result_code'] );
		unset( $lists['result_message'] );
		unset( $lists['result_output'] );

		if ( ! empty( $args['ids'] ) && 'all' === $args['ids'] ) {
			$this->lists = array_values( $lists );
		}
		return array_values( $lists );
	}

	/**
	 * Get all applicable lists and segments as Send_List objects.
	 *
	 * @param array   $args Array of search args. See Send_Lists::get_default_args() for supported params and default values.
	 * @param boolean $to_array If true, convert Send_List objects to arrays before returning.
	 *
	 * @return Send_List[]|array|WP_Error Array of Send_List objects or arrays on success, or WP_Error object on failure.
	 */
	public function get_send_lists( $args = [], $to_array = false ) {
		$send_lists = [];
		if ( empty( $args['type'] ) || 'list' === $args['type'] ) {
			$list_args = [
				'limit' => ! empty( $args['limit'] ) ? intval( $args['limit'] ) : 100,
			];

			// Search by IDs.
			if ( ! empty( $args['ids'] ) ) {
				$list_args['ids'] = implode( ',', $args['ids'] );
			}

			// Search by name.
			if ( ! empty( $args['search'] ) ) {
				if ( is_array( $args['search'] ) ) {
					return new WP_Error(
						'newspack_newsletters_active_campaign_fetch_send_lists',
						__( 'ActiveCampaign supports searching by a single search term only.', 'newspack-newsletters' )
					);
				}
				$list_args['filters[name]'] = $args['search'];
			}

			$lists = $this->get_lists( $list_args );
			if ( is_wp_error( $lists ) ) {
				return $lists;
			}
			foreach ( $lists as $list ) {
				$send_lists[] = new Send_List(
					[
						'provider'    => $this->service,
						'type'        => 'list',
						'id'          => $list['id'],
						'name'        => $list['name'],
						'entity_type' => 'list',
						'count'       => $list['subscriber_count'] ?? 0,
					]
				);
			}
		}

		if ( empty( $args['type'] ) || 'sublist' === $args['type'] ) {
			$segment_args = [];
			if ( ! empty( $args['ids'] ) ) {
				$segment_args['ids'] = $args['ids'];
			}
			if ( ! empty( $args['search'] ) ) {
				$segment_args['search'] = $args['search'];
			}
			$segments = $this->get_segments( $segment_args );
			if ( is_wp_error( $segments ) ) {
				return $segments;
			}
			foreach ( $segments as $segment ) {
				$segment_name = ! empty( $segment['name'] ) ?
					$segment['name'] . ' (ID ' . $segment['id'] . ')' :
					sprintf(
						// Translators: %s is the segment ID.
						__( 'Untitled %s', 'newspack-newsletters' ),
						$segment['id']
					);
				$send_lists[] = new Send_List(
					[
						'provider'    => $this->service,
						'type'        => 'sublist',
						'id'          => $segment['id'],
						'parent_id'   => $args['parent_id'] ?? null,
						'name'        => $segment_name,
						'entity_type' => 'segment',
						'count'       => $segment['subscriber_count'] ?? null,
					]
				);
			}
		}

		// Convert to arrays if requested.
		if ( $to_array ) {
			$send_lists = array_map(
				function ( $list ) {
					return $list->to_array();
				},
				$send_lists
			);
		}
		return $send_lists;
	}

	/**
	 * Get segments.
	 *
	 * @param array $args Array of search args.
	 *
	 * @return array|WP_Error List os existing segments or error.
	 */
	public function get_segments( $args = [] ) {
		if ( null !== $this->segments ) {
			if ( ! empty( $args['ids'] ) ) {
				$filtered = array_values(
					array_filter(
						$this->segments,
						function ( $segment ) use ( $args ) {
							return Send_Lists::matches_id( $args['ids'], $segment['id'] );
						}
					)
				);
				return array_slice( $filtered, 0, $args['limit'] ?? count( $filtered ) );
			}
			if ( ! empty( $args['search'] ) ) {
				$filtered = array_values(
					array_filter(
						$this->segments,
						function ( $segment ) use ( $args ) {
							return Send_Lists::matches_search( $args['search'], [ $segment['name'] ] );
						}
					)
				);
				return array_slice( $filtered, 0, $args['limit'] ?? count( $filtered ) );
			}
			return $this->segments;
		}

		$query_args           = $args;
		$query_args['limit']  = $args['limit'] ?? 100;
		$query_args['offset'] = 0;
		$result = $this->api_v3_request(
			'segments',
			'GET',
			[
				'query' => $query_args,
			]
		);
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		$segments = $result['segments'];
		if ( isset( $args['limit'] ) ) {
			return $segments;
		}

		// If not passed a limit, get all the segments.
		$total = $result['meta']['total'];
		while ( $total > $query_args['offset'] + $query_args['limit'] ) {
			$query_args['offset'] = $query_args['offset'] + $query_args['limit'];
			$result = $this->api_v3_request(
				'segments',
				'GET',
				[
					'query' => $query_args,
				]
			);
			if ( is_wp_error( $result ) ) {
				return $result;
			}
			$segments = array_merge( $segments, $result['segments'] );
		}

		$this->segments = $segments;
		if ( ! empty( $args['ids'] ) || ! empty( $args['search'] ) ) {
			return $this->get_segments( $args );
		}

		return $this->segments;
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
	 * Given legacy newsletterData, extract sender and send-to info.
	 *
	 * @param array $newsletter_data Newsletter data from the ESP.
	 * @return array {
	 *    Extracted sender and send-to info. All keys are optional and will be
	 *    returned only if found in the campaign data.
	 *
	 *    @type string $senderName Sender name.
	 *    @type string $senderEmail Sender email.
	 *    @type string $list_id List ID.
	 *    @type string $sublist_id Sublist ID.
	 * }
	 */
	public function extract_campaign_info( $newsletter_data ) {
		$campaign_info = [];

		// Sender info.
		if ( ! empty( $newsletter_data['from_name'] ) ) {
			$campaign_info['senderName'] = $newsletter_data['from_name'];
		}
		if ( ! empty( $newsletter_data['from_email'] ) ) {
			$campaign_info['senderEmail'] = $newsletter_data['from_email'];
		}

		// List.
		if ( ! empty( $newsletter_data['list_id'] ) ) {
			$campaign_info['list_id'] = $newsletter_data['list_id'];
		}

		// Segment.
		if ( ! empty( $newsletter_data['segment_id'] ) ) {
			$campaign_info['sublist_id'] = $newsletter_data['segment_id'];
		}

		return $campaign_info;
	}

	/**
	 * Retrieve a campaign.
	 *
	 * @param int  $post_id    Numeric ID of the Newsletter post.
	 * @param bool $skip_sync Whether to skip syncing the campaign.
	 * @throws Exception Error message.

	 * @return array|WP_Error API Response or error.
	 */
	public function retrieve( $post_id, $skip_sync = false ) {
		try {
			if ( ! $this->has_api_credentials() ) {
				throw new Exception( esc_html__( 'Missing or invalid ActiveCampign credentials.', 'newspack-newsletters' ) );
			}

			$campaign_id     = get_post_meta( $post_id, 'ac_campaign_id', true );
			$send_list_id    = get_post_meta( $post_id, 'send_list_id', true );
			$send_sublist_id = get_post_meta( $post_id, 'send_sublist_id', true );
			$newsletter_data = [
				'campaign'                          => true, // Satisfy the JS API.
				'campaign_id'                       => $campaign_id,
				'supports_multiple_test_recipients' => true,
			];

			// Handle legacy send-to meta.
			if ( ! $send_list_id ) {
				$legacy_list_id = get_post_meta( $post_id, 'ac_list_id', true );
				if ( $legacy_list_id ) {
					$newsletter_data['send_list_id'] = $legacy_list_id;
					$send_list_id               = $legacy_list_id;
				}
			}
			if ( ! $send_sublist_id ) {
				$legacy_sublist_id = get_post_meta( $post_id, 'ac_segment_id', true );
				if ( $legacy_sublist_id ) {
					$newsletter_data['send_sublist_id'] = $legacy_sublist_id;
					$send_sublist_id               = $legacy_sublist_id;
				}
			}
			$send_lists = $this->get_send_lists( // Get first 10 top-level send lists for autocomplete.
				[
					'ids'  => $send_list_id ? [ $send_list_id ] : null, // If we have a selected list, make sure to fetch it.
					'type' => 'list',
				],
				true
			);
			if ( is_wp_error( $send_lists ) ) {
				throw new Exception( wp_kses_post( $send_lists->get_error_message() ) );
			}
			$newsletter_data['lists'] = $send_lists;
			$send_sublists = $send_list_id || $send_sublist_id ?
				$this->get_send_lists(
					[
						'ids'       => [ $send_sublist_id ], // If we have a selected sublist, make sure to fetch it. Otherwise, we'll populate sublists later.
						'parent_id' => $send_list_id,
						'type'      => 'sublist',
					],
					true
				) :
				[];
			if ( is_wp_error( $send_sublists ) ) {
				throw new Exception( wp_kses_post( $send_sublists->get_error_message() ) );
			}
			$newsletter_data['sublists'] = $send_sublists;

			if ( $campaign_id ) {
				$newsletter_data['link'] = sprintf(
					'https://%s.activehosted.com/app/campaigns/%d',
					explode( '.', str_replace( 'https://', '', $this->api_credentials()['url'] ) )[0],
					$campaign_id
				);
			}

			// Handle legacy sender meta.
			$from_name   = get_post_meta( $post_id, 'senderName', true );
			$from_email  = get_post_meta( $post_id, 'senderEmail', true );
			if ( ! $from_name ) {
				$legacy_from_name = get_post_meta( $post_id, 'ac_from_name', true );
				if ( $legacy_from_name ) {
					$newsletter_data['senderName'] = $legacy_from_name;
				}
			}
			if ( ! $from_email ) {
				$legacy_from_email = get_post_meta( $post_id, 'ac_from_email', true );
				if ( $legacy_from_email ) {
					$newsletter_data['senderEmail'] = $legacy_from_email;
				}
			}

			if ( ! $campaign_id && true !== $skip_sync ) {
				$sync_result = $this->sync( get_post( $post_id ) );
				if ( is_wp_error( $sync_result ) ) {
					throw new Exception( $sync_result->get_error_message() );
				}
				$newsletter_data = wp_parse_args(
					$sync_result,
					$newsletter_data
				);
			}
			return $newsletter_data;
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_active_campaign_error',
				$e->getMessage()
			);
		}
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

		/** Get the latest message ID from the temporary campaign. */
		$campaign_data = $this->api_v1_request(
			'campaign_list',
			'GET',
			[
				'query' => [
					'action' => 'test',
					'ids'    => $campaign['id'],
				],
			]
		);
		if ( is_wp_error( $campaign_data ) ) {
			return $campaign_data;
		}
		$campaign_messages = explode( ',', $campaign_data[0]['messageslist'] );
		$message_id        = ! empty( $campaign_messages ) ? reset( $campaign_messages ) : 0;

		$test_result = $this->api_v1_request(
			'campaign_send',
			'GET',
			[
				'query' => [
					'type'       => 'html',
					'action'     => 'test',
					'campaignid' => $campaign['id'],
					'messageid'  => $message_id,
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

		// Clear prior error messages.
		$transient_name = $this->get_transient_name( $post->ID );
		delete_transient( $transient_name );

		$from_name    = get_post_meta( $post->ID, 'senderName', true );
		$from_email   = get_post_meta( $post->ID, 'senderEmail', true );
		$send_list_id = get_post_meta( $post->ID, 'send_list_id', true );
		$message_id   = get_post_meta( $post->ID, 'ac_message_id', true );

		$renderer = new Newspack_Newsletters_Renderer();
		$content  = $renderer->retrieve_email_html( $post );

		$message_action = 'message_add';
		$message_data   = [];
		$sync_data = [
			'campaign' => true, // Satisfy JS API.
		];

		if ( $message_id ) {
			$message = $this->api_v1_request( 'message_view', 'GET', [ 'query' => [ 'id' => $message_id ] ] );
			if ( is_wp_error( $message ) ) {
				return $message;
			}
			$message_action     = 'message_edit';
			$message_data['id'] = $message['id'];

			// If sender data is not available locally, update from ESP.
			if ( ! $from_name || ! $from_email ) {
				$sync_data['senderName']  = $message['fromname'];
				$sync_data['senderEmail'] = $message['fromemail'];
			}
		} else {
			// Validate required meta if campaign and message are not yet created.
			if ( empty( $from_name ) || empty( $from_email ) ) {
				return new \WP_Error(
					'newspack_newsletters_active_campaign_invalid_sender',
					__( 'Please input sender name and email address.', 'newspack-newsletters' )
				);
			}
			if ( empty( $send_list_id ) ) {
				return new \WP_Error(
					'newspack_newsletters_active_campaign_invalid_list',
					__( 'Please select a list.', 'newspack-newsletters' )
				);
			}
		}

		$message_data = wp_parse_args(
			[
				'format'                   => 'html',
				'htmlconstructor'          => 'editor',
				'html'                     => $content,
				'p[' . $send_list_id . ']' => 1,
				'fromemail'                => $from_email,
				'fromname'                 => $from_name,
				'subject'                  => html_entity_decode( $post->post_title ),
			],
			$message_data
		);

		$message = $this->api_v1_request( $message_action, 'POST', [ 'body' => $message_data ] );
		if ( is_wp_error( $message ) ) {
			return $message;
		}

		update_post_meta( $post->ID, 'ac_message_id', $message['id'] );
		$sync_data['message_id'] = $message['id'];

		// Retrieve and store campaign data.
		$data = $this->retrieve( $post->ID, true );
		if ( is_wp_error( $data ) ) {
			set_transient( $transient_name, __( 'ActiveCampaign sync error: ', 'newspack-newsletters' ) . $data->get_error_message(), 45 );
			return $data;
		} else {
			$data = array_merge( $data, $sync_data );
		}

		return $sync_data;
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

		$from_name       = get_post_meta( $post->ID, 'senderName', true );
		$from_email      = get_post_meta( $post->ID, 'senderEmail', true );
		$send_list_id    = get_post_meta( $post->ID, 'send_list_id', true );
		$send_sublist_id = get_post_meta( $post->ID, 'send_sublist_id', true );

		$is_public = get_post_meta( $post->ID, 'is_public', true );
		if ( empty( $campaign_name ) ) {
			$campaign_name = $this->get_campaign_name( $post );
		}
		$campaign_data = [
			'type'                                  => 'single',
			'status'                                => 0, // 0 = Draft; 1 = Scheduled.
			'public'                                => (int) $is_public,
			'name'                                  => $campaign_name,
			'fromname'                              => $from_name,
			'fromemail'                             => $from_email,
			'segmentid'                             => $send_sublist_id ?? 0, // 0 = No segment.
			'p[' . $send_list_id . ']'              => $send_list_id,
			'm[' . $sync_result['message_id'] . ']' => 100, // 100 = 100% of contacts will receive this.
		];
		if ( defined( 'NEWSPACK_NEWSLETTERS_AC_DISABLE_LINK_TRACKING' ) && NEWSPACK_NEWSLETTERS_AC_DISABLE_LINK_TRACKING ) {
			$campaign_data['tracklinks'] = 'none';
		}
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
	 * Update ESP campaign after refreshing the email HTML, which is triggered by post save.
	 *
	 * @param int   $meta_id Numeric ID of the meta field being updated.
	 * @param int   $post_id The post ID for the meta field being updated.
	 * @param mixed $meta_key The meta key being updated.
	 */
	public function save( $meta_id, $post_id, $meta_key ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( Newspack_Newsletters::EMAIL_HTML_META !== $meta_key ) {
			return;
		}
		$post = get_post( $post_id );
		if ( ! Newspack_Newsletters_Editor::is_editing_email( $post_id ) ) {
			return;
		}
		if ( 'trash' === $post->post_status ) {
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
		$date_fields = [
			'Registration Date',
			'Last Payment Date',
			'Next Payment Date',
			'Current Subscription End Date',
			'Current Subscription Start Date',
		];

		foreach ( $date_fields as $date_field ) {
			if ( str_contains( $field_name, $date_field ) ) {
				return 'date';
			}
		}
		return 'text';
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
					if ( \is_wp_error( $field_res ) ) {
						return $field_res;
					}
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
			// Log the error with any details returned from the API.
			do_action(
				'newspack_log',
				'newspack_active_campaign_add_contact_failed',
				'Error adding contact to ActiveCampaign.',
				[
					'type'       => 'error',
					'data'       => [
						'messages' => $result->get_error_messages(),
						'status'   => $result->get_error_code(),
					],
					'user_email' => $contact['email'],
					'file'       => 'newspack_active_campaign',
				]
			);

			if ( Newspack_Newsletters::debug_mode() ) {
				return $result;
			}

			$reader_error = $this->get_add_contact_reader_error_message(
				[
					'email'   => $contact['email'],
					'list_id' => $list_id,
				]
			);
			return new \WP_Error( 'newspack_active_campaign_add_contact_failed', $reader_error );
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
			// Call Newspack_Newsletters_Contacts's method (not the provider's directly),
			// so the appropriate hooks are called.
			$contact_data = Newspack_Newsletters_Contacts::upsert( [ 'email' => $email ] );
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
			$fields = $this->get_all_contact_fields( $new_offset );
			if ( \is_wp_error( $fields ) ) {
				return $fields;
			}
			$result = array_merge( $result, $fields );
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
				function ( $acc, $field ) {
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
				function ( $acc, $field ) use ( $fields_perstag_by_id ) {
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
	 * @param mixed $context The context in which the labels are being applied.
	 * @return array
	 */
	public static function get_labels( $context = '' ) {
		return array_merge(
			parent::get_labels(),
			[
				'name'                   => 'Active Campaign',
				'list_explanation'       => __( 'Active Campaign List', 'newspack-newsletters' ),
				'local_list_explanation' => __( 'Active Campaign Tag', 'newspack-newsletters' ),
				'list'                   => __( 'list', 'newspack-newsletters' ), // "list" in lower case singular format.
				'lists'                  => __( 'lists', 'newspack-newsletters' ), // "list" in lower case plural format.
				'sublist'                => __( 'segment', 'newspack-newsletters' ), // Sublist entities in lowercase singular format.
				'List'                   => __( 'List', 'newspack-newsletters' ), // "list" in uppercase case singular format.
				'Lists'                  => __( 'Lists', 'newspack-newsletters' ), // "list" in uppercase case plural format.
				'Sublist'                => __( 'Segments', 'newspack-newsletters' ), // Sublist entities in uppercase singular format.
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

	/**
	 * Get usage report.
	 */
	public function get_usage_report() {
		$ac_usage_reports = new Newspack_Newsletters_Active_Campaign_Usage_Reports();
		return $ac_usage_reports->get_usage_report();
	}
}
