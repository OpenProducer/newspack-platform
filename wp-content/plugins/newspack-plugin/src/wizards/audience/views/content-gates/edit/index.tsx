/**
 * Content Gates edit component.
 */

/**
 * WordPress dependencies.
 */
import { __, sprintf } from '@wordpress/i18n';
import { __experimentalVStack as VStack } from '@wordpress/components'; // eslint-disable-line @wordpress/no-unsafe-wp-apis
import { useDispatch } from '@wordpress/data';
import { useCallback, useEffect, useState } from '@wordpress/element';
import { commentAuthorAvatar, currencyDollar, postList, settings } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import { AUDIENCE_CONTENT_GATES_WIZARD_SLUG } from '../consts';
import { CardSettingsGroup, Divider, Grid, Notice, SectionHeader, TextControl } from '../../../../../../packages/components/src';
import { WIZARD_STORE_NAMESPACE } from '../../../../../../packages/components/src/wizard/store';
import { useWizardData } from '../../../../../../packages/components/src/wizard/store/utils';
import { useWizardApiFetch } from '../../../../hooks/use-wizard-api-fetch';
import ContentRules from './content-rules';
import Registration from './registration';
import CustomAccess from './custom-access';
import { getGateStatus, getGateStatusBadgeLevel } from '../utils';
import './style.scss';

type ContentGateEditProps = {
	history: { push: ( path: string ) => void };
	match: { params: { id: string; type: string } };
	updateGatesData: ( gates: Gate[] ) => void;
};

const DEFAULT_GATE: Gate = {
	id: 0,
	title: '',
	priority: 0,
	status: 'draft',
	content_rules: [ { slug: 'post_types', value: [ 'post' ] } ],
	registration: { active: false, metering: { enabled: false, count: 1, period: 'month' }, require_verification: false, gate_layout_id: 0 },
	custom_access: { active: false, metering: { enabled: false, count: 1, period: 'month' }, gate_layout_id: 0, access_rules: [] },
};

const getContentTypeFromRules = ( rules: GateContentRule[] ): 'all' | 'custom' | undefined => {
	if ( rules.length === 0 ) {
		return undefined;
	}
	if ( rules.length !== 1 ) {
		return 'custom';
	}
	const [ rule ] = rules;
	if ( rule.slug !== 'post_types' || ! Array.isArray( rule.value ) ) {
		return 'custom';
	}
	if ( rule.value.length !== 1 || rule.value[ 0 ] !== 'post' ) {
		return 'custom';
	}
	return 'all';
};

const Edit = ( { history, match, updateGatesData }: ContentGateEditProps ) => {
	const { id: _id, type } = match.params;
	const id = _id ? parseInt( _id ) : 0;
	const { gates = null as unknown as Gate[] } = useWizardData( AUDIENCE_CONTENT_GATES_WIZARD_SLUG ) as WizardData;
	const { wizardApiFetch, isFetching, errorMessage, resetError, setError } = useWizardApiFetch( AUDIENCE_CONTENT_GATES_WIZARD_SLUG );
	const { setHeaderData } = useDispatch( WIZARD_STORE_NAMESPACE );
	const [ gate, setGate ] = useState< Gate >( ( gates && gates.find( g => g.id === id ) ) || DEFAULT_GATE ); // eslint-disable-line @typescript-eslint/no-unused-vars
	const [ title, setTitle ] = useState< string >( gate.title );
	const [ isRenaming, setIsRenaming ] = useState< boolean >( false );
	const [ isDeleting, setIsDeleting ] = useState< boolean >( false );
	const [ contentRules, setContentRules ] = useState< GateContentRule[] >( gate.content_rules );
	const [ registration, setRegistration ] = useState< Registration >( gate.registration );
	const [ customAccess, setCustomAccess ] = useState< CustomAccess >( gate.custom_access );
	const [ contentType, setContentType ] = useState< 'all' | 'custom' | undefined >( type as 'all' | 'custom' | undefined );
	const [ status, setStatus ] = useState< GateStatus >( gate.status );

	const isNew = _id === 'new' || ! id;

	const handleCreate = useCallback( () => {
		if ( isFetching ) {
			return;
		}
		resetError();
		const _gate = {
			...gate,
			title,
			status: 'draft',
			content_rules: contentRules,
			registration,
			custom_access: customAccess,
		};
		wizardApiFetch< Gate >(
			{
				path: `/newspack/v1/wizard/${ AUDIENCE_CONTENT_GATES_WIZARD_SLUG }`,
				method: 'POST',
				data: { gate: _gate },
			},
			{
				onSuccess( data ) {
					updateGatesData( [ ...gates, { ...data } ] );
					history.push( `/edit/${ data.id }` );
				},
			}
		);
	}, [ gate, contentRules, registration, customAccess, status, title ] );

	const handleSave = useCallback( () => {
		if ( isFetching ) {
			return;
		}
		resetError();
		const _gate = {
			...gate,
			title,
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
				onSuccess( data: Gate ) {
					updateGatesData( gates.map( g => ( g.id === data.id ? data : g ) ) );
					setIsRenaming( false );
				},
			}
		);
	}, [ gate, contentRules, registration, customAccess, status, title ] );

	const handleStatusChange = ( _status: GateStatus ) => {
		if ( isFetching ) {
			return;
		}
		setStatus( _status );
	};

	const handleDelete = useCallback(
		( gateId: number ) => {
			// eslint-disable-next-line no-alert
			if ( ! confirm( __( 'Are you sure you want to permanently delete this content gate?', 'newspack-plugin' ) ) ) {
				return;
			}
			resetError();
			setIsDeleting( true );
			wizardApiFetch(
				{
					path: `/newspack/v1/wizard/${ AUDIENCE_CONTENT_GATES_WIZARD_SLUG }/${ gateId }`,
					method: 'DELETE',
				},
				{
					onSuccess() {
						const newGates = gates.filter( g => g.id !== gateId );
						updateGatesData( newGates );
						history.push( `/content-gates` );
						setIsDeleting( false );
					},
					onFinally() {
						setIsDeleting( false );
					},
				}
			);
		},
		[ gate, contentRules, registration, customAccess, status, title ]
	);

	// Load gate data.
	useEffect( () => {
		setHeaderData( {
			backNav: '#/content-gates',
			sectionName: isNew ? __( 'Add new', 'newspack-plugin' ) : __( 'Edit', 'newspack-plugin' ),
		} );
		if ( isNew ) {
			return;
		}
		const matchedGate = gates ? gates.find( g => g.id === id ) : null;
		if ( matchedGate === null || isDeleting || isFetching ) {
			return;
		}
		if ( matchedGate === undefined ) {
			// translators: %d is the content gate ID.
			setError( sprintf( __( 'Content gate %d not found. Create a new gate?', 'newspack-plugin' ), id ) );
			setGate( DEFAULT_GATE );
			setTitle( '' );
			setContentRules( DEFAULT_GATE.content_rules );
			setRegistration( DEFAULT_GATE.registration );
			setCustomAccess( DEFAULT_GATE.custom_access );
			setStatus( 'draft' );
			setContentType( 'all' );
			history.push( `/edit/new/all` );
			return;
		}
		setGate( matchedGate );
		setTitle( matchedGate.title );
		setContentRules( matchedGate.content_rules );
		setRegistration( matchedGate.registration );
		setCustomAccess( matchedGate.custom_access );
		setStatus( matchedGate.status );
		setContentType( getContentTypeFromRules( matchedGate.content_rules ) );
		resetError();
	}, [ gates, id, isDeleting, isFetching, isNew ] );

	// Set header actions.
	useEffect( () => {
		const actions = [
			{
				type: 'primary',
				label: __( 'Save', 'newspack-plugin' ),
				action: isNew ? handleCreate : handleSave,
				disabled:
					isFetching ||
					! title ||
					! contentRules.length ||
					( ! registration.active && ! customAccess.active ) ||
					( ! registration.active &&
						! customAccess.access_rules.some( ruleGroup =>
							ruleGroup.some(
								rule =>
									( Array.isArray( rule.value ) && rule.value?.length > 0 ) ||
									( ! Array.isArray( rule.value ) && rule.hasOwnProperty( 'value' ) )
							)
						) ),
			},
		];
		if ( ! isNew ) {
			actions.push( {
				type: 'more',
				label: __( 'Rename', 'newspack-plugin' ),
				action: () => setIsRenaming( true ),
				disabled: isFetching || isRenaming,
			} );
			if ( gate.status !== 'publish' ) {
				actions.push( {
					type: 'more',
					label: __( 'Activate', 'newspack-plugin' ),
					action: () => handleStatusChange( 'publish' ),
					disabled: isFetching,
				} );
			} else {
				actions.push( {
					type: 'more',
					label: __( 'Deactivate', 'newspack-plugin' ),
					action: () => handleStatusChange( 'draft' ),
					disabled: isFetching,
				} );
			}
			actions.push( {
				type: 'more',
				label: __( 'Delete', 'newspack-plugin' ),
				action: () => handleDelete( gate.id ),
				disabled: isFetching,
				destructive: true,
			} );
		}
		setHeaderData( {
			actions,
			badges: isNew ? [] : [ { label: getGateStatus( gate.status ), level: getGateStatusBadgeLevel( gate.status ) } ],
			sectionTitle: isNew ? __( 'Add new content gate', 'newspack-plugin' ) : title || __( 'Untitled content gate', 'newspack-plugin' ),
		} );
	}, [
		contentRules.length,
		customAccess.active,
		gate.id,
		gate.status,
		handleCreate,
		handleSave,
		isFetching,
		isNew,
		isRenaming,
		registration.active,
		title,
	] );

	// Update content rules.
	useEffect( () => {
		setContentRules( contentType === 'all' ? DEFAULT_GATE.content_rules : contentRules );
	}, [ contentType ] );

	// Update gate status.
	useEffect( () => {
		if ( ! isNew && status !== gate.status ) {
			handleSave();
		}
	}, [ isNew, gate.status, status, handleSave ] );

	return (
		<div className="newspack-content-gate__edit">
			{ errorMessage && <Notice isError noticeText={ errorMessage } /> }
			{ ( isNew || isRenaming ) && (
				<>
					<Grid columns={ 2 } gutter={ 32 }>
						<SectionHeader
							heading={ 2 }
							title={ __( 'What should we call this gate?', 'newspack-plugin' ) }
							description={ __( 'Choose a name to help you find this gate later. It won’t be shown to readers.', 'newspack-plugin' ) }
						/>
						<TextControl
							label={ __( 'Content gate name', 'newspack-plugin' ) }
							placeholder={ __( 'e.g. Premium Articles', 'newspack-plugin' ) }
							value={ title }
							onChange={ setTitle }
							hideLabelFromVision
							__next40pxDefaultSize
						/>
					</Grid>
					<Divider alignment="full-width" variant="tertiary" />
				</>
			) }
			<Grid columns={ 2 } gutter={ 32 }>
				<SectionHeader
					heading={ 2 }
					title={ __( 'What would you like to restrict?', 'newspack-plugin' ) }
					description={ __( 'Choose whether to restrict all posts or select specific content.', 'newspack-plugin' ) }
				/>
				<VStack spacing={ 4 }>
					<CardSettingsGroup
						actionType="chevron"
						title={ __( 'Restrict all posts', 'newspack-plugin' ) }
						description={ __( 'All posts on your site will require access.', 'newspack-plugin' ) }
						icon={ postList }
						isActive={ contentType === 'all' }
						onEnable={ () => setContentType( 'all' ) }
					/>
					<CardSettingsGroup
						actionType="chevron"
						title={ __( 'Choose specific content', 'newspack-plugin' ) }
						description={ __( 'Select which content to restrict using custom rules.', 'newspack-plugin' ) }
						icon={ settings }
						isActive={ contentType === 'custom' }
						onEnable={ () => setContentType( 'custom' ) }
					>
						<ContentRules rules={ contentRules } onChange={ setContentRules } />
					</CardSettingsGroup>
				</VStack>
			</Grid>
			<Divider alignment="full-width" variant="tertiary" />
			<Grid columns={ 2 } gutter={ 32 } noMargin>
				<SectionHeader
					heading={ 2 }
					title={ __( 'What’s required to access this content?', 'newspack-plugin' ) }
					description={ __(
						'Choose how readers can unlock this content. Enable registered access, paid access, or both. Each option can include metering to give readers limited free access before the restriction applies.',
						'newspack-plugin'
					) }
				/>
				<VStack spacing={ 4 }>
					<CardSettingsGroup
						actionType="toggle"
						title={ __( 'Registered access', 'newspack-plugin' ) }
						description={ __( 'Readers must log in to view this content.', 'newspack-plugin' ) }
						icon={ commentAuthorAvatar }
						isActive={ registration?.active }
						onEnable={ () => setRegistration( { ...registration, active: ! registration.active } ) }
					>
						<Registration gateId={ gate.id } registration={ registration } onChange={ setRegistration } />
					</CardSettingsGroup>
					<CardSettingsGroup
						actionType="toggle"
						title={ __( 'Paid access', 'newspack-plugin' ) }
						description={ __( 'Set conditions like subscriptions, domain, and more.', 'newspack-plugin' ) }
						icon={ currencyDollar }
						isActive={ customAccess?.active }
						onEnable={ () => setCustomAccess( { ...customAccess, active: ! customAccess.active } ) }
					>
						<CustomAccess gateId={ gate.id } customAccess={ customAccess } onChange={ setCustomAccess } />
					</CardSettingsGroup>
				</VStack>
			</Grid>
		</div>
	);
};
export default Edit;
