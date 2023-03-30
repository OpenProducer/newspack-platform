<?php
/**
 * AMP_Polyfills.
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Manages settings for AMP_Polyfills.
 */
class AMP_Polyfills {
	/**
	 * Add hooks.
	 */
	public static function init() {
		add_filter( 'the_content', [ __CLASS__, 'amp_tags' ], 1, 1 );
	}

	/**
	 * Insert HTML into a DOMDocument.
	 *
	 * @param \DOMDocument $dom DOMDocument.
	 * @param string       $html HTML.
	 * @param \DOMElement  $tag Tag.
	 */
	private static function insert_html( $dom, $html, $tag ) {
		$replacement = $dom->createElement( 'div' );
		$fragment    = $dom->createDocumentFragment();
		$fragment->appendXML( $html );
		foreach ( $fragment->childNodes as $node ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$imported_node = $dom->importNode( $node, true );
			$replacement->appendChild( $imported_node );
		}
		$tag->parentNode->replaceChild( $replacement, $tag ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}

	/**
	 * Polyfill AMP tags.
	 *
	 * @param string $content Content.
	 */
	public static function amp_tags( $content ) {
		// If the AMP plugin is active, don't do anything.
		if ( function_exists( 'amp_is_canonical' ) ) {
			return $content;
		}

		// Polyfill amp-img.
		$content = preg_replace(
			'/<amp-img([^>]*)>[^<]*<\/amp-img>/',
			'<img\1>', // img is a void element.
			$content
		);
		// Polyfill amp-iframe.
		$content = preg_replace(
			'/<amp-iframe([^>]*)>[^<]*<\/amp-iframe>/',
			'<iframe\1></iframe>',
			$content
		);

		$has_amp_fit_text = false !== stripos( $content, '<amp-fit-text' );
		$has_amp_youtube  = false !== stripos( $content, '<amp-youtube' );

		if ( $has_amp_fit_text || $has_amp_youtube ) {
			$dom = new \DomDocument();
			libxml_use_internal_errors( true );
			$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', get_bloginfo( 'charset' ) ) );
			$xpath = new \DOMXpath( $dom );

			// Process amp-fit-text tags.
			if ( $has_amp_fit_text ) {
				foreach ( $xpath->query( '//amp-fit-text' ) as $tag ) {
					$outer_html = $dom->saveHTML( $tag );
					// AMP plugin used to (https://github.com/ampproject/amp-wp/pull/5729) have a feature to wrap
					// headings in amp-fit-text, but it was removed. The enclosing amp-fit-text tag will be stripped,
					// leaving the original heading tag.
					$html = preg_replace( '/<amp-fit-text[^>]*>(.*)<\/amp-fit-text>/', '\1', $outer_html );
					self::insert_html( $dom, $html, $tag );
					$content = $dom->saveHTML();
				}
			}

			// Process amp-youtube tags.
			if ( $has_amp_youtube ) {
				foreach ( $xpath->query( '//amp-youtube' ) as $tag ) {
					$yt_id = false;
					foreach ( $tag->attributes as $attribute ) {
						if ( 'data-videoid' === $attribute->name ) {
							$yt_id = $attribute->value;
						}
					}
					if ( $yt_id ) {
						// Return a YouTube embed block.
						$video_url = 'https://www.youtube.com/watch?v=' . $yt_id;
						$html      = '<div><!-- wp:embed {"url":"' . $video_url . '","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
                            <figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
                            ' . $video_url . '
                            </div></figure>
                        <!-- /wp:embed --></div>';
						self::insert_html( $dom, $html, $tag );
						$body    = $dom->getElementsByTagName( 'body' )->item( 0 );
						$content = preg_replace( '/<\/?body>/', '', $dom->saveHTML( $body ) );
					}
				}
			}
		}

		return $content;
	}
}
AMP_Polyfills::init();
