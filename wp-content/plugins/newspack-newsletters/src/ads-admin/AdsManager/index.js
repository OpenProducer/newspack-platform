/**
 * WordPress dependencies
 */
import { useEffect, useState, Fragment } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { format, isInTheFuture } from '@wordpress/date';

/**
 * External dependencies
 */
import {
	Card,
	Grid,
	Button,
	FormattedHeader,
	ActionCard,
	Waiting,
	ActionCardSections,
} from 'newspack-components';
import { Modal } from '@wordpress/components';
import classnames from 'classnames';
import HeaderIcon from '@material-ui/icons/FeaturedVideo';

/**
 * Internal dependencies
 */
import { NEWSLETTER_AD_CPT_SLUG } from '../../utils/consts';
import { isAdActive } from '../utils';
import './style.scss';

const AdCard = ( { adPost, deleteAd } ) => {
	const [ modalVisible, setModalVisible ] = useState( false );
	const title = adPost.title.rendered;
	let isExpired = false;
	const { expiry_date } = adPost.meta;
	if ( expiry_date ) {
		isExpired = ! isInTheFuture( expiry_date );
	}
	let description = null;
	if ( expiry_date ) {
		const formattedExpiryDate = format( 'M j Y', expiry_date );
		description = isExpired
			? `${ __( 'Expired', 'newspack-newsletters' ) } ${ formattedExpiryDate }.`
			: `${ __( 'Will expire', 'newspack-newsletters' ) } ${ formattedExpiryDate }.`;
	} else if ( ! isExpired ) {
		description = __( 'No expiry date set.', 'newspack-newsletters' );
	}
	return (
		<Fragment>
			<ActionCard
				isSmall
				className={ classnames( {
					'newspack-card__is-primary': ! isExpired && adPost.status === 'publish',
					'newspack-card__is-secondary': ! isExpired && adPost.status !== 'publish',
					'newspack-card__is-disabled': isExpired,
				} ) }
				key={ adPost.id }
				title={ title.length ? title : __( '(no title)', 'newspack-newsletters' ) }
				actionText={ __( 'Edit', 'newspack-newsletters' ) }
				href={ `/wp-admin/post.php?post=${ adPost.id }&action=edit` }
				secondaryActionText={ __( 'Delete', 'newspack-newsletters' ) }
				onSecondaryActionClick={ e => {
					setModalVisible( true );
					e.preventDefault();
					return false;
				} }
				description={ description }
			/>
			{ modalVisible && (
				<Modal
					className="newspack-newsletters__modal"
					title={ __( 'Are you sure you want to delete this ad?', 'newspack-newsletters' ) }
					onRequestClose={ () => setModalVisible( false ) }
				>
					<Button isPrimary onClick={ () => deleteAd( adPost.id ) }>
						{ __( 'Delete', 'newspack-newsletters' ) }
					</Button>
					<Button isSecondary onClick={ () => setModalVisible( false ) }>
						{ __( 'Skip', 'newspack-newsletters' ) }
					</Button>
				</Modal>
			) }
		</Fragment>
	);
};

const AdsManager = () => {
	const [ inFlight, setInFlight ] = useState( true );
	const [ allAds, setAllAds ] = useState( [] );
	const retrieveAds = () => {
		apiFetch( { path: `/wp/v2/${ NEWSLETTER_AD_CPT_SLUG }?status=publish,future,draft` } ).then(
			response => {
				setAllAds( response );
				setInFlight( false );
			}
		);
	};
	useEffect(() => {
		retrieveAds();
	}, []);

	const deleteAd = adId => {
		setInFlight( true );
		apiFetch( { path: `/wp/v2/${ NEWSLETTER_AD_CPT_SLUG }/${ adId }`, method: 'DELETE' } ).then(
			() => retrieveAds()
		);
	};

	const activeAds = allAds.filter( isAdActive );
	const expiredAds = allAds.filter( ad => ! isAdActive( ad ) && ad.status !== 'draft' );
	const draftAds = allAds.filter( ad => ! isAdActive( ad ) && ad.status === 'draft' );

	return (
		<Grid>
			<FormattedHeader
				headerIcon={ <HeaderIcon /> }
				headerText={ __( 'Newsletter Ads', 'newspack-newsletters' ) }
				subHeaderText={ __(
					'Monetize your newsletters through self-serve ads.',
					'newspack-newsletters'
				) }
			/>
			<Card>
				{ inFlight ? (
					<div className="newspack-newsletters-ads__waiting">
						<Waiting />
					</div>
				) : (
					<Fragment>
						<ActionCardSections
							sections={ [
								{ key: 'active', label: __( 'Active', 'newspack-newsletters' ), items: activeAds },
								{ key: 'draft', label: __( 'Draft', 'newspack-newsletters' ), items: draftAds },
								{
									key: 'expired',
									label: __( 'Expired', 'newspack-newsletters' ),
									items: expiredAds,
								},
							] }
							renderCard={ ad => <AdCard adPost={ ad } deleteAd={ deleteAd } /> }
							emptyMessage={ __( 'No ads have been created yet.', 'newspack-newsletters' ) }
						/>
					</Fragment>
				) }

				<div className="newspack-buttons-card">
					<Button isPrimary href={ `/wp-admin/post-new.php?post_type=${ NEWSLETTER_AD_CPT_SLUG }` }>
						{ __( 'Create new ad', 'newspack-newsletters' ) }
					</Button>
				</div>
			</Card>
		</Grid>
	);
};

export default AdsManager;
