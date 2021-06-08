<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Newspack_Blocks
 */

$newspack_blocks_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $newspack_blocks_tests_dir ) {
	$newspack_blocks_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $newspack_blocks_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $newspack_blocks_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $newspack_blocks_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function newspack_blocks_manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/newspack-blocks.php';
}
tests_add_filter( 'muplugins_loaded', 'newspack_blocks_manually_load_plugin' );

// Start up the WP testing environment.
require $newspack_blocks_tests_dir . '/includes/bootstrap.php';
