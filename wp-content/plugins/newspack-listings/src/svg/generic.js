/**
 * External dependencies
 */
import { Path, SVG } from '@wordpress/components';

export default ( { size = 48 } ) => (
	<SVG className="newspack-listings__query-logo" height={ size } viewBox="0 0 24 24" width={ size }>
		<Path d="M0 0h24v24H0z" fill="none" />
		<Path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z" />
	</SVG>
);
