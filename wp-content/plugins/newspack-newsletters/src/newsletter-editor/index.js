/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment, useState } from '@wordpress/element';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import InitModal from '../components/init-modal';
import Layout from './layout/';
import Sidebar from './sidebar/';
import Testing from './testing/';
import { Styling, ApplyStyling } from './styling/';
import registerEditorPlugin from './editor/';

registerEditorPlugin();

const NewsletterEdit = ( { layoutId } ) => {
	const [ hasKeys, setHasKeys ] = useState(
		window && window.newspack_newsletters_data && window.newspack_newsletters_data.has_keys
	);

	const isDisplayingInitModal = ! hasKeys || -1 === layoutId;

	return isDisplayingInitModal ? (
		<InitModal hasKeys={ hasKeys } onSetupStatus={ setHasKeys } />
	) : (
		<Fragment>
			<PluginDocumentSettingPanel
				name="newsletters-settings-panel"
				title={ __( 'Newsletter', 'newspack-newsletters' ) }
			>
				<Sidebar />
			</PluginDocumentSettingPanel>
			<PluginDocumentSettingPanel
				name="newsletters-styling-panel"
				title={ __( 'Styling', 'newspack-newsletters' ) }
			>
				<Styling />
			</PluginDocumentSettingPanel>
			<PluginDocumentSettingPanel
				name="newsletters-testing-panel"
				title={ __( 'Testing', 'newspack-newsletters' ) }
			>
				<Testing />
			</PluginDocumentSettingPanel>
			<PluginDocumentSettingPanel
				name="newsletters-layout-panel"
				title={ __( 'Layout', 'newspack-newsletters' ) }
			>
				<Layout />
			</PluginDocumentSettingPanel>

			<ApplyStyling />
		</Fragment>
	);
};

const NewsletterEditWithSelect = compose( [
	withSelect( select => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		const meta = getEditedPostAttribute( 'meta' );
		return { layoutId: meta.template_id };
	} ),
] )( NewsletterEdit );

registerPlugin( 'newspack-newsletters-sidebar', {
	render: NewsletterEditWithSelect,
	icon: null,
} );
