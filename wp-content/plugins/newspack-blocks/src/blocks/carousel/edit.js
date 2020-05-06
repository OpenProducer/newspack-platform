/* eslint-disable jsx-a11y/anchor-is-valid, jsx-a11y/anchor-has-content, jsx-a11y/click-events-have-key-events, jsx-a11y/interactive-supports-focus */

/**
 * Internal dependencies
 */
import QueryControls from '../../components/query-controls';
import createSwiper from './create-swiper';
import { formatAvatars, formatByline } from '../../shared/js/utils';

/**
 * External dependencies
 */
import { isUndefined, pickBy } from 'lodash';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { dateI18n, __experimentalGetSettings } from '@wordpress/date';
import { Component, createRef, Fragment } from '@wordpress/element';
import { InspectorControls } from '@wordpress/editor';
import {
	PanelBody,
	PanelRow,
	Placeholder,
	RangeControl,
	Spinner,
	ToggleControl,
} from '@wordpress/components';
import { withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { decodeEntities } from '@wordpress/html-entities';

class Edit extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			autoPlayState: true,
		};
		this.btnNextRef = createRef();
		this.btnPrevRef = createRef();
		this.carouselRef = createRef();
		this.paginationRef = createRef();
	}
	componentDidUpdate() {
		const { attributes, latestPosts } = this.props;
		const { autoPlayState } = this.state;
		const { autoplay, delay } = attributes;
		const realIndex =
			this.swiperInstance && latestPosts && this.swiperInstance.realIndex < latestPosts.length
				? this.swiperInstance.realIndex
				: 0;
		// eslint-disable-next-line no-unused-expressions
		this.swiperInstance && this.swiperInstance.destroy( true, true );
		this.swiperInstance = createSwiper(
			this.carouselRef.current,
			{
				autoplay:
					autoplay && autoPlayState
						? {
								delay: delay * 1000,
								disableOnInteraction: false,
						  }
						: false,
				effect: 'slide',
				initialSlide: realIndex,
				loop: true,
				navigation: {
					nextEl: this.btnNextRef.current,
					prevEl: this.btnPrevRef.current,
				},
				pagination: {
					clickable: true,
					el: this.paginationRef.current,
					type: 'bullets',
				},
			},
			{}
		);
	}

	render() {
		const { attributes, className, setAttributes, latestPosts } = this.props;
		const { autoPlayState } = this.state;
		const {
			authors,
			autoplay,
			categories,
			delay,
			postsToShow,
			showCategory,
			showDate,
			showAuthor,
			showAvatar,
			tags,
		} = attributes;
		const classes = classnames(
			className,
			'wp-block-newspack-blocks-carousel', // Default to make styles work for third-party consumers.
			'swiper-container',
			autoplay && autoPlayState && 'wp-block-newspack-blocks-carousel__autoplay-playing'
		);
		const dateFormat = __experimentalGetSettings().formats.date;
		return (
			<Fragment>
				<div className={ classes } ref={ this.carouselRef }>
					{ latestPosts && ! latestPosts.length && (
						<Placeholder>{ __( 'Sorry, no posts were found.' ) }</Placeholder>
					) }
					{ ! latestPosts && (
						<Placeholder icon={ <Spinner /> } className="component-placeholder__align-center" />
					) }
					{ latestPosts && (
						<Fragment>
							<div className="swiper-wrapper">
								{ latestPosts.map(
									post =>
										post.newspack_featured_image_src && (
											<article className="post-has-image swiper-slide" key={ post.id }>
												<figure className="post-thumbnail">
													{ post.newspack_featured_image_src && (
														<a href="#" rel="bookmark">
															<img src={ post.newspack_featured_image_src.large } alt="" />
														</a>
													) }
												</figure>
												<div className="entry-wrapper">
													{ showCategory && post.newspack_category_info.length && (
														<div className="cat-links">
															<a href="#">{ post.newspack_category_info }</a>
														</div>
													) }
													<h3 className="entry-title">
														<a href="#">{ decodeEntities( post.title.rendered.trim() ) }</a>
													</h3>
													<div className="entry-meta">
														{ showAuthor &&
															showAvatar &&
															formatAvatars( post.newspack_author_info ) }
														{ showAuthor && formatByline( post.newspack_author_info ) }
														{ showDate && (
															<time className="entry-date published" key="pub-date">
																{ dateI18n( dateFormat, post.date_gmt ) }
															</time>
														) }
													</div>
												</div>
											</article>
										)
								) }
							</div>
							<a
								className="amp-carousel-button amp-carousel-button-prev swiper-button-prev"
								ref={ this.btnPrevRef }
								role="button"
							/>
							<a
								className="amp-carousel-button amp-carousel-button-next swiper-button-next"
								ref={ this.btnNextRef }
								role="button"
							/>
							{ autoplay && (
								<Fragment>
									<a
										className="amp-carousel-button-pause amp-carousel-button"
										role="button"
										onClick={ () => {
											this.swiperInstance.autoplay.stop();
											this.setState( { autoPlayState: false } );
										} }
									/>
									<a
										className="amp-carousel-button-play amp-carousel-button"
										role="button"
										onClick={ () => {
											this.swiperInstance.autoplay.start();
											this.setState( { autoPlayState: true } );
										} }
									/>
								</Fragment>
							) }
							<div
								className="swiper-pagination-bullets amp-pagination"
								ref={ this.paginationRef }
							/>
						</Fragment>
					) }
				</div>
				<InspectorControls>
					<PanelBody title={ __( 'Display Settings' ) } initialOpen={ true }>
						{ postsToShow && (
							<QueryControls
								enableSpecific={ false }
								numberOfItems={ postsToShow }
								onNumberOfItemsChange={ value => setAttributes( { postsToShow: value } ) }
								authors={ authors }
								onAuthorsChange={ value => setAttributes( { authors: value } ) }
								categories={ categories }
								onCategoriesChange={ value => setAttributes( { categories: value } ) }
								tags={ tags }
								onTagsChange={ value => setAttributes( { tags: value } ) }
							/>
						) }
					</PanelBody>
					<PanelBody title={ __( 'Slideshow Settings' ) } initialOpen={ true }>
						<ToggleControl
							label={ __( 'Autoplay' ) }
							help={ __( 'Autoplay between slides' ) }
							checked={ autoplay }
							onChange={ _autoplay => {
								setAttributes( { autoplay: _autoplay } );
							} }
						/>
						{ autoplay && (
							<RangeControl
								label={ __( 'Delay between transitions (in seconds)' ) }
								value={ delay }
								onChange={ _delay => {
									setAttributes( { delay: _delay } );
								} }
								min={ 1 }
								max={ 20 }
							/>
						) }
					</PanelBody>
					<PanelBody title={ __( 'Article Meta Settings', 'newspack-blocks' ) }>
						<PanelRow>
							<ToggleControl
								label={ __( 'Show Date', 'newspack-blocks' ) }
								checked={ showDate }
								onChange={ () => setAttributes( { showDate: ! showDate } ) }
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={ __( 'Show Category', 'newspack-blocks' ) }
								checked={ showCategory }
								onChange={ () => setAttributes( { showCategory: ! showCategory } ) }
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={ __( 'Show Author', 'newspack-blocks' ) }
								checked={ showAuthor }
								onChange={ () => setAttributes( { showAuthor: ! showAuthor } ) }
							/>
						</PanelRow>
						{ showAuthor && (
							<PanelRow>
								<ToggleControl
									label={ __( 'Show Author Avatar', 'newspack-blocks' ) }
									checked={ showAvatar }
									onChange={ () => setAttributes( { showAvatar: ! showAvatar } ) }
								/>
							</PanelRow>
						) }
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	}
}

export default compose( [
	withSelect( ( select, props ) => {
		const { postsToShow, authors, categories, tags } = props.attributes;
		const { getEntityRecords } = select( 'core' );
		const latestPostsQuery = pickBy(
			{
				per_page: postsToShow,
				categories,
				author: authors,
				tags,
				meta_key: '_thumbnail_id',
				meta_value_num: 0,
				meta_compare: '>',
			},
			value => ! isUndefined( value )
		);
		return {
			latestPosts: getEntityRecords( 'postType', 'post', latestPostsQuery ),
		};
	} ),
] )( Edit );
