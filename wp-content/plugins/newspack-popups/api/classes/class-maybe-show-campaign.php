<?php
/**
 * Newspack Campaigns maybe display campaign.
 *
 * @package Newspack
 */

/**
 * Extend the base Lightweight_API class.
 */
require_once 'class-lightweight-api.php';

/**
 * GET endpoint to determine if campaign is shown or not.
 */
class Maybe_Show_Campaign extends Lightweight_API {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$should_show = $this->should_campaign_be_shown();
		if ( ( $should_show || $this->should_perform_utm_logic() ) && 'test' !== $this->frequency ) {
			if ( $this->should_suppress_because_utm_suppression() ) {
				$should_show = false;
			}
			if ( $this->should_suppress_because_utm_medium() ) {
				$should_show = false;
			}
			if ( $should_show && $this->should_suppress_because_newsletter_campaign_dismissed() ) {
				$should_show = false;
			}
		}

		$this->response['displayPopup'] = $should_show;
		$this->respond();
	}

	/**
	 * Primary campaign visibility logic.
	 *
	 * @return bool Whether campaign should be shown.
	 */
	public function should_campaign_be_shown() {
		$data                = $this->get_transient( $this->get_transient_name() );
		$current_views       = ! empty( $data['count'] ) ? (int) $data['count'] : 0;
		$suppress_forever    = ! empty( $data['suppress_forever'] ) ? (int) $data['suppress_forever'] : false;
		$mailing_list_status = ! empty( $data['mailing_list_status'] ) ? (int) $data['mailing_list_status'] : false;
		$last_view           = ! empty( $data['time'] ) ? (int) $data['time'] : 0;

		if ( $suppress_forever ) {
			return false;
		}
		if ( $mailing_list_status ) {
			return false;
		}
		if ( 'always' === $this->frequency || 'test' === $this->frequency ) {
			return true;
		}
		if ( 'daily' === $this->frequency ) {
			return $last_view < strtotime( '-1 day' );
		}
		if ( 'once' === $this->frequency ) {
			return $current_views < 1;
		}
		return false;
	}

	/**
	 * Assess whether the UTM logic is necessary.
	 *
	 * @return bool Whether UTM logic should be performed.
	 */
	public function should_perform_utm_logic() {
		if ( $this->utm_suppression && stripos( urldecode( $this->referer_url ), 'utm_source=' . $this->utm_suppression ) ) {
			return true;
		}
		if ( stripos( $this->referer_url, 'utm_medium=email' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Handle utm_source query param, update transients if needed.
	 *
	 * @return bool Should campaign be shown.
	 */
	public function should_suppress_because_utm_suppression() {
		if ( ! $this->utm_suppression ) {
			return false;
		}
		// Suppressing based on UTM Source parameter in the URL.
		// If the visitor came from a campaign with suppressed utm_source, then it should not be displayed.
		$utm_source_transient_name = $this->get_suppression_data_transient_name( 'utm_source' );
		$utm_source_transient      = $this->get_transient( $utm_source_transient_name );
		if ( ! $utm_source_transient || ! is_array( $utm_source_transient ) ) {
			$utm_source_transient = [];
		}
		if ( $this->referer_url && stripos( urldecode( $this->referer_url ), 'utm_source=' . $this->utm_suppression ) ) {
			$utm_source_transient[ $this->utm_suppression ] = true;
			$this->set_transient( $utm_source_transient_name, $utm_source_transient );
			return true;
		}

		if ( ! empty( $utm_source_transient[ $this->utm_suppression ] ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Handle utm_medium query param, update transients if needed.
	 *
	 * @return bool Should campaign be shown.
	 */
	public function should_suppress_because_utm_medium() {
		// Suppressing based on UTM Medium parameter in the URL. If:
		// - the visitor came from email,
		// - the suppress_newsletter_campaigns setting is on,
		// - the pop-up has a newsletter form,
		// then it should not be displayed.
		$has_utm_medium_in_url     = stripos( $this->referer_url, 'utm_medium=email' );
		$utm_medium_transient_name = $this->get_suppression_data_transient_name( 'utm_medium' );

		if (
			$this->suppress_newsletter_campaigns &&
			$this->has_newsletter_prompt &&
			( $has_utm_medium_in_url || $this->get_transient( $utm_medium_transient_name ) )
		) {
			$this->set_transient( $utm_medium_transient_name, true );
			return true;
		}
		return false;
	}

	/**
	 * Should campaign be suppressed because it is a Newsletter campaign and a Newsletter campaign was previously dismissed.
	 *
	 * @return bool Should campaign be shown.
	 */
	public function should_suppress_because_newsletter_campaign_dismissed() {
		// Suppressing a newsletter campaign if any newsletter campaign was dismissed.
		$name = $this->legacy_get_suppression_data_transient_name_reversed( 'newsletter-campaign-suppression' );

		if ( $this->suppress_all_newsletter_campaigns_if_one_dismissed && $this->has_newsletter_prompt && $this->get_transient( $name ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Set the utm_source suppression-related transient.
	 *
	 * @param string $utm_source utm_source param.
	 */
	public function set_utm_source_transient( $utm_source ) {
		if ( ! empty( $utm_source ) ) {
			$transient_name           = $this->get_suppression_data_transient_name( 'utm_source' );
			$transient                = $this->get_transient( $transient_name );
			$transient[ $utm_source ] = true;
			$this->set_transient( $transient_name, true );
		}
	}
}
new Maybe_Show_Campaign();
