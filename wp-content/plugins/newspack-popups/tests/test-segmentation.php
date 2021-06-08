<?php
/**
 * Class Segmentation Test
 *
 * @package Newspack_Popups
 */

/**
 * Segmentation test case.
 */
class SegmentationTest extends WP_UnitTestCase {
	private static $post_read_payload = []; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	private static $request = []; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	public function setUp() { // phpcs:ignore Squiz.Commenting.FunctionComment.Missing
		global $wpdb;
		$events_table_name = Segmentation::get_events_table_name();
		$wpdb->query( "DELETE FROM $events_table_name;" ); // phpcs:ignore
		if ( file_exists( Segmentation::get_log_file_path() ) ) {
			unlink( Segmentation::get_log_file_path() ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
		}
		self::$post_read_payload = [
			'is_post'    => true,
			'clientId'   => 'test-' . uniqid(),
			'post_id'    => '42',
			'categories' => '5,6',
			'created_at' => gmdate( 'Y-m-d H:i:s' ),
		];
		self::$request           = [
			'cid'      => self::$post_read_payload['clientId'],
			'popups'   => wp_json_encode( [] ),
			'settings' => wp_json_encode( [] ),
			'visit'    => wp_json_encode(
				[
					'post_id'    => self::$post_read_payload['post_id'],
					'categories' => self::$post_read_payload['categories'],
					'is_post'    => true,
					'date'       => gmdate( 'Y-m-d', time() ),
				]
			),
		];
	}

	/**
	 * Log file updating with a post_read event.
	 */
	public function test_log_visit() {
		Segmentation_Report::log_single_visit( self::$post_read_payload );

		$expected_log_line = implode(
			';',
			[
				'post_read',
				self::$post_read_payload['clientId'],
				gmdate( 'Y-m-d H:i:s', time() ),
				self::$post_read_payload['post_id'],
				self::$post_read_payload['categories'],
			]
		) . "\n";

		self::assertEquals(
			$expected_log_line,
			file_get_contents( Segmentation::get_log_file_path() ),
			'Log file contains the expected line.'
		);

		Segmentation_Report::log_single_visit(
			array_merge(
				self::$post_read_payload,
				[
					'is_post' => false,
				]
			)
		);

		self::assertEquals(
			$expected_log_line,
			file_get_contents( Segmentation::get_log_file_path() ),
			'Log file is not updated after a non-post visit is reported.'
		);

		Newspack_Popups_Parse_Logs::parse_events_logs();

		self::assertEquals(
			'',
			file_get_contents( Segmentation::get_log_file_path() ),
			'Log file is emptied after parsing logs.'
		);
	}

	/**
	 * Reporting visits while checking popups visibility.
	 */
	public function test_visit_reporting() {
		$_REQUEST = self::$request;

		// Log an article read event.
		// Checking campaign visibility will log a visit, this way there are less API requests.
		$maybe_show_campaign = new Maybe_Show_Campaign();

		// And a duplicate.
		$maybe_show_campaign = new Maybe_Show_Campaign();

		$date = gmdate( 'Y-m-d', strtotime( '+1 week' ) );

		// Duplicate article read, but on a different date.
		$_REQUEST            = self::$request;
		$_REQUEST['visit']   = wp_json_encode(
			array_merge(
				(array) json_decode( self::$request['visit'] ),
				[
					'date' => $date,
				]
			)
		);
		$maybe_show_campaign = new Maybe_Show_Campaign();

		$read_posts = $maybe_show_campaign->get_client_data( self::$post_read_payload['clientId'] )['posts_read'];

		self::assertEquals(
			1,
			count( $read_posts ),
			'The read posts array is of expected length – the duplicates were not inserted.'
		);

		self::assertEquals(
			[
				'post_id'      => self::$post_read_payload['post_id'],
				'category_ids' => self::$post_read_payload['categories'],
				'created_at'   => self::$post_read_payload['created_at'],
			],
			$read_posts[0],
			'The read posts array contains the reported post.'
		);

		// Now a visit with a different post id.
		$_REQUEST            = self::$request;
		$_REQUEST['visit']   = wp_json_encode(
			array_merge(
				(array) json_decode( self::$request['visit'] ),
				[
					'post_id' => '23',
					'date'    => $date,
				]
			)
		);
		$maybe_show_campaign = new Maybe_Show_Campaign();

		$read_posts = $maybe_show_campaign->get_client_data( self::$post_read_payload['clientId'] )['posts_read'];

		self::assertEquals(
			2,
			count( $read_posts ),
			'The read posts array is of expected length after reading another post.'
		);

		// Now a non-post visit.
		$_REQUEST            = self::$request;
		$_REQUEST['visit']   = wp_json_encode(
			array_merge(
				(array) json_decode( self::$request['visit'] ),
				[
					'post_id' => '12345',
					'is_post' => false,
				]
			)
		);
		$maybe_show_campaign = new Maybe_Show_Campaign();
		$read_posts          = $maybe_show_campaign->get_client_data( self::$post_read_payload['clientId'] )['posts_read'];

		self::assertEquals(
			2,
			count( $read_posts ),
			'The read posts array is not updated after a non-post visit was made.'
		);
	}

	/**
	 * Parse a "view as" spec.
	 */
	public function test_parse_view_as() {
		self::assertEquals(
			Segmentation::parse_view_as( 'groups:one,two;segment:123' ),
			[
				'groups'  => 'one,two',
				'segment' => '123',
			],
			'Spec is parsed.'
		);

		self::assertEquals(
			Segmentation::parse_view_as( 'all' ),
			[
				'all' => true,
			],
			'Spec is parsed with the "all" value'
		);

		self::assertEquals(
			Segmentation::parse_view_as( '' ),
			[],
			'Empty array is returned if there is no spec.'
		);
	}
}
