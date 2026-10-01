<aside class="admin-sidebar" id="adminSidebar" aria-label="Navigasi admin">
  <!-- Brand -->
  <div class="sidebar-brand">
    <a href="{{ route('admin.dashboard') }}" class="brand-logo" aria-label="Maybead.s">
      <span class="brand-full">Maybead.s</span>
      <span class="brand-short" aria-hidden="true">M</span>
    </a>
  </div>

  <div class="sidebar-nav" data-lenis-prevent>
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
          <a href="/admin/product" data-label="Produk" class="nav-item {{ ($active ?? '') === 'product' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
              <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
              <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span class="nav-text">Produk</span>
          </a>
        </li>
        <li>
          <a href="/admin/orders" data-label="Pesanan" class="nav-item {{ ($active ?? '') === 'pesanan' ? 'active' : '' }}">
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
          <a href="/admin/payments" data-label="Pembayaran" class="nav-item {{ ($active ?? '') === 'pembayaran' ? 'active' : '' }}">
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
          <a href="/admin/users" data-label="User" class="nav-item {{ ($active ?? '') === 'user' ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <span class="nav-text">User</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- MENU KASIR -->
    <div class="nav-section">
      <div class="nav-header">MENU KASIR</div>
      <ul class="nav-list">
        <li>
          <a href="/" target="_blank" rel="noopener" data-label="Halaman Utama" class="nav-item nav-item-external">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="nav-text">Halaman Utama</span>
            <svg class="nav-external-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="7" y1="17" x2="17" y2="7"></line>
              <polyline points="7 7 17 7 17 17"></polyline>
            </svg>
          </a>
        </li>
        <li>
          <a href="/admin/pos/transactions" data-label="Transaksi Kasir" class="nav-item {{ ($active ?? '') === 'transaksi_kasir' ? 'active' : '' }}">
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

  <!-- Footer: kartu user -->
  <div class="sidebar-footer">
    <div class="user-profile-badge">
      <div class="user-avatar-circle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
      </div>
      <div class="user-meta">
        <div class="user-name">M. Waiz Fadhillah</div>
        <div class="user-role">Admin</div>
      </div>
    </div>
  </div>
</aside>