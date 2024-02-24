/* eslint-disable no-nested-ternary */

/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import {
	BaseControl,
	CheckboxControl,
	ExternalLink,
	Path,
	SVG,
	TextareaControl,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { Fragment, useEffect, useState } from '@wordpress/element';

/**
 * External dependencies
 */
import { stringify } from 'qs';

/**
 * Internal dependencies
 */
import {
	Attachment,
	InputField,
	InputValues,
	PromptOptions,
	PromptProps,
	PromptType,
	PromptOptionsBaseKey,
} from './types';
import {
	ActionCard,
	Button,
	Grid,
	ImageUpload,
	Notice,
	TextControl,
	WebPreview,
	hooks,
} from '../../../components/src';

// Note: Schema and types for the `prompt` prop is defined in Newspack Campaigns: https://github.com/Automattic/newspack-popups/blob/trunk/includes/schemas/class-prompts.php
export default function Prompt( { inFlight, prompt, setInFlight, setPrompts }: PromptProps ) {
	const [ values, setValues ] = useState< InputValues | Record< string, never > >( {} );
	const [ error, setError ] = useState< false | { message: string } >( false );
	const [ isDirty, setIsDirty ] = useState< boolean >( false );
	const [ success, setSuccess ] = useState< false | string >( false );
	const [ image, setImage ] = useState< null | Attachment >( null );
	const [ isSavingFromPreview, setIsSavingFromPreview ] = useState( false );

	useEffect( () => {
		if ( Array.isArray( prompt?.user_input_fields ) ) {
			const fields = { ...values };
			prompt.user_input_fields.forEach( ( field: InputField ) => {
				fields[ field.name ] = field.value || field.default;
			} );
			setValues( fields );
		}

		if ( prompt.featured_image_id ) {
			setInFlight( true );
			apiFetch< Attachment >( {
				path: `/wp/v2/media/${ prompt.featured_image_id }`,
			} )
				.then( ( attachment: Attachment ) => {
					if ( attachment?.source_url || attachment?.url ) {
						setImage( { url: attachment.source_url || attachment.url } );
					}
				} )
				.catch( setError )
				.finally( () => {
					setInFlight( false );
				} );
		}
	}, [ prompt ] );

	// Clear success message after a few seconds.
	useEffect( () => {
		setTimeout( () => setSuccess( false ), 5000 );
	}, [ success ] );

	const previewIcon = (
		<SVG xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
			<Path
				fillRule="evenodd"
				clipRule="evenodd"
				d="M4.5001 13C5.17092 13.3354 5.17078 13.3357 5.17066 13.3359L5.17346 13.3305C5.1767 13.3242 5.18233 13.3135 5.19036 13.2985C5.20643 13.2686 5.23209 13.2218 5.26744 13.1608C5.33819 13.0385 5.44741 12.8592 5.59589 12.6419C5.89361 12.2062 6.34485 11.624 6.95484 11.0431C8.17357 9.88241 9.99767 8.75 12.5001 8.75C15.0025 8.75 16.8266 9.88241 18.0454 11.0431C18.6554 11.624 19.1066 12.2062 19.4043 12.6419C19.5528 12.8592 19.662 13.0385 19.7328 13.1608C19.7681 13.2218 19.7938 13.2686 19.8098 13.2985C19.8179 13.3135 19.8235 13.3242 19.8267 13.3305L19.8295 13.3359C19.8294 13.3357 19.8293 13.3354 20.5001 13C21.1709 12.6646 21.1708 12.6643 21.1706 12.664L21.1702 12.6632L21.1693 12.6614L21.1667 12.6563L21.1588 12.6408C21.1522 12.6282 21.1431 12.6108 21.1315 12.5892C21.1083 12.5459 21.0749 12.4852 21.0311 12.4096C20.9437 12.2584 20.8146 12.0471 20.6428 11.7956C20.2999 11.2938 19.7823 10.626 19.0798 9.9569C17.6736 8.61759 15.4977 7.25 12.5001 7.25C9.50252 7.25 7.32663 8.61759 5.92036 9.9569C5.21785 10.626 4.70033 11.2938 4.35743 11.7956C4.1856 12.0471 4.05654 12.2584 3.96909 12.4096C3.92533 12.4852 3.89191 12.5459 3.86867 12.5892C3.85705 12.6108 3.84797 12.6282 3.84141 12.6408L3.83346 12.6563L3.8309 12.6614L3.82997 12.6632L3.82959 12.664C3.82943 12.6643 3.82928 12.6646 4.5001 13ZM12.5001 16C14.4331 16 16.0001 14.433 16.0001 12.5C16.0001 10.567 14.4331 9 12.5001 9C10.5671 9 9.0001 10.567 9.0001 12.5C9.0001 14.433 10.5671 16 12.5001 16Z"
				fill={ inFlight ? '#828282' : '#3366FF' }
			/>
		</SVG>
	);

	const getPreviewUrl = ( { options, slug }: { options: PromptOptions; slug: string } ) => {
		const { placement, trigger_type: triggerType } = options;
		const previewQueryKeys = window.newspack_engagement_wizard.preview_query_keys;
		const abbreviatedKeys = { preset: slug, values };
		const optionsKeys = Object.keys( options ) as Array< PromptOptionsBaseKey >;
		optionsKeys.forEach( key => {
			if ( previewQueryKeys.hasOwnProperty( key ) ) {
				// @ts-ignore To be fixed in the future perhaps.
				abbreviatedKeys[ previewQueryKeys[ key ] ] = options[ key ];
			}
		} );

		let previewURL = '/';
		if ( 'archives' === placement && window.newspack_engagement_wizard?.preview_archive ) {
			previewURL = window.newspack_engagement_wizard.preview_archive;
		} else if (
			( 'inline' === placement || 'scroll' === triggerType ) &&
			window &&
			window.newspack_engagement_wizard?.preview_post
		) {
			previewURL = window.newspack_engagement_wizard?.preview_post;
		}

		return `${ previewURL }?${ stringify( { ...abbreviatedKeys } ) }`;
	};

	const unblock = hooks.usePrompt(
		isDirty,
		__( 'You have unsaved changes. Discard changes?', 'newspack' )
	);

	const savePrompt = ( slug: string, data: InputValues ) => {
		return new Promise< void >( ( res, rej ) => {
			if ( unblock ) {
				unblock();
			}
			setError( false );
			setSuccess( false );
			setInFlight( true );
			apiFetch< [ PromptType ] >( {
				path: '/newspack-popups/v1/reader-activation/campaign',
				method: 'post',
				data: {
					slug,
					data,
				},
			} )
				.then( ( fetchedPrompts: Array< PromptType > ) => {
					setPrompts( fetchedPrompts );
					setSuccess( __( 'Prompt saved.', 'newspack' ) );
					setIsDirty( false );
					res();
				} )
				.catch( err => {
					setError( err );
					rej( err );
				} )
				.finally( () => {
					setInFlight( false );
				} );
		} );
	};

	const helpInfo = prompt.help_info || null;

	return (
		<ActionCard
			isMedium
			expandable
			collapse={ prompt.ready && ! isSavingFromPreview }
			title={ prompt.title }
			description={ sprintf(
				// Translators: Status of the prompt.
				__( 'Status: %s', 'newspack' ),
				isDirty
					? __( 'Unsaved changes', 'newspack' )
					: prompt.ready
					? __( 'Ready', 'newspack' )
					: __( 'Pending', 'newspack' )
			) }
			checkbox={ prompt.ready && ! isDirty ? 'checked' : 'unchecked' }
		>
			{
				<Grid columns={ 2 } gutter={ 64 } className="newspack-ras-campaign__grid">
					<div className="newspack-ras-campaign__fields">
						{ prompt.user_input_fields.map( ( field: InputField ) => (
							<Fragment key={ field.name }>
								{ 'array' === field.type && Array.isArray( field.options ) && (
									<BaseControl
										id={ `newspack-engagement-wizard__${ field.name }` }
										label={ field.label }
									>
										{ field.options.map( option => (
											<BaseControl
												key={ option.id }
												id={ `newspack-engagement-wizard__${ option.id }` }
												className="newspack-checkbox-control"
												help={ option.description }
											>
												<CheckboxControl
													disabled={ inFlight }
													label={ option.label }
													value={ option.id }
													// @ts-ignore To be fixed in the future perhaps.
													checked={ values[ field.name ]?.indexOf( option.id ) > -1 }
													onChange={ ( value: boolean ) => {
														const toUpdate = { ...values };
														// @ts-ignore To be fixed in the future perhaps.
														if ( ! value && toUpdate[ field.name ].indexOf( option.id ) > -1 ) {
															// @ts-ignore To be fixed in the future perhaps.
															toUpdate[ field.name ].value = toUpdate[ field.name ].splice(
																// @ts-ignore To be fixed in the future perhaps.
																toUpdate[ field.name ].indexOf( option.id ),
																1
															);
														}
														// @ts-ignore To be fixed in the future perhaps.
														if ( value && toUpdate[ field.name ].indexOf( option.id ) === -1 ) {
															// @ts-ignore To be fixed in the future perhaps.
															toUpdate[ field.name ].push( option.id );
														}
														setValues( toUpdate );
														setIsDirty( true );
													} }
												/>
											</BaseControl>
										) ) }
									</BaseControl>
								) }
								{ 'string' === field.type && field.max_length && 150 < field.max_length && (
									<TextareaControl
										className="newspack-textarea-control"
										label={ field.label }
										disabled={ inFlight }
										// @ts-ignore To be fixed in the future perhaps.
										help={ `${ values[ field.name ]?.length || 0 } / ${ field.max_length }` }
										onChange={ ( value: string ) => {
											// @ts-ignore There's a check for max_length above.
											if ( value.length > field.max_length ) {
												return;
											}

											const toUpdate = { ...values };
											toUpdate[ field.name ] = value;
											setValues( toUpdate );
											setIsDirty( true );
										} }
										placeholder={ typeof field.default === 'string' ? field.default : '' }
										rows={ 10 }
										// @ts-ignore TS still does not see it as a string.
										value={ typeof values[ field.name ] === 'string' ? values[ field.name ] : '' }
									/>
								) }
								{ 'string' === field.type && field.max_length && 150 >= field.max_length && (
									<TextControl
										label={ field.label }
										disabled={ inFlight }
										// @ts-ignore To be fixed in the future perhaps.
										help={ `${ values[ field.name ]?.length || 0 } / ${ field.max_length }` }
										onChange={ ( value: string ) => {
											// @ts-ignore There's a check for max_length above.
											if ( value.length > field.max_length ) {
												return;
											}

											const toUpdate = { ...values };
											toUpdate[ field.name ] = value;
											setValues( toUpdate );
											setIsDirty( true );
										} }
										placeholder={ field.default }
										value={ values[ field.name ] || '' }
									/>
								) }
								{ 'int' === field.type && 'featured_image_id' === field.name && (
									<BaseControl
										id={ `newspack-engagement-wizard__${ field.name }` }
										label={ field.label }
									>
										<ImageUpload
											buttonLabel={ __( 'Select file', 'newspack' ) }
											disabled={ inFlight }
											image={ image }
											onChange={ ( attachment: Attachment ) => {
												const toUpdate = { ...values };
												toUpdate[ field.name ] = attachment?.id || 0;
												if ( toUpdate[ field.name ] !== values[ field.name ] ) {
												}
												setValues( toUpdate );
												setIsDirty( true );
												if ( attachment?.url ) {
													setImage( attachment );
												} else {
													setImage( null );
												}
											} }
										/>
									</BaseControl>
								) }
							</Fragment>
						) ) }
						{ error && (
							<Notice
								noticeText={ error?.message || __( 'Something went wrong.', 'newspack' ) }
								isError
							/>
						) }
						{ success && <Notice noticeText={ success } isSuccess /> }
						<div className="newspack-buttons-card">
							<Button
								isPrimary
								onClick={ () => {
									setIsSavingFromPreview( false );
									savePrompt( prompt.slug, values );
								} }
								disabled={ inFlight }
							>
								{ inFlight
									? __( 'Saving…', 'newspack' )
									: sprintf(
											// Translators: Save or Update settings.
											__( '%s prompt settings', 'newspack' ),
											prompt.ready ? __( 'Update', 'newspack' ) : __( 'Save', 'newspack' )
									  ) }
							</Button>
							<WebPreview
								url={ getPreviewUrl( prompt ) }
								renderButton={ ( { showPreview }: { showPreview: () => void } ) => (
									<Button
										disabled={ inFlight }
										icon={ previewIcon }
										isSecondary
										onClick={ async () => showPreview() }
									>
										{ __( 'Preview prompt', 'newspack' ) }
									</Button>
								) }
							/>
						</div>
					</div>
					{ helpInfo && (
						<div className="newspack-ras-campaign__help">
							{ helpInfo.screenshot && <img src={ helpInfo.screenshot } alt={ prompt.title } /> }
							{ helpInfo.description && (
								<p>
									<span dangerouslySetInnerHTML={ { __html: helpInfo.description } } />{ ' ' }
									{ helpInfo.url && (
										<ExternalLink href={ helpInfo.url }>
											{ __( 'Learn more', 'newspack' ) }
										</ExternalLink>
									) }
								</p>
							) }
							{ helpInfo.recommendations && (
								<>
									<h4 className="newspack-ras-campaign__recommendation-heading">
										{ __( 'We recommend', 'newspack' ) }
									</h4>
									<ul>
										{ helpInfo.recommendations.map( ( recommendation, index ) => (
											<li key={ index }>
												<span dangerouslySetInnerHTML={ { __html: recommendation } } />
											</li>
										) ) }
									</ul>
								</>
							) }
						</div>
					) }
				</Grid>
			}
		</ActionCard>
	);
}
