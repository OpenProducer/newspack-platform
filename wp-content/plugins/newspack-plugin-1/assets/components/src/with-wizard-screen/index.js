/**
 * Higher-Order Component to provide plugin management and error handling to Newspack Wizards.
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Button, Card, FormattedHeader, Handoff, Grid, SecondaryNavigation, TabbedNavigation } from '../';
import { buttonProps } from '../../../shared/js/';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

export default function withWizardScreen( WrappedComponent, config ) {
	return class extends Component {
		render() {
			const {
				className,
				buttonText,
				buttonAction,
				buttonDisabled,
				headerIcon,
				headerText,
				subHeaderText,
				noBackground,
				noCard,
				isWide,
				tabbedNavigation,
				secondaryNavigation,
				footer,
				notice,
				secondaryButtonText,
				secondaryButtonAction,
				secondaryButtonStyle,
				hidden,
			} = this.props;
			const classes = classnames(
				'newspack-wizard',
				className,
				hidden ? 'newspack-wizard__is-hidden' : '',
			);
			const content = <WrappedComponent { ...this.props } />;
			const retrievedButtonProps = buttonProps( buttonAction );
			return (
				<Fragment>
					{ ! hidden && (
						<Grid>
							<Card noBackground>
								{ headerText && (
									<FormattedHeader headerIcon={ headerIcon } headerText={ headerText } subHeaderText={ subHeaderText } />
								) }
							</Card>
							{ tabbedNavigation && (
								<Card noBackground>
									<TabbedNavigation items={ tabbedNavigation } />
									{ secondaryNavigation && <SecondaryNavigation items={ secondaryNavigation } /> }
								</Card>
							) }
						</Grid>
					) }
					{ !! noCard && content }
					{ ! noCard && (
						<Grid isWide={ isWide }>
							<Card className={ classes } noBackground={ noBackground }>
								{ content }
								{ ! hidden && (
									<div className="newspack-buttons-card">
										{ buttonText && buttonAction && !! retrievedButtonProps.plugin && (
											<Handoff
												isPrimary
												{ ...retrievedButtonProps }
											>
												{ buttonText }
											</Handoff>
										) }
										{ notice }
										{ buttonText && buttonAction && ! retrievedButtonProps.plugin && (
											<Button
												isPrimary={ ! buttonDisabled }
												isDefault={ !! buttonDisabled }
												disabled={ buttonDisabled }
												{ ...retrievedButtonProps }
											>
												{ buttonText }
											</Button>
										) }
										{ footer }
										{ secondaryButtonText && (
											<Button
												{ ...secondaryButtonStyle }
												isDefault
												{ ...buttonProps( secondaryButtonAction ) }
											>
												{ secondaryButtonText }
											</Button>
										) }
									</div>
								) }
							</Card>
						</Grid>
					) }
				</Fragment>
			);
		}
	};
}
