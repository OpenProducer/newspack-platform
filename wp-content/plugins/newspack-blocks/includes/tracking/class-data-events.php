<?php
/**
 * Newspack Blocks Tracking Data Events Integration.
 *
 * @package Newspack
 */

namespace Newspack_Blocks\Tracking;

/**
 * Tracking Data Events Class.
 */
final class Data_Events {

	/**
	 * The name of the action for form submissions
	 */
	const FORM_SUBMISSION_SUCCESS = 'form_submission_success';

	/**
	 * The name of the action for form submissions
	 */
	const FORM_SUBMISSION_FAILURE = 'form_submission_failure';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'plugins_loaded', [ __CLASS__, 'register_listeners' ] );
	}

	/**
	 * Register listeners.
	 */
	public static function register_listeners() {
		if ( ! method_exists( 'Newspack\Data_Events', 'register_handler' ) ) {
			return;
		}

		/**
		 * Modal Checkout Interation: Completed Order.
		 */
		\Newspack\Data_Events::register_listener(
			'woocommerce_checkout_order_processed',
			'modal_checkout_interaction',
			[ __CLASS__, 'order_status_completed' ]
		);
	}

	/**
	 * Returns whether a product is a one time purchase, or recurring and when.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function get_purchase_recurrence( $product_id ) {
		$recurrence = get_post_meta( $product_id, '_subscription_period', true );
		if ( empty( $recurrence ) ) {
			$recurrence = 'once';
		}
		return $recurrence;
	}

	/**
	 * Returns whether a product ID is associated with a membership.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function is_membership_product( $product_id ) {
		if ( ! function_exists( 'wc_memberships_get_membership_plans' ) ) {
			return false;
		}
		$membership_plans = wc_memberships_get_membership_plans();
		$plans            = [];

		foreach ( $membership_plans as $plan ) {
			$subscription_plan  = new \WC_Memberships_Integration_Subscriptions_Membership_Plan( $plan->get_id() );
			$required_products = $subscription_plan->get_subscription_product_ids();
			if ( in_array( $product_id, $required_products ) ) {
				return true;
			}
		}
		return false;
	}


	/**
	 * Returns the product type: product, subscription, donation, or membership.
	 * TODOGA4: move this check & related functions into a more central location, and update based on final decision for product types.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function get_product_type( $product_id ) {
		$product_type = 'product';
		$recurrence   = self::get_purchase_recurrence( $product_id );

		// Check if it's a subscription product.
		if ( 'once' !== $recurrence ) {
			$product_type = 'subscription';
		}

		// Check if it's a membership product.
		if ( self::is_membership_product( $product_id ) ) {
			$product_type = 'membership';
		}

		// Check if it's a donation product.
		if ( method_exists( 'Newspack\Donations', 'is_donation_product' ) ) {
			if ( \Newspack\Donations::is_donation_product( $product_id ) ) {
				$product_type = 'donation';
			}
		}

		return $product_type;
	}

	/**
	 * Returns the action type: checkout_button or donation.
	 *
	 * @param string $product_id Product's ID.
	 */
	public static function get_action_type( $product_id ) {
		$action_type = 'checkout_button';
		// Check if it's a donation product, and update action_type, product_type.
		if ( method_exists( 'Newspack\Donations', 'is_donation_product' ) ) {
			if ( \Newspack\Donations::is_donation_product( $product_id ) ) {
				$action_type = 'donation';
			}
		}
		return $action_type;
	}

	/**
	 * Returns an array of product information.
	 *
	 * @param string $product_id Product's ID.
	 * @param array  $cart_item Cart item.
	 * @param string $product_parent_id Product's ID when it has variations.
	 */
	public static function build_js_data_events( $product_id, $cart_item, $product_parent_id = '' ) {
		// Reassign the IDs to make sure the product is the product and the variation is the variation.
		$variation_id = '';
		if ( '' !== $product_parent_id && 0 !== $product_parent_id ) {
			$variation_id = $product_id;
			$product_id = $product_parent_id;
		}

		$data_order_details = [
			'amount'       => $cart_item['data']->get_price(),
			'action_type'  => self::get_action_type( $product_id ),
			'currency'     => function_exists( 'get_woocommerce_currency' ) ? \get_woocommerce_currency() : 'USD',
			'product_id'   => strval( $product_id ),
			'product_type' => self::get_product_type( $product_id ),
			'referrer'     => str_replace( home_url(), '', $cart_item['referer'] ), // Keeps format consistent for Homepage with Donate and Checkout Button blocks.
			'recurrence'   => self::get_purchase_recurrence( $product_id ),
			'variation_id' => strval( $variation_id ),
		];
		return $data_order_details;
	}

	/**
	 * Send data to GA4.
	 *
	 * @param string    $order_id Order's ID.
	 * @param array     $posted_data Posted Data.
	 * @param \WC_Order $order Order object.
	 */
	public static function order_status_completed( $order_id, $posted_data, $order ) {
		// Check if in a modal checkout; if no, bail.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_modal_checkout = ( isset( $_REQUEST['modal_checkout'] ) ? true : false );
		if ( ! $is_modal_checkout ) {
			return;
		}

		$data = \Newspack\Data_Events\Utils::get_order_data( $order_id );
		if ( empty( $data ) ) {
			return;
		}

		$product_id = is_array( $data['platform_data']['product_id'] ) ? $data['platform_data']['product_id'][0] : $data['platform_data']['product_id'];

		$data['action']       = self::FORM_SUBMISSION_SUCCESS;
		$data['action_type']  = self::get_action_type( $product_id );
		$data['product_id']   = $product_id;
		$data['product_type'] = self::get_product_type( $product_id );
		$data['recurrence']   = self::get_purchase_recurrence( $product_id );

		return $data;
	}
}
Data_Events::init();
