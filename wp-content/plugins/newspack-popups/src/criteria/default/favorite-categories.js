import { setMatchingFunction } from '../utils';

setMatchingFunction( 'favorite_categories', ( config, ras ) => {
	let match = false;
	const views = ras.getUniqueActivitiesBy( 'article_view', 'post_id' );

	if ( 1 >= views.length ) {
		return match;
	}

	const categories = views.reduce( ( c, v ) => {
		if ( v.data?.categories?.length ) {
			c.push( ...v.data.categories );
		}
		return c;
	}, [] );
	const counts = categories.reduce( ( number, category ) => {
		number[ category ] = ( number[ category ] || 0 ) + 1;
		return number;
	}, {} );
	const countsArray = Object.entries( counts );
	countsArray.sort( ( a, b ) => b[ 1 ] - a[ 1 ] );

	if ( ! countsArray || ! countsArray.length ) {
		return match;
	}

	// Must have viewed at least 2 categories or 2 posts within the same category in order to rank.
	if ( ! countsArray[ 1 ] || countsArray[ 0 ][ 1 ] > countsArray[ 1 ][ 1 ] ) {
		if ( -1 < config.value.indexOf( parseInt( countsArray[ 0 ][ 0 ] ) ) ) {
			match = true;
		}
	}

	return match;
} );
