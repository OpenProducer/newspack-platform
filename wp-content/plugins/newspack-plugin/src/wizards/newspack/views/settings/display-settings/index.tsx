/**
 * Newspack > Settings > Emails.
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import { DEFAULT_THEME_MODS } from '../constants';
import WizardsTab from '../../../../wizards-tab';
import WizardSection from '../../../../wizards-section';
import { Button, hooks, Notice, utils } from '../../../../../components/src';
import { useWizardApiFetch } from '../../../../hooks/use-wizard-api-fetch';
import Recirculation from './recirculation';
import AuthorBio from './author-bio';
import FeaturedImagePostsAll from './featured-image-posts-all';
import FeaturedImagePostsNew from './featured-image-posts-new';
import MediaCredits from './media-credits';

export default function DisplaySettings() {
	const [ data, setData ] = hooks.useObjectState< DisplaySettings >( {
		...DEFAULT_THEME_MODS,
	} );
	const [ etc, setEtc ] = hooks.useObjectState< Etc >( {
		post_count: '0',
	} );

	const [ recirculationData, setRecirculationData ] =
		hooks.useObjectState< Recirculation >( {
			relatedPostsMaxAge: 0,
			relatedPostsEnabled: false,
			relatedPostsError: null,
			relatedPostsUpdated: false,
		} );

	const { wizardApiFetch, isFetching, errorMessage } = useWizardApiFetch(
		'newspack-settings/theme-mods'
	);
	const {
		wizardApiFetch: wizardApiFetchRecirculation,
		isFetching: isFetchingRecirculation,
	} = useWizardApiFetch( 'newspack-settings/display-settings/recirculation' );

	useEffect( () => {
		wizardApiFetch< ThemeData >(
			{
				path: '/newspack/v1/wizard/newspack-setup-wizard/theme',
			},
			{
				onSuccess( { theme_mods, etc: newEtc } ) {
					setData( theme_mods );
					setEtc( newEtc );
				},
			}
		);
		wizardApiFetchRecirculation< Recirculation >(
			{
				path: '/newspack/v1/wizard/newspack-settings/related-content',
			},
			{
				onSuccess: setRecirculationData,
			}
		);
	}, [] );

	function save() {
		wizardApiFetchRecirculation(
			{
				path: '/newspack/v1/wizard/newspack-settings/related-posts-max-age',
				method: 'POST',
				updateCacheKey: {
					'/newspack/v1/wizard/newspack-settings/related-content':
						'GET',
				},
				data: recirculationData,
			},
			{
				onSuccess: setRecirculationData,
			}
		);
		if (
			data.featured_image_all_posts !== 'none' ||
			data.post_template_all_posts !== 'none'
		) {
			if (
				! utils.confirmAction(
					__(
						'Saving will overwrite existing posts, this cannot be undone. Are you sure you want to proceed?',
						'newspack-plugin'
					)
				)
			) {
				return;
			}
		}
		wizardApiFetch(
			{
				path: '/newspack/v1/wizard/newspack-setup-wizard/theme',
				method: 'POST',
				updateCacheMethods: [ 'GET' ],
				data: { theme_mods: data },
			},
			{
				onSuccess: savedData => {
					setData( {
						...savedData,
						// Strange UX behavior: if the user saves the settings with the "all posts" options selected, the settings are reset to "none".
						featured_image_all_posts: 'none',
						post_template_all_posts: 'none',
					} );
				},
			}
		);
	}

	return (
		<WizardsTab
			title={ __( 'Display Settings', 'newspack-plugin' ) }
			isFetching={ isFetching || isFetchingRecirculation }
		>
			<WizardSection title={ __( 'Recirculation', 'newspack-plugin' ) }>
				<Recirculation
					isFetching={ isFetchingRecirculation }
					update={ setRecirculationData }
					data={ recirculationData }
				/>
			</WizardSection>
			<WizardSection title={ __( 'Author Bio', 'newspack-plugin' ) }>
				<AuthorBio
					update={ setData }
					data={ data }
					isFetching={ isFetching }
				/>
			</WizardSection>
			<WizardSection
				title={ __(
					'Default Featured Image Position And Post Template',
					'newspack-plugin'
				) }
				description={ __(
					'Modify how the featured image and post template settings are applied to new posts.',
					'newspack-plugin'
				) }
			>
				<FeaturedImagePostsNew data={ data } update={ setData } />
			</WizardSection>
			<WizardSection
				title={ __(
					'Featured Image Position And Post Template For All Posts',
					'newspack-plugin'
				) }
				description={ __(
					'Modify how the featured image and post template settings are applied to existing posts. Warning: saving these options will override all posts.',
					'newspack-plugin'
				) }
			>
				<FeaturedImagePostsAll
					data={ data }
					postCount={ etc.post_count }
					update={ setData }
				/>
			</WizardSection>
			<WizardSection title={ __( 'Media Credits', 'newspack-plugin' ) }>
				<MediaCredits data={ data } update={ setData } />
			</WizardSection>
			{ errorMessage && <Notice /> }
			<div className="newspack-buttons-card">
				<Button variant="tertiary" href="/wp-admin/customize.php">
					{ __( 'Advanced Settings', 'newspack-plugin' ) }
				</Button>
				<Button variant="primary" onClick={ save }>
					{ __( 'Save', 'newspack-plugin' ) }
				</Button>
			</div>
		</WizardsTab>
	);
}
