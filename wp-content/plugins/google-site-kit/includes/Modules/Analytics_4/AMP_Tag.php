<?php
/**
 * Class Google\Site_Kit\Modules\Analytics_4\AMP_Tag
 *
 * @package   Google\Site_Kit\Modules\Analytics_4
 * @copyright 2023 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Modules\Analytics_4;

use Google\Site_Kit\Core\Util\Method_Proxy_Trait;
use Google\Site_Kit\Modules\Analytics\AMP_Tag as Analytics_AMP_Tag;

/**
 * Class for AMP tag.
 *
 * @since  1.104.0
 * @access private
 * @ignore
 */
class AMP_Tag extends Analytics_AMP_Tag {

	use Method_Proxy_Trait;

	/**
	 * Registers tag hooks.
	 *
	 * @since 1.104.0
	 */
	public function register() {
		parent::register();

		// If the UA AMP tag is being placed, extend it, otherwise there's nothing more to do.
		if ( did_action( 'googlesitekit_analytics_init_tag_amp' ) ) {
			add_filter( 'googlesitekit_amp_gtag_opt', $this->get_method_proxy( 'extend_gtag_opt' ) );
		}
	}

	/**
	 * Outputs gtag <amp-analytics> tag.
	 *
	 * @since 1.104.0
	 */
	protected function render() {
		// Only render this tag if the UA AMP tag was not rendered to avoid multiple tags.
		if ( did_action( 'googlesitekit_analytics_init_tag_amp' ) ) {
			return;
		}

		parent::render();
	}

	/**
	 * Extends gtag vars config with the GA4 tag config.
	 *
	 * @since 1.104.0
	 *
	 * @param array $opt AMP gtag config.
	 * @return array
	 */
	protected function extend_gtag_opt( $opt ) {
		$opt['vars']['config'] = array_merge(
			$opt['vars']['config'],
			$this->get_tag_config()
		);
		// `gtag_id` isn't used in a multi-destination configuration.
		// See https://developers.google.com/analytics/devguides/collection/amp-analytics/#sending_data_to_multiple_destinations.
		unset( $opt['vars']['gtag_id'] );

		return $opt;
	}
}
