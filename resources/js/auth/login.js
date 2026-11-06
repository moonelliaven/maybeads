import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { animate, stagger, inView } from 'motion';

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

    /* ========================================================================
       Motion Graph Animations
       ======================================================================== */
    var easeOut = [0.16, 1, 0.3, 1];
    var easeBounce = [0.34, 1.56, 0.64, 1];

    if (!reduce) {
      /* Auth card entrance — scale + fade from bottom */
      var authCard = $('.auth-card');
      if (authCard) {
        animate(authCard, 
          { opacity: [0, 1], y: [40, 0], scale: [0.96, 1] },
          { duration: 0.9, ease: easeOut, delay: 0.1 }
        );
      }

      /* Form elements staggered entrance */
      var authInner = $('.auth-inner');
      if (authInner) {
        var innerChildren = $$(
          '.auth-brand, .auth-title, .auth-sub, .alert, .auth-fields, .or, .google, .alt, .auth-foot'
        ).filter(function(el) { return authInner.contains(el); });
        
        if (innerChildren.length) {
          // Set initial hidden state
          innerChildren.forEach(function(el) { 
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
          });
          
          animate(innerChildren, 
            { opacity: [0, 1], y: [16, 0] },
            { duration: 0.7, ease: easeOut, delay: stagger(0.07, { startDelay: 0.3 }) }
          );
        }
      }

      /* Form fields individual entrance with slide */
      var formFields = $$('.auth-fields .f');
      if (formFields.length) {
        formFields.forEach(function(field) { 
          field.style.opacity = '0';
          field.style.transform = 'translateX(-12px)';
        });
        
        animate(formFields, 
          { opacity: [0, 1], x: [-12, 0] },
          { duration: 0.6, ease: easeOut, delay: stagger(0.08, { startDelay: 0.5 }) }
        );
      }

      /* Submit button bounce entrance */
      var submitBtn = $('.submit');
      if (submitBtn) {
        submitBtn.style.opacity = '0';
        submitBtn.style.transform = 'scale(0.9)';
        animate(submitBtn, 
          { opacity: [0, 1], scale: [0.9, 1] },
          { duration: 0.6, ease: easeBounce, delay: 0.85 }
        );
      }

      /* Auth art image zoom and fade */
      var authArt = $('.auth-art img');
      if (authArt) {
        animate(authArt, 
          { scale: [1.2, 1.09], filter: ['saturate(0.3) brightness(0.8)', 'saturate(1) brightness(1)'] },
          { duration: 1.6, ease: easeOut, delay: 0.2 }
        );
      }

      /* Input focus glow animation */
      $$('.inp').forEach(function(inp) {
        var input = inp.querySelector('input');
        if (input) {
          input.addEventListener('focus', function() {
            animate(inp, 
              { boxShadow: ['0 0 0 0px rgba(53,83,137,0)', '0 0 0 4px rgba(53,83,137,0.12)'] },
              { duration: 0.3, ease: easeOut }
            );
          });
          input.addEventListener('blur', function() {
            animate(inp, 
              { boxShadow: '0 0 0 0px rgba(53,83,137,0)' },
              { duration: 0.3, ease: easeOut }
            );
          });
        }
      });

      /* Google button hover ripple */
      var googleBtn = $('.google');
      if (googleBtn) {
        googleBtn.addEventListener('mouseenter', function() {
          animate(googleBtn, 
            { scale: [1, 1.02], boxShadow: ['0 0 0 0px rgba(53,83,137,0)', '0 8px 24px -8px rgba(53,83,137,0.15)'] },
            { duration: 0.3, ease: easeOut }
          );
        });
        googleBtn.addEventListener('mouseleave', function() {
          animate(googleBtn, 
            { scale: [1.02, 1], boxShadow: '0 0 0 0px rgba(53,83,137,0)' },
            { duration: 0.3, ease: easeOut }
          );
        });
      }

      /* Floating particles on auth page */
      var authPage = $('.auth-page');
      if (authPage) {
        var particleContainer = document.createElement('div');
        particleContainer.className = 'auth-particles';
        authPage.appendChild(particleContainer);

        for (var i = 0; i < 8; i++) {
          var particle = document.createElement('span');
          particle.className = 'auth-particle';
          particle.style.setProperty('--x', (Math.random() * 100) + '%');
          particle.style.setProperty('--y', (Math.random() * 100) + '%');
          particle.style.setProperty('--size', (3 + Math.random() * 4) + 'px');
          particle.style.setProperty('--duration', (8 + Math.random() * 10) + 's');
          particle.style.setProperty('--delay', (Math.random() * 5) + 's');
          particleContainer.appendChild(particle);
        }
      }
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
        eye.setAttribute('aria-pressed', show);
        eye.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        
        /* Animate eye toggle */
        if (!reduce) {
          animate(eye, { scale: [0.8, 1.1, 1] }, { duration: 0.35, ease: easeOut });
        }
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
    var submitButton = form ? form.querySelector('button[type="submit"]') : null;

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
            confirmButtonColor: '#3a4f98',
            customClass: {
              popup: 'y2k-swal-popup'
            }
          });
          return;
        }

        var originalBtnText = submitButton ? submitButton.textContent : 'Masuk';
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.textContent = 'Memproses...';
          /* Animate button loading state */
          if (!reduce) {
            animate(submitButton, { opacity: [1, 0.7] }, { duration: 0.8, ease: 'linear', repeat: Infinity, direction: 'alternate' });
          }
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
          var isJson = (response.headers.get('content-type') || '').includes('application/json');
          if (isJson) {
            return response.json().then(function (data) {
              return { ok: response.ok, status: response.status, data: data };
            });
          }
          return {
            ok: response.ok,
            status: response.status,
            data: { success: response.ok, message: response.ok ? 'Berhasil' : 'Gagal memproses permintaan.' }
          };
        })
        .then(function (res) {
          if (res.ok && res.data.success) {
            var isAdmin = res.data.role === 'admin';
            
            /* Success animation on card */
            if (!reduce) {
              var card = $('.auth-card');
              if (card) {
                animate(card, { scale: [1, 0.98, 1.01, 1], borderColor: ['rgba(20,20,24,0.55)', '#34a853'] }, { duration: 0.5, ease: easeOut });
              }
            }

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
            if (submitButton) {
              submitButton.disabled = false;
              submitButton.textContent = originalBtnText;
              submitButton.style.opacity = '1';
            }

            /* Error shake animation on card */
            if (!reduce) {
              var card = $('.auth-card');
              if (card) {
                animate(card, { x: [0, -8, 8, -6, 6, -3, 3, 0] }, { duration: 0.5, ease: easeOut });
              }
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
          if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = originalBtnText;
            submitButton.style.opacity = '1';
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
