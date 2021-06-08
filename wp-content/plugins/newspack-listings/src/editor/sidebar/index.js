/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelRow, ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import { isListing } from '../utils';
import './style.scss';

const SidebarComponent = ( { meta, updateMetaValue } ) => {
	const { post_type_label, post_types } = window.newspack_listings_data;
	const { newspack_listings_hide_author } = meta;

	if ( ! post_types || ! isListing() ) {
		return null;
	}

	return (
		<PluginDocumentSettingPanel
			className="newspack-listings__editor-sidebar"
			name="newspack-listings"
			title={ post_type_label + ' ' + __( 'Settings', 'newspack-listings' ) }
		>
			<PanelRow>
				<ToggleControl
					className={ 'newspack-listings__hide-author-control' }
					label={ __( 'Hide listing author', 'newspack-listings' ) }
					checked={ newspack_listings_hide_author }
					onChange={ value => updateMetaValue( 'newspack_listings_hide_author', value ) }
				/>
			</PanelRow>
		</PluginDocumentSettingPanel>
	);
};

const mapStateToProps = select => {
	const { getEditedPostAttribute } = select( 'core/editor' );

	return {
		meta: getEditedPostAttribute( 'meta' ),
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
