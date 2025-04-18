<?php
/**
 * Reader Activation Sync Metadata.
 *
 * @package Newspack
 */

namespace Newspack\Reader_Activation\Sync;

use Newspack\Donations;
use Newspack\Reader_Activation;
use Newspack\Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Metadata Class.
 */
class Metadata {

	const DATE_FORMAT   = 'Y-m-d H:i:s';
	const PREFIX        = 'NP_';
	const PREFIX_OPTION = '_newspack_metadata_prefix';

	/**
	 * The option name for choosing which metadata fields to sync.
	 *
	 * @var string
	 */
	const FIELDS_OPTION = '_newspack_metadata_fields';

	/**
	 * Metadata keys map for Reader Activation.
	 *
	 * @var array
	 */
	public static $keys = [];

	/**
	 * Get the metadata keys map for Reader Activation.
	 *
	 * @return array List of fields.
	 */
	public static function get_keys() {
		if ( empty( self::$keys ) ) {
			// Only get Woo fields if using Woo.
			$fields = Donations::is_platform_wc() ? self::get_all_fields() : self::get_basic_fields();

			/**
			 * Filters the list of key/value pairs for metadata fields to be synced to the connected ESP.
			 *
			 * @param array $keys The list of key/value pairs for metadata fields to be synced to the connected ESP.
			 */
			self::$keys = \apply_filters( 'newspack_ras_metadata_keys', $fields );
		}
		return self::$keys;
	}

	/**
	 * Fetch the prefix for synced metadata fields.
	 * Default is NP_ but it can be configured in the Reader Activation settings page.
	 *
	 * @return string
	 */
	public static function get_prefix() {
		$prefix = \get_option( self::PREFIX_OPTION, self::PREFIX );

		// Guard against empty strings and falsy values.
		if ( empty( $prefix ) ) {
			return self::PREFIX;
		}

		/**
		 * Filters the string used to prefix custom fields synced.
		 *
		 * @param string $prefix Prefix to prepend the field name.
		 */
		return apply_filters( 'newspack_ras_metadata_prefix', $prefix );
	}

	/**
	 * Update the prefix for synced metadata fields.
	 *
	 * @param string $prefix Value to set.
	 *
	 * @return boolean True if updated, false otherwise.
	 */
	public static function update_prefix( $prefix ) {
		if ( empty( $prefix ) ) {
			$prefix = self::PREFIX;
		}

		return \update_option( self::PREFIX_OPTION, $prefix );
	}

	/**
	 * Get the list of possible fields to be synced.
	 *
	 * @return string[] List of fields.
	 */
	public static function get_default_fields() {
		return array_values( array_unique( array_values( self::get_keys() ) ) );
	}

	/**
	 * Get the list of fields to be synced.
	 *
	 * @return string[] List of fields to be synced.
	 */
	public static function get_fields() {
		return array_values( \get_option( self::FIELDS_OPTION, self::get_default_fields() ) );
	}

	/**
	 * Get enabled fields which match provided keys.
	 * Will return key-value pairs of enabled fields which match the keys provided.
	 *
	 * @param string[] $keys Array of keys to match.
	 */
	public static function filter_enabled_fields( $keys ) {
		$enabled_fields = self::get_fields();
		return array_filter(
			self::get_keys(),
			function( $val, $key ) use ( $keys, $enabled_fields ) {
				return in_array( $key, $keys ) && in_array( $val, $enabled_fields );
			},
			ARRAY_FILTER_USE_BOTH
		);
	}

	/**
	 * Update the list of fields to be synced.
	 *
	 * @param array $fields List of fields to sync.
	 *
	 * @return boolean True if updated, false otherwise.
	 */
	public static function update_fields( $fields ) {
		// Only allow fields that are in the metadata keys map.
		$fields = array_intersect( self::get_default_fields(), $fields );
		return \update_option( self::FIELDS_OPTION, array_values( $fields ) );
	}

	/**
	 * Get the "raw" unprefixed metadata keys. Only return fields selected to sync.
	 *
	 * @return string[] List of raw metadata keys.
	 */
	public static function get_raw_keys() {
		$fields_to_sync = self::get_fields();
		$raw_keys       = [];

		foreach ( self::get_keys() as $raw_key => $field_name ) {
			if ( in_array( $field_name, $fields_to_sync, true ) ) {
				$raw_keys[] = $raw_key;
			}
		}

		return array_unique( $raw_keys );
	}

	/**
	 * Get the "prefixed" metadata keys. Only return fields selected to sync.
	 *
	 * @return string[] List of prefixed metadata keys.
	 */
	public static function get_prefixed_keys() {
		$fields_to_sync = self::get_fields();
		$prefixed_keys  = [];

		foreach ( self::get_keys() as $raw_key => $field_name ) {
			if ( in_array( $field_name, $fields_to_sync, true ) ) {
				$prefixed_keys[] = self::get_key( $raw_key );
			}
		}

		return array_unique( $prefixed_keys );
	}

	/**
	 * Given a field name, prepend it with the metadata field prefix.
	 *
	 * @param string $key Metadata field to fetch.
	 *
	 * @return string Prefixed field name.
	 */
	public static function get_key( $key ) {
		if ( ! isset( self::get_keys()[ $key ] ) ) {
			return false;
		}

		$prefix = self::get_prefix();
		$name   = self::get_keys()[ $key ];
		$key    = $prefix . $name;

		/**
		 * Filters the full, prefixed field name of each custom field synced to the ESP.
		 *
		 * @param string $key Full, prefixed key.
		 * @param string $prefix The prefix part of the key.
		 * @param string $name The unprefixed part of the key.
		 */
		return apply_filters( 'newspack_ras_metadata_key', $key, $prefix, $name );
	}

	/**
	 * Get basic metadata fields.
	 *
	 * @return array List of fields.
	 */
	public static function get_basic_fields() {
		return [
			'account'              => 'Account',
			'registration_date'    => 'Registration Date',
			'connected_account'    => 'Connected Account',
			'signup_page_utm'      => 'Signup UTM: ',
			'newsletter_selection' => 'Newsletter Selection',
			'referer'              => 'Referrer Path',
			'registration_page'    => 'Registration Page',
			'current_page_url'     => 'Registration Page',
			'registration_method'  => 'Registration Method',
		];
	}

	/**
	 * Get payment-related metadata fields.
	 *
	 * @return array List of fields.
	 */
	public static function get_payment_fields() {
		return [
			'membership_status'   => 'Membership Status',
			// URL of the page on which the payment has happened.
			'payment_page'        => 'Payment Page',
			'payment_page_utm'    => 'Payment UTM: ',
			'sub_start_date'      => 'Current Subscription Start Date',
			'sub_end_date'        => 'Current Subscription End Date',
			'cancellation_reason' => 'Subscription Cancellation Reason',
			// At what interval does the recurring payment occur – e.g. day, week, month or year.
			'billing_cycle'       => 'Billing Cycle',
			// The total value of the recurring payment.
			'recurring_payment'   => 'Recurring Payment',
			'last_payment_date'   => 'Last Payment Date',
			'last_payment_amount' => 'Last Payment Amount',
			// Product name, as it appears in WooCommerce.
			'product_name'        => 'Product Name',
			'next_payment_date'   => 'Next Payment Date',
			// Total value spent by this customer on the site.
			'total_paid'          => 'Total Paid',
		];
	}

	/**
	 * Get all metadata fields.
	 *
	 * @return array List of fields.
	 */
	public static function get_all_fields() {
		return array_merge( self::get_basic_fields(), self::get_payment_fields() );
	}

	/**
	 * Check if a metadata key exists in the given metadata.
	 *
	 * This method checks for both raw and prefixed keys.
	 *
	 * @param string $key      Metadata key to check.
	 * @param array  $metadata Metadata to check.
	 *
	 * @return boolean
	 */
	private static function has_key( $key, $metadata ) {
		return isset( $metadata[ $key ] ) || isset( $metadata[ self::get_key( $key ) ] );
	}

	/**
	 * Get a metadata key value from the given metadata.
	 *
	 * This method checks for both raw and prefixed keys.
	 *
	 * @param string $key      Metadata key to fetch.
	 * @param array  $metadata Metadata to fetch from.
	 *
	 * @return mixed|null Metadata value or null if not found.
	 */
	private static function get_key_value( $key, $metadata ) {
		if ( isset( $metadata[ $key ] ) ) {
			return $metadata[ $key ];
		}
		if ( isset( $metadata[ self::get_key( $key ) ] ) ) {
			return $metadata[ self::get_key( $key ) ];
		}
		return null;
	}

	/**
	 * Get the UTM key from a raw or prefixed key.
	 * The returned key must have a suffix (source, medium, campaign, content).
	 *
	 * @param string $key Key to check.
	 *
	 * @return string|false Formatted key if it is a UTM key, false otherwise.
	 */
	public static function get_utm_key( $key ) {
		$keys     = [ 'signup_page_utm', 'payment_page_utm' ];
		$raw_keys = self::get_raw_keys();
		foreach ( $keys as $utm_key ) {
			if ( ! in_array( $utm_key, $raw_keys, true ) ) { // Skip if the UTM key is not in the list of fields to sync.
				continue;
			}
			$prefixed_key = self::get_key( $utm_key );
			if ( 0 === strpos( $key, $utm_key ) ) {
				$suffix = str_replace( $utm_key . '_', '', $key );
				return ! empty( trim( $suffix ) ) && $suffix !== $key ? $prefixed_key . $suffix : false;
			}
			if ( 0 === strpos( $key, $prefixed_key ) && $key !== $prefixed_key ) {
				return $key;
			}
		}
		return false;
	}

	/**
	 * Add user's registration-related data to the given metadata.
	 * These won't be included in every sync request, but they might be stored as user meta.
	 *
	 * @param array $metadata Metadata to add to.
	 *
	 * @return array Metadata with registration data added.
	 */
	private static function add_registration_data( $metadata ) {
		$user = self::has_key( 'account', $metadata ) ? \get_user_by( 'id', self::get_key_value( 'account', $metadata ) ) : false;
		if ( ! $user ) {
			return $metadata;
		}

		$registration_method = self::has_key( 'registration_method', $metadata ) ? self::get_key_value( 'registration_method', $metadata ) : \get_user_meta( $user->ID, Reader_Activation::REGISTRATION_METHOD, true );
		if ( ! empty( $registration_method ) ) {
			$metadata['registration_method'] = $registration_method;
		}

		$connected_account = self::has_key( 'connected_account', $metadata ) ? self::get_key_value( 'connected_account', $metadata ) : \get_user_meta( $user->ID, Reader_Activation::CONNECTED_ACCOUNT, true );
		if ( ! empty( $connected_account ) && in_array( $connected_account, Reader_Activation::SSO_REGISTRATION_METHODS ) ) {
			$metadata['connected_account'] = $connected_account;
		} elseif ( ! empty( $registration_method ) && in_array( $registration_method, Reader_Activation::SSO_REGISTRATION_METHODS ) ) {
			$metadata['connected_account'] = $registration_method;
		}

		return $metadata;
	}

	/**
	 * Add UTM fields to the given metadata.
	 *
	 * @param array $metadata Metadata to add to.
	 *
	 * @return array Metadata with UTM fields added.
	 */
	public static function add_utm_data( $metadata ) {
		// Capture UTM params and signup/payment page URLs as meta for registration or payment.
		if ( self::has_key( 'current_page_url', $metadata ) || self::has_key( 'registration_page', $metadata ) || self::has_key( 'payment_page', $metadata ) ) {
			$payment_page = self::has_key( 'payment_page', $metadata ) ? self::get_key_value( 'payment_page', $metadata ) : false;
			$raw_url    = false;
			if ( ! empty( $payment_page ) ) {
				$raw_url = self::get_key_value( 'payment_page', $metadata );
			} elseif ( self::has_key( 'current_page_url', $metadata ) ) {
				$raw_url = self::get_key_value( 'current_page_url', $metadata );
			} else {
				$raw_url = self::get_key_value( 'registration_page', $metadata );
			}

			$parsed_url = \wp_parse_url( $raw_url );

			// Maybe set UTM meta.
			if ( ! empty( $parsed_url['query'] ) ) {
				$utm_key_prefix = ! empty( $payment_page ) ? 'payment_page_utm' : 'signup_page_utm';
				$params         = [];
				\wp_parse_str( $parsed_url['query'], $params );
				foreach ( $params as $param => $value ) {
					$param = \sanitize_text_field( $param );
					if ( 'utm' === substr( $param, 0, 3 ) ) {
						$param = str_replace( 'utm_', '', $param );
						$key   = self::get_key( $utm_key_prefix ) . $param;
						if ( ! isset( $metadata[ $key ] ) || empty( $metadata[ $key ] ) ) {
							$metadata[ $key ] = $value;
						}
					}
				}
			}
		}

		return $metadata;
	}

	/**
	 * Normalizes contact metadata keys before syncing to ESP.
	 *
	 * @param array $contact Contact data.
	 * @return array Normalized contact data.
	 */
	public static function normalize_contact_data( $contact ) {
		if ( ! isset( $contact['metadata'] ) ) {
			$contact['metadata'] = [];
		}

		$metadata            = $contact['metadata'];
		$metadata            = self::add_registration_data( $metadata );
		$metadata            = self::add_utm_data( $metadata );
		$raw_keys            = self::get_raw_keys();
		$prefixed_keys       = self::get_prefixed_keys();
		$normalized_metadata = [];

		// Keys allowed to pass through without prefixing.
		$allowed_keys = [ 'status', 'status_if_new' ];

		// UTM keys must be suffixed.
		$disallowed_keys = [
			'payment_page_utm',
			'payment_page_utm_',
			'signup_page_utm',
			'signup_page_utm_',
			self::get_key( 'payment_page_utm' ),
			self::get_key( 'signup_page_utm' ),
		];

		foreach ( $metadata as $meta_key => $meta_value ) {
			if ( in_array( $meta_key, $raw_keys, true ) && ! in_array( $meta_key, $disallowed_keys, true ) ) { // Handle raw keys.
				$normalized_metadata[ self::get_key( $meta_key ) ] = $meta_value;
			} elseif ( in_array( $meta_key, $prefixed_keys, true ) && ! in_array( $meta_key, $disallowed_keys, true ) ) { // Handle prefixed keys.
				$normalized_metadata[ $meta_key ] = $meta_value;
			} elseif ( self::get_utm_key( $meta_key ) ) { // Handle UTM keys.
				$normalized_metadata[ self::get_utm_key( $meta_key ) ] = $meta_value;
			} elseif ( in_array( $meta_key, $allowed_keys, true ) ) { // Handle allowed keys.
				$normalized_metadata[ $meta_key ] = $meta_value;
			} else { // If the key is not in the list of fields to sync, ignore it.
				Logger::log( 'Ignoring metadata key: ' . $meta_key );
			}
		}

		$contact['metadata'] = $normalized_metadata;

		Logger::log( 'Normalizing contact data for reader ESP sync:' );
		Logger::log( $contact );

		/**
		 * Filters the normalized contact data before syncing to the ESP.
		 *
		 * @param array $contact Contact data.
		 */
		return apply_filters( 'newspack_esp_sync_normalize_contact', $contact );
	}
}
