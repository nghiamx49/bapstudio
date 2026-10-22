import { revealTiles } from './animations.js';

/** "Xem thêm ảnh" on album pages: fetch the next 24 photos and append them. */
export function initLoadMore() {
  const btn = document.querySelector('[data-load-more]');
  const grid = document.querySelector('[data-gallery]');
  if (!btn || !grid || !window.BAP) return;

  const label = btn.textContent;

  btn.addEventListener('click', async () => {
    const page = Number(btn.dataset.page) + 1;
    const pages = Number(btn.dataset.pages);
    btn.disabled = true;
    btn.textContent = window.BAP.i18n.loading;
    try {
      const res = await fetch(`${window.BAP.restUrl}album/${btn.dataset.album}?page=${page}`);
      if (!res.ok) throw new Error(res.statusText);
      const data = await res.json();
      const tpl = document.createElement('template');
      tpl.innerHTML = data.html;
      const tiles = Array.from(tpl.content.children);
      grid.append(...tiles);
      revealTiles(tiles);
      btn.dataset.page = String(page);
      if (page >= pages) btn.closest('.load-more-wrap').remove();
    } catch (err) {
      console.error(err); // eslint-disable-line no-console
    } finally {
      btn.disabled = false;
      btn.textContent = label;
    }
  });
}
