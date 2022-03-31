<?php
/**
 * Newspack Campaigns report client data.
 *
 * @package Newspack
 */

/**
 * Extend the base Lightweight_API class.
 */
require_once dirname( __FILE__ ) . '/../classes/class-lightweight-api.php';

require_once __DIR__ . '/../../vendor/autoload.php';

use \DrewM\MailChimp\MailChimp;

/**
 * POST endpoint to report client data.
 */
class Segmentation_Client_Data extends Lightweight_API {
	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		parent::__construct();
		$payload = $this->get_post_payload();
		if ( empty( $payload ) ) {
			return;
		}
		$this->report_client_data( $payload );
		$this->respond();
	}

	/**
	 * Handle reporting client data – e.g. views, dismissals.
	 *
	 * @param object $request A request.
	 */
	public function report_client_data( $request ) {
		$client_id          = $this->get_request_param( 'client_id', $request );
		$client_data_update = [];

		// Add a donation to client.
		$donation = $this->get_request_param( 'donation', $request );
		if ( $donation ) {
			if ( 'string' === gettype( $donation ) ) {
				$donation = (array) json_decode( $donation );
			}
			$client_data_update['donations'][] = $donation;
		}

		// Add a subscription to client.
		$email_subscription = $this->get_request_param( 'email_subscription', $request );
		if ( $email_subscription ) {
			$client_data_update['email_subscriptions'][] = $email_subscription;
		}

		// Get user ID if they are logged in.
		$user_id = $this->get_request_param( 'user_id', $request );
		if ( $user_id ) {
			$client_data_update['user_id'] = $user_id;
		}
		// Add donations data from WC orders.
		$orders = $this->get_request_param( 'orders', $request );
		if ( $orders ) {
			$client_data_update['donations'] = json_decode( $orders );
		}

		// Fetch Mailchimp data.
		$mailchimp_campaign_id   = $this->get_request_param( 'mc_cid', $request );
		$mailchimp_subscriber_id = $this->get_request_param( 'mc_eid', $request );
		if ( $mailchimp_campaign_id && $mailchimp_subscriber_id ) {
			$mailchimp_api_key_option_name        = 'newspack_mailchimp_api_key';
			$mailchimp_api_key_option_name_legacy = 'newspack_newsletters_mailchimp_api_key';
			global $wpdb;
			$mailchimp_api_key = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->prepare( "SELECT option_value FROM `$wpdb->options` WHERE option_name IN (%s,%s) ORDER BY FIELD(%s,%s)", $mailchimp_api_key_option_name, $mailchimp_api_key_option_name_legacy, $mailchimp_api_key_option_name, $mailchimp_api_key_option_name_legacy )
			);
			if ( $mailchimp_api_key ) {
				$mc            = new Mailchimp( $mailchimp_api_key->option_value );
				$campaign_data = $mc->get( "campaigns/$mailchimp_campaign_id" );
				if ( isset( $campaign_data['recipients'], $campaign_data['recipients']['list_id'] ) ) {
					$list_id = $campaign_data['recipients']['list_id'];
					$members = $mc->get( "/lists/$list_id/members", [ 'unique_email_id' => $mailchimp_subscriber_id ] )['members'];

					if ( ! empty( $members ) ) {
						$subscriber                                  = $members[0];
						$client_data_update['email_subscriptions'][] = [
							'email' => $subscriber['email_address'],
						];

						if ( ! isset( $subscriber['merge_fields'] ) ) {
							return;
						}

						$donor_merge_field_option_name      = 'newspack_popups_mc_donor_merge_field';
						$donor_merge_fields                 = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
							$wpdb->prepare( "SELECT option_value FROM `$wpdb->options` WHERE option_name = %s LIMIT 1", $donor_merge_field_option_name )
						);
						$donor_merge_fields                 = isset( $donor_merge_fields->option_value ) ? explode( ',', $donor_merge_fields->option_value ) : [ 'DONAT' ];
						$has_donated_according_to_mailchimp = array_reduce(
							// Get all merge fields whose name contains one of the Donor Merge Field option strings.
							array_filter(
								array_keys( $subscriber['merge_fields'] ),
								function ( $merge_field ) use ( $donor_merge_fields ) {
									$matches = false;
									foreach ( $donor_merge_fields as $donor_merge_field ) {
										if ( strpos( $merge_field, trim( $donor_merge_field ) ) !== false ) {
											$matches = true;
										}
									}
									return $matches;
								}
							),
							// If any of these fields is "true", the subscriber has donated.
							function ( $result, $donation_merge_field_name ) use ( $subscriber ) {
								if ( 'true' === $subscriber['merge_fields'][ $donation_merge_field_name ] ) {
									$result = true;
								}
								return $result;
							},
							false
						);

						if ( $has_donated_according_to_mailchimp ) {
							$client_data_update['donations'][] = [
								'mailchimp_has_donated' => true,
							];
						}
					}
				}
			}
		}

		if ( ! empty( $client_data_update ) ) {
			$this->save_client_data( $client_id, $client_data_update );
		}
	}
}
new Segmentation_Client_Data();
