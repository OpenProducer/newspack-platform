/**
 * Waiting
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * Material UI dependencies.
 */
import LoopIcon from '@material-ui/icons/Loop';

/**
 * Internal dependencies.
 */
import './style.scss';

/**
 * External dependencies.
 */
import classNames from 'classnames';

class Waiting extends Component {
	/**
	 * Render
	 */
	render() {
		const { className, isRight, isLeft } = this.props;
		const classes = classNames(
			'newspack-is-waiting',
			className,
			isRight && 'newspack-is-waiting__is-right',
			isLeft && 'newspack-is-waiting__is-left'
		);
		return <LoopIcon className={ classes } />;
	}
}

export default Waiting;
