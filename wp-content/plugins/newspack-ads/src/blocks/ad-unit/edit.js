/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';
import {
	ExternalLink,
	Placeholder,
	SelectControl,
	Spinner,
	withNotices,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

class Edit extends Component {
	/**
	 * Constructor.
	 */
	constructor() {
		super( ...arguments );
		this.state = {
			adUnits: null,
		};
	}

	componentDidMount() {
		apiFetch( { path: '/newspack/v1/wizard/advertising' } ).then( response =>
			this.setState( { adUnits: response.ad_units, initialUpdate: true } )
		);
	}

	componentDidUpdate( prevProps ) {
		const { attributes, noticeOperations } = this.props;
		const { activeAd } = attributes;
		if ( activeAd !== prevProps.attributes.activeAd ) {
			const { code, width, height } = this.activeAdDataForActiveAd( activeAd );
			if ( code && ! width && ! height ) {
				noticeOperations.createErrorNotice( __( 'Invalid ad unit code. No dimensions available' ) );
			} else {
				noticeOperations.removeAllNotices();
			}
		}
	}

	adUnitsForSelect = adUnits => {
		return [
			{
				label: __( 'Select an ad unit' ),
				value: null,
			},
			...Object.values( adUnits ).map( adUnit => {
				return {
					label: adUnit.name,
					value: adUnit.id,
				};
			} ),
		];
	};

	activeAdDataForActiveAd = activeAd => {
		const { adUnits } = this.state;
		const data =
			adUnits && adUnits.find( adUnit => parseInt( adUnit.id ) === parseInt( activeAd ) );
		return this.dimensionsFromAd( data );
	};

	dimensionsFromAd = adData => {
		const { sizes } = adData || {};
		const primarySize = sizes && sizes.length ? sizes[ 0 ] : [ 320, 240 ];
		const [ width, height ] = primarySize;
		return {
			width,
			height,
		};
	};

	render() {
		/**
		 * Constants
		 */
		const { attributes, setAttributes, noticeUI } = this.props;
		const { activeAd } = attributes;
		const { adUnits } = this.state;
		const { width = 100, height = 75 } = this.activeAdDataForActiveAd( activeAd );
		const constrainedWidth = Math.max( 100, width );
		const ratioStyle = { padding: `0 0 ${ Math.min( 500, ( height * 100 ) / width ) }%` };
		return (
			<Fragment>
				{ noticeUI }
				<div className="wp-block-newspack-ads-blocks-ad-unit">
					<div className="newspack-ads-ad-unit">
						<Placeholder style={ { width: `${ constrainedWidth }px` } }>
							<div className="newspack-ads-ad-unit__ratio" style={ ratioStyle } />
							{ ! adUnits && <Spinner /> }
							{ adUnits && !! adUnits.length && (
								<SelectControl
									label={ __( 'Ad Unit' ) }
									value={ activeAd }
									options={ this.adUnitsForSelect( adUnits ) }
									onChange={ _activeAd => setAttributes( { activeAd: _activeAd } ) }
								/>
							) }
							{ adUnits && ! adUnits.length && (
								<div className="components-base-control">
									<div className="components-base-control__field">
										{ __( 'No ad units have been created yet.' ) }
										<ExternalLink href="/wp-admin/admin.php?page=newspack-google-ad-manager-wizard#/">
											{ __( 'You can create ad units in the Ads wizard' ) }
										</ExternalLink>
									</div>
								</div>
							) }
						</Placeholder>
					</div>
				</div>
			</Fragment>
		);
	}
}

export default withNotices( Edit );
