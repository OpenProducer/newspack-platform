<?php
/**
 * Payment method fields
 *
 * @see https://woo.com/document/template-structure/
 * @package Newspack_Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<label class="newspack-ui__input-card" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
		<span>
			<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
			<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
				<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
					<?php $gateway->payment_fields(); ?>
					<?php do_action( 'newspack_blocks_after_payment_fields', $gateway->id ); ?>
				</div>
			<?php endif; ?>
		</span>
	</label>
</li>
