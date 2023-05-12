<?php
/**
 * Memberships Pay Wall One Tier Pattern.
 *
 * @package Newspack
 */

?>
<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:paragraph {"align":"left","textColor":"medium-gray","fontSize":"normal"} -->
	<p class="has-text-align-left has-medium-gray-color has-text-color has-normal-font-size">
		<em><?php esc_html_e( 'Become a member to continue reading', 'newspack' ); ?></em>
	</p>
	<!-- /wp:paragraph -->

	<!-- wp:separator {"className":"is-style-wide"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
	<!-- /wp:separator -->

	<!-- wp:columns {"verticalAlignment":"center"} -->
	<div class="wp-block-columns are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%">
			<!-- wp:list { "style":{"typography":{"fontSize":"24px"}} } -->
			<ul style="font-size:24px">
				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Unlimited access to our content', 'newspack' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( 'Weekly newsletters', 'newspack' ); ?></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:newspack-blocks/checkout-button { "text":"<?php esc_attr_e( 'Subscribe now!', 'newspack' ); ?>","align":"wide","style":{"color":{"background":"#cc1818"}} } /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
