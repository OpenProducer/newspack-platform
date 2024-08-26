<?php
/**
 * Title: Author Bio with Avatar
 * Slug: newspack-block-theme/author-bio-avatar
 * Categories: newspack-block-theme-author-bio
 * Viewport Width: 632
 * Block Types: core/avatar, core/post-author-name, core/post-author-biography
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Author Bio', 'newspack-block-theme' ); ?>"},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group">

	<!-- wp:separator {"className":"is-style-wide"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
	<!-- /wp:separator -->

	<!-- wp:group {"metadata":{"name":"<?php esc_html_e( 'Content', 'newspack-block-theme' ); ?>"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">

		<!-- wp:avatar {"size":128,"align":"right","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|20","right":"0","left":"var:preset|spacing|40"}}}} /-->

		<!-- wp:post-author-name {"style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"typography":{"fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"contrast","fontSize":"large"} /-->

		<!-- wp:post-author-biography /-->

	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"className":"is-style-wide"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
	<!-- /wp:separator -->

</div>
<!-- /wp:group -->
