/**
 * WordPress dependencies.
 */
import { CheckboxControl, TextControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { FormTokenField } from '../../../../../packages/components/src';

const noop = () => {};

export default function AccessRuleControl( { slug, value, onChange }: GateAccessRuleControlProps ) {
	const rule = window.newspackAudienceContentGates.available_access_rules[ slug ];

	if ( ! rule ) {
		return null;
	}
	if ( rule.is_boolean ) {
		return <CheckboxControl label={ rule.name } checked={ true } onChange={ noop } disabled help={ rule.description } />;
	}
	if ( rule.options && rule.options.length > 0 ) {
		return (
			<FormTokenField
				label={ rule.name }
				value={ rule.options.filter( o => value.includes( o.value ) ).map( o => o.label ) }
				onChange={ ( items: string[] ) => onChange( rule.options?.filter( o => items.includes( o.label ) ).map( o => o.value ) ?? [] ) }
				suggestions={ rule.options.map( o => o.label ) }
				__experimentalExpandOnFocus
				__next40pxDefaultSize
			/>
		);
	}
	return <TextControl label={ rule.name } value={ value as string } onChange={ onChange } help={ rule.description } __next40pxDefaultSize />;
}
