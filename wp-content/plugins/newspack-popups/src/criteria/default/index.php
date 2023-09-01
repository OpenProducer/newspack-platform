<?php
/**
 * Register default segmentation criteria.
 *
 * @package Newspack
 */

namespace Newspack_Popups;

defined( 'ABSPATH' ) || exit;

$criteria = [
	/**
	 * Reader Engagement.
	 */
	'articles_read'            => [
		'name'              => __( 'Articles read', 'newspack-popups' ),
		'description'       => __( 'Number of articles read in the last 30 day period.', 'newspack-popups' ),
		'category'          => 'reader_engagement',
		'matching_function' => 'range',
	],
	'articles_read_in_session' => [
		'name'              => __( 'Articles read in session', 'newspack-popups' ),
		'description'       => __( 'Number of articles recently read before 30 minutes of inactivity.', 'newspack-popups' ),
		'category'          => 'reader_engagement',
		'matching_function' => 'range',
	],
	'favorite_categories'      => [
		'name'              => __( 'Favorite Categories', 'newspack-popups' ),
		'description'       => __( 'Most read categories of reader.', 'newspack-popups' ),
		'category'          => 'reader_engagement',
		'matching_function' => 'list__in',
	],
	/**
	 * Reader Activity.
	 */
	'newsletter'               => [
		'name'     => __( 'Newsletter', 'newspack-popups' ),
		'category' => 'reader_activity',
		'options'  => [
			[
				'label' => __( 'Subscribers and non-subscribers', 'newspack-popups' ),
				'value' => '',
			],
			[
				'label' => __( 'Subscribers', 'newspack-popups' ),
				'value' => 'subscribers',
			],
			[
				'label' => __( 'Non-subscribers', 'newspack-popups' ),
				'value' => 'non-subscribers',
			],
		],
	],
	'donation'                 => [
		'name'        => __( 'Donation', 'newspack-popups' ),
		'description' => __( '(if checkout happens on-site)', 'newspack-popups' ),
		'category'    => 'reader_activity',
		'options'     => [
			[
				'label' => __( 'Donors and non-donors', 'newspack-popups' ),
				'value' => '',
			],
			[
				'label' => __( 'Donors', 'newspack-popups' ),
				'value' => 'donors',
			],
			[
				'label' => __( 'Non-donors', 'newspack-popups' ),
				'value' => 'non-donors',
			],
			[
				'label' => __( 'Former donors (who cancelled a recurring donation)', 'newspack-popups' ),
				'value' => 'former-donors',
			],
		],
	],
	'user_account'             => [
		'name'     => __( 'User Account', 'newspack-popups' ),
		'category' => 'reader_activity',
		'options'  => [
			[
				'label' => __( 'All users', 'newspack-popups' ),
				'value' => '',
			],
			[
				'label' => __( 'Has user account', 'newspack-popups' ),
				'value' => 'with-account',
			],
			[
				'label' => __( 'Does not have user account', 'newspack-popups' ),
				'value' => 'without-account',
			],
		],
	],
	/**
	 * Referrer Sources.
	 */
	'sources_to_match'         => [
		'name'               => __( 'Sources to match', 'newspack-popups' ),
		'description'        => __( 'Segment based on traffic source', 'newspack-popups' ),
		'help'               => __( 'A comma-separated list of domains.', 'newspack-popups' ),
		'placeholder'        => 'google.com, facebook.com',
		'category'           => 'referrer_sources',
		'matching_function'  => 'list__in',
		'matching_attribute' => 'referrer',
	],
	'sources_to_exclude'       => [
		'name'               => __( 'Sources to exclude', 'newspack-popups' ),
		'description'        => __( 'Segment based on traffic source - hide campaigns for visitors coming from specific sources.', 'newspack-popups' ),
		'help'               => __( 'A comma-separated list of domains.', 'newspack-popups' ),
		'placeholder'        => 'twitter.com, instagram.com',
		'category'           => 'referrer_sources',
		'matching_function'  => 'list__not_in',
		'matching_attribute' => 'referrer',
	],
];

/**
 * Filters the default criteria to be registered.
 *
 * @param array $criteria The default criteria config keyed by criteria ID.
 */
$criteria = apply_filters( 'newspack_popups_default_criteria', $criteria );

foreach ( $criteria as $criteria_id => $config ) {
	\Newspack_Popups_Criteria::register_criteria( $criteria_id, $config );
}
