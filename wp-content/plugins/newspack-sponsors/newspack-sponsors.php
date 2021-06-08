<?php
/**
 * Plugin Name:     Newspack Sponsors
 * Plugin URI:      https://newspack.pub
 * Description:     Add sponsors and sponsor info to posts. Allows special visual treatment for sponsored content.
 * Author:          Automattic
 * Author URI:      https://newspack.pub
 * Text Domain:     newspack-sponsors
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Newspack_Sponsors
 */

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_SPONSORS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_SPONSORS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_SPONSORS_PLUGIN_FILE', plugin_dir_path( __FILE__ ) );
	define( 'NEWSPACK_SPONSORS_URL', plugin_dir_url( __FILE__ ) );
}

// Include plugin resources.
require_once NEWSPACK_SPONSORS_PLUGIN_FILE . '/vendor/autoload.php';
require_once NEWSPACK_SPONSORS_PLUGIN_FILE . '/includes/class-newspack-sponsors-core.php';
require_once NEWSPACK_SPONSORS_PLUGIN_FILE . '/includes/class-newspack-sponsors-settings.php';
require_once NEWSPACK_SPONSORS_PLUGIN_FILE . '/includes/class-newspack-sponsors-editor.php';
require_once NEWSPACK_SPONSORS_PLUGIN_FILE . '/includes/newspack-sponsors-theme-helpers.php';
