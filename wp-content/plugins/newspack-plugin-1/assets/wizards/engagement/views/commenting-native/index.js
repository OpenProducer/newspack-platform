/**
 * Native Commenting screen.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Disqus dependencies
 */
import { ActionCard, withWizardScreen } from '../../../../components/src';

/**
 * Native Commenting Screen
 */
class CommentingNative extends Component {
	/**
	 * Render.
	 */
	render() {
		return (
			<ActionCard
				title={ __( 'WordPress Commenting' ) }
				description={ __( 'Description TK.' ) }
				actionText={ __( 'Configure' ) }
				handoff="wordpress-settings-discussion"
			/>
		);
	}
}

export default withWizardScreen( CommentingNative );
