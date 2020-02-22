/**
 * Revenue Main Screen
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
 * Revenue Main Screen Component
 */
class RevenueMain extends Component {
	/**
	 * Render.
	 */
	render() {
		return (
			<div>
				<p>{ __( 'Newspack can help you set up a donations page and accept one-time or recurring payments from your readers.' ) }</p>
			</div>
		);
	}
}

export default withWizardScreen( RevenueMain );
