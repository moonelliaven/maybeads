<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk Pembayaran — Maybeads</title>
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
      padding: 14px 40px;
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
      gap: 28px;
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
      padding: 12px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .sub-nav-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #355389;
      font-size: 18px;
      font-weight: 800;
    }

    .sub-nav-crumb {
      font-size: 11px;
      color: #94a3b8;
      font-weight: 500;
    }

    .sub-nav-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sub-btn-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 999px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #334155;
      font-size: 11.5px;
      font-weight: 600;
      text-decoration: none;
    }

    /* Main Container */
    .receipt-container-main {
      max-width: 440px;
      width: 100%;
      margin: 28px 40px 60px;
      padding: 0;
      flex: 1;
    }

    .breadcrumb-text {
      font-size: 11.5px;
      color: #64748b;
      margin-bottom: 4px;
      display: inline-block;
      text-decoration: none;
    }

    .page-heading-title {
      font-size: 19px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 24px;
    }

    /* Struk Card */
    .receipt-box-card {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 12px;
      padding: 24px 26px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
      margin-bottom: 16px;
    }

    .receipt-top-status {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 20px;
    }

    .receipt-status-icon-box {
      width: 44px;
      height: 44px;
      border-radius: 8px;
      background: #d4d4d8;
      flex-shrink: 0;
    }

    .receipt-status-head {
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 2px;
    }

    .receipt-status-sub {
      font-size: 10.5px;
      color: #64748b;
    }

    .receipt-order-no-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 12px;
      padding-bottom: 4px;
    }

    .order-label-text {
      font-size: 14px;
      font-weight: 700;
      color: #141418;
    }

    .order-code-text {
      font-size: 15px;
      font-weight: 800;
      color: #141418;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-copy-icon {
      background: none;
      border: none;
      cursor: pointer;
      color: #141418;
      padding: 2px;
    }

    .receipt-divider-line {
      height: 1px;
      background-color: #e5e7eb;
      margin: 14px 0;
    }

    .receipt-subtitle {
      font-size: 12.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 12px;
    }

    .receipt-item-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 11px;
      color: #475569;
      margin-bottom: 8px;
    }

    .receipt-item-line .receipt-val {
      color: #141418;
      font-weight: 500;
      text-align: right;
    }

    .receipt-total-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      font-weight: 800;
      color: #141418;
    }

    /* Button Unduh Struk */
    .btn-download-receipt-box {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 8px;
      width: 100%;
      padding: 12px 18px;
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 8px;
      color: #141418;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.15s;
    }

    .btn-download-receipt-box:hover {
      background: #f8fafc;
    }

    @media print {
      .top-dark-bar, .sub-nav-bar, .breadcrumb-text, .btn-download-receipt-box {
        display: none !important;
      }
      .receipt-container-main {
        margin: 0;
        max-width: 100%;
      }
      .receipt-box-card {
        border: none;
        box-shadow: none;
      }
    }

    @media (max-width: 800px) {
      .receipt-container-main {
        margin: 20px auto 60px;
        padding: 0 20px;
      }
      .top-dark-bar, .sub-nav-bar {
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
    <div class="sub-nav-brand">
      <span>Maybeads</span>
      <span class="sub-nav-crumb">/ Katalog Belanja</span>
    </div>
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
  <main class="receipt-container-main">
    <a href="{{ route('cart.index') }}" class="breadcrumb-text">&lt; Dashboard / Keranjang / Order</a>
    <h1 class="page-heading-title">Proses Pembayaran</h1>

    <div class="receipt-box-card" id="printableReceipt">
      <div class="receipt-top-status">
        <div class="receipt-status-icon-box"></div>
        <div>
          <div class="receipt-status-head">Pembayaran Berhasil</div>
          <div class="receipt-status-sub">Menunggu Konfirmasi dari Pihak Toko</div>
        </div>
      </div>

      <div class="receipt-order-no-row">
        <span class="order-label-text">Nomor Pesanan</span>
        <span class="order-code-text">
          <span>#KDMP-000001</span>
          <button type="button" class="btn-copy-icon" onclick="navigator.clipboard.writeText('#KDMP-000001'); alert('Nomor pesanan disalin!');" title="Salin Nomor">
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.5;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
          </button>
        </span>
      </div>

      <div class="receipt-divider-line"></div>

      <div class="receipt-subtitle">Rincian Pembayaran</div>

      <div class="receipt-item-line">
        <span>Subtotal</span>
        <span class="receipt-val">Rp 120.000,00</span>
      </div>

      <div class="receipt-item-line">
        <span>Pengantaran</span>
        <span class="receipt-val">Rp 15.000,00</span>
      </div>

      <div class="receipt-item-line">
        <span>Biaya Admin</span>
        <span class="receipt-val">Rp 2.500,00</span>
      </div>

      <div class="receipt-divider-line"></div>

      <div class="receipt-total-line">
        <span>Total Pembayaran</span>
        <span>Rp. 137.500,00</span>
      </div>

      <div class="receipt-divider-line"></div>

      <div class="receipt-item-line">
        <span>Metode Pembayaran</span>
        <span class="receipt-val">QRIS</span>
      </div>

      <div class="receipt-item-line">
        <span>Status Pembayaran</span>
        <span class="receipt-val">Berhasil</span>
      </div>

      <div class="receipt-item-line">
        <span>ID Transaksi</span>
        <span class="receipt-val">QRIS-234878472</span>
      </div>

      <div class="receipt-item-line">
        <span>Referensi</span>
        <span class="receipt-val">0202093123</span>
      </div>

      <div class="receipt-item-line">
        <span>Tanggal</span>
        <span class="receipt-val">02 November 2024, 14:35 WIB</span>
      </div>
    </div>

    <button type="button" class="btn-download-receipt-box" onclick="window.print()">
      <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
      <span>Unduh Struk</span>
    </button>
  </main>

</body>
</html>
