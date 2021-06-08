/**
 * External dependencies.
 */
import { uniqBy } from 'lodash';

/**
 * WordPress dependencies.
 */
import { useReducer } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import { DEFAULT_OFFSET } from './consts';

const filtersInitialState = {
	event_label_id: '',
	event_action: '',
	offset: DEFAULT_OFFSET.value,
};

const filtersReducer = ( state, action ) => {
	switch ( action.type ) {
		case 'SET_OFFSET_FILTER':
			return { ...state, offset: action.payload };
		case 'SET_EVENT_LABEL_FILTER':
			return { ...state, event_label_id: action.payload };
		case 'SET_EVENT_ACTION_FILTER':
			return { ...state, event_action: action.payload };
		default:
			return state;
	}
};

export const useFiltersState = () => useReducer( filtersReducer, filtersInitialState );

const analyticsInitialState = {
	labels: [],
	actions: [],
};

const analyticsReducer = ( state, action ) => {
	switch ( action.type ) {
		case 'UPDATE_ALL':
			const { labels, actions, ...rest } = action.payload;
			const newState = {
				...state,
				...rest,
				// Persist all fetched labels and actions, so the select options do not disappear
				labels: uniqBy( [ ...state.labels, ...labels ], 'value' ),
				actions: uniqBy( [ ...state.actions, ...actions ], 'value' ),
			};
			return newState;
		default:
			return state;
	}
};

export const useAnalyticsState = () => useReducer( analyticsReducer, analyticsInitialState );
