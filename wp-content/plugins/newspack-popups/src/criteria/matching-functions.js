/**
 * Common matching functions that can be used by criteria.
 */
export default {
	/**
	 * Matches the exact value of the criteria against the segment config.
	 */
	default: ( criteria, config ) => criteria.value === config.value,
	/**
	 * Matches the criteria value against a list provided by the segment config,
	 * returns true if the value is on the list.
	 */
	list__in: ( criteria, config ) => {
		let list = config.value;
		if ( typeof list === 'string' ) {
			list = config.value.split( ',' ).map( item => item.trim() );
		}
		if ( ! Array.isArray( list ) ) {
			return false;
		}
		if ( ! criteria.value || ! list.includes( criteria.value ) ) {
			return false;
		}
		return true;
	},
	/**
	 * Matches the criteria value against a list provided by the segment config,
	 * returns true if the value is empty or not on the list.
	 */
	list__not_in: ( criteria, config ) => {
		let list = config.value;
		if ( typeof list === 'string' ) {
			list = config.value.split( ',' ).map( item => item.trim() );
		}
		if ( ! Array.isArray( list ) ) {
			return true;
		}
		if ( ! criteria.value || ! list.includes( criteria.value ) ) {
			return true;
		}
		return false;
	},
	/**
	 * Matches the criteria value against a range of 'min' and 'max' provided by
	 * the segment config.
	 */
	range: ( criteria, config ) => {
		const { min, max } = config.value;
		if ( ! criteria.value || ( min && criteria.value < min ) || ( max && criteria.value > max ) ) {
			return false;
		}
		return true;
	},
};
