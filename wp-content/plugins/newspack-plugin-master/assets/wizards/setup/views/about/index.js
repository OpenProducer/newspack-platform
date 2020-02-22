/**
 * About your publication setup screen.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { SelectControl, TextControl, withWizardScreen } from '../../../../components/src';
import './style.scss';

/**
 * Location Setup Screen.
 */
class About extends Component {
	/**
	 * Render.
	 */
	render() {
		const { profile, updateProfile, currencies, countries } = this.props;
		const { address, address2, city, currency, country, zip } = profile || {};
		return (
			<Fragment>
				<SelectControl
					label={ __( 'Where is your business based?' ) }
					value={ country }
					onChange={ value => updateProfile( 'country', value ) }
					options={ countries }
				/>
				<TextControl
					label={ __( 'Address' ) }
					value={ address }
					onChange={ value => updateProfile( 'address', value ) }
				/>
				<TextControl
					label={ __( 'Address line 2' ) }
					value={ address2 }
					onChange={ value => updateProfile( 'address2', value ) }
				/>
				<div className="newspack-setup-wizard__form_element_row">
					<div style={ { flex: 2 } }>
						<TextControl
							label={ __( 'City' ) }
							value={ city }
							onChange={ value => updateProfile( 'city', value ) }
						/>
					</div>
					<div style={ { flex: 1 } }>
						<TextControl
							label={ __( 'Postcode/Zip' ) }
							value={ zip }
							onChange={ value => updateProfile( 'zip', value ) }
						/>
					</div>
				</div>
				<SelectControl
					label={ __( 'Currency' ) }
					value={ currency }
					onChange={ value => updateProfile( 'currency', value ) }
					options={ currencies }
				/>
			</Fragment>
		);
	}
}

export default withWizardScreen( About );
