<?php
/**
 * Newspack Newsletter Renderer
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Newsletters Renderer Class.
 */
final class Newspack_Newsletters_Renderer {
	/**
	 * The color palette to be used.
	 *
	 * @var Object
	 */
	public static $color_palette = null;

	/**
	 * Newsletter ID being rendered.
	 *
	 * @var int
	 */
	public static $newsletter_id = null;

	/**
	 * The header font.
	 *
	 * @var String
	 */
	protected static $font_header = null;

	/**
	 * The body font.
	 *
	 * @var String
	 */
	protected static $font_body = null;

	/**
	 * Cache of already processed links (avoid recursive processing).
	 *
	 * @var boolean[] Map of link URLs to whether they were processed.
	 */
	protected static $processed_links = [];

	/**
	 * The post permalink, if the post is public.
	 *
	 * @var String
	 */
	protected static $post_permalink = null;

	/**
	 * Inline tags that are allowed to be rendered in a text block.
	 *
	 * @var bool[]|array[] Associative array of tag names to allowed attributes.
	 */
	public static $allowed_inline_tags = [
		's'      => true,
		'b'      => true,
		'strong' => true,
		'i'      => true,
		'em'     => true,
		'span'   => true,
		'u'      => true,
		'small'  => true,
		'sub'    => true,
		'sup'    => true,
		'a'      => [
			'href'   => true,
			'target' => true,
			'rel'    => true,
		],
	];

	/**
	 * Convert a list to HTML attributes.
	 *
	 * @param array $attributes Array of attributes.
	 * @return string HTML attributes as a string.
	 */
	private static function array_to_attributes( $attributes ) {
		$attributes = apply_filters( 'newspack_newsletters_mjml_component_attributes', $attributes );
		return join(
			' ',
			array_map(
				function( $key ) use ( $attributes ) {
					if (
						isset( $attributes[ $key ] ) &&
						( is_string( $attributes[ $key ] ) || is_numeric( $attributes[ $key ] ) ) // Don't convert values that can't be expressed as a string.
					) {
						return $key . '="' . esc_attr( $attributes[ $key ] ) . '"';
					} else {
						return '';
					}
				},
				array_keys( $attributes )
			)
		);
	}

	/**
	 * Get a value for an image alt attribute.
	 *
	 * @param int $attachment_id Attachment ID of the image.
	 *
	 * @return string A value for the alt attribute.
	 */
	private static function get_image_alt( $attachment_id ) {
		$alt        = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		$attachment = get_post( $attachment_id );

		if ( empty( $alt ) ) {
			$alt = $attachment->post_content;
		}
		if ( empty( $alt ) ) {
			$alt = $attachment->post_excerpt;
		}
		if ( empty( $alt ) ) {
			$alt = $attachment->post_title;
		}

		return $alt;
	}

	/**
	 * Get font size based on block attributes.
	 *
	 * @param array $block_attrs Block attributes.
	 * @return string font size.
	 */
	private static function get_font_size( $block_attrs ) {
		if ( isset( $block_attrs['customFontSize'] ) ) {
			return $block_attrs['customFontSize'] . 'px';
		}
		if ( isset( $block_attrs['fontSize'] ) ) {
			$sizes = array(
				'small'   => '12px',
				'normal'  => '16px',
				'medium'  => '16px',
				'large'   => '24px',
				'huge'    => '36px',
				'x-large' => '36px',
			);
			return $sizes[ $block_attrs['fontSize'] ];
		}
	}

	/**
	 * Get the social icon and color based on the block attributes.
	 *
	 * @param string $service_name The service name.
	 * @param array  $block_attrs  Block attributes.
	 *
	 * @return array[
	 *   'icon'  => string,
	 *   'color' => string,
	 * ] The icon and color or empty array if service not found.
	 */
	private static function get_social_icon( $service_name, $block_attrs ) {
		$services_colors = [
			'facebook'  => '#1977f2',
			'instagram' => '#f00075',
			'linkedin'  => '#0577b5',
			'tiktok'    => '#000000',
			'tumblr'    => '#011835',
			'twitter'   => '#21a1f3',
			'wordpress' => '#3499cd',
			'youtube'   => '#ff0100',
		];
		if ( ! isset( $services_colors[ $service_name ] ) ) {
			return [];
		}
		$icon  = 'white';
		$color = $services_colors[ $service_name ];
		if ( isset( $block_attrs['className'] ) ) {
			if ( 'is-style-filled-black' === $block_attrs['className'] || 'is-style-circle-white' === $block_attrs['className'] ) {
				$icon = 'black';
			}
			if ( 'is-style-filled-black' === $block_attrs['className'] || 'is-style-filled-white' === $block_attrs['className'] ) {
				$color = 'transparent';
			} elseif ( 'is-style-circle-black' === $block_attrs['className'] ) {
				$color = '#000';
			} elseif ( 'is-style-circle-white' === $block_attrs['className'] ) {
				$color = '#fff';
			}
		}
		return [
			'icon'  => sprintf( '%s-%s.png', $icon, $service_name ),
			'color' => $color,
		];
	}

	/**
	 * Get colors based on block attributes.
	 *
	 * @param array $block_attrs Block attributes.
	 * @return array Array of color attributes for MJML component.
	 */
	private static function get_colors( $block_attrs ) {
		$colors = array();

		// For text.
		if ( isset( $block_attrs['textColor'], self::$color_palette[ $block_attrs['textColor'] ] ) ) {
			$colors['color'] = self::$color_palette[ $block_attrs['textColor'] ];
		}
		// customTextColor is set inline, but it's passed here for consistency.
		if ( isset( $block_attrs['customTextColor'] ) ) {
			$colors['color'] = $block_attrs['customTextColor'];
		}
		if ( isset( $block_attrs['backgroundColor'], self::$color_palette[ $block_attrs['backgroundColor'] ] ) ) {
			$colors['background-color'] = self::$color_palette[ $block_attrs['backgroundColor'] ];
		}
		// customBackgroundColor is set inline, but not on mjml wrapper element.
		if ( isset( $block_attrs['customBackgroundColor'] ) ) {
			$colors['background-color'] = $block_attrs['customBackgroundColor'];
		}

		// For separators.
		if ( isset( $block_attrs['color'], self::$color_palette[ $block_attrs['color'] ] ) ) {
			$colors['border-color'] = self::$color_palette[ $block_attrs['color'] ];
		}
		if ( isset( $block_attrs['customColor'] ) ) {
			$colors['border-color'] = $block_attrs['customColor'];
		}

		// Custom color handling.
		if ( isset( $block_attrs['style'] ) ) {
			if ( isset( $block_attrs['style']['color']['background'] ) ) {
				$colors['background-color'] = $block_attrs['style']['color']['background'];
			}
			if ( isset( $block_attrs['style']['color']['text'] ) ) {
				$colors['color'] = $block_attrs['style']['color']['text'];
			}
		}

		// Add !important to all colors.
		if ( isset( $colors['color'] ) ) {
			$colors['color'] .= ' !important';
		}
		if ( isset( $colors['background-color'] ) ) {
			$colors['background-color'] .= ' !important';
		}

		return $colors;
	}

	/**
	 * Get spacing value.
	 *
	 * @param string $value Spacing value.
	 *
	 * @return string Spacing value.
	 */
	private static function get_spacing_value( $value ) {
		$presets = [
			'50' => 'clamp( 1.25rem, 1rem + 0.8333vw, 1.5rem )',
			'60' => 'clamp( 1.5rem, 0.75rem + 2.5vw, 2.25rem )',
			'70' => 'clamp( 1.75rem, 0.12rem + 5.4333vw, 3.38rem )',
			'80' => 'clamp( 2rem, -1.06rem + 10.2vw, 5.06rem )',
		];
		if ( 0 === strpos( $value, 'var' ) ) {
			$preset_key = explode( '|', $value );
			$preset     = end( $preset_key );
			if ( isset( $presets[ $preset ] ) ) {
				return $presets[ $preset ];
			}
			return '';
		}
		return $value;
	}

	/**
	 * Add color attributes and a padding, if component has a background color.
	 *
	 * @param array $attrs Block attributes.
	 * @return array MJML component attributes.
	 */
	private static function process_attributes( $attrs ) {
		$attrs     = array_merge(
			$attrs,
			self::get_colors( $attrs )
		);
		$font_size = self::get_font_size( $attrs );
		if ( isset( $font_size ) ) {
			$attrs['font-size'] = $font_size;
		}

		if ( isset( $attrs['style']['spacing']['padding'] ) ) {
			$padding = $attrs['style']['spacing']['padding'];
			foreach ( $padding as $key => $value ) {
				$padding[ $key ] = self::get_spacing_value( $value, $key );
			}
			$attrs['padding'] = sprintf( '%s %s %s %s', $padding['top'], $padding['right'], $padding['bottom'], $padding['left'] );
		}

		if ( ! empty( $attrs['borderRadius'] ) ) {
			$attrs['borderRadius'] = $attrs['borderRadius'] . 'px';
		}
		if ( isset( $attrs['style']['border']['radius'] ) ) {
			$attrs['borderRadius'] = $attrs['style']['border']['radius'];
		}

		// Remove block-only attributes.
		array_map(
			function ( $key ) use ( &$attrs ) {
				if ( isset( $attrs[ $key ] ) ) {
					unset( $attrs[ $key ] );
				}
			},
			[ 'customBackgroundColor', 'customTextColor', 'customFontSize', 'fontSize', 'backgroundColor', 'style' ]
		);

		if ( ! isset( $attrs['padding'] ) && isset( $attrs['background-color'] ) ) {
			$attrs['padding'] = '0';
		}

		if ( isset( $attrs['textAlign'] ) && ! isset( $attrs['align'] ) ) {
			$attrs['align'] = $attrs['textAlign'];
			unset( $attrs['textAlign'] );
		}

		if ( isset( $attrs['align'] ) && 'full' == $attrs['align'] ) {
			$attrs['full-width'] = 'full-width';
			unset( $attrs['align'] );
		}

		if ( ! isset( $attrs['padding'] ) && isset( $attrs['full-width'] ) && 'full-width' == $attrs['full-width'] && isset( $attrs['background-color'] ) ) {
			$attrs['padding'] = '12px 0';
		}

		return $attrs;
	}

	/**
	 * Append UTM param to links.
	 *
	 * @param string   $html Input HTML.
	 * @param \WP_Post $post Optional post object.
	 * @return string HTML with processed links.
	 */
	public static function process_links( $html, $post = null ) {
		preg_match_all( '/href="([^"]*)"/', $html, $matches );
		$href_params = $matches[0];
		$urls        = $matches[1];
		foreach ( $urls as $index => $url ) {
			/** Skip if link was already processed. */
			if ( ! empty( self::$processed_links[ $url ] ) ) {
				continue;
			}
			/** Link href content can be invalid (placeholder) so we must skip it. */
			if ( ! wp_http_validate_url( $url ) ) {
				continue;
			}
			$url_with_params = apply_filters(
				'newspack_newsletters_process_link',
				add_query_arg(
					[
						'utm_medium' => 'email',
					],
					$url
				),
				$url,
				$post
			);

			self::$processed_links[ $url_with_params ] = true;

			$html = str_replace( $href_params[ $index ], 'href="' . $url_with_params . '"', $html );
		}
		return $html;
	}

	/**
	 * Whether the block is empty.
	 *
	 * @param WP_Block $block The block.
	 *
	 * @return bool Whether the block is empty.
	 */
	public static function is_empty_block( $block ) {
		$blocks_without_inner_html = [
			'core/site-logo',
			'core/site-title',
			'core/site-tagline',
			'newspack-newsletters/ad',
		];

		$empty_block_name = empty( $block['blockName'] );
		$empty_html       = ! in_array( $block['blockName'], $blocks_without_inner_html, true ) && empty( $block['innerHTML'] );

		return $empty_block_name || $empty_html;
	}

	/**
	 * Convert a Gutenberg block to an MJML component.
	 * MJML component will be put in an mj-column in an mj-section for consistent layout,
	 * unless it's a group or a columns block.
	 *
	 * @param WP_Block $block The block.
	 * @param bool     $is_in_column Whether the component is a child of a column component.
	 * @param bool     $is_in_group Whether the component is a child of a group component.
	 * @param array    $default_attrs Default attributes for the component.
	 * @param bool     $is_in_list_or_quote Whether the component is a child of a list or quote block.
	 * @return string MJML component.
	 */
	public static function render_mjml_component( $block, $is_in_column = false, $is_in_group = false, $default_attrs = [], $is_in_list_or_quote = false ) {
		/**
		 * Filter to short-circuit the markup generation for a block.
		 *
		 * @param string|null $markup The markup to return. If null, the default markup will be generated.
		 * @param WP_Block    $block The block.
		 * @param bool        $is_in_column Whether the component is a child of a column component.
		 * @param bool        $is_in_group Whether the component is a child of a group component.
		 * @param array       $default_attrs Default attributes for the component.
		 * @param bool        $is_in_list_or_quote Whether the component is a child of a list or quote block.
		 * @param int         $newsletter_id The newsletter post ID.
		 *
		 * @return string|null The markup to return. If null, the default markup will be generated.
		 */
		$markup = apply_filters( 'newspack_newsletters_render_mjml_component', null, $block, $is_in_column, $is_in_group, $default_attrs, $is_in_list_or_quote, self::$newsletter_id );
		if ( null !== $markup ) {
			return $markup;
		}

		$block_name    = $block['blockName'];
		$attrs         = $block['attrs'];
		$inner_blocks  = $block['innerBlocks'];
		$inner_html    = $block['innerHTML'];
		$inner_content = isset( $block['innerContent'] ) ? $block['innerContent'] : [ $inner_html ];

		if ( ! isset( $attrs['innerBlocksToInsert'] ) && self::is_empty_block( $block ) ) {
			return '';
		}

		// Verify if block is configured to be web-only.
		if ( isset( $attrs['newsletterVisibility'] ) && 'web' === $attrs['newsletterVisibility'] ) {
			return '';
		}

		$block_mjml_markup = '';
		$attrs             = self::process_attributes( array_merge( $default_attrs, $attrs ) );

		$conditionals = [];
		if ( ! empty( $attrs['conditionalBefore'] ) && ! empty( $attrs['conditionalAfter'] ) ) {
			$conditionals = [
				'before' => $attrs['conditionalBefore'],
				'after'  => $attrs['conditionalAfter'],
			];
		}

		// Remove attributes that are not supported by MJML.
		$unsupported_attrs = [
			'newsletterVisibility',
			'conditionalBefore',
			'conditionalAfter',
		];
		foreach ( $unsupported_attrs as $attr ) {
			if ( isset( $attrs[ $attr ] ) ) {
				unset( $attrs[ $attr ] );
			}
		}

		// Default attributes for the section which will envelop the mj-column.
		$section_attrs = array_merge(
			$attrs,
			array(
				'padding' => '0',
			)
		);

		// Default attributes for the column which will envelop the component.
		$column_attrs = array_merge(
			array(
				'padding' => '12px',
			)
		);

		$font_family = 'core/heading' === $block_name ? self::$font_header : self::$font_body;

		if ( ! empty( $inner_html ) ) {
			// Replace <mark /> with <span />.
			$inner_html = preg_replace( '/<mark\s(.+?)>(.+?)<\/mark>/is', '<span $1>$2</span>', $inner_html );
		}

		switch ( $block_name ) {
			/**
			 * Text-based blocks.
			 */
			case 'core/paragraph':
			case 'core/heading':
			case 'core/site-title':
			case 'core/site-tagline':
			case 'newspack-newsletters/share':
				$text_attrs = array_merge(
					array(
						'padding'     => '0',
						'line-height' => '1.5',
						'font-size'   => '16px',
						'font-family' => $font_family,
					),
					$attrs
				);

				if ( 'newspack-newsletters/share' === $block_name && ! self::$post_permalink ) {
					// If there's no permalink (which is not set if the post is not public), the share link has no utility.
					return '';
				}

				if ( 'core/site-tagline' === $block_name ) {
					$inner_html = get_bloginfo( 'description' );
				}

				if ( 'core/site-title' === $block_name ) {
					$inner_html = get_bloginfo( 'name' );
					$tag_name   = 'h1';
					if ( isset( $attrs['level'] ) ) {
						$tag_name = 0 === $attrs['level'] ? 'p' : 'h' . (int) $attrs['level'];
					}
					if ( ! ( isset( $attrs['isLink'] ) && ! $attrs['isLink'] ) ) {
						$link_attrs = array(
							'href="' . esc_url( get_bloginfo( 'url' ) ) . '"',
						);
						if ( isset( $attrs['linkTarget'] ) && '_blank' === $attrs['linkTarget'] ) {
							$link_attrs[] = 'target="_blank"';
						}
						$inner_html = sprintf( '<a %1$s>%2$s</a>', implode( ' ', $link_attrs ), esc_html( $inner_html ) );
					}
					$inner_html = sprintf( '<%1$s>%2$s</%1$s>', $tag_name, $inner_html );
				}

				// Only mj-text has to use container-background-color attr for background color.
				if ( isset( $text_attrs['background-color'] ) ) {
					$text_attrs['container-background-color'] = $text_attrs['background-color'];
					unset( $text_attrs['background-color'] );
				}

				// Avoid wrapping markup in `mj-text` if the block is an inner block.
				$block_mjml_markup = $is_in_list_or_quote ? $inner_html : '<mj-text ' . self::array_to_attributes( $text_attrs ) . '>' . $inner_html . '</mj-text>';
				break;

			/**
			 * Site logo block.
			 */
			case 'core/site-logo':
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
				$markup         = '';
				if ( ! empty( $image ) ) {
					$img_attrs = array(
						'padding' => '0',
						'width'   => sprintf( '%spx', isset( $attrs['width'] ) ? $attrs['width'] : '125' ),
						'align'   => isset( $attrs['align'] ) ? $attrs['align'] : 'left',
						'src'     => $image[0],
						'href'    => isset( $attrs['isLink'] ) && ! $attrs['isLink'] ? '' : esc_url( home_url( '/' ) ),
						'target'  => isset( $attrs['linkTarget'] ) && '_blank' === $attrs['linkTarget'] ? '_blank' : '',
						'alt'     => self::get_image_alt( $custom_logo_id ),
					);
					$markup   .= '<mj-image ' . self::array_to_attributes( $img_attrs ) . ' />';
				}
				$block_mjml_markup = $markup;
				break;

			/**
			 * Image block.
			 */
			case 'core/image':
				// Parse block content.
				$dom = new DomDocument();
				libxml_use_internal_errors( true );
				$dom->loadHTML( mb_convert_encoding( $inner_html, 'HTML-ENTITIES', get_bloginfo( 'charset' ) ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
				$img        = $dom->getElementsByTagName( 'img' )->item( 0 );
				$img_src    = $img->getAttribute( 'src' );
				$img_alt    = self::get_image_alt( $attrs['id'] );
				$figcaption = $dom->getElementsByTagName( 'figcaption' )->item( 0 );

				$img_attrs = array(
					'padding' => '0',
					'align'   => isset( $attrs['align'] ) ? $attrs['align'] : 'left',
					'src'     => $img_src,
					'alt'     => $img_alt,
				);

				if ( isset( $attrs['sizeSlug'] ) ) {
					if ( 'medium' == $attrs['sizeSlug'] ) {
						$img_attrs['width'] = '300px';
					}
					if ( 'thumbnail' == $attrs['sizeSlug'] ) {
						$img_attrs['width'] = '150px';
					}
				} elseif ( isset( $attrs['className'] ) ) {
					if ( 'size-medium' == $attrs['className'] ) {
						$img_attrs['width'] = '300px';
					}
					if ( 'size-thumbnail' == $attrs['className'] ) {
						$img_attrs['width'] = '150px';
					}
				}
				if ( isset( $attrs['width'] ) ) {
					$img_attrs['width'] = $attrs['width'] . 'px';
				}
				if ( isset( $attrs['height'] ) ) {
					$img_attrs['height'] = $attrs['height'] . 'px';
				}
				if ( isset( $attrs['href'] ) ) {
					$img_attrs['href'] = $attrs['href'];
				} else {
					$maybe_link = $img->parentNode;// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					if ( $maybe_link && 'a' === $maybe_link->nodeName && $maybe_link->getAttribute( 'href' ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$img_attrs['href'] = trim( $maybe_link->getAttribute( 'href' ) );
					}
				}
				if ( isset( $attrs['className'] ) && strpos( $attrs['className'], 'is-style-rounded' ) !== false ) {
					$img_attrs['border-radius'] = '999px';
				}
				$markup = '<mj-image ' . self::array_to_attributes( $img_attrs ) . ' />';

				if ( $figcaption ) {
					$caption_html  = '';
					$caption_nodes = $figcaption->childNodes; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					foreach ( $caption_nodes as $caption_node ) {
						$caption_html .= $dom->saveHTML( $caption_node );
					}
					$caption_attrs = array(
						'css-class'   => 'image-caption',
						'align'       => 'center',
						'color'       => '#555d66',
						'line-height' => '1.56',
						'font-size'   => '13px',
						'font-family' => $font_family,
					);
					$markup       .= '<mj-text ' . self::array_to_attributes( $caption_attrs ) . '>' . wp_kses(
						$caption_html,
						self::$allowed_inline_tags
					) . '</mj-text>';
				}

				$block_mjml_markup = $markup;
				break;

			/**
			 * Buttons block.
			 */
			case 'core/buttons':
				// Total percentage of button colunns with defined widths.
				$total_defined_width = array_reduce(
					$inner_blocks,
					function( $acc, $block ) {
						if ( isset( $block['attrs']['width'] ) ) {
							$acc .= intval( $block['attrs']['width'] );
						}
						return $acc;
					},
					0
				);

				// Number of button columns with no defined width.
				$no_widths = count(
					array_filter(
						$inner_blocks,
						function( $block ) {
							return empty( $block['attrs']['width'] );
						}
					)
				);

				// Default width is total amount of undefined width divided by number of undefined width columns, or a minimum of 10%.
				$default_width = ! $no_widths ? 10 : max( 10, ( ( 100 - $total_defined_width ) / $no_widths ) );
				foreach ( $inner_blocks as $button_block ) {
					if ( empty( $button_block['innerHTML'] ) ) {
						break;
					}

					// Parse block content.
					$dom = new DomDocument();
					libxml_use_internal_errors( true );
					$dom->loadHTML( mb_convert_encoding( $button_block['innerHTML'], 'HTML-ENTITIES', get_bloginfo( 'charset' ) ) );
					$xpath         = new DOMXpath( $dom );
					$anchor        = $xpath->query( '//a' )[0];
					$attrs         = self::process_attributes( $button_block['attrs'] );
					$text          = $anchor->textContent; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					$border_radius = isset( $attrs['borderRadius'] ) ? $attrs['borderRadius'] : '999px';
					$is_outlined   = isset( $attrs['className'] ) && 'is-style-outline' == $attrs['className'];

					if ( ! $anchor ) {
						break;
					}

					$default_button_attrs = array(
						'padding'       => '0',
						'inner-padding' => '12px 24px',
						'line-height'   => '1.5',
						'href'          => $anchor->getAttribute( 'href' ),
						'border-radius' => $border_radius,
						'font-size'     => ! empty( $attrs['font-size'] ) ? $attrs['font-size'] : '16px',
						'font-family'   => $font_family,
						'font-weight'   => 'bold',
						// Default color - will be replaced by get_colors if there are colors set.
						'color'         => $is_outlined ? '#32373c' : '#fff !important',
					);
					if ( $is_outlined ) {
						$default_button_attrs['background-color'] = 'transparent';
					} else {
						$default_button_attrs['background-color'] = '#32373c';
					}
					if ( ! empty( $attrs['background-color'] ) ) {
						$default_button_attrs['background-color'] = $attrs['background-color'];
					}
					if ( ! empty( $attrs['color'] ) ) {
						$default_button_attrs['color'] = $attrs['color'];
					}

					$column_attrs['css-class'] = 'mj-column-has-width';
					$column_attrs['width']     = $default_width . '%';
					if ( ! empty( $attrs['width'] ) ) {
						$column_attrs['width']         = $attrs['width'] . '%';
						$default_button_attrs['width'] = '100%'; // Buttons with defined width should fill their column.
					}

					if ( ! empty( $attrs['padding'] ) ) {
						$default_button_attrs['inner-padding'] = $attrs['padding'];
					}
					$button_attrs = array_merge(
						$default_button_attrs,
						self::get_colors( $attrs )
					);

					if ( $is_outlined ) {
						$button_attrs['css-class'] = $attrs['className'];
					}

					$block_mjml_markup .= '<mj-column ' . self::array_to_attributes( $column_attrs ) . '><mj-button ' . self::array_to_attributes( $button_attrs ) . ">$text</mj-button></mj-column>";
				}


				break;

			/**
			 * Separator block.
			 */
			case 'core/separator':
				$is_wide       = isset( $block['attrs']['className'] ) && 'is-style-wide' === $block['attrs']['className'];
				$divider_attrs = array(
					'padding'      => '0',
					'border-width' => '1px',
					'width'        => $is_wide ? '100%' : '128px',
				);
				// Remove colors from section attrs.
				unset( $section_attrs['background-color'] );
				if ( isset( $block['attrs']['backgroundColor'] ) && isset( self::$color_palette[ $block['attrs']['backgroundColor'] ] ) ) {
					$divider_attrs['border-color'] = self::$color_palette[ $block['attrs']['backgroundColor'] ];
				}
				if ( isset( $block['attrs']['style']['color']['background'] ) ) {
					$divider_attrs['border-color'] = $block['attrs']['style']['color']['background'];
				}
				$block_mjml_markup .= '<mj-divider ' . self::array_to_attributes( $divider_attrs ) . '/>';

				break;

			/**
			 * Spacer block.
			 */
			case 'core/spacer':
				$attrs['height']    = $attrs['height'];
				$block_mjml_markup .= '<mj-spacer ' . self::array_to_attributes( $attrs ) . '/>';
				break;

			/**
			 * Social links block.
			 */
			case 'core/social-links':
				$social_wrapper_attrs = array(
					'icon-size'     => '24px',
					'mode'          => 'horizontal',
					'padding'       => '0',
					'border-radius' => '999px',
					'icon-padding'  => '7px',
				);
				if ( isset( $attrs['align'] ) ) {
					$social_wrapper_attrs['align'] = $attrs['align'];
				} else {
					$social_wrapper_attrs['align'] = 'left';
				}
				$markup = '<mj-social ' . self::array_to_attributes( $social_wrapper_attrs ) . '>';
				foreach ( $inner_blocks as $link_block ) {
					if ( isset( $link_block['attrs']['url'] ) ) {
						$url = $link_block['attrs']['url'];
						// Handle older version of the block, where innner blocks we named `core/social-link-<service>`.
						$service_name = isset( $link_block['attrs']['service'] ) ? $link_block['attrs']['service'] : str_replace( 'core/social-link-', '', $link_block['blockName'] );
						$social_icon  = self::get_social_icon( $service_name, $attrs );

						if ( ! empty( $social_icon ) ) {
							$img_attrs = array(
								'href'             => $url,
								'src'              => plugins_url( 'assets/' . $social_icon['icon'], dirname( __FILE__ ) ),
								'background-color' => $social_icon['color'],
								'css-class'        => 'social-element',
							);

							$markup .= '<mj-social-element ' . self::array_to_attributes( $img_attrs ) . '/>';
						}
					}
				}
				$block_mjml_markup .= $markup . '</mj-social>';

				break;

			/**
			 * Single Column block.
			 */
			case 'core/column':
				if ( isset( $attrs['verticalAlignment'] ) ) {
					if ( 'center' === $attrs['verticalAlignment'] ) {
						$column_attrs['vertical-align'] = 'middle';
					} else {
						$column_attrs['vertical-align'] = $attrs['verticalAlignment'];
					}
				}

				if ( isset( $attrs['width'] ) ) {
					$column_attrs['width']     = $attrs['width'];
					$column_attrs['css-class'] = 'mj-column-has-width';
				}

				$markup = '<mj-column ' . self::array_to_attributes( $column_attrs ) . '>';
				foreach ( $inner_blocks as $block ) {
					$markup .= self::render_mjml_component( $block, true, false, $default_attrs );
				}
				$block_mjml_markup = $markup . '</mj-column>';
				break;

			/**
			 * Columns block.
			 */
			case 'core/columns':
				// Some columns might have no width set.
				$widths_sum            = 0;
				$no_width_cols_indexes = [];
				foreach ( $inner_blocks as $i => $block ) {
					if ( isset( $block['attrs']['width'] ) ) {
						$widths_sum += floatval( $block['attrs']['width'] );
					} else {
						array_push( $no_width_cols_indexes, $i );
					}
				};
				foreach ( $no_width_cols_indexes as $no_width_cols_index ) {
					$inner_blocks[ $no_width_cols_index ]['attrs']['width'] = ( 100 - $widths_sum ) / count( $no_width_cols_indexes ) . '%';
				};

				if ( isset( $attrs['color'] ) ) {
					$default_attrs['color'] = $attrs['color'];
				}
				$stack_on_mobile = ! isset( $attrs['isStackedOnMobile'] ) || true === $attrs['isStackedOnMobile'];
				if ( ! $stack_on_mobile ) {
					$markup = '<mj-group>';
				} else {
					$markup = '';
				}
				foreach ( $inner_blocks as $block ) {
					$markup .= self::render_mjml_component( $block, true, false, $default_attrs );
				}
				if ( ! $stack_on_mobile ) {
					$markup .= '</mj-group>';
				}
				$block_mjml_markup = $markup;
				break;

			/**
			 * Newspack Newsletters Posts Inserter block template.
			 */
			case 'newspack-newsletters/posts-inserter':
				$markup = '';
				foreach ( $attrs['innerBlocksToInsert'] as $block ) {
					$markup .= self::render_mjml_component( $block );
				}
				$block_mjml_markup = $markup;
				break;

			/**
			 * List, list item, and quote blocks.
			 * These blocks may or may not contain innerBlocks with their actual content.
			 */
			case 'core/list':
			case 'core/list-item':
			case 'core/quote':
				$text_attrs = array_merge(
					array(
						'padding'     => '0',
						'line-height' => '1.5',
						'font-size'   => '16px',
						'font-family' => $font_family,
					),
					$attrs
				);

				// If a wrapper block, wrap in mj-text.
				if ( ! $is_in_list_or_quote ) {
					$block_mjml_markup .= '<mj-text ' . self::array_to_attributes( $text_attrs ) . '>';
				}

				$block_mjml_markup .= $inner_content[0];
				if ( ! empty( $inner_blocks ) && 1 < count( $inner_content ) ) {
					foreach ( $inner_blocks as $inner_block ) {
						$block_mjml_markup .= self::render_mjml_component( $inner_block, false, false, [], true );
					}
					$block_mjml_markup .= $inner_content[ count( $inner_content ) - 1 ];
				}

				if ( ! $is_in_list_or_quote ) {
					$block_mjml_markup .= '</mj-text>';
				}

				break;

			/**
			 * Group block.
			 */
			case 'core/group':
				// There's no color attribute on mj-wrapper, so it has to be passed to children.
				// https://github.com/mjmlio/mjml/issues/1881 .
				if ( isset( $attrs['color'] ) ) {
					$default_attrs['color'] = $attrs['color'];
				}
				$markup = '<mj-wrapper ' . self::array_to_attributes( $attrs ) . '>';
				foreach ( $inner_blocks as $block ) {
					$markup .= self::render_mjml_component( $block, false, true, $default_attrs );
				}
				$block_mjml_markup = $markup . '</mj-wrapper>';
				break;

			/**
			 * Embed block.
			 */
			case 'core/embed':
				$oembed = _wp_oembed_get_object();
				$data   = $oembed->get_data( $attrs['url'] );

				if ( ! $data || empty( $data->type ) ) {
					break;
				}

				$text_attrs = array(
					'padding'     => '0',
					'line-height' => '1.5',
					'font-size'   => '16px',
					'font-family' => $font_family,
				);

				$caption_attrs = array(
					'align'       => 'center',
					'color'       => '#555d66',
					'line-height' => '1.56',
					'font-size'   => '13px',
					'font-family' => $font_family,
				);

				// Parse block caption.
				$dom = new DomDocument();
				libxml_use_internal_errors( true );
				$dom->loadHTML( mb_convert_encoding( $inner_html, 'HTML-ENTITIES', get_bloginfo( 'charset' ) ) );
				$xpath      = new DOMXpath( $dom );
				$figcaption = $xpath->query( '//figcaption/text()' )[0];
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$caption = ! empty( $figcaption->wholeText ) && is_string( $figcaption->wholeText ) ? $figcaption->wholeText : '';
				if ( empty( $caption ) && ! empty( $data->title ) && is_string( $data->title ) ) {
					$caption = $data->title;
				}

				$markup = '';

				switch ( $data->type ) {
					case 'photo':
						if ( empty( $data->url ) || empty( $data->width ) || empty( $data->height ) ) {
							break;
						}
						if ( ! is_string( $data->url ) || ! is_numeric( $data->width ) || ! is_numeric( $data->height ) ) {
							break;
						}
						$img_attrs = array(
							'src'    => $data->url,
							'alt'    => $caption,
							'width'  => $data->width,
							'height' => $data->height,
							'href'   => $attrs['url'],
						);
						$markup   .= '<mj-image ' . self::array_to_attributes( $img_attrs ) . ' />';
						if ( ! empty( $caption ) ) {
							$markup .= '<mj-text ' . self::array_to_attributes( $caption_attrs ) . '>' . esc_html( $caption ) . ' - ' . esc_html( $data->provider_name ) . '</mj-text>';
						}
						break;
					case 'video':
						if ( ! empty( $data->thumbnail_url ) ) {
							$img_attrs = array(
								'padding' => '0',
								'src'     => $data->thumbnail_url,
								'width'   => $data->thumbnail_width . 'px',
								'height'  => $data->thumbnail_height . 'px',
								'href'    => $attrs['url'],
							);
							$markup   .= '<mj-image ' . self::array_to_attributes( $img_attrs ) . ' />';
							if ( ! empty( $caption ) ) {
								$markup .= '<mj-text ' . self::array_to_attributes( $caption_attrs ) . '>' . esc_html( $caption ) . ' - ' . esc_html( $data->provider_name ) . '</mj-text>';
							}
						} elseif ( ! empty( $caption ) ) {
							$markup .= '<mj-text ' . self::array_to_attributes( $text_attrs ) . '><a href="' . esc_url( $attrs['url'] ) . '">' . esc_html( $caption ) . '</a></mj-text>';
						}
						break;
					case 'rich':
						$html = wp_kses( (string) $data->html, Newspack_Newsletters_Embed::$allowed_html );
						if ( ! empty( $html ) ) {
							$markup .= '<mj-text ' . self::array_to_attributes( $text_attrs ) . '>' . $html . '</mj-text>';
						} elseif ( ! empty( $caption ) ) {
							$markup .= '<mj-text ' . self::array_to_attributes( $text_attrs ) . '><a href="' . esc_url( $attrs['url'] ) . '">' . esc_html( $caption ) . '</a></mj-text>';
						}
						break;
					case 'link':
						if ( ! empty( $caption ) ) {
							$markup .= '<mj-text ' . self::array_to_attributes( $text_attrs ) . '><a href="' . esc_url( $attrs['url'] ) . '">' . esc_html( $caption ) . '</a></mj-text>';
						}
						break;
				}
				$block_mjml_markup = $markup;
				break;
			case 'newspack-newsletters/ad':
				$ad_post = false;
				if ( ! empty( $attrs['adId'] ) ) {
					$ad_post = get_post( $attrs['adId'] );
				} elseif ( ! empty( self::$newsletter_id ) ) {
					$ads = Newspack_Newsletters_Ads::get_newsletter_ads( self::$newsletter_id );
					foreach ( $ads as $ad ) {
						if ( ! Newspack_Newsletters_Ads::is_ad_inserted( self::$newsletter_id, $ad->ID ) ) {
							$ad_post = $ad;
							break;
						}
					}
				}
				if ( $ad_post ) {
					$block_mjml_markup = self::post_to_mjml_components( $ad_post );
					if ( ! empty( self::$newsletter_id ) ) {
						Newspack_Newsletters_Ads::mark_ad_inserted( self::$newsletter_id, $ad_post->ID );
					}
				}
				break;
		}

		$is_posts_inserter_block = 'newspack-newsletters/posts-inserter' == $block_name;
		$is_grouped_block        = in_array( $block_name, [ 'core/group', 'core/list', 'core/list-item', 'core/quote' ], true );

		if (
			! $is_in_column &&
			! $is_in_list_or_quote &&
			! $is_grouped_block &&
			'core/columns' != $block_name &&
			'core/column' != $block_name &&
			'core/buttons' != $block_name &&
			'core/separator' != $block_name &&
			! $is_posts_inserter_block
		) {
			$column_attrs['width'] = '100%';
			$block_mjml_markup     = '<mj-column ' . self::array_to_attributes( $column_attrs ) . '>' . $block_mjml_markup . '</mj-column>';
		}

		if ( ! $is_in_column && ! $is_in_list_or_quote && ! $is_posts_inserter_block ) {
			$block_mjml_markup = '<mj-section ' . self::array_to_attributes( $section_attrs ) . '>' . $block_mjml_markup . '</mj-section>';
		}

		if ( ! empty( $conditionals ) ) {
			$block_mjml_markup = '<mj-raw>' . $conditionals['before'] . '</mj-raw>' . $block_mjml_markup . '<mj-raw>' . $conditionals['after'] . '</mj-raw>';
		}

		return $block_mjml_markup;
	}

	/** Convert a WP post to an array of non-empty blocks.
	 *
	 * @param WP_Post $post The post.
	 * @return array[] Blocks.
	 */
	private static function get_valid_post_blocks( $post ) {
		// Disable photon for newsletter images (webp is not supported on some email clients).
		add_filter( 'jetpack_photon_skip_image', '__return_true' );
		/**
		 * Filters the newsletter post content before parsing it into blocks.
		 *
		 * @param string  $content The post content.
		 * @param WP_Post $post    The post object.
		 */
		$content = apply_filters( 'newspack_newsletters_newsletter_content', $post->post_content, $post );
		return array_filter(
			parse_blocks( $content ),
			function ( $block ) {
				return null !== $block['blockName'];
			}
		);
	}

	/**
	 * Convert a WP post to MJML components.
	 *
	 * @param WP_Post $post The post.
	 *
	 * @return string MJML markup to be injected into the template.
	 */
	public static function post_to_mjml_components( $post ) {
		$body         = '';
		$valid_blocks = self::get_valid_post_blocks( $post );

		// Build MJML body.
		foreach ( $valid_blocks as $block ) {
			$block_content = '';

			// Convert reusable block to group block.
			// Reusable blocks are CPTs, where the block's ref attribute is the post ID.
			if ( 'core/block' === $block['blockName'] && isset( $block['attrs']['ref'] ) ) {
				$reusable_block_post = get_post( $block['attrs']['ref'] );
				if ( ! empty( $reusable_block_post ) ) {
					$block['blockName']    = 'core/group';
					$block['innerBlocks']  = self::get_valid_post_blocks( $reusable_block_post );
					$block['innerHTML']    = $reusable_block_post->post_content;
					$block['innerContent'] = $reusable_block_post->post_content;
				}
			}

			if ( 'core/group' === $block['blockName'] ) {
				$default_attrs = [];
				$attrs         = self::process_attributes( $block['attrs'] );
				$conditionals  = [];
				if ( ! empty( $attrs['conditionalBefore'] ) && ! empty( $attrs['conditionalAfter'] ) ) {
					$conditionals = [
						'before' => $attrs['conditionalBefore'],
						'after'  => $attrs['conditionalAfter'],
					];
				}
				if ( isset( $attrs['color'] ) ) {
					$default_attrs['color'] = $attrs['color'];
				}
				$mjml_markup = '<mj-wrapper ' . self::array_to_attributes( $attrs ) . '>';
				foreach ( $block['innerBlocks'] as $block ) {
					$inner_block_content = self::render_mjml_component( $block, false, true, $default_attrs );
					$mjml_markup        .= $inner_block_content;
				}
				$block_content = $mjml_markup . '</mj-wrapper>';
				if ( ! empty( $conditionals ) ) {
					$block_content = '<mj-raw>' . $conditionals['before'] . '</mj-raw>' . $block_content . '<mj-raw>' . $conditionals['after'] . '</mj-raw>';
				}
			} else {
				$block_content = self::render_mjml_component( $block );
			}

			$body .= $block_content;
		}

		return self::process_links( $body, $post );
	}

	/**
	 * Convert a WP post to MJML markup.
	 *
	 * @param WP_Post $post The post.
	 * @return string MJML markup.
	 */
	public static function render_post_to_mjml( $post ) {
		self::$newsletter_id = $post->ID;
		self::$color_palette = json_decode( get_option( 'newspack_newsletters_color_palette', false ), true );
		self::$font_header   = get_post_meta( $post->ID, 'font_header', true );
		self::$font_body     = get_post_meta( $post->ID, 'font_body', true );
		$is_public           = get_post_meta( $post->ID, 'is_public', true );

		if ( $is_public ) {
			self::$post_permalink = get_permalink( $post->ID );
		}
		if ( ! in_array( self::$font_header, Newspack_Newsletters::$supported_fonts ) ) {
			self::$font_header = 'Arial';
		}
		if ( ! in_array( self::$font_body, Newspack_Newsletters::$supported_fonts ) ) {
			self::$font_body = 'Georgia';
		}

		$title = $post->post_title; // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable

		/**
		 * Generate a string of MJML as the body of the email. We include ads at this stage.
		 */
		$body             = self::post_to_mjml_components( $post ); // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable
		$background_color = get_post_meta( $post->ID, 'background_color', true );
		$preview_text     = get_post_meta( $post->ID, 'preview_text', true );
		$custom_css       = get_post_meta( $post->ID, 'custom_css', true );
		if ( ! $background_color ) {
			$background_color = '#ffffff';
		}

		ob_start();
		include dirname( __FILE__ ) . '/email-template.mjml.php';
		return ob_get_clean();
	}

	/**
	 * Retrieve email-compliant HTML for a newsletter CPT.
	 *
	 * @param WP_Post $post The post.
	 * @return string email-compliant HTML.
	 */
	public static function retrieve_email_html( $post ) {
		return get_post_meta( $post->ID, Newspack_Newsletters::EMAIL_HTML_META, true );
	}
}
