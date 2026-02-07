/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { useCallback } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { ActionCard, Card } from '../../../../../packages/components/src';
import { getEditGateLayoutUrl } from './utils';
import Metering from './metering';
import AccessRules from './access-rules';

interface CustomAccessProps {
	gateId?: number;
	customAccess: CustomAccess;
	onChange: ( customAccess: Partial< CustomAccess > ) => void;
	cardProps?: Partial< React.ComponentPropsWithoutRef< typeof ActionCard > >;
}

export default function CustomAccess( { gateId, customAccess, onChange, cardProps = {} }: CustomAccessProps ) {
	const handleChange = useCallback(
		( value: Partial< CustomAccess > ) => {
			onChange( {
				active: customAccess.active,
				metering: customAccess.metering,
				access_rules: customAccess.access_rules,
				...value,
			} );
		},
		[ customAccess, onChange ]
	);
	return (
		<ActionCard
			title={ __( 'Paid Access', 'newspack-plugin' ) }
			description={ __( 'Readers must pay to view this content.', 'newspack-plugin' ) }
			toggleChecked={ customAccess.active }
			toggleOnChange={ ( active: boolean ) => handleChange( { active } ) }
			actionText={ gateId ? __( 'Edit Layout', 'newspack-plugin' ) : undefined }
			href={ gateId ? getEditGateLayoutUrl( gateId, 'custom_access' ) : undefined }
			{ ...cardProps }
		>
			{ customAccess.active && (
				<Card noBorder>
					<AccessRules
						rules={ customAccess.access_rules }
						onChange={ ( rules: GateAccessRule[] ) => handleChange( { access_rules: rules } ) }
					/>
					<hr />
					<Metering metering={ customAccess.metering } onChange={ ( metering: Metering ) => handleChange( { metering } ) } />
				</Card>
			) }
		</ActionCard>
	);
}
