<?php
/**
 * Title: Header (Mobile) - Style 1
 * Slug: newspack-block-theme/header-mobile-style-1
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
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:template-part {"slug":"mobile-menu","theme":"newspack-block-theme","tagName":"div","lock":{"move":false,"remove":true},"className":"mobile-menu"} /-->

			<!-- wp:site-logo {"width":256,"lock":{"move":false,"remove":true}} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:buttons {"layout":{"type":"flex","flexWrap":"nowrap"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"has-x-small-size","fontSize":"x-small"} -->
				<div class="wp-block-button has-custom-font-size has-x-small-size has-x-small-font-size"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Donate', 'newspack-block-theme' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
