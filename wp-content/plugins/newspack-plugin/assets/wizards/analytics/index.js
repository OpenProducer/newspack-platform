import '../../shared/js/public-path';

/**
 * Analytics
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
import { Plugins, CustomDimensions, CustomEvents } from './views';
import './style.scss';

const { HashRouter, Redirect, Route, Switch } = Router;

const TABS = [
	{
		label: __( 'Plugins', 'newspack' ),
		path: '/',
		exact: true,
	},
	{
		label: __( 'Custom Dimensions', 'newspack' ),
		path: '/custom-dimensions',
	},
	{
		label: __( 'Custom Events', 'newspack' ),
		path: '/custom-events',
	},
];

class AnalyticsWizard extends Component {
	/**
	 * Render
	 */
	render() {
		const { pluginRequirements, wizardApiFetch, isLoading } = this.props;
		const sharedProps = {
			headerText: __( 'Analytics', 'newspack' ),
			subHeaderText: __( 'Track traffic and activity', 'newspack' ),
			tabbedNavigation: TABS,
			wizardApiFetch,
			isLoading,
		};
		return (
			<Fragment>
				<HashRouter hashType="slash">
					<Switch>
						{ pluginRequirements }
						<Route
							path="/custom-dimensions"
							exact
							render={ () => <CustomDimensions { ...sharedProps } /> }
						/>
						<Route
							path="/custom-events"
							exact
							render={ () => <CustomEvents { ...sharedProps } /> }
						/>
						<Route path="/" exact render={ () => <Plugins { ...sharedProps } /> } />
						<Redirect to="/" />
					</Switch>
				</HashRouter>
			</Fragment>
		);
	}
}

render(
	createElement( withWizard( AnalyticsWizard, [ 'google-site-kit' ] ) ),
	document.getElementById( 'newspack-analytics-wizard' )
);
