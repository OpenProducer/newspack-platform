<?php
/**
 * Server-side rendering of the `core/post-author` block.
 *
 * @package WordPress
 */

/**
 * Build an array with CSS classes and inline styles defining the colors
 * which will be applied to the navigation markup in the front-end.
 *
 * @param  array $attributes Navigation block attributes.
 * @return array Colors CSS classes and inline styles.
 */
function gutenberg_post_author_build_css_colors( $attributes ) {
	$text_colors       = array(
		'css_classes'   => array(),
		'inline_styles' => '',
	);
	$background_colors = array(
		'css_classes'   => array(),
		'inline_styles' => '',
	);

	// Text color.
	$has_named_text_color  = array_key_exists( 'textColor', $attributes );
	$has_custom_text_color = array_key_exists( 'customTextColor', $attributes );

	// If has text color.
	if ( $has_custom_text_color || $has_named_text_color ) {
		// Add has-text-color class.
		$text_colors['css_classes'][] = 'has-text-color';
	}

	if ( $has_named_text_color ) {
		// Add the color class.
		$text_colors['css_classes'][] = sprintf( 'has-%s-color', $attributes['textColor'] );
	} elseif ( $has_custom_text_color ) {
		// Add the custom color inline style.
		$text_colors['inline_styles'] .= sprintf( 'color: %s;', $attributes['customTextColor'] );
	}

	// Background color.
	$has_named_background_color  = array_key_exists( 'backgroundColor', $attributes );
	$has_custom_background_color = array_key_exists( 'customBackgroundColor', $attributes );

	// If has background color.
	if ( $has_custom_background_color || $has_named_background_color ) {
		// Add has-background-color class.
		$background_colors['css_classes'][] = 'has-background-color';
	}

	if ( $has_named_background_color ) {
		// Add the background-color class.
		$background_colors['css_classes'][] = sprintf( 'has-%s-background-color', $attributes['backgroundColor'] );
	} elseif ( $has_custom_background_color ) {
		// Add the custom background-color inline style.
		$background_colors['inline_styles'] .= sprintf( 'background-color: %s;', $attributes['customBackgroundColor'] );
	}

	return array(
		'text'       => $text_colors,
		'background' => $background_colors,
	);
}

/**
 * Build an array with CSS classes and inline styles defining the font sizes
 * which will be applied to the navigation markup in the front-end.
 *
 * @param  array $attributes Navigation block attributes.
 * @return array Font size CSS classes and inline styles.
 */
function gutenberg_post_author_build_css_font_sizes( $attributes ) {
	// CSS classes.
	$font_sizes = array(
		'css_classes'   => array(),
		'inline_styles' => '',
	);

	$has_named_font_size  = array_key_exists( 'fontSize', $attributes );
	$has_custom_font_size = array_key_exists( 'style', $attributes )
	&& array_key_exists( 'typography', $attributes['style'] )
	&& array_key_exists( 'fontSize', $attributes['style']['typography'] );

	if ( $has_named_font_size ) {
		// Add the font size class.
		$font_sizes['css_classes'][] = sprintf( 'has-%s-font-size', $attributes['fontSize'] );
	} elseif ( $has_custom_font_size ) {
		// Add the custom font size inline style.
		$font_sizes['inline_styles'] = sprintf( 'font-size: %spx;', $attributes['style']['typography']['fontSize'] );
	}

	return $font_sizes;
}

/**
 * Renders the `core/post-author` block on the server.
 *
 * @param  array    $attributes Block attributes.
 * @param  string   $content    Block default content.
 * @param  WP_Block $block      Block instance.
 * @return string Returns the rendered author block.
 */
function gutenberg_render_block_core_post_author( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$author_id = get_post_field( 'post_author', $block->context['postId'] );
	if ( empty( $author_id ) ) {
		return '';
	}

	$avatar = ! empty( $attributes['avatarSize'] ) ? get_avatar(
		$author_id,
		$attributes['avatarSize']
	) : null;

	$byline     = ! empty( $attributes['byline'] ) ? $attributes['byline'] : false;
	$colors     = gutenberg_post_author_build_css_colors( $attributes );
	$font_sizes = gutenberg_post_author_build_css_font_sizes( $attributes );
	$classes    = array_merge(
		$colors['background']['css_classes'],
		$font_sizes['css_classes'],
		array( 'wp-block-post-author' ),
		isset( $attributes['className'] ) ? array( $attributes['className'] ) : array(),
		isset( $attributes['itemsJustification'] ) ? array( 'items-justified-' . $attributes['itemsJustification'] ) : array(),
		isset( $attributes['align'] ) ? array( 'has-text-align-' . $attributes['align'] ) : array()
	);

	$class_attribute = sprintf( ' class="%s"', esc_attr( implode( ' ', $classes ) ) );
	$style_attribute = ( $colors['background']['inline_styles'] || $font_sizes['inline_styles'] )
	? sprintf( ' style="%s"', esc_attr( $colors['background']['inline_styles'] ) . esc_attr( $font_sizes['inline_styles'] ) )
	: '';

	// Add text colors on content for higher specificty.  Prevents theme override for has-background-color.
	$content_class_attribute = sprintf( ' class="wp-block-post-author__content %s"', esc_attr( implode( ' ', $colors['text']['css_classes'] ) ) );
	$content_style_attribute = ( $colors['text']['inline_styles'] )
	? sprintf( ' style="%s"', esc_attr( $colors['text']['inline_styles'] ) )
	: '';

	return sprintf( '<div %1$s %2$s>', $class_attribute, $style_attribute ) .
	( ! empty( $attributes['showAvatar'] ) ? '<div class="wp-block-post-author__avatar">' . $avatar . '</div>' : '' ) .
	sprintf( '<div %1$s %2$s>', $content_class_attribute, $content_style_attribute ) .
	( ! empty( $byline ) ? '<p class="wp-block-post-author__byline">' . $byline . '</p>' : '' ) .
	'<p class="wp-block-post-author__name">' . get_the_author_meta( 'display_name', $author_id ) . '</p>' .
	( ! empty( $attributes['showBio'] ) ? '<p class="wp-block-post-author__bio">' . get_the_author_meta( 'user_description', $author_id ) . '</p>' : '' ) .
	'</div>' .
	'</div>';
}

/**
 * Registers the `core/post-author` block on the server.
 */
function gutenberg_register_block_core_post_author() {
	register_block_type_from_metadata(
		__DIR__ . '/post-author',
		array(
			'render_callback' => 'gutenberg_render_block_core_post_author',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_post_author', 20 );
