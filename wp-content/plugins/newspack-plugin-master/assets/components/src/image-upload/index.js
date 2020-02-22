/**
 * Image Upload
 */

/**
 * WordPress dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Material UI dependencies.
 */
import AddPhotoAlternateIcon from '@material-ui/icons/AddPhotoAlternate';
import DeleteIcon from '@material-ui/icons/Delete';

/**
 * Internal dependencies.
 */
import { Button } from '../';
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
		const attachment = this.state.frame
			.state()
			.get( 'selection' )
			.first()
			.toJSON();
		onChange( attachment );
	};

	/**
	 * Clear the selected image.
	 */
	removeImage = () => {
		const { onChange } = this.props;
		onChange( null );
	};

	/**
	 * Render.
	 */
	render = () => {
		const { className, image, removeText, addText } = this.props;
		const classes = classnames( 'newspack-image-upload', className );
		return (
			<div className={ classes }>
				{ !! image && (
					<Fragment>
						<div className="newspack-image-upload__image-preview">
							<img src={ image.url } alt="Upload preview" />
						</div>
						<Button
							onClick={ this.removeImage }
							className="newspack-image-upload__remove-image"
							isTertiary
							isSmall
						>
							<DeleteIcon />
							{ ! removeText && __( 'Remove image' ) }
							{ removeText && removeText }
						</Button>
					</Fragment>
				) }
				{ ! image && (
					<Button
						onClick={ this.openModal }
						className="newspack-image-upload__add-image"
						isTertiary
						isSmall
					>
						<AddPhotoAlternateIcon />
						{ ! addText && __( 'Add image' ) }
						{ addText && addText }
					</Button>
				) }
			</div>
		);
	};
}
export default ImageUpload;
