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
	 * @param int $last_n_days Number of days to get the report for.
	 *
	 * @return array Usage report.
	 */
	public static function get_usage_report( $last_n_days ) {
		$mailchimp_instance = self::get_mc_instance();
		$mc_api             = new Mailchimp( $mailchimp_instance->api_key() );

		// Get daily activity for each list.
		$lists = $mc_api->get( 'lists', [ 'count' => 1000 ] );
		foreach ( $lists['lists'] as &$list ) {
			$activity_response = $mc_api->get(
				'lists/' . $list['id'] . '/activity',
				[
					'count' => $last_n_days,
				]
			);
			$list['activity']  = $activity_response['activity'];
		}

		return self::process_data_to_report( $lists['lists'] );
	}

	/**
	 * Process lists and activity data to a usage report.
	 *
	 * @param array $lists_with_activity Lists with activity.
	 */
	public static function process_data_to_report( $lists_with_activity ) {
		$report = [];
		foreach ( $lists_with_activity as $list ) {
			foreach ( $list['activity'] as $day ) {
				$date = $day['day'];
				if ( isset( $report[ $date ] ) ) {
					$existing_day    = $report[ $date ];
					$report[ $date ] = array_merge(
						$existing_day,
						[
							'emails_sent' => $existing_day['emails_sent'] + $day['emails_sent'],
							'opens'       => $existing_day['opens'] + $day['unique_opens'],
							'clicks'      => $existing_day['clicks'] + $day['recipient_clicks'],
							'subs'        => $existing_day['subs'] + $day['subs'],
							'unsubs'      => $existing_day['unsubs'] + $day['unsubs'],
						]
					);
				} else {
					$report[ $date ] = [
						'date'        => $date,
						'emails_sent' => $day['emails_sent'],
						'opens'       => $day['unique_opens'],
						'clicks'      => $day['recipient_clicks'],
						'subs'        => $day['subs'],
						'unsubs'      => $day['unsubs'],
					];
				}
			}
		}
		return array_values( $report );
	}
}
