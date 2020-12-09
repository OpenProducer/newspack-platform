<?php
/**
 * Plugin Name:     Newspack Ads
 * Plugin URI:      https://newspack.blog
 * Description:     Ad services integration.
 * Author:          Automattic
 * License:         GPL2
 * Version:         1.10.0
 *
 * @package         Newspack
 */

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_ADS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_ADS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_ADS_PLUGIN_FILE', __FILE__ );
}

// Include the main Newspack Google Ad Manager class.
if ( ! class_exists( 'Newspack_Ads' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-newspack-ads.php';
}
