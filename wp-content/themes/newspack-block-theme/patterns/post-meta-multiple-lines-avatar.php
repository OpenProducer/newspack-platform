<?php
/**
 * Title: Avatar, Byline and Date on multiple lines
 * Slug: newspack-block-theme/post-meta-multiple-lines-avatar
 * Categories: newspack-block-theme-post-meta
 * Viewport Width: 632
 * Block Types: core/avatar, core/post-author, core/post-date, jetpack/sharing-buttons
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"metadata":{"name":"Post Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"className":"post-meta","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
<div class="wp-block-group post-meta">
	
<!-- wp:group {"lock":{"move":false,"remove":true},"metadata":{"name":"Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group">

<?php if ( class_exists( 'CoAuthors_Guest_Authors' ) ) : ?>
	
	<!-- wp:co-authors-plus/coauthors {"layout":{"type":"flex","orientation":"horizontal","flexWrap":"nowrap"},"lock":{"move":true,"remove":true},"style":{"layout":{"selfStretch":"fit"},"spacing":{"blockGap":"0"}},"className":"cap-avatar"} -->
	<div class="wp-block-co-authors-plus-coauthors cap-avatar"><!-- wp:co-authors-plus/avatar {"size":48} /--></div>
	<!-- /wp:co-authors-plus/coauthors -->

<?php else : ?>

	<!-- wp:avatar {"size":48,"lock":{"move":true,"remove":true}} /-->

<?php endif; ?>

<!-- wp:group {"templateLock":"all","lock":{"move":true,"remove":true},"metadata":{"name":"Byline + Date"},"style":{"spacing":{"blockGap":"4px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">

<?php if ( class_exists( 'CoAuthors_Guest_Authors' ) ) : ?>

	<!-- wp:co-authors-plus/coauthors -->
	<div class="wp-block-co-authors-plus-coauthors"><!-- wp:co-authors-plus/name {"isLink":true} /--></div>
	<!-- /wp:co-authors-plus/coauthors -->

<?php else : ?>

	<!-- wp:post-author {"showAvatar":false,"byline":"By","isLink":true} /-->

<?php endif; ?>

<!-- wp:post-date {"format":"F j, Y"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<?php if ( function_exists( 'sharing_display' ) ) : ?>

	<!-- wp:jetpack/sharing-buttons {"styleType":"icon","layout":{"type":"flex","justifyContent":"left","flexWrap":"nowrap"}} -->
	<ul class="wp-block-jetpack-sharing-buttons has-normal-icon-size jetpack-sharing-buttons__services-list" id="jetpack-sharing-serivces-list">
		<!-- wp:jetpack/sharing-button {"service":"facebook","label":"Facebook"} /-->
		<!-- wp:jetpack/sharing-button {"service":"x","label":"X"} /-->
		<!-- wp:jetpack/sharing-button {"service":"mail","label":"Mail"} /-->
	</ul>
	<!-- /wp:jetpack/sharing-buttons -->

<?php endif; ?>

</div>
<!-- /wp:group -->
