/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { DropdownMenu } from '@wordpress/components';
import { useMemo, useCallback } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { ActionCard, Grid } from '../../../../../packages/components/src';
import RulesChoices from './rules-choices';
import ContentRuleControl from './content-rule-control';

const availableContentRules = window.newspackAudienceContentGates.available_content_rules || {};

interface ContentRulesProps {
	rules: GateContentRule[];
	onChange: ( rules: GateContentRule[] ) => void;
}

export default function ContentRules( { rules, onChange }: ContentRulesProps ) {
	const choices = useMemo( () => {
		return Object.keys( availableContentRules ).map( slug => {
			const rule = availableContentRules[ slug ];
			return {
				label: rule.name,
				value: slug,
				info: rule.description || '',
			};
		} );
	}, [] );

	const handleToggle = useCallback(
		( slug: string ) => {
			const hasRule = rules.find( r => r.slug === slug );
			if ( hasRule ) {
				onChange( rules.filter( r => r.slug !== slug ) );
			} else {
				onChange( [ ...rules, { slug, value: availableContentRules[ slug ].default } ] );
			}
		},
		[ rules, onChange ]
	);

	const handleChange = useCallback(
		( slug: string ) => ( v: GateContentRuleValue ) => {
			onChange( rules.map( r => ( r.slug === slug ? { ...r, value: v } : r ) ) );
		},
		[ onChange, rules ]
	);

	const handleChangeExclusion = useCallback(
		( slug: string ) => ( e: boolean ) => {
			onChange( rules.map( r => ( r.slug === slug ? { ...r, exclusion: e } : r ) ) );
		},
		[ onChange, rules ]
	);

	return (
		<ActionCard
			title={ __( 'Content Rules', 'newspack-plugin' ) }
			description={ __( 'Configure which content is restricted by this content gate.', 'newspack-plugin' ) }
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
				{ rules.map( ( rule: GateContentRule ) => (
					<ContentRuleControl
						key={ rule.slug }
						slug={ rule.slug }
						value={ rule.value }
						exclusion={ rule.exclusion }
						onChange={ handleChange( rule.slug ) }
						onChangeExclusion={ handleChangeExclusion( rule.slug ) }
					/>
				) ) }
			</Grid>
		</ActionCard>
	);
}
