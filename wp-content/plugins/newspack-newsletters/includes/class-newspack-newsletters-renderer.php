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
	protected static $color_palette = null;

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
	 * Convert a list to HTML attributes.
	 *
	 * @param array $attributes Array of attributes.
	 * @return string HTML attributes as a string.
	 */
	private static function array_to_attributes( $attributes ) {
		return join(
			' ',
			array_map(
				function( $key ) use ( $attributes ) {
					if ( isset( $attributes[ $key ] ) ) {
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
			// Gutenberg's default font size presets.
			// https://github.com/WordPress/gutenberg/blob/359858da0675943d8a759a0a7c03e7b3846536f5/packages/block-editor/src/store/defaults.js#L87-L113 .
			$sizes = array(
				'small'  => '13px',
				'normal' => '16px',
				'medium' => '20px',
				'large'  => '36px',
				'huge'   => '48px',
			);
			return $sizes[ $block_attrs['fontSize'] ];
		}
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

		if ( isset( $attrs['style'] ) ) {
			if ( isset( $attrs['style']['color']['background'] ) ) {
				$attrs['background-color'] = $attrs['style']['color']['background'];
			}
			if ( isset( $attrs['style']['color']['text'] ) ) {
				$attrs['color'] = $attrs['style']['color']['text'];
			}
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

		if ( isset( $attrs['background-color'] ) ) {
			$attrs['padding'] = '0';
		}

		if ( isset( $attrs['align'] ) && 'full' == $attrs['align'] ) {
			$attrs['full-width'] = 'full-width';
			unset( $attrs['align'] );
		}

		if ( isset( $attrs['full-width'] ) && 'full-width' == $attrs['full-width'] && isset( $attrs['background-color'] ) ) {
			$attrs['padding'] = '20px 0';
		}

		return $attrs;
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
	private static function render_mjml_component( $block, $is_in_column = false, $is_in_group = false, $default_attrs = [] ) {
		$block_name   = $block['blockName'];
		$attrs        = $block['attrs'];
		$inner_blocks = $block['innerBlocks'];
		$inner_html   = $block['innerHTML'];

		if ( ! isset( $attrs['innerBlocksToInsert'] ) && ( empty( $block_name ) || empty( $inner_html ) ) ) {
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
				'padding' => '20px',
			)
		);

		$font_family = 'core/heading' === $block_name ? self::$font_header : self::$font_body;

		switch ( $block_name ) {
			/**
			 * Paragraph, List, Heading blocks.
			 */
			case 'core/paragraph':
			case 'core/list':
			case 'core/heading':
			case 'core/quote':
				$text_attrs = array_merge(
					array(
						'padding'     => '0',
						'line-height' => '1.8',
						'font-size'   => '16px',
						'font-family' => $font_family,
					),
					$attrs
				);

				// Only mj-text has to use container-background-color attr for background color.
				if ( isset( $text_attrs['background-color'] ) ) {
					$text_attrs['container-background-color'] = $text_attrs['background-color'];
					unset( $text_attrs['background-color'] );
				}

				$block_mjml_markup = '<mj-text ' . self::array_to_attributes( $text_attrs ) . '>' . $inner_html . '</mj-text>';
				break;

			/**
			 * Image block.
			 */
			case 'core/image':
				// Parse block content.
				$dom = new DomDocument();
				@$dom->loadHTML( $inner_html ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				$xpath      = new DOMXpath( $dom );
				$img        = $xpath->query( '//img' )[0];
				$img_src    = $img->getAttribute( 'src' );
				$figcaption = $xpath->query( '//figcaption/text()' )[0];

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
				if ( isset( $attrs['linkDestination'] ) ) {
					$img_attrs['href'] = $attrs['linkDestination'];
				}

				if ( isset( $attrs['className'] ) && strpos( $attrs['className'], 'is-style-rounded' ) !== false ) {
					$img_attrs['border-radius'] = '999px';
				}
				$markup = '<mj-image ' . self::array_to_attributes( $img_attrs ) . ' />';

				if ( $figcaption ) {
					$caption_attrs = array(
						'align'       => 'center',
						'color'       => '#555d66',
						'font-size'   => '13px',
						'font-family' => $font_family,
					);
					 // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					$markup .= '<mj-text ' . self::array_to_attributes( $caption_attrs ) . '>' . $figcaption->wholeText . '</mj-text>';
				}

				$block_mjml_markup = $markup;
				break;

			/**
			 * Buttons block.
			 */
			case 'core/buttons':
				foreach ( $inner_blocks as $button_block ) {
					// Parse block content.
					$dom = new DomDocument();
					@$dom->loadHTML( $button_block['innerHTML'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
					$xpath         = new DOMXpath( $dom );
					$anchor        = $xpath->query( '//a' )[0];
					$attrs         = $button_block['attrs'];
					$text          = $anchor->textContent; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					$border_radius = isset( $attrs['borderRadius'] ) ? $attrs['borderRadius'] : 28;
					$is_outlined   = isset( $attrs['className'] ) && 'is-style-outline' == $attrs['className'];

					$default_button_attrs = array(
						'padding'       => '0',
						'inner-padding' => '12px 24px',
						'line-height'   => '1.8',
						'href'          => $anchor->getAttribute( 'href' ),
						'border-radius' => $border_radius . 'px',
						'font-size'     => '18px',
						'font-family'   => $font_family,
						// Default color - will be replaced by get_colors if there are colors set.
						'color'         => $is_outlined ? '#32373c' : '#fff',
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
				$is_style_default   = isset( $attrs['className'] ) ? 'is-style-default' == $attrs['className'] : true;
				$divider_attrs      = array_merge(
					array(
						'padding'      => '0',
						'border-width' => $is_style_default ? '2px' : '1px',
						'width'        => $is_style_default ? '100px' : '100%',
						// Default color - will be replaced by get_colors if there are colors set.
						'border-color' => '#8f98a1',
					),
					self::get_colors( $attrs )
				);
				$block_mjml_markup .= '<mj-divider ' . self::array_to_attributes( $divider_attrs ) . '/>';

				break;

			/**
			 * Spacer block.
			 */
			case 'core/spacer':
				$attrs['height']    = $attrs['height'] . 'px';
				$block_mjml_markup .= '<mj-spacer ' . self::array_to_attributes( $attrs ) . '/>';
				break;

			/**
			 * Social links block.
			 */
			case 'core/social-links':
				$social_icons = array(
					'wordpress' => array(
						'color' => '#3499cd',
						'icon'  => 'wordpress.png',
					),
					'facebook'  => array(
						'color' => '#1977f2',
						'icon'  => 'facebook.png',
					),
					'twitter'   => array(
						'color' => '#21a1f3',
						'icon'  => 'twitter.png',
					),
					'instagram' => array(
						'color' => '#f00075',
						'icon'  => 'instagram.png',
					),
					'linkedin'  => array(
						'color' => '#0577b5',
						'icon'  => 'linkedin.png',
					),
					'youtube'   => array(
						'color' => '#ff0100',
						'icon'  => 'youtube.png',
					),
				);

				$social_wrapper_attrs = array(
					'align'         => isset( $attrs['align'] ) && 'center' == $attrs['align'] ? 'center' : 'left',
					'icon-size'     => '22px',
					'mode'          => 'horizontal',
					'padding'       => '0',
					'border-radius' => '999px',
					'icon-padding'  => '8px',
				);
				$markup               = '<mj-social ' . self::array_to_attributes( $social_wrapper_attrs ) . '>';
				foreach ( $inner_blocks as $link_block ) {
					if ( isset( $link_block['attrs']['url'] ) ) {
						$url = $link_block['attrs']['url'];
						// Handle older version of the block, where innner blocks we named `core/social-link-<service>`.
						$service_name = isset( $link_block['attrs']['service'] ) ? $link_block['attrs']['service'] : str_replace( 'core/social-link-', '', $link_block['blockName'] );

						if ( isset( $social_icons[ $service_name ] ) ) {
							$img_attrs = array(
								'href'             => $url,
								'src'              => plugins_url( 'assets/' . $social_icons[ $service_name ]['icon'], dirname( __FILE__ ) ),
								'background-color' => $social_icons[ $service_name ]['color'],
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
					$column_attrs['width']     = $attrs['width'] . '%';
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
					$inner_blocks[ $no_width_cols_index ]['attrs']['width'] = ( 100 - $widths_sum ) / count( $no_width_cols_indexes );
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
		}

		$is_posts_inserter_block = 'newspack-newsletters/posts-inserter' == $block_name;
		$is_group_block          = 'core/group' == $block_name;

		if (
			! $is_in_column &&
			! $is_group_block &&
			'core/columns' != $block_name &&
			'core/column' != $block_name &&
			'core/buttons' != $block_name &&
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
	 * Convert a WP post to MJML components.
	 *
	 * @param WP_Post $post The post.
	 * @param Boolean $include_ads Whether to include ads.
	 * @return string MJML markup to be injected into the template.
	 */
	private static function post_to_mjml_components( $post, $include_ads ) {
		self::$color_palette = json_decode( get_option( 'newspack_newsletters_color_palette', false ), true );
		self::$font_header   = get_post_meta( $post->ID, 'font_header', true );
		self::$font_body     = get_post_meta( $post->ID, 'font_body', true );
		if ( ! in_array( self::$font_header, Newspack_Newsletters::$supported_fonts ) ) {
			self::$font_header = 'Arial';
		}
		if ( ! in_array( self::$font_body, Newspack_Newsletters::$supported_fonts ) ) {
			self::$font_body = 'Georgia';
		}
		$blocks = parse_blocks( $post->post_content );
		$body   = '';
		foreach ( $blocks as $block ) {
			$block_content = self::render_mjml_component( $block );
			if ( ! empty( $block_content ) ) {
				$body .= $block_content;
			}
		}

		// Insert any ads.
		if ( $include_ads ) {
			$ads_query  = new WP_Query(
				array(
					'post_type'      => Newspack_Newsletters_Ads::NEWSPACK_NEWSLETTERS_ADS_CPT,
					'posts_per_page' => -1,
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => 'expiry_date',
							'value'   => gmdate( 'Y-m-d' ),
							'compare' => '>=',
							'type'    => 'DATE',
						),
					),
				)
			);
			$ads        = $ads_query->get_posts();
			$ads_markup = '';
			foreach ( $ads as $ad ) {
				$expiry_date = new DateTime( get_post_meta( $ad->ID, 'expiry_date', true ) );
				$ads_markup .= self::post_to_mjml_components( $ad, false );
			}
			$body .= $ads_markup;
		}

		return $body;
	}

	/**
	 * Convert a WP post to MJML markup.
	 *
	 * @param WP_Post $post The post.
	 * @return string MJML markup.
	 */
	private static function render_mjml( $post ) {
		$title            = $post->post_title; // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable
		$body             = self::post_to_mjml_components( $post, true ); // phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UnusedVariable
		$background_color = get_post_meta( $post->ID, 'background_color', true );
		if ( ! $background_color ) {
			$background_color = '#ffffff';
		}
		ob_start();
		include dirname( __FILE__ ) . '/email-template.mjml.php';
		return ob_get_clean();
	}

	/**
	 * Return MJML API credentials.
	 *
	 * @return string API key and API secret as a key:secret string.
	 */
	public static function mjml_api_credentials() {
		$key    = get_option( 'newspack_newsletters_mjml_api_key', false );
		$secret = get_option( 'newspack_newsletters_mjml_api_secret', false );
		if ( isset( $key, $secret ) ) {
			return "$key:$secret";
		}
		return false;
	}

	/**
	 * Convert a WP Post to email-compliant HTML.
	 *
	 * @param WP_Post $post The post.
	 * @return string email-compliant HTML.
	 * @throws Exception Error message.
	 */
	public static function render_html_email( $post ) {
		$mjml_creds = self::mjml_api_credentials();
		if ( $mjml_creds ) {
			$mjml_api_url = 'https://api.mjml.io/v1/render';
			$request      = wp_remote_post(
				$mjml_api_url,
				array(
					'body'    => wp_json_encode(
						array(
							'mjml' => self::render_mjml( $post ),
						)
					),
					'headers' => array(
						'Authorization' => 'Basic ' . base64_encode( $mjml_creds ),
					),
					'timeout' => 45, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
				)
			);
			if ( 401 === intval( $request['response']['code'] ) ) {
				throw new Exception( __( 'MJML rendering error.', 'newspack_newsletters' ) );
			}
			return is_wp_error( $request ) ? $request : json_decode( $request['body'] )->html;
		}
	}
}
