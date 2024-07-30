<?php
/**
 * Title: Header (Desktop) - Style 3
 * Slug: newspack-block-theme/header-desktop-style-3
 * Inserter: no
 * Block Types: core/template-part/header
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"lock":{"move":true,"remove":true},"metadata":{"name":"<?php esc_html_e( 'Header (Desktop)', 'newspack-block-theme' ); ?>"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"0","right":"var:preset|spacing|30","left":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|30","margin":{"bottom":"var:preset|spacing|80"}}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-background-color has-background" style="margin-bottom:var(--wp--preset--spacing--80);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:0;padding-left:var(--wp--preset--spacing--30)">

	<!-- wp:columns {"verticalAlignment":"center","lock":{"move":false,"remove":true},"metadata":{"name":"<?php esc_html_e( 'Menu', 'newspack-block-theme' ); ?>"},"align":"wide","className":"newspack-grid is-style-first-col-to-second"} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center newspack-grid is-style-first-col-to-second">
		<!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
			<!-- wp:site-logo {"width":256,"align":"center","lock":{"move":true,"remove":true}} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%">
			<!-- wp:pattern {"slug":"newspack-block-theme/drawer-menu"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%">
			<!-- wp:template-part {"slug":"search-menu","theme":"newspack-block-theme","tagName":"div","align":"right","className":"search-menu"} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
