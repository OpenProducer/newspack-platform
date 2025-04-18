/**
 * WordPress dependencies
 */
import { ExternalLink, Path, SVG } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { registerBlockStyle } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import edit from './edit';
import metadata from './block.json';

/**
 * Style dependencies - will load in editor
 */
import './styles/editor.scss';
import './styles/view.scss';

const { name, attributes, category, supports } = metadata;

// Name must be exported separately.
export { name };

export const title = __( 'Donate', 'newspack-blocks' );

export const icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
		<Path
			fillRule="evenodd"
			d="M14.75 9a2.5 2.5 0 0 0 0-5 2.5 2.5 0 0 0-2.5 2.5 2.5 2.5 0 0 0-5 0A2.5 2.5 0 0 0 9.75 9H4v11h16V9h-5.25Zm-1-2.5c0-.55.45-1 1-1s1 .45 1 1-.45 1-1 1h-1v-1Zm-5 0c0-.55.45-1 1-1s1 .45 1 1v1h-1c-.55 0-1-.45-1-1Zm-3.25 4h6v8h-6v-8Zm13 8H13v-8h5.5v8Z"
			clipRule="evenodd"
		/>
	</SVG>
);

export const settings = {
	title,
	icon: {
		src: icon,
		foreground: '#406ebc',
	},
	category,
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
			<ExternalLink href="https://help.newspack.com/publishing-and-appearance/blocks/donate-block/">
				{ __( 'Support reference', 'newspack-blocks' ) }
			</ExternalLink>
		</>
	),
	attributes,
	supports,
	edit,
	save: () => null, // to use view.php
};

/**
 * Block Styles
 */
registerBlockStyle( 'newspack-blocks/donate', {
	name: 'alternate',
	label: __( 'Alternate', 'newspack-blocks' ),
} );

registerBlockStyle( 'newspack-blocks/donate', {
	name: 'minimal',
	label: __( 'Minimal', 'newspack-blocks' ),
} );

registerBlockStyle( 'newspack-blocks/donate', {
	name: 'modern',
	label: __( 'Modern', 'newspack-blocks' ),
} );
