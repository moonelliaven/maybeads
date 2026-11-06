import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { animate, stagger, inView } from 'motion';

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

    /* ========================================================================
       Motion Graph Animations
       ======================================================================== */
    var easeOut = [0.16, 1, 0.3, 1];
    var easeBounce = [0.34, 1.56, 0.64, 1];

    if (!reduce) {
      /* Auth card entrance — slide from right + fade */
      var authCard = $('.auth-card');
      if (authCard) {
        animate(authCard, 
          { opacity: [0, 1], x: [30, 0], scale: [0.97, 1] },
          { duration: 0.9, ease: easeOut, delay: 0.1 }
        );
      }

      /* Form elements staggered entrance — cascade from top */
      var authInner = $('.auth-inner');
      if (authInner) {
        var innerChildren = $$(
          '.auth-brand, .auth-title, .auth-sub, .auth-fields, .or, .google, .alt, .auth-foot'
        ).filter(function(el) { return authInner.contains(el); });
        
        if (innerChildren.length) {
          innerChildren.forEach(function(el) { 
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
          });
          
          animate(innerChildren, 
            { opacity: [0, 1], y: [16, 0] },
            { duration: 0.7, ease: easeOut, delay: stagger(0.06, { startDelay: 0.25 }) }
          );
        }
      }

      /* Form fields entrance with alternating slide direction */
      var formFields = $$('.auth-fields .f');
      if (formFields.length) {
        formFields.forEach(function(field, index) { 
          field.style.opacity = '0';
          field.style.transform = index % 2 === 0 ? 'translateX(-14px)' : 'translateX(14px)';
        });
        
        formFields.forEach(function(field, index) {
          var fromX = index % 2 === 0 ? -14 : 14;
          animate(field, 
            { opacity: [0, 1], x: [fromX, 0] },
            { duration: 0.6, ease: easeOut, delay: 0.45 + index * 0.08 }
          );
        });
      }

      /* Submit button spring entrance */
      var submitBtn = $('.submit');
      if (submitBtn) {
        submitBtn.style.opacity = '0';
        submitBtn.style.transform = 'scale(0.88)';
        animate(submitBtn, 
          { opacity: [0, 1], scale: [0.88, 1.03, 1] },
          { duration: 0.7, ease: easeBounce, delay: 0.9 }
        );
      }

      /* Auth art image reveal with ken burns */
      var authArt = $('.auth-art img');
      if (authArt) {
        animate(authArt, 
          { scale: [1.25, 1.09], filter: ['saturate(0.2) brightness(0.7)', 'saturate(1) brightness(1)'] },
          { duration: 1.8, ease: easeOut, delay: 0.15 }
        );
      }

      /* Input focus animation with border glow */
      $$('.inp').forEach(function(inp) {
        var input = inp.querySelector('input');
        if (input) {
          input.addEventListener('focus', function() {
            animate(inp, 
              { boxShadow: ['0 0 0 0px rgba(53,83,137,0)', '0 0 0 4px rgba(53,83,137,0.12)'], borderColor: ['', 'var(--ink)'] },
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

      /* Google button interactive hover */
      var googleBtn = $('.google');
      if (googleBtn) {
        googleBtn.addEventListener('mouseenter', function() {
          animate(googleBtn, 
            { scale: [1, 1.02], boxShadow: '0 8px 24px -8px rgba(53,83,137,0.15)' },
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

      /* Password strength indicator animation */
      var pwInput = $('#password');
      if (pwInput) {
        var strengthBar = document.createElement('div');
        strengthBar.className = 'pw-strength';
        var strengthFill = document.createElement('div');
        strengthFill.className = 'pw-strength-fill';
        strengthBar.appendChild(strengthFill);
        pwInput.closest('.f').appendChild(strengthBar);

        pwInput.addEventListener('input', function() {
          var val = pwInput.value;
          var strength = 0;
          if (val.length >= 4) strength += 25;
          if (val.length >= 8) strength += 25;
          if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength += 25;
          if (/[0-9!@#$%^&*]/.test(val)) strength += 25;

          var color = strength <= 25 ? '#ef4444' : strength <= 50 ? '#f59e0b' : strength <= 75 ? '#3b82f6' : '#22c55e';
          animate(strengthFill, 
            { width: strength + '%', backgroundColor: color },
            { duration: 0.4, ease: easeOut }
          );
        });
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
    function toggleEye(eyeBtn, pwInput) {
      if (!eyeBtn || !pwInput) return;
      eyeBtn.addEventListener('click', function () {
        var show = pwInput.type === 'password';
        pwInput.type = show ? 'text' : 'password';
        eyeBtn.setAttribute('aria-pressed', show);
        eyeBtn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        
        /* Animate eye toggle */
        if (!reduce) {
          animate(eyeBtn, { scale: [0.8, 1.1, 1], rotate: [0, show ? 180 : -180, 0] }, { duration: 0.4, ease: easeOut });
        }
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
    var submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();

        var name = $('#name') ? $('#name').value.trim() : '';
        var email = $('#email') ? $('#email').value.trim() : '';
        var pw = $('#password') ? $('#password').value : '';
        var pwConfirm = $('#password_confirmation') ? $('#password_confirmation').value : '';

        if (!name || !email || !pw) {
          /* Shake the empty fields */
          if (!reduce) {
            $$('.auth-fields .f').forEach(function(field) {
              var input = field.querySelector('input');
              if (input && !input.value.trim()) {
                animate(field, { x: [0, -6, 6, -4, 4, -2, 2, 0] }, { duration: 0.45, ease: easeOut });
              }
            });
          }
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
          /* Highlight mismatched password fields */
          if (!reduce) {
            var confirmField = $('#password_confirmation');
            if (confirmField) {
              var inp = confirmField.closest('.inp');
              if (inp) {
                animate(inp, { borderColor: ['var(--field)', '#ef4444', 'var(--field)'] }, { duration: 0.8, ease: easeOut });
              }
            }
          }
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

        var originalBtnText = submitButton ? submitButton.textContent : 'Daftar sekarang';
        if (submitButton) {
          submitButton.disabled = true;
          submitButton.textContent = 'Mendaftar...';
          /* Animate button loading state */
          if (!reduce) {
            animate(submitButton, { opacity: [1, 0.7] }, { duration: 0.8, ease: 'linear', repeat: Infinity, direction: 'alternate' });
          }
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
            /* Success animation — card pulse green */
            if (!reduce) {
              var card = $('.auth-card');
              if (card) {
                animate(card, { scale: [1, 0.98, 1.02, 1], borderColor: ['rgba(20,20,24,0.55)', '#22c55e'] }, { duration: 0.6, ease: easeOut });
              }
            }

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
            if (submitButton) {
              submitButton.disabled = false;
              submitButton.textContent = originalBtnText;
              submitButton.style.opacity = '1';
            }

            /* Error shake animation */
            if (!reduce) {
              var card = $('.auth-card');
              if (card) {
                animate(card, { x: [0, -10, 10, -7, 7, -4, 4, 0] }, { duration: 0.5, ease: easeOut });
              }
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
          if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = originalBtnText;
            submitButton.style.opacity = '1';
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
