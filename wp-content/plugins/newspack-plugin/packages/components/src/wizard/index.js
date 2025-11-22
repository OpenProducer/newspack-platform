/**
 * External dependencies.
 */
import classnames from 'classnames';

/**
 * WordPress dependencies.
 */
import { useSelect } from '@wordpress/data';
import { useState, forwardRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { category } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import { Footer, Notice, Button, NewspackIcon, TabbedNavigation, PluginInstaller, HandoffMessage } from '../';
import Router from '../proxied-imports/router';
import registerStore, { WIZARD_STORE_NAMESPACE } from './store';
import WizardError from './components/WizardError';

registerStore();

const { HashRouter, Redirect, Route, Switch } = Router;

/**
 * @typedef  {Object}     WizardProps
 * @property {string}     headerText                The header text.
 * @property {string}     [subHeaderText]           The sub-header text, optional.
 * @property {string}     [apiSlug]                 The API slug, optional.
 * @property {string}     [className]               CSS classes, optional.
 * @property {any[]}      sections                  Array of sections.
 * @property {boolean}    [hasSimpleFooter]         Indicates if a simple footer is used, optional.
 * @property {() => void} [renderAboveSections]     Function to render content above sections, optional.
 * @property {string[]}   [requiredPlugins]         Array of required plugin strings, optional.
 * @property {boolean}    [isInitialFetchTriggered] Indicates if the initial fetch should be triggered, optional.
 */

/**
 * Wizard Component
 *
 * Provides a tabbed UI with history.
 *
 * @param {WizardProps} props
 * @return {JSX.Element} Wizard component
 */
const Wizard = (
	{
		sections = [],
		headerText,
		apiSlug,
		subHeaderText,
		hasSimpleFooter,
		className,
		renderAboveSections,
		requiredPlugins = [],
		isInitialFetchTriggered = true,
	},
	ref
) => {
	const isLoading = useSelect( select => select( WIZARD_STORE_NAMESPACE ).isLoading() );
	const isQuietLoading = useSelect( select => select( WIZARD_STORE_NAMESPACE ).isQuietLoading() );

	// Trigger initial data fetch. Some sections might not use the wizard data,
	// but for consistency, fetching is triggered regardless of the section.
	useSelect( select => isInitialFetchTriggered && select( WIZARD_STORE_NAMESPACE ).getWizardAPIData( apiSlug ) );

	let displayedSections = sections.filter( section => ! section.isHidden );

	const [ pluginRequirementsSatisfied, setPluginRequirementsSatisfied ] = useState( requiredPlugins.length === 0 );
	if ( ! pluginRequirementsSatisfied ) {
		headerText = requiredPlugins.length > 1 ? __( 'Required plugins', 'newspack-plugin' ) : __( 'Required plugin', 'newspack-plugin' );
		displayedSections = [
			{
				path: '/',
				render: () => (
					<PluginInstaller plugins={ requiredPlugins } onStatus={ ( { complete } ) => setPluginRequirementsSatisfied( complete ) } />
				),
			},
		];
	}

	const urlWithoutHash = window.location.href.split( '#' )[ 0 ];

	return (
		<div ref={ ref }>
			<div
				className={ classnames( isLoading ? 'newspack-wizard__is-loading' : 'newspack-wizard__is-loaded', {
					'newspack-wizard__is-loading-quiet': isQuietLoading,
				} ) }
			>
				<HashRouter hashType="slash">
					{ newspack_aux_data.is_debug_mode && <Notice debugMode /> }
					<div className="bg-white">
						<div className="newspack-wizard__header__inner">
							<div className="newspack-wizard__title">
								{ newspack_urls.dashboard !== urlWithoutHash ? (
									<Button
										isLink
										href={ newspack_urls.dashboard }
										label={ __( 'Return to Dashboard', 'newspack-plugin' ) }
										showTooltip={ true }
										icon={ category }
										iconSize={ 36 }
									>
										<NewspackIcon size={ 36 } />
									</Button>
								) : (
									<NewspackIcon size={ 36 } />
								) }
								<div>
									{ headerText && <h2>{ headerText }</h2> }
									{ subHeaderText && <span>{ subHeaderText }</span> }
								</div>
							</div>
						</div>
					</div>

					{ displayedSections.length > 1 && (
						<TabbedNavigation items={ displayedSections }>
							<WizardError />
						</TabbedNavigation>
					) }
					<HandoffMessage />

					<Switch>
						{ displayedSections.map( ( section, index ) => {
							const SectionComponent = section.render;
							return (
								<Route key={ index } exact={ section.exact ?? false } path={ section.path }>
									<div className={ classnames( 'newspack-wizard__content', className ) }>
										{ 'function' === typeof renderAboveSections ? renderAboveSections() : null }
										<SectionComponent />
									</div>
								</Route>
							);
						} ) }
						<Redirect to={ displayedSections[ 0 ].path } />
					</Switch>
				</HashRouter>
			</div>
			{ ! isLoading && <Footer simple={ hasSimpleFooter } /> }
		</div>
	);
};

export default forwardRef( Wizard );
