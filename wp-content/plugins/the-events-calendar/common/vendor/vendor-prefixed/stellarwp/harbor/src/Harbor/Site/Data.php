<?php

namespace TEC\Common\LiquidWeb\Harbor\Site;

use TEC\Common\LiquidWeb\Harbor\Utils\Cast;
use TEC\Common\StellarWP\ContainerContract\ContainerInterface;
use TEC\Common\LiquidWeb\Harbor\Config;

class Data {
	/**
	 * Container.
	 *
	 * @since 1.0.0
	 *
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// @phpstan-ignore-next-line
		$this->container = Config::get_container();
	}

	/**
	 * Gets the domain for the site.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_domain(): string {
		$cache_key = 'lw_harbor_domain';
		$domain    = $this->container->has( $cache_key ) ? Cast::to_string( $this->container->get( $cache_key ) ) : null;

		if ( null === $domain ) {
			$domain = is_multisite() ? $this->get_domain_multisite_option() : $this->get_site_domain();
			$this->container->bind(
				$cache_key,
				function () use ( $domain ) {
					return $domain;
				}
			);
		}

		/**
		 * Filters the domain for the site.
		 *
		 * @since 1.0.0
		 *
		 * @param string $domain Domain.
		 */
		$domain = apply_filters( 'lw-harbor/get_domain', $domain );

		return sanitize_text_field( Cast::to_string( $domain ) );
	}

	/**
	 * Return domain for multisite
	 *
	 * @return string
	 */
	protected function get_domain_multisite_option(): string {
		/** @var string */
		$site_url = get_site_option( 'siteurl', '' );

		/** @var array<string> */
		$site_url = wp_parse_url( $site_url );
		if ( ! $site_url || ! isset( $site_url['host'] ) ) {
			return '';
		}

		return strtolower( $site_url['host'] );
	}

	/**
	 * Returns the domain of the single site installation
	 *
	 * Will try to read it from the $_SERVER['SERVER_NAME'] variable
	 * and fall back on the one contained in the siteurl option.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_site_domain(): string {
		/** @var string */
		$site_url = get_option( 'siteurl', '' );

		/** @var array<string> */
		$site_url = wp_parse_url( $site_url );
		if ( ! $site_url || ! isset( $site_url['host'] ) ) {
			if ( isset( $_SERVER['SERVER_NAME'] ) ) {
				return Cast::to_string( $_SERVER['SERVER_NAME'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- SERVER_NAME is set by the web server, not user input.
			}

			return '';
		}

		return strtolower( $site_url['host'] );
	}
}
