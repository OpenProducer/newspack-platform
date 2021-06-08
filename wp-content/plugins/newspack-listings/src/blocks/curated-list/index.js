/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './editor.scss';
import { CuratedListEditor } from './edit';
import { List } from '../../svg';
import metadata from './block.json';
const { attributes, category, name } = metadata;

export const registerCuratedListBlock = () => {
	registerBlockType( name, {
		title: __( 'Curated List', 'newspack-listing' ),
		icon: <List />,
		category,
		keywords: [
			__( 'curated', 'newspack-listings' ),
			__( 'list', 'newspack-listings' ),
			__( 'lists', 'newspack-listings' ),
			__( 'listings', 'newspack-listings' ),
			__( 'latest', 'newspack-listings' ),
		],

		attributes,

		edit: CuratedListEditor,
		save: () => <InnerBlocks.Content />, // also uses view.php
	} );
};
