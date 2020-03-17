/**
 * Popup Custom Post Type
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Component, Fragment } from '@wordpress/element';
import {
	CheckboxControl,
	RangeControl,
	RadioControl,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel, PluginPostStatusInfo } from '@wordpress/edit-post';
import { ColorPaletteControl } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import { optionsFieldsSelector, updateEditorColors } from './utils';
import PopupPreview from './PopupPreview';

class PopupSidebar extends Component {
	state = {
		isSiteWideDefault: this.props.newspack_popups_is_sitewide_default,
	};
	componentDidMount() {
		const { background_color } = this.props;
		updateEditorColors( background_color );
	}
	componentDidUpdate( prevProps ) {
		const { background_color, isSavingPost, id } = this.props;
		const { isSiteWideDefault } = this.state;
		if ( ! prevProps.isSavingPost && isSavingPost ) {
			const params = {
				path: '/newspack-popups/v1/sitewide_default/' + id,
				method: isSiteWideDefault ? 'POST' : 'DELETE',
			};
			apiFetch( params );
		}
		if ( background_color !== prevProps.background_color ) {
			updateEditorColors( background_color );
		}
	}
	frequencyOptions = placement => {
		const options = [
			{ value: 'test', label: __( 'Test mode', 'newspack-popups' ) },
			{ value: 'never', label: __( 'Never', 'newspack-popups' ) },
			{ value: 'once', label: __( 'Once', 'newspack-popups' ) },
			{ value: 'daily', label: __( 'Once a day', 'newspack-popups' ) },
		];
		if ( 'inline' === placement ) {
			options.push( { value: 'always', label: __( 'Every page', 'newspack-popups' ) } );
		}
		return options;
	};
	/**
	 * Render
	 */
	render() {
		const {
			background_color,
			dismiss_text,
			display_title,
			frequency,
			onMetaFieldChange,
			overlay_opacity,
			overlay_color,
			placement,
			trigger_scroll_progress,
			trigger_delay,
			trigger_type,
			utm_suppression,
			onSitewideDefaultChange,
			isCurrentPostPublished,
		} = this.props;
		const { isSiteWideDefault } = this.state;
		const isInline = 'inline' === placement;
		return (
			<Fragment>
				{ isCurrentPostPublished && (
					<CheckboxControl
						label={ __( 'Sitewide Default', 'newspack-popups' ) }
						checked={ isSiteWideDefault }
						onChange={ _isSiteWideDefault => {
							this.setState( { isSiteWideDefault: _isSiteWideDefault } );
							// an ugly hack to update the post attribute, just to make the editor aware of a change,
							// so that "update" button becomes enabled
							onSitewideDefaultChange( Math.random() );
						} }
					/>
				) }
				<SelectControl
					label={ __( 'Placement' ) }
					value={ placement }
					onChange={ value => onMetaFieldChange( 'placement', value ) }
					options={ [
						{ value: 'center', label: __( 'Center' ) },
						{ value: 'top', label: __( 'Top' ) },
						{ value: 'bottom', label: __( 'Bottom' ) },
						{ value: 'inline', label: __( 'Inline' ) },
					] }
				/>
				{ ! isInline && (
					<Fragment>
						<RadioControl
							label={ __( 'Trigger' ) }
							help={ __( 'The event to trigger the popup' ) }
							selected={ trigger_type }
							options={ [
								{ label: __( 'Timer' ), value: 'time' },
								{ label: __( 'Scroll Progress' ), value: 'scroll' },
							] }
							onChange={ value => onMetaFieldChange( 'trigger_type', value ) }
						/>
						{ 'time' === trigger_type && (
							<RangeControl
								label={ __( 'Delay (seconds)' ) }
								value={ trigger_delay }
								onChange={ value => onMetaFieldChange( 'trigger_delay', value ) }
								min={ 0 }
								max={ 60 }
							/>
						) }
						{ 'scroll' === trigger_type && (
							<RangeControl
								label={ __( 'Scroll Progress (percent) ' ) }
								value={ trigger_scroll_progress }
								onChange={ value => onMetaFieldChange( 'trigger_scroll_progress', value ) }
								min={ 1 }
								max={ 100 }
							/>
						) }
					</Fragment>
				) }
				{ isInline && (
					<RangeControl
						label={ __( 'Approximate position (in percent)' ) }
						value={ trigger_scroll_progress }
						onChange={ value => onMetaFieldChange( 'trigger_scroll_progress', value ) }
						min={ 1 }
						max={ 100 }
					/>
				) }
				<SelectControl
					label={ __( 'Frequency' ) }
					value={ frequency }
					onChange={ value => onMetaFieldChange( 'frequency', value ) }
					options={ this.frequencyOptions( placement ) }
					help={ __(
						'In "Test Mode" logged-in admins will see the Pop-up every time, and non-admins will never see them.',
						'newspack-popups'
					) }
				/>
				<TextControl
					label={ __( 'UTM Suppression' ) }
					help={ __(
						'Users arriving at the site from URLs with this utm_source will never be shown the pop-up.'
					) }
					value={ utm_suppression }
					onChange={ value => onMetaFieldChange( 'utm_suppression', value ) }
				/>
				<ColorPaletteControl
					value={ background_color }
					onChange={ value => onMetaFieldChange( 'background_color', value ) }
					label={ __( 'Background Color' ) }
				/>
				{ ! isInline && (
					<Fragment>
						<ColorPaletteControl
							value={ overlay_color }
							onChange={ value => onMetaFieldChange( 'overlay_color', value ) }
							label={ __( 'Overlay Color' ) }
						/>
						<RangeControl
							label={ __( 'Overlay opacity' ) }
							value={ overlay_opacity }
							onChange={ value => onMetaFieldChange( 'overlay_opacity', value ) }
							min={ 0 }
							max={ 100 }
						/>
					</Fragment>
				) }
				<ToggleControl
					label={ __( 'Display Pop-up title', 'newspack-popups' ) }
					checked={ display_title }
					onChange={ value => onMetaFieldChange( 'display_title', value ) }
				/>
				<TextControl
					label={ __( 'Text for "Not Interested" button' ) }
					value={ dismiss_text }
					onChange={ value => onMetaFieldChange( 'dismiss_text', value ) }
				/>
			</Fragment>
		);
	}
}

const PopupSidebarWithData = compose( [
	withSelect( optionsFieldsSelector ),
	withDispatch( dispatch => {
		return {
			onMetaFieldChange: ( key, value ) => {
				dispatch( 'core/editor' ).editPost( { meta: { [ key ]: value } } );
			},
			onSitewideDefaultChange: value => {
				dispatch( 'core/editor' ).editPost( { newspack_popups_is_sitewide_default: value } );
			},
		};
	} ),
] )( PopupSidebar );

const PluginDocumentSettingPanelDemo = () => (
	<PluginDocumentSettingPanel name="popup-settings-panel" title={ __( ' Pop-up Settings' ) }>
		<PopupSidebarWithData />
	</PluginDocumentSettingPanel>
);
registerPlugin( 'newspack-popups', {
	render: PluginDocumentSettingPanelDemo,
	icon: null,
} );

// Add a button in post status section
const PluginPostStatusInfoTest = () => (
	<PluginPostStatusInfo>
		<PopupPreview />
	</PluginPostStatusInfo>
);
registerPlugin( 'newspack-popups-preview', { render: PluginPostStatusInfoTest } );
