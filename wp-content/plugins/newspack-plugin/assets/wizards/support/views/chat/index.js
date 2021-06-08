/**
 * External dependencies
 */
import Happychat from 'happychat-client';

/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { withWizardScreen } from '../../../../components/src';
import withWPCOMAuth from '../../components/withWPCOMAuth';
import './style.scss';

/**
 * Chat Support screen.
 */
class Chat extends Component {
	closeHappychat = null;

	renderChat = () => {
		const { token } = this.props;
		this.closeHappychat = Happychat.open( {
			nodeId: 'newspack-happychat',
			authentication: {
				type: 'wpcom-oauth-by-token',
				options: { token },
			},
			entry: 'ENTRY_CHAT',
			// BUG in happychat-client - no default defaultValues
			entryOptions: {
				defaultValues: {},
			},
		} );
	};

	componentDidMount() {
		this.renderChat();

		let didSendInitialInfo;
		Happychat.on( 'availability', availability => {
			if ( ! didSendInitialInfo && availability ) {
				didSendInitialInfo = true;
				Happychat.sendUserInfo( {
					site: {
						ID: '0',
						URL: `${ window.location.protocol }//${ window.location.hostname }`,
					},
					// just to bust the default 'gettingStarted'
					howCanWeHelp: 'newspack',
				} );

				Happychat.sendEvent(
					__(
						'[ Newspack customer (fieldguide.automattic.com/supporting-newspack-customers) ]',
						'newspack'
					)
				);
			}
		} );
	}

	componentWillUnmount() {
		if ( this.closeHappychat ) {
			this.closeHappychat();
		}
	}

	render() {
		return <div id="newspack-happychat" />;
	}
}

export default withWizardScreen( withWPCOMAuth( Chat ) );
