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
				<ActionCard
					isMedium
					title={ __( 'Under construction', 'newspack' ) }
					description={ __( 'Discourage search engines from indexing this site.', 'newspack' ) }
					toggleChecked={ underConstruction }
					toggleOnChange={ value => onChange( { underConstruction: value } ) }
				/>
			</Fragment>
		);
	}
}
Environment.defaultProps = {
	data: {},
};

export default withWizardScreen( Environment );
