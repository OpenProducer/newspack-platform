/**
 * WordPress dependencies.
 */
import { Component, createRef, Fragment, createPortal } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { close, desktop, mobile, tablet } from '@wordpress/icons';

/**
 * Internal dependencies.
 */
import { Button, ButtonGroup, Waiting } from '../';
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

		const classes = classnames(
			'newspack-web-preview__container',
			device,
			loaded && 'newspack-web-preview__is-loaded'
		);
		beforeLoad();
		return createPortal(
			<div className={ classes }>
				<div className="newspack-web-preview__interior">
					<div className="newspack-web-preview__toolbar">
						<div className="newspack-web-preview__toolbar-left">
							<ButtonGroup>
								<Button
									onClick={ () => this.setState( { device: 'desktop' } ) }
									isSmall
									className={ classnames( 'is-desktop', 'desktop' === device && 'is-selected' ) }
									icon={ desktop }
									label={ __( 'Preview desktop size', 'newspack' ) }
								/>
								<Button
									onClick={ () => this.setState( { device: 'tablet' } ) }
									isSmall
									className={ classnames( 'is-tablet', 'tablet' === device && 'is-selected' ) }
									icon={ tablet }
									label={ __( 'Preview tablet size', 'newspack' ) }
								/>
								<Button
									onClick={ () => this.setState( { device: 'phone' } ) }
									isSmall
									className={ classnames( 'is-phone', 'phone' === device && 'is-selected' ) }
									icon={ mobile }
									label={ __( 'Preview phone size', 'newspack' ) }
								/>
							</ButtonGroup>
						</div>
						<div className="newspack-web-preview__toolbar-right">
							<Button
								onClick={ () => {
									onClose();
									this.setState( { isPreviewVisible: false, loaded: false } );
								} }
								isSmall
								icon={ close }
								label={ __( 'Close preview', 'newspack' ) }
							/>
						</div>
					</div>
					<div className="newspack-web-preview__content">
						{ ! loaded && (
							<div className="newspack-web-preview__is-waiting">
								<Waiting isLeft />
								{ __( 'Loading...', 'newspack' ) }
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
			isPrimary,
			isSecondary,
			isTertiary,
			isQuaternary,
			isLink,
			isSmall,
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
					<Button
						isPrimary={ isPrimary }
						isSecondary={ isSecondary }
						isTertiary={ isTertiary }
						isQuaternary={ isQuaternary }
						isLink={ isLink }
						isSmall={ isSmall }
						onClick={ this.showPreview }
						tabIndex="0"
					>
						{ label }
					</Button>
				) }
				{ this.getWebPreviewModal() }
			</Fragment>
		);
	}
}

WebPreview.defaultProps = {
	url: '//newspack.pub',
	label: __( 'Preview', 'newspack' ),
	onLoad: () => {},
};

export default WebPreview;
