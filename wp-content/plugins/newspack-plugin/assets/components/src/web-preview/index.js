/**
 * WordPress dependencies.
 */
import { Component, Fragment, createPortal } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Material UI dependencies.
 */
import CloseIcon from '@material-ui/icons/Close';
import DesktopWindowsIcon from '@material-ui/icons/DesktopWindows';
import PhoneAndroidIcon from '@material-ui/icons/PhoneAndroid';
import TabletAndroidIcon from '@material-ui/icons/TabletAndroid';

/**
 * Internal dependencies.
 */
import { Button, Waiting } from '../';
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
			document.body.classList.add( 'newspack-web-preview__open' );
		} else {
			document.body.classList.remove( 'newspack-web-preview__open' );
		}
	}

	/**
	 * Create JSX for the modal
	 */
	getWebPreviewModal = () => {
		const { url } = this.props;
		const { device, loaded, isPreviewVisible } = this.state;

		if ( ! this.modalDOMElement || ! isPreviewVisible ) {
			return null;
		}

		const classes = classnames(
			'newspack-web-preview__container',
			device,
			loaded && 'newspack-web-preview__is-loaded'
		);

		return createPortal(
			<div className={ classes }>
				<div className="newspack-web-preview__interior">
					<div className="newspack-web-preview__toolbar">
						<div className="newspack-web-preview__toolbar-left">
							<Button
								onClick={ () => this.setState( { device: 'desktop' } ) }
								isPrimary={ 'desktop' === device }
								isSecondary={ 'desktop' !== device }
								className="is-desktop"
							>
								<DesktopWindowsIcon />
								<span className="screen-reader-text">{ __( 'Preview desktop size' ) }</span>
							</Button>
							<Button
								onClick={ () => this.setState( { device: 'tablet' } ) }
								isPrimary={ 'tablet' === device }
								isSecondary={ 'tablet' !== device }
								className="is-tablet"
							>
								<TabletAndroidIcon />
								<span className="screen-reader-text">{ __( 'Preview tablet size' ) }</span>
							</Button>
							<Button
								onClick={ () => this.setState( { device: 'phone' } ) }
								isPrimary={ 'phone' === device }
								isSecondary={ 'phone' !== device }
								className="is-phone"
							>
								<PhoneAndroidIcon />
								<span className="screen-reader-text">{ __( 'Preview phone size' ) }</span>
							</Button>
						</div>
						<div className="newspack-web-preview__toolbar-right">
							<Button onClick={ () => this.setState( { isPreviewVisible: false, loaded: false } ) }>
								<CloseIcon />
								<span className="screen-reader-text">{ __( 'Close preview' ) }</span>
							</Button>
						</div>
					</div>
					<div className="newspack-web-preview__content">
						{ ! loaded && (
							<div className="newspack-web-preview__is-waiting">
								<Waiting isLeft />
								{ __( 'Loading...' ) }
							</div>
						) }
						<iframe
							title="web-preview"
							src={ url }
							onLoad={ () => this.setState( { loaded: true } ) }
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
			isLink,
			isLarge,
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
						isLink={ isLink }
						isLarge={ isLarge }
						isSmall={ isSmall }
						onClick={ this.showPreview }
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
	url: '//newspack.blog',
	label: __( 'Preview', 'newspack' ),
};

export default WebPreview;
