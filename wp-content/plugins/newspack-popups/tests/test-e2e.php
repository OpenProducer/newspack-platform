<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Class E2E Test
 *
 * @package Newspack_Popups
 */

/**
 * E2E test case.
 * (as e2e as we can get without spinning up a headless browser).
 */
class E2ETest extends WP_UnitTestCase_PageWithPopups {
	/**
	 * Basic E2E test - render popups on a page and check API response.
	 */
	public function test_e2e_basic() {
		$another_popup_id = self::createPopup();
		self::renderPost();

		self::assertEquals(
			[
				'id_' . $another_popup_id => true,
				'id_' . self::$popup_id   => true,
			],
			self::getAPIResponse(),
			'API response has expected shape.'
		);
	}

	/**
	 * Mutual exclusion.
	 * A page should render at most one popup of each condition:
	 * - overlays,
	 * - above-header,
	 * - each of custom placements.
	 */
	public function test_e2e_mutual_exclusion() {
		// Remove the default popup that would be programmatically inserted.
		wp_delete_post( self::$popup_id );

		$popups = [
			// Overlays.
			self::createPopup( null, [ 'placement' => 'center' ] ),
			self::createPopup(
				null,
				[
					'placement'           => 'center',
					'selected_segment_id' => self::$segments[0]['id'],
				]
			),
			// Above-header.
			self::createPopup( null, [ 'placement' => 'above_header' ] ),
			self::createPopup(
				null,
				[
					'placement'           => 'above_header',
					'selected_segment_id' => self::$segments[0]['id'],
				]
			),
			// Custom Placement 1.
			self::createPopup( null, [ 'placement' => 'custom1' ] ),
			self::createPopup(
				null,
				[
					'placement'           => 'custom1',
					'selected_segment_id' => self::$segments[0]['id'],
				]
			),
		];

		self::renderPost( '', '<!-- wp:newspack-popups/custom-placement {"customPlacement":"custom1"} /-->' );

		self::assertEquals(
			[
				'id_' . $popups[0] => false,
				'id_' . $popups[1] => true,
				'id_' . $popups[2] => false,
				'id_' . $popups[3] => true,
				'id_' . $popups[4] => false,
				'id_' . $popups[5] => true,
			],
			self::getAPIResponse(),
			'Only one popup (the one with segment assigned) of each mutually-excluding pair is displayed.'
		);
	}
}
