/**
 * Salesforce Settings Screen
 */

/**
 * External dependencies
 */
import { parse } from 'qs';

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';
import { Component, Fragment } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import { Card, Grid, Notice, TextControl, withWizardScreen } from '../../../../components/src';

/**
 * Salesforce Settings Screen Component
 */
class Salesforce extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			error: null,
		};
	}

	/**
	 * On component mount.
	 */
	componentDidMount() {
		const query = parse( window.location.search );
		const authorizationCode = query.code;
		const redirectURI =
			window.location.origin +
			window.location.pathname +
			'?page=' +
			query[ '?page' ] +
			window.location.hash;

		if ( authorizationCode ) {
			// Remove `code` param from URL without adding history.
			window.history.replaceState( {}, '', redirectURI );

			this.getTokens( authorizationCode, redirectURI );
		}
	}

	/**
	 * On component update.
	 */
	componentDidUpdate( prevProps ) {
		const { isConnected } = this.props;

		// Clear any state errors on reset.
		if ( prevProps.isConnected && ! isConnected ) {
			return this.setState( { error: null } );
		}

		// If we're already connected, check status of refresh token.
		if ( ! prevProps.isConnected && isConnected ) {
			return this.checkConnectionStatus();
		}
	}

	/**
	 * Use auth code to request access and refresh tokens for Salesforce API.
	 * Saves tokens to options table.
	 * https://help.salesforce.com/articleView?id=remoteaccess_oauth_web_server_flow.htm&type=5
	 *
	 * @param {string} authorizationCode Auth code fetched from Salesforce.
	 * @return {void}
	 */
	async getTokens( authorizationCode, redirectURI ) {
		const { data, onChange, wizardApiFetch } = this.props;

		try {
			// Get the tokens.
			const response = await wizardApiFetch( {
				path: '/newspack/v1/wizard/salesforce/tokens',
				method: 'POST',
				data: {
					code: authorizationCode,
					redirect_uri: redirectURI,
				},
			} );

			const { access_token, client_id, client_secret, instance_url, refresh_token } = response;

			// Update values in parent state.
			if ( access_token && refresh_token ) {
				return onChange( {
					...data,
					access_token,
					client_id,
					client_secret,
					instance_url,
					refresh_token,
				} );
			}
		} catch ( e ) {
			this.setState( {
				error: __(
					'We couldn’t establish a connection to Salesforce. Please verify your Consumer Key and Secret and try connecting again.',
					'newspack'
				),
			} );
		}
	}

	/**
	 * Check validity of refresh token and show an error message if it's no longer active.
	 * The refresh token is valid until it's manually revoked in the Salesforce dashboard,
	 * or the Connected App is deleted there.
	 */
	async checkConnectionStatus() {
		const { wizardApiFetch } = this.props;
		const response = await wizardApiFetch( {
			path: '/newspack/v1/wizard/salesforce/connection-status',
			method: 'POST',
		} );
		if ( response.error ) {
			this.setState( { error: response.error } );
		}
	}

	/**
	 * Render.
	 */
	render() {
		const { data, isConnected, onChange } = this.props;
		const { client_id = '', client_secret = '', error } = data;

		return (
			<Grid>
				<Card noBorder>
					{ this.state.error && <Notice noticeText={ this.state.error } isWarning /> }

					{ isConnected && ! this.state.error && (
						<Notice
							noticeText={ __( 'Your site is connected to Salesforce.', 'newspack' ) }
							isSuccess
						/>
					) }

					{ ! isConnected && ! this.state.error && (
						<Fragment>
							<p>
								{ __(
									'To connect with Salesforce, create or choose a Connected App for this site in your Salesforce dashboard. Make sure to paste the the full URL for this page into the “Callback URL” field in the Connected App’s settings. ',
									'newspack'
								) }
								<ExternalLink href="https://help.salesforce.com/articleView?id=connected_app_create.htm">
									{ __( 'Learn how to create a Connected App', 'newspack' ) }
								</ExternalLink>
							</p>

							<p>
								{ __(
									'Enter your Consumer Key and Secret, then click “Connect” to authorize access to your Salesforce account.',
									'newspack'
								) }
							</p>
						</Fragment>
					) }

					{ isConnected && (
						<p>
							{ __(
								'To reconnect your site in case of issues, or to connect to a different Salesforce account, click “Reset". You will need to re-enter your Consumer Key and Secret before you can re-connect to Salesforce.',
								'newspack'
							) }
						</p>
					) }
				</Card>

				<Card noBorder>
					{ error && (
						<Notice
							noticeText={ __(
								'We couldn’t connect to Salesforce. Please verify that you entered the correct Consumer Key and Secret and try again. If you just created your Connected App or edited the Callback URL settings, it may take up to an hour before we can establish a connection.',
								'newspack'
							) }
							isError
						/>
					) }
					<TextControl
						disabled={ isConnected }
						label={
							( isConnected ? __( 'Your', 'newspack' ) : __( 'Enter your', 'newspack' ) ) +
							__( ' Salesforce Consumer Key', 'newspack' )
						}
						value={ client_id }
						onChange={ value => {
							if ( isConnected ) {
								return;
							}
							onChange( { ...data, client_id: value } );
						} }
					/>
					<TextControl
						disabled={ isConnected }
						label={
							( isConnected ? __( 'Your', 'newspack' ) : __( 'Enter your', 'newspack' ) ) +
							__( ' Salesforce Consumer Secret', 'newspack' )
						}
						value={ client_secret }
						onChange={ value => {
							if ( isConnected ) {
								return;
							}
							onChange( { ...data, client_secret: value } );
						} }
					/>
				</Card>
			</Grid>
		);
	}
}

Salesforce.defaultProps = {
	data: {},
	onChange: () => null,
};

export default withWizardScreen( Salesforce );
