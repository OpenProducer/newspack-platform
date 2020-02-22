/**
 * Dashboard Card
 */

/**
 * WordPress dependencies.
 */
import { Component, Fragment } from '@wordpress/element';

/**
 * Material UI dependencies.
 */
import AccountBalanceWalletIcon from '@material-ui/icons/AccountBalanceWallet';
import CheckCircleIcon from '@material-ui/icons/CheckCircle';
import ChevronRightIcon from '@material-ui/icons/ChevronRight';
import FeaturedVideoIcon from '@material-ui/icons/FeaturedVideo';
import ForumIcon from '@material-ui/icons/Forum';
import HealingIcon from '@material-ui/icons/Healing';
import SearchIcon from '@material-ui/icons/Search';
import SpeedIcon from '@material-ui/icons/Speed';
import SyncAltIcon from '@material-ui/icons/SyncAlt';
import TrendingUpIcon from '@material-ui/icons/TrendingUp';
import WebIcon from '@material-ui/icons/Web';
import WidgetsIcon from '@material-ui/icons/Widgets';

/**
 * Internal dependencies.
 */
import { Card } from '../../../components/src';

/**
 * External dependencies.
 */
import classNames from 'classnames';

class DashboardCard extends Component {
	/**
	 * Render.
	 */
	render() {
		const { name, description, slug, url, status } = this.props;
		const classes = classNames( 'newspack-dashboard-card', slug, status );
		const iconMap = {
			'site-design': <WebIcon />,
			'reader-revenue': <AccountBalanceWalletIcon />,
			'advertising': <FeaturedVideoIcon />,
			'syndication': <SyncAltIcon />,
			'analytics': <TrendingUpIcon />,
			'performance': <SpeedIcon />,
			'seo': <SearchIcon />,
			'health-check': <HealingIcon />,
			'engagement': <ForumIcon />,
		};
		const contents = (
			<div className="newspack-dashboard-card__contents">
				{ iconMap[ slug ] || <WidgetsIcon /> }
				<div className="newspack-dashboard-card__header">
					<h2>{ name }</h2>
					<p>{ description }</p>
				</div>
			</div>
		);

		if ( 'disabled' === status ) {
			return (
				<Card className={ classes }>
					<div className="newspack-dashboard-card__disabled-link">{ contents }</div>
				</Card>
			);
		} else {
			return (
				<Card className={ classes }>
					<a href={ url }>
						{ contents }
						{ 'completed' === status ? <CheckCircleIcon /> : <ChevronRightIcon /> }
					</a>
				</Card>
			);
		}
	}
}
export default DashboardCard;
