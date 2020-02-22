/**
 * External dependencies
 */
import { merge } from 'lodash';
import Swiper from 'swiper';
import '../../../node_modules/swiper/dist/css/swiper.css';

/**
 * @param {string} container Selector
 * @param {Object} params Params passed to Swiper
 */
export default function createSwiper( container = '.swiper-container', params = {} ) {
	const defaultParams = {
		effect: 'slide',
		grabCursor: true,
		init: true,
		initialSlide: 0,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		pagination: {
			bulletElement: 'button',
			clickable: true,
			el: '.swiper-pagination',
			type: 'bullets',
		},
		preventClicksPropagation: false /* Necessary for normal block interactions */,
		releaseFormElements: false,
		setWrapperSize: true,
		touchStartPreventDefault: false,
	};

	return new Swiper( container, merge( {}, defaultParams, params ) );
}
