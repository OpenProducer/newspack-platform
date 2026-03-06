/**
 * Card using WP Core's Card component.
 * https://wordpress.github.io/gutenberg/?path=/docs/components-card--docs
 */

/**
 * WordPress dependencies
 */
import { Card as CardWrapper, CardHeader, CardFooter, ToggleControl } from '@wordpress/components';
import { Icon, chevronRight } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import './style-core.scss';

/**
 * External dependencies
 */
import classNames from 'classnames';

const CoreCard = ( {
	actionType,
	as,
	buttonsCard,
	className,
	footer,
	header,
	icon,
	iconBackgroundColor,
	isActive,
	isNarrow,
	isSmall,
	onHeaderClick,
	noBorder,
	children,
	...otherProps
} ) => {
	const classes = classNames(
		'newspack-card--core',
		className,
		( buttonsCard || as === 'a' ) && 'newspack-card--core__buttons-card',
		isNarrow && 'newspack-card--core__is-narrow',
		isSmall && 'newspack-card--core__is-small',
		icon && 'newspack-card--core__has-icon',
		iconBackgroundColor && 'newspack-card--core__has-icon-background-color',
		isActive && 'newspack-card--core__is-active',
		children && 'newspack-card--core__has-children'
	);
	let sizeProps = isSmall ? 'small' : otherProps.size;
	if ( buttonsCard || as === 'a' ) {
		if ( ! isSmall ) {
			sizeProps = 'large';
		}
		if ( as !== 'a' ) {
			otherProps.as = 'a'; // Render as an anchor tag.
		}
	}
	if ( noBorder ) {
		otherProps.isBorderless = true;
	}
	return (
		<CardWrapper as={ as } className={ classes } { ...otherProps }>
			{ ( header || icon ) && (
				<CardHeader
					as={ onHeaderClick ? 'button' : undefined }
					className="newspack-card--core__header"
					size={ sizeProps }
					onClick={ onHeaderClick }
				>
					{ icon && (
						<div className="newspack-card--core__icon">
							<Icon icon={ icon } height={ isSmall ? 24 : 48 } width={ isSmall ? 24 : 48 } />
						</div>
					) }
					{ header && <div className="newspack-card--core__header-content">{ header }</div> }
					{ actionType === 'chevron' && <Icon className="newspack-card--core__action" icon={ chevronRight } height={ 24 } width={ 24 } /> }
					{ actionType === 'toggle' && (
						<ToggleControl
							className="newspack-card--core__action"
							label={ otherProps.title }
							hideLabelFromVision
							checked={ isActive }
							onChange={ () => {} }
						/>
					) }
				</CardHeader>
			) }
			{ children && <div className="newspack-card--core__body">{ children }</div> }
			{ footer && <CardFooter size={ sizeProps }>{ footer }</CardFooter> }
		</CardWrapper>
	);
};

export default CoreCard;
