/**
 * Wizard Pagination
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';

/**
 * Material UI dependencies.
 */
import ArrowBackIcon from '@material-ui/icons/ArrowBack';

/**
 * Internal dependencies.
 */
import { Button } from '../';
import Router from '../proxied-imports/router';
import './style.scss';

const { withRouter } = Router;

class WizardPagination extends Component {
	/**
	 * Render.
	 */
	render() {
		const { history, location, routes } = this.props;
		if ( ! routes || ! history || ! location ) {
			return;
		}
		const currentIndex = parseInt( routes.indexOf( location.pathname ) );
		if ( 0 === currentIndex ) {
			return <Fragment />;
		}
		return (
			<Fragment>
				<div className="newspack-wizard-pagination">
					<div className="newspack-wizard-pagination__pagination">
						{ __( 'Step' ) } { currentIndex } { __( 'of' ) } { routes.length - 1 }{' '}
					</div>
					<Button
						isLink
						className="newspack-wizard-pagination__navigation"
						onClick={ () => history.goBack() }
					>
						<ArrowBackIcon />
						{ __( 'Back' ) }
					</Button>
				</div>
			</Fragment>
		);
	}
}

export default withRouter( WizardPagination );
