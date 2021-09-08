/* globals newspack_popups_settings */

/**
 * WordPress dependencies
 */
import { render, useState } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {
	Button,
	Card,
	CardBody,
	CardHeader,
	CheckboxControl,
	FlexBlock,
	SelectControl,
} from '@wordpress/components';

/**
 * Newspack dependencies.
 */
import { NewspackLogo } from 'newspack-components';

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
		<div className="newspack-campaigns__wrapper">
			<div className="newspack-logo__wrapper">
				<Button href="https://newspack.pub/" target="_blank" label={ __( 'By Newspack' ) }>
					<NewspackLogo height={ 32 } />
				</Button>
			</div>
			<Card>
				<CardHeader isShady>
					<FlexBlock>
						<h2>{ __( 'Settings', 'newspack-popups' ) }</h2>
					</FlexBlock>
				</CardHeader>
				<CardBody>{ settings.map( renderSetting ) }</CardBody>
			</Card>
		</div>
	);
};

domReady( () => {
	const element = document.getElementById( 'newspack-popups-settings-root' );
	render( <App />, element );
} );
