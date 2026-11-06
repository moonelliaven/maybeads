/* ==========================================================================
   Maybeads — Landing Page interactions
   Lenis (smooth scroll) + Motion (animate / scroll / inView)
   Enhanced with advanced motion graph animations
   ========================================================================== */
import Lenis from 'lenis';
import { animate, scroll, inView, stagger, spring } from 'motion';

const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
const ease = [0.22, 1, 0.36, 1];
const easeOut = [0.16, 1, 0.3, 1];
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
   Scroll progress bar
   --------------------------------------------------------------------------- */
if (!reduce) {
  const progressBar = document.createElement('div');
  progressBar.className = 'scroll-progress';
  document.body.appendChild(progressBar);
  
  scroll(animate(progressBar, { scaleX: [0, 1] }, { ease: 'linear' }));
}

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
   Reveal helpers — enhanced with spring physics
   --------------------------------------------------------------------------- */
const revealLines = (root, delay = 0) =>
  animate($$('.line > span', root), { y: ['110%', '0%'] }, { duration: 0.9, ease, delay: stagger(0.09, { startDelay: delay }) });

const fadeUp = (els, delay = 0) =>
  animate(els, { opacity: [0, 1], y: [24, 0] }, { duration: 0.85, ease: easeOut, delay: stagger(0.08, { startDelay: delay }) });

const scaleIn = (els, delay = 0) =>
  animate(els, { opacity: [0, 1], scale: [0.92, 1], y: [16, 0] }, { duration: 0.7, ease: easeOut, delay: stagger(0.06, { startDelay: delay }) });

const slideInLeft = (els, delay = 0) =>
  animate(els, { opacity: [0, 1], x: [-40, 0] }, { duration: 0.8, ease: easeOut, delay: stagger(0.1, { startDelay: delay }) });

const slideInRight = (els, delay = 0) =>
  animate(els, { opacity: [0, 1], x: [40, 0] }, { duration: 0.8, ease: easeOut, delay: stagger(0.1, { startDelay: delay }) });

if (!reduce) {
  /* Hero intro — enhanced with staggered reveals */
  revealLines($('.hero-title'), 0.15);
  fadeUp($$('.hero-copy [data-fade]'), 0.55);
  
  // Hero gallery: enhanced clip-path reveal with scale
  $$('.hero-gallery [data-reveal]').forEach((fig, i) => {
    animate(fig, { clipPath: ['inset(100% 0 0 0)', 'inset(0% 0 0 0)'] }, { duration: 1.1, ease, delay: 0.3 + i * 0.15 });
    animate($('img', fig), { scale: [1.25, 1] }, { duration: 1.6, ease, delay: 0.3 + i * 0.15 });
  });

  /* Subtle image parallax tied to scroll — enhanced depth */
  $$('.hg img').forEach((img) => {
    scroll(animate(img, { y: ['0%', '-9%'] }, { ease: 'linear' }), {
      target: img.parentElement,
      offset: ['start end', 'end start'],
    });
  });

  /* Section titles with enhanced reveal */
  $$('.section .split').forEach((t) => inView(t, () => { revealLines(t); }, { amount: 0.4 }));

  /* Generic fades (outside hero) — enhanced */
  $$('[data-fade]')
    .filter((el) => !el.closest('.hero'))
    .forEach((el) => inView(el, () => { fadeUp(el, 0.15); }, { amount: 0.3 }));

  /* Product cards — enhanced stagger reveal with scale */
  inView('#rev-grid', (grid) => { 
    const revCards = $$('.rev', grid);
    animate(revCards, { 
      opacity: [0, 1], 
      y: [32, 0], 
      scale: [0.95, 1] 
    }, { 
      duration: 0.7, 
      ease: easeOut, 
      delay: stagger(0.1) 
    });
  }, { amount: 0.25 });

  /* Rating counter with spring animation */
  const rating = $('#rating');
  if (rating) {
    inView(rating, () => {
      animate(0, parseFloat(rating.dataset.to), {
        duration: 1.6,
        ease,
        onUpdate: (v) => (rating.textContent = v.toFixed(1).replace('.', ',')),
      });
      // Animate the rating block scale
      const ratingBlock = rating.closest('.rating-block');
      if (ratingBlock) {
        animate(ratingBlock, { scale: [0.85, 1], opacity: [0, 1] }, { duration: 0.8, ease: easeOut });
      }
    }, { amount: 0.6 });
  }

  /* CTA band: enhanced parallax + fade */
  const band = $('#cta');
  if (band) {
    scroll(animate(band, { backgroundPositionY: ['0%', '30%'] }, { ease: 'linear' }), {
      target: band,
      offset: ['start end', 'end start'],
    });
    const bandTitle = $('.band-title', band) || $('.band-heading', band);
    if (bandTitle) {
      inView(band, () => { 
        animate(bandTitle, { opacity: [0, 1], y: [30, 0], scale: [0.96, 1] }, { duration: 0.9, ease: easeOut });
      }, { amount: 0.3 });
    }
  }

  /* Footer columns staggered reveal */
  const footer = $('.footer');
  if (footer) {
    inView(footer, () => {
      fadeUp($$('.f-grid > *', footer), 0.1);
    }, { amount: 0.2 });
  }

  /* --------------------------------------------------------------------------
     Magnetic hover effect on buttons
     -------------------------------------------------------------------------- */
  $$('.btn, .tab').forEach((btn) => {
    btn.addEventListener('pointermove', (e) => {
      const rect = btn.getBoundingClientRect();
      const x = (e.clientX - rect.left - rect.width / 2) * 0.15;
      const y = (e.clientY - rect.top - rect.height / 2) * 0.15;
      btn.style.transform = `translate(${x}px, ${y}px)`;
    });
    btn.addEventListener('pointerleave', () => {
      animate(btn, { transform: 'translate(0px, 0px)' }, { duration: 0.4, ease: easeOut });
    });
  });

  /* --------------------------------------------------------------------------
     Floating particles background effect on hero
     -------------------------------------------------------------------------- */
  const hero = $('.hero');
  if (hero) {
    const particleContainer = document.createElement('div');
    particleContainer.className = 'hero-particles';
    hero.appendChild(particleContainer);

    for (let i = 0; i < 12; i++) {
      const particle = document.createElement('span');
      particle.className = 'particle';
      particle.style.setProperty('--x', `${Math.random() * 100}%`);
      particle.style.setProperty('--y', `${Math.random() * 100}%`);
      particle.style.setProperty('--size', `${3 + Math.random() * 5}px`);
      particle.style.setProperty('--duration', `${6 + Math.random() * 8}s`);
      particle.style.setProperty('--delay', `${Math.random() * 5}s`);
      particleContainer.appendChild(particle);
    }
  }

  /* --------------------------------------------------------------------------
     Cursor follower glow
     -------------------------------------------------------------------------- */
  const glow = document.createElement('div');
  glow.className = 'cursor-glow';
  document.body.appendChild(glow);
  
  let glowX = 0, glowY = 0, curX = 0, curY = 0;
  document.addEventListener('pointermove', (e) => {
    curX = e.clientX;
    curY = e.clientY;
  });
  
  (function glowLoop() {
    glowX += (curX - glowX) * 0.08;
    glowY += (curY - glowY) * 0.08;
    glow.style.transform = `translate(${glowX - 150}px, ${glowY - 150}px)`;
    requestAnimationFrame(glowLoop);
  })();

  /* --------------------------------------------------------------------------
     Scroll-triggered section divider lines
     -------------------------------------------------------------------------- */
  $$('.section').forEach((section) => {
    inView(section, () => {
      const divider = section.querySelector('.sec-divider');
      if (divider) {
        animate(divider, { scaleX: [0, 1] }, { duration: 1.2, ease });
      }
    }, { amount: 0.1 });
  });
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
  if (animateIn && !reduce) {
    // Enhanced card animation with scale and stagger
    animate(visible, { 
      opacity: [0, 1], 
      y: [28, 0], 
      scale: [0.94, 1] 
    }, { 
      duration: 0.6, 
      ease: easeOut, 
      delay: stagger(0.07) 
    });
  }
  else visible.forEach((c) => (c.style.opacity = 1));
};

if (tabs) {
  const active = $('.tab.active', tabs);
  if (pill && active) movePill(active);
  filterCards('all', false);
  if (!reduce) cards.forEach((c) => (c.style.opacity = 0));

  tabs.addEventListener('click', (e) => {
    const btn = e.target.closest('.tab');
    if (!btn || btn.classList.contains('active')) return;
    $$('.tab', tabs).forEach((t) => t.classList.toggle('active', t === btn));
    if (pill) movePill(btn);
    filterCards(btn.dataset.cat, true);
  });

  if (pill) {
    addEventListener('resize', () => movePill($('.tab.active', tabs)));
    document.fonts?.ready.then(() => movePill($('.tab.active', tabs)));
  }
}

if (!reduce) {
  inView('#cards', (grid) => {
    cardsSeen = true;
    const visibleCards = $$('.card:not([hidden])', grid);
    animate(visibleCards, { 
      opacity: [0, 1], 
      y: [28, 0], 
      scale: [0.94, 1] 
    }, { 
      duration: 0.6, 
      ease: easeOut, 
      delay: stagger(0.07) 
    });
  }, { amount: 0.2 });
}
