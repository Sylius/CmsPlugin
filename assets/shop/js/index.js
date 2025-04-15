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
        slidesPerView: 4,
        spaceBetween: 32,
        speed: 400,
    });
}
