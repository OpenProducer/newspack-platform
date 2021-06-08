/**
 * Action Card
 */

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { Button, Card, Handoff, Notice, ToggleControl, Waiting } from '../';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

class ActionCard extends Component {
	backgroundImageStyles = url => {
		return url ? { backgroundImage: `url(${ url })` } : {};
	};

	/**
	 * Render
	 */
	render() {
		const {
			badge,
			className,
			children,
			disabled,
			title,
			description,
			handoff,
			editLink,
			href,
			notification,
			notificationLevel,
			notificationHTML,
			actionText,
			secondaryActionText,
			image,
			imageLink,
			isSmall,
			isMedium,
			simple,
			onClick,
			onSecondaryActionClick,
			isWaiting,
			titleLink,
			toggleChecked,
			toggleOnChange,
			hasGreyHeader,
		} = this.props;
		const hasChildren = notification || children;
		const classes = classnames(
			'newspack-action-card',
			simple && 'newspack-card--is-clickable',
			hasGreyHeader && 'newspack-card--has-grey-header',
			hasChildren && 'newspack-card--has-children',
			isSmall && 'is-small',
			isMedium && 'is-medium',
			className
		);
		const titleProps =
			toggleOnChange && ! titleLink && ! disabled
				? { onClick: () => toggleOnChange( ! toggleChecked ), tabIndex: '0' }
				: {};
		const hasInternalLink = href && href.indexOf( 'http' ) !== 0;
		return (
			<Card className={ classes } onClick={ simple && onClick }>
				<div className="newspack-action-card__region newspack-action-card__region-top">
					{ toggleOnChange && (
						<ToggleControl
							checked={ toggleChecked }
							onChange={ toggleOnChange }
							disabled={ disabled }
						/>
					) }
					{ image && ! toggleOnChange && (
						<div className="newspack-action-card__region newspack-action-card__region-left">
							<a href={ imageLink }>
								<div
									className="newspack-action-card__image"
									style={ this.backgroundImageStyles( image ) }
								/>
							</a>
						</div>
					) }
					<div className="newspack-action-card__region newspack-action-card__region-center">
						<h2>
							<span className="newspack-action-card__title" { ...titleProps }>
								{ titleLink ? <a href={ titleLink }>{ title }</a> : title }
							</span>
							{ badge && <span className="newspack-action-card__badge">{ badge }</span> }
						</h2>
						<p>{ description }</p>
					</div>
					{ actionText && (
						<div className="newspack-action-card__region newspack-action-card__region-right">
							{ /* eslint-disable no-nested-ternary */
							handoff ? (
								<Handoff plugin={ handoff } editLink={ editLink } compact isLink>
									{ actionText }
								</Handoff>
							) : onClick || hasInternalLink ? (
								<Button
									isLink
									href={ href }
									onClick={ onClick }
									className="newspack-action-card__primary_button"
								>
									{ actionText }
								</Button>
							) : href ? (
								<ExternalLink href={ href } className="newspack-action-card__primary_button">
									{ actionText }
								</ExternalLink>
							) : (
								<div className="newspack-action-card__container">
									{ actionText }
									{ isWaiting && <Waiting isRight /> }
								</div>
							) }
							{ /* eslint-enable no-nested-ternary */ }

							{ secondaryActionText && onSecondaryActionClick && (
								<Button
									isLink
									onClick={ onSecondaryActionClick }
									className="newspack-action-card__secondary_button"
								>
									{ secondaryActionText }
								</Button>
							) }
						</div>
					) }
				</div>
				{ notification && (
					<div className="newspack-action-card__notification newspack-action-card__region-children">
						{ 'error' === notificationLevel && (
							<Notice noticeText={ notification } isError rawHTML={ notificationHTML } />
						) }
						{ 'info' === notificationLevel && (
							<Notice noticeText={ notification } rawHTML={ notificationHTML } />
						) }
						{ 'success' === notificationLevel && (
							<Notice noticeText={ notification } isSuccess rawHTML={ notificationHTML } />
						) }
						{ 'warning' === notificationLevel && (
							<Notice noticeText={ notification } isWarning rawHTML={ notificationHTML } />
						) }
					</div>
				) }
				{ children && <div className="newspack-action-card__region-children">{ children }</div> }
			</Card>
		);
	}
}

ActionCard.defaultProps = {
	toggleChecked: false,
};

export default ActionCard;
