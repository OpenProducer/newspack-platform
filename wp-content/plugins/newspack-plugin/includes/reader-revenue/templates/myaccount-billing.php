<?php
/**
 * My Account Payments.
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

?>

<?php
if ( $error_message ) :
	?>

	<div class="newspack-wc-message newspack-wc-message--error">
		<p><?php echo esc_html( $error_message ); ?></p>
	</div>

	<?php
else :
	?>

	<a href="<?php echo esc_url( $stripe_billing_portal_url ); ?>">
		<?php echo esc_html__( 'Update payment details, recurring payments, and view billing history', 'newspack' ); ?>
	</a>

	<?php
endif;
