/**
 * Starter Content Screen.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ProgressBar, withWizardScreen } from '../../../../components/src';

/**
 * Location Setup Screen.
 */
class StarterContent extends Component {
	/**
	 * Render.
	 */
	render() {
		const { starterContentProgress, starterContentTotal } = this.props;
		return (
			<div className="newspack-setup-wizard__welcome">
				<p>
					{ __(
						'Optionally pre-populate the site with categories, 40 placeholder stories, Newspack branding, and some homepage blocks. This feature will pre-configure the site for experimentation and testing and all placeholder content can be deleted and replaced later.'
					) }
				</p>
				{ starterContentProgress > 0 && starterContentTotal > 0 && (
					<ProgressBar
						completed={ starterContentProgress }
						total={ starterContentTotal }
						displayFraction
					/>
				) }
			</div>
		);
	}
}

export default withWizardScreen( StarterContent );
