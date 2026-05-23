<?php declare( strict_types=1 );

namespace TEC\Common\LiquidWeb\Harbor\Features;

use TEC\Common\LiquidWeb\Harbor\Portal\Catalog_Repository;
use TEC\Common\LiquidWeb\Harbor\Portal\Results\Catalog_Feature;
use TEC\Common\LiquidWeb\Harbor\Portal\Results\Product_Catalog;
use TEC\Common\LiquidWeb\Harbor\Features\Contracts\Installable;
use TEC\Common\LiquidWeb\Harbor\Features\Types\Feature;
use TEC\Common\LiquidWeb\Harbor\Features\Types\Plugin;
use TEC\Common\LiquidWeb\Harbor\Features\Types\Service;
use TEC\Common\LiquidWeb\Harbor\Features\Types\Theme;
use TEC\Common\LiquidWeb\Harbor\Licensing\Enums\Validation_Status;
use TEC\Common\LiquidWeb\Harbor\Licensing\Error_Code as Licensing_Error_Code;
use TEC\Common\LiquidWeb\Harbor\Licensing\License_Manager;
use TEC\Common\LiquidWeb\Harbor\Licensing\Product_Collection;
use TEC\Common\LiquidWeb\Harbor\Site\Data;
use TEC\Common\LiquidWeb\Harbor\Traits\With_Debugging;
use WP_Error;

/**
 * Joins catalog and licensing data to produce a resolved Feature_Collection.
 *
 * For each catalog feature, computes is_available and in_catalog_tier by checking
 * the product entry's capabilities array and the user's licensed tier rank.
 * dot.org and free-tier (rank 0) features are unconditionally available regardless of capabilities.
 *
 * @since 1.0.0
 */
class Resolve_Feature_Collection {

	use With_Debugging;

	/**
	 * The catalog repository.
	 *
	 * @since 1.0.0
	 *
	 * @var Catalog_Repository
	 */
	private Catalog_Repository $catalog;

	/**
	 * The license manager.
	 *
	 * @since 1.0.0
	 *
	 * @var License_Manager
	 */
	private License_Manager $licensing;

	/**
	 * The site data provider.
	 *
	 * @since 1.0.0
	 *
	 * @var Data
	 */
	private Data $site_data;

	/**
	 * Map of catalog type strings to Feature subclass names.
	 *
	 * @since 1.0.0
	 *
	 * @var array<string, class-string<Feature>>
	 */
	private array $type_map = [
		Feature::TYPE_PLUGIN  => Plugin::class,
		Feature::TYPE_THEME   => Theme::class,
		Feature::TYPE_SERVICE => Service::class,
	];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Catalog_Repository $catalog   The catalog repository.
	 * @param License_Manager    $licensing The license manager.
	 * @param Data               $site_data The site data provider.
	 */
	public function __construct(
		Catalog_Repository $catalog,
		License_Manager $licensing,
		Data $site_data
	) {
		$this->catalog   = $catalog;
		$this->licensing = $licensing;
		$this->site_data = $site_data;
	}

	/**
	 * Registers a Feature subclass for a given catalog type string.
	 *
	 * @since 1.0.0
	 *
	 * @param string                $type          A Feature::TYPE_* constant (e.g. Feature::TYPE_PLUGIN).
	 * @param class-string<Feature> $feature_class The Feature subclass FQCN.
	 *
	 * @return void
	 */
	public function register_type( string $type, string $feature_class ): void { // phpcs:ignore Squiz.Commenting.FunctionComment.IncorrectTypeHint -- class-string<Feature> is a PHPStan type narrowing.
		$this->type_map[ $type ] = $feature_class;
	}

	/**
	 * Fetches catalog and licensing data and resolves them into a Feature_Collection.
	 *
	 * Iterates each catalog product, finds the matching license entry,
	 * and hydrates Feature objects with computed is_available values.
	 *
	 * @since 1.0.0
	 *
	 * @return Feature_Collection|WP_Error
	 */
	public function __invoke() {
		$catalog = $this->catalog->get();

		if ( is_wp_error( $catalog ) ) {
			static::debug_log_wp_error(
				$catalog,
				'Catalog fetch failed during feature resolution'
			);

			return $catalog;
		}

		$products = $this->licensing->get_products( $this->site_data->get_domain() );

		if ( is_wp_error( $products ) ) {
			if ( $products->get_error_code() === Licensing_Error_Code::INVALID_KEY ) {
				$products = new Product_Collection();
			} else {
				static::debug_log_wp_error(
					$products,
					'Licensing fetch failed during feature resolution'
				);

				return $products;
			}
		}

		$collection = new Feature_Collection();

		foreach ( $catalog as $product ) {
			if ( ! $product instanceof Product_Catalog ) {
				continue;
			}

			$capabilities      = $this->resolve_capabilities( $product, $products );
			$license_tier_rank = $this->resolve_license_tier_rank( $product, $products );

			foreach ( $product->get_features() as $catalog_feature ) {
				$feature = $this->hydrate_feature( $catalog_feature, $product, $capabilities, $license_tier_rank );

				if ( is_wp_error( $feature ) ) {
					static::debug_log( $feature->get_error_message() );
					continue;
				}

				$collection->add( $feature );
			}
		}

		return $collection;
	}

	/**
	 * Resolves the capabilities granted by the license for a given product.
	 *
	 * Returns null when no license is present or when the license is known to be
	 * ineffective for this domain (any non-valid validation_status). Returning null
	 * causes paid-tier features to render as locked rather than "Unavailable".
	 *
	 * @since 1.0.0
	 *
	 * @param Product_Catalog    $product  The catalog product.
	 * @param Product_Collection $products The licensing product collection.
	 *
	 * @return string[]|null The capabilities array, or null if the product has no effective license.
	 */
	private function resolve_capabilities( Product_Catalog $product, Product_Collection $products ): ?array {
		$license = $products->get_activated_entry( $product->get_product_slug() );

		if ( null === $license ) {
			return null;
		}

		if ( $this->is_license_invalid( $license->get_validation_status() ) ) {
			return null;
		}

		return $license->get_capabilities();
	}

	/**
	 * Returns the rank of the user's licensed tier for a product, or -1 if unlicensed.
	 *
	 * Returns -1 when the license is known to be ineffective for this domain, matching
	 * the resolve_capabilities() guard so both flags are consistent.
	 *
	 * @since 1.0.0
	 *
	 * @param Product_Catalog    $product  The catalog product.
	 * @param Product_Collection $products The licensing product collection.
	 *
	 * @return int The license tier rank, or -1 if no license covers this product.
	 */
	private function resolve_license_tier_rank( Product_Catalog $product, Product_Collection $products ): int {
		$license = $products->get_activated_entry( $product->get_product_slug() );

		if ( null === $license ) {
			return -1;
		}

		if ( $this->is_license_invalid( $license->get_validation_status() ) ) {
			return -1;
		}

		$tier = $product->get_tier_by_slug( $license->get_tier() );

		return $tier !== null ? $tier->get_rank() : -1;
	}

	/**
	 * Returns true when a known validation status is present but is not 'valid'.
	 *
	 * Covers not_activated, activation_required, expired, suspended, cancelled,
	 * out_of_activations, license_suspended, license_banned, no_entitlement, etc.
	 * A null status means no domain was provided in the request, so capabilities
	 * are left untouched in that case.
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $validation_status The product's validation status.
	 *
	 * @return bool
	 */
	private function is_license_invalid( ?string $validation_status ): bool {
		return $validation_status !== null && $validation_status !== Validation_Status::VALID;
	}

	/**
	 * Hydrates a Feature object from a catalog feature entry.
	 *
	 * Maps catalog types (plugin, theme) to Feature subclasses
	 * and computes is_available and in_catalog_tier.
	 *
	 * dot.org and free-tier (rank 0) features are unconditionally available regardless of capabilities.
	 * When capabilities is null (no license), all paid-tier features are unavailable and not in tier.
	 *
	 * @since 1.0.0
	 *
	 * @param Catalog_Feature $catalog_feature   The catalog feature entry.
	 * @param Product_Catalog $product           The parent catalog product.
	 * @param string[]|null   $capabilities      The license capabilities, or null if unlicensed.
	 * @param int             $license_tier_rank The user's licensed tier rank, or -1 if unlicensed.
	 *
	 * @return Feature|WP_Error The hydrated feature, or WP_Error for unknown types.
	 */
	private function hydrate_feature(
		Catalog_Feature $catalog_feature,
		Product_Catalog $product,
		?array $capabilities,
		int $license_tier_rank
	) {
		$catalog_kind = $catalog_feature->get_kind();
		$class        = $this->type_map[ $catalog_kind ] ?? null;

		if ( $class === null ) {
			return new WP_Error(
				Error_Code::UNKNOWN_FEATURE_TYPE,
				sprintf(
					'No Feature subclass registered for catalog kind "%s" (feature: %s).',
					$catalog_kind,
					$catalog_feature->get_slug()
				)
			);
		}

		$minimum_tier = $product->get_tier_by_slug( $catalog_feature->get_minimum_tier() );
		$minimum_rank = $minimum_tier !== null ? $minimum_tier->get_rank() : PHP_INT_MAX;

		if ( $catalog_feature->is_wporg() || $minimum_rank === 0 ) {
			// WordPress.org and free-tier features are unconditionally available — capabilities and tier are irrelevant.
			$is_available    = true;
			$in_catalog_tier = true;
		} elseif ( $capabilities === null ) {
			// No license: paid-tier features are neither available nor in tier.
			$is_available    = false;
			$in_catalog_tier = false;
		} else {
			$is_available    = in_array( $catalog_feature->get_slug(), $capabilities, true );
			$in_catalog_tier = ( $license_tier_rank >= $minimum_rank );
		}

		$data = [
			'slug'              => $catalog_feature->get_slug(),
			'product'           => $product->get_product_slug(),
			'tier'              => $catalog_feature->get_minimum_tier(),
			'name'              => $catalog_feature->get_name(),
			'description'       => $catalog_feature->get_description(),
			'type'              => $catalog_kind,
			'is_available'      => $is_available,
			'in_catalog_tier'   => $in_catalog_tier,
			'documentation_url' => $catalog_feature->get_documentation_url(),
			'release_date'      => $catalog_feature->get_release_date(),
			'plugin_file'       => $catalog_feature->get_plugin_file() ?? '',
			'wporg_slug'        => $catalog_feature->get_wporg_slug(),
			'version'           => $catalog_feature->get_version(),
			'changelog'         => $catalog_feature->get_changelog(),
		];

		$feature = $class::from_array( $data );

		if ( $feature instanceof Installable ) {
			$data['installed_version'] = $feature->get_installed_version();
			$feature                   = $class::from_array( $data );
		}

		return $feature;
	}
}
