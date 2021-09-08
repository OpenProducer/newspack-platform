<?php
/**
 * Class Google\Site_Kit\Modules\AdSense\Web_Tag
 *
 * @package   Google\Site_Kit\Modules\AdSense
 * @copyright 2021 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Modules\AdSense;

use Google\Site_Kit\Core\Modules\Tags\Module_Web_Tag;
use Google\Site_Kit\Core\Util\Method_Proxy_Trait;
use Google\Site_Kit\Core\Tags\Tag_With_DNS_Prefetch_Trait;

/**
 * Class for Web tag.
 *
 * @since 1.24.0
 * @access private
 * @ignore
 */
class Web_Tag extends Module_Web_Tag {

	use Method_Proxy_Trait, Tag_With_DNS_Prefetch_Trait;

	/**
	 * Registers tag hooks.
	 *
	 * @since 1.24.0
	 */
	public function register() {
		add_action( 'wp_head', $this->get_method_proxy_once( 'render' ) );

		add_filter(
			'wp_resource_hints',
			$this->get_dns_prefetch_hints_callback( '//pagead2.googlesyndication.com' ),
			10,
			2
		);

		$this->do_init_tag_action();
	}

	/**
	 * Outputs the AdSense script tag.
	 *
	 * @since 1.24.0
	 */
	protected function render() {
		// If we haven't completed the account connection yet, we still insert the AdSense tag
		// because it is required for account verification.
		printf(
			'<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"%s></script>', // // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$this->get_tag_blocked_on_consent_attribute() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		$auto_ads_opt = array(
			'google_ad_client'      => $this->tag_id,
			'enable_page_level_ads' => true,
			'tag_partner'           => 'site_kit',
		);

		$auto_ads_opt_filtered = apply_filters( 'googlesitekit_auto_ads_opt', $auto_ads_opt, $this->tag_id );

		if ( ! is_array( $auto_ads_opt_filtered ) || empty( $auto_ads_opt_filtered ) ) {
			$auto_ads_opt_filtered = $auto_ads_opt;
		}

		$auto_ads_opt_filtered['google_ad_client'] = $this->tag_id;

		printf(
			'<script>(adsbygoogle = window.adsbygoogle || []).push(%s);</script>',
			wp_json_encode( $auto_ads_opt_filtered )
		);
	}

}
