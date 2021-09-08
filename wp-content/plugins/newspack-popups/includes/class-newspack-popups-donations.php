<?php
/**
 * Newspack Popups Donations
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/../api/segmentation/class-segmentation.php';

/**
 * Main Newspack Popups Donations Class.
 */
final class Newspack_Popups_Donations {
	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Popups_Donations
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Popups Donations Instance.
	 * Ensures only one instance of Newspack Popups Donations Instance is loaded or can be loaded.
	 *
	 * @return Newspack Popups Donations Instance - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ __CLASS__, 'rest_api_init' ] );
		add_action( 'admin_init', [ __CLASS__, 'create_wc_webhook' ] );
		add_action( 'woocommerce_checkout_update_order_meta', [ __CLASS__, 'woocommerce_checkout_update_order_meta' ] );
		add_filter( 'woocommerce_billing_fields', [ __CLASS__, 'woocommerce_billing_fields' ] );
	}

	/**
	 * Initialise REST API endpoints.
	 */
	public static function rest_api_init() {
		\register_rest_route(
			'newspack-popups/v1',
			'woocommerce-sync',
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'api_woocommerce_sync' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * Create a WooCommerce webhook.
	 */
	public static function create_wc_webhook() {
		if ( ! class_exists( 'WC_Webhook' ) || ! class_exists( 'WC_Data_Store' ) ) {
			return;
		}

		$webhook_endpoint     = 'newspack-popups/v1/woocommerce-sync';
		$webhook_delivery_url = get_rest_url( null, $webhook_endpoint );

		// Find the webhook with matching endpoint. If the delivery URL is not as expected,
		// update the webhook. This might happen after a site is migrated.
		// Otherwise, if such webhook is not found, create it.
		$data_store            = WC_Data_Store::load( 'webhook' );
		$matching_webhooks     = array_values(
			array_filter(
				$data_store->search_webhooks(),
				function( $webhook_id ) use ( $webhook_endpoint ) {
					$webhook = wc_get_webhook( $webhook_id );
					return false !== stripos( $webhook->get_delivery_url(), $webhook_endpoint );
				}
			)
		);
		$should_upsert_webhook = false;
		if ( 0 !== count( $matching_webhooks ) ) {
			$webhook               = wc_get_webhook( $matching_webhooks[0] );
			$should_upsert_webhook = $webhook->get_delivery_url() !== $webhook_delivery_url;
		} else {
			$webhook               = new \WC_Webhook();
			$should_upsert_webhook = true;
		}

		if ( $should_upsert_webhook ) {
			$webhook->set_name( 'Sync to Newspack Campaigns' );
			$webhook->set_topic( 'order.created' );
			$webhook->set_delivery_url( $webhook_delivery_url );
			$webhook->set_status( 'active' );
			$webhook->set_user_id( get_current_user_id() );
			$webhook->save();
		}
	}

	/**
	 * Webhook callback handler for syncing WooCommerce data.
	 *
	 * @param WP_REST_Request $request Request containing webhook.
	 */
	public static function api_woocommerce_sync( $request ) {
		$wc_order_data = $request->get_params();
		if ( ! isset( $wc_order_data['id'] ) ) {
			return;
		}

		// If there was a donation, update the client data.
		Newspack_Popups_Segmentation::update_client_data(
			get_post_meta( $wc_order_data['id'], Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME, true ),
			[
				'donation' => [
					'order_id' => $wc_order_data['id'],
					'date'     => date_format( date_create( $wc_order_data['date_created'] ), 'Y-m-d' ),
					'amount'   => $wc_order_data['total'],
				],
			]
		);
	}

	/**
	 * Update WC order with the client id from hidden form field.
	 *
	 * @param String $order_id WC order id.
	 */
	public static function woocommerce_checkout_update_order_meta( $order_id ) {
		if ( ! empty( $_POST[ Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			update_post_meta( $order_id, Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME, sanitize_text_field( $_POST[ Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		}
	}

	/**
	 * Add a hidden billing field with client id.
	 *
	 * @param Array $form_fields WC form fields.
	 */
	public static function woocommerce_billing_fields( $form_fields ) {
		$client_id = Newspack_Popups_Segmentation::get_client_id();

		if ( $client_id ) {
			$form_fields[ Newspack_Popups_Segmentation::NEWSPACK_SEGMENTATION_CID_NAME ] = [
				'type'    => 'text',
				'default' => $client_id,
				'class'   => [ 'hide' ],
			];
		}

		return $form_fields;
	}
}
Newspack_Popups_Donations::instance();
