{{-- Modal Peringatan Wajib Login (Sesuai Desain Figma / Mockup Gambar) --}}
<div class="login-required-backdrop" id="loginRequiredModal" role="dialog" aria-modal="true" aria-labelledby="loginRequiredTitle" style="display: none;">
  <div class="login-required-card">
    
    <!-- User Icon Badge (Lingkaran Biru Muda Lembut + Icon Avatar User) -->
    <div class="login-required-icon-wrap">
      <svg class="login-required-user-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="8" r="4.2"></circle>
        <path d="M5.5 20.5a6.5 6.5 0 0 1 13 0"></path>
      </svg>
    </div>

    <!-- Title & Subtitle -->
    <h3 class="login-required-title" id="loginRequiredTitle">Perhatian</h3>
    <p class="login-required-subtitle">Silahkan login terlebih dahulu</p>

    <!-- Buttons Row: Kembali & Login -->
    <div class="login-required-actions">
      <button type="button" class="btn-login-req-back" id="btnCancelLoginRequired">Kembali</button>
      <a href="{{ route('login') }}" class="btn-login-req-proceed" id="btnProceedLoginRequired">Login</a>
    </div>

  </div>
</div>

<style>
  .login-required-backdrop {
    position: fixed;
    inset: 0;
    background-color: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: loginBackdropFadeIn 0.18s ease-out;
  }

  @keyframes loginBackdropFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
  }

  .login-required-card {
    background-color: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 360px;
    padding: 32px 28px 26px;
    text-align: center;
    box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.25);
    animation: loginCardPop 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  }

  @keyframes loginCardPop {
    from {
      opacity: 0;
      transform: scale(0.92) translateY(6px);
    }
    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }

  /* User Icon Wrap */
  .login-required-icon-wrap {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #eaf2fc;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: #204173;
  }

  .login-required-user-icon {
    width: 28px;
    height: 28px;
    stroke: #204173;
  }

  /* Title & Subtitle */
  .login-required-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.01em;
    margin: 0 0 8px;
    line-height: 1.2;
  }

  .login-required-subtitle {
    font-size: 13.5px;
    font-weight: 500;
    color: #475569;
    margin: 0 0 26px;
    line-height: 1.4;
  }

  /* Buttons Row */
  .login-required-actions {
    display: grid;
    grid-template-columns: 1fr 1.15fr;
    gap: 12px;
    align-items: center;
  }

  .btn-login-req-back {
    padding: 11px 18px;
    background-color: #f8fafc;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    color: #1e293b;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: inherit;
    line-height: 1.2;
  }

  .btn-login-req-back:hover {
    background-color: #f1f5f9;
    border-color: #94a3b8;
  }

  .btn-login-req-back:active {
    transform: scale(0.98);
  }

  .btn-login-req-proceed {
    padding: 11px 22px;
    background-color: #204173;
    border: none;
    border-radius: 10px;
    color: #ffffff !important;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
    box-shadow: 0 4px 12px rgba(32, 65, 115, 0.22);
    font-family: inherit;
    line-height: 1.2;
  }

  .btn-login-req-proceed:hover {
    background-color: #162f55;
    box-shadow: 0 6px 16px rgba(32, 65, 115, 0.32);
  }

  .btn-login-req-proceed:active {
    transform: scale(0.98);
  }
</style>

<script>
  (function() {
    window.openLoginRequiredModal = function(redirectUrl) {
      const modal = document.getElementById('loginRequiredModal');
      const proceedBtn = document.getElementById('btnProceedLoginRequired');
      if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
      if (proceedBtn) {
        const target = redirectUrl || window.location.href;
        sessionStorage.setItem('maybeads_redirect_after_login', target);
        // Also pass target as query param if desired
        proceedBtn.href = "{{ route('login') }}?redirect=" + encodeURIComponent(target);
      }
    };

    window.closeLoginRequiredModal = function() {
      const modal = document.getElementById('loginRequiredModal');
      if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
      }
    };

    document.addEventListener('DOMContentLoaded', function() {
      const cancelBtn = document.getElementById('btnCancelLoginRequired');
      const modal = document.getElementById('loginRequiredModal');

      if (cancelBtn) {
        cancelBtn.addEventListener('click', window.closeLoginRequiredModal);
      }

      if (modal) {
        modal.addEventListener('click', function(e) {
          if (e.target === modal) {
            window.closeLoginRequiredModal();
          }
        });
      }

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          window.closeLoginRequiredModal();
        }
      });

      // Bind all trigger buttons
      document.querySelectorAll('.btn-trigger-login-prompt').forEach(function(el) {
        el.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          window.openLoginRequiredModal();
        });
      });
    });
  })();
</script>

