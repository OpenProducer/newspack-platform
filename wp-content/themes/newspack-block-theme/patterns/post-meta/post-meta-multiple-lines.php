<?php
/**
 * Title: Byline and Date on multiple lines
 * Slug: newspack-block-theme/post-meta-multiple-lines
 * Categories: newspack-block-theme-post-meta
 * Viewport Width: 632
 * Block Types: core/post-author, core/post-date, jetpack/sharing-buttons
 *
 * @package Newspack_Block_Theme
 */

$registry = WP_Block_Type_Registry::get_instance();
?>
<!-- wp:group {"metadata":{"name":"Post Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"className":"post-meta","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group post-meta">

<!-- wp:group {"lock":{"move":false,"remove":true},"metadata":{"name":"Meta"},"style":{"spacing":{"blockGap":"4px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">

<?php if ( $registry->get_registered( 'co-authors-plus/coauthors' ) ) : ?>

	<!-- wp:co-authors-plus/coauthors {"lock":{"move":true,"remove":true}} -->
	<div class="wp-block-co-authors-plus-coauthors"><!-- wp:co-authors-plus/name {"isLink":true,"lock":{"move":true,"remove":true}} /--></div>
	<!-- /wp:co-authors-plus/coauthors -->

<?php else : ?>

	<!-- wp:post-author {"showAvatar":false,"byline":"By","isLink":true,"lock":{"move":true,"remove":true}} /-->

<?php endif; ?>

<!-- wp:post-date {"format":"F j, Y","lock":{"move":true,"remove":true}} /--></div>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"newspack-block-theme/jetpack-sharing-buttons"} /-->

</div>
<!-- /wp:group -->
