/**
 * Ad Unit Management Screens.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, withWizardScreen } from '../../../../components/src';

/**
 * Advertising management screen.
 */
const AdUnits = ( { adUnits, onDelete, service } ) => {
	return (
		<>
			<p>
				{ __(
					'Set up multiple ad units to use on your homepage, articles and other places throughout your site. You can place ads through our Newspack Ad Block in the Editor.'
				) }
			</p>
			{ Object.values( adUnits ).map( ( { id, name } ) => {
				return (
					<ActionCard
						key={ id }
						title={ name }
						actionText={ __( 'Edit' ) }
						titleLink={ `#${ service }/${ id }` }
						href={ `#${ service }/${ id }` }
						secondaryActionText={ __( 'Delete' ) }
						onSecondaryActionClick={ () => onDelete( id ) }
					/>
				);
			} ) }
		</>
	);
};

export default withWizardScreen( AdUnits );
