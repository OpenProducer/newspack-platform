/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Grid, SectionHeader, TextControl, withWizardScreen } from '../../../../components/src';

/**
 * SEO Social screen.
 */
class Social extends Component {
	/**
	 * Render.
	 */
	render() {
		const { data, onChange } = this.props;
		const { urls } = data;
		const { facebook, linkedin, twitter, youtube, instagram, pinterest } = urls;
		return (
			<Fragment>
				<SectionHeader
					title={ __( 'Social accounts', 'newspack' ) }
					description={ __(
						'Let search engines know which social profiles are associated to this site',
						'newspack'
					) }
				/>
				<Grid columns={ 3 } gutter={ 32 } rowGap={ 16 }>
					<TextControl
						label={ __( 'Facebook Page', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, facebook: value } } ) }
						value={ facebook }
						placeholder={ __( 'https://facebook.com/page', 'newspack' ) }
					/>
					<TextControl
						label={ __( 'Twitter Username', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, twitter: value } } ) }
						value={ twitter }
						placeholder={ __( 'username', 'newspack' ) }
					/>
					<TextControl
						label="Instagram"
						onChange={ value => onChange( { urls: { ...urls, instagram: value } } ) }
						value={ instagram }
						placeholder={ __( 'https://instagram.com/user', 'newspack' ) }
					/>
					<TextControl
						label="LinkedIn"
						onChange={ value => onChange( { urls: { ...urls, linkedin: value } } ) }
						value={ linkedin }
						placeholder={ __( 'https://linkedin.com/user', 'newspack' ) }
					/>
					<TextControl
						label="YouTube"
						onChange={ value => onChange( { urls: { ...urls, youtube: value } } ) }
						value={ youtube }
						placeholder={ __( 'https://youtube.com/c/channel', 'newspack' ) }
					/>
					<TextControl
						label="Pinterest"
						onChange={ value => onChange( { urls: { ...urls, pinterest: value } } ) }
						value={ pinterest }
						placeholder={ __( 'https://pinterest.com/user', 'newspack' ) }
					/>
				</Grid>
			</Fragment>
		);
	}
}
Social.defaultProps = {
	data: {},
};

export default withWizardScreen( Social );
