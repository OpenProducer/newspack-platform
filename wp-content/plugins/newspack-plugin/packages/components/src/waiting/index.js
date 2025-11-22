/**
 * Waiting
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';
import { Spinner } from '@wordpress/components';

/**
 * Internal dependencies.
 */
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

class Waiting extends Component {
	/**
	 * Render
	 */
	render() {
		const { className, isRight, isLeft, isCenter, ...otherProps } = this.props;
		const classes = classnames( 'newspack-waiting', className, {
			'is-right': isRight,
			'is-left': isLeft,
			'is-center': isCenter,
		} );
		return <Spinner className={ classes } { ...otherProps } />;
	}
}

export default Waiting;
