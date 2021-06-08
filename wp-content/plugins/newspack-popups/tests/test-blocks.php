<?php
/**
 * Class Blocks Test
 *
 * @package Newspack_Popups
 */

/**
 * Blocks test case.
 */
class BlocksTest extends WP_UnitTestCase {
	public function setUp() { // phpcs:ignore Squiz.Commenting.FunctionComment.Missing
		// Remove any popups (from previous tests).
		foreach ( Newspack_Popups_Model::retrieve_popups() as $popup ) {
			wp_delete_post( $popup['id'] );
		}
	}

	/**
	 * Create a popup.
	 *
	 * @param object $options Popup options.
	 */
	private function create_popup( $options = [] ) {
		$popup_id = self::factory()->post->create(
			[
				'post_type'    => Newspack_Popups::NEWSPACK_POPUPS_CPT,
				'post_title'   => 'Popup title',
				'post_content' => 'Hello, world.',
			]
		);
		Newspack_Popups_Model::set_popup_options( $popup_id, $options );
		return $popup_id;
	}

	/**
	 * Basic Block rendering - Single Prompt block.
	 */
	public function test_prompt_block_rendering() {
		$inline_popup_id       = self::create_popup( [ 'placement' => 'inline' ] );
		$overlay_popup_id      = self::create_popup( [ 'placement' => 'center' ] );
		$inline_block_content  = Newspack_Popups\Prompt_Block\render_block( [ 'promptId' => $inline_popup_id ] );
		$overlay_block_content = Newspack_Popups\Prompt_Block\render_block( [ 'promptId' => $overlay_popup_id ] );

		self::assertEquals(
			$inline_block_content,
			'<!-- wp:shortcode -->[newspack-popup id="' . $inline_popup_id . '"]<!-- /wp:shortcode -->',
			'Includes inline popup shortcode.'
		);

		self::assertEquals(
			$overlay_block_content,
			'',
			'Overlay prompt not rendered by the Single Prompt block.'
		);
	}

	/**
	 * Basic Block rendering - Custom Placement block.
	 */
	public function test_custom_placement_block_rendering() {
		$custom_placement_id = 'custom1';
		$popup_id            = self::create_popup( [ 'placement' => $custom_placement_id ] );
		$block_content       = Newspack_Popups\Custom_Placement_Block\render_block( [ 'customPlacement' => $custom_placement_id ] );

		self::assertEquals(
			$block_content,
			'<!-- wp:shortcode -->[newspack-popup id="' . $popup_id . '"]<!-- /wp:shortcode -->',
			'Includes popup shortcode.'
		);
	}

	/**
	 * Block rendering with conflicting popups.
	 */
	public function test_block_rendering_with_conflict() {
		$custom_placement_id = 'custom1';
		$popup_id_first      = self::create_popup( [ 'placement' => $custom_placement_id ] );
		sleep( 1 ); // Ensure the creation dates are not the same.
		$popup_id_second = self::create_popup( [ 'placement' => $custom_placement_id ] );
		$block_content   = Newspack_Popups\Custom_Placement_Block\render_block( [ 'customPlacement' => $custom_placement_id ] );

		self::assertEquals(
			$block_content,
			'<!-- wp:shortcode -->[newspack-popup id="' . $popup_id_second . '"]<!-- /wp:shortcode --><!-- wp:shortcode -->[newspack-popup id="' . $popup_id_first . '"]<!-- /wp:shortcode -->',
			'Includes all popup shortcodes in case of a conflict, since the API will decide what to show.'
		);
	}
}
