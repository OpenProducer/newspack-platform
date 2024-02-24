<?php
/**
 * Memberships Donation Wall Pattern.
 *
 * @package Newspack
 */

$donate_settings = [
	'className'           => 'is-style-minimal',
	'manual'              => true,
	'amounts'             => [
		'once'  => [ 9, 20, 90, 20 ],
		'month' => [ 7, 15, 30, 15 ],
		'year'  => [ 84, 180, 360, 180 ],
	],
	'disabledFrequencies' => [
		'once'  => true,
		'month' => false,
		'year'  => false,
	],
	'minimumDonation'     => 5,
	'layoutOption'        => 'tiers',
];
?>
<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->

<!-- wp:group { "style":{ "spacing":{"padding":{"top":"var:preset|spacing|70","right":"var:preset|spacing|70","bottom":"var:preset|spacing|70","left":"var:preset|spacing|70"}} },"className":"is-style-border newspack-content-gate","layout":{"type":"constrained"} } -->
<div class="wp-block-group is-style-border newspack-content-gate" style="padding-top:var(--wp--preset--spacing--70);padding-right:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--70)">

	<!-- wp:heading { "textAlign":"center","style":{"typography":{"fontSize":"28px"}} } -->
	<h2 class="wp-block-heading has-text-align-center" style="font-size:28px">
		<?php esc_html_e( 'Choose an option to continue reading', 'newspack' ); ?>
	</h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph { "align":"center","style":{"typography":{"fontSize":"13px"}},"className":"newspack-sign-in" } -->
	<p class="has-text-align-center newspack-sign-in" style="font-size:13px">
		<?php esc_html_e( 'Already have an account?', 'newspack' ); ?>
		<a href="#signin_modal"><?php esc_html_e( 'Sign In', 'newspack' ); ?></a>.
	</p>
	<!-- /wp:paragraph -->

	<!-- wp:newspack-blocks/donate <?php echo wp_json_encode( $donate_settings ); ?> /-->

</div>
<!-- /wp:group -->
