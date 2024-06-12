<?php
/**
 * Service Provider: Mailchimp Usage Reports
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

use DrewM\MailChimp\MailChimp;

/**
 * Usage reports for Mailchimp.
 */
class Newspack_Newsletters_Mailchimp_Usage_Reports {
	const REPORTS_OPTION_NAME = 'newspack_newsletters_mailchimp_usage_reports';

	/**
	 * Retrieves the main Mailchimp instance
	 *
	 * @return Newspack_Newsletters_Mailchimp
	 */
	private static function get_mc_instance() {
		return Newspack_Newsletters_Mailchimp::instance();
	}

	/**
	 * Get list activity reports for a specific timeframe between n days in past and yesterday.
	 *
	 * @param string $days_in_past_count How many days in the past to look for.
	 * @return Newspack_Newsletters_Service_Provider_Usage_Report[] Usage reports.
	 */
	public static function get_list_activity_reports( $days_in_past_count = 1 ) {
		$mc_api = new Mailchimp( self::get_mc_instance()->api_key() );

		$reports = [];
		$lists  = $mc_api->get( 'lists', [ 'count' => 1000 ] );
		$lists_activity_reports = [];

		foreach ( $lists['lists'] as &$list ) {
			// Get daily activity for each list.
			$activity_response = $mc_api->get(
				'lists/' . $list['id'] . '/activity',
				[
					'count' => $days_in_past_count + 1, // Add 1 to include the current day, which will be disregarded.
				]
			);
			$list['activity']  = $activity_response['activity'];
		}

		for ( $day_index = 1; $day_index <= $days_in_past_count; $day_index++ ) {
			$report = new Newspack_Newsletters_Service_Provider_Usage_Report();
			$report->set_date( gmdate( 'Y-m-d', strtotime( "-$day_index day" ) ) );
			$report->total_contacts += $list['stats']['member_count'];

			foreach ( $lists['lists'] as $list ) {
				$list_activity_for_day = $list['activity'][ $day_index ];
				$report->emails_sent  += $list_activity_for_day['emails_sent'];
				$report->opens        += $list_activity_for_day['unique_opens'];
				$report->clicks       += $list_activity_for_day['recipient_clicks'];
				$report->subscribes   += $list_activity_for_day['subs'];
				$report->unsubscribes += $list_activity_for_day['unsubs'];
			}
			$reports[] = $report;
		}

		return $reports;
	}

	/**
	 * Creates a usage report.
	 *
	 * @return Newspack_Newsletters_Service_Provider_Usage_Report Usage report.
	 */
	public static function get_usage_report() {
		// Start with lists activity reports. These are good for historical data and also will provide
		// subscribes and unsubscribes data. However, in order to get recent
		// sent/opens/clicks data, the campaign reports have to be used.
		// It appears that the sent/opens/clicks data in the lists activity are only added after a
		// delay of 2-3 days.
		$reports = self::get_list_activity_reports( 1 );

		$mc_api = new Mailchimp( self::get_mc_instance()->api_key() );

		$campaign_reports = [];
		$campaign_reports_response = $mc_api->get(
			'reports',
			[
				// Look at reports for campaigns sent at most two weeks ago.
				'since_send_time' => gmdate( 'Y-m-d H:i:s', strtotime( '-14 day' ) ),
				'type'            => 'regular', // Email campaigns.
			]
		);
		// For each report, save the stats per-campaign.
		foreach ( $campaign_reports_response['reports'] as $campaign_report ) {
			$send_time = $campaign_report['send_time'];
			// Disregards reports for campaigns sent today (this data is incomplete and
			// should surface in tomorrow's report).
			if ( gmdate( 'Y-m-d', strtotime( $send_time ) ) === gmdate( 'Y-m-d' ) ) {
				continue;
			}
			$campaign_reports[ $campaign_report['id'] ] = [
				'emails_sent' => $campaign_report['emails_sent'],
				'opens'       => $campaign_report['opens']['unique_opens'],
				// `unique_subscriber_clicks` will match what's visible in MC UI, but more accurate would be
				// to use `unique_clicks`.
				'clicks'      => $campaign_report['clicks']['unique_subscriber_clicks'],
				'send_time'   => $send_time,
			];
		}

		// Compare to stored reports to compute the delta.
		$saved_reports = get_option( self::REPORTS_OPTION_NAME, [] );

		foreach ( $reports as $report ) {
			$report_date = $report->get_date();
			foreach ( $campaign_reports as $campaign_id => $campaign_report ) {
				if ( $campaign_report['send_time'] >= $report_date ) {
					// If the campaign was sent in the last 24h, no need to look up historical data.
					$report->emails_sent += $campaign_report['emails_sent'];
					$report->opens += $campaign_report['opens'];
					$report->clicks += $campaign_report['clicks'];
				} elseif ( isset( $saved_reports[ $campaign_id ] ) ) {
					// If the campaign was sent earlier than the last 24h, look up historical data
					// to substract from new data.
					$previous_report = $saved_reports[ $campaign_id ];
					$report->emails_sent += $campaign_report['emails_sent'] - $previous_report['emails_sent'];
					$report->opens += $campaign_report['opens'] - $previous_report['opens'];
					$report->clicks += $campaign_report['clicks'] - $previous_report['clicks'];
				}
			}
		}

		// Save the recent response.
		update_option( self::REPORTS_OPTION_NAME, $campaign_reports );

		return reset( $reports );
	}
}
