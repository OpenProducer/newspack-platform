<?php
/**
 * Tests the plugin management functionality.
 *
 * @package Newspack\Tests
 */

/**
 * Test plugin management functionality.
 */
class Newspack_Ads_Test_Plugin_Manager extends WP_UnitTestCase {

	/**
	 * Test adding a unit.
	 */
	public function test_add_unit() {
		$unit = array(
			'name'       => 'testname',
			'code'       => 'testcode',
			'sizes'      => [ [ 120, 120 ] ],
			'ad_service' => 'google_ad_manager',
		);

		$result = Newspack_Ads_Model::add_ad_unit( $unit );
		$this->assertTrue( $result['id'] > 0 );
		$this->assertEquals( $unit['name'], $result['name'] );
		$this->assertEquals( $unit['code'], $result['code'] );
		$this->assertEquals( $unit['sizes'], $result['sizes'] );
	}

	/**
	 * Test updating a unit.
	 */
	public function test_update_unit() {
		$unit = array(
			'name'       => 'test',
			'code'       => 'testcode',
			'sizes'      => [ [ 120, 120 ] ],
			'ad_service' => 'google_ad_manager',
		);

		$result          = Newspack_Ads_Model::add_ad_unit( $unit );
		$update          = $result;
		$update['name']  = 'new test';
		$update['code']  = 'new_test';
		$update['sizes'] = [ [ 120, 120 ] ];

		$update_result = Newspack_Ads_Model::update_ad_unit( $update );
		$this->assertEquals( $update, $update_result );
	}


	/**
	 * Test deleting a unit.
	 */
	public function test_delete_unit() {
		$unit = array(
			'name'       => 'test',
			'code'       => 'testcode',
			'sizes'      => [ [ 120, 120 ] ],
			'ad_service' => 'google_ad_manager',
		);

		$result        = Newspack_Ads_Model::add_ad_unit( $unit );
		$delete_result = Newspack_Ads_Model::delete_ad_unit( $result['id'] );
		$this->assertTrue( $delete_result );
		$saved_unit = Newspack_Ads_Model::get_ad_unit( $result['id'] );
		$this->assertTrue( is_wp_error( $saved_unit ) );

		$generic_post = array(
			'post_title'  => 'Title',
			'post_type'   => 'post',
			'post_status' => 'publish',
		);

		$generic_post_id = wp_insert_post( $generic_post );
		$delete_result   = Newspack_Ads_Model::delete_ad_unit( $generic_post_id );
		$this->assertTrue( is_wp_error( $delete_result ) );

	}

	/**
	 * Test retrieving all units.
	 */
	public function test_get_units() {
		$unit1 = array(
			'name'       => 'test1',
			'code'       => 'test1_code',
			'sizes'      => [ [ 120, 120 ] ],
			'ad_service' => 'google_ad_manager',
		);
		$unit2 = array(
			'name'       => 'test2',
			'code'       => 'test2_code',
			'sizes'      => [ [ 120, 120 ] ],
			'ad_service' => 'google_ad_manager',
		);
		Newspack_Ads_Model::add_ad_unit( $unit1 );
		Newspack_Ads_Model::add_ad_unit( $unit2 );
		$units = Newspack_Ads_Model::get_ad_units();
		$this->assertEquals( 2, count( $units ) );
		foreach ( $units as $unit ) {
			$this->assertTrue( $unit['id'] > 0 );
			$this->assertTrue( $unit['name'] === $unit1['name'] || $unit['name'] === $unit2['name'] );
			$this->assertTrue( $unit['code'] === $unit1['code'] || $unit['code'] === $unit2['code'] );
			$this->assertTrue( $unit['sizes'] === $unit1['sizes'] || $unit['sizes'] === $unit2['sizes'] );
		}
	}

	/**
	 * Test sanitization functions.
	 */
	public function test_sanitization() {
		$sizes = [ [ 10, 10 ], [ 100, 100 ] ];
		$this->assertEquals( $sizes, Newspack_Ads_Model::sanitize_sizes( $sizes ) );

		$sizes = [ [ 10, 10 ] ];
		$this->assertEquals( $sizes, Newspack_Ads_Model::sanitize_sizes( $sizes ) );

		$sizes = [ [ 10, 10, 90 ] ];
		$this->assertNotEquals( $sizes, Newspack_Ads_Model::sanitize_sizes( $sizes ) );

		$sizes = [ [ 'dog', 'cat' ] ];
		$this->assertNotEquals( $sizes, Newspack_Ads_Model::sanitize_sizes( $sizes ) );

		$sizes = 'notanarray';
		$this->assertNotEquals( $sizes, Newspack_Ads_Model::sanitize_sizes( $sizes ) );

		$ad_service = 'google_ad_manager';
		$this->assertEquals( $ad_service, Newspack_Ads_Model::sanitize_ad_service( $ad_service ) );

		$ad_service = 'something_else';
		$this->assertNotEquals( $ad_service, Newspack_Ads_Model::sanitize_ad_service( $ad_service ) );

	}
}
