import '../../shared/js/public-path';

/**
 * Reader Revenue
 */

/**
 * WordPress dependencies.
 */
import { Component, render, Fragment, createElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router';
import {
	Donation,
	LocationSetup,
	NRHSettings,
	Platform,
	Services,
	StripeSetup,
	Salesforce,
} from './views';
import { NEWSPACK, NRH } from './constants';

const { HashRouter, Redirect, Route, Switch } = Router;
const headerText = __( 'Reader revenue', 'newspack' );
const subHeaderText = __( 'Generate revenue from your customers.', 'newspack' );

class ReaderRevenueWizard extends Component {
	/**
	 * Constructor.
	 */
	constructor() {
		super( ...arguments );
		this.state = {
			data: {
				locationData: {},
				stripeData: {},
				donationData: {},
				salesforceData: {},
				platformData: {},
				pluginStatus: false,
			},
		};
	}

	/**
	 * wizardReady will be called when all plugin requirements are met.
	 */
	onWizardReady = () => this.fetch();

	/**
	 * Retrieve data model
	 */
	fetch = () => {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( { path: '/newspack/v1/wizard/newspack-reader-revenue-wizard' } )
			.then( data => {
				return new Promise( resolve => {
					this.setState(
						{
							data: this.parseData( data ),
						},
						() => {
							setError();
							resolve( this.state );
						}
					);
				} );
			} )
			.catch( error => {
				setError( error );
			} );
	};

	/**
	 * Update data model
	 */
	update = ( screen, data ) => {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( {
			path: '/newspack/v1/wizard/newspack-reader-revenue-wizard/' + screen,
			method: 'POST',
			data,
		} )
			.then( _data => {
				return new Promise( resolve => {
					this.setState(
						{
							data: this.parseData( _data ),
						},
						() => {
							setError();
							resolve( this.state );
						}
					);
				} );
			} )
			.catch( error => {
				setError( error );
			} );
	};

	/**
	 * Parse API data
	 */
	parseData = data => ( {
		locationData: data.location_data,
		stripeData: data.stripe_data,
		donationData: data.donation_data,
		countryStateFields: data.country_state_fields,
		currencyFields: data.currency_fields,
		donationPage: data.donation_page,
		salesforceData: data.salesforce_settings,
		platformData: data.platform_data,
		pluginStatus: data.plugin_status,
	} );

	/**
	 * Button handler for Salesforce wizard.
	 */
	handleSalesforce = async () => {
		const { wizardApiFetch } = this.props;
		const { data } = this.state;
		const { salesforceData } = data;
		const salesforceIsConnected = !! salesforceData.refresh_token;

		// If Salesforce is already connected, button should reset settings.
		if ( salesforceIsConnected ) {
			const defaultSettings = {
				client_id: '',
				client_secret: '',
				access_token: '',
				refresh_token: '',
				instance_url: '',
			};

			this.setState( { data: { ...data, salesforceData: defaultSettings } } );
			return this.update( 'salesforce', defaultSettings );
		}

		// Otherwise, attempt to establish a connection with Salesforce.
		const { client_id, client_secret } = salesforceData;

		if ( client_id && client_secret ) {
			const loginUrl = addQueryArgs( 'https://login.salesforce.com/services/oauth2/authorize', {
				response_type: 'code',
				client_id: encodeURIComponent( client_id ),
				client_secret: encodeURIComponent( client_secret ),
				redirect_uri: encodeURI( window.location.href ),
			} );

			// Save credentials to options table.
			await this.update( 'salesforce', salesforceData );

			// Validate credentials before redirecting.
			const valid = await wizardApiFetch( {
				path: '/newspack/v1/wizard/salesforce/validate',
				method: 'POST',
				data: {
					client_id,
					client_secret,
					redirect_uri: window.location.href,
				},
			} );

			if ( valid ) {
				return window.location.assign( loginUrl );
			}

			this.setState( {
				data: { ...data, salesforceData: { ...salesforceData, error: 'invalid_credentials' } },
			} );
		}
	};

	/**
	 * Get navigation tabs dependant on selected platform.
	 */
	navigationForPlatform = ( platform, data ) => {
		if ( NEWSPACK === platform ) {
			const { pluginStatus } = data;
			if ( ! pluginStatus ) {
				return [];
			}
			return [
				{
					label: __( 'Donations', 'newspack' ),
					path: '/donations',
					exact: true,
				},
				{
					label: __( 'Salesforce', 'newspack' ),
					path: '/salesforce',
					exact: true,
				},
				{
					label: __( 'Address', 'newspack' ),
					path: '/location-setup',
				},
				{
					label: __( 'Stripe', 'newspack' ),
					path: '/stripe-setup',
				},
				{
					label: __( 'Platform', 'newspack' ),
					path: '/',
					exact: true,
				},
			];
		} else if ( NRH === platform ) {
			return [
				{
					label: __( 'Settings', 'newspack' ),
					path: '/settings',
					exact: true,
				},
				{
					label: __( 'Platform', 'newspack' ),
					path: '/',
					exact: true,
				},
			];
		}
		return null;
	};

	/**
	 * Render
	 */
	render() {
		const { pluginRequirements, wizardApiFetch } = this.props;
		const { data } = this.state;
		const {
			countryStateFields,
			currencyFields,
			locationData,
			stripeData,
			donationData,
			donationPage,
			salesforceData,
			platformData,
			pluginStatus,
		} = data;
		const { platform } = platformData;
		const salesforceIsConnected = !! salesforceData.refresh_token;
		const tabbedNavigation = this.navigationForPlatform( platform, data );
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						{ pluginRequirements }
						<Route
							path="/"
							exact
							render={ () => (
								<Platform
									data={ platformData }
									pluginStatus={ pluginStatus }
									headerText={ headerText }
									subHeaderText={ subHeaderText }
									tabbedNavigation={ tabbedNavigation }
									onChange={ _platformData => this.update( '', _platformData ) }
									onReady={ () => {
										this.setState( { data: { ...data, pluginStatus: true } } );
									} }
								/>
							) }
						/>
						<Route
							path="/services"
							exact
							render={ () => (
								<Services
									data={ platformData }
									headerText={ headerText }
									subHeaderText={ subHeaderText }
									tabbedNavigation={ tabbedNavigation }
								/>
							) }
						/>
						<Route
							path="/settings"
							exact
							render={ () => (
								<NRHSettings
									data={ platformData }
									headerText={ headerText }
									subHeaderText={ subHeaderText }
									tabbedNavigation={ tabbedNavigation }
									buttonText={ __( 'Update', 'newspack' ) }
									buttonAction={ () => this.update( '', platformData ) }
									onChange={ _platformData =>
										this.setState( { data: { ...data, platformData: _platformData } } )
									}
								/>
							) }
						/>
						<Route
							path="/location-setup"
							render={ () => (
								<LocationSetup
									data={ locationData }
									countryStateFields={ countryStateFields }
									currencyFields={ currencyFields }
									headerText={ headerText }
									subHeaderText={ subHeaderText }
									buttonText={ __( 'Save Settings', 'newspack' ) }
									buttonAction={ () => this.update( 'location', locationData ) }
									tabbedNavigation={ tabbedNavigation }
									onChange={ _locationData =>
										this.setState( { data: { ...data, locationData: _locationData } } )
									}
								/>
							) }
						/>
						<Route
							path="/stripe-setup"
							render={ () => (
								<StripeSetup
									data={ stripeData }
									headerText={ headerText }
									subHeaderText={ subHeaderText }
									buttonText={ __( 'Save Settings', 'newspack' ) }
									buttonAction={ () => this.update( 'stripe', stripeData ) }
									tabbedNavigation={ tabbedNavigation }
									onChange={ _stripeData =>
										this.setState( { data: { ...data, stripeData: _stripeData } } )
									}
								/>
							) }
						/>
						<Route
							path="/donations"
							render={ () => (
								<Donation
									data={ donationData }
									headerText={ __( 'Set up donations' ) }
									subHeaderText={ __(
										'Configure your landing page and your suggested donation presets.'
									) }
									donationPage={ donationPage }
									buttonText={ __( 'Save Settings' ) }
									buttonAction={ () => this.update( 'donations', donationData ) }
									onChange={ _donationData =>
										this.setState( { data: { ...data, donationData: _donationData } } )
									}
									tabbedNavigation={ tabbedNavigation }
								/>
							) }
						/>
						<Route
							path="/salesforce"
							render={ routeProps => (
								<Salesforce
									routeProps={ routeProps }
									data={ salesforceData }
									headerText={ __( 'Configure Salesforce', 'newspack' ) }
									isConnected={ salesforceIsConnected }
									subHeaderText={ __(
										'Connect your site with a Salesforce account to capture donor contact information.',
										'newspack'
									) }
									buttonText={
										salesforceIsConnected ? __( 'Reset', 'newspack' ) : __( 'Connect', 'newspack' )
									}
									buttonAction={ this.handleSalesforce }
									buttonDisabled={
										! salesforceIsConnected &&
										( ! salesforceData.client_id || ! salesforceData.client_secret )
									}
									onChange={ _salesforceData =>
										this.setState( { data: { ...data, salesforceData: _salesforceData } } )
									}
									tabbedNavigation={ tabbedNavigation }
									wizardApiFetch={ wizardApiFetch }
								/>
							) }
						/>
						<Redirect to="/" />
					</Switch>
				</HashRouter>
			</Fragment>
		);
	}
}

render(
	createElement( withWizard( ReaderRevenueWizard, [ 'newspack-blocks' ] ) ),
	document.getElementById( 'newspack-reader-revenue-wizard' )
);
