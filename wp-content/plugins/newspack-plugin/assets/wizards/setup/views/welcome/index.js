/**
 * External dependencies.
 */
import { omit, times } from 'lodash';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { useRef, useEffect, useState } from '@wordpress/element';
import { Icon, check, info } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import {
	ActionCard,
	Button,
	Card,
	NewspackLogo,
	ProgressBar,
	withWizardScreen,
	CheckboxControl,
} from '../../../../components/src';
import Router from '../../../../components/src/proxied-imports/router';

const { useHistory } = Router;
const POST_COUNT = newspack_aux_data.is_e2e ? 12 : 40;
const STARTER_CONTENT_REQUEST_COUNT = POST_COUNT + 3;

const REQUIRED_SOFTWARE_SLUGS = [
	'jetpack',
	'amp',
	'pwa',
	'wordpress-seo',
	'woocommerce',
	'google-site-kit',
	'newspack-blocks',
	'newspack-newsletters',
	'newspack-theme',
];

const ERROR_TYPES = {
	plugin_configuration: { message: __( 'Installation', 'newspack' ) },
	starter_content: { message: __( 'Demo content', 'newspack' ) },
};

const starterContentFetch = endpoint =>
	apiFetch( {
		path: `/newspack/v1/wizard/newspack-setup-wizard/starter-content/${ endpoint }`,
		method: 'post',
	} );

const Welcome = ( { buttonAction } ) => {
	const [ installationProgress, setInstallationProgress ] = useState( 0 );
	const [ softwareInfo, setSoftwareInfo ] = useState( [] );
	const [ shouldInstallStarterContent, setShouldInstallStarterContent ] = useState( true );
	const [ errors, setErrors ] = useState( [] );
	const addError = errorInfo => error =>
		setErrors( _errors => [ ..._errors, { ...errorInfo, error } ] );

	const total =
		( shouldInstallStarterContent ? STARTER_CONTENT_REQUEST_COUNT : 0 ) +
		REQUIRED_SOFTWARE_SLUGS.length;

	useEffect( () => {
		document.body.classList.add( 'newspack_page_newspack-setup-wizard__welcome' );

		Promise.all(
			REQUIRED_SOFTWARE_SLUGS.map( item =>
				apiFetch( { path: `/newspack/v1/plugins/${ item }` } ).then( res =>
					setSoftwareInfo( _softwareInfo => [ ..._softwareInfo, res ] )
				)
			)
		);

		return () => document.body.classList.remove( 'newspack_page_newspack-setup-wizard__welcome' );
	}, [] );

	const increment = () => setInstallationProgress( progress => progress + 1 );

	const install = async () => {
		// Reset state.
		setErrors( [] );
		setInstallationProgress( 0 );
		// Wait 1ms to avoid an immediate "done" state if there's no need to install anything.
		await new Promise( resolve => setTimeout( resolve, 1 ) );
		// Plugins and theme.
		const softwarePromises = softwareInfo.map( item => {
			if ( item.Status === 'active' ) {
				increment();
				return () => Promise.resolve();
			}
			return () =>
				apiFetch( {
					path: `/newspack/v1/plugins/${ item.Slug }/configure/`,
					method: 'POST',
				} )
					.then( increment )
					.catch(
						addError( {
							info: ERROR_TYPES.plugin_configuration,
							item: `${ __( 'Failed to install', 'newspack' ) } ${ item.Name }`,
						} )
					);
		} );
		for ( let i = 0; i < softwarePromises.length; i++ ) {
			await softwarePromises[ i ]();
		}

		if ( shouldInstallStarterContent ) {
			// Starter content.
			await starterContentFetch( `categories` )
				.then( increment )
				.catch(
					addError( {
						info: ERROR_TYPES.starter_content,
						item: __( 'Failed to create the categories.', 'newspack' ),
					} )
				);
			await Promise.allSettled(
				times( POST_COUNT, n =>
					starterContentFetch( `post/${ n }` )
						.then( increment )
						.catch(
							addError( {
								info: ERROR_TYPES.starter_content,
								item: __( 'Failed to create a post.', 'newspack' ),
							} )
						)
				)
			);
			await starterContentFetch( `homepage` )
				.then( increment )
				.catch(
					addError( {
						info: ERROR_TYPES.starter_content,
						item: __( 'Failed to create the homepage.', 'newspack' ),
					} )
				);
			await starterContentFetch( `theme` )
				.then( increment )
				.catch(
					addError( {
						info: ERROR_TYPES.starter_content,
						item: __( 'Failed to activate the theme.', 'newspack' ),
					} )
				);
		}
	};

	const history = useHistory();
	const nextRouteAddress = buttonAction.href;
	const hasErrors = errors.length > 0;
	const isInit = installationProgress === 0;
	const isDone = installationProgress === total;
	const redirectCounterRef = useRef();

	const REDIRECT_COUNTER_DURATION = 5;
	const [ redirectCounter, setRedirectCounter ] = useState( REDIRECT_COUNTER_DURATION );
	useEffect( () => {
		if ( redirectCounter === 0 ) {
			clearInterval( redirectCounterRef.current );
			history.push( nextRouteAddress.replace( '#', '' ) );
		}
	}, [ redirectCounter ] );

	useEffect( () => {
		if ( isDone && redirectCounter === REDIRECT_COUNTER_DURATION ) {
			// Trigger redirect countdown.
			redirectCounterRef.current = setInterval( () => {
				setRedirectCounter( counter => counter - 1 );
			}, 1000 );
		}
	}, [ isDone, redirectCounter ] );

	const getHeadingText = () => {
		if ( hasErrors ) {
			return __( 'Installation error', 'newspack' );
		}
		if ( isInit ) {
			return __( 'Welcome to WordPress for your Newsroom!', 'newspack' );
		}
		if ( isDone ) {
			return __( 'Installation complete', 'newspack' );
		}
		return __( 'Installing…', 'newspack' );
	};

	const getInfoText = () => {
		if ( hasErrors ) {
			return __(
				'There has been an error during the installation. Please retry or manually install required plugins to continue with the configuration of your Newspack site.',
				'newspack'
			);
		}
		if ( isInit ) {
			return __(
				'We will help you get set up by installing the most relevant plugins first before requiring a few details from you in order to build your Newspack site.',
				'newspack'
			);
		}
		if ( isDone ) {
			return __( 'Click the button below to start configuring your Newspack site.', 'newspack' );
		}
		if ( shouldInstallStarterContent ) {
			return __(
				'We are now installing core plugins and pre-populating your site with categories and placeholder stories to help you pre-configure it. All placeholder content can be deleted later.',
				'newspack'
			);
		}
		return __( 'We are now installing core plugins.', 'newspack' );
	};

	const getHeadingIcon = () => {
		if ( hasErrors ) {
			return <Icon className="newspack--error" icon={ info } />;
		}
		if ( isDone ) {
			return <Icon className="newspack--success" icon={ check } />;
		}
	};

	const renderErrorBox = ( error, i ) => (
		<ActionCard
			isSmall
			key={ i }
			title={ error.info.message + ': ' + error.item }
			actionText={ __( 'Retry', 'newspack' ) }
			onClick={ install }
		/>
	);

	return (
		<>
			<div className="newspack-logo__wrapper">
				<NewspackLogo centered height={ 72 } />
			</div>
			<Card
				isMedium
				className={ errors.length === 0 && installationProgress > 0 && ! isDone ? 'loading' : null }
			>
				<h1>
					{ getHeadingIcon() }
					{ getHeadingText() }
				</h1>
				{ errors.length === 0 && installationProgress > 0 ? (
					<ProgressBar completed={ installationProgress } total={ total } />
				) : null }
				<p>
					{ getInfoText() }
					{ isDone && (
						<>
							<br />
							<i>
								{ __( 'Automatic redirection in', 'newspack' ) } { redirectCounter }{' '}
								{ __( 'seconds…', 'newspack' ) }
							</i>
						</>
					) }
				</p>

				{ errors.length ? errors.map( renderErrorBox ) : null }
				{ ( isInit || isDone ) && (
					<Card noBorder className="newspack-card__footer">
						{ isInit ? (
							<CheckboxControl
								checked={ shouldInstallStarterContent }
								label={ __( 'Install demo content', 'newspack' ) }
								onChange={ setShouldInstallStarterContent }
							/>
						) : (
							<div />
						) }
						<div>
							<Button
								disabled={ REQUIRED_SOFTWARE_SLUGS.length !== softwareInfo.length }
								isPrimary
								onClick={ isInit ? install : null }
								href={ isDone ? nextRouteAddress : null }
							>
								{ isInit ? __( 'Get Started', 'newspack' ) : __( 'Continue', 'newspack' ) }
							</Button>
						</div>
					</Card>
				) }
			</Card>
		</>
	);
};

const WelcomeWizardScreen = withWizardScreen( Welcome );
// eslint-disable-next-line react/display-name
export default props => (
	<WelcomeWizardScreen { ...omit( props, [ 'routes', 'headerText', 'buttonText' ] ) } />
);
