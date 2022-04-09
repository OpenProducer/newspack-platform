/**
 * WordPress dependencies
 */
import { ExternalLink } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { registerBlockStyle } from '@wordpress/blocks';
import { payment } from '@wordpress/icons';

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
		src: payment,
		foreground: '#36f',
	},
	category: 'newspack',
	keywords: [
		__( 'donate', 'newspack-blocks' ),
		__( 'memberships', 'newspack-blocks' ),
		__( 'subscriptions', 'newspack-blocks' ),
	],
	description: (
		<>
			<p>
				{ __(
					'Manually place a donation block on any post or page on your site.',
					'newspack-blocks'
				) }
			</p>
			<ExternalLink href={ __( 'https://newspack.pub/support/blocks/donate-block/' ) }>
				{ __( 'Support reference', 'newspack-blocks' ) }
			</ExternalLink>
		</>
	),
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
			default: __( 'Donate Now', 'newspack-blocks' ),
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

/**
 * Block Styles
 */
registerBlockStyle( 'newspack-blocks/donate', {
	name: 'alternate',
	label: __( 'Alternate', 'newapack-blocks' ),
} );

registerBlockStyle( 'newspack-blocks/donate', {
	name: 'minimal',
	label: __( 'Minimal', 'newapack-blocks' ),
} );
