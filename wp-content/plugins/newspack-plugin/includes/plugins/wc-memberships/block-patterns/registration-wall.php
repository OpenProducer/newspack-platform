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

<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Wall', 'newspack' ); ?>"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","left":"var:preset|spacing|80","right":"var:preset|spacing|80"}},"border":{"radius":"6px","width":"1px"}},"borderColor":"base-3","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-border-color has-base-3-border-color" style="border-width:1px;border-radius:6px;padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--80)">

	<!-- wp:heading {"textAlign":"center","level":3} -->
	<h3 class="wp-block-heading has-text-align-center">
		<?php esc_html_e( 'Register to continue reading', 'newspack' ); ?>
	</h3>
	<!-- /wp:heading -->

	<!-- wp:newspack/reader-registration -->
	<div class="wp-block-newspack-reader-registration">
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html_e( 'Thank you for registering.', 'newspack' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:newspack/reader-registration -->

</div>
<!-- /wp:group -->
