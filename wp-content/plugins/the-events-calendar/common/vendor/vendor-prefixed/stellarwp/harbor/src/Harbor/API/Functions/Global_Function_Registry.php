<?php declare( strict_types=1 );

namespace TEC\Common\LiquidWeb\Harbor\API\Functions;

use TEC\Common\LiquidWeb\Harbor\Admin\Feature_Manager_Page;
use TEC\Common\LiquidWeb\Harbor\API\Functions\Actions\Display_Legacy_License_Page_Notice;
use TEC\Common\LiquidWeb\Harbor\API\Functions\Actions\Register_Submenu;
use TEC\Common\LiquidWeb\Harbor\Config;
use TEC\Common\LiquidWeb\Harbor\Features\Manager;
use TEC\Common\LiquidWeb\Harbor\Licensing\Repositories\License_Repository;
use TEC\Common\LiquidWeb\Harbor\Site\Data;
use TEC\Common\LiquidWeb\Harbor\Traits\With_Debugging;
use Throwable;

/**
 * Registers this Harbor instance's callbacks into the global function registry.
 *
 * Each vendor-prefixed Harbor instance calls register() during init, storing
 * version-keyed closures via _lw_harbor_global_function_registry(). The closures are defined
 * here (inside the namespaced file) so Strauss-prefixed class references
 * resolve correctly for this specific instance.
 *
 * @since 1.0.0
 */
class Global_Function_Registry {

	use With_Debugging;

	/**
	 * Registers this instance's callbacks into the global function registry.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version The version of this Harbor instance.
	 *
	 * @return void
	 */
	public static function register( string $version ): void {
		\_lw_harbor_global_function_registry(
			'lw_harbor_has_unified_license_key',
			$version,
			static function (): bool {
				try {
					return Config::get_container()->get( License_Repository::class )->key_exists();
				} catch ( Throwable $e ) {
					self::debug_log_throwable( $e, 'Error checking unified license key existence' );

					return false;
				}
			}
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_get_unified_license_key',
			$version,
			static function (): ?string {
				try {
					return Config::get_container()->get( License_Repository::class )->get_key();
				} catch ( Throwable $e ) {
					self::debug_log_throwable( $e, 'Error getting unified license key' );

					return null;
				}
			}
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_is_product_license_active',
			$version,
			static function ( string $product ): bool {
				try {
					return Config::get_container()->get( License_Repository::class )->is_product_valid( $product );
				} catch ( Throwable $e ) {
					self::debug_log_throwable( $e, 'Error checking product license' );

					return false;
				}
			}
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_is_feature_enabled',
			$version,
			static function ( string $slug ) {
				try {
					$result = Config::get_container()->get( Manager::class )->is_enabled( $slug );

					if ( is_wp_error( $result ) ) {
						self::debug_log_wp_error( $result, 'Error checking feature enabled state' );

						return false;
					}

					return $result;
				} catch ( Throwable $e ) {
					self::debug_log_throwable( $e, 'Error checking feature enabled state' );

					return false;
				}
			}
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_is_feature_available',
			$version,
			static function ( string $slug ) {
				try {
					$result = Config::get_container()->get( Manager::class )->is_available( $slug );

					if ( is_wp_error( $result ) ) {
						self::debug_log_wp_error( $result, 'Error checking feature availability' );

						return false;
					}

					return $result;
				} catch ( Throwable $e ) {
					self::debug_log_throwable( $e, 'Error checking feature availability' );

					return false;
				}
			}
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_get_license_page_url',
			$version,
			static function (): string {
				return admin_url( 'options-general.php?page=' . Feature_Manager_Page::PAGE_SLUG );
			}
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_display_legacy_license_page_notice',
			$version,
			new Display_Legacy_License_Page_Notice()
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_register_submenu',
			$version,
			new Register_Submenu()
		);

		\_lw_harbor_global_function_registry(
			'lw_harbor_get_licensed_domain',
			$version,
			static function (): string {
				try {
					return Config::get_container()->get( Data::class )->get_domain();
				} catch ( Throwable $e ) {
					self::debug_log_throwable( $e, 'Error getting site domain' );

					return '';
				}
			}
		);
	}
}
