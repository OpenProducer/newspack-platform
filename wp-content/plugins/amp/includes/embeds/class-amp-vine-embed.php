<?php
/**
 * Class AMP_Vine_Embed_Handler
 *
 * @package AMP
 */

/**
 * Class AMP_Vine_Embed_Handler
 */
class AMP_Vine_Embed_Handler extends AMP_Base_Embed_Handler {
	const URL_PATTERN = '#https?://vine\.co/v/([^/?]+)#i';

	/**
	 * Default width.
	 *
	 * @var int
	 */
	protected $DEFAULT_WIDTH = 400;

	/**
	 * Default height.
	 *
	 * @var int
	 */
	protected $DEFAULT_HEIGHT = 400;

	/**
	 * Registers embed.
	 */
	public function register_embed() {
		wp_embed_register_handler( 'amp-vine', self::URL_PATTERN, [ $this, 'oembed' ], -1 );
	}

	/**
	 * Unregisters embed.
	 */
	public function unregister_embed() {
		wp_embed_unregister_handler( 'amp-vine', -1 );
	}

	/**
	 * WordPress OEmbed rendering callback.
	 *
	 * @param array  $matches URL pattern matches.
	 * @param array  $attr    Matched attributes.
	 * @param string $url     Matched URL.
	 * @return string HTML markup for rendered embed.
	 */
	public function oembed( $matches, $attr, $url ) {
		return $this->render(
			[
				'url'     => $url,
				'vine_id' => end( $matches ),
			]
		);
	}

	/**
	 * Gets the rendered embed markup.
	 *
	 * @param array $args Embed rendering arguments.
	 * @return string HTML markup for rendered embed.
	 */
	public function render( $args ) {
		$args = wp_parse_args(
			$args,
			[
				'url'     => false,
				'vine_id' => false,
			]
		);

		if ( empty( $args['vine_id'] ) ) {
			return AMP_HTML_Utils::build_tag(
				'a',
				[
					'href'  => esc_url_raw( $args['url'] ),
					'class' => 'amp-wp-embed-fallback',
				],
				esc_html( $args['url'] )
			);
		}

		$this->did_convert_elements = true;

		return AMP_HTML_Utils::build_tag(
			'amp-vine',
			[
				'data-vineid' => $args['vine_id'],
				'layout'      => 'responsive',
				'width'       => $this->args['width'],
				'height'      => $this->args['height'],
			]
		);
	}
}
