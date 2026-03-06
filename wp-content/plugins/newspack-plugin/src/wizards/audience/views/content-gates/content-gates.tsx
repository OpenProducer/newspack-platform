/**
 * Content Gate component.
 */

/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';
import { useEffect, useRef, useState } from '@wordpress/element';
import { ENTER } from '@wordpress/keycodes';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Button, Card, Modal, Notice, SectionHeader, TextControl } from '../../../../../packages/components/src';
import { useWizardData } from '../../../../../packages/components/src/wizard/store/utils';
import { useWizardApiFetch } from '../../../hooks/use-wizard-api-fetch';
import WizardsActionCard from '../../../wizards-action-card';
import ContentGatesOnboarding from './content-gates-onboarding';
import ContentGateSettings from './content-gate-settings';
import { AUDIENCE_CONTENT_GATES_WIZARD_SLUG } from './consts';
import { getGateStatus, getGateStatusBadgeLevel } from './utils';
import './style.scss';

const ContentGates = ( { updateGatesData }: { updateGatesData: ( gates: Gate[] ) => void } ) => {
	const wizardData = useWizardData( AUDIENCE_CONTENT_GATES_WIZARD_SLUG ) as WizardData;
	const { wizardApiFetch, isFetching, errorMessage, resetError } = useWizardApiFetch( AUDIENCE_CONTENT_GATES_WIZARD_SLUG );
	const [ showModal, setShowModal ] = useState( false );
	const [ newGateName, setNewGateName ] = useState( '' );
	const [ isInFlight, setIsInFlight ] = useState( false );
	const [ error, setError ] = useState< string | null >( null );
	const ref = useRef( null );

	const gates = ( wizardData?.gates || [] ) as Gate[];

	const resetErrors = () => {
		setError( null );
		resetError();
	};

	useEffect( () => {
		resetErrors();
	}, [] );

	useEffect( () => {
		if ( isFetching ) {
			setIsInFlight( true );
		} else {
			setIsInFlight( false );
		}
	}, [ isFetching ] );

	useEffect( () => {
		if ( errorMessage ) {
			setError( errorMessage );
		}
	}, [ errorMessage ] );

	const handleCreateGate = () => {
		if ( isInFlight ) {
			return;
		}
		resetErrors();
		setIsInFlight( true );
		wizardApiFetch< Gate >(
			{
				path: `/newspack/v1/wizard/${ AUDIENCE_CONTENT_GATES_WIZARD_SLUG }`,
				method: 'POST',
				data: {
					gate: {
						title: newGateName,
						status: 'draft',
					},
				},
			},
			{
				onSuccess( data ) {
					const newGates = [
						...gates.map( g => {
							g.isExpanded = false;
							return g;
						} ),
						{ ...data, isExpanded: true },
					];
					updateGatesData( newGates );
					setShowModal( false );
					setNewGateName( '' );
				},
				onFinally() {
					setIsInFlight( false );
				},
			}
		);
	};

	const handleDeleteGate = ( id: number ) => {
		const currentStatus = gates.find( g => g.id === id )?.status;
		if ( currentStatus === 'trash' ) {
			// eslint-disable-next-line no-alert
			if ( ! confirm( __( 'Are you sure you want to permanently delete this content gate?', 'newspack-plugin' ) ) ) {
				return;
			}
		}
		resetErrors();
		wizardApiFetch(
			{
				path: `/newspack/v1/wizard/${ AUDIENCE_CONTENT_GATES_WIZARD_SLUG }/${ id }`,
				method: 'DELETE',
			},
			{
				onSuccess() {
					if ( currentStatus === 'trash' ) {
						const newGates = gates.filter( g => g.id !== id );
						updateGatesData( newGates );
					} else {
						const newGates = gates.map( g => {
							if ( g.id === id ) {
								g.status = 'trash';
							}
							return g;
						} );
						updateGatesData( newGates );
					}
				},
			}
		);
	};

	const handleUpdateGatePriorities = ( updates: Gate[] ) => {
		if ( isInFlight ) {
			return;
		}
		const oldGates = [ ...gates ];
		updateGatesData( updates );
		setIsInFlight( true );
		resetErrors();
		apiFetch< Gate >( {
			path: `/newspack/v1/wizard/${ AUDIENCE_CONTENT_GATES_WIZARD_SLUG }/priority`,
			method: 'POST',
			data: {
				gates: updates.map( g => ( { id: g.id, priority: g.priority } ) ),
			},
		} )
			.catch( ( fetchError: WpFetchError ) => {
				setError( fetchError.message );
				updateGatesData( oldGates );
			} )
			.finally( () => setIsInFlight( false ) );
	};

	const handleSaveGate = ( gate: Gate ) => {
		if ( isInFlight ) {
			return;
		}
		const newGates = gates.map( g => ( g.id === gate.id ? gate : g ) );
		updateGatesData( newGates );
	};

	if ( ! gates?.length ) {
		return <ContentGatesOnboarding />;
	}

	return (
		<div className="newspack-content-gates__gates">
			{ error && <Notice isError noticeText={ error } /> }
			<Card noBorder headerActions>
				<SectionHeader heading={ 1 } title={ __( 'Content Gates', 'newspack-plugin' ) } noMargin />
				<Button variant="secondary" onClick={ () => setShowModal( true ) }>
					{ __( 'Add Content Gate', 'newspack-plugin' ) }
				</Button>
				{ showModal && (
					<Modal isNarrow title={ __( 'Add Content Gate', 'newspack-plugin' ) } onRequestClose={ () => setShowModal( false ) }>
						<TextControl
							disabled={ isInFlight }
							label={ __( 'Name', 'newspack-plugin' ) }
							placeholder={ __( 'Enter a name for the content gate', 'newspack-plugin' ) }
							onChange={ ( value: string ) => setNewGateName( value ) }
							onKeyUp={ ( event: KeyboardEvent ) => {
								if ( ENTER === event.keyCode && '' !== newGateName ) {
									event.preventDefault();
									handleCreateGate();
								}
							} }
						/>
						<Card buttonsCard noBorder className="justify-end">
							<Button variant="primary" onClick={ handleCreateGate } disabled={ isInFlight }>
								{ __( 'Add Content Gate', 'newspack-plugin' ) }
							</Button>
							<Button disabled={ isInFlight } isDestructive variant="secondary" onClick={ () => setShowModal( false ) }>
								{ __( 'Cancel', 'newspack-plugin' ) }
							</Button>
						</Card>
					</Modal>
				) }
			</Card>
			{ gates.length === 0 && (
				<Card noBorder>
					<p>{ __( 'No content gates configured. Add a content gate to configure access rules.', 'newspack-plugin' ) }</p>
				</Card>
			) }
			<div ref={ ref }>
				{ gates.map( ( gate, index ) => {
					const reorderGates = ( targetIndex: number ) => {
						const sortedGates = [ ...gates ];

						sortedGates.splice( index, 1 );
						sortedGates.splice( targetIndex, 0, gate );

						// Reindex priorities to avoid gaps and dupes.
						sortedGates.forEach( ( g, i ) => ( g.priority = i ) );

						// Only trigger the API request if the order has changed.
						if ( JSON.stringify( sortedGates ) !== JSON.stringify( gates ) ) {
							handleUpdateGatePriorities( sortedGates );
						}
					};
					return (
						<WizardsActionCard
							className="newspack-content-gates__gate"
							draggable={ gates.length > 1 }
							expandable
							isExpanded={ gate.isExpanded || gates.length === 1 }
							id={ gate.id }
							key={ gate.id }
							title={ gate.title }
							titleLink={ `#/edit/${ gate.id }` }
							isMedium={ gates.length > 1 }
							toggleChecked={ true }
							dragIndex={ index }
							dragWrapperRef={ ref }
							onDragCallback={ reorderGates }
							disabled={ isInFlight }
							badge={ getGateStatus( gate.status ) }
							badgeLevel={ getGateStatusBadgeLevel( gate.status ) }
						>
							<ContentGateSettings gate={ gate } onDelete={ handleDeleteGate } onSave={ handleSaveGate } />
						</WizardsActionCard>
					);
				} ) }
			</div>
		</div>
	);
};
export default ContentGates;
