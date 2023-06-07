<?php
/**
 * Memberships Registration Wall Pattern.
 *
 * @package Newspack
 */

?>
<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->

<!-- wp:group { "style":{ "spacing":{"padding":{"top":"var:preset|spacing|70","right":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70"}} },"className":"is-style-border newspack-content-gate","layout":{"type":"constrained"} } -->
<div class="wp-block-group is-style-border newspack-content-gate" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">
	<!-- wp:heading { "textAlign":"center","style":{"typography":{"fontSize":"28px"}} } -->
	<h2 class="wp-block-heading has-text-align-center" style="font-size:28px">
		<?php esc_html_e( 'Register to continue reading', 'newspack' ); ?>
	</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph { "align":"center","style":{"typography":{"fontSize":"13px"}},"className":"newspack-sign-in" } -->
	<p class="has-text-align-center newspack-sign-in" style="font-size:13px">
		<?php esc_html_e( 'Already have an account?', 'newspack' ); ?>
		<a href="#signin_modal"><?php esc_html_e( 'Sign In', 'newspack' ); ?></a>.
	</p>
	<!-- /wp:paragraph -->

	<!-- wp:newspack/reader-registration -->
	<div class="wp-block-newspack-reader-registration">
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html_e( 'Thank you for registering!', 'newspack' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:newspack/reader-registration -->
</div>
<!-- /wp:group -->
