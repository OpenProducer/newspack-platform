/**
 * Popups screen.
 */

/**
 * WordPress dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import { __, _n, sprintf } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { Button, Notice, PluginInstaller, withWizardScreen } from '../../../../components/src';
import PopupActionCard from './components/popup-action-card';
import './style.scss';

/**
 * Popups Screen
 */
class Popups extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			pluginRequirementsMet: false,
		};
	}

	/**
	 * Render.
	 */
	render() {
		const { pluginRequirementsMet } = this.state;
		const { deletePopup, popups, setSiteWideDefaultPopup, setCategoriesForPopup } = this.props;
		const hasPopups = popups && popups.length > 0;
		if ( ! pluginRequirementsMet ) {
			return (
				<PluginInstaller
					plugins={ [ 'newspack-popups' ] }
					onStatus={ ( { complete } ) => this.setState( { pluginRequirementsMet: complete } ) }
				/>
			);
		}
		const popupsWithSiteSitewideFirst = [
			...popups.filter( popup => popup.sitewide_default ),
			...popups.filter( popup => ! popup.sitewide_default ),
		];

		const inactivePopupCount = popups.reduce(
			( accumulator, popup ) =>
				accumulator + ( ! popup.categories.length && ! popup.sitewide_default ? 1 : 0 ),
			0
		);

		return (
			<Fragment>
				{ inactivePopupCount > 0 && (
					<Notice
						noticeText={ sprintf(
							'You have %d inactive %s.',
							inactivePopupCount,
							_n( 'popup', 'popups', inactivePopupCount, 'Popups', 'newspack' ),
							'newspack'
						) }
						isWarning
					/>
				) }
				{ hasPopups && (
					<Fragment>
						<h2>{ __( 'Manage Pop-ups' ) }</h2>
						{ popupsWithSiteSitewideFirst.map( popup => (
							<PopupActionCard
								key={ popup.id }
								popup={ popup }
								setCategoriesForPopup={ setCategoriesForPopup }
								setSiteWideDefaultPopup={ setSiteWideDefaultPopup }
								deletePopup={ deletePopup }
							/>
						) ) }
					</Fragment>
				) }
				{ ! hasPopups && <p>{ __( 'No Pop-ups have been created yet.', 'newspack' ) }</p> }
				<div className="newspack-buttons-card">
					<Button href="/wp-admin/post-new.php?post_type=newspack_popups_cpt" isPrimary>
						{ hasPopups
							? __( 'Add new Pop-up', 'newspack' )
							: __( 'Add first Pop-up', 'newspack' ) }
					</Button>
				</div>
			</Fragment>
		);
	}
}

export default withWizardScreen( Popups );
