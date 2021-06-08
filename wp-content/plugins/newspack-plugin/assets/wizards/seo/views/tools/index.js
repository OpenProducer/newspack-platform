/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { Grid, SectionHeader, TextControl, withWizardScreen } from '../../../../components/src';

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
				<SectionHeader
					title={ __( 'Webmaster tools verification', 'newspack' ) }
					description={ __( 'Add a verification meta tag on your homepage', 'newspack' ) }
				/>
				<Grid gutter={ 32 }>
					<TextControl
						label={ __( 'Google', 'newspack' ) }
						onChange={ value => onChange( { verification: { ...verification, google: value } } ) }
						value={ google }
						help={
							<>
								{ __( 'Get your Google verification code in', 'newspack' ) + ' ' }
								<ExternalLink href="https://www.google.com/webmasters/verification/verification?tid=alternate">
									{ __( 'Google Search Console', 'newspack' ) }
								</ExternalLink>
							</>
						}
					/>
					<TextControl
						label={ __( 'Bing', 'newspack' ) }
						onChange={ value => onChange( { verification: { ...verification, bing: value } } ) }
						value={ bing }
						help={
							<>
								{ __( 'Get your Bing verification code in', 'newspack' ) + ' ' }
								<ExternalLink href="https://www.bing.com/toolbox/webmaster/#/Dashboard/">
									{ __( 'Bing Webmaster Tool', 'newspack' ) }
								</ExternalLink>
							</>
						}
					/>
				</Grid>
			</Fragment>
		);
	}
}
Tools.defaultProps = {
	data: {},
};

export default withWizardScreen( Tools );
