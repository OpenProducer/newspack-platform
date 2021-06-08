/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	BaseControl,
	Button,
	DatePicker,
	DateTimePicker,
	PanelBody,
	PanelRow,
	ToggleControl,
} from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import { Fragment } from '@wordpress/element';

export const EventDatesEditor = ( { attributes, clientId, setAttributes } ) => {
	const { endDate, showEnd, showTime, startDate } = attributes;
	const { createNotice } = useDispatch( 'core/notices' );
	const DatePickerComponent = showTime ? DateTimePicker : DatePicker;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Event Dates Settings' ) }>
					<PanelRow>
						<ToggleControl
							className="newspack-listings__event-time-toggle"
							label={ __( 'Show Times', 'newspack-listings' ) }
							checked={ showTime }
							onChange={ () => setAttributes( { showTime: ! showTime } ) }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							className="newspack-listings__event-time-toggle"
							label={ sprintf(
								__( 'Show End %s', 'newspack-listings' ),
								showTime ? __( 'Time', 'newspack-listings' ) : __( 'Date', 'newspack-listings' )
							) }
							checked={ showEnd }
							onChange={ () => {
								setAttributes( { showEnd: ! showEnd } );
								setAttributes( { endDate: '' } );
							} }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<div className="newspack-listings__event-dates">
				<div className="newspack-listings__event-dates-controls">
					<BaseControl
						id={ `event-start-date-${ clientId }` }
						label={ sprintf(
							__( 'Event %s %s', 'newspack-listings' ),
							showEnd ? __( 'Start', 'newspack-listings' ) : '',
							showTime ? __( 'Time', 'newspack-listings' ) : __( 'Date', 'newspack-listings' )
						) }
					>
						<DatePickerComponent
							currentDate={ startDate ? new Date( startDate ) : null }
							is12Hour={ true }
							onChange={ value => {
								if (
									! value || // If clearing the value.
									! endDate || // If there isn't an end date to compare with.
									( endDate && 0 <= new Date( endDate ) - new Date( value ) ) // If there is an end date, and it's after the selected start date.
								) {
									return setAttributes( { startDate: value } );
								}

								createNotice(
									'warning',
									__( 'Event end must be after event start.', 'newspack-listings' ),
									{
										id: 'newspack-listings__date-error',
										isDismissible: true,
										type: 'default',
									}
								);
							} }
						/>
						{ ! showTime && startDate && (
							<Button isLink onClick={ () => setAttributes( { startDate: '' } ) }>
								{ __( 'Reset', 'newspack-listings' ) }
							</Button>
						) }
					</BaseControl>
					{ showEnd && (
						<BaseControl
							id={ `event-end-date-${ clientId }` }
							label={ sprintf(
								__( 'Event End %s', 'newspack-listings' ),
								showTime ? __( 'Time', 'newspack-listings' ) : __( 'Date', 'newspack-listings' )
							) }
						>
							<DatePickerComponent
								currentDate={ endDate ? new Date( endDate ) : null }
								is12Hour={ true }
								onChange={ value => {
									if (
										! value ||
										! startDate ||
										( startDate && 0 <= new Date( value ) - new Date( startDate ) )
									) {
										return setAttributes( { endDate: value } );
									}

									createNotice(
										'warning',
										__( 'Event end must be after event start.', 'newspack-listings' ),
										{
											id: 'newspack-listings__date-error',
											isDismissible: true,
											type: 'default',
										}
									);
								} }
							/>
							{ ! showTime && endDate && (
								<Button isLink onClick={ () => setAttributes( { endDate: '' } ) }>
									{ __( 'Reset', 'newspack-listings' ) }
								</Button>
							) }
						</BaseControl>
					) }
				</div>
			</div>
		</Fragment>
	);
};
