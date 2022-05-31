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
	 * Is client a subscriber?
	 *
	 * @param object $client_data Client data.
	 */
	public static function is_subscriber( $client_data ) {
		return ! empty( $client_data['email_subscriptions'] );
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
	 * Is client logged in?
	 *
	 * @param object $client_data Client data.
	 */
	public static function is_logged_in( $client_data ) {
		return ! empty( $client_data['user_id'] );
	}

	/**
	 * Compare page referrer to a list of referrers.
	 *
	 * @param string $page_referrer_url Referrer to compare to.
	 * @param string $referrers_list_string Comma-separated referrer domains list.
	 */
	public static function does_referrer_match( $page_referrer_url, $referrers_list_string ) {
		$referer_domain      = parse_url( $page_referrer_url, PHP_URL_HOST ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url, WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$referer_domain_root = implode( '.', array_slice( explode( '.', $referer_domain ), -2 ) );
		$referrer_matches    = in_array(
			$referer_domain_root,
			array_map( 'trim', explode( ',', $referrers_list_string ) )
		);
		return $referrer_matches;
	}

	/**
	 * Add default values to a segment.
	 *
	 * @param object $segment Segment configuration.
	 * @return object Segment configuration with default values.
	 */
	public static function canonize_segment( $segment ) {
		return (object) array_merge(
			[
				'min_posts'           => 0,
				'max_posts'           => 0,
				'min_session_posts'   => 0,
				'max_session_posts'   => 0,
				'is_subscribed'       => false,
				'is_not_subscribed'   => false,
				'is_donor'            => false,
				'is_not_donor'        => false,
				'is_logged_in'        => false,
				'is_not_logged_in'    => false,
				'referrers'           => '',
				'favorite_categories' => [],
				'priority'            => PHP_INT_MAX,
			],
			(array) $segment
		);
	}

	/**
	 * Given a segment and client data, decide if the prompt should be shown.
	 *
	 * @param object $campaign_segment Segment data.
	 * @param object $client_data Client data.
	 * @param string $referer_url URL of the page performing the API request.
	 * @param string $page_referrer_url URL of the referrer of the frontend page that is making the API request.
	 * @return bool Whether the prompt should be shown.
	 */
	public static function does_client_match_segment( $campaign_segment, $client_data, $referer_url = '', $page_referrer_url = '' ) {
		$should_display = true;
		// Posts read.
		$posts_read_count = count( $client_data['posts_read'] );
		// Posts read in the current session.
		$session_start            = strtotime( '-45 minutes', time() );
		$posts_read_count_session = count(
			array_filter(
				$client_data['posts_read'],
				function ( $post_data ) use ( $session_start ) {
					if ( ! isset( $post_data['created_at'] ) ) {
						return false;
					}
					return strtotime( $post_data['created_at'] ) > $session_start;
				}
			)
		);
		$is_subscriber            = self::is_subscriber( $client_data );
		$is_donor                 = self::is_donor( $client_data );
		$is_logged_in             = self::is_logged_in( $client_data );
		$campaign_segment         = self::canonize_segment( $campaign_segment );

		// Read counts for categories.
		$categories_read_counts = array_reduce(
			$client_data['posts_read'],
			function ( $read_counts, $read_post ) {
				foreach ( explode( ',', $read_post['category_ids'] ) as $cat_id ) {
					if ( isset( $read_counts[ $cat_id ] ) ) {
						$read_counts[ $cat_id ]++;
					} else {
						$read_counts[ $cat_id ] = 1;
					}
				}
				return $read_counts;
			},
			[]
		);
		arsort( $categories_read_counts );
		$favorite_category_matches_segment = in_array( key( $categories_read_counts ), $campaign_segment->favorite_categories );

		/**
		 * By posts read count.
		 */
		if ( $campaign_segment->min_posts > 0 && $campaign_segment->min_posts > $posts_read_count ) {
			$should_display = false;
		}
		if ( $campaign_segment->max_posts > 0 && $campaign_segment->max_posts < $posts_read_count ) {
			$should_display = false;
		}

		/**
		 * By posts read in session count.
		 */
		if ( $campaign_segment->min_session_posts > 0 && $campaign_segment->min_session_posts > $posts_read_count_session ) {
			$should_display = false;
		}
		if ( $campaign_segment->max_session_posts > 0 && $campaign_segment->max_session_posts < $posts_read_count_session ) {
			$should_display = false;
		}

		/**
		 * By subscription status.
		 */
		if ( $campaign_segment->is_subscribed && ! $is_subscriber ) {
			$should_display = false;
		}
		if ( $campaign_segment->is_not_subscribed && $is_subscriber ) {
			$should_display = false;
		}

		/**
		 * By donation status.
		 */
		if ( $campaign_segment->is_donor && ! $is_donor ) {
			$should_display = false;
		}
		if ( $campaign_segment->is_not_donor && $is_donor ) {
			$should_display = false;
		}

		/**
		 * By login status.
		 */
		if ( $campaign_segment->is_logged_in && ! $is_logged_in ) {
			$should_display = false;
		}
		if ( $campaign_segment->is_not_logged_in && $is_logged_in ) {
			$should_display = false;
		}

		/**
		 * By referrer domain.
		 */
		if ( ! empty( $campaign_segment->referrers ) ) {
			if ( empty( $page_referrer_url ) ) {
				$should_display = false;
			}
			if ( empty( self::does_referrer_match( $page_referrer_url, $campaign_segment->referrers ) ) ) {
				$should_display = false;
			}
		}

		/**
		 * By referrer domain - negative.
		 */
		if ( ! empty( $campaign_segment->referrers_not ) && ! empty( self::does_referrer_match( $page_referrer_url, $campaign_segment->referrers_not ) ) ) {
			$should_display = false;
		}

		/**
		 * By most read category.
		 */
		if ( count( $campaign_segment->favorite_categories ) > 0 && ! $favorite_category_matches_segment ) {
			$should_display = false;
		}

		return $should_display;
	}
}
