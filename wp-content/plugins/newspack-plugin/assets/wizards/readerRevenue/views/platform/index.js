/**
 * Platform Selection Screen
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Grid, PluginInstaller, SelectControl, withWizardScreen } from '../../../../components/src';
import Router from '../../../../components/src/proxied-imports/router';
import { NEWSPACK, NRH } from '../../constants';

const { withRouter } = Router;

/**
 * Platform Selection  Screen Component
 */
class Platform extends Component {
	/**
	 * Constructor.
	 */
	constructor() {
		super( ...arguments );
		this.state = {
			manualPlatformChange: false,
		};
	}

	/**
	 * Redirect after platform change.
	 */
	componentDidUpdate( prevProps ) {
		const { manualPlatformChange } = this.state;
		const { data, history, pluginStatus } = this.props;
		const { platform } = data;
		if ( ! manualPlatformChange && this.props.data.platform !== prevProps.data.platform ) {
			if ( NRH === platform ) {
				history.push( '/settings' );
			} else if ( NEWSPACK === platform && pluginStatus ) {
				history.push( '/donations' );
			}
		}
	}

	/**
	 * Render.
	 */
	render() {
		const { data, onChange, onReady, pluginStatus } = this.props;
		const { platform } = data;
		return (
			<Fragment>
				<Grid>
					<SelectControl
						label={ __( 'Select Reader Revenue Platform', 'newspack' ) }
						value={ platform }
						options={ [
							{ label: __( '-- Select Your Platform --', 'newspack' ), value: '' },
							{
								label: __( 'Newspack', 'newspack' ),
								value: NEWSPACK,
							},
							{
								label: __( 'News Revenue Hub', 'newspack' ),
								value: NRH,
							},
						] }
						onChange={ _platform => {
							if ( _platform.length ) {
								this.setState( { manualPlatformChange: true } );
								onChange( {
									...data,
									platform: _platform,
								} );
							}
						} }
					/>
				</Grid>
				{ NEWSPACK === platform && ! pluginStatus && (
					<PluginInstaller
						plugins={ [
							'woocommerce',
							'woocommerce-subscriptions',
							'woocommerce-name-your-price',
							'woocommerce-gateway-stripe',
						] }
						onStatus={ ( { complete } ) => {
							if ( complete ) {
								onReady();
							}
						} }
						withoutFooterButton={ true }
					/>
				) }
			</Fragment>
		);
	}
}
export default withWizardScreen( withRouter( Platform ) );
