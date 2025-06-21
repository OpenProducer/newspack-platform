<?php
/**
 * Checkout Button Block Front-End Functions
 *
 * @package Newspack_Blocks
 */

namespace Newspack_Blocks\Checkout_Button;

use Newspack_Blocks;
use Newspack_Blocks\Modal_Checkout;
use Newspack_Blocks\Modal_Checkout\Checkout_Data;

/**
 * Register the block.
 */
function register_block() {
	register_block_type_from_metadata(
		__DIR__ . '/block.json',
		[
			'render_callback' => __NAMESPACE__ . '\\render_callback',
		]
	);
}
add_action( 'init', __NAMESPACE__ . '\\register_block' );

/**
 * Render the block.
 *
 * @param array $attributes Block attributes.
 *
 * @return string
 */
function render_callback( $attributes ) {
	$product_id   = $attributes['product'] ?? '';
	$variation_id = $attributes['variation'] ?? '';
	$text         = $attributes['text'] ?? '';

	if ( ( ! $product_id && ! $variation_id ) || ! $text ) {
		return '';
	}
	$product_id = $attributes['product'];
	if ( $attributes['is_variable'] && ! empty( $attributes['variation'] ) ) {
		$product_id = $attributes['variation'];
	}
	\Newspack_Blocks\Modal_Checkout::enqueue_modal( $product_id );
	\Newspack_Blocks::enqueue_view_assets( 'checkout-button' );

	$background_color           = $attributes['backgroundColor'] ?? '';
	$gradient                   = $attributes['gradient'] ?? '';
	$font_size                  = $attributes['fontSize'] ?? '';
	$style                      = $attributes['style'] ?? [];
	$text_align                 = $attributes['textAlign'] ?? '';
	$width                      = $attributes['width'] ?? '';
	$after_success_behavior     = $attributes['afterSuccessBehavior'] ?? '';
	$after_success_button_label = $attributes['afterSuccessButtonLabel'] ?? '';
	$after_success_url          = $attributes['afterSuccessURL'] ?? '';
	$is_variable                = $attributes['is_variable'];

	if ( $is_variable && $variation_id ) {
		$product_id = $variation_id;
	}

	// Generate the button.
	$button_color = '';
	// Get button color from style attribute since style engine doesn't seem to handle this.
	if ( isset( $style['elements']['link']['color']['text'] ) ) {
		$color = $style['elements']['link']['color']['text'];
		$color = explode( '|', $color );
		if ( isset( $color[2] ) ) {
			$button_color = $color[2];
		}
	}
	$button_styles = Newspack_Blocks::block_styles(
		$attributes,
		[
			$background_color ? 'background-color:' . esc_attr( $background_color ) . ';' : '',
			$font_size ? 'font-size:' . esc_attr( $font_size ) . ';' : '',
			$width ? 'width:' . esc_attr( $width ) . '%;' : '',
			$button_color ? 'color:' . esc_attr( $button_color ) . ';' : '',
		]
	);

	$button_classes = Newspack_Blocks::block_classes(
		'button',
		$attributes,
		[
			'wp-block-button__link',
			$background_color ? 'has-background has-' . esc_attr( $background_color ) . '-background-color' : '',
			$gradient ? 'has-background has-' . esc_attr( $gradient ) . '-gradient-background' : '',
			$text_align ? 'has-text-align-' . esc_attr( $text_align ) : '',
			isset( $style['border']['radius'] ) && $style['border']['radius'] === 0 ? 'no-border-radius' : '',
			$button_color ? 'has-text-color has-' . esc_attr( $button_color ) . '-color' : '',
		]
	);

	$button = sprintf(
		'<button class="%1$s" style="%2$s" type="submit">%3$s</button>',
		$button_classes,
		$button_styles,
		$text
	);

	// Generate hidden fields for the form.
	$hidden_fields = '<input type="hidden" name="newspack_checkout" value="1" />';
	if ( ! Modal_Checkout::has_unsupported_payment_gateway() ) {
		$hidden_fields .= $after_success_behavior ? '<input type="hidden" name="after_success_behavior" value="' . esc_attr( $after_success_behavior ) . '" />' : '';
		$hidden_fields .= $after_success_button_label ? '<input type="hidden" name="after_success_button_label" value="' . esc_attr( $after_success_button_label ) . '" />' : '';
		$hidden_fields .= $after_success_url ? '<input type="hidden" name="after_success_url" value="' . esc_attr( $after_success_url ) . '" />' : '';
	}

	ob_start();
	/**
	 * Fires when generating hidden fields for the checkout button.
	 *
	 * @param array $attributes Block attributes.
	 */
	do_action( 'newspack_blocks_checkout_button_hidden_fields', $attributes );
	$hidden_fields .= ob_get_clean();

	// Generate the form.
	if ( function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return '';
		}

		$price     = $product->get_price();
		$min_price = '';
		if ( ! empty( $attributes['price'] ) ) {
			// Default to the price set in the block attributes.
			$price = $attributes['price'];
		} elseif ( class_exists( '\WC_Name_Your_Price_Helpers' ) && \WC_Name_Your_Price_Helpers::is_nyp( $product_id ) ) {
			// Use suggested price if NYP is active and set for variation.
			$price     = \WC_Name_Your_Price_Helpers::get_suggested_price( $product_id );
			$min_price = \WC_Name_Your_Price_Helpers::get_minimum_price( $product_id );
		}

		$is_variable  = $attributes['is_variable'];
		$variation_id = $attributes['variation'];

		// Check if the button should be output: it needs a price, or needs to be a product with variations to pick.
		if ( $min_price && ! $price ) {
			$price = $min_price;
		}
		if ( ( ! $is_variable && ! $variation_id && ! $price ) || ( $variation_id && ! $price ) ) {
			return '';
		}

		$checkout_data = Checkout_Data::get_checkout_data( $product );
		$checkout_data['is_variable'] = $is_variable;

		$form = sprintf(
			'<form data-checkout="%1$s">%2$s %3$s</form>',
			esc_attr( wp_json_encode( $checkout_data ) ),
			$button,
			$hidden_fields
		);
	} else {
		$form = sprintf(
			'<form>%1$s %2$s</form>',
			$button,
			$hidden_fields
		);
	}

	$container_classes = Newspack_Blocks::block_classes(
		'checkout-button',
		$attributes,
		[
			'wp-block-button',
			( $font_size || isset( $style['typography']['fontSize'] ) ) ? 'has-custom-font-size' : '',
			$width ? ' has-custom-width wp-block-button__width-' . esc_attr( $width ) : '',
		]
	);
	return sprintf(
		'<div class="%1$s">%2$s</div>',
		$container_classes,
		$form
	);
}
