/* eslint-disable jsx-a11y/anchor-is-valid */

/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { BlockControls, InspectorControls } from '@wordpress/block-editor';
import {
	BaseControl,
	Button,
	ButtonGroup,
	Notice,
	PanelBody,
	PanelRow,
	Placeholder,
	Spinner,
	ToggleControl,
	Toolbar,
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { decodeEntities } from '@wordpress/html-entities';
import { Icon, edit, people } from '@wordpress/icons';
import { __, sprintf } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';

/**
 * External dependencies
 */
import { AutocompleteWithSuggestions } from 'newspack-components';

export default ( { attributes, setAttributes } ) => {
	const [ author, setAuthor ] = useState( null );
	const [ error, setError ] = useState( null );
	const [ isLoading, setIsLoading ] = useState( false );
	const [ maxItemsToSuggest, setMaxItemsToSuggest ] = useState( 0 );
	const {
		authorId,
		showBio,
		showSocial,
		showEmail,
		showArchiveLink,
		showAvatar,
		avatarBorderRadius,
		avatarSize,
	} = attributes;

	useEffect( () => {
		if ( 0 !== authorId ) {
			getAuthorById();
		}
	}, [ authorId ] );

	const getAuthorById = async () => {
		setError( null );
		setIsLoading( true );
		try {
			const response = await apiFetch( {
				path: addQueryArgs( '/newspack-blocks/v1/authors', {
					author_id: authorId,
					fields: 'id,name,bio,email,social,avatar,url',
				} ),
			} );

			const _author = response.pop();

			if ( ! _author ) {
				throw sprintf(
					__( 'No authors or guest authors found for ID %s.', 'newspack-blocks' ),
					authorId
				);
			}
			setAuthor( _author );
		} catch ( e ) {
			setError(
				e.message ||
					e ||
					sprintf(
						__( 'No authors or guest authors found for ID %s.', 'newspack-blocks' ),
						authorId
					)
			);
		}
		setIsLoading( false );
	};

	// Combine social links and email, which are shown together.
	const socialLinks = ( showSocial && author && author.social ) || {};
	if ( showEmail && author && author.email ) {
		socialLinks.email = author.email;
	} else {
		delete socialLinks.email;
	}

	// Show a link to the author's post archive page, if available.
	const MaybeLink = ( { children } ) =>
		showArchiveLink && author && author.url ? (
			<a href="#" className="no-op">
				{ children }
			</a>
		) : (
			<>{ children }</>
		);

	// Available units for avatarBorderRadius option.
	const units = [
		{
			value: '%',
			label: '%',
		},
		{
			value: 'px',
			label: 'px',
		},
		{
			value: 'em',
			label: 'em',
		},
		{
			value: 'rem',
			label: 'rem',
		},
	];

	// Avatar size options.
	const avatarSizeOptions = [
		{
			value: 72,
			label: /* translators: label for small avatar size option */ __( 'Small', 'newspack-blocks' ),
			shortName: /* translators: abbreviation for small avatar size option */ __(
				'S',
				'newspack-blocks'
			),
		},
		{
			value: 128,
			label: /* translators: label for medium avatar size option */ __(
				'Medium',
				'newspack-blocks'
			),
			shortName: /* translators: abbreviation for medium avatar size option */ __(
				'M',
				'newspack-blocks'
			),
		},
		{
			value: 192,
			label: /* translators: label for large avatar size option */ __( 'Large', 'newspack-blocks' ),
			shortName: /* translators: abbreviation for large avatar size option */ __(
				'L',
				'newspack-blocks'
			),
		},
		{
			value: 256,
			label: /* translators: label for extra-large avatar size option */ __(
				'Extra-large',
				'newspack-blocks'
			),
			shortName: /* translators: abbreviation for extra-large avatar size option  */ __(
				'XL',
				'newspack-blocks'
			),
		},
	];

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Author Profile Settings', 'newspack-blocks' ) }>
					<PanelRow>
						<ToggleControl
							label={ __( 'Display biographical info', 'newspack-blocks' ) }
							checked={ showBio }
							onChange={ () => setAttributes( { showBio: ! showBio } ) }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={ __( 'Display social links', 'newspack-blocks' ) }
							checked={ showSocial }
							onChange={ () => setAttributes( { showSocial: ! showSocial } ) }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={ __( 'Display email address', 'newspack-blocks' ) }
							checked={ showEmail }
							onChange={ () => setAttributes( { showEmail: ! showEmail } ) }
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={ __( 'Link to author archive', 'newspack-blocks' ) }
							checked={ showArchiveLink }
							onChange={ () => setAttributes( { showArchiveLink: ! showArchiveLink } ) }
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody title={ __( 'Avatar Settings', 'newspack-blocks' ) }>
					<PanelRow>
						<ToggleControl
							label={ __( 'Display avatar', 'newspack-blocks' ) }
							checked={ showAvatar }
							onChange={ () => setAttributes( { showAvatar: ! showAvatar } ) }
						/>
					</PanelRow>
					{ showAvatar && (
						<BaseControl
							label={ __( 'Avatar size', 'newspack-blocks' ) }
							id="newspack-blocks__avatar-size-control"
						>
							<PanelRow>
								<ButtonGroup
									id="newspack-blocks__avatar-size-control-buttons"
									aria-label={ __( 'Avatar size', 'newspack-blocks' ) }
								>
									{ avatarSizeOptions.map( option => {
										const isCurrent = avatarSize === option.value;
										return (
											<Button
												isLarge
												isPrimary={ isCurrent }
												aria-pressed={ isCurrent }
												aria-label={ option.label }
												key={ option.value }
												onClick={ () => setAttributes( { avatarSize: option.value } ) }
											>
												{ option.shortName }
											</Button>
										);
									} ) }
								</ButtonGroup>
							</PanelRow>
						</BaseControl>
					) }
					{ showAvatar && (
						<PanelRow>
							<UnitControl
								label={ __( 'Avatar border radius', 'newspack-blocks' ) }
								labelPosition="edge"
								__unstableInputWidth="80px"
								units={ units }
								value={ avatarBorderRadius }
								onChange={ value =>
									setAttributes( { avatarBorderRadius: 0 > parseFloat( value ) ? '0' : value } )
								}
							/>
						</PanelRow>
					) }
				</PanelBody>
			</InspectorControls>
			{ author && (
				<BlockControls>
					<Toolbar
						controls={ [
							{
								icon: <Icon icon={ edit } />,
								title: __( 'Edit selection', 'newspack-blocks' ),
								onClick: () => {
									setAttributes( { authorId: 0 } );
									setAuthor( null );
								},
							},
						] }
					/>
				</BlockControls>
			) }
			<div className="newspack-author-profile">
				{ ! isLoading && ! error && author && (
					<div className="newspack-author-profile__author-card">
						{ showAvatar && author.avatar && (
							<div className="newspack-author-profile__avatar">
								<MaybeLink>
									<figure
										style={ { borderRadius: avatarBorderRadius, width: `${ avatarSize }px` } }
										dangerouslySetInnerHTML={ { __html: author.avatar } }
									/>
								</MaybeLink>
							</div>
						) }
						<div className="newspack-author-profile__bio">
							<h3>
								<MaybeLink>{ author.name }</MaybeLink>
							</h3>
							{ showBio && author.bio && (
								<p>
									{ author.bio }{' '}
									{ showArchiveLink && (
										<a href="#" className="no-op">
											More by { author.name }
										</a>
									) }
								</p>
							) }
							{ ( showEmail || showSocial ) && (
								<ul className="newspack-author-profile__social-links">
									{ Object.keys( socialLinks ).map( service => (
										<li key={ service }>
											<a href="#" className="no-op">
												{ socialLinks[ service ].svg && (
													<span
														dangerouslySetInnerHTML={ { __html: socialLinks[ service ].svg } }
													/>
												) }
												<span className={ socialLinks[ service ].svg ? 'hidden' : 'visible' }>
													{ service }
												</span>
											</a>
										</li>
									) ) }
								</ul>
							) }
						</div>
					</div>
				) }
				{ ! author && (
					<Placeholder
						icon={ <Icon icon={ people } /> }
						label={ __( 'Author Profile', 'newspack-blocks' ) }
					>
						{ error && (
							<Notice status="error" isDismissible={ false }>
								{ error }
							</Notice>
						) }
						{ isLoading && (
							<>
								<p style={ { margin: 0 } }>
									{ __( 'Fetching author info...', 'newspack-blocks' ) }
								</p>
								<Spinner />
							</>
						) }
						{ ! isLoading && (
							<AutocompleteWithSuggestions
								label={ __( 'Search for an author to display', 'newspack-blocks' ) }
								help={ __(
									'Begin typing name, click autocomplete result to select.',
									'newspack=blocks'
								) }
								fetchSuggestions={ async ( search = null, offset = 0 ) => {
									// If we already have a selected author, no need to fetch suggestions.
									if ( authorId ) {
										return [];
									}

									const response = await apiFetch( {
										parse: false,
										path: addQueryArgs( '/newspack-blocks/v1/authors', {
											search,
											offset,
											fields: 'id,name',
										} ),
									} );

									const total = parseInt( response.headers.get( 'x-wp-total' ) || 0 );
									const authors = await response.json();

									// Set max items for "load more" functionality in suggestions list.
									if ( ! maxItemsToSuggest && ! search ) {
										setMaxItemsToSuggest( total );
									}

									return authors.map( _author => ( {
										value: _author.id,
										label: decodeEntities( _author.name ) || __( '(no name)', 'newspack' ),
									} ) );
								} }
								maxItemsToSuggest={ maxItemsToSuggest }
								onChange={ items => setAttributes( { authorId: parseInt( items[ 0 ].value ) } ) }
								postTypeLabel={ __( 'author', 'newspack-blocks' ) }
								postTypeLabelPlural={ __( 'authors', 'newspack-blocks' ) }
								selectedItems={ [] }
							/>
						) }
					</Placeholder>
				) }
			</div>
		</>
	);
};
