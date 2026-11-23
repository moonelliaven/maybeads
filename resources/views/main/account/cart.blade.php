<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Data Lengkap Keranjang — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css'])
  <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

  <style>
    /* ==========================================================================
       CART DETAIL PAGE - Exact Match to Design Reference
       ========================================================================== */
    body.cart-detail-page {
      background-color: #f3f4f7;
      color: #18181b;
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .cart-main-container {
      max-width: 1240px;
      width: 100%;
      margin: 36px auto 140px;
      padding: 0 24px;
      flex: 1;
    }

    /* Top Bar on Header (Maybead.s) */
    .cart-top-bar {
      background-color: #202634;
      height: 52px;
      color: #ffffff;
      display: flex;
      align-items: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .cart-top-container {
      max-width: 1240px;
      width: 100%;
      margin: 0 auto;
      padding: 0 32px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .top-nav-brand-logo {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 20px;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: -0.2px;
      text-decoration: none;
      transition: opacity 0.15s ease;
    }
    .top-nav-brand-logo:hover {
      opacity: 0.9;
    }
    .top-nav-menu-links {
      display: flex;
      align-items: center;
      gap: 28px;
      list-style: none;
      margin: 0;
      padding: 0;
    }
    .top-menu-link {
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      color: #e2e8f0;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-decoration: none;
      transition: color 0.15s ease;
    }
    .top-menu-link:hover {
      color: #ffffff;
    }

    /* Sub-Navbar Bar */
    .cart-sub-navbar-bar {
      background: #ffffff;
      border-bottom: 1px solid #e2e8f0;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .cart-sub-navbar-container {
      max-width: 1240px;
      width: 100%;
      margin: 0 auto;
      padding: 12px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .cart-sub-nav-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .cart-sub-nav-brand {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 24px;
      font-weight: 800;
      color: #23477d;
      letter-spacing: -0.3px;
      text-decoration: none;
    }
    .cart-sub-nav-crumb {
      font-size: 13.5px;
      color: #64748b;
      font-weight: 500;
    }
    .cart-sub-nav-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .cart-action-pill-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #ffffff;
      border: 1.5px solid #cbd5e1;
      border-radius: 8px;
      padding: 7px 16px;
      font-size: 13px;
      font-weight: 600;
      color: #0f172a;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s ease;
    }
    .cart-action-pill-btn:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }
    .cart-action-pill-btn .pill-icon {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 1.8;
    }

    /* Page Header: Breadcrumb + Title + Search */
    .cart-header-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 24px;
      gap: 20px;
      flex-wrap: wrap;
    }
    .cart-header-left {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .cart-back-breadcrumb {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 13px;
      font-weight: 500;
      color: #64748b;
      text-decoration: none;
      transition: color 0.15s;
    }
    .cart-back-breadcrumb:hover {
      color: #0f172a;
    }
    .cart-header-title {
      font-size: 22px;
      font-weight: 700;
      color: #141418;
      letter-spacing: -0.2px;
      margin: 0;
    }

    /* Search Bar Pill */
    .cart-search-box {
      position: relative;
      width: 290px;
      max-width: 100%;
    }
    .cart-search-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 16px;
      height: 16px;
      stroke: #94a3b8;
      stroke-width: 2;
      fill: none;
      pointer-events: none;
    }
    .cart-search-input {
      width: 100%;
      padding: 8px 16px 8px 38px;
      background: #ffffff;
      border: 1px solid #d1d5db;
      border-radius: 999px;
      font-family: inherit;
      font-size: 12.5px;
      color: #1e293b;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .cart-search-input:focus {
      border-color: #244173;
      box-shadow: 0 0 0 3px rgba(36, 65, 115, 0.12);
    }
    .cart-search-input::placeholder {
      color: #94a3b8;
    }

    /* Table Card */
    .cart-table-card {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
    }

    /* Table Grid Columns: Checkbox (36px), Produk (1fr), Harga Asli (130px), Jumlah (130px), Total (140px), Aksi (80px) */
    .cart-grid-thead {
      background-color: #2a2c32;
      color: #ffffff;
      padding: 14px 24px;
      display: grid;
      grid-template-columns: 36px 1fr 130px 130px 140px 80px;
      align-items: center;
      gap: 16px;
      font-size: 13.5px;
      font-weight: 600;
    }
    .cart-grid-thead .col-check {
      display: flex;
      align-items: center;
    }
    .cart-grid-thead .col-unit-price {
      text-align: center;
    }
    .cart-grid-thead .col-qty {
      text-align: center;
    }
    .cart-grid-thead .col-total {
      text-align: center;
    }
    .cart-grid-thead .col-action {
      text-align: right;
    }

    /* Table Body Rows */
    .cart-table-tbody {
      background: #ffffff;
    }
    .cart-grid-row {
      padding: 18px 24px;
      display: grid;
      grid-template-columns: 36px 1fr 130px 130px 140px 80px;
      align-items: center;
      gap: 16px;
      border-bottom: 1px solid #eaecee;
      transition: background 0.15s;
    }
    .cart-grid-row:last-child {
      border-bottom: none;
    }
    .cart-grid-row:hover {
      background: #fafbfc;
    }

    /* Checkbox */
    .custom-cart-checkbox {
      width: 17px;
      height: 17px;
      border-radius: 4px;
      border: 1.5px solid #94a3b8;
      cursor: pointer;
      accent-color: #18181b;
      margin: 0;
    }

    /* Product Info Column */
    .cart-product-cell {
      display: flex;
      align-items: center;
      gap: 14px;
      min-width: 0;
    }
    .cart-thumb-img {
      width: 72px;
      height: 60px;
      min-width: 72px;
      border-radius: 8px;
      object-fit: cover;
      background: #e2e8f0;
      border: 1px solid #e2e8f0;
    }
    .cart-info-texts {
      display: flex;
      flex-direction: column;
      gap: 2px;
      min-width: 0;
    }
    .cart-item-title {
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
      line-height: 1.35;
      margin: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .cart-item-variant {
      font-size: 11.5px;
      color: #71717a;
    }
    .cart-unit-price-inline {
      display: none;
      font-size: 12px;
      font-weight: 700;
      color: #23477d;
      margin-top: 2px;
    }
    .cart-unit-price-inline .price-label {
      font-size: 10.5px;
      font-weight: 600;
      color: #64748b;
      margin-right: 3px;
    }

    /* Total Stepper Column */
    .cart-stepper-cell {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .stepper-pill-box {
      display: inline-flex;
      align-items: center;
      background: #ebeef2;
      border-radius: 6px;
      padding: 3px 6px;
      gap: 12px;
    }
    .stepper-btn {
      background: none;
      border: none;
      font-size: 16px;
      font-weight: 700;
      color: #475569;
      width: 22px;
      height: 22px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.15s;
    }
    .stepper-btn:hover {
      color: #0f172a;
    }
    .stepper-value {
      font-size: 13px;
      font-weight: 700;
      color: #141418;
      min-width: 18px;
      text-align: center;
    }

    /* Unit Price Column (Harga Asli) */
    .cart-unit-price-cell {
      font-size: 13.5px;
      font-weight: 600;
      color: #475569;
      text-align: center;
    }

    /* Total Price Column (Harga Akhir: rawPrice * qty) */
    .cart-total-price-cell {
      font-size: 14.5px;
      font-weight: 700;
      color: #141418;
      text-align: center;
    }
    .cart-price-cell {
      font-size: 14px;
      font-weight: 700;
      color: #141418;
      padding-left: 12px;
    }

    /* Action Icons Column */
    .cart-action-cell {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 14px;
    }
    .btn-action-icon {
      background: none;
      border: none;
      color: #475569;
      cursor: pointer;
      padding: 4px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: color 0.15s;
    }
    .btn-action-icon svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }
    .btn-action-delete:hover {
      color: #ef4444;
    }

    /* Empty state */
    .cart-empty-message {
      text-align: center;
      padding: 60px 20px;
      color: #64748b;
    }
    .cart-empty-message svg {
      width: 48px;
      height: 48px;
      stroke: #94a3b8;
      stroke-width: 1.5;
      fill: none;
      margin-bottom: 12px;
    }
    .cart-empty-message h3 {
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 6px;
    }

    /* ==========================================================================
       Sticky Bottom Bar (Pilih Semua, Hapus, Tambah Produk | Subtotal & Checkout)
       ========================================================================== */
    .cart-bottom-sticky-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 50;
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      box-shadow: 0 -4px 18px rgba(0, 0, 0, 0.04);
      padding: 14px 24px;
    }
    .cart-bottom-container {
      max-width: 1240px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }
    .cart-bottom-left {
      display: flex;
      align-items: center;
      gap: 24px;
      font-size: 13px;
      font-weight: 600;
    }
    .label-select-all {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: #64748b;
      font-weight: 600;
      user-select: none;
    }
    .label-select-all input {
      width: 16px;
      height: 16px;
      cursor: pointer;
      accent-color: #18181b;
    }
    .btn-delete-selected {
      color: #dc2626;
      background: none;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-size: 13px;
      font-weight: 600;
      transition: color 0.15s;
    }
    .btn-delete-selected:hover {
      color: #b91c1c;
      text-decoration: underline;
    }
    .btn-add-more-products {
      color: #475569;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: color 0.15s;
    }
    .btn-add-more-products:hover {
      color: #0f172a;
    }

    .cart-bottom-right {
      display: flex;
      align-items: center;
      gap: 24px;
    }
    .cart-subtotal-display {
      font-size: 15px;
      font-weight: 600;
      color: #1e293b;
    }
    .cart-subtotal-display strong {
      font-size: 18px;
      font-weight: 800;
      color: #0f172a;
      margin-left: 4px;
    }
    .btn-checkout-action {
      background: #244173;
      color: #ffffff;
      padding: 11px 26px;
      border-radius: 6px;
      font-family: inherit;
      font-size: 13.5px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.15s, transform 0.15s;
    }
    .btn-checkout-action:hover {
      background: #19315a;
      transform: translateY(-1px);
    }
    .btn-checkout-action svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      stroke-width: 2.2;
      fill: none;
    }

    @media (min-width: 861px) {
      .cart-grid-thead {
        grid-template-columns: 36px 1fr 130px 130px 140px 60px;
      }
      .cart-grid-row {
        padding: 14px 24px;
        display: grid;
        grid-template-columns: 36px 1fr 130px 130px 140px 60px;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #eaecee;
      }
      .cart-card-main-row {
        display: contents;
      }
      .cart-card-bottom-row {
        display: contents;
      }
      .total-label-mobile {
        display: none;
      }
      .cart-unit-price-inline {
        display: none;
      }
    }

    /* Responsive (Layout HP / Mobile) */
    @media (max-width: 860px) {
      .cart-main-container {
        margin: 18px auto 110px;
        padding: 0 14px;
      }
      .cart-header-title {
        font-size: 18px;
      }
      .cart-grid-thead {
        display: none;
      }
      .cart-grid-row {
        display: flex;
        flex-direction: column;
        padding: 12px 14px;
        gap: 10px;
        border-bottom: 1px solid #eaecee;
      }
      .cart-card-main-row {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
      }
      .cart-card-main-row .col-check {
        flex-shrink: 0;
      }
      .cart-product-cell {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
      }
      .cart-thumb-img {
        width: 62px;
        height: 62px;
        min-width: 62px;
        border-radius: 8px;
      }
      .cart-info-texts {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
        flex: 1;
      }
      .cart-item-title {
        font-size: 12.5px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .cart-item-variant {
        font-size: 10.5px;
        color: #64748b;
      }
      /* Harga asli tetap dibawah kategori barang dan disamping foto */
      .cart-unit-price-inline {
        display: flex;
        align-items: center;
        font-size: 12px;
        font-weight: 700;
        color: #23477d;
        margin-top: 2px;
      }
      .cart-unit-price-inline .price-label {
        font-size: 10.5px;
        font-weight: 600;
        color: #64748b;
        margin-right: 3px;
      }
      .cart-unit-price-cell {
        display: none;
      }
      .cart-card-bottom-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-left: 27px;
        width: 100%;
      }
      .cart-stepper-cell {
        justify-content: flex-start;
      }
      .stepper-pill-box {
        padding: 2px 4px;
        gap: 8px;
      }
      .stepper-btn {
        width: 20px;
        height: 20px;
        font-size: 14px;
      }
      .stepper-value {
        font-size: 12px;
        min-width: 16px;
      }
      .cart-total-price-cell {
        font-size: 13.5px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 4px;
        text-align: right;
      }
      .total-label-mobile {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
      }
      .cart-action-cell {
        justify-content: flex-end;
      }
      .btn-action-delete {
        padding: 4px;
        color: #ef4444;
      }
      .cart-bottom-container {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        padding: 10px 14px;
      }
      .cart-bottom-left {
        justify-content: space-between;
      }
      .cart-bottom-right {
        justify-content: space-between;
      }
      .btn-checkout-action {
        padding: 9px 18px;
        font-size: 12.5px;
      }
    }
  </style>
</head>
<body class="cart-detail-page">

  <!-- Top bar (Dark Slate Header - Maybead.s) -->
  <header class="cart-top-bar">
    <div class="cart-top-container">
      <div class="cart-top-left">
        <a href="{{ route('home') }}" class="top-nav-brand-logo">
          Maybead.s
        </a>
      </div>

      <nav class="top-nav-right" aria-label="Navigasi Utama">
        <ul class="top-nav-menu-links">
          <li><a href="{{ route('home') }}" class="top-menu-link">BERANDA</a></li>
          <li><a href="{{ route('home') }}#kontak" class="top-menu-link">KONTAK</a></li>
          <li><a href="{{ route('main.dashboard') }}" class="top-menu-link">PRODUK</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Sub-Navbar Bar -->
  <nav class="cart-sub-navbar-bar">
    <div class="cart-sub-navbar-container">
      <div class="cart-sub-nav-left">
        <a href="{{ route('main.dashboard') }}" class="cart-sub-nav-brand">
          Maybeads
        </a>
      </div>

      <div class="cart-sub-nav-right">
        <!-- Akun Button with Menu Dropdown -->
        <div class="account-dropdown-wrapper" style="position: relative;">
          <button type="button" class="cart-action-pill-btn" id="cartAccountDropdownBtn" aria-expanded="false" title="Menu Akun">
            <svg class="pill-icon" viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Akun</span>
            <svg class="chevron-down" viewBox="0 0 24 24" style="width: 13px; height: 13px; stroke: #475569; fill: none; stroke-width: 2.2;"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </button>

          <div class="account-menu-popover" id="cartAccountMenuPopover">
            @auth
              <div class="acc-popover-header">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
              </div>
              <div class="acc-popover-divider"></div>
              @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="acc-popover-item">
                  <svg viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/></svg>
                  Admin Dashboard
                </a>
              @endif
              <a href="{{ route('orders.history') }}" class="acc-popover-item">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Riwayat Pesanan
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                <button type="submit" class="acc-popover-item text-danger">
                  <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                  Keluar
                </button>
              </form>
            @else
              <div class="acc-popover-header">
                <strong>Akun Pengguna</strong>
                <small>Silakan masuk atau daftar</small>
              </div>
              <div class="acc-popover-divider"></div>
              <a href="{{ route('login') }}" class="acc-popover-item">Masuk ke Akun</a>
              <a href="{{ route('register') }}" class="acc-popover-item">Daftar Akun Baru</a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="cart-main-container">
    <!-- Header: Back to Home & Search -->
    <div class="cart-header-row">
      <div class="cart-header-left">
        <div style="margin-bottom: 8px;">
          <a href="{{ route('main.dashboard') }}" class="btn-back-to-home" onclick="if(window.history.length > 1 && document.referrer && document.referrer.includes(window.location.host)){window.history.back();return false;}" style="display: inline-flex; align-items: center; gap: 7px; background: #ffffff; border: 1.5px solid #23477d; color: #23477d; font-weight: 700; font-size: 13px; padding: 6px 16px; border-radius: 9999px; text-decoration: none; transition: all 0.2s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 15px; height: 15px;">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            <span>Kembali ke Halaman Utama</span>
          </a>
        </div>
        <h1 class="cart-header-title">Data Lengkap Keranjang</h1>
      </div>

      <!-- Search input pill -->
      <div class="cart-search-box">
        <svg class="cart-search-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="cartSearchInput" class="cart-search-input" placeholder="Cari Berdasarkan ...">
      </div>
    </div>

    <!-- Cart Table Card -->
    <div class="cart-table-card">
      <!-- Dark Table Header -->
      <div class="cart-grid-thead">
        <div class="col-check">
          <input type="checkbox" id="headerSelectAll" class="custom-cart-checkbox" title="Pilih Semua">
        </div>
        <div class="col-product">Nama Produk</div>
        <div class="col-unit-price">Harga Asli</div>
        <div class="col-qty">Jumlah</div>
        <div class="col-total">Total</div>
        <div class="col-action">Aksi</div>
      </div>

      <!-- Table Body Rows -->
      <div class="cart-table-tbody" id="cartTableRowsContainer">
        <!-- Rendered via JavaScript -->
      </div>
    </div>
  </main>

  <!-- Sticky Bottom Checkout Bar -->
  <footer class="cart-bottom-sticky-bar">
    <div class="cart-bottom-container">
      <div class="cart-bottom-left">
        <label class="label-select-all">
          <input type="checkbox" id="footerSelectAll" class="custom-cart-checkbox">
          <span id="footerSelectedCountText">(0) Pilih Semua</span>
        </label>
        <button type="button" class="btn-delete-selected" id="btnDeleteSelected">Hapus</button>
        <a href="{{ route('main.dashboard') }}" class="btn-add-more-products">+ Tambah Produk</a>
      </div>

      <div class="cart-bottom-right">
        <div class="cart-subtotal-display">
          Subtotal: <strong id="cartSubtotalText">Rp. 0</strong>
        </div>
        @auth
          <a href="{{ route('checkout') }}" class="btn-checkout-action" id="btnCheckoutAction">
            <span>Checkout</span>
            <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </a>
        @else
          <a href="{{ route('checkout') }}" class="btn-checkout-action" id="btnCheckoutAction">
            <span>Checkout</span>
            <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
          </a>
        @endauth
      </div>
    </div>
  </footer>

  <!-- Interactive Cart Management Script -->
  <script>
    window.isUserLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    document.addEventListener('DOMContentLoaded', () => {
      // Sub-navbar Account Popover
      const cartAccountDropdownBtn = document.getElementById('cartAccountDropdownBtn');
      const cartAccountMenuPopover = document.getElementById('cartAccountMenuPopover');
      if (cartAccountDropdownBtn && cartAccountMenuPopover) {
        cartAccountDropdownBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          const isOpen = cartAccountMenuPopover.classList.toggle('show');
          cartAccountDropdownBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        document.addEventListener('click', (e) => {
          if (!cartAccountMenuPopover.contains(e.target) && !cartAccountDropdownBtn.contains(e.target)) {
            cartAccountMenuPopover.classList.remove('show');
            cartAccountDropdownBtn.setAttribute('aria-expanded', 'false');
          }
        });
      }

      // Default sample items matching mockup exactly
      const sampleDefaultItems = [
        {
          id: 101,
          name: 'Nama Produk',
          color: 'Biru, Kuning',
          qty: 3,
          price: 'Rp 92.000',
          rawPrice: 92000,
          image: '/images/landing/prod-hairclip.jpg',
          checked: true,
          addedAt: Date.now() - 3000
        },
        {
          id: 102,
          name: 'Nama Produk',
          color: 'Biru, Kuning',
          qty: 3,
          price: 'Rp 92.000',
          rawPrice: 92000,
          image: '/images/landing/prod-ring.jpg',
          checked: true,
          addedAt: Date.now() - 2000
        },
        {
          id: 103,
          name: 'Nama Produk',
          color: 'Biru, Kuning',
          qty: 3,
          price: 'Rp 92.000',
          rawPrice: 92000,
          image: '/images/landing/prod-butterfly.jpg',
          checked: true,
          addedAt: Date.now() - 1000
        }
      ];

      let rawCart = localStorage.getItem('maybeads_cart');
      let cart = rawCart !== null ? JSON.parse(rawCart) : sampleDefaultItems;

      // Ensure every item has a checked state & reasonable Rupiah price (min Rp 1.000)
      cart = cart.map((it, idx) => {
        if (typeof it.checked === 'undefined') {
          it.checked = true;
        }
        if (!it.rawPrice) {
          it.rawPrice = parseInt(String(it.price || '').replace(/[^0-9]/g, ''), 10) || 42000;
        }
        it.rawPrice = Number(it.rawPrice);
        if (it.rawPrice < 1000) {
          it.rawPrice = it.rawPrice * 1000;
        }
        it.price = 'Rp ' + it.rawPrice.toLocaleString('id-ID');
        return it;
      });

      // Always save back sanitized reasonable prices to localStorage
      localStorage.setItem('maybeads_cart', JSON.stringify(cart));

      const rowsContainer      = document.getElementById('cartTableRowsContainer');
      const headerSelectAll    = document.getElementById('headerSelectAll');
      const footerSelectAll    = document.getElementById('footerSelectAll');
      const selectedCountText  = document.getElementById('footerSelectedCountText');
      const subtotalText       = document.getElementById('cartSubtotalText');
      const btnDeleteSelected  = document.getElementById('btnDeleteSelected');
      const cartSearchInput    = document.getElementById('cartSearchInput');
      const btnCheckoutAction  = document.getElementById('btnCheckoutAction');

      let currentSearchQuery = '';

      function saveCart() {
        localStorage.setItem('maybeads_cart', JSON.stringify(cart));
      }

      function updateCalculations() {
        const checkedItems = cart.filter(it => it.checked);
        const checkedCount = checkedItems.length;
        const allChecked   = cart.length > 0 && checkedCount === cart.length;

        if (headerSelectAll) {
          headerSelectAll.checked = allChecked;
          headerSelectAll.indeterminate = checkedCount > 0 && checkedCount < cart.length;
        }
        if (footerSelectAll) {
          footerSelectAll.checked = allChecked;
          footerSelectAll.indeterminate = checkedCount > 0 && checkedCount < cart.length;
        }

        if (selectedCountText) {
          selectedCountText.textContent = `(${checkedCount}) Pilih Semua`;
        }

        // Subtotal = sum of (rawPrice * qty) for checked items
        const subtotal = checkedItems.reduce((sum, it) => {
          const unit = Number(it.rawPrice) || 0;
          const count = Number(it.qty) || 1;
          return sum + (unit * count);
        }, 0);

        if (subtotalText) {
          subtotalText.textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');
        }
      }

      function renderCartRows() {
        if (!rowsContainer) return;

        let filtered = cart.map((item, originalIdx) => ({ item, originalIdx }));
        if (currentSearchQuery) {
          const q = currentSearchQuery.toLowerCase();
          filtered = filtered.filter(({ item }) =>
            (item.name || '').toLowerCase().includes(q) ||
            (item.color || '').toLowerCase().includes(q)
          );
        }

        if (filtered.length === 0) {
          rowsContainer.innerHTML = `
            <div class="cart-empty-message">
              <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              <h3>${cart.length === 0 ? 'Keranjang Anda Kosong' : 'Tidak Ada Produk yang Cocok'}</h3>
              <p>${cart.length === 0 ? 'Silakan tambahkan produk menarik dari katalog Maybeads.' : 'Coba gunakan kata kunci pencarian lainnya.'}</p>
            </div>
          `;
          updateCalculations();
          return;
        }

        rowsContainer.innerHTML = filtered.map(({ item, originalIdx }) => {
          let rawPrice = Number(item.rawPrice) || 42000;
          if (rawPrice < 1000) rawPrice *= 1000;
          const qty = parseInt(item.qty, 10) || 1;
          const rowTotal = rawPrice * qty;
          const displayUnitPrice = 'Rp ' + rawPrice.toLocaleString('id-ID');
          const displayRowTotal = 'Rp ' + rowTotal.toLocaleString('id-ID');
          const variantText = item.color || 'Standar';
          const imgSrc = item.image || '/images/products/keychain-01.jpg';

          return `
            <div class="cart-grid-row" data-idx="${originalIdx}">
              <!-- Top Row: Checkbox + Foto + Info (Harga Asli di bawah kategori dan di samping foto) -->
              <div class="cart-card-main-row">
                <!-- Checkbox -->
                <div class="col-check">
                  <input type="checkbox"
                         class="custom-cart-checkbox row-checkbox"
                         data-idx="${originalIdx}"
                         ${item.checked ? 'checked' : ''}>
                </div>

                <!-- Product Info & Media -->
                <div class="cart-product-cell">
                  <img src="${imgSrc}" alt="${item.name}" class="cart-thumb-img" onerror="this.onerror=null;this.src='/images/landing/prod-keychain.jpg'">
                  <div class="cart-info-texts">
                    <h4 class="cart-item-title" title="${item.name}">${item.name}</h4>
                    <span class="cart-item-variant">Jenis: ${variantText}</span>
                    <!-- Harga Asli di bawah kategori barang dan di samping foto -->
                    <div class="cart-unit-price-inline">
                      <span class="price-label">Harga Asli:</span>
                      <span class="price-val">${displayUnitPrice}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Harga Asli (Unit Price di Desktop Table) -->
              <div class="cart-unit-price-cell">
                <span>${displayUnitPrice}</span>
              </div>

              <!-- Bottom Row on Mobile / Columns on Desktop: Stepper, Total, Hapus -->
              <div class="cart-card-bottom-row">
                <!-- Stepper (Jumlah / Quantity) -->
                <div class="cart-stepper-cell">
                  <div class="stepper-pill-box">
                    <button type="button" class="stepper-btn btn-minus" data-idx="${originalIdx}" title="Kurangi">-</button>
                    <span class="stepper-value" id="qty-val-${originalIdx}">${qty}</span>
                    <button type="button" class="stepper-btn btn-plus" data-idx="${originalIdx}" title="Tambah">+</button>
                  </div>
                </div>

                <!-- Total Harga Produk (rawPrice * qty) -->
                <div class="cart-total-price-cell">
                  <span class="total-label-mobile">Total:</span>
                  <span id="row-total-${originalIdx}">${displayRowTotal}</span>
                </div>

                <!-- Action Icon: HANYA HAPUS (Menu Edit Produk Dihapus) -->
                <div class="cart-action-cell">
                  <button type="button" class="btn-action-icon btn-action-delete" data-idx="${originalIdx}" title="Hapus Produk">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
                </div>
              </div>
            </div>
          `;
        }).join('');

        updateCalculations();
        attachRowEventListeners();
      }

      function attachRowEventListeners() {
        // Row Checkboxes
        rowsContainer.querySelectorAll('.row-checkbox').forEach(chk => {
          chk.addEventListener('change', () => {
            const idx = parseInt(chk.dataset.idx, 10);
            if (cart[idx]) {
              cart[idx].checked = chk.checked;
              saveCart();
              updateCalculations();
            }
          });
        });

        // Minus button
        rowsContainer.querySelectorAll('.btn-minus').forEach(btn => {
          btn.addEventListener('click', () => {
            const idx = parseInt(btn.dataset.idx, 10);
            if (!cart[idx]) return;
            if (cart[idx].qty > 1) {
              cart[idx].qty--;
              saveCart();
              renderCartRows();
            } else {
              const confirmRemove = confirm('Hapus produk ini dari keranjang?');
              if (confirmRemove) {
                cart.splice(idx, 1);
                saveCart();
                renderCartRows();
              }
            }
          });
        });

        // Plus button
        rowsContainer.querySelectorAll('.btn-plus').forEach(btn => {
          btn.addEventListener('click', () => {
            const idx = parseInt(btn.dataset.idx, 10);
            if (!cart[idx]) return;
            cart[idx].qty = (parseInt(cart[idx].qty, 10) || 1) + 1;
            saveCart();
            renderCartRows();
          });
        });

        // Delete single item (Menu Edit Produk sudah ditiadakan)
        rowsContainer.querySelectorAll('.btn-action-delete').forEach(btn => {
          btn.addEventListener('click', () => {
            const idx = parseInt(btn.dataset.idx, 10);
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                title: 'Hapus Produk',
                text: 'Produk yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d9383a',
                cancelButtonColor: '#5c5656',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                customClass: { popup: 'maybeads-swal-popup' }
              }).then((result) => {
                if (result.isConfirmed) {
                  cart.splice(idx, 1);
                  saveCart();
                  renderCartRows();
                }
              });
            } else {
              if (confirm('Hapus produk ini dari keranjang?')) {
                cart.splice(idx, 1);
                saveCart();
                renderCartRows();
              }
            }
          });
        });
      }

      // Select All Toggle (Header & Footer)
      function setAllSelection(checked) {
        cart.forEach(it => (it.checked = checked));
        saveCart();
        renderCartRows();
      }

      if (headerSelectAll) {
        headerSelectAll.addEventListener('change', () => {
          setAllSelection(headerSelectAll.checked);
        });
      }

      if (footerSelectAll) {
        footerSelectAll.addEventListener('change', () => {
          setAllSelection(footerSelectAll.checked);
        });
      }

      // Delete Selected Items
      if (btnDeleteSelected) {
        btnDeleteSelected.addEventListener('click', () => {
          const checkedCount = cart.filter(it => it.checked).length;
          if (checkedCount === 0) {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                title: 'Pilih Produk',
                text: 'Silakan centang produk yang ingin dihapus terlebih dahulu.',
                icon: 'warning',
                confirmButtonText: 'Kembali',
                confirmButtonColor: '#5c5656',
                customClass: { popup: 'maybeads-swal-popup' }
              });
            } else {
              alert('Silakan pilih produk yang ingin dihapus terlebih dahulu.');
            }
            return;
          }

          if (typeof Swal !== 'undefined') {
            Swal.fire({
              title: 'Hapus Produk Terpilih',
              text: `Hapus ${checkedCount} produk yang dipilih dari keranjang belanja?`,
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d9383a',
              cancelButtonColor: '#5c5656',
              confirmButtonText: 'Ya, Hapus',
              cancelButtonText: 'Kembali',
              reverseButtons: true,
              customClass: { popup: 'maybeads-swal-popup' }
            }).then((result) => {
              if (result.isConfirmed) {
                cart = cart.filter(it => !it.checked);
                saveCart();
                renderCartRows();
              }
            });
          } else {
            if (confirm(`Hapus ${checkedCount} produk yang dipilih dari keranjang?`)) {
              cart = cart.filter(it => !it.checked);
              saveCart();
              renderCartRows();
            }
          }
        });
      }

      // Search Filter
      if (cartSearchInput) {
        cartSearchInput.addEventListener('input', (e) => {
          currentSearchQuery = e.target.value.trim();
          renderCartRows();
        });
      }

      // Checkout Button: Simpan data dan navigasi ke halaman checkout
      if (btnCheckoutAction) {
        btnCheckoutAction.addEventListener('click', (e) => {
          e.preventDefault();
          if (!window.isUserLoggedIn) {
            window.openLoginRequiredModal("{{ route('checkout') }}");
            return;
          }

          const checkedItems = cart.filter(it => it.checked);
          if (checkedItems.length === 0) {
            alert('Silakan pilih setidaknya 1 produk untuk checkout.');
            return;
          }
          const subtotal = checkedItems.reduce((sum, it) => sum + ((it.rawPrice || 42000) * (it.qty || 1)), 0);
          localStorage.setItem('maybeads_cart', JSON.stringify(cart));
          localStorage.setItem('maybeads_checkout_items', JSON.stringify(checkedItems));
          localStorage.setItem('maybeads_subtotal', String(subtotal));
          window.location.href = "{{ route('checkout') }}";
        });
      }

      // Initial Render
      renderCartRows();
    });
  </script>

  @include('partials.login-required-modal')
</body>
</html>
