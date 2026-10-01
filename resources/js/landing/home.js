(function () {
  'use strict';

  function initHome() {
      'use strict';

      var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
      var $ = function (s) { return document.querySelector(s); };
      var $$ = function (s) { return [].slice.call(document.querySelectorAll(s)); };
      var clamp = function (v) { return Math.max(0, Math.min(1, v)); };

      /* Inisialisasi Lenis Smooth Scroll */
      var lenis = null;
      if (typeof Lenis !== 'undefined' && !reduce) {
        lenis = new Lenis({
          duration: 1.2,
          easing: function (t) {
            return Math.min(1, 1.001 - Math.pow(2, -10 * t));
          },
          orientation: 'vertical',
          gestureOrientation: 'vertical',
          smoothWheel: true,
          wheelMultiplier: 1,
          touchMultiplier: 1.5,
        });
      }

      /* Smooth scroll navigasi anchor menggunakan Lenis */
      $$('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
          var targetId = this.getAttribute('href');
          if (targetId && targetId !== '#') {
            var targetEl = document.querySelector(targetId);
            if (targetEl) {
              e.preventDefault();
              if (lenis) {
                lenis.scrollTo(targetEl, { offset: 0, duration: 1.2 });
              } else {
                targetEl.scrollIntoView({ behavior: 'smooth' });
              }
            }
          }
        });
      });

      /* Judul hero: huruf naik satu-satu */
      var i = 0;
      $$('#title .ln').forEach(function (ln) {
        ln.dataset.t.split('').forEach(function (c) {
          var s = document.createElement('span');
          s.className = 'ch' + (c === '.' ? ' dot' : '');
          s.style.setProperty('--i', i++);
          s.textContent = c;
          ln.appendChild(s);
        });
      });

      /* Split kata untuk heading */
      $$('[data-split]').forEach(function (h) {
        var words = h.textContent.trim().split(' ');
        h.textContent = '';
        words.forEach(function (w, k) {
          var o = document.createElement('span');
          o.className = 'w';
          var n = document.createElement('span');
          n.textContent = w;
          n.style.setProperty('--i', k);
          o.appendChild(n);
          h.appendChild(o);
          h.appendChild(document.createTextNode(' '));
        });
      });

      /* Reveal saat masuk viewport + count-up */
      var io = new IntersectionObserver(function (es) {
        es.forEach(function (e) {
          if (!e.isIntersecting) return;
          e.target.classList.add('in');
          io.unobserve(e.target);

          $$('[data-count]').forEach(function (c) {
            if (!e.target.contains(c)) return;
            var to = +c.dataset.count;
            var t0 = performance.now();
            (function tick(t) {
              var p = clamp((t - t0) / 1400);
              c.textContent = Math.round(to * (1 - Math.pow(1 - p, 3)));
              if (p < 1) requestAnimationFrame(tick);
            })(t0);
          });
        });
      }, { threshold: 0.2 });

      $$('.rv, .split').forEach(function (el, k) {
        el.style.setProperty('--d', (k % 3) * 90 + 'ms');
        io.observe(el);
      });

      /* Manik-manik di kartu */
      $$('.art').forEach(function (el) {
        var cols = el.dataset.beads.split(',');
        for (var k = 0; k < 9; k++) {
          var b = document.createElement('i');
          var s = 26 + Math.round(Math.random() * 28);
          b.style.cssText =
            'width:' + s + 'px;' +
            'height:' + s + 'px;' +
            'background:' + cols[k % cols.length] + ';' +
            'left:' + (6 + k * 10 + Math.random() * 4) + '%;' +
            'top:' + (26 + Math.sin(k / 1.4) * 22 + 24) + '%;' +
            'transition-delay:' + (k * 30) + 'ms';
          el.appendChild(b);
        }
      });

      /* Marquee: kecepatan & arah mengikuti scroll */
      function fill(id, words) {
        var el = $(id);
        var h = '';
        for (var r = 0; r < 4; r++) {
          words.forEach(function (w) {
            h += '<span>' + w + '</span>';
          });
        }
        el.innerHTML = h;
        return el;
      }
      var m1 = fill('#m1', ['manik-manik', 'charm hp', 'kalung chrome', 'jepit kupu-kupu']);
      var m2 = fill('#m2', ['y2k forever', 'gelang bubblegum', 'drop baru', 'handmade']);
      var x1 = 0;
      var x2 = 0;

      /* State */
      var mx = 0;
      var my = 0;
      var gx = innerWidth / 2;
      var gy = innerHeight / 2;
      var tx = gx;
      var ty = gy;
      var ly = lenis ? lenis.scroll : window.scrollY;
      var vel = 0;

      var stickers = $$('[data-speed]');
      var track = $('#track');
      var hs = $('#product');
      var bar = $('#bar');
      var shape = $('#shape');
      var morph = $('#morph');
      var nav = $('#nav');
      var glow = $('#glow');
      var title = $('#title');
      var cards = $$('.card');

      addEventListener('pointermove', function (e) {
        mx = e.clientX / innerWidth - 0.5;
        my = e.clientY / innerHeight - 0.5;
        tx = e.clientX;
        ty = e.clientY;
      });

      /* Tombol magnetik */
      $$('[data-magnet]').forEach(function (b) {
        b.addEventListener('pointermove', function (e) {
          var r = b.getBoundingClientRect();
          b.style.transform =
            'translate(' +
            (e.clientX - r.left - r.width / 2) * 0.25 + 'px,' +
            (e.clientY - r.top - r.height / 2) * 0.35 + 'px)';
        });
        b.addEventListener('pointerleave', function () {
          b.style.transform = '';
        });
      });

      /* Morph lingkaran -> bintang */
      function drawShape(p) {
        var n = 24;
        var pts = [];
        for (var k = 0; k < n; k++) {
          var a = (k / n) * Math.PI * 2 - Math.PI / 2 + p * 3.1;
          var r = 100 + ((k % 2 ? 50 : 104) - 100) * p;
          pts.push((Math.cos(a) * r).toFixed(1) + ',' + (Math.sin(a) * r).toFixed(1));
        }
        shape.setAttribute('points', pts.join(' '));
      }
      drawShape(0);

      /* Animation Frame Loop dengan Lenis integration */
      function frame(time) {
        if (lenis) {
          lenis.raf(time);
        }

        var y = lenis ? lenis.scroll : window.scrollY;
        vel += ((y - ly) - vel) * 0.1;
        ly = y;
        nav.classList.toggle('scrolled', y > 40);

        if (!reduce) {
          gx += (tx - gx) * 0.08;
          gy += (ty - gy) * 0.08;
          glow.style.transform = 'translate3d(' + gx + 'px,' + gy + 'px,0)';

          stickers.forEach(function (el) {
            var sp = +el.dataset.speed;
            var m = +(el.dataset.mouse || 0);
            el.style.transform =
              'translate3d(' + mx * m + 'px,' + (y * sp + my * m) + 'px,0) rotate(' + (y * sp * 0.08) + 'deg)';
          });

          title.style.transform =
            'translate3d(' + (mx * -14) + 'px,' + (y * -0.12 + my * -10) + 'px,0) scale(' + (1 - clamp(y / 1200) * 0.12) + ')';

          x1 -= 0.6 + Math.abs(vel) * 0.5;
          x2 += 0.6 + Math.abs(vel) * 0.5;
          var w1 = m1.scrollWidth / 2;
          var w2 = m2.scrollWidth / 2;
          x1 = -((-x1) % w1);
          x2 = -w2 + (x2 % w2);
          m1.style.transform = 'translate3d(' + x1 + 'px,0,0) skewX(' + Math.max(-10, Math.min(10, -vel * 0.3)) + 'deg)';
          m2.style.transform = 'translate3d(' + x2 + 'px,0,0) skewX(' + Math.max(-10, Math.min(10, -vel * 0.3)) + 'deg)';
        }

        /* Horizontal scroll + efek 3D per kartu */
        var r = hs.getBoundingClientRect();
        var p = clamp(-r.top / (hs.offsetHeight - innerHeight));
        track.style.transform = 'translate3d(' + (-p * (track.scrollWidth - innerWidth + innerWidth * 0.1)) + 'px,0,0)';
        bar.style.width = p * 100 + '%';

        if (!reduce) {
          cards.forEach(function (c) {
            var b = c.getBoundingClientRect();
            var d = (b.left + b.width / 2 - innerWidth / 2) / innerWidth;
            c.style.transform =
              'rotateY(' + (-d * 18) + 'deg) translateY(' + (Math.abs(d) * 30) + 'px) scale(' + (1 - Math.abs(d) * 0.12) + ')';
          });
        }

        var v = morph.getBoundingClientRect();
        drawShape(reduce ? 0 : clamp((innerHeight * 0.8 - v.top) / (v.height + innerHeight * 0.3)));

        requestAnimationFrame(frame);
      }
      requestAnimationFrame(frame);

      /* Newsletter submit form */
      $('#form').addEventListener('submit', function (e) {
        e.preventDefault();
        $('#ok').textContent = 'Terdaftar! Info drop berikutnya akan dikirim ke emailmu.';
        e.target.reset();
      });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHome);
  } else {
    initHome();
  }
})();
