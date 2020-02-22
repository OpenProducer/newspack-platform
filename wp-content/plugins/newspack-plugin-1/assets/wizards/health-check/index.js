/**
 * Health Check
 */

/**
 * WordPress dependencies.
 */
import { Component, render, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Material UI dependencies.
 */
 import HeaderIcon from '@material-ui/icons/Healing';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router'
import { RemoveUnsupportedPlugins } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

class HealthCheckWizard extends Component {
	constructor( props ) {
		super( props );
		this.state = {
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
			.then( healthCheckData => this.setState( { healthCheckData } ) )
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
	/**
	 * Render
	 */
	render() {
		const { healthCheckData } = this.state;
		const { unsupported_plugins: unsupportedPlugins } = healthCheckData;
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						<Route
							path="/"
							exact
							render={ routeProps => (
								<RemoveUnsupportedPlugins
									headerIcon={ <HeaderIcon /> }
									headerText={ __( 'Health Check', 'newspack' ) }
									subHeaderText={ __( 'Verify and correct site health issues', 'newspack' ) }
									deactivateAllPlugins={ this.deactivateAllPlugins }
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
