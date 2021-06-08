/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { Button, ExternalLink, SelectControl, Spinner, TextControl } from '@wordpress/components';
import { Fragment, useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ENTER } from '@wordpress/keycodes';
import { dispatch } from '@wordpress/data';

/**
 * External dependencies
 */
import classnames from 'classnames';
import { values } from 'lodash';

const { lockPostAutosaving, unlockPostAutosaving, savePost } = dispatch( 'core/editor' );

export default ( { onSetupStatus } ) => {
	const [ settings, setSettings ] = useState( {} );
	const [ inFlight, setInFlight ] = useState( false );
	const [ errors, setErrors ] = useState( {} );

	const commitSettings = () => {
		setInFlight( true );
		setErrors( {} );
		apiFetch( {
			path: '/newspack-newsletters/v1/settings',
			method: 'POST',
			data: settings,
		} )
			.then( results => {
				window.newspack_newsletters_data.service_provider = results.service_provider;
				unlockPostAutosaving( 'newsletters-modal-is-open-lock' );
				savePost().then( () => {
					setInFlight( false );
					setSettings( results );
					onSetupStatus( ! results.status );
				} );
			} )
			.catch( handleErrors );
	};

	const handleErrors = error => {
		if ( 'newspack_rest_forbidden' === error.code ) {
			setInFlight( false );
			setErrors( {
				newspack_newsletters_invalid_keys: __(
					'Only administrators can set API keys.',
					'newspack-newsletters'
				),
			} );
			return;
		}
		const allErrors = { [ error.code ]: error.message };
		( error.additional_errors || [] ).forEach(
			additionalError => ( allErrors[ additionalError.code ] = additionalError.message )
		);
		setErrors( allErrors );
		setInFlight( false );
	};

	useEffect(() => {
		lockPostAutosaving( 'newsletters-modal-is-open-lock' );
		setInFlight( true );
		apiFetch( { path: `/newspack-newsletters/v1/settings` } )
			.then( results => {
				setInFlight( false );
				setSettings( results );
				onSetupStatus( ! results.status );
			} )
			.catch( handleErrors );
	}, []);

	const { service_provider: serviceProvider = '', credentials = {} } = settings;

	const canSubmit = values( credentials ).join( '' ).length;

	const classes = classnames(
		'newspack-newsletters-modal__content',
		'newspack-newsletters-modal__settings',
		inFlight && 'newspack-newsletters-modal__in-flight'
	);

	const handleKeyDown = event => {
		if ( canSubmit && ENTER === event.keyCode ) {
			event.preventDefault();
			commitSettings();
		}
	};

	const setCredentials = key => value =>
		setSettings( { ...settings, credentials: { ...credentials, [ key ]: value } } );

	return (
		<Fragment>
			<div className={ classes }>
				<div className="newspack-newsletters-modal__settings-wrapper">
					{ inFlight && <Spinner /> }
					<h4>{ __( 'Select your email service provider', 'newspack-newsletters' ) }</h4>
					<SelectControl
						label={ __( 'Service Provider', 'newspack-newsletters' ) }
						value={ serviceProvider }
						disabled={ inFlight }
						onChange={ value => setSettings( { ...settings, service_provider: value } ) }
						options={ [
							{
								value: '',
								disabled: true,
								label: __( 'Select service provider', 'newspack-newsletters' ),
							},
							{ value: 'mailchimp', label: __( 'Mailchimp', 'newspack-newsletters' ) },
							{
								value: 'constant_contact',
								label: __( 'Constant Contact', 'newspack-newsletters' ),
							},
							{
								value: 'campaign_monitor',
								label: __( 'Campaign Monitor', 'newspack-newsletters' ),
							},
						] }
					/>
					<hr />
					{ 'mailchimp' === serviceProvider && (
						<Fragment>
							<h4>{ __( 'Enter your Mailchimp API key', 'newspack-newsletters' ) }</h4>
							<TextControl
								label={ __( 'Mailchimp API key', 'newspack-newsletters' ) }
								value={ credentials.api_key }
								onChange={ setCredentials( 'api_key' ) }
								disabled={ inFlight }
								onKeyDown={ handleKeyDown }
								className={ errors.newspack_newsletters_invalid_keys && 'has-error' }
							/>
							{ errors.newspack_newsletters_invalid_keys && (
								<p className="error">{ errors.newspack_newsletters_invalid_keys }</p>
							) }

							<p>
								<ExternalLink href="https://us1.admin.mailchimp.com/account/api/">
									{ __( 'Generate Mailchimp API key', 'newspack-newsletters' ) }
								</ExternalLink>
								<span className="separator"> | </span>
								<ExternalLink href="https://mailchimp.com/help/about-api-keys/">
									{ __( 'About Mailchimp API keys', 'newspack-newsletters' ) }
								</ExternalLink>
							</p>
							<hr />
						</Fragment>
					) }
					{ 'constant_contact' === serviceProvider && (
						<Fragment>
							<h4>
								{ __(
									'Enter your Constant Contact API key and access token',
									'newspack-newsletters'
								) }
							</h4>
							<TextControl
								label={ __( 'Constant Contact API key', 'newspack-newsletters' ) }
								value={ credentials.api_key }
								onChange={ setCredentials( 'api_key' ) }
								disabled={ inFlight }
								onKeyDown={ handleKeyDown }
								className={ errors.newspack_newsletters_invalid_keys && 'has-error' }
							/>
							<TextControl
								label={ __( 'Constant Contact Access token', 'newspack-newsletters' ) }
								value={ credentials.access_token }
								onChange={ setCredentials( 'access_token' ) }
								disabled={ inFlight }
								onKeyDown={ handleKeyDown }
								className={ errors.newspack_newsletters_invalid_keys && 'has-error' }
							/>
							{ errors.newspack_newsletters_invalid_keys && (
								<p className="error">{ errors.newspack_newsletters_invalid_keys }</p>
							) }

							<p>
								<ExternalLink href="https://constantcontact.mashery.com/">
									{ __( 'Get Constant Contact API key', 'newspack-newsletters' ) }
								</ExternalLink>
								<span className="separator"> | </span>
								<ExternalLink href="https://constantcontact.mashery.com/io-docs">
									{ __( 'Get Constant Contact access token', 'newspack-newsletters' ) }
								</ExternalLink>
							</p>
							<hr />
						</Fragment>
					) }
					{ 'campaign_monitor' === serviceProvider && (
						<Fragment>
							<h4>
								{ __(
									'Enter your Campaign Monitor API key and client ID',
									'newspack-newsletters'
								) }
							</h4>
							<TextControl
								label={ __( 'Campaign Monitor API key', 'newspack-newsletters' ) }
								value={ credentials.api_key }
								onChange={ setCredentials( 'api_key' ) }
								disabled={ inFlight }
								onKeyDown={ handleKeyDown }
								className={ errors.newspack_newsletters_invalid_keys && 'has-error' }
							/>
							<TextControl
								label={ __( 'Campaign Monitor Client ID', 'newspack-newsletters' ) }
								value={ credentials.client_id }
								onChange={ setCredentials( 'client_id' ) }
								disabled={ inFlight }
								onKeyDown={ handleKeyDown }
								className={ errors.newspack_newsletters_invalid_keys && 'has-error' }
							/>
							{ errors.newspack_newsletters_invalid_keys && (
								<p className="error">{ errors.newspack_newsletters_invalid_keys }</p>
							) }

							<p>
								<ExternalLink href="https://help.campaignmonitor.com/api-keys">
									{ __( 'Get Campaign Monitor API key and Client ID', 'newspack-newsletters' ) }
								</ExternalLink>
							</p>
						</Fragment>
					) }
				</div>
			</div>
			<div className="newspack-newsletters-modal__action-buttons">
				<Button isPrimary onClick={ commitSettings } disabled={ inFlight || ! canSubmit }>
					{ __( 'Save settings', 'newspack-newsletter' ) }
				</Button>
			</div>
		</Fragment>
	);
};
