/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';
import { Fragment, useState, useEffect, useMemo } from '@wordpress/element';
import { DropdownMenu, SelectControl, CheckboxControl, TextControl, Button } from '@wordpress/components';
import { shield } from '@wordpress/icons';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, Grid, Card, SectionHeader } from '../../../../../packages/components/src';
import RulesChoices from './rules-choices';
import AccessRuleControl from './access-rule-control';

const availableRules = window.newspackAudienceContentGates.available_rules || {};

type ContentGateSettingsProps = {
	value: Gate;
	onDelete: ( id: number ) => void;
};

export default function ContentGateSettings( { value, onDelete }: ContentGateSettingsProps ) {
	const [ gate, setGate ] = useState< Gate >( value );

	useEffect( () => {
		setGate( value );
	}, [ value ] );

	const handleToggleAccessRule = ( slug: string ) => {
		const rule = availableRules[ slug ];
		if ( ! rule ) {
			return;
		}
		if ( hasRule( slug ) ) {
			setGate( {
				...gate,
				access_rules: [ ...gate.access_rules.filter( r => r.slug !== slug ) ],
			} );
		} else {
			setGate( {
				...gate,
				access_rules: [ ...gate.access_rules, { slug, value: rule.default } ],
			} );
		}
	};

	const handleUpdateAccessRule = ( slug: string ) => ( v: string | string[] | boolean ) => {
		setGate( {
			...gate,
			access_rules: gate.access_rules.map( r => ( r.slug === slug ? { ...r, value: v } : r ) ),
		} );
	};

	const isRuleDisabled = ( slug: string ): boolean => {
		const conflicts = availableRules[ slug ].conflicts;
		// Check whether any conflicting rule is enabled.
		if ( conflicts?.some( conflict => gate.access_rules.find( r => r.slug === conflict ) ) ) {
			return true;
		}
		return false;
	};

	const hasRule = ( slug: string ): boolean => {
		if ( ! gate ) {
			return false;
		}
		return !! gate.access_rules.find( r => r.slug === slug );
	};

	const accessRulesChoices = useMemo( () => {
		return Object.keys( availableRules ).map( slug => {
			const rule = availableRules[ slug ];
			return {
				label: rule.name,
				value: slug,
				disabled: isRuleDisabled( slug ),
				info: rule.description,
			};
		} );
	}, [ gate.access_rules ] );

	const handleSave = () => {
		apiFetch< Gate >( {
			path: `/newspack/v1/content-gate/${ gate.id }`,
			method: 'POST',
			data: { gate },
		} )
			.then( data => {
				setGate( data );
			} )
			.catch( error => console.error( error ) ); // eslint-disable-line no-console
	};

	const handleDelete = () => onDelete( gate.id );

	return (
		<Fragment>
			<ActionCard
				title={ __( 'Access Rules', 'newspack-plugin' ) }
				description={ __( 'Configure how readers can bypass this content gate.', 'newspack-plugin' ) }
				hasWhiteHeader={ true }
				noBorder={ true }
				noMargin={ true }
				actionContent={
					<DropdownMenu icon={ shield } text={ __( 'Manage Rules', 'newspack-plugin' ) } label={ __( 'Manage Rules', 'newspack-plugin' ) }>
						{ () => (
							<RulesChoices
								choices={ accessRulesChoices }
								onSelect={ handleToggleAccessRule }
								value={ gate.access_rules.map( r => r.slug ) }
							/>
						) }
					</DropdownMenu>
				}
			>
				{ gate.access_rules.length > 0 && (
					<Grid columns={ Math.min( 3, gate.access_rules.length ) } gutter={ 32 }>
						{ gate.access_rules.map( ( rule: GateRule ) => (
							<AccessRuleControl
								key={ rule.slug }
								slug={ rule.slug }
								value={ rule.value }
								onChange={ handleUpdateAccessRule( rule.slug ) }
							/>
						) ) }
					</Grid>
				) }
			</ActionCard>
			<ActionCard
				title={ __( 'Content Rules', 'newspack-plugin' ) }
				description={ __( 'Configure which content is restricted by this content gate.', 'newspack-plugin' ) }
				hasWhiteHeader={ true }
				noBorder={ true }
				noMargin={ true }
				actionContent={
					<DropdownMenu
						icon="plus"
						toggleProps={ {
							iconSize: 16,
						} }
						text={ __( 'Add Rule', 'newspack-plugin' ) }
						label={ __( 'Add Rule', 'newspack-plugin' ) }
						controls={ [
							{
								title: __( 'Post types', 'newspack-plugin' ),
							},
							{
								title: __( 'Categories', 'newspack-plugin' ),
							},
							{
								title: __( 'Tags', 'newspack-plugin' ),
							},
						] }
					/>
				}
			>
				{ gate.access_rules.length > 0 && <Grid columns={ 3 } gutter={ 32 } /> }
			</ActionCard>
			<Card noBorder>
				<SectionHeader heading={ 3 } title={ __( 'Metering', 'newspack-plugin' ) } noMargin />
				<Card noBorder>
					<CheckboxControl
						label={ __( 'Meter content views for this gate', 'newspack-plugin' ) }
						checked={ gate.metering.enabled }
						onChange={ () => setGate( { ...gate, metering: { ...gate.metering, enabled: ! gate.metering.enabled } } ) }
					/>
				</Card>
				{ gate.metering.enabled && (
					<Grid columns={ 3 } gutter={ 32 }>
						<TextControl
							type={ 'number' }
							label={ __( 'Article limit for anonymous viewers', 'newspack-plugin' ) }
							help={ __(
								'Number of times an anonymous reader can view gated content. If set to 0, anonymous readers will always render the gate.',
								'newspack-plugin'
							) }
							value={ gate.metering.anonymous_count }
							onChange={ v => setGate( { ...gate, metering: { ...gate.metering, anonymous_count: parseInt( v ) } } ) }
						/>
						<TextControl
							type={ 'number' }
							label={ __( 'Article limit for registered viewers', 'newspack-plugin' ) }
							help={ __(
								'Number of times a registered reader can view gated content. If set to 0, registered readers will always render the gate.',
								'newspack-plugin'
							) }
							value={ gate.metering.registered_count }
							onChange={ v => setGate( { ...gate, metering: { ...gate.metering, registered_count: parseInt( v ) } } ) }
						/>
						<SelectControl
							label={ __( 'Time period', 'newspack-plugin' ) }
							help={ __(
								'The time period during which the metering views will be counted. For example, if the metering period is set to "Weekly", the metering views will be reset every week.',
								'newspack-plugin'
							) }
							value={ gate.metering.period }
							onChange={ v => setGate( { ...gate, metering: { ...gate.metering, period: v } } ) }
							options={ [
								{
									value: 'week',
									label: __( 'Weekly', 'newspack-plugin' ),
								},
								{
									value: 'month',
									label: __( 'Monthly', 'newspack-plugin' ),
								},
							] }
						/>
					</Grid>
				) }
			</Card>
			<div className="newspack-buttons-card">
				<Button variant="primary" onClick={ handleSave }>
					{ __( 'Save Settings', 'newspack-plugin' ) }
				</Button>
				<Button isDestructive variant="secondary" onClick={ handleDelete }>
					{ __( 'Delete', 'newspack-plugin' ) }
				</Button>
			</div>
		</Fragment>
	);
}
