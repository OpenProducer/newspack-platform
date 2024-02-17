<?php
/**
 * Plugin Name:     Newspack Campaigns
 * Plugin URI:      https://newspack.blog
 * Description:     Build persuasive call-to-action prompts from scratch and display them as overlays, inline with the story, or above the site header.
 * Author:          Automattic
 * Author URI:      https://newspack.com
 * Text Domain:     newspack-popups
 * Domain Path:     /languages
 * Version:         2.30.1
 *
 * @package         Newspack_Popups
 */

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_ADS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_POPUPS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_POPUPS_PLUGIN_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

// Include the main Newspack Google Ad Manager class.
if ( ! class_exists( 'Newspack_Popups' ) ) {
	include_once __DIR__ . '/includes/class-newspack-popups.php';
}
