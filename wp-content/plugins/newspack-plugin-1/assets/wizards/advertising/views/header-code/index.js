/**
 * Edit header codes for ad services.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { ExternalLink } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Button, Card, TextControl, withWizardScreen } from '../../../../components/src';

/**
 * New/Edit Ad Unit Screen.
 */
class HeaderCode extends Component {
	/**
	 * Handle an update to an ad unit field.
	 *
	 * @param string key Ad Unit field
	 * @param mixed  value New value for field
	 *
	 */
	handleOnChange( key, value ) {
		const { adUnit, onChange } = this.props;
		adUnit[ key ] = value;
		onChange( adUnit );
	}

	/**
	 * Render.
	 */
	render() {
		const { onChange, code, service } = this.props;
		return (
			<Fragment>
				<TextControl
					label={ __( 'Google Ad Manager Network Code', 'newspack' ) }
					placeholder={ __( '123456789' ) }
					value={ code }
					onChange={ onChange }
				/>
			</Fragment>
		);
	}
}

export default withWizardScreen( HeaderCode );
