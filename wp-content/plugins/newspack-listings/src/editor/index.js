/**
 * WordPress dependencies
 */
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import { Sidebar } from './sidebar';
import {
	registerCuratedListBlock,
	registerListContainerBlock,
	registerEventDatesBlock,
	registerListingBlock,
	setCustomCategory,
} from '../blocks';
import { isListing } from './utils';

/**
 * Register Curated List blocks. Don't register if we're in a listing already
 * (to avoid possibly infinitely nesting lists within list items).
 */
if ( isListing() ) {
	const { post_types } = window.newspack_listings_data || {};

	// Register plugin editor settings.
	registerPlugin( 'newspack-listings-editor', {
		render: Sidebar,
		icon: null,
	} );

	// Register Event Dates block if we're editing an Event.
	if ( isListing( post_types.event.name ) ) {
		registerEventDatesBlock();
	}
} else {
	setCustomCategory();
	registerCuratedListBlock();
	registerListContainerBlock();
	registerListingBlock();
}
