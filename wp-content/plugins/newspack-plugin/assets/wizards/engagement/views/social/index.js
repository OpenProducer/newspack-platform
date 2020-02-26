/**
 * Social screen.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, withWizardScreen } from '../../../../components/src';

/**
 * Social Screen
 */
class Social extends Component {
	/**
	 * Render.
	 */
	render() {
		return (
			<ActionCard
				title={ __( 'Jetpack Publicize' ) }
				description={ __(
					'Publicize makes it easy to share your site’s posts on several social media networks automatically when you publish a new post.'
				) }
				actionText={ __( 'Configure' ) }
				handoff="jetpack"
				editLink="admin.php?page=jetpack#/sharing"
			/>
		);
	}
}

export default withWizardScreen( Social );
