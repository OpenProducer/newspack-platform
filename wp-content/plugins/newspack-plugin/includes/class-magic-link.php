<?php
/**
 * Newspack Magic Links functionality.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Magic Links class.
 */
final class Magic_Link {

	const ADMIN_ACTIONS = [
		'send'    => 'np_magic_link_send',
		'clear'   => 'np_magic_link_clear',
		'disable' => 'np_magic_link_disable',
		'enable'  => 'np_magic_link_enable',
	];

	const TOKENS_META   = 'np_magic_link_tokens';
	const DISABLED_META = 'np_magic_link_disabled';

	const AUTH_ACTION        = 'np_auth_link';
	const AUTH_ACTION_RESULT = 'np_auth_link_result';
	const COOKIE             = 'np_auth_link';

	const MAGIC_LINK_PLACEHOLDER = '%MAGIC_LINK_URL%';

	/**
	 * Current session secret.
	 *
	 * @var string
	 */
	private static $session_secret = '';

	/**
	 * Initialize hooks.
	 */
	public static function init() {

		/** Authentication hooks */
		\add_action( 'clear_auth_cookie', [ __CLASS__, 'clear_client_secret_cookie' ] );
		\add_action( 'set_auth_cookie', [ __CLASS__, 'clear_client_secret_cookie' ] );
		\add_action( 'template_redirect', [ __CLASS__, 'process_token_request' ] );

		/** Admin functionality */
		\add_action( 'init', [ __CLASS__, 'wp_cli' ] );
		\add_action( 'admin_init', [ __CLASS__, 'process_admin_action' ] );
		\add_filter( 'user_row_actions', [ __CLASS__, 'user_row_actions' ], 10, 2 );
		\add_action( 'edit_user_profile', [ __CLASS__, 'edit_user_profile' ] );

	}

	/**
	 * Whether a user can use magic links.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return bool Whether the user can use magic links.
	 */
	public static function can_magic_link( $user_id ) {
		if ( ! Reader_Activation::is_enabled() ) {
			return false;
		}

		$user = \get_user_by( 'id', $user_id );

		if ( ! $user || \is_wp_error( $user ) ) {
			return false;
		}

		if ( ! Reader_Activation::is_user_reader( $user ) ) {
			return false;
		}

		$can_magic_link = ! (bool) \get_user_meta( $user_id, self::DISABLED_META, true );

		/**
		 * Filters whether the user can use magic links.
		 *
		 * @param bool     $can_magic_link Whether the user can use magic links.
		 * @param \WP_User $user           User object.
		 */
		return \apply_filters( 'newspack_can_magic_link', $can_magic_link, $user );
	}

	/**
	 * Get magic link token expiration period.
	 *
	 * @return int Expiration in seconds.
	 */
	private static function get_token_expiration_period() {
		/**
		 * Filters the duration of the magic link token expiration period.
		 *
		 * @param int    $length Duration of the expiration period in seconds.
		 */
		return \apply_filters( 'newspack_magic_link_token_expiration', 30 * MINUTE_IN_SECONDS );
	}

	/**
	 * Clear client secret cookie.
	 */
	public static function clear_client_secret_cookie() {
		/** This filter is documented in wp-includes/pluggable.php */
		if ( ! apply_filters( 'send_auth_cookies', true ) ) {
			return;
		}

		if ( ! isset( $_COOKIE[ self::COOKIE ] ) ) {
			return;
		}

		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
		setcookie( self::COOKIE, ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Get locally stored secret to be used as part of the client hash.
	 *
	 * @param bool $reset Whether to generate a new secret.
	 */
	private static function get_client_secret( $reset = false ) {
		$secret = self::$session_secret;

		/** Fetch cookie if available. */
		if ( empty( $secret ) && isset( $_COOKIE[ self::COOKIE ] ) ) {
			// phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			$secret = \sanitize_text_field( \wp_unslash( $_COOKIE[ self::COOKIE ] ) );
		}

		/** Regenerate if empty or resetting. */
		if ( empty( $secret ) || true === $reset ) {
			$secret = \wp_generate_password( 43, false, false );
		}

		self::$session_secret = $secret;

		/** This filter is documented in wp-includes/pluggable.php */
		if ( \apply_filters( 'send_auth_cookies', true ) ) {
			/** Add an extra 5 minutes to the client secret cookie expiration. */
			$expiration = time() + self::get_token_expiration_period() + ( 5 * MINUTE_IN_SECONDS );

			// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
			setcookie( self::COOKIE, $secret, $expiration, COOKIEPATH, COOKIE_DOMAIN, true );
		}

		return $secret;
	}

	/**
	 * Get client hash for the current session, if self-served.
	 *
	 * @param \WP_User $user         User the client hash is being generated for.
	 * @param bool     $reset_secret Whether to reset the stored client secret.
	 *
	 * @return string|null Client hash or null if unable to generate one.
	 */
	private static function get_client_hash( $user, $reset_secret = false ) {
		/** Don't return client hash from CLI command */
		if ( defined( 'WP_CLI' ) ) {
			return null;
		}

		/** Return client hash only if it's self-served. */
		if ( \is_user_logged_in() && \get_current_user_id() !== $user->ID ) {
			return null;
		}

		$hash_args = [];

		/**
		 * Filters whether to use IP as hash argument.
		 *
		 * @param bool $use_ip
		 */
		if ( true === \apply_filters( 'newspack_magic_link_hash_use_ip', true ) ) {
			// phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__REMOTE_ADDR__
			if ( isset( $_SERVER['REMOTE_ADDR'] ) && ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
				// phpcs:ignore WordPressVIPMinimum.Variables.ServerVariables.UserControlledHeaders, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__REMOTE_ADDR__
				$hash_args['ip'] = \wp_unslash( $_SERVER['REMOTE_ADDR'] );
			}
		}

		/**
		 * Filters whether to use user agent as hash argument.
		 *
		 * @param bool $use_user_agent
		 */
		if ( true === \apply_filters( 'newspack_magic_link_hash_use_user_agent', false ) ) {
			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___SERVER__HTTP_USER_AGENT__
				$hash_args['user_agent'] = \wp_unslash( $_SERVER['HTTP_USER_AGENT'] );
			}
		}

		/**
		 * Filters whether to use a locally stored secret as a client hash argument.
		 *
		 * @param bool $use_secret
		 */
		if ( true === \apply_filters( 'newspack_magic_link_hash_use_secret', false ) ) {
			$hash_args['secret'] = self::get_client_secret( $reset_secret );
		}

		/**
		 * Filters the client hash arguments for the current session.
		 *
		 * @param string[] $hash_args Client hash arguments.
		 */
		$hash_args = \apply_filters( 'newspack_magic_link_client_hash_args', $hash_args );

		return ! empty( $hash_args ) ? \wp_hash( implode( '', $hash_args ) ) : null;
	}

	/**
	 * Clear all user tokens.
	 *
	 * @param \WP_User $user User to clear tokens for.
	 */
	public static function clear_user_tokens( $user ) {
		\delete_user_meta( $user->ID, self::TOKENS_META );

		/**
		 * Fires after all user tokens are cleared.
		 *
		 * @param \WP_User $user User for which tokens were cleared.
		 */
		do_action( 'newspack_magic_link_user_tokens_cleared', $user );
	}

	/**
	 * Generate magic link token.
	 *
	 * @param \WP_User $user User to generate the magic link token for.
	 *
	 * @return array|\WP_Error {
	 *   Magic link token data.
	 *
	 *   @type string $token  The token.
	 *   @type string $client Client hash.
	 *   @type string $time   Token creation time.
	 * }
	 */
	public static function generate_token( $user ) {
		if ( ! self::can_magic_link( $user->ID ) ) {
			return new \WP_Error( 'newspack_magic_link_invalid_user', __( 'Invalid user.', 'newspack' ) );
		}

		$now    = time();
		$tokens = \get_user_meta( $user->ID, self::TOKENS_META, true );
		if ( empty( $tokens ) ) {
			$tokens = [];
		}

		$expire = $now - self::get_token_expiration_period();
		if ( ! empty( $tokens ) ) {
			/** Limit maximum tokens to 5. */
			$tokens = array_slice( $tokens, -4, 4 );
			/** Clear expired tokens. */
			foreach ( $tokens as $index => $token_data ) {
				if ( $token_data['time'] < $expire ) {
					unset( $tokens[ $index ] );
				}
			}
			$tokens = array_values( $tokens );
		}

		/** Generate the new token. */
		$token       = \wp_generate_password( 60, false, false );
		$client_hash = self::get_client_hash( $user, true );
		$token_data  = [
			'token'  => $token,
			'client' => ! empty( $client_hash ) ? $client_hash : '',
			'time'   => $now,
		];
		$tokens[]    = $token_data;
		\update_user_meta( $user->ID, self::TOKENS_META, $tokens );
		return $token_data;
	}

	/**
	 * Generate a magic link.
	 *
	 * @param \WP_User $user User to generate the magic link for.
	 * @param string   $url  Destination url. Default is home_url().
	 *
	 * @return string|\WP_Error Magic link url or WP_Error if token generation failed.
	 */
	private static function generate_url( $user, $url = '' ) {
		$token_data = self::generate_token( $user );

		if ( \is_wp_error( $token_data ) ) {
			return $token_data;
		}

		return \add_query_arg(
			[
				'action' => self::AUTH_ACTION,
				'email'  => urlencode( $user->user_email ),
				'token'  => $token_data['token'],
			],
			! empty( $url ) ? $url : \home_url()
		);
	}

	/**
	 * Get a magic link email arguments given a user.
	 *
	 * @param \WP_User $user        User to generate the magic link for.
	 * @param string   $redirect_to Which page to redirect the reader after authenticating.
	 * @param string   $subject     The subject of the email.
	 * @param string   $message     Message to show in body of email.
	 *
	 * @return array|\WP_Error $email Email arguments or error. {
	 *   Used to build wp_mail().
	 *
	 *   @type string $to      The intended recipient - New user email address.
	 *   @type string $subject The subject of the email.
	 *   @type string $message The body of the email.
	 *   @type string $headers The headers of the email.
	 * }
	 */
	public static function generate_email( $user, $redirect_to = '', $subject, $message = '' ) {
		if ( ! self::can_magic_link( $user->ID ) ) {
			return new \WP_Error( 'newspack_magic_link_invalid_user', __( 'Invalid user.', 'newspack' ) );
		}

		$magic_link_url = self::generate_url( $user, $redirect_to );

		if ( \is_wp_error( $magic_link_url ) ) {
			return $magic_link_url;
		}

		$blogname = \wp_specialchars_decode( \get_option( 'blogname' ), ENT_QUOTES );

		$switched_locale = \switch_to_locale( \get_user_locale( $user ) );

		if ( empty( $subject ) ) {
			$subject = __( 'Authentication link', 'newspack' );
		}

		if ( empty( $message ) ) {
			/* translators: %s: Site title. */
			$message  = sprintf( __( 'Welcome back to %s!', 'newspack' ), $blogname ) . "\r\n\r\n";
			$message .= __( 'Log into your account by visiting the following URL:', 'newspack' ) . "\r\n\r\n";
		}

		// If the message contains a magic link placeholder, populate the magic link there.
		if ( false !== strpos( $message, self::MAGIC_LINK_PLACEHOLDER ) ) {
			$message = str_replace( self::MAGIC_LINK_PLACEHOLDER, $magic_link_url, $message );
		} else {
			// Otherwise, append it to the end of the message.
			$message .= $magic_link_url . "\r\n";
		}

		$email = [
			'to'      => $user->user_email,
			/* translators: %s Site title. */
			'subject' => __( '[%s] ', 'newspack' ) . $subject,
			'message' => $message,
			'headers' => [
				sprintf(
					'From: %1$s <%2$s>',
					Reader_Activation::get_from_name(),
					Reader_Activation::get_from_email()
				),
			],
		];

		if ( $switched_locale ) {
			\restore_previous_locale();
		}

		/**
		 * Filters the magic link email.
		 *
		 * @param array    $email          Email arguments. {
		 *   Used to build wp_mail().
		 *
		 *   @type string $to      The intended recipient - New user email address.
		 *   @type string $subject The subject of the email.
		 *   @type string $message The body of the email.
		 *   @type string $headers The headers of the email.
		 * }
		 * @param \WP_User $user           User to send the magic link to.
		 * @param string   $magic_link_url Magic link url.
		 */
		return \apply_filters( 'newspack_magic_link_email', $email, $user, $magic_link_url );
	}

	/**
	 * Send magic link email to reader.
	 *
	 * @param \WP_User $user        User to send the magic link to.
	 * @param string   $redirect_to Which page to redirect the reader after authenticating.
	 * @param string   $subject     Subject linke for the email.
	 * @param string   $message     Message to show in body of email.
	 *
	 * @return bool|\WP_Error Whether the email was sent or WP_Error if sending failed.
	 */
	public static function send_email( $user, $redirect_to = '', $subject = '', $message = '' ) {
		$email = self::generate_email( $user, $redirect_to, $subject, $message );

		if ( \is_wp_error( $email ) ) {
			return $email;
		}

		$blogname = \wp_specialchars_decode( \get_option( 'blogname' ), ENT_QUOTES );

		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_mail_wp_mail
		$sent = \wp_mail(
			$email['to'],
			\wp_specialchars_decode( sprintf( $email['subject'], $blogname ) ),
			$email['message'],
			$email['headers']
		);
		Logger::log( 'Sending magic link to ' . $email['to'] );

		return $sent;
	}

	/**
	 * Verify and returns the valid token given a user, token and client.
	 *
	 * This method cleans up expired tokens and returns the token data for
	 * immediate use.
	 *
	 * @param int    $user_id User ID.
	 * @param string $client  Client hash.
	 * @param string $token   Token to verify.
	 *
	 * @return array|\WP_Error {
	 *   Token data.
	 *
	 *   @type string $token  The token.
	 *   @type string $client Client hash.
	 *   @type string $time   Token creation time.
	 * }
	 */
	public static function validate_token( $user_id, $client, $token ) {
		$errors = new \WP_Error();
		$user   = \get_user_by( 'id', $user_id );

		if ( ! $user ) {
			$errors->add( 'invalid_user', __( 'User not found.', 'newspack' ) );
		} elseif ( ! self::can_magic_link( $user->ID ) ) {
			$errors->add( 'invalid_user_type', __( 'Not allowed for this user', 'newspack' ) );
		} else {
			$tokens = \get_user_meta( $user->ID, self::TOKENS_META, true );
			if ( empty( $tokens ) || empty( $token ) ) {
				$errors->add( 'invalid_token', __( 'Invalid token.', 'newspack' ) );
			}
		}

		$valid_token = false;

		if ( $errors->has_errors() ) {
			return $errors;
		}

		$expire = time() - self::get_token_expiration_period();

		foreach ( $tokens as $index => $token_data ) {
			if ( $token_data['time'] < $expire ) {
				unset( $tokens[ $index ] );

			} elseif ( $token_data['token'] === $token ) {
				$valid_token = $token_data;

				/** If token data has a client hash, it must be equal to the user's. */
				if ( ! empty( $token_data['client'] ) && $token_data['client'] !== $client ) {
					$errors->add( 'invalid_client', __( 'Invalid client.', 'newspack' ) );
				}

				unset( $tokens[ $index ] );
				break;
			}
		}

		if ( empty( $valid_token ) ) {
			$errors->add( 'invalid_token', __( 'Invalid token.', 'newspack' ) );
		}
		self::clear_client_secret_cookie();

		$tokens = array_values( $tokens );
		\update_user_meta( $user->ID, self::TOKENS_META, $tokens );

		return $errors->has_errors() ? $errors : $valid_token;
	}

	/**
	 * Handle a reader authentication attempt using magic link token.
	 *
	 * @param int    $user_id User ID.
	 * @param string $token   Token to authenticate.
	 *
	 * @return bool|\WP_Error Whether the user was authenticated or WP_Error.
	 */
	private static function authenticate( $user_id, $token ) {
		/** Refresh reader session if same reader is already authenticated. */
		if ( \is_user_logged_in() && \get_current_user_id() !== $user_id ) {
			return false;
		}

		$user = \get_user_by( 'id', $user_id );

		if ( ! $user ) {
			return new \WP_Error( 'invalid_user', __( 'User not found.', 'newspack' ) );
		}

		$client_hash = self::get_client_hash( $user );
		$token_data  = self::validate_token( $user_id, $client_hash, $token );

		if ( \is_wp_error( $token_data ) ) {
			return $token_data;
		}

		if ( empty( $token_data ) ) {
			return false;
		}

		// Authenticate the reader.
		Reader_Activation::set_current_reader( $user->ID );

		/**
		 * Fires after a reader has been authenticated via magic link.
		 *
		 * @param \WP_User $user User that has been authenticated.
		 */
		do_action( 'newspack_magic_link_authenticated', $user );

		return true;
	}

	/**
	 * Process magic link token from request.
	 */
	public static function process_token_request() {
		if ( ! Reader_Activation::is_enabled() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET[ self::AUTH_ACTION_RESULT ] ) && 0 === \absint( $_GET[ self::AUTH_ACTION_RESULT ] ) ) {
			\add_action(
				'before_header',
				function () {
					?>
					<style>
						.newspack-magic-link-error {
							text-align: center;
							padding: 1em;
							font-size: 0.7em;
							border-bottom: 1px solid var( --wc-red );
						}
					</style>
					<div class="newspack-magic-link-error">
						<?php \esc_html_e( 'We were not able to authenticate your account through this link. Please generate a new one.', 'newspack' ); ?>
					</div>
					<?php
				},
				1
			);
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['action'] ) || self::AUTH_ACTION !== $_GET['action'] ) {
			return;
		}

		$errored = false;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['token'] ) || ! isset( $_GET['email'] ) ) {
			$errored = true;
		}

		if ( ! $errored ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$email = \sanitize_email( $_GET['email'] );
			if ( $email ) {
				$user = \get_user_by( 'email', $email );
				if ( ! $user ) {
					$errored = true;
				}
			} else {
				$errored = true;
			}
		}

		$authenticated = false;

		if ( ! $errored ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$token         = \sanitize_text_field( \wp_unslash( $_GET['token'] ) );
			$authenticated = self::authenticate( $user->ID, $token );
		}

		\wp_safe_redirect(
			\add_query_arg(
				[ self::AUTH_ACTION_RESULT => true === $authenticated ? '1' : '0' ],
				\remove_query_arg( [ 'action', 'email', 'token' ] )
			)
		);
		exit;
	}

	/**
	 * WP CLI Commands.
	 */
	public static function wp_cli() {
		if ( ! defined( 'WP_CLI' ) ) {
			return;
		}
		if ( ! Reader_Activation::is_enabled() ) {
			return;
		}

		/**
		 * Sends a magic link to a reader.
		 *
		 * ## OPTIONS
		 *
		 * <email_or_id>
		 * : The email address or user ID of the reader.
		 *
		 * ## EXAMPLES
		 *
		 *     wp newspack magic-link send 12
		 *     wp newspack magic-link send john@doe.com
		 *
		 * @when after_wp_load
		 */
		$send = function( $args, $assoc_args ) {
			if ( ! isset( $args[0] ) ) {
				\WP_CLI::error( 'Please provide a user email or ID.' );
			}
			$id_or_email = $args[0];

			if ( \absint( $id_or_email ) ) {
				$user = \get_user_by( 'id', $id_or_email );
			} else {
				$user = \get_user_by( 'email', $id_or_email );
			}

			if ( ! $user || \is_wp_error( $user ) ) {
				\WP_CLI::error( __( 'User not found.', 'newspack' ) );
			}

			$result = self::send_email( $user );

			if ( \is_wp_error( $result ) ) {
				\WP_CLI::error( $result->get_error_message() );
			}

			// translators: %s is the email address of the user.
			\WP_CLI::success( sprintf( __( 'Email sent to %s.', 'newspack' ), $user->user_email ) );
		};
		\WP_CLI::add_command(
			'newspack magic-link send',
			$send,
			[
				'shortdesc' => __( 'Send a magic link to a reader.', 'newspack' ),
			]
		);
	}

	/**
	 * Get url for an admin action.
	 *
	 * @param string $action  Which admin action get the URL for.
	 * @param int    $user_id User to get the URL for.
	 *
	 * @return string Admin URL to perform an admin action.
	 */
	private static function get_admin_action_url( $action, $user_id ) {
		if ( ! \is_admin() ) {
			return '';
		}
		if ( ! isset( self::ADMIN_ACTIONS[ $action ] ) ) {
			return '';
		}
		$admin_action = self::ADMIN_ACTIONS[ $action ];
		return \add_query_arg(
			[
				'action'   => $admin_action,
				'uid'      => $user_id,
				'_wpnonce' => \wp_create_nonce( $admin_action ),
			]
		);
	}

	/**
	 * Adds magic link send action to user row actions.
	 *
	 * @param string[] $actions User row actions.
	 * @param \WP_User $user    User object.
	 *
	 * @return string[] User row actions.
	 */
	public static function user_row_actions( $actions, $user ) {
		if ( ! Reader_Activation::is_enabled() ) {
			return $actions;
		}
		if ( self::can_magic_link( $user->ID ) && \current_user_can( 'edit_user', $user->ID ) ) {
			$url                                 = self::get_admin_action_url( 'send', $user->ID );
			$actions['newspack-magic-link-send'] = '<a href="' . $url . '">' . \esc_html__( 'Send authentication link', 'newspack' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Process admin action request.
	 */
	public static function process_admin_action() {
		if ( ! Reader_Activation::is_enabled() ) {
			return;
		}

		$actions = self::ADMIN_ACTIONS;

		/** Add notice if admin action was successful. */
		if ( isset( $_GET['update'] ) && in_array( $_GET['update'], $actions, true ) ) {
			$update  = \sanitize_text_field( \wp_unslash( $_GET['update'] ) );
			$message = '';
			switch ( $update ) {
				case $actions['send']:
					$message = __( 'Authentication link sent.', 'newspack' );
					break;
				case $actions['clear']:
					$message = __( 'All authentication link tokens were removed.', 'newspack' );
					break;
				case $actions['disable']:
					$message = __( 'Authentication links are now disabled.', 'newspack' );
					break;
				case $actions['enable']:
					$message = __( 'Authentication links are now enabled.', 'newspack' );
					break;
			}
			if ( ! empty( $message ) ) {
				\add_action(
					'admin_notices',
					function() use ( $message ) {
						?>
						<div id="message" class="updated notice is-dismissible"><p><?php echo \esc_html( $message ); ?></p></div>
						<?php
					}
				);
			}
		}

		if ( ! isset( $_GET['action'] ) || ! in_array( $_GET['action'], $actions, true ) ) {
			return;
		}

		$action = \sanitize_text_field( \wp_unslash( $_GET['action'] ) );

		if ( ! isset( $_GET['uid'] ) ) {
			\wp_die( \esc_html__( 'Invalid request.', 'newspack' ) );
		}

		if ( ! \check_admin_referer( $action ) ) {
			\wp_die( \esc_html__( 'Invalid request.', 'newspack' ) );
		}

		$user_id = \absint( \wp_unslash( $_GET['uid'] ) );

		if ( ! \current_user_can( 'edit_user', $user_id ) ) {
			\wp_die( \esc_html__( 'You do not have permission to do that.', 'newspack' ) );
		}

		$user = \get_user_by( 'id', $user_id );

		if ( ! $user || \is_wp_error( $user ) ) {
			\wp_die( \esc_html__( 'User not found.', 'newspack' ) );
		}

		switch ( $action ) {
			case $actions['send']:
				$result = self::send_email( $user );
				if ( \is_wp_error( $result ) ) {
					\wp_die( $result ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				break;
			case $actions['clear']:
				self::clear_user_tokens( $user );
				break;
			case $actions['disable']:
				self::clear_user_tokens( $user );
				\update_user_meta( $user_id, self::DISABLED_META, true );
				break;
			case $actions['enable']:
				\delete_user_meta( $user_id, self::DISABLED_META );
				break;
		}

		$redirect = \add_query_arg( [ 'update' => $action ], \remove_query_arg( [ 'action', 'uid', '_wpnonce' ] ) );
		\wp_safe_redirect( $redirect );
		exit;
	}

	/**
	 * Magic link management for the user profile editor page.
	 *
	 * @param WP_User $user The current WP_User object.
	 */
	public static function edit_user_profile( $user ) {
		if ( ! Reader_Activation::is_enabled() ) {
			return;
		}

		if ( ! Reader_Activation::is_user_reader( $user ) ) {
			return;
		}

		$disabled = (bool) \get_user_meta( $user->ID, self::DISABLED_META, true );
		?>
		<div class="newspack-magic-link-management">
			<h2><?php _e( 'Passwordless Authentication Management', 'newspack' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr id="newspack-magic-link-support">
					<th><label><?php _e( 'Authentication Link Support', 'newspack' ); ?></label></th>
					<td>
						<?php if ( $disabled ) : ?>
							<a class="button" href="<?php echo \esc_url( self::get_admin_action_url( 'enable', $user->ID ) ); ?>"><?php _e( 'Enable Authentication Links' ); ?></a>
						<?php else : ?>
							<a class="button" href="<?php echo \esc_url( self::get_admin_action_url( 'disable', $user->ID ) ); ?>"><?php _e( 'Disable Authentication Links' ); ?></a>
						<?php endif; ?>
						<p class="description">
								<?php
								printf(
									/* translators: %1$s: Disabled or enabled. %2$s: User's display name. */
									\esc_html__( 'Authentication links support is currently %1$s for %2$s.', 'newspack' ),
									$disabled ? \esc_html__( 'disabled', 'newspack' ) : \esc_html__( 'enabled', 'newspack' ),
									\esc_html( $user->display_name )
								);
								?>
							</p>
					</td>
				</tr>
				<?php if ( ! $disabled ) : ?>
					<tr id="newspack-magic-link-send">
						<th><label><?php _e( 'Send Authentication Link', 'newspack' ); ?></label></th>
						<td>
							<a class="button" href="<?php echo \esc_url( self::get_admin_action_url( 'send', $user->ID ) ); ?>"><?php _e( 'Send Authentication Link' ); ?></a>
							<p class="description">
								<?php
								printf(
									/* translators: %1$s: User's display name. %2$d is the expiration period in minutes. */
									\esc_html__( 'Generate and send a new link to %1$s, which will authenticate them instantly. The link will be valid for %2$d minutes after its creation.', 'newspack' ),
									\esc_html( $user->display_name ),
									\esc_html( \absint( self::get_token_expiration_period() ) / MINUTE_IN_SECONDS )
								);
								?>
							</p>
						</td>
					</tr>
					<tr id="newspack-magic-link-clear">
						<th><label><?php _e( 'Clear All Tokens', 'newspack' ); ?></label></th>
						<td>
							<a class="button" href="<?php echo \esc_url( self::get_admin_action_url( 'clear', $user->ID ) ); ?>"><?php _e( 'Clear All Tokens' ); ?></a>
							<p class="description">
								<?php
								printf(
									/* translators: %s: User's display name. */
									\esc_html__( 'Clear all existing authentication link tokens for %s.', 'newspack' ),
									\esc_html( $user->display_name )
								);
								?>
							</p>
						</td>
					</tr>
				<?php endif; ?>
			</table>
		</div>
		<?php
	}
}
Magic_Link::init();
