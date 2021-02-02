<?php
/**
 * Publish to Apple News: \Apple_Exporter\Components\Audio class
 *
 * @package Apple_News
 * @subpackage Apple_Exporter\Components
 */

namespace Apple_Exporter\Components;

use \Apple_Exporter\Exporter_Content;

/**
 * An HTML audio tag.
 *
 * @since 0.2.0
 */
class Audio extends Component {

	/**
	 * Look for node matches for this component.
	 *
	 * @param \DOMElement $node The node to examine for matches.
	 * @access public
	 * @return \DOMElement|null The node on success, or null on no match.
	 */
	public static function node_matches( $node ) {
		// Is this an audio node?

		if (
			// Is this a gutenberg audio block?
			( self::node_has_class( $node, 'wp-block-audio' )
				&& $node->hasChildNodes()
				&& 'audio' === $node->firstChild->nodeName
			)
			// Or is this a stand-alone audio tag?
			|| 'audio' === $node->nodeName
		) {
			return $node;
		}

		return null;
	}

	/**
	 * Register all specs for the component.
	 *
	 * @access public
	 */
	public function register_specs() {
		$this->register_spec(
			'json-with-caption-text',
			__( 'JSON With Caption Text', 'apple-news' ),
			array(
				'role'       => 'container',
				'components' => array(
					array(
						'role' => 'audio',
						'URL'  => '#url#',
					),
					array(
						'role'   => 'caption',
						'text'   => '#caption_text#',
						'format' => 'html',
					),
				),
			)
		);

		$this->register_spec(
			'json',
			__( 'JSON', 'apple-news' ),
			array(
				'role' => 'audio',
				'URL'  => '#url#',
			)
		);
	}

	/**
	 * Build the component.
	 *
	 * @param string $html The HTML to parse into text for processing.
	 * @access protected
	 */
	protected function build( $html ) {
		// Remove initial and trailing tags: <video><p>...</p></video>.
		if ( ! preg_match( '/src="([^"]+)"/', $html, $match ) ) {
			return;
		}

		// Ensure the URL is valid.
		$url = Exporter_Content::format_src_url( $match[1] );
		if ( empty( $url ) ) {
			return;
		}

		$audio_spec    = 'json';
		$audio_caption = '';
		if ( preg_match( '/<figcaption>(.+?)<\/figcaption>/', $html, $caption_match ) ) {
			$audio_caption = $caption_match[1];
			$audio_spec    = 'json-with-caption-text';
		}
		$values = array(
			'#url#'          => esc_url_raw( $url ),
			'#caption_text#' => $audio_caption,
		);

		$this->register_json( $audio_spec, $values );
	}

}
