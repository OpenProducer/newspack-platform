/**
 * Configure Plugins Screen
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { withWizardScreen, Notice } from '../../../../components/src';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Configure Plugins Screen
 */
class ConfigurePlugins extends Component {
	componentDidMount = () => {
		const { onMount, plugin } = this.props;
		onMount( plugin );
	};
	/**
	 * Render.
	 */
	render() {
		const { plugin, pluginConfigured } = this.props;
		const classNames = classnames( 'newspack-setup__configure-plugin-card', plugin );
		return (
			<div className="newspack-setup__configure-plugin">
				<div className={ classNames }>
					<h2>
						{ 'jetpack' === plugin && 'Jetpack' }
						{ 'google-site-kit' === plugin && 'Google Site Kit' }
					</h2>
					<p>
						{ 'jetpack' === plugin &&
							__(
								'The ideal plugin for stats, related posts, search engine optimization, social sharing, protection, backups, security, and more.'
							) }
						{ 'google-site-kit' === plugin &&
							__(
								'The one-stop solution to deploy, manage, and get insights from critical Google tools to make the site successful on the web.'
							) }
					</p>
					{ pluginConfigured && (
						<Notice isSuccess noticeText={ __( 'Plugin configuration complete' ) } />
					) }
				</div>
			</div>
		);
	}
}

ConfigurePlugins.defaultProps = {
	plugin: null,
	onMount: () => null,
};

export default withWizardScreen( ConfigurePlugins );
