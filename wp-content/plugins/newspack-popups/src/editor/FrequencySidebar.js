/**
 * Popup frequency options.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { SelectControl, TextControl } from '@wordpress/components';

const FrequencySidebar = ( { frequency, onMetaFieldChange, isOverlay, utm_suppression } ) => {
	return (
		<Fragment>
			<SelectControl
				label={ __( 'Frequency' ) }
				value={ frequency }
				onChange={ value => onMetaFieldChange( 'frequency', value ) }
				options={ [
					{ value: 'once', label: __( 'Once', 'newspack-popups' ) },
					{ value: 'daily', label: __( 'Once a day', 'newspack-popups' ) },
					{
						value: 'always',
						label: __( 'Every page view', 'newspack-popups' ),
						disabled: isOverlay,
					},
				] }
			/>
			<TextControl
				label={ __( 'UTM Suppression' ) }
				help={ __(
					'Readers arriving at the site via URLs with this utm_source parameter will never be shown the prompt.'
				) }
				value={ utm_suppression }
				placeholder={ __( 'utm_campaign_name', 'newspack' ) }
				onChange={ value => onMetaFieldChange( 'utm_suppression', value ) }
			/>
		</Fragment>
	);
};

export default FrequencySidebar;
