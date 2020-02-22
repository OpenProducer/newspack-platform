/**
 * Component for inputting money amounts.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { TextControl } from '../../../../components/src';
import './style.scss';

/**
 * Settings for donation collection.
 */
class MoneyInput extends Component {

	/**
	 * Render.
	 */
	render() {
		const { currencySymbol, label, value, onChange } = this.props;

		return (
			<div className='newspack-donations-wizard__money-input'>
				<span className='currency'>{ currencySymbol }</span>
				<TextControl
					type="number"
					step="0.01"
					label={ label }
					value={ value }
					onChange={ onChange }
				/>
			</div>
		);
	}
}

export default MoneyInput;
