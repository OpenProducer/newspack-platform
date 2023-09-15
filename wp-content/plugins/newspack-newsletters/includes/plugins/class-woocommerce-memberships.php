<?php
/**
 * Newspack Newsletters Settings Page
 *
 * @package Newspack
 */

namespace Newspack_Newsletters\Plugins;

use Newspack\Newsletters\Subscription_List;
use Newspack\Newsletters\Subscription_Lists;
use Newspack_Newsletters;
use Newspack_Newsletters_Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Manages Settings page.
 */
class Woocommerce_Memberships {

	/**
	 * Holds the ID of the user in the scope of the current request.
	 *
	 * When a user is granted membership to a plan that requires a registration, the user is not logged in yet,
	 * so we use this information to add the user to the lists when the user is created.
	 *
	 * @var ?int
	 */
	protected static $user_id_in_scope;

	/** 
	 * Initialize the class
	 */
	public static function init() {
		add_action( 'plugins_loaded', [ __CLASS__, 'init_hooks' ] );
	}
	
	/** 
	 * Initialize the hooks after all plugins are loaded
	 */
	public static function init_hooks() {
		if ( ! class_exists( 'WC_Memberships_Loader' ) ) {
			return;
		}
		add_filter( 'newspack_newsletters_contact_lists', [ __CLASS__, 'filter_lists' ] );
		add_filter( 'newspack_newsletters_subscription_block_available_lists', [ __CLASS__, 'filter_lists' ] );
		add_filter( 'newspack_newsletters_manage_newsletters_available_lists', [ __CLASS__, 'filter_lists_objects' ] );
		add_filter( 'newspack_auth_form_newsletters_lists', [ __CLASS__, 'filter_lists_objects' ] );
		add_action( 'wc_memberships_user_membership_status_changed', [ __CLASS__, 'remove_user_from_list' ], 10, 3 );
		add_action( 'wc_memberships_user_membership_saved', [ __CLASS__, 'add_user_to_list' ], 10, 2 );
		add_action( 'wc_memberships_user_membership_deleted', [ __CLASS__, 'deleted_membership' ] );
	}

	/**
	 * Keep users from being added to lists that require a membership plan they dont have
	 * Also filters lists that require a membership plan to be displayed in the subscription block and in the Manage Newsletters page in My Account
	 *
	 * @param array $lists The List IDs.
	 * @return array
	 */
	public static function filter_lists( $lists ) {
		if ( ! is_array( $lists ) || empty( $lists ) ) {
			return $lists;
		}
		$lists = array_filter(
			$lists,
			function( $list ) {
				$list_object = Subscription_List::from_form_id( $list );
				if ( ! $list_object ) {
					return false;
				}

				$is_post_restricted = wc_memberships_is_post_content_restricted( $list_object->get_id() );

				if ( ! $is_post_restricted ) {
					return true;
				}

				$user_id = self::$user_id_in_scope ?? get_current_user_id();

				return wc_memberships_user_can( $user_id, 'view', [ 'post' => $list_object->get_id() ] );

			} 
		);
		return array_values( $lists );
	}

	/**
	 * Receives an array of Lists and returns only the ones that the user has access to
	 *
	 * @param array $lists An array of lists in which the key are the list IDs.
	 * @return array
	 */
	public static function filter_lists_objects( $lists ) {
		if ( ! is_array( $lists ) || empty( $lists ) ) {
			return $lists;
		}

		$list_ids = self::filter_lists( array_keys( $lists ) );

		return array_filter(
			$lists,
			function( $list_id ) use ( $list_ids ) {
				return in_array( $list_id, $list_ids );
			},
			ARRAY_FILTER_USE_KEY
		);

	}

	/**
	 * Remove lists that require a membership plan when the membership is cancelled
	 *
	 * @param WC_Memberships_User_Membership $user_membership The User Membership object.
	 * @param string                         $old_status old status, without the `wcm-` prefix.
	 * @param string                         $new_status new status, without the `wcm-` prefix.
	 * @return void
	 */
	public static function remove_user_from_list( $user_membership, $old_status, $new_status ) {
		Newspack_Newsletters_Logger::log( 'Membership status changed to ' . $new_status );
		$status_considered_active = wc_memberships()->get_user_memberships_instance()->get_active_access_membership_statuses();

		if ( in_array( $new_status, $status_considered_active ) ) {
			return;
		}
		
		$lists_to_remove = [];
		$user            = $user_membership->get_user();
		if ( ! $user ) {
			return;
		}
		$user_email = $user->user_email;
		$rules      = $user_membership->get_plan()->get_content_restriction_rules();
		foreach ( $rules as $rule ) {
			if ( Subscription_Lists::CPT !== $rule->get_content_type_name() ) {
				continue;
			}
			$object_ids = $rule->get_object_ids();
			foreach ( $object_ids as $object_id ) {
				try {
					$subscription_list = new Subscription_List( $object_id );
					$lists_to_remove[] = $subscription_list->get_form_id();
				} catch ( \InvalidArgumentException $e ) {
					continue;
				}
			}
		}
		$provider = Newspack_Newsletters::get_service_provider();
		$provider->update_contact_lists_handling_local( $user_email, [], $lists_to_remove );
		Newspack_Newsletters_Logger::log( 'Reader ' . $user_email . ' removed from the following lists: ' . implode( ', ', $lists_to_remove ) );
		
	}

	/**
	 * Adds user to premium lists when a membership is granted
	 *
	 * @param \WC_Memberships_Membership_Plan $plan the plan that user was granted access to.
	 * @param array                           $args 
	 * {
	 *     Array of User Membership arguments.
	 * 
	 *     @type int $user_id the user ID the membership is assigned to.
	 *     @type int $user_membership_id the user membership ID being saved.
	 *     @type bool $is_update whether this is a post update or a newly created membership.
	 * }
	 * @return void
	 */
	public static function add_user_to_list( $plan, $args ) {

		// When creating the membership via admin panel, this hook is called once before the membership is actually created.
		if ( ! $plan instanceof \WC_Memberships_Membership_Plan ) {
			return;
		}

		$status_considered_active = wc_memberships()->get_user_memberships_instance()->get_active_access_membership_statuses();

		$user_membership = new \WC_Memberships_User_Membership( $args['user_membership_id'] );

		if ( ! in_array( $user_membership->get_status(), $status_considered_active, true ) ) {
			return;
		}

		$user_id = $args['user_id'] ?? false;
		if ( ! $user_id ) {
			return;
		}
		$user = get_user_by( 'ID', $user_id );
		if ( ! $user ) {
			return;
		}

		self::$user_id_in_scope = $user_id;

		$lists_to_add = [];
		$user_email   = $user->user_email;
		$rules        = $plan->get_content_restriction_rules();

		Newspack_Newsletters_Logger::log( 'New membership granted to ' . $user_email );

		foreach ( $rules as $rule ) {
			if ( Subscription_Lists::CPT !== $rule->get_content_type_name() ) {
				continue;
			}
			$object_ids = $rule->get_object_ids();
			foreach ( $object_ids as $object_id ) {
				$subscription_list = new Subscription_List( $object_id );
				$lists_to_add[]    = $subscription_list->get_form_id();
			}
		}
		$provider = Newspack_Newsletters::get_service_provider();
		$provider->update_contact_lists_handling_local( $user_email, $lists_to_add );
		Newspack_Newsletters_Logger::log( 'Reader ' . $user_email . ' added to the following lists: ' . implode( ', ', $lists_to_add ) );
		
	}

	/**
	 * Remove lists that require a membership plan when the membership is cancelled
	 *
	 * @param WC_Memberships_User_Membership $user_membership The User Membership object.
	 * @return void
	 */
	public static function deleted_membership( $user_membership ) {
		self::remove_user_from_list( $user_membership, '', 'deleted' );
	}

}

Woocommerce_Memberships::init();
