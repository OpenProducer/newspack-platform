<?php
/**
 * Newspack Group Subscriptions.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Group_Subscription {
	/**
	 * User meta key for group subscription associations.
	 */
	const GROUP_SUBSCRIPTION_USER_META_KEY = '_newspack_group_subscription';

	/**
	 * Check if a subscription is a group subscription.
	 *
	 * @param \WC_Subscription|int $subscription The subscription object or ID.
	 *
	 * @return bool Whether the subscription is a group subscription.
	 */
	public static function is_group_subscription( $subscription ) {
		$settings = Group_Subscription_Settings::get_subscription_settings( $subscription );
		return $settings['enabled'];
	}

	/**
	 * Get the managers of a group subscription.
	 *
	 * @param \WC_Subscription|int $subscription The subscription object or ID.
	 *
	 * @return int[] The group manager user IDs.
	 */
	public static function get_managers( $subscription ) {
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = \wcs_get_subscription( $subscription );
		}

		/**
		 * Filter the managers of a group subscription.
		 * Currently this is only the subscription owner.
		 *
		 * @param int[] $member_ids The group manager user IDs.
		 * @param WC_Subscription $subscription The subscription object.
		 */
		return apply_filters( 'newspack_group_subscription_managers', [ $subscription ? $subscription->get_user_id() : 0 ], $subscription );
	}

	/**
	 * Get the members of a group subscription.
	 *
	 * @param \WC_Subscription|int $subscription The subscription object or ID.
	 *
	 * @return int[] Array of user IDs for the group subscription members.
	 */
	public static function get_members( $subscription ) {
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = \wcs_get_subscription( $subscription );
		}
		if ( ! $subscription ) {
			return [];
		}
		$subscription_id = $subscription->get_id();
		$members         = array_map(
			function( $user ) {
				return $user->ID;
			},
			\get_users(
				[
					'fields'     => [ 'ID' ],
					'meta_query' => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						[
							'key'   => self::GROUP_SUBSCRIPTION_USER_META_KEY,
							'value' => $subscription_id,
						],
					],
				]
			)
		);

		/**
		 * Filter the members of a group subscription.
		 *
		 * @param int[] $members Array of user IDs for group subscription members.
		 * @param \WC_Subscription $subscription The subscription object.
		 */
		return apply_filters( 'newspack_group_subscription_members', $members, $subscription );
	}

	/**
	 * Update the member IDs for a group subscription.
	 *
	 * @param \WC_Subscription|int $subscription The subscription object or ID.
	 * @param int[]                $members_to_add Group member user IDs to add the subscription.
	 * @param int[]                $members_to_remove Group member user IDs to remove from the subscription.
	 *
	 * @return array|\WP_Error Added/removed results.
	 */
	public static function update_members( $subscription, $members_to_add, $members_to_remove = [] ) {
		if ( ! function_exists( 'wcs_get_subscription' ) ) {
			return new \WP_Error( 'newspack_group_subscription_update_members', __( 'WooCommerce Subscriptions is not available.', 'newspack-plugin' ) );
		}
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = \wcs_get_subscription( $subscription );
		}
		if ( ! $subscription ) {
			return new \WP_Error( 'newspack_group_subscription_update_members', __( 'Subscription not found.', 'newspack-plugin' ) );
		}
		$subscription_settings = Group_Subscription_Settings::get_subscription_settings( $subscription );

		// If the subscription is not enabled, enable it.
		if ( ! $subscription_settings['enabled'] ) {
			Group_Subscription_Settings::update_subscription_settings( $subscription, [ 'enabled' => true ] );
		}
		$members_to_add    = array_values( array_unique( array_map( 'absint', (array) $members_to_add ) ) );
		$members_to_remove = array_values( array_unique( array_map( 'absint', (array) $members_to_remove ) ) );
		$members_added     = [];
		$members_removed   = [];

		// Remove members.
		foreach ( $members_to_remove as $member_id ) {
			if ( ! Reader_Activation::is_user_reader( $member_id ) ) {
				continue;
			}
			if ( \delete_user_meta( $member_id, self::GROUP_SUBSCRIPTION_USER_META_KEY, $subscription->get_id() ) ) {
				$members_removed[ $member_id ] = [
					'email' => \get_userdata( $member_id )->user_email,
					'url'   => \get_edit_user_link( $member_id ),
				];
			}
		}

		$existing_members = self::get_members( $subscription );
		if ( $subscription_settings['limit'] > 0 && count( $existing_members ) + count( $members_to_add ) > $subscription_settings['limit'] ) {
			return new \WP_Error( 'newspack_group_subscription_update_members', __( 'Member limit reached. Please remove some members or increase the limit.', 'newspack-plugin' ) );
		}

		// Add new members.
		foreach ( $members_to_add as $member_id ) {
			if ( ! Reader_Activation::is_user_reader( $member_id ) ) {
				continue;
			}

			// Avoid adding duplicate meta entries.
			$existing_group_subscription_ids = self::get_group_subscriptions_for_user( $member_id, true );
			if ( in_array( $subscription->get_id(), $existing_group_subscription_ids, true ) ) {
				continue;
			}
			if ( \add_user_meta( $member_id, self::GROUP_SUBSCRIPTION_USER_META_KEY, $subscription->get_id() ) ) {
				$members_added[ $member_id ] = [
					'email' => \get_userdata( $member_id )->user_email,
					'url'   => \get_edit_user_link( $member_id ),
				];
			}
		}
		return [
			'members_added'   => $members_added,
			'members_removed' => $members_removed,
		];
	}

	/**
	 * Check if a user is a member or manager of a group subscription.
	 *
	 * @param int                  $user_id The user ID.
	 * @param \WC_Subscription|int $subscription The subscription object or ID.
	 *
	 * @return bool|null Whether the user has access to the group subscription, or null if not a group subscription.
	 */
	public static function user_is_member( $user_id, $subscription ) {
		if ( ! self::is_group_subscription( $subscription ) ) {
			return null;
		}
		if ( ! is_a( $subscription, 'WC_Subscription' ) ) {
			$subscription = \wcs_get_subscription( $subscription );
		}
		if ( ! $subscription ) {
			return null;
		}
		$is_member = in_array( $subscription->get_id(), self::get_group_subscriptions_for_user( $user_id, true ), true ) || in_array( $user_id, self::get_managers( $subscription ), true );

		/**
		 * Filter whether a user is a member or manager of a group subscription.
		 *
		 * @param bool|null $is_member Whether the user is a member or manager of the group subscription, or null if not a group subscription.
		 * @param int $user_id The user ID.
		 * @param \WC_Subscription|int $subscription The subscription object or ID.
		 */
		return apply_filters( 'newspack_group_subscription_user_is_member', $is_member, $user_id, $subscription );
	}

	/**
	 * Get the group subscriptions a user is a member of.
	 * Group membership is represented as a repeatable user meta key with the subscription IDs the value.
	 *
	 * @param int  $user_id The user ID.
	 * @param bool $ids_only If true, return only the subscription IDs instead of the subscription objects.
	 *
	 * @return \WC_Subscription[]|int[] The group subscriptions or subscription IDs the user is a member of.
	 */
	public static function get_group_subscriptions_for_user( $user_id, $ids_only = false ) {
		if ( ! function_exists( 'wcs_get_subscription' ) ) {
			return [];
		}
		if ( ! Reader_Activation::is_user_reader( \get_user_by( 'id', $user_id ) ) ) {
			return [];
		}
		$subscription_ids = array_map( 'absint', \get_user_meta( $user_id, self::GROUP_SUBSCRIPTION_USER_META_KEY, false ) );
		$subscriptions    = [];
		foreach ( $subscription_ids as $subscription_id ) {
			$subscriptions[] = $ids_only ? $subscription_id : \wcs_get_subscription( $subscription_id );
		}

		/**
		 * Filter the group subscriptions a user is a member of.
		 *
		 * @param \WC_Subscription[]|int[] $subscriptions The group subscriptions or subscription IDs the user is a member of.
		 * @param int $user_id The user ID.
		 */
		return apply_filters( 'newspack_group_subscriptions_for_user', $subscriptions, $user_id );
	}
}
