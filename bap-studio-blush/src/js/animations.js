import { gsap, ScrollTrigger, SplitText, reducedMotion } from '@parent/motion.js';
import { revealTiles } from '@parent/animations.js';

/**
 * Editorial motion: masked line reveals on the big serif titles, photos that
 * unveil from the bottom, a tilted paper card that settles into place.
 * Everything is skipped with "reduce motion"; content is visible without JS.
 */
export function initAnimations() {
  if (reducedMotion()) return;

  const ease = 'power3.out';
  const mm = gsap.matchMedia();

  mm.add({ desktop: '(min-width: 769px)', mobile: '(max-width: 768px)' }, (ctx) => {
    const { desktop } = ctx.conditions;
    const dist = desktop ? 40 : 24;

    hero(desktop);

    // Section headers: kicker slides in, title rises line by line from a mask, aside fades up.
    gsap.utils.toArray('.section-head').forEach((head) => {
      const tl = gsap.timeline({ defaults: { ease }, scrollTrigger: { trigger: head, start: 'top 85%', once: true } });
      const kicker = head.querySelector('.kicker');
      const title = head.querySelector('[data-split]');
      const aside = head.querySelector('.section-head-aside');
      if (kicker) tl.from(kicker, { x: -24, autoAlpha: 0, duration: 0.7 }, 0);
      if (title) {
        const split = SplitText.create(title, { type: 'lines', mask: 'lines', linesClass: 'line' });
        tl.from(split.lines, { yPercent: 110, duration: 1.1, stagger: 0.1 }, 0.1);
      }
      if (aside) tl.from(aside.children, { y: 20, autoAlpha: 0, duration: 0.8, stagger: 0.1 }, 0.4);
    });
    // The header handles its own reveal, so stop [data-reveal] from fading it twice.
    gsap.utils.toArray('[data-reveal]:not(.section-head)').forEach((el) => {
      gsap.from(el, { y: dist, autoAlpha: 0, duration: 1, ease, scrollTrigger: { trigger: el, start: 'top 85%', once: true } });
    });

    // Hairline columns (pinned albums, pricing): the rule above draws across,
    // then each column rises in and its photo unveils from the bottom.
    gsap.utils.toArray('[data-stagger]').forEach((group) => {
      const items = group.querySelectorAll('[data-stagger-item]');
      const tl = gsap.timeline({ defaults: { ease }, scrollTrigger: { trigger: group, start: 'top 80%', once: true } });
      tl.from(group, { '--rule': 0, duration: 1.2, ease: 'power2.inOut' }, 0)
        .from(items, { y: dist * 1.5, autoAlpha: 0, duration: 0.9, stagger: 0.14 }, 0.15);
      const covers = group.querySelectorAll('.album-card-cover');
      if (covers.length) {
        tl.from(covers, { clipPath: 'inset(100% 0 0 0)', duration: 1.2, stagger: 0.14, ease: 'power4.inOut', clearProps: 'clipPath' }, 0.25);
        tl.from(group.querySelectorAll('.album-card-cover img'), { scale: 1.2, duration: 1.6, stagger: 0.14, clearProps: 'transform' }, 0.25);
      }
    });

    // Carousel band rises in.
    gsap.utils.toArray('[data-carousel]').forEach((el) => {
      gsap.from(el, { y: dist, autoAlpha: 0, duration: 1.1, ease, scrollTrigger: { trigger: el, start: 'top 85%', once: true } });
    });

    // Album page tiles (also used by "load more").
    revealTiles(gsap.utils.toArray('[data-gallery] [data-tile]'));
  });

  window.addEventListener('load', () => ScrollTrigger.refresh());
}

function hero(desktop) {
  const root = document.querySelector('[data-banner]');
  if (!root) return;

  const media = root.querySelector('[data-banner-media]');
  const title = root.querySelector('[data-split]');
  const cardStack = root.querySelector('[data-hero-cards]');
  const cards = root.querySelectorAll('[data-hero-card]');
  const split = title ? SplitText.create(title, { type: 'lines', mask: 'lines', linesClass: 'line' }) : null;

  const tl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: 0.1 });
  tl.from(media, { scale: 1.15, duration: 2.4, ease: 'power2.out' }, 0)
    .from(root.querySelector('[data-banner-eyebrow]'), { x: -24, autoAlpha: 0, duration: 0.8 }, 0.3);
  if (split) tl.from(split.lines, { yPercent: 115, duration: 1.2, stagger: 0.12 }, 0.4);
  tl.from(root.querySelector('[data-banner-sub]'), { y: 20, autoAlpha: 0, duration: 0.8 }, '-=0.7')
    .from(root.querySelectorAll('[data-banner-buttons] > *'), { y: 16, autoAlpha: 0, duration: 0.6, stagger: 0.1 }, '-=0.5');

  // Each card drops in from a steeper tilt and settles at its CSS --tilt, then drifts gently,
  // out of step with the others. Only --lift/--spin are animated, so the tilt stays responsive.
  cards.forEach((card, i) => {
    tl.from(card, { '--lift': '80px', '--spin': '-10deg', autoAlpha: 0, duration: 1.3, ease: 'back.out(1.4)' }, 0.9 + i * 0.15);
    gsap.to(card, {
      '--lift': '-10px',
      '--spin': '1.5deg',
      duration: 3.2 + i * 0.4,
      ease: 'sine.inOut',
      yoyo: true,
      repeat: -1,
      delay: 2.3 + i * 0.6,
    });
  });

  // Parallax photo and fading text while scrolling past.
  gsap.to(media, {
    yPercent: desktop ? 14 : 8,
    ease: 'none',
    scrollTrigger: { trigger: root, start: 'top top', end: 'bottom top', scrub: true },
  });
  gsap.to(root.querySelector('[data-banner-content]'), {
    autoAlpha: 0,
    y: -60,
    ease: 'none',
    scrollTrigger: { trigger: root, start: 'center top+=20%', end: 'bottom top', scrub: true },
  });
  if (cardStack && desktop) {
    gsap.to(cardStack, { yPercent: -60, ease: 'none', scrollTrigger: { trigger: root, start: 'top top', end: 'bottom top', scrub: true } });
  }
}
