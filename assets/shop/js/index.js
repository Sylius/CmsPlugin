import Swiper from 'swiper';
import { Navigation, Autoplay } from 'swiper/modules';

import 'swiper/css';

const productsCarousels = document.querySelectorAll('.products-carousel')

for (let i= 0; i < productsCarousels.length; i++ ) {

    productsCarousels[i].classList.add(`products-carousel-${i}`);

    const swiper = new Swiper(`.products-carousel-${i}`, {
        autoplay: {
            delay: 5000,
        },
        loop: true,
        modules: [Navigation, Autoplay],
        navigation: {
            nextEl: `.products-carousel-${i} .swiper-button-next`,
            prevEl: `.products-carousel-${i} .swiper-button-prev`,
        },
        observeParents: true,
        observer: true,
        slidesPerView: 1,
        breakpoints: {
            576: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
            1400: {
                slidesPerView: 5,
            },
        },
        spaceBetween: 32,
        speed: 400,
    });
}
