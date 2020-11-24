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

		if ( $visit['is_post'] && defined( 'ENABLE_CAMPAIGN_EVENT_LOGGING' ) && ENABLE_CAMPAIGN_EVENT_LOGGING ) {
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

		foreach ( $campaigns as $campaign ) {
			$response[ $campaign->id ] = $this->should_campaign_be_shown(
				$client_id,
				$campaign,
				$settings,
				filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING )
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
	 * @param string $referer_url Referer URL.
	 * @param string $now Current timestamp.
	 * @return bool Whether campaign should be shown.
	 */
	public function should_campaign_be_shown( $client_id, $campaign, $settings, $referer_url = '', $now = false ) {
		if ( false === $now ) {
			$now = time();
		}
		$campaign_data      = $this->get_campaign_data( $client_id, $campaign->id );
		$init_campaign_data = $campaign_data;

		if ( $campaign_data['suppress_forever'] ) {
			return false;
		}

		$should_display = true;

		// Handle frequency.
		$frequency = $campaign->f;
		switch ( $frequency ) {
			case 'daily':
				$should_display = $campaign_data['last_viewed'] < strtotime( '-1 day', $now );
				break;
			case 'once':
				$should_display = $campaign_data['count'] < 1;
				break;
			case 'always':
				$should_display = true;
				break;
			case 'never':
			default:
				$should_display = false;
				break;
		}

		$has_newsletter_prompt = $campaign->n;
		// Suppressing based on UTM Medium parameter in the URL.
		$has_utm_medium_in_url = stripos( $referer_url, 'utm_medium=email' );

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

		// Handle segmentation.
		$campaign_segment = isset( $settings->all_segments->{$campaign->s} ) ? $settings->all_segments->{$campaign->s} : false;
		if ( ! empty( $campaign_segment ) ) {
			$posts_read_count = count( $client_data['posts_read'] );
			// If coming from email, assume it's a subscriber.
			$is_subscriber = ! empty( $client_data['email_subscriptions'] ) || $has_utm_medium_in_url;
			$is_donor      = ! empty( $client_data['donations'] );
			if (
				$campaign_segment->min_posts > 0 && $campaign_segment->min_posts > $posts_read_count
			) {
				$should_display = false;
			}
			if (
				$campaign_segment->max_posts > 0 && $campaign_segment->max_posts < $posts_read_count
			) {
				$should_display = false;
			}
			if ( $campaign_segment->is_subscribed && ! $is_subscriber ) {
				$should_display = false;
			}
			if ( $campaign_segment->is_not_subscribed && $is_subscriber ) {
				$should_display = false;
				if ( $has_utm_medium_in_url ) { // Save suppression for this campaign.
					$campaign_data['suppress_forever'] = true;
				}
			}
			if ( $campaign_segment->is_donor && ! $is_donor ) {
				$should_display = false;
			}
			if ( $campaign_segment->is_not_donor && $is_donor ) {
				$should_display = false;
			}
		}

		if ( ! empty( array_diff( $init_campaign_data, $campaign_data ) ) ) {
			$this->save_campaign_data( $client_id, $campaign->id, $campaign_data );
		}

		return $should_display;
	}
}
new Maybe_Show_Campaign();
