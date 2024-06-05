<?php
/**
 * Co_Authors_Plus integration class.
 * https://wordpress.org/plugins/co-authors-plus
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Co_Authors_Plus {
	/**
	 * Custom capability name.
	 */
	const ASSIGNABLE_TO_POSTS_CAPABILITY_NAME = 'edit_cap_posts';

	/**
	 * Custom role name for users who are assignable as post authors but aren't allowed to edit posts.
	 */
	const CONTRIBUTOR_NO_EDIT_ROLE_NAME = 'contributor_no_edit';

	/**
	 * Option name to mark the version of the settings. If the implementation details
	 * change, the expected option value should be updated to trigger a reset of the settings.
	 */
	const SETTINGS_VERSION_OPTION_NAME = 'newspack_coauthors_plus_settings_version';

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'coauthors_edit_author_cap', [ __CLASS__, 'coauthors_edit_author_cap' ] );
		add_action( 'admin_init', [ __CLASS__, 'setup_custom_role_and_capability' ] );
		add_action( 'template_redirect', [ __CLASS__, 'prevent_myaccount_update' ] );
	}

	/**
	 * Override the capability required to assign a user as a co-author.
	 *
	 * @param string $edit_cap Capability required for a user to be assigned as a co-author.
	 */
	public static function coauthors_edit_author_cap( $edit_cap ) {
		return self::ASSIGNABLE_TO_POSTS_CAPABILITY_NAME;
	}

	/**
	 * Prevent users from updating their account details in My Account, if they have the custom role.
	 */
	public static function prevent_myaccount_update() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( empty( $action ) || 'save_account_details' !== $action ) {
			return;
		}

		$user_id = \get_current_user_id();
		if ( $user_id <= 0 ) {
			return;
		}
		$user = \get_user_by( 'id', $user_id );

		$is_contributor_no_edit = in_array( self::CONTRIBUTOR_NO_EDIT_ROLE_NAME, $user->roles );
		if ( $is_contributor_no_edit ) {
			if ( function_exists( 'wc_add_notice' ) ) {
				/* translators: %s is the custom role name. */
				\wc_add_notice( sprintf( __( 'Can\'t update details of a "%s" user.', 'newspack-plugin' ), self::CONTRIBUTOR_NO_EDIT_ROLE_NAME ), 'error' );
			}
			return;
		}
	}

	/**
	 * Create the custom role and then add custom capability.
	 */
	public static function setup_custom_role_and_capability() {
		$current_settings_version = '1';
		if ( \get_option( self::SETTINGS_VERSION_OPTION_NAME ) === $current_settings_version ) {
			return;
		}

		// Create the custom role if it doesn't exist.
		if ( get_role( self::CONTRIBUTOR_NO_EDIT_ROLE_NAME ) === null ) {
			add_role( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.custom_role_add_role
				self::CONTRIBUTOR_NO_EDIT_ROLE_NAME,
				__( 'Non-Editing Contributor', 'newspack-plugin' ),
				[
					self::ASSIGNABLE_TO_POSTS_CAPABILITY_NAME => true,
				]
			);
		}

		$wp_roles = wp_roles();
		foreach ( $wp_roles->roles as $role_name => $role ) {
			$role = $wp_roles->get_role( $role_name );
			if ( $role->has_cap( 'edit_posts' ) || $role_name === self::CONTRIBUTOR_NO_EDIT_ROLE_NAME ) {
				$role->add_cap( self::ASSIGNABLE_TO_POSTS_CAPABILITY_NAME );
			}
		}

		\update_option( self::SETTINGS_VERSION_OPTION_NAME, $current_settings_version );
	}
}
Co_Authors_Plus::init();
