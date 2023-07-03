<?php
/**
 * Adds large featured image to the RSS feed.
 *
 * @package Newspack
 */

namespace Newspack;

/**
 * RSS_Add_Image class.
 */
class RSS_Add_Image {
	/**
	 * Hook actions and filters.
	 */
	public static function init() {
		add_filter( 'the_excerpt_rss', [ __CLASS__, 'thumbnails_in_rss' ] );
		add_filter( 'the_content_feed', [ __CLASS__, 'thumbnails_in_rss' ] );
	}

	/**
	 * Display Featured Images in RSS feed.
	 *
	 * @param string $content Thumbail to add to RSS feed.
	 */
	public static function thumbnails_in_rss( $content ) {
		global $post;
		if ( has_post_thumbnail( $post->ID ) ) {
			$content = '<figure>' . get_the_post_thumbnail( $post->ID, 'large' ) . '</figure>' . $content;
		}
		return $content;
	}
}
RSS_Add_Image::init();
