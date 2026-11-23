/* ==========================================================================
   Maybeads — Contact Page interactions
   Lenis (smooth scroll) + Motion (animate / scroll / inView / stagger / spring)
   Motion graph animations identical to landing home & auth
   ========================================================================== */
import Lenis from 'lenis';
import { animate, scroll, inView, stagger, spring } from 'motion';

const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
const ease = [0.22, 1, 0.36, 1];
const easeOut = [0.16, 1, 0.3, 1];
const easeBounce = [0.34, 1.56, 0.64, 1];
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

/* ---------------------------------------------------------------------------
   Navigation: smooth condensation + shadow on scroll (identik login & register)
   --------------------------------------------------------------------------- */
const nav = $('#nav');
const onScroll = (y) => {
  if (nav) nav.classList.toggle('scrolled', y > 40);
};
if (lenis) lenis.on('scroll', ({ scroll: y }) => onScroll(y));
else addEventListener('scroll', () => onScroll(scrollY), { passive: true });
onScroll(window.scrollY);

/* ---------------------------------------------------------------------------
   Scroll progress bar (Motion graph like home)
   --------------------------------------------------------------------------- */
if (!reduce) {
  const progressBar = document.createElement('div');
  progressBar.className = 'scroll-progress';
  document.body.appendChild(progressBar);

  scroll(animate(progressBar, { scaleX: [0, 1] }, { ease: 'linear' }));
}

/* ---------------------------------------------------------------------------
   Motion Graph Entrance & Reveal Animations
   --------------------------------------------------------------------------- */
if (!reduce) {
  /* 1. Header entrance animation on page load */
  if (nav) {
    animate(nav, { opacity: [0, 1], y: [-20, 0] }, { duration: 0.75, ease: easeOut });
  }

  /* 2. Top row: Heading slide & spring reveal */
  const heading = $('.contact-heading');
  if (heading) {
    animate(heading, 
      { opacity: [0, 1], x: [40, 0], scale: [0.95, 1] }, 
      { duration: 0.85, ease: easeOut, delay: 0.15 }
    );
  }

  /* 3. Top row: Social links cascade */
  const socialLinks = $$('.contact-socials a');
  if (socialLinks.length) {
    animate(socialLinks, 
      { opacity: [0, 1], x: [-30, 0] }, 
      { duration: 0.7, ease: easeOut, delay: stagger(0.08, { startDelay: 0.2 }) }
    );

    // Interactive spring hover effect
    socialLinks.forEach((link) => {
      link.addEventListener('mouseenter', () => {
        animate(link, { x: 5, color: 'var(--navy)' }, { duration: 0.25, ease: easeOut });
      });
      link.addEventListener('mouseleave', () => {
        animate(link, { x: 0, color: 'var(--ink)' }, { duration: 0.25, ease: easeOut });
      });
    });
  }

  /* 4. Map Container: reveal with clip-path & scale like hero gallery */
  const mapWrapper = $('.contact-map-wrapper');
  if (mapWrapper) {
    animate(mapWrapper, 
      { opacity: [0, 1], y: [35, 0], scale: [0.97, 1] }, 
      { duration: 0.9, ease: easeOut, delay: 0.35 }
    );
  }

  /* 5. Bottom Info Row: address and hours staggered reveal */
  const infoCards = $$('.contact-info > div');
  if (infoCards.length) {
    animate(infoCards, 
      { opacity: [0, 1], y: [24, 0] }, 
      { duration: 0.75, ease: easeOut, delay: stagger(0.12, { startDelay: 0.5 }) }
    );
  }

  /* 6. Footer columns inView entrance animation */
  const footerGrid = $('.f-grid');
  if (footerGrid) {
    inView(footerGrid, () => {
      animate(footerGrid.children, 
        { opacity: [0, 1], y: [28, 0] }, 
        { duration: 0.7, ease: easeOut, delay: stagger(0.09) }
      );
    }, { amount: 0.2 });
  }

  /* 7. Footer social icons hover interaction */
  $$('.f-soc-link').forEach((btn) => {
    btn.addEventListener('mouseenter', () => {
      animate(btn, { scale: 1.05, y: -2 }, { duration: 0.2, ease: easeOut });
    });
    btn.addEventListener('mouseleave', () => {
      animate(btn, { scale: 1, y: 0 }, { duration: 0.2, ease: easeOut });
    });
  });
}

