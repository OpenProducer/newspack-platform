<?php
/**
 * InDesign Converter - Converts WordPress posts to Adobe InDesign Tagged Text format.
 *
 * @package Newspack
 */

namespace Newspack\Optional_Modules\InDesign_Export;

defined( 'ABSPATH' ) || exit;

/**
 * Converts WordPress posts to Adobe InDesign Tagged Text format.
 */
class InDesign_Converter {

	/**
	 * Default InDesign styles configuration.
	 *
	 * @var array
	 */
	private static $default_styles = [
		'headline'          => '<pstyle:24head>',
		'initial_paragraph' => '<pstyle:dropcap>',
		'paragraph'         => '<pstyle:text>',
		'subhead'           => '<pstyle:12sub>',
		'byline'            => '<pstyle:byline>By ',
		'pullquote'         => '<pstyle:pullquote>',
		'pullquote_name'    => '<pstyle:pullquotename>',
	];

	/**
	 * InDesign styles configuration.
	 *
	 * @var array
	 */
	private $styles;

	/**
	 * Constructor.
	 *
	 * @param array $styles Optional. Custom InDesign styles configuration.
	 */
	public function __construct( $styles = [] ) {
		$this->styles = wp_parse_args( $styles, self::$default_styles );
	}

	/**
	 * Convert a WordPress post to InDesign Tagged Text format.
	 *
	 * @param int|\WP_Post $post Post ID or WP_Post object.
	 * @param array        $options Optional conversion options.
	 * @return string|false InDesign Tagged Text content, or false on failure.
	 */
	public function convert_post( $post, $options = [] ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return false;
		}

		$default_options = [
			'include_subtitle' => true,
			'include_byline'   => true,
		];
		$options = wp_parse_args( $options, $default_options );

		$content_parts = [];

		$content_parts[] = '<ASCII-WIN>';
		$content_parts[] = $this->styles['headline'] . $this->get_transformed_text( $post->post_title );

		if ( $options['include_subtitle'] ) {
			$subtitle = $this->get_post_subtitle( $post );
			if ( $subtitle ) {
				$content_parts[] = $this->styles['subhead'] . $this->get_transformed_text( $subtitle );
			}
		}

		if ( $options['include_byline'] ) {
			$byline = $this->get_byline( $post );
			if ( ! empty( $byline ) ) {
				$content_parts[] = $this->styles['byline'] . $this->get_transformed_text( $byline );
			}
		}

		$content_parts[] = $this->process_post_content( $post->post_content, $options );
		$content_parts[] = $this->process_post_images( $post );

		return implode( "\r\n", array_filter( $content_parts ) );
	}

	/**
	 * Get the post subtitle.
	 *
	 * @param \WP_Post $post Post object.
	 * @return string|null Post subtitle or null if not available.
	 */
	private function get_post_subtitle( $post ) {
		$subtitle = get_post_meta( $post->ID, 'newspack_post_subtitle', true );
		return $subtitle ?? null;
	}

	/**
	 * Get the post authors.
	 *
	 * @param \WP_Post $post Post object.
	 * @return array Array of author objects.
	 */
	private function get_post_authors( $post ) {
		if ( function_exists( 'get_coauthors' ) ) {
			return get_coauthors( $post->ID );
		}

		$author = get_userdata( $post->post_author );
		return $author ? [ $author ] : [];
	}

	/**
	 * Format byline.
	 *
	 * @param \WP_Post $post Post object.
	 * @return string Formatted byline.
	 */
	private function get_byline( $post ) {
		$authors = $this->get_post_authors( $post );

		if ( empty( $authors ) ) {
			return '';
		}

		$author_names = [];
		foreach ( $authors as $author ) {
			$author_names[] = $author->display_name;
		}

		if ( 1 === count( $author_names ) ) {
			return $author_names[0];
		} else {
			$last_author = array_pop( $author_names );
			return implode( ', ', $author_names ) . ' & ' . $last_author;
		}
	}

	/**
	 * Process post content for InDesign export.
	 *
	 * @param string $content Raw post content.
	 * @param array  $options Conversion options.
	 * @return string Processed content.
	 */
	private function process_post_content( $content, $options = [] ) {
		if ( has_blocks( $content ) ) {
			$content = $this->process_blocks( $content );
		}
		$content = $this->process_html_headings( $content );
		$content = $this->process_quotes( $content );
		$content = $this->convert_html_to_indesign( $content );
		$content = preg_replace( '/<!--.*?-->/s', '', $content );
		$content = $this->get_transformed_text( $content );
		$content = $this->clean_whitespace( $content );

		return $content;
	}

	/**
	 * Process blocks in the content.
	 *
	 * @param string $content Post content.
	 *
	 * @return string Content with processed blocks.
	 */
	private function process_blocks( $content ) {
		$blocks = parse_blocks( $content );
		$content = '';
		foreach ( $blocks as $block ) {
			$tag = $this->get_block_tag( $block );
			if ( ! empty( $tag ) ) {
				$content .= $tag . $this->get_transformed_text( preg_replace( '/^<[^>]+>(.*)<\/[^>]+>$/s', '$1', trim( $block['innerHTML'] ) ) );
			} else {
				$content .= serialize_block( $block );
			}
		}
		return $content;
	}

	/**
	 * Get the tag for a block.
	 *
	 * @param array $block Block data.
	 *
	 * @return string Block tag.
	 */
	private function get_block_tag( $block ) {
		if ( ! empty( $block['attrs']['indesignTag'] ) ) {
			return sprintf( '<%1$s>', $block['attrs']['indesignTag'] );
		}

		if ( 'core/paragraph' === $block['blockName'] ) {
			return $this->styles['paragraph'];
		}

		if ( 'core/heading' === $block['blockName'] ) {
			if ( 4 === $block['attrs']['level'] ) {
				return $this->styles['subhead'];
			}
			return $this->styles['paragraph'];
		}
		return '';
	}

	/**
	 * Process headings in the content.
	 *
	 * @param string $content Post content.
	 *
	 * @return string Content with processed subheads.
	 */
	private function process_html_headings( $content ) {
		$content = preg_replace_callback(
			'/<h([2-6])[^>]*>(.*?)<\/h[2-6]>/is',
			function ( $matches ) {
				switch ( $matches[1] ) {
					/**
					 * Process subheadings (h4 elements) in the content.
					 */
					case '4':
						return $this->styles['subhead'] . $this->get_transformed_text( $matches[2] );
					/**
					 * TODO: Handle other heading levels as per requirements.
					 * For now, treating them as regular paragraphs.
					 */
					case '2':
					case '3':
					case '5':
					case '6':
					default:
						return $this->styles['paragraph'] . $this->get_transformed_text( $matches[2] );
				}
			},
			$content
		);

		return $content;
	}

	/**
	 * Process blockquotes and pullquotes.
	 *
	 * @param string $content Post content.
	 *
	 * @return string Content with processed blockquotes and pullquotes.
	 */
	private function process_quotes( $content ) {
		$pattern      = '/<blockquote[^>]*>(.*?)<\/blockquote>/is';
		$cite_pattern = '/<cite[^>]*>(.*?)<\/cite>/is';

		preg_match_all( $pattern, $content, $matches );
		$blockquotes = $matches[1];

		foreach ( $blockquotes as $blockquote ) {
			$quote = $this->styles['pullquote'] . wp_strip_all_tags( preg_replace( $cite_pattern, '', $blockquote ) );

			preg_match( $cite_pattern, $blockquote, $matches );
			if ( ! empty( $matches ) ) {
				$cite = $matches[1];
				if ( ! empty( $cite ) ) {
					$quote .= "\r\n" . $this->styles['pullquote_name'] . wp_strip_all_tags( $cite );
				}
			}

			$content = preg_replace( $pattern, $quote, $content, 1 );
		}
		return $content;
	}

	/**
	 * Convert HTML elements to InDesign tagged text equivalents.
	 *
	 * @param string $content Post content.
	 *
	 * @return string Content with InDesign tags.
	 */
	private function convert_html_to_indesign( $content ) {
		$conversions = [
			// Remove figcaption entirely. TODO: Move them to the bottom of the export file.
			'/<figcaption[^>]*>.*?<\/figcaption>/' => '',

			// Paragraphs.
			'/<(?!pstyle:)(p[^>]*)>/'              => $this->styles['paragraph'],

			// Lists. TODO: Handle numbered and nested lists.
			'/<li[^>]*>/'                          => '<bnListType:Bullet>',

			// Line breaks.
			'/<br[^>]*>/'                          => '<0x000A>',

			// Typography.
			'/<strong[^>]*>/'                      => '<cTypeface:Bold>',
			'/<\/strong>/'                         => '<cTypeface:>',
			'/<em[^>]*>/'                          => '<cTypeface:Italic>',
			'/<\/em>/'                             => '<cTypeface:>',
			'/<(?!img)i[^>]*>/'                    => '<cTypeface:Italic>',
			'/<\/i>/'                              => '<cTypeface:>',
			'/<sup[^>]*>/'                         => '<cPosition:Superscript>',
			'/<\/sup>/'                            => '<cPosition:>',
			'/<sub[^>]*>/'                         => '<cPosition:Subscript>',
			'/<\/sub>/'                            => '<cPosition:>',

			// Remove unsupported tags while preserving content.
			'/<(?:div|ol|ul|a|img|figure)[^>]*>/'  => '',

			// Replace paragraphs and lists end tags with line breaks.
			'/<\/(?:p|li|ul|ol)[^>]*>/'            => "\r\n",

			// Remove all remaining closing tags.
			'/<\/[^>]*>/'                          => '',
		];

		foreach ( $conversions as $pattern => $replacement ) {
			$content = preg_replace(
				$pattern,
				$replacement,
				$content
			);
		}

		return $content;
	}

	/**
	 * Convert text for InDesign, handling special characters and typography.
	 *
	 * @param string $text Text to convert.
	 *
	 * @return string Converted text.
	 */
	private function get_transformed_text( $text ) {
		// Character conversions for InDesign Tagged Text.
		$conversions = [
			// Dashes.
			'--' => '<0x2014>',
			'—'  => '<0x2014>',
			'–'  => '<0x2014>',

			// Quotes.
			'“'  => '"',
			'”'  => '"',
			'‘'  => "'",
			'’'  => "'",

			// Special characters.
			'•'  => '<CharStyle:bullet>n<CharStyle:>',
		];

		$text = str_replace( array_keys( $conversions ), array_values( $conversions ), $text );
		$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		// Convert remaining HTML entities.
		$text = str_replace(
			[ '&nbsp;', '&amp;', '&lt;', '&gt;' ],
			[ ' ', '&', '<', '>' ],
			$text
		);

		// Remove non-breaking space UTF-8 character.
		$text = str_replace( "\xC2\xA0", ' ', $text );

		// Convert remaining special characters to hexadecimal unicode code points.
		$char_length = mb_strlen( $text, 'UTF-8' );
		for ( $i = 0; $i < $char_length; $i++ ) {
			$char       = mb_substr( $text, $i, 1, 'UTF-8' );
			$code_point = mb_ord( $char, 'UTF-8' );
			if ( $code_point > 127 ) {
				$text        = str_replace( $char, sprintf( '<0x%04X>', $code_point ), $text );
				$char_length = mb_strlen( $text, 'UTF-8' );
			}
		}

		return $text;
	}

	/**
	 * Clean up whitespace and line breaks.
	 *
	 * @param string $content Content to clean.
	 *
	 * @return string Cleaned content.
	 */
	private function clean_whitespace( $content ) {
		$content = preg_replace( '/\n{2,}/', "\r\n", $content );
		$content = trim( $content );

		return $content;
	}

	/**
	 * Recursively get all the image blocks.
	 *
	 * @param array $blocks Blocks to process.
	 *
	 * @return array Image blocks.
	 */
	private function get_image_blocks( $blocks ) {
		$block_names  = [ 'core/image', 'jetpack/slideshow', 'jetpack/tiled-gallery' ];
		$image_blocks = [];
		foreach ( $blocks as $block ) {
			if ( in_array( $block['blockName'], $block_names, true ) ) {
				$image_blocks[] = $block;
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$image_blocks = array_merge( $image_blocks, $this->get_image_blocks( $block['innerBlocks'] ) );
			}
		}
		return $image_blocks;
	}

	/**
	 * Process post images metadata to generate photo credit and caption tags.
	 *
	 * @param \WP_Post $post Post object.
	 *
	 * @return string Photo credit and caption tags.
	 */
	private function process_post_images( $post ) {
		$images = [];

		$featured_image_id = get_post_thumbnail_id( $post->ID );
		if ( $featured_image_id ) {
			if ( ! isset( $images[ $featured_image_id ] ) ) {
				$images[ $featured_image_id ] = true;
			}
		}

		// Avoid processing images from Newspack Network Content Distribution.
		if ( ! get_post_meta( $post->ID, 'newspack_network_post_id', true ) ) {
			$blocks       = parse_blocks( $post->post_content );
			$image_blocks = $this->get_image_blocks( $blocks );

			foreach ( $image_blocks as $block ) {
				$id = $block['attrs']['id'] ?? null;
				if ( ! empty( $id ) && ! isset( $images[ $id ] ) ) {
					$images[ $id ] = true;
				}
				if ( ! empty( $block['attrs']['ids'] ) && is_array( $block['attrs']['ids'] ) ) {
					foreach ( $block['attrs']['ids'] as $id ) {
						if ( ! isset( $images[ $id ] ) ) {
							$images[ $id ] = true;
						}
					}
				}
			}
		}

		if ( empty( array_filter( $images ) ) ) {
			return '';
		}

		$tag_content = "\r\n";

		foreach ( $images as $image_id => $insert_tag ) {
			if ( ! $insert_tag ) {
				continue;
			}

			$caption = wp_get_attachment_caption( $image_id );
			$credit  = get_post_meta( $image_id, '_media_credit', true ) ?? '';

			if ( ! $caption && ! $credit ) {
				continue;
			}

			$tag_content .= "\r\n";
			if ( $caption ) {
				$tag_content .= '<pstyle:PhotoCaption>' . $caption . "\r\n";
			}
			if ( $credit ) {
				$tag_content .= '<pstyle:PhotoCredit>' . $credit . "\r\n";
			}
		}

		return $tag_content;
	}

	/**
	 * Update the InDesign styles configuration.
	 *
	 * @param array $styles New styles configuration.
	 */
	public function set_styles( $styles ) {
		$this->styles = wp_parse_args( $styles, self::$default_styles );
	}

	/**
	 * Get the current InDesign styles configuration.
	 *
	 * @return array Current styles configuration.
	 */
	public function get_styles() {
		return $this->styles;
	}

	/**
	 * Get the default InDesign styles configuration.
	 *
	 * @return array Default styles configuration.
	 */
	public static function get_default_styles() {
		return self::$default_styles;
	}
}
