// Booking links: package pre-selection and the move of the home contact section to its own page.

const CONTACT_ANCHOR = 'lien-he';
const PACKAGE_PARAM = 'goi';

const findPackageSelect = () => document.querySelector('select[name="your-package"]');

// Old links and bookmarks to "/#lien-he" land on the home page, which has no contact section anymore.
// Returns true when the page is being redirected.
function redirectMissingContactAnchor() {
  const contactUrl = window.BAP && window.BAP.contactUrl;
  const isMissingContactAnchor = window.location.hash === `#${CONTACT_ANCHOR}` && !document.getElementById(CONTACT_ANCHOR);
  if (!contactUrl || !isMissingContactAnchor) return false;
  window.location.replace(contactUrl);
  return true;
}

// Pricing cards on other pages link to the contact page with ?goi=<package title>.
function preselectPackageFromUrl() {
  const select = findPackageSelect();
  const pkg = new URLSearchParams(window.location.search).get(PACKAGE_PARAM);
  if (select && pkg) select.value = pkg;
}

// Pricing cards on a page with its own contact section scroll down to it.
function preselectPackageOnClick(e) {
  const btn = e.target.closest('[data-package]');
  const select = findPackageSelect();
  if (btn && select) select.value = btn.dataset.package;
}

export function initBooking() {
  if (redirectMissingContactAnchor()) return;
  preselectPackageFromUrl();
  document.addEventListener('click', preselectPackageOnClick);
}
