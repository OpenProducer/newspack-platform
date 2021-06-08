<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Newspack_Popups
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	$_SERVER['HTTP_REFERER'] = 'https://' . $_SERVER['HTTP_HOST']; // phpcs:ignore
	$_SERVER['HTTP_USER_AGENT'] = 'Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/89.0.4389.90 Safari\/537.36'; // phpcs:ignore

	require dirname( dirname( __FILE__ ) ) . '/newspack-popups.php';
	require dirname( dirname( __FILE__ ) ) . '/src/blocks/custom-placement/view.php';
	require dirname( dirname( __FILE__ ) ) . '/api/campaigns/class-maybe-show-campaign.php';
	require dirname( dirname( __FILE__ ) ) . '/api/campaigns/class-report-campaign-data.php';
	require dirname( dirname( __FILE__ ) ) . '/api/segmentation/class-segmentation-client-data.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

define( 'IS_TEST_ENV', 1 );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
require dirname( dirname( __FILE__ ) ) . '/tests/wp-unittestcase-pagewithpopups.php';
