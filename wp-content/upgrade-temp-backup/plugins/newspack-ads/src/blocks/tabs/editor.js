/**
 * WordPress dependencies
 */
import { Path, SVG } from '@wordpress/components';
import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { registerBlock } from '../utils/register-block';
import edit from './edit';
import metadata from './block.json';

const { name } = metadata;

const labels = {
	title: __('Tabs', 'newspack-ads'),
	description: __('Add tabbed content.', 'newspack-ads'),
};

const icon = (
	<SVG
		xmlns="http://www.w3.org/2000/svg"
		width="24"
		height="24"
		viewBox="0 0 24 24"
	>
		<Path
			clip-rule="evenodd"
			d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3ZM5 4.5H8.5L8.5 8.5H4.5V5C4.5 4.72386 4.72386 4.5 5 4.5ZM10 8.5L10 4.5H14V8.5H10ZM4.5 10V19C4.5 19.2761 4.72386 19.5 5 19.5H19C19.2761 19.5 19.5 19.2761 19.5 19V10H4.5ZM15.5 8.5V4.5H19C19.2761 4.5 19.5 4.72386 19.5 5V8.5H15.5Z"
			fill-rule="evenodd"
		/>
	</SVG>
);

const tabs = {
	name,
	settings: {
		...metadata,
		...labels,
		icon: {
			src: icon,
			foreground: '#36f',
		},
		edit,
		save: () => <InnerBlocks.Content />,
	},
};

// Register the block
// wp.domReady is required for core filters to work with this custom block. See - https://github.com/WordPress/gutenberg/issues/9757
wp.domReady(function () {
	registerBlock(tabs);
});
