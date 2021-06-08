/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { withWizardScreen, Waiting } from '../../../../components/src';
import './style.scss';

/**
 * Loading screen.
 */
class Loading extends Component {
	render() {
		return (
			<div className="newspack_support_loading">
				<Waiting isLeft />
				{ __( 'Loading...', 'newspack' ) }
			</div>
		);
	}
}
export default withWizardScreen( Loading );
