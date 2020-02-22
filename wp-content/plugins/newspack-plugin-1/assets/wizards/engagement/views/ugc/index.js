/**
 * UGC screen.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Card, withWizardScreen } from '../../../../components/src';

/**
 * UGC Screen
 */
class UGC extends Component {
	/**
	 * Render.
	 */
	render() {
		const { connected, connectURL } = this.props;

		return (
			<Card noBackground>
				<h2>{ __('Coming Soon') }</h2>
				<p>{ __( 'User Generated Content features TK.' ) }</p>
			</Card>
		);
	}
}

export default withWizardScreen( UGC );
