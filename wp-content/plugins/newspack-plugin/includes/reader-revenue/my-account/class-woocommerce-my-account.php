<?php
/**
 * Connection with WooCommerce's features.
 *
 * @package Newspack
 */

namespace Newspack;

use Newspack\Reader_Activation;
use Newspack\Reader_Activation\Sync\Metadata;
use Newspack\Reader_Activation\ESP_Sync;
use Newspack\Stripe_Connection;
use Newspack\WooCommerce_Connection;

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
	const CANCEL_EMAIL_CHANGE_PARAM    = 'cancel-email-change';
	const VERIFY_EMAIL_CHANGE_PARAM    = 'verify-email-change';
	const PENDING_EMAIL_CHANGE_META    = 'newspack_pending_email_change';
	const ALLOWED_PARAMS               = [
		self::RESET_PASSWORD_URL_PARAM,
		self::DELETE_ACCOUNT_URL_PARAM,
		self::SEND_MAGIC_LINK_PARAM,
		self::AFTER_ACCOUNT_DELETION_PARAM,
		self::CANCEL_EMAIL_CHANGE_PARAM,
		self::VERIFY_EMAIL_CHANGE_PARAM,
	];

	/**
	 * Cron hook for syncing email change with ESP.
	 */
	const SYNC_ESP_EMAIL_CHANGE_CRON_HOOK = 'newspack_esp_sync_email_change';

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		\add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
		\add_filter( 'woocommerce_account_menu_items', [ __CLASS__, 'my_account_menu_items' ], 1000 );
		\add_filter( 'woocommerce_default_address_fields', [ __CLASS__, 'required_address_fields' ] );
		\add_filter( 'woocommerce_billing_fields', [ __CLASS__, 'required_address_fields' ] );
		\add_filter( 'woocommerce_get_checkout_url', [ __CLASS__, 'get_checkout_url' ] );
		\add_filter( 'woocommerce_get_checkout_payment_url', [ __CLASS__, 'get_checkout_url' ] );
		\add_filter( 'wc_stripe_update_subs_payment_method_card_statuses', [ __CLASS__, 'update_payment_methods_for_all_subs' ] );

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
			\add_action( 'woocommerce_save_account_details', [ __CLASS__, 'handle_email_change_request' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'handle_cancel_email_change' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'handle_verify_email_change' ] );
			\add_filter( 'send_email_change_email', '__return_false' );
			\add_action( 'init', [ __CLASS__, 'restrict_account_content' ], 100 );
			\add_filter( 'woocommerce_save_account_details_required_fields', [ __CLASS__, 'remove_required_fields' ] );
			\add_action( 'template_redirect', [ __CLASS__, 'verify_saved_account_details' ] );
			\add_action( 'logout_redirect', [ __CLASS__, 'redirect_to_home_after_logout' ] );
			\add_action( 'woocommerce_account_subscriptions_endpoint', [ __CLASS__, 'append_membership_table' ], 11 );
			\add_filter( 'wcs_my_account_redirect_to_single_subscription', [ __CLASS__, 'redirect_to_single_subscription' ] );
			\add_filter( 'wc_memberships_members_area_my-memberships_actions', [ __CLASS__, 'hide_cancel_button_from_memberships_table' ] );
			\add_filter( 'wc_memberships_my_memberships_column_names', [ __CLASS__, 'remove_next_bill_on' ], 21 );
			\add_action( 'profile_update', [ __CLASS__, 'handle_admin_email_change_request' ], 10, 3 );
			\add_action( self::SYNC_ESP_EMAIL_CHANGE_CRON_HOOK, [ __CLASS__, 'sync_email_change_with_esp' ], 10, 3 );
		}
	}

	/**
	 * Register routes.
	 */
	public static function register_routes() {
		\register_rest_route(
			NEWSPACK_API_NAMESPACE,
			'/check-rate',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'api_check_rate_limit' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * REST API handler for rate limit check.
	 */
	public static function api_check_rate_limit() {
		$is_rate_limited = WooCommerce_Connection::rate_limit_by_user( 'add_payment_method', __( 'Please wait a moment before trying to add a new payment method.', 'newspack-plugin' ), true );
		$response        = [ 'success' => false ];
		if ( ! \is_wp_error( $is_rate_limited ) && ! $is_rate_limited ) {
			$response['success'] = true;
		}
		if ( \is_wp_error( $is_rate_limited ) ) {
			$response['error'] = $is_rate_limited->get_error_message();
		}
		return new \WP_REST_Response( $response );
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
					'labels'            => [
						'cancel_subscription_message' => __( 'Are you sure you want to cancel this subscription?', 'newspack-plugin' ),
					],
					'rest_url'          => get_rest_url(),
					'should_rate_limit' => WooCommerce_Connection::rate_limiting_enabled(),
					'nonce'             => wp_create_nonce( 'wp_rest' ),
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

				if ( empty( array_filter( $billing_address ) ) && empty( array_filter( $shipping_address ) ) ) {
					$default_disabled_items[] = 'edit-address';
				}

				// Hide Orders and Payment Methods if the reader has no orders and no subscriptions.
				if ( ! $customer->get_is_paying_customer() && ( function_exists( 'wcs_get_users_subscriptions' ) && empty( \wcs_get_users_subscriptions( $customer_id ) ) ) ) {
					$default_disabled_items[] = 'orders';
					$default_disabled_items[] = 'payment-methods';
				}
			}
			if ( function_exists( 'wc_get_customer_available_downloads' ) ) {
				$wc_customer_downloads = \wc_get_customer_available_downloads( $customer_id );
				if ( empty( $wc_customer_downloads ) ) {
					$default_disabled_items[] = 'downloads';
				}
			}
			if ( function_exists( 'wcs_user_has_subscription' ) && ! \wcs_user_has_subscription( $customer_id ) ) {
				$default_disabled_items[] = 'subscriptions';
			}

			$disabled_wc_menu_items = \apply_filters( 'newspack_my_account_disabled_pages', $default_disabled_items );
			foreach ( $disabled_wc_menu_items as $key ) {
				if ( isset( $items[ $key ] ) ) {
					unset( $items[ $key ] );
				}
			}

			// Move "Account Details" and "S"ubscriptions" to the top of the menu.
			if ( isset( $items['subscriptions'] ) ) {
				$items = [ 'subscriptions' => $items['subscriptions'] ] + $items;
			}
			$items = [ 'edit-account' => $items['edit-account'] ] + $items;
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

		/**
		 * Fires before the account deletion email is sent.
		 *
		 * @param int $user_id The user ID of the account being deleted.
		 */
		do_action( 'newspack_before_delete_account', $user_id );

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
	 * Do not redirect if the request is a resubscribe or renewal request, as
	 * these requests do their own redirect to the cart/checkout page.
	 * Do not redirect if this request is a membership cancellation.
	 */
	public static function redirect_to_account_details() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$is_resubscribe_request       = isset( $_REQUEST['resubscribe'] );
		$is_renewal_request           = isset( $_REQUEST['subscription_renewal'] );
		$is_cancel_membership_request = isset( $_REQUEST['cancel_membership'] );
		$is_checkout_request          = isset( $_REQUEST['my_account_checkout'] );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if (
			\is_user_logged_in() &&
			Reader_Activation::is_enabled() &&
			function_exists( 'wc_get_page_permalink' ) &&
			! $is_resubscribe_request &&
			! $is_renewal_request &&
			! $is_cancel_membership_request &&
			! $is_checkout_request &&
			! self::is_myaccount_url()
		) {
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
	 * Detect if the current checkout page is coming from a My Account referrer.
	 *
	 * @return bool True if the current checkout page is coming from a My Account referrer, false otherwise.
	 */
	public static function is_from_my_account() {
		// If we're in My Account.
		if ( did_action( 'wp' ) && function_exists( 'is_account_page' ) && \is_account_page() ) {
			return true;
		}

		// If we have an `is_my_account` param in POST or GET.
		$is_my_account_param = rest_sanitize_boolean( $_REQUEST['my_account_checkout'] ?? false ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( $is_my_account_param ) {
			return true;
		}

		// If the referrer URL had a `my_account_checkout` param.
		$referrer = \wp_get_referer();
		if ( $referrer ) {
			$referrer_query = \wp_parse_url( $referrer, PHP_URL_QUERY );
			\wp_parse_str( $referrer_query, $referrer_query_params );
			if ( ! empty( $referrer_query_params['my_account_checkout'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * On My Account pages, append a query param to the checkout URL to indicate the user is coming from My Account.
	 *
	 * @param string $url Checkout URL.
	 *
	 * @return string
	 */
	public static function get_checkout_url( $url ) {
		if ( self::is_from_my_account() ) {
			return \add_query_arg(
				[
					'my_account_checkout' => 1,
				],
				$url
			);
		}
		return $url;
	}

	/**
	 * Ensure that only billing address fields enabled in Reader Revenue settings are required.
	 *
	 * @param array $fields Billing fields.
	 *
	 * @return array Filtered billing fields.
	 */
	public static function get_required_fields( $fields ) {
		$billing_fields = apply_filters( 'newspack_blocks_donate_billing_fields_keys', [] );
		if ( empty( $billing_fields ) ) {
			return $fields;
		}

		foreach ( $fields as $field_name => $field_config ) {
			if (
				! in_array( $field_name, $billing_fields, true ) &&
				! in_array( 'billing_' . $field_name, $billing_fields, true ) &&
				is_array( $field_config )
			) {
				$field_config['required'] = false;
				$fields[ $field_name ] = $field_config;
			}
		}

		// Add a hidden field so we can pass this onto subsequent pages in the Checkout flow.
		if ( ! isset( $fields['my_account_checkout'] ) ) {
			$fields['my_account_checkout'] = [
				'type'    => 'hidden',
				'default' => 1,
			];
		}

		return $fields;
	}

	/**
	 * Ensure that only billing address fields enabled in Reader Revenue settings
	 * are required in My Account edit billing address page.
	 *
	 * @param array $fields Address fields.
	 * @return array Filtered address fields.
	 */
	public static function required_address_fields( $fields ) {
		global $wp;

		if (
			self::is_from_my_account() && // Only when coming from My Account.
			(
				( function_exists( 'is_checkout' ) && is_checkout() ) || // If on the checkout page.
				( isset( $wp->query_vars['edit-address'] ) && 'billing' === $wp->query_vars['edit-address'] ) // If editing billing address.
			)
		) {
			$fields = self::get_required_fields( $fields );
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
	 * Modify redurect url to home after a reader logs out from My Account.
	 *
	 * @param string $redirect_to The redirect destination URL.
	 *
	 * @return string The filtered destination URL.
	 */
	public static function redirect_to_home_after_logout( $redirect_to ) {
		if ( ! function_exists( 'wc_get_page_permalink' ) ) {
			return;
		}

		if ( \wc_get_page_permalink( 'myaccount' ) === $redirect_to ) {
			$redirect_to = \get_home_url();
		}

		return $redirect_to;
	}

	/**
	 * Check if a reader has memberships that aren't associated with subscriptions.
	 *
	 * @return array
	 */
	public static function get_memberships_without_subs() {
		if ( function_exists( 'wc_memberships_get_user_active_memberships' ) ) {
			$customer_id              = \get_current_user_id();
			$memberships_info         = \wc_memberships_get_user_active_memberships( $customer_id );
			$memberships_without_subs = [];

			// Create an array of active memberships without active subscriptions.
			if ( function_exists( 'wc_memberships_has_subscription_product_granted_access' ) ) {
				foreach ( $memberships_info as $membership ) {
					if ( ! \wc_memberships_has_subscription_product_granted_access( $membership ) ) {
						$memberships_without_subs[] = $membership;
					}
				}
			}

			return $memberships_without_subs;
		}
	}

	/**
	 * Optionally append a table of active memberships without subscriptions on the My Account Subscriptions tab.
	 */
	public static function append_membership_table() {
		// If this option is not enabled, stop.
		if ( ! Memberships::get_show_on_subscription_tab_setting() ) {
			return;
		}

		$memberships_without_subs = self::get_memberships_without_subs();

		// If there are active memberships without subscriptions, present them in a table.
		if ( $memberships_without_subs ) {
			echo '<div class="woocommerce-memberships-without-subs">';
			echo '<h2>' . esc_html__( 'Active Memberships', 'newspack-plugin' ) . '</h2>';
			echo '<p>' . esc_html__( 'These memberships are active, but don\'t have an associated subscription. They will need to be manually renewed when they expire.', 'newspack-plugin' ) . '</p>';
			wc_get_template(
				'myaccount/my-memberships.php',
				array(
					'customer_memberships' => $memberships_without_subs,
					'user_id'              => \get_current_user_id(),
				)
			);
			echo '</div>';
		}
	}

	/**
	 * Returns whether or not to redirect the Subscriptions link to a single subscription, or to the main Subscriptions screen.
	 *
	 * @return bool
	 */
	public static function redirect_to_single_subscription() {
		// If this option is not enabled, stop.
		if ( ! Memberships::get_show_on_subscription_tab_setting() ) {
			return true;
		}

		$memberships_without_subs = self::get_memberships_without_subs();

		// If there are memberships without subs, we want to remove the redirect and go to Subscriptions; otherwise, return true.
		if ( $memberships_without_subs ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Hides 'Cancel' button on main Memberships table to tidy it up.
	 *
	 * @param array $actions WooCommerce Memberships available actions.
	 * @return array
	 */
	public static function hide_cancel_button_from_memberships_table( $actions ) {
		if ( ! empty( $actions['cancel'] ) ) {
			unset( $actions['cancel'] );
		}
		return $actions;
	}

	/**
	 * Removes the 'Next Bill On' column in the main Memberships table to tidy it up.
	 *
	 * @param array $columns WooCommerce Memberships table columns.
	 * @return array
	 */
	public static function remove_next_bill_on( $columns ) {
		if ( ! empty( $columns['membership-next-bill-on'] ) ) {
			unset( $columns['membership-next-bill-on'] );
		}
		return $columns;
	}

	/**
	 * When adding a new payment method, apply to subscriptions of all statuses.
	 * By default, this is only applied to subscriptions with an `active` status.
	 * Applies to the Stripe payment gateway only.
	 *
	 * @return array Filtered array of statuses.
	 */
	public static function update_payment_methods_for_all_subs() {
		return [
			'active',
			'pending',
			'on-hold',
			'pending-cancel',
		];
	}

	/**
	 * Whether email changes are enabled.
	 */
	public static function is_email_change_enabled() {
		/**
		 * Filters whether or not to allow email changes in My Account.
		 *
		 * @param bool $enabled Whether or not to allow email changes.
		 */
		return \apply_filters( 'newspack_email_change_enabled', true );
	}

	/**
	 * Get email change verification url.
	 *
	 * @param string $param The email change param.
	 * @param string $value The email change param value.
	 *
	 * @return string
	 */
	public static function get_email_change_url( $param, $value ) {
		return \add_query_arg(
			[
				$param => \wp_hash( $value ),
			],
			\wc_get_endpoint_url( 'edit-account', '', \wc_get_page_permalink( 'myaccount' ) )
		);
	}

	/**
	 * Handle email change request.
	 *
	 * @param int $user_id User ID.
	 */
	public static function handle_email_change_request( $user_id ) {
		$new_email = filter_input( INPUT_POST, 'newspack_account_email', FILTER_SANITIZE_EMAIL );
		if (
			empty( $new_email )
			|| ! \is_user_logged_in()
			|| ! Reader_Activation::is_enabled()
			|| ! self::is_email_change_enabled()
		) {
			return;
		}
		$old_email = \wp_get_current_user()->user_email;
		if ( $new_email === $old_email ) {
			return;
		}
		if ( ! \is_email( $new_email ) ) {
			\wc_add_notice( __( 'Please enter a valid email address.', 'newspack-plugin' ), 'error' );
		} elseif ( \email_exists( $new_email ) ) {
			\wc_add_notice( __( 'This email address is already in use.', 'newspack-plugin' ), 'error' );
		} else {
			$update = \update_user_meta( $user_id, self::PENDING_EMAIL_CHANGE_META, $new_email );
			if ( ! $update ) {
				\wc_add_notice( __( 'Something went wrong. Please try again.', 'newspack-plugin' ), 'error' );
			} else {
				$sent = [];
				if (
					Emails::send_email(
						Reader_Activation_Emails::EMAIL_TYPES['CHANGE_EMAIL_CANCEL'],
						$old_email,
						[
							[
								'template' => '*PENDING_EMAIL_ADDRESS*',
								'value'    => $new_email,
							],
							[
								'template' => '*EMAIL_CANCELLATION_URL*',
								'value'    => self::get_email_change_url( self::CANCEL_EMAIL_CHANGE_PARAM, $old_email ),
							],
						]
					)
				) {
					$sent[] = $old_email;
				}
				if (
					Emails::send_email(
						Reader_Activation_Emails::EMAIL_TYPES['CHANGE_EMAIL'],
						$new_email,
						[
							[
								'template' => '*EMAIL_VERIFICATION_URL*',
								'value'    => self::get_email_change_url( self::VERIFY_EMAIL_CHANGE_PARAM, $old_email ),
							],
							[
								'template' => '*EMAIL_CANCELLATION_URL*',
								'value'    => self::get_email_change_url( self::CANCEL_EMAIL_CHANGE_PARAM, $old_email ),
							],
						]
					)
				) {
					$sent[] = $new_email;
				}
				if ( empty( $sent ) ) {
					\wc_add_notice( __( 'Something went wrong. Please contact the site administrator.', 'newspack-plugin' ), 'error' );
				} else {
					\wc_add_notice(
						sprintf(
							// Translators: %s is the email address the verification email was sent to..
							__( 'A verification email has been sent to %s. Please verify to complete the change.', 'newspack-plugin' ),
							$new_email
						)
					);
				}
			}
		}
		// Redirect and exit ahead of Woo so only our notice is displayed.
		\wp_safe_redirect( \wc_get_endpoint_url( 'edit-account', '', \wc_get_page_permalink( 'myaccount' ) ) );
		exit;
	}

	/**
	 * Handle admin email change request.
	 *
	 * @param int     $user_id User ID.
	 * @param WP_User $user    User object.
	 * @param array   $data    User data.
	 */
	public static function handle_admin_email_change_request( $user_id, $user, $data ) {
		if ( ! is_admin() || ! self::is_email_change_enabled() ) {
			return;
		}
		$new_email = $data['user_email'] ?? '';
		$old_email = $user->user_email;
		if ( $new_email !== $old_email && \is_email( $new_email ) && \is_email( $old_email ) ) {
			self::maybe_sync_email_change_with_stripe( $user_id, $new_email );
			self::sync_email_change_with_esp( $user_id, $new_email, $old_email );
		}
	}

	/**
	 * Handle email change verification.
	 */
	public static function handle_verify_email_change() {
		if ( ! self::is_email_change_enabled() || ! \is_user_logged_in() ) {
			return;
		}
		$secret = filter_input( INPUT_GET, self::VERIFY_EMAIL_CHANGE_PARAM, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $secret ) {
			return;
		}
		$message   = __( 'Your email address has been successfully updated.', 'newspack-plugin' );
		$is_error  = false;
		$user_id   = \get_current_user_id();
		$new_email = \get_user_meta( $user_id, self::PENDING_EMAIL_CHANGE_META, true );
		$old_email = \wp_get_current_user()->user_email;
		if ( $new_email && \wp_hash( $old_email ) === $secret ) {
			\delete_user_meta( \get_current_user_id(), self::PENDING_EMAIL_CHANGE_META );
			$update = \wp_update_user(
				[
					'ID'         => $user_id,
					'user_email' => $new_email,
				]
			);
			if ( $update ) {
				$customer = new \WC_Customer( $user_id );
				$customer->set_billing_email( $new_email );
				$customer->save();
				self::maybe_sync_email_change_with_stripe( $user_id, $new_email );
				self::sync_email_change_with_esp( $user_id, $new_email, $old_email );
				\delete_user_meta( $user_id, self::PENDING_EMAIL_CHANGE_META );
			} else {
				$message  = __( 'Something went wrong.', 'newspack-plugin' );
				$is_error = true;
			}
		} else {
			$message  = __( 'This email change request has been cancelled or expired.', 'newspack-plugin' );
			$is_error = true;
		}
		\wp_safe_redirect(
			\add_query_arg(
				[
					'message'  => $message,
					'is_error' => $is_error,
				],
				\wc_get_endpoint_url(
					'edit-account',
					'',
					\wc_get_page_permalink( 'myaccount' )
				)
			)
		);
		exit;
	}

	/**
	 * Handle email change cancellation.
	 */
	public static function handle_cancel_email_change() {
		if ( ! self::is_email_change_enabled() || ! \is_user_logged_in() ) {
			return;
		}
		$secret = filter_input( INPUT_GET, self::CANCEL_EMAIL_CHANGE_PARAM, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $secret ) {
			return;
		}
		$current_email = \wp_get_current_user()->user_email;
		$message       = __( 'Your email address change request has been cancelled.', 'newspack-plugin' );
		$is_error      = false;
		if ( \wp_hash( $current_email ) === $secret ) {
			\delete_user_meta( \get_current_user_id(), self::PENDING_EMAIL_CHANGE_META );
		} else {
			$message  = __( 'This email change request has been cancelled or expired.', 'newspack-plugin' );
			$is_error = true;
		}
		\wp_safe_redirect(
			\add_query_arg(
				[
					'message'  => $message,
					'is_error' => $is_error,
				],
				\wc_get_endpoint_url(
					'edit-account',
					'',
					\wc_get_page_permalink( 'myaccount' )
				)
			)
		);
		exit;
	}

	/**
	 * Sync reader email change with stripe.
	 *
	 * @param int    $user_id User ID.
	 * @param string $email   New email.
	 */
	public static function maybe_sync_email_change_with_stripe( $user_id, $email ) {
		$result = Stripe_Connection::update_customer_data(
			$user_id,
			[
				'email' => $email,
			]
		);
		if ( false === $result ) {
			Logger::log( 'Skipping Stripe email update: no Stripe customer found for user ' . $email );
		}
		if ( \is_wp_error( $result ) ) {
			Logger::error( 'Error updating Stripe customer email: ' . $result->get_error_message() );
		}
	}

	/**
	 * Sync email change with site ESPs.
	 *
	 * @param int    $user_id User ID.
	 * @param string $new_email New email address.
	 * @param string $old_email Old email address.
	 */
	public static function sync_email_change_with_esp( $user_id, $new_email, $old_email ) {
		if ( ! ESP_Sync::can_esp_sync() ) {
			return;
		}
		$contact = ESP_Sync::get_contact_data( $user_id );
		if ( ! $contact ) {
			return;
		}
		$update = ESP_Sync::sync( $contact, 'Email_Change', array_merge( $contact, [ 'email' => $old_email ] ) );
		if ( is_wp_error( $update ) ) {
			// If the update failed, retry in 24 hours.
			\wp_schedule_single_event( time() + DAY_IN_SECONDS, self::SYNC_ESP_EMAIL_CHANGE_CRON_HOOK, [ $user_id, $new_email, $old_email ] );
			Logger::error( 'Error syncing email change with ESP: ' . $update->get_error_message() . '. Retrying in 24 hours.' );
		}
	}

	/**
	 * Check if url is newspack my account url.
	 *
	 * @return bool
	 */
	public static function is_myaccount_url() {
		$cancel_secret = filter_input( INPUT_GET, self::CANCEL_EMAIL_CHANGE_PARAM, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$verify_secret = filter_input( INPUT_GET, self::VERIFY_EMAIL_CHANGE_PARAM, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		return ! empty( $cancel_secret ) || ! empty( $verify_secret );
	}
}

WooCommerce_My_Account::init();
