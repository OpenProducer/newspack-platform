/**
 * Syndication Intro View
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
 * Syndication Intro screen.
 */
class Intro extends Component {
	/**
	 * Render.
	 */
	render() {
		return (
			<Fragment>
				<ActionCard
					title={ __( 'Google Analytics' ) }
					description={ __( 'Configure and view site analytics' ) }
					actionText={ __( 'View' ) }
					handoff="google-site-kit"
					editLink="admin.php?page=googlesitekit-module-analytics"
				/>
			</Fragment>
		);
	}
}

export default withWizardScreen( Intro );
