/**
 * Info Button
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';
import { Tooltip } from '@wordpress/components';

/**
 * Material UI dependencies.
 */
import InfoIcon from '@material-ui/icons/Info';

/**
 * External dependencies.
 */
import classnames from 'classnames';

/**
 * Internal dependencies.
 */
import './style.scss';

class InfoButton extends Component {

	/**
	 * Render.
	 */
	render() {
		const { className, ...otherProps } = this.props;
		return (
			<Tooltip { ...otherProps }>
				<div className={ classnames( 'newspack-info-button', className ) }>
					<InfoIcon />
				</div>
			</Tooltip>
		);
	}
}

export default InfoButton;
