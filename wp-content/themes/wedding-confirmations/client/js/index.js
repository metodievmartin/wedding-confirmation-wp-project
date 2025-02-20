import 'bootstrap/dist/js/bootstrap.bundle.min';
import * as contactForm from './modules/contactForm';
import * as countdown from './modules/countdown';
import * as galleryCarousel from './modules/galleryCarousel';
import * as globalEvents from './modules/globalEvents';

globalEvents.init();
countdown.init();
contactForm.init();
galleryCarousel.init();

console.log('JS scripts loaded...');
