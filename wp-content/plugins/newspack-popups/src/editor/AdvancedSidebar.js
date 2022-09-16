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
			<BaseControl>
				<CategoryAutocomplete
					label={ __( 'Excluded Categories', 'newspack-popups' ) }
					description={ __(
						'The prompt will not be shown on posts that have any these categories.',
						'newspack-popups'
					) }
					value={ excluded_categories }
					onChange={ tokens =>
						onMetaFieldChange( {
							excluded_categories: tokens.map( token => parseInt( token.id ) ),
						} )
					}
				/>
				<hr />
				<CategoryAutocomplete
					label={ __( 'Excluded Tags', 'newspack-popups' ) }
					description={ __(
						'The prompt will not be shown on posts that have any these tags.',
						'newspack-popups'
					) }
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
