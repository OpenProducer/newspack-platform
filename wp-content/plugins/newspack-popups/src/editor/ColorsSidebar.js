/**
 * Popup color options.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { RangeControl } from '@wordpress/components';
import { ColorPaletteControl } from '@wordpress/block-editor';

const ColorsSidebar = ( {
	background_color,
	onMetaFieldChange,
	overlay_opacity,
	overlay_color,
	isOverlay,
} ) => (
	<Fragment>
		<ColorPaletteControl
			value={ background_color }
			onChange={ value => onMetaFieldChange( 'background_color', value || '#FFFFFF' ) }
			label={ __( 'Background Color' ) }
		/>
		{ isOverlay && (
			<Fragment>
				<ColorPaletteControl
					value={ overlay_color }
					onChange={ value => onMetaFieldChange( 'overlay_color', value || '#000000' ) }
					label={ __( 'Overlay Color' ) }
				/>
				<RangeControl
					label={ __( 'Overlay opacity' ) }
					value={ overlay_opacity }
					onChange={ value => onMetaFieldChange( 'overlay_opacity', value ) }
					min={ 0 }
					max={ 100 }
				/>
			</Fragment>
		) }
	</Fragment>
);

export default ColorsSidebar;
