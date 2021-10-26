<?php
/**
 * Plugin Name:     Newspack Listings
 * Plugin URI:      https://newspack.pub
 * Description:     Listings and directories for Newspack sites.
 * Author:          Automattic
 * Author URI:      https://newspack.pub
 * Text Domain:     newspack-listings
 * Domain Path:     /languages
 * Version:         2.6.0
 *
 * @package         Newspack_Listings
 */

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_LISTINGS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_LISTINGS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_LISTINGS_FILE', __FILE__ );
	define( 'NEWSPACK_LISTINGS_PLUGIN_FILE', plugin_dir_path( NEWSPACK_LISTINGS_FILE ) );
	define( 'NEWSPACK_LISTINGS_URL', plugin_dir_url( NEWSPACK_LISTINGS_FILE ) );
	define( 'NEWSPACK_LISTINGS_VERSION', '1.2.0' );
}

// Include plugin resources.
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/vendor/autoload.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/newspack-listings-utils.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-newspack-listings-settings.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-newspack-listings-core.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-newspack-listings-blocks.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-newspack-listings-block-patterns.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-newspack-listings-taxonomies.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-newspack-listings-api.php';

// Enable experimental/in-progress self-serve listings functionality.
if ( defined( 'NEWSPACK_LISTINGS_SELF_SERVE_ENABLED' ) && NEWSPACK_LISTINGS_SELF_SERVE_ENABLED ) {
	require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/class-newspack-listings-products.php';
}

// CLI importer files.
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/importer/newspack-listings-importer-utils.php';
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/importer/class-newspack-listings-importer.php';

// Migration utilities.
require_once NEWSPACK_LISTINGS_PLUGIN_FILE . '/includes/migration/class-newspack-listings-migration.php';
