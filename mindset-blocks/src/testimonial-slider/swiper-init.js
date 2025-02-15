/**
* Swiper dependencies
*
* @see https://swiperjs.com/get-started
*/
// Import Swiper JS bundle with all modules installed.
import Swiper from 'swiper/bundle';

/**
* Initialize the slider.
*
* @param {Element} container HTMLElement.
* @param {Object}  options   Slider parameters.
*
* @return {Object} Returns initialized slider instance.
*
* @see https://swiperjs.com/swiper-api#parameters
*/
export function SwiperInit( container, options = {} ) {
    const parameters = {
        loop: true,
        autoHeight: true,
        slidesPerView: 1,
        spaceBetween: 10,
        breakpoints: {
            800: {
                slidesPerView: 2,
                spaceBetween: 20
            },
        },
        navigation: options?.navigation ? { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' } : {enabled: false },
        pagination: options?.pagination ? {el: '.swiper-pagination', clickable: true } : {enabled: false },
    };

    return new Swiper( container, parameters );
}