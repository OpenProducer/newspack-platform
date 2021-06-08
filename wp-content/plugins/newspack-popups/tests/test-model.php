<?php
/**
 * Class Model Test
 *
 * @package Newspack_Popups
 */

/**
 * Model test case.
 */
class ModelTest extends WP_UnitTestCase {
	private static $popup_id = false; // phpcs:ignore Squiz.Commenting.VariableComment.Missing

	public static function wpSetUpBeforeClass() { // phpcs:ignore Squiz.Commenting.FunctionComment.Missing
		self::$popup_id = self::factory()->post->create(
			[
				'post_type'    => Newspack_Popups::NEWSPACK_POPUPS_CPT,
				'post_title'   => 'Platea fames',
				'post_content' => 'Faucibus placerat senectus.',
			]
		);
	}

	/**
	 * Test popup object creation.
	 */
	public function test_popup_object_creation() {
		$popup_object_default = Newspack_Popups_Model::create_popup_object( get_post( self::$popup_id ) );
		self::assertEquals(
			$popup_object_default['options'],
			[
				'background_color'        => '#FFFFFF',
				'display_title'           => false,
				'hide_border'             => false,
				'dismiss_text'            => '',
				'dismiss_text_alignment'  => 'center',
				'frequency'               => 'always',
				'overlay_color'           => '#000000',
				'overlay_opacity'         => '30',
				'placement'               => 'inline',
				'trigger_type'            => 'scroll',
				'trigger_delay'           => '3',
				'trigger_scroll_progress' => '30',
				'utm_suppression'         => null,
				'selected_segment_id'     => '',
			],
			'Default options are as expected.'
		);

		$popup_object = Newspack_Popups_Model::create_popup_object(
			get_post( self::$popup_id ),
			false,
			[
				'trigger_type'            => 'scroll',
				'trigger_scroll_progress' => '42',
			]
		);
		self::assertEquals(
			$popup_object['options']['trigger_scroll_progress'],
			'42',
			'Sets options when passed as argument.'
		);
	}

	/**
	 * Test popup markup generation.
	 */
	public function test_markup_generation() {
		Newspack_Popups_Model::set_popup_options(
			self::$popup_id,
			[
				'placement'    => 'center',
				'trigger_type' => 'time',
			]
		);

		$popup_object_default = Newspack_Popups_Model::create_popup_object( get_post( self::$popup_id ) );

		$dom = new DomDocument();
		@$dom->loadHTML( Newspack_Popups_Model::generate_popup( $popup_object_default ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$xpath = new DOMXpath( $dom );

		self::assertEquals(
			0,
			$xpath->query( '//*[@id="page-position-marker"]' )->length,
			'The page position marker is not output for a default (time-triggered) popup.'
		);

		self::assertEquals(
			'visibility',
			$xpath->query( '//amp-animation' )->item( 0 )->getAttribute( 'trigger' ),
			'The amp-animation trigger is set to "visibility" for default (time-triggered) popup.'
		);

		$popup_object_with_just_scroll = Newspack_Popups_Model::create_popup_object(
			get_post( self::$popup_id ),
			false,
			[
				'placement'    => 'center',
				'trigger_type' => 'scroll',
			]
		);

		$dom = new DomDocument();
		@$dom->loadHTML( Newspack_Popups_Model::generate_popup( $popup_object_with_just_scroll ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$xpath = new DOMXpath( $dom );

		self::assertContains(
			'top: 0%',
			$xpath->query( '//*[@id="page-position-marker"]' )->item( 0 )->getAttribute( 'style' ),
			'The position marker is set at 0% by default.'
		);

		$popup_object_with_set_scroll_progress = Newspack_Popups_Model::create_popup_object(
			get_post( self::$popup_id ),
			false,
			[
				'placement'               => 'center',
				'trigger_type'            => 'scroll',
				'trigger_scroll_progress' => 42,
			]
		);

		$dom = new DomDocument();
		@$dom->loadHTML( Newspack_Popups_Model::generate_popup( $popup_object_with_set_scroll_progress ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$xpath = new DOMXpath( $dom );

		self::assertContains(
			'top: 42%',
			$xpath->query( '//*[@id="page-position-marker"]' )->item( 0 )->getAttribute( 'style' ),
			'The position marker is set at position passed in options.'
		);
	}
}
