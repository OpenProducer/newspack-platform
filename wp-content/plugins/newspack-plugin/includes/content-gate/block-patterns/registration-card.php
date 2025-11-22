<?php
/**
 * Memberships Registration Card Pattern.
 *
 * @package Newspack
 */

?>
<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Registration', 'newspack' ); ?>"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|80","right":"var:preset|spacing|80"}},"border":{"radius":"6px","width":"1px"}},"borderColor":"base-3","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-border-color has-base-3-border-color" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--80)">

	<!-- wp:heading {"textAlign":"center","level":3} -->
	<h3 class="wp-block-heading has-text-align-center">
		<?php esc_html_e( 'Register to continue reading', 'newspack' ); ?>
	</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center"} -->
	<p class="has-text-align-center">
		<?php echo wp_kses( __( 'Register now and get <strong>3 free articles every week.</strong>', 'newspack' ), 'post' ); ?>
	</p>
	<!-- /wp:paragraph -->

	<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Buttons', 'newspack' ); ?>"},"layout":{"type":"constrained","contentSize":"300px"}} -->
	<div class="wp-block-group">
		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap","orientation":"vertical"},"style":{"spacing":{"blockGap":"12px"}}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"width":100} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100">
				<a class="wp-block-button__link wp-element-button" href="#register_modal">
					<?php esc_html_e( 'Register for free', 'newspack' ); ?>
				</a>
			</div>
			<!-- /wp:button -->

			<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100,"style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100">
				<a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background has-link-color wp-element-button" href="#signin_modal">
					<?php esc_html_e( 'Sign in to an existing account', 'newspack' ); ?>
				</a>
			</div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
