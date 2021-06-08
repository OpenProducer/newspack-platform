/**
 * Remove unsupported plugins.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, Button, Notice, withWizardScreen } from '../../../../components/src';

/**
 * SEO Intro screen.
 */
class Plugins extends Component {
	/**
	 * Render.
	 */
	render() {
		const { unsupportedPlugins, deactivateAllPlugins } = this.props;
		return (
			<Fragment>
				{ unsupportedPlugins && unsupportedPlugins.length > 0 && (
					<Fragment>
						<Notice noticeText={ __( 'Newspack does not support these plugins:' ) } isError />
						{ unsupportedPlugins.map( unsupportedPlugin => (
							<ActionCard
								title={ unsupportedPlugin.Name }
								key={ unsupportedPlugin.Slug }
								description={ unsupportedPlugin.Description }
								className="newspack-card__is-unsupported"
							/>
						) ) }
						<div className="newspack-buttons-card">
							<Button isPrimary onClick={ deactivateAllPlugins }>
								{ __( 'Deactivate All' ) }
							</Button>
						</div>
					</Fragment>
				) }
				{ unsupportedPlugins && unsupportedPlugins.length === 0 && (
					<Fragment>
						<Notice noticeText={ __( 'No unsupported plugins found.' ) } isSuccess />
					</Fragment>
				) }
			</Fragment>
		);
	}
}

export default withWizardScreen( Plugins );
