<?php
/**
 * Title: Post Header
 * Slug: newspack-block-theme/post-header
 * Categories: newspack-block-theme
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:post-terms {"term":"category","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase"}},"fontSize":"medium"} /-->

<!-- wp:post-title {"level":1} /--></div>
<!-- /wp:group -->

<!-- wp:post-excerpt {"fontSize":"large"} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group">

<?php if ( class_exists( 'CoAuthors_Guest_Authors' ) ) : ?>
<!-- wp:co-authors-plus/coauthors -->
<div class="wp-block-co-authors-plus-coauthors">
<!-- wp:co-authors-plus/name /--></div>
<!-- /wp:co-authors-plus/coauthors -->
<?php else : ?>
<!-- wp:post-author {"showAvatar":false,"byline":"By","isLink":false} /-->
<?php endif; ?>

<!-- wp:post-date {"format":"F j, Y, g:i a","isLink":false} /--></div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
