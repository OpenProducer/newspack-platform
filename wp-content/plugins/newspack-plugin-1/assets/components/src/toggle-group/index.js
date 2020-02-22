/**
 * Toggle Group
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies.
 */
import { ToggleControl } from '../';
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

class ToggleGroup extends Component {
	/**
	 * Render.
	 */
	render() {
		const { checked, children, description, onChange, title, className } = this.props;
		const classes = classnames( 'newspack-toggle-group', className );
		return (
			<div className={ classes }>
				<ToggleControl checked={ checked || false } onChange={ onChange } />
				<div className="newspack-toggle-group__description">
					<p className="is-dark">
						<strong>{ title }</strong>
					</p>
					<p>{ description }</p>
					{ checked && children }
				</div>
			</div>
		);
	}
}

export default ToggleGroup;
