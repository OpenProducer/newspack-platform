/**
 * UGC screen.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { withWizardScreen } from '../../../../components/src';

/**
 * UGC Screen
 */
class UGC extends Component {
	/**
	 * Render.
	 */
	render() {
		return (
			<div>
				<h2>{ __( 'Coming Soon' ) }</h2>
				<p>{ __( 'User Generated Content features TK.' ) }</p>
			</div>
		);
	}
}

export default withWizardScreen( UGC );
