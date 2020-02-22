/**
 * Checkbox Control
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { CheckboxControl as BaseComponent } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { InfoButton } from '../';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

class CheckboxControl extends Component {

	/**
	 * Render.
	 */
	render() {
		const { className, tooltip, ...otherProps } = this.props;
		const classes = classnames( 'newspack-checkbox-control', className );
		return (
			<div className={ classes }>
				<BaseComponent { ...otherProps } />
				{ tooltip && <InfoButton text={ tooltip } /> }
			</div>
		);
	}
}

export default CheckboxControl;
