<?php
/**
 * Connection with WooCommerce's features.
 *
 * @package Newspack
 */

namespace Newspack;

use Newspack\Reader_Activation;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Connection with WooCommerce's "My Account" page.
 */
class WooCommerce_My_Account {
	const RESET_PASSWORD_URL_PARAM     = 'reset-password';
	const DELETE_ACCOUNT_URL_PARAM     = 'delete-account';
	const DELETE_ACCOUNT_FORM          = 'delete-account-form';
	const SEND_MAGIC_LINK_PARAM        = 'magic-link';
	const AFTER_ACCOUNT_DELETION_PARAM = 'account-deleted';

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_filter( 'woocommerce_account_menu_items', [ __CLASS__, 'my_account_menu_items' ], 1000 );
		\add_filter( 'woocommerce_billing_fields', [ __CLASS__, 'edit_address_required_fields' ] );

		// Reader Activation mods.
		if ( Reader_Activation::is_enabled() ) {
			\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
			\add_filter( 'wc_get_template', [ __CLASS__, 'wc_get_template' ], 10, 5 );
			\add_action( 'template_redirect', [ __CLASS__, 'handle_password_reset_request' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'handle_delete_account_request' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'handle_delete_account' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'handle_magic_link_request' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'redirect_to_account_details' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'edit_account_prevent_email_update' ] );
			\add_action( 'init', [ __CLASS__, 'restrict_account_content' ], 100 );
			\add_filter( 'woocommerce_save_account_details_required_fields', [ __CLASS__, 'remove_required_fields' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'verify_saved_account_details' ] );
			\add_action( 'logout_redirect', [ __CLASS__, 'add_param_after_logout' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'show_message_after_logout' ] );
		}
	}

	/**
	 * Enqueue front-end scripts.
	 */
	public static function enqueue_scripts() {
		if ( function_exists( 'is_account_page' ) && is_account_page() ) {
			\wp_enqueue_script(
				'my-account',
				\Newspack\Newspack::plugin_url() . '/dist/my-account.js',
				[],
				NEWSPACK_PLUGIN_VERSION,
				true
			);
			\wp_localize_script(
				'my-account',
				'newspack_my_account',
				[
					'labels' => [
						'cancel_subscription_message' => __( 'Are you sure you want to cancel this subscription?', 'newspack-plugin' ),
					],
				]
			);
			\wp_enqueue_style(
				'my-account',
				\Newspack\Newspack::plugin_url() . '/dist/my-account.css',
				[],
				NEWSPACK_PLUGIN_VERSION
			);
		}
	}

	/**
	 * Filter "My Account" items.
	 *
	 * @param array $items Items.
	 */
	public static function my_account_menu_items( $items ) {
		$default_disabled_items = [];

		// Rename 'Logout' action to 'Log out', for grammatical reasons.
		if ( isset( $items['customer-logout'] ) ) {
			$items['customer-logout'] = __( 'Log out', 'newspack-plugin' );
		}

		if ( Reader_Activation::is_enabled() ) {
			// If the reader hasn't verified their account, only show options to verify or log out.
			if ( ! self::is_user_verified() ) {
				$minimum_items = [ 'edit-account', 'customer-logout' ];
				foreach ( $items as $key => $label ) {
					if ( ! in_array( $key, $minimum_items, true ) ) {
						unset( $items[ $key ] );
					}
				}
				return $items;
			}

			$default_disabled_items = array_merge( $default_disabled_items, [ 'dashboard', 'members-area' ] );
			$customer_id            = \get_current_user_id();
			if ( class_exists( 'WC_Customer' ) ) {
				$ignored_fields   = [ 'first_name', 'last_name', 'email' ];
				$customer         = new \WC_Customer( $customer_id );
				$billing_address  = $customer->get_billing();
				$shipping_address = $customer->get_shipping();

				// We only want to show the Addresses menu item if the reader has address info (not first/last name or email).
				foreach ( $ignored_fields as $ignored_field ) {
					unset( $billing_address[ $ignored_field ] );
					unset( $shipping_address[ $ignored_field ] );
				}

				if ( empty( array_filter( $billing_address ) ) && empty( array_filter( $billing_address ) ) ) {
					$default_disabled_items[] = 'edit-address';
				}
			}
			if ( function_exists( 'wc_get_customer_available_downloads' ) ) {
				$customer_id           = \get_current_user_id();
				$wc_customer_downloads = \wc_get_customer_available_downloads( $customer_id );
				if ( empty( $wc_customer_downloads ) ) {
					$default_disabled_items[] = 'downloads';
				}
			}

			$disabled_wc_menu_items = \apply_filters( 'newspack_my_account_disabled_pages', $default_disabled_items );
			foreach ( $disabled_wc_menu_items as $key ) {
				if ( isset( $items[ $key ] ) ) {
					unset( $items[ $key ] );
				}
			}
		}

		return $items;
	}

	/**
	 * Handle password reset request.
	 */
	public static function handle_password_reset_request() {
		if ( ! \is_user_logged_in() ) {
			return;
		}

		$nonce = filter_input( INPUT_GET, self::RESET_PASSWORD_URL_PARAM, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce ) {
			return;
		}

		$is_error = false;
		if ( \wp_verify_nonce( $nonce, self::RESET_PASSWORD_URL_PARAM ) ) {
			$result  = \retrieve_password( \wp_get_current_user()->user_email );
			$message = __( 'Please check your email inbox for instructions on how to set a new password.', 'newspack-plugin' );
			if ( \is_wp_error( $result ) ) {
				Logger::error( 'Error resetting password: ' . $result->get_error_message() );
				$message  = $result->get_error_message();
				$is_error = true;
			}
		} else {
			$message  = __( 'Something went wrong.', 'newspack-plugin' );
			$is_error = true;
		}

		\wp_safe_redirect(
			\add_query_arg(
				[
					'message'  => $message,
					'is_error' => $is_error,
				],
				\remove_query_arg( self::RESET_PASSWORD_URL_PARAM )
			)
		);
		exit;
	}

	/**
	 * Handle delete account request.
	 */
	public static function handle_delete_account_request() {
		if ( ! \is_user_logged_in() ) {
			return;
		}

		$user_id = \get_current_user_id();
		$user    = \wp_get_current_user();
		if ( ! Reader_Activation::is_user_reader( $user ) ) {
			return;
		}

		$nonce = filter_input( INPUT_GET, self::DELETE_ACCOUNT_URL_PARAM, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $nonce || ! \wp_verify_nonce( $nonce, self::DELETE_ACCOUNT_URL_PARAM ) ) {
			return;
		}

		$token      = \wp_generate_password( 43, false, false );
		$form_nonce = \wp_create_nonce( self::DELETE_ACCOUNT_FORM );

		$url = \add_query_arg(
			[
				self::DELETE_ACCOUNT_FORM => $form_nonce,
				'token'                   => $token,
			],
			\wc_get_account_endpoint_url( 'edit-account' )
		);
		\set_transient( 'np_reader_account_delete_' . $user_id, $token, DAY_IN_SECONDS );

		$sent = Emails::send_email(
			Reader_Activation_Emails::EMAIL_TYPES['DELETE_ACCOUNT'],
			$user->user_email,
			[
				[
					'template' => '*DELETION_LINK*',
					'value'    => $url,
				],
			]
		);

		\wp_safe_redirect(
			\add_query_arg(
				[
					'message'  => $sent ? __( 'Please check your email inbox for instructions on how to delete your account.', 'newspack-plugin' ) : __( 'Something went wrong.', 'newspack-plugin' ),
					'is_error' => ! $sent,
				],
				\remove_query_arg( self::DELETE_ACCOUNT_URL_PARAM )
			)
		);
		exit;
	}

	/**
	 * Handle delete account confirmation.
	 */
	public static function handle_delete_account() {

		/** Make sure `wp_delete_user()` is available. */
		require_once ABSPATH . 'wp-admin/includes/user.php';

		if ( ! isset( $_POST[ self::DELETE_ACCOUNT_FORM ] ) ) {
			return;
		}

		$form_nonce = \sanitize_text_field( $_POST[ self::DELETE_ACCOUNT_FORM ] );
		if ( ! $form_nonce || ! \wp_verify_nonce( $form_nonce, self::DELETE_ACCOUNT_FORM ) ) {
			\wp_die( \esc_html__( 'Invalid request.', 'newspack-plugin' ) );
		}

		if ( ! isset( $_POST['confirm_delete'] ) ) {
			return;
		}

		if ( ! \is_user_logged_in() ) {
			return;
		}

		$user_id = \get_current_user_id();
		$user    = \wp_get_current_user();
		if ( ! Reader_Activation::is_user_reader( $user ) ) {
			return;
		}

		$token           = isset( $_POST['token'] ) ? \sanitize_text_field( $_POST['token'] ) : '';
		$transient_token = \get_transient( 'np_reader_account_delete_' . $user_id );
		if ( ! $token || ! $transient_token || $token !== $transient_token ) {
			\wp_die( \esc_html__( 'Invalid request.', 'newspack-plugin' ) );
		}
		\delete_transient( 'np_reader_account_delete_' . $user_id );

		\wp_delete_user( $user_id );
		\wp_safe_redirect( add_query_arg( self::AFTER_ACCOUNT_DELETION_PARAM, 1, \wc_get_account_endpoint_url( 'edit-account' ) ) );
		exit;
	}

	/**
	 * Handle magic link request.
	 */
	public static function handle_magic_link_request() {
		if ( ! \is_user_logged_in() ) {
			return;
		}
		$nonce = filter_input( INPUT_GET, self::SEND_MAGIC_LINK_PARAM, FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( $nonce ) {
			$is_error = false;
			if ( \wp_verify_nonce( $nonce, self::SEND_MAGIC_LINK_PARAM ) ) {
				$result  = Reader_Activation::send_verification_email( \wp_get_current_user() );
				$message = __( 'Please check your email inbox for a link to verify your account.', 'newspack-plugin' );
				if ( \is_wp_error( $result ) ) {
					Logger::error( 'Error sending verification email: ' . $result->get_error_message() );
					$message  = $result->get_error_message();
					$is_error = true;
				}
			} else {
				$message  = __( 'Something went wrong.', 'newspack-plugin' );
				$is_error = true;
			}
			wp_safe_redirect(
				\add_query_arg(
					[
						'message'  => $message,
						'is_error' => $is_error,
					],
					\remove_query_arg( self::SEND_MAGIC_LINK_PARAM )
				)
			);
			exit;
		}
	}

	/**
	 * Check if the user is logged in and verified.
	 */
	public static function is_user_verified() {
		// Don't lock access if Reader Activation features aren't enabled.
		if ( ! Reader_Activation::is_enabled() ) {
			return true;
		}
		// Don't lock access if the user is not a reader.
		if ( \is_user_logged_in() && ! Reader_Activation::is_user_reader( \wp_get_current_user(), true ) ) {
			return true;
		}

		return \is_user_logged_in() && Reader_Activation::is_reader_verified( \wp_get_current_user() );
	}

	/**
	 * Redirect to "Account details" if accessing "My Account" directly.
	 * Do not redirect if the request is a resubscribe request, as resubscribe
	 * requests do their own redirect to the cart/checkout page.
	 */
	public static function redirect_to_account_details() {
		$resubscribe_request = isset( $_REQUEST['resubscribe'] ) ? 'shop_subscription' === get_post_type( absint( $_REQUEST['resubscribe'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( \is_user_logged_in() && Reader_Activation::is_enabled() && function_exists( 'wc_get_page_permalink' ) && ! $resubscribe_request ) {
			global $wp;
			$current_url               = \home_url( $wp->request );
			$my_account_page_permalink = \wc_get_page_permalink( 'myaccount' );
			$logout_url                = \wc_get_account_endpoint_url( 'customer-logout' );
			if ( \trailingslashit( $current_url ) === \trailingslashit( $my_account_page_permalink ) ) {
				\wp_safe_redirect( \wc_get_account_endpoint_url( 'edit-account' ) );
				exit;
			}
		}
	}

	/**
	 * Remove WC's required fields.
	 *
	 * @param array $required_fields Required fields.
	 */
	public static function remove_required_fields( $required_fields ) {
		if ( Donations::is_platform_wc() ) {
			$newspack_required_fields = [
				'account_email'        => __( 'Email address', 'newspack-plugin' ),
				'account_display_name' => __( 'Display name', 'newspack-plugin' ),
			];

			/**
			 * Filters the fields required when editing account details in My Account.
			 *
			 * @param array $newspack_required_fields Required fields, keyed by field name.
			 */
			return \apply_filters( 'newspack_myaccount_required_fields', $newspack_required_fields );
		}
		return $required_fields;
	}

	/**
	 * Intercept account details saved by the reader in My Account.
	 */
	public static function verify_saved_account_details() {
		$action       = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$display_name = filter_input( INPUT_POST, 'account_display_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$email        = filter_input( INPUT_POST, 'account_email', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( empty( $action ) || 'save_account_details' !== $action || empty( $display_name ) || empty( $email ) ) {
			return;
		}

		$user_id = \get_current_user_id();
		if ( $user_id <= 0 ) {
			return;
		}

		$user = \get_user_by( 'id', $user_id );
		if ( ! Reader_Activation::is_user_reader( $user ) || $user->data->user_email !== $email ) {
			return false;
		}

		// If the reader has intentionally saved a display name we consider generic, mark it as such.
		if (
			Reader_Activation::generate_user_nicename( $email ) === $display_name || // New generated construction (URL-sanitized version of the email address minus domain).
			Reader_Activation::strip_email_domain( $email ) === $display_name // Legacy generated construction (just the email address minus domain).
		) {
			\update_user_meta( $user_id, Reader_Activation::READER_SAVED_GENERIC_DISPLAY_NAME, 1 );
		}
	}

	/**
	 * Ensure that only billing address fields enabled in Reader Revenue settings
	 * are required in My Account edit billing address page.
	 *
	 * @param array $fields Address fields.
	 * @return array Filtered address fields.
	 */
	public static function edit_address_required_fields( $fields ) {
		global $wp;

		if (
			! function_exists( 'is_account_page' ) ||
			! \is_account_page() || // Only on My Account page.
			! isset( $wp->query_vars['edit-address'] ) || // Only when editing address.
			'billing' !== $wp->query_vars['edit-address'] // Only when editing billing address.
			) {
			return $fields;
		}

		$required_fields = Donations::get_billing_fields();
		foreach ( $fields as $field_name => $field_config ) {
			if ( ! in_array( $field_name, $required_fields, true ) ) {
				$fields[ $field_name ]['required'] = false;
			}
		}

		return $fields;
	}

	/**
	 * WC's page templates hijacking.
	 *
	 * @param string $template      Template path.
	 * @param string $template_name Template name.
	 */
	public static function wc_get_template( $template, $template_name ) {
		switch ( $template_name ) {
			case 'myaccount/form-login.php':
				if ( isset( $_GET[ self::AFTER_ACCOUNT_DELETION_PARAM ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					return dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/reader-revenue/templates/myaccount-after-delete-account.php';
				}
				return $template;
			case 'myaccount/form-edit-account.php':
				if ( isset( $_GET[ self::DELETE_ACCOUNT_FORM ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					return dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/reader-revenue/templates/myaccount-delete-account.php';
				}
				return dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/reader-revenue/templates/myaccount-edit-account.php';
			default:
				return $template;
		}
	}

	/**
	 * Restrict account content for unverified readers.
	 */
	public static function restrict_account_content() {
		if ( defined( 'NEWSPACK_ALLOW_MY_ACCOUNT_ACCESS_WITHOUT_VERIFICATION' ) && NEWSPACK_ALLOW_MY_ACCOUNT_ACCESS_WITHOUT_VERIFICATION ) {
			return;
		}

		if ( \is_user_logged_in() && ! self::is_user_verified() ) {
			\remove_all_actions( 'woocommerce_account_content' );
			\add_action(
				'woocommerce_account_content',
				function() {
					include dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/reader-revenue/templates/myaccount-verify.php';
				}
			);
		}
	}

	/**
	 * Prevent updating email via Edit Account page.
	 */
	public static function edit_account_prevent_email_update() {
		if (
			empty( $_POST['account_email'] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
			|| ! \is_user_logged_in()
			|| ! Reader_Activation::is_enabled()
		) {
			return;
		}
		$_POST['account_email'] = \wp_get_current_user()->user_email;
	}

	/**
	 * Append a logout param after a reader logs out from My Account.
	 *
	 * @param string $redirect_to The redirect destination URL.
	 *
	 * @return string The filtered destination URL.
	 */
	public static function add_param_after_logout( $redirect_to ) {
		if ( ! function_exists( 'wc_get_page_permalink' ) ) {
			return;
		}

		if ( \wc_get_page_permalink( 'myaccount' ) === $redirect_to ) {
			$redirect_to = \add_query_arg(
				[ 'logged_out' => 1 ],
				$redirect_to
			);
		}

		return $redirect_to;
	}

	/**
	 * Show a logout success message to readers after logging out via My Account.
	 */
	public static function show_message_after_logout() {
		if ( isset( $_GET['logged_out'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			WooCommerce_Connection::add_wc_notice( __( 'You have successfully logged out.', 'newspack-plugin' ), 'success' );
		}
	}
}

WooCommerce_My_Account::init();
