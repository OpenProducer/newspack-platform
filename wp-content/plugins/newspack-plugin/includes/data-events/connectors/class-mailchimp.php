<?php
/**
 * Newspack Data Events Mailchimp Connector
 *
 * @package Newspack
 */

namespace Newspack\Data_Events\Connectors;

use Newspack\Logger;
use Newspack\Data_Events;
use Newspack\Newspack_Newsletters;
use Newspack\Reader_Activation;

defined( 'ABSPATH' ) || exit;

/**
 * Main Class.
 */
class Mailchimp extends Connector {
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
			Data_Events::register_handler( [ __CLASS__, 'reader_deleted' ], 'reader_deleted' );
			Data_Events::register_handler( [ __CLASS__, 'reader_logged_in' ], 'reader_logged_in' );
			Data_Events::register_handler( [ __CLASS__, 'order_completed' ], 'order_completed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'donation_subscription_changed' );
			Data_Events::register_handler( [ __CLASS__, 'subscription_updated' ], 'product_subscription_changed' );
			Data_Events::register_handler( [ __CLASS__, 'network_new_reader' ], 'network_new_reader' );
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
			$audience_id = \mailchimp_get_list_id();
		}
		return ! empty( $audience_id ) ? $audience_id : false;
	}

	/**
	 * Get default reader MailChimp status.
	 *
	 * @return string MailChimp status slug, 'transactional' or 'subscriber'. (Default: 'transactional').
	 */
	private static function get_default_reader_status() {
		$allowed_statuses = [
			'transactional',
			'subscribed',
		];
		$default_status = Reader_Activation::get_setting( 'mailchimp_reader_default_status' );
		return in_array( $default_status, $allowed_statuses, true ) ? $default_status : 'transactional';
	}

	/**
	 * Update a Mailchimp contact
	 *
	 * @param array  $contact Contact info to sync to ESP without lists.
	 * @param string $context Context of the update for logging purposes.
	 * @return array|WP_Error response body or error.
	 */
	protected static function put( $contact, $context ) {
		$audience_id = self::get_audience_id();
		if ( ! $audience_id ) {
			return;
		}

		if ( empty( $contact['metadata'] ) ) {
			$contact['metadata'] = [];
		}

		$contact['metadata']['status_if_new'] = self::get_default_reader_status();

		return \Newspack_Newsletters_Contacts::upsert( $contact, $audience_id, $context );
	}
}
new Mailchimp();
