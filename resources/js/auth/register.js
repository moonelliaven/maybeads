import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

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

    /* Handle Form Register dengan SweetAlert2 */
    var form = $('#form');
    var submitBtn = form ? form.querySelector('button[type="submit"]') : null;

    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        var name = $('#name') ? $('#name').value.trim() : '';
        var email = $('#email') ? $('#email').value.trim() : '';
        var pw = $('#password') ? $('#password').value : '';
        var pwConfirm = $('#password_confirmation') ? $('#password_confirmation').value : '';

        if (!name || !email || !pw) {
          Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Harap isi semua kolom formulir pendaftaran.',
            confirmButtonColor: '#3a4f98',
            customClass: {
              popup: 'y2k-swal-popup'
            }
          });
          return;
        }

        if (pw.length < 8) {
          Swal.fire({
            icon: 'warning',
            title: 'Kata Sandi Terlalu Pendek',
            text: 'Kata sandi minimal harus terdiri dari 8 karakter.',
            confirmButtonColor: '#3a4f98',
            customClass: {
              popup: 'y2k-swal-popup'
            }
          });
          return;
        }

        if (pw !== pwConfirm) {
          Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi Sandi Berbeda',
            text: 'Kata sandi dan konfirmasi kata sandi tidak cocok.',
            confirmButtonColor: '#3a4f98',
            customClass: {
              popup: 'y2k-swal-popup'
            }
          });
          return;
        }

        var originalBtnText = submitBtn ? submitBtn.textContent : 'Daftar sekarang';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Mendaftar...';
        }

        var formData = new FormData(form);
        var csrfToken = document.querySelector('meta[name="csrf-token"]') 
          ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
          : '';

        fetch(form.action || '/register', {
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
            Swal.fire({
              icon: 'success',
              title: 'Pendaftaran Berhasil!',
              text: res.data.message || 'Selamat datang di Maybeads! Mengalihkan...',
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

            var errMsg = 'Terjadi kesalahan saat pendaftaran.';
            if (res.data && res.data.errors) {
              var firstKey = Object.keys(res.data.errors)[0];
              if (firstKey && res.data.errors[firstKey].length) {
                errMsg = res.data.errors[firstKey][0];
              }
            } else if (res.data && res.data.message) {
              errMsg = res.data.message;
            }

            Swal.fire({
              icon: 'error',
              title: 'Gagal Mendaftar',
              text: errMsg,
              confirmButtonText: 'Periksa Kembali',
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
          console.error('Register error:', err);
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
    document.addEventListener('DOMContentLoaded', initRegister);
  } else {
    initRegister();
  }
})();
