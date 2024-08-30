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

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class ActiveCampaign extends Connector {
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
			Data_Events::register_handler( [ __CLASS__, 'reader_deleted' ], 'reader_deleted' );
			Data_Events::register_handler( [ __CLASS__, 'reader_logged_in' ], 'reader_logged_in' );
			Data_Events::register_handler( [ __CLASS__, 'order_completed' ], 'order_completed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'donation_subscription_changed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'product_subscription_changed' );
			Data_Events::register_handler( [ __CLASS__, 'newsletter_updated' ], 'newsletter_subscribed' );
			Data_Events::register_handler( [ __CLASS__, 'newsletter_updated' ], 'newsletter_updated' );
			Data_Events::register_handler( [ __CLASS__, 'network_new_reader' ], 'network_new_reader' );
		}
	}

	/**
	 * Upsert the contact.
	 *
	 * @param array  $contact Contact info to sync to ESP.
	 * @param string $context Context of the update for logging purposes.
	 */
	protected static function put( $contact, $context ) {
		$master_list_id = Reader_Activation::get_setting( 'active_campaign_master_list' );
		if ( ! $master_list_id ) {
			return;
		}
		return \Newspack_Newsletters_Contacts::upsert( $contact, $master_list_id, $context );
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
		self::put( $contact, 'Updating the account and newsletter_selection fields after a change in the subscription lists.' );
	}
}
new ActiveCampaign();
