const LS_ITEM_NAME = 'newspack-support-auth-return';

export const saveReturnPath = () =>
	window.localStorage.setItem( LS_ITEM_NAME, window.location.href );
export const getReturnPath = () => window.localStorage.getItem( LS_ITEM_NAME );
