/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './editor.scss';
import { ListingEditor } from './edit';
import { List } from '../../svg';
import metadata from './block.json';
import parentData from '../curated-list/block.json';

const parentAttributes = parentData.attributes;
const { attributes, category } = metadata;
const { post_types } = window.newspack_listings_data;

export const registerListingBlock = () => {
	for ( const listingType in post_types ) {
		if ( post_types.hasOwnProperty( listingType ) ) {
			registerBlockType( `newspack-listings/${ listingType }`, {
				title: listingType.charAt( 0 ).toUpperCase() + listingType.slice( 1 ),
				icon: <List />,
				category,
				parent: [ 'newspack-listings/list-container' ],
				keywords: [
					__( 'lists', 'newspack-listings' ),
					__( 'listings', 'newspack-listings' ),
					__( 'latest', 'newspack-listings' ),
				],

				// Combine attributes with parent attributes, so parent can pass data to InnerBlocks without relying on contexts.
				attributes: Object.assign( attributes, parentAttributes ),

				// Hide from Block Inserter if there are no published posts of this type.
				supports: {
					inserter: post_types[ listingType ].show_in_inserter || false,
				},

				edit: ListingEditor,
				save: () => null, // uses view.php
			} );
		}
	}
};
