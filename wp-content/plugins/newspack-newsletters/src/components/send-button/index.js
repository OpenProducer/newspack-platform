/**
 * WordPress dependencies
 */
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Button, Modal } from '@wordpress/components';
import { Fragment, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { get } from 'lodash';

/**
 * Internal dependencies
 */
import { getServiceProvider } from '../../service-providers';

const { renderPreSendInfo } = getServiceProvider();

export default compose( [
	withDispatch( dispatch => {
		const { editPost, savePost } = dispatch( 'core/editor' );
		return { editPost, savePost };
	} ),
	withSelect( ( select, { forceIsSaving, forceIsDirty } ) => {
		const {
			getCurrentPost,
			getEditedPostAttribute,
			getEditedPostVisibility,
			isEditedPostPublishable,
			isEditedPostSaveable,
			isSavingPost,
			isEditedPostBeingScheduled,
		} = select( 'core/editor' );
		const { newsletterData = {}, newsletterValidationErrors } = getEditedPostAttribute( 'meta' );
		return {
			isPublishable: forceIsDirty || isEditedPostPublishable(),
			isSaveable: isEditedPostSaveable(),
			isSaving: forceIsSaving || isSavingPost(),
			validationErrors: newsletterValidationErrors,
			status: getEditedPostAttribute( 'status' ),
			isEditedPostBeingScheduled: isEditedPostBeingScheduled(),
			hasPublishAction: get( getCurrentPost(), [ '_links', 'wp:action-publish' ], false ),
			visibility: getEditedPostVisibility(),
			newsletterData,
		};
	} ),
] )(
	( {
		editPost,
		isPublishable,
		isSaveable,
		isSaving,
		savePost,
		status,
		validationErrors,
		isEditedPostBeingScheduled,
		hasPublishAction,
		visibility,
		newsletterData,
	} ) => {
		const isButtonEnabled =
			( isPublishable || isEditedPostBeingScheduled ) &&
			isSaveable &&
			validationErrors &&
			! validationErrors.length &&
			'publish' !== status;
		let label;
		if ( 'publish' === status ) {
			label = isSaving
				? __( 'Sending', 'newspack-newsletters' )
				: __( 'Sent', 'newspack-newsletters' );
		} else if ( 'future' === status ) {
			// Scheduled to be sent
			label = __( 'Scheduled', 'newspack-newsletters' );
		} else if ( isEditedPostBeingScheduled ) {
			label = __( 'Schedule sending', 'newspack-newsletters' );
		} else {
			label = __( 'Send', 'newspack-newsletters' );
		}

		let publishStatus;
		if ( ! hasPublishAction ) {
			publishStatus = 'pending';
		} else if ( visibility === 'private' ) {
			publishStatus = 'private';
		} else if ( isEditedPostBeingScheduled ) {
			publishStatus = 'future';
		} else {
			publishStatus = 'publish';
		}

		const triggerCampaignSend = () => {
			editPost( { status: publishStatus } );
			savePost();
		};

		const [ modalVisible, setModalVisible ] = useState( false );

		return (
			<Fragment>
				<Button
					className="editor-post-publish-button"
					isBusy={ isSaving && 'publish' === status }
					isPrimary
					isLarge
					onClick={ () => setModalVisible( true ) }
					disabled={ ! isButtonEnabled }
				>
					{ label }
				</Button>
				{ modalVisible && (
					<Modal
						className="newspack-newsletters__modal"
						title={ __( 'Send your newsletter?', 'newspack-newsletters' ) }
						onRequestClose={ () => setModalVisible( false ) }
					>
						{ renderPreSendInfo( newsletterData ) }
						<Button
							isPrimary
							onClick={ () => {
								triggerCampaignSend();
								setModalVisible( false );
							} }
						>
							{ __( 'Send', 'newspack-newsletters' ) }
						</Button>
						<Button isSecondary onClick={ () => setModalVisible( false ) }>
							{ __( 'Cancel', 'newspack-newsletters' ) }
						</Button>
					</Modal>
				) }
			</Fragment>
		);
	}
);
