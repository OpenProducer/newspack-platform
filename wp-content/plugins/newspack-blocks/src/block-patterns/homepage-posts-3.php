<?php
/**
 * Homepage Posts Pattern 3.
 *
 * @package Newspack_Blocks
 */

return array(
	'title'         => __( 'Homepage Posts Pattern 3', 'newspack-blocks' ),
	'content'       => "<!-- wp:group {\"className\":\"newspack-pattern homepage-posts__style-3\"} -->\n<div class=\"wp-block-group newspack-pattern homepage-posts__style-3\"><div class=\"wp-block-group__inner-container\"><!-- wp:columns {\"className\":\"is-style-borders\"} -->\n<div class=\"wp-block-columns is-style-borders\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-blocks/homepage-articles {\"showImage\":false,\"showAvatar\":false,\"postsToShow\":2,\"typeScale\":3} /--></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-blocks/homepage-articles {\"showImage\":false,\"showAvatar\":false,\"postsToShow\":2,\"typeScale\":3} /--></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-blocks/homepage-articles {\"showImage\":false,\"showAvatar\":false,\"postsToShow\":2,\"typeScale\":3} /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:group -->",
	'viewportWidth' => 1000,
	'categories'    => array( 'newspack-homepage-posts' ),
);
