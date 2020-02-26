/**
 * WordPress dependencies.
 */
import { Component, render, Fragment, createElement } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Material UI dependencies.
 */
import SettingsIcon from '@material-ui/icons/Settings';
import StyleIcon from '@material-ui/icons/Style';

/**
 * Internal dependencies.
 */
import { withWizard } from '../../components/src';
import Router from '../../components/src/proxied-imports/router';
import { ThemeMods, ThemeSelection } from './views';

const { HashRouter, Redirect, Route, Switch } = Router;

/**
 * Site Design Wizard.
 */
class SiteDesignWizard extends Component {
	componentDidMount = () => {
		this.retrieveTheme();
	};

	retrieveTheme = () => {
		const { setError, wizardApiFetch } = this.props;
		const params = {
			path: '/newspack/v1/wizard/newspack-setup-wizard/theme',
			method: 'GET',
		};
		wizardApiFetch( params )
			.then( response => {
				const { theme, theme_mods: themeMods } = response;
				this.setState( { theme, themeMods } );
			} )
			.catch( error => {
				console.log( '[Theme Fetch Error]', error );
				setError( { error } );
			} );
	};

	updateTheme = newTheme => {
		const { setError, wizardApiFetch } = this.props;
		const params = {
			path: '/newspack/v1/wizard/newspack-setup-wizard/theme/' + newTheme,
			method: 'POST',
		};
		wizardApiFetch( params )
			.then( response => {
				const { theme, theme_mods: themeMods } = response;
				this.setState( { theme, themeMods } );
			} )
			.catch( error => {
				console.log( '[Theme Update Error]', error );
				setError( { error } );
			} );
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

	state = {
		theme: null,
	};

	/**
	 * Render
	 */
	render() {
		const { pluginRequirements } = this.props;
		const tabbedNavigation = [
			{
				label: __( 'Theme' ),
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
								const { theme } = this.state;
								return (
									<ThemeSelection
										headerIcon={ <StyleIcon /> }
										headerText={ __( 'Theme', 'newspack' ) }
										subHeaderText={ __( 'Choose a Newspack theme', 'newspack' ) }
										tabbedNavigation={ tabbedNavigation }
										buttonText={ __( 'Configure', 'newspack' ) }
										buttonAction="#/settings"
										updateTheme={ this.updateTheme }
										theme={ theme }
										isWide
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
									<ThemeMods
										headerIcon={ <SettingsIcon /> }
										headerText={ __( 'Settings', 'newspack' ) }
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
