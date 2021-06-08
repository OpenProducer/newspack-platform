/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './style.scss';

const PreviewBox = ( { children } ) => (
	<div className="newspack-preview-box">
		<div className="newspack-preview-box__label">{ __( 'Preview', 'newspack' ) }</div>
		<div className="newspack-preview-box__content">{ children }</div>
	</div>
);

export default PreviewBox;
