import { gsap, ScrollTrigger, SplitText, reducedMotion } from './motion.js';

/**
 * Page motion. Everything here is skipped for visitors with
 * "reduce motion" turned on; content is fully visible without JS.
 */
export function initAnimations() {
  if (reducedMotion()) return;

  const mm = gsap.matchMedia();

  mm.add({ desktop: '(min-width: 769px)', mobile: '(max-width: 768px)' }, (ctx) => {
    const { desktop } = ctx.conditions;
    const dist = desktop ? 40 : 24;

    banner(desktop);

    // Section headings and single blocks: fade up when they enter the viewport.
    gsap.utils.toArray('[data-reveal]').forEach((el) => {
      gsap.from(el, {
        y: dist,
        autoAlpha: 0,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: { trigger: el, start: 'top 85%', once: true },
      });
      const title = el.querySelector('.section-title');
      if (title) {
        gsap.from(title, {
          letterSpacing: '0.06em',
          duration: 1.4,
          ease: 'power2.out',
          clearProps: 'letterSpacing',
          scrollTrigger: { trigger: el, start: 'top 85%', once: true },
        });
      }
    });

    // Card groups (pinned albums, pricing): stagger in.
    gsap.utils.toArray('[data-stagger]').forEach((group) => {
      gsap.from(group.querySelectorAll('[data-stagger-item]'), {
        y: dist * 1.5,
        autoAlpha: 0,
        duration: 0.9,
        stagger: 0.12,
        ease: 'power3.out',
        scrollTrigger: { trigger: group, start: 'top 80%', once: true },
      });
    });

    // Gallery tiles: stagger in batches as they scroll into view.
    revealTiles(gsap.utils.toArray('[data-gallery] [data-tile]'));

    // Carousel block rises in.
    gsap.utils.toArray('[data-carousel]').forEach((el) => {
      gsap.from(el, {
        y: dist,
        autoAlpha: 0,
        duration: 1.1,
        ease: 'power3.out',
        scrollTrigger: { trigger: el, start: 'top 85%', once: true },
      });
    });
  });

  // Recalculate positions after late-loading images change the layout.
  window.addEventListener('load', () => ScrollTrigger.refresh());
}

function banner(desktop) {
  const root = document.querySelector('[data-banner]');
  if (!root) return;

  const title = root.querySelector('[data-split]');
  const split = title ? SplitText.create(title, { type: 'lines', mask: 'lines', linesClass: 'line' }) : null;

  const tl = gsap.timeline({ defaults: { ease: 'power3.out' }, delay: 0.15 });
  tl.from(root.querySelector('[data-banner-media]'), { scale: 1.12, duration: 2.2, ease: 'power2.out' }, 0)
    .from(root.querySelector('[data-banner-eyebrow]'), { y: 16, autoAlpha: 0, duration: 0.8 }, 0.3);
  if (split) tl.from(split.lines, { yPercent: 110, duration: 1.1, stagger: 0.12 }, 0.45);
  tl.from(root.querySelector('[data-banner-rule]'), { scaleX: 0, duration: 0.9, ease: 'power2.inOut' }, '-=0.6')
    .from(root.querySelector('[data-banner-sub]'), { y: 20, autoAlpha: 0, duration: 0.8 }, '-=0.5')
    .from(root.querySelectorAll('[data-banner-buttons] > *'), { y: 16, autoAlpha: 0, duration: 0.6, stagger: 0.1 }, '-=0.5');

  // Parallax background and fading text while scrolling past the banner.
  gsap.to(root.querySelector('[data-banner-media]'), {
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
}

/** Stagger gallery tiles in, batched by scroll position. Also used by "load more". */
export function revealTiles(tiles) {
  if (!tiles.length || reducedMotion()) return;
  gsap.set(tiles, { autoAlpha: 0, y: 30 });
  ScrollTrigger.batch(tiles, {
    start: 'top 92%',
    once: true,
    onEnter: (batch) => gsap.to(batch, { autoAlpha: 1, y: 0, duration: 0.8, stagger: 0.08, ease: 'power3.out', overwrite: true }),
  });
  ScrollTrigger.refresh();
}
