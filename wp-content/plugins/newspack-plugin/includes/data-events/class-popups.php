<?php
/**
 * Newspack Data Events Popups helper.
 *
 * @package Newspack
 */

namespace Newspack\Data_Events;

use Newspack_Popups_Model;
use Newspack\Data_Events;
use WP_Error;

/**
 * Class to register the Popups listeners.
 */
final class Popups {

	/**
	 * Initialize the class by registering the listeners.
	 *
	 * @return void
	 */
	public static function init() {
		Data_Events::register_listener(
			'newspack_reader_registration_form_processed',
			'campaign_interaction',
			[ __CLASS__, 'registration_submission' ]
		);

		Data_Events::register_listener(
			'newspack_reader_registration_form_processed',
			'campaign_interaction',
			[ __CLASS__, 'registration_submission_with_status' ]
		);

		Data_Events::register_listener(
			'newspack_newsletters_subscribe_form_processed',
			'campaign_interaction',
			[ __CLASS__, 'newsletter_submission' ]
		);

		Data_Events::register_listener(
			'newspack_newsletters_subscribe_form_processed',
			'campaign_interaction',
			[ __CLASS__, 'newsletter_submission_with_status' ]
		);
	}

	/**
	 * Extract the relevant data from a popup.
	 *
	 * @param int|array $popup The popup ID or object.
	 * @return array
	 */
	public static function get_popup_metadata( $popup ) {
		if ( is_numeric( $popup ) ) {
			$popup = Newspack_Popups_Model::retrieve_popup_by_id( $popup );
		}
		$data = [];
		if ( ! $popup ) {
			return $data;
		}

		$data['campaign_id']    = $popup['id'];
		$data['campaign_title'] = $popup['title'];

		if ( isset( $popup['options'] ) ) {
			$data['campaign_frequency'] = $popup['options']['frequency'] ?? '';
			$data['campaign_placement'] = $popup['options']['placement'] ?? '';
		}

		$data['campaign_has_registration_block'] = has_block( 'newspack/reader-registration', $popup['content'] );
		$data['campaign_has_donation_block']     = false; // TODO.
		$data['campaign_has_newsletter_block']   = has_block( 'newspack-newsletters/subscribe', $popup['content'] );

		return $data;
	}

	/**
	 * A listener for the registration block form submission
	 *
	 * Will trigger the event with "form_submission" as action in all cases.
	 *
	 * @param string              $email   Email address of the reader.
	 * @param int|false|\WP_Error $user_id The created user ID in case of registration, false if not created or a WP_Error object.
	 * @param int|false           $popup_id The ID of the popup that triggered the registration, or false if not triggered by a popup.
	 * @return ?array
	 */
	public static function registration_submission( $email, $user_id, $popup_id ) {
		if ( ! $popup_id ) {
			return;
		}
		$popup_data = self::get_popup_metadata( $popup_id );
		return array_merge(
			$popup_data,
			[
				'action' => 'form_submission',
			]
		);
	}

	/**
	 * A listener for the registration block form submission
	 *
	 * Will trigger the event with "form_submission_success" or "form_submission_failure" as action.
	 *
	 * @param string              $email   Email address of the reader.
	 * @param int|false|\WP_Error $user_id The created user ID in case of registration, false if not created or a WP_Error object.
	 * @param int|false           $popup_id The ID of the popup that triggered the registration, or false if not triggered by a popup.
	 * @return ?array
	 */
	public static function registration_submission_with_status( $email, $user_id, $popup_id ) {
		if ( ! $popup_id ) {
			return;
		}
		$action = 'form_submission_success';
		if ( ! $user_id || \is_wp_error( $user_id ) ) {
			$action = 'form_submission_failure';
		}
		$popup_data = self::get_popup_metadata( $popup_id );
		return array_merge(
			$popup_data,
			[
				'action' => $action,
			]
		);
	}

	/**
	 * A listener for the registration block form submission
	 *
	 * Will trigger the event with "form_submission" as action in all cases.
	 *
	 * @param string         $email  Email address of the reader.
	 * @param array|WP_Error $result Contact data if it was added, or error otherwise.
	 * @param int|false      $popup_id The ID of the popup that triggered the registration, or false if not triggered by a popup.
	 * @return ?array
	 */
	public static function newsletter_submission( $email, $result, $popup_id = false ) {
		if ( ! $popup_id ) {
			return;
		}
		$popup_data = self::get_popup_metadata( $popup_id );
		return array_merge(
			$popup_data,
			[
				'action' => 'form_submission',
			]
		);
	}

	/**
	 * A listener for the registration block form submission
	 *
	 * Will trigger the event with "form_submission_success" or "form_submission_failure" as action.
	 *
	 * @param string         $email  Email address of the reader.
	 * @param array|WP_Error $result Contact data if it was added, or error otherwise.
	 * @param int|false      $popup_id The ID of the popup that triggered the registration, or false if not triggered by a popup.
	 * @return ?array
	 */
	public static function newsletter_submission_with_status( $email, $result, $popup_id = false ) {
		if ( ! $popup_id ) {
			return;
		}
		$action = 'form_submission_success';
		if ( ! $result || \is_wp_error( $result ) ) {
			$action = 'form_submission_failure';
		}
		$popup_data = self::get_popup_metadata( $popup_id );
		return array_merge(
			$popup_data,
			[
				'action' => $action,
			]
		);
	}

}
Popups::init();
