/**
 * Button Card
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { Icon, chevronRight } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

class ButtonCard extends Component {
	/**
	 * Render
	 */
	render() {
		const { chevron, className, desc, grouped, icon, isSmall, title, ...otherProps } = this.props;
		const classes = classnames(
			'newspack-button-card',
			className,
			chevron && 'has-chevron',
			grouped && 'grouped',
			icon && 'has-icon',
			isSmall && 'is-small'
		);

		return (
			<a className={ classes } { ...otherProps }>
				{ icon && <Icon icon={ icon } height={ 48 } width={ 48 } /> }
				<div>
					{ title && <div className="title">{ title }</div> }
					{ desc && <div className="desc">{ desc }</div> }
				</div>
				{ chevron && <Icon icon={ chevronRight } height={ 24 } width={ 24 } /> }
			</a>
		);
	}
}

export default ButtonCard;
