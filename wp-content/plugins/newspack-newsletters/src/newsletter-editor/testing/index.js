/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Fragment, useState } from '@wordpress/element';
import { Button, TextControl } from '@wordpress/components';
import { hasValidEmail } from '../utils';

/**
 * Internal dependencies
 */
import withApiHandler from '../../components/with-api-handler';

export default compose( [
	withApiHandler(),
	withSelect( select => {
		const { getCurrentPostId } = select( 'core/editor' );
		return { postId: getCurrentPostId() };
	} ),
	withDispatch( dispatch => {
		const { savePost } = dispatch( 'core/editor' );
		return {
			savePost,
		};
	} ),
] )( ( { apiFetchWithErrorHandling, inFlight, postId, savePost, setInFlightForAsync } ) => {
	const [ testEmail, setTestEmail ] = useState( '' );
	const sendTestEmail = async () => {
		setInFlightForAsync();
		await savePost();
		const params = {
			path: `/newspack-newsletters/v1/mailchimp/${ postId }/test`,
			data: {
				test_email: testEmail,
			},
			method: 'POST',
		};
		apiFetchWithErrorHandling( params );
	};
	return (
		<Fragment>
			<TextControl
				label={ __( 'Send a test to', 'newspack-newsletters' ) }
				value={ testEmail }
				type="email"
				onChange={ setTestEmail }
				help={ __( 'Use commas to separate multiple emails.', 'newspack-newsletters' ) }
			/>
			<Button
				isPrimary
				onClick={ sendTestEmail }
				disabled={ inFlight || ! hasValidEmail( testEmail ) }
			>
				{ __( 'Send a Test Email', 'newspack-newsletters' ) }
			</Button>
		</Fragment>
	);
} );
