<?php
/**
 * Title: Archive - Offset Right
 * Slug: newspack-block-theme/archive-offset-right
 * Categories: newspack-block-theme
 * Description:
 * Keywords: archives
 * Block Types: core/query
 * Template Types: archive
 *
 * @package Newspack_Block_Theme
 */

?>
<!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"tagName":"main","className":"newspack-archive-offset-right","layout":{"type":"constrained"}} -->
<main class="wp-block-query newspack-archive-offset-right"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"22.7%"} -->
<div class="wp-block-column" style="flex-basis:22.7%"></div>
<!-- /wp:column -->

<!-- wp:column {"width":""} -->
<div class="wp-block-column"><!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"bottom":"var:preset|spacing|70"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--70)"><!-- wp:query-title {"type":"archive","showPrefix":false} /-->

<!-- wp:term-description {"style":{"spacing":{"margin":{"top":"0","right":"0","bottom":"0","left":"0"}}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:post-template -->
<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|60"}}},"className":"is-style-first-col-to-second"} -->
<div class="wp-block-columns is-style-first-col-to-second"><!-- wp:column {"width":"31.25%"} -->
<div class="wp-block-column" style="flex-basis:31.25%"><!-- wp:post-featured-image {"aspectRatio":"3/2"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":""} -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:post-terms {"term":"category","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase"}},"fontSize":"small"} /-->

<!-- wp:post-title {"isLink":true,"fontSize":"large"} /--></div>
<!-- /wp:group -->

<!-- wp:post-excerpt /-->

<!-- wp:post-author {"showAvatar":false,"byline":"By","isLink":true} /-->

<!-- wp:post-date {"isLink":true} /--></div>
<!-- /wp:group -->

<!-- wp:separator {"backgroundColor":"base-3"} -->
<hr class="wp-block-separator has-text-color has-base-3-color has-alpha-channel-opacity has-base-3-background-color has-background"/>
<!-- /wp:separator --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:post-template --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:group --></main>
<!-- /wp:query -->
