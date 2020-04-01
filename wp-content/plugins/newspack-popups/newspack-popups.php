<?php
/**
 * Plugin Name:     Newspack Popups
 * Plugin URI:      https://newspack.block
 * Description:     AMP-compatible popup notifications.
 * Author:          Automattic
 * Author URI:      https://newspack.block
 * Text Domain:     newspack-popups
 * Domain Path:     /languages
 * Version:         1.2.0
 *
 * @package         Newspack_Popups
 */

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_ADS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_POPUPS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_POPUPS_PLUGIN_FILE', __FILE__ );
}

// Include the main Newspack Google Ad Manager class.
if ( ! class_exists( 'Newspack_Popups' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-newspack-popups.php';
}
