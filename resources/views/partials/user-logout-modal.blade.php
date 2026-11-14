{{-- User Logout Confirmation Modal (Maybead.s) --}}
<div class="user-logout-backdrop" id="userLogoutModal" style="display: none;">
  <div class="user-logout-card">
    <div class="user-logout-header">
      <h3 class="user-logout-title">Konfirmasi Keluar</h3>
      <button type="button" class="user-logout-close" id="btnCloseUserLogoutModal" aria-label="Tutup">&times;</button>
    </div>

    <div class="user-logout-body">
      <div class="user-logout-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
      </div>
      <div class="user-logout-text">
        <h4>Keluar dari Akun Anda?</h4>
        <p>Apakah Anda yakin ingin keluar dari Maybeads? Anda perlu masuk kembali untuk mengakses keranjang belanja dan memproses pesanan.</p>
      </div>
    </div>

    <div class="user-logout-footer">
      <button type="button" class="btn-user-logout-cancel" id="btnCancelUserLogout">Kembali</button>
      <button type="button" class="btn-user-logout-confirm" id="btnConfirmUserLogout">Ya, Keluar</button>
    </div>
  </div>
</div>

<style>
  .user-logout-backdrop {
    position: fixed;
    inset: 0;
    background-color: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .user-logout-card {
    background-color: #ffffff;
    border-radius: 16px;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 25px 40px -10px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    animation: userModalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  }
  @keyframes userModalFadeIn {
    from { opacity: 0; transform: scale(0.96) translateY(-8px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
  }
  .user-logout-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
  }
  .user-logout-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
  }
  .user-logout-close {
    background: none;
    border: none;
    font-size: 22px;
    line-height: 1;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    transition: all 0.15s;
  }
  .user-logout-close:hover {
    color: #0f172a;
    background-color: #f1f5f9;
  }
  .user-logout-body {
    padding: 28px 24px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 16px;
  }
  .user-logout-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background-color: transparent;
    color: #fcaa30;
    border: 3.5px solid #fcaa30;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 42px;
    font-weight: 700;
    line-height: 1;
  }
  .user-logout-text h4 {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
  }
  .user-logout-text p {
    font-size: 14.5px;
    color: #334155;
    line-height: 1.5;
  }
  .user-logout-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 24px 28px;
    background-color: #ffffff;
  }
  .btn-user-logout-cancel {
    padding: 10px 24px;
    background-color: #5c5656;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.15s;
    min-width: 105px;
  }
  .btn-user-logout-cancel:hover {
    background-color: #484343;
  }
  .btn-user-logout-confirm {
    padding: 10px 24px;
    background-color: #d9383a;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.15s;
    min-width: 105px;
  }
  .btn-user-logout-confirm:hover {
    background-color: #bd2b2d;
  }
</style>

<script>
  (function () {
    let pendingLogoutForm = null;

    function initUserLogout() {
      const modal = document.getElementById('userLogoutModal');
      const btnClose = document.getElementById('btnCloseUserLogoutModal');
      const btnCancel = document.getElementById('btnCancelUserLogout');
      const btnConfirm = document.getElementById('btnConfirmUserLogout');

      if (!modal && typeof Swal === 'undefined') return;

      function openModal(form) {
        pendingLogoutForm = form;
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Keluar dari Akun Anda',
            text: 'Apakah Anda yakin ingin keluar dari Maybeads?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d9383a',
            cancelButtonColor: '#5c5656',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Kembali',
            reverseButtons: true,
            customClass: { popup: 'maybeads-swal-popup' }
          }).then(function(result) {
            if (result.isConfirmed) {
              if (pendingLogoutForm) pendingLogoutForm.submit();
              else {
                const defaultForm = document.querySelector('form[action*="logout"]');
                if (defaultForm) defaultForm.submit();
              }
            }
          });
          return;
        }
        if (modal) modal.style.display = 'flex';
      }

      function closeModal() {
        modal.style.display = 'none';
        pendingLogoutForm = null;
      }

      // Cari semua form logout di halaman user
      document.querySelectorAll('form[action*="logout"]').forEach(form => {
        form.addEventListener('submit', function (e) {
          if (!pendingLogoutForm) {
            e.preventDefault();
            e.stopPropagation();
            openModal(this);
          }
        });
      });

      // Cari tombol khusus logout jika ada
      document.querySelectorAll('[data-logout-btn], .btn-trigger-logout').forEach(btn => {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          const form = this.closest('form') || document.querySelector('form[action*="logout"]');
          openModal(form);
        });
      });

      if (btnClose) btnClose.addEventListener('click', closeModal);
      if (btnCancel) btnCancel.addEventListener('click', closeModal);

      if (btnConfirm) {
        btnConfirm.addEventListener('click', function () {
          if (pendingLogoutForm) {
            pendingLogoutForm.submit();
          } else {
            // Fallback submit form logout pertama
            const defaultForm = document.querySelector('form[action*="logout"]');
            if (defaultForm) defaultForm.submit();
          }
        });
      }

      window.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
      });

      window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') closeModal();
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initUserLogout);
    } else {
      initUserLogout();
    }
  })();
</script>

