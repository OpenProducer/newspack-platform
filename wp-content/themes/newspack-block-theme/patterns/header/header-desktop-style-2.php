<?php
/**
 * Title: Header (Desktop) - Style 2
 * Slug: newspack-block-theme/header-desktop-style-2
 * Inserter: no
 * Block Types: core/template-part/header
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"lock":{"move":true,"remove":true},"metadata":{"name":"<?php esc_html_e( 'Header (Desktop)', 'newspack-block-theme' ); ?>"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"0","right":"var:preset|spacing|30","left":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30","margin":{"bottom":"var:preset|spacing|80"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--80);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:0;padding-left:var(--wp--preset--spacing--30)">

	<!-- wp:site-logo {"width":256,"align":"center","lock":{"move":true,"remove":true}} /-->

	<!-- wp:group {"lock":{"move":false,"remove":true},"metadata":{"name":"<?php esc_html_e( 'Menu', 'newspack-block-theme' ); ?>"},"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:pattern {"slug":"newspack-block-theme/drawer-menu"} /-->

		<!-- wp:navigation {"className":"primary-navigation","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"center","orientation":"horizontal","flexWrap":"wrap"},"style":{"typography":{"textTransform":"uppercase"}}} /-->

		<!-- wp:template-part {"slug":"search-menu","theme":"newspack-block-theme","tagName":"div","className":"search-menu"} /-->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
