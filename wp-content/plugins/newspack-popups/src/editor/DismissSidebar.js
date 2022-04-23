/**
 * Dismiss button settings.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { SelectControl, TextControl, ToggleControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { isOverlayPlacement } from './utils';

const DismissSidebar = ( {
	dismiss_text,
	dismiss_text_alignment,
	onMetaFieldChange,
	placement,
	undismissible_prompt,
} ) => {
	return (
		<>
			{ ! isOverlayPlacement( placement ) && (
				<ToggleControl
					checked={ ! undismissible_prompt }
					onChange={ () => onMetaFieldChange( 'undismissible_prompt', ! undismissible_prompt ) }
					label={ __( 'Allow this prompt to be dismissed', 'newspack-popups' ) }
				/>
			) }
			{ ( isOverlayPlacement( placement ) || ! undismissible_prompt ) && (
				<>
					<TextControl
						label={ __( 'Label', 'newspack-popups' ) }
						help={ __(
							'When clicked, this button will permanently dismiss the prompt for the current reader.',
							'newspack-popups'
						) }
						value={ dismiss_text }
						onChange={ value => onMetaFieldChange( 'dismiss_text', value ) }
					/>
					<SelectControl
						label={ __( 'Alignment', 'newspack-popups' ) }
						id="newspack-popups-dimiss-button-alignment"
						onChange={ value => onMetaFieldChange( 'dismiss_text_alignment', value ) }
						value={ dismiss_text_alignment }
						options={ [
							{
								label: __( 'Center', 'newspack-popups' ),
								value: '',
							},
							{
								label: __( 'Left', 'newspack-popups' ),
								value: 'left',
							},
							{
								label: __( 'Right', 'newspack-popups' ),
								value: 'right',
							},
						] }
					/>
				</>
			) }
		</>
	);
};

export default DismissSidebar;
