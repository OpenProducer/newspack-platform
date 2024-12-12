<?php
/**
 * Title: Header (Mobile) - Style 2
 * Slug: newspack-block-theme/header-mobile-style-2
 * Viewport Width: 632
 * Inserter: no
 * Block Types: core/template-part/header
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"lock":{"move":true,"remove":true},"metadata":{"name":"<?php esc_html_e( 'Header (Mobile)', 'newspack-block-theme' ); ?>"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"0","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"margin":{"bottom":"var:preset|spacing|50"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:0;padding-left:var(--wp--preset--spacing--30)">

	<!-- wp:group {"lock":{"move":true,"remove":true},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:template-part {"slug":"mobile-menu","theme":"newspack-block-theme","tagName":"div","lock":{"move":false,"remove":true},"className":"mobile-menu"} /-->

		<!-- wp:site-logo {"width":256,"lock":{"move":false,"remove":true}} /-->

		<!-- wp:template-part {"slug":"search-menu","theme":"newspack-block-theme","tagName":"div","className":"search-menu"} /-->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
