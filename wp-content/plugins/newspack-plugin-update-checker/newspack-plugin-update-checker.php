<?php
/**
 * Plugin Name: Newspack Plugin Update Checker
 * Description: Keep tabs on updates to Newspack plugins that are only available on Github
 * Version: 1.0.0
 * Author: Media Toybox
 * Author URI: https://mediatoybox.com
 * License: GPL2
 */

defined( 'ABSPATH' ) || exit;

// load the update checker library https://github.com/YahnisElsts/plugin-update-checker
require_once 'vendor/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// the Automattic github org url
define( 'A8C_GH_URL', 'https://github.com/Automattic/' );

if ( ! function_exists( 'npuc_newspack_plugin_update' ) ) {
    // loop through the plugins, make sure they exist, run the update checker
    function npuc_newspack_plugin_update() {

        // the most commonly used Newspack plugins that are not in the wp.org directory
        $newspack_plugin_list = array(
            'newspack-plugin',
            'newspack-ads',
            'newspack-blocks',
            'newspack-popups',
            'newspack-listings',
            'newsspack-sponsors',
            'newspack-media-partners',
            'newspack-rss-enhancements',
            'newspack-supporters'
        );
        apply_filters( 'npuc_newspack_plugin_list', $newspack_plugin_list );

        foreach ( $newspack_plugin_list as $plugin_slug ) {
            if ( $plugin_slug === 'newspack-plugin' ) {
                // the main Newspack plugin uses newspack.php instead of newspack-plugin.php
                $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_slug . '/newspack.php';
            } else {
                $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_slug . '.php';
            }
            
            // github repo URL for the plugin
            $github_url = A8C_GH_URL . $plugin_slug;

            // check to make sure this particular plugin file exists before we check for updates
            if ( file_exists( $plugin_file ) ) {
                $my_update_checker = PucFactory::buildUpdateChecker(
                    $github_url,
                    $plugin_file,
                    $plugin_slug,
                );
                // Newspack uses composer to build releases so we need to use the zip file from the latest release instead of the stable branch.
                $my_update_checker->getVcsApi()->enableReleaseAssets();
            }
        }
    }
};
add_action( 'plugins_loaded', 'npuc_newspack_plugin_update' );
