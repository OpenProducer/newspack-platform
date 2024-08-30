<?php
/**
 * Guest_Contributor_Role class.
 * https://wordpress.org/plugins/co-authors-plus
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_User;

/**
 * This class implements an alternative for the Guest Authors feature of Co-Authors Plus.
 *
 * The Non Editing Contributor role behaves similarly to the Guest Authors feature, but it's a custom role that can be assigned to users.
 *
 * This role can also be assigned to users who have other roles, so they can be assigned as co-authors of a post without having the capability to edit posts.
 * This is done via a custom UI in the user profile.
 */
class Guest_Contributor_Role {
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
		add_action( 'newspack_before_delete_account', [ __CLASS__, 'before_delete_account' ] );

		if ( defined( 'NEWSPACK_DISABLE_CAP_GUEST_AUTHORS' ) && NEWSPACK_DISABLE_CAP_GUEST_AUTHORS ) {
			add_filter( 'coauthors_guest_authors_enabled', '__return_false' );
			add_action( 'admin_menu', [ __CLASS__, 'guest_author_menu_replacement' ] );
		}

		// Do not allow guest authors to login.
		\add_filter( 'wp_authenticate_user', [ __CLASS__, 'wp_authenticate_user' ], 10, 2 );

		// Modify the user profile and user creation forms.
		\add_action( 'admin_footer', [ __CLASS__, 'admin_footer' ] );
		\add_filter( 'user_profile_update_errors', [ __CLASS__, 'user_profile_update_errors' ], 10, 3 );
		\add_action( 'admin_print_scripts-user-new.php', [ __CLASS__, 'admin_footer' ] );
		\add_action( 'admin_print_scripts-user-edit.php', [ __CLASS__, 'admin_footer' ] );

		\add_filter( 'option_default_role', [ __CLASS__, 'create_user_default_role' ] );
		\add_filter( 'option_cme_capabilities_add_user_multi_roles', [ __CLASS__, 'cme_capabilities_add_user_multi_roles' ] );

		// Disable some features from the user profile.
		\add_filter( 'show_password_fields', [ __CLASS__, 'disable_feature' ], 10, 2 );
		\add_filter( 'wp_is_application_passwords_available_for_user', [ __CLASS__, 'disable_feature' ], 10, 2 );
		\add_filter( 'allow_password_reset', [ __CLASS__, 'disable_feature' ], 10, 2 );
		\add_filter( 'woocommerce_current_user_can_edit_customer_meta_fields', [ __CLASS__, 'disable_feature' ], 10, 2 );

		// Only if Members plugin is not active, because it has its own UI for roles.
		if ( ! class_exists( 'Members_Plugin' ) ) {
			// Add UI to the user profile to assign the custom role.
			add_action( 'edit_user_profile', [ __CLASS__, 'edit_user_profile' ] );
			add_action( 'wp_update_user', [ __CLASS__, 'edit_user_profile_update' ] );
		}

		// Hide author email on the frontend, if it's a placeholder email.
		\add_filter( 'theme_mod_show_author_email', [ __CLASS__, 'should_display_author_email' ] );
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
	 * Determines whether a user is only a "guest author", meaning it only has the custom role and no other role.
	 *
	 * In this case, the user won't be able to login and will have some features removed from their profile.
	 *
	 * Users who have more than one role other than non_edit_contributor are still able to login and a have a full profile.
	 *
	 * @param WP_User $user The user to check.
	 * @return bool
	 */
	private static function is_guest_author( WP_User $user ) {
		return 1 === count( $user->roles ) && self::CONTRIBUTOR_NO_EDIT_ROLE_NAME === current( $user->roles );
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
	 * Prevents the Delete Account email to be sent and display an error message to the user
	 *
	 * @param int $user_id The user ID trying to delete the account.
	 * @return void
	 */
	public static function before_delete_account( $user_id ) {
		if ( user_can( $user_id, self::ASSIGNABLE_TO_POSTS_CAPABILITY_NAME ) ) {
			\wp_safe_redirect(
				\add_query_arg(
					[
						'message'  => __( 'It looks like you are an author on this site. Please contact a site adminstrator to get your account deactivated.', 'newspack-plugin' ),
						'is_error' => true,
					],
					\remove_query_arg( WooCommerce_My_Account::DELETE_ACCOUNT_URL_PARAM )
				)
			);
			exit;
		}
	}

	/**
	 * Create the custom role and then add custom capability.
	 */
	public static function setup_custom_role_and_capability() {
		$current_settings_version = '2';
		if ( \get_option( self::SETTINGS_VERSION_OPTION_NAME ) === $current_settings_version ) {
			return;
		}

		// Update the custom role.
		remove_role( self::CONTRIBUTOR_NO_EDIT_ROLE_NAME );
		add_role( // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.custom_role_add_role
			self::CONTRIBUTOR_NO_EDIT_ROLE_NAME,
			__( 'Guest Contributor', 'newspack-plugin' ),
			[
				self::ASSIGNABLE_TO_POSTS_CAPABILITY_NAME => true,
			]
		);

		$wp_roles = wp_roles();
		foreach ( $wp_roles->roles as $role_name => $role ) {
			$role = $wp_roles->get_role( $role_name );
			if ( $role->has_cap( 'edit_posts' ) || $role_name === self::CONTRIBUTOR_NO_EDIT_ROLE_NAME ) {
				$role->add_cap( self::ASSIGNABLE_TO_POSTS_CAPABILITY_NAME );
			}
		}

		\update_option( self::SETTINGS_VERSION_OPTION_NAME, $current_settings_version );
	}

	/**
	 * Get dummy email domain.
	 */
	public static function get_dummy_email_domain() {
		return \apply_filters( 'newspack_guest_author_email_domain', 'example.com' );
	}

	/**
	 * Is a dummy email address?
	 *
	 * @param string $email_address Email address to check.
	 */
	public static function is_dummy_email_address( $email_address ) {
		return strpos( $email_address, '@' . self::get_dummy_email_domain() ) !== false;
	}

	/**
	 * Create a placeholder/dummy email address.
	 *
	 * @param WP_User|string $user_or_name The user, or just the name.
	 */
	public static function get_dummy_email_address( $user_or_name ) {
		$email_domain = self::get_dummy_email_domain();
		if ( is_string( $user_or_name ) ) {
			return $user_or_name . '@' . $email_domain;
		}
		return $user->user_login . '@' . $email_domain;
	}

	/**
	 * Filters user validation to allow empty emails for guest authors
	 *
	 * When creating a new user, also automatically generate a username from the display name.
	 *
	 * @param WP_Error $errors WP_Error object (passed by reference).
	 * @param bool     $update Whether this is a user update.
	 * @param stdClass $user   User object (passed by reference).
	 * @return WP_Error
	 */
	public static function user_profile_update_errors( $errors, $update, $user ) {

		if ( ! isset( $user->role ) || self::CONTRIBUTOR_NO_EDIT_ROLE_NAME !== $user->role ) {
			return $errors;
		}

		if ( ! empty( $errors->errors['empty_email'] ) ) {
			$errors->remove( 'empty_email' );
		}

		if ( ! empty( $errors->errors['user_login'] ) ) {
			$errors->remove( 'user_login' );
		}

		// We still don't want users with duplicate emails.
		if ( ! empty( $errors->errors['email_exists'] ) ) {
			return $errors;
		}

		if ( ! $update ) {
			// For guest authors, the form is modified via JS and we get the display name in the username field.
			$user->display_name = $user->user_login;

			// Create user name from Display name.
			$user->user_login = self::generate_username( $user->display_name );
		}

		if ( empty( $user->user_email ) ) {
			// Create a placeholder email address to avoid any issues with empty emails.
			$user->user_email = self::get_dummy_email_address( $user );
		}

		return $errors;
	}

	/**
	 * Generates a unique username from a display name.
	 *
	 * @param string $display_name The user's display name.
	 * @return string
	 */
	public static function generate_username( $display_name ) {
		$username = \sanitize_user( $display_name, true );
		$username = \sanitize_title( $username );

		while ( \username_exists( $username ) ) {
			$username = $username . '-' . \wp_rand( 1, 100 );
		}

		return $username;
	}

	/**
	 * Enqueues the JS that modifies the user profile and user creation forms.
	 *
	 * @return void
	 */
	public static function admin_footer() {
		global $pagenow;
		\wp_enqueue_script(
			'newspack-co-authors-plus',
			Newspack::plugin_url() . '/dist/other-scripts/co-authors-plus.js',
			[ 'jquery' ],
			NEWSPACK_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'newspack-co-authors-plus',
			'guestAuthorRole',
			[
				'role'             => self::CONTRIBUTOR_NO_EDIT_ROLE_NAME,
				'displayNameLabel' => __( 'Display name', 'newspack-plugin' ),
				'screen'           => $pagenow === 'user-new.php' ? 'new' : 'edit',
			]
		);
	}

	/**
	 * A generic callback applied to filters that check if a user has access to a feature, or if a certain field should be displayed in its profile.
	 *
	 * These callbacks pass the return of the check as the first argument ant the user or user ID as the second.
	 *
	 * @param bool        $result The result of the check.
	 * @param int|WP_User $user A user ID or user object.
	 * @return bool
	 */
	public static function disable_feature( $result, $user ) {
		if ( is_int( $user ) ) {
			$user = \get_user_by( 'id', $user );
		}

		if ( ! is_a( $user, 'WP_User' ) ) {
			return $result;
		}

		if ( self::is_guest_author( $user ) ) {
			return false;
		}

		return $result;
	}

	/**
	 * Filters user authentication to prevent guest authors from logging in.
	 *
	 * @param WP_Error|WP_User $user The logged in user or login error.
	 * @param string           $password The user's password.
	 * @return WP_Error|WP_User
	 */
	public static function wp_authenticate_user( $user, $password ) {
		if ( ! is_a( $user, 'WP_User' ) ) {
			return $user;
		}

		if ( self::is_guest_author( $user ) ) {
			return new \WP_Error( 'guest_authors_cannot_login', __( 'Guest Contributors cannot login.', 'newspack-plugin' ) );
		}

		return $user;
	}

	/**
	 * Adds a replacement Guest Authors menu item.
	 */
	public static function guest_author_menu_replacement() {
		add_submenu_page(
			'users.php',
			__( 'Guest Authors', 'newspack-plugin' ),
			__( 'Guest Authors', 'newspack-plugin' ),
			'list_users',
			'newspack-view-guest-authors',
			[ __CLASS__, 'render_guest_authors_replacement_page' ]
		);
	}

	/**
	 * Render the replacement Guest Authors page.
	 */
	public static function render_guest_authors_replacement_page() {
		?>
			<div class="wrap">
				<h1><?php echo esc_html__( 'Guest Authors', 'newspack-plugin' ); ?></h1>

				<p><?php echo esc_html__( "Co-Authors-Plus' Guest Authors are disabled in this site. Use the Guest Contributor user role instead.", 'newspack-plugin' ); ?></p>
				<p><?php echo esc_html__( 'You can use one of the shortcuts below:', 'newspack-plugin' ); ?></p>

				<a href="<?php echo esc_url( admin_url( 'user-new.php?role=' . self::CONTRIBUTOR_NO_EDIT_ROLE_NAME ) ); ?>" class="page-title-action"><?php echo esc_html__( 'Create a new Guest Contributor', 'newspack-plugin' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'users.php?role=' . self::CONTRIBUTOR_NO_EDIT_ROLE_NAME ) ); ?>" class="page-title-action"><?php echo esc_html__( 'View all Guest Contributors', 'newspack-plugin' ); ?></a>
			</div>
		<?php
	}

	/**
	 * Save custom fields.
	 *
	 * @param int $user_id User ID.
	 */
	public static function edit_user_profile_update( $user_id ) {
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-user_' . $user_id ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$user = get_userdata( $user_id );

		if ( self::is_guest_author( $user ) ) {
			return;
		}

		if ( ! empty( $_POST['newspack_cap_custom_cap_option'] ) ) {
			$user->add_role( self::CONTRIBUTOR_NO_EDIT_ROLE_NAME );
		} else {
			$user->remove_role( self::CONTRIBUTOR_NO_EDIT_ROLE_NAME );
		}
	}

	/**
	 * Add user profile fields.
	 *
	 * @param WP_User $user The current WP_User object.
	 */
	public static function edit_user_profile( $user ) {

		if ( self::is_guest_author( get_userdata( $user->ID ) ) ) { // For some reason $user is not the full user object.
			return;
		}
		$current_status = user_can( $user->ID, self::CONTRIBUTOR_NO_EDIT_ROLE_NAME );
		?>
		<div class="newspack-plugin-cap-options">

			<h2><?php echo esc_html__( 'Co-Authors Plus Options', 'newspack-plugin' ); ?></h2>

			<table class="form-table" role="presentation">
				<tr class="user-newspack_cap_custom_cap_option-wrap">
					<th scope="row">
						<?php esc_html_e( 'Enable as coauthor', 'newspack-plugin' ); ?>
					</th>
					<td>
						<label for="newspack_cap_custom_cap_option">
							<input type="checkbox" name="newspack_cap_custom_cap_option" id="newspack_cap_custom_cap_option" value="1" <?php checked( $current_status ); ?> />
							<?php esc_html_e( 'Allow this user to be assigned as a co-author of a post.', 'newspack-plugin' ); ?>
						</label>
						<p class="description">
						<?php
							esc_html_e( 'If this option is checked, the "Non Editing Contributor" role will be added to the user and they will be able to be assigned as a co-author of a post even if they are not allowed to edit posts. For users with edit access, this option has no effect.', 'newspack-plugin' );
						?>
						</p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Is creating a new user with the no-edit role?
	 */
	public static function is_adding_user_with_no_edit_role() {
		global $pagenow;
		return $pagenow === 'user-new.php' && isset( $_GET['role'] ) && $_GET['role'] === self::CONTRIBUTOR_NO_EDIT_ROLE_NAME; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Filter the `default_role` option value.
	 *
	 * @param string $default_role The default role slug.
	 */
	public static function create_user_default_role( $default_role ) {
		if ( self::is_adding_user_with_no_edit_role() ) {
			return self::CONTRIBUTOR_NO_EDIT_ROLE_NAME;
		}
		return $default_role;
	}

	/**
	 * Handle the `capability-manager-enhanced` plugin - disable multi-role UI if adding a new no-edit role user.
	 *
	 * @param bool $value Whether to enable the multi-role UI.
	 */
	public static function cme_capabilities_add_user_multi_roles( $value ) {
		if ( self::is_adding_user_with_no_edit_role() ) {
			return false;
		}
		return $value;
	}

	/**
	 * Hide the author email on the frontend if it's a placeholder email.
	 *
	 * @param bool $value Whether to show the author email.
	 */
	public static function should_display_author_email( $value ) {
		if ( is_author() || is_singular() ) { // Run on archive pages and single posts/pages.
			$author_id = get_the_author_meta( 'ID' );
			$user = get_userdata( $author_id );
			if ( $user && self::is_guest_author( $user ) && self::is_dummy_email_address( $user->user_email ) ) {
				return false;
			}
		}
		return $value;
	}
}
Guest_Contributor_Role::init();
