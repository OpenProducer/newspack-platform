<?php
/**
 * Service Provider: Constant Contact Implementation
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\TestSend;
use Ctct\ConstantContact;
use Ctct\Exceptions\CtctException;

/**
 * Main Newspack Newsletters Class for Constant Contact ESP.
 */
final class Newspack_Newsletters_Constant_Contact extends \Newspack_Newsletters_Service_Provider {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->service    = 'constant_contact';
		$this->controller = new Newspack_Newsletters_Constant_Contact_Controller( $this );

		add_action( 'save_post_' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT, [ $this, 'save' ], 10, 3 );
		add_action( 'publish_' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT, [ $this, 'send' ], 10, 2 );
		add_action( 'wp_trash_post', [ $this, 'trash' ], 10, 1 );

		parent::__construct( $this );
	}

	/**
	 * Get API key for service provider.
	 *
	 * @return String Stored API key for the service provider.
	 */
	public function api_key() {
		// TODO: UI for user input of API key in keys modal and settings page.
		return defined( 'NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_API_KEY' ) ? NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_API_KEY : null;
	}

	/**
	 * Get Access Token key for service provider.
	 *
	 * @return String Stored Access Token key for the service provider.
	 */
	public function access_token() {
		// TODO: UI for user input of access token in keys modal and settings page.
		return defined( 'NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_ACCESS_TOKEN' ) ? NEWSPACK_NEWSLETTERS_CONSTANT_CONTACT_ACCESS_TOKEN : null;
	}

	/**
	 * Set the API key for the service provider.
	 *
	 * @param string $key API key.
	 */
	public function set_api_key( $key ) {
		// TODO: UI for user input of Constant Contact API credentials.
		return null;
	}

	/**
	 * Set list for a campaign.
	 *
	 * @param string $post_id Campaign Id.
	 * @param string $list_id ID of the list.
	 * @return object|WP_Error API API Response or error.
	 */
	public function list( $post_id, $list_id ) {
		try {
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new ConstantContact( $this->api_key() );

			$campaign = $cc->emailMarketingService->getCampaign( $this->access_token(), $cc_campaign_id ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			$campaign->addList( $list_id );

			$campaign_result = $cc->emailMarketingService->updateCampaign( $this->access_token(), $campaign ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			$data           = $this->retrieve( $post_id );
			$data['result'] = $campaign_result;
			return \rest_ensure_response( $data );

		} catch ( CtctException $e ) {
			return $this->manage_ctct_exception( $e );
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
		try {
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new ConstantContact( $this->api_key() );

			$campaign = $cc->emailMarketingService->getCampaign( $this->access_token(), $cc_campaign_id ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			$campaign->sent_to_contact_lists = array_filter(
				$campaign->sent_to_contact_lists,
				function( $list ) use ( $list_id ) {
					return $list->id !== $list_id;
				}
			);

			$campaign_result = $cc->emailMarketingService->updateCampaign( $this->access_token(), $campaign ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			$data           = $this->retrieve( $post_id );
			$data['result'] = $campaign_result;
			return \rest_ensure_response( $data );

		} catch ( CtctException $e ) {
			return $this->manage_ctct_exception( $e );
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
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new ConstantContact( $this->api_key() );

			$campaign = $cc->emailMarketingService->getCampaign( $this->access_token(), $cc_campaign_id ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			$lists = $cc->listService->getLists( $this->access_token() ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			return [
				'lists'       => $lists,
				'campaign'    => $campaign,
				'campaign_id' => $cc_campaign_id,
			];

		} catch ( CtctException $e ) {
			return $this->manage_ctct_exception( $e );
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
		try {
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new ConstantContact( $this->api_key() );

			$campaign = $cc->emailMarketingService->getCampaign( $this->access_token(), $cc_campaign_id ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			$campaign->from_email     = $reply_to;
			$campaign->reply_to_email = $reply_to;
			$campaign->from_name      = $from_name;

			$campaign_result = $cc->emailMarketingService->updateCampaign( $this->access_token(), $campaign ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			$data           = $this->retrieve( $post_id );
			$data['result'] = $campaign_result;

			return \rest_ensure_response( $data );
		} catch ( CtctException $e ) {
			return $this->manage_ctct_exception( $e );
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
		try {
			$cc_campaign_id = $this->retrieve_campaign_id( $post_id );
			$cc             = new ConstantContact( $this->api_key() );

			$result = $cc->campaignScheduleService->sendTest( //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$this->access_token(),
				$cc_campaign_id,
				TestSend::create(
					[
						'format'          => 'HTML',
						'email_addresses' => $emails,
					]
				)
			);

			$data            = $this->retrieve( $post_id );
			$data['result']  = $result;
			$data['message'] = sprintf(
			// translators: Message after successful test email.
				__( 'Constant Contact test sent successfully to %s.', 'newspack-newsletters' ),
				implode( ', ', $emails )
			);

			return \rest_ensure_response( $data );

		} catch ( CtctException $e ) {
			return $this->manage_ctct_exception( $e );
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
	 * @param WP_POST $post Post to synchronize.
	 * @return object|null API Response or error.
	 * @throws Exception Error message.
	 */
	public function sync( $post ) {
		$api_key = $this->api_key();
		if ( ! $api_key ) {
			return new WP_Error(
				'newspack_newsletters_missing_api_key',
				__( 'No Constant Contact API key available.', 'newspack-newsletters' )
			);
		}
		try {
			$cc             = new ConstantContact( $api_key );
			$cc_campaign_id = get_post_meta( $post->ID, 'cc_campaign_id', true );
			$renderer       = new Newspack_Newsletters_Renderer();
			$content        = $renderer->render_html_email( $post );
			if ( $cc_campaign_id ) {
				$campaign = $cc->emailMarketingService->getCampaign( $this->access_token(), $cc_campaign_id ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

				$campaign->subject       = $post->post_title;
				$campaign->email_content = $content;

				$campaign_result = $cc->emailMarketingService->updateCampaign( $this->access_token(), $campaign ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			} else {

				$account_info = $cc->accountService->getAccountInfo( $this->access_token() ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

				$initial_sender = __( 'Sender Name', 'newspack-newsletters' );
				if ( $account_info->organization_name ) {
					$initial_sender = $account_info->organization_name;
				} elseif ( $account_info->first_name && $account_info->last_name ) {
					$initial_sender = $account_info->first_name . ' ' . $account_info->last_name;
				}

				$verified_email_addresses = $cc->accountService->getVerifiedEmailAddresses( $this->access_token() ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

				if ( empty( $verified_email_addresses ) ) {
					throw new Exception( __( 'There are no verified email addresses in the Constant Contact account.', 'newspack-newsletters' ) );
				}

				$initial_email_address = $verified_email_addresses[0]->email_address;

				$campaign                       = new Campaign();
				$campaign->name                 = __( 'Newspack Newsletters', 'newspack-newsletters' ) . ' ' . uniqid();
				$campaign->subject              = $post->post_title;
				$campaign->from_email           = $initial_email_address;
				$campaign->reply_to_email       = $initial_email_address;
				$campaign->from_name            = $initial_sender;
				$campaign->email_content        = $content;
				$campaign->text_content         = $content;
				$campaign->email_content_format = 'HTML';

				$campaign_result = $cc->emailMarketingService->addCampaign( $this->access_token(), $campaign ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

				update_post_meta( $post->ID, 'cc_campaign_id', $campaign_result->id );
			}

			return [
				'campaign_result' => $campaign_result,
			];

		} catch ( CtctException $e ) {
			$wp_error  = $this->manage_ctct_exception( $e );
			$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
			set_transient( $transient, implode( ' ', $wp_error->get_error_messages() ), 45 );
			return $wp_error;
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
		if ( ! $update ) {
			update_post_meta( $post_id, 'template_id', -1 );
		}
		$status = get_post_status( $post_id );
		if ( 'trash' === $status ) {
			return;
		}
		$this->sync( $post );
	}

	/**
	 * Send a campaign.
	 *
	 * @param integer $post_id Post ID to send.
	 * @param WP_POST $post Post to send.
	 */
	public function send( $post_id, $post ) {
		if ( ! Newspack_Newsletters::validate_newsletter_id( $post_id ) ) {
			return new WP_Error(
				'newspack_newsletters_incorrect_post_type',
				__( 'Post is not a Newsletter.', 'newspack-newsletters' )
			);
		}

		try {
			$sync_result = $this->sync( $post );

			if ( is_wp_error( $sync_result ) ) {
				return $sync_result;
			}

			$cc_campaign_id = get_post_meta( $post_id, 'cc_campaign_id', true );
			if ( ! $cc_campaign_id ) {
				return new WP_Error(
					'newspack_newsletters_no_campaign_id',
					__( 'Constant Contact campaign ID not found.', 'newspack-newsletters' )
				);
			}

			$cc       = new ConstantContact( $this->api_key() );
			$schedule = new Schedule();

			$cc->campaignScheduleService->addSchedule( $this->access_token(), $cc_campaign_id, $schedule ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		} catch ( CtctException $e ) {
			$wp_error  = $this->manage_ctct_exception( $e );
			$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
			set_transient( $transient, implode( ' ', $wp_error->get_error_messages() ), 45 );
			return $wp_error;
		} catch ( Exception $e ) {
			$transient = sprintf( 'newspack_newsletters_error_%s_%s', $post->ID, get_current_user_id() );
			set_transient( $transient, $e->getMessage(), 45 );
			return;
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
		$cc_campaign_id = get_post_meta( $post_id, 'cc_campaign_id', true );
		if ( ! $cc_campaign_id ) {
			return;
		}

		$api_key = $this->api_key();
		if ( ! $api_key ) {
			return;
		}
		try {
			$cc       = new ConstantContact( $this->api_key() );
			$campaign = $cc->emailMarketingService->getCampaign( $this->access_token(), $cc_campaign_id ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( $campaign && 'DRAFT' === $campaign->status ) {
				$result = $cc->emailMarketingService->deleteCampaign( $this->access_token(), $cc_campaign_id ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
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
	 * Format and handle Constant Contact error messages.
	 *
	 * @param CtctException $e Error.
	 */
	public function manage_ctct_exception( $e ) {
		return new WP_Error(
			'newspack_newsletters_constant_contact_error',
			implode(
				' ',
				array_map(
					function( $error ) {
						return $error->error_message;
					},
					$e->getErrors()
				)
			)
		);
	}
}
