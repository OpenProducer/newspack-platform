/**
 * WordPress dependencies.
 */
import { Component, createRef, Fragment, createPortal } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { closeSmall, desktop, mobile, tablet } from '@wordpress/icons';
import { Button, ButtonGroup } from '@wordpress/components';

/**
 * Internal dependencies.
 */
import { Waiting } from '../';
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

const PORTAL_PARENT_ID = 'newspack-components-web-preview-wrapper';

/**
 * Web Preview. A component to preview a website in an iframe, with device sizing options.
 */
class WebPreview extends Component {
	state = {
		device: 'desktop',
		loaded: false,
		isPreviewVisible: false,
	};

	constructor( props ) {
		super( props );
		this.iframeRef = createRef();
	}

	/**
	 * If a div with id PORTAL_PARENT_ID exists, assign it to class field.
	 * If not, create it and append to the body.
	 */
	componentDidMount() {
		const existingDOMElement = document.getElementById( PORTAL_PARENT_ID );
		this.modalDOMElement = existingDOMElement || document.createElement( 'div' );
		if ( ! existingDOMElement ) {
			this.modalDOMElement.id = PORTAL_PARENT_ID;
			document.body.appendChild( this.modalDOMElement );
		}
	}

	/**
	 * Add or remove applicable body classes
	 */
	componentDidUpdate() {
		if ( this.state.isPreviewVisible === true ) {
			document.body.classList.add( 'newspack-web-preview--open' );
		} else {
			document.body.classList.remove( 'newspack-web-preview--open' );
		}
	}

	/**
	 * Create JSX for the modal
	 */
	getWebPreviewModal = () => {
		const { beforeLoad = () => {}, onClose = () => {}, url } = this.props;
		const { device, loaded, isPreviewVisible } = this.state;

		if ( ! this.modalDOMElement || ! isPreviewVisible ) {
			return null;
		}

		const classes = classnames( 'newspack-web-preview', device, loaded && 'is-loaded' );
		beforeLoad();
		return createPortal(
			<div className={ classes }>
				<div className="newspack-web-preview__interior">
					<div className="newspack-web-preview__toolbar">
						<div className="newspack-web-preview__toolbar-left">
							<ButtonGroup>
								<Button
									onClick={ () => this.setState( { device: 'desktop' } ) }
									variant={ 'desktop' === device && 'primary' }
									icon={ desktop }
									label={ __( 'Preview Desktop Size', 'newspack-plugin' ) }
								/>
								<Button
									onClick={ () => this.setState( { device: 'tablet' } ) }
									variant={ 'tablet' === device && 'primary' }
									icon={ tablet }
									label={ __( 'Preview Tablet Size', 'newspack-plugin' ) }
								/>
								<Button
									onClick={ () => this.setState( { device: 'phone' } ) }
									variant={ 'phone' === device && 'primary' }
									icon={ mobile }
									label={ __( 'Preview Phone Size', 'newspack-plugin' ) }
								/>
							</ButtonGroup>
						</div>
						<div className="newspack-web-preview__toolbar-right">
							<Button
								onClick={ () => {
									onClose();
									this.setState( { isPreviewVisible: false, loaded: false } );
								} }
								icon={ closeSmall }
								label={ __( 'Close Preview', 'newspack-plugin' ) }
							/>
						</div>
					</div>
					<div className="newspack-web-preview__content">
						{ ! loaded && (
							<div className="newspack-web-preview__is-waiting">
								<Waiting isLeft />
								{ __( 'Loading…', 'newspack-plugin' ) }
							</div>
						) }
						<iframe
							ref={ this.iframeRef }
							title="web-preview"
							src={ url }
							onLoad={ () => {
								this.setState( { loaded: true } );
								this.props.onLoad( this.iframeRef.current );
							} }
						/>
					</div>
				</div>
			</div>,
			this.modalDOMElement
		);
	};

	showPreview = () => {
		this.setState( { isPreviewVisible: true } );
	};

	/**
	 * Render.
	 */
	render() {
		const {
			label,
			variant,
			/**
			 * Inversion of control - let the caller render
			 * the button that will trigger the modal
			 */
			renderButton,
		} = this.props;

		return (
			<Fragment>
				{ renderButton ? (
					renderButton( { showPreview: this.showPreview } )
				) : (
					<Button variant={ variant } onClick={ this.showPreview } tabIndex="0">
						{ label }
					</Button>
				) }
				{ this.getWebPreviewModal() }
			</Fragment>
		);
	}
}

WebPreview.defaultProps = {
	url: '//newspack.com',
	label: __( 'Preview', 'newspack-plugin' ),
	onLoad: () => {},
};

export default WebPreview;
