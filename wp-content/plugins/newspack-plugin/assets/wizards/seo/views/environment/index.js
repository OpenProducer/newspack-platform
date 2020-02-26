/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { withWizardScreen, ToggleControl } from '../../../../components/src';
import './style.scss';

/**
 * SEO Environment screen.
 */
class Environment extends Component {
	/**
	 * Render.
	 */
	render() {
		const { data, onChange } = this.props;
		const { underConstruction } = data;
		return (
			<Fragment>
				<h2>{ __( 'Environment', 'newspack' ) }</h2>
				<ToggleControl
					isDark
					label={ __( 'Site under construction', 'newspack' ) }
					checked={ underConstruction }
					onChange={ value => onChange( { underConstruction: value } ) }
					help={ __( 'Site is under construction and should not be indexed.', 'newspack' ) }
				/>
			</Fragment>
		);
	}
}
Environment.defaultProps = {
	data: {},
};

export default withWizardScreen( Environment );
