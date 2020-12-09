<?php
/**
 * Allow Google Ad Manager scripts in AMP pages
 *
 * @see https://github.com/Automattic/amp-wp
 * @package Newspack
 */

/**
 * Sanitizer class to allow Google Ad Manager scripts in AMP pages.
 */
class AMP_Sanitizer_GAM extends AMP_Base_Sanitizer {
	/**
	 * Sanitize document.
	 *
	 * @since 1.3
	 */
	public function sanitize() {
		$xpath = new DOMXPath( $this->dom );
		$found = false;
		foreach ( $xpath->query( '//script[ @data-amp-plus-gam ]' ) as $node ) {
			if ( $node instanceof DOMElement ) {
				$node->setAttribute( AMP_Rule_Spec::DEV_MODE_ATTRIBUTE, '' );
				$found = true;
			}
		}
		if ( $found ) {
			$this->dom->documentElement->setAttribute( AMP_Rule_Spec::DEV_MODE_ATTRIBUTE, '' );
		}
	}
}
