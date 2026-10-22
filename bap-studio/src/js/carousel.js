import { gsap, reducedMotion } from './motion.js';

const AUTOPLAY_MS = 5000;

/** Home carousel: GSAP crossfade with a slow zoom, autoplay, dots, swipe, keyboard. */
export function initCarousels() {
  document.querySelectorAll('[data-carousel]').forEach(setup);
}

function setup(root) {
  const slides = Array.from(root.querySelectorAll('[data-slide]'));
  if (!slides.length) return;

  const dots = Array.from(root.querySelectorAll('[data-carousel-dot]'));
  const playBtn = root.querySelector('[data-carousel-play]');
  const playLabel = root.querySelector('[data-carousel-play-label]');
  const i18n = (window.BAP && window.BAP.i18n) || { pause: 'Tạm dừng', play: 'Tự chạy' };

  let current = 0;
  let timer = null;
  let paused = reducedMotion(); // never autoplay for reduced-motion visitors
  let hovering = false;
  let offscreen = false;
  let animating = false;

  const show = (i) => {
    slides.forEach((s, n) => {
      s.classList.toggle('is-active', n === i);
      s.setAttribute('aria-hidden', n === i ? 'false' : 'true');
      s.querySelectorAll('button').forEach((b) => (b.tabIndex = n === i ? 0 : -1));
    });
    dots.forEach((d, n) => (n === i ? d.setAttribute('aria-current', 'true') : d.removeAttribute('aria-current')));
  };

  const goTo = (next, dir = 1) => {
    next = (next + slides.length) % slides.length;
    if (next === current || animating) return;
    const from = slides[current];
    const to = slides[next];
    current = next;

    if (reducedMotion()) {
      show(next);
      return;
    }
    animating = true;
    to.classList.add('is-active');
    gsap.set(to, { zIndex: 2 });
    const tl = gsap.timeline({
      onComplete: () => {
        gsap.set([from, to], { clearProps: 'zIndex,opacity,visibility,clipPath' });
        show(next);
        animating = false;
      },
    });
    tl.fromTo(
      to,
      { clipPath: dir > 0 ? 'inset(0 0 0 100%)' : 'inset(0 100% 0 0)' },
      { clipPath: 'inset(0 0% 0 0%)', duration: 1.1, ease: 'power3.inOut' }
    )
      .fromTo(to.querySelector('img'), { scale: 1.15 }, { scale: 1, duration: 1.6, ease: 'power2.out' }, 0)
      .to(from.querySelector('img'), { scale: 1.05, duration: 1.1, ease: 'power3.inOut', clearProps: 'transform' }, 0)
      .from(to.querySelectorAll('.carousel-caption > *'), { y: 24, autoAlpha: 0, stagger: 0.08, duration: 0.6, ease: 'power2.out' }, 0.6);
  };

  const schedule = () => {
    clearTimeout(timer);
    if (paused || hovering || offscreen || slides.length < 2) return;
    timer = setTimeout(() => {
      goTo(current + 1, 1);
      schedule();
    }, AUTOPLAY_MS);
  };

  const setPaused = (p) => {
    paused = p;
    if (playBtn) playBtn.setAttribute('aria-pressed', String(p));
    if (playLabel) playLabel.textContent = p ? i18n.play : i18n.pause;
    schedule();
  };

  root.querySelector('[data-carousel-prev]')?.addEventListener('click', () => { goTo(current - 1, -1); schedule(); });
  root.querySelector('[data-carousel-next]')?.addEventListener('click', () => { goTo(current + 1, 1); schedule(); });
  dots.forEach((d) => d.addEventListener('click', () => {
    const n = Number(d.dataset.carouselDot);
    goTo(n, n > current ? 1 : -1);
    schedule();
  }));
  playBtn?.addEventListener('click', () => setPaused(!paused));

  root.addEventListener('mouseenter', () => { hovering = true; schedule(); });
  root.addEventListener('mouseleave', () => { hovering = false; schedule(); });
  root.addEventListener('focusin', () => { hovering = true; schedule(); });
  root.addEventListener('focusout', () => { hovering = false; schedule(); });
  root.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') { goTo(current - 1, -1); schedule(); }
    if (e.key === 'ArrowRight') { goTo(current + 1, 1); schedule(); }
  });

  // Swipe: a horizontal drag changes slide and suppresses the click that opens the dialog.
  let startX = null;
  let swiped = false;
  const viewport = root.querySelector('.carousel-viewport');
  viewport.addEventListener('pointerdown', (e) => { startX = e.clientX; swiped = false; });
  viewport.addEventListener('pointerup', (e) => {
    if (startX === null) return;
    const dx = e.clientX - startX;
    startX = null;
    if (Math.abs(dx) > 50) {
      swiped = true;
      goTo(current + (dx < 0 ? 1 : -1), dx < 0 ? 1 : -1);
      schedule();
    }
  });
  viewport.addEventListener('click', (e) => {
    if (swiped) {
      e.stopPropagation();
      e.preventDefault();
      swiped = false;
    }
  }, true);

  // Pause when the carousel is off screen.
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(([entry]) => {
      offscreen = !entry.isIntersecting;
      schedule();
    }).observe(root);
  }

  show(0);
  setPaused(paused);
}
