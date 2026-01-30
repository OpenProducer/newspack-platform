/**
 * WordPress dependencies.
 */
import { useEffect, useMemo, useState, useCallback } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import AccessRules from './access-rules';
import ContentRules from './content-rules';
import Metering from './metering';
import { AUDIENCE_CONTENT_GATES_WIZARD_SLUG } from './consts';
import { useWizardApiFetch } from '../../../hooks/use-wizard-api-fetch';

type ContentGateSettingsProps = {
	gate: Gate;
	onDelete: ( id: number ) => void;
	onSave: ( gate: Gate ) => void;
};

export default function ContentGateSettings( { gate, onDelete, onSave }: ContentGateSettingsProps ) {
	const { wizardApiFetch } = useWizardApiFetch( AUDIENCE_CONTENT_GATES_WIZARD_SLUG );
	const [ accessRules, setAccessRules ] = useState< GateAccessRule[] >( gate.access_rules );
	const [ contentRules, setContentRules ] = useState< GateContentRule[] >( gate.content_rules );
	const [ metering, setMetering ] = useState< Metering >( gate.metering );
	const [ status, setStatus ] = useState< GateStatus >( gate.status );
	const [ isEditingStatus, setIsEditingStatus ] = useState( false );

	const isReady = useMemo( () => {
		return contentRules.length > 0 && accessRules.length > 0;
	}, [ contentRules, accessRules ] );

	const handleSave = useCallback( () => {
		const _gate = {
			...gate,
			access_rules: accessRules,
			content_rules: contentRules,
			metering,
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
	}, [ accessRules, contentRules, metering, status ] );

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
			<hr />
			<AccessRules rules={ accessRules } onChange={ setAccessRules } />
			<hr />
			<Metering metering={ metering } onChange={ setMetering } />
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
