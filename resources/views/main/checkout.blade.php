<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Data Pesanan — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background-color: #f8f9fa;
      color: #141418;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Top Dark Bar */
    .top-dark-bar {
      background-color: #1a2232;
      color: #ffffff;
      padding: 14px 48px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .top-brand-logo {
      font-size: 19px;
      font-weight: 800;
      color: #ffffff;
      text-decoration: none;
      letter-spacing: -0.01em;
    }

    .top-menu-links {
      display: flex;
      gap: 32px;
      list-style: none;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    .top-menu-links a {
      color: #cbd5e1;
      text-decoration: none;
      transition: color 0.15s;
    }

    .top-menu-links a:hover {
      color: #ffffff;
    }

    /* Sub Navbar */
    .sub-nav-bar {
      background: #ffffff;
      border-bottom: 1px solid #e5e7eb;
      padding: 12px 48px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .sub-nav-brand {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }

    .brand-title {
      color: #2b4570;
      font-size: 19px;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .sub-nav-crumb {
      font-size: 12px;
      color: #8c96a4;
      font-weight: 500;
    }

    .sub-nav-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .sub-btn-pill {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 7px 16px;
      border-radius: 999px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #334155;
      font-size: 12px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.15s ease;
    }

    .sub-btn-pill:hover {
      background-color: #f8fafc;
      border-color: #94a3b8;
    }

    /* Main Container */
    .order-checkout-main {
      max-width: 1160px;
      width: 100%;
      margin: 28px auto 140px;
      padding: 0 24px;
      flex: 1;
    }

    .page-title-main {
      font-size: 20px;
      font-weight: 800;
      color: #141418;
      letter-spacing: -0.01em;
      margin-bottom: 24px;
    }

    /* Two Columns Layout */
    .checkout-two-cols {
      display: grid;
      grid-template-columns: 1.45fr 1fr;
      gap: 24px;
      align-items: start;
    }

    .order-box-card {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 12px;
      padding: 24px 28px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .box-card-title {
      font-size: 14.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 16px;
    }

    /* Product Table in Left Card */
    .items-table-header {
      background-color: #2b2d33;
      color: #ffffff;
      border-radius: 6px;
      padding: 10px 16px;
      display: grid;
      grid-template-columns: 2.2fr 1.2fr 0.8fr 1.2fr;
      font-size: 11px;
      font-weight: 700;
      margin-bottom: 12px;
    }

    .items-table-row {
      display: grid;
      grid-template-columns: 2.2fr 1.2fr 0.8fr 1.2fr;
      align-items: center;
      padding: 12px 16px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 11.5px;
    }

    .prod-cell {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .prod-thumb-box {
      width: 40px;
      height: 40px;
      border-radius: 6px;
      background: #d4d4d8;
      object-fit: cover;
      flex-shrink: 0;
    }

    .prod-name-title {
      font-size: 12px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 2px;
      line-height: 1.3;
    }

    .prod-cat-sub {
      font-size: 10px;
      color: #8c96a4;
    }

    /* Total Harga Summary in Left Card */
    .total-calc-wrap {
      margin-top: 24px;
    }

    .calc-row-item {
      display: flex;
      justify-content: space-between;
      font-size: 11.5px;
      color: #475569;
      margin-bottom: 10px;
    }

    .calc-row-item span:last-child {
      color: #141418;
      font-weight: 600;
    }

    .calc-divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 14px 0;
    }

    .final-row-item {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
    }

    .final-price-bold {
      font-size: 18px;
      font-weight: 800;
      color: #141418;
    }

    /* Right Card: Pengambilan & Pembayaran */
    .radio-option-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12px;
      margin-bottom: 14px;
      cursor: pointer;
      user-select: none;
    }

    .radio-left-label {
      display: flex;
      align-items: center;
      gap: 9px;
      font-weight: 600;
      color: #141418;
    }

    .radio-left-label input[type="radio"] {
      width: 15px;
      height: 15px;
      accent-color: #141418;
      cursor: pointer;
    }

    .radio-cost-text {
      color: #64748b;
      font-size: 11px;
      font-weight: 600;
    }

    /* Address Input Wrapper with Integrated Button */
    .address-input-wrapper {
      display: flex;
      align-items: center;
      border: 1px solid #cbd5e1;
      border-radius: 7px;
      background: #ffffff;
      padding: 4px 5px 4px 12px;
      margin-top: 10px;
      margin-bottom: 6px;
      transition: all 0.2s ease;
    }

    .address-input-wrapper:focus-within {
      border-color: #2b4570;
      box-shadow: 0 0 0 2px rgba(43, 69, 112, 0.1);
    }

    .address-input-wrapper.error {
      border-color: #ef4444;
      box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.15);
    }

    .address-field {
      flex: 1;
      border: none;
      outline: none;
      font-size: 11.5px;
      color: #141418;
      background: transparent;
      padding: 6px 8px 6px 0;
      font-family: inherit;
      width: 100%;
      min-width: 0;
    }

    .btn-default-addr {
      flex-shrink: 0;
      padding: 5px 12px;
      background: #f8fafc;
      border: 1px solid #cbd5e1;
      border-radius: 5px;
      font-size: 10.5px;
      font-weight: 700;
      color: #334155;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      white-space: nowrap;
      transition: all 0.15s;
    }

    .btn-default-addr:hover {
      background: #e2e8f0;
      color: #0f172a;
    }

    .btn-default-addr:active {
      transform: scale(0.98);
    }

    /* Address Status and Quick Action Below Box */
    .address-helper-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      font-size: 10.5px;
      padding: 0 2px;
    }

    .address-badge-status {
      color: #64748b;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .btn-change-address {
      background: none;
      border: none;
      color: #2b4570;
      font-size: 10.5px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: underline;
      padding: 0;
    }

    .btn-change-address:hover {
      color: #1e3355;
    }

    .section-divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 18px 0 20px 0;
    }

    /* Sticky Bottom Bar */
    .sticky-checkout-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      padding: 14px 48px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 18px;
      z-index: 100;
      box-shadow: 0 -4px 14px rgba(0,0,0,0.03);
    }

    .total-footer-label {
      font-size: 13.5px;
      color: #141418;
    }

    .total-footer-val {
      font-size: 16.5px;
      font-weight: 800;
      color: #141418;
      margin-right: 12px;
    }

    .btn-footer-cancel {
      padding: 9px 26px;
      border: 1.5px solid #cbd5e1;
      background: #ffffff;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      color: #141418;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.15s;
    }

    .btn-footer-cancel:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }

    .btn-footer-proceed {
      padding: 9px 24px;
      border: none;
      background: #2b4570;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      color: #ffffff;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 7px;
      cursor: pointer;
      transition: background 0.15s;
    }

    .btn-footer-proceed:hover {
      background: #1e3355;
    }

    /* Toast Notification */
    .address-toast {
      position: fixed;
      top: 24px;
      right: 24px;
      background: #10b981;
      color: #ffffff;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      box-shadow: 0 4px 14px rgba(0,0,0,0.12);
      z-index: 9999;
      opacity: 0;
      transform: translateY(-10px);
      transition: all 0.25s ease;
      pointer-events: none;
    }

    .address-toast.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* Address Selection Modal */
    .address-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.45);
      backdrop-filter: blur(2px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }

    .address-modal-backdrop.show {
      display: flex;
    }

    .address-modal-card {
      background: #ffffff;
      border-radius: 12px;
      max-width: 480px;
      width: 100%;
      padding: 24px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      animation: modalFadeIn 0.2s ease;
    }

    @keyframes modalFadeIn {
      from { opacity: 0; transform: scale(0.96); }
      to { opacity: 1; transform: scale(1); }
    }

    .address-modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
      padding-bottom: 12px;
      border-bottom: 1px solid #e2e8f0;
    }

    .address-modal-title {
      font-size: 15px;
      font-weight: 800;
      color: #0f172a;
    }

    .btn-close-modal {
      background: none;
      border: none;
      font-size: 20px;
      line-height: 1;
      color: #94a3b8;
      cursor: pointer;
    }

    .saved-addr-card {
      border: 1.5px solid #e2e8f0;
      border-radius: 8px;
      padding: 12px 14px;
      margin-bottom: 10px;
      cursor: pointer;
      transition: all 0.15s;
    }

    .saved-addr-card:hover {
      border-color: #2b4570;
      background: #f8fafc;
    }

    .saved-addr-card.selected {
      border-color: #2b4570;
      background: #eff6ff;
    }

    .saved-addr-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 4px;
    }

    .saved-addr-tag {
      font-size: 11px;
      font-weight: 700;
      color: #2b4570;
    }

    .saved-addr-detail {
      font-size: 11.5px;
      color: #334155;
      line-height: 1.4;
    }

    .custom-addr-wrap {
      margin-top: 14px;
    }

    .custom-addr-label {
      display: block;
      font-size: 11px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 6px;
    }

    .custom-addr-textarea {
      width: 100%;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 8px 10px;
      font-size: 11.5px;
      font-family: inherit;
      color: #141418;
      outline: none;
      resize: vertical;
    }

    .custom-addr-textarea:focus {
      border-color: #2b4570;
    }

    .address-modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
      padding-top: 14px;
      border-top: 1px solid #e2e8f0;
    }

    .btn-modal-cancel {
      padding: 8px 16px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      border-radius: 6px;
      font-size: 11.5px;
      font-weight: 600;
      color: #475569;
      cursor: pointer;
    }

    .btn-modal-apply {
      padding: 8px 18px;
      border: none;
      background: #2b4570;
      border-radius: 6px;
      font-size: 11.5px;
      font-weight: 700;
      color: #ffffff;
      cursor: pointer;
    }

    @media (max-width: 860px) {
      .checkout-two-cols {
        grid-template-columns: 1fr;
      }
      .top-dark-bar, .sub-nav-bar, .sticky-checkout-footer {
        padding: 12px 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Top Dark Header -->
  <header class="top-dark-bar">
    <a href="{{ route('home') }}" class="top-brand-logo">Maybead.s</a>
    <ul class="top-menu-links">
      <li><a href="{{ route('home') }}">BERANDA</a></li>
      <li><a href="{{ route('contact') }}">KONTAK</a></li>
      <li><a href="{{ route('main.dashboard') }}">PRODUK</a></li>
    </ul>
  </header>

  <!-- Sub Navbar -->
  <div class="sub-nav-bar">
    <a href="{{ route('main.dashboard') }}" class="sub-nav-brand">
      <span class="brand-title">Maybeads</span>
      <span class="sub-nav-crumb">/ Katalog Belanja</span>
    </a>
    <div class="sub-nav-actions">
      <a href="{{ route('cart.index') }}" class="sub-btn-pill">
        <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        <span>Keranjang</span>
      </a>
      <a href="{{ route('orders.history') }}" class="sub-btn-pill">
        <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        <span>Akun</span>
        <svg viewBox="0 0 24 24" style="width:10px;height:10px;stroke:currentColor;fill:none;stroke-width:2.5;"><polyline points="6 9 12 15 18 9"></polyline></svg>
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <main class="order-checkout-main">
    <h1 class="page-title-main">Detail Data Pesanan</h1>

    <div class="checkout-two-cols">
      
      <!-- Kotak Kiri: Daftar Produk & Total Harga -->
      <div class="order-box-card">
        <h2 class="box-card-title">Daftar Produk</h2>

        <div class="items-table-header">
          <span>Nama Produk</span>
          <span>Harga Satuan</span>
          <span>Jumlah</span>
          <span style="text-align: right;">Total</span>
        </div>

        <div id="checkoutItemsList">
          <!-- Item 1 Default Sample matching Mockup -->
          <div class="items-table-row">
            <div class="prod-cell">
              <div class="prod-thumb-box"></div>
              <div>
                <div class="prod-name-title">Deskripsi Nama Produk</div>
                <div class="prod-cat-sub">Nama Kategori</div>
              </div>
            </div>
            <div>Rp 42.000,00</div>
            <div>x 1</div>
            <div style="text-align: right; font-weight: 600;">Rp 42.000,00</div>
          </div>

          <!-- Item 2 Default Sample matching Mockup -->
          <div class="items-table-row">
            <div class="prod-cell">
              <div class="prod-thumb-box"></div>
              <div>
                <div class="prod-name-title">Deskripsi Nama Produk</div>
                <div class="prod-cat-sub">Nama Kategori</div>
              </div>
            </div>
            <div>Rp 42.000,00</div>
            <div>x 1</div>
            <div style="text-align: right; font-weight: 600;">Rp 42.000,00</div>
          </div>

          <!-- Item 3 Default Sample matching Mockup -->
          <div class="items-table-row">
            <div class="prod-cell">
              <div class="prod-thumb-box"></div>
              <div>
                <div class="prod-name-title">Deskripsi Nama Produk</div>
                <div class="prod-cat-sub">Nama Kategori</div>
              </div>
            </div>
            <div>Rp 42.000,00</div>
            <div>x 1</div>
            <div style="text-align: right; font-weight: 600;">Rp 42.000,00</div>
          </div>
        </div>

        <!-- Total Harga Summary -->
        <div class="total-calc-wrap">
          <h2 class="box-card-title" style="margin-bottom: 14px;">Total Harga</h2>

          <div class="calc-row-item">
            <span>Subtotal</span>
            <span id="subtotalVal">Rp. 42.000,00</span>
          </div>

          <div class="calc-row-item">
            <span>Biaya Pengantaran</span>
            <span id="pengantaranVal">Rp. 0,00</span>
          </div>

          <div class="calc-row-item">
            <span>Biaya Admin</span>
            <span id="adminVal">Rp. 200,00</span>
          </div>

          <div class="calc-row-item">
            <span>Pengambilan</span>
            <span id="pengambilanVal">Rp. 1.000,00</span>
          </div>

          <div class="calc-divider"></div>

          <div class="final-row-item">
            <span>Harga Akhir</span>
            <span class="final-price-bold" id="finalPriceVal">43.200,00</span>
          </div>
        </div>

      </div>

      <!-- Kotak Kanan: Pilihan Pengambilan & Pembayaran -->
      <div class="order-box-card">
        <h2 class="box-card-title">Pilih Jenis Pengambilan:</h2>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pickup_type" value="pickup" checked id="radioPickup">
            <span>Ambil Ditempat</span>
          </span>
          <span class="radio-cost-text">+ Rp 1.000</span>
        </label>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pickup_type" value="delivery" id="radioDelivery">
            <span>Diantar</span>
          </span>
          <span class="radio-cost-text" id="deliveryHintText">Menghitung...</span>
        </label>

        <!-- Address Box with Integrated Button -->
        <div class="address-input-wrapper" id="addressInputWrapper">
          <input type="text"
                 class="address-field"
                 id="addressField"
                 placeholder="Isi Alamat Lengkap..."
                 value="{{ $user?->address ?? 'Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru' }}">
          <button type="button" class="btn-default-addr" id="btnDefaultAddress" title="Gunakan Alamat Default">Alamat Default</button>
        </div>

        <div class="address-helper-row">
          <span class="address-badge-status" id="addressStatusBadge">📍 Toko: Store Maybeads Banjarbaru</span>
          <button type="button" class="btn-change-address" id="btnOpenAddressModal">Pilih Alamat Lain</button>
        </div>

        <div class="section-divider"></div>

        <h2 class="box-card-title">Pilih Jenis Pembayaran</h2>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pay_type" value="qris" checked id="radioQris">
            <span>QRIS</span>
          </span>
          <span class="radio-cost-text">+ Rp 200</span>
        </label>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pay_type" value="ewallet" id="radioEwallet">
            <span>E-Wallet</span>
          </span>
          <span class="radio-cost-text">+ Rp 200</span>
        </label>

      </div>

    </div>
  </main>

  <!-- Sticky Bottom Bar -->
  <footer class="sticky-checkout-footer">
    <div class="total-footer-label">Total</div>
    <div class="total-footer-val" id="footerTotalDisplay">Rp. 48.000</div>
    <a href="{{ route('cart.index') }}" class="btn-footer-cancel">Batal</a>
    <a href="{{ route('payment.qris') }}" class="btn-footer-proceed" id="btnGoToPayment">
      <span>Lanjut Ke Pembayaran</span>
      <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
    </a>
  </footer>

  <!-- Modal Pilih Alamat -->
  <div class="address-modal-backdrop" id="addressModalBackdrop">
    <div class="address-modal-card">
      <div class="address-modal-header">
        <h3 class="address-modal-title">Pilih Alamat Pengiriman</h3>
        <button type="button" class="btn-close-modal" id="btnCloseAddressModal">&times;</button>
      </div>

      <div class="saved-addr-card selected" data-address="Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru">
        <div class="saved-addr-top">
          <span class="saved-addr-tag">🏠 Rumah (Default)</span>
          <span style="font-size:10px; color:#10b981; font-weight:700;">✓ Aktif</span>
        </div>
        <div class="saved-addr-detail">Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru, Kalimantan Selatan</div>
      </div>

      <div class="saved-addr-card" data-address="Gedung Menara Banjarbaru Lt. 3, Jl. A. Yani Km. 34, Banjarbaru Selatan">
        <div class="saved-addr-top">
          <span class="saved-addr-tag">🏢 Kantor</span>
        </div>
        <div class="saved-addr-detail">Gedung Menara Banjarbaru Lt. 3, Jl. A. Yani Km. 34, Banjarbaru Selatan</div>
      </div>

      <div class="saved-addr-card" data-address="Store Maybeads, Jl. Panglima Batur No. 12, Banjarbaru">
        <div class="saved-addr-top">
          <span class="saved-addr-tag">🏪 Store Maybeads</span>
        </div>
        <div class="saved-addr-detail">Store Maybeads, Jl. Panglima Batur No. 12, Loktabat Utara, Banjarbaru</div>
      </div>

      <div class="custom-addr-wrap">
        <label class="custom-addr-label" for="customAddressInput">Ketik Alamat Baru:</label>
        <textarea id="customAddressInput" class="custom-addr-textarea" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."></textarea>
      </div>

      <div class="address-modal-footer">
        <button type="button" class="btn-modal-cancel" id="btnCancelAddressModal">Tutup</button>
        <button type="button" class="btn-modal-apply" id="btnApplyAddress">Gunakan Alamat Ini</button>
      </div>
    </div>
  </div>

  <!-- Toast Feedback -->
  <div class="address-toast" id="addressToast">✓ Alamat default diterapkan</div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let currentSubtotal = 42000;
      let hasCustomCart = false;

      const defaultAddressHome = 'Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru';
      const storeAddress = 'Store Maybeads — Jl. Panglima Batur No. 12, Banjarbaru';

      const addressField = document.getElementById('addressField');
      const addressInputWrapper = document.getElementById('addressInputWrapper');
      const btnDefaultAddress = document.getElementById('btnDefaultAddress');
      const addressStatusBadge = document.getElementById('addressStatusBadge');
      const addressToast = document.getElementById('addressToast');
      const deliveryHintText = document.getElementById('deliveryHintText');

      const addressModalBackdrop = document.getElementById('addressModalBackdrop');
      const btnOpenAddressModal = document.getElementById('btnOpenAddressModal');
      const btnCloseAddressModal = document.getElementById('btnCloseAddressModal');
      const btnCancelAddressModal = document.getElementById('btnCancelAddressModal');
      const btnApplyAddress = document.getElementById('btnApplyAddress');
      const customAddressInput = document.getElementById('customAddressInput');
      const savedAddrCards = document.querySelectorAll('.saved-addr-card');

      function showToast(msg) {
        if (!addressToast) return;
        addressToast.textContent = msg || '✓ Alamat berhasil diterapkan';
        addressToast.classList.add('show');
        setTimeout(() => {
          addressToast.classList.remove('show');
        }, 2200);
      }

      // Restore saved address from localStorage if exists
      const savedAddr = localStorage.getItem('maybeads_order_address');
      if (savedAddr && addressField) {
        addressField.value = savedAddr;
      }

      // Populate Cart Items if available in localStorage
      try {
        const rawCheckout = localStorage.getItem('maybeads_checkout_items');
        const rawCart = localStorage.getItem('maybeads_cart');
        let items = null;

        if (rawCheckout) {
          items = JSON.parse(rawCheckout);
        } else if (rawCart) {
          const parsed = JSON.parse(rawCart);
          items = parsed.filter(c => c.checked !== false);
        }

        if (items && items.length > 0) {
          hasCustomCart = true;
          const container = document.getElementById('checkoutItemsList');
          container.innerHTML = '';
          let sum = 0;

          items.forEach(item => {
            let price = Number(item.rawPrice) || 42000;
            if (price < 1000) price *= 1000;
            const q = Number(item.qty) || 1;
            const lineTotal = price * q;
            sum += lineTotal;

            const row = document.createElement('div');
            row.className = 'items-table-row';
            const imgHtml = item.image
              ? `<img src="${item.image}" alt="${item.name}" class="prod-thumb-box">`
              : `<div class="prod-thumb-box"></div>`;

            row.innerHTML = `
              <div class="prod-cell">
                ${imgHtml}
                <div>
                  <div class="prod-name-title">${item.name || 'Deskripsi Nama Produk'}</div>
                  <div class="prod-cat-sub">${item.color || 'Aksesoris'}</div>
                </div>
              </div>
              <div>Rp ${price.toLocaleString('id-ID')},00</div>
              <div>x ${q}</div>
              <div style="text-align: right; font-weight: 600;">Rp ${lineTotal.toLocaleString('id-ID')},00</div>
            `;
            container.appendChild(row);
          });

          currentSubtotal = sum;
        }
      } catch(e) {
        console.error('Error loading cart items:', e);
      }

      function updateCalculation() {
        const isDelivery = document.getElementById('radioDelivery').checked;
        const pengantaranCost = isDelivery ? 15000 : 0;
        const pengambilanCost = isDelivery ? 0 : 1000;
        const adminCost = 200;

        // Update delivery hint text on right card
        if (isDelivery) {
          deliveryHintText.textContent = '+ Rp 15.000';
          deliveryHintText.style.color = '#2b4570';
          addressStatusBadge.textContent = '🚚 Pengantaran Kurir ke Alamat Anda';
        } else {
          deliveryHintText.textContent = 'Menghitung...';
          deliveryHintText.style.color = '#64748b';
          addressStatusBadge.textContent = '📍 Toko: Store Maybeads Banjarbaru';
        }

        if (hasCustomCart) {
          const totalFinal = currentSubtotal + pengantaranCost + pengambilanCost + adminCost;
          
          document.getElementById('subtotalVal').textContent = 'Rp. ' + currentSubtotal.toLocaleString('id-ID') + ',00';
          document.getElementById('pengantaranVal').textContent = isDelivery ? 'Rp. 15.000,00' : 'Rp. 0,00';
          document.getElementById('adminVal').textContent = 'Rp. 200,00';
          document.getElementById('pengambilanVal').textContent = isDelivery ? 'Rp. 0,00' : 'Rp. 1.000,00';
          
          document.getElementById('finalPriceVal').textContent = totalFinal.toLocaleString('id-ID') + ',00';
          document.getElementById('footerTotalDisplay').textContent = 'Rp. ' + totalFinal.toLocaleString('id-ID');
        } else {
          // Fallback mockup calculation
          const totalFinal = currentSubtotal + pengantaranCost + pengambilanCost + adminCost;
          document.getElementById('subtotalVal').textContent = 'Rp. 42.000,00';
          document.getElementById('pengantaranVal').textContent = isDelivery ? 'Rp. 15.000,00' : 'Rp. 0,00';
          document.getElementById('adminVal').textContent = 'Rp. 200,00';
          document.getElementById('pengambilanVal').textContent = isDelivery ? 'Rp. 0,00' : 'Rp. 1.000,00';
          document.getElementById('finalPriceVal').textContent = totalFinal.toLocaleString('id-ID') + ',00';
          document.getElementById('footerTotalDisplay').textContent = 'Rp. ' + totalFinal.toLocaleString('id-ID');
        }
      }

      // Radio Listeners
      const radios = document.querySelectorAll('input[name="pickup_type"], input[name="pay_type"]');
      radios.forEach(r => r.addEventListener('change', () => {
        updateCalculation();
        addressInputWrapper.classList.remove('error');
      }));

      // Default Address Button: Instantly sets to default and gives feedback
      if (btnDefaultAddress && addressField) {
        btnDefaultAddress.addEventListener('click', () => {
          const isPickup = document.getElementById('radioPickup').checked;
          const targetAddr = isPickup ? defaultAddressHome : defaultAddressHome;
          addressField.value = targetAddr;
          localStorage.setItem('maybeads_order_address', targetAddr);
          addressInputWrapper.classList.remove('error');
          showToast('✓ Alamat default diterapkan');
          addressField.focus();
        });
      }

      // Input change listener: persists address typing
      if (addressField) {
        addressField.addEventListener('input', () => {
          addressInputWrapper.classList.remove('error');
          localStorage.setItem('maybeads_order_address', addressField.value.trim());
        });
      }

      // Open Modal
      if (btnOpenAddressModal) {
        btnOpenAddressModal.addEventListener('click', () => {
          addressModalBackdrop.classList.add('show');
        });
      }

      // Close Modal
      function closeModal() {
        if (addressModalBackdrop) addressModalBackdrop.classList.remove('show');
      }

      if (btnCloseAddressModal) btnCloseAddressModal.addEventListener('click', closeModal);
      if (btnCancelAddressModal) btnCancelAddressModal.addEventListener('click', closeModal);
      if (addressModalBackdrop) {
        addressModalBackdrop.addEventListener('click', (e) => {
          if (e.target === addressModalBackdrop) closeModal();
        });
      }

      // Card selection in modal
      let selectedModalAddr = defaultAddressHome;
      savedAddrCards.forEach(card => {
        card.addEventListener('click', () => {
          savedAddrCards.forEach(c => c.classList.remove('selected'));
          card.classList.add('selected');
          selectedModalAddr = card.dataset.address;
          if (customAddressInput) customAddressInput.value = '';
        });
      });

      // Apply address from modal
      if (btnApplyAddress) {
        btnApplyAddress.addEventListener('click', () => {
          let chosen = selectedModalAddr;
          if (customAddressInput && customAddressInput.value.trim() !== '') {
            chosen = customAddressInput.value.trim();
          }
          if (addressField) {
            addressField.value = chosen;
            localStorage.setItem('maybeads_order_address', chosen);
            addressInputWrapper.classList.remove('error');
          }
          closeModal();
          showToast('✓ Alamat pengiriman diperbarui');
        });
      }

      // Proceed to Payment Button
      const btnGoToPayment = document.getElementById('btnGoToPayment');
      if (btnGoToPayment) {
        btnGoToPayment.addEventListener('click', (e) => {
          e.preventDefault();
          const isDelivery = document.getElementById('radioDelivery').checked;
          const address = addressField ? addressField.value.trim() : '';
          
          if (isDelivery && !address) {
            addressInputWrapper.classList.add('error');
            addressField.focus();
            showToast('⚠️ Silakan masukkan alamat pengantaran!');
            return;
          }

          const isQris = document.getElementById('radioQris').checked;
          localStorage.setItem('maybeads_order_address', address || storeAddress);
          localStorage.setItem('maybeads_order_pickup', isDelivery ? 'delivery' : 'pickup');
          localStorage.setItem('maybeads_order_payment_method', isQris ? 'qris' : 'ewallet');

          window.location.href = "{{ route('payment.qris') }}";
        });
      }

      // Initial calculation
      updateCalculation();
    });
  </script>
</body>
</html>
