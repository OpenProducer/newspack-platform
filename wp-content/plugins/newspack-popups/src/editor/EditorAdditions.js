/**
 * Popup-related editor changes.
 */

/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { updateEditorColors } from './utils';

const EditorAdditions = () => {
	const meta = useSelect( select => select( 'core/editor' ).getEditedPostAttribute( 'meta' ) );
	const { dismiss_text, dismiss_text_alignment, background_color } = meta;

	// Update editor colors to match popup colors.
	useEffect(() => {
		updateEditorColors( background_color );
	}, [ background_color ]);

	// Render a preview of the dismiss button at the end of the block content area.
	useEffect(() => {
		let dismissButtonPreview = document.querySelector(
			'.newspack-popups__not-interested-button-preview'
		);

		if ( ! dismiss_text ) {
			if ( dismissButtonPreview ) {
				dismissButtonPreview.parentNode.removeChild( dismissButtonPreview );
			}
			return;
		}

		const alignClass = 'has-text-align-' + ( dismiss_text_alignment || 'center' );

		if ( ! dismissButtonPreview ) {
			const rootContainer = document.querySelector(
				'.block-editor-block-list__layout.is-root-container'
			);

			if ( rootContainer ) {
				dismissButtonPreview = document.createElement( 'div' );
				rootContainer.appendChild( dismissButtonPreview );
			}
		}

		if ( dismissButtonPreview ) {
			dismissButtonPreview.className =
				'newspack-popups__not-interested-button-preview wp-block ' + alignClass;
			dismissButtonPreview.textContent = dismiss_text;
		}
	}, [ dismiss_text, dismiss_text_alignment ]);
	return null;
};

export default EditorAdditions;
