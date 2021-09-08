/**
 * WordPress dependencies
 */
import { Button } from '@wordpress/components';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { stringify } from 'qs';
import { WebPreview } from 'newspack-components';

const PreviewSetting = ( { autosavePost, isSavingPost, postId, metaFields } ) => {
	const query = stringify( {
		newspack_popups_preview_id: postId,
		// Autosave does not handle meta fields, so these will be passed in the URL
		...metaFields,
	} );

	const previewURL = window.newspack_popups_data?.preview_post || '/';

	return (
		<WebPreview
			url={ `${ previewURL }?${ query }` }
			renderButton={ ( { showPreview } ) => (
				<Button
					isPrimary
					isBusy={ isSavingPost }
					disabled={ isSavingPost }
					onClick={ () => autosavePost().then( showPreview ) }
				>
					{ __( 'Preview', 'newspack-popups' ) }
				</Button>
			) }
		/>
	);
};

const connectPreviewSetting = compose( [
	withSelect( select => {
		const { isSavingPost, getCurrentPostId, getEditedPostAttribute } = select( 'core/editor' );
		return {
			postId: getCurrentPostId(),
			metaFields: getEditedPostAttribute( 'meta' ),
			isSavingPost: isSavingPost(),
		};
	} ),
	withDispatch( dispatch => {
		return {
			autosavePost: () => dispatch( 'core/editor' ).autosave(),
		};
	} ),
] );

export default connectPreviewSetting( PreviewSetting );
