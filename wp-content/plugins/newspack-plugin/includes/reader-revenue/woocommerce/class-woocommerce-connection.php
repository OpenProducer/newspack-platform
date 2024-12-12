<?php
/**
 * Connection with WooCommerce's features.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Connection with WooCommerce's features.
 */
class WooCommerce_Connection {
	/**
	 * Statuses considered active.
	 */
	const ACTIVE_SUBSCRIPTION_STATUSES = [ 'active', 'pending', 'pending-cancel' ];
	const ACTIVE_ORDER_STATUSES = [ 'processing', 'completed' ];


	/**
	 * These are the status a subscription can have for us to consider it from a former subscriber.
	 */
	const FORMER_SUBSCRIBER_STATUSES = [ 'on-hold', 'cancelled', 'expired' ];

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		include_once __DIR__ . '/class-woocommerce-cli.php';
		include_once __DIR__ . '/class-woocommerce-cover-fees.php';
		include_once __DIR__ . '/class-woocommerce-order-utm.php';
		include_once __DIR__ . '/class-woocommerce-products.php';

		\add_action( 'admin_init', [ __CLASS__, 'disable_woocommerce_setup' ] );
		\add_filter( 'option_woocommerce_subscriptions_allow_switching', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'option_woocommerce_subscriptions_allow_switching_nyp_price', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'option_woocommerce_subscriptions_enable_retry', [ __CLASS__, 'force_allow_failed_payment_retry' ] );
		\add_filter( 'default_option_woocommerce_subscriptions_allow_switching', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'default_option_woocommerce_subscriptions_allow_switching_nyp_price', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'default_option_woocommerce_subscriptions_enable_retry', [ __CLASS__, 'force_allow_failed_payment_retry' ] );
		\add_filter( 'woocommerce_email_enabled_customer_completed_order', [ __CLASS__, 'send_customizable_receipt_email' ], 10, 3 );
		\add_filter( 'woocommerce_email_enabled_cancelled_subscription', [ __CLASS__, 'send_customizable_cancellation_email' ], 10, 3 );
		\add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'maybe_update_reader_display_name' ], 10, 2 );
		\add_action( 'option_woocommerce_feature_order_attribution_enabled', [ __CLASS__, 'force_disable_order_attribution' ] );
		\add_filter( 'woocommerce_related_products', [ __CLASS__, 'disable_related_products' ] );
		\add_action( 'cli_init', [ __CLASS__, 'register_cli_commands' ] );

		// WooCommerce Subscriptions.
		\add_filter( 'wc_stripe_generate_payment_request', [ __CLASS__, 'stripe_gateway_payment_request_data' ], 10, 2 );

		// woocommerce-memberships-for-teams plugin.
		\add_filter( 'wc_memberships_for_teams_product_team_user_input_fields', [ __CLASS__, 'wc_memberships_for_teams_product_team_user_input_fields' ] );

		\add_action( 'woocommerce_payment_complete', [ __CLASS__, 'order_paid' ], 101 );
		\add_action( 'wc_stripe_save_to_subs_checked', '__return_true' );

		\add_filter( 'page_template', [ __CLASS__, 'page_template' ] );
		\add_filter( 'get_post_metadata', [ __CLASS__, 'get_post_metadata' ], 10, 3 );
	}

	/**
	 * Check if the given status is considered active in Woo Subscriptions.
	 *
	 * @param string $status Subscription status.
	 *
	 * @return boolean
	 */
	public static function is_subscription_active( $status ) {
		$status = str_replace( 'wc-', '', $status ); // Normalize status strings.
		return in_array( $status, self::ACTIVE_SUBSCRIPTION_STATUSES, true );
	}

	/**
	 * Register CLI command
	 *
	 * @return void
	 */
	public static function register_cli_commands() {
		\WP_CLI::add_command( 'newspack-woocommerce', 'Newspack\\WooCommerce_Cli' );
	}

	/**
	 * Hide WooCommerce's setup task list. Newspack does the setup behind the scenes.
	 */
	public static function disable_woocommerce_setup() {
		if ( class_exists( '\Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists' ) ) {
			$task_list = \Automattic\WooCommerce\Admin\Features\OnboardingTasks\TaskLists::get_list( 'setup' );
			if ( $task_list ) {
				$task_list->hide();
			}
		}
	}

	/**
	 * Disable related products on product pages.
	 *
	 * @param array $related_products Related products.
	 *
	 * @return array
	 */
	public static function disable_related_products( $related_products ) {
		if ( defined( 'NEWSPACK_ALLOW_RELATED_PRODUCTS' ) && NEWSPACK_ALLOW_RELATED_PRODUCTS ) {
			return $related_products;
		}
		return [];
	}

	/**
	 * Donations actions when order is processed.
	 *
	 * @param int $order_id Order ID.
	 */
	public static function order_paid( $order_id ) {
		$product_id = Donations::get_order_donation_product_id( $order_id );

		/** Bail if not a donation order. */
		if ( false === $product_id ) {
			return;
		}

		/**
		 * Fires when a donation order is processed.
		 *
		 * @param int $order_id   Order post ID.
		 * @param int $product_id Donation product post ID.
		 */
		\do_action( 'newspack_donation_order_processed', $order_id, $product_id );
	}

	/**
	 * Does the given user have any subscriptions with an active status?
	 * Can optionally pass an array of product IDs. If given, only subscriptions
	 * that have at least one of the given product IDs will be returned.
	 *
	 * @param int   $user_id User ID.
	 * @param array $product_ids Optional array of product IDs to filter by.
	 *
	 * @return int[] Array of active subscription IDs.
	 */
	public static function get_active_subscriptions_for_user( $user_id, $product_ids = [] ) {
		$subcriptions = array_reduce(
			array_keys( \wcs_get_users_subscriptions( $user_id ) ),
			function( $acc, $subscription_id ) use ( $product_ids ) {
				$subscription = \wcs_get_subscription( $subscription_id );
				if ( $subscription->has_status( self::ACTIVE_SUBSCRIPTION_STATUSES ) ) {
					if ( ! empty( $product_ids ) ) {
						foreach ( $product_ids as $product_id ) {
							if ( $subscription->has_product( $product_id ) ) {
								$acc[] = $subscription_id;
								return $acc;
							}
						}
					} else {
						$acc[] = $subscription_id;
					}
				}
				return $acc;
			},
			[]
		);

		return $subcriptions;
	}

	/**
	 * Filter post request made by the Stripe Gateway for Stripe payments.
	 *
	 * @param array     $post_data An array of metadata.
	 * @param \WC_Order $order The order object.
	 */
	public static function stripe_gateway_payment_request_data( $post_data, $order ) {
		if ( ! function_exists( 'wcs_get_subscriptions_for_renewal_order' ) ) {
			return $post_data;
		}
		$related_subscriptions = \wcs_get_subscriptions_for_renewal_order( $order );
		if ( ! empty( $related_subscriptions ) ) {
			// In theory, there should be just one subscription per renewal.
			$subscription    = reset( $related_subscriptions );
			$subscription_id = $subscription->get_id();
			// Add subscription ID to any renewal.
			$post_data['metadata']['subscription_id'] = $subscription_id;
			if ( \wcs_order_contains_renewal( $order ) ) {
				$post_data['metadata']['subscription_status'] = 'renewed';
			} else {
				$post_data['metadata']['subscription_status'] = 'created';
			}
		}
		return $post_data;
	}

	/**
	 * Force values for subscription switching options to ON unless the
	 * NEWSPACK_PREVENT_WC_SUBS_ALLOW_SWITCHING_OVERRIDE constant is set.
	 * This affects the following "Allow Switching" options:
	 *
	 * - Between Subscription Variations
	 * - Between Grouped Subscriptions
	 * - Change Name Your Price subscription amount
	 *
	 * @param bool   $can_switch Whether the subscription amount can be switched.
	 * @param string $option_name The name of the option.
	 *
	 * @return string Option value.
	 */
	public static function force_allow_subscription_switching( $can_switch, $option_name ) {
		if ( defined( 'NEWSPACK_PREVENT_WC_SUBS_ALLOW_SWITCHING_OVERRIDE' ) && NEWSPACK_PREVENT_WC_SUBS_ALLOW_SWITCHING_OVERRIDE ) {
			return $can_switch;
		}

		// Subscriptions' default switching options are combined into a single options row with possible values 'no', 'variable', 'grouped', or 'variable_grouped'.
		if ( 'woocommerce_subscriptions_allow_switching' === $option_name ) {
			return 'variable_grouped';
		}

		// Other options added by the woocommerce_subscriptions_allow_switching_options filter are either 'yes' or 'no'.
		return 'yes';
	}

	/**
	 * Force option for allowing retries for failed payments to ON unless the
	 * NEWSPACK_PREVENT_WC_ALLOW_FAILED_PAYMENT_RETRIES_OVERRIDE constant is set.
	 *
	 * See: https://woo.com/document/subscriptions/failed-payment-retry/
	 *
	 * @param bool $should_retry Whether WooCommerce should automatically retry failed payments.
	 *
	 * @return string Option value.
	 */
	public static function force_allow_failed_payment_retry( $should_retry ) {
		if ( defined( 'NEWSPACK_PREVENT_WC_ALLOW_FAILED_PAYMENT_RETRIES_OVERRIDE' ) && NEWSPACK_PREVENT_WC_ALLOW_FAILED_PAYMENT_RETRIES_OVERRIDE ) {
			return $should_retry;
		}

		return 'yes';
	}

	/**
	 * Force option for enabling order attribution to OFF unless the
	 * NEWSPACK_PREVENT_WC_ALLOW_ORDER_ATTRIBUTION_OVERRIDE constant is set.
	 * Right now, it causes JavaScript errors in the modal checkout.
	 *
	 * See:https://woo.com/document/order-attribution-tracking/
	 *
	 * @param bool $should_allow Whether WooCommerce should allow enabling Order Attribution.
	 *
	 * @return string Option value.
	 */
	public static function force_disable_order_attribution( $should_allow ) {
		if ( defined( 'NEWSPACK_PREVENT_WC_ALLOW_ORDER_ATTRIBUTION_OVERRIDE' ) && NEWSPACK_PREVENT_WC_ALLOW_ORDER_ATTRIBUTION_OVERRIDE ) {
			return $should_allow;
		}
		return false;
	}

	/**
	 * Send the customizable receipt or welcome email instead of WooCommerce's default receipt.
	 *
	 * @param bool     $enable Whether to send the default receipt email.
	 * @param WC_Order $order The order object for the receipt email.
	 * @param WC_Email $class Instance of the WC_Email class.
	 *
	 * @return bool
	 */
	public static function send_customizable_receipt_email( $enable, $order, $class ) {
		// If we don't have a valid order, or the customizable email isn't enabled, bail.
		if ( empty( $order ) || ! is_a( $order, 'WC_Order' ) || ! Emails::can_send_email( Reader_Revenue_Emails::EMAIL_TYPES['RECEIPT'] ) ) {
			return $enable;
		}

		// If there are no donation products in the order, do not override the default WC receipt email.
		$has_donation_product = \Newspack\Donations::get_order_donation_product_id( $order->get_id() ) !== false;
		if ( ! $has_donation_product ) {
			return $enable;
		}

		$email_type      = Reader_Revenue_Emails::EMAIL_TYPES['RECEIPT'];
		$email_sent_meta = '_newspack_receipt_email_sent';

		// If this is a new registration, and the welcome email is enabled, send the welcome email instead.
		if ( $order->get_meta( '_newspack_checkout_registration_meta' ) && Emails::can_send_email( Reader_Revenue_Emails::EMAIL_TYPES['WELCOME'] ) ) {
			$email_type      = Reader_Revenue_Emails::EMAIL_TYPES['WELCOME'];
			$email_sent_meta = '_newspack_welcome_email_sent';
		}

		// if the customizable email isn't enabled bail.
		if ( ! Emails::can_send_email( $email_type ) ) {
			return $enable;
		}

		if ( $order->get_meta( $email_sent_meta, true ) ) {
			return $enable;
		}

		$frequencies = [
			'month' => __( 'Monthly', 'newspack-plugin' ),
			'year'  => __( 'Yearly', 'newspack-plugin' ),
		];
		$product_map = [];
		foreach ( $frequencies as $frequency => $label ) {
			$product_id = Donations::get_donation_product( $frequency );
			if ( $product_id ) {
				$product_map[ $product_id ] = $label;
			}
		}

		$items = $order->get_items();
		$item  = array_shift( $items );

		// Replace content placeholders.
		$placeholders = [
			[
				'template' => '*BILLING_NAME*',
				'value'    => trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ),
			],
			[
				'template' => '*BILLING_FIRST_NAME*',
				'value'    => $order->get_billing_first_name(),
			],
			[
				'template' => '*BILLING_LAST_NAME*',
				'value'    => $order->get_billing_last_name(),
			],
			[
				'template' => '*BILLING_FREQUENCY*',
				'value'    => $product_map[ $item->get_product_id() ] ?? __( 'One-time', 'newspack-plugin' ),
			],
			[
				'template' => '*PRODUCT_NAME*',
				'value'    => $item->get_name(),
			],
			[
				'template' => '*AMOUNT*',
				'value'    => \wp_strip_all_tags( $order->get_formatted_order_total() ),
			],
			[
				'template' => '*DATE*',
				'value'    => $order->get_date_created()->date_i18n(),
			],
			[
				'template' => '*PAYMENT_METHOD*',
				'value'    => __( 'Card', 'newspack-plugin' ) . ' – ' . $order->get_payment_method(),
			],
			[
				'template' => '*RECEIPT_URL*',
				'value'    => sprintf( '<a href="%s">%s</a>', $order->get_view_order_url(), __( 'My Account', 'newspack-plugin' ) ),
			],
			[
				'template' => '*ACCOUNT_URL*',
				'value'    => function_exists( '\wc_get_account_endpoint_url' ) ? \wc_get_account_endpoint_url( 'dashboard' ) : get_bloginfo( 'wpurl' ),
			],
		];

		$sent = Emails::send_email(
			$email_type,
			$order->get_billing_email(),
			$placeholders
		);
		if ( $sent ) {
			$order->add_meta_data( $email_sent_meta, true, true );
			return false;
		}
		return $enable;
	}

	/**
	 * Send the customizable cancellation email in addition to WooCommerce Subscription's default.
	 * We still want to allow WCS to send its cancellation email since this targets the store admin.
	 *
	 * @param bool            $enable        Whether to send the cancellation email.
	 * @param WC_Subscription $subscription  The order object for the cancellation email.
	 * @param WC_Email        $class         Instance of the WC_Email class.
	 *
	 * @return bool
	 */
	public static function send_customizable_cancellation_email( $enable, $subscription, $class ) {
		// If we don't have a valid subscription, or the customizable email isn't enabled, bail.
		if ( ! is_a( $subscription, 'WC_Subscription' ) || ! Emails::can_send_email( Reader_Revenue_Emails::EMAIL_TYPES['CANCELLATION'] ) ) {
			return $enable;
		}

		$frequencies = [
			'month' => __( 'Monthly', 'newspack-plugin' ),
			'year'  => __( 'Yearly', 'newspack-plugin' ),
		];
		$product_map = [];
		foreach ( $frequencies as $frequency => $label ) {
			$product_id = Donations::get_donation_product( $frequency );
			if ( $product_id ) {
				$product_map[ $product_id ] = $label;
			}
		}

		$items = $subscription->get_items();

		if ( ! empty( $items ) ) {
			$item         = array_shift( $items );
			$is_donation  = Donations::is_donation_product( $item->get_product_id() );
			// Replace content placeholders.
			$placeholders = [
				[
					'template' => '*BILLING_NAME*',
					'value'    => trim( $subscription->get_billing_first_name() . ' ' . $subscription->get_billing_last_name() ),
				],
				[
					'template' => '*BILLING_FIRST_NAME*',
					'value'    => $subscription->get_billing_first_name(),
				],
				[
					'template' => '*BILLING_LAST_NAME*',
					'value'    => $subscription->get_billing_last_name(),
				],
				[
					'template' => '*BILLING_FREQUENCY*',
					'value'    => $product_map[ $item->get_product_id() ] ?? __( 'One-time', 'newspack-plugin' ),
				],
				[
					'template' => '*PRODUCT_NAME*',
					'value'    => $item->get_name(),
				],
				[
					'template' => '*END_DATE*',
					'value'    => wcs_format_datetime( wcs_get_datetime_from( $subscription->get_date( 'end' ) ) ),
				],
				[
					'template' => '*BUTTON_TEXT*',
					'value'    => $is_donation ? __( 'Restart Donation', 'newspack-plugin' ) : __( 'Restart Subscription', 'newspack-plugin' ),
				],
				[
					'template' => '*CANCELLATION_DATE*',
					'value'    => wcs_format_datetime( wcs_get_datetime_from( $subscription->get_date( 'cancelled' ) ) ),
				],
				[
					'template' => '*CANCELLATION_TITLE*',
					'value'    => $is_donation ? __( 'Donation Cancelled', 'newspack-plugin' ) : __( 'Subscription Cancelled', 'newspack-plugin' ),
				],
				[
					'template' => '*CANCELLATION_TYPE*',
					'value'    => $is_donation ? __( 'recurring donation', 'newspack-plugin' ) : __( 'subscription', 'newspack-plugin' ),
				],
				[
					'template' => '*SUBSCRIPTION_URL*',
					'value'    => $subscription->get_view_order_url(),
				],
			];

			$sent = Emails::send_email(
				Reader_Revenue_Emails::EMAIL_TYPES['CANCELLATION'],
				$subscription->get_billing_email(),
				$placeholders
			);
		}

		return $enable;
	}

	/**
	 * If the reader completes an order, check if they have a generic display name.
	 * If they do and they also have a billing first and/or last name, we can upgrade
	 * the display name to match their provided billing name.
	 *
	 * @param int      $order_id Order ID.
	 * @param WC_Order $order Completed order.
	 */
	public static function maybe_update_reader_display_name( $order_id, $order ) {
		$customer_id = $order->get_customer_id();
		if ( ! Reader_Activation::reader_has_generic_display_name( $customer_id ) ) {
			return;
		}

		// If they have a generated display name, construct it from billing name.
		$first_name = $order->get_billing_first_name();
		$last_name  = $order->get_billing_last_name();
		if ( ! empty( $first_name ) || ! empty( $last_name ) ) {
			$display_name = trim( "$first_name $last_name" );
			\wp_update_user(
				[
					'ID'           => $customer_id,
					'display_name' => $display_name,
				]
			);
		}
	}

	/**
	 * Get an array of product IDs associated with the given order ID.
	 *
	 * @param int     $order_id Order ID.
	 * @param boolean $include_donations If true, include donation products, otherwise omit them.
	 * @return array Array of product IDs associated with this order.
	 */
	public static function get_products_for_order( $order_id, $include_donations = false ) {
		$product_ids = [];
		if ( ! function_exists( 'wc_get_order' ) ) {
			return $product_ids;
		}

		$order       = \wc_get_order( $order_id );
		$order_items = $order->get_items();

		foreach ( $order_items as $item ) {
			$product_id = $item->get_product_id();
			if ( $include_donations || ! Donations::is_donation_product( $product_id ) ) {
				$product_ids[] = $product_id;
			}
		}

		return $product_ids;
	}

	/**
	 * Add a WC notice.
	 *
	 * @param string $message Message to display.
	 * @param string $type Type of notice.
	 */
	public static function add_wc_notice( $message, $type ) {
		if ( ! function_exists( '\wc_add_notice' ) || ! function_exists( 'WC' ) ) {
			return;
		}
		if ( ! WC()->session ) {
			return;
		}
		\wc_add_notice( $message, $type );
	}

	/**
	 * Should override the template for the given page?
	 */
	private static function should_override_template() {
		if ( defined( 'NEWSPACK_DISABLE_WC_TEMPLATE_OVERRIDE' ) && NEWSPACK_DISABLE_WC_TEMPLATE_OVERRIDE ) {
			return false;
		}
		if ( ! function_exists( 'WC' ) ) {
			return false;
		}
		return is_checkout() || is_cart() || is_account_page();
	}

	/**
	 * Override page templates for WC pages.
	 *
	 * @param string $template Template path.
	 */
	public static function page_template( $template ) {
		if ( self::should_override_template() ) {
			return get_theme_file_path( '/single-wide.php' );
		}
		return $template;
	}

	/**
	 * Override post meta for WC pages.
	 *
	 * @param mixed  $value    Meta value to return.
	 * @param int    $id       Post ID.
	 * @param string $meta_key Meta key.
	 */
	public static function get_post_metadata( $value, $id, $meta_key ) {
		if ( '_wp_page_template' === $meta_key && self::should_override_template() ) {
			return 'single-wide';
		}
		return $value;
	}

	/**
	 * Fix woocommerce-memberships-for-teams when on /order-pay page. This page is available
	 * from edit order screen -> "Customer payment page" link when the order is pending payment.
	 * It allows the customer to pay for the order.
	 * If woocommerce-memberships-for-teams is used, a cart validation error prevents the customer from
	 * paying for the order because the team name is not set. This filter sets the team name from the order item.
	 *
	 * @param array $fields associative array of user input fields.
	 */
	public static function wc_memberships_for_teams_product_team_user_input_fields( $fields ) {
		global $wp;
		if ( ! isset( $wp->query_vars['order-pay'] ) || ! class_exists( 'WC_Order' ) || ! function_exists( 'wc_memberships_for_teams_get_team_for_order_item' ) ) {
			return $fields;
		}
		$order = new \WC_Order( $wp->query_vars['order-pay'] );
		foreach ( $order->get_items( 'line_item' ) as $id => $item ) {
			$team = wc_memberships_for_teams_get_team_for_order_item( $item );
			if ( $team ) {
				$_REQUEST['team_name'] = $team->get_name();
			}
		}
		return $fields;
	}
}

WooCommerce_Connection::init();
