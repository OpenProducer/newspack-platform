/**
 * Modal
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';
import { Modal as BaseComponent } from '@wordpress/components';

/**
 * Internal dependencies.
 */
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

class Modal extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, overlayClassName, isWide, ...otherProps } = this.props;
		const classes = classnames( 'newspack-modal', isWide && 'newspack-modal--wide', className );
		const overlayClasses = classnames( 'newspack-modal__overlay', overlayClassName );

		return (
			<BaseComponent className={ classes } overlayClassName={ overlayClasses } { ...otherProps } />
		);
	}
}

export default Modal;
