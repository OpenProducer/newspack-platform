/**
 * Plugin Installation Progress Screen.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { PluginInstaller, withWizardScreen } from '../../../../components/src';

/**
 * Plugin Installation Screen.
 */
class InstallationProgress extends Component {
	/**
	 * Render.
	 */
	render() {
		const { autoInstall, plugins, onStatus } = this.props;
		return (
			<PluginInstaller autoInstall={ autoInstall } plugins={ plugins } onStatus={ onStatus } />
		);
	}
}

export default withWizardScreen( InstallationProgress );
