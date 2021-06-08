/* global newspack_analytics_wizard_data */

/**
 * External dependencies
 */
import { find } from 'lodash';

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { ExternalLink } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Icon, check } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import {
	ActionCard,
	Button,
	Grid,
	Notice,
	TextControl,
	SelectControl,
	SectionHeader,
	CheckboxControl,
	withWizardScreen,
	Modal,
} from '../../../../components/src';
import { NEWSPACK_SUPPORT_URL } from '../../../../components/src/consts';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Not implemented:
 * - visibility, ini-load: require the element to be AMP element,
 * - scroll: requires some more UI for scroll parameters, can be implemented later.
 */
const TRIGGER_OPTIONS = [
	{ value: 'click', label: __( 'Click', 'newspack' ) },
	{ value: 'submit', label: __( 'Submit', 'newspack' ) },
];

const NEW_EVENT_TEMPLATE = {
	event_name: '',
	event_category: '',
	event_label: '',
	on: TRIGGER_OPTIONS[ 0 ].value,
	element: '',
	amp_element: '',
	non_interaction: true,
	is_active: true,
};

const validateEvent = event =>
	Boolean( event.event_name && event.event_category && event.on && event.element );

const NTG_EVENTS_ENDPOINT = '/newspack/v1/wizard/analytics/ntg';

/**
 * Analytics Custom Events screen.
 */
class CustomEvents extends Component {
	state = {
		error: newspack_analytics_wizard_data.analyticsConnectionError,
		customEvents: newspack_analytics_wizard_data.customEvents,
		editedEvent: NEW_EVENT_TEMPLATE,
		editedEventId: null,
		ntgEventsStatus: {},
	};

	componentDidMount() {
		this.props
			.wizardApiFetch( { path: NTG_EVENTS_ENDPOINT } )
			.then( ntgEventsStatus => this.setState( { ntgEventsStatus } ) );
	}

	handleAPIError = ( { message: error } ) => this.setState( { error } );

	updateCustomEvents = updatedEvents => {
		const { wizardApiFetch } = this.props;
		wizardApiFetch( {
			path: '/newspack/v1/wizard/analytics/custom-events',
			method: 'POST',
			data: { events: updatedEvents },
		} )
			.then( ( { events } ) =>
				this.setState( {
					customEvents: events,
					editedEvent: NEW_EVENT_TEMPLATE,
					editedEventId: null,
				} )
			)
			.catch( this.handleAPIError );
	};

	handleCustomEventEdit = () => {
		const { customEvents, editedEvent, editedEventId } = this.state;
		if ( editedEventId === 'new' ) {
			this.updateCustomEvents( [ ...customEvents, editedEvent ] );
		} else {
			this.updateCustomEvents(
				customEvents.map( event => {
					if ( event.id === editedEventId ) {
						return editedEvent;
					}
					return event;
				} )
			);
		}
	};

	updateEditedEvent = key => value =>
		this.setState( ( { editedEvent } ) => ( { editedEvent: { ...editedEvent, [ key ]: value } } ) );

	setEditModal = editedEventId => () => {
		const editedEvent =
			editedEventId !== null && find( this.state.customEvents, [ 'id', editedEventId ] );
		this.setState( {
			editedEventId,
			...( editedEvent
				? {
						editedEvent,
				  }
				: {
						editedEvent: NEW_EVENT_TEMPLATE,
				  } ),
		} );
	};

	render() {
		const { error, customEvents, editedEvent, editedEventId } = this.state;
		const { isLoading } = this.props;

		const isCreatingEvent = editedEventId === 'new';

		return (
			<div className="newspack__analytics-configuration">
				<div className="newspack__analytics-configuration__header">
					<SectionHeader
						title={ __( 'User-defined custom events', 'newspack' ) }
						description={ __( 'Collect and analyze specific user interactions', 'newspack' ) }
						noMargin
					/>
					<Button onClick={ this.setEditModal( 'new' ) } isPrimary isSmall>
						{ __( 'Add New Custom Event', 'newspack' ) }
					</Button>
				</div>
				<Notice
					rawHTML
					isInfo
					noticeText={ `${ __(
						'This is an advanced feature, read more about it on our',
						'newspack'
					) } <a href="${ NEWSPACK_SUPPORT_URL }/analytics">${ __(
						'support page',
						'newspack'
					) }</a>.` }
				/>
				{ error ? (
					<Notice noticeText={ error } isError rawHTML />
				) : (
					<Fragment>
						<table>
							<thead>
								<tr>
									{ [
										__( 'Active', 'newspack' ),
										__( 'Action', 'newspack' ),
										__( 'Category', 'newspack' ),
										__( 'Label', 'newspack' ),
										__( 'Trigger', 'newspack' ),
										__( 'Edit', 'newspack' ),
									].map( ( colName, i ) => (
										<th key={ i }>{ colName }</th>
									) ) }
								</tr>
							</thead>
							<tbody>
								{ customEvents.map( event => (
									<tr key={ event.id }>
										<td>
											<span
												className={ classnames(
													'newspack-checkbox-icon',
													event.is_active && 'newspack-checkbox-icon--checked'
												) }
											>
												{ event.is_active && <Icon icon={ check } /> }
											</span>
										</td>
										<td>{ event.event_name }</td>
										<td>{ event.event_category }</td>
										<td>{ event.event_label }</td>
										<td>
											<code>{ event.on }</code>
										</td>
										<td>
											<Button isSmall isLink onClick={ this.setEditModal( event.id ) }>
												{ __( 'Edit', 'newspack' ) }
											</Button>
										</td>
									</tr>
								) ) }
							</tbody>
						</table>

						{ editedEventId !== null && (
							<Modal
								title={
									isCreatingEvent
										? __( 'New custom event', 'newspack' )
										: __( 'Editing custom event', 'newspack' )
								}
								onRequestClose={ this.setEditModal( null ) }
								isWide
							>
								<div>
									<Grid gutter={ 32 }>
										<TextControl
											disabled={ isLoading }
											value={ editedEvent.event_name }
											onChange={ this.updateEditedEvent( 'event_name' ) }
											label={ __( 'Action', 'newspack' ) }
											required
										/>
										<TextControl
											disabled={ isLoading }
											value={ editedEvent.event_category }
											onChange={ this.updateEditedEvent( 'event_category' ) }
											label={ __( 'Category', 'newspack' ) }
											required
										/>
										<TextControl
											disabled={ isLoading }
											value={ editedEvent.event_label }
											onChange={ this.updateEditedEvent( 'event_label' ) }
											label={ __( 'Label', 'newspack' ) }
										/>
										<SelectControl
											disabled={ isLoading }
											value={ editedEvent.on }
											onChange={ this.updateEditedEvent( 'on' ) }
											label={ __( 'Trigger', 'newspack' ) }
											options={ TRIGGER_OPTIONS }
											required
										/>
										<TextControl
											disabled={ isLoading }
											value={ editedEvent.element }
											onChange={ this.updateEditedEvent( 'element' ) }
											label={ __( 'Selector', 'newspack' ) }
											className="code"
											required
										/>
										<TextControl
											disabled={ isLoading }
											value={ editedEvent.amp_element }
											onChange={ this.updateEditedEvent( 'amp_element' ) }
											label={ __( 'AMP Selector', 'newspack' ) }
											className="code"
										/>
									</Grid>
									<CheckboxControl
										disabled={ isLoading }
										checked={ editedEvent.non_interaction }
										onChange={ this.updateEditedEvent( 'non_interaction' ) }
										label={ __( 'Non-interaction event', 'newspack' ) }
									/>
									<CheckboxControl
										disabled={ isLoading }
										checked={ editedEvent.is_active }
										onChange={ this.updateEditedEvent( 'is_active' ) }
										label={ __( 'Active', 'newspack' ) }
									/>
									<div className="newspack-buttons-card">
										<Button
											onClick={ this.handleCustomEventEdit }
											disabled={ ! validateEvent( editedEvent ) || isLoading }
											isPrimary
										>
											{ isCreatingEvent ? __( 'Add', 'newspack' ) : __( 'Update', 'newspack' ) }
										</Button>
										{ ! isCreatingEvent && (
											<Button
												isSecondary
												disabled={ isLoading }
												onClick={ () =>
													this.updateCustomEvents(
														this.state.customEvents.filter( ( { id } ) => editedEvent.id !== id )
													)
												}
											>
												{ __( 'Delete', 'newspack' ) }
											</Button>
										) }
									</div>
								</div>
							</Modal>
						) }
					</Fragment>
				) }
				<ActionCard
					isMedium
					title={ __( 'News Tagging Guide custom events', 'newspack' ) }
					description={ [
						__(
							'Free tool that helps you make the most of Google Analytics by capturing better data.',
							'newspack'
						) + '\u00A0',
						<ExternalLink
							href="https://newsinitiative.withgoogle.com/training/datatools/ntg"
							key="info-link"
						>
							{ __( 'More info', 'newspack' ) }
						</ExternalLink>,
					] }
					toggle
					disabled={ this.state.ntgEventsStatus.enabled === undefined }
					toggleChecked={ this.state.ntgEventsStatus.enabled }
					toggleOnChange={ () =>
						this.props
							.wizardApiFetch( {
								path: NTG_EVENTS_ENDPOINT,
								method: this.state.ntgEventsStatus.enabled ? 'DELETE' : 'POST',
								quiet: true,
							} )
							.then( ntgEventsStatus => this.setState( { ntgEventsStatus } ) )
					}
				/>
			</div>
		);
	}
}

export default withWizardScreen( CustomEvents );
