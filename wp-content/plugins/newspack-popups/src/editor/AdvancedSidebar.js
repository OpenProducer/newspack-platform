/**
 * Popup Advanced settings.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { BaseControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { CategoryAutocomplete } from 'newspack-components';

const AdvancedSidebar = ( { onMetaFieldChange, excluded_categories = [], excluded_tags = [] } ) => {
	return (
		<Fragment>
			<BaseControl className="newspack-popups__segmentation-sidebar">
				<CategoryAutocomplete
					label={ __( 'Post categories', 'newspack ' ) }
					value={ excluded_categories }
					onChange={ tokens =>
						onMetaFieldChange( {
							excluded_categories: tokens.map( token => parseInt( token.id ) ),
						} )
					}
				/>
				<CategoryAutocomplete
					label={ __( 'Post tags', 'newspack ' ) }
					taxonomy="tags"
					value={ excluded_tags }
					onChange={ tokens =>
						onMetaFieldChange( {
							excluded_tags: tokens.map( token => parseInt( token.id ) ),
						} )
					}
				/>
			</BaseControl>
		</Fragment>
	);
};

export default AdvancedSidebar;
