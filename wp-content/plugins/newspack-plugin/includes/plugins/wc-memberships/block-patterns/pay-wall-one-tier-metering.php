<?php
/**
 * Memberships Paywall with One Tier and Metering Pattern.
 *
 * @package Newspack
 */

?>
<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->

<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Wall', 'newspack' ); ?>"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|80","right":"var:preset|spacing|80"}},"border":{"radius":"6px","width":"1px"}},"borderColor":"base-3","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-border-color has-base-3-border-color" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--80)">

	<!-- wp:heading {"textAlign":"center","level":3} -->
	<h3 class="wp-block-heading has-text-align-center">
		<?php esc_html_e( "You've read all free articles for the week", 'newspack' ); ?>
	</h3>
	<!-- /wp:heading -->

	<!-- wp:columns {"metadata":{"name":"<?php esc_html_e( 'Content', 'newspack' ); ?>"},"className":"is-style-borders","fontSize":"small"} -->
	<div class="wp-block-columns is-style-borders has-small-font-size">

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center">
				<?php echo wp_kses( __( 'Register now and get<br><strong>3 free articles every week.</strong>', 'newspack' ), 'post' ); ?>
			</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"width":100,"className":"is-style-outline"} -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline">
					<a class="wp-block-button__link wp-element-button" href="#register_modal">
						<?php esc_html_e( 'Register for free', 'newspack' ); ?>
					</a>
				</div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center">
				<?php echo wp_kses( __( 'Unlimited access to our<br><strong>daily content and archives</strong>.', 'newspack' ), 'post' ); ?>
			</p>
			<!-- /wp:paragraph -->

			<!-- wp:newspack-blocks/checkout-button {"text":"<?php esc_html_e( 'Subscribe for $5/month', 'newspack' ); ?>","width":100,"align":"wide"} /-->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} -->
		<div class="wp-block-button">
			<a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background has-link-color wp-element-button" href="#signin_modal">
				<?php esc_html_e( 'Sign in to an existing account', 'newspack' ); ?>
			</a>
		</div>
		<!-- /wp:button -->
	</div><!-- /wp:buttons -->

</div>
<!-- /wp:group -->
