<?php declare( strict_types=1 );

namespace TEC\Common\LiquidWeb\Harbor\Portal;

use TEC\Common\LiquidWeb\Harbor\Config;
use TEC\Common\LiquidWeb\Harbor\Licensing\Repositories\License_Repository;
use TEC\Common\LiquidWeb\Harbor\Portal\Contracts\Download_Url_Builder;
use TEC\Common\LiquidWeb\Harbor\Site\Data;

/**
 * Builds Herald download URLs for catalog features.
 *
 * Herald is the StellarWP download service. It accepts the license key as a
 * path segment and the site domain as a query parameter, which allows Harbor
 * to construct authenticated download URLs entirely from local data without
 * relying on the catalog API response.
 *
 * URL format: {herald_base_url}/download/{slug}/latest/{license_key}/zip?site={domain}
 *
 * @since 1.0.0
 */
final class Herald_Url_Builder implements Download_Url_Builder {

	/**
	 * The license key provider.
	 *
	 * @since 1.0.0
	 *
	 * @var License_Repository
	 */
	private License_Repository $license_repository;

	/**
	 * Site data provider.
	 *
	 * @since 1.0.0
	 *
	 * @var Data
	 */
	private Data $site_data;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param License_Repository $license_repository The license key provider.
	 * @param Data               $site_data          Site data provider.
	 */
	public function __construct( License_Repository $license_repository, Data $site_data ) {
		$this->license_repository = $license_repository;
		$this->site_data          = $site_data;
	}

	/**
	 * Builds a Herald download URL for the given feature slug.
	 *
	 * Returns an empty string if no license key is stored or no domain is available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug The catalog feature slug.
	 *
	 * @return string
	 */
	public function build( string $slug ): string {
		$license_key = $this->license_repository->get_key();
		$domain      = $this->site_data->get_domain();

		if ( $license_key === null || $domain === '' ) {
			return '';
		}

		$url = Config::get_herald_base_url()
			. '/download/'
			. rawurlencode( $slug )
			. '/latest/'
			. rawurlencode( $license_key )
			. '/zip';

		return add_query_arg(
			[
				'site' => rawurlencode( $domain ),
			],
			$url
		);
	}
}
