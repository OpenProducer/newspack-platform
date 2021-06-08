/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Button, Handoff, Notice, TabbedNavigation, WizardPagination } from '../';
import { buttonProps } from '../../../shared/js/';
import './style.scss';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Higher-Order Component to provide plugin management and error handling to Newspack Wizards.
 */
export default function withWizardScreen( WrappedComponent, { hidePrimaryButton } = {} ) {
	const WrappedWithWizardScreen = props => {
		const {
			className,
			buttonText,
			buttonAction,
			buttonDisabled,
			headerText,
			subHeaderText,
			tabbedNavigation,
			secondaryButtonText,
			secondaryButtonAction,
			routes,
		} = props;
		const retrievedButtonProps = buttonProps( buttonAction );
		const retrievedSecondaryButtonProps = buttonProps( secondaryButtonAction );
		const SecondaryCTAComponent = retrievedSecondaryButtonProps.plugin ? Handoff : Button;
		const shouldRenderPrimaryButton = buttonText && buttonAction;
		const shouldRenderSecondaryButton = secondaryButtonText && secondaryButtonAction;
		const renderPrimaryButton = ( overridingProps = {} ) =>
			retrievedButtonProps.plugin ? (
				<Handoff isPrimary { ...retrievedButtonProps } { ...overridingProps }>
					{ buttonText }
				</Handoff>
			) : (
				<Button
					isPrimary={ ! buttonDisabled }
					isSecondary={ !! buttonDisabled }
					disabled={ buttonDisabled }
					// Allow overridingProps to set children.
					// eslint-disable-next-line react/no-children-prop
					children={ buttonText }
					{ ...retrievedButtonProps }
					{ ...overridingProps }
				/>
			);
		return (
			<>
				{ newspack_aux_data.is_debug_mode && (
					<Notice
						isWarning
						className="newspack-wizard__debug-mode-notice"
						noticeText={ __( 'Newspack is in debug mode.', 'newspack' ) }
					/>
				) }
				<div className="newspack-wizard__header">
					<div className="newspack-wizard__header__inner">
						{ headerText && <h1>{ headerText }</h1> }
						{ subHeaderText && <p>{ subHeaderText }</p> }
						{ tabbedNavigation && (
							<>
								<TabbedNavigation items={ tabbedNavigation } />
							</>
						) }
					</div>
					{ routes && <WizardPagination routes={ routes } /> }
				</div>

				<div className={ classnames( 'newspack-wizard newspack-wizard__content', className ) }>
					{ <WrappedComponent { ...props } renderPrimaryButton={ renderPrimaryButton } /> }
					{ ( shouldRenderPrimaryButton || shouldRenderSecondaryButton ) && (
						<div className="newspack-buttons-card">
							{ shouldRenderPrimaryButton && ! hidePrimaryButton && renderPrimaryButton() }
							{ shouldRenderSecondaryButton && (
								<SecondaryCTAComponent isSecondary { ...retrievedSecondaryButtonProps }>
									{ secondaryButtonText }
								</SecondaryCTAComponent>
							) }
						</div>
					) }
				</div>
			</>
		);
	};
	return WrappedWithWizardScreen;
}
