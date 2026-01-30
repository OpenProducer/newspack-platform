/**
 * Content Gate component.
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { CheckboxControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import ContentRuleControlTaxonomy from './content-rule-control-taxonomy';
import { FormTokenField } from '../../../../../packages/components/src';

export default function ContentRuleControl( { slug, value, exclusion, onChange, onChangeExclusion }: GateContentRuleControlProps ) {
	const rule = window.newspackAudienceContentGates.available_content_rules[ slug ];

	if ( ! rule || ! Array.isArray( value ) ) {
		return null;
	}

	return (
		<div className="newspack-content-gates__content-rule-control">
			{ rule.options && rule.options.length > 0 ? (
				<FormTokenField
					label={ rule.name }
					value={ rule.options.filter( o => value.includes( o.value ) ).map( o => o.label ) }
					onChange={ ( items: string[] ) => onChange( rule.options?.filter( o => items.includes( o.label ) ).map( o => o.value ) ?? [] ) }
					suggestions={ rule.options.map( o => o.label ) }
					__experimentalExpandOnFocus
					__next40pxDefaultSize
				/>
			) : (
				<ContentRuleControlTaxonomy slug={ slug } value={ value } onChange={ onChange } />
			) }
			<CheckboxControl
				label={ __( 'Exclusion rule', 'newspack-plugin' ) }
				help={ __( 'Apply this rule to everything EXCEPT the items matching the above.', 'newspack-plugin' ) }
				checked={ exclusion ?? false }
				onChange={ e => onChangeExclusion?.( e ) }
			/>
		</div>
	);
}
