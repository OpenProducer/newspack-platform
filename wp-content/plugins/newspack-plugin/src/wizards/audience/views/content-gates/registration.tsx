/**
 * WordPress dependencies.
 */
import { CheckboxControl } from '@wordpress/components';
import { useCallback } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, Card } from '../../../../../packages/components/src';
import { getEditGateLayoutUrl } from './utils';
import Metering from './metering';

interface RegistrationProps {
	gateId?: number;
	registration: Registration;
	onChange: ( registration: Partial< Registration > ) => void;
	cardProps?: Partial< React.ComponentPropsWithoutRef< typeof ActionCard > >;
}

export default function Registration( { gateId, registration, onChange, cardProps = {} }: RegistrationProps ) {
	const handleChange = useCallback(
		( value: Partial< Registration > ) => {
			onChange( {
				active: registration.active,
				metering: registration.metering,
				require_verification: registration.require_verification,
				...value,
			} );
		},
		[ registration, onChange ]
	);
	return (
		<ActionCard
			title={ __( 'Registered Access', 'newspack-plugin' ) }
			description={ __( 'Readers must log in to view this content.', 'newspack-plugin' ) }
			toggleChecked={ registration.active }
			toggleOnChange={ ( active: boolean ) => handleChange( { active } ) }
			actionText={ gateId ? __( 'Edit Layout', 'newspack-plugin' ) : undefined }
			href={ gateId ? getEditGateLayoutUrl( gateId, 'registration' ) : undefined }
			{ ...cardProps }
		>
			{ registration.active && (
				<Card noBorder>
					<CheckboxControl
						label={ __( 'Require readers to verify their email address.', 'newspack-plugin' ) }
						checked={ registration.require_verification }
						onChange={ () => handleChange( { require_verification: ! registration.require_verification } ) }
					/>
					<hr />
					<Metering metering={ registration.metering } onChange={ ( metering: Metering ) => handleChange( { metering } ) } />
				</Card>
			) }
		</ActionCard>
	);
}
