'use strict';

/**
 * WordPress dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';
import { ToggleControl } from '@wordpress/components';
import { Component } from '@wordpress/element';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';

/**
 * Add a section to the Document settings with a toggle for suppressing ads on the current single.
 */
class NewspackSuppressAdsPanel extends Component {
	render() {
		const { newspack_ads_suppress_ads, updateSuppressAds } = this.props;
		return (
			<PluginDocumentSettingPanel
				name="newspack-ad-free"
				title={ __( 'Newspack Ad Settings', 'newspack' ) }
				className="newspack-subtitle"
			>
				<ToggleControl
					label={ __( "Don't show ads on this post or page", 'newspack' ) }
					checked={ newspack_ads_suppress_ads }
					onChange={ value => {
						updateSuppressAds( value );
					} }
				/>
			</PluginDocumentSettingPanel>
		);
	}
}

const ComposedPanel = compose( [
	withSelect( select => {
		const { newspack_ads_suppress_ads } = select( 'core/editor' ).getEditedPostAttribute( 'meta' );
		return { newspack_ads_suppress_ads };
	} ),
	withDispatch( dispatch => ( {
		updateSuppressAds( value ) {
			dispatch( 'core/editor' ).editPost( { meta: { newspack_ads_suppress_ads: value } } );
		},
	} ) ),
] )( NewspackSuppressAdsPanel );

registerPlugin( 'plugin-document-setting-panel-newspack-suppress-ads', {
	render: ComposedPanel,
	icon: null,
} );
