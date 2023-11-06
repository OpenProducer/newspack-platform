<?php
/**
 * Newspack Data Events ActiveCampaign Connector
 *
 * @package Newspack
 */

namespace Newspack\Data_Events\Connectors;

use \Newspack\Data_Events;
use \Newspack\Newspack_Newsletters;
use \Newspack\Reader_Activation;
use \Newspack\WooCommerce_Connection;
use \Newspack\Donations;

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class ActiveCampaign {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'register_handlers' ] );
	}

	/**
	 * Register handlers.
	 */
	public static function register_handlers() {
		if ( ! method_exists( 'Newspack_Newsletters', 'get_service_provider' ) ) {
			return;
		}
		$provider = \Newspack_Newsletters::get_service_provider();
		if (
			Reader_Activation::is_enabled() &&
			true === Reader_Activation::get_setting( 'sync_esp' ) &&
			$provider && 'active_campaign' === $provider->service
		) {
			Data_Events::register_handler( [ __CLASS__, 'reader_registered' ], 'reader_registered' );
			Data_Events::register_handler( [ __CLASS__, 'donation_new' ], 'donation_new' );
			Data_Events::register_handler( [ __CLASS__, 'donation_subscription_new' ], 'donation_subscription_new' );
			Data_Events::register_handler( [ __CLASS__, 'newsletter_updated' ], 'newsletter_updated' );
			Data_Events::register_handler( [ __CLASS__, 'newsletter_subscribed' ], 'newsletter_updated' );
		}
	}

	/**
	 * Upsert the contact.
	 *
	 * @param string $email    Email address.
	 * @param array  $metadata Metadata to add to the contact.
	 */
	private static function put( $email, $metadata ) {
		$master_list_id = Reader_Activation::get_setting( 'active_campaign_master_list' );
		if ( ! $master_list_id ) {
			return;
		}
		\Newspack_Newsletters_Subscription::add_contact(
			[
				'email'    => $email,
				'metadata' => $metadata,
			],
			$master_list_id
		);
	}

	/**
	 * Handle a reader registering.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function reader_registered( $timestamp, $data, $client_id ) {
		$prefix                = Newspack_Newsletters::get_metadata_prefix();
		$account_key           = Newspack_Newsletters::get_metadata_key( 'account' );
		$registration_date_key = Newspack_Newsletters::get_metadata_key( 'registration_date' );
		$metadata              = [
			$account_key           => $data['user_id'],
			$registration_date_key => gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $timestamp ),
		];
		if ( isset( $data['metadata']['current_page_url'] ) ) {
			$metadata[ $prefix . 'Registration Page' ] = $data['metadata']['current_page_url'];
		}
		if ( isset( $data['metadata']['registration_method'] ) ) {
			$metadata[ $prefix . 'Registration Method' ] = $data['metadata']['registration_method'];
		}
		self::put( $data['email'], $metadata );
	}

	/**
	 * Handle a donation being made.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function donation_new( $timestamp, $data, $client_id ) {
		if ( ! isset( $data['platform_data']['order_id'] ) ) {
			return;
		}

		$order_id = $data['platform_data']['order_id'];
		$contact  = WooCommerce_Connection::get_contact_from_order( $order_id );

		if ( ! $contact ) {
			return;
		}

		$email         = $contact['email'];
		$metadata      = $contact['metadata'];
		$keys          = Newspack_Newsletters::$metadata_keys;
		$prefixed_keys = array_map(
			function( $key ) {
				return Newspack_Newsletters::get_metadata_key( $key );
			},
			array_values( array_flip( $keys ) )
		);

		// Only use metadata defined in 'Newspack_Newsletters'.
		$metadata = array_intersect_key( $metadata, array_flip( $prefixed_keys ) );

		// Remove "product name" from metadata, we'll use
		// 'donation_subscription_new' action for this data.
		unset( $metadata[ Newspack_Newsletters::get_metadata_key( 'product_name' ) ] );

		self::put( $email, $metadata );
	}

	/**
	 * Handle a new subscription.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function donation_subscription_new( $timestamp, $data, $client_id ) {
		if ( empty( $data['platform_data']['order_id'] ) ) {
			return;
		}
		$account_key  = Newspack_Newsletters::get_metadata_key( 'account' );
		$metadata     = [
			$account_key => $data['user_id'],
		];
		$order_id     = $data['platform_data']['order_id'];
		$product_id   = Donations::get_order_donation_product_id( $order_id );
		$product_name = get_the_title( $product_id );

		$key              = Newspack_Newsletters::get_metadata_key( 'product_name' );
		$metadata[ $key ] = $product_name;

		self::put( $data['email'], $metadata );
	}

	/**
	 * Handle newsletter subscription update.
	 *
	 * @param int   $timestamp Timestamp.
	 * @param array $data      Data.
	 */
	public static function newsletter_updated( $timestamp, $data ) {
		if ( empty( $data['user_id'] ) || empty( $data['email'] ) ) {
			return;
		}
		if ( ! class_exists( '\Newspack_Newsletters' ) || ! class_exists( '\Newspack_Newsletters_Subscription' ) ) {
			return;
		}
		$subscribed_lists = \Newspack_Newsletters_Subscription::get_contact_lists( $data['email'] );
		if ( is_wp_error( $subscribed_lists ) || ! is_array( $subscribed_lists ) ) {
			return;
		}
		$lists = \Newspack_Newsletters_Subscription::get_lists();
		if ( is_wp_error( $lists ) ) {
			return;
		}
		$lists_names = [];
		foreach ( $subscribed_lists as $subscribed_list_id ) {
			foreach ( $lists as $list ) {
				if ( $list['id'] === $subscribed_list_id ) {
					$lists_names[] = $list['name'];
				}
			}
		}

		$account_key              = Newspack_Newsletters::get_metadata_key( 'account' );
		$newsletter_selection_key = Newspack_Newsletters::get_metadata_key( 'newsletter_selection' );

		$metadata = [
			$account_key              => $data['user_id'],
			$newsletter_selection_key => implode( ', ', $lists_names ),
		];
		self::put( $data['email'], $metadata );
	}
}
new ActiveCampaign();
