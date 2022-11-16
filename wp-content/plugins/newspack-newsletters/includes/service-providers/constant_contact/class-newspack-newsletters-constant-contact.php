<?php
/**
 * Service Provider: Constant Contact Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Newsletters Class for Constant Contact ESP.
 */
final class Newspack_Newsletters_Constant_Contact extends \Newspack_Newsletters_Service_Provider {

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
	 * Class constructor.
	 */
	public function __construct() {
		$this->service    = 'constant_contact';
		$this->controller = new Newspack_Newsletters_Constant_Contact_Controller( $this );

		add_action( 'admin_init', [ $this, 'oauth_callback' ] );
		add_action( 'update_option_newspack_newsletters_constant_contact_api_key', [ $this, 'clear_tokens' ], 10, 2 );
		add_action( 'update_option_newspack_newsletters_constant_contact_api_secret', [ $this, 'clear_tokens' ], 10, 2 );
		add_action( 'save_post_' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT, [ $this, 'save' ], 10, 3 );
		add_action( 'wp_trash_post', [ $this, 'trash' ], 10, 1 );

		parent::__construct( $this );
	}

	/**
	 * Get API credentials for service provider.
	 *
	 * @return Object Stored API credentials for the service provider.
	 */
	public function api_credentials() {
		return [
			'api_key'       => get_option( 'newspack_newsletters_constant_contact_api_key', '' ),
			'api_secret'    => get_option( 'newspack_newsletters_constant_contact_api_secret', '' ),
			'access_token'  => get_option( 'newspack_newsletters_constant_contact_api_access_token', '' ),
			'refresh_token' => get_option( 'newspack_newsletters_constant_contact_api_refresh_token', '' ),
		];
	}

	/**
	 * Check if provider has all necessary credentials set.
	 *
	 * @return Boolean Result.
	 */
	public function has_api_credentials() {
		return ! empty( $this->api_key() ) && ! empty( $this->api_secret() );
	}

	/**
	 * Verify service provider connection.
	 *
	 * @param boolean $refresh Whether to attempt connection refresh.
	 *
	 * @return array
	 */
	public function verify_token( $refresh = true ) {
		$credentials  = $this->api_credentials();
		$redirect_uri = $this->get_oauth_redirect_uri();
		$cc           = new Newspack_Newsletters_Constant_Contact_SDK(
			$credentials['api_key'],
			$credentials['api_secret'],
			$credentials['access_token']
		);

		$response = [
			'error'    => null,
			'valid'    => false,
			'auth_url' => $cc->get_auth_code_url( wp_create_nonce( 'constant_contact_oauth2' ), $redirect_uri ),
		];

		try {
			// If we have a valid access token, we're connected.
			if ( $cc->validate_token() ) {
				$response['valid'] = true;
				return $response;
			}
			// If we have a refresh token, we can get a new access token.
			if ( $refresh && ! empty( $credentials['refresh_token'] ) ) {
				$token             = $cc->refresh_token( $credentials['refresh_token'] );
				$response['valid'] = $this->set_access_token( $token->access_token, $token->refresh_token );
				return $response;
			}
			return $response;
		} catch ( Exception $e ) {
			return $response;
		}
	}

	/**
	 * Check if is connected to service provider.
	 *
	 * @return Boolean
	 */
	public function has_valid_connection() {
		return $this->verify_token( false )['valid'];
	}

	/**
	 * Get OAuth Redirect URI.
	 *
	 * @return string OAuth Redirect URI.
	 */
	private function get_oauth_redirect_uri() {
		return add_query_arg(
			'cc_oauth2',
			1,
			admin_url( 'index.php' )
		);
	}

	/**
	 * Authorization code callback
	 */
	public function oauth_callback() {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}
		if ( ! isset( $_GET['cc_oauth2'] ) ) {
			return;
		}
		if (
			! isset( $_GET['state'] ) ||
			! wp_verify_nonce( sanitize_text_field( $_GET['state'] ), 'constant_contact_oauth2' ) ||
			! isset( $_GET['code'] )
		) {
			return;
		}

		if ( isset( $_GET['error_description'] ) ) {
			wp_die( esc_html( sanitize_text_field( $_GET['error_description'] ) ) );
		}

		$redirect_uri = $this->get_oauth_redirect_uri();
		$code         = sanitize_text_field( $_GET['code'] );

		$this->connect( $redirect_uri, $code );
		?>
		<script type="text/javascript">
			window.close();
			if(window.opener && window.opener.verify) {
				window.opener.verify();
			}
		</script>
		<?php
		wp_die();
	}

	/**
	 * Connect using authorization code.
	 *
	 * @param string $redirect_uri Redirect URI.
	 * @param string $code         Authorization code.
	 *
	 * @return Boolean Whether we are connected.
	 */
	private function connect( $redirect_uri, $code ) {
		$cc    = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret() );
		$token = $cc->get_access_token( $redirect_uri, $code );
		return $this->set_access_token(
			$token->access_token,
			isset( $token->refresh_token ) ? $token->refresh_token : ''
		);
	}

	/**
	 * Get API key for service provider.
	 *
	 * @return String Stored API key for the service provider.
	 */
	public function api_key() {
		$credentials = $this->api_credentials();
		return $credentials['api_key'];
	}

	/**
	 * Get API secret for service provider.
	 *
	 * @return String Stored API secret for the service provider.
	 */
	public function api_secret() {
		$credentials = $this->api_credentials();
		return $credentials['api_secret'];
	}

	/**
	 * Get Access Token key for service provider.
	 *
	 * @return String Stored Access Token key for the service provider.
	 */
	public function access_token() {
		$credentials = $this->api_credentials();
		return $credentials['access_token'];
	}

	/**
	 * Set the API credentials for the service provider.
	 *
	 * @param object $credentials API credentials.
	 *
	 * @return Boolean Whether any API credential was updated.
	 */
	public function set_api_credentials( $credentials ) {
		if ( empty( $credentials['api_key'] ) || empty( $credentials['api_secret'] ) ) {
			return new WP_Error(
				'newspack_newsletters_invalid_keys',
				__( 'Please input Constant Contact API key and secret.', 'newspack-newsletters' )
			);
		} else {
			$update_api_key    = update_option( 'newspack_newsletters_constant_contact_api_key', $credentials['api_key'] );
			$update_api_secret = update_option( 'newspack_newsletters_constant_contact_api_secret', $credentials['api_secret'] );
			return $update_api_key || $update_api_secret;
		}
	}

	/**
	 * Clear API tokens when API key or secret changes.
	 *
	 * @param string $old_api_value Old API value.
	 * @param string $new_api_value New API value.
	 */
	public function clear_tokens( $old_api_value, $new_api_value ) {
		if ( $old_api_value !== $new_api_value ) {
			delete_option( 'newspack_newsletters_constant_contact_api_access_token' );
			delete_option( 'newspack_newsletters_constant_contact_api_refresh_token	' );
		}
	}

	/**
	 * Set acccess and refresh tokens.
	 *
	 * @param string $access_token  Access token.
	 * @param string $refresh_token Refresh token.
	 *
	 * @return Boolean Whether values were updated.
	 *
	 * @throws Exception Error message.
	 */
	private function set_access_token( $access_token = '', $refresh_token = '' ) {
		if ( empty( $access_token ) ) {
			throw new Exception(
				__( 'Access token is required.', 'newspack-newsletter' )
			);
		}
		$update_access_token  = update_option( 'newspack_newsletters_constant_contact_api_access_token', $access_token );
		$update_refresh_token = update_option( 'newspack_newsletters_constant_contact_api_refresh_token', $refresh_token );
		return $update_access_token;
	}

	/**
	 * Get campaign name.
	 *
	 * @param WP_Post $post Post object.
	 * @return String Campaign name.
	 */
	private function get_campaign_name( $post ) {
		return 'Newspack Newsletter #' . $post->ID;
	}

	/**
	 * Set list for a campaign.
	 *
	 * @param string $post_id Campaign Id.
	 * @param string $list_id ID of the list.
	 * @return object|WP_Error API API Response or error.
	 */
	public function list( $post_id, $list_id ) {
		if ( ! $this->has_valid_connection() ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
				__( 'Unable to connect to Constant Contact API', 'newspack-newsletters' )
			);
		}
		try {
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );

			$campaign = $cc->get_campaign( $cc_campaign_id );
			$activity = $campaign->activity;

			if ( ! in_array( $list_id, $activity->contact_list_ids, true ) ) {
				$activity->contact_list_ids[] = $list_id;
			}

			$cc->update_campaign_activity( $activity->campaign_activity_id, $activity );

			return \rest_ensure_response( $this->retrieve( $post_id ) );

		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Unset list for a campaign.
	 *
	 * @param string $post_id Campaign Id.
	 * @param string $list_id ID of the list.
	 * @return object|WP_Error API API Response or error.
	 */
	public function unset_list( $post_id, $list_id ) {
		if ( ! $this->has_valid_connection() ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
				__( 'Unable to connect to Constant Contact API', 'newspack-newsletters' )
			);
		}
		try {
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );

			$campaign = $cc->get_campaign( $cc_campaign_id );
			$activity = $campaign->activity;

			$activity->contact_list_ids = array_diff( $activity->contact_list_ids, [ $list_id ] );

			$cc->update_campaign_activity( $activity->campaign_activity_id, $activity );

			return \rest_ensure_response( $this->retrieve( $post_id ) );

		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
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
		if ( ! $this->has_api_credentials() || ! $this->has_valid_connection() ) {
			return [];
		}
		$transient       = sprintf( 'newspack_newsletters_error_%s_%s', $post_id, get_current_user_id() );
		$persisted_error = get_transient( $transient );
		if ( $persisted_error ) {
			delete_transient( $transient );
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
				$persisted_error
			);
		}
		try {
			$cc             = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );
			$cc_campaign_id = get_post_meta( $post_id, 'cc_campaign_id', true );

			if ( ! $cc_campaign_id ) {
				$campaign       = $this->sync( get_post( $post_id ) );
				$cc_campaign_id = $campaign->campaign_id;
			} else {
				$campaign = $cc->get_campaign( $cc_campaign_id );
			}

			$lists = $cc->get_contact_lists();

			return [
				'lists'       => $lists,
				'campaign'    => $campaign,
				'campaign_id' => $cc_campaign_id,
			];

		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
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
		if ( ! $this->has_valid_connection() ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
				__( 'Unable to connect to Constant Contact API', 'newspack-newsletters' )
			);
		}
		try {
			$post           = get_post( $post_id );
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );
			$renderer       = new Newspack_Newsletters_Renderer();
			$content        = $renderer->retrieve_email_html( $post );

			$campaign = $cc->get_campaign( $cc_campaign_id );

			$activity = [
				'format_type'      => 5,
				'email_content'    => $content,
				'subject'          => $post->post_title,
				'contact_list_ids' => $campaign->activity->contact_list_ids,
				'from_name'        => $from_name,
				'from_email'       => $reply_to,
				'reply_to_email'   => $reply_to,
			];

			$cc->update_campaign_activity( $campaign->activity->campaign_activity_id, $activity );

			return \rest_ensure_response( $this->retrieve( $post_id ) );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
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
		if ( ! $this->has_valid_connection() ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
				__( 'Unable to connect to Constant Contact API', 'newspack-newsletters' )
			);
		}
		try {
			$cc = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );

			$data = $this->retrieve( $post_id );

			if ( is_wp_error( $data ) ) {
				return $data;
			}

			$activity = $data['campaign']->activity;
			$result   = $cc->test_campaign( $activity->campaign_activity_id, $emails );

			$data['result']  = $result;
			$data['message'] = sprintf(
			// translators: Message after successful test email.
				__( 'Constant Contact test sent successfully to %s.', 'newspack-newsletters' ),
				implode( ', ', $emails )
			);

			return \rest_ensure_response( $data );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_constant_contact_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Synchronize post with corresponding ESP campaign.
	 *
	 * @param WP_Post $post Post to synchronize.
	 *
	 * @return object|WP_Error API Response or error.
	 *
	 * @throws Exception Error message.
	 */
	public function sync( $post ) {
		try {
			$api_key = $this->api_key();
			if ( ! $api_key ) {
				throw new Exception(
					__( 'No Constant Contact API key available.', 'newspack-newsletters' )
				);
			}
			if ( empty( $post->post_title ) ) {
				throw new Exception(
					__( 'The newsletter subject cannot be empty.', 'newspack-newsletters' )
				);
			}

			if ( ! $this->has_valid_connection() ) {
				return;
			}

			$cc              = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );
			$cc_campaign_id  = get_post_meta( $post->ID, 'cc_campaign_id', true );
			$renderer        = new Newspack_Newsletters_Renderer();
			$content         = $renderer->retrieve_email_html( $post );
			$auto_draft_html = '<html><body>[[trackingImage]]<p>Auto draft</p></body></html>';
			$account_info    = $cc->get_account_info();

			$activity_data = [
				'format_type'  => 5, // https://v3.developer.constantcontact.com/api_guide/email_campaigns_overview.html#collapse-format-types .
				'html_content' => empty( $content ) ? $auto_draft_html : $content,
				'subject'      => $post->post_title,
			];

			if ( $account_info->physical_address ) {
				$activity_data['physical_address_in_footer'] = $account_info->physical_address;
			}

			if ( $cc_campaign_id ) {
				$campaign = $cc->get_campaign( $cc_campaign_id );

				// Constant Constact only allow updates on DRAFT or SENT status.
				if ( ! in_array( $campaign->current_status, [ 'DRAFT', 'SENT' ], true ) ) {
					return;
				}

				$activity = array_merge(
					$activity_data,
					[
						'contact_list_ids' => $campaign->activity->contact_list_ids,
						'from_name'        => $campaign->activity->from_name,
						'from_email'       => $campaign->activity->from_email,
						'reply_to_email'   => $campaign->activity->reply_to_email,
					]
				);

				$cc->update_campaign_activity( $campaign->activity->campaign_activity_id, $activity );

				$campaign_result = $cc->get_campaign( $cc_campaign_id );
			} else {

				$initial_sender = __( 'Sender Name', 'newspack-newsletters' );
				if ( $account_info->organization_name ) {
					$initial_sender = $account_info->organization_name;
				} elseif ( $account_info->first_name && $account_info->last_name ) {
					$initial_sender = $account_info->first_name . ' ' . $account_info->last_name;
				}

				$email_addresses          = (array) $cc->get_email_addresses();
				$verified_email_addresses = array_values(
					array_filter(
						$email_addresses,
						function ( $email ) {
							return 'CONFIRMED' === $email->confirm_status;
						}
					)
				);

				if ( empty( $verified_email_addresses ) ) {
					throw new Exception( __( 'There are no verified email addresses in the Constant Contact account.', 'newspack-newsletters' ) );
				}

				$initial_email_address = $verified_email_addresses[0]->email_address;

				$campaign = [
					'name'                      => $this->get_campaign_name( $post ),
					'email_campaign_activities' => [
						array_merge(
							$activity_data,
							[
								'subject'        => $post->post_title,
								'from_name'      => $initial_sender,
								'from_email'     => $initial_email_address,
								'reply_to_email' => $initial_email_address,
							]
						),
					],
				];

				$campaign_result = $cc->create_campaign( $campaign );
			}
			update_post_meta( $post->ID, 'cc_campaign_id', $campaign_result->campaign_id );
			return $campaign_result;

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
	 * @param WP_Post $post Post to send.
	 *
	 * @return true|WP_Error True if the campaign was sent or error if failed.
	 */
	public function send( $post ) {
		$post_id = $post->ID;

		try {
			$sync_result = $this->sync( $post );
		} catch ( Exception $e ) {
			return new WP_Error( 'newspack_newsletters_error', $e->getMessage() );
		}

		if ( ! $sync_result ) {
			return new WP_Error(
				'newspack_newsletters_error',
				__( 'Unable to synchronize with Constant Contact.', 'newspack-newsletters' )
			);
		}

		$cc_campaign_id = get_post_meta( $post_id, 'cc_campaign_id', true );
		if ( ! $cc_campaign_id ) {
			return new WP_Error(
				'newspack_newsletters_error',
				__( 'Constant Contact campaign ID not found.', 'newspack-newsletters' )
			);
		}

		try {
			$cc = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );
			$cc->create_schedule( $sync_result->activity->campaign_activity_id );
		} catch ( Exception $e ) {
			return new WP_Error(
				'newspack_newsletters_error',
				$e->getMessage()
			);
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
		$cc_campaign_id = get_post_meta( $post_id, 'cc_campaign_id', true );
		if ( ! $cc_campaign_id ) {
			return;
		}

		$api_key = $this->api_key();
		if ( ! $api_key ) {
			return;
		}

		try {
			if ( ! $this->verify_token() ) {
				return;
			}

			$cc = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );

			$campaign = $cc->get_campaign( $cc_campaign_id );
			if ( $campaign && 'DRAFT' === $campaign->current_status ) {
				$result = $cc->delete_campaign( $cc_campaign_id );
				delete_post_meta( $post_id, 'cc_campaign_id', $cc_campaign_id );
			}
		} catch ( Exception $e ) {
			return; // Fail silently.
		}
	}

	/**
	 * Convenience method to retrieve the Constant Contact campaign ID for a post or throw an error.
	 *
	 * @param string $post_id Numeric ID of the campaign.
	 * @return string Constant Contact campaign ID.
	 * @throws Exception Error message.
	 */
	public function retrieve_campaign_id( $post_id ) {
		$cc_campaign_id = get_post_meta( $post_id, 'cc_campaign_id', true );
		if ( ! $cc_campaign_id ) {
			throw new Exception( __( 'Constant Contact campaign ID not found.', 'newspack-newsletters' ) );
		}
		return $cc_campaign_id;
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
		try {
			$cc          = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );
			$this->lists = $cc->get_contact_lists();
		} catch ( Exception $e ) {
			return new WP_Error( 'newspack_newsletters_error', $e->getMessage() );
		}
		return array_map(
			function( $list ) {
				return [
					'id'   => $list->list_id,
					'name' => $list->name,
				];
			},
			$this->lists
		);
	}

	/**
	 * Add contact to a list or update an existing contact.
	 *
	 * @param array        $contact      {
	 *      Contact data.
	 *
	 *    @type string   $email    Contact email address.
	 *    @type string   $name     Contact name. Optional.
	 *    @type string[] $metadata Contact additional metadata. Optional.
	 * }
	 * @param string|false $list_id      List to add the contact to.
	 *
	 * @return array|WP_Error Contact data if the contact was added or error if failed.
	 */
	public function add_contact( $contact, $list_id = false ) {
		$cc   = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );
		$data = [];
		if ( $list_id ) {
			$data['list_ids'] = [ $list_id ];
		}
		if ( isset( $contact['name'] ) ) {
			$name_fragments     = explode( ' ', $contact['name'], 2 );
			$data['first_name'] = $name_fragments[0];
			if ( isset( $name_fragments[1] ) ) {
				$data['last_name'] = $name_fragments[1];
			}
		}
		if ( isset( $contact['metadata'] ) ) {
			$data['custom_fields'] = [];
			foreach ( $contact['metadata'] as $key => $value ) {
				$data['custom_fields'][ strval( $key ) ] = strval( $value );
			}
		}
		return get_object_vars( $cc->upsert_contact( $contact['email'], $data ) );
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
		$cc      = new Newspack_Newsletters_Constant_Contact_SDK( $this->api_key(), $this->api_secret(), $this->access_token() );
		$contact = $cc->get_contact( $email );
		if ( ! $contact ) {
			return new WP_Error(
				'newspack_newsletters_error',
				__( 'Contact not found.', 'newspack-newsletters' )
			);
		}
		return get_object_vars( $contact );
	}

}
