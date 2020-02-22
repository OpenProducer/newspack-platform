/**
 * Form Token Field
 */

/**
 * WordPress dependencies.
 */
import { FormTokenField as BaseComponent } from '@wordpress/components';
import { Component } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

class FormTokenField extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, ...otherProps } = this.props;
		const classes = classnames( 'newspack-form-token-field__input-container', className );
		return (
			<div className="newspack-form-token-field">
				<BaseComponent className={ classes } { ...otherProps } />
			</div>
		);
	}
}

export default FormTokenField;
