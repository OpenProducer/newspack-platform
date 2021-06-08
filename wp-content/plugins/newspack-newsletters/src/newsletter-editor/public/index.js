/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { ToggleControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';
import { Fragment } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

const PublicSettingsComponent = props => {
	const { meta, updateIsPublic } = props;
	const { is_public } = meta;

	return (
		<Fragment>
			<ToggleControl
				className="newspack-newsletters__public-toggle-control"
				label={ __( 'Make newsletter page public?', 'newspack-newsletters' ) }
				help={ __( 'Make this newsletter viewable as a public page once it’s been sent.' ) }
				checked={ is_public }
				onChange={ value => updateIsPublic( value ) }
			/>
		</Fragment>
	);
};

const mapStateToProps = select => {
	const { getEditedPostAttribute } = select( 'core/editor' );

	return {
		meta: getEditedPostAttribute( 'meta' ),
	};
};

const mapDispatchToProps = dispatch => {
	const { editPost } = dispatch( 'core/editor' );

	return {
		updateIsPublic: value => editPost( { meta: { is_public: value } } ),
	};
};

export const PublicSettings = compose( [
	withSelect( mapStateToProps ),
	withDispatch( mapDispatchToProps ),
] )( PublicSettingsComponent );
