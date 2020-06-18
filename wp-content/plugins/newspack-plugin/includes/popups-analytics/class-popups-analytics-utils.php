<?php
/**
 * Popups Analytics utils.
 *
 * @package Newspack
 */

use Google\Site_Kit\Modules\Analytics;
use Google\Site_Kit\Context;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_DateRange;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_Metric;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_ReportRequest;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_Dimension;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_GetReportsRequest;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_SegmentDimensionFilter;
use Google\Site_Kit_Dependencies\Google_Service_AnalyticsReporting_DimensionFilterClause;
use Google\Site_Kit\Core\Storage\Options;
use Google\Site_Kit\Core\Storage\User_Options;
use Google\Site_Kit\Core\Authentication\Authentication;

/**
 * Popup Analytics Utilities.
 */
class Popups_Analytics_Utils {
	// Category is fixed, it identifies the GA custom event.
	const EVENT_CATEGORY = 'Newspack Announcement';

	/**
	 * Get dates in range.
	 *
	 * @param Date   $start start date.
	 * @param Date   $end end date.
	 * @param string $format date format.
	 * @return array array of dates in the given range.
	 */
	private static function get_dates_in_range( $start, $end, $format = 'Y-m-d' ) {
		return array_map(
			function( $timestamp ) use ( $format ) {
				return gmdate( $format, $timestamp );
			},
			range( strtotime( $start ) + ( $start < $end ? 4000 : 8000 ), strtotime( $end ) + ( $start < $end ? 8000 : 4000 ), 86400 )
		);
	}

	/**
	 * Date before.
	 *
	 * @param string $offset date offset in days.
	 * @return string formatted date.
	 */
	private static function date_before( $offset ) {
		$today = new \DateTime();
		$today = $today->modify( '-' . $offset . ' days' );
		return $today->format( 'Y-m-d' );
	}

	/**
	 * Fill in dates.
	 *
	 * @param array  $input array of days.
	 * @param string $offset date offset in days.
	 * @return array array of dates.
	 */
	private static function fill_in_dates( $input, $offset ) {
		$all_days = self::get_dates_in_range(
			self::date_before( $offset ),
			self::date_before( '1' )
		);

		$days_filled = array_map(
			function ( $day ) use ( $input ) {
				return array(
					'date'  => $day,
					'value' => isset( $input[ $day ] ) ? intval( $input[ $day ] ) : 0,
				);
			},
			$all_days
		);

		return $days_filled;
	}

	/**
	 * Get GA report.
	 *
	 * @param Object $options options.
	 * @return array array of rows with GA report data.
	 */
	public static function get_ga_report( $options ) {
		$offset = $options['offset'];

		// Load and query analytics.
		if ( defined( 'GOOGLESITEKIT_PLUGIN_MAIN_FILE' ) ) {
			$context   = new Context( GOOGLESITEKIT_PLUGIN_MAIN_FILE );
			$analytics = new Analytics( $context );

			if ( $analytics->is_connected() ) {
				// Create the DateRange object.
				$date_range = new Google_Service_AnalyticsReporting_DateRange();
				$start_date = gmdate( 'Y-m-d', strtotime( $offset . ' days ago' ) );
				$end_date   = gmdate( 'Y-m-d', strtotime( '1 days ago' ) );
				$date_range->setStartDate( $start_date );
				$date_range->setEndDate( $end_date );

				// Create the Metrics object.
				$metrics = new Google_Service_AnalyticsReporting_Metric();
				$metrics->setExpression( 'ga:totalEvents' );
				$metrics->setAlias( 'events' );

				// Filter just the popups custom event category.
				$dimension_category_filter = new Google_Service_AnalyticsReporting_SegmentDimensionFilter();
				$dimension_category_filter->setDimensionName( 'ga:eventCategory' );
				$dimension_category_filter->setOperator( 'EXACT' );
				$dimension_category_filter->setExpressions( array( self::EVENT_CATEGORY ) );

				// Create the DimensionFilterClauses.
				$dimension_filter_clause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
				$dimension_filter_clause->setFilters( array( $dimension_category_filter ) );

				// Create the Dimension objects.
				$date_dimension = new Google_Service_AnalyticsReporting_Dimension();
				$date_dimension->setName( 'ga:date' );
				$action_dimension = new Google_Service_AnalyticsReporting_Dimension();
				$action_dimension->setName( 'ga:eventAction' );
				$label_dimension = new Google_Service_AnalyticsReporting_Dimension();
				$label_dimension->setName( 'ga:eventLabel' );

				// Create the ReportRequest object.
				$profile_id = $analytics->get_data( 'profile-id' );
				$request    = new Google_Service_AnalyticsReporting_ReportRequest();
				$request->setViewId( $profile_id );
				$request->setDateRanges( $date_range );
				$request->setDimensions(
					array(
						$date_dimension,
						$action_dimension,
						$label_dimension,
					)
				);
				$request->setMetrics( array( $metrics ) );
				$request->setDimensionFilterClauses( array( $dimension_filter_clause ) );

				$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
				$body->setReportRequests( array( $request ) );
				$ga_options     = new Options( $context );
				$user_options   = new User_Options( $context );
				$authentication = new Authentication( $context, $ga_options, $user_options );
				$client         = $authentication->get_oauth_client()->get_client();

				try {
					$analyticsreporting = new Google_Service_AnalyticsReporting( $client );
					$report_results     = $analyticsreporting->reports->batchGet( $body );
					if ( isset( $report_results->reports[0] ) ) {
						return $report_results->reports[0]->data->rows;
					}
				} catch ( \Exception $error ) {
					$error_data = json_decode( $error->getMessage() );
					if ( isset( $error_data ) && 'UNAUTHENTICATED' === $error_data->error->status ) {
						return new WP_Error( 'newspack_campaign_analytics_sitekit_auth', __( 'Please authenticate with Google Analytics to view Campaign analytics.', 'newspack' ) );
					}
					return new WP_Error( 'newspack_campaign_analytics', __( 'Google Analytics data fetching error.', 'newspack' ), $error_data );
				}
			} else {
				return new WP_Error( 'newspack_campaign_analytics_sitekit_disconnected', __( 'Connect Site Kit plugin to view Campaign Analytics.', 'newspack' ) );
			}
		}
	}

	/**
	 * Get report.
	 *
	 * @param Object $options options.
	 * @return Object report data.
	 */
	public static function get_report( $options ) {
		$offset         = $options['offset'];
		$event_label_id = $options['event_label_id'];
		$event_action   = $options['event_action'];

		$ga_data_rows = self::get_ga_report( $options );
		if ( is_wp_error( $ga_data_rows ) ) {
			return $ga_data_rows;
		}

		$all_actions = [];
		$all_labels  = [];

		// Key metrics.
		$aggregate_seen_events            = 0;
		$aggregate_form_submission_events = -1;
		$aggregate_link_click_events      = -1;

		$post_edit_link = null;

		$ga_data_days = array_reduce(
			$ga_data_rows,
			function ( $days, $row ) use ( $event_label_id, $event_action, &$all_actions, &$all_labels, &$aggregate_seen_events, &$aggregate_form_submission_events, &$aggregate_link_click_events, &$post_edit_link ) {
				$action = array(
					'label' => $row['dimensions'][1],
					'value' => $row['dimensions'][1],
				);

				$label_name = str_replace( self::EVENT_CATEGORY . ': ', '', $row['dimensions'][2] );
				preg_match(
					'/\(([0-9]*)\)$/',
					$label_name,
					$id_matches
				);

				$post_id = isset( $id_matches[1] ) ? $id_matches[1] : '';
				$label   = array(
					// Remove post id in parens.
					'label' => str_replace( " ($post_id)", '', $label_name ),
					'value' => $post_id,
				);

				$matches_label  = '' == $event_label_id || $post_id == $event_label_id;
				$matches_action = '' == $event_action || $action['value'] == $event_action;

				if ( $matches_label && $post_id ) {
					$value = $row['metrics'][0]['values'][0];

					$post = get_post( $post_id, ARRAY_A );
					if ( isset( $post['post_content'] ) ) {
						$post_content = $post['post_content'];
						if (
							-1 === $aggregate_link_click_events &&
							strpos( $post_content, '<a ' ) !== false
						) {
							$aggregate_link_click_events = 0;
						}
						if (
							-1 === $aggregate_form_submission_events &&
							strpos( $post_content, '<!-- wp:jetpack/mailchimp' ) !== false ||
							strpos( $post_content, '<!-- wp:mailchimp-for-wp/form' ) !== false ||
							strpos( $post_content, '<form' ) !== false
						) {
							$aggregate_form_submission_events = 0;
						}

						if ( '' != $event_label_id && get_post_status( $post['ID'] ) == 'publish' ) {
							$post_edit_link = get_edit_post_link( $post['ID'] );
						}
					}

					// Key metrics.
					if ( 'Seen' == $action['value'] ) {
						$aggregate_seen_events = $aggregate_seen_events + $value;
					}
					if ( 'Form Submission' == $action['value'] ) {
						$aggregate_form_submission_events = $aggregate_form_submission_events + $value;
					}
					if ( 'Link Click' == $action['value'] ) {
						$aggregate_link_click_events = $aggregate_link_click_events + $value;
					}

					if ( $matches_action ) {
						if ( ! in_array( $action, $all_actions ) ) {
							$all_actions[] = $action;
						}

						if ( $post_id ) {
							// Last event with this label will be the final one.
							// If a popup was renamed, the last title will end up as the label.
							$all_labels[ $post_id ] = $label;
						}

						$parsed_date = date_create( $row['dimensions'][0] );
						$date        = date_format( $parsed_date, 'Y-m-d' );
						if ( isset( $days[ $date ] ) ) {
							$value = $days[ $date ] + $value;
						}
						$days[ $date ] = $value;
					}
				}


				return $days;

			},
			array()
		);

		// Key metrics.
		$key_metrics = array(
			'seen'             => $aggregate_seen_events,
			'form_submissions' => $aggregate_form_submission_events,
			'link_clicks'      => $aggregate_link_click_events,
		);

		return array(
			'report'         => self::fill_in_dates( $ga_data_days, $offset ),
			'actions'        => $all_actions,
			'labels'         => array_values( $all_labels ),
			'key_metrics'    => $key_metrics,
			'post_edit_link' => isset( $post_edit_link ) ? $post_edit_link : false,
		);
	}
}
