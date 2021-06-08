/**
 * WordPress dependencies
 */
import { compose, useInstanceId } from '@wordpress/compose';
import { ColorPicker, BaseControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { withDispatch, withSelect } from '@wordpress/data';
import { Fragment, useEffect } from '@wordpress/element';
import SelectControlWithOptGroup from '../../components/select-control-with-optgroup/';

/**
 * External dependencies
 */
import { CSSLint } from 'csslint';
import CodeMirror from '@uiw/react-codemirror';
// eslint-disable-next-line import/no-extraneous-dependencies
import 'codemirror/mode/css/css';
// eslint-disable-next-line import/no-extraneous-dependencies
import 'codemirror/addon/lint/lint';
// eslint-disable-next-line import/no-extraneous-dependencies
import 'codemirror/addon/lint/lint.css';
// eslint-disable-next-line import/no-extraneous-dependencies
import 'codemirror/addon/lint/css-lint';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * Add CSSLint to global scope for CodeMirror.
 */
window.CSSLint = window.CSSLint || CSSLint;

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

const customStylesSelector = select => {
	const { getEditedPostAttribute } = select( 'core/editor' );
	const meta = getEditedPostAttribute( 'meta' );
	return {
		fontBody: meta.font_body || fontOptgroups[ 1 ].options[ 0 ].value,
		fontHeader: meta.font_header || fontOptgroups[ 0 ].options[ 0 ].value,
		backgroundColor: meta.background_color || '#ffffff',
		customCss: meta.custom_css || '',
	};
};

// Create a temporary DOM document (not displayed) for parsing CSS rules.
const doc = document.implementation.createHTMLDocument( 'Temp' );

/**
 * Takes a given CSS string, parses it, and scopes all its rules to the given `scope`.
 *
 * @param {string} scope The scope to apply to each rule in the CSS.
 * @param {string} css The CSS to scope.
 * @returns Scoped CSS string.
 */
const getScopedCss = ( scope, css ) => {
	const style = doc.querySelector( 'style' ) || document.createElement( 'style' );

	style.textContent = css;
	doc.head.appendChild( style );

	const rules = [ ...style.sheet.cssRules ];
	const scopedRules = rules.map( rule => scope + ' ' + rule.cssText );

	return scopedRules.join( '\n' );
};

export const ApplyStyling = withSelect( customStylesSelector )(
	( { fontBody, fontHeader, backgroundColor, customCss } ) => {
		useEffect(() => {
			document.documentElement.style.setProperty( '--body-font', fontBody );
		}, [ fontBody ]);
		useEffect(() => {
			document.documentElement.style.setProperty( '--header-font', fontHeader );
		}, [ fontHeader ]);
		useEffect(() => {
			const editorElement = document.querySelector( '.editor-styles-wrapper' );
			if ( editorElement ) {
				editorElement.style.backgroundColor = backgroundColor;
			}
		}, [ backgroundColor ]);
		useEffect(() => {
			const editorElement = document.querySelector( '.edit-post-visual-editor' );
			if ( editorElement ) {
				let styleEl = document.getElementById( 'newspack-newsletters__custom-styles' );
				if ( ! styleEl ) {
					styleEl = document.createElement( 'style' );
					styleEl.setAttribute( 'type', 'text/css' );
					styleEl.setAttribute( 'id', 'newspack-newsletters__custom-styles' );
					document.head.appendChild( styleEl );
				}

				const scopedCss = getScopedCss( '.edit-post-visual-editor', customCss );

				styleEl.textContent = scopedCss;
			}
		}, [ customCss ]);

		return null;
	}
);

export const Styling = compose( [
	withDispatch( dispatch => {
		const { editPost } = dispatch( 'core/editor' );
		return { editPost };
	} ),
	withSelect( customStylesSelector ),
] )( ( { editPost, fontBody, fontHeader, customCss, backgroundColor } ) => {
	const updateStyleValue = ( key, value ) => {
		editPost( { meta: { [ key ]: value } } );
	};

	const instanceId = useInstanceId( SelectControlWithOptGroup );
	const id = `inspector-select-control-${ instanceId }`;

	return (
		<Fragment>
			<SelectControlWithOptGroup
				label={ __( 'Headings font', 'newspack-newsletters' ) }
				value={ fontHeader }
				optgroups={ fontOptgroups }
				onChange={ value => updateStyleValue( 'font_header', value ) }
			/>
			<SelectControlWithOptGroup
				label={ __( 'Body font', 'newspack-newsletters' ) }
				value={ fontBody }
				optgroups={ fontOptgroups }
				onChange={ value => updateStyleValue( 'font_body', value ) }
			/>
			<BaseControl label={ __( 'Background color', 'newspack-newsletters' ) } id={ id }>
				<ColorPicker
					id={ id }
					color={ backgroundColor }
					onChangeComplete={ value => updateStyleValue( 'background_color', value.hex ) }
					disableAlpha
				/>
			</BaseControl>
			<BaseControl
				id={ `inspector-custom-css-control-${ instanceId }` }
				label={ __( 'Custom CSS', 'newspack-newsletters' ) }
				help={ __(
					'This is an advanced feature and may result in unpredictable behavior. Custom CSS will be appended to default styles in sent emails only.',
					'newspack-newsletters'
				) }
			>
				<CodeMirror
					className="components-textarea-control__input"
					value={ customCss }
					height={ 250 }
					onChange={ instance => editPost( { meta: { custom_css: instance.getValue() } } ) }
					options={ {
						gutters: [ 'CodeMirror-lint-markers' ],
						height: 'auto',
						indentWithTabs: true,
						mode: 'css',
						lint: true,
					} }
				/>
			</BaseControl>
		</Fragment>
	);
} );
