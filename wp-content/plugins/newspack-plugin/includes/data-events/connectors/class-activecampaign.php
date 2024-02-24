<?php
/**
 * Newspack Data Events ActiveCampaign Connector
 *
 * @package Newspack
 */

namespace Newspack\Data_Events\Connectors;

use Newspack\Data_Events;
use Newspack\Newspack_Newsletters;
use Newspack\Reader_Activation;
use Newspack\WooCommerce_Connection;
use Newspack\Donations;

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
		if ( ! method_exists( 'Newspack_Newsletters', 'service_provider' ) ) {
			return;
		}
		if (
			Reader_Activation::is_enabled() &&
			true === Reader_Activation::get_setting( 'sync_esp' ) &&
			'active_campaign' === \Newspack_Newsletters::service_provider()
		) {
			Data_Events::register_handler( [ __CLASS__, 'reader_registered' ], 'reader_registered' );
			Data_Events::register_handler( [ __CLASS__, 'reader_logged_in' ], 'reader_logged_in' );
			Data_Events::register_handler( [ __CLASS__, 'order_completed' ], 'order_completed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'donation_subscription_changed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'product_subscription_changed' );
			Data_Events::register_handler( [ __CLASS__, 'newsletter_updated' ], 'newsletter_subscribed' );
			Data_Events::register_handler( [ __CLASS__, 'newsletter_updated' ], 'newsletter_updated' );
		}
	}

	/**
	 * Upsert the contact.
	 *
	 * @param array $contact Contact info to sync to ESP.
	 */
	private static function put( $contact ) {
		$master_list_id = Reader_Activation::get_setting( 'active_campaign_master_list' );
		if ( ! $master_list_id ) {
			return;
		}
		\Newspack_Newsletters_Subscription::add_contact( $contact, $master_list_id );
	}

	/**
	 * Handle a reader registering.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function reader_registered( $timestamp, $data, $client_id ) {
		$account_key           = Newspack_Newsletters::get_metadata_key( 'account' );
		$registration_date_key = Newspack_Newsletters::get_metadata_key( 'registration_date' );
		$metadata              = [
			$account_key           => $data['user_id'],
			$registration_date_key => gmdate( Newspack_Newsletters::METADATA_DATE_FORMAT, $timestamp ),
		];
		if ( isset( $data['metadata']['current_page_url'] ) ) {
			$metadata[ Newspack_Newsletters::get_metadata_key( 'registration_page' ) ] = $data['metadata']['current_page_url'];
		}
		if ( isset( $data['metadata']['registration_method'] ) ) {
			$metadata[ Newspack_Newsletters::get_metadata_key( 'registration_method' ) ] = $data['metadata']['registration_method'];
		}
		$contact = [
			'email'    => $data['email'],
			'metadata' => $metadata,
		];
		self::put( $contact );
	}

	/**
	 * Sync reader data on login.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function reader_logged_in( $timestamp, $data, $client_id ) {
		if ( empty( $data['email'] ) || empty( $data['user_id'] ) ) {
			return;
		}

		$customer = new \WC_Customer( $data['user_id'] );

		// If user is not a Woo customer, don't need to sync them.
		if ( ! $customer->get_order_count() ) {
			return;
		}

		$contact = WooCommerce_Connection::get_contact_from_customer( $customer );

		self::put( $contact );
	}

	/**
	 * Handle a completed order of any type.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function order_completed( $timestamp, $data, $client_id ) {
		if ( ! isset( $data['platform_data']['order_id'] ) ) {
			return;
		}

		$order_id = $data['platform_data']['order_id'];
		$contact  = WooCommerce_Connection::get_contact_from_order( $order_id, $data['referer'], true );

		if ( ! $contact ) {
			return;
		}

		self::put( $contact );
	}

	/**
	 * Handle a change in subscription status.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function subscription_updated( $timestamp, $data, $client_id ) {
		if ( empty( $data['status_before'] ) || empty( $data['status_after'] ) || empty( $data['user_id'] ) ) {
			return;
		}

		/*
		 * If the subscription is being activated after a successful first or renewal payment,
		 * the contact will be synced when that order is completed, so no need to sync again.
		 */
		if (
			( 'pending' === $data['status_before'] || 'on-hold' === $data['status_before'] ) &&
			'active' === $data['status_after'] ) {
			return;
		}

		$customer = new \WC_Customer( $data['user_id'] );
		$contact  = WooCommerce_Connection::get_contact_from_customer( $customer );

		if ( ! $contact ) {
			return;
		}

		self::put( $contact );
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
		$contact  = [
			'email'    => $data['email'],
			'metadata' => $metadata,
		];
		self::put( $contact );
	}
}
new ActiveCampaign();
