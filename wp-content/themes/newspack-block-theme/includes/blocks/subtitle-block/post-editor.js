/* globals newspack_block_theme_subtitle_block */
'use strict';

/**
 * WordPress dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

const META_FIELD_NAME = newspack_block_theme_subtitle_block.post_meta_name;

const SUBTITLE_ID = 'newspack-post-subtitle-element';
const SUBTITLE_STYLE_ID = 'newspack-post-subtitle-element-style';

const appendSubtitleToTitleDOMElement = ( subtitle, callback ) => {
	const titleWrapperEl = document.querySelector( '.edit-post-visual-editor__post-title-wrapper' );

	if ( titleWrapperEl && typeof subtitle === 'string' ) {
		let subtitleEl = document.getElementById( SUBTITLE_ID );
		const titleParent = titleWrapperEl.parentNode;

		if ( ! document.getElementById( SUBTITLE_STYLE_ID ) ) {
			const style = document.createElement( 'style' );
			style.innerHTML = `
                #${ SUBTITLE_ID } {
                    font-style: italic;
                    max-width: calc(632px + var(--wp--preset--spacing--30)* 2);
                    margin-left: auto;
                    margin-right: auto;
                    margin-bottom: 2em;
                    padding-left: var(--wp--preset--spacing--30);
                    padding-right: var(--wp--preset--spacing--30);
                }
            `;
			document.head.appendChild( style );
		}

		if ( ! subtitleEl ) {
			subtitleEl = document.createElement( 'div' );
			subtitleEl.setAttribute( 'contenteditable', 'plaintext-only' );
			subtitleEl.addEventListener( 'input', () => {
				callback( subtitleEl.innerHTML );
			} );
			subtitleEl.id = SUBTITLE_ID;
			titleParent.insertBefore( subtitleEl, titleWrapperEl.nextSibling );
		}
		subtitleEl.innerHTML = subtitle;
	}
};

/**
 * This functionality is handled via DOM interaction, which is risky, but in the name of WYSIWYG.
 * The post subtitle is edited directly beneath the post title, and no block is
 * registered in the post editor – this block will only be registered in the site editor.
 */
const NewspackSubtitlePanel = () => {
	const subtitle = useSelect(
		select => select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ META_FIELD_NAME ]
	);
	const dispatch = useDispatch();
	const saveSubtitle = updatedSubtitle => {
		dispatch( 'core/editor' ).editPost( {
			meta: {
				[ META_FIELD_NAME ]: updatedSubtitle,
			},
		} );
	};
	useEffect( () => {
		appendSubtitleToTitleDOMElement( subtitle, saveSubtitle );
	}, [] );
};

registerPlugin( 'plugin-document-setting-panel-newspack-subtitle', {
	render: NewspackSubtitlePanel,
	icon: null,
} );
