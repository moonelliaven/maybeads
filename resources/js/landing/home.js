/* ==========================================================================
   Maybeads — Landing Page interactions
   Lenis (smooth scroll) + Motion (animate / scroll / inView)
   ========================================================================== */
import Lenis from 'lenis';
import { animate, scroll, inView, stagger } from 'motion';

const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
const ease = [0.22, 1, 0.36, 1];
const $ = (s, c = document) => c.querySelector(s);
const $$ = (s, c = document) => [...c.querySelectorAll(s)];

/* ---------------------------------------------------------------------------
   Lenis smooth scroll
   --------------------------------------------------------------------------- */
const lenis = reduce
  ? null
  : new Lenis({
      duration: 1.15,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      smoothWheel: true,
      touchMultiplier: 1.4,
      autoRaf: true,
    });

$$('a[href^="#"]').forEach((a) => {
  a.addEventListener('click', (e) => {
    const id = a.getAttribute('href');
    if (!id || id === '#') return;
    const target = $(id);
    if (!target) return;
    e.preventDefault();
    if (lenis) lenis.scrollTo(target, { offset: id === '#home' ? -200 : -70 });
    else target.scrollIntoView({ behavior: 'smooth' });
  });
});

/* ---------------------------------------------------------------------------
   Navigation: shadow on scroll + hide on scroll down
   --------------------------------------------------------------------------- */
const nav = $('#nav');
let lastY = 0;
const onScroll = (y) => {
  nav.classList.toggle('scrolled', y > 20);
  nav.classList.toggle('hidden', y > 400 && y > lastY + 2);
  if (y < lastY - 2) nav.classList.remove('hidden');
  lastY = y;
};
if (lenis) lenis.on('scroll', ({ scroll: y }) => onScroll(y));
else addEventListener('scroll', () => onScroll(scrollY), { passive: true });

/* ---------------------------------------------------------------------------
   Marquee strip — infinite loop, speeds up with scroll velocity
   --------------------------------------------------------------------------- */
const strip = $('#strip');
if (strip && !reduce) {
  const loop = animate(strip, { x: ['0%', '-25%'] }, { duration: 28, ease: 'linear', repeat: Infinity });
  if (lenis) {
    let speed = 1;
    lenis.on('scroll', ({ velocity }) => {
      speed = 1 + Math.min(Math.abs(velocity) * 0.25, 4);
      loop.speed = velocity < 0 ? -speed : speed;
    });
    // ease back to normal speed when idle
    setInterval(() => {
      const s = loop.speed;
      loop.speed = s + ((s < 0 ? -1 : 1) - s) * 0.15;
    }, 60);
  }
}

if (reduce) {
  // CSS already shows everything; just set final rating text
  const r = $('#rating');
  if (r) r.textContent = r.dataset.to.replace('.', ',');
}

/* ---------------------------------------------------------------------------
   Reveal helpers
   --------------------------------------------------------------------------- */
const revealLines = (root, delay = 0) =>
  animate($$('.line > span', root), { y: ['110%', '0%'] }, { duration: 0.9, ease, delay: stagger(0.09, { startDelay: delay }) });

const fadeUp = (els, delay = 0) =>
  animate(els, { opacity: [0, 1], y: [18, 0] }, { duration: 0.8, ease, delay: stagger(0.08, { startDelay: delay }) });

if (!reduce) {
  /* Hero intro */
  revealLines($('.hero-title'), 0.15);
  fadeUp($$('.hero-copy [data-fade]'), 0.55);
  $$('.hero-gallery [data-reveal]').forEach((fig, i) => {
    animate(fig, { clipPath: ['inset(100% 0 0 0)', 'inset(0% 0 0 0)'] }, { duration: 1.1, ease, delay: 0.3 + i * 0.15 });
    animate($('img', fig), { scale: [1.25, 1] }, { duration: 1.6, ease, delay: 0.3 + i * 0.15 });
  });

  /* Subtle image parallax tied to scroll */
  $$('.hg img').forEach((img) => {
    scroll(animate(img, { y: ['0%', '-9%'] }, { ease: 'linear' }), {
      target: img.parentElement,
      offset: ['start end', 'end start'],
    });
  });

  /* Section titles */
  $$('.section .split').forEach((t) => inView(t, () => { revealLines(t); }, { amount: 0.4 }));

  /* Generic fades (outside hero) */
  $$('[data-fade]')
    .filter((el) => !el.closest('.hero'))
    .forEach((el) => inView(el, () => { fadeUp(el, 0.15); }, { amount: 0.3 }));

  /* Review cards */
  inView('#rev-grid', (grid) => { fadeUp($$('.rev', grid)); }, { amount: 0.25 });

  /* Rating counter */
  const rating = $('#rating');
  if (rating) {
    inView(rating, () => {
      animate(0, parseFloat(rating.dataset.to), {
        duration: 1.6,
        ease,
        onUpdate: (v) => (rating.textContent = v.toFixed(1).replace('.', ',')),
      });
    }, { amount: 0.6 });
  }

  /* CTA band: title reveal + gentle drift on scroll */
  const band = $('#cta');
  if (band) {
    inView(band, () => { revealLines($('.band-title', band)); }, { amount: 0.4 });
    scroll(animate('.band-title', { x: [-30, 20] }, { ease: 'linear' }), { target: band, offset: ['start end', 'end start'] });
  }
}

/* ---------------------------------------------------------------------------
   Best seller: category tabs with sliding pill
   --------------------------------------------------------------------------- */
const tabs = $('#tabs');
const pill = $('#tab-pill');
const cards = $$('#cards .card');
const MAX = 4;
let cardsSeen = false;

const movePill = (btn) => {
  if (!pill || !btn) return;
  pill.style.width = `${btn.offsetWidth}px`;
  pill.style.height = `${btn.offsetHeight}px`;
  pill.style.top = `${btn.offsetTop}px`;
  pill.style.transform = `translateX(${btn.offsetLeft - 4}px)`;
};

const filterCards = (cat, animateIn = true) => {
  let shown = 0;
  const visible = [];
  cards.forEach((c) => {
    const match = cat === 'all' || c.dataset.cat === cat || c.dataset.cat === 'all';
    const show = match && shown < MAX;
    c.hidden = !show;
    if (show) { shown++; visible.push(c); }
  });
  if (animateIn && !reduce) fadeUp(visible);
  else visible.forEach((c) => (c.style.opacity = 1));
};

if (tabs) {
  const active = $('.tab.active', tabs);
  movePill(active);
  filterCards('all', false);
  if (!reduce) cards.forEach((c) => (c.style.opacity = 0));

  tabs.addEventListener('click', (e) => {
    const btn = e.target.closest('.tab');
    if (!btn || btn.classList.contains('active')) return;
    $$('.tab', tabs).forEach((t) => t.classList.toggle('active', t === btn));
    movePill(btn);
    filterCards(btn.dataset.cat, cardsSeen);
  });

  addEventListener('resize', () => movePill($('.tab.active', tabs)));
  document.fonts?.ready.then(() => movePill($('.tab.active', tabs)));
}

if (!reduce) {
  inView('#cards', (grid) => {
    cardsSeen = true;
    fadeUp($$('.card:not([hidden])', grid));
  }, { amount: 0.2 });
}
