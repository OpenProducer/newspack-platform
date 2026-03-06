<?php
/**
 * ESP integration
 *
 * @package Newspack
 */

namespace Newspack\Reader_Activation\Integrations;

use Newspack\Reader_Activation\Integration;
use Newspack\Reader_Activation;
use Newspack_Newsletters_Contacts;

defined( 'ABSPATH' ) || exit;

/**
 * ESP Integration Class.
 *
 * Generic integration for ESPs using Newspack Newsletters plugin.
 */
class ESP extends Integration {
	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( 'esp', __( 'ESPs Integration', 'newspack-plugin' ) );
	}

	/**
	 * Whether contacts can be synced to the ESP.
	 *
	 * @param bool $return_errors Optional. Whether to return a WP_Error object. Default false.
	 *
	 * @return bool|WP_Error True if contacts can be synced, false otherwise. WP_Error if return_errors is true.
	 */
	public function can_sync( $return_errors = false ) {
		$errors = new \WP_Error();

		if ( defined( 'NEWSPACK_FORCE_ALLOW_ESP_SYNC' ) && NEWSPACK_FORCE_ALLOW_ESP_SYNC ) {
			return $return_errors ? $errors : true;
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
	 * Push contact data to the integration destination.
	 *
	 * @param array      $contact The contact data to push.
	 * @param string     $context Optional. The context of the sync.
	 * @param array|null $existing_contact Optional. Existing contact data if available.
	 *
	 * @return true|\WP_Error True on success or WP_Error on failure.
	 */
	public function push_contact_data( $contact, $context = '', $existing_contact = null ) {

		$can_sync = $this->can_sync( true );
		if ( $can_sync->has_errors() ) {
			return $can_sync;
		}

		$master_list_id = Reader_Activation::get_esp_master_list_id();

		return Newspack_Newsletters_Contacts::upsert( $contact, $master_list_id, $context, $existing_contact );
	}
}
