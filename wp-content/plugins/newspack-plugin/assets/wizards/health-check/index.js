import '../../shared/js/public-path';

/**
 * Health Check
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
import { Configuration, Plugins } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

class HealthCheckWizard extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			hasData: false,
			healthCheckData: {
				unsupportedPlugins: [],
			},
		};
	}
	onWizardReady = () => {
		this.fetchHealthData();
	};

	fetchHealthData = () => {
		const { wizardApiFetch, setError } = this.props;
		wizardApiFetch( { path: '/newspack/v1/wizard/newspack-health-check-wizard/' } )
			.then( healthCheckData => this.setState( { healthCheckData, hasData: true } ) )
			.catch( error => {
				setError( error );
			} );
	};

	deactivateAllPlugins = () => {
		const { wizardApiFetch, setError } = this.props;
		wizardApiFetch( {
			path: '/newspack/v1/wizard/newspack-health-check-wizard/unsupported_plugins',
			method: 'delete',
		} )
			.then( healthCheckData => this.setState( { healthCheckData } ) )
			.catch( error => {
				setError( error );
			} );
	};

	repairConfiguration = configuration => {
		const { wizardApiFetch, setError } = this.props;
		wizardApiFetch( {
			path: '/newspack/v1/wizard/newspack-health-check-wizard/repair/' + configuration,
		} )
			.then( healthCheckData => this.setState( { healthCheckData } ) )
			.catch( error => {
				setError( error );
			} );
	};
	/**
	 * Render
	 */
	render() {
		const { hasData, healthCheckData } = this.state;
		const {
			unsupported_plugins: unsupportedPlugins,
			configuration_status: configurationStatus,
		} = healthCheckData;
		const tabs = [
			{
				label: __( 'Plugins', 'newspack' ),
				path: '/',
				exact: true,
			},
			{
				label: __( 'Configuration' ),
				path: '/configuration',
			},
		];
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						<Route
							path="/"
							exact
							render={ () => (
								<Plugins
									headerText={ __( 'Health Check', 'newspack' ) }
									subHeaderText={ __( 'Verify and correct site health issues', 'newspack' ) }
									deactivateAllPlugins={ this.deactivateAllPlugins }
									tabbedNavigation={ tabs }
									unsupportedPlugins={
										unsupportedPlugins &&
										Object.keys( unsupportedPlugins ).map( value => ( {
											...unsupportedPlugins[ value ],
											Slug: value,
										} ) )
									}
								/>
							) }
						/>
						<Route
							path="/configuration"
							exact
							render={ () => (
								<Configuration
									hasData={ hasData }
									headerText={ __( 'Health Check', 'newspack' ) }
									subHeaderText={ __( 'Verify and correct site health issues', 'newspack' ) }
									tabbedNavigation={ tabs }
									configurationStatus={ configurationStatus }
									repairConfiguration={ this.repairConfiguration }
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
	createElement( withWizard( HealthCheckWizard ) ),
	document.getElementById( 'newspack-health-check-wizard' )
);
