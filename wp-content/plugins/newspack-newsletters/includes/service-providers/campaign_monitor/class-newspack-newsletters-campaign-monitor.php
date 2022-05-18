<?php
/**
 * Service Provider: Campaign Monitor Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

// Increase default timeout for 3rd-party API requests to 30s.
define( 'CS_REST_CALL_TIMEOUT', 30 );

/**
 * Main Newspack Newsletters Class for Campaign Monitor ESP.
 */
final class Newspack_Newsletters_Campaign_Monitor extends \Newspack_Newsletters_Service_Provider {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->service    = 'campaign_monitor';
		$this->controller = new Newspack_Newsletters_Campaign_Monitor_Controller( $this );

		add_action( 'save_post_' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT, [ $this, 'save' ], 10, 3 );

		parent::__construct( $this );
	}

	/**
	 * Get API credentials for service provider.
	 *
	 * @return Object Stored API credentials for the service provider.
	 */
	public function api_credentials() {
		return [
			'api_key'   => get_option( 'newspack_newsletters_campaign_monitor_api_key', '' ),
			'client_id' => get_option( 'newspack_newsletters_campaign_monitor_client_id', '' ),
		];
	}

	/**
	 * Check if provider has all necessary credentials set.
	 *
	 * @return Boolean Result.
	 */
	public function has_api_credentials() {
		return ! empty( $this->api_key() ) && ! empty( $this->client_id() );
	}

	/**
	 * Get API key for service provider.
	 *
	 * @return String Stored API key for the service provider.
	 */
	public function api_key() {
		$credentials = self::api_credentials();
		return $credentials['api_key'];
	}

	/**
	 * Get Access Token key for service provider.
	 *
	 * @return String Stored Access Token key for the service provider.
	 */
	public function client_id() {
		$credentials = self::api_credentials();
		return $credentials['client_id'];
	}

	/**
	 * Get sender info.
	 */

	/**
	 * Set the API credentials for the service provider.
	 *
	 * @param object $credentials API credentials.
	 */
	public function set_api_credentials( $credentials ) {
		if ( empty( $credentials['api_key'] ) || empty( $credentials['client_id'] ) ) {
			return new WP_Error(
				'newspack_newsletters_invalid_keys',
				__( 'Please input Campaign Monitor API key and Client ID.', 'newspack-newsletters' )
			);
		} else {
			$update_api_key   = update_option( 'newspack_newsletters_campaign_monitor_api_key', $credentials['api_key'] );
			$update_client_id = update_option( 'newspack_newsletters_campaign_monitor_client_id', $credentials['client_id'] );
			return $update_api_key && $update_client_id;
		}
	}

	/**
	 * Get lists for a client iD.
	 *
	 * @return object|WP_Error API API Response or error.
	 */
	public function get_lists() {
		$api_key   = $this->api_key();
		$client_id = $this->client_id();

		if ( ! $api_key ) {
			return new WP_Error(
				'newspack_newsletters_missing_api_key',
				__( 'No Campaign Monitor API key available.', 'newspack-newsletters' )
			);
		}
		if ( ! $client_id ) {
			return new WP_Error(
				'newspack_newsletters_missing_client_id',
				__( 'No Campaign Monitor Client ID available.', 'newspack-newsletters' )
			);
		}

		$cm_clients = new CS_REST_Clients( $client_id, [ 'api_key' => $api_key ] );
		$lists      = $cm_clients->get_lists();

		// If the request failed, throw an error.
		if ( ! $lists->was_successful() ) {
			return new WP_Error(
				'newspack_newsletters_campaign_monitor_error',
				__( 'Could not retrieve Campaign Monitor list info. Please check your API key and client ID.', 'newspack-newsletters' )
			);
		}

		return array_map(
			function ( $item ) {
				$item->id   = $item->ListID; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$item->name = $item->Name; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				return $item;
			},
			$lists->response
		);
	}

	/**
	 * Get segments for a client iD.
	 *
	 * @return object|WP_Error API API Response or error.
	 */
	public function get_segments() {
		$api_key   = $this->api_key();
		$client_id = $this->client_id();

		if ( ! $api_key ) {
			return new WP_Error(
				'newspack_newsletters_missing_api_key',
				__( 'No Campaign Monitor API key available.', 'newspack-newsletters' )
			);
		}
		if ( ! $client_id ) {
			return new WP_Error(
				'newspack_newsletters_missing_client_id',
				__( 'No Campaign Monitor Client ID available.', 'newspack-newsletters' )
			);
		}

		$cm_clients = new CS_REST_Clients( $client_id, [ 'api_key' => $api_key ] );
		$segments   = $cm_clients->get_segments();

		// If the request failed, throw an error.
		if ( ! $segments->was_successful() ) {
			return new WP_Error(
				'newspack_newsletters_campaign_monitor_error',
				__( 'Could not retrieve Campaign Monitor segment info. Please check your API key and client ID.', 'newspack-newsletters' )
			);
		}

		return $segments->response;
	}

	/**
	 * Retrieve campaign details.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @param boolean $fetch_all If true, returns all campaign data, even those stored in WP.
	 * @return object|WP_Error API Response or error.
	 */
	public function retrieve( $post_id, $fetch_all = false ) {
		if ( ! $this->has_api_credentials() ) {
			return [];
		}
		try {
			$cm       = new CS_REST_General( $this->api_key() );
			$response = [];

			$lists    = $this->get_lists();
			$segments = $this->get_segments();

			$response['lists']    = ! empty( $lists ) ? $lists : [];
			$response['segments'] = ! empty( $segments ) ? $segments : [];

			if ( $fetch_all ) {
				$cm_send_mode  = $this->retrieve_send_mode( $post_id );
				$cm_list_id    = $this->retrieve_list_id( $post_id );
				$cm_segment_id = $this->retrieve_segment_id( $post_id );
				$cm_from_name  = $this->retrieve_from_name( $post_id );
				$cm_from_email = $this->retrieve_from_email( $post_id );

				$response['send_mode']  = $cm_send_mode;
				$response['list_id']    = $cm_list_id;
				$response['segment_id'] = $cm_segment_id;
				$response['from_name']  = $cm_from_name;
				$response['from_email'] = $cm_from_email;
				$response['campaign']   = true;
			}

			return $response;
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_campaign_monitor_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Send test email or emails.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @param array   $emails Array of email addresses to send to.
	 * @return object|WP_Error API Response or error.
	 */
	public function test( $post_id, $emails ) {
		try {
			$api_key   = $this->api_key();
			$client_id = $this->client_id();

			if ( ! $api_key ) {
				return new WP_Error(
					'newspack_newsletters_missing_api_key',
					__( 'No Campaign Monitor API key available.', 'newspack-newsletters' )
				);
			}
			if ( ! $client_id ) {
				return new WP_Error(
					'newspack_newsletters_missing_client_id',
					__( 'No Campaign Monitor Client ID available.', 'newspack-newsletters' )
				);
			}

			$cm_campaigns = new CS_REST_Campaigns( null, [ 'api_key' => $api_key ] );
			$args         = $this->format_campaign_args( $post_id );

			// Create a temporary test campaign and get the ID from the response.
			$test_campaign = $cm_campaigns->create( $client_id, $args );

			if ( ! $test_campaign->was_successful() ) {
				return new WP_Error(
					'newspack_newsletters_campaign_monitor_error',
					__( 'Failed sending Campaign Monitor test campaign: ', 'newspack-newsletters' ) . $test_campaign->response->Message
				);
			}

			// Use the temporary test campaign ID to send a preview.
			$preview = new CS_REST_Campaigns( $test_campaign->response, [ 'api_key' => $api_key ] );
			$preview->send_preview( $emails );

			// After sending a preview, delete the temporary test campaign. We must do this because the API doesn't support updating campaigns.
			$delete = $preview->delete();

			$data['result']  = $test_campaign->response;
			$data['message'] = sprintf(
			// translators: Message after successful test email.
				__( 'Campaign Monitor test sent successfully to %s.', 'newspack-newsletters' ),
				implode( ', ', $emails )
			);

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_campaign_monitor_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Creates an array of arguments to pass to the Campaign Monitor API.
	 *
	 * @param integer $post_id Post ID for the newsletter.
	 * @return object Args for sending a campaign or campaign preview.
	 */
	public function format_campaign_args( $post_id ) {
		$data = $this->validate( $this->retrieve( $post_id, true ) );
		$args = [
			'Subject'   => get_the_title( $post_id ),
			'Name'      => get_the_title( $post_id ) . ' ' . gmdate( 'h:i:s A' ), // Name must be unique.
			'FromName'  => $data['from_name'],
			'FromEmail' => $data['from_email'],
			'ReplyTo'   => $data['from_email'],
			'HtmlUrl'   => rest_url(
				$this::BASE_NAMESPACE . $this->service . '/' . $post_id . '/content'
			),
		];

		if ( 'list' === $data['send_mode'] ) {
			$args['ListIDs'] = [ $data['list_id'] ];
		} else {
			$args['SegmentIDs'] = [ $data['segment_id'] ];
		}

		return $args;
	}

	/**
	 * Get rendered HTML content of post for the campaign.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return object|WP_Error API Response or error.
	 */
	public function content( $post_id ) {
		if ( ! Newspack_Newsletters::validate_newsletter_id( $post_id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}

		$post     = get_post( $post_id );
		$renderer = new Newspack_Newsletters_Renderer();
		$html     = $renderer->retrieve_email_html( $post );

		return $html;
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

		$api_key   = $this->api_key();
		$client_id = $this->client_id();

		if ( ! $api_key ) {
			return new WP_Error(
				'newspack_newsletter_error',
				__( 'No Campaign Monitor API key available.', 'newspack-newsletters' )
			);
		}
		if ( ! $client_id ) {
			return new WP_Error(
				'newspack_newsletter_error',
				__( 'No Campaign Monitor Client ID available.', 'newspack-newsletters' ) 
			);
		}

		$cm_campaigns = new CS_REST_Campaigns( null, [ 'api_key' => $api_key ] );
		$args         = $this->format_campaign_args( $post_id );

		// Set the current user's email address as the email to receive sent confirmation.
		$current_user       = wp_get_current_user();
		$confirmation_email = $current_user->user_email;

		// Create a draft campaign and get the ID from the response.
		$new_campaign = $cm_campaigns->create( $client_id, $args );

		if ( ! $new_campaign->was_successful() ) {
			return new WP_Error(
				'newspack_newsletter_error',
				__( 'Failed creating Campaign Monitor campaign: ', 'newspack-newsletters' ) . $new_campaign->response->Message
			);
		}

		try {
			// Send the draft campaign.
			$campaign_to_send = new CS_REST_Campaigns( $new_campaign->response, [ 'api_key' => $api_key ] );
			$campaign_to_send->send(
				[
					'ConfirmationEmail' => $confirmation_email,
					'SendDate'          => 'Immediately',
				]
			);
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_campaign_monitor_error',
				$e->getMessage()
			);
		}

		return true;
	}

	/**
	 * Convenience method to retrieve the Campaign Monitor campaign ID for a post.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return string Campaign Monitor campaign ID.
	 */
	public function retrieve_campaign_id( $post_id ) {
		return get_post_meta( $post_id, 'cm_campaign_id', true );
	}

	/**
	 * Convenience method to retrieve the Campaign Monitor send mode for a post.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return string Campaign Monitor send mode
	 */
	public function retrieve_send_mode( $post_id ) {
		return get_post_meta( $post_id, 'cm_send_mode', true );
	}

	/**
	 * Convenience method to retrieve the Campaign Monitor list ID for a post.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return string Campaign Monitor list ID.
	 */
	public function retrieve_list_id( $post_id ) {
		return get_post_meta( $post_id, 'cm_list_id', true );
	}

	/**
	 * Convenience method to retrieve the Campaign Monitor segment ID for a post.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return string Campaign Monitor segment ID.
	 */
	public function retrieve_segment_id( $post_id ) {
		return get_post_meta( $post_id, 'cm_segment_id', true );
	}

	/**
	 * Convenience method to retrieve the Campaign Monitor From Name for a post.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return string Campaign Monitor from name.
	 */
	public function retrieve_from_name( $post_id ) {
		return get_post_meta( $post_id, 'cm_from_name', true );
	}

	/**
	 * Convenience method to retrieve the Campaign Monitor From Email for a post.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return string Campaign Monitor from email.
	 */
	public function retrieve_from_email( $post_id ) {
		return get_post_meta( $post_id, 'cm_from_email', true );
	}

	/**
	 * Validate newsletter data required to send a campaign.
	 * Throws an error if data is missing or invalid.
	 *
	 * @param Object $data Newsletter data to validate.
	 * @param String $preferred_error Preset error to use instead of generic errors.
	 * @throws Exception Error message.
	 * @return Ojbect Validated data.
	 */
	public function validate( $data, $preferred_error = null ) {
		if ( ! $data ) {
			if ( $preferred_error ) {
				// If passed an error, throw that.
				throw new Exception( $preferred_error );
			} else {
				// Otherwise, throw the generic error.
				throw new Exception( __( 'Campaign Monitor error: Missing required campaign data.', 'newspack-newsletters' ) );
			}
		}

		if ( empty( $data['send_mode'] ) || empty( $data['from_name'] ) || empty( $data['from_email'] ) ) {
			// If passed an error, throw that.
			if ( $preferred_error && ! Newspack_Newsletters::debug_mode() ) {
				throw new Exception( $preferred_error );
			}

			// Otherwise, throw the generic error.
			throw new Exception( __( 'Campaign Monitor error: Missing campaign sender data.', 'newspack-newsletters' ) );
		}

		if ( 'list' === $data['send_mode'] && empty( $data['list_id'] ) ) {
			// If passed an error, throw that.
			if ( $preferred_error && ! Newspack_Newsletters::debug_mode() ) {
				throw new Exception( $preferred_error );
			}

			// Otherwise, throw the generic error.
			throw new Exception( __( 'Campaign Monitor error: Must select a list if sending in list mode.', 'newspack-newsletters' ) );
		}

		if ( 'segment' === $data['send_mode'] && empty( $data['segment_id'] ) ) {
			// If passed an error, throw that.
			if ( $preferred_error && ! Newspack_Newsletters::debug_mode() ) {
				throw new Exception( $preferred_error );
			}

			// Otherwise, throw the generic error.
			throw new Exception( __( 'Campaign Monitor error: Must select a segment if sending in segment mode.', 'newspack-newsletters' ) );
		}

		return $data;
	}

	/**
	 * On save.
	 *
	 * @param string   $post_id Numeric ID of the campaign.
	 * @param \WP_Post $post The complete post object.
	 * @param boolean  $update Whether this is an existing post being updated or not.
	 */
	public function save( $post_id, $post, $update ) {
		$this->retrieve( $post_id );
		return $post_id;
	}

	/**
	 * List not used in this ESP, because campaigns are generated on send.
	 *
	 * @param string $post_id Campaign Id.
	 * @param string $list_id ID of the list.
	 * @return object|WP_Error API Response or error.
	 */
	public function list( $post_id, $list_id ) {
		return null;
	}

	/**
	 * Sender not used in this ESP, because campaigns are generated on send.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @param string $from_name Sender name.
	 * @param string $reply_to Reply to email address.
	 * @return object|WP_Error API Response or error.
	 */
	public function sender( $post_id, $from_name, $reply_to ) {
		return null;
	}

	/**
	 * Sync not used in this ESP, because campaigns are generated on send.
	 *
	 * @param WP_POST $post Post to synchronize.
	 * @return object|null API Response or error.
	 */
	public function sync( $post ) {
		return null;
	}

	/**
	 * Trash not used in this ESP, because campaigns are generated on send.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 */
	public function trash( $post_id ) {
		return null;
	}

	/**
	 * Add contact to a list.
	 *
	 * @param array  $contact Contact data.
	 * @param strine $list_id List ID.
	 */
	public function add_contact( $contact, $list_id ) {
		try {
			$api_key   = $this->api_key();
			$client_id = $this->client_id();
			if ( $api_key && $client_id ) {
				$cm_subscribers   = new CS_REST_Subscribers( $list_id, [ 'api_key' => $api_key ] );
				$email_address    = $contact['email'];
				$found_subscriber = $cm_subscribers->get( $email_address, true );
				$update_payload   = [
					'EmailAddress'   => $email_address,
					'Name'           => $contact['name'],
					'CustomFields'   => [],
					'ConsentToTrack' => 'yes',
					'Resubscribe'    => true,
				];

				// Get custom fields (metadata) to create them if needed.
				$cm_list            = new CS_REST_Lists( $list_id, [ 'api_key' => $api_key ] );
				$custom_fields_keys = array_map(
					function( $field ) {
						return $field->FieldName; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					},
					$cm_list->get_custom_fields()->response
				);
				foreach ( $contact['metadata'] as $key => $value ) {
					$update_payload['CustomFields'][] = [
						'Key'   => $key,
						'Value' => (string) $value,
					];
					if ( ! in_array( $key, $custom_fields_keys ) ) {
						$cm_list->create_custom_field(
							[
								'FieldName' => $key,
								'DataType'  => CS_REST_CUSTOM_FIELD_TYPE_TEXT,
							]
						);
					}
				}

				if ( 200 === $found_subscriber->http_status_code ) {
					$result = $cm_subscribers->update( $email_address, $update_payload );
				} else {
					$result = $cm_subscribers->add( $update_payload );
				};
			}
		} catch ( \Exception $e ) {
			return new \WP_Error(
				'newspack_add_contact',
				$e->getMessage()
			);
		}
	}
}
