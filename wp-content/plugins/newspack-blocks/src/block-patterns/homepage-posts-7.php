<?php
/**
 * Homepage Posts Pattern 7.
 *
 * @package Newspack_Blocks
 */

return array(
	'title'         => __( 'Homepage Posts Pattern 7', 'newspack-blocks' ),
	'content'       => "<!-- wp:group {\"className\":\"newspack-pattern homepage-posts__style-7\"} -->\n<div class=\"wp-block-group newspack-pattern homepage-posts__style-7\"><div class=\"wp-block-group__inner-container\"><!-- wp:columns {\"className\":\"is-style-borders\"} -->\n<div class=\"wp-block-columns is-style-borders\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-blocks/homepage-articles {\"excerptLength\":26,\"imageShape\":\"square\",\"showAvatar\":false,\"postsToShow\":1,\"typeScale\":6} /--></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:newspack-ads/ad-unit /--><!-- wp:newspack-blocks/homepage-articles {\"className\":\"is-style-borders\",\"excerptLength\":28,\"showImage\":false,\"showAuthor\":false,\"showAvatar\":false,\"postsToShow\":5,\"mediaPosition\":\"left\",\"typeScale\":3,\"imageScale\":1} /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:group -->",
	'viewportWidth' => 1000,
	'categories'    => array( 'newspack-homepage-posts' ),
);
