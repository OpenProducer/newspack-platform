/**
 * Popup frequency options.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import {
	SelectControl,
	TextControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';

const frequencyOptions = [
	{ value: 'once', label: __( 'Once a month', 'newspack-popups' ) },
	{ value: 'weekly', label: __( 'Once a week', 'newspack-popups' ) },
	{ value: 'daily', label: __( 'Once a day', 'newspack-popups' ) },
	{
		value: 'preset_1',
		label: __( 'Every 4th pageview, up to 5x per month', 'newspack-popups' ),
	},
	{
		value: 'always',
		label: __( 'Every pageview', 'newspack-popups' ),
	},
	{ value: 'custom', label: __( 'Custom', 'newspack-popups' ) },
];

const getFrequencyOptions = isOverlay => {
	const { experimental } = window.newspack_popups_data;
	const experimentalKeys = [ 'weekly', 'preset_1', 'custom' ];

	if ( experimental ) {
		return frequencyOptions;
	}

	return frequencyOptions
		.filter( item => 0 > experimentalKeys.indexOf( item.value ) )
		.map( item => {
			item.disabled = 'always' === item.value && isOverlay;
			return item;
		} );
};

const FrequencySidebar = ( {
	frequency,
	frequency_max,
	frequency_start,
	frequency_between,
	frequency_reset,
	isOverlay,
	onMetaFieldChange,
	utm_suppression,
} ) => {
	return (
		<Fragment>
			<SelectControl
				label={ __( 'Frequency' ) }
				value={ frequency }
				onChange={ value => {
					const metaToUpdate = {
						frequency: value,
					};

					if ( 'once' === value ) {
						metaToUpdate.frequency_max = 1;
						metaToUpdate.frequency_start = 0;
						metaToUpdate.frequency_between = 0;
						metaToUpdate.frequency_reset = 'month';
					}

					if ( 'daily' === value ) {
						metaToUpdate.frequency_max = 1;
						metaToUpdate.frequency_start = 0;
						metaToUpdate.frequency_between = 0;
						metaToUpdate.frequency_reset = 'day';
					}

					if ( 'weekly' === value ) {
						metaToUpdate.frequency_max = 1;
						metaToUpdate.frequency_start = 0;
						metaToUpdate.frequency_between = 0;
						metaToUpdate.frequency_reset = 'week';
					}

					if ( 'always' === value ) {
						metaToUpdate.frequency_max = 0;
						metaToUpdate.frequency_start = 0;
						metaToUpdate.frequency_between = 0;
						metaToUpdate.frequency_reset = 'month';
					}

					if ( 'preset_1' === value ) {
						metaToUpdate.frequency_max = 5;
						metaToUpdate.frequency_start = 3;
						metaToUpdate.frequency_between = 3;
						metaToUpdate.frequency_reset = 'month';
					}

					onMetaFieldChange( metaToUpdate );
				} }
				options={ getFrequencyOptions( isOverlay ) }
			/>
			{ 'custom' === frequency && (
				<>
					<NumberControl
						className="newspack-popups__frequency-number-control"
						label={ __( 'Max number of displays', 'newspack-popups' ) }
						value={ frequency_max }
						min={ 0 }
						onChange={ value => onMetaFieldChange( { frequency_max: value } ) }
					/>
					<NumberControl
						className="newspack-popups__frequency-number-control"
						label={ __( 'Start after pageview', 'newspack-popups' ) }
						value={ frequency_start }
						min={ 0 }
						onChange={ value => onMetaFieldChange( { frequency_start: value } ) }
					/>
					<NumberControl
						className="newspack-popups__frequency-number-control"
						label={ __( 'Pageviews between displays', 'newspack-popups' ) }
						value={ frequency_between }
						min={ 0 }
						onChange={ value => onMetaFieldChange( { frequency_between: value } ) }
					/>
					<SelectControl
						label={ __( 'Reset counter per:', 'newspack-popups' ) }
						value={ frequency_reset }
						options={ [
							{ value: 'month', label: __( 'Month', 'newspack-popups' ) },
							{ value: 'week', label: __( 'Week', 'newspack-popups' ) },
							{ value: 'day', label: __( 'Day', 'newspack-popups' ) },
						] }
						onChange={ value => onMetaFieldChange( { frequency_reset: value } ) }
					/>
				</>
			) }
			<hr />
			<TextControl
				label={ __( 'UTM Suppression' ) }
				help={ __(
					'Readers arriving at the site via URLs with this utm_source parameter will never be shown the prompt.'
				) }
				value={ utm_suppression }
				placeholder={ __( 'utm_campaign_name', 'newspack' ) }
				onChange={ value => onMetaFieldChange( { utm_suppression: value } ) }
			/>
		</Fragment>
	);
};

export default FrequencySidebar;
