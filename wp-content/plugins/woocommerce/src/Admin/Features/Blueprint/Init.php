<?php

declare( strict_types = 1 );

namespace Automattic\WooCommerce\Admin\Features\Blueprint;

use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCCoreProfilerOptions;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCPaymentGateways;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsAccount;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsAdvanced;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsEmails;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsGeneral;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsIntegrations;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsProducts;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCSettingsSiteVisibility;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCShipping;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCTaskOptions;
use Automattic\WooCommerce\Admin\Features\Blueprint\Exporters\ExportWCTaxRates;
use Automattic\WooCommerce\Admin\Features\Blueprint\Importers\ImportSetWCPaymentGateways;
use Automattic\WooCommerce\Admin\Features\Blueprint\Importers\ImportSetWCShipping;
use Automattic\WooCommerce\Admin\Features\Blueprint\Importers\ImportSetWCTaxRates;
use Automattic\WooCommerce\Admin\PageController;
use Automattic\WooCommerce\Blueprint\Exporters\HasAlias;
use Automattic\WooCommerce\Blueprint\Exporters\StepExporter;
use Automattic\WooCommerce\Blueprint\StepProcessor;
use Automattic\WooCommerce\Blueprint\UseWPFunctions;

/**
 * Class Init
 *
 * This class initializes the Blueprint feature for WooCommerce.
 */
class Init {
	use UseWPFunctions;

	/**
	 * Array of initialized exporters.
	 *
	 * @var StepExporter[]
	 */
	private array $initialized_exporters = array();

	/**
	 * Init constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'init_rest_api' ) );
		add_filter( 'woocommerce_admin_shared_settings', array( $this, 'add_js_vars' ) );

		add_filter(
			'wooblueprint_export_landingpage',
			function () {
				return '/wp-admin/admin.php?page=wc-admin';
			}
		);

		add_filter( 'wooblueprint_exporters', array( $this, 'add_woo_exporters' ) );
	}

	/**
	 * Register REST API routes.
	 *
	 * @return void
	 */
	public function init_rest_api() {
		( new RestApi() )->register_routes();
	}

	/**
	 * Return Woo Exporter classnames.
	 *
	 * @return StepExporter[]
	 */
	public function get_woo_exporters() {
		$classnames = array(
			ExportWCSettingsGeneral::class,
			ExportWCSettingsProducts::class,
			ExportWCTaxRates::class,
			ExportWCShipping::class,
			ExportWCPaymentGateways::class,
			ExportWCSettingsAccount::class,
			ExportWCSettingsEmails::class,
			ExportWCSettingsIntegrations::class,
			ExportWCSettingsSiteVisibility::class,
			ExportWCSettingsAdvanced::class,
		);

		$exporters = array();
		foreach ( $classnames as $classname ) {
			$exporters[ $classname ]                   = $this->initialized_exporters[ $classname ] ?? new $classname();
			$this->initialized_exporters[ $classname ] = $exporters[ $classname ];
		}

		return array_values( $exporters );
	}

	/**
	 * Add Woo Specific Exporters.
	 *
	 * @param StepExporter[] $exporters Array of step exporters.
	 *
	 * @return StepExporter[]
	 */
	public function add_woo_exporters( array $exporters ) {
		return array_merge(
			$exporters,
			$this->get_woo_exporters()
		);
	}

	/**
	 * Return step groups for JS.
	 *
	 * This is used to populate exportable items on the blueprint settings page.
	 *
	 * @return array
	 */
	public function get_step_groups_for_js() {
		$all_plugins    = $this->wp_get_plugins();
		$active_plugins = array_intersect_key( $all_plugins, array_flip( get_option( 'active_plugins', array() ) ) );
		$active_theme   = $this->wp_get_theme();

		return array(
			array(
				'id'          => 'settings',
				'description' => __( 'It includes all the items featured in WooCommerce | Settings.', 'woocommerce' ),
				'label'       => __( 'WooCommerce Settings', 'woocommerce' ),
				'icon'        => 'settings',
				'items'       => array_map(
					function ( $exporter ) {
						return array(
							'id'          => $exporter instanceof HasAlias ? $exporter->get_alias() : $exporter->get_step_name(),
							'label'       => $exporter->get_label(),
							'description' => $exporter->get_description(),
						);
					},
					$this->get_woo_exporters()
				),
			),
			array(
				'id'          => 'plugins',
				'description' => __( 'It includes all the installed plugins and extensions.', 'woocommerce' ),
				'label'       => __( 'Plugins and extensions', 'woocommerce' ),
				'icon'        => 'plugins',
				'items'       => array_map(
					function ( $key, $plugin ) {
						return array(
							'id'    => $key,
							'label' => $plugin['Name'],
						);
					},
					array_keys( $active_plugins ),
					$active_plugins
				),
			),
			array(
				'id'          => 'themes',
				'description' => __( 'It includes all the installed themes.', 'woocommerce' ),
				'label'       => __( 'Themes', 'woocommerce' ),
				'icon'        => 'brush',
				'items'       => array(
					array(
						'id'    => $active_theme->get_stylesheet(),
						'label' => $active_theme->get( 'Name' ),
					),
				),
			),
		);
	}

	/**
	 * Add shared JS vars.
	 *
	 * @param array $settings shared settings.
	 *
	 * @return mixed
	 */
	public function add_js_vars( $settings ) {
		if ( ! is_admin() ) {
			return $settings;
		}

		$screen_id     = PageController::get_instance()->get_current_screen_id();
		$advanced_page = strpos( $screen_id, 'woocommerce_page_wc-settings-advanced' ) !== false;
		if ( 'woocommerce_page_wc-admin' === $screen_id || $advanced_page ) {
			// Add upload nonce to global JS settings. The value can be accessed at wcSettings.admin.blueprint_upload_nonce.
			$settings['blueprint_upload_nonce'] = wp_create_nonce( 'blueprint_upload_nonce' );
		}

		if ( $advanced_page ) {
			// Used on the settings page.
			// wcSettings.admin.blueprint_step_groups.
			$settings['blueprint_step_groups'] = $this->get_step_groups_for_js();
			$settings['blueprint_max_step_size_bytes'] = RestApi::MAX_FILE_SIZE;
		}

		return $settings;
	}
}
