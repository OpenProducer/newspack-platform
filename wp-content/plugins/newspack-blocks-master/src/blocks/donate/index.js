/**
 * External dependencies
 */
import { Path, SVG } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import edit from './edit';

/**
 * Style dependencies - will load in editor
 */
import './editor.scss';
import './view.scss';

export const name = 'donate';
export const title = __( 'Donate', 'newspack-blocks' );

/* From https://material.io/tools/icons */
export const icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
		<Path d="M0 0h24v24H0z" fill="none" />
		<Path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z" />
	</SVG>
);

export const settings = {
	title,
	icon,
	category: 'newspack',
	keywords: [
		__( 'donate', 'newspack-blocks' ),
		__( 'memberships', 'newspack-blocks' ),
		__( 'subscriptions', 'newspack-blocks' ),
	],
	description: __( 'Enable donations.', 'newspack-blocks' ),
	attributes: {
		className: {
			type: 'string',
		},
		manual: {
			type: 'boolean',
		},
		suggestedAmounts: {
			type: 'array',
			default: [ 0, 0, 0 ],
		},
		suggestedAmountUntiered: {
			type: 'integer',
			default: 0,
		},
		tiered: {
			type: 'boolean',
			default: true,
		},
		campaign: {
			type: 'string',
		},
	},
	supports: {
		html: false,
		align: false,
	},
	edit,
	save: () => null, // to use view.php
};
