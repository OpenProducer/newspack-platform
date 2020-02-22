/**
 * Secondary Navigation
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * External dependencies.
 */
import classnames from 'classnames';

/**
 * Internal dependencies.
 */
import Router from '../proxied-imports/router'
import './style.scss';

const { NavLink } = Router;

/**
 * Secondary navigation.
 */
class SecondaryNavigation extends Component {
	/**
	 * Render.
	 */
	render() {
		const { items, className } = this.props;
		const classes = classnames( 'newspack-secondary-navigation', className );
		return (
			<div className={ classes }>
				<ul>
					{ items.map( ( item, key ) => (
						<li key={ key }>
							<NavLink to={ item.path } exact={ item.exact } activeClassName="selected">
								{ item.label }
							</NavLink>
						</li>
					) ) }
				</ul>
			</div>
		);
	}
}

export default SecondaryNavigation;
