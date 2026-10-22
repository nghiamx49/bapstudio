import { gsap, reducedMotion } from './motion.js';

/**
 * Image dialog. Any element with data-photo (plus data-full, data-title,
 * data-album, data-desc) opens it. Prev/next move through the photos in the
 * same gallery, carousel or album card.
 */
export function initDialog() {
  const dialog = document.querySelector('[data-dialog]');
  if (!dialog) return;

  const img = dialog.querySelector('[data-dialog-img]');
  const media = dialog.querySelector('[data-dialog-media]');
  const textPanel = dialog.querySelector('[data-dialog-text]');
  const title = dialog.querySelector('[data-dialog-title]');
  const album = dialog.querySelector('[data-dialog-album]');
  const desc = dialog.querySelector('[data-dialog-desc]');
  const pos = dialog.querySelector('[data-dialog-pos]');

  let group = [];
  let index = 0;
  let opener = null;

  const groupFor = (el) => {
    const scope = el.closest('[data-gallery], [data-carousel], .album-card-thumbs') || document;
    return Array.from(scope.querySelectorAll('[data-photo]'));
  };

  const fill = (el) => {
    const d = el.dataset;
    const thumb = el.querySelector('img');
    // Show the already-loaded thumbnail first, then swap in the large image.
    if (thumb) img.src = thumb.currentSrc || thumb.src;
    img.alt = thumb ? thumb.alt : d.title || '';
    if (d.full && d.full !== img.src) {
      const large = new Image();
      large.onload = () => {
        if (group[index] === el) img.src = d.full;
      };
      large.src = d.full;
    }
    title.textContent = d.title || '';
    album.textContent = d.album || '';
    album.hidden = !d.album;
    // Description HTML comes from the server already sanitised (wp_kses_post).
    desc.innerHTML = d.desc || '';
    pos.textContent = group.length > 1 ? `${index + 1} / ${group.length}` : '';
    dialog.classList.toggle('single', group.length < 2);
  };

  const animateIn = (fromEl) => {
    if (reducedMotion()) return;
    const thumb = fromEl && fromEl.querySelector('img');
    const tl = gsap.timeline();
    if (thumb) {
      // FLIP: start the large image where the thumbnail is, then grow into place.
      const from = thumb.getBoundingClientRect();
      const to = img.getBoundingClientRect();
      if (to.width && to.height) {
        tl.from(img, {
          x: from.left + from.width / 2 - (to.left + to.width / 2),
          y: from.top + from.height / 2 - (to.top + to.height / 2),
          scaleX: from.width / to.width,
          scaleY: from.height / to.height,
          duration: 0.6,
          ease: 'power3.inOut',
          clearProps: 'transform',
        });
      }
    } else {
      tl.from(img, { autoAlpha: 0, scale: 0.96, duration: 0.5, ease: 'power2.out' });
    }
    tl.from(textPanel, { xPercent: 12, autoAlpha: 0, duration: 0.6, ease: 'power3.out', clearProps: 'all' }, '-=0.35');
    tl.from(textPanel.children, { y: 16, autoAlpha: 0, stagger: 0.06, duration: 0.4, ease: 'power2.out', clearProps: 'all' }, '-=0.35');
  };

  const open = (el) => {
    opener = el;
    group = groupFor(el);
    index = Math.max(0, group.indexOf(el));
    fill(el);
    dialog.showModal();
    document.body.classList.add('dialog-open');
    if (img.complete) animateIn(el);
    else img.addEventListener('load', () => animateIn(el), { once: true });
  };

  const close = () => {
    if (!dialog.open) return;
    const done = () => {
      dialog.close();
    };
    if (reducedMotion()) return done();
    gsap.to(dialog, { autoAlpha: 0, duration: 0.25, onComplete: () => { done(); gsap.set(dialog, { clearProps: 'all' }); } });
  };

  const go = (step) => {
    if (group.length < 2) return;
    index = (index + step + group.length) % group.length;
    const el = group[index];
    if (reducedMotion()) return fill(el);
    gsap.to([img, textPanel], {
      autoAlpha: 0,
      x: -24 * step,
      duration: 0.2,
      ease: 'power1.in',
      onComplete: () => {
        fill(el);
        gsap.fromTo([img, textPanel], { autoAlpha: 0, x: 24 * step }, { autoAlpha: 1, x: 0, duration: 0.35, ease: 'power2.out', clearProps: 'all' });
      },
    });
  };

  document.addEventListener('click', (e) => {
    const el = e.target.closest('[data-photo]');
    if (el && !dialog.contains(el)) {
      e.preventDefault();
      open(el);
    }
  });

  dialog.querySelector('[data-dialog-close]').addEventListener('click', close);
  dialog.querySelector('[data-dialog-prev]').addEventListener('click', () => go(-1));
  dialog.querySelector('[data-dialog-next]').addEventListener('click', () => go(1));

  // Click on the dark area around the photo closes the dialog.
  media.addEventListener('click', (e) => {
    if (e.target === media) close();
  });

  dialog.addEventListener('cancel', (e) => {
    e.preventDefault();
    close();
  });
  dialog.addEventListener('close', () => {
    document.body.classList.remove('dialog-open');
    img.removeAttribute('src');
    if (opener) opener.focus({ preventScroll: true });
  });
  dialog.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') go(-1);
    if (e.key === 'ArrowRight') go(1);
  });

  // Swipe left/right on touch screens.
  let startX = null;
  media.addEventListener('pointerdown', (e) => { startX = e.clientX; });
  media.addEventListener('pointerup', (e) => {
    if (startX === null) return;
    const dx = e.clientX - startX;
    startX = null;
    if (Math.abs(dx) > 50) go(dx < 0 ? 1 : -1);
  });
}
