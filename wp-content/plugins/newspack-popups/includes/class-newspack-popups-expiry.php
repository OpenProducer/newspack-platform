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
		add_action( 'init', [ __CLASS__, 'register_recurring_event' ] );
		add_action( self::CRON_HOOK, [ __CLASS__, 'revert_expired_to_draft' ] );
	}

	/**
	 * Register the recurring event, prioritizing the use of Action Scheduler.
	 *
	 * @return void
	 */
	public static function register_recurring_event() {
		if ( is_admin() && function_exists( 'as_schedule_recurring_action' ) ) {
			self::register_check_expiry_as_event();

			// If AS supports, it, also hook into ensure_recurring actions.
			if ( function_exists( 'as_supports' ) && as_supports( 'ensure_recurring_actions_hook' ) ) {
				add_action( 'action_scheduler_ensure_recurring_actions', [ __CLASS__, 'register_check_expiry_as_event' ] );
			}
		} elseif ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( strtotime( 'tomorrow' ), 'daily', self::CRON_HOOK );
		}
	}

	/**
	 * Registers the Action Scheduler's recurring event
	 *
	 * @return void
	 */
	public static function register_check_expiry_as_event() {
		if ( false === wp_cache_get( 'newspack_popups_recurring_action_scheduled' ) ) {
			if ( ! as_has_scheduled_action( self::CRON_HOOK ) ) {
				as_schedule_recurring_action(
					strtotime( 'tomorrow' ) + 60, // plus 60 to avoid race conditions.
					DAY_IN_SECONDS,
					self::CRON_HOOK
				);
			}
			wp_cache_set( 'newspack_popups_recurring_action_scheduled', true, HOUR_IN_SECONDS );
		}
	}

	/**
	 * Check if a given date should be considered expired.
	 * A date is expired if it's at or before the next occuring midnight.
	 *
	 * @param string $date The date string to check.
	 *
	 * @return bool True if the date is expired, false otherwise.
	 */
	public static function is_expired( $date ) {
		if ( ! $date ) {
			return false;
		}
		$expiration_date = date_create_immutable( $date, wp_timezone() );
		if ( false === $expiration_date ) {
			return false;
		}

		$now      = current_datetime(); // Site timezone aware.
		$tomorrow = $now->setTime( 0, 0 )->modify( '+1 day' ); // Next occurring midnight in the site's timezone.

		return $expiration_date < $tomorrow;
	}

	/**
	 * Revert expired prompts to draft state.
	 */
	public static function revert_expired_to_draft() {
		// Get all prompts with the expiration_date in the past.
		$prompts_with_expiration = get_posts(
			[
				'post_type'      => Newspack_Popups::NEWSPACK_POPUPS_CPT,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'AND',
					[
						'key'     => 'expiration_date',
						'compare' => 'EXISTS',
					],
					[
						'key'     => 'expiration_date',
						'compare' => '!=',
						'value'   => '',
					],
				],
			]
		);
		foreach ( $prompts_with_expiration as $prompt ) {
			// Change the post status to draft.
			if ( self::is_expired( get_post_meta( $prompt->ID, 'expiration_date', true ) ) ) {
				wp_update_post(
					[
						'ID'          => $prompt->ID,
						'post_status' => 'draft',
					]
				);
				Newspack_Popups_Logger::log(
					sprintf(
						'Prompt "%s" has expired and reverted to draft status.',
						$prompt->post_title
					)
				);
			}
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
