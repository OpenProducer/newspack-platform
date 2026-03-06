/**
 * WordPress dependencies.
 */
import { CardBody, CardDivider, ToggleControl } from '@wordpress/components';
import { useCallback } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, Button } from '../../../../../../packages/components/src';
import { getEditGateLayoutUrl } from '../utils';
import Metering from './metering';

interface RegistrationProps {
	gateId?: number;
	registration: Registration;
	onChange: ( registration: Partial< Registration > ) => void;
	cardProps?: Partial< React.ComponentPropsWithoutRef< typeof ActionCard > >;
}

export default function Registration( { gateId, registration, onChange }: RegistrationProps ) {
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
		<>
			{ gateId ? (
				<>
					<CardBody size="small">
						<Button variant="secondary" href={ getEditGateLayoutUrl( gateId, 'registration' ) }>
							{ __( 'Edit Layout', 'newspack-plugin' ) }
						</Button>
					</CardBody>
					<CardDivider />
				</>
			) : null }
			<CardBody size="small">
				<ToggleControl
					label={ __( 'Require verification', 'newspack-plugin' ) }
					help={ __( 'Readers must verify their account to access.', 'newspack-plugin' ) }
					checked={ registration.require_verification }
					onChange={ () => handleChange( { require_verification: ! registration.require_verification } ) }
				/>
			</CardBody>
			<CardDivider />
			<CardBody size="small">
				<Metering
					description={ __( 'Allow limited free views before requiring login.', 'newspack-plugin' ) }
					metering={ registration.metering }
					onChange={ ( metering: Metering ) => handleChange( { metering } ) }
				/>
			</CardBody>
		</>
	);
}
