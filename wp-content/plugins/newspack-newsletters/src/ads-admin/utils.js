/**
 * WordPress dependencies
 */
import { isInTheFuture, date } from '@wordpress/date';

export const isAdActive = ad => {
	if ( ad.status === 'publish' && ad.meta.expiry_date ) {
		const adDayDate = date( 'Y-m-d', ad.meta.expiry_date );
		const todayDate = date( 'Y-m-d' );
		return adDayDate === todayDate || isInTheFuture( adDayDate );
	}
	return false;
};
