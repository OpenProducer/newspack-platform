/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { TextControl, withWizardScreen } from '../../../../components/src';
import './style.scss';

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
				<h2>{ __( 'Social profiles', 'newspack' ) }</h2>
				<p>
					{ __(
						'To let search engines know which social profiles are associated to this site, enter your site social profiles data below.',
						'newspack'
					) }
				</p>
				<div className="newspack-seo-wizard-social__text-controls">
					<TextControl
						label={ __( 'Facebook page URL', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, facebook: value } } ) }
						value={ facebook }
					/>
					<TextControl
						label={ __( 'Twitter username', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, twitter: value } } ) }
						value={ twitter }
					/>
					<TextControl
						label={ __( 'Instagram URL', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, instagram: value } } ) }
						value={ instagram }
					/>
					<TextControl
						label={ __( 'LinkedIn URL', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, linkedin: value } } ) }
						value={ linkedin }
					/>
					<TextControl
						label={ __( 'YouTube URL', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, youtube: value } } ) }
						value={ youtube }
					/>
					<TextControl
						label={ __( 'Pinterest URL', 'newspack' ) }
						onChange={ value => onChange( { urls: { ...urls, pinterest: value } } ) }
						value={ pinterest }
					/>
				</div>
			</Fragment>
		);
	}
}
Social.defaultProps = {
	data: {},
};

export default withWizardScreen( Social );
