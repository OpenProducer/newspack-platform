/**
 * Waiting
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';
import { Path, SVG } from '@wordpress/components';
import { Icon } from '@wordpress/icons';

/**
 * Internal dependencies.
 */
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

export const icon = (
	<SVG xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
		<Path
			clipRule="evenodd"
			d="M12 4h-.033a7.975 7.975 0 00-5.636 2.322L5.138 5.129l-.353 3.889 3.889-.354-1.282-1.281A6.48 6.48 0 0112 5.5a6.48 6.48 0 014.63 1.904 6.469 6.469 0 011.848 3.747l1.487-.195a7.969 7.969 0 00-2.275-4.613A7.975 7.975 0 0012.033 4H12zm-6.478 8.85l-1.487.194a7.969 7.969 0 002.275 4.613A7.975 7.975 0 0012 20a7.975 7.975 0 005.669-2.322l1.193 1.193.353-3.889-3.889.354 1.282 1.281A6.48 6.48 0 0112 18.5a6.48 6.48 0 01-4.63-1.904 6.469 6.469 0 01-1.848-3.747z"
			fillRule="evenodd"
		/>
	</SVG>
);

class Waiting extends Component {
	/**
	 * Render
	 */
	render() {
		const { className, isRight, isLeft } = this.props;
		const classes = classnames(
			'newspack-is-waiting',
			className,
			isRight && 'newspack-is-waiting__is-right',
			isLeft && 'newspack-is-waiting__is-left'
		);
		return <Icon icon={ icon } className={ classes } />;
	}
}

export default Waiting;
