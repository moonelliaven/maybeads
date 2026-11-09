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

    /* Hero Banner */
    .hero-banner-wrap {
      position: relative;
      height: 110px;
      background: linear-gradient(135deg, #1e293b 0%, #293b59 50%, #355389 100%);
      overflow: hidden;
      display: flex;
      align-items: center;
    }

    .hero-banner-bg {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.35;
      mix-blend-mode: overlay;
    }

    /* Main Container */
    .receipt-main-wrap {
      max-width: 480px;
      width: 100%;
      margin: 28px 40px 60px;
      padding: 0;
      flex: 1;
    }

    .page-back-crumb {
      font-size: 11.5px;
      color: #64748b;
      margin-bottom: 4px;
      display: inline-block;
      text-decoration: none;
    }

    .page-back-crumb:hover {
      color: #141418;
    }

    .page-heading-title {
      font-size: 19px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 24px;
    }

    /* Struk Card Box */
    .receipt-card-box {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 12px;
      padding: 26px 28px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
      margin-bottom: 16px;
    }

    .receipt-card-title {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 18px;
    }

    .receipt-row-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 11.5px;
      color: #475569;
      margin-bottom: 12px;
    }

    .receipt-row-line .val-text {
      color: #141418;
      font-weight: 500;
      text-align: right;
    }

    .receipt-divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 16px 0;
    }

    .receipt-section-subtitle {
      font-size: 14px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 14px;
    }

    .receipt-total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
      font-weight: 700;
      color: #141418;
    }

    /* Download Receipt Button */
    .btn-download-receipt {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 8px;
      width: 100%;
      padding: 12px 20px;
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 8px;
      color: #141418;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.15s, border-color 0.15s;
    }

    .btn-download-receipt:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }

    @media print {
      .top-dark-bar, .hero-banner-wrap, .page-back-crumb, .btn-download-receipt {
        display: none !important;
      }
      .receipt-main-wrap {
        margin: 0;
        max-width: 100%;
      }
      .receipt-card-box {
        border: none;
        box-shadow: none;
      }
    }

    @media (max-width: 800px) {
      .receipt-main-wrap {
        margin: 20px auto 60px;
        padding: 0 20px;
      }
      .top-dark-bar {
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

  <!-- Hero Banner Motif -->
  <div class="hero-banner-wrap">
    <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Banner" class="hero-banner-bg">
  </div>

  <!-- Main Content -->
  <main class="receipt-main-wrap">
    <a href="{{ route('cart.index') }}" class="page-back-crumb">&lt; Dashboard / Keranjang / Order</a>
    <h1 class="page-heading-title">Proses Pembayaran</h1>

    <div class="receipt-card-box" id="printableReceipt">
      <div class="receipt-card-title">Struk Pembayaran</div>

      <div class="receipt-row-line">
        <span>Nomor Pesanan</span>
        <span class="val-text">#MD0SJA198231837</span>
      </div>

      <div class="receipt-divider"></div>

      <div class="receipt-section-subtitle">Rincian Pembayaran</div>

      <div class="receipt-row-line">
        <span>Subtotal</span>
        <span class="val-text" id="receiptSubtotal">Rp 120.000,00</span>
      </div>

      <div class="receipt-row-line">
        <span>Pengantaran</span>
        <span class="val-text">Rp 15.000,00</span>
      </div>

      <div class="receipt-row-line">
        <span>Biaya Admin</span>
        <span class="val-text">Rp 2.500,00</span>
      </div>

      <div class="receipt-divider"></div>

      <div class="receipt-total-row">
        <span>Total Pembayaran</span>
        <span id="receiptTotal">Rp. 137.500,00</span>
      </div>

      <div class="receipt-divider"></div>

      <div class="receipt-row-line">
        <span>Metode Pembayaran</span>
        <span class="val-text">QRIS</span>
      </div>

      <div class="receipt-row-line">
        <span>Status Pembayaran</span>
        <span class="val-text">Berhasil</span>
      </div>

      <div class="receipt-row-line">
        <span>ID Transaksi</span>
        <span class="val-text">QRIS-234878472</span>
      </div>

      <div class="receipt-row-line">
        <span>Referensi</span>
        <span class="val-text">0202093123</span>
      </div>

      <div class="receipt-row-line">
        <span>Tanggal</span>
        <span class="val-text">{{ now()->format('d F Y, H:i') }} WIB</span>
      </div>
    </div>

    <button type="button" class="btn-download-receipt" onclick="window.print()">
      <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
      <span>Unduh Struk</span>
    </button>
  </main>

  <script>
    // Read amounts if available
    try {
      const raw = localStorage.getItem('maybeads_cart');
      if (raw) {
        const cart = JSON.parse(raw);
        const checked = cart.filter(c => c.checked !== false);
        if (checked.length > 0) {
          const sub = checked.reduce((acc, curr) => acc + ((curr.rawPrice || 24000) * (curr.qty || 1)), 0);
          const finalTotal = sub + 15000 + 2500;
          document.getElementById('receiptSubtotal').textContent = 'Rp ' + sub.toLocaleString('id-ID') + ',00';
          document.getElementById('receiptTotal').textContent = 'Rp. ' + finalTotal.toLocaleString('id-ID') + ',00';
        }
      }
    } catch(e) {}
  </script>
</body>
</html>
