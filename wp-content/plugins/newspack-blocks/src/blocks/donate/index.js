/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Icon, payment } from '@wordpress/icons';

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

export const settings = {
	title,
	icon: {
		src: <Icon icon={ payment } />,
		foreground: '#36f',
	},
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
		thanksText: {
			type: 'string',
			default: __( 'Your contribution is appreciated.', 'newspack-blocks' ),
		},
		buttonText: {
			type: 'string',
			default: __( 'Donate now!', 'newspack-blocks' ),
		},
		defaultFrequency: {
			type: 'string',
			default: 'month',
		},
	},
	supports: {
		html: false,
		align: false,
	},
	edit,
	save: () => null, // to use view.php
};
