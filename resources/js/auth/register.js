(function () {
  'use strict';

  function initRegister() {
    var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    var $ = function (s) { return document.querySelector(s); };
    var $$ = function (s) { return [].slice.call(document.querySelectorAll(s)); };

    /* Inisialisasi Lenis Smooth Scroll */
    var lenis = null;
    if (typeof Lenis !== 'undefined' && !reduce) {
      lenis = new Lenis({
        duration: 1.2,
        easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); },
        smoothWheel: true,
        touchMultiplier: 1.5,
      });
    }

    /* Judul: huruf naik satu-satu */
    var big = $('#big');
    if (big && big.dataset && big.dataset.t) {
      big.innerHTML = '';
      big.dataset.t.split('').forEach(function (c, i) {
        var sp = document.createElement('span');
        sp.style.setProperty('--i', i);
        sp.textContent = c;
        big.appendChild(sp);
      });
    }

    /* Navbar: pill saat scroll */
    var nav = $('#nav');
    function onScroll() {
      if (nav) nav.classList.toggle('scrolled', window.scrollY > 40);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    /* Tampilkan / sembunyikan kata sandi */
    function toggleEye(eyeBtn, pwInput) {
      if (!eyeBtn || !pwInput) return;
      eyeBtn.addEventListener('click', function () {
        var show = pwInput.type === 'password';
        pwInput.type = show ? 'text' : 'password';
        eyeBtn.textContent = show ? 'Sembunyi' : 'Lihat';
        eyeBtn.setAttribute('aria-pressed', show);
        eyeBtn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
      });
    }
    toggleEye($('#eye'), $('#password'));
    toggleEye($('#eye2'), $('#password_confirmation'));

    /* Tombol magnetik */
    $$('[data-magnet]').forEach(function (b) {
      b.addEventListener('pointermove', function (e) {
        var r = b.getBoundingClientRect();
        b.style.transform = 'translate(' + (e.clientX - r.left - r.width / 2) * .25 + 'px,' + (e.clientY - r.top - r.height / 2) * .35 + 'px)';
      });
      b.addEventListener('pointerleave', function () { b.style.transform = ''; });
    });

    /* Stiker mengikuti mouse + Lenis RAF */
    if (reduce) return;
    var mx = 0, my = 0;
    var stickers = $$('[data-mouse]');
    window.addEventListener('pointermove', function (e) {
      mx = e.clientX / window.innerWidth - .5;
      my = e.clientY / window.innerHeight - .5;
    });
    (function frame(time) {
      if (lenis) lenis.raf(time);
      stickers.forEach(function (el) {
        var m = +el.dataset.mouse;
        el.style.transform = 'translate3d(' + mx * m + 'px,' + my * m + 'px,0) rotate(' + mx * m * .5 + 'deg)';
      });
      requestAnimationFrame(frame);
    })(performance.now());
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRegister);
  } else {
    initRegister();
  }
})();
