<?php
/**
 * Newspack Data Events Mailchimp Connector
 *
 * @package Newspack
 */

namespace Newspack\Data_Events\Connectors;

use Newspack\Logger;
use Newspack\Data_Events;
use Newspack\Mailchimp_API;
use Newspack\Newspack_Newsletters;
use Newspack\Reader_Activation;
use Newspack\WooCommerce_Connection;
use Newspack\Donations;

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class Mailchimp {
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
			'mailchimp' === \Newspack_Newsletters::service_provider()
		) {
			Data_Events::register_handler( [ __CLASS__, 'reader_registered' ], 'reader_registered' );
			Data_Events::register_handler( [ __CLASS__, 'reader_logged_in' ], 'reader_logged_in' );
			Data_Events::register_handler( [ __CLASS__, 'order_completed' ], 'order_completed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'donation_subscription_changed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'product_subscription_changed' );
		}
	}

	/**
	 * Get audience ID.
	 *
	 * @return string|bool Audience ID or false if not set.
	 */
	private static function get_audience_id() {
		$audience_id = Reader_Activation::get_setting( 'mailchimp_audience_id' );
		/** Attempt to use list ID from "Mailchimp for WooCommerce" */
		if ( ! $audience_id && function_exists( 'mailchimp_get_list_id' ) ) {
			$audience_id = mailchimp_get_list_id();
		}
		return ! empty( $audience_id ) ? $audience_id : false;
	}

	/**
	 * Get merge field type.
	 *
	 * @param mixed $value Value to check.
	 *
	 * @return string Merge field type.
	 */
	private static function get_merge_field_type( $value ) {
		if ( is_numeric( $value ) ) {
			return 'number';
		}
		if ( is_bool( $value ) ) {
			return 'boolean';
		}
		return 'text';
	}

	/**
	 * Get merge fields given data.
	 *
	 * @param string $audience_id Audience ID.
	 * @param array  $data        Data to check.
	 *
	 * @return array Merge fields.
	 */
	private static function get_merge_fields( $audience_id, $data ) {
		$merge_fields = [];

		// Strip arrays.
		$data = array_filter(
			$data,
			function( $value ) {
				return ! is_array( $value );
			}
		);

		// Get and match existing merge fields.
		$merge_fields_res = Mailchimp_API::get( "lists/$audience_id/merge-fields?count=1000" );
		if ( \is_wp_error( $merge_fields_res ) ) {
			Logger::log(
				sprintf(
					// Translators: %1$s is the error message.
					__( 'Error getting merge fields: %1$s', 'newspack-plugin' ),
					$merge_fields_res->get_error_message()
				)
			);
			return [];
		}
		$existing_fields = $merge_fields_res['merge_fields'];
		usort(
			$existing_fields,
			function( $a, $b ) {
				return $a['merge_id'] - $b['merge_id'];
			}
		);

		$list_merge_fields = [];

		// Handle duplicate fields.
		foreach ( $existing_fields as $field ) {
			if ( ! isset( $list_merge_fields[ $field['name'] ] ) ) {
				$list_merge_fields[ $field['name'] ] = $field['tag'];
			} else {
				Logger::log(
					sprintf(
						// Translators: %1$s is the merge field name, %2$s is the field's unique tag.
						__( 'Warning: Duplicate merge field %1$s found with tag %2$s.', 'newspack-plugin' ),
						$field['name'],
						$field['tag']
					)
				);
			}
		}

		foreach ( $data as $field_name => $field_value ) {
			// If field already exists, add it to the payload.
			if ( isset( $list_merge_fields[ $field_name ] ) ) {
				$merge_fields[ $list_merge_fields[ $field_name ] ] = $data[ $field_name ];
				unset( $data[ $field_name ] );
			}
		}

		// Create remaining fields.
		$remaining_fields = array_keys( $data );
		foreach ( $remaining_fields as $field_name ) {
			$created_field = Mailchimp_API::post(
				"lists/$audience_id/merge-fields",
				[
					'name' => $field_name,
					'type' => self::get_merge_field_type( $data[ $field_name ] ),
				]
			);
			// Skip field if it failed to create.
			if ( is_wp_error( $created_field ) ) {
				continue;
			}
			Logger::log(
				sprintf(
					// Translators: %1$s is the merge field key, %2$s is the error message.
					__( 'Created merge field %1$s.', 'newspack-plugin' ),
					$field_name
				)
			);
			$merge_fields[ $created_field['tag'] ] = $data[ $field_name ];
		}

		return $merge_fields;
	}

	/**
	 * Update a Mailchimp contact
	 *
	 * @param array $contact Contact info to sync to ESP without lists.
	 *
	 * @return array|WP_Error response body or error.
	 */
	public static function put( $contact ) {
		$audience_id = self::get_audience_id();
		if ( ! $audience_id ) {
			return;
		}
		$hash    = md5( strtolower( $contact['email'] ) );
		$payload = [
			'email_address' => $contact['email'],
			'status_if_new' => 'transactional',
		];

		// Normalize contact metadata.
		$contact = Newspack_Newsletters::normalize_contact_data( $contact );
		if ( ! empty( $contact['metadata'] ) ) {
			$merge_fields = self::get_merge_fields( $audience_id, $contact['metadata'] );
			if ( ! empty( $merge_fields ) ) {
				$payload['merge_fields'] = $merge_fields;
			}
		}

		Logger::log(
			'Syncing contact with metadata key(s): ' . implode( ', ', array_keys( $contact['metadata'] ) ) . '.',
			Data_Events::LOGGER_HEADER
		);

		// Upsert the contact.
		return Mailchimp_API::put(
			"lists/$audience_id/members/$hash",
			$payload
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
}
new Mailchimp();
