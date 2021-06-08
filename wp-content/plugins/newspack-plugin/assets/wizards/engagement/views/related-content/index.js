/**
 * Related content screen.
 */

/**
 * WordPress dependencies
 */
import { ExternalLink } from '@wordpress/components';
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ActionCard, Notice, TextControl, withWizardScreen } from '../../../../components/src';
import './style.scss';

/**
 * Related Content Screen
 */
class RelatedContent extends Component {
	/**
	 * Render.
	 */
	render() {
		const { onChange, relatedPostsEnabled, relatedPostsError, relatedPostsMaxAge } = this.props;

		return (
			<div className="newspack-salesforce-wizard">
				<Fragment>
					<h2>{ __( 'Recirculation Settings', 'newspack' ) }</h2>

					{ relatedPostsError && <Notice noticeText={ relatedPostsError } isError /> }

					<p>
						{ __( 'These extra settings apply to Related Posts shown by Jetpack.', 'newspack' ) }{' '}
						{
							<ExternalLink href="/wp-admin/admin.php?page=jetpack#/traffic" key="jetpack-link">
								{ __( 'Configure Related Posts in Jetpack', 'newspack' ) }
							</ExternalLink>
						}
					</p>

					{ relatedPostsEnabled ? (
						<TextControl
							className="newspack-related-content__age-input"
							help={ __(
								'If set, posts will be shown as related content only if published within the past number of months. If 0, any published post can be shown, regardless of publish date.',
								'newspack'
							) }
							label={ __( 'Maximum age of related content, in months', 'newspack' ) }
							onChange={ value => onChange( value ) }
							placeholder={ __( 'Maximum age of related content, in months' ) }
							type="number"
							value={ relatedPostsMaxAge || 0 }
						/>
					) : (
						<ActionCard
							title={ __( 'Jetpack Related Posts' ) }
							description={ __( 'Enable Related Posts via Jetpack.' ) }
							actionText={ __( 'Configure' ) }
							handoff="jetpack"
							editLink="admin.php?page=jetpack#/traffic"
						/>
					) }
				</Fragment>
			</div>
		);
	}
}

export default withWizardScreen( RelatedContent );
