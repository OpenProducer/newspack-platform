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
import { ListContainerEditor } from './edit';
import { List } from '../../svg';
import parentData from '../curated-list/block.json';

const parentAttributes = parentData.attributes;

export const registerListContainerBlock = () => {
	registerBlockType( 'newspack-listings/list-container', {
		title: __( 'Curated List', 'newspack-listing' ),
		icon: <List />,
		category: 'newspack',
		parent: [ 'newspack-listings/curated-list' ],
		keywords: [
			__( 'curated', 'newspack-listings' ),
			__( 'list', 'newspack-listings' ),
			__( 'lists', 'newspack-listings' ),
			__( 'listings', 'newspack-listings' ),
			__( 'latest', 'newspack-listings' ),
		],

		attributes: parentAttributes,

		// Hide from block inserter menus.
		supports: {
			inserter: false,
		},

		edit: ListContainerEditor,
		save: () => <InnerBlocks.Content />, // also uses view.php
	} );
};
