/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, PluginInstaller, withWizardScreen } from '../../../../components/src';

const Commenting = () => {
	const [ disqusActive, setDisqusActive ] = useState( false );
	const [ coralActive, setCoralActive ] = useState( false );
	return (
		<>
			<h2>{ __( 'WordPress comments', 'newspack' ) }</h2>
			<ActionCard
				title={ __( 'WordPress Commenting' ) }
				description={ __( 'Native WordPress commenting system.' ) }
				actionText={ __( 'Configure' ) }
				handoff="wordpress-settings-discussion"
			/>

			<h2>{ __( 'Disqus', 'newspack' ) }</h2>
			{ disqusActive ? (
				<ActionCard
					title={ __( 'Disqus', 'newspack' ) }
					description={ __( 'Disqus commenting system.' ) }
					actionText={ __( 'Configure' ) }
					handoff="disqus-comment-system"
				/>
			) : (
				<PluginInstaller
					withoutFooterButton
					plugins={ [ 'disqus-comment-system', 'newspack-disqus-amp' ] }
					onStatus={ ( { complete } ) => setDisqusActive( complete ) }
				/>
			) }

			<h2>{ __( 'The Coral Project', 'newspack' ) }</h2>
			{ coralActive ? (
				<ActionCard
					title={ __( 'The Coral Project', 'newspack' ) }
					description={ __( 'Coral Project  commenting system.' ) }
					actionText={ __( 'Configure' ) }
					handoff="talk-wp-plugin"
				/>
			) : (
				<PluginInstaller
					withoutFooterButton
					plugins={ [ 'talk-wp-plugin' ] }
					onStatus={ ( { complete } ) => setCoralActive( complete ) }
				/>
			) }
		</>
	);
};

export default withWizardScreen( Commenting );
