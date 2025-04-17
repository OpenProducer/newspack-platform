/**
 * Newspack - Dashboard
 *
 * WP Admin Newspack Dashboard page.
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';
import sections from './sections';
import BrandHeader from '../../components/brand-header';
import QuickActions from '../../components/quick-actions';
import SiteStatuses from '../../components/site-statuses';
import { GlobalNotices, Notice, Wizard } from '../../../../components/src';

const {
	newspack_aux_data: { is_debug_mode: isDebugMode = false },
} = window;

function Dashboard() {
	return (
		<Fragment>
			<GlobalNotices />
			{ isDebugMode && <Notice debugMode /> }
			<Wizard
				headerText={ __( 'Newspack / Dashboard', 'newspack' ) }
				sections={ sections }
				renderAboveSections={ () => (
					<>
						<BrandHeader />
						<SiteStatuses />
						<hr />
						<QuickActions />
					</>
				) }
			/>
		</Fragment>
	);
}

export default Dashboard;
