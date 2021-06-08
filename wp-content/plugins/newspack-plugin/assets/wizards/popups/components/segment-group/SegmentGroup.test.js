/**
 * External dependencies
 */
import React from 'react';
import { render } from '@testing-library/react';

/**
 * Internal dependencies
 */
import SegmentGroup from './index';

// Mock component props.
const CAMPAIGN = {
	campaignData: null,
	campaignId: undefined,
};
const SEGMENT = {
	label: 'Donors',
	configuration: {
		min_posts: 0,
		max_posts: 0,
		min_session_posts: 0,
		max_session_posts: 0,
		is_subscribed: false,
		is_donor: true,
		is_not_subscribed: false,
		is_not_donor: false,
		favorite_categories: [],
		referrers: '',
	},
	id: '603692fc1f548',
	created_at: '2020-11-02',
	updated_at: '2020-11-02',
	priority: 0,
};
const PROMPTS = {
	overlaysUncategorized: [
		{
			campaign_groups: [],
			categories: [],
			content: 'Overlay Prompt 1',
			id: 1,
			options: {
				placement: 'center',
				selected_segment_id: SEGMENT.id,
				frequency: 'daily',
			},
			status: 'publish',
			title: 'Overlay Prompt 1',
		},
		{
			campaign_groups: [],
			categories: [],
			content: 'Overlay Prompt 2',
			id: 2,
			options: {
				placement: 'center',
				selected_segment_id: SEGMENT.id,
				frequency: 'daily',
			},
			status: 'publish',
			title: 'Overlay Prompt 2',
		},
	],
	overlaysCategorized: [
		{
			campaign_groups: [],
			categories: [
				{
					name: 'Tech',
					term_id: 1,
				},
			],
			content: 'Overlay Prompt 3 with category',
			id: 3,
			options: {
				placement: 'center',
				selected_segment_id: SEGMENT.id,
				frequency: 'daily',
			},
			status: 'publish',
			title: 'Overlay Prompt 3 with category',
		},
		{
			campaign_groups: [],
			categories: [
				{
					name: 'Tech',
					term_id: 1,
				},
				{
					name: 'News',
					term_id: 2,
				},
			],
			content: 'Overlay Prompt 4 with category',
			id: 4,
			options: {
				placement: 'center',
				selected_segment_id: SEGMENT.id,
				frequency: 'daily',
			},
			status: 'publish',
			title: 'Overlay Prompt 4 with category',
		},
	],
	aboveHeadersUncategorized: [
		{
			campaign_groups: [],
			categories: [],
			content: 'Above Header Prompt 1',
			id: 5,
			options: {
				placement: 'above_header',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Above Header Prompt 1',
		},
		{
			campaign_groups: [],
			categories: [],
			content: 'Above Header Prompt 2',
			id: 6,
			options: {
				placement: 'above_header',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Above Header Prompt 2',
		},
	],
	aboveHeadersCategorized: [
		{
			campaign_groups: [],
			categories: [
				{
					name: 'Tech',
					term_id: 1,
				},
			],
			content: 'Above Header Prompt 3 with category',
			id: 7,
			options: {
				placement: 'above_header',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Above Header Prompt 3 with category',
		},
		{
			campaign_groups: [],
			categories: [
				{
					name: 'Tech',
					term_id: 1,
				},
				{
					name: 'News',
					term_id: 2,
				},
			],
			content: 'Above Header Prompt 4 with category',
			id: 8,
			options: {
				placement: 'above_header',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Above Header Prompt 4 with category',
		},
	],
	customPlacementsUncategorized: [
		{
			campaign_groups: [],
			categories: [],
			content: 'Custom Placement Prompt 1',
			id: 9,
			options: {
				placement: 'custom1',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Custom Placement Prompt 1',
		},
		{
			campaign_groups: [],
			categories: [],
			content: 'Custom Placement Prompt 2',
			id: 10,
			options: {
				placement: 'custom1',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Custom Placement Prompt 2',
		},
	],
	customPlacementsCategorized: [
		{
			campaign_groups: [],
			categories: [
				{
					name: 'Tech',
					term_id: 1,
				},
			],
			content: 'Custom Placement Prompt 3 with category',
			id: 11,
			options: {
				placement: 'custom1',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Custom Placement Prompt 3 with category',
		},
		{
			campaign_groups: [],
			categories: [
				{
					name: 'Tech',
					term_id: 1,
				},
				{
					name: 'News',
					term_id: 2,
				},
			],
			content: 'Custom Placement Prompt 4 with category',
			id: 12,
			options: {
				placement: 'custom1',
				selected_segment_id: SEGMENT.id,
				frequency: 'always',
			},
			status: 'publish',
			title: 'Custom Placement Prompt 4 with category',
		},
	],
};

describe( 'A segment with conflicting prompts', () => {
	beforeEach( () => {
		// Mock global vars for custom placements.
		window.newspack_popups_wizard_data = {
			custom_placements: {
				custom1: 'Custom Placement 1',
			},
		};
	} );

	it( 'renders a conflict notice for uncategorized overlays in the same segment', async () => {
		SEGMENT.prompts = PROMPTS.overlaysUncategorized;

		// Expected warning text.
		const noticeText =
			'If multiple uncategorized overlays share the same segment, only the most recent one will be displayed.';
		const props = {
			campaignData: CAMPAIGN.campaignData,
			campaign: CAMPAIGN.campaignId,
			segment: SEGMENT,
		};
		const { getByTestId } = render( <SegmentGroup { ...props } /> );
		const notice1 = getByTestId( 'conflict-warning-1' );
		const notice2 = getByTestId( 'conflict-warning-2' );

		// Notice text has expected shape.
		expect( notice1.textContent ).toEqual(
			`${ PROMPTS.overlaysUncategorized[ 1 ].title }: ${ noticeText }`
		);
		expect( notice2.textContent ).toEqual(
			`${ PROMPTS.overlaysUncategorized[ 0 ].title }: ${ noticeText }`
		);
	} );

	it( 'renders a conflict notice for categorized overlays in the same segment', async () => {
		SEGMENT.prompts = PROMPTS.overlaysCategorized;

		// Expected warning text.
		const noticeText =
			'If multiple overlays share the same segment and category filtering, only the most recent one will be displayed.';
		const props = {
			campaignData: CAMPAIGN.campaignData,
			campaign: CAMPAIGN.campaignId,
			segment: SEGMENT,
		};
		const { getByTestId } = render( <SegmentGroup { ...props } /> );
		const notice1 = getByTestId( 'conflict-warning-3' );
		const notice2 = getByTestId( 'conflict-warning-4' );

		// Notice text has expected shape.
		expect( notice1.textContent ).toEqual(
			`${ PROMPTS.overlaysCategorized[ 1 ].title }: ${ noticeText }`
		);
		expect( notice2.textContent ).toEqual(
			`${ PROMPTS.overlaysCategorized[ 0 ].title }: ${ noticeText }`
		);
	} );

	it( 'renders a conflict notice for uncategorized above-header prompts in the same segment', async () => {
		SEGMENT.prompts = PROMPTS.aboveHeadersUncategorized;

		// Expected warning text.
		const noticeText =
			'If multiple uncategorized above-header prompts share the same segment, only the most recent one will be displayed.';
		const props = {
			campaignData: CAMPAIGN.campaignData,
			campaign: CAMPAIGN.campaignId,
			segment: SEGMENT,
		};
		const { getByTestId } = render( <SegmentGroup { ...props } /> );
		const notice1 = getByTestId( 'conflict-warning-5' );
		const notice2 = getByTestId( 'conflict-warning-6' );

		// Notice text has expected shape.
		expect( notice1.textContent ).toEqual(
			`${ PROMPTS.aboveHeadersUncategorized[ 1 ].title }: ${ noticeText }`
		);
		expect( notice2.textContent ).toEqual(
			`${ PROMPTS.aboveHeadersUncategorized[ 0 ].title }: ${ noticeText }`
		);
	} );

	it( 'renders a conflict notice for above-header prompts in the same segment', async () => {
		SEGMENT.prompts = PROMPTS.aboveHeadersCategorized;

		// Expected warning text.
		const noticeText =
			'If multiple above-header prompts share the same segment and category filtering, only the most recent one will be displayed.';
		const props = {
			campaignData: CAMPAIGN.campaignData,
			campaign: CAMPAIGN.campaignId,
			segment: SEGMENT,
		};
		const { getByTestId } = render( <SegmentGroup { ...props } /> );
		const notice1 = getByTestId( 'conflict-warning-7' );
		const notice2 = getByTestId( 'conflict-warning-8' );

		// Notice text has expected shape.
		expect( notice1.textContent ).toEqual(
			`${ PROMPTS.aboveHeadersCategorized[ 1 ].title }: ${ noticeText }`
		);
		expect( notice2.textContent ).toEqual(
			`${ PROMPTS.aboveHeadersCategorized[ 0 ].title }: ${ noticeText }`
		);
	} );

	it( 'renders a conflict notice for uncategorized prompts in the same custom placement and segment', async () => {
		SEGMENT.prompts = PROMPTS.customPlacementsUncategorized;

		// Expected warning text.
		const noticeText =
			'If multiple uncategorized prompts in the same custom placement share the same segment, only the most recent one will be displayed.';
		const props = {
			campaignData: CAMPAIGN.campaignData,
			campaign: CAMPAIGN.campaignId,
			segment: SEGMENT,
		};
		const { getByTestId } = render( <SegmentGroup { ...props } /> );
		const notice1 = getByTestId( 'conflict-warning-9' );
		const notice2 = getByTestId( 'conflict-warning-10' );

		// Notice text has expected shape.
		expect( notice1.textContent ).toEqual(
			`${ PROMPTS.customPlacementsUncategorized[ 1 ].title }: ${ noticeText }`
		);
		expect( notice2.textContent ).toEqual(
			`${ PROMPTS.customPlacementsUncategorized[ 0 ].title }: ${ noticeText }`
		);
	} );

	it( 'renders a conflict notice for categorized prompts in the same custom placement and segment', async () => {
		SEGMENT.prompts = PROMPTS.customPlacementsCategorized;

		// Expected warning text.
		const noticeText =
			'If multiple prompts in the same custom placement share the same segment and category filtering, only the most recent one will be displayed.';
		const props = {
			campaignData: CAMPAIGN.campaignData,
			campaign: CAMPAIGN.campaignId,
			segment: SEGMENT,
		};
		const { getByTestId } = render( <SegmentGroup { ...props } /> );
		const notice1 = getByTestId( 'conflict-warning-11' );
		const notice2 = getByTestId( 'conflict-warning-12' );

		// Notice text has expected shape.
		expect( notice1.textContent ).toEqual(
			`${ PROMPTS.customPlacementsCategorized[ 1 ].title }: ${ noticeText }`
		);
		expect( notice2.textContent ).toEqual(
			`${ PROMPTS.customPlacementsCategorized[ 0 ].title }: ${ noticeText }`
		);
	} );

	it( 'renders a conflict notice for conflicting prompts that have no segment', async () => {
		const everyone = {
			configuration: {},
			id: '',
			label: 'Everyone',
			prompts: [ ...PROMPTS.overlaysUncategorized ],
		};

		// Unset selected segment ids.
		everyone.prompts.forEach( prompt => ( prompt.options.selected_segment_id = '' ) );

		// Expected warning text.
		const noticeText =
			'If multiple uncategorized overlays share the same segment, only the most recent one will be displayed.';
		const props = {
			campaignData: CAMPAIGN.campaignData,
			campaign: CAMPAIGN.campaignId,
			segment: everyone,
		};
		const { getByTestId } = render( <SegmentGroup { ...props } /> );
		const notice1 = getByTestId( 'conflict-warning-1' );
		const notice2 = getByTestId( 'conflict-warning-2' );

		// Notice text has expected shape.
		expect( notice1.textContent ).toEqual(
			`${ PROMPTS.overlaysUncategorized[ 1 ].title }: ${ noticeText }`
		);
		expect( notice2.textContent ).toEqual(
			`${ PROMPTS.overlaysUncategorized[ 0 ].title }: ${ noticeText }`
		);
	} );
} );
