<?php
/**
 * Reader Activation.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Reader Activation Class.
 */
final class Reader_Activation {

	const AUTH_INTENTION_COOKIE = 'np_auth_intention';

	/**
	 * Reader user meta keys.
	 */
	const READER         = 'np_reader';
	const EMAIL_VERIFIED = 'np_reader_email_verified';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		if ( self::is_enabled() ) {
			\add_action( 'clear_auth_cookie', [ __CLASS__, 'clear_auth_intention_cookie' ] );
			\add_action( 'set_auth_cookie', [ __CLASS__, 'clear_auth_intention_cookie' ] );
			\add_filter( 'login_form_defaults', [ __CLASS__, 'add_auth_intention_to_login_form' ], 20 );
			\add_action( 'resetpass_form', [ __CLASS__, 'set_reader_verified' ] );
			\add_action( 'password_reset', [ __CLASS__, 'set_reader_verified' ] );
		}
	}

	/**
	 * Whether reader activation is enabled.
	 *
	 * @return bool True if reader activation is enabled.
	 */
	public static function is_enabled() {
		$is_enabled = defined( 'NEWSPACK_EXPERIMENTAL_READER_ACTIVATION' ) && NEWSPACK_EXPERIMENTAL_READER_ACTIVATION;

		/**
		 * Filters whether reader activation is enabled.
		 *
		 * @param bool $is_enabled Whether reader activation is enabled.
		 */
		return \apply_filters( 'newspack_reader_activation_enabled', $is_enabled );
	}

	/**
	 * Add auth intention email to login form defaults.
	 *
	 * @param array $defaults Login form defaults.
	 *
	 * @return array
	 */
	public static function add_auth_intention_to_login_form( $defaults ) {
		$email = self::get_auth_intention_value();
		if ( ! empty( $email ) ) {
			$defaults['label_username'] = __( 'Email address', 'newspack' );
			$defaults['value_username'] = $email;
		}
		return $defaults;
	}

	/**
	 * Clear the auth intention cookie.
	 */
	public static function clear_auth_intention_cookie() {
		/** This filter is documented in wp-includes/pluggable.php */
		if ( ! apply_filters( 'send_auth_cookies', true ) ) {
			return;
		}

		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
		setcookie( self::AUTH_INTENTION_COOKIE, ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Set the auth intention cookie.
	 *
	 * @param string $email Email address.
	 */
	public static function set_auth_intention_cookie( $email ) {
		/** This filter is documented in wp-includes/pluggable.php */
		if ( ! apply_filters( 'send_auth_cookies', true ) ) {
			return;
		}

		/**
		 * Filters the duration of the auth intention cookie expiration period.
		 *
		 * @param int    $length Duration of the expiration period in seconds.
		 * @param string $email  Email address.
		 */
		$expire = time() + \apply_filters( 'newspack_auth_intention_expiration', 30 * DAY_IN_SECONDS, $email );
		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
		setcookie( self::AUTH_INTENTION_COOKIE, $email, $expire, COOKIEPATH, COOKIE_DOMAIN, true );
	}

	/**
	 * Get the auth intention value.
	 *
	 * @return string|null Email address or null if not set.
	 */
	public static function get_auth_intention_value() {
		$email_address = null;
		if ( isset( $_COOKIE[ self::AUTH_INTENTION_COOKIE ] ) ) {
			// phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			$email_address = \sanitize_email( $_COOKIE[ self::AUTH_INTENTION_COOKIE ] );
		}
		/**
		 * Filters the session auth intention email address.
		 *
		 * @param string|null $email_address Email address or null if not set.
		 */
		return \apply_filters( 'newspack_auth_intention', $email_address );
	}

	/**
	 * Whether the user is a reader.
	 *
	 * @param \WP_User $user User object.
	 *
	 * @return bool Whether the user is a reader.
	 */
	public static function is_user_reader( $user ) {
		$is_reader = (bool) \get_user_meta( $user->ID, self::READER, true );

		if ( false === $is_reader ) {
			/**
			 * Filters the roles that can determine if a user is a reader.
			 *
			 * @param string[] $roles Array of user roles.
			 */
			$reader_roles = \apply_filters( 'newspack_reader_user_roles', [ 'subscriber', 'customer' ] );
			if ( ! empty( $reader_roles ) ) {
				$user_data = \get_userdata( $user->ID );
				$is_reader = ! empty( array_intersect( $reader_roles, $user_data->roles ) );
			}
		}

		/**
		 * Filters whether the user is a reader.
		 *
		 * @param bool     $is_reader Whether the user is a reader.
		 * @param \WP_User $user      User object.
		 */
		return \apply_filters( 'newspack_is_user_reader', $is_reader, $user );
	}

	/**
	 * Verify email address of a reader given the user.
	 *
	 * @param \WP_User $user User object.
	 *
	 * @return bool Whether the email address was verified.
	 */
	public static function set_reader_verified( $user ) {
		if ( ! $user ) {
			return false;
		}

		/** Should not verify email if user is not a reader. */
		if ( ! self::is_user_reader( $user ) ) {
			return false;
		}

		$verified = \get_user_meta( $user->ID, self::EMAIL_VERIFIED, true );
		if ( $verified ) {
			return true;
		}

		\update_user_meta( $user->ID, self::EMAIL_VERIFIED, true );

		return true;
	}

	/**
	 * Check if current reader has its email verified.
	 *
	 * @param \WP_User $user User object.
	 *
	 * @return bool|null Whether the email address is verified, null if invalid user.
	 */
	public static function is_reader_verified( $user ) {
		if ( ! $user ) {
			return null;
		}

		/** Should not verify email if user is not a reader. */
		if ( ! self::is_user_reader( $user ) ) {
			return null;
		}

		return (bool) \get_user_meta( $user->ID, self::EMAIL_VERIFIED, true );
	}

	/**
	 * Authenticate a reader session given its user ID.
	 *
	 * Warning: this method will only verify if the user is a reader in order to
	 * authenticate. It will not check for any credentials.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return \WP_User|\WP_Error The authenticated reader or WP_Error if authentication failed.
	 */
	public static function set_current_reader( $user_id ) {
		$user_id = \absint( $user_id );
		if ( empty( $user_id ) ) {
			return new \WP_Error( 'newspack_authenticate_invalid_user_id', __( 'Invalid user id.', 'newspack' ) );
		}

		$user = \get_user_by( 'id', $user_id );
		if ( ! $user || \is_wp_error( $user ) || ! self::is_user_reader( $user ) ) {
			return new \WP_Error( 'newspack_authenticate_invalid_user', __( 'Invalid user.', 'newspack' ) );
		}

		\wp_clear_auth_cookie();
		\wp_set_current_user( $user->ID );
		\wp_set_auth_cookie( $user->ID, true );
		\do_action( 'wp_login', $user->user_login, $user );

		return $user;
	}

	/**
	 * Register a reader given its email.
	 *
	 * Due to authentication or auth intention, this method should be used
	 * preferably on POST or API requests to avoid issues with caching.
	 *
	 * @param string $email        Email address.
	 * @param string $display_name Reader display name to be used on account creation.
	 * @param bool   $authenticate Whether to authenticate after registering. Default to true.
	 *
	 * @return int|false|\WP_Error The created user ID in case of registration, false if the user already exists, or a WP_Error object.
	 */
	public static function register_reader( $email, $display_name = '', $authenticate = true ) {
		if ( ! self::is_enabled() ) {
			return new \WP_Error( 'newspack_register_reader_disabled', __( 'Registration is disabled.', 'newspack' ) );
		}

		if ( \is_user_logged_in() ) {
			return new \WP_Error( 'newspack_register_reader_logged_in', __( 'Cannot register while logged in.', 'newspack' ) );
		}

		$email = \sanitize_email( $email );

		if ( empty( $email ) ) {
			return new \WP_Error( 'newspack_register_reader_empty_email', __( 'Please enter a valid email address.', 'newspack' ) );
		}

		self::set_auth_intention_cookie( $email );

		$existing_user = \get_user_by( 'email', $email );
		if ( \is_wp_error( $existing_user ) ) {
			return $existing_user;
		}

		$user_id = false;

		if ( $existing_user ) {
			Magic_Link::send_email( $existing_user );
		} else {
			/**
			 * Create new reader.
			 */
			if ( empty( $display_name ) ) {
				$display_name = explode( '@', $email, 2 )[0];
			}

			$random_password = \wp_generate_password();

			if ( function_exists( '\wc_create_new_customer' ) ) {
				/**
				 * Create WooCommerce Customer if possible.
				 *
				 * Email notification for WooCommerce is handled by the plugin.
				 */
				$user_id = \wc_create_new_customer( $email, $email, $random_password, [ 'display_name' => $display_name ] );
			} else {
				$user_id = \wp_insert_user(
					[
						'user_login'   => $email,
						'user_email'   => $email,
						'user_pass'    => $random_password,
						'display_name' => $display_name,
					]
				);
				\wp_new_user_notification( $user_id, null, 'user' );
			}

			if ( \is_wp_error( $user_id ) ) {
				return $user_id;
			}

			/** Add default reader related meta. */
			\update_user_meta( $user_id, self::READER, true );
			\update_user_meta( $user_id, self::EMAIL_VERIFIED, false );

			if ( $authenticate ) {
				self::set_current_reader( $user_id );
			}
		}

		/**
		 * Action after registering and authenticating a reader.
		 *
		 * @param string         $email         Email address.
		 * @param bool           $authenticate  Whether to authenticate after registering.
		 * @param false|int      $user_id       The created user id.
		 * @param false|\WP_User $existing_user The existing user object.
		 */
		\do_action( 'newspack_registered_reader', $email, $authenticate, $user_id, $existing_user );

		return $user_id;
	}
}
Reader_Activation::init();
