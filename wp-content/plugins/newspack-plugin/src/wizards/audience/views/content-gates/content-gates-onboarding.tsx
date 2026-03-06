/**
 * Content Gates Onboarding component.
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { __experimentalVStack as VStack } from '@wordpress/components'; // eslint-disable-line @wordpress/no-unsafe-wp-apis
import { postList, settings } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import { Card, Grid, SectionHeader } from '../../../../../packages/components/src';
import { lockDoor } from '../../../../../packages/icons';

const ContentGatesOnboarding = () => {
	return (
		<Grid columns={ 4 } noMargin>
			<VStack start={ 2 } end={ 4 } spacing={ 8 }>
				<SectionHeader
					icon={ lockDoor }
					title={ __( 'Get started with access control', 'newspack-plugin' ) }
					description={ __(
						'Set up gates to manage what content readers can access across your site. Start by selecting which content to restrict, then configure access through registered and/or paid options (including metered rules).',
						'newspack-plugin'
					) }
					pageHeader
					noMargin
				/>
				<VStack spacing={ 4 }>
					<Card
						actionType="chevron"
						isSmall
						__experimentalCoreCard
						__experimentalCoreProps={ {
							as: 'a',
							header: (
								<>
									<h3>{ __( 'Restrict all posts', 'newspack-plugin' ) }</h3>
									<p>{ __( 'All posts on your site will require access.', 'newspack-plugin' ) }</p>
								</>
							),
							href: '#/edit/new/all',
							icon: postList,
							iconBackgroundColor: true,
						} }
					/>
					<Card
						actionType="chevron"
						isSmall
						__experimentalCoreCard
						__experimentalCoreProps={ {
							as: 'a',
							header: (
								<>
									<h3>{ __( 'Choose specific content', 'newspack-plugin' ) }</h3>
									<p>{ __( 'Select which content to restrict using custom rules.', 'newspack-plugin' ) }</p>
								</>
							),
							href: '#/edit/new/custom',
							icon: settings,
							iconBackgroundColor: true,
						} }
					/>
				</VStack>
			</VStack>
		</Grid>
	);
};

export default ContentGatesOnboarding;
