<?php
/**
 * Memberships Paywall with One Tier Pattern.
 *
 * @package Newspack
 */

$features = [
	__( 'Unlimited access to our content and archive', 'newspack' ),
	__( 'Puzzles and recipes', 'newspack' ),
	__( 'Exclusive podcasts and newsletters', 'newspack' ),
];

?>
<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->

<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Wall', 'newspack' ); ?>"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","right":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|80"}},"border":{"radius":"6px","width":"1px"}},"borderColor":"base-3","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-border-color has-base-3-border-color" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--80)">

	<!-- wp:heading {"textAlign":"center","level":3} -->
	<h3 class="wp-block-heading has-text-align-center">
		<?php esc_html_e( 'Subscribe to continue reading', 'newspack' ); ?>
	</h3>
	<!-- /wp:heading -->

	<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Content', 'newspack' ); ?>"},"layout":{"type":"constrained","contentSize":"300px"}} -->
	<div class="wp-block-group">

		<!-- wp:list {"className":"is-style-checked","fontSize":"small"} -->
		<ul class="is-style-checked has-small-font-size">
			<?php foreach ( $features as $feature ) : ?>
				<!-- wp:list-item -->
				<li><?php echo esc_html( $feature ); ?></li>
				<!-- /wp:list-item -->
			<?php endforeach; ?>
		</ul>
		<!-- /wp:list -->

		<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Buttons', 'newspack' ); ?>"},"style":{"spacing":{"blockGap":"12px"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group">

			<!-- wp:newspack-blocks/checkout-button {"text":"<?php esc_html_e( 'Subscribe for only $5/month', 'newspack' ); ?>","width":100,"align":"center"} /-->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
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

</div>
<!-- /wp:group -->
