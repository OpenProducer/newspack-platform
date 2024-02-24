<?php
/**
 * Plugin Name: Newspack
 * Description: An advanced open-source publishing and revenue-generating platform for news organizations.
 * Version: 3.1.1
 * Author: Automattic
 * Author URI: https://newspack.com/
 * License: GPL2
 * Text Domain: newspack-plugin
 * Domain Path: /languages/
 *
 * @package         Newspack_Plugin
 */

defined( 'ABSPATH' ) || exit;

define( 'NEWSPACK_PLUGIN_VERSION', '3.1.1' );

// Load language files.
load_plugin_textdomain( 'newspack-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

// Define NEWSPACK_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_PLUGIN_FILE', __FILE__ );
}

require_once 'vendor/autoload.php';

// Include the main Newspack class.
if ( ! class_exists( 'Newspack' ) ) {
	include_once __DIR__ . '/includes/class-newspack.php';
}
