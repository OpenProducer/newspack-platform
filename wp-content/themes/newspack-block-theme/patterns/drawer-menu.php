<?php
/**
 * Title: Drawer Menu
 * Slug: newspack-block-theme/drawer-menu
 * Categories: newspack-block-theme
 * Viewport Width: 632
 * Block Types: core/navigation, core/social-links
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Drawer Menu', 'newspack-block-theme' ); ?>"},"className":"drawer-menu","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group drawer-menu">
	<!-- wp:buttons {"lock":{"move":false,"remove":false},"className":"has-x-small-size","fontSize":"small"} -->
	<div class="wp-block-buttons has-custom-font-size has-x-small-size has-small-font-size">
		<!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"typography":{"textTransform":"uppercase"}},"className":"drawer-menu__toggle"} -->
		<div class="wp-block-button drawer-menu__toggle" style="text-transform:uppercase">
			<a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background has-link-color wp-element-button" href="#"><?php esc_html_e( 'Menu', 'newspack-block-theme' ); ?></a>
		</div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

	<!-- wp:template-part {"slug":"drawer-contents","theme":"newspack-block-theme","tagName":"div","className":"drawer-menu__contents overlay-contents overlay-contents--position--left"} /-->
</div>
<!-- /wp:group -->
