<?php
/**
 * Abstract Newspack Data Events Connector class
 *
 * @package Newspack
 */

namespace Newspack\Data_Events\Connectors;

use Newspack\Newspack_Newsletters;
use Newspack\WooCommerce_Connection;

defined( 'ABSPATH' ) || exit;

/**
 * Standard methods shared by all connectors.
 */
abstract class Connector {
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
		/**
		 * Filters the contact metadata sent to the ESP when a reader account is registered for the first time.
		 *
		 * @param array $metadata The contact metadata.
		 * @param int   $user_id The ID of the user.
		 *
		 * @return array The modified contact metadata.
		 */
		$metadata = \apply_filters( 'newspack_data_events_reader_registered_metadata', $metadata, $data['user_id'] );
		$contact  = [
			'email'    => $data['email'],
			'metadata' => $metadata,
		];

		static::put( $contact );
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

		static::put( $contact );
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

		static::put( $contact );
	}

	/**
	 * Handle a change in subscription status.
	 *
	 * @param int   $timestamp Timestamp of the event.
	 * @param array $data      Data associated with the event.
	 * @param int   $client_id ID of the client that triggered the event.
	 */
	public static function subscription_updated( $timestamp, $data, $client_id ) {
		if ( empty( $data['status_before'] ) || empty( $data['status_after'] ) || empty( $data['subscription_id'] ) ) {
			return;
		}

		$contact = WooCommerce_Connection::get_contact_from_order( $data['subscription_id'] );

		if ( ! $contact ) {
			return;
		}

		static::put( $contact );
	}
}
