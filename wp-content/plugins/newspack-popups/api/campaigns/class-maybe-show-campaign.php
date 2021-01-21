<?php
/**
 * Newspack Campaigns maybe display campaign.
 *
 * @package Newspack
 */

/**
 * Extend the base Lightweight_API class.
 */
require_once dirname( __FILE__ ) . '/../classes/class-lightweight-api.php';

require_once dirname( __FILE__ ) . '/../segmentation/class-segmentation-report.php';
require_once dirname( __FILE__ ) . '/class-campaign-data-utils.php';

/**
 * GET endpoint to determine if campaign is shown or not.
 */
class Maybe_Show_Campaign extends Lightweight_API {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		if ( ! isset( $_REQUEST['popups'], $_REQUEST['settings'], $_REQUEST['cid'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		$campaigns = json_decode( $_REQUEST['popups'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$settings  = json_decode( $_REQUEST['settings'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$visit     = (array) json_decode( $_REQUEST['visit'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$response  = [];
		$client_id = $_REQUEST['cid']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$view_as_spec = [];
		if ( ! empty( $_REQUEST['view_as'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$view_as_spec = Segmentation::parse_view_as( json_decode( $_REQUEST['view_as'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}

		if ( empty( $view_as_spec ) && $visit['is_post'] && defined( 'ENABLE_CAMPAIGN_EVENT_LOGGING' ) && ENABLE_CAMPAIGN_EVENT_LOGGING ) {
			// Update the cache.
			$posts_read        = $this->get_client_data( $client_id )['posts_read'];
			$already_read_post = count(
				array_filter(
					$posts_read,
					function ( $post_data ) use ( $visit ) {
						return $post_data['post_id'] == $visit['post_id'];
					}
				)
			) > 0;

			if ( false === $already_read_post ) {
				$posts_read[] = [
					'post_id'      => $visit['post_id'],
					'category_ids' => $visit['categories'],
					'created_at'   => gmdate( 'Y-m-d H:i:s' ),
				];
				$this->save_client_data(
					$client_id,
					[
						'posts_read' => $posts_read,
					]
				);
			}

			Segmentation_Report::log_single_visit(
				array_merge(
					[
						'clientId' => $client_id,
					],
					$visit
				)
			);
		}

		$page_referer_url = isset( $_REQUEST['ref'] ) ? $_REQUEST['ref'] : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		foreach ( $campaigns as $campaign ) {
			$response[ $campaign->id ] = $this->should_campaign_be_shown(
				$client_id,
				$campaign,
				$settings,
				filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING ),
				$page_referer_url,
				$view_as_spec
			);
		}
		$this->response = $response;
		$this->respond();
	}

	/**
	 * Primary campaign visibility logic.
	 *
	 * @param string $client_id Client ID.
	 * @param object $campaign Campaign.
	 * @param object $settings Settings.
	 * @param string $referer_url URL of the page performing the API request.
	 * @param string $page_referer_url URL of the referrer of the frontend page that is making the API request.
	 * @param object $view_as_spec "View As" specification.
	 * @param string $now Current timestamp.
	 * @return bool Whether campaign should be shown.
	 */
	public function should_campaign_be_shown( $client_id, $campaign, $settings, $referer_url = '', $page_referer_url = '', $view_as_spec = false, $now = false ) {
		if ( false === $now ) {
			$now = time();
		}
		$campaign_data      = $this->get_campaign_data( $client_id, $campaign->id );
		$init_campaign_data = $campaign_data;

		if ( ! $view_as_spec && $campaign_data['suppress_forever'] ) {
			return false;
		}

		$should_display = true;

		// Handle frequency.
		$frequency = $campaign->f;

		$has_newsletter_prompt = $campaign->n;
		// Suppressing based on UTM Medium parameter in the URL.
		$has_utm_medium_in_url = Campaign_Data_Utils::is_url_from_email( $referer_url );

		// Handle referer-based conditions.
		if ( ! empty( $referer_url ) ) {
			// Suppressing based on UTM Source parameter in the URL.
			$utm_suppression = ! empty( $campaign->utm ) ? urldecode( $campaign->utm ) : null;
			if ( $utm_suppression && stripos( urldecode( $referer_url ), 'utm_source=' . $utm_suppression ) ) {
				$should_display                    = false;
				$campaign_data['suppress_forever'] = true;
			}

			if (
				$has_utm_medium_in_url &&
				$settings->suppress_newsletter_campaigns &&
				$has_newsletter_prompt
			) {
				$should_display                    = false;
				$campaign_data['suppress_forever'] = true;
			}
		}

		$client_data                        = $this->get_client_data( $client_id );
		$has_suppressed_newsletter_campaign = $client_data['suppressed_newsletter_campaign'];

		// Handle suppressing a newsletter campaign if any newsletter campaign was dismissed.
		if (
			$has_newsletter_prompt &&
			$settings->suppress_all_newsletter_campaigns_if_one_dismissed &&
			$has_suppressed_newsletter_campaign
		) {
			$should_display                    = false;
			$campaign_data['suppress_forever'] = true;
		}

		$has_donated        = count( $client_data['donations'] ) > 0;
		$has_donation_block = $campaign->d;

		// Handle suppressing a donation campaign if reader is a donor and appropriate setting is active.
		if (
			$has_donation_block &&
			$settings->suppress_donation_campaigns_if_donor &&
			$has_donated
		) {
			$should_display                    = false;
			$campaign_data['suppress_forever'] = true;
		}

		// Using "view as" feature.
		$view_as_segment = false;
		if ( $view_as_spec ) {
			$should_display = true;
			if ( isset( $view_as_spec['segment'] ) && $view_as_spec['segment'] ) {
				$segment_config = [];
				if ( isset( $settings->all_segments->{$view_as_spec['segment']} ) ) {
					$segment_config = $settings->all_segments->{$view_as_spec['segment']};
				}
				$view_as_segment = empty( $segment_config ) ? false : $segment_config;
			}
		}

		// Handle segmentation.
		$campaign_segment = isset( $settings->all_segments->{$campaign->s} ) ? $settings->all_segments->{$campaign->s} : false;
		if ( ! empty( $campaign_segment ) ) {
			$campaign_segment = Campaign_Data_Utils::canonize_segment( $campaign_segment );
			$should_display   = Campaign_Data_Utils::should_display_campaign(
				$campaign_segment,
				$client_data,
				$referer_url,
				$page_referer_url,
				$view_as_segment
			);

			if (
				$campaign_segment->is_not_subscribed &&
				$has_utm_medium_in_url &&
				! empty( $client_data['email_subscriptions'] )
			) {
				// Save suppression for this campaign.
				$campaign_data['suppress_forever'] = true;
			}
		}

		if ( ! $view_as_spec ) {
			if ( ! empty( array_diff( $init_campaign_data, $campaign_data ) ) ) {
				$this->save_campaign_data( $client_id, $campaign->id, $campaign_data );
			}
			if ( 'once' === $frequency && $campaign_data['count'] >= 1 ) {
				$should_display = false;
			}
			if ( 'daily' === $frequency && $campaign_data['last_viewed'] >= strtotime( '-1 day', $now ) ) {
				$should_display = false;
			}
		}

		return $should_display;
	}
}
new Maybe_Show_Campaign();
