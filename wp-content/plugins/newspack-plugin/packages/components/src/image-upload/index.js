/**
 * Image Upload
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { Button, InfoButton } from '../';
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

class ImageUpload extends Component {
	/**
	 * Constructor.
	 */
	constructor() {
		super( ...arguments );
		this.state = {
			frame: false,
		};
	}

	/**
	 * Open the WP media modal.
	 */
	openModal = () => {
		if ( this.state.frame ) {
			this.state.frame.open();
			return;
		}

		this.setState(
			{
				frame: wp.media( {
					title: __( 'Select or upload image' ),
					button: {
						text: __( 'Select' ),
					},
					library: {
						type: 'image',
					},
					multiple: false,
				} ),
			},
			() => {
				this.state.frame.on( 'select', this.handleImageSelect );
				this.state.frame.open();
			}
		);
	};

	/**
	 * Update the state when an image is selected from the media modal.
	 */
	handleImageSelect = () => {
		const { onChange } = this.props;
		const attachment = this.state.frame.state().get( 'selection' ).first().toJSON();
		onChange( attachment );
	};

	/**
	 * Render.
	 */
	render = () => {
		const { buttonLabel, className, disabled, help, image, info, isCovering, label, onChange, style = {} } = this.props;
		const classes = classnames(
			'newspack-image-upload__image',
			{ 'newspack-image-upload__image--has-image': image },
			{ 'newspack-image-upload__image--covering': isCovering }
		);
		return (
			<div className={ classnames( 'newspack-image-upload', className ) }>
				<div className="newspack-image-upload__header">
					{ /* eslint-disable-next-line jsx-a11y/label-has-for, jsx-a11y/label-has-associated-control */ }
					{ label && <label className="newspack-image-upload__label">{ label }</label> }
					{ info && <InfoButton text={ info } /> }
				</div>
				<div className={ classes } style={ { ...style } }>
					{ image?.url ? (
						<>
							<img data-testid="image-upload" src={ image.url } alt={ __( 'Image preview', 'newspack-plugin' ) } />
							<div className="newspack-image-upload__controls">
								<Button disabled={ disabled } onClick={ this.openModal } isLink>
									{ __( 'Replace', 'newspack-plugin' ) }
								</Button>
								<span className="sep" />
								<Button disabled={ disabled } onClick={ () => onChange( null ) } isLink isDestructive>
									{ __( 'Remove', 'newspack-plugin' ) }
								</Button>
							</div>
						</>
					) : (
						<Button disabled={ disabled } onClick={ this.openModal } isLink>
							{ buttonLabel ? buttonLabel : __( 'Upload', 'newspack-plugin' ) }
						</Button>
					) }
				</div>
				{ help && <p className="newspack-image-upload__help">{ help }</p> }
			</div>
		);
	};
}
export default ImageUpload;
