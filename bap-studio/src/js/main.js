// Fonts (self-hosted, Vietnamese + Latin subsets).
import '@fontsource/be-vietnam-pro/vietnamese-300.css';
import '@fontsource/be-vietnam-pro/latin-300.css';
import '@fontsource/be-vietnam-pro/vietnamese-400.css';
import '@fontsource/be-vietnam-pro/latin-400.css';
import '@fontsource/be-vietnam-pro/vietnamese-500.css';
import '@fontsource/be-vietnam-pro/latin-500.css';
import '@fontsource/cormorant-garamond/vietnamese-400.css';
import '@fontsource/cormorant-garamond/latin-400.css';
import '@fontsource/cormorant-garamond/vietnamese-400-italic.css';
import '@fontsource/cormorant-garamond/latin-400-italic.css';
import '@fontsource/cormorant-garamond/vietnamese-500.css';
import '@fontsource/cormorant-garamond/latin-500.css';

import '../scss/main.scss';

import { initNav } from './nav.js';
import { initDialog } from './dialog.js';
import { initCarousels } from './carousel.js';
import { initLoadMore } from './gallery.js';
import { initBooking } from './booking.js';
import { initAnimations } from './animations.js';

const ready = (fn) =>
  document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn) : fn();

ready(() => {
  initNav();
  initDialog();
  initCarousels();
  initLoadMore();
  initBooking();
  // Wait for fonts so text is split into lines at its final size.
  (document.fonts ? document.fonts.ready : Promise.resolve()).then(initAnimations);
});
