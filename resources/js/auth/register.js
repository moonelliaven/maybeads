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

    /* Handle Form Register dengan Verifikasi OTP & SweetAlert2 */
    var form = $('#form');
    var submitBtn = form ? form.querySelector('button[type="submit"]') : null;

    function showOtpModal(email, submitBtn, originalBtnText) {
      var countdown = 60;
      var timerInterval = null;

      Swal.fire({
        title: 'Verifikasi Email',
        html: `
          <div style="text-align: center; margin-top: 4px;">
            <p style="color: #4a4a58; font-size: 0.95rem; margin-bottom: 16px; line-height: 1.5;">
              Kami telah mengirimkan 6 digit kode verifikasi ke email:<br>
              <strong style="color: #1f3bff; word-break: break-all;">${email}</strong>
            </p>
            <div style="position: relative; max-width: 260px; margin: 0 auto;">
              <input id="swal-otp-input" type="text" maxlength="6" inputmode="numeric" pattern="[0-9]*" placeholder="••••••" autocomplete="one-time-code"
                style="width: 100%; text-align: center; font-size: 1.8rem; font-weight: 800; letter-spacing: 10px; padding: 12px 14px; border-radius: 999px; border: 2px solid rgba(31,59,255,0.25); outline: none; background: #f8faff; color: #0a0a0f; box-sizing: border-box; transition: border-color .3s, box-shadow .3s;"
              >
            </div>
            <p style="font-size: 0.82rem; color: #8a8da0; margin-top: 8px;">Cek folder kotak masuk atau spam email Anda.</p>
            <div style="margin-top: 14px;">
              <button type="button" id="swal-btn-resend" disabled style="background: none; border: none; color: #8a8da0; font-size: 0.88rem; font-weight: 600; cursor: not-allowed;">
                Kirim ulang kode (<span id="swal-resend-timer">60</span>s)
              </button>
            </div>
          </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Verifikasi & Daftar',
        cancelButtonText: 'Ubah Data',
        confirmButtonColor: '#1f3bff',
        cancelButtonColor: '#94a3b8',
        allowOutsideClick: false,
        customClass: {
          popup: 'y2k-swal-popup'
        },
        didOpen: function () {
          var input = document.getElementById('swal-otp-input');
          if (input) {
            input.focus();
            input.addEventListener('focus', function () {
              this.style.borderColor = '#1f3bff';
              this.style.boxShadow = '0 0 0 4px rgba(31,59,255,0.15)';
            });
            input.addEventListener('blur', function () {
              this.style.borderColor = 'rgba(31,59,255,0.25)';
              this.style.boxShadow = 'none';
            });
            input.addEventListener('input', function () {
              this.value = this.value.replace(/[^0-9]/g, '');
            });
            input.addEventListener('keypress', function (e) {
              if (e.key === 'Enter') {
                Swal.clickConfirm();
              }
            });
          }

          var resendBtn = document.getElementById('swal-btn-resend');
          var timerSpan = document.getElementById('swal-resend-timer');

          timerInterval = setInterval(function () {
            countdown--;
            if (timerSpan) timerSpan.textContent = countdown;
            if (countdown <= 0) {
              clearInterval(timerInterval);
              if (resendBtn) {
                resendBtn.disabled = false;
                resendBtn.style.color = '#1f3bff';
                resendBtn.style.cursor = 'pointer';
                resendBtn.style.textDecoration = 'underline';
                resendBtn.textContent = 'Kirim ulang kode';
              }
            }
          }, 1000);

          if (resendBtn) {
            resendBtn.addEventListener('click', function () {
              if (countdown > 0) return;
              resendBtn.disabled = true;
              resendBtn.textContent = 'Mengirim...';

              var csrfToken = document.querySelector('meta[name="csrf-token"]') 
                ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                : '';

              fetch('/register/resend-code', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ email: email })
              })
              .then(function (r) { return r.json(); })
              .then(function (res) {
                if (res.success) {
                  countdown = 60;
                  resendBtn.disabled = true;
                  resendBtn.style.color = '#8a8da0';
                  resendBtn.style.cursor = 'not-allowed';
                  resendBtn.style.textDecoration = 'none';
                  resendBtn.innerHTML = 'Kirim ulang kode (<span id="swal-resend-timer">60</span>s)';
                  timerSpan = document.getElementById('swal-resend-timer');
                  clearInterval(timerInterval);
                  timerInterval = setInterval(function () {
                    countdown--;
                    if (timerSpan) timerSpan.textContent = countdown;
                    if (countdown <= 0) {
                      clearInterval(timerInterval);
                      resendBtn.disabled = false;
                      resendBtn.style.color = '#1f3bff';
                      resendBtn.style.cursor = 'pointer';
                      resendBtn.style.textDecoration = 'underline';
                      resendBtn.textContent = 'Kirim ulang kode';
                    }
                  }, 1000);
                } else {
                  resendBtn.disabled = false;
                  resendBtn.textContent = 'Kirim ulang kode';
                  Swal.showValidationMessage(res.message || 'Gagal mengirim ulang kode.');
                }
              })
              .catch(function () {
                resendBtn.disabled = false;
                resendBtn.textContent = 'Kirim ulang kode';
              });
            });
          }
        },
        willClose: function () {
          if (timerInterval) clearInterval(timerInterval);
        },
        preConfirm: function () {
          var otpInput = document.getElementById('swal-otp-input');
          var code = otpInput ? otpInput.value.trim() : '';

          if (!code || code.length !== 6) {
            Swal.showValidationMessage('Masukkan 6 digit angka kode verifikasi');
            return false;
          }

          var csrfToken = document.querySelector('meta[name="csrf-token"]') 
            ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            : '';

          return fetch('/register/verify', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ email: email, code: code })
          })
          .then(function (response) {
            return response.json().then(function (data) {
              return { ok: response.ok, data: data };
            });
          })
          .then(function (res) {
            if (!res.ok || !res.data.success) {
              throw new Error(res.data.message || 'Verifikasi kode gagal.');
            }
            return res.data;
          })
          .catch(function (error) {
            Swal.showValidationMessage(error.message || 'Terjadi kesalahan saat memverifikasi.');
          });
        }
      }).then(function (result) {
        if (result.isConfirmed && result.value) {
          Swal.fire({
            icon: 'success',
            title: 'Verifikasi Berhasil!',
            text: result.value.message || 'Akun Anda berhasil didaftarkan. Mengalihkan...',
            timer: 1600,
            timerProgressBar: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
              popup: 'y2k-swal-popup'
            },
            willClose: function () {
              window.location.href = result.value.redirect || '/';
            }
          });
        } else {
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
          }
        }
      });
    }

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
            confirmButtonColor: '#1f3bff',
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
            confirmButtonColor: '#1f3bff',
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
            confirmButtonColor: '#1f3bff',
            customClass: {
              popup: 'y2k-swal-popup'
            }
          });
          return;
        }

        var originalBtnText = submitBtn ? submitBtn.textContent : 'Daftar sekarang';
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.textContent = 'Mengirim kode...';
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
          if (res.ok && res.data.success && res.data.requires_verification) {
            // Tampilkan popup input OTP
            showOtpModal(res.data.email, submitBtn, originalBtnText);
          } else if (res.ok && res.data.success) {
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
