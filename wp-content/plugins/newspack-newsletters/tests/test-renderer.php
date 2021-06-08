<?php
/**
 * Class Newsletters Renderer Test
 *
 * @package Newspack_Newsletters
 */

/**
 * Newsletters Renderer Test.
 */
class Newsletters_Renderer_Test extends WP_UnitTestCase {

	/**
	 * Test the MJML rendering function.
	 */
	public function test_render_mjml_component() {
		$inner_html                                   = '<p>Hello, Newspack!</p>\n';
		Newspack_Newsletters_Renderer::$color_palette = [
			'vivid-purple' => '#db18e6',
		];

		$this->assertEquals(
			Newspack_Newsletters_Renderer::render_mjml_component(
				[
					'blockName'   => 'core/paragraph',
					'attrs'       => [],
					'innerBlocks' => [],
					'innerHTML'   => $inner_html,
				]
			),
			'<mj-section padding="0"><mj-column padding="12px" width="100%"><mj-text padding="0" line-height="1.8" font-size="16px" >' . $inner_html . '</mj-text></mj-column></mj-section>',
			'Renders default paragraph'
		);

		$this->assertEquals(
			Newspack_Newsletters_Renderer::render_mjml_component(
				[
					'blockName'   => 'core/paragraph',
					'attrs'       => [
						'textColor' => 'vivid-purple',
						'fontSize'  => 'normal',
						'style'     => [
							'color' => [
								'background' => '#4aadd7',
							],
						],
					],
					'innerBlocks' => [],
					'innerHTML'   => $inner_html,
				]
			),
			'<mj-section textColor="vivid-purple" color="#db18e6" background-color="#4aadd7" font-size="16px" padding="0"><mj-column padding="12px" width="100%"><mj-text padding="0" line-height="1.8" font-size="16px"  textColor="vivid-purple" color="#db18e6" container-background-color="#4aadd7">' . $inner_html . '</mj-text></mj-column></mj-section>',
			'Renders styled paragraph'
		);
	}

	/**
	 * Test other rendering-related function.
	 */
	public function test_aux_functions() {
		$this->assertEquals(
			Newspack_Newsletters_Renderer::process_links( '<a href="//newspack.pub">linky<a>' ),
			'<a href="//newspack.pub?utm_medium=email">linky<a>',
			'Appends utm_medium=email to links'
		);
		$this->assertEquals(
			Newspack_Newsletters_Renderer::process_links( '<a href="//newspack.pub?value=1">linky<a>' ),
			'<a href="//newspack.pub?value=1&utm_medium=email">linky<a>',
			'Appends utm_medium=email to links with params'
		);
	}

	/**
	 * Rendering a reusable block component.
	 */
	public function test_reusable_block() {
		$reusable_block_post_id = self::factory()->post->create(
			[
				'post_type'    => 'wp_block',
				'post_title'   => 'Reusable block.',
				'post_content' => "<!-- wp:paragraph -->\n<p>Hello<\/p>\n<!-- \/wp:paragraph -->",
			]
		);
		$newsletter_post        = self::factory()->post->create(
			[
				'post_type'    => Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
				'post_title'   => 'A newsletter with a reusable block in it.',
				'post_content' => '<!-- wp:block {"ref":' . $reusable_block_post_id . '} /-->',
			]
		);
		$this->assertEquals(
			Newspack_Newsletters_Renderer::post_to_mjml_components(
				get_post( $newsletter_post )
			),
			"<mj-wrapper ref=\"4\"><mj-section padding=\"0\"><mj-column padding=\"12px\" width=\"100%\"><mj-text padding=\"0\" line-height=\"1.8\" font-size=\"16px\" >\n<p>Hello</p>\n</mj-text></mj-column></mj-section></mj-wrapper>",
			'Renders the reusable block into valid markup'
		);
	}

	/**
	 * Rendering with custom CSS.
	 */
	public function test_custom_css() {
		$custom_css_str  = 'p { color: pink; }';
		$newsletter_post = self::factory()->post->create(
			[
				'post_type'    => Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
				'post_title'   => 'A newsletter with custom CSS.',
				'post_content' => "<!-- wp:paragraph -->\n<p>A paragraph with some custom CSS applied.<\/p>\n<!-- \/wp:paragraph -->",
			]
		);
		$post_object     = get_post( $newsletter_post );

		// Add the custom CSS.
		update_post_meta( $post_object->ID, 'custom_css', $custom_css_str );

		$this->assertContains(
			$custom_css_str,
			Newspack_Newsletters_Renderer::render_post_to_mjml( $post_object ),
			'Rendered email contains the custom CSS.'
		);
	}
}
