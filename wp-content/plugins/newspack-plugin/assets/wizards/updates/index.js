import '../../shared/js/public-path';

/**
 * Updates
 */

/**
 * WordPress dependencies.
 */
import { Component, render, createElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router';
import { DevInfo } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

class UpdatesWizard extends Component {
	/**
	 * Render
	 */
	render() {
		return (
			<HashRouter hashType="slash">
				<Switch>
					<Route
						path="/"
						exact
						render={ () => (
							<DevInfo
								headerText={ __( 'Updates', 'newspack' ) }
								subHeaderText={ __( 'Updates to the Newspack plugins and theme', 'newspack' ) }
							/>
						) }
					/>
					<Redirect to="/" />
				</Switch>
			</HashRouter>
		);
	}
}

render(
	createElement( withWizard( UpdatesWizard, [] ) ),
	document.getElementById( 'newspack-updates-wizard' )
);
