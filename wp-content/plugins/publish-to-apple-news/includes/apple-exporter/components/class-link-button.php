<?php
/**
 * Publish to Apple News: \Apple_Exporter\Components\Link class
 *
 * @package Apple_News
 * @subpackage Apple_Exporter\Components
 */

namespace Apple_Exporter\Components;

/**
 * A Link/Anchor component.
 *
 * @since 2.0.0
 */
class Link_Button extends Component {

	/**
	 * Look for node matches for this component.
	 *
	 * @param \DOMElement $node The node to examine for matches.
	 * @access public
	 * @return \DOMElement|null The node on success, or null on no match.
	 */
	public static function node_matches( $node ) {
		return 'a' === $node->nodeName
			&& self::node_has_class( $node, 'wp-block-button__link' )
			&& ! empty( $node->getAttribute( 'href' ) )
			&& ! empty( $node->nodeValue )
				? $node
				: null;
	}

	/**
	 * Register all specs for the component.
	 *
	 * @access public
	 */
	public function register_specs() {
		$this->register_spec(
			'json',
			__( 'JSON', 'apple-news' ),
			array(
				'role'      => 'link_button',
				'text'      => '#text#',
				'URL'       => '#url#',
				'style'     => 'default-link-button',
				'layout'    => 'link-button-layout',
				'textStyle' => 'default-link-button-text-style',
			)
		);

		// Register the JSON for the link button layout.
		$this->register_spec(
			'link-button-layout',
			__( 'Button Layout', 'apple-news' ),
			array(
				'margin'  => array(
					'bottom' => 20,
				),
				'padding' => array(
					'top'    => 10,
					'bottom' => 10,
					'left'   => 15,
					'right'  => 15,
				),
			)
		);

		// Register the JSON for the link button style.
		$this->register_spec(
			'default-link-button',
			__( 'Link Button Style', 'apple-news' ),
			array(
				'backgroundColor' => '#DDD',
				'mask'            => array(
					'type'   => 'corners',
					'radius' => 25,
				),
			)
		);

		// Register the JSON for the link button text style.
		$this->register_spec(
			'default-link-button-text-style',
			__( 'Link Button Text Style', 'apple-news' ),
			array(
				'textColor' => '#000',
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

		// Extract the button href and text to register the JSON.
		if ( preg_match( '/<a.+?href="([^"]+)".*?>([^<]+)<\/a>/', $html, $link_button_match ) ) {
			// Negotiate the URL.
			$url = $link_button_match[1];
			if ( 0 === strpos( $url, '/' ) ) {
				$url = home_url( $url );
			} elseif ( 0 === strpos( $url, '#' ) ) {
				$url = trailingslashit( get_the_permalink() ) . $url;
			}

			// Register JSON for this component.
			$this->register_json(
				'json',
				array(
					'#url#'  => $url,
					'#text#' => $link_button_match[2],
				)
			);
		} else {
			// If, for some reason, the match failed, bail out.
			return;
		}

		// Register the layout for the link button.
		$this->register_layout( 'link-button-layout', 'link-button-layout' );

		// Register the style for the link button.
		$this->register_component_style(
			'default-link-button',
			'default-link-button'
		);

		// Register the style for the link button text.
		$this->register_style(
			'default-link-button-text-style',
			'default-link-button-text-style'
		);
	}
}

