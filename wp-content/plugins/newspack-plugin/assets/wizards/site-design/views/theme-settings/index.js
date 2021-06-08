/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {
	RadioControl,
	TextControl,
	ToggleControl,
	ToggleGroup,
	withWizardScreen,
} from '../../../../components/src';
import './style.scss';

/**
 * Theme Settings Screen.
 */
class ThemeSettings extends Component {
	/**
	 * Render.
	 */
	render() {
		const { themeMods, setThemeMods } = this.props;
		const {
			show_author_bio: authorBio = true,
			show_author_email: authorEmail = false,
			author_bio_length: authorBioLength = 200,
			featured_image_default: featuredImageDefault,
		} = themeMods;
		return (
			<Fragment>
				<h2>{ __( 'Author bio', 'newspack' ) }</h2>
				<ToggleGroup
					title={ __( 'Author bio', 'newspack' ) }
					description={ __( 'Display an author bio under individual posts.', 'newspack' ) }
					checked={ authorBio }
					onChange={ value => setThemeMods( { show_author_bio: value } ) }
				>
					<ToggleControl
						isDark
						label={ __( 'Author email', 'newspack' ) }
						help={ __( 'Display the author email with bio on individual posts.', 'newspack' ) }
						checked={ authorEmail }
						onChange={ value => setThemeMods( { show_author_email: value } ) }
					/>
					<TextControl
						label={ __( 'Length', 'newspack' ) }
						help={ __(
							'Truncates the author bio on single posts to this approximate character length, but without breaking a word. The full bio appears on the author archive page.',
							'newspack'
						) }
						type="number"
						value={ authorBioLength }
						onChange={ value => setThemeMods( { author_bio_length: value } ) }
					/>
				</ToggleGroup>
				<hr />
				<h2>{ __( 'Featured Image', 'newspack' ) }</h2>
				<RadioControl
					label={ __( 'Default position', 'newspack' ) }
					selected={ featuredImageDefault }
					options={ [
						{ label: __( 'Large', 'newspack' ), value: 'large' },
						{ label: __( 'Small', 'newspack' ), value: 'small' },
						{ label: __( 'Behind article title', 'newspack' ), value: 'behind' },
						{ label: __( 'Beside article title', 'newspack' ), value: 'beside' },
						{ label: __( 'Hidden', 'newspack' ), value: 'hidden' },
					] }
					onChange={ value => setThemeMods( { featured_image_default: value } ) }
				/>
			</Fragment>
		);
	}
}

ThemeSettings.defaultProps = {
	themeMods: {},
	setThemeMods: () => null,
};

export default withWizardScreen( ThemeSettings );
