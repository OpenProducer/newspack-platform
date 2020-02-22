/**
 * WordPress dependencies
 */
import { Component, Fragment, RawHTML } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { TextControl, withWizardScreen } from '../../../../components/src';

/**
 * SEO Tools screen.
 */
class Tools extends Component {
	/**
	 * Render.
	 */
	render() {
		const { data, onChange } = this.props;
		const { verification } = data;
		const { google, bing } = verification;
		return (
			<Fragment>
				<h2>{ __( 'Webmaster tools verification', 'newspack' ) }</h2>
				<p>
					{ __(
						'You can use the boxes below to verify with the different Webmaster Tools. This feature will add a verification meta tag on your home page. Follow the links to the different Webmaster Tools and look for instructions for the meta tag verification method to get the verification code.',
						'newspack'
					) }
				</p>
				<TextControl
					label={ __( 'Google', 'newspack' ) }
					onChange={ value => onChange( { verification: { ...verification, google: value } } ) }
					value={ google }
					help={
						<RawHTML>
							{ sprintf(
								__(
									'Get your Google verification code in <a href="%s">Google Search Console</a>.',
									'newspack'
								),
								'https://www.google.com/webmasters/verification/verification?tid=alternate'
							) }
						</RawHTML>
					}
				/>
				<TextControl
					label={ __( 'Bing', 'newspack' ) }
					onChange={ value => onChange( { verification: { ...verification, bing: value } } ) }
					value={ bing }
					help={
						<RawHTML>
							{ sprintf(
								__(
									'Get your Bing verification code in <a href="%s">Bing Webmaster Tool</a>.',
									'newspack'
								),
								'https://www.bing.com/toolbox/webmaster/#/Dashboard/'
							) }
						</RawHTML>
					}
				/>
			</Fragment>
		);
	}
}
Tools.defaultProps = {
	data: {},
};

export default withWizardScreen( Tools );
