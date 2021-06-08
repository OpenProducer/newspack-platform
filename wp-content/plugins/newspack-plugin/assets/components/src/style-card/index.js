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
	render() {
		const {
			ariaLabel,
			className,
			cardTitle,
			url,
			image,
			imageType,
			isActive,
			onClick,
			id,
		} = this.props;
		const classes = classnames(
			'newspack-style-card',
			isActive && 'newspack-style-card__is-active',
			className
		);
		return (
			<div className={ classes } id={ id }>
				<div className="newspack-style-card__image">
					{ imageType === 'html' ? (
						<div dangerouslySetInnerHTML={ image } />
					) : (
						<img src={ image } alt={ cardTitle + ' ' + __( 'Thumbnail', 'newspack' ) } />
					) }
					<div className="newspack-style-card__actions">
						{ ! isActive && (
							<Button
								isLink
								onClick={ onClick }
								aria-label={
									ariaLabel ? ariaLabel : __( 'Activate', 'newspack' ) + ' ' + cardTitle
								}
								tabIndex="0"
							>
								{ __( 'Activate', 'newspack' ) }
							</Button>
						) }
						{ url && <WebPreview url={ url } label={ __( 'View Demo', 'newspack' ) } isLink /> }
					</div>
				</div>
				{ cardTitle && (
					<div
						className="newspack-style-card__title"
						title={ isActive ? __( 'Active', 'newspack' ) : null }
					>
						{ cardTitle }
					</div>
				) }
			</div>
		);
	}
}

export default StyleCard;
