<?php
/**
 * Service Provider: Active_Campaign Usage Reports
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Usage reports for Active_Campaign.
 */
class Newspack_Newsletters_Active_Campaign_Usage_Reports {
	/**
	 * Name of the option to store the last result under.
	 */
	const LAST_CAMPAIGNS_DATA_OPTION_NAME = 'newspack_newsletters_active_campaign_last_report';

	/**
	 * Newspack_Newsletters_Active_Campaign instance.
	 *
	 * @var Newspack_Newsletters_Active_Campaign
	 */
	private $ac_instance;

	/**
	 * Constructor with dependency injection for the sake of tests.
	 *
	 * @param Newspack_Newsletters_Active_Campaign $active_campaign Active Campaign instance.
	 */
	public function __construct( $active_campaign = null ) {
		if ( null === $active_campaign ) {
			$active_campaign = new Newspack_Newsletters_Active_Campaign();
		}
		$this->ac_instance = $active_campaign;
	}

	/**
	 * Get all contacts.
	 *
	 * @param int   $status Status of contacts to get.
	 * @param date  $created_after Date after which contacts were created.
	 * @param array $contacts Array of contacts.
	 */
	private function get_all_contacts( $status = 1, $created_after = null, $contacts = [] ) {
		$ac     = $this->ac_instance;
		$params = [
			'query' => [
				'offset' => count( $contacts ),
				'limit'  => 100,
				'status' => $status,
			],
		];
		if ( null !== $created_after ) {
			$params['query']['filters'] = [
				'created_after' => gmdate( 'Y-m-d', $created_after ),
			];
		}
		$contacts_result = $ac->api_v3_request( 'contacts', 'GET', $params );
		if ( \is_wp_error( $contacts_result ) ) {
			return $contacts_result;
		}
		$total    = intval( $contacts_result['meta']['total'] );
		$contacts = array_map(
			function( $contact ) {
				return array_intersect_key( $contact, array_flip( [ 'cdate', 'udate', 'email', 'id' ] ) );
			},
			array_merge( $contacts, $contacts_result['contacts'] )
		);
		Newspack_Newsletters_Logger::log( 'Fetched contacts with status ' . $status . ' (' . count( $contacts ) . '/' . $total . ')' );
		if ( count( $contacts ) === $total ) {
			return $contacts;
		}
		return $this->get_all_contacts( $status, $created_after, $contacts );
	}

	/**
	 * Update report with contact data.
	 *
	 * @param string $param Contact parameter to check.
	 * @param string $report_key Report key to update.
	 * @param array  $all_contacts Array of all contacts.
	 * @param array  $report Report to update.
	 * @param int    $last_n_days Number of last days to get the data about.
	 */
	private static function update_report_with_contact_data( $param, $report_key, $all_contacts, $report, $last_n_days ) {
		$cutoff_datetime = strtotime( '-' . $last_n_days . ' days' );
		foreach ( $all_contacts as $contact ) {
			if ( isset( $contact[ $param ] ) ) {
				$date        = strtotime( $contact[ $param ] );
				$date_string = gmdate( 'Y-m-d', $date );
				if ( $date < $cutoff_datetime ) {
					continue;
				}
				if ( isset( $report[ $date_string ] ) ) {
					if ( isset( $report[ $date_string ][ $report_key ] ) ) {
						$report[ $date_string ][ $report_key ]++;
					} else {
						$report[ $date_string ][ $report_key ] = 1;
					}
				} else {
					$report[ $date_string ] = [
						$report_key => 1,
					];
				}
			}
		}
		return $report;
	}

	/**
	 * Get contacts data - subs and unsubs.
	 *
	 * @param int $last_n_days Number of last days to get the data about.
	 */
	private function get_contacts_data( $last_n_days ) {
		$report = [];

		$cutoff_datetime = strtotime( '-' . $last_n_days . ' days' );

		// Subscribers, with the cutoff date – only created (subscribed) afterwards.
		$subscribed = $this->get_all_contacts( 1 );
		if ( \is_wp_error( $subscribed ) ) {
			return $subscribed;
		}
		$report = self::update_report_with_contact_data( 'cdate', 'subs', $subscribed, $report, $last_n_days );

		// Unsubscribed contacts, without the cutoff date. All have to be pulled because
		// there's no way to filter by unsubscribed date.
		$unsubscribed = $this->get_all_contacts( 2 );
		if ( \is_wp_error( $unsubscribed ) ) {
			return $unsubscribed;
		}
		$report = self::update_report_with_contact_data( 'udate', 'unsubs', $unsubscribed, $report, $last_n_days );

		return $report;
	}

	/**
	 * Get default report.
	 */
	private static function get_default_report() {
		$report = new Newspack_Newsletters_Service_Provider_Usage_Report();
		return $report->to_array();
	}

	/**
	 * Get default campaign data.
	 */
	private static function get_default_campaign_data() {
		return [
			'emails_sent' => 0,
			'opens'       => 0,
			'clicks'      => 0,
		];
	}

	/**
	 * Get campaign data - emails sent, opens, and clicks.
	 *
	 * @param int $last_n_days Number of last days to get the data about.
	 */
	private function get_campaign_data( $last_n_days ) {
		$last_campaigns_data = get_option( self::LAST_CAMPAIGNS_DATA_OPTION_NAME );
		$campaigns_data      = self::get_default_campaign_data();

		$current_campaign_data = $this->get_current_campaign_data( $last_n_days );
		update_option( self::LAST_CAMPAIGNS_DATA_OPTION_NAME, $current_campaign_data );

		if ( ! $last_campaigns_data ) {
			// No data about campaigns yet, so there is nothing to compare the new data with.
			return $campaigns_data;
		}

		foreach ( $current_campaign_data as $campaign_id => $current_data ) {
			$prior_data = self::get_default_campaign_data();
			// From the current totals, subtract the totals from the last report.
			if ( isset( $last_campaigns_data[ $campaign_id ] ) ) {
				// Only consider campaigns that are present in the current response.
				$prior_data = $last_campaigns_data[ $campaign_id ];
			}
			$campaigns_data['emails_sent'] += $current_data['emails_sent'] - $prior_data['emails_sent'];
			$campaigns_data['opens']       += $current_data['opens'] - $prior_data['opens'];
			$campaigns_data['clicks']      += $current_data['clicks'] - $prior_data['clicks'];
		}
		return $campaigns_data;
	}

	/**
	 * Get campaign data - emails sent, opens, and clicks live from the ESP.
	 *
	 * @param int $last_n_days Number of last days to get the data about.
	 */
	private function get_current_campaign_data( $last_n_days ) {
		$ac               = $this->ac_instance;
		$params           = [
			'query' => [
				'limit'  => 100, // Assuming there will be no more than 100 campaigns in the requested period (last n days).
				'orders' => [ 'sdate' => 'DESC' ],
			],
		];
		$campaigns_result = $ac->api_v3_request( 'campaigns', 'GET', $params );
		$cutoff_datetime  = strtotime( '-' . $last_n_days . ' days' );

		if ( \is_wp_error( $campaigns_result ) ) {
			return $campaigns_result;
		}

		$campaigns_data = [];

		foreach ( $campaigns_result['campaigns'] as $campaign ) {
			if (
				! isset( $campaign['sdate'] )
				|| 5 != $campaign['status'] // Status "5" is "completed. See https://www.activecampaign.com/api/example.php?call=campaign_list.
				) {
				continue;
			}
			// If the send date is before the cutoff date, break out.
			$campaign_send_date = strtotime( $campaign['sdate'] );
			if ( $campaign_send_date < $cutoff_datetime ) {
				break;
			}
			$campaigns_data[ intval( $campaign['id'] ) ] = [
				'emails_sent' => intval( $campaign['send_amt'] ),
				'opens'       => intval( $campaign['uniqueopens'] ),
				'clicks'      => intval( $campaign['uniquelinkclicks'] ),
			];
		}
		return $campaigns_data;
	}

	/**
	 * Get total number of active contacts.
	 */
	private function get_total_active_contacts() {
		$ac              = $this->ac_instance;
		$contacts_result = $ac->api_v3_request(
			'contacts',
			'GET',
			[
				'query' => [
					'limit'  => 1,
					'status' => 1,
				],
			]
		);
		return (int) $contacts_result['meta']['total'];
	}

	/**
	 * Creates a usage report.
	 *
	 * @return array Usage report.
	 */
	public function get_usage_report() {
		$report = new Newspack_Newsletters_Service_Provider_Usage_Report();

		// Get contact data to retrieve subs and unsubs.
		$contacts_data = $this->get_contacts_data( 1 );
		if ( \is_wp_error( $contacts_data ) ) {
			return $contacts_data;
		}

		// Get campaign data to retrieve emails sent, opens, and clicks.
		$campaign_data = $this->get_campaign_data( 30 ); // Consider Campaigns sent up to 30 days in the past.
		if ( \is_wp_error( $campaign_data ) ) {
			return $campaign_data;
		}

		$report->emails_sent = $campaign_data['emails_sent'];
		$report->opens       = $campaign_data['opens'];
		$report->clicks      = $campaign_data['clicks'];

		$report->total_contacts = $this->get_total_active_contacts();

		$yesterday = gmdate( 'Y-m-d', strtotime( '-1 day' ) );
		if ( isset( $contacts_data[ $yesterday ], $contacts_data[ $yesterday ]['subs'] ) ) {
			$report->subscribes = $contacts_data[ $yesterday ]['subs'];
		}
		if ( isset( $contacts_data[ $yesterday ], $contacts_data[ $yesterday ]['unsubs'] ) ) {
			$report->unsubscribes = $contacts_data[ $yesterday ]['unsubs'];
		}

		return $report;
	}
}
