/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { SelectControl, TextareaControl, TextControl, ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import './style.scss';

const SidebarComponent = props => {
	if ( props.postType !== 'newspack_spnsrs_cpt' ) {
		return null;
	}

	const { meta, title, updateMetaValue } = props;
	const {
		newspack_sponsor_url,
		newspack_sponsor_flag_override,
		newspack_sponsor_byline_prefix,
		newspack_sponsor_sponsorship_scope,
		newspack_sponsor_only_direct,
		newspack_sponsor_disclaimer_override,
	} = meta;
	const { settings, defaults } = window.newspack_sponsors_data;

	return (
		<PluginDocumentSettingPanel
			className="newspack-sponsors"
			name="newspack-sponsors"
			title={ __( 'Sponsor Settings', 'newspack-sponsors' ) }
		>
			<SelectControl
				className="newspack-sponsors__select-control"
				label={ __( 'Sponsorship Scope', 'newspack-sponsors' ) }
				value={ newspack_sponsor_sponsorship_scope || 'native' }
				options={ [
					{ value: 'native', label: __( 'Native content', 'newspack-sponsors' ) },
					{ value: 'underwritten', label: __( 'Underwritten content', 'newspack-sponsors' ) },
				] }
				onChange={ value => updateMetaValue( 'newspack_sponsor_sponsorship_scope', value ) }
				help={ __(
					'Generally, native content is authored by the sponsor, while underwritten content is authored by editorial staff but supported by the sponsor. This option allows you to select a different visual treatment for native vs. underwitten content.',
					'newspack-sponsors'
				) }
			/>
			<TextControl
				className="newspack-sponsors__text-control"
				label={ __( 'Sponsor URL', 'newspack-sponsors' ) }
				placeholder={ __( 'URL to link to for this sponsor', 'newspack-sponsors' ) }
				help={ __(
					'Required if you want to link the sponsor logo to an external URL.',
					'newspack-sponsors'
				) }
				type="url"
				value={ newspack_sponsor_url }
				onChange={ value => updateMetaValue( 'newspack_sponsor_url', value ) }
			/>
			<TextControl
				className="newspack-sponsors__text-control"
				label={ __( 'Sponsor Flag Override (Optional)', 'newspack-sponsors' ) }
				placeholder={ settings.flag || defaults.flag }
				help={ __(
					'The label for the flag that appears in lieu of category flags. If not empty, this field will override the site-wide setting.',
					'newspack-sponsors'
				) }
				type="url"
				value={ newspack_sponsor_flag_override }
				onChange={ value => updateMetaValue( 'newspack_sponsor_flag_override', value ) }
			/>
			<TextareaControl
				className="newspack-sponsors__textarea-control"
				label={ __( 'Sponsor Disclaimer Override (Optional)', 'newspack-sponsors' ) }
				placeholder={ ( settings.disclaimer || defaults.disclaimer ).replace(
					'[sponsor name]',
					title
				) }
				help={ __(
					'Text shown to explain sponsorship by this sponsor. If not empty, this field will override the site-wide setting.',
					'newspack-sponsors'
				) }
				value={ newspack_sponsor_disclaimer_override }
				onChange={ value => updateMetaValue( 'newspack_sponsor_disclaimer_override', value ) }
			/>
			<TextControl
				className="newspack-sponsors__text-control"
				label={ __( 'Sponsor Byline Prefix (Optional)', 'newspack-sponsors' ) }
				help={ __(
					'The prefix for the sponsor attribution that appears in lieu of author byline. If not empty, this field will override the site-wide setting.',
					'newspack-sponsors'
				) }
				placeholder={ settings.byline || defaults.byline }
				type="url"
				value={ newspack_sponsor_byline_prefix }
				onChange={ value => updateMetaValue( 'newspack_sponsor_byline_prefix', value ) }
			/>
			<ToggleControl
				className="newspack-sponsors__toggle-control"
				label={ __( 'Show on posts only if a direct sponsor?', 'newspack-newsletters' ) }
				help={ __(
					'If this option is enabled, this sponsor will only be shown on single posts if assigned as a direct sponsor. It will still appear on category and tag archive pages, if applicable.'
				) }
				checked={ newspack_sponsor_only_direct }
				onChange={ value => updateMetaValue( 'newspack_sponsor_only_direct', value ) }
			/>
		</PluginDocumentSettingPanel>
	);
};

const mapStateToProps = select => {
	const { getCurrentPostType, getEditedPostAttribute } = select( 'core/editor' );

	return {
		meta: getEditedPostAttribute( 'meta' ),
		postType: getCurrentPostType(),
		title: getEditedPostAttribute( 'title' ),
	};
};

const mapDispatchToProps = dispatch => {
	const { editPost } = dispatch( 'core/editor' );

	return {
		updateMetaValue: ( key, value ) => editPost( { meta: { [ key ]: value } } ),
	};
};

export const Sidebar = compose( [
	withSelect( mapStateToProps ),
	withDispatch( mapDispatchToProps ),
] )( SidebarComponent );
