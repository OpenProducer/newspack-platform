/**
 * Task
 */

/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';

/**
 * Material UI dependencies.
 */
import CheckCircleIcon from '@material-ui/icons/CheckCircle';

/**
 * Internal dependencies.
 */
import { Card, Button } from '../';
import './style.scss';

/**
 * External dependencies.
 */
import classnames from 'classnames';

class Task extends Component {
	/**
	 * Render.
	 */
	render() {
		const { active, buttonText, completed, description, onClick, onDismiss, title } = this.props;
		const classes = classnames(
			'newspack-task',
			active && 'is-active',
			completed && 'is-completed'
		);
		return (
			<Card className={ classes }>
				<div className="newspack-task__task-icon">{ completed && <CheckCircleIcon /> }</div>
				<div className="newspack-task__task-description">
					<p className="is-dark">
						<strong>{ title }</strong>
					</p>
					{ ! completed && <p>{ description }</p> }
					{ completed && (
						<Button isLink onClick={ onClick }>
							{ __( 'Edit' ) }
						</Button>
					) }
				</div>
				<div className="newspack-task__task-buttons">
					{ active && (
						<Fragment>
							{ onClick && (
								<Button isPrimary onClick={ onClick }>
									{ buttonText }
								</Button>
							) }
							{ onDismiss && (
								<Button isDefault onClick={ onDismiss }>
									{ __( 'Skip' ) }
								</Button>
							) }
						</Fragment>
					) }
				</div>
			</Card>
		);
	}
}

export default Task;
