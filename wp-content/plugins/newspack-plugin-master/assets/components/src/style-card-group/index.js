/**
 * Style Card Group
 */

/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

class StyleCardGroup extends Component {
	/**
	 * Render.
	 */
	render() {
		const { className, ...otherProps } = this.props;
		const classes = classnames( 'newspack-style-card-group', className );
		return <div className={ classes } role="group" { ...otherProps } />;
	}
}

export default StyleCardGroup;
