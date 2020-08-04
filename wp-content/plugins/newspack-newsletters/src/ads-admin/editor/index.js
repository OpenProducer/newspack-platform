/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';
import { PluginDocumentSettingPanel, PluginPrePublishPanel } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { DatePicker, BaseControl, Notice, Button, RangeControl } from '@wordpress/components';
import { format, isInTheFuture } from '@wordpress/date';

/**
 * Internal dependencies
 */
import './style.scss';

const AdEdit = ( { expiryDate, positionInContent, editPost } ) => {
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
				title={ __( 'Ad settings', 'newspack-newsletters' ) }
			>
				<RangeControl
					label={ __( 'Approximate position (in percent)' ) }
					value={ positionInContent }
					onChange={ position_in_content => editPost( { meta: { position_in_content } } ) }
					min={ 0 }
					max={ 100 }
				/>
				{ /* eslint-disable-next-line @wordpress/no-base-control-with-label-without-id */ }
				<BaseControl
					className={ classnames( 'newspack-newsletters__date-picker', {
						'newspack-newsletters__date-picker--has-no-date': ! expiryDate,
					} ) }
					label={ __( 'Expiration Date', 'newspack-newsletters' ) }
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
				</BaseControl>
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
		return { expiryDate: meta.expiry_date, positionInContent: meta.position_in_content };
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
