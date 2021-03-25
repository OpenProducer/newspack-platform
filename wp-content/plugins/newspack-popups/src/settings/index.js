/* globals newspack_popups_settings */

/**
 * WordPress dependencies
 */
import { render, useState } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * External dependencies
 */
import { Card, Grid, FormattedHeader, CheckboxControl, SelectControl } from 'newspack-components';
import HeaderIcon from '@material-ui/icons/Settings';

/**
 * Internal dependencies
 */
import './style.scss';

const App = () => {
	const [ inFlight, setInFlight ] = useState( false );
	const [ settings, setSettings ] = useState( newspack_popups_settings );
	const handleSettingChange = option_name => option_value => {
		setInFlight( true );
		apiFetch( {
			path: '/newspack-popups/v1/settings/',
			method: 'POST',
			data: { option_name, option_value },
		} ).then( response => {
			setSettings( response );
			setInFlight( false );
		} );
	};

	const renderSetting = setting => {
		if ( setting.label ) {
			const props = {
				key: setting.key,
				label: setting.label,
				help: setting.help,
				disabled: inFlight,
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
					return <CheckboxControl { ...props } checked={ setting.value === '1' } />;
			}
		}
		return null;
	};

	return (
		<Grid>
			<FormattedHeader
				headerIcon={ <HeaderIcon /> }
				headerText={ __( 'Campaigns Settings', 'newspack-popups' ) }
			/>
			<Card>{ settings.map( renderSetting ) }</Card>
		</Grid>
	);
};

domReady( () => {
	const element = document.getElementById( 'newspack-popups-settings-root' );
	render( <App />, element );
} );
