// Fonts (self-hosted, Vietnamese + Latin subsets).
// The design pairs Cormorant Garamond with Manrope and DM Sans, but neither of those
// has Vietnamese glyphs, so Be Vietnam Pro stands in for both sans voices.
import '@fontsource/be-vietnam-pro/vietnamese-400.css';
import '@fontsource/be-vietnam-pro/latin-400.css';
import '@fontsource/be-vietnam-pro/vietnamese-600.css';
import '@fontsource/be-vietnam-pro/latin-600.css';
import '@fontsource/be-vietnam-pro/vietnamese-700.css';
import '@fontsource/be-vietnam-pro/latin-700.css';
import '@fontsource/cormorant-garamond/vietnamese-400-italic.css';
import '@fontsource/cormorant-garamond/latin-400-italic.css';
import '@fontsource/cormorant-garamond/vietnamese-500.css';
import '@fontsource/cormorant-garamond/latin-500.css';

import '../scss/main.scss';

// Behaviour shared with the parent theme.
import { initNav } from '@parent/nav.js';
import { initDialog } from '@parent/dialog.js';
import { initCarousels } from '@parent/carousel.js';
import { initLoadMore } from '@parent/gallery.js';
import { initBooking } from '@parent/booking.js';

import { initAnimations } from './animations.js';

const ready = (fn) =>
  document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn) : fn();

ready(() => {
  initNav();
  initDialog();
  initCarousels();
  initLoadMore();
  initBooking();
  // Wait for fonts so headlines are split into lines at their final size.
  (document.fonts ? document.fonts.ready : Promise.resolve()).then(initAnimations);
});
