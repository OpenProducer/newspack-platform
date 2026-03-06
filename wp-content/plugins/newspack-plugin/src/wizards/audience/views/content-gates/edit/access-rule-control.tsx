/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { TextControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { FormTokenField } from '../../../../../../packages/components/src';

export default function AccessRuleControl( { slug, value, onChange }: GateRuleControlProps ) {
	const rule = window.newspackAudienceContentGates.available_access_rules[ slug ];

	if ( ! rule || rule.is_boolean ) {
		return null;
	}
	if ( rule.options && rule.options.length > 0 ) {
		return (
			<FormTokenField
				label={ '' }
				value={ rule.options.filter( o => value.includes( o.value ) ).map( o => o.label ) }
				onChange={ ( items: string[] ) => onChange( rule.options?.filter( o => items.includes( o.label ) ).map( o => o.value ) ?? [] ) }
				suggestions={ rule.options.map( o => o.label ) }
				__experimentalExpandOnFocus
				__next40pxDefaultSize
			/>
		);
	}
	return (
		<TextControl
			hideLabelFromVision
			label={ rule.name }
			help={ __( 'Separate with commas.', 'newspack-plugin' ) }
			value={ value as string }
			onChange={ onChange }
			__next40pxDefaultSize
		/>
	);
}
