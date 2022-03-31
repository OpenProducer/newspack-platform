<?php
/**
 * Service Provider: Mailchimp Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use \DrewM\MailChimp\MailChimp;

/**
 * Main Newspack Newsletters Class.
 */
final class Newspack_Newsletters_Mailchimp extends \Newspack_Newsletters_Service_Provider {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->service    = 'mailchimp';
		$this->controller = new Newspack_Newsletters_Mailchimp_Controller( $this );

		add_action( 'save_post_' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT, [ $this, 'save' ], 10, 3 );
		add_action( 'transition_post_status', [ $this, 'send' ], 10, 3 );
		add_action( 'wp_trash_post', [ $this, 'trash' ], 10, 1 );
		add_filter( 'newspack_newsletters_process_link', [ $this, 'process_link' ], 10, 2 );

		parent::__construct( $this );
	}

	/**
	 * Get API credentials for service provider.
	 *
	 * @return Object Stored API credentials for the service provider.
	 */
	public function api_credentials() {
		return [
			// 'newspack_mailchimp_api_key' is a new option introduced to manage MC API key accross Newspack plugins.
			// Keeping the old option for backwards compatibility.
			'api_key' => get_option( 'newspack_mailchimp_api_key', get_option( 'newspack_newsletters_mailchimp_api_key', '' ) ),
		];
	}

	/**
	 * Check if provider has all necessary credentials set.
	 *
	 * @return Boolean Result.
	 */
	public function has_api_credentials() {
		return ! empty( $this->api_key() );
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
	 * Set the API credentials for the service provider.
	 *
	 * @param object $credentials API credentials.
	 */
	public function set_api_credentials( $credentials ) {
		$api_key = $credentials['api_key'];
		if ( empty( $api_key ) ) {
			return new WP_Error(
				'newspack_newsletters_invalid_keys',
				__( 'Please input a Mailchimp API key.', 'newspack-newsletters' )
			);
		}
		try {
			$mc   = new Mailchimp( $api_key );
			$ping = $mc->get( 'ping' );
		} catch ( Exception $e ) {
			$ping = null;
		}
		return $ping ?
			update_option( 'newspack_mailchimp_api_key', $api_key ) :
			new WP_Error(
				'newspack_newsletters_invalid_keys',
				__( 'Please input a valid Mailchimp API key.', 'newspack-newsletters' )
			);
	}

	/**
	 * Set list for a campaign.
	 *
	 * @param string $post_id Campaign Id.
	 * @param string $list_id ID of the list.
	 * @return object|WP_Error API API Response or error.
	 */
	public function list( $post_id, $list_id ) {
		$mc_campaign_id = get_post_meta( $post_id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}

		try {
			$mc      = new Mailchimp( $this->api_key() );
			$payload = [
				'recipients' => [
					'list_id' => $list_id,
				],
			];
			$result  = $this->validate(
				$mc->patch( "campaigns/$mc_campaign_id", $payload ),
				__( 'Error setting Mailchimp list.', 'newspack_newsletters' )
			);

			$data = $this->retrieve( $post_id );
			if ( is_wp_error( $data ) ) {
				return \rest_ensure_response( $data );
			}

			$data['result'] = $result;

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Retrieve a campaign.
	 *
	 * @param integer $post_id Numeric ID of the Newsletter post.
	 * @return object|WP_Error API Response or error.
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
				'newspack_newsletters_mailchimp_error',
				$persisted_error
			);
		}
		try {
			$mc_campaign_id = get_post_meta( $post_id, 'mc_campaign_id', true );
			if ( ! $mc_campaign_id ) {
				$this->sync( get_post( $post_id ) );
			}
			$mc                  = new Mailchimp( $this->api_key() );
			$campaign            = $this->validate(
				$mc->get( "campaigns/$mc_campaign_id" ),
				__( 'Error retrieving Mailchimp campaign.', 'newspack_newsletters' )
			);
			$list_id             = $campaign && isset( $campaign['recipients']['list_id'] ) ? $campaign['recipients']['list_id'] : null;
			$merge_fields        = $list_id ? $this->get_list_merge_fields( $list_id ) : [];
			$interest_categories = $list_id ? $this->validate(
				$mc->get( "lists/$list_id/interest-categories" ),
				__( 'Error retrieving Mailchimp groups.', 'newspack_newsletters' )
			) : null;
			if ( $interest_categories && count( $interest_categories['categories'] ) ) {
				foreach ( $interest_categories['categories'] as &$category ) {
					$category_id           = $category['id'];
					$category['interests'] = $this->validate(
						$mc->get( "lists/$list_id/interest-categories/$category_id/interests" ),
						__( 'Error retrieving Mailchimp groups.', 'newspack_newsletters' )
					);
				}
			}

			$segments = [];
			if ( $list_id ) {
				$saved_segments_response  = $this->validate(
					$mc->get(
						"lists/$list_id/segments",
						[
							'type'  => 'saved',
							'count' => 1000,
						],
						60
					),
					__( 'Error retrieving Mailchimp segments.', 'newspack_newsletters' )
				);
				$static_segments_response = $this->validate(
					$mc->get(
						"lists/$list_id/segments",
						[
							'type'  => 'static',
							'count' => 1000,
						],
						60
					),
					__( 'Error retrieving Mailchimp segments.', 'newspack_newsletters' )
				);
				$segments                 = array_merge( $saved_segments_response['segments'], $static_segments_response['segments'] );
			}

			return [
				'lists'               => $this->get_lists(),
				'merge_fields'        => $merge_fields,
				'campaign'            => $campaign,
				'campaign_id'         => $mc_campaign_id,
				'interest_categories' => $interest_categories,
				'segments'            => $segments,
			];
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Get lists.
	 */
	public function get_lists() {
		try {
			$mc             = new Mailchimp( $this->api_key() );
			$lists_response = $this->validate(
				$mc->get(
					'lists',
					[
						'count' => 1000,
					]
				),
				__( 'Error retrieving Mailchimp lists.', 'newspack_newsletters' )
			);
			if ( is_wp_error( $lists_response ) ) {
				return new WP_Error(
					'newspack_newsletters_mailchimp_error',
					$lists_response->getMessage()
				);
			}
			return $lists_response['lists'];
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Retrieve the list merge fields.
	 *
	 * @param string $list_id List ID.
	 *
	 * @return array|WP_Error List of merge fields or error.
	 */
	public function get_list_merge_fields( $list_id ) {
		try {
			$mc       = new Mailchimp( $this->api_key() );
			$response = $this->validate(
				$mc->get(
					"lists/$list_id/merge-fields",
					[
						'count' => 1000,
					]
				),
				__( 'Error retrieving Mailchimp list merge fields.', 'newspack_newsletters' )
			);
			return $response['merge_fields'];
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Set sender data.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @param string $from_name Sender name.
	 * @param string $reply_to Reply to email address.
	 * @return object|WP_Error API Response or error.
	 */
	public function sender( $post_id, $from_name, $reply_to ) {
		$mc_campaign_id = get_post_meta( $post_id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}
		try {
			$mc = new Mailchimp( $this->api_key() );

			$result = $this->validate(
				$mc->get( 'verified-domains' ),
				__( 'Error retrieving verified domains from Mailchimp.', 'newspack-newsletters' )
			);

			$verified_domains = array_filter(
				array_map(
					function( $domain ) {
						return $domain['verified'] ? strtolower( trim( $domain['domain'] ) ) : null;
					},
					$result['domains']
				),
				function( $domain ) {
					return ! empty( $domain );
				}
			);

			$explode = explode( '@', $reply_to );
			$domain  = strtolower( trim( array_pop( $explode ) ) );

			if ( ! in_array( $domain, $verified_domains ) ) {
				return new WP_Error(
					'newspack_newsletters_unverified_sender_domain',
					sprintf(
					// Translators: explanation that current domain is not verified, list of verified options.
						__( '%1$s is not a verified domain. Verified domains for the linked Mailchimp account are: %2$s.', 'newspack-newsletters' ),
						$domain,
						implode( ', ', $verified_domains )
					)
				);
			}

			$settings = [];
			if ( $from_name ) {
				$settings['from_name'] = $from_name;
			}
			if ( $reply_to ) {
				$settings['reply_to'] = $reply_to;
			}
			$payload = [
				'settings' => $settings,
			];
			$result  = $this->validate(
				$mc->patch( "campaigns/$mc_campaign_id", $payload ),
				__( 'Error setting sender name and email.', 'newspack_newsletters' )
			);

			$data           = $this->retrieve( $post_id );
			$data['result'] = $result;

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
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
		$mc_campaign_id = get_post_meta( $post_id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}
		try {

			$sync_result = $this->sync( get_post( $post_id ) );
			if ( ! $sync_result ) {
				return new WP_Error(
					'newspack_newsletters_mailchimp_error',
					__( 'Unable to synchronize with Mailchimp.', 'newspack-newsletters' )
				);
			}
			if ( is_wp_error( $sync_result ) ) {
				return $sync_result;
			}

			$mc      = new Mailchimp( $this->api_key() );
			$payload = [
				'test_emails' => $emails,
				'send_type'   => 'html',
			];
			$result  = $this->validate(
				$mc->post(
					"campaigns/$mc_campaign_id/actions/test",
					$payload,
					60
				),
				__( 'Error sending test email.', 'newspack_newsletters' )
			);

			$data            = $this->retrieve( $post_id );
			$data['result']  = $result;
			$data['message'] = sprintf(
			// translators: Message after successful test email.
				__( 'Mailchimp test sent successfully to %s.', 'newspack-newsletters' ),
				implode( ', ', $emails )
			);

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Synchronize post with corresponding ESP campaign.
	 *
	 * @param WP_POST $post Post to synchronize.
	 * @return object|null API Response or error.
	 */
	public function sync( $post ) {
		$api_key = $this->api_key();
		if ( ! $api_key ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'No Mailchimp API key available.', 'newspack-newsletters' )
			);
		}
		try {
			$mc      = new Mailchimp( $api_key );
			$payload = [
				'type'         => 'regular',
				'content_type' => 'template',
				'settings'     => [
					'subject_line' => $post->post_title,
					'title'        => $post->post_title,
				],
			];

			$mc_campaign_id = get_post_meta( $post->ID, 'mc_campaign_id', true );

			/**
			 * Filter the metadata payload sent to Mailchimp when syncing.
			 *
			 * Allows custom tracking codes to be sent.
			 *
			 * @param array  $payload        Mailchimp payload.
			 * @param object $post           Post object.
			 * @param string $mc_campaign_id Mailchimp campaign ID, if defined.
			 */
			$payload = apply_filters( 'newspack_newsletters_mc_payload_sync', $payload, $post, $mc_campaign_id );

			if ( $mc_campaign_id ) {
				$campaign_result = $this->validate(
					$mc->patch( "campaigns/$mc_campaign_id", $payload ),
					__( 'Error updating campaign title.', 'newspack_newsletters' )
				);
			} else {
				$campaign_result = $this->validate(
					$mc->post( 'campaigns', $payload ),
					__( 'Error setting campaign title.', 'newspack_newsletters' )
				);
				$mc_campaign_id  = $campaign_result['id'];
				update_post_meta( $post->ID, 'mc_campaign_id', $mc_campaign_id );
			}

			// Prevent updating content of a sent campaign.
			if ( in_array( $campaign_result['status'], [ 'sent', 'sending' ] ) ) {
				return;
			}

			$renderer        = new Newspack_Newsletters_Renderer();
			$content_payload = [
				'html' => $renderer->retrieve_email_html( $post ),
			];

			$content_result = $this->validate(
				$mc->put( "campaigns/$mc_campaign_id/content", $content_payload ),
				__( 'Error updating campaign content.', 'newspack_newsletters' )
			);
			return [
				'campaign_result' => $campaign_result,
				'content_result'  => $content_result,
			];
		} catch ( Exception $e ) {
			$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
			set_transient( $transient, $e->getMessage(), 45 );
			return;
		}
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
	 * @param string  $new_status New status of the post.
	 * @param string  $old_status Old status of the post.
	 * @param WP_Post $post       Post to send.
	 *
	 * @throws Exception Error message if sending fails.
	 */
	public function send( $new_status, $old_status, $post ) {
		$post_id = $post->ID;

		// Only run if the current service provider is Mailchimp.
		if ( 'mailchimp' !== get_option( 'newspack_newsletters_service_provider', false ) ) {
			return;
		}

		if ( ! Newspack_Newsletters::validate_newsletter_id( $post_id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}

		if ( 'publish' === $new_status && 'publish' !== $old_status ) {
			try {
				// Check if campaign has already been sent and if so, don't attempt to
				// send again.
				$campaign_data = $this->retrieve( $post_id );
				if (
					isset( $campaign_data['campaign']['status'] ) &&
					in_array( $campaign_data['campaign']['status'], [ 'sent', 'sending' ], true )
				) {
					return;
				}

				$sync_result = $this->sync( $post );

				if ( ! $sync_result || is_wp_error( $sync_result ) ) {
					throw new Exception(
						__( 'Unable to synchronize with Mailchimp.', 'newspack-newsletters' )
					);
				}

				$mc_campaign_id = get_post_meta( $post_id, 'mc_campaign_id', true );
				if ( ! $mc_campaign_id ) {
					throw new Exception(
						__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
					);
				}

				$mc = new Mailchimp( $this->api_key() );

				$payload = [
					'send_type' => 'html',
				];
				$this->validate(
					$mc->post( "campaigns/$mc_campaign_id/actions/send", $payload ),
					__( 'Error sending campaign.', 'newspack_newsletters' )
				);
			} catch ( Exception $e ) {
				$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
				set_transient( $transient, $e->getMessage(), 45 );
				// Reset publish status.
				wp_update_post(
					[
						'ID'          => $post_id,
						'post_status' => 'draft',
					],
					true
				);
				wp_die( esc_html( $e->getMessage() ) );
			}
		}
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
		$mc_campaign_id = get_post_meta( $post_id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return;
		}

		$api_key = $this->api_key();
		if ( ! $api_key ) {
			return;
		}
		try {
			$mc       = new Mailchimp( $api_key );
			$campaign = $mc->get( "campaigns/$mc_campaign_id" );
			if ( $campaign ) {
				$status = $campaign['status'];
				if ( ! in_array( $status, [ 'sent', 'sending' ] ) ) {
					$result = $mc->delete( "campaigns/$mc_campaign_id" );
					delete_post_meta( $post_id, 'mc_campaign_id', $mc_campaign_id );
				}
			}
		} catch ( Exception $e ) {
			return; // Fail silently.
		}
	}

	/**
	 * Set Mailchimp Audience segments for a Campaign.
	 *
	 * @param string     $post_id   Numeric ID of the post.
	 * @param string|int $target_id Segment/tag ID or compound interest ID (field name and ID).
	 *
	 * @return object|WP_Error API API Response or error.
	 */
	public function audience_segments( $post_id, $target_id ) {

		$interest_id = false;
		$segment_id  = false;

		// Determine if we're dealing with an interest or a segment.
		if ( false !== strpos( $target_id, ':' ) ) {
			$exploded    = explode( ':', $target_id );
			$field       = count( $exploded ) ? $exploded[0] : null;
			$interest_id = count( $exploded ) > 1 ? $exploded[1] : null;
		} elseif ( '' !== $target_id ) {
			$segment_id = $target_id;
		}

		$mc_campaign_id = get_post_meta( $post_id, 'mc_campaign_id', true );
		if ( ! $mc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_no_campaign_id',
				__( 'Mailchimp campaign ID not found.', 'newspack-newsletters' )
			);
		}

		if ( '' !== $target_id && ! $interest_id && ! $segment_id ) {
			return new WP_Error(
				'newspack_newsletters_invalid_mailchimp_interest',
				__( 'Invalid Mailchimp Interest.', 'newspack-newsletters' )
			);
		}

		try {
			$mc       = new Mailchimp( $this->api_key() );
			$campaign = $this->validate(
				$mc->get( "campaigns/$mc_campaign_id" ),
				__( 'Error retrieving Mailchimp campaign.', 'newspack_newsletters' )
			);
			$list_id  = isset( $campaign, $campaign['recipients'], $campaign['recipients']['list_id'] ) ? $campaign['recipients']['list_id'] : null;

			if ( ! $list_id ) {
				return new WP_Error(
					'newspack_newsletters_no_campaign_id',
					__( 'Mailchimp list ID not found.', 'newspack-newsletters' )
				);
			}

			$segment_opts = (object) [];

			if ( $interest_id ) {
				$segment_opts = [
					'match'      => 'all',
					'conditions' => [
						[
							'condition_type' => 'Interests',
							'field'          => $field,
							'op'             => 'interestcontains',
							'value'          => [ $interest_id ],
						],
					],
				];
			} elseif ( $segment_id ) {
				$segment_data = $mc->get( "lists/$list_id/segments/$segment_id" );
				if ( 'static' === $segment_data['type'] ) {
					// Handle static segments (tags).
					$segment_opts = [
						'match'      => 'all',
						'conditions' => [
							[
								'condition_type' => 'StaticSegment',
								'field'          => 'static_segment',
								'op'             => 'static_is',
								'value'          => $segment_id,
							],
						],
					];
				} elseif ( 'saved' === $segment_data['type'] ) {
					// Handle saved segments.
					$segment_opts = $segment_data['options'];
				}
			}

			$payload = [
				'recipients' => [
					'list_id'      => $list_id,
					'segment_opts' => $segment_opts,
				],
			];

			// Add saved segment ID to payload if present.
			if ( $segment_data && 'saved' === $segment_data['type'] ) {
				$payload['recipients']['segment_opts']['saved_segment_id'] = (int) $segment_id;
			}

			$result = $this->validate(
				$mc->patch( "campaigns/$mc_campaign_id", $payload ),
				__( 'Error updating Mailchimp segments.', 'newspack_newsletters' )
			);

			$data           = $this->retrieve( $post_id );
			$data['result'] = $result;

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_mailchimp_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Throw an Exception if Mailchimp response indicates an error.
	 *
	 * @param Object $result Result of the Mailchimp operation.
	 * @param String $preferred_error Preset error to use instead of Mailchimp errors.
	 * @throws Exception Error message.
	 * @return The results of the API call.
	 */
	public function validate( $result, $preferred_error = null ) {
		if ( ! $result ) {
			if ( $preferred_error ) {
				throw new Exception( $preferred_error );
			} else {
				throw new Exception( __( 'A Mailchimp error has occurred.', 'newspack-newsletters' ) );
			}
		}
		if ( ! empty( $result['status'] ) && in_array( $result['status'], [ 400, 404 ] ) ) {
			if ( $preferred_error && ! Newspack_Newsletters::debug_mode() ) {
				if ( ! empty( $result['detail'] ) ) {
					$preferred_error .= ' ' . $result['detail'];
				}
				throw new Exception( $preferred_error );
			}
			$messages = [];
			if ( ! empty( $result['errors'] ) ) {
				foreach ( $result['errors'] as $error ) {
					if ( ! empty( $error['message'] ) ) {
						$messages[] = $error['message'];
					}
				}
			}
			if ( ! count( $messages ) && ! empty( $result['detail'] ) ) {
				$messages[] = $result['detail'];
			}
			if ( ! count( $messages ) ) {
				$message[] = __( 'A Mailchimp error has occurred.', 'newspack-newsletters' );
			}
			throw new Exception( implode( ' ', $messages ) );
		}
		return $result;
	}

	/**
	 * Special handling for link hrefs containing Mailchimp merge fields.
	 *
	 * @param String $processed The processed link, with utm_medium parameter added.
	 * @param String $original The original, unprocessed link.
	 * @return The link to use.
	 */
	public function process_link( $processed, $original ) {
		// Match Mailchimp Merge Fields.
		if ( preg_match( '/\*\|([A-Z_0-9:]+)\|\*/', $original ) ) {
			// Check if http:// was prepended.
			if ( 0 === strpos( $original, 'http://*|' ) ) {
				$original = substr( $original, 7 );
			}
			return $original;
		}
		return $processed;
	}

	/**
	 * Add contact to a list.
	 *
	 * @param array  $contact Contact data.
	 * @param strine $list_id List ID.
	 */
	public function add_contact( $contact, $list_id ) {
		try {
			$mc             = new Mailchimp( $this->api_key() );
			$email_address  = $contact['email'];
			$name_fragments = explode( ' ', $contact['name'], 2 );
			$merge_fields   = [
				'FNAME' => $name_fragments[0],
			];
			if ( isset( $name_fragments[1] ) ) {
				$merge_fields['LNAME'] = $name_fragments[1];
			}
			$update_payload = [
				'email_address' => $email_address,
				'merge_fields'  => $merge_fields,
				'status'        => 'subscribed',
			];

			// Get list merge fields (metadata) to create them if needed.
			$list_merge_fields = array_reduce(
				$mc->get( "lists/$list_id/merge-fields" )['merge_fields'],
				function( $acc, $field ) {
					$acc[ $field['name'] ] = $field['tag'];
					return $acc;
				},
				[]
			);
			foreach ( $contact['metadata'] as $key => $value ) {
				if ( isset( $list_merge_fields[ $key ] ) ) {
					$update_payload['merge_fields'][ $list_merge_fields[ $key ] ] = (string) $value;
				} else {
					$created_merge_field = $mc->post(
						"lists/$list_id/merge-fields",
						[
							'name' => $key,
							'type' => 'text',
						]
					);
					$update_payload['merge_fields'][ $created_merge_field['tag'] ] = (string) $value;
				}
			}

			// Create or update a list member.
			$found_subscribers = $mc->get(
				'search-members',
				[
					'list_id' => $list_id,
					'query'   => $email_address,
				]
			)['exact_matches']['members'];
			if ( empty( $found_subscribers ) ) {
				$mc->post( "lists/$list_id/members", $update_payload );
			} else {
				$member_id = $found_subscribers[0]['id'];
				$mc->patch( "lists/$list_id/members/$member_id", $update_payload );
			}
		} catch ( \Exception $e ) {
			return new \WP_Error(
				'newspack_add_contact',
				$e->getMessage()
			);
		}
	}
}
