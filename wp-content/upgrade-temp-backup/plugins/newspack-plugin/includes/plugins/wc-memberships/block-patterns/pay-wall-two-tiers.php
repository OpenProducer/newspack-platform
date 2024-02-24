<?php
/**
 * Memberships Pay Wall with Two Tiers Pattern.
 *
 * @package Newspack
 */

$member_features = [
	__( 'Unlimited access to our content', 'newspack' ),
	__( 'Puzzles and recipes', 'newspack' ),
	__( 'Support quality journalism', 'newspack' ),
];

$patron_features = [
	__( 'Everything Members get', 'newspack' ),
	__( 'Exclusive podcasts and newsletters', 'newspack' ),
	__( 'Our appreciation and love', 'newspack' ),
]

?>
<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->

<!-- wp:group { "style":{ "spacing":{"padding":{"top":"var:preset|spacing|70","right":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70"}} },"className":"is-style-border newspack-content-gate","layout":{"type":"constrained"} } -->
<div class="wp-block-group is-style-border newspack-content-gate" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">

	<!-- wp:heading { "textAlign":"center","style":{"typography":{"fontSize":"28px"}} } -->
	<h2 class="wp-block-heading has-text-align-center" style="font-size:28px">
		<?php esc_html_e( 'Choose an option to continue reading', 'newspack' ); ?>
	</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph { "align":"center","style":{"typography":{"fontSize":"13px"}},"className":"newspack-sign-in" } -->
	<p class="has-text-align-center newspack-sign-in" style="font-size:13px">
		<?php esc_html_e( 'Already have an account?', 'newspack' ); ?>
		<a href="#signin_modal"><?php esc_html_e( 'Sign In', 'newspack' ); ?></a>.
	</p>
	<!-- /wp:paragraph -->

	<!-- wp:columns { "style":{ "spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}} },"backgroundColor":"light-gray","className":"is-style-borders" } -->
	<div class="wp-block-columns is-style-borders has-light-gray-background-color has-background" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading { "level":3,"style":{"typography":{"fontSize":"19px"}} } -->
			<h3 class="wp-block-heading" style="font-size:19px">
				<?php esc_html_e( 'Member', 'newspack' ); ?>
			</h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph { "style":{"typography":{"fontSize":"28px"}} } -->
			<p style="font-size:28px">
				<strong>$7</strong> <sub><?php esc_html_e( 'per month', 'newspack' ); ?></sub>
			</p>
			<!-- /wp:paragraph -->
			<!-- wp:list {"style":{"typography":{"fontSize":"15px"}},"className":"newspack-feature-list"} -->
			<ul class="newspack-feature-list" style="font-size:15px">
				<?php foreach ( $member_features as $feature ) : ?>
					<li><?php echo esc_html( $feature ); ?></li>
				<?php endforeach; ?>
			</ul>
			<!-- /wp:list -->
			<!-- wp:newspack-blocks/checkout-button {"text":"<?php esc_attr_e( 'Become a Member', 'newspack' ); ?>","align":"wide","className":"is-style-outline"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading { "level":3,"style":{"typography":{"fontSize":"19px"}} } -->
			<h3 class="wp-block-heading" style="font-size:19px">
				<?php esc_html_e( 'Patron', 'newspack' ); ?>
			</h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph { "style":{"typography":{"fontSize":"28px"}} } -->
			<p style="font-size:28px">
				<strong>$10</strong> <sub><?php esc_html_e( 'per month', 'newspack' ); ?></sub>
			</p>
			<!-- /wp:paragraph -->
			<!-- wp:list {"style":{"typography":{"fontSize":"15px"}},"className":"newspack-feature-list"} -->
			<ul class="newspack-feature-list" style="font-size:15px">
				<?php foreach ( $patron_features as $feature ) : ?>
					<li><?php echo esc_html( $feature ); ?></li>
				<?php endforeach; ?>
			</ul>
			<!-- /wp:list -->
			<!-- wp:newspack-blocks/checkout-button { "text":"<?php esc_attr_e( 'Become a Patron', 'newspack' ); ?>","align":"wide","className":"is-style-fill","style":{"color":{"background":"#cc1818"}} } /-->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
