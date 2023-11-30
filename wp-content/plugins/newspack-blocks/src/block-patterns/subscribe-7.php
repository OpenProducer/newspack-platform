<?php
/**
 * Subscribe Pattern 7.
 *
 * @package Newspack_Blocks
 */

return array(
	'title'      => esc_html__( 'Subscribe Pattern 7', 'newspack-blocks' ),
	'content'    => "<!-- wp:group {\"templateLock\":\"all\",\"className\":\"newspack-pattern subscribe__style-7\"} -->\n<div class=\"wp-block-group newspack-pattern subscribe__style-7\"><!-- wp:media-text {\"mediaId\":999,\"mediaLink\":\"https://newspackdev.mystagingwebsite.com/prompts-patterns/pexels-brotin-biswas-518543/\",\"mediaType\":\"image\",\"imageFill\":true} -->\n<div class=\"wp-block-media-text alignwide is-stacked-on-mobile is-image-fill\"><figure class=\"wp-block-media-text__media\" style=\"background-image:url(https://newspackdev.mystagingwebsite.com/wp-content/uploads/2022/03/pexels-brotin-biswas-518543-1024x683.jpeg);background-position:50% 50%\"><img src=\"https://newspackdev.mystagingwebsite.com/wp-content/uploads/2022/03/pexels-brotin-biswas-518543-1024x683.jpeg\" alt=\"\" class=\"wp-image-999 size-full\"/></figure><div class=\"wp-block-media-text__content\"><!-- wp:heading {\"lock\":{\"remove\":false},\"level\":4,\"className\":\"newspack-pattern__heading\"} -->\n<h4 class=\"newspack-pattern__heading\">" . esc_html__( 'Subscribe to our Newsletter', 'newspack-blocks' ) . "</h4>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"lock\":{\"remove\":false}} -->\n<p>" . esc_html__( 'Be the first to know about breaking news, articles, and updates.', 'newspack-blocks' ) . "</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:newspack-newsletters/subscribe {\"lists\":[]} /--></div></div>\n<!-- /wp:media-text --></div>\n<!-- /wp:group -->",
	'categories' => array( 'newspack-subscribe' ),
);
