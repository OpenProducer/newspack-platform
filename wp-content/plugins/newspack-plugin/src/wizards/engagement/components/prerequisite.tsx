/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { PrequisiteProps } from './types';
import { ActionCard, Button, Grid, TextControl } from '../../../components/src';
import { HANDOFF_KEY } from '../../../components/src/consts';
import type { Config, ConfigKey } from './types';

/**
 * Expandable ActionCard for RAS prerequisites checklist.
 */
export default function Prerequisite( {
	config,
	getSharedProps,
	inFlight,
	prerequisite,
	saveConfig,
}: PrequisiteProps ) {
	const { href } = prerequisite;
	const isValid = Boolean( prerequisite.active || prerequisite.is_skipped );

	// If the prerequisite is active but has empty fields, show a warning.
	const hasEmptyFields = () => {
		if ( isValid && prerequisite.fields && prerequisite.warning ) {
			const emptyValues = Object.keys( prerequisite.fields ).filter(
				fieldName => '' === config[ fieldName as keyof Config ]
			);
			if ( emptyValues.length ) {
				return prerequisite.warning;
			}
		}
		return null;
	};

	const fieldKeys = Object.keys( prerequisite.fields || {} ) as ConfigKey[];

	const renderInnerContent = () => (
		// Inner card content.
		<>
			{ prerequisite.description && (
				<p>
					{ prerequisite.description }
					{ prerequisite.help_url && (
						<>
							{ ' ' }
							<ExternalLink href={ prerequisite.help_url }>
								{ __( 'Learn more', 'newspack-plugin' ) }
							</ExternalLink>
						</>
					) }
				</p>
			) }
			{
				// Form fields.
				prerequisite.fields && (
					<Grid columns={ 2 } gutter={ 16 }>
						<div>
							{ fieldKeys.map( fieldName => {
								if ( ! prerequisite.fields || ! prerequisite.fields[ fieldName ] ) {
									return undefined;
								}
								return (
									<TextControl
										key={ fieldName }
										label={ prerequisite.fields[ fieldName ].label }
										help={ prerequisite.fields[ fieldName ].description }
										{ ...getSharedProps( fieldName, 'text' ) }
									/>
								);
							} ) }

							<Button
								variant={ 'primary' }
								onClick={ () => {
									const dataToSave: Partial< Config > = {};
									fieldKeys.forEach( fieldName => {
										if ( config[ fieldName ] ) {
											// @ts-ignore - not sure what's the issue here.
											dataToSave[ fieldName ] = config[ fieldName ];
										}
									} );
									saveConfig( dataToSave );
								} }
								disabled={ inFlight }
							>
								{ inFlight
									? __( 'Saving…', 'newspack-plugin' )
									: sprintf(
										// Translators: Save or Update settings.
										__( '%s settings', 'newspack-plugin' ),
										isValid ? __( 'Update', 'newspack-plugin' ) : __( 'Save', 'newspack-plugin' )
									) }
							</Button>
						</div>
					</Grid>
				)
			}
			{
				// Link to another settings page or update config in place.
				href && prerequisite.action_text && (
					<Grid columns={ 2 } gutter={ 16 }>
						<div>
							{ ( ! prerequisite.hasOwnProperty( 'action_enabled' ) ||
								prerequisite.action_enabled ) && (
								<Button
									variant={ 'primary' }
									onClick={ () => {
										// Set up a handoff to indicate that the user is coming from the RAS wizard page.
										if ( prerequisite.instructions ) {
											window.localStorage.setItem(
												HANDOFF_KEY,
												JSON.stringify( {
													message: sprintf(
														// Translators: %s is specific instructions for satisfying the prerequisite.
														__(
															'%1$s%2$sReturn to the Reader Activation page to complete the settings and activate%3$s.',
															'newspack-plugin'
														),
														prerequisite.instructions + ' ',
														window.newspack_engagement_wizard?.reader_activation_url
															? `<a href="${ window.newspack_engagement_wizard.reader_activation_url }">`
															: '',
														window.newspack_engagement_wizard?.reader_activation_url ? '</a>' : ''
													),
													url: href,
												} )
											);
										}

										window.location.href = href;
									} }
								>
									{ /* eslint-disable no-nested-ternary */ }
									{ ( isValid
										? __( 'Update ', 'newspack-plugin' )
										: prerequisite.fields
											? __( 'Save ', 'newspack-plugin' )
											: __( 'Configure ', 'newspack-plugin' ) ) + prerequisite.action_text }
								</Button>
							) }
							{ prerequisite.hasOwnProperty( 'action_enabled' ) && ! prerequisite.action_enabled && (
								<Button variant={ 'secondary' } disabled>
									{ prerequisite.disabled_text || prerequisite.action_text }
								</Button>
							) }
						</div>
					</Grid>
				)
			}
		</>
	);

	let status = __( 'Pending', 'newspack-plugin' );
	if ( isValid ) {
		status = `${ __( 'Ready', 'newspack-plugin' ) } ${
			prerequisite.is_skipped ? `(${ __( 'Skipped', 'newspack-plugin' ) })` : ''
		}`;
	}
	if ( prerequisite.is_unavailable ) {
		status = __( 'Unavailable', 'newspack-plugin' );
	}

	return (
		<ActionCard
			className="newspack-ras-wizard__prerequisite"
			isMedium
			expandable={ ! prerequisite.is_unavailable }
			collapse={ isValid }
			title={ prerequisite.label }
			description={ sprintf(
				/* translators: %s: Prerequisite status */
				__( 'Status: %s', 'newspack-plugin' ),
				status
			) }
			checkbox={ isValid ? 'checked' : 'unchecked' }
			notificationLevel="info"
			notification={ hasEmptyFields() }
		>
			{ prerequisite.is_unavailable ? null : renderInnerContent() }
		</ActionCard>
	);
}
