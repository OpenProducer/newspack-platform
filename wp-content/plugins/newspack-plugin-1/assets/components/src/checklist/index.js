/**
 * Checklist
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component, Children, cloneElement } from '@wordpress/element';

/**
 * Material UI dependencies.
 */
import ExpandLessIcon from '@material-ui/icons/ExpandLess';
import ExpandMoreIcon from '@material-ui/icons/ExpandMore';

/**
 * Internal dependencies.
 */
import { Button, Card, ProgressBar } from '../';
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

class Checklist extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			hideCompleted: false,
		};
	}

	/**
	 * Render.
	 */
	render() {
		const { className, children, progressBarText, ...otherProps } = this.props;
		const { hideCompleted } = this.state;
		const completedLabel = hideCompleted ? __( 'Show completed' ) : __( 'Hide completed' );
		const completedIcon = hideCompleted ? <ExpandMoreIcon /> : <ExpandLessIcon />;
		const classes = classnames(
			'newspack-checklist',
			className,
			hideCompleted && 'is-hide-completed'
		);
		const completedCount = Children.toArray( children ).reduce( ( completedCount, child ) => {
			return completedCount + ( child.props.completed ? 1 : 0 );
		}, 0 );
		return (
			<Card className={ classes } { ...otherProps }>
				<div className="newspack-checklist__wrapper">
					<div className="newspack-checklist__header">
						<div className="newspack-checklist__progress-bar">
							<ProgressBar
								completed={ completedCount }
								total={ Children.count( children ) }
								displayFraction
								label={ progressBarText }
							/>
						</div>
						<div className="newspack-checklist__header-action">
							<label htmlFor="newspack-checklist__header-action">{ completedLabel }</label>
							<Button
								id="newspack-checklist__header-action"
								onClick={ () => this.setState( { hideCompleted: ! hideCompleted } ) }
							>
								{ completedIcon }
							</Button>
						</div>
					</div>
					<div className="newspack-checklist__tasks">{ children }</div>
				</div>
			</Card>
		);
	}
}

export default Checklist;
