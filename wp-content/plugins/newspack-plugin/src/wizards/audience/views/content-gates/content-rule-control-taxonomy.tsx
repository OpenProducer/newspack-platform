/**
 * Content Gate component.
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { FormTokenField } from '@wordpress/components';
import type { TokenItem } from '@wordpress/components/build-types/form-token-field/types.d.ts';
import apiFetch from '@wordpress/api-fetch';
import { useEffect, useState, useCallback, useMemo } from '@wordpress/element';
import { decodeEntities } from '@wordpress/html-entities';
import { addQueryArgs } from '@wordpress/url';

const debounce = ( func: ( search?: string ) => void, wait: number ) => {
	let timeout: ReturnType< typeof setTimeout >;
	return ( search?: string ) => {
		clearTimeout( timeout );
		timeout = setTimeout( () => func( search ), wait );
	};
};

export default function ContentRuleControlTaxonomy( { slug, value, onChange }: GateContentRuleControlProps ) {
	const rule = useMemo( () => window.newspackAudienceContentGates.available_content_rules[ slug ], [ slug ] );

	const [ savedItems, setSavedItems ] = useState< { value: string; label: string }[] >( [] );
	const [ suggestions, setSuggestions ] = useState< { value: string; label: string }[] >( [] );

	const endpoint = useMemo( () => {
		let _endpoint = '';
		switch ( slug ) {
			case 'post_tag':
				_endpoint = 'tags';
				break;
			case 'category':
				_endpoint = 'categories';
				break;
			default:
				_endpoint = slug;
		}
		return _endpoint;
	}, [ slug ] );

	const fetchSuggestions = useCallback(
		( search: string = '' ) => {
			apiFetch< { id: number; name: string }[] >( {
				path: addQueryArgs( 'wp/v2/' + endpoint, {
					search,
					per_page: 10,
					_fields: 'id,name',
				} ),
			} )
				.then( terms => {
					if ( ! terms || terms.length === 0 ) {
						setSuggestions( [] );
						return;
					}
					setSuggestions(
						terms.map( term => ( {
							value: term.id.toString(),
							label: decodeEntities( term.name ) || __( '(no name)', 'newspack-plugin' ),
						} ) )
					);
				} )
				.catch( error => {
					console.warn( 'Error fetching suggestions for taxonomy: ' + endpoint, error ); // eslint-disable-line no-console
				} );
		},
		[ endpoint ]
	);

	// Fetch current items.
	useEffect( () => {
		if ( ! value || value.length === 0 ) {
			return;
		}
		apiFetch< { id: number; name: string }[] >( {
			path: addQueryArgs( 'wp/v2/' + endpoint, {
				include: value.join( ',' ),
			} ),
		} )
			.then( terms => {
				setSavedItems(
					terms.map( term => ( { value: term.id.toString(), label: decodeEntities( term.name ) || __( '(no name)', 'newspack-plugin' ) } ) )
				);
			} )
			.catch( error => {
				console.warn( 'Error fetching saved items for taxonomy: ' + endpoint, error ); // eslint-disable-line no-console
			} );
	}, [ value, endpoint ] );

	// Set initial suggestions.
	useEffect( () => {
		fetchSuggestions();
	}, [ fetchSuggestions ] );

	const debouncedFetchSuggestions = useMemo( () => debounce( fetchSuggestions, 100 ), [ fetchSuggestions ] );

	const handleInputChange = ( search: string ) => {
		debouncedFetchSuggestions( search );
	};

	const tokens = useMemo( () => {
		const items = [ ...savedItems, ...suggestions ];
		const result = items.filter( i => value.includes( i.value ) ).map( i => `${ i.value }: ${ i.label }` );
		return [ ...new Set( result ) ];
	}, [ value, savedItems, suggestions ] );

	const handleChange = useCallback(
		( newTokens: ( string | TokenItem )[] ) => {
			const items = [ ...savedItems, ...suggestions ];

			// Find items.
			const foundItems = newTokens.map( t => {
				if ( typeof t === 'string' ) {
					const [ val ] = t.split( ':' );
					return items.find( i => i.value === val );
				}
				return items.find( i => i.value === t.value );
			} );
			onChange( foundItems.filter( i => i !== undefined ).map( i => i.value ) );
		},
		[ savedItems, suggestions, onChange ]
	);

	if ( ! rule || ! Array.isArray( value ) ) {
		return null;
	}

	return (
		<FormTokenField
			label={ rule.name }
			suggestions={ suggestions.map( s => `${ s.value }: ${ s.label }` ) }
			onInputChange={ handleInputChange }
			value={ tokens }
			onChange={ handleChange }
			__experimentalExpandOnFocus
			__next40pxDefaultSize
		/>
	);
}
