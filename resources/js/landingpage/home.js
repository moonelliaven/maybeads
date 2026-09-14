import Lenis from 'lenis';

document.documentElement.classList.add('js-ready');

const lenis = new Lenis({
    duration: 1.2,
    smoothWheel: true,
    wheelMultiplier: 0.9,
    lerp: 0.08,
    touchMultiplier: 1.2,
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

const revealItems = document.querySelectorAll('.reveal-up');

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        }
    });
}, {
    threshold: 0.15,
    rootMargin: '0px 0px -30px 0px',
});

revealItems.forEach((item) => {
    observer.observe(item);
});

document.querySelectorAll('.tab').forEach((tab) => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach((el) => el.classList.remove('active'));
        tab.classList.add('active');
    });
});
