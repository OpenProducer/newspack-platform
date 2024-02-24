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
	const CUSTOM_FIELD_NAME = 'newspack-wc-pay-fees';

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		\add_filter( 'woocommerce_checkout_fields', [ __CLASS__, 'add_checkout_fields' ] );
		\add_action( 'woocommerce_checkout_update_order_review', [ __CLASS__, 'persist_fee_selection' ] );
		\add_action( 'woocommerce_cart_calculate_fees', [ __CLASS__, 'add_transaction_fee' ] );
		\add_action( 'woocommerce_checkout_order_processed', [ __CLASS__, 'add_order_note' ], 1, 3 );
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
	 * Persist the transaction fee selection in the Woo sesion when updating the
	 * order review.
	 *
	 * @param string $posted_data Posted posted_data.
	 */
	public static function persist_fee_selection( $posted_data ) {
		$data = [];
		parse_str( $posted_data, $data );
		if ( self::should_apply_fee( $data ) ) {
			\WC()->session->set( self::CUSTOM_FIELD_NAME, 1 );
		} else {
			\WC()->session->set( self::CUSTOM_FIELD_NAME, 0 );
		}
	}

	/**
	 * Add fee.
	 *
	 * @param \WC_Cart $cart Cart object.
	 */
	public static function add_transaction_fee( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		if ( ! \WC()->session->get( self::CUSTOM_FIELD_NAME ) ) {
			return;
		}
		$cart->add_fee(
			sprintf(
				// Translators: %s is the fee percentage.
				__( 'Transaction fee (%s)', 'newspack-plugin' ),
				self::get_cart_fee_display_value()
			),
			self::get_cart_fee_value()
		);
	}

	/**
	 * Add an order note.
	 *
	 * @param int       $order_id Order ID.
	 * @param array     $posted_data Posted data.
	 * @param \WC_Order $order Order object.
	 */
	public static function add_order_note( $order_id, $posted_data, $order ) {
		if ( \WC()->session->get( self::CUSTOM_FIELD_NAME ) ) {
			$order->add_order_note( __( 'The donor opted to cover Stripe\'s transaction fee.', 'newspack-plugin' ) );
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
		return true;
	}

	/**
	 * Whether to apply the fee in the current request.
	 *
	 * @param array $data Posted data.
	 *
	 * @return bool
	 */
	private static function should_apply_fee( $data ) {
		if ( ! self::should_allow_covering_fees() ) {
			return false;
		}
		if ( ! isset( $data['payment_method'] ) || 'stripe' !== $data['payment_method'] ) {
			return false;
		}
		if ( ! isset( $data[ self::CUSTOM_FIELD_NAME ] ) || '1' !== $data[ self::CUSTOM_FIELD_NAME ] ) {
			return false;
		}
		return true;
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
					id="<?php echo esc_attr( self::CUSTOM_FIELD_NAME ); ?>"
					name="<?php echo esc_attr( self::CUSTOM_FIELD_NAME ); ?>"
					type="checkbox"
					style="margin-right: 8px;"
					value="1"
					<?php if ( get_option( 'newspack_donations_allow_covering_fees_default', false ) ) : ?>
						checked
					<?php endif; ?>
				/>
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
							esc_html( self::get_cart_fee_display_value() ),
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
	 * Print the checkout helper JS script.
	 */
	public static function print_checkout_helper_script() {
		if ( ! self::should_allow_covering_fees() ) {
			return;
		}
		$handler = 'newspack-wc-cover-fees';
		wp_enqueue_script(
			$handler,
			\Newspack\Newspack::plugin_url() . '/dist/other-scripts/wc-cover-fees.js',
			[ 'jquery' ],
			NEWSPACK_PLUGIN_VERSION,
			[ 'in_footer' => true ]
		);
		wp_localize_script(
			$handler,
			'newspack_wc_cover_fees',
			[
				'custom_field_name' => self::CUSTOM_FIELD_NAME,
			]
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
	 *
	 * @param float $subtotal The subtotal to calculate the fee for.
	 */
	public static function get_fee_display_value( $subtotal ) {
		$total = self::get_total_with_fee( $subtotal );
		// Just one decimal place, please.
		$flat_percentage = (float) number_format( ( ( $total - $subtotal ) * 100 ) / $subtotal, 1 );
		return $flat_percentage . '%';
	}

	/**
	 * Get the fee value.
	 *
	 * @param float $subtotal The subtotal to calculate the fee for.
	 */
	public static function get_fee_value( $subtotal ) {
		$fee_multiplier = self::get_stripe_fee_multiplier_value();
		$fee_static     = self::get_stripe_fee_static_value();
		$fee            = ( ( ( $subtotal + $fee_static ) / ( 100 - $fee_multiplier ) ) * 100 - $subtotal );
		return $fee;
	}

	/**
	 * Calculate the adjusted total, taking the fee into account.
	 *
	 * @param float $subtotal The subtotal to calculate the total for.
	 * @return float
	 */
	public static function get_total_with_fee( $subtotal ) {
		return $subtotal + self::get_fee_value( $subtotal );
	}

	/**
	 * Get the fee value for the current cart.
	 *
	 * @return float
	 */
	public static function get_cart_fee_value() {
		return self::get_fee_value( WC()->cart->get_subtotal() );
	}

	/**
	 * Get the fee display value for the current cart.
	 *
	 * @return string
	 */
	public static function get_cart_fee_display_value() {
		return self::get_fee_display_value( WC()->cart->get_subtotal() );
	}

	/**
	 * Get the total with fee for the current cart.
	 *
	 * @return float
	 */
	public static function get_cart_total_with_fee() {
		return self::get_total_with_fee( WC()->cart->get_subtotal() );
	}
}
WooCommerce_Cover_Fees::init();
