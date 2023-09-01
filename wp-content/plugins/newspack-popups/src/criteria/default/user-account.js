import { setMatchingFunction } from '../utils';

setMatchingFunction( 'user_account', ( config, { store } ) => {
	switch ( config.value ) {
		case 'with-account':
			return store.get( 'reader' )?.email;
		case 'without-account':
			return ! store.get( 'reader' )?.email;
	}
} );
