/**
 * WordPress dependencies
 */
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';
import { createPortal, useEffect, useState } from '@wordpress/element';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import { getEditPostPayload } from '../utils';
import { getServiceProvider } from '../../service-providers';
import withApiHandler from '../../components/with-api-handler';
import SendButton from '../../components/send-button';
import './style.scss';

const Editor = compose( [
	withApiHandler(),
	withSelect( select => {
		const {
			getCurrentPostId,
			getEditedPostAttribute,
			isPublishingPost,
			isSavingPost,
			isCleanNewPost,
		} = select( 'core/editor' );
		const { getActiveGeneralSidebarName } = select( 'core/edit-post' );
		const { getSettings } = select( 'core/block-editor' );
		const meta = getEditedPostAttribute( 'meta' );

		return {
			isCleanNewPost: isCleanNewPost(),
			postId: getCurrentPostId(),
			isReady: meta.newsletterValidationErrors
				? meta.newsletterValidationErrors.length === 0
				: false,
			activeSidebarName: getActiveGeneralSidebarName(),
			isPublishingOrSavingPost: isSavingPost() || isPublishingPost(),
			colorPalette: getSettings().colors.reduce(
				( colors, { slug, color } ) => ( { ...colors, [ slug ]: color } ),
				{}
			),
		};
	} ),
	withDispatch( dispatch => {
		const { lockPostSaving, unlockPostSaving, editPost } = dispatch( 'core/editor' );
		return { lockPostSaving, unlockPostSaving, editPost };
	} ),
] )( props => {
	const [ publishEl ] = useState( document.createElement( 'div' ) );
	// Create alternate publish button
	useEffect(() => {
		const publishButton = document.getElementsByClassName(
			'editor-post-publish-button__button'
		)[ 0 ];
		publishButton.parentNode.insertBefore( publishEl, publishButton );
	}, []);
	const { getFetchDataConfig } = getServiceProvider();

	// Fetch data from service provider.
	useEffect(() => {
		if ( ! props.isCleanNewPost && ! props.isPublishingOrSavingPost ) {
			props
				.apiFetchWithErrorHandling( getFetchDataConfig( { postId: props.postId } ) )
				.then( result => {
					const postUpdate = getEditPostPayload( result );
					postUpdate.meta.color_palette = props.colorPalette;
					props.editPost( postUpdate );
				} );
		}
	}, [ props.isPublishingOrSavingPost ]);

	// Lock or unlock post publishing.
	useEffect(() => {
		if ( props.isReady ) {
			props.unlockPostSaving( 'newspack-newsletters-post-lock' );
		} else {
			props.lockPostSaving( 'newspack-newsletters-post-lock' );
		}
	}, [ props.isReady ]);

	return createPortal( <SendButton />, publishEl );
} );

export default () => {
	registerPlugin( 'newspack-newsletters-edit', {
		render: Editor,
	} );
};
