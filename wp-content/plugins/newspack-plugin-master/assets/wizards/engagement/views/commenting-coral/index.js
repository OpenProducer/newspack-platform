/**
 * The Coral Project screen.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * The Coral Project dependencies
 */
import { ActionCard, PluginInstaller, withWizardScreen } from '../../../../components/src';

/**
 * Commenting Screen
 */
class CommentingCoral extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			pluginRequirementsMet: false,
		};
	}
	/**
	 * Render.
	 */
	render() {
		const { pluginRequirementsMet } = this.state;
		return ! pluginRequirementsMet ? (
			<PluginInstaller
				plugins={ [ 'talk-wp-plugin' ] }
				onStatus={ ( { complete } ) => this.setState( { pluginRequirementsMet: complete } ) }
			/>
		) : (
			<ActionCard
				title={ __( 'The Coral Project' ) }
				description={ __( 'Description TK.' ) }
				actionText={ __( 'Configure' ) }
				handoff="talk-wp-plugin"
			/>
		);
	}
}

export default withWizardScreen( CommentingCoral );
