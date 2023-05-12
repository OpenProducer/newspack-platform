<?php
/**
 * Memberships Registration Wall Pattern.
 *
 * @package Newspack
 */

$block_title       = __( 'Become a member to continue reading', 'newspack' );
$block_description = __( 'Get unlimited access to our content and weekly newsletters by registering!', 'newspack' );
$block_thank_you   = __( 'Thank you for registering!', 'newspack' );
?>
<!-- wp:group -->
<div class="wp-block-group">
	<!-- wp:newspack/reader-registration {"title":"<?php echo esc_attr( $block_title ); ?>","description":"<?php echo esc_attr( $block_description ); ?>"} -->
	<div class="wp-block-newspack-reader-registration">
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html( $block_thank_you ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:newspack/reader-registration -->
</div>
<!-- /wp:group -->
