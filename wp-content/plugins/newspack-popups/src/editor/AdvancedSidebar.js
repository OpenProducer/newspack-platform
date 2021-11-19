/**
 * Popup Advanced settings.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { BaseControl } from '@wordpress/components';
import { decodeEntities } from '@wordpress/html-entities';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';

/**
 * Internal dependencies
 */
import { AutocompleteTokenField } from 'newspack-components';

const AdvancedSidebar = ( { onMetaFieldChange, excluded_categories = [], excluded_tags = [] } ) => {
	const getTaxonomyTitle = item =>
		decodeEntities( item.name ) || __( '(no title)', 'newspack-blocks' );

	const fetchTaxonomySuggestions = ( taxonomyRestRoute, search ) => {
		return apiFetch( {
			path: addQueryArgs( taxonomyRestRoute, {
				search,
				per_page: 20,
				_fields: 'id,name,parent',
				orderby: 'count',
				order: 'desc',
			} ),
		} ).then( taxonomies =>
			taxonomies.map( taxonomy => ( {
				value: taxonomy.id,
				label: getTaxonomyTitle( taxonomy ),
			} ) )
		);
	};

	const fetchSavedTaxonomies = ( taxonomyRestRoute, taxonomyIDs ) => {
		return apiFetch( {
			path: addQueryArgs( taxonomyRestRoute, {
				per_page: 100,
				_fields: 'id,name',
				include: taxonomyIDs.join( ',' ),
			} ),
		} ).then( taxonomies =>
			taxonomies.map( taxonomy => ( {
				value: taxonomy.id,
				label: getTaxonomyTitle( taxonomy ),
			} ) )
		);
	};

	return (
		<Fragment>
			<BaseControl className="newspack-popups__segmentation-sidebar">
				<AutocompleteTokenField
					key="categories"
					tokens={ excluded_categories }
					onChange={ _excluded_categories => {
						onMetaFieldChange( 'excluded_categories', _excluded_categories );
					} }
					fetchSuggestions={ search => fetchTaxonomySuggestions( '/wp/v2/categories', search ) }
					fetchSavedInfo={ taxonomyIDs => fetchSavedTaxonomies( '/wp/v2/categories', taxonomyIDs ) }
					label={ __( 'Excluded Categories', 'newspack-blocks' ) }
				/>

				<AutocompleteTokenField
					key="tags"
					tokens={ excluded_tags }
					onChange={ _excluded_tags => {
						onMetaFieldChange( 'excluded_tags', _excluded_tags );
					} }
					fetchSuggestions={ search => fetchTaxonomySuggestions( '/wp/v2/tags', search ) }
					fetchSavedInfo={ taxonomyIDs => fetchSavedTaxonomies( '/wp/v2/tags', taxonomyIDs ) }
					label={ __( 'Excluded Tags', 'newspack-blocks' ) }
				/>
			</BaseControl>
		</Fragment>
	);
};

export default AdvancedSidebar;
