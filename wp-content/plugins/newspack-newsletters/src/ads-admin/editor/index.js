/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { PluginDocumentSettingPanel, PluginPrePublishPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { DatePicker, Notice, Button } from '@wordpress/components';
import { format, isInTheFuture } from '@wordpress/date';

const AdEdit = ( { expiryDate, editPost } ) => {
	let noticeProps;
	if ( expiryDate ) {
		const formattedExpiryDate = format( 'M j Y', expiryDate );
		const isExpiryInTheFuture = isInTheFuture( expiryDate );
		noticeProps = {
			children: isExpiryInTheFuture
				? `${ __( 'This ad will expire on ', 'newspack-newsletters' ) } ${ formattedExpiryDate }.`
				: __(
						'The expiration date is set in the past. This ad will not be displayed.',
						'newspack-newsletters'
				  ),
			status: isExpiryInTheFuture ? 'info' : 'warning',
		};
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
				{ expiryDate ? (
					<div style={ { textAlign: 'center' } }>
						<Button
							isSecondary
							isLink
							isDestructive
							onClick={ () => editPost( { meta: { expiry_date: null } } ) }
						>
							{ __( 'Remove expiry date', 'newspack-newsletters' ) }
						</Button>
					</div>
				) : null }
			</PluginDocumentSettingPanel>
			{ noticeProps ? (
				<PluginPrePublishPanel>
					<Notice isDismissible={ false } { ...noticeProps } />
				</PluginPrePublishPanel>
			) : null }
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
		const { editPost } = dispatch( 'core/editor' );
		return { editPost };
	} ),
] )( AdEdit );

registerPlugin( 'newspack-newsletters-sidebar', {
	render: AdEditWithSelect,
	icon: null,
} );
