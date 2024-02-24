<?php
/**
 * Memberships Pay Wall with One Tier and Metering Pattern.
 *
 * @package Newspack
 */

?>
<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->

<!-- wp:group { "style":{ "spacing":{ "padding":{ "top":"var:preset|spacing|70","right":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70" } } },"className":"is-style-border newspack-content-gate","layout":{ "type":"constrained" } } -->
<div class="wp-block-group is-style-border newspack-content-gate" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">

	<!-- wp:paragraph {"align":"center","fontSize":"normal"} -->
	<p class="has-text-align-center has-normal-font-size">
		<?php esc_html_e( "You've read all free articles for the week.", 'newspack' ); ?>
	</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns { "style":{ "spacing":{ "padding":{ "top":"var:preset|spacing|60","right":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60" } } },"backgroundColor":"light-gray","className":"is-style-borders" } -->
	<div class="wp-block-columns is-style-borders has-light-gray-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
			<p class="has-text-align-center has-small-font-size">
				<?php echo wp_kses( __( 'Register now and get<br><strong>3 free articles every week.</strong>', 'newspack' ), 'post' ); ?>
			</p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"secondary-variation","width":100} -->
				<div class="wp-block-button has-custom-width wp-block-button__width-100">
					<a class="wp-block-button__link has-secondary-variation-background-color has-background wp-element-button" href="#register_modal">
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
			<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
			<p class="has-text-align-center has-small-font-size">
				<?php echo wp_kses( __( 'Unlimited access to our<br><strong>daily content and archives</strong>.', 'newspack' ), 'post' ); ?>
			</p>
			<!-- /wp:paragraph -->

			<!-- wp:newspack-blocks/checkout-button { "text":"<?php esc_attr_e( 'Subscribe for $5/month!', 'newspack' ); ?>","align":"wide","style":{"color":{"background":"#cc1818"}} } /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:paragraph { "align":"center","style":{"typography":{"fontSize":"13px"}},"className":"newspack-sign-in" } -->
	<p class="has-text-align-center newspack-sign-in" style="font-size:13px">
		<?php esc_html_e( 'Already have an account?', 'newspack' ); ?>
		<a href="#signin_modal"><?php esc_html_e( 'Sign In', 'newspack' ); ?></a>.
	</p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
