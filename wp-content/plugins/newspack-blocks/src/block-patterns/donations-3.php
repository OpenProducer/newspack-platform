<?php
/**
 * Donations Pattern 3.
 *
 * @package Newspack_Blocks
 */

return array(
	'title'         => __( 'Donations Pattern 3', 'newspack-blocks' ),
	'content'       => "<!-- wp:group {\"align\":\"full\",\"style\":{\"color\":{\"background\":\"#1e1e1e\"},\"spacing\":{\"padding\":{\"top\":\"var:preset|spacing|80\",\"bottom\":\"var:preset|spacing|80\"}}},\"textColor\":\"white\",\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group alignfull has-white-color has-text-color has-background\" style=\"background-color:#1e1e1e;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)\"><!-- wp:columns {\"verticalAlignment\":\"center\",\"align\":\"wide\",\"className\":\"newspack-grid\"} -->\n<div class=\"wp-block-columns alignwide are-vertically-aligned-center newspack-grid\"><!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"33.33%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:33.33%\"><!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . __( 'Support our publication', 'newspack-blocks' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>" . __( 'With the support of readers like you, we provide thoughtfully researched articles for a more informed and connected community. This is your chance to support credible, community-based, public-service journalism. Please join us!', 'newspack-blocks' ) . "</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"66.66%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:66.66%\"><!-- wp:newspack-blocks/donate {\"className\":\"is-style-modern\"} /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:group -->",
	'viewportWidth' => 782,
	'categories'    => array( 'newspack-donations' ),
);
