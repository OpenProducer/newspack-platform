/**
 * Newspack - Dashboard
 *
 * WP Admin Newspack Dashboard page.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { createRoot, lazy, Suspense } from '@wordpress/element';

/**
 * Internal dependencies
 */
import * as Components from '../components/src';

/**
 * Internal dependencies
 */
import '../shared/js/public-path';

const pageParam =
	new URLSearchParams( window.location.search ).get( 'page' ) ?? '';
const rootElement = document.getElementById( pageParam );

const components: Record< string, any > = {
	/**
	 * `page` param with `newspack-*`.
	 */
	'newspack-dashboard': {
		label: __( 'Dashboard', 'newspack-plugin' ),
		component: lazy(
			() =>
				import(
					/* webpackChunkName: "newspack-wizards" */ './newspack/views/dashboard'
				)
		),
	},
	'newspack-settings': {
		label: __( 'Settings', 'newspack-plugin' ),
		component: lazy(
			() =>
				import(
					/* webpackChunkName: "newspack-wizards" */ './newspack/views/settings'
				)
		),
	},
	'newspack-audience': {
		label: __( 'Audience', 'newspack-plugin' ),
		component: lazy(
			() =>
				import(
					/* webpackChunkName: "audience-wizards" */ './audience/views/setup'
				)
		),
	},
	'newspack-audience-campaigns': {
		label: __( 'Audience Campaigns', 'newspack-plugin' ),
		component: lazy(
			() =>
				import(
					/* webpackChunkName: "audience-wizards" */ './audience/views/campaigns'
				)
		),
	},
	'newspack-audience-donations': {
		label: __( 'Audience Donations', 'newspack-plugin' ),
		component: lazy(
			() =>
				import(
					/* webpackChunkName: "audience-wizards" */ './audience/views/donations'
				)
		),
	},
	'newspack-audience-subscriptions': {
		label: __( 'Audience Subscriptions', 'newspack-plugin' ),
		component: lazy(
			() =>
				import(
					/* webpackChunkName: "audience-wizards" */ './audience/views/subscriptions'
				)
		),
	},
} as const;

const AdminPageLoader = ( { label }: { label: string } ) => {
	return (
		<div className="newspack-wizard__loader">
			<div>
				<Components.Waiting
					style={ {
						height: '50px',
						width: '50px',
					} }
					isCenter
				/>
				<span>
					{ label } { __( 'loading', 'newspack-plugin' ) }…
				</span>
			</div>
		</div>
	);
};

const AdminPages = () => {
	const PageComponent = components[ pageParam ].component;
	return (
		<Suspense
			fallback={
				<AdminPageLoader label={ components[ pageParam ].label } />
			}
		>
			<PageComponent />
		</Suspense>
	);
};

if ( rootElement && pageParam in components ) {
	createRoot( rootElement ).render( <AdminPages /> );
} else {
	// eslint-disable-next-line no-console
	console.error( `${ pageParam } not found!` );
}
