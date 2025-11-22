/**
 * Nextdoor Onboarding View
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import { ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { ActionCard, Button, Card, Grid, Notice, SelectControl, TextControl } from '../../../../../../../../packages/components/src';
import { OnboardingProps } from '../types';

/**
 * Styles
 */
import './style.scss';

/**
 * Onboarding component.
 */
export const Onboarding = ( { settings, status, error, updateSettings, startOAuthFlow, claimPage, disconnect, setError }: OnboardingProps ) => {
	const [ clientId, setClientId ] = useState( settings.client_id || '' );
	const [ clientSecret, setClientSecret ] = useState( settings.client_secret || '' );
	const [ email, setEmail ] = useState( '' );
	const [ country, setCountry ] = useState( 'US' );
	const [ publicationUrl, setPublicationUrl ] = useState( settings.publication_url || '' );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ currentStep, setCurrentStep ] = useState( 1 );

	// Get country options and redirect URI from localized data
	const countryOptions = window.newspackSettings?.social?.nextdoor?.country_options || [];
	const redirectUri = window.newspackSettings?.social?.nextdoor?.redirect_uri || '';

	useEffect( () => {
		// Check URL params for OAuth success
		const urlParams = new URLSearchParams( window.location.search );
		if ( urlParams.get( 'oauth_success' ) === '1' ) {
			setCurrentStep( 3 );
			setError( null );
		}
	}, [] );

	useEffect( () => {
		// Check for OAuth error in URL params
		const urlParams = new URLSearchParams( window.location.search );
		const oauthError = urlParams.get( 'nextdoor_oauth_error' );

		if ( oauthError ) {
			setError( decodeURIComponent( oauthError ) );
		}
	}, [] );

	useEffect( () => {
		// Determine current step based on status
		if ( status.is_connected ) {
			setCurrentStep( 4 );
		} else if ( status.has_tokens ) {
			setCurrentStep( 3 );
		} else if ( status.has_credentials ) {
			setCurrentStep( 2 );
		} else {
			setCurrentStep( 1 );
		}
	}, [ status ] );

	const handleSaveCredentials = async () => {
		if ( ! clientId || ! clientSecret ) {
			setError( __( 'Please enter both Client ID and Client Secret.', 'newspack-plugin' ) );
			return;
		}

		try {
			setIsSaving( true );
			setError( null );
			await updateSettings( {
				client_id: clientId,
				client_secret: clientSecret,
			} );
			setCurrentStep( 2 );
		} finally {
			setIsSaving( false );
		}
	};

	const handleStartOAuth = async () => {
		if ( ! email ) {
			setError( __( 'Please enter your email address.', 'newspack-plugin' ) );
			return;
		}

		try {
			setIsSaving( true );
			setError( null );
			const response = await startOAuthFlow( email, country );

			// Redirect to login URL
			window.location.href = response.login_url ?? window.location.href;
		} finally {
			setIsSaving( false );
		}
	};

	const handleClaimPage = async () => {
		if ( ! publicationUrl ) {
			setError( __( 'Please enter your publication URL.', 'newspack-plugin' ) );
			return;
		}

		try {
			setIsSaving( true );
			setError( null );
			const result = await claimPage( publicationUrl );
			if ( result.success ) {
				window.location.reload();
			} else {
				setError( __( 'Failed to claim page.', 'newspack-plugin' ) );
			}
		} finally {
			setIsSaving( false );
		}
	};

	const handleDisconnect = async () => {
		try {
			setIsSaving( true );
			setError( null );
			await disconnect();
			setCurrentStep( 1 );
		} finally {
			setIsSaving( false );
		}
	};

	return (
		<>
			{ error && <Notice noticeText={ error } isError onClose={ () => setError( null ) } /> }

			{ /* Step 1: API Credentials */ }
			{ currentStep === 1 && (
				<Card>
					<p>{ __( 'To get started, you need to register your site with Nextdoor and obtain API credentials.', 'newspack-plugin' ) }</p>
					<div className="nextdoor-onboarding__redirect-uri-box">
						<strong>{ __( 'Redirect URI:', 'newspack-plugin' ) }</strong>
						<br />
						<div className="nextdoor-onboarding__redirect-uri-container">
							<code className="nextdoor-onboarding__redirect-uri-code">{ redirectUri }</code>
						</div>
						<small className="nextdoor-onboarding__redirect-uri-help">
							{ __( 'Use this URL as the Redirect URI when signing up for Nextdoor credentials.', 'newspack-plugin' ) }
						</small>
					</div>
					<p>
						<ExternalLink href="https://developer.nextdoor.com/reference/applying-for-access">
							{ __( 'Get your API credentials from Nextdoor Developer Portal', 'newspack-plugin' ) }
						</ExternalLink>
					</p>

					<Grid columns={ 1 } gutter={ 16 }>
						<TextControl
							label={ __( 'Client ID', 'newspack-plugin' ) }
							value={ clientId }
							onChange={ setClientId }
							placeholder={ __( 'Enter your Nextdoor Client ID', 'newspack-plugin' ) }
						/>
						<TextControl
							label={ __( 'Client Secret', 'newspack-plugin' ) }
							value={ clientSecret }
							onChange={ setClientSecret }
							type="password"
							placeholder={ __( 'Enter your Nextdoor Client Secret', 'newspack-plugin' ) }
						/>
					</Grid>

					<div className="newspack-buttons-card">
						<Button
							variant="primary"
							onClick={ handleSaveCredentials }
							disabled={ ! clientId || ! clientSecret || isSaving }
							isBusy={ isSaving }
						>
							{ __( 'Save & Continue', 'newspack-plugin' ) }
						</Button>
					</div>
				</Card>
			) }

			{ /* Step 2: Account Authentication */ }
			{ currentStep === 2 && (
				<Card>
					<p>{ __( 'Connect your Nextdoor account to authorize publishing articles.', 'newspack-plugin' ) }</p>

					<Grid columns={ 1 } gutter={ 16 }>
						<TextControl
							label={ __( 'Email Address', 'newspack-plugin' ) }
							value={ email }
							onChange={ setEmail }
							type="email"
							placeholder={ __( 'Enter your Nextdoor account email', 'newspack-plugin' ) }
							help={ __( 'This should be the email address associated with your Nextdoor account.', 'newspack-plugin' ) }
						/>
						<SelectControl
							label={ __( 'Country', 'newspack-plugin' ) }
							value={ country }
							onChange={ setCountry }
							options={ countryOptions }
						/>
					</Grid>

					<div className="newspack-buttons-card">
						<Button variant="primary" onClick={ handleStartOAuth } disabled={ ! email || isSaving } isBusy={ isSaving }>
							{ __( 'Connect Account', 'newspack-plugin' ) }
						</Button>
						<Button variant="secondary" onClick={ () => setCurrentStep( 1 ) }>
							{ __( 'Back', 'newspack-plugin' ) }
						</Button>
					</div>
				</Card>
			) }

			{ /* Step 3: Claim Page */ }
			{ currentStep === 3 && (
				<Card>
					<p>{ __( 'Claim your news page on Nextdoor to start publishing articles.', 'newspack-plugin' ) }</p>

					<Grid columns={ 1 } gutter={ 16 }>
						<TextControl
							label={ __( 'Publication URL', 'newspack-plugin' ) }
							value={ publicationUrl }
							onChange={ setPublicationUrl }
							type="url"
							placeholder={ __( 'https://yoursite.com', 'newspack-plugin' ) }
							help={ __( 'The main URL of your news publication.', 'newspack-plugin' ) }
						/>
					</Grid>

					<div className="newspack-buttons-card">
						<Button variant="primary" onClick={ handleClaimPage } disabled={ ! publicationUrl || isSaving } isBusy={ isSaving }>
							{ __( 'Claim Page', 'newspack-plugin' ) }
						</Button>
						<Button variant="secondary" onClick={ () => setCurrentStep( 2 ) }>
							{ __( 'Back', 'newspack-plugin' ) }
						</Button>
					</div>
				</Card>
			) }

			{ /* Step 4: Success */ }
			{ currentStep === 4 && status.is_connected && (
				<ActionCard
					title={ __( 'Nextdoor Connected Successfully!', 'newspack-plugin' ) }
					description={ __(
						'Your site is now connected to Nextdoor. You can start publishing articles to your local community.',
						'newspack-plugin'
					) }
					actionText={ __( 'Configure Settings', 'newspack-plugin' ) }
					handoff={ 'settings' }
					editLink="#/settings"
					hasGreyHeader={ false }
				/>
			) }

			{ /* Connection Status */ }
			{ currentStep > 1 && (
				<Card>
					<Grid columns={ 2 } gutter={ 16 }>
						<div>
							<div className="nextdoor-onboarding__status-label">{ __( 'API Credentials:', 'newspack-plugin' ) }</div>
							{ status.has_credentials ? (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--success">
									{ __( 'Configured', 'newspack-plugin' ) }
								</span>
							) : (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--error">
									{ __( 'Not configured', 'newspack-plugin' ) }
								</span>
							) }
						</div>
						<div>
							<div className="nextdoor-onboarding__status-label">{ __( 'Account Connected:', 'newspack-plugin' ) }</div>
							{ status.has_tokens ? (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--success">
									{ __( 'Yes', 'newspack-plugin' ) }
								</span>
							) : (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--error">
									{ __( 'No', 'newspack-plugin' ) }
								</span>
							) }
						</div>
						<div>
							<div className="nextdoor-onboarding__status-label">{ __( 'Page Claimed:', 'newspack-plugin' ) }</div>
							{ status.has_page ? (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--success">
									{ __( 'Yes', 'newspack-plugin' ) }
								</span>
							) : (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--error">
									{ __( 'No', 'newspack-plugin' ) }
								</span>
							) }
						</div>
						<div>
							<div className="nextdoor-onboarding__status-label">{ __( 'Overall Status:', 'newspack-plugin' ) }</div>
							{ status.is_connected ? (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--success">
									{ __( 'Connected', 'newspack-plugin' ) }
								</span>
							) : (
								<span className="nextdoor-onboarding__status-value nextdoor-onboarding__status-value--error">
									{ __( 'Not connected', 'newspack-plugin' ) }
								</span>
							) }
						</div>
					</Grid>

					{ status.is_connected && (
						<div className="newspack-buttons-card">
							<Button isDestructive onClick={ handleDisconnect } disabled={ isSaving } isBusy={ isSaving }>
								{ __( 'Disconnect', 'newspack-plugin' ) }
							</Button>
						</div>
					) }
				</Card>
			) }
		</>
	);
};

export default Onboarding;
