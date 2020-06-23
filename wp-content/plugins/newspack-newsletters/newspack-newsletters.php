<?php
/**
 * Plugin Name:     Newspack Newsletters
 * Plugin URI:      https://newspack.blog
 * Description:     Newsletter authoring using the Gutenberg editor.
 * Author:          Automattic
 * Author URI:      https://newspack.blog
 * Text Domain:     newspack-newsletters
 * Domain Path:     /languages
 * Version:         1.2.0
 *
 * @package         Newspack_Newsletters
 */

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_NEWSLETTERS_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_NEWSLETTERS_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_NEWSLETTERS_PLUGIN_FILE', plugin_dir_path( __FILE__ ) );
}

// Include the main Newspack Newsletters class.
if ( ! class_exists( 'Newspack_Newsletters' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-newspack-newsletters.php';
}
