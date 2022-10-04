/**
 * Popup style options.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

const StylesSidebar = props => {
	const { onMetaFieldChange, hide_border, large_border } = props;

	return (
		<div className="newspack-popups-style-selector">
			<Button
				variant={ ! hide_border && ! large_border ? 'primary' : 'secondary' }
				isPressed={ ! hide_border && ! large_border }
				onClick={ () => onMetaFieldChange( { hide_border: false, large_border: false } ) }
				aria-current={ ! hide_border && ! large_border }
			>
				{ __( 'Default', 'newspack-popups' ) }
			</Button>
			<Button
				variant={ hide_border ? 'primary' : 'secondary' }
				isPressed={ hide_border }
				onClick={ () => onMetaFieldChange( { hide_border: true, large_border: false } ) }
				aria-current={ hide_border }
			>
				{ __( 'Hide Border', 'newspack-popups' ) }
			</Button>
			<Button
				variant={ large_border ? 'primary' : 'secondary' }
				isPressed={ large_border }
				onClick={ () => onMetaFieldChange( { hide_border: false, large_border: true } ) }
				aria-current={ large_border }
			>
				{ __( 'Large Border', 'newspack-popups' ) }
			</Button>
		</div>
	);
};

export default StylesSidebar;
