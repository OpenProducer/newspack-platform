<?php
/**
 * Memberships Donation Wall Pattern.
 *
 * @package Newspack
 */

$donate_settings = [
	'className'           => 'is-style-default',
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
	<!-- wp:newspack-blocks/donate <?php echo wp_json_encode( $donate_settings ); ?> /-->
</div>
<!-- /wp:group -->
