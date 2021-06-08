/**
 * WordPress dependencies
 */
import { addFilter } from '@wordpress/hooks';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import { Sidebar } from './sidebar';
import { TaxonomyPanel } from './taxonomy-panel';

/**
 * Filter the PostTaxonomies component.
 */
addFilter( 'editor.PostTaxonomyType', 'newspack-sponsors-editor', TaxonomyPanel );

/**
 * Register plugin editor settings.
 */
registerPlugin( 'newspack-sponsors-editor', {
	render: Sidebar,
	icon: null,
} );
