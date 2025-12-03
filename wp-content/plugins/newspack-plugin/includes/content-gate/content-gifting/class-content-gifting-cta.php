<?php
/**
 * Newspack Content Gifting Call-to-action functionality.
 *
 * @package Newspack
 */

namespace Newspack;

/**
 * Content Gifting Call-to-action class.
 */
class Content_Gifting_CTA {
	/**
	 * Initialize hooks.
	 */
	public static function init() {
		add_action( 'wp_footer', [ __CLASS__, 'print_cta' ] );
		add_filter( 'body_class', [ __CLASS__, 'filter_body_class' ] );
		add_filter( 'newspack_ads_placement_data', [ __CLASS__, 'filter_ads_placement_data' ], 10, 2 );
	}

	/**
	 * Disable the sticky footer ad placement when rendering
	 * the gifted article CTA.
	 *
	 * @param array  $data          The ads placement data.
	 * @param string $placement_key The placement key.
	 *
	 * @return array The filtered ads placement data.
	 */
	public static function filter_ads_placement_data( $data, $placement_key ) {
		if ( ! Content_Gifting::is_gifted_post() ) {
			return $data;
		}
		if ( $placement_key === 'sticky' ) {
			$data['enabled'] = false;
		}
		return $data;
	}

	/**
	 * Get cta label.
	 *
	 * @return string The cta label.
	 */
	public static function get_cta_label() {
		return (string) get_option( 'newspack_content_gifting_cta_label', __( 'This article has been gifted to you by someone who values great journalism.', 'newspack-plugin' ) );
	}

	/**
	 * Set cta label.
	 *
	 * @param string $label The cta label.
	 *
	 * @return void
	 */
	public static function set_cta_label( $label ) {
		update_option( 'newspack_content_gifting_cta_label', $label );
	}

	/**
	 * Get cta url.
	 *
	 * @return string The cta url.
	 */
	public static function get_cta_url() {
		return (string) get_option( 'newspack_content_gifting_cta_url', '' );
	}

	/**
	 * Set cta url.
	 *
	 * @param string $url The cta url.
	 *
	 * @return void
	 */
	public static function set_cta_url( $url ) {
		update_option( 'newspack_content_gifting_cta_url', $url );
	}

	/**
	 * Get button label.
	 *
	 * @return string The button label.
	 */
	public static function get_button_label() {
		return (string) get_option( 'newspack_content_gifting_button_label', __( 'Subscribe now', 'newspack-plugin' ) );
	}

	/**
	 * Set button label.
	 *
	 * @param string $label The button label.
	 *
	 * @return void
	 */
	public static function set_button_label( $label ) {
		update_option( 'newspack_content_gifting_button_label', $label );
	}

	/**
	 * Print the subscribe button.
	 */
	public static function print_subscribe_button() {
		if ( ! class_exists( 'Newspack_Blocks' ) || ! class_exists( 'Newspack_Blocks\Modal_Checkout' ) || ! class_exists( 'Newspack_Blocks\Modal_Checkout\Checkout_Data' ) || ! function_exists( 'wc_get_product' ) ) {
			return;
		}
		$button_label = self::get_button_label();
		$button_class = 'dark' === self::get_style() ? 'newspack-ui__button--primary-light' : 'newspack-ui__button--accent';

		$cta_url = self::get_cta_url();
		if ( $cta_url ) {
			?>
			<a href="<?php echo esc_url( $cta_url ); ?>" class="newspack-ui__button newspack-ui__button--x-small <?php echo esc_attr( $button_class ); ?>"><?php echo esc_html( $button_label ); ?></a>
			<?php
			return;
		}

		// If CTA url is not provided, try a modal checkout using the primary subscription tier product.
		$product = Subscriptions_Tiers::get_primary_subscription_tier_product();
		if ( ! $product ) {
			return;
		}
		\Newspack_Blocks\Modal_Checkout::enqueue_modal( $product->get_id() );
		\Newspack_Blocks::enqueue_view_assets( 'checkout-button' );
		$checkout_data = \Newspack_Blocks\Modal_Checkout\Checkout_Data::get_checkout_data( $product );
		?>
		<div class="wp-block-newspack-blocks-checkout-button">
			<form data-checkout="<?php echo esc_attr( wp_json_encode( $checkout_data ) ); ?>" target="newspack_modal_checkout_iframe">
				<input type="hidden" name="newspack_checkout" value="1" />
				<input type="hidden" name="modal_checkout" value="1" />
				<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>" />
				<button type="submit" class="newspack-ui__button newspack-ui__button--x-small <?php echo esc_attr( $button_class ); ?>"><?php echo esc_html( $button_label ); ?></button>
			</form>
		</div>
		<?php
	}

	/**
	 * Get CTA style.
	 *
	 * @return string The style, 'light' or 'dark'.
	 */
	public static function get_style() {
		$style = (string) get_option( 'newspack_content_gifting_cta_style', 'light' );
		return in_array( $style, [ 'light', 'dark' ], true ) ? $style : 'light';
	}

	/**
	 * Set CTA style.
	 *
	 * @param string $style The style value.
	 *
	 * @return void
	 */
	public static function set_style( $style ) {
		$style = in_array( $style, [ 'light', 'dark' ], true ) ? $style : 'light';
		update_option( 'newspack_content_gifting_cta_style', $style );
	}

	/**
	 * Hook the cta.
	 */
	public static function print_cta() {
		if ( ! Content_Gifting::is_gifted_post() ) {
			return;
		}
		// Don't render CTA if user already has access to the post.
		if ( ! Content_Gate::is_post_restricted() ) {
			return;
		}
		$style_class = sprintf( 'is-style-%s', self::get_style() );
		?>
		<div class="newspack-ui">
			<div class="banner newspack-content-gifting__cta <?php echo esc_attr( $style_class ); ?>">
				<div class="wrapper newspack-content-gifting__cta__content">
					<div class="newspack-ui__font--s">
						<?php echo esc_html( self::get_cta_label() ); ?>
						<?php if ( ! is_user_logged_in() ) : ?>
							<div class="newspack-ui__font--xs newspack-content-gifting__cta__content__links">
								<?php if ( Metering::has_metering( get_the_ID() ) ) : ?>
									<a href="#register_modal"><?php echo esc_html( __( 'Create an account', 'newspack-plugin' ) ); ?></a>
								<?php else : ?>
									<a href="#signin_modal"><?php echo esc_html( __( 'Sign in to an existing account', 'newspack-plugin' ) ); ?></a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
					<?php self::print_subscribe_button(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Filter the body class.
	 *
	 * @param array $classes The body classes.
	 *
	 * @return array The filtered body classes.
	 */
	public static function filter_body_class( $classes ) {
		if ( Content_Gifting::is_gifted_post() ) {
			$classes[] = 'newspack-is-gifted-post';
		}
		return $classes;
	}
}
Content_Gifting_CTA::init();
