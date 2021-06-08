/**
 * WordPress dependencies.
 */
import { Component, createRef, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies.
 */
import { Button, Modal, Notice, PluginInstaller } from '../';
import Router from '../proxied-imports/router';
import Footer from '../footer';
import './style.scss';

const { Redirect, Route } = Router;

/**
 * Higher-Order Component to provide plugin management and error handling to Newspack Wizards.
 */
export default function withWizard( WrappedComponent, requiredPlugins ) {
	return class WrappedWithWizard extends Component {
		constructor( props ) {
			super( props );
			this.state = {
				complete: null,
				error: null,
				loading: requiredPlugins && requiredPlugins.length > 0 ? 1 : 0,
				quietLoading: false,
			};
			this.wrappedComponentRef = createRef();
		}

		componentDidMount = () => {
			// If there are no requiredPlugins, fire onWizardReady as soon as component mounts.
			if ( ! requiredPlugins ) {
				const instance = this.wrappedComponentRef.current;
				// eslint-disable-next-line no-unused-expressions
				instance && instance.onWizardReady && instance.onWizardReady();
			}
		};

		/**
		 * Set the error. Called by Wizards when an error occurs.
		 *
		 * @return {Promise} Resolved after state update
		 */
		setError = error => {
			return new Promise( resolve => {
				this.setState( { error: error || null }, () => resolve() );
			} );
		};

		/**
		 * Render any errors that need rendering.
		 *
		 * @return {Component} Error UI
		 */
		getError = () => {
			const { error } = this.state;
			if ( ! error ) {
				return null;
			}

			const parsedError = this.parseError( error );
			const { level } = parsedError;
			if ( 'fatal' === level ) {
				return this.getFatalError( parsedError );
			}

			return this.getErrorNotice( parsedError );
		};

		/**
		 * Get a notice-level error.
		 *
		 * @param {Error} error object already parsed by parseError
		 * @return {Component} Error notice
		 */
		getErrorNotice = error => {
			const { message } = error;
			return <Notice noticeText={ message } isError rawHTML />;
		};

		/**
		 * Get a fatal-level error.
		 *
		 * @param {Error} error object already parsed by parseError
		 * @return {Component} React object
		 */
		getFatalError = error => {
			const fallbackURL = this.getFallbackURL();
			if ( ! fallbackURL ) {
				return null;
			}
			const { message } = error;
			return (
				<Modal
					title={ __( 'Unrecoverable error' ) }
					onRequestClose={ () => ( window.location = fallbackURL ) }
				>
					<Notice noticeText={ message } isError rawHTML />
					<div className="newspack-buttons-card">
						<Button isPrimary href={ fallbackURL }>
							{ __( 'Return to dashboard' ) }
						</Button>
					</div>
				</Modal>
			);
		};

		/**
		 * Get all the relevant info out of a raw API error response.
		 *
		 * @param {Object} error error object
		 * @return {Object} Error object with relevant fields and defaults
		 */
		parseError = error => {
			const { data, message, code } = error;
			let level = 'fatal';
			if ( !! data && 'level' in data ) {
				level = data.level;
			} else if ( 'rest_invalid_param' === code ) {
				level = 'notice';
			}

			return {
				message,
				level,
			};
		};

		/**
		 * Called when plugin installation is complete. Updates state and calls onWizardReady on the wrapped component.
		 */
		pluginInstallationStatus = ( { complete } ) => {
			if ( this.state.loading ) {
				this.doneLoading();
			}
			const instance = this.wrappedComponentRef.current;
			this.setState( { complete }, () => {
				// eslint-disable-next-line no-unused-expressions
				complete && instance && instance.onWizardReady && instance.onWizardReady();
			} );
		};

		/**
		 * Begin loading.
		 */
		startLoading = quiet => {
			if ( quiet ) {
				this.setState( state => ( {
					quietLoading: state.quietLoading + 1,
				} ) );
			} else {
				this.setState( state => ( {
					loading: state.loading + 1,
				} ) );
			}
		};

		/**
		 * End loading.
		 */
		doneLoading = quiet => {
			if ( quiet ) {
				this.setState( state => ( {
					quietLoading: state.quietLoading - 1,
				} ) );
			} else {
				this.setState( state => ( {
					loading: state.loading - 1,
				} ) );
			}
		};

		/**
		 * Replacement for core apiFetch that automatically manages wizard loading UI.
		 */
		wizardApiFetch = args => {
			const { quiet } = args;
			this.startLoading( quiet );
			return new Promise( ( resolve, reject ) => {
				apiFetch( args )
					.then( response => {
						this.doneLoading( quiet );
						resolve( response );
					} )
					.catch( error => {
						this.doneLoading( quiet );
						reject( error );
					} );
			} );
		};

		/**
		 * Render a Route that checks for plugin installation requirements, and redirects to '/' when all are done.
		 *
		 * @return {void}
		 */
		pluginRequirements = () => {
			const { complete } = this.state;
			/* After all plugins are loaded, redirect to / (this could be configurable) */
			if ( complete ) {
				return <Redirect from="/plugin-requirements" to="/" />;
			}
			return (
				<Route
					path="/"
					render={ () => (
						<Fragment>
							{ complete !== null && (
								<Fragment>
									<div className="newspack-wizard__header">
										<div className="newspack-wizard__header__inner">
											<h1>
												{ requiredPlugins.length > 1
													? __( 'Required plugins' )
													: __( 'Required plugin' ) }
											</h1>
											<p>
												{ requiredPlugins.length > 1
													? __( 'This feature requires the following plugins.' )
													: __( 'This feature requires the following plugin.' ) }
											</p>
										</div>
									</div>
								</Fragment>
							) }
							<div className="newspack-wizard newspack-wizard__content">
								<PluginInstaller
									plugins={ requiredPlugins }
									onStatus={ status => this.pluginInstallationStatus( status ) }
									isSmall
								/>
							</div>
						</Fragment>
					) }
				/>
			);
		};

		getFallbackURL = () => {
			if ( typeof newspack_urls !== 'undefined' ) {
				return newspack_urls.dashboard;
			}
		};

		/**
		 * Render.
		 */
		render() {
			const { simpleFooter } = this.props;
			const { loading, quietLoading, error } = this.state;
			const loadingClasses = [
				loading ? 'newspack-wizard__is-loading' : 'newspack-wizard__is-loaded',
			];
			if ( quietLoading ) {
				loadingClasses.push( 'newspack-wizard__is-loading-quiet' );
			}
			return (
				<Fragment>
					{ this.getError() }
					<div className={ loadingClasses.join( ' ' ) }>
						<WrappedComponent
							pluginRequirements={ requiredPlugins && this.pluginRequirements() }
							clearError={ this.clearError }
							getError={ this.getError }
							errorData={ error }
							setError={ this.setError }
							isLoading={ loading }
							startLoading={ this.startLoading }
							doneLoading={ this.doneLoading }
							wizardApiFetch={ this.wizardApiFetch }
							ref={ this.wrappedComponentRef }
							{ ...this.props }
						/>
					</div>
					{ ! loading && <Footer simple={ simpleFooter } /> }
				</Fragment>
			);
		}
	};
}
