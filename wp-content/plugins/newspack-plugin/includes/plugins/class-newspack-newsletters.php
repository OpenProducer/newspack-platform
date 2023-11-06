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
		if ( Reader_Activation::is_enabled() && Reader_Activation::get_setting( 'sync_esp' ) ) {
			\add_filter( 'newspack_newsletters_contact_lists', [ __CLASS__, 'add_activecampaign_master_list' ], 10, 3 );
		}
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
