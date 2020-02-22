/**
 * Syndication Intro View
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, withWizardScreen } from '../../../../components/src';

/**
 * Syndication Intro screen.
 */
class Intro extends Component {
	/**
	 * Render.
	 */
	render() {
		return (
			<Fragment>
				<ActionCard
					title={ __( 'Apple News' ) }
					description={ __( 'Export and sync posts to Apple format.' ) }
					actionText={ __( 'Configure' ) }
					handoff="publish-to-apple-news"
				/>
				<ActionCard
					title={ __( 'Facebook Instant Articles' ) }
					description={ __(
						'Add support for Instant Articles for Facebook to your WordPress site.'
					) }
					actionText={ __( 'Configure' ) }
					handoff="fb-instant-articles"
				/>
			</Fragment>
		);
	}
}

export default withWizardScreen( Intro );
