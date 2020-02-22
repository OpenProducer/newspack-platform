/**
 * Newsroom
 */

/**
 * WordPress dependencies
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {
	CheckboxControl,
	SelectControl,
	TextControl,
	withWizardScreen,
} from '../../../../components/src';
import './style.scss';

/**
 * Location Setup Screen.
 */
class Newsroom extends Component {
	/**
	 * Render.
	 */
	render() {
		const { profile, updateProfile } = this.props;
		const {
			newsroom_size,
			medium,
			audience,
			content_focus,
			publication_volume,
			monthly_uniques,
			engagement_newsletters,
			engagement_social_media,
			engagement_ugc,
			engagement_subscriptions,
		} = profile || {};
		return (
			<Fragment>
				<SelectControl
					label={ __( 'Size of your newsroom' ) }
					value={ newsroom_size }
					onChange={ value => updateProfile( 'newsroom_size', value ) }
					options={ [
						{ label: __( '1-3' ), value: '1-3' },
						{ label: __( '4-10' ), value: '4-10' },
						{ label: __( '11-20' ), value: '11-20' },
						{ label: __( '21-50' ), value: '21-50' },
						{ label: __( '50-100' ), value: '50-100' },
						{ label: __( 'Over 100' ), value: '101+' },
					] }
				/>
				<SelectControl
					label={ __( 'Your publishing medium' ) }
					value={ medium }
					onChange={ value => updateProfile( 'medium', value ) }
					options={ [
						{ label: __( 'Digital only' ), value: 'digital' },
						{ label: __( 'Print only' ), value: 'print' },
						{ label: __( 'Digital and Print' ), value: 'both' },
					] }
				/>
				<SelectControl
					label={ __( 'Size of your audience' ) }
					value={ audience }
					onChange={ value => updateProfile( 'audience', value ) }
					options={ [
						{ label: __( '0-1000' ), value: '0-500' },
						{ label: __( '1,000-10,000' ), value: '1000-10000' },
						{ label: __( '10,000-100,000' ), value: '10000-100000' },
						{ label: __( '100,000-1,000,000' ), value: '100000-1000000' },
						{ label: __( 'Over 1,000,000' ), value: '1000000+' },
					] }
				/>
				<SelectControl
					label={ __( 'Content focus' ) }
					value={ content_focus }
					onChange={ value => updateProfile( 'content_focus', value ) }
					options={ [
						{ label: __( 'Local News' ), value: 'local' },
						{ label: __( 'National News' ), value: 'national' },
						{ label: __( 'Topical' ), value: 'topical' },
						{ label: __( 'Other' ), value: 'other' },
					] }
				/>
				<SelectControl
					label={ __( 'Average number of stories published monthly' ) }
					value={ publication_volume }
					onChange={ value => updateProfile( 'publication_volume', value ) }
					options={ [
						{ label: __( '1-50' ), value: '1-50' },
						{ label: __( '50-100' ), value: '50-100' },
						{ label: __( '101-500' ), value: '101-500' },
						{ label: __( 'Over 500' ), value: '500+' },
					] }
				/>
				<SelectControl
					label={ __( 'Monthly unique visitors' ) }
					value={ monthly_uniques }
					onChange={ value => updateProfile( 'monthly_uniques', value ) }
					options={ [
						{ label: __( '0-1000' ), value: '0-500' },
						{ label: __( '1,000-10,000' ), value: '1000-10000' },
						{ label: __( '10,000-100,000' ), value: '10000-100000' },
						{ label: __( '100,000-1,000,000' ), value: '100000-1000000' },
						{ label: __( 'Over 1,000,000' ), value: '1000000+' },
					] }
				/>
				<p className="is-dark newspack-setup-wizard_newsroom_audience_title">
					<strong>
						{ __( 'How do you engage with your audience? (select all that apply)' ) }
					</strong>
				</p>
				<div className="newspack-setup-wizard_newsroom_audience_checkboxes">
					<CheckboxControl
						label={ __( 'Newsletters' ) }
						checked={ engagement_newsletters }
						onChange={ value => updateProfile( 'engagement_newsletters', value ) }
					/>
					<CheckboxControl
						label={ __( 'Subscriptions' ) }
						checked={ engagement_subscriptions }
						onChange={ value => updateProfile( 'engagement_subscriptions', value ) }
					/>
					<CheckboxControl
						label={ __( 'Social Media' ) }
						checked={ engagement_social_media }
						onChange={ value => updateProfile( 'engagement_social_media', value ) }
					/>
					<CheckboxControl
						label={ __( 'User generated content' ) }
						checked={ engagement_ugc }
						onChange={ value => updateProfile( 'engagement_ugc', value ) }
					/>
				</div>
			</Fragment>
		);
	}
}

export default withWizardScreen( Newsroom );
