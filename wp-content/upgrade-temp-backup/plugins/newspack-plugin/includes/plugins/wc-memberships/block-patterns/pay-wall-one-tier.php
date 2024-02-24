<?php
/**
 * Memberships Pay Wall with One Tier Pattern.
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

<!-- wp:group { "style":{ "spacing":{"padding":{"top":"var:preset|spacing|70","right":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70"}} },"className":"is-style-border newspack-content-gate","layout":{"type":"constrained"} } -->
<div class="wp-block-group is-style-border newspack-content-gate" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">

<!-- wp:heading { "textAlign":"center","style":{"typography":{"fontSize":"28px"}} } -->
	<h2 class="wp-block-heading has-text-align-center" style="font-size:28px">
		Subscribe to continue reading
	</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph { "align":"center","style":{"typography":{"fontSize":"13px"}},"className":"newspack-sign-in" } -->
	<p class="has-text-align-center newspack-sign-in" style="font-size:13px">
		<?php esc_html_e( 'Already have an account?', 'newspack' ); ?>
		<a href="#signin_modal"><?php esc_html_e( 'Sign In', 'newspack' ); ?></a>.
	</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column {"width":"15%"} -->
		<div class="wp-block-column" style="flex-basis:15%"></div>
		<!-- /wp:column -->

		<!-- wp:column {"width":""} -->
		<div class="wp-block-column">
			<!-- wp:group {"backgroundColor":"light-gray","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-light-gray-background-color has-background">
				<!-- wp:list {"style":{"typography":{"fontSize":"15px"}},"className":"newspack-feature-list"} -->
				<ul class="newspack-feature-list" style="font-size:15px">
					<?php foreach ( $features as $feature ) : ?>
						<li><?php echo esc_html( $feature ); ?></li>
					<?php endforeach; ?>
				</ul>
				<!-- /wp:list -->
				<!-- wp:newspack-blocks/checkout-button { "text":"Subscribe for only $5/month!","align":"wide","style":{"color":{"background":"#cc1818"}} } /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"15%"} -->
		<div class="wp-block-column" style="flex-basis:15%"></div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
