<?php
/**
 * Plugin Name:     Newspack Campaigns
 * Plugin URI:      https://newspack.blog
 * Description:     AMP-compatible overlay and inline Campaigns.
 * Author:          Automattic
 * Author URI:      https://newspack.blog
 * Text Domain:     newspack-popups
 * Domain Path:     /languages
 * Version:         2.1.0
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
