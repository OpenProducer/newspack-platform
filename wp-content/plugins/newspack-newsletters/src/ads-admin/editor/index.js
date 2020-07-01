/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment, useEffect } from '@wordpress/element';
import { PluginDocumentSettingPanel, PluginPrePublishPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { DatePicker, Notice } from '@wordpress/components';
import { format, isInTheFuture } from '@wordpress/date';

const AdEdit = ( { expiryDate, editPost, lockPostSaving, unlockPostSaving } ) => {
	// Lock post saving if expiry date is not set
	useEffect(() => {
		if ( expiryDate ) {
			unlockPostSaving( 'no-expiry-lock' );
		} else {
			lockPostSaving( 'no-expiry-lock' );
		}
	}, [ expiryDate ]);

	const noticeProps = {
		children: __( 'Set an expiry date to publish the ad.', 'newspack-newsletters' ),
		status: 'error',
	};
	if ( expiryDate ) {
		const formattedExpiryDate = format( 'M j Y', expiryDate );
		const isExpiryInTheFuture = isInTheFuture( expiryDate );
		noticeProps.children = isExpiryInTheFuture
			? `${ __( 'This ad will expire on ', 'newspack-newsletters' ) } ${ formattedExpiryDate }.`
			: __(
					'The expiration date is set in the past. This ad will not be displayed.',
					'newspack-newsletters'
			  );
		noticeProps.status = isExpiryInTheFuture ? 'info' : 'warning';
	}

	return (
		<Fragment>
			<PluginDocumentSettingPanel
				name="newsletters-ads-settings-panel"
				title={ __( 'Expiry date', 'newspack-newsletters' ) }
			>
				<DatePicker
					currentDate={ expiryDate }
					onChange={ expiry_date => editPost( { meta: { expiry_date } } ) }
				/>
			</PluginDocumentSettingPanel>
			<PluginPrePublishPanel>
				<Notice isDismissible={ false } { ...noticeProps } />
			</PluginPrePublishPanel>
		</Fragment>
	);
};

const AdEditWithSelect = compose( [
	withSelect( select => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		const meta = getEditedPostAttribute( 'meta' );
		return { expiryDate: meta.expiry_date };
	} ),
	withDispatch( dispatch => {
		const { editPost, lockPostSaving, unlockPostSaving } = dispatch( 'core/editor' );
		return { editPost, lockPostSaving, unlockPostSaving };
	} ),
] )( AdEdit );

registerPlugin( 'newspack-newsletters-sidebar', {
	render: AdEditWithSelect,
	icon: null,
} );
