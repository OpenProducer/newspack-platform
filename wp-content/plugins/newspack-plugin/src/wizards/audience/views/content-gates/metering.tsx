/**
 * WordPress dependencies.
 */
import { CheckboxControl, __experimentalNumberControl as NumberControl } from '@wordpress/components'; // eslint-disable-line @wordpress/no-unsafe-wp-apis
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, Card, Grid, Notice, SelectControl } from '../../../../../packages/components/src';

interface MeteringProps {
	metering: Metering;
	onChange: React.Dispatch< React.SetStateAction< Metering > >;
}

export default function Metering( { metering, onChange }: MeteringProps ) {
	return (
		<ActionCard
			title={ __( 'Metering', 'newspack-plugin' ) }
			description={ __( 'Configure how many times a reader can view restricted content before being gated.', 'newspack-plugin' ) }
			hasWhiteHeader={ true }
			noBorder={ true }
			noMargin={ true }
		>
			<Card noBorder>
				<CheckboxControl
					label={ __( 'Meter content views for this gate', 'newspack-plugin' ) }
					checked={ metering.enabled }
					onChange={ () => onChange( prevMetering => ( { ...prevMetering, enabled: ! prevMetering.enabled } ) ) }
				/>
				{ metering.enabled && parseInt( metering.anonymous_count ) === 0 && parseInt( metering.registered_count ) === 0 && (
					<Notice
						isWarning
						noticeText={ __(
							'Metering is enabled but no views are available. Content will be gated for all readers.',
							'newspack-plugin'
						) }
					/>
				) }
			</Card>
			{ metering.enabled && (
				<Grid columns={ 2 } gutter={ 32 } noMargin={ true }>
					<NumberControl
						label={ __( 'Free views for anonymous viewers', 'newspack-plugin' ) }
						help={ __(
							'Number of times an anonymous reader can view gated content. If set to 0, anonymous readers will always render the gate.',
							'newspack-plugin'
						) }
						min={ 0 }
						value={ parseInt( metering.anonymous_count ) }
						onChange={ v => onChange( prevMetering => ( { ...prevMetering, anonymous_count: parseInt( v ) } ) ) }
					/>
					<NumberControl
						label={ __( 'Free views for registered viewers', 'newspack-plugin' ) }
						help={ __(
							'Number of times a registered reader can view gated content. If set to 0, registered readers will always render the gate.',
							'newspack-plugin'
						) }
						min={ 0 }
						value={ parseInt( metering.registered_count ) }
						onChange={ v => onChange( prevMetering => ( { ...prevMetering, registered_count: parseInt( v ) } ) ) }
					/>
					<SelectControl
						label={ __( 'Time period', 'newspack-plugin' ) }
						help={ __(
							'The time period during which the metering views will be counted. For example, if the metering period is set to "Weekly", the metering views will be reset every week.',
							'newspack-plugin'
						) }
						value={ metering.period }
						onChange={ v => onChange( prevMetering => ( { ...prevMetering, period: v as Metering[ 'period' ] } ) ) }
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
		</ActionCard>
	);
}
