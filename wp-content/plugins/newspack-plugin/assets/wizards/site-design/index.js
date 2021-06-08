import '../../shared/js/public-path';

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
import { ThemeSettings, Main } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

/**
 * Site Design Wizard.
 */
class SiteDesignWizard extends Component {
	componentDidMount = () => {
		const { setError, wizardApiFetch } = this.props;
		const params = {
			path: '/newspack/v1/wizard/newspack-setup-wizard/theme',
			method: 'GET',
		};
		wizardApiFetch( params )
			.then( response => this.setState( { themeMods: response.theme_mods } ) )
			.catch( setError );
	};

	setThemeMods = themeModUpdates =>
		this.setState( { themeMods: { ...this.state.themeMods, ...themeModUpdates } } );

	updateThemeMods = () => {
		const { setError, wizardApiFetch } = this.props;
		const { themeMods } = this.state;
		const params = {
			path: '/newspack/v1/wizard/newspack-setup-wizard/theme-mods/',
			method: 'POST',
			data: { theme_mods: themeMods },
			quiet: true,
		};
		wizardApiFetch( params )
			.then( response => {
				const { theme, theme_mods } = response;
				this.setState( { theme, themeMods: theme_mods } );
			} )
			.catch( error => {
				console.log( '[Theme Update Error]', error );
				setError( { error } );
			} );
	};

	state = {};

	/**
	 * Render
	 */
	render() {
		const { pluginRequirements, wizardApiFetch, setError } = this.props;
		const tabbedNavigation = [
			{
				label: __( 'Design' ),
				path: '/',
				exact: true,
			},
			{
				label: __( 'Settings' ),
				path: '/settings',
				exact: true,
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
							render={ () => {
								return (
									<Main
										headerText={ __( 'Site Design', 'newspack' ) }
										subHeaderText={ __( 'Configure your Newspack theme', 'newspack' ) }
										tabbedNavigation={ tabbedNavigation }
										wizardApiFetch={ wizardApiFetch }
										setError={ setError }
										isPartOfSetup={ false }
									/>
								);
							} }
						/>
						<Route
							path="/settings"
							exact
							render={ () => {
								const { themeMods } = this.state;
								return (
									<ThemeSettings
										headerText={ __( 'Site Design', 'newspack' ) }
										subHeaderText={ __( 'Configure your Newspack theme', 'newspack' ) }
										tabbedNavigation={ tabbedNavigation }
										themeMods={ themeMods }
										setThemeMods={ this.setThemeMods }
										buttonText={ __( 'Save', 'newspack' ) }
										buttonAction={ this.updateThemeMods }
										secondaryButtonText={ __( 'Advanced settings', 'newspack' ) }
										secondaryButtonAction="/wp-admin/customize.php"
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
	createElement( withWizard( SiteDesignWizard ) ),
	document.getElementById( 'newspack-site-design-wizard' )
);
