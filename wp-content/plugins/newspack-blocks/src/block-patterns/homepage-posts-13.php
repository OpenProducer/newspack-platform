<?php
/**
 * Homepage Posts Pattern 13.
 *
 * @package Newspack_Blocks
 */

return array(
	'title'         => __( 'Homepage Posts Pattern 13', 'newspack-blocks' ),
	'content'       => "<!-- wp:group {\"className\":\"newspack-pattern homepage-posts__style-13\"} -->\n<div class=\"wp-block-group newspack-pattern homepage-posts__style-13\"><div class=\"wp-block-group__inner-container\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-blocks/homepage-articles {\"showExcerpt\":false,\"minHeight\":40,\"showAuthor\":false,\"postsToShow\":1,\"mediaPosition\":\"behind\",\"typeScale\":3} /--></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-blocks/homepage-articles {\"showExcerpt\":false,\"minHeight\":40,\"showAuthor\":false,\"postsToShow\":1,\"mediaPosition\":\"behind\",\"typeScale\":3} /--></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-blocks/homepage-articles {\"showExcerpt\":false,\"minHeight\":40,\"showAuthor\":false,\"postsToShow\":1,\"mediaPosition\":\"behind\",\"typeScale\":3} /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:group -->",
	'viewportWidth' => 1000,
	'categories'    => array( 'newspack-homepage-posts' ),
);
