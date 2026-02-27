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
	onChange: React.Dispatch< React.SetStateAction< Metering > > | ( ( metering: Metering ) => void );
}

export default function Metering( { metering, onChange }: MeteringProps ) {
	const count = typeof metering.count === 'number' ? metering.count : parseInt( String( metering.count ), 10 );
	const isCountZero = ! isNaN( count ) && count === 0;

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
					onChange={ () => onChange( { ...metering, enabled: ! metering.enabled } ) }
				/>
				{ metering.enabled && isCountZero && (
					<Notice
						isWarning
						noticeText={ __(
							'Metering is enabled but the number of views is set to 0. Content will be gated for all readers.',
							'newspack-plugin'
						) }
					/>
				) }
			</Card>
			{ metering.enabled && (
				<Grid columns={ 2 } gutter={ 32 } noMargin={ true }>
					<NumberControl
						label={ __( 'Number of views', 'newspack-plugin' ) }
						help={ __(
							'Number of times a reader can view gated content. If set to 0, readers will always be gated.',
							'newspack-plugin'
						) }
						min={ 0 }
						value={ count }
						onChange={ v => onChange( { ...metering, count: v !== undefined ? Number( v ) : 0 } ) }
					/>
					<SelectControl
						label={ __( 'Period', 'newspack-plugin' ) }
						help={ __(
							'The period during which the metering views will be counted. For example, if the metering period is set to "Weekly", the metering views will be reset every week.',
							'newspack-plugin'
						) }
						value={ metering.period }
						onChange={ v => onChange( { ...metering, period: v as Metering[ 'period' ] } ) }
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
