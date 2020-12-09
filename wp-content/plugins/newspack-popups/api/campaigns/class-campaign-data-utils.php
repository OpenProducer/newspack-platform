<?php
/**
 * Newspack Campaigns API utils.
 *
 * @package Newspack
 */

/**
 * Utils.
 */
class Campaign_Data_Utils {
	/**
	 * Is the URL from a newsletter?
	 *
	 * @param string $url A URL.
	 */
	public static function is_url_from_email( $url ) {
		return stripos( $url, 'utm_medium=email' );
	}

	/**
	 * Is client a donor?
	 *
	 * @param object $client_data Client data.
	 */
	public static function is_donor( $client_data ) {
		return ! empty( $client_data['donations'] );
	}

	/**
	 * Is client a subscriber?
	 *
	 * @param object $client_data Client data.
	 * @param string $url URL.
	 */
	public static function is_subscriber( $client_data, $url ) {
		// If coming from email, assume it's a subscriber.
		return ! empty( $client_data['email_subscriptions'] ) || self::is_url_from_email( $url );
	}

	/**
	 * Given a segment and client data, decide if the campaign should be shown.
	 *
	 * @param object $campaign_segment Segment data.
	 * @param object $client_data Client data.
	 * @param bool   $referer_url Referrer URL.
	 * @return bool Whether the campaign should be shown.
	 */
	public static function should_display_campaign( $campaign_segment, $client_data, $referer_url = '' ) {
		$should_display   = true;
		$posts_read_count = count( $client_data['posts_read'] );
		$is_subscriber    = self::is_subscriber( $client_data, $referer_url );
		$is_donor         = self::is_donor( $client_data );

		if ( $campaign_segment->min_posts > 0 && $campaign_segment->min_posts > $posts_read_count ) {
			$should_display = false;
		}
		if ( $campaign_segment->max_posts > 0 && $campaign_segment->max_posts < $posts_read_count ) {
			$should_display = false;
		}
		if ( $campaign_segment->is_subscribed && ! $is_subscriber ) {
			$should_display = false;
		}
		if ( $campaign_segment->is_not_subscribed && $is_subscriber ) {
			$should_display = false;
		}
		if ( $campaign_segment->is_donor && ! $is_donor ) {
			$should_display = false;
		}
		if ( $campaign_segment->is_not_donor && $is_donor ) {
			$should_display = false;
		}
		return $should_display;
	}
}
