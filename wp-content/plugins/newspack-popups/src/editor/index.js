/**
 * Popup Custom Post Type
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel, PluginPostStatusInfo } from '@wordpress/edit-post';

/**
 * Internal dependencies
 */
import { optionsFieldsSelector } from './utils';
import Sidebar from './Sidebar';
import FrequencySidebar from './FrequencySidebar';
import SegmentationSidebar from './SegmentationSidebar';
import ColorsSidebar from './ColorsSidebar';
import AdvancedSidebar from './AdvancedSidebar';
import Preview from './Preview';
import Duplicate from './Duplicate';
import EditorAdditions from './EditorAdditions';
import PostTypesPanel from './PostTypesPanel';
import './style.scss';

// Action dispatchers for the sidebar components.
const mapDispatchToProps = dispatch => {
	const { createNotice, removeNotice } = dispatch( 'core/notices' );
	return {
		onMetaFieldChange: ( key, value ) => {
			dispatch( 'core/editor' ).editPost( { meta: { [ key ]: value } } );
		},
		createNotice,
		removeNotice,
	};
};

const connectData = compose( [
	withSelect( optionsFieldsSelector ),
	withDispatch( mapDispatchToProps ),
] );

// Connect data to components.
const SidebarWithData = connectData( Sidebar );
const FrequencySidebarWithData = connectData( FrequencySidebar );
const SegmentationSidebarWithData = connectData( SegmentationSidebar );
const ColorsSidebarWithData = connectData( ColorsSidebar );
const PostTypesPanelWithData = connectData( PostTypesPanel );
const AdvancedSidebarWithData = connectData( AdvancedSidebar );

// Register components.
registerPlugin( 'newspack-popups', {
	render: () => (
		<PluginDocumentSettingPanel
			name="popup-settings-panel"
			title={ __( 'Prompt Settings', 'newspack-popups' ) }
		>
			<SidebarWithData />
		</PluginDocumentSettingPanel>
	),
	icon: null,
} );

registerPlugin( 'newspack-popups-frequency', {
	render: () => (
		<PluginDocumentSettingPanel
			name="-frequency-panel"
			title={ __( 'Frequency Settings', 'newspack-popups' ) }
		>
			<FrequencySidebarWithData />
		</PluginDocumentSettingPanel>
	),
	icon: null,
} );

registerPlugin( 'newspack-popups-segmentation', {
	render: () => (
		<PluginDocumentSettingPanel
			name="popup-segmentation-panel"
			title={ __( 'Segmentation Settings', 'newspack-popups' ) }
		>
			<SegmentationSidebarWithData />
		</PluginDocumentSettingPanel>
	),
	icon: null,
} );

registerPlugin( 'newspack-popups-colors', {
	render: () => (
		<PluginDocumentSettingPanel
			name="popup-colors-panel"
			title={ __( 'Color Settings', 'newspack-popups' ) }
		>
			<ColorsSidebarWithData />
		</PluginDocumentSettingPanel>
	),
	icon: null,
} );

registerPlugin( 'newspack-popups-post-types', {
	render: () => (
		<PluginDocumentSettingPanel
			name="post-types-panel"
			title={ __( 'Post Types', 'newspack-popups' ) }
		>
			<PostTypesPanelWithData />
		</PluginDocumentSettingPanel>
	),
	icon: null,
} );

registerPlugin( 'newspack-popups-advanced', {
	render: () => (
		<PluginDocumentSettingPanel
			name="popup-advanced-panel"
			title={ __( 'Advanced Settings', 'newspack-popups' ) }
		>
			<AdvancedSidebarWithData />
		</PluginDocumentSettingPanel>
	),
	icon: null,
} );

registerPlugin( 'newspack-popups-editor', {
	render: EditorAdditions,
	icon: null,
} );

// Add a button in post status section
const PluginPostStatusInfoTest = () => (
	<PluginPostStatusInfo className="newspack-popups__status-options">
		<Preview />
		<Duplicate />
	</PluginPostStatusInfo>
);
registerPlugin( 'newspack-popups-preview', { render: PluginPostStatusInfoTest } );
