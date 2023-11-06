<?php
/**
 * Login form.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce by default doesn't allow order details to be shown
 * if the order is completed using a customer email address that doesn't match
 * the currently logged in user.
 *
 * Details: https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/includes/shortcodes/class-wc-shortcode-checkout.php#L302-L321
 *
 * For Newspack sites, we don't want to emphasize user account flows outside of
 * RAS. This custom login template replaces the login form that appears on the
 * order-received.php template with an order details summary so the experience
 * matches whether or not the email address used is already associated with an
 * existing customer account.
 */
function newspack_blocks_replace_login_with_order_summary() {
	$order    = isset( $_GET['order_id'] ) ? \wc_get_order( \absint( \wp_unslash( $_GET['order_id'] ) ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$key      = isset( $_GET['key'] ) ? \wc_clean( \sanitize_text_field( \wp_unslash( $_GET['key'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$is_valid = $order && is_a( $order, 'WC_Order' ) && hash_equals( $order->get_order_key(), $key ); // Validate order key to prevent CSRF.
	?>

	<div class="woocommerce-order">
		<?php if ( $is_valid ) : ?>

		<h4><?php esc_html_e( 'Summary', 'newspack-blocks' ); ?></h4>

		<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

			<li class="woocommerce-order-overview__date date">
				<?php esc_html_e( 'Date:', 'newspack-blocks' ); ?>
				<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<?php if ( $order->get_billing_email() ) : ?>
				<li class="woocommerce-order-overview__email email">
					<?php esc_html_e( 'Email:', 'newspack-blocks' ); ?>
					<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>
			<?php endif; ?>

			<li class="woocommerce-order-overview__total total">
				<?php esc_html_e( 'Total:', 'newspack-blocks' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

			<?php if ( $order->get_payment_method_title() ) : ?>
				<li class="woocommerce-order-overview__payment-method method">
					<?php esc_html_e( 'Payment method:', 'newspack-blocks' ); ?>
					<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
				</li>
			<?php endif; ?>

			<li class="woocommerce-order-overview__order order">
				<?php esc_html_e( 'Transaction:', 'newspack-blocks' ); ?>
				<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</li>

		</ul>
		<?php else : ?>
		<h4><?php esc_html_e( 'Summary', 'newspack-blocks' ); ?></h4>
		<p>
			<?php
			echo wp_kses_post(
				sprintf(
					// Translators: URL to My Account.
					__( 'Please log in to <a href="%s">My Account</a> to see order details.', 'newspack-blocks' ),
					\wc_get_account_endpoint_url( 'dashboard' )
				),
				'newspack-blocks'
			);
			?>
		</p>
		<?php endif; ?>
	</div>
	<?php
}

newspack_blocks_replace_login_with_order_summary();
