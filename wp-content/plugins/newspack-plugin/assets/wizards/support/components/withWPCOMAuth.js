/* global newspack_support_data */

/**
 * WordPress dependencies
 */
import { Fragment, Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import { Notice, Button, Waiting } from '../../../components/src';
import { saveReturnPath } from '../utils';

const { WPCOM_ACCESS_TOKEN } = newspack_support_data;

const withWPCOMAuth = WrappedComponent => {
	return class WithWPCOMAuth extends Component {
		state = {
			isInFlight: false,
			shouldAuthenticate: true,
			errorMessage: null,
		};
		componentDidMount() {
			if ( WPCOM_ACCESS_TOKEN ) {
				this.setState( { isInFlight: true } );
				apiFetch( {
					path: `/newspack/v1/oauth/wpcom/validate`,
				} )
					.then( () => {
						this.setState( { isInFlight: false, shouldAuthenticate: false } );
					} )
					.catch( ( { code, message } ) => {
						if ( code !== 'invalid_wpcom_token' ) {
							this.setState( { errorMessage: message } );
						}
						saveReturnPath();
						this.setState( { isInFlight: false, shouldAuthenticate: true } );
					} );
			} else {
				saveReturnPath();
				this.setState( { shouldAuthenticate: true } );
			}
		}
		renderContent = () =>
			this.state.shouldAuthenticate ? (
				<Fragment>
					<Notice
						isError={ !! this.state.errorMessage }
						noticeText={
							this.state.errorMessage ||
							__(
								'Click the button below to authenticate using a WordPress.com account.',
								'newspack'
							)
						}
					/>
					<div className="newspack-buttons-card">
						<Button href={ newspack_support_data.WPCOM_AUTH_URL } isPrimary>
							{ __( 'Authenticate', 'newspack' ) }
						</Button>
					</div>
				</Fragment>
			) : (
				<WrappedComponent token={ WPCOM_ACCESS_TOKEN } { ...this.props } />
			);
		render() {
			return (
				<Fragment>
					{ this.state.isInFlight ? (
						<div className="newspack_support_loading">
							<Waiting isLeft />
							{ __( 'Loading...', 'newspack' ) }
						</div>
					) : (
						this.renderContent()
					) }
				</Fragment>
			);
		}
	};
};

export default withWPCOMAuth;
