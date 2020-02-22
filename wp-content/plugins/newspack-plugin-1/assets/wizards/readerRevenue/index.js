/**
 * Reader Revenue
 */

/**
 * WordPress dependencies.
 */
import { Component, render, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Material UI dependencies.
 */
import HeaderIcon from '@material-ui/icons/AccountBalanceWallet';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router'
import { ConfigureLandingPage, Donation, LocationSetup, StripeSetup, RevenueMain } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

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
			data: this.prepareData( screen, data ),
		} )
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
	 * Parse API data
	 */
	parseData = data => ( {
		locationData: data.location_data,
		stripeData: data.stripe_data,
		donationData: data.donation_data,
		countryStateFields: data.country_state_fields,
		currencyFields: data.currency_fields,
		donationPage: data.donation_page,
	} );

	/**
	 * Prepare data for API update.
	 */
	prepareData = ( screen, data ) => {
		if ( 'donations' === screen ) {
			data.imageID = data.image ? data.image.id : 0;
		}
		return data;
	};

	/**
	 * Render
	 */
	render() {
		const { pluginRequirements } = this.props;
		const { data } = this.state;
		const {
			countryStateFields,
			currencyFields,
			locationData,
			stripeData,
			donationData,
			donationPage,
		} = data;
		const tabbedNavigation = [
			{
				label: __( 'Welcome' ),
				path: '/',
				exact: true,
			},
			{
				label: __( 'Address' ),
				path: '/location-setup',
			},
			{
				label: __( 'Stripe' ),
				path: '/stripe-setup',
			},
			{
				label: __( 'Donations' ),
				path: '/donations',
			},
			{
				label: __( 'Memberships' ),
				path: '/configure-landing-page',
			},
		];
		const isConfigured = !! donationData.created;
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						{ pluginRequirements }
						<Route
							path="/"
							exact
							render={ routeProps => (
								<RevenueMain
									headerIcon={ <HeaderIcon /> }
									headerText={ __( 'Accept donations on your site' ) }
									subHeaderText={ __( 'Generate revenue from your customers.' ) }
									tabbedNavigation={ isConfigured && tabbedNavigation }
									buttonText={ ! isConfigured && __( 'Get Started' ) }
									buttonAction="#location-setup"
								/>
							) }
						/>
						<Route
							path="/location-setup"
							render={ routeProps => (
								<LocationSetup
									data={ locationData }
									countryStateFields={ countryStateFields }
									currencyFields={ currencyFields }
									headerIcon={ <HeaderIcon /> }
									headerText={ __( 'Set up address' ) }
									subHeaderText={ __( "Configure your publication's address." ) }
									buttonText={ isConfigured ? __( 'Save Settings' ) : __( 'Continue Setup' ) }
									buttonAction={ () =>
										this.update( 'location', locationData ).then(
											data => ! isConfigured && routeProps.history.push( 'stripe-setup' )
										)
									}
									tabbedNavigation={ isConfigured && tabbedNavigation }
									onChange={ locationData => this.setState( { data: { ...data, locationData } } ) }
								/>
							) }
						/>
						<Route
							path="/stripe-setup"
							render={ routeProps => (
								<StripeSetup
									data={ stripeData }
									headerIcon={ <HeaderIcon /> }
									headerText={ __( 'Set up Stripe' ) }
									subHeaderText={ __( 'Configure your payment gateway to process transactions.' ) }
									buttonText={ isConfigured ? __( 'Save Settings' ) : __( 'Continue Setup' ) }
									buttonAction={ () =>
										this.update( 'stripe', stripeData ).then(
											data => ! isConfigured && routeProps.history.push( 'donations' )
										)
									}
									tabbedNavigation={ isConfigured && tabbedNavigation }
									onChange={ stripeData => this.setState( { data: { ...data, stripeData } } ) }
								/>
							) }
						/>
						<Route
							path="/donations"
							render={ routeProps => (
								<Donation
									data={ donationData }
									headerIcon={ <HeaderIcon /> }
									headerText={ __( 'Set up donations' ) }
									subHeaderText={ __( 'Configure your suggested donation presets.' ) }
									buttonText={ __( 'Save Settings' ) }
									buttonAction={ () =>
										this.update( 'donations', donationData ).then(
											data => ! isConfigured && routeProps.history.push( '/configure-landing-page' )
										)
									}
									tabbedNavigation={ isConfigured && tabbedNavigation }
									onChange={ donationData => this.setState( { data: { ...data, donationData } } ) }
								/>
							) }
						/>
						<Route
							path="/configure-landing-page"
							render={ routeProps => (
								<ConfigureLandingPage
									headerIcon={ <HeaderIcon /> }
									headerText={ __( 'Set up memberships' ) }
									subHeaderText={ __( 'Configure your memberships landing page.' ) }
									tabbedNavigation={ isConfigured && tabbedNavigation }
									donationPage={ donationPage }
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
	createElement(
		withWizard( ReaderRevenueWizard, [
			'newspack-blocks',
			'woocommerce',
			'woocommerce-subscriptions',
			'woocommerce-name-your-price',
		] )
	),
	document.getElementById( 'newspack-reader-revenue-wizard' )
);
