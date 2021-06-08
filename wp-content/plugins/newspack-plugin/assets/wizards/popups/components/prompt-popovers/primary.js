/**
 * Primary PromptActionCard Popover.
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';
import { MenuItem } from '@wordpress/components';
import { ESCAPE } from '@wordpress/keycodes';

/**
 * Internal dependencies.
 */
import { Popover } from '../../../../components/src';
import './style.scss';

const PrimaryPromptPopover = ( {
	deletePopup,
	prompt,
	previewPopup,
	onFocusOutside,
	publishPopup,
	unpublishPopup,
} ) => {
	const { id, edit_link: editLink, status } = prompt;
	const isPublished = 'publish' === status;
	return (
		<Popover
			position="bottom left"
			onFocusOutside={ onFocusOutside }
			onKeyDown={ event => ESCAPE === event.keyCode && onFocusOutside() }
			className="newspack-popover__campaigns__primary-popover"
		>
			<MenuItem onClick={ () => onFocusOutside() } className="screen-reader-text">
				{ __( 'Close Popover', 'newspack' ) }
			</MenuItem>
			<MenuItem
				onClick={ () => {
					onFocusOutside();
					previewPopup( prompt );
				} }
				className="newspack-button"
			>
				{ __( 'Preview', 'newspack' ) }
			</MenuItem>
			<MenuItem href={ decodeEntities( editLink ) } className="newspack-button" isLink>
				{ __( 'Edit', 'newspack' ) }
			</MenuItem>
			{ ! isPublished && (
				<MenuItem
					onClick={ () => {
						onFocusOutside();
						publishPopup( id );
					} }
					className="newspack-button"
				>
					{ __( 'Activate', 'newspack' ) }
				</MenuItem>
			) }
			{ isPublished && (
				<MenuItem
					onClick={ () => {
						onFocusOutside();
						unpublishPopup( id );
					} }
					className="newspack-button"
				>
					{ __( 'Deactivate', 'newspack' ) }
				</MenuItem>
			) }
			<MenuItem onClick={ () => deletePopup( id ) } className="newspack-button">
				{ __( 'Delete', 'newspack' ) }
			</MenuItem>
			<div className="newspack-popover__campaigns__info">
				{ __( 'ID:', 'newspack' ) } { id }
			</div>
		</Popover>
	);
};
export default PrimaryPromptPopover;
