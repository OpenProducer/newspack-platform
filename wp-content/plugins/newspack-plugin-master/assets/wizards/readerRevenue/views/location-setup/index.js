/**
 * Location Setup Screen
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { SelectControl, TextControl, withWizardScreen } from '../../../../components/src';

/**
 * Location Setup Screen Component
 */
class LocationSetup extends Component {
	/**
	 * Render.
	 */
	render() {
		const { countryStateFields, currencyFields, data, onChange } = this.props;
		const {
			address1 = '',
			address2 = '',
			city = '',
			countrystate = '',
			currency = '',
			postcode = '',
		} = data;
		return (
			<Fragment>
				<SelectControl
					label={ __( 'Where is your business based?' ) }
					value={ countrystate }
					options={ countryStateFields }
					onChange={ _countrystate => onChange( { ...data, countrystate: _countrystate } ) }
				/>
				<TextControl
					label={ __( 'Address' ) }
					value={ address1 }
					onChange={ _address1 => onChange( { ...data, address1: _address1 } ) }
				/>
				<TextControl
					label={ __( 'Address line 2' ) }
					value={ address2 }
					onChange={ _address2 => onChange( { ...data, address2: _address2 } ) }
				/>
				<TextControl
					label={ __( 'City' ) }
					value={ city }
					onChange={ _city => onChange( { ...data, city: _city } ) }
				/>
				<TextControl
					label={ __( 'Postcode / Zip' ) }
					value={ postcode }
					onChange={ _postcode => onChange( { ...data, postcode: _postcode } ) }
				/>
				<SelectControl
					label={ 'Which currency does your business use?' }
					value={ currency }
					options={ currencyFields }
					onChange={ _currency => onChange( { ...data, currency: _currency } ) }
				/>
			</Fragment>
		);
	}
}

LocationSetup.defaultProps = {
	countryStateFields: [ {} ],
	currencyFields: [ {} ],
	data: {},
	onChange: () => null,
};

export default withWizardScreen( LocationSetup );
