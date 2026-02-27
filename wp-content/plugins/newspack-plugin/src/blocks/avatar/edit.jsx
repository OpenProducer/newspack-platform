/**
 * External dependencies
 */
import clsx from 'clsx';
/**
 * WordPress dependencies
 */
import {
	InspectorControls,
	useBlockProps,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalUseBorderProps as useBorderProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody, RangeControl, ToggleControl } from '@wordpress/components';
import { addQueryArgs, removeQueryArgs } from '@wordpress/url';
/**
 * Internal dependencies
 */
import { useUserAvatar, usePostAuthors } from './hooks';
const AvatarInspectorControls = ( { setAttributes, attributes } ) => (
	<InspectorControls>
		<PanelBody title={ __( 'Settings', 'newspack-plugin' ) }>
			<RangeControl
				__nextHasNoMarginBottom
				__next40pxDefaultSize
				label={ __( 'Image size', 'newspack-plugin' ) }
				onChange={ newSize =>
					setAttributes( {
						size: newSize,
					} )
				}
				min={ 16 }
				max={ 128 }
				initialPosition={ attributes.size }
				value={ attributes.size }
			/>
			<ToggleControl
				__nextHasNoMarginBottom
				label={ __( 'Link to author archive', 'newspack-plugin' ) }
				onChange={ () => setAttributes( { linkToAuthorArchive: ! attributes.linkToAuthorArchive } ) }
				checked={ attributes.linkToAuthorArchive }
			/>
		</PanelBody>
	</InspectorControls>
);

const AvatarWrapper = ( { avatar, size, attributes } ) => {
	const { className } = useBlockProps();
	const borderProps = useBorderProps( attributes );

	const avatarSrc = avatar?.src;
	if ( ! avatarSrc ) {
		return null;
	}

	const duotoneClassName = className ? className.split( ' ' ).filter( classes => classes.includes( 'wp-duotone' ) ) : '';
	const classNames = clsx( 'newspack-avatar-wrapper', duotoneClassName );
	const doubledSizedSrc = addQueryArgs( removeQueryArgs( avatarSrc, [ 's' ] ), {
		s: attributes?.size * 2,
	} );
	const avatarImage = (
		<img
			src={ doubledSizedSrc }
			alt={ avatar.alt || '' }
			className={ clsx( 'avatar', 'avatar-' + size, 'photo', 'wp-block-newspack-avatar__image', borderProps.className ) }
			style={ {
				width: size,
				height: size,
				...borderProps.style,
			} }
		/>
	);
	return (
		<div
			className={ classNames }
			style={ {
				'--avatar-size': size + 'px',
			} }
		>
			{ attributes.linkToAuthorArchive ? (
				<a href="#avatar-pseudo-link" className="wp-block-newspack-avatar__link" onClick={ event => event.preventDefault() }>
					{ avatarImage }
				</a>
			) : (
				avatarImage
			) }
		</div>
	);
};

const Edit = ( { attributes, context, setAttributes } ) => {
	const { postId, postType } = context;
	const avatar = useUserAvatar( { userId: attributes?.userId, postId, postType } );
	const allAuthors = usePostAuthors( { postId, postType } );
	const blockProps = useBlockProps();

	const authors = allAuthors?.length ? allAuthors : null;

	// Wait until we have something to render
	if ( ! avatar?.src && ! authors?.length ) {
		return <div { ...blockProps }>{ __( 'Loading avatar…', 'newspack-plugin' ) }</div>;
	}

	const renderAvatar = ( currentAvatar, key ) => (
		<AvatarWrapper key={ key } avatar={ currentAvatar } size={ attributes.size } attributes={ attributes } />
	);
	return (
		<>
			<AvatarInspectorControls attributes={ attributes } setAttributes={ setAttributes } />
			{ authors?.length
				? authors.map( ( author, index ) => {
						const currentAvatar = {
							src: author.avatarSrc,
							alt: author?.name || author?.display_name || '',
						};
						return renderAvatar( currentAvatar, author.id || index );
				  } )
				: renderAvatar( avatar, 'single-author' ) }
		</>
	);
};

export default Edit;
