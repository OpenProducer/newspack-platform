/**
 * Data selector for popup options (stored in post meta)
 *
 * @param {Function} select Select function
 */
export const optionsFieldsSelector = select => {
	const { getEditedPostAttribute, getCurrentPostId, isSavingPost, isCurrentPostPublished } = select(
		'core/editor'
	);
	const meta = getEditedPostAttribute( 'meta' );
	const {
		background_color,
		frequency,
		dismiss_text,
		display_title,
		overlay_color,
		overlay_opacity,
		placement,
		trigger_scroll_progress,
		trigger_delay,
		trigger_type,
		utm_suppression,
	} = meta || {};
	return {
		background_color,
		dismiss_text,
		display_title,
		frequency,
		id: getCurrentPostId(),
		overlay_color,
		overlay_opacity,
		newspack_popups_is_sitewide_default: getEditedPostAttribute(
			'newspack_popups_is_sitewide_default'
		),
		placement,
		trigger_scroll_progress,
		trigger_delay,
		trigger_type,
		utm_suppression,
		isSavingPost: isSavingPost(),
		isCurrentPostPublished: isCurrentPostPublished(),
	};
};

/**
 * Convert hex color to RGB.
 * From https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
 *
 * @param {string} hex Color in HEX format
 */
const hexToRGB = hex =>
	hex
		.replace( /^#?([a-f\d])([a-f\d])([a-f\d])$/i, ( m, r, g, b ) => '#' + r + r + g + g + b + b )
		.substring( 1 )
		.match( /.{2}/g )
		.map( x => parseInt( x, 16 ) );

/**
 * Set the background color meta field.
 * Based on https://github.com/Automattic/newspack-theme/blob/master/newspack-theme/inc/template-functions.php#L401-L431
 *
 * @param {string} backgroundColor color string
 */
export const updateEditorColors = backgroundColor => {
	if ( ! backgroundColor ) {
		return;
	}
	const blackColor = '#000';
	const whiteColor = '#fff';

	const backgroundColorRGB = hexToRGB( backgroundColor );
	const blackRGB = hexToRGB( blackColor );

	const l1 =
		0.2126 * Math.pow( backgroundColorRGB[ 0 ] / 255, 2.2 ) +
		0.7152 * Math.pow( backgroundColorRGB[ 1 ] / 255, 2.2 ) +
		0.0722 * Math.pow( backgroundColorRGB[ 2 ] / 255, 2.2 );
	const l2 =
		0.2126 * Math.pow( blackRGB[ 0 ] / 255, 2.2 ) +
		0.7152 * Math.pow( blackRGB[ 1 ] / 255, 2.2 ) +
		0.0722 * Math.pow( blackRGB[ 2 ] / 255, 2.2 );

	const contrastRatio =
		l1 > l2 ? parseInt( ( l1 + 0.05 ) / ( l2 + 0.05 ) ) : parseInt( ( l2 + 0.05 ) / ( l1 + 0.05 ) );

	const foregroundColor = contrastRatio > 5 ? blackColor : whiteColor;

	const editorStylesEl = document.querySelector( '.edit-post-visual-editor.editor-styles-wrapper' );
	const editorPostTitleEl = document.querySelector(
		'.wp-block.editor-post-title__block .editor-post-title__input'
	);
	const editorPostTitlePlaceholderEl = document.querySelector(
		'.wp-block.editor-post-title__block .editor-post-title__input::placeholder'
	);

	if ( editorStylesEl ) {
		editorStylesEl.style.backgroundColor = backgroundColor;
		editorStylesEl.style.color = foregroundColor;
	}
	if ( editorPostTitleEl ) {
		editorPostTitleEl.style.color = foregroundColor;
	}
	if ( editorPostTitlePlaceholderEl ) {
		editorPostTitlePlaceholderEl.style.color = foregroundColor;
	}
};
