/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { compose, useInstanceId } from '@wordpress/compose';
import { ColorPicker, BaseControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { withDispatch, withSelect } from '@wordpress/data';
import { Fragment, useEffect } from '@wordpress/element';
import SelectControlWithOptGroup from '../../components/select-control-with-optgroup/';

const fontOptgroups = [
	{
		label: __( 'Sans Serif', 'newspack-newsletters' ),
		options: [
			{
				value: 'Arial, Helvetica, sans-serif',
				label: __( 'Arial', 'newspack-newsletters' ),
			},
			{
				value: 'Tahoma, sans-serif',
				label: __( 'Tahoma', 'newspack-newsletters' ),
			},
			{
				value: 'Trebuchet MS, sans-serif',
				label: __( 'Trebuchet', 'newspack-newsletters' ),
			},
			{
				value: 'Verdana, sans-serif',
				label: __( 'Verdana', 'newspack-newsletters' ),
			},
		],
	},

	{
		label: __( 'Serif', 'newspack-newsletters' ),
		options: [
			{
				value: 'Georgia, serif',
				label: __( 'Georgia', 'newspack-newsletters' ),
			},
			{
				value: 'Palatino, serif',
				label: __( 'Palatino', 'newspack-newsletters' ),
			},
			{
				value: 'Times New Roman, serif',
				label: __( 'Times New Roman', 'newspack-newsletters' ),
			},
		],
	},

	{
		label: __( 'Monospace', 'newspack-newsletters' ),
		options: [
			{
				value: 'Courier, monospace',
				label: __( 'Courier', 'newspack-newsletters' ),
			},
		],
	},
];

export default compose( [
	withDispatch( dispatch => {
		const { editPost } = dispatch( 'core/editor' );
		return { editPost };
	} ),
	withSelect( select => {
		const { getEditedPostAttribute, getCurrentPostId } = select( 'core/editor' );
		const meta = getEditedPostAttribute( 'meta' );
		return {
			postId: getCurrentPostId(),
			fontBody: meta.font_body || '',
			fontHeader: meta.font_header || '',
			backgroundColor: meta.background_color || '',
		};
	} ),
] )( ( { editPost, fontBody, fontHeader, backgroundColor, postId } ) => {
	const updateStyleValue = ( key, value ) => {
		editPost( { meta: { [ key ]: value } } );
		apiFetch( {
			data: { key, value },
			method: 'POST',
			path: `/newspack-newsletters/v1/styling/${ postId }`,
		} );
	};

	useEffect(() => {
		document.documentElement.style.setProperty( '--body-font', fontBody );
	}, [ fontBody ]);
	useEffect(() => {
		document.documentElement.style.setProperty( '--header-font', fontHeader );
	}, [ fontHeader ]);
	useEffect(() => {
		document.querySelector( '.edit-post-visual-editor' ).style.backgroundColor = backgroundColor;
	}, [ backgroundColor ]);

	const instanceId = useInstanceId( SelectControlWithOptGroup );
	const id = `inspector-select-control-${ instanceId }`;

	return (
		<Fragment>
			<SelectControlWithOptGroup
				label={ __( 'Headings font', 'newspack-newsletters' ) }
				value={ fontHeader || 'Arial' }
				optgroups={ fontOptgroups }
				onChange={ value => updateStyleValue( 'font_header', value ) }
			/>
			<SelectControlWithOptGroup
				label={ __( 'Body font', 'newspack-newsletters' ) }
				value={ fontBody || 'Georgia' }
				optgroups={ fontOptgroups }
				onChange={ value => updateStyleValue( 'font_body', value ) }
			/>
			<BaseControl label={ __( 'Background color', 'newspack-newsletters' ) } id={ id }>
				<ColorPicker
					id={ id }
					color={ backgroundColor || '#ffffff' }
					onChangeComplete={ value => updateStyleValue( 'background_color', value.hex ) }
					disableAlpha
				/>
			</BaseControl>
		</Fragment>
	);
} );
