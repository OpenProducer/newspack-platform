<?php
/**
 * Newspack Listings - Sets up WooCommerce products for self-serve listings.
 *
 * @package Newspack_Listings
 */

namespace Newspack_Listings;

use \Newspack_Listings\Newspack_Listings_Settings as Settings;
use \Newspack_Listings\Newspack_Listings_Core as Core;
use \Newspack_Listings\Utils as Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Products class.
 * Sets up WooCommerce products for listings.
 */
final class Newspack_Listings_Products {

	/**
	 * The option name for the product ID.
	 */
	const NEWSPACK_LISTINGS_PRODUCT_OPTION = 'newspack_listings_product_id';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Listings_Products
	 */
	protected static $instance = null;

	/**
	 * Is WooCommerce active? If not, we can't use any of its functionality.
	 *
	 * @var $wc_is_active
	 */
	protected static $wc_is_active = false;

	/**
	 * Main Newspack_Listings_Products instance.
	 * Ensures only one instance of Newspack_Listings_Products is loaded or can be loaded.
	 *
	 * @return Newspack_Listings_Products - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'init' ] );

		// When product settings are updated, make sure to update the corresponding WooCommerce products as well.
		add_action( 'update_option', [ __CLASS__, 'update_products' ], 10, 3 );
	}

	/**
	 * After WP init.
	 */
	public static function init() {
		// Check whether WooCommerce is active and available.
		if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Subscriptions_Product' ) ) {
			self::$wc_is_active = true;
			self::create_products();
		}
	}

	/**
	 * Create the WooCommerce products for self-serve listings.
	 */
	public static function create_products() {
		if ( ! self::$wc_is_active ) {
			return false;
		}

		$product_id = get_option( self::NEWSPACK_LISTINGS_PRODUCT_OPTION, false );
		if ( ! $product_id ) {
			$settings     = Settings::get_settings();
			$product_name = __( 'Self-Serve Listings', 'newspack-listings' );

			// Parent product.
			$parent_product = new \WC_Product_Grouped();
			$parent_product->set_name( $product_name );
			$parent_product->set_catalog_visibility( 'hidden' );
			$parent_product->set_virtual( true );
			$parent_product->set_downloadable( true );
			$parent_product->set_sold_individually( true );

			// Single listing product.
			$single_product = new \WC_Product_Simple();
			/* translators: %s: Product name */
			$single_product->set_name( sprintf( __( '%s: Single Listing', 'newspack-listings' ), $product_name ) );
			$single_product->set_regular_price( $settings['newspack_listings_single_price'] );
			$single_product->update_meta_data( '_newspack_listings_product_slug', 'newspack_listings_single_price' );
			$single_product->set_virtual( true );
			$single_product->set_downloadable( true );
			$single_product->set_catalog_visibility( 'hidden' );
			$single_product->set_sold_individually( true );
			$single_product->save();

			// Single "featured" listing upgrade.
			$featured_upgrade_single = new \WC_Product_Simple();
			/* translators: %s: Product name */
			$featured_upgrade_single->set_name( sprintf( __( '%s: “Featured” Listing Upgrade', 'newspack-listings' ), $product_name ) );
			$featured_upgrade_single->set_regular_price( $settings['newspack_listings_featured_add_on'] );
			$featured_upgrade_single->update_meta_data( '_newspack_listings_product_slug', 'newspack_listings_featured_add_on' );
			$featured_upgrade_single->set_virtual( true );
			$featured_upgrade_single->set_downloadable( true );
			$featured_upgrade_single->set_catalog_visibility( 'hidden' );
			$featured_upgrade_single->set_sold_individually( true );
			$featured_upgrade_single->save();

			// Monthly subscription product.
			$monthly_product = new \WC_Product_Subscription();
			/* translators: %s: Product name */
			$monthly_product->set_name( sprintf( __( '%s: Monthly Subscription', 'newspack-listings' ), $product_name ) );
			$monthly_product->set_regular_price( $settings['newspack_listings_subscription_price'] );
			$monthly_product->update_meta_data( '_newspack_listings_product_slug', 'newspack_listings_subscription_price' );
			$monthly_product->update_meta_data( '_subscription_price', wc_format_decimal( $settings['newspack_listings_subscription_price'] ) );
			$monthly_product->update_meta_data( '_subscription_period', 'month' );
			$monthly_product->update_meta_data( '_subscription_period_interval', 1 );
			$monthly_product->set_virtual( true );
			$monthly_product->set_downloadable( true );
			$monthly_product->set_catalog_visibility( 'hidden' );
			$monthly_product->set_sold_individually( true );
			$monthly_product->save();

			// Monthly "featured" listing upgrade.
			$featured_upgrade_monthly = new \WC_Product_Subscription();
			/* translators: %s: Product name */
			$featured_upgrade_monthly->set_name( sprintf( __( '%s: “Featured” Listing Upgrade (subscription)', 'newspack-listings' ), $product_name ) );
			$featured_upgrade_monthly->set_regular_price( $settings['newspack_listings_featured_add_on'] );
			$featured_upgrade_monthly->update_meta_data( '_newspack_listings_product_slug', 'newspack_listings_featured_add_on' );
			$featured_upgrade_monthly->update_meta_data( '_subscription_price', wc_format_decimal( $settings['newspack_listings_featured_add_on'] ) );
			$featured_upgrade_monthly->update_meta_data( '_subscription_period', 'month' );
			$featured_upgrade_monthly->update_meta_data( '_subscription_period_interval', 1 );
			$featured_upgrade_monthly->set_virtual( true );
			$featured_upgrade_monthly->set_downloadable( true );
			$featured_upgrade_monthly->set_catalog_visibility( 'hidden' );
			$featured_upgrade_monthly->set_sold_individually( true );
			$featured_upgrade_monthly->save();

			// Monthly "premium subscription" upgrade.
			$premium_upgrade_monthly = new \WC_Product_Subscription();
			/* translators: %s: Product name */
			$premium_upgrade_monthly->set_name( sprintf( __( '%s: Premium Subscription Upgrade', 'newspack-listings' ), $product_name ) );
			$premium_upgrade_monthly->set_regular_price( $settings['newspack_listings_premium_subscription_add_on'] );
			$premium_upgrade_monthly->update_meta_data( '_newspack_listings_product_slug', 'newspack_listings_premium_subscription_add_on' );
			$premium_upgrade_monthly->update_meta_data( '_subscription_price', wc_format_decimal( $settings['newspack_listings_premium_subscription_add_on'] ) );
			$premium_upgrade_monthly->update_meta_data( '_subscription_period', 'month' );
			$premium_upgrade_monthly->update_meta_data( '_subscription_period_interval', 1 );
			$premium_upgrade_monthly->set_virtual( true );
			$premium_upgrade_monthly->set_downloadable( true );
			$premium_upgrade_monthly->set_catalog_visibility( 'hidden' );
			$premium_upgrade_monthly->set_sold_individually( true );
			$premium_upgrade_monthly->save();

			$parent_product->set_children(
				[
					$single_product->get_id(),
					$featured_upgrade_single->get_id(),
					$monthly_product->get_id(),
					$featured_upgrade_monthly->get_id(),
					$premium_upgrade_monthly->get_id(),
				]
			);
			$parent_product->save();
			update_option( self::NEWSPACK_LISTINGS_PRODUCT_OPTION, $parent_product->get_id() );
		}
	}

	/**
	 * When Newspack Listing settings are updated, update the corresopnding WC products as well.
	 *
	 * @param string $option Name of the option to update.
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $new_value The new option value.
	 */
	public static function update_products( $option, $old_value, $new_value ) {
		if ( ! self::$wc_is_active ) {
			return false;
		}

		// Only if the updated option is a Newspack Listing setting.
		$settings = Settings::get_settings();
		if ( ! in_array( $option, array_keys( $settings ) ) ) {
			return;
		}

		$product_id = get_option( self::NEWSPACK_LISTINGS_PRODUCT_OPTION, false );
		if ( $product_id ) {
			$parent_product = \wc_get_product( $product_id );
			$parent_product->set_status( 'publish' );
			$parent_product->save();

			foreach ( $parent_product->get_children() as $child_id ) {
				$child_product = \wc_get_product( $child_id );

				if ( ! $child_product ) {
					continue;
				}

				$settings_slug = $child_product->get_meta( '_newspack_listings_product_slug' );
				if ( $option === $settings_slug ) {
					// Set base price.
					$child_product->set_regular_price( $new_value );

					// Set subscription price, if applicable.
					if ( 'subscription' === $child_product->get_type() ) {
						$child_product->update_meta_data( '_subscription_price', \wc_format_decimal( $new_value ) );
					}

					$child_product->save();
				}
			}
		}
	}

	/**
	 * Get the WooCommerce product for the parent listings product.
	 */
	public static function get_products() {
		if ( ! self::$wc_is_active ) {
			return false;
		}

		$product_id = get_option( self::NEWSPACK_LISTINGS_PRODUCT_OPTION, false );
		$product    = \wc_get_product( $product_id );

		if ( ! $product || ! $product->is_type( 'grouped' ) ) {
			return false;
		}

		return $product;
	}
}

Newspack_Listings_Products::instance();
