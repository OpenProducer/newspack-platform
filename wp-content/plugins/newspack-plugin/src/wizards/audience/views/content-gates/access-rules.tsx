/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { DropdownMenu } from '@wordpress/components';
import { useCallback, useMemo } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { ActionCard, Grid } from '../../../../../packages/components/src';
import RulesChoices from './rules-choices';
import AccessRuleControl from './access-rule-control';

const availableAccessRules = window.newspackAudienceContentGates.available_access_rules || {};

interface AccessRulesProps {
	rules: GateAccessRule[];
	onChange: ( rules: GateAccessRule[] ) => void;
}

export default function AccessRules( { rules, onChange }: AccessRulesProps ) {
	const choices = useMemo( () => {
		return Object.keys( availableAccessRules ).map( slug => {
			const rule = availableAccessRules[ slug ];
			return {
				label: rule.name,
				value: slug,
				info: rule.description,
			};
		} );
	}, [] );

	const handleToggle = useCallback(
		( slug: string ) => {
			const hasRule = rules.find( r => r.slug === slug );
			if ( hasRule ) {
				onChange( rules.filter( r => r.slug !== slug ) );
			} else {
				onChange( [ ...rules, { slug, value: availableAccessRules[ slug ].default } ] );
			}
		},
		[ rules, onChange ]
	);

	const handleChange = useCallback(
		( slug: string ) => ( v: GateAccessRuleValue ) => {
			onChange( rules.map( r => ( r.slug === slug ? { ...r, value: v } : r ) ) );
		},
		[ onChange, rules ]
	);

	return (
		<ActionCard
			title={ __( 'Access Rules', 'newspack-plugin' ) }
			description={ __( 'Configure how readers can bypass this content gate.', 'newspack-plugin' ) }
			hasWhiteHeader={ true }
			noBorder={ true }
			noMargin={ true }
			actionContent={
				<div style={ { background: 'var(--newspack-ui-color-neutral-5)' } }>
					<DropdownMenu icon={ false } text={ __( 'Manage Rules', 'newspack-plugin' ) } label={ __( 'Manage Rules', 'newspack-plugin' ) }>
						{ () => <RulesChoices choices={ choices } onSelect={ handleToggle } value={ rules.map( r => r.slug ) } /> }
					</DropdownMenu>
				</div>
			}
		>
			<Grid columns={ 2 } gutter={ 32 } noMargin={ true }>
				{ rules.map( ( rule: GateAccessRule ) => (
					<AccessRuleControl key={ rule.slug } slug={ rule.slug } value={ rule.value } onChange={ handleChange( rule.slug ) } />
				) ) }
			</Grid>
		</ActionCard>
	);
}
