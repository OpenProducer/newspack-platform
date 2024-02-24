<?php
/**
 * Newspack Data Events Content Gate and Memberships helper.
 *
 * @package Newspack
 */

namespace Newspack\Data_Events;

use Newspack\Memberships as NewspackMemberships;
use Newspack\Reader_Activation;
use Newspack\Data_Events;
use WP_Error;

/**
 * Data Events Memberships Class
 */
final class Memberships {

	const METADATA_NAME = 'memberships_content_gate';

	/**
	 * The name of the action for form submissions
	 */
	const FORM_SUBMISSION = 'form_submission_received';

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
		add_action( 'init', [ __CLASS__, 'register_listeners' ] );
		add_filter( 'newspack_blocks_modal_checkout_cart_item_data', [ __CLASS__, 'checkout_cart_item_data' ], 10, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', [ __CLASS__, 'checkout_create_order_line_item' ], 10, 4 );
		add_filter( 'newspack_register_reader_form_metadata', [ __CLASS__, 'register_reader_metadata' ], 10, 2 );
	}

	/**
	 * Add content gate metadata to the cart item.
	 *
	 * @param array $cart_item_data The cart item data.
	 *
	 * @return array
	 */
	public static function checkout_cart_item_data( $cart_item_data ) {
		if ( isset( $_REQUEST[ self::METADATA_NAME ] ) && ! empty( $_REQUEST[ self::METADATA_NAME ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$cart_item_data[ self::METADATA_NAME ] = true;
		}
		return $cart_item_data;
	}

	/**
	 * Add content gate metadata from the cart item to the order.
	 *
	 * @param \WC_Order_Item_Product $item The cart item.
	 * @param string                 $cart_item_key The cart item key.
	 * @param array                  $values The cart item values.
	 * @param \WC_Order              $order The order.
	 * @return void
	 */
	public static function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
		if ( ! empty( $values[ self::METADATA_NAME ] ) ) {
			$order->add_meta_data( '_memberships_content_gate', true );
		}
	}

	/**
	 * Add content gate metadata on reader registration.
	 *
	 * @param array     $metadata The metadata.
	 * @param int|false $user_id  The user ID or false if not created.
	 *
	 * @return array
	 */
	public static function register_reader_metadata( $metadata, $user_id ) {
		if ( isset( $_REQUEST[ self::METADATA_NAME ] ) && ! empty( $_REQUEST[ self::METADATA_NAME ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$metadata[ self::METADATA_NAME ] = true;
			$metadata['registration_method'] = 'registration-block-content-gate';
		}
		return $metadata;
	}

	/**
	 * Register listeners.
	 */
	public static function register_listeners() {
		/**
		 * Gate interaction: Registration membership
		 */
		Data_Events::register_listener(
			'newspack_reader_registration_form_processed',
			'gate_interaction',
			[ __CLASS__, 'registration_submission' ]
		);
		Data_Events::register_listener(
			'newspack_reader_registration_form_processed',
			'gate_interaction',
			[ __CLASS__, 'registration_submission_with_status' ]
		);

		/**
		 * Gate interaction: Paid membership
		 */
		Data_Events::register_listener(
			'woocommerce_checkout_order_processed',
			'gate_interaction',
			[ __CLASS__, 'woocommerce_checkout_order_processed' ]
		);
		Data_Events::register_listener(
			'woocommerce_order_status_failed',
			'gate_interaction',
			[ __CLASS__, 'woocommerce_order_status_failed' ]
		);
		Data_Events::register_listener(
			'woocommerce_order_status_completed',
			'gate_interaction',
			[ __CLASS__, 'woocommerce_order_status_completed' ]
		);
	}

	/**
	 * Get common metadata to be sent with all gate interaction events.
	 */
	private static function get_gate_metadata() {
		return [
			'gate_post_id' => NewspackMemberships::get_gate_post_id(),
		];
	}

	/**
	 * A listener for the registration block form submission
	 *
	 * Will trigger the event with "form_submission" as action in all cases.
	 *
	 * @param string              $email   Email address of the reader.
	 * @param int|false|\WP_Error $user_id The created user ID in case of registration, false if not created or a WP_Error object.
	 * @param array               $metadata Array with metadata about the user being registered.
	 * @return ?array
	 */
	public static function registration_submission( $email, $user_id, $metadata ) {
		if ( ! isset( $metadata[ self::METADATA_NAME ] ) ) {
			return;
		}
		$data = array_merge(
			self::get_gate_metadata(),
			[
				'action'      => self::FORM_SUBMISSION,
				'action_type' => 'registration',
				'referer'     => $metadata['referer'],
			]
		);
		$data['interaction_data']['registration_method'] = $metadata['registration_method'];
		return $data;
	}

	/**
	 * A listener for the registration block form submission
	 *
	 * Will trigger the event with "form_submission" as action in all cases.
	 *
	 * @param string              $email   Email address of the reader.
	 * @param int|false|\WP_Error $user_id The created user ID in case of registration, false if not created or a WP_Error object.
	 * @param array               $metadata Array with metadata about the user being registered.
	 * @return ?array
	 */
	public static function registration_submission_with_status( $email, $user_id, $metadata ) {
		if ( ! isset( $metadata[ self::METADATA_NAME ] ) ) {
			return;
		}
		$action = self::FORM_SUBMISSION_SUCCESS;
		if ( ! $user_id || \is_wp_error( $user_id ) ) {
			$action = self::FORM_SUBMISSION_FAILURE;
		}
		$data = array_merge(
			self::get_gate_metadata(),
			[
				'action'      => $action,
				'action_type' => 'registration',
				'referer'     => $metadata['referer'],
			]
		);
		$data['interaction_data']['registration_method'] = $metadata['registration_method'];
		return $data;
	}

	/**
	 * Get order data.
	 *
	 * @param int       $order_id The order ID.
	 * @param \WC_Order $order    The order object.
	 *
	 * @return ?array
	 */
	private static function get_order_data( $order_id, $order ) {
		$is_from_gate = $order->get_meta( '_memberships_content_gate' );
		if ( ! $is_from_gate ) {
			return;
		}
		$item = array_shift( $order->get_items() );
		$data = array_merge(
			self::get_gate_metadata(),
			[
				'action_type' => 'paid_membership',
				'order_id'    => $order_id,
				'product_id'  => $item->get_product_id(),
				'amount'      => (float) $order->get_total(),
				'currency'    => $order->get_currency(),
				'referer'     => $order->get_meta( '_newspack_referer' ),
			]
		);
		return $data;
	}

	/**
	 * A listener for when a WooCommerce order is processed.
	 *
	 * @param int $order_id The order ID.
	 *
	 * @return ?array
	 */
	public static function woocommerce_checkout_order_processed( $order_id ) {
		$order = \wc_get_order( $order_id );
		if ( ! \Newspack\WooCommerce_Connection::should_sync_order( $order ) ) {
			return;
		}
		$data = self::get_order_data( $order_id, $order );
		if ( empty( $data ) ) {
			return;
		}
		$data['action'] = self::FORM_SUBMISSION;
		return $data;
	}

	/**
	 * A listener for when a WooCommerce order has failed.
	 *
	 * @param int       $order_id The order ID.
	 * @param \WC_Order $order    The order object.
	 *
	 * @return ?array
	 */
	public static function woocommerce_order_status_failed( $order_id, $order ) {
		$data = self::get_order_data( $order_id, $order );
		if ( empty( $data ) ) {
			return;
		}
		$data['action'] = self::FORM_SUBMISSION_FAILURE;
		return $data;
	}

	/**
	 * A listener for when a WooCommerce order is completed.
	 *
	 * @param int       $order_id The order ID.
	 * @param \WC_Order $order    The order object.
	 *
	 * @return ?array
	 */
	public static function woocommerce_order_status_completed( $order_id, $order ) {
		$data = self::get_order_data( $order_id, $order );
		if ( empty( $data ) ) {
			return;
		}
		$data['action'] = self::FORM_SUBMISSION_SUCCESS;
		return $data;
	}
}
Memberships::init();
