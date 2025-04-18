<?php
/**
 * Reader contact data syncing with the connected ESP using Newspack Newsletters.
 *
 * @package Newspack
 */

namespace Newspack\Reader_Activation;

use Newspack\Reader_Activation;
use Newspack\Data_Events;
use Newspack\Logger;

defined( 'ABSPATH' ) || exit;

/**
 * ESP Sync Class.
 */
class ESP_Sync extends Sync {
	/**
	 * Context of the sync.
	 *
	 * @var string
	 */
	protected static $context = 'ESP Sync';

	/**
	 * Queued syncs containing their contexts keyed by email address.
	 *
	 * @var array[]
	 */
	protected static $queued_syncs = [];

	/**
	 * Initialize hooks.
	 */
	public static function init_hooks() {
		add_action( 'newspack_scheduled_esp_sync', [ __CLASS__, 'scheduled_sync' ], 10, 2 );
		add_action( 'shutdown', [ __CLASS__, 'run_queued_syncs' ] );
	}

	/**
	 * Whether contacts can be synced to the ESP.
	 *
	 * @param bool $return_errors Optional. Whether to return a WP_Error object. Default false.
	 *
	 * @return bool|WP_Error True if contacts can be synced, false otherwise. WP_Error if return_errors is true.
	 */
	public static function can_esp_sync( $return_errors = false ) {
		$errors = new \WP_Error();

		if ( defined( 'NEWSPACK_FORCE_ALLOW_ESP_SYNC' ) && NEWSPACK_FORCE_ALLOW_ESP_SYNC ) {
			return $return_errors ? $errors : true;
		}

		$can_sync = static::can_sync( true );
		if ( $can_sync->has_errors() ) {
			$can_sync->export_to( $errors );
		}

		if ( ! class_exists( 'Newspack_Newsletters_Contacts' ) ) {
			$errors->add(
				'newspack_newsletters_contacts_not_found',
				__( 'Newspack Newsletters is not available.', 'newspack-plugin' )
			);
		}

		if ( ! Reader_Activation::get_setting( 'sync_esp' ) ) {
			$errors->add(
				'ras_esp_sync_not_enabled',
				__( 'ESP sync is not enabled.', 'newspack-plugin' )
			);
		}

		if ( ! Reader_Activation::get_esp_master_list_id() ) {
			$errors->add(
				'ras_esp_master_list_id_not_found',
				__( 'ESP master list ID is not set.', 'newspack-plugin' )
			);
		}

		if ( $return_errors ) {
			return $errors;
		}

		if ( $errors->has_errors() ) {
			return false;
		}

		return true;
	}

	/**
	 * Sync contact to the ESP.
	 *
	 * @param array  $contact          The contact data to sync.
	 * @param string $context          The context of the sync. Defaults to static::$context.
	 * @param array  $existing_contact Optional. Existing contact data to merge with. Defaults to null.
	 *
	 * @return true|\WP_Error True if succeeded or WP_Error.
	 */
	public static function sync( $contact, $context = '', $existing_contact = null ) {
		$can_sync = static::can_esp_sync( true );
		if ( $can_sync->has_errors() ) {
			return $can_sync;
		}

		if ( empty( $context ) ) {
			$context = static::$context;
		}

		// If we're running in a data event, queue the sync to run on shutdown.
		if ( ! isset( self::$queued_syncs[ $contact['email'] ] ) ) {
			self::$queued_syncs[ $contact['email'] ] = [
				'contexts' => [],
				'contact'  => [],
			];
		}
		if ( ! empty( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'] ) ) {
			$contact['metadata'] = array_merge( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'], $contact['metadata'] );
		}
		self::$queued_syncs[ $contact['email'] ]['contexts'][] = $context;
		self::$queued_syncs[ $contact['email'] ]['contact']    = $contact;
		if ( Data_Events::current_event() && ! did_action( 'shutdown' ) ) {
			return;
		}

		$master_list_id = Reader_Activation::get_esp_master_list_id();

		/**
		 * Filters the contact data before normalizing and syncing to the ESP.
		 *
		 * @param array  $contact The contact data to sync.
		 * @param string $context The context of the sync.
		 */
		$contact = \apply_filters( 'newspack_esp_sync_contact', $contact, $context );
		$contact = Sync\Metadata::normalize_contact_data( $contact );
		$result  = \Newspack_Newsletters_Contacts::upsert( $contact, $master_list_id, $context, $existing_contact );

		return \is_wp_error( $result ) ? $result : true;
	}

	/**
	 * Schedule a future sync.
	 *
	 * @param int    $user_id The user ID for the contact to sync.
	 * @param string $context The context of the sync.
	 * @param int    $delay   The delay in seconds.
	 */
	public static function schedule_sync( $user_id, $context, $delay ) {
		// Schedule another sync in $delay number of seconds.
		if ( ! is_int( $delay ) ) {
			return;
		}

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return;
		}

		static::log(
			sprintf(
				// Translators: %s is the email address of the contact to synced.
				__( 'Scheduling secondary sync for contact %s.', 'newspack-plugin' ),
				$user->data->user_email
			),
			[
				'user_email' => $user->data->user_email,
				'user_id'    => $user_id,
				'context'    => $context,
			]
		);
		\wp_schedule_single_event( \time() + $delay, 'newspack_scheduled_esp_sync', [ $user_id, $context ] );
	}

	/**
	 * Handle a scheduled sync event.
	 *
	 * @param int    $user_id The user ID for the contact to sync.
	 * @param string $context The context of the sync.
	 */
	public static function scheduled_sync( $user_id, $context ) {
		$contact = Sync\WooCommerce::get_contact_from_customer( new \WC_Customer( $user_id ) );
		if ( ! $contact ) {
			return;
		}
		self::sync( $contact, $context );
	}

	/**
	 * Get contact data for syncing.
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return array|\WP_Error The contact data or WP_Error.
	 */
	public static function get_contact_data( $user_id ) {
		$user = \get_userdata( $user_id );

		$customer = new \WC_Customer( $user_id );
		if ( ! $customer || ! $customer->get_id() ) {
			return new \WP_Error(
				'newspack_esp_sync_contact',
				sprintf(
				// Translators: %d is the user ID.
					__( 'Customer with ID %d does not exist.', 'newspack-plugin' ),
					$user_id
				)
			);
		}

		// Ensure the customer has a billing address.
		if ( ! $customer->get_billing_email() && $customer->get_email() ) {
			$customer->set_billing_email( $customer->get_email() );
			$customer->save();
		}

		$contact = Sync\WooCommerce::get_contact_from_customer( $customer );

		// Include data from queued syncs too.
		if ( ! empty( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'] ) ) {
			$contact['metadata'] = array_merge( self::$queued_syncs[ $contact['email'] ]['contact']['metadata'], $contact['metadata'] );
		}

		return $contact;
	}

	/**
	 * Given a user ID or WooCommerce Order, sync that reader's contact data to
	 * the connected ESP.
	 *
	 * @param int|\WC_order $user_id_or_order User ID or WC_Order object.
	 * @param bool          $is_dry_run       True if a dry run.
	 *
	 * @return true|\WP_Error True if the contact was synced successfully, WP_Error otherwise.
	 */
	public static function sync_contact( $user_id_or_order, $is_dry_run = false ) {
		$can_sync = static::can_esp_sync( true );
		if ( ! $is_dry_run && $can_sync->has_errors() ) {
			return $can_sync;
		}

		$is_order = $user_id_or_order instanceof \WC_Order;
		$order    = $is_order ? $user_id_or_order : false;
		$user_id  = $is_order ? $order->get_customer_id() : $user_id_or_order;

		$contact = $is_order ? Sync\WooCommerce::get_contact_from_order( $order ) : self::get_contact_data( $user_id );
		$result  = $is_dry_run ? true : self::sync( $contact );

		if ( $result && ! \is_wp_error( $result ) ) {
			static::log(
				sprintf(
					// Translators: %1$s is the status and %2$s is the contact's email address.
					__( '%1$s contact data for %2$s.', 'newspack-plugin' ),
					$is_dry_run ? __( 'Would sync', 'newspack-plugin' ) : __( 'Synced', 'newspack-plugin' ),
					$contact['email']
				)
			);
		}

		return $result;
	}

	/**
	 * Run queued syncs.
	 *
	 * @return void
	 */
	public static function run_queued_syncs() {
		if ( empty( self::$queued_syncs ) ) {
			return;
		}

		foreach ( self::$queued_syncs as $email => $queued_sync ) {
			$user = get_user_by( 'email', $email );
			if ( ! $user ) {
				continue;
			}

			$contact = self::get_contact_data( $user->ID );
			if ( ! $contact ) {
				continue;
			}
			$contexts = $queued_sync['contexts'];
			self::sync( $contact, implode( '; ', $contexts ) );
		}

		self::$queued_syncs = [];
	}
}
ESP_Sync::init_hooks();
