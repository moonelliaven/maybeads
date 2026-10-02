import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

(function () {
  'use strict';

  function initLogin() {
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
    var eye = $('#eye'), pw = $('#password');
    if (eye && pw) {
      eye.addEventListener('click', function () {
        var show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        eye.textContent = show ? 'Sembunyi' : 'Lihat';
        eye.setAttribute('aria-pressed', show);
        eye.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
      });
    }

    /* Tombol magnetik */
    $$('[data-magnet]').forEach(function (b) {
      b.addEventListener('pointermove', function (e) {
        var r = b.getBoundingClientRect();
        b.style.transform = 'translate(' + (e.clientX - r.left - r.width / 2) * .25 + 'px,' + (e.clientY - r.top - r.height / 2) * .35 + 'px)';
      });
      b.addEventListener('pointerleave', function () { b.style.transform = ''; });
    });

    /* Stiker mengikuti mouse + Lenis RAF */
    if (!reduce) {
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

    /* Handle Form Login dengan SweetAlert2 */
    var form = $('#form');
    var submitBtn = form ? form.querySelector('button[type="submit"]') : null;

    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        var emailInput = $('#email');
        var passwordInput = $('#password');

        var email = emailInput ? emailInput.value.trim() : '';
        var password = passwordInput ? passwordInput.value : '';

        // Validasi sederhana client-side
        if (!email || !password) {
          Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Harap isi email dan kata sandi Anda.',
            confirmButtonColor: '#1f3bff',
            customClass: {
              popup: 'y2k-swal-popup'
            }
          });
          return;
        }

        var originalBtnText = submitBtn ? submitBtn.textContent : 'Masuk';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Memproses...';
        }

        var formData = new FormData(form);
        var csrfToken = document.querySelector('meta[name="csrf-token"]') 
          ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
          : '';

        fetch(form.action || '/login', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
          },
          body: formData
        })
        .then(function (response) {
          return response.json().then(function (data) {
            return { ok: response.ok, status: response.status, data: data };
          });
        })
        .then(function (res) {
          if (res.ok && res.data.success) {
            var isAdmin = res.data.role === 'admin';
            Swal.fire({
              icon: 'success',
              title: 'Berhasil Masuk!',
              text: isAdmin 
                ? 'Selamat datang Admin! Mengalihkan ke dashboard...' 
                : (res.data.message || 'Selamat datang kembali! Mengalihkan...'),
              timer: 1600,
              timerProgressBar: true,
              showConfirmButton: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              customClass: {
                popup: 'y2k-swal-popup'
              },
              willClose: function () {
                window.location.href = res.data.redirect || '/';
              }
            });
          } else {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.textContent = originalBtnText;
            }

            var errMsg = (res.data && res.data.message) ? res.data.message : 'Email atau kata sandi yang Anda masukkan salah.';
            if (res.data && res.data.errors && res.data.errors.email) {
              errMsg = res.data.errors.email[0];
            }

            Swal.fire({
              icon: 'error',
              title: 'Gagal Masuk',
              text: errMsg,
              confirmButtonText: 'Coba Lagi',
              confirmButtonColor: '#ef4444',
              customClass: {
                popup: 'y2k-swal-popup'
              }
            });
          }
        })
        .catch(function (err) {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
          }
          console.error('Login error:', err);
          Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Gagal menghubungi server. Silakan coba lagi nanti.',
            confirmButtonColor: '#ef4444',
            customClass: {
              popup: 'y2k-swal-popup'
            }
          });
        });
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLogin);
  } else {
    initLogin();
  }
})();
