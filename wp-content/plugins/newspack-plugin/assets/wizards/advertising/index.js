import '../../shared/js/public-path';

/**
 * Advertising
 */

/**
 * WordPress dependencies.
 */
import { Component, render, Fragment, createElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router';
import { AdUnit, AdUnits, HeaderCode, Placements, Services } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

class AdvertisingWizard extends Component {
	/**
	 * Constructor.
	 */
	constructor() {
		super( ...arguments );
		this.state = {
			advertisingData: {
				adUnits: [],
				placements: {
					global_above_header: {},
					global_below_header: {},
					global_above_footer: {},
					archives: {},
					search_results: {},
					sticky: {},
				},
				services: {
					google_ad_manager: {},
					google_adsense: {},
					wordads: {},
				},
			},
		};
	}

	/**
	 * wizardReady will be called when all plugin requirements are met.
	 */
	onWizardReady = () => {
		this.fetchAdvertisingData();
	};

	/**
	 * Retrieve advertising data
	 */
	fetchAdvertisingData = () => {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( { path: '/newspack/v1/wizard/advertising' } )
			.then( advertisingData => {
				return new Promise( resolve => {
					this.setState(
						{
							advertisingData: this.prepareData( advertisingData ),
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
	 * Toggle advertising service.
	 */
	toggleService( service, enabled ) {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( {
			path: '/newspack/v1/wizard/advertising/service/' + service,
			method: enabled ? 'POST' : 'DELETE',
			quiet: true,
		} )
			.then( advertisingData => {
				return new Promise( resolve => {
					this.setState(
						{
							advertisingData: this.prepareData( advertisingData ),
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
	}

	/**
	 * Toggle placement.
	 */
	togglePlacement( placement, enabled ) {
		const { setError, wizardApiFetch } = this.props;
		return wizardApiFetch( {
			path: '/newspack/v1/wizard/advertising/placement/' + placement,
			method: enabled ? 'POST' : 'DELETE',
			quiet: true,
		} )
			.then( advertisingData => {
				return new Promise( resolve => {
					this.setState(
						{
							advertisingData: this.prepareData( advertisingData ),
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
	}

	/**
	 * Update GAM Network Code.
	 */
	updateNetworkCode = ( code, service ) => {
		const { advertisingData } = this.state;
		advertisingData.services[ service ].network_code = code;
		this.setState( { advertisingData } );
	};

	/**
	 * Save Network Code.
	 */
	saveNetworkCode = service => {
		const { setError, wizardApiFetch } = this.props;
		const { advertisingData } = this.state;
		const network_code = advertisingData.services[ service ].network_code;
		return new Promise( ( resolve, reject ) => {
			wizardApiFetch( {
				path: '/newspack/v1/wizard/advertising/service/' + service + '/network_code',
				method: 'post',
				quiet: true,
				data: {
					network_code,
				},
			} )
				.then( data => {
					this.setState(
						{
							advertisingData: this.prepareData( data ),
						},
						() => {
							setError();
							resolve( this.state );
						}
					);
				} )
				.catch( error => {
					setError( error ).then( () => reject( error ) );
				} );
		} );
	};

	/**
	 * Save placement.
	 */
	savePlacement = ( placement, data ) => {
		const { setError, wizardApiFetch } = this.props;
		return new Promise( ( resolve, reject ) => {
			wizardApiFetch( {
				path: '/newspack/v1/wizard/advertising/placement/' + placement,
				method: 'post',
				data: {
					ad_unit: data.adUnit,
					service: data.service,
				},
				quiet: true,
			} )
				.then( advertisingData => {
					this.setState(
						{
							advertisingData: this.prepareData( advertisingData ),
						},
						() => {
							setError();
							resolve( this.state );
						}
					);
				} )
				.catch( error => {
					setError( error ).then( () => reject( error ) );
				} );
		} );
	};

	/**
	 * Update a single ad unit.
	 */
	onAdUnitChange = adUnit => {
		const { advertisingData } = this.state;
		advertisingData.adUnits[ adUnit.id ] = adUnit;
		this.setState( { advertisingData } );
	};

	/**
	 * Save the fields to an ad unit.
	 */
	saveAdUnit( id ) {
		const { setError, wizardApiFetch } = this.props;
		const { adUnits } = this.state.advertisingData;
		const adUnit = adUnits[ id ];
		const { name, code, sizes, ad_service } = adUnit;
		const data = {
			id,
			code,
			name,
			sizes,
			ad_service,
		};
		return new Promise( ( resolve, reject ) => {
			wizardApiFetch( {
				path: '/newspack/v1/wizard/advertising/ad_unit/' + ( id || 0 ),
				method: 'post',
				data,
				quiet: true,
			} )
				.then( advertisingData => {
					this.setState(
						{
							advertisingData: this.prepareData( advertisingData ),
						},
						() => {
							setError();
							resolve( this.state );
						}
					);
				} )
				.catch( error => {
					setError( error ).then( () => reject( error ) );
				} );
		} );
	}

	/**
	 * Delete an ad unit.
	 *
	 * @param {number} id Ad Unit ID.
	 */
	deleteAdUnit( id ) {
		const { setError, wizardApiFetch } = this.props;
		// eslint-disable-next-line no-alert
		if ( confirm( __( 'Are you sure you want to delete this ad unit?', 'newspack' ) ) ) {
			wizardApiFetch( {
				path: '/newspack/v1/wizard/advertising/ad_unit/' + id,
				method: 'delete',
				quiet: true,
			} )
				.then( advertisingData => {
					this.setState(
						{
							advertisingData: this.prepareData( advertisingData ),
						},
						() => {
							setError();
						}
					);
				} )
				.catch( error => {
					setError( error );
				} );
		}
	}

	prepareData = data => {
		return {
			services: data.services,
			placements: data.placements,
			adUnits: data.ad_units.reduce( ( result, value ) => {
				result[ value.id ] = value;
				return result;
			}, {} ),
		};
	};

	/**
	 * Render
	 */
	render() {
		const { advertisingData } = this.state;
		const { pluginRequirements } = this.props;
		const { services, placements, adUnits } = advertisingData;
		const tabs = [
			{
				label: __( 'Ad Providers', 'newspack' ),
				path: '/',
				exact: true,
			},
			{
				label: __( 'Global Settings', 'newspack' ),
				path: '/ad-placements',
			},
		];
		const gam_tabs = [
			{
				label: __( 'Individual Ad Units', 'newspack' ),
				path: '/google_ad_manager',
			},
			{
				label: __( 'Global Code', 'newspack' ),
				path: '/google_ad_manager-global-codes',
			},
		];
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						{ pluginRequirements }
						<Route
							path="/"
							exact
							render={ () => (
								<Services
									headerText={ __( 'Advertising', 'newspack' ) }
									subHeaderText={ __( 'Monetize your content through advertising', 'newspack' ) }
									services={ services }
									toggleService={ ( service, value ) => this.toggleService( service, value ) }
									tabbedNavigation={ tabs }
								/>
							) }
						/>
						<Route
							path="/ad-placements"
							render={ () => (
								<Placements
									headerText={ __( 'Advertising', 'newspack' ) }
									subHeaderText={ __( 'Monetize your content through advertising', 'newspack' ) }
									placements={ placements }
									adUnits={ adUnits }
									services={ services }
									onChange={ ( placement, data ) => this.savePlacement( placement, data ) }
									togglePlacement={ ( placement, value ) =>
										this.togglePlacement( placement, value )
									}
									tabbedNavigation={ tabs }
								/>
							) }
						/>
						<Route
							path="/google_ad_manager"
							exact
							render={ () => (
								<AdUnits
									headerText={ __( 'Google Ad Manager', 'newspack' ) }
									subHeaderText={ __( 'Monetize your content through advertising', 'newspack' ) }
									adUnits={ adUnits }
									tabbedNavigation={ gam_tabs }
									service={ 'google_ad_manager' }
									onDelete={ id => this.deleteAdUnit( id ) }
									buttonText={ __( 'Add an individual ad unit', 'newspack' ) }
									buttonAction="#/google_ad_manager/create"
									secondaryButtonText={ __( 'Back to advertising options', 'newspack' ) }
									secondaryButtonAction="#/"
								/>
							) }
						/>
						<Route
							path="/google_ad_manager-global-codes"
							exact
							render={ routeProps => (
								<HeaderCode
									headerText={ __( 'Google Ad Manager', 'newspack' ) }
									subHeaderText={ __( 'Monetize your content through advertising', 'newspack' ) }
									adUnits={ adUnits }
									code={ advertisingData.services.google_ad_manager.network_code }
									tabbedNavigation={ gam_tabs }
									service={ 'google_ad_manager' }
									onChange={ value => this.updateNetworkCode( value, 'google_ad_manager' ) }
									buttonText={ __( 'Save', 'newspack' ) }
									buttonAction={ () =>
										this.saveNetworkCode( 'google_ad_manager' ).then( () =>
											routeProps.history.push( '/google_ad_manager' )
										)
									}
									secondaryButtonText={ __( "I'm done configuring ads", 'newspack' ) }
									secondaryButtonAction="#/google_ad_manager"
								/>
							) }
						/>
						<Route
							path="/google_ad_manager/create"
							render={ routeProps => {
								return (
									<AdUnit
										headerText={ __( 'Add an ad unit', 'newspack' ) }
										subHeaderText={ __(
											'Setting up individual ad units allows you to place ads on your site through our Google Ad Manager Gutenberg block.',
											'newspack'
										) }
										adUnit={
											adUnits[ 0 ] || {
												id: 0,
												name: '',
												code: '',
												sizes: [ [ 120, 120 ] ],
											}
										}
										service={ 'google_ad_manager' }
										onChange={ this.onAdUnitChange }
										onSave={ id =>
											this.saveAdUnit( id ).then( () => {
												routeProps.history.push( '/google_ad_manager' );
											} )
										}
									/>
								);
							} }
						/>
						<Route
							path="/google_ad_manager/:id"
							render={ routeProps => {
								return (
									<AdUnit
										headerText={ __( 'Edit Ad Unit', 'newspack' ) }
										subHeaderText={ __(
											'Allows you to place ads on your site through our Ads block',
											'newspack'
										) }
										adUnit={ adUnits[ routeProps.match.params.id ] || {} }
										service={ 'google_ad_manager' }
										onChange={ this.onAdUnitChange }
										onSave={ id =>
											this.saveAdUnit( id ).then( () => {
												routeProps.history.push( '/google_ad_manager' );
											} )
										}
									/>
								);
							} }
						/>
						<Redirect to="/" />
					</Switch>
				</HashRouter>
			</Fragment>
		);
	}
}

render(
	createElement( withWizard( AdvertisingWizard, [ 'newspack-ads' ] ) ),
	document.getElementById( 'newspack-advertising-wizard' )
);
