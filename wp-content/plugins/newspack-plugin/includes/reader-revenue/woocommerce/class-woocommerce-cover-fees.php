<?php
/**
 * Add ability to cover transaction fees for an order.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Order UTM class.
 */
class WooCommerce_Cover_Fees {
	const CUSTOM_FIELD_NAME  = 'newspack-wc-pay-fees';
	const PRICE_ELEMENT_ID   = 'newspack-wc-price';
	const WC_ORDER_META_NAME = 'newspack_donor_covers_fees';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		\add_filter( 'woocommerce_checkout_fields', [ __CLASS__, 'add_checkout_fields' ] );
		\add_filter( 'woocommerce_checkout_create_order', [ __CLASS__, 'set_total_with_fees' ], 1, 2 );
		\add_action( 'woocommerce_checkout_order_processed', [ __CLASS__, 'add_order_note' ], 1, 3 );
		\add_filter( 'wc_price', [ __CLASS__, 'amend_price_markup' ], 1, 2 );
		\add_action( 'wc_stripe_payment_fields_stripe', [ __CLASS__, 'render_stripe_input' ] );
		\add_action( 'wp_enqueue_scripts', [ __CLASS__, 'print_checkout_helper_script' ] );
	}

	/**
	 * Add the "cover fees" checkout field.
	 *
	 * @param array $fields Checkout fields.
	 *
	 * @return array
	 */
	public static function add_checkout_fields( $fields ) {
		if ( ! self::should_allow_covering_fees() ) {
			return $fields;
		}
		$fields['newspack'] = [
			self::CUSTOM_FIELD_NAME => [
				'type'    => 'checkbox',
				'default' => intval( get_option( 'newspack_donations_allow_covering_fees_default', false ) ),
			],
		];
		return $fields;
	}

	/**
	 * Set order total, taking the fee into account.
	 *
	 * @param \WC_Order $order Order object.
	 * @param array     $data  Posted data.
	 *
	 * @return \WC_Order
	 */
	public static function set_total_with_fees( $order, $data ) {
		if ( isset( $data[ self::CUSTOM_FIELD_NAME ] ) && 1 === $data[ self::CUSTOM_FIELD_NAME ] ) {
			$order->add_meta_data( self::WC_ORDER_META_NAME, 1 );
			$order->set_total( self::get_total_with_fee( $order->get_total() ) );
		}
		return $order;
	}

	/**
	 * Add an order note.
	 *
	 * @param int       $order_id Order ID.
	 * @param array     $posted_data Posted data.
	 * @param \WC_Order $order Order object.
	 */
	public static function add_order_note( $order_id, $posted_data, $order ) {
		if ( 1 === intval( $order->get_meta_data( self::WC_ORDER_META_NAME ) ) ) {
			$order->add_order_note( __( 'The donor opted to cover Stripe\'s transaction fee. The total amount will be updated.', 'newspack-plugin' ) );
		}
	}

	/**
	 * Should this feature be active?
	 *
	 * Some of this code is taken from Newspack Blocks, but can't be reused directly because
	 * WC renders the checkout form before Blocks' code is available.
	 */
	private static function should_allow_covering_fees() {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return false;
		}
		if ( ! Donations::is_donation_cart() ) {
			// Only allow covering fees for donations.
			return false;
		}
		if ( 0 < count( WC()->cart->get_coupon_discount_totals() ) ) {
			// If the checkout has coupons applied, bail. This can be develped in the future,
			// but at this point handling coupons + covering fees is an edge case.
			return false;
		}
		if ( true !== boolval( get_option( 'newspack_donations_allow_covering_fees' ) ) ) {
			return false;
		}
		if ( \Newspack_Blocks\Modal_Checkout::is_modal_checkout() ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}
		if ( isset( $_POST['post_data'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			parse_str( \sanitize_text_field( \wp_unslash( $_POST['post_data'] ) ), $post_data ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( isset( $post_data['modal_checkout'] ) && 1 === intval( $post_data['modal_checkout'] ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Render the "cover fees" input for Stripe.
	 */
	public static function render_stripe_input() {
		if ( ! self::should_allow_covering_fees() ) {
			return;
		}
		?>
		<fieldset>
			<p class="form-row newspack-cover-fees" style="display: flex;">
				<input
					id=<?php echo esc_attr( self::CUSTOM_FIELD_NAME ); ?>
					name=<?php echo esc_attr( self::CUSTOM_FIELD_NAME ); ?>
					type="checkbox"
					value="true"
					style="margin-right: 8px;"
					onchange="newspackHandleCoverFees(this)"
					<?php if ( get_option( 'newspack_donations_allow_covering_fees_default', false ) ) : ?>
						checked
					<?php endif; ?>
				>
				<label for=<?php echo esc_attr( self::CUSTOM_FIELD_NAME ); ?> style="display:inline;">
					<?php
					$custom_message = get_option( 'newspack_donations_allow_covering_fees_label', '' );
					if ( ! empty( $custom_message ) ) {
						echo esc_html( $custom_message );
					} else {
						printf(
							// Translators: %s is the possessive form of the site name.
							esc_html__(
								'I’d like to cover the %1$s transaction fee to ensure my full donation goes towards %2$s mission.',
								'newspack-plugin'
							),
							esc_html( self::get_fee_display_value() ),
							esc_html( self::get_possessive( get_option( 'blogname' ) ) )
						);
					}
					?>
				</label>
			</p>
		</fieldset>
		<?php
	}

	/**
	 * Get possessive form of the given string. Proper nouns ending in S should not have a trailing S.
	 *
	 * @param string $string String to modify.
	 * @return string Modified string.
	 */
	private static function get_possessive( $string ) {
		return $string . '’' . ( 's' !== $string[ strlen( $string ) - 1 ] ? 's' : '' );
	}

	/**
	 * Amend the price markup so it's possible to manipulate it via JS.
	 *
	 * @param string $html  Price HTML.
	 * @param string $price Price.
	 */
	public static function amend_price_markup( $html, $price ) {
		if ( ! self::should_allow_covering_fees() ) {
			return $html;
		}
		return str_replace( $price, '<span id="' . self::PRICE_ELEMENT_ID . '">' . $price . '</span>', $html );
	}

	/**
	 * Print the checkout helper JS script.
	 */
	public static function print_checkout_helper_script() {
		if ( ! self::should_allow_covering_fees() ) {
			return;
		}
		$handler = 'newspack-wc-modal-checkout-helper';
		wp_register_script( $handler, '', [], false, [ 'in_footer' => true ] ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NoExplicitVersion
		wp_enqueue_script( $handler );
		$original_price          = WC()->cart->total;
		$price_with_fee          = number_format( self::get_total_with_fee( $original_price ), 2 );
		$has_coupons_available   = WC()->cart->get_coupon_discount_totals();
		$coupons_handling_script = '';
		if ( \wc_coupons_enabled() ) {
			// Handle an edge case where the price was updated in the UI, and then a coupon was applied.
			// In this case, the price has to be reverted to the original value, since covering fees
			// is not supported with coupons.
			$coupons_handling_script = 'setInterval(function(){
				if(document.querySelector(".woocommerce-remove-coupon")){
					document.getElementById( "' . self::PRICE_ELEMENT_ID . '" ).textContent =  "' . $original_price . '";
				}
			}, 1000);';
		}
		wp_add_inline_script(
			$handler,
			'const form = document.querySelector(\'form[name="checkout"]\');
			if ( form ) {
				form.addEventListener(\'change\', function( e ){
					const inputEl = document.getElementById( "' . self::CUSTOM_FIELD_NAME . '" );
					if( e.target.name === "payment_method" && e.target.value !== "stripe" && inputEl.checked ){
						inputEl.checked = false;
						newspackHandleCoverFees(inputEl);
					}
				});
			}
			function newspackHandleCoverFees(inputEl) {
				document.getElementById( "' . self::PRICE_ELEMENT_ID . '" ).textContent = inputEl.checked ? "' . $price_with_fee . '" : "' . $original_price . '";
			};' . $coupons_handling_script
		);
	}

	/**
	 * Get the fee multiplier value.
	 */
	private static function get_stripe_fee_multiplier_value() {
		return floatval( get_option( 'newspack_blocks_donate_fee_multiplier', '2.9' ) );
	}

	/**
	 * Get the fee static portion value.
	 */
	private static function get_stripe_fee_static_value() {
		return floatval( get_option( 'newspack_blocks_donate_fee_static', '0.3' ) );
	}

	/**
	 * Get the fee display value.
	 */
	public static function get_fee_display_value() {
		$price = floatval( WC()->cart->total );
		$total = self::get_total_with_fee( $price );
		// Just one decimal place, please.
		$flat_percentage = (float) number_format( ( ( $total - $price ) * 100 ) / $price, 1 );
		return $flat_percentage . '%';
	}

	/**
	 * Calculate the adjusted total, taking the fee into account.
	 *
	 * @param float $total Total amount.
	 *
	 * @return float
	 */
	private static function get_total_with_fee( $total ) {
		$fee_multiplier = self::get_stripe_fee_multiplier_value();
		$fee_static     = self::get_stripe_fee_static_value();
		$fee            = ( ( ( $total + $fee_static ) / ( 100 - $fee_multiplier ) ) * 100 - $total );
		return $total + $fee;
	}
}
WooCommerce_Cover_Fees::init();
