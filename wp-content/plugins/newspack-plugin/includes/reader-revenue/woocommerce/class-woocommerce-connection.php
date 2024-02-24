<?php
/**
 * Connection with WooCommerce's features.
 *
 * @package Newspack
 */

namespace Newspack;

use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Connection with WooCommerce's features.
 */
class WooCommerce_Connection {
	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		include_once __DIR__ . '/class-woocommerce-order-utm.php';
		include_once __DIR__ . '/class-woocommerce-cover-fees.php';
		include_once __DIR__ . '/class-woocommerce-cli.php';

		\add_action( 'admin_init', [ __CLASS__, 'disable_woocommerce_setup' ] );
		\add_filter( 'option_woocommerce_subscriptions_allow_switching', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'option_woocommerce_subscriptions_allow_switching_nyp_price', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'option_woocommerce_subscriptions_enable_retry', [ __CLASS__, 'force_allow_failed_payment_retry' ] );
		\add_filter( 'default_option_woocommerce_subscriptions_allow_switching', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'default_option_woocommerce_subscriptions_allow_switching_nyp_price', [ __CLASS__, 'force_allow_subscription_switching' ], 10, 2 );
		\add_filter( 'default_option_woocommerce_subscriptions_enable_retry', [ __CLASS__, 'force_allow_failed_payment_retry' ] );
		\add_filter( 'woocommerce_email_enabled_customer_completed_order', [ __CLASS__, 'send_customizable_receipt_email' ], 10, 3 );
		\add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'maybe_update_reader_display_name' ], 10, 2 );
		\add_action( 'option_woocommerce_feature_order_attribution_enabled', [ __CLASS__, 'force_disable_order_attribution' ] );
		\add_action( 'cli_init', [ __CLASS__, 'register_cli_commands' ] );

		// WooCommerce Subscriptions.
		\add_filter( 'wc_stripe_generate_payment_request', [ __CLASS__, 'stripe_gateway_payment_request_data' ], 10, 2 );

		// woocommerce-memberships-for-teams plugin.
		\add_filter( 'wc_memberships_for_teams_product_team_user_input_fields', [ __CLASS__, 'wc_memberships_for_teams_product_team_user_input_fields' ] );

		\add_action( 'woocommerce_payment_complete', [ __CLASS__, 'order_paid' ], 101 );

		\add_filter( 'page_template', [ __CLASS__, 'page_template' ] );
		\add_filter( 'get_post_metadata', [ __CLASS__, 'get_post_metadata' ], 10, 3 );
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
	 * Get the last successful order for a given customer.
	 *
	 * @param \WC_Customer $customer Customer object.
	 *
	 * @return \WC_Order|false Order object or false.
	 */
	public static function get_last_successful_order( $customer ) {
		if ( ! is_a( $customer, 'WC_Customer' ) ) {
			return false;
		}

		// See https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query for query args.
		$args = [
			'customer_id' => $customer->get_id(),
			'status'      => [ 'wc-completed' ],
			'limit'       => 1,
			'order'       => 'DESC',
			'orderby'     => 'date',
			'return'      => 'objects',
		];

		$orders = wc_get_orders( $args );

		if ( ! empty( $orders ) ) {
			return reset( $orders );
		}

		return false;
	}

	/**
	 * Get data about a customer's order to sync to the connected ESP.
	 *
	 * @param \WC_Order|int $order WooCommerce order or order ID.
	 * @param bool|string   $payment_page_url Payment page URL. If not provided, checkout URL will be used.
	 * @param bool          $is_new Whether the order is new and should count as the last payment amount.
	 *
	 * @return array Contact order metadata.
	 */
	public static function get_contact_order_metadata( $order, $payment_page_url = false, $is_new = false ) {
		if ( ! is_a( $order, 'WC_Order' ) ) {
			$order = new \WC_Order( $order );
		}

		if ( ! self::should_sync_order( $order ) ) {
			return [];
		}

		// Only update last payment data if new payment has been received.
		$payment_received = $is_new && $order->has_status( [ 'processing', 'completed' ] );

		$metadata = [];

		$referer_from_order = $order->get_meta( '_newspack_referer' );
		if ( empty( $referer_from_order ) ) {
			$payment_page_url = \wc_get_checkout_url();
		} else {
			$payment_page_url = $referer_from_order;
		}
		$metadata['payment_page'] = $payment_page_url;

		$utm = $order->get_meta( 'utm' );
		if ( ! empty( $utm ) ) {
			foreach ( $utm as $key => $value ) {
				$metadata[ Newspack_Newsletters::get_metadata_key( 'payment_page_utm' ) . $key ] = $value;
			}
		}

		$order_subscriptions = \wcs_get_subscriptions_for_order( $order->get_id(), [ 'order_type' => 'any' ] );
		$is_donation_order   = Donations::is_donation_order( $order );

		// One-time transaction.
		if ( empty( $order_subscriptions ) ) {
			if ( $is_donation_order ) {
				$metadata[ Newspack_Newsletters::get_metadata_key( 'membership_status' ) ] = 'Donor';
			}

			$metadata[ Newspack_Newsletters::get_metadata_key( 'product_name' ) ] = '';
			$order_items = $order->get_items();
			if ( $order_items ) {
				$metadata[ Newspack_Newsletters::get_metadata_key( 'product_name' ) ] = reset( $order_items )->get_name();
			}
			$order_date_paid = $order->get_date_paid();
			if ( $payment_received && ! empty( $order_date_paid ) ) {
				$metadata[ Newspack_Newsletters::get_metadata_key( 'last_payment_amount' ) ] = \wc_format_localized_price( $order->get_total() );
				$metadata[ Newspack_Newsletters::get_metadata_key( 'last_payment_date' ) ]   = $order_date_paid->date( Newspack_Newsletters::METADATA_DATE_FORMAT );
			}

			// Subscription transaction.
		} else {
			$current_subscription = reset( $order_subscriptions );

			if ( $is_donation_order ) {
				$donor_status = 'Donor';
				if ( 'month' === $current_subscription->get_billing_period() ) {
					$donor_status = 'Monthly ' . $donor_status;
				}
				if ( 'year' === $current_subscription->get_billing_period() ) {
					$donor_status = 'Yearly ' . $donor_status;
				}

				// If the subscription has moved to a cancelled or expired status.
				if ( $current_subscription->has_status( [ 'cancelled', 'expired' ] ) ) {
					$donor_status = 'Ex-' . $donor_status;
				}
				$metadata[ Newspack_Newsletters::get_metadata_key( 'membership_status' ) ] = $donor_status;
			}

			$metadata[ Newspack_Newsletters::get_metadata_key( 'sub_start_date' ) ]    = $current_subscription->get_date( 'start' );
			$metadata[ Newspack_Newsletters::get_metadata_key( 'sub_end_date' ) ]      = $current_subscription->get_date( 'end' ) ? $current_subscription->get_date( 'end' ) : '';
			$metadata[ Newspack_Newsletters::get_metadata_key( 'billing_cycle' ) ]     = $current_subscription->get_billing_period();
			$metadata[ Newspack_Newsletters::get_metadata_key( 'recurring_payment' ) ] = $current_subscription->get_total();

			if ( $payment_received ) {
				$metadata[ Newspack_Newsletters::get_metadata_key( 'last_payment_amount' ) ] = \wc_format_localized_price( $current_subscription->get_total() );
				$metadata[ Newspack_Newsletters::get_metadata_key( 'last_payment_date' ) ]   = $current_subscription->get_date( 'last_order_date_paid' ) ? $current_subscription->get_date( 'last_order_date_paid' ) : gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT );
			}

			// When a WC Subscription is terminated, the next payment date is set to 0. We don't want to sync that – the next payment date should remain as it was
			// in the event of cancellation.
			$next_payment_date = $current_subscription->get_date( 'next_payment' );
			if ( $next_payment_date ) {
				$metadata[ Newspack_Newsletters::get_metadata_key( 'next_payment_date' ) ] = $next_payment_date;
			}

			$metadata[ Newspack_Newsletters::get_metadata_key( 'product_name' ) ] = '';
			if ( $current_subscription ) {
				$subscription_order_items = $current_subscription->get_items();
				if ( $subscription_order_items ) {
					$metadata[ Newspack_Newsletters::get_metadata_key( 'product_name' ) ] = reset( $subscription_order_items )->get_name();
				}
			}
		}

		return $metadata;
	}

	/**
	 * Get data for a customer to sync to the connected ESP.
	 *
	 * @param \WC_Customer    $customer Customer object.
	 * @param \WC_Order|false $order Order object to sync with. If not given, the last successful order will be used.
	 * @param bool|string     $payment_page_url Payment page URL. If not provided, checkout URL will be used.
	 * @param bool            $is_new Whether the order is new and should count as the last payment amount.
	 *
	 * @return array|false Contact data or false.
	 */
	public static function get_contact_from_customer( $customer, $order = false, $payment_page_url = false, $is_new = false ) {
		if ( ! is_a( $customer, 'WC_Customer' ) ) {
			return false;
		}

		$metadata       = [];
		$order_metadata = [];

		$metadata[ Newspack_Newsletters::get_metadata_key( 'account' ) ]           = $customer->get_id();
		$metadata[ Newspack_Newsletters::get_metadata_key( 'registration_date' ) ] = $customer->get_date_created()->date( Newspack_Newsletters::METADATA_DATE_FORMAT );
		$metadata[ Newspack_Newsletters::get_metadata_key( 'total_paid' ) ]        = \wc_format_localized_price( $customer->get_total_spent() );

		if ( ! $order ) {
			$order = self::get_last_successful_order( $customer );
		}

		if ( $order ) {
			$order_metadata = self::get_contact_order_metadata( $order, $payment_page_url, $is_new );
		} else {
			// If the customer has no successful orders, ensure their spend totals are correct.
			$order_metadata[ Newspack_Newsletters::get_metadata_key( 'last_payment_amount' ) ] = \wc_format_localized_price( '0.00' );
		}

		$metadata = array_merge( $metadata, $order_metadata );

		$first_name = $customer->get_billing_first_name();
		$last_name  = $customer->get_billing_last_name();
		$full_name  = trim( "$first_name $last_name" );
		$contact    = [
			'email'    => $customer->get_billing_email(),
			'metadata' => array_filter( $metadata ),
		];
		if ( ! empty( $full_name ) ) {
			$contact['name'] = $full_name;
		}
		return array_filter( $contact );
	}

	/**
	 * Get the contact data from a WooCommerce order.
	 *
	 * @param \WC_Order|int $order WooCommerce order or order ID.
	 * @param bool|string   $payment_page_url Payment page URL. If not provided, checkout URL will be used.
	 * @param bool          $is_new Whether the order is new and should count as the last payment amount.
	 *
	 * @return array|false Contact data or false.
	 */
	public static function get_contact_from_order( $order, $payment_page_url = false, $is_new = false ) {
		if ( ! is_a( $order, 'WC_Order' ) ) {
			$order = new \WC_Order( $order );
		}

		if ( ! self::should_sync_order( $order ) ) {
			return;
		}

		$user_id  = $order->get_customer_id();
		$customer = new \WC_Customer( $user_id );

		return self::get_contact_from_customer( $customer, $order, $payment_page_url, $is_new );
	}

	/**
	 * Should an order be synchronized with the integrations?
	 *
	 * @param WC_Order $order Order object.
	 */
	public static function should_sync_order( $order ) {
		// $order is not a valid WC_Order object, so don't try to sync.
		if ( ! is_a( $order, 'WC_Order' ) ) {
			return false;
		}
		// If the order lacks a customer.
		if ( ! $order->get_customer_id() ) {
			return [];
		}
		if ( $order->get_meta( '_subscription_switch' ) ) {
			// This is a "switch" order, which is just recording a subscription update. It has value of 0 and
			// should not be synced anywhere.
			return false;
		}
		return true;
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
	 * Send the customizable receipt email instead of WooCommerce's default receipt.
	 *
	 * @param bool     $enable Whether to send the default receipt email.
	 * @param WC_Order $order The order object for the receipt email.
	 * @param WC_Email $class Instance of the WC_Email class.
	 *
	 * @return bool
	 */
	public static function send_customizable_receipt_email( $enable, $order, $class ) {
		// If we don't have a valid order, or the customizable email isn't enabled, bail.
		if ( ! is_a( $order, 'WC_Order' ) || ! Emails::can_send_email( Reader_Revenue_Emails::EMAIL_TYPES['RECEIPT'] ) ) {
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
		];

		$sent = Emails::send_email(
			Reader_Revenue_Emails::EMAIL_TYPES['RECEIPT'],
			$order->get_billing_email(),
			$placeholders
		);

		return false;
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
