/**
 * Ad Unit Management Screens.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, withWizardScreen } from '../../../../components/src';

/**
 * Advertising management screen.
 */
class AdUnits extends Component {
	/**
	 * Render.
	 */
	render() {
		const { adUnits, onDelete, service } = this.props;

		return (
			<Fragment>
				<p>
					{ __(
						'Set up multiple ad units to use on your homepage, articles and other places throughout your site. You can place ads through our Newspack Ad Block in the Editor.'
					) }
				</p>
				{ Object.values( adUnits ).map( ( { id, name, code } ) => {
					return (
						<ActionCard
							key={ id }
							title={ name }
							actionText={ __( 'Edit' ) }
							href={ `#${ service }/${ id }` }
							secondaryActionText={ __( 'Delete' ) }
							onSecondaryActionClick={ () => onDelete( id ) }
						/>
					);
				} ) }
			</Fragment>
		);
	}
}

export default withWizardScreen( AdUnits );
