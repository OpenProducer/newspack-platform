/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './editor.scss';
import { EventDatesEditor } from './edit';
import { Event } from '../../svg';
import metadata from './block.json';
const { attributes, category, name } = metadata;

export const registerEventDatesBlock = () => {
	registerBlockType( name, {
		title: __( 'Event Dates', 'newspack-listing' ),
		icon: <Event />,
		category,
		keywords: [
			__( 'curated', 'newspack-listings' ),
			__( 'list', 'newspack-listings' ),
			__( 'lists', 'newspack-listings' ),
			__( 'listings', 'newspack-listings' ),
			__( 'latest', 'newspack-listings' ),
			__( 'event', 'newspack-listings' ),
			__( 'events', 'newspack-listings' ),
		],

		attributes,

		edit: EventDatesEditor,
		save: () => null, // uses view.php
	} );
};
