<?php
/**
 * Teams for Memberships integration class.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

use Newspack\Donations;
use Newspack\Reader_Activation\Sync;

/**
 * Main class.
 */
class Teams_For_Memberships {

	/**
	 * Initialize hooks and filters.
	 */
	public static function init() {
		add_filter( 'newspack_ras_metadata_keys', [ __CLASS__, 'add_teams_metadata_keys' ] );
		add_filter( 'newspack_esp_sync_contact', [ __CLASS__, 'handle_esp_sync_contact' ] );
		add_filter( 'newspack_my_account_disabled_pages', [ __CLASS__, 'enable_members_area_for_team_members' ] );
	}

	/**
	 * Check if Teams for Memberships is enabled.
	 *
	 * @return bool True if enabled, false otherwise.
	 */
	private static function is_enabled() {
		return Donations::is_platform_wc() && class_exists( 'WC_Memberships_For_Teams_Loader' );
	}

	/**
	 * Add Teams metadata keys.
	 *
	 * @param array $metadata_keys Metadata keys.
	 * @return array Metadata keys.
	 */
	public static function add_teams_metadata_keys( $metadata_keys ) {
		if ( self::is_enabled() ) {
			$metadata_keys['woo_team'] = 'Woo Team';
		}
		return $metadata_keys;
	}

	/**
	 * Add Teams metadata to contact data.
	 *
	 * @param array $contact Contact data.
	 *
	 * @return array Updated contact data.
	 */
	public static function handle_esp_sync_contact( $contact ) {
		if ( ! self::is_enabled() ) {
			return $contact;
		}

		$filtered_enabled_fields = Sync\Metadata::filter_enabled_fields( [ 'woo_team' ] );
		if ( count( $filtered_enabled_fields ) === 0 ) {
			return $contact;
		}

		if ( empty( $contact['email'] ) ) {
			return $contact;
		}

		$user = \get_user_by( 'email', $contact['email'] );

		if ( ! $user ) {
			return $contact;
		}

		if ( ! isset( $contact['metadata'] ) ) {
			$contact['metadata'] = [];
		}

		global $wpdb;

		$existing_membership_teams = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_member_id' AND meta_value = %d", $user->ID )
		);
		if ( empty( $existing_membership_teams ) || empty( $existing_membership_teams[0] ) ) {
			return $contact;
		}

		$team_slugs = [];

		foreach ( $existing_membership_teams as $post_id ) {
			$team = get_post( $post_id );
			if ( $team ) {
				$team_slugs[] = $team->post_name;
			}
		}
		$team_slugs = implode( ',', $team_slugs );
		if ( $team_slugs ) {
			$contact['metadata']['woo_team'] = $team_slugs;
		}

		return $contact;
	}

	/**
	 * Enable Members Area for team members only. Team owners/managers get access to the "Teams" menu instead.
	 *
	 * @param array $disabled_wc_menu_items Disabled WooCommerce menu items.
	 *
	 * @return array Updated disabled WooCommerce menu items.
	 */
	public static function enable_members_area_for_team_members( $disabled_wc_menu_items ) {
		if ( ! function_exists( 'wc_memberships_for_teams_get_teams' ) ) {
			return $disabled_wc_menu_items;
		}
		if (
			in_array( 'members-area', $disabled_wc_menu_items, true ) &&
			! empty( \wc_memberships_for_teams_get_teams( \get_current_user_id(), [ 'role' => 'member' ] ) )
		) {
			$disabled_wc_menu_items = array_values( array_diff( $disabled_wc_menu_items, [ 'members-area' ] ) );
		}
		return $disabled_wc_menu_items;
	}
}

Teams_For_Memberships::init();
