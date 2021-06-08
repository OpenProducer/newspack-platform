/**
 * External dependencies
 */
import { pick, omit, get, isEmpty } from 'lodash';
import mjml2html from 'mjml-browser';

/**
 * WordPress dependencies
 */
import { compose } from '@wordpress/compose';
import {
	withDispatch,
	dispatch as globalDispatch,
	withSelect,
	select as globalSelect,
} from '@wordpress/data';
import { createPortal, useEffect, useState } from '@wordpress/element';
import { registerPlugin } from '@wordpress/plugins';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import { NEWSLETTER_CPT_SLUG } from '../../utils/consts';

const POST_META_WHITELIST = [
	'is_public',
	'preview_text',
	'diable_ads',
	'font_body',
	'font_header',
	'background_color',
	'custom_css',
];

/**
 * Use a middleware to hijack the post update request.
 * When a post is about to be updated, first the email-compliant HTML has
 * to be produced. To do that, MJML (more at mjml.io) is used.
 */
apiFetch.use( async ( options, next ) => {
	const { method, path = '', data = {} } = options;
	if (
		path.indexOf( window.newspack_newsletters_data.newsletter_cpt ) > 0 &&
		data.content &&
		data.id &&
		( method === 'POST' || method === 'PUT' )
	) {
		const emailHTMLMetaName = window.newspack_newsletters_data.email_html_meta;

		// Strip the meta which will be updated explicitly from post update payload.
		options.data.meta = omit( options.data.meta, [ ...POST_META_WHITELIST, emailHTMLMetaName ] );

		// First, save post meta. It is not saved when saving a draft, so
		// it's saved here in order for the backend to have access to these.
		const postMeta = globalSelect( 'core/editor' ).getEditedPostAttribute( 'meta' );
		await apiFetch( {
			data: { meta: pick( postMeta, POST_META_WHITELIST ) },
			method: 'POST',
			path: `/wp/v2/${ NEWSLETTER_CPT_SLUG }/${ data.id }`,
		} );

		// Then, send the content over to the server to convert the post content
		// into MJML markup.
		return apiFetch( {
			path: `/newspack-newsletters/v1/post-mjml`,
			method: 'POST',
			data: {
				id: data.id,
				title: data.title,
				content: data.content,
			},
		} )
			.then( ( { mjml } ) => {
				// Once received MJML markup, convert it to email-compliant HTML
				// and save as post meta for later retrieval.
				const { html } = mjml2html( mjml );
				return apiFetch( {
					data: { meta: { [ emailHTMLMetaName ]: html } },
					method: 'POST',
					path: `/wp/v2/${ NEWSLETTER_CPT_SLUG }/${ data.id }`,
				} );
			} )
			.then( () => next( options ) ) // Proceed with the post update request.
			.catch( error => {
				// In case of an error, display notice and proceed with the post update request.
				const { createErrorNotice } = globalDispatch( 'core/notices' );
				createErrorNotice( error.message || __( 'Something went wrong', 'newspack-newsletters' ) );
				return next( options );
			} );
	}
	return next( options );
} );

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
			getCurrentPostAttribute,
			getEditedPostAttribute,
			isPublishingPost,
			isSavingPost,
			isCleanNewPost,
		} = select( 'core/editor' );
		const { getActiveGeneralSidebarName } = select( 'core/edit-post' );
		const { getSettings } = select( 'core/block-editor' );
		const meta = getEditedPostAttribute( 'meta' );
		const status = getCurrentPostAttribute( 'status' );
		const sentDate = getCurrentPostAttribute( 'date' );
		const settings = getSettings();
		const experimentalSettingsColors = get( settings, [
			'__experimentalFeatures',
			'global',
			'color',
			'palette',
		] );
		const colors = settings.colors || experimentalSettingsColors || [];

		return {
			isCleanNewPost: isCleanNewPost(),
			postId: getCurrentPostId(),
			isReady: meta.newsletterValidationErrors
				? meta.newsletterValidationErrors.length === 0
				: false,
			activeSidebarName: getActiveGeneralSidebarName(),
			isPublishingOrSavingPost: isSavingPost() || isPublishingPost(),
			colorPalette: colors.reduce(
				( _colors, { slug, color } ) => ( { ..._colors, [ slug ]: color } ),
				{}
			),
			status,
			sentDate,
			isPublic: meta.is_public,
		};
	} ),
	withDispatch( dispatch => {
		const { lockPostAutosaving, lockPostSaving, unlockPostSaving, editPost } = dispatch(
			'core/editor'
		);
		const { createNotice } = dispatch( 'core/notices' );
		return { lockPostAutosaving, lockPostSaving, unlockPostSaving, editPost, createNotice };
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

	// Set color palette option.
	useEffect(() => {
		if ( isEmpty( props.colorPalette ) ) {
			return;
		}
		props.apiFetchWithErrorHandling( {
			path: `/newspack-newsletters/v1/color-palette`,
			data: props.colorPalette,
			method: 'POST',
		} );
	}, [ JSON.stringify( props.colorPalette ) ]);

	// Fetch data from service provider.
	useEffect(() => {
		if ( ! props.isCleanNewPost && ! props.isPublishingOrSavingPost ) {
			const params = getFetchDataConfig( { postId: props.postId } );
			if ( 0 === params.path.indexOf( '/newspack-newsletters/v1/example/' ) ) {
				return;
			}
			props.apiFetchWithErrorHandling( params ).then( result => {
				props.editPost( getEditPostPayload( result ) );
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

	useEffect(() => {
		if ( 'publish' === props.status && ! props.isPublishingOrSavingPost ) {
			const dateTime = props.sentDate ? new Date( props.sentDate ).toLocaleString() : '';

			// Lock autosaving after a newsletter is sent.
			props.lockPostAutosaving();

			// Show an editor notice if the newsletter has been sent.
			props.createNotice( 'success', props.successNote + dateTime, {
				isDismissible: false,
			} );
		}
	}, [ props.status ]);

	useEffect(() => {
		// Hide post title if the newsletter is a not a public post.
		const editorTitleEl = document.querySelector( '.editor-post-title' );
		if ( editorTitleEl ) {
			editorTitleEl.classList[ props.isPublic ? 'remove' : 'add' ](
				'newspack-newsletters-post-title-hidden'
			);
		}
	}, [ props.isPublic ]);

	return createPortal( <SendButton />, publishEl );
} );

export default () => {
	registerPlugin( 'newspack-newsletters-edit', {
		render: Editor,
	} );
};
