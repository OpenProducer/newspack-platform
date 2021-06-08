/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { Icon } from '@wordpress/icons';
import { BaseControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { Button, ButtonGroup, hooks } from '..';

const ButtonGroupControl = ( { buttonOptions, onChange, value, label, className } ) => {
	const id = hooks.useUniqueId( 'button-group' );
	return (
		<BaseControl
			label={ label }
			id={ id.current }
			className={ classnames( className, 'components-select-control' ) }
		>
			<ButtonGroup noMargin>
				{ buttonOptions.map( option => {
					const isSelected = value === option.value;
					let Label = () => option.label;
					if ( option.icon ) {
						// eslint-disable-next-line react/display-name
						Label = () => <Icon icon={ option.icon } />;
					}
					return (
						<Button
							key={ option.value }
							isSecondary={ ! isSelected }
							isPressed={ isSelected }
							onClick={ () => onChange( option.value ) }
						>
							<Label />
						</Button>
					);
				} ) }
			</ButtonGroup>
		</BaseControl>
	);
};

export default ButtonGroupControl;
