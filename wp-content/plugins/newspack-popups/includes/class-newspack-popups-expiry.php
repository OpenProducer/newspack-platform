<?php
/**
 * Newspack Popups Expiry
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Newspack Popups Expiry Class.
 */
final class Newspack_Popups_Expiry {
	/**
	 * Hook name for the cron job.
	 */
	const CRON_HOOK = 'newspack_popups_check_expiry';

	/**
	 * Init Newspack Popups Expiry.
	 */
	public static function init() {
		add_action( 'transition_post_status', [ __CLASS__, 'transition_post_status' ], 10, 3 );
		add_action( self::CRON_HOOK, [ __CLASS__, 'revert_expired_to_draft' ] );
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time(), 'hourly', self::CRON_HOOK );
		}
	}

	/**
	 * Revert expired prompts to draft state.
	 */
	public static function revert_expired_to_draft() {
		// Get all prompts with the expiration_date in the past.
		$expired_prompts = get_posts(
			[
				'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
				'posts_per_page' => -1,
				'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					[
						'key'     => 'expiration_date',
						'value'   => gmdate( 'Y-m-d H:i:s' ),
						'compare' => '<=',
					],
				],
			]
		);
		foreach ( $expired_prompts as $prompt_post ) {
			// Change the post status to draft.
			wp_update_post(
				[
					'ID'          => $prompt_post->ID,
					'post_status' => 'draft',
				]
			);
		}
	}

	/**
	 * If the post is published, and it has an expiry date in the past, remove the expiry data.
	 *
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public static function transition_post_status( $new_status, $old_status, $post ) {
		if ( $old_status !== 'publish' && $new_status === 'publish' ) {
			$expiration_date = get_post_meta( $post->ID, 'expiration_date', true );
			if ( $expiration_date && strtotime( $expiration_date ) < time() ) {
				delete_post_meta( $post->ID, 'expiration_date' );
			}
		}
	}
}

Newspack_Popups_Expiry::init();
