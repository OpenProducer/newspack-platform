/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { withWizardScreen, CheckboxControl, Grid, SelectControl } from '../../../../components/src';

const ENDPOINT = `/newspack/v1/wizard/newspack-popups-wizard/settings`;

const Settings = ( { isLoading, wizardApiFetch } ) => {
	const [ settings, setSettings ] = useState( [] );

	const handleSettingChange = option_name => option_value => {
		wizardApiFetch( {
			path: ENDPOINT,
			method: 'POST',
			quiet: true,
			data: { option_name, option_value },
		} ).then( setSettings );
	};

	useEffect( () => {
		wizardApiFetch( {
			path: ENDPOINT,
			method: 'GET',
		} ).then( setSettings );
	}, [] );

	const renderSetting = setting => {
		if ( setting.label ) {
			const props = {
				key: setting.key,
				label: setting.label,
				help: setting.help,
				disabled: isLoading,
				onChange: handleSettingChange( setting.key ),
			};
			switch ( setting.type ) {
				case 'select':
					return (
						<SelectControl
							{ ...props }
							value={ setting.value }
							options={ [ { label: setting.no_option_text, value: '' }, ...setting.options ] }
						/>
					);
				default:
					return <CheckboxControl { ...props } checked={ setting.value } />;
			}
		}
		return null;
	};

	return (
		<Grid gutter={ 32 } rowGap={ 16 }>
			{ settings.map( renderSetting ) }
		</Grid>
	);
};

export default withWizardScreen( Settings );
