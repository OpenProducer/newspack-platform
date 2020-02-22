/**
 * Formatted header for titling screens/sections.
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classNames from 'classnames';

class FormattedHeader extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, headerIcon, headerText, subHeaderText } = this.props;
		const classes = classNames( 'newspack-formatted-header', className );
		return (
			<header className={ classes }>
				<h1 className="newspack-formatted-header__title">
					{ headerIcon && (
						<span className="newspack-formatted-header__icon">{ headerIcon }</span>
					) }
					{ headerText }
				</h1>
				{ subHeaderText && (
					<p className="newspack-formatted-header__subtitle">{ subHeaderText }</p>
				) }
			</header>
		);
	}
}

export default FormattedHeader;
