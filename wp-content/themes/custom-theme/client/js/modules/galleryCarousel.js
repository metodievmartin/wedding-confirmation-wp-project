import $ from 'jquery';
import '../lib/owl.carousel.min';

export function init(carouselSelector = '.gallery-carousel-container') {
  const $galleryContainer = $(carouselSelector);

  if ($galleryContainer.length) {
    $galleryContainer.owlCarousel({
      slidesToShow: 8,
      slidesToScroll: 1,
      loop: true,
      autoplay: true,
      speed: 2000,
      smartSpeed: 1000,
      nav: false,
      dots: false,
      margin: 0,
      autoplayHoverPause: true,
      responsive: {
        0: {
          nav: false,
          items: 2,
        },
        768: {
          nav: false,
          items: 8,
        },
      },
    });
  }
}
