/**
 * Handoff
 */

/**
 * WordPress dependencies.
 */
import apiFetch from '@wordpress/api-fetch';
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { Button, Card, Modal, Waiting } from '../';

/**
 * External dependencies.
 */
import assign from 'lodash/assign';
import classnames from 'classnames';

class Handoff extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			pluginInfo: [],
			showModal: false,
		};
	}

	componentDidMount = () => {
		this._isMounted = true;
		const { plugin } = this.props;
		this.retrievePluginInfo( plugin );
	};

	componentWillUnmount = () => {
		this._isMounted = false;
	};

	retrievePluginInfo = plugin => {
		const { onReady } = this.props;
		apiFetch( { path: '/newspack/v1/plugins/' + plugin } ).then( pluginInfo => {
			if ( this._isMounted ) {
				onReady( pluginInfo );
				this.setState( { pluginInfo } );
			}
		} );
	};

	textForPlugin = pluginInfo => {
		const defaults = {
			modalBody: null,
			modalTitle: pluginInfo.Name && `${ __( 'Manage', 'newspack-plugin' ) } ${ pluginInfo.Name }`,
			primaryButton: pluginInfo.Name && `${ __( 'Manage', 'newspack-plugin' ) } ${ pluginInfo.Name }`,
			primaryModalButton: __( 'Manage', 'newspack-plugin' ),
			dismissModalButton: __( 'Dismiss', 'newspack-plugin' ),
		};
		return assign( defaults, this.props );
	};

	goToPlugin = plugin => {
		const { editLink, showOnBlockEditor } = this.props;
		apiFetch( {
			path: '/newspack/v1/plugins/' + plugin + '/handoff',
			method: 'POST',
			data: {
				editLink,
				handoffReturnUrl: window && window.location.href,
				showOnBlockEditor: showOnBlockEditor ? true : false,
			},
		} ).then( response => {
			window.location.href = response.HandoffLink;
		} );
	};

	/**
	 * Render.
	 */
	render() {
		const {
			className,
			children,
			compact,
			useModal,
			// eslint-disable-next-line no-unused-vars,@typescript-eslint/no-unused-vars
			modalTitle: _modalTitle,
			// eslint-disable-next-line no-unused-vars,@typescript-eslint/no-unused-vars
			modalBody: _modalBody,
			// eslint-disable-next-line no-unused-vars,@typescript-eslint/no-unused-vars
			onReady,
			// eslint-disable-next-line no-unused-vars,@typescript-eslint/no-unused-vars
			editLink,
			...otherProps
		} = this.props;
		const { pluginInfo, showModal } = this.state;
		const { modalBody, modalTitle, primaryButton, primaryModalButton, dismissModalButton } = this.textForPlugin( pluginInfo );
		const { Configured, Name, Slug, Status } = pluginInfo;
		const classes = classnames( Configured && 'is-configured', className );
		return (
			<Fragment>
				{ Name && 'active' === Status && (
					<Button
						className={ classes }
						isSecondary={ ! otherProps.isPrimary && ! otherProps.isTertiary && ! otherProps.isLink }
						{ ...otherProps }
						onClick={ () => ( useModal ? this.setState( { showModal: true } ) : this.goToPlugin( Slug ) ) }
					>
						{ children ? children : primaryButton }
					</Button>
				) }
				{ Name && 'active' !== Status && (
					<Button className={ classes } variant="secondary" disabled { ...otherProps }>
						{ Name + __( ' not installed', 'newspack-plugin' ) }
					</Button>
				) }
				{ ! Name && (
					<Button
						className={ classes }
						isSecondary={ ! otherProps.isPrimary && ! otherProps.isTertiary && ! otherProps.isLink }
						{ ...otherProps }
					>
						<Fragment>
							{ ! compact && <Waiting isLeft /> }
							{ __( 'Retrieving Plugin Info', 'newspack-plugin' ) }
						</Fragment>
					</Button>
				) }
				{ showModal && (
					<Modal title={ modalTitle } onRequestClose={ () => this.setState( { showModal: false } ) }>
						<p>{ modalBody }</p>
						<Card buttonsCard noBorder className="justify-end">
							<Button variant="secondary" onClick={ () => this.setState( { showModal: false } ) }>
								{ dismissModalButton }
							</Button>
							<Button variant="primary" onClick={ () => this.goToPlugin( Slug ) }>
								{ primaryModalButton }
							</Button>
						</Card>
					</Modal>
				) }
			</Fragment>
		);
	}
}

Handoff.defaultProps = {
	onReady: () => {},
};

export default Handoff;
