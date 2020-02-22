/**
 * Disqus screen.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Disqus dependencies
 */
import { ActionCard, PluginInstaller, withWizardScreen } from '../../../../components/src';

/**
 * Commenting Screen
 */
class CommentingDisqus extends Component {
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
				plugins={ [ 'disqus-comment-system', 'newspack-disqus-amp' ] }
				onStatus={ ( { complete } ) => this.setState( { pluginRequirementsMet: complete } ) }
			/>
		) : (
			<ActionCard
				title={ __( 'Disqus' ) }
				description={ __( 'Description TK.' ) }
				actionText={ __( 'Configure' ) }
				handoff="disqus-comment-system"
			/>
		);
	}
}

export default withWizardScreen( CommentingDisqus );
