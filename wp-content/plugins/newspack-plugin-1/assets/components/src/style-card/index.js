/**
 * Style Card
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Button, WebPreview } from '../';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

class StyleCard extends Component {
	/**
	 * Render.
	 */
	render( props ) {
		const { className, cardTitle, url, image, isActive, onClick, ...otherProps } = this.props;
		const classes = classnames(
			'newspack-style-card',
			isActive && 'newspack-style-card__is-active',
			className
		);
		return (
			<div className={ classes } tabIndex="0">
				<div className="newspack-style-card__image">
				{ image && <img src={ image } /> }
					<div className="newspack-style-card__actions">
						{ ! isActive && <Button isPrimary isSmall onClick={ onClick }>{ __( 'Activate' ) }</Button> }
						{ url && <WebPreview url={ url } label={ __( 'View Demo' ) } isSmall isSecondary /> }
					</div>
				</div>
				<div className="newspack-style-card__content">
					<h2 className="newspack-style-card__heading">
						<span className="newspack-style-card__title">{ cardTitle }</span>
						{ isActive && <span className="newspack-style-card__status">{ __( 'Active' ) }</span> }
					</h2>
				</div>
			</div>
		);
	}
}

export default StyleCard;
