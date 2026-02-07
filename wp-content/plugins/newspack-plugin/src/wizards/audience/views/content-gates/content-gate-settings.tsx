/**
 * WordPress dependencies.
 */
import { useEffect, useMemo, useState, useCallback } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { AUDIENCE_CONTENT_GATES_WIZARD_SLUG } from './consts';
import { useWizardApiFetch } from '../../../hooks/use-wizard-api-fetch';
import ContentRules from './content-rules';
import Registration from './registration';
import CustomAccess from './custom-access';

type ContentGateSettingsProps = {
	gate: Gate;
	onDelete: ( id: number ) => void;
	onSave: ( gate: Gate ) => void;
};

export default function ContentGateSettings( { gate, onDelete, onSave }: ContentGateSettingsProps ) {
	const { wizardApiFetch } = useWizardApiFetch( AUDIENCE_CONTENT_GATES_WIZARD_SLUG );
	const [ contentRules, setContentRules ] = useState< GateContentRule[] >( gate.content_rules );
	const [ registration, setRegistration ] = useState< Registration >( gate.registration );
	const [ customAccess, setCustomAccess ] = useState< CustomAccess >( gate.custom_access );
	const [ status, setStatus ] = useState< GateStatus >( gate.status );
	const [ isEditingStatus, setIsEditingStatus ] = useState( false );

	const isReady = useMemo( () => {
		return contentRules.length > 0 && ( registration.active || ( customAccess.active && customAccess.access_rules.length > 0 ) );
	}, [ contentRules, registration, customAccess ] );

	const handleSave = useCallback( () => {
		const _gate = {
			...gate,
			content_rules: contentRules,
			registration,
			custom_access: customAccess,
			status,
		};
		wizardApiFetch< Gate >(
			{
				path: `/newspack/v1/wizard/${ AUDIENCE_CONTENT_GATES_WIZARD_SLUG }/${ gate.id }`,
				method: 'POST',
				data: { gate: _gate },
			},
			{
				onSuccess( data ) {
					onSave( data );
				},
				onError( error ) {
					console.error( error ); // eslint-disable-line no-console
				},
				onFinally() {
					setIsEditingStatus( false );
				},
			}
		);
	}, [ contentRules, registration, customAccess, status ] );

	// Update status and trigger save.
	useEffect( () => {
		if ( ! isEditingStatus ) {
			return;
		}
		handleSave();
	}, [ isEditingStatus, status, handleSave ] );

	const handleDelete = () => onDelete( gate.id );
	const handleRestore = () => {
		setIsEditingStatus( true );
		setStatus( 'draft' );
	};

	const handlePublish = () => {
		// eslint-disable-next-line no-alert
		if ( ! confirm( __( 'Are you sure you want to publish this content gate?', 'newspack-plugin' ) ) ) {
			return;
		}
		setIsEditingStatus( true );
		setStatus( 'publish' );
	};

	return (
		<>
			<ContentRules rules={ contentRules } onChange={ setContentRules } />
			<Registration gateId={ gate.id } registration={ registration } onChange={ setRegistration } />
			<CustomAccess gateId={ gate.id } customAccess={ customAccess } onChange={ setCustomAccess } />
			<div className="newspack-buttons-card">
				{ gate.status === 'draft' && (
					<Button disabled={ ! isReady } variant="primary" onClick={ handlePublish }>
						{ __( 'Publish', 'newspack-plugin' ) }
					</Button>
				) }
				{ gate.status !== 'trash' && (
					<Button variant={ gate.status === 'publish' ? 'primary' : 'secondary' } onClick={ handleSave }>
						{ gate.status === 'publish' ? __( 'Update', 'newspack-plugin' ) : __( 'Save draft', 'newspack-plugin' ) }
					</Button>
				) }
				{ gate.status === 'publish' && (
					<Button isDestructive variant="secondary" onClick={ handleRestore }>
						{ __( 'Unpublish', 'newspack-plugin' ) }
					</Button>
				) }
				{ 'trash' === gate.status && (
					<Button variant="secondary" onClick={ handleRestore }>
						{ __( 'Restore', 'newspack-plugin' ) }
					</Button>
				) }
				{ gate.status !== 'publish' && (
					<Button variant="tertiary" isDestructive onClick={ handleDelete }>
						{ 'trash' === gate.status ? __( 'Delete permanently', 'newspack-plugin' ) : __( 'Delete', 'newspack-plugin' ) }
					</Button>
				) }
			</div>
		</>
	);
}
