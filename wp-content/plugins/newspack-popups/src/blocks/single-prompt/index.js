/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * External dependencies.
 */
import CallToActionIcon from '@material-ui/icons/CallToAction';

/**
 * Internal dependencies.
 */
import './editor.scss';
import metadata from './block.json';
const { attributes, category, name } = metadata;
import { SinglePromptEditor } from './edit';

export const registerSinglePromptBlock = () => {
	const isPrompt = Boolean( window.newspack_popups_blocks_data?.is_prompt );

	// No prompts inside prompts.
	if ( isPrompt ) {
		return null;
	}

	registerBlockType( name, {
		title: __( 'Newspack Campaigns: Single Prompt', 'newspack-listing' ),
		icon: <CallToActionIcon style={ { color: '#36f' } } />,
		category,
		keywords: [
			__( 'newspack', 'newspack-popups' ),
			__( 'campaigns', 'newspack-popups' ),
			__( 'campaign', 'newspack-popups' ),
			__( 'single', 'newspack-popups' ),
			__( 'prompt', 'newspack-popups' ),
		],

		attributes,

		edit: SinglePromptEditor,
		save: () => null, // uses view.php
	} );
};
