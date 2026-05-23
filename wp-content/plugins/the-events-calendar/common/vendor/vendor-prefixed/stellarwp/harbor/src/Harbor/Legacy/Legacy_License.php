<?php

declare(strict_types=1);

namespace TEC\Common\LiquidWeb\Harbor\Legacy;

use TEC\Common\LiquidWeb\Harbor\Utils\Cast;

/**
 * Represents a license key discovered from a plugin's legacy storage.
 *
 * @since 1.0.0
 */
class Legacy_License {

	/**
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $key;

	/**
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $slug;

	/**
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $product;

	/**
	 * Whether the license is currently active.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	public bool $is_active;

	/**
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $page_url;

	/**
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $expires_at;

	/**
	 * @since 1.0.0
	 *
	 * @return array<string, mixed>
	 */
	public function to_array(): array {
		return [
			'key'        => $this->key,
			'slug'       => $this->slug,
			'name'       => $this->name,
			'product'    => $this->product,
			'is_active'  => $this->is_active,
			'page_url'   => $this->page_url,
			'expires_at' => $this->expires_at,
		];
	}

	/**
	 * @since 1.0.0
	 *
	 * @param array<string, mixed> $data The legacy license data.
	 */
	public static function from_data( array $data ): Legacy_License {
		$self = new self();

		$self->key        = Cast::to_string( $data['key'] ?? '' );
		$self->slug       = Cast::to_string( $data['slug'] ?? '' );
		$self->name       = Cast::to_string( $data['name'] ?? '' );
		$self->product    = Cast::to_string( $data['product'] ?? '' );
		$self->is_active  = (bool) ( $data['is_active'] ?? false );
		$self->page_url   = Cast::to_string( $data['page_url'] ?? '' );
		$self->expires_at = Cast::to_string( $data['expires_at'] ?? '' );

		return $self;
	}
}
