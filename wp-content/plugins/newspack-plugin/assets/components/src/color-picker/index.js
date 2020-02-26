/**
 * WordPress dependencies.
 */
import { ColorIndicator, ColorPalette } from '@wordpress/components';
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import './style.scss';

/**
 * Color Picker.
 */
class ColorPicker extends Component {
	/**
	 * Render.
	 */
	render() {
		const { color, hasDefaultColors, label, onChange, suggestedColors, ...otherProps } = this.props;
		const defaultColors = [
			{
				name: __( 'navy' ),
				color: '#001f3f',
			},
			{ name: __( 'blue' ), color: '#0074d9' },
			{
				name: __( 'aqua' ),
				color: '#7fdbff',
			},
			{
				name: __( 'teal' ),
				color: '#39cccc',
			},
			{
				name: __( 'olive' ),
				color: '#3d9970',
			},
			{
				name: __( 'green' ),
				color: '#2ecc40',
			},
			{
				name: __( 'lime' ),
				color: '#01ff70',
			},
			{
				name: __( 'yellow' ),
				color: '#ffdc00',
			},
			{
				name: __( 'orange' ),
				color: '#ff851b',
			},
			{
				name: __( 'red' ),
				color: '#ff4136',
			},
			{
				name: __( 'maroon' ),
				color: '#85144b',
			},
			{
				name: __( 'fuchsia' ),
				color: '#f012be',
			},
			{
				name: __( 'purple' ),
				color: '#b10dc9',
			},
			{
				name: __( 'black' ),
				color: '#111111',
			},
			{
				name: __( 'gray' ),
				color: '#aaaaaa',
			},
			{
				name: __( 'silver' ),
				color: '#dddddd',
			},
		];
		let colors;
		if ( hasDefaultColors ) {
			colors = defaultColors;
		} else if ( suggestedColors ) {
			colors = suggestedColors;
		} else {
			colors = null;
		}
		return (
			<div className="newspack-color-picker">
				<div className="newspack-color-picker__label">
					{ label }
					<ColorIndicator colorValue={ color } />
				</div>
				<ColorPalette colors={ colors } value={ color } onChange={ onChange } { ...otherProps } />
			</div>
		);
	}
}

ColorPicker.defaultProps = {
	color: '#ffffff',
	onChange: () => null,
};

export default ColorPicker;
