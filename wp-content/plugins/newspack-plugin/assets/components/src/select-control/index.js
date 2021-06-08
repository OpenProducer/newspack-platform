/**
 * Select Control
 */

/**
 * WordPress dependencies
 */
import { SelectControl as BaseComponent } from '@wordpress/components';
import { Component } from '@wordpress/element';

/**
 * External dependencies
 */
import classNames from 'classnames';

/**
 * Internal dependencies
 */
import './style.scss';
import GroupedSelectControl from './GroupedSelectControl';
import ButtonGroupControl from './ButtonGroupControl';

class SelectControl extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, optgroups, buttonOptions, ...otherProps } = this.props;
		const classes = classNames(
			'newspack-select-control',
			optgroups && 'newspack-grouped-select-control',
			buttonOptions && 'newspack-buttons-select-control',
			className
		);
		return (
			<div className={ classes }>
				{ /* eslint-disable no-nested-ternary */ }
				{ optgroups ? (
					<GroupedSelectControl optgroups={ optgroups } { ...otherProps } />
				) : buttonOptions ? (
					<ButtonGroupControl buttonOptions={ buttonOptions } { ...otherProps } />
				) : (
					<BaseComponent { ...otherProps } />
				) }
				{ /* eslint-enable no-nested-ternary */ }
			</div>
		);
	}
}

export default SelectControl;
