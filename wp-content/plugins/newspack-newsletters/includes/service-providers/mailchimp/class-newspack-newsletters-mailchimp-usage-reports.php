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
	/**
	 * Retrieves the main Mailchimp instance
	 *
	 * @return Newspack_Newsletters_Mailchimp
	 */
	private static function get_mc_instance() {
		return Newspack_Newsletters_Mailchimp::instance();
	}

	/**
	 * Creates a usage report.
	 *
	 * @return array Usage report.
	 */
	public static function get_usage_report() {
		$mailchimp_instance = self::get_mc_instance();
		$mc_api             = new Mailchimp( $mailchimp_instance->api_key() );

		$report = new Newspack_Newsletters_Service_Provider_Usage_Report();
		$lists  = $mc_api->get( 'lists', [ 'count' => 1000 ] );

		foreach ( $lists['lists'] as &$list ) {
			$report->total_contacts += $list['stats']['member_count'];

			// Get daily activity for each list.
			$activity_response = $mc_api->get(
				'lists/' . $list['id'] . '/activity',
				[
					'count' => 2,
				]
			);
			$list['activity']  = $activity_response['activity'];
			$yesterday         = gmdate( 'Y-m-d', strtotime( '-1 day' ) );

			if ( ! empty( $activity_response['activity'][1] ) && $yesterday === $activity_response['activity'][1]['day'] ) {
				$report->emails_sent  += $activity_response['activity'][1]['emails_sent'];
				$report->opens        += $activity_response['activity'][1]['unique_opens'];
				$report->clicks       += $activity_response['activity'][1]['recipient_clicks'];
				$report->subscribes   += $activity_response['activity'][1]['subs'];
				$report->unsubscribes += $activity_response['activity'][1]['unsubs'];
			}
		}

		return $report;
	}
}
