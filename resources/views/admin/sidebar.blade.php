<aside class="admin-sidebar" id="adminSidebar" aria-label="Navigasi admin">
  <!-- Brand -->
  <div class="sidebar-brand">
    <a href="{{ route('admin.dashboard') }}" class="brand-logo" aria-label="Maybead.s">
      <span class="brand-full">Maybeads</span>
      <span class="brand-short" aria-hidden="true">M</span>
    </a>
  </div>

  <div class="sidebar-nav">
    <!-- MENU UTAMA -->
    <div class="nav-section">
      <div class="nav-header">MENU UTAMA</div>
      <ul class="nav-list">
        <li>
          <a href="{{ route('admin.dashboard') }}" data-label="Dashboard" class="nav-item {{ ($active ?? '') === 'dashboard' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
              <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span class="nav-text">Dashboard</span>
          </a>
        </li>

    <li>
        <a href="{{ route('admin.product.index') }}"
          data-label="Produk"
          class="nav-item {{ ($active ?? '') === 'product' ? 'active' : '' }}">

            <svg class="nav-icon" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>

            <span class="nav-text">Produk</span>
        </a>
    </li>

    <li>
        <a href="{{ route('admin.category.index') }}"
          data-label="Kategori"
          class="nav-item {{ ($active ?? '') === 'category' ? 'active' : '' }}">

            <svg class="nav-icon" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>

            <span class="nav-text">Kategori</span>
        </a>
    </li>

        <li>
          <a href="{{ route('admin.order.index') }}" data-label="Pesanan" class="nav-item {{ in_array($active ?? '', ['pesanan', 'orders']) ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            <span class="nav-text">Pesanan</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.order.pending') }}" data-label="Pending Order" class="nav-item {{ ($active ?? '') === 'pending_order' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span class="nav-text">Pending Order</span>
            @php
              $sidebarPendingOrders = \App\Models\Order::whereRaw('LOWER(order_status) = ?', ['pending'])->count();
            @endphp
            @if($sidebarPendingOrders > 0)
              <span class="sidebar-pending-badge" style="margin-left: auto; background: #f97316; color: #ffffff; font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 999px;">{{ $sidebarPendingOrders }}</span>
            @endif
          </a>
        </li>
        <li>
          <a href="/admin/payments" data-label="Pembayaran" class="nav-item {{ in_array($active ?? '', ['pembayaran', 'payments']) ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="5" width="20" height="14" rx="2"></rect>
              <line x1="2" y1="10" x2="22" y2="10"></line>
            </svg>
            <span class="nav-text">Pembayaran</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- MANAGEMENT -->
    <div class="nav-section">
      <div class="nav-header">MANAGEMENT</div>
      <ul class="nav-list">
        <li>
          <a href="{{ route('admin.user.index') }}" data-label="User" class="nav-item {{ in_array($active ?? '', ['user', 'users']) ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span class="nav-text">User</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.payment-method.index') }}" data-label="Metode Pembayaran" class="nav-item {{ ($active ?? '') === 'payment-method' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="5" width="20" height="14" rx="2"></rect>
              <line x1="2" y1="10" x2="22" y2="10"></line>
              <path d="M7 15h.01"></path>
              <path d="M11 15h2"></path>
            </svg>
            <span class="nav-text">Metode Pembayaran</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.delivery.index') }}" data-label="Data Pengantaran" class="nav-item {{ in_array($active ?? '', ['delivery', 'pengantaran']) ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="1" y="3" width="15" height="13"></rect>
              <polygon points="16 8 20 8 23 11 23 16 16 16 8"></polygon>
              <circle cx="5.5" cy="18.5" r="2.5"></circle>
              <circle cx="18.5" cy="18.5" r="2.5"></circle>
            </svg>
            <span class="nav-text">Data Pengantaran</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.restore.index') }}" data-label="Restore" class="nav-item {{ ($active ?? '') === 'restore' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="1 4 1 10 7 10"></polyline>
              <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
            </svg>
            <span class="nav-text">Restore</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- MENU KASIR -->
    <div class="nav-section">
      <div class="nav-header">MENU KASIR</div>
      <ul class="nav-list">
        <li>
          <a href="{{ route('admin.pos.index') }}" data-label="Halaman Utama" class="nav-item {{ in_array($active ?? '', ['pos', 'pos_register']) ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            <span class="nav-text">Halaman Utama</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.pos.transactions') }}" data-label="Transaksi Kasir" class="nav-item {{ in_array($active ?? '', ['pos_transactions', 'transaksi_kasir']) ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1-2-1z"></path>
              <line x1="8" y1="6" x2="16" y2="6"></line>
              <line x1="8" y1="10" x2="16" y2="10"></line>
              <line x1="8" y1="14" x2="12" y2="14"></line>
            </svg>
            <span class="nav-text">Transaksi Kasir</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Footer: kartu user & menu popover -->
  <div class="sidebar-footer" id="sidebarFooter">
    <!-- Dropup Menu Pengguna -->
    <div class="sidebar-user-menu" id="sidebarUserMenu" role="menu" aria-label="Menu Opsi Pengguna" hidden>
      <div class="sidebar-user-menu-header">
        <div class="sidebar-user-menu-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
        <div class="sidebar-user-menu-email">{{ Auth::user()->email ?? 'admin@maybeads.com' }}</div>
      </div>
      <div class="sidebar-user-menu-divider" role="separator"></div>
      <ul class="sidebar-user-menu-list">
        <li>
          <a href="{{ route('admin.notifications') }}" class="sidebar-user-menu-item" role="menuitem">
            <svg class="menu-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="menu-item-text">Notifikasi</span>
            @if(($sidebarPendingOrders ?? 0) > 0)
              <span class="badge-notif-count">{{ $sidebarPendingOrders }}</span>
            @endif
          </a>
        </li>
        <li>
          <a href="{{ route('admin.settings.index') }}" class="sidebar-user-menu-item" role="menuitem">
            <svg class="menu-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="3"></circle>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
            <span class="menu-item-text">Pengaturan Website</span>
          </a>
        </li>
        <li class="sidebar-user-menu-divider" role="separator"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}" id="adminSidebarLogoutForm" class="sidebar-logout-form" style="margin: 0;">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
            <button type="button" id="btnAdminSidebarLogout" class="sidebar-user-menu-item danger" role="menuitem">
              <svg class="menu-item-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              <span class="menu-item-text">Logout</span>
            </button>
          </form>
        </li>
      </ul>
    </div>

    <!-- Badge Profil Pengguna dengan Chevron -->
    <div class="user-profile-badge" id="sidebarUserBadge" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-controls="sidebarUserMenu" title="Buka opsi pengguna">
      <div class="user-avatar-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      </div>
      <div class="user-meta">
        <div class="user-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
        <div class="user-role">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</div>
      </div>
      <button type="button" class="user-chevron-btn" id="sidebarChevronBtn" aria-label="Buka menu opsi pengguna" tabindex="-1">
        <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </button>
    </div>
  </div>
</aside>

{{-- MODAL KONFIRMASI LOGOUT ADMIN --}}
<div class="modal-backdrop-logout" id="adminLogoutModal" style="display: none;">
  <div class="modal-card-logout">
    <div class="modal-logout-header">
      <h3 class="modal-logout-title">Konfirmasi Keluar (Logout)</h3>
      <button type="button" class="btn-logout-modal-close" id="btnCloseAdminLogoutModal" aria-label="Tutup">&times;</button>
    </div>
    <div class="modal-logout-body">
      <div class="logout-icon-circle admin-icon-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
      </div>
      <div class="logout-text-desc">
        <h4>Keluar dari Admin Dashboard?</h4>
        <p>Apakah Anda yakin ingin keluar dari sesi Admin Maybeads? Anda harus login kembali untuk mengakses panel administrasi.</p>
      </div>
    </div>
    <div class="modal-logout-footer">
      <button type="button" class="btn-logout-cancel" id="btnCancelAdminLogout">Kembali</button>
      <button type="button" class="btn-logout-confirm admin-btn-danger" id="btnConfirmAdminLogout">Ya, Keluar</button>
    </div>
  </div>
</div>

<script>
  (function() {
    function initAdminLogout() {
      const logoutBtn = document.getElementById('btnAdminSidebarLogout');
      const defaultLogoutForm = document.getElementById('adminSidebarLogoutForm');
      const modal = document.getElementById('adminLogoutModal');
      const btnClose = document.getElementById('btnCloseAdminLogoutModal');
      const btnCancel = document.getElementById('btnCancelAdminLogout');
      const btnConfirm = document.getElementById('btnConfirmAdminLogout');

      if (!modal && typeof Swal === 'undefined') return;

      let pendingAdminLogoutForm = null;

      function openModal(form) {
        pendingAdminLogoutForm = form || defaultLogoutForm;
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Keluar dari Akun Admin',
            text: 'Apakah Anda yakin ingin keluar dari Admin Dashboard Maybeads?',
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
              submitAdminLogout(pendingAdminLogoutForm);
            }
          });
          return;
        }
        if (modal) modal.style.display = 'flex';
      }

      function submitAdminLogout(form) {
        const targetForm = form || defaultLogoutForm || document.querySelector('form[action*="logout"]');
        if (targetForm) {
          if (!targetForm.querySelector('input[name="redirect_to"]')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'redirect_to';
            input.value = window.location.href;
            targetForm.appendChild(input);
          }
          targetForm.submit();
        } else {
          window.location.href = '/logout?redirect_to=' + encodeURIComponent(window.location.href);
        }
      }

      function closeModal() {
        modal.style.display = 'none';
        pendingAdminLogoutForm = null;
      }

      if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          openModal(defaultLogoutForm);
        });
      }

      // Intercept any logout forms across the admin page (e.g. topbar dropdowns)
      document.querySelectorAll('form[action*="logout"]').forEach(form => {
        form.addEventListener('submit', function (e) {
          if (!pendingAdminLogoutForm) {
            e.preventDefault();
            e.stopPropagation();
            openModal(this);
          }
        });
      });

      if (btnClose) btnClose.addEventListener('click', closeModal);
      if (btnCancel) btnCancel.addEventListener('click', closeModal);

      if (btnConfirm) {
        btnConfirm.addEventListener('click', function() {
          submitAdminLogout(pendingAdminLogoutForm);
        });
      }

      window.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
      });

      window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') closeModal();
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initAdminLogout);
    } else {
      initAdminLogout();
    }
  })();

  // Navigasi Kembali Cepat & Perlindungan Logout BFCache untuk Admin
  (function() {
    // 1. Jika tombol kembali dalam aplikasi diklik (btn-back, btn-back-link, btn-form-cancel)
    document.addEventListener('click', function(e) {
      var backBtn = e.target.closest('.btn-back, .btn-back-link, .btn-form-cancel, [data-action="back"]');
      if (backBtn && window.history.length > 1 && document.referrer && document.referrer.includes(window.location.host)) {
        e.preventDefault();
        window.history.back();
      }
    });

    // 2. Jika kembali dari riwayat (BFCache), periksa apakah sesi admin masih valid
    window.addEventListener('pageshow', function(event) {
      if (event.persisted) {
        fetch('{{ route("admin.dashboard") }}', { method: 'HEAD', cache: 'no-store' })
          .then(function(res) {
            if (res.redirected || res.status === 401 || res.status === 403 || (res.url && res.url.includes('/login'))) {
              window.location.replace('{{ route("login") }}');
            }
          })
          .catch(function() {});
      }
    });
  })();

  // Toggle Menu Dropup Pengguna di Footer Sidebar (Bisa di-klik di halaman manapun)
  (function() {
    function initSidebarUserDropdown() {
      var badge = document.getElementById('sidebarUserBadge');
      var menu = document.getElementById('sidebarUserMenu');
      var footer = document.getElementById('sidebarFooter');
      if (!badge || !menu) return;

      badge.style.cursor = 'pointer';

      function toggleMenu(e) {
        if (e) {
          e.preventDefault();
          e.stopPropagation();
        }
        var isHidden = menu.hasAttribute('hidden') || !menu.classList.contains('show');
        if (isHidden) {
          menu.removeAttribute('hidden');
          menu.classList.add('show');
          if (footer) footer.classList.add('menu-open');
          badge.setAttribute('aria-expanded', 'true');
        } else {
          menu.setAttribute('hidden', '');
          menu.classList.remove('show');
          if (footer) footer.classList.remove('menu-open');
          badge.setAttribute('aria-expanded', 'false');
        }
      }

      badge.addEventListener('click', toggleMenu);

      var chevronBtn = document.getElementById('sidebarChevronBtn');
      if (chevronBtn && chevronBtn !== badge) {
        chevronBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          toggleMenu(e);
        });
      }

      document.addEventListener('click', function(e) {
        if (!badge.contains(e.target) && !menu.contains(e.target)) {
          menu.setAttribute('hidden', '');
          menu.classList.remove('show');
          if (footer) footer.classList.remove('menu-open');
          badge.setAttribute('aria-expanded', 'false');
        }
      });

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !menu.hasAttribute('hidden')) {
          menu.setAttribute('hidden', '');
          menu.classList.remove('show');
          if (footer) footer.classList.remove('menu-open');
          badge.setAttribute('aria-expanded', 'false');
        }
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initSidebarUserDropdown);
    } else {
      initSidebarUserDropdown();
    }
  })();
</script>