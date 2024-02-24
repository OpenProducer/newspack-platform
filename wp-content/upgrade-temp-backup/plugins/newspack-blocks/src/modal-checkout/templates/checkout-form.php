<?php
/**
 * Checkout Form
 * Original template: https://github.com/woocommerce/woocommerce/blob/trunk/plugins/woocommerce/templates/checkout/form-checkout.php
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package Newspack_Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WooCommerce hooks.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variables.

$cart = WC()->cart;

$has_filled_billing = \Newspack_Blocks\Modal_Checkout::has_filled_required_fields( 'billing' );
$edit_billing       = ! $has_filled_billing || isset( $_REQUEST['edit_billing'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$form_action         = $edit_billing ? '#checkout' : wc_get_checkout_url();
$form_class          = 'checkout woocommerce-checkout';
$form_method         = $edit_billing ? 'get' : 'post';
$form_billing_fields = \Newspack_Blocks\Modal_Checkout::get_prefilled_fields();

$after_success_behavior     = filter_input( INPUT_GET, 'after_success_behavior', FILTER_SANITIZE_SPECIAL_CHARS );
$after_success_url          = filter_input( INPUT_GET, 'after_success_url', FILTER_SANITIZE_SPECIAL_CHARS );
$after_success_button_label = filter_input( INPUT_GET, 'after_success_button_label', FILTER_SANITIZE_SPECIAL_CHARS );

if ( $edit_billing ) {
	$form_class .= ' edit-billing';
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'newspack-blocks' ) ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	return;
}
?>

<?php if ( 1 === $cart->get_cart_contents_count() ) : ?>
	<div class="order-details-summary">
		<?php
		// Simplified output of order.
		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
		<h4>
				<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' ' . sprintf( '%s&nbsp;&times;', $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<strong>
				<?php
				echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</strong>
			<span>
				<?php
				echo apply_filters( 'woocommerce_cart_item_subtotal', $cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</span>
		</h4>
				<?php
			}
		}
		?>
	</div><!-- .order-details-summary -->
<?php endif; ?>

<form name="checkout" method="<?php echo esc_attr( $form_method ); ?>" class="<?php echo esc_attr( $form_class ); ?>" action="<?php echo esc_url( $form_action ); ?>" enctype="multipart/form-data">

	<input type="hidden" name="modal_checkout" value="1" />
	<input type="hidden" name="after_success_behavior" value="<?php echo esc_attr( $after_success_behavior ); ?>" />
	<input type="hidden" name="after_success_url" value="<?php echo esc_attr( $after_success_url ); ?>" />
	<input type="hidden" name="after_success_button_label" value="<?php echo esc_attr( $after_success_button_label ); ?>" />

	<?php
	if ( $edit_billing ) {
		wp_nonce_field( 'newspack_blocks_edit_billing', 'newspack_blocks_edit_billing_nonce' );
	}
	?>

	<div id="order-details-wrapper">
		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
		<h3 id="order_review_heading" class="screen-reader-text"><?php esc_html_e( 'Order Details', 'newspack-blocks' ); ?></h3>
		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
		<div id="order_review" class="woocommerce-checkout-review-order">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>
		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	</div><!-- .full-order-details -->

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound ?>

		<?php if ( $edit_billing ) : ?>
			<div class="checkout-billing">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
				<button type="button" class="button alt wp-element-button modal-continue"><?php esc_html_e( 'Continue', 'newspack-blocks' ); ?></button>
			</div>
		<?php else : ?>
			<div class="checkout-billing checkout-billing-summary">
				<div class="checkout-billing-summary__grid">
					<h3><?php esc_html_e( 'Billing details', 'newspack-blocks' ); ?></h3>
					<a href="<?php echo esc_url( add_query_arg( 'edit_billing', 1 ) ); ?>" class="edit-billing-link"><?php esc_html_e( 'Edit', 'newspack-blocks' ); ?></a>
				</div>
				<p>
					<?php echo esc_html( $form_billing_fields['email'] ); ?><br/>
					<?php echo WC()->countries->get_formatted_address( $form_billing_fields ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			</div>
			<?php foreach ( $form_billing_fields as $key => $value ) : ?>
				<?php if ( 'state' === $key ) : ?>

					<?php
					$country = isset( $form_billing_fields['country'] ) ? $form_billing_fields['country'] : WC()->customer->get_billing_country();
					$wc_states = WC()->countries->get_states( $country );
					?>
					<select style="display:none;" id="<?php echo esc_attr( 'billing_' . $key ); ?>" name="<?php echo esc_attr( 'billing_' . $key ); ?>" value="<?php echo esc_attr( $value ); ?>" >
						<?php foreach ( $wc_states as $key => $value ) { ?>
							<option <?php echo $key === $form_billing_fields['state'] ? 'selected' : ''; ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
						<?php } ?>
					</select>
					<span id="select2-billing_state-container" style="display:none;"></span>
				<?php else : ?>
					<input type="hidden" id="<?php echo esc_attr( 'billing_' . $key ); ?>" name="<?php echo esc_attr( 'billing_' . $key ); ?>" value="<?php echo esc_attr( $value ); ?>" />
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
				<div class="woocommerce-account-fields">
					<?php if ( ! $checkout->is_registration_required() ) : ?>

						<p class="form-row form-row-wide create-account">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'newspack-blocks' ); ?></span>
							</label>
						</p>

					<?php endif; ?>

					<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

					<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

						<div class="create-account">
							<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
								<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
							<?php endforeach; ?>
							<div class="clear"></div>
						</div>

					<?php endif; ?>

					<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
				</div>
			<?php endif; ?>

			<div class="after-customer-details">
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			</div>
		<?php endif; ?>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
