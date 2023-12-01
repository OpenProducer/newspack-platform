<?php
/**
 * Subscribe Pattern 9.
 *
 * @package Newspack_Blocks
 */

return array(
	'title'      => esc_html__( 'Subscribe Pattern 9', 'newspack-blocks' ),
	'content'    => "<!-- wp:group {\"templateLock\":\"all\",\"className\":\"newspack-pattern subscribe__style-9\"} -->\n<div class=\"wp-block-group newspack-pattern subscribe__style-9\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column {\"templateLock\":\"all\"} -->\n<div class=\"wp-block-column\"><!-- wp:heading {\"lock\":{\"remove\":false},\"level\":4,\"className\":\"newspack-pattern__heading\"} -->\n<h4 class=\"newspack-pattern__heading\">" . esc_html__( 'Subscribe to our Newsletter', 'newspack-blocks' ) . "</h4>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"lock\":{\"remove\":false}} -->\n<p>" . esc_html__( 'Be the first to know about breaking news, articles, and updates.', 'newspack-blocks' ) . "</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"templateLock\":\"all\"} -->\n<div class=\"wp-block-column\"><!-- wp:newspack-newsletters/subscribe {\"lists\":[]} /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:group -->",
	'categories' => array( 'newspack-subscribe' ),
);
