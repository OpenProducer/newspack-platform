import { __, sprintf } from '@wordpress/i18n';

/**
 * Data selector for popup options (stored in post meta)
 *
 * @param {Function} select Select function
 */
export const optionsFieldsSelector = select => {
	const { getEditedPostAttribute } = select( 'core/editor' );
	const meta = getEditedPostAttribute( 'meta' );
	const {
		background_color,
		frequency,
		dismiss_text,
		dismiss_text_alignment,
		display_title,
		hide_border,
		overlay_color,
		overlay_opacity,
		placement,
		trigger_scroll_progress,
		trigger_delay,
		trigger_type,
		utm_suppression,
		selected_segment_id,
	} = meta || {};

	const isInlinePlacement = placementValue =>
		-1 === [ 'top', 'bottom', 'center' ].indexOf( placementValue );
	const isOverlay = ! isInlinePlacement( placement );

	return {
		background_color,
		dismiss_text,
		dismiss_text_alignment,
		display_title,
		hide_border,
		frequency,
		overlay_color,
		overlay_opacity,
		placement,
		trigger_scroll_progress,
		trigger_delay,
		trigger_type,
		utm_suppression,
		selected_segment_id,
		isInlinePlacement,
		isOverlay,
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
	const blackColor = '#000000';
	const whiteColor = '#ffffff';

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

	const editorStylesEl = document.querySelector( '.editor-styles-wrapper' );
	const editorPostTitleEl = document.querySelector(
		'.wp-block.editor-post-title__block .editor-post-title__input'
	);
	if ( editorStylesEl ) {
		editorStylesEl.style.backgroundColor = backgroundColor;
		editorStylesEl.style.color = foregroundColor;
	}
	if ( editorPostTitleEl ) {
		editorPostTitleEl.style.color = foregroundColor;
		editorPostTitleEl.style.setProperty(
			'--newspack-popups-editor-placeholder-color',
			`${ foregroundColor }80`
		);
	}
};

/**
 * Is the given placement value a custom placement?
 *
 * @param {string} placementValue Placement of the prompt.
 * @return {boolean} Whether or not the prompt has a custom placement.
 */
export const isCustomPlacement = placementValue => {
	const customPlacements = window.newspack_popups_data?.custom_placements || {};
	return -1 < Object.keys( customPlacements ).indexOf( placementValue );
};

/**
 * Given a placement value, construct a context-sensitive help message to display in the editor sidebar.
 *
 * @param {string} placementValue Placement of the prompt.
 * @param {int|string} triggerPercentage Insertion percentage, for inline prompts.
 * @returns
 */
export const getPlacementHelpMessage = ( placementValue, triggerPercentage = 0 ) => {
	if ( isCustomPlacement( placementValue ) ) {
		const customPlacements = window.newspack_popups_data?.custom_placements || {};
		return sprintf(
			__(
				'The prompt will appear where %s is inserted using the Custom Placement block.',
				'newspack-popups'
			),
			customPlacements[ placementValue ] || __( 'this custom placement', 'newspack-popups' )
		);
	}

	switch ( placementValue ) {
		case 'center':
			return __(
				'The prompt will be displayed as an overlay at the center of the viewport.',
				'newspack-popups'
			);
		case 'top':
			return __(
				'The prompt will be displayed as an overlay at the top of the viewport.',
				'newspack-popups'
			);
		case 'bottom':
			return __(
				'The prompt will be displayed as an overlay at the bottom of the viewport.',
				'newspack-popups'
			);
		case 'above_header':
			return __(
				'The prompt will be automatically inserted at the very top of the page, above the header.',
				'newspack-popups'
			);
		case 'inline':
			return sprintf(
				__(
					'The prompt will be automatically inserted about %s into article content.',
					'newspack-popups'
				),
				triggerPercentage + '%'
			);
		case 'manual':
			return __(
				'The prompt will appear only where inserted using the Single Prompt block or a shortcode.',
				'newspack-popups'
			);
		default:
			return __( 'The placement where the prompt can appear.', 'newspack-popups' );
	}
};
