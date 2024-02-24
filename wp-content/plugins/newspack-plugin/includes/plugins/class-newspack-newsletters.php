<?php
/**
 * Newspack Newsletters integration class.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Main class.
 */
class Newspack_Newsletters {
	const METADATA_DATE_FORMAT   = 'Y-m-d';
	const METADATA_PREFIX        = 'NP_';
	const METADATA_PREFIX_OPTION = '_newspack_metadata_prefix';


	/**
	 * Metadata keys map for Reader Activation.
	 *
	 * @var array
	 */
	public static $metadata_keys = [
		'account'              => 'Account',
		'registration_date'    => 'Registration Date',
		'connected_account'    => 'Connected Account',
		'signup_page'          => 'Signup Page',
		'signup_page_utm'      => 'Signup UTM: ',
		'newsletter_selection' => 'Newsletter Selection',
		'referer'              => 'Referrer Path',
		'registration_page'    => 'Registration Page',
		'current_page_url'     => 'Registration Page',
		'registration_method'  => 'Registration Method',

		// Payment-related.
		'membership_status'    => 'Membership Status',
		'payment_page'         => 'Payment Page',
		'payment_page_utm'     => 'Payment UTM: ',
		'sub_start_date'       => 'Current Subscription Start Date',
		'sub_end_date'         => 'Current Subscription End Date',
		'billing_cycle'        => 'Billing Cycle',
		'recurring_payment'    => 'Recurring Payment',
		'last_payment_date'    => 'Last Payment Date',
		'last_payment_amount'  => 'Last Payment Amount',
		'product_name'         => 'Product Name',
		'next_payment_date'    => 'Next Payment Date',
		'total_paid'           => 'Total Paid',
	];

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		\add_filter( 'newspack_newsletters_contact_data', [ __CLASS__, 'normalize_contact_data' ] );

		if ( self::should_sync_ras_metadata() ) {
			\add_filter( 'newspack_newsletters_contact_lists', [ __CLASS__, 'add_activecampaign_master_list' ], 10, 3 );
		}
	}

	/**
	 * Whether or not we should use the special metadata keys for RAS sites.
	 *
	 * @return boolean True if a RAS sync, otherwise false.
	 */
	public static function should_sync_ras_metadata() {
		return Reader_Activation::is_enabled() && Reader_Activation::get_setting( 'sync_esp' );
	}

	/**
	 * Fetch the prefix for synced metadata fields.
	 * Default is NP_ but it can be configured in the Reader Activation settings page.
	 *
	 * @return string
	 */
	public static function get_metadata_prefix() {
		$prefix = \get_option( self::METADATA_PREFIX_OPTION, self::METADATA_PREFIX );

		// Guard against empty strings and falsy values.
		if ( empty( $prefix ) ) {
			return self::METADATA_PREFIX;
		}

		/**
		 * Filters the string used to prefix custom fields synced to Newsletter ESPs.
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
	public static function update_metadata_prefix( $prefix ) {
		if ( empty( $prefix ) ) {
			$prefix = self::METADATA_PREFIX;
		}

		return \update_option( self::METADATA_PREFIX_OPTION, $prefix );
	}

	/**
	 * Given a field name, prepend it with the metadata field prefix.
	 *
	 * @param string $key Metadata field to fetch.
	 *
	 * @return string Prefixed field name.
	 */
	public static function get_metadata_key( $key ) {
		if ( ! isset( self::$metadata_keys[ $key ] ) ) {
			return false;
		}

		$prefix = self::get_metadata_prefix();
		$name   = self::$metadata_keys[ $key ];
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
	 * Normalizes contact metadata keys before syncing to ESP. If RAS is enabled, we should favor RAS metadata keys.
	 *
	 * @param array $contact Contact data.
	 * @return array Normalized contact data.
	 */
	public static function normalize_contact_data( $contact ) {
		// If syncing for RAS, ensure that metadata keys are normalized with the correct RAS metadata keys.
		if ( isset( $contact['metadata'] ) ) {
			$normalized_metadata = [];
			$raw_keys            = array_values( array_flip( self::$metadata_keys ) );
			$prefixed_keys       = array_map(
				function( $key ) {
					return self::get_metadata_key( $key );
				},
				$raw_keys
			);

			// Capture UTM params and signup/payment page URLs as meta for registration or payment.
			if (
				isset( $contact['metadata']['current_page_url'] ) ||
				isset( $contact['metadata'][ self::get_metadata_key( 'current_page_url' ) ] ) ||
				isset( $contact['metadata']['payment_page'] ) ||
				isset( $contact['metadata'][ self::get_metadata_key( 'payment_page' ) ] )
			) {
				$is_payment = isset( $contact['metadata']['payment_page'] ) || isset( $contact['metadata'][ self::get_metadata_key( 'payment_page' ) ] );
				$raw_url    = false;
				if ( $is_payment ) {
					$raw_url = isset( $contact['metadata']['payment_page'] ) ? $contact['metadata']['payment_page'] : $contact['metadata'][ self::get_metadata_key( 'payment_page' ) ];
				} else {
					$raw_url = isset( $contact['metadata']['current_page_url'] ) ? $contact['metadata']['current_page_url'] : $contact['metadata'][ self::get_metadata_key( 'current_page_url' ) ];
				}

				$parsed_url = \wp_parse_url( $raw_url );

				// Maybe set UTM meta.
				if ( ! empty( $parsed_url['query'] ) ) {
					$utm_key_prefix = $is_payment ? 'payment_page_utm' : 'signup_page_utm';
					$params         = [];
					\wp_parse_str( $parsed_url['query'], $params );
					foreach ( $params as $param => $value ) {
						$param = \sanitize_text_field( $param );
						if ( 'utm' === substr( $param, 0, 3 ) ) {
							$param = str_replace( 'utm_', '', $param );
							$key   = self::get_metadata_key( $utm_key_prefix ) . $param;
							if ( ! isset( $contact['metadata'][ $key ] ) ) {
								$contact['metadata'][ $key ] = $value;
							}
						}
					}
				}
			}

			foreach ( $contact['metadata'] as $meta_key => $meta_value ) {
				if ( self::should_sync_ras_metadata() ) {
					if ( in_array( $meta_key, $raw_keys, true ) ) {
						$normalized_metadata[ self::get_metadata_key( $meta_key ) ] = $meta_value; // If passed a raw key, map it to the prefixed key.
					} elseif (
						in_array( $meta_key, $prefixed_keys, true ) ||
						( false !== strpos( $meta_key, self::get_metadata_key( 'signup_page_utm' ) ) || false !== strpos( $meta_key, self::get_metadata_key( 'payment_page_utm' ) ) ) // UTM meta keys can have arbitrary suffixes.
					) {
						$normalized_metadata[ $meta_key ] = $meta_value;
					}
				} else { // phpcs:ignore Universal.ControlStructures.DisallowLonelyIf.Found
					// If not syncing for RAS, we only want to sync email (for all ESPs) + First/Last Name (for MC only).
					if ( in_array( $meta_key, [ 'First Name', 'Last Name' ], true ) ) {
						$normalized_metadata[ $meta_key ] = $meta_value;
					}
				}
			}
			$contact['metadata'] = $normalized_metadata;
		}

		// Parse full name into first + last for MC, which stores these as separate merge fields.
		if ( method_exists( 'Newspack_Newsletters', 'service_provider' ) && 'mailchimp' === \Newspack_Newsletters::service_provider() ) {
			if ( isset( $contact['name'] ) ) {
				if ( ! isset( $contact['metadata'] ) ) {
					$contact['metadata'] = [];
				}
				$name_fragments                    = explode( ' ', $contact['name'], 2 );
				$contact['metadata']['First Name'] = $name_fragments[0];
				if ( isset( $name_fragments[1] ) ) {
					$contact['metadata']['Last Name'] = $name_fragments[1];
				}
			}
		}

		Logger::log( 'Normalizing contact data for reader ESP sync:' );
		Logger::log( $contact );

		return $contact;
	}

	/**
	 * Get lists without the master list, if set.
	 *
	 * @param int[] $list_ids List IDs to filter.
	 */
	public static function get_lists_without_active_campaign_master_list( $list_ids ) {
		$master_list_id = Reader_Activation::get_setting( 'active_campaign_master_list' );
		if ( is_int( intval( $master_list_id ) ) && is_array( $list_ids ) ) {
			return array_values( // Reset keys.
				array_filter(
					$list_ids,
					function( $id ) use ( $master_list_id ) {
						return $id !== $master_list_id;
					}
				)
			);
		}
		return $list_ids;
	}

	/**
	 * Ensure the contact is always added to ActiveCampaign's selected master list.
	 *
	 * @param string[]|false $lists    Array of list IDs the contact will be subscribed to, or false.
	 * @param array          $contact  {
	 *    Contact information.
	 *
	 *    @type string   $email                 Contact email address.
	 *    @type string   $name                  Contact name. Optional.
	 *    @type string   $existing_contact_data Existing contact data, if updating a contact. The hook will be also called when
	 *    @type string[] $metadata              Contact additional metadata. Optional.
	 * }
	 * @param string         $provider The provider name.
	 *
	 * @return string[]|false
	 */
	public static function add_activecampaign_master_list( $lists, $contact, $provider ) {
		if ( 'active_campaign' !== $provider ) {
			return $lists;
		}
		$master_list_id = Reader_Activation::get_setting( 'active_campaign_master_list' );
		if ( ! $master_list_id ) {
			return $lists;
		}
		if ( empty( $lists ) ) {
			return [ $master_list_id ];
		}
		if ( array_search( $master_list_id, $lists ) === false ) {
			$lists[] = $master_list_id;
		}
		return $lists;
	}
}
Newspack_Newsletters::init();
