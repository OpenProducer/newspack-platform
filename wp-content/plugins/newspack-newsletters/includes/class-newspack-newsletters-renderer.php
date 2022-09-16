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
	 * Ads to insert.
	 *
	 * @var Array
	 */
	protected static $ads_to_insert = [];

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
						return $key . '="' . $attributes[ $key ] . '"';
					} else {
						return '';
					}
				},
				array_keys( $attributes )
			)
		);
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
			$padding          = $attrs['style']['spacing']['padding'];
			$attrs['padding'] = sprintf( '%s %s %s %s', $padding['top'], $padding['right'], $padding['bottom'], $padding['left'] );
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
	 * @param string $html input HTML.
	 * @return string HTML with processed links.
	 */
	public static function process_links( $html ) {
		preg_match_all( '/href="([^"]*)"/', $html, $matches );
		$href_params = $matches[0];
		$urls        = $matches[1];
		foreach ( $urls as $index => $url ) {
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
				$url
			);

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
	 * @return string MJML component.
	 */
	public static function render_mjml_component( $block, $is_in_column = false, $is_in_group = false, $default_attrs = [] ) {
		$block_name   = $block['blockName'];
		$attrs        = $block['attrs'];
		$inner_blocks = $block['innerBlocks'];
		$inner_html   = $block['innerHTML'];

		if ( ! isset( $attrs['innerBlocksToInsert'] ) && self::is_empty_block( $block ) ) {
			return '';
		}

		// Verify if block is configured to be web-only.
		if ( isset( $attrs['newsletterVisibility'] ) && 'web' === $attrs['newsletterVisibility'] ) {
			return '';
		}

		$block_mjml_markup = '';
		$attrs             = self::process_attributes( array_merge( $default_attrs, $attrs ) );

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
			case 'core/list':
			case 'core/heading':
			case 'core/quote':
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

				$block_mjml_markup = '<mj-text ' . self::array_to_attributes( $text_attrs ) . '>' . $inner_html . '</mj-text>';
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
				$figcaption = $dom->getElementsByTagName( 'figcaption' )->item( 0 );

				$img_attrs = array(
					'padding' => '0',
					'align'   => isset( $attrs['align'] ) ? $attrs['align'] : 'left',
					'src'     => $img_src,
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
					$attrs         = $button_block['attrs'];
					$text          = $anchor->textContent; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					$border_radius = isset( $attrs['borderRadius'] ) ? $attrs['borderRadius'] : 999;
					$is_outlined   = isset( $attrs['className'] ) && 'is-style-outline' == $attrs['className'];

					if ( ! $anchor ) {
						break;
					}

					$default_button_attrs = array(
						'padding'       => '0',
						'inner-padding' => '12px 24px',
						'line-height'   => '1.5',
						'href'          => $anchor->getAttribute( 'href' ),
						'border-radius' => $border_radius . 'px',
						'font-size'     => '16px',
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
				if ( $block['attrs']['backgroundColor'] && isset( self::$color_palette[ $block['attrs']['backgroundColor'] ] ) ) {
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
				$markup = '';
				foreach ( $inner_blocks as $block ) {
					$markup .= self::render_mjml_component( $block, true, false, $default_attrs );
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
		}

		$is_posts_inserter_block = 'newspack-newsletters/posts-inserter' == $block_name;
		$is_group_block          = 'core/group' == $block_name;

		if (
			! $is_in_column &&
			! $is_group_block &&
			'core/columns' != $block_name &&
			'core/column' != $block_name &&
			'core/buttons' != $block_name &&
			'core/separator' != $block_name &&
			! $is_posts_inserter_block
		) {
			$column_attrs['width'] = '100%';
			$block_mjml_markup     = '<mj-column ' . self::array_to_attributes( $column_attrs ) . '>' . $block_mjml_markup . '</mj-column>';
		}
		if ( $is_in_column || $is_group_block || $is_posts_inserter_block ) {
			// Render a nested block without a wrapping section.
			return $block_mjml_markup;
		} else {
			return '<mj-section ' . self::array_to_attributes( $section_attrs ) . '>' . $block_mjml_markup . '</mj-section>';
		}
	}

	/**
	 * Get total length of newsletter's content.
	 *
	 * @param array $blocks Array of post blocks.
	 * @return number Total length of the newsletter content.
	 */
	private static function get_total_newsletter_character_length( $blocks ) {
		return array_reduce(
			$blocks,
			function( $length, $block ) {
				if ( 'newspack-newsletters/posts-inserter' === $block['blockName'] ) {
					$length += self::get_total_newsletter_character_length( $block['attrs']['innerBlocksToInsert'] );
				} elseif ( isset( $block['innerBlocks'] ) && count( $block['innerBlocks'] ) ) {
					$length += self::get_total_newsletter_character_length( $block['innerBlocks'] );
				} else {
					$length += strlen( wp_strip_all_tags( $block['innerHTML'] ) );
				}
				return $length;
			},
			0
		);
	}

	/**
	 * Insert ads in a piece of markup.
	 *
	 * @param string $markup The markup.
	 * @param number $current_position Current position, as character offset.
	 * @return string Markup with ads inserted.
	 */
	private static function insert_ads( $markup, $current_position ) {
		foreach ( self::$ads_to_insert as &$ad_to_insert ) {
			if (
				! $ad_to_insert['is_inserted'] &&
				(
					// If ad is at 100%, insert it only in the last pass, which appends ads to the bottom of the newsletter.
					// Otherwise, such ad might end up right before the last block because of the `>=` check below.
					1 === $ad_to_insert['percentage']
						? INF === $current_position
						: $current_position >= $ad_to_insert['precise_position']
				)
			) {
				$markup                     .= $ad_to_insert['markup'];
				$ad_to_insert['is_inserted'] = true;
			}
		}
		return $markup;
	}

	/**
	 * Return an array of Newspack-native ads.
	 *
	 * @param int $total_length_of_content The total length of content.
	 * @return array
	 */
	private static function generate_array_of_newspack_native_ads_to_insert( $total_length_of_content ) {
		$ad_post_type          = Newspack_Newsletters_Ads::NEWSPACK_NEWSLETTERS_ADS_CPT;
		$all_ads_no_pagination = -1;

		$query_to_fetch_published_ads = new WP_Query(
			[
				'post_type'      => $ad_post_type,
				'posts_per_page' => $all_ads_no_pagination,
			]
		);

		$ads = $query_to_fetch_published_ads->get_posts();

		$published_ads_to_insert = [];

		foreach ( $ads as $ad ) {
			$ad_post_status      = $ad->post_status;
			$ad_is_not_published = 'publish' !== $ad_post_status;

			if ( $ad_is_not_published ) {
				continue;
			}

			$ad_prepared_for_insertion = self::get_ad_prepared_for_insertion( $ad, $total_length_of_content );
			if ( ! empty( $ad_prepared_for_insertion ) ) {
				$published_ads_to_insert[] = $ad_prepared_for_insertion;
			}
		}

		return $published_ads_to_insert;
	}

	/**
	 * Return an array of ads prepared to be inserted into the email template. If the Newspack
	 * author has Letterhead enabled, we'll prefer fetching ads from that API. If not, we'll
	 * prefer Newspack ad types.
	 *
	 * @param string $post_date The WP Post date.
	 * @param int    $total_length The total length of the content.
	 * @return array
	 */
	public static function get_ads( $post_date, $total_length ) {
		/**
		 * The Letterhead API just likes dates that look like this.
		 *
		 * @example '2021-04-12'
		 * @var string $publication_date_formatted_for_letterhead_api
		 */
		$publication_date_formatted_for_letterhead_api = gmdate( 'Y-m-d', strtotime( $post_date ) );
		$letterhead                                    = new Newspack_Newsletters_Letterhead();

		/**
		 * Whether when getting ads we should load Newspack's ad post type.
		 *
		 * @var bool $prefer_newspack_native_ads
		 */
		$prefer_newspack_native_ads = ! $letterhead->has_api_credentials();

		/**
		 * If our Newspack user isn't connected to Letterhead, no worries. We will return
		 * any native ads they might have.
		 */
		if ( $prefer_newspack_native_ads ) {
			return self::generate_array_of_newspack_native_ads_to_insert( $total_length );
		}

		/**
		 * Otherwise, we will fetch Letterhead ads ("promotions") from that API.
		 */
		return $letterhead->get_and_prepare_promotions_for_insertion( $publication_date_formatted_for_letterhead_api, $total_length );
	}

	/**
	 * Gets a newspack ad and formats it for insertion.
	 *
	 * @param WP_Post $ad The Ad newsletter post.
	 * @param int     $total_length_of_content The length of content.
	 * @return array|null
	 */
	private static function get_ad_prepared_for_insertion( $ad, $total_length_of_content ) {
		$ad_id                  = $ad->ID;
		$is_published_ad_active = self::is_published_ad_active( $ad_id );

		if ( $is_published_ad_active ) {
			$positioning      = self::get_ad_position_percentage( $ad_id );
			$precise_position = self::get_ad_placement_precise_position( $positioning, $total_length_of_content );

			return [
				'is_inserted'      => false,
				'markup'           => self::post_to_mjml_components( $ad, false ),
				'percentage'       => $positioning,
				'precise_position' => $precise_position,
			];
		}

		return null;
	}

	/**
	 * Given the position preference on the ad object and the total length of newsletter content, we'll return
	 * a specific number to indicate where the ad should be inserted in the body of the newsletter.
	 *
	 * @param int $position_percentage The position preference on an ad.
	 * @param int $total_length_of_newsletter_content The total length of newsletter content.
	 * @return float|int
	 */
	public static function get_ad_placement_precise_position( $position_percentage, $total_length_of_newsletter_content ) {
		return $total_length_of_newsletter_content * $position_percentage;
	}

	/**
	 * Gets the position preference of a newspack native ad from post meta.
	 *
	 * @param int $ad_id The id of the ad post type.
	 * @return float|int
	 */
	private static function get_ad_position_percentage( $ad_id ) {
		$position_key   = 'position_in_content';
		$position_value = get_post_meta( $ad_id, $position_key, true );

		return intval( $position_value ) / 100;
	}


	/**
	 * Whether the newspack native ad is active or expired.
	 *
	 * @param int $ad_id ID of the Ad post type.
	 * @return bool
	 */
	private static function is_published_ad_active( $ad_id ) {
		$expiration_date = self::get_ad_expiration_date( $ad_id );

		if ( ! $expiration_date ) {
			return true;
		}

		return self::is_ad_unexpired( $expiration_date );
	}

	/**
	 * Determines whetherthe newspack native ad is expired.
	 *
	 * @param string $expiration_date_as_datetime The expiration date as datetime.
	 * @return bool
	 */
	private static function is_ad_unexpired( $expiration_date_as_datetime ) {
		$date_format               = 'Y-m-d';
		$formatted_expiration_date = $expiration_date_as_datetime->format( $date_format );
		$today                     = gmdate( $date_format );

		return $formatted_expiration_date >= $today;
	}

	/**
	 * Returns the ad expiration date of a native Ad post type from the post meta.
	 *
	 * @param int $ad_id The ad id.
	 * @return DateTime
	 */
	private static function get_ad_expiration_date( $ad_id ) {
		$expiration_date_meta_key   = 'expiry_date';
		$expiration_date_meta_value = get_post_meta( $ad_id, $expiration_date_meta_key, true );

		return new DateTime( $expiration_date_meta_value );
	}

	/** Convert a WP post to an array of non-empty blocks.
	 *
	 * @param WP_Post $post The post.
	 * @return array[] Blocks.
	 */
	private static function get_valid_post_blocks( $post ) {
		return array_filter(
			parse_blocks( $post->post_content ),
			function ( $block ) {
				return null !== $block['blockName'];
			}
		);
	}

	/**
	 * Convert a WP post to MJML components.
	 *
	 * @param WP_Post $post The post.
	 * @param Boolean $include_ads Whether to include ads.
	 * @return string MJML markup to be injected into the template.
	 */
	public static function post_to_mjml_components( $post, $include_ads = false ) {
		$body          = '';
		$valid_blocks  = self::get_valid_post_blocks( $post );
		$total_length  = self::get_total_newsletter_character_length( $valid_blocks );
		$is_newsletter = Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT === get_post_type( $post->ID );

		/**
		 * When ads are enabled, we fetch and format them for insertion.
		 *
		 * @note "diable_ads" is a typo that's been in production for awhile.
		 */
		if ( $include_ads && $is_newsletter && ! get_post_meta( $post->ID, 'diable_ads', true ) ) {
			self::$ads_to_insert = self::get_ads( $post->post_date, $total_length );
		}

		// Build MJML body and insert ads.
		$current_position = 0;
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

			// Insert ads between top-level group blocks' inner blocks.
			if ( 'core/group' === $block['blockName'] ) {
				$default_attrs = [];
				$attrs         = self::process_attributes( $block['attrs'] );
				if ( isset( $attrs['color'] ) ) {
					$default_attrs['color'] = $attrs['color'];
				}
				$mjml_markup = '<mj-wrapper ' . self::array_to_attributes( $attrs ) . '>';
				foreach ( $block['innerBlocks'] as $block ) {
					$inner_block_content = self::render_mjml_component( $block, false, true, $default_attrs );
					if ( $include_ads ) {
						$current_position += strlen( wp_strip_all_tags( $inner_block_content ) );
						$mjml_markup       = self::insert_ads( $mjml_markup, $current_position );
					}
					$mjml_markup .= $inner_block_content;
				}
				$block_content = $mjml_markup . '</mj-wrapper>';
			} else {
				// Insert ads between other blocks.
				$block_content = self::render_mjml_component( $block );
				if ( $include_ads ) {
					$current_position += strlen( wp_strip_all_tags( $block_content ) );
					$body              = self::insert_ads( $body, $current_position );
				}
			}

			$body .= $block_content;
		}

		// Insert any remaining ads at the end.
		if ( $include_ads ) {
			$body = self::insert_ads( $body, INF );
		}

		return self::process_links( $body );
	}

	/**
	 * Convert a WP post to MJML markup.
	 *
	 * @param WP_Post $post The post.
	 * @return string MJML markup.
	 */
	public static function render_post_to_mjml( $post ) {
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
		$body             = self::post_to_mjml_components( $post, true ); // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable
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
