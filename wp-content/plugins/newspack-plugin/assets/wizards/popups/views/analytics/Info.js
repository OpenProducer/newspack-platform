/**
 * External dependencies.
 */
import classnames from 'classnames';
import { unescape } from 'lodash';
import humanNumber from 'human-number';

/**
 * WordPress dependencies.
 */
import { Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies.
 */
import { Notice } from '../../../../components/src';

const formatPercentage = num => `${ String( ( num * 100 ).toFixed( 2 ) ).replace( /\.00$/, '' ) }%`;

const Info = ( { keyMetrics, filtersState, labelFilters, isLoading, postEditLink } ) => {
	const nameFilter =
		filtersState.event_label_id !== '' &&
		labelFilters.find( ( { value } ) => value === filtersState.event_label_id );

	const { seen, form_submissions, link_clicks } = keyMetrics;

	const hasConversionRate = form_submissions >= 0 && seen > 0;
	const hasClickThroughRate = link_clicks >= 0 && seen > 0;
	const notApplicable = __( 'n/a', 'newspack' );

	return (
		<div className="newspack-campaigns-wizard-analytics__info">
			<h2>
				{ nameFilter ? `${ nameFilter.label }:` : __( 'All:', 'newspack' ) }
				{ postEditLink && (
					<Fragment>
						{' '}
						(<a href={ unescape( postEditLink ) }>edit</a>)
					</Fragment>
				) }
			</h2>
			<div className="newspack-campaigns-wizard-analytics__info__sections">
				{ [
					{ label: __( 'Seen', 'newspack' ), value: humanNumber( seen ), withSeparator: true },
					{
						label: __( 'Conversion Rate', 'newspack' ),
						value: hasConversionRate ? formatPercentage( form_submissions / seen ) : notApplicable,
					},
					{
						label: __( 'Form Submissions', 'newspack' ),
						value: hasConversionRate ? form_submissions : notApplicable,
						withSeparator: true,
					},
					{
						label: __( 'Click-through Rate', 'newspack' ),
						value: hasClickThroughRate ? formatPercentage( link_clicks / seen ) : notApplicable,
					},
					{
						label: __( 'Link Clicks', 'newspack' ),
						value: hasClickThroughRate ? link_clicks : notApplicable,
					},
				].map( ( section, i ) => (
					<div
						className={ classnames(
							'newspack-campaigns-wizard-analytics__info__sections__section',
							{
								'newspack-campaigns-wizard-analytics__info__sections__section--with-separator':
									section.withSeparator,
								'newspack-campaigns-wizard-analytics__info__sections__section--dimmed':
									! isLoading && section.value === notApplicable,
							}
						) }
						key={ i }
					>
						<h2>{ isLoading ? '-' : section.value }</h2>
						<span>{ section.label }</span>
					</div>
				) ) }
			</div>
			{ ! nameFilter && labelFilters.length !== 1 && (
				<Notice
					noticeText={ __(
						'These are aggregated metrics for multiple campaigns. Some of them might not have links or forms, which can skew the displayed rates.',
						'newspack'
					) }
					isWarning
				/>
			) }
		</div>
	);
};

export default Info;
