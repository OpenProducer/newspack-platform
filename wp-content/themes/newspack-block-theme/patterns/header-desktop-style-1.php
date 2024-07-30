<?php
/**
 * Title: Header (Desktop) - Style 1
 * Slug: newspack-block-theme/header-desktop-style-1
 * Inserter: no
 * Block Types: core/template-part/header
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"lock":{"move":true,"remove":true},"metadata":{"name":"<?php esc_html_e( 'Header (Desktop)', 'newspack-block-theme' ); ?>"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"0","right":"var:preset|spacing|30","left":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30","margin":{"bottom":"var:preset|spacing|80"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--80);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:0;padding-left:var(--wp--preset--spacing--30)">

	<!-- wp:group {"templateLock":false,"lock":{"move":false,"remove":false},"metadata":{"name":"<?php esc_html_e( 'Top', 'newspack-block-theme' ); ?>"},"align":"wide","layout":{"type":"flex","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:site-logo {"width":256,"lock":{"move":true,"remove":true}} /-->

		<!-- wp:buttons {"lock":{"move":true,"remove":false},"className":"has-small-size","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"x-small"} -->
		<div class="wp-block-buttons has-custom-font-size has-small-size has-x-small-font-size">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Donate', 'newspack-block-theme' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"lock":{"move":false,"remove":true},"metadata":{"name":"<?php esc_html_e( 'Menu', 'newspack-block-theme' ); ?>"},"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:navigation {"className":"primary-navigation","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left","orientation":"horizontal","flexWrap":"wrap"},"style":{"typography":{"textTransform":"uppercase"}}} /-->

		<!-- wp:template-part {"slug":"search-menu","theme":"newspack-block-theme","tagName":"div","className":"search-menu"} /-->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
