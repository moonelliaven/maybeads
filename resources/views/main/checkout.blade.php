<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Proses Pembayaran — Maybeads</title>
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

    /* Hero Banner (Motif Perhiasan Biru Navy) */
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
      font-size: 17px;
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
    .payment-main-wrap {
      max-width: 1080px;
      width: 100%;
      margin: 28px auto 60px;
      padding: 0 24px;
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

    /* Grid Layout: Left Method + Right Summary */
    .payment-two-cols {
      display: grid;
      grid-template-columns: 0.9fr 1.15fr;
      gap: 28px;
      align-items: start;
    }

    .payment-card-box {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 28px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    /* Left Card: QRIS */
    .left-card-title {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 4px;
    }

    .left-card-sub {
      font-size: 11px;
      color: #64748b;
      margin-bottom: 18px;
    }

    .method-switch-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 16px;
    }

    .method-tab-btn {
      padding: 5px 12px;
      border-radius: 6px;
      border: 1px solid #e2e8f0;
      background: #f8fafc;
      font-size: 11px;
      font-weight: 600;
      color: #475569;
      cursor: pointer;
      transition: all 0.15s;
    }

    .method-tab-btn.active {
      background: #141418;
      color: #ffffff;
      border-color: #141418;
    }

    /* QR Code Display */
    .qr-frame-box {
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 18px;
    }

    .qr-image-svg {
      width: 170px;
      height: 170px;
      display: block;
    }

    .qr-label-name {
      font-size: 13.5px;
      font-weight: 800;
      color: #141418;
      margin-top: 10px;
    }

    .qr-supported-text {
      font-size: 8.5px;
      color: #64748b;
      margin-top: 2px;
      text-align: center;
    }

    /* Gopay View */
    .gopay-view-box {
      display: none;
      padding: 30px 10px;
      text-align: center;
    }

    .btn-gopay-connect {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 11px 16px;
      background: #232328;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      margin-top: 10px;
    }

    .btn-gopay-connect:hover {
      background: #141418;
    }

    .btn-download-qr {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      width: 100%;
      padding: 10px 16px;
      background: #232328;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 11.5px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.15s;
    }

    .btn-download-qr:hover {
      background: #141418;
    }

    /* Right Card: Ringkasan Pembayaran */
    .summary-heading {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 18px;
    }

    .summary-row-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 11.5px;
      color: #475569;
      margin-bottom: 12px;
    }

    .summary-row-line .amount-val {
      font-weight: 500;
      color: #141418;
    }

    .summary-divider-line {
      height: 1px;
      background-color: #e5e7eb;
      margin: 16px 0;
    }

    .final-price-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .final-price-label {
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
    }

    .final-price-num {
      font-size: 17px;
      font-weight: 800;
      color: #141418;
    }

    /* Gray Timer Box */
    .timer-gray-card {
      background: #f1f3f7;
      border-radius: 8px;
      padding: 18px;
      text-align: center;
      margin-bottom: 14px;
    }

    .timer-caption-text {
      font-size: 11px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }

    .timer-digits-big {
      font-size: 36px;
      font-weight: 800;
      color: #141418;
      line-height: 1.1;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .timer-footer-note {
      font-size: 9.5px;
      color: #64748b;
    }

    /* Status Strip */
    .status-strip-banner {
      background: #fdf5eb;
      color: #b45309;
      border-radius: 6px;
      padding: 10px;
      text-align: center;
      font-size: 11px;
      font-weight: 600;
    }

    /* Bottom Action Buttons */
    .bottom-buttons-bar {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 16px;
      margin-top: 36px;
    }

    .btn-cancel-pay {
      padding: 9px 24px;
      border-radius: 8px;
      border: 1px solid #fecaca;
      background: #fef2f2;
      color: #dc2626;
      font-size: 11.5px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.15s;
    }

    .btn-cancel-pay:hover {
      background: #fee2e2;
    }

    .btn-already-paid {
      padding: 9px 26px;
      border-radius: 8px;
      border: 1.5px solid #141418;
      background: #ffffff;
      color: #141418;
      font-size: 11.5px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.15s;
    }

    .btn-already-paid:hover {
      background: #141418;
      color: #ffffff;
    }

    @media (max-width: 800px) {
      .payment-two-cols {
        grid-template-columns: 1fr;
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

  <!-- Hero Banner Motif -->
  <div class="hero-banner-wrap">
    <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Banner" class="hero-banner-bg">
  </div>

  <!-- Sub Navbar -->
  <div class="sub-nav-bar">
    <div class="sub-nav-brand">
      <span>Maybead.s</span>
      <span class="sub-nav-crumb">Detail Pesanan</span>
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
  <main class="payment-main-wrap">
    <a href="{{ route('cart.index') }}" class="page-back-crumb">&lt; Dashboard / Keranjang / Order</a>
    <h1 class="page-heading-title">Proses Pembayaran</h1>

    <div class="payment-two-cols">
      
      <!-- Sisi Kiri: Scan QRIS / Gopay Tab -->
      <div class="payment-card-box">
        <div class="method-switch-tabs">
          <button type="button" class="method-tab-btn active" id="tabQrisBtn" onclick="switchMethod('qris')">QRIS</button>
          <button type="button" class="method-tab-btn" id="tabGopayBtn" onclick="switchMethod('gopay')">Gopay</button>
        </div>

        <!-- View 1: QRIS -->
        <div id="viewQris">
          <div class="left-card-title">Scan QRIS</div>
          <div class="left-card-sub">Scan kode QR di bawah untuk pembayaran</div>

          <div class="qr-frame-box">
            <!-- QR Code Vector Exact -->
            <svg class="qr-image-svg" viewBox="0 0 160 160" fill="none">
              <rect width="160" height="160" fill="#ffffff" rx="6"/>
              <!-- Top-Left Target -->
              <rect x="15" y="15" width="40" height="40" fill="#141418" rx="4"/>
              <rect x="23" y="23" width="24" height="24" fill="#ffffff" rx="2"/>
              <rect x="29" y="29" width="12" height="12" fill="#141418"/>
              <!-- Top-Right Target -->
              <rect x="105" y="15" width="40" height="40" fill="#141418" rx="4"/>
              <rect x="113" y="23" width="24" height="24" fill="#ffffff" rx="2"/>
              <rect x="119" y="29" width="12" height="12" fill="#141418"/>
              <!-- Bottom-Left Target -->
              <rect x="15" y="105" width="40" height="40" fill="#141418" rx="4"/>
              <rect x="23" y="113" width="24" height="24" fill="#ffffff" rx="2"/>
              <rect x="29" y="119" width="12" height="12" fill="#141418"/>
              <!-- Pattern Elements -->
              <rect x="65" y="20" width="8" height="8" fill="#141418"/>
              <rect x="80" y="20" width="12" height="8" fill="#141418"/>
              <rect x="65" y="35" width="14" height="8" fill="#141418"/>
              <rect x="85" y="35" width="8" height="8" fill="#141418"/>
              <rect x="20" y="65" width="8" height="14" fill="#141418"/>
              <rect x="35" y="65" width="14" height="8" fill="#141418"/>
              <rect x="20" y="85" width="12" height="8" fill="#141418"/>
              <rect x="38" y="80" width="8" height="14" fill="#141418"/>
              <rect x="65" y="65" width="16" height="16" fill="#141418"/>
              <rect x="88" y="65" width="8" height="8" fill="#141418"/>
              <rect x="88" y="80" width="14" height="8" fill="#141418"/>
              <rect x="110" y="65" width="8" height="12" fill="#141418"/>
              <rect x="125" y="65" width="15" height="8" fill="#141418"/>
              <rect x="110" y="85" width="18" height="8" fill="#141418"/>
              <rect x="135" y="80" width="8" height="14" fill="#141418"/>
              <rect x="65" y="110" width="10" height="10" fill="#141418"/>
              <rect x="82" y="110" width="16" height="8" fill="#141418"/>
              <rect x="65" y="128" width="18" height="8" fill="#141418"/>
              <rect x="90" y="125" width="8" height="14" fill="#141418"/>
              <rect x="110" y="110" width="12" height="12" fill="#141418"/>
              <rect x="130" y="110" width="10" height="10" fill="#141418"/>
              <rect x="115" y="130" width="22" height="8" fill="#141418"/>
            </svg>

            <div class="qr-label-name">QRIS</div>
            <div class="qr-supported-text">Shopeepay, Gopay, Dana, OVO, LinkAja, dan Transferbank Lainnya</div>
          </div>

          <a href="#" class="btn-download-qr" onclick="alert('Mengunduh QRIS Maybeads...'); return false;">
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.5;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            <span>Unduh QR</span>
          </a>
        </div>

        <!-- View 2: Gopay -->
        <div id="viewGopay" class="gopay-view-box">
          <div class="left-card-title" style="text-align: left;">Pembayaran Gopay</div>
          <div class="left-card-sub" style="text-align: left;">Klik link untuk redict</div>

          <button type="button" class="btn-gopay-connect" onclick="window.open('https://gopay.co.id', '_blank')">
            <span>Klik Untuk Menghubungkan</span>
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.5;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
          </button>
        </div>

      </div>

      <!-- Sisi Kanan: Ringkasan Pembayaran -->
      <div class="payment-card-box">
        <h2 class="summary-heading">Ringkasan Pembayaran</h2>

        <div class="summary-row-line">
          <span>Subtotal</span>
          <span class="amount-val" id="sumSubtotal">Rp. 120.000,00</span>
        </div>

        <div class="summary-row-line">
          <span>Biaya Pengantaran</span>
          <span class="amount-val">Rp. 15.000,00</span>
        </div>

        <div class="summary-row-line">
          <span>Biaya Admin</span>
          <span class="amount-val">Rp. 2.500,00</span>
        </div>

        <div class="summary-row-line">
          <span>Pengambilan</span>
          <span class="amount-val" style="color: #64748b;">-Rp. 10.000,00</span>
        </div>

        <div class="summary-divider-line"></div>

        <div class="final-price-row">
          <span class="final-price-label">Harga Akhir</span>
          <span class="final-price-num" id="sumFinal">127.500,00</span>
        </div>

        <!-- Gray Timer Box -->
        <div class="timer-gray-card">
          <div class="timer-caption-text">Selesaikan Pembayaran Dalam</div>
          <div class="timer-digits-big" id="qrisTimer">09:59</div>
          <div class="timer-footer-note">Kode QR akan kedaluarsa setelah waktu habis</div>
        </div>

        <!-- Status Strip -->
        <div class="status-strip-banner">
          Menunggu Pembayaran
        </div>
      </div>

    </div>

    <!-- Bottom Buttons -->
    <div class="bottom-buttons-bar">
      <a href="{{ route('cart.index') }}" class="btn-cancel-pay">Batalkan Pembayaran</a>
      <a href="{{ route('payment.success') }}" class="btn-already-paid">Saya Sudah Bayar</a>
    </div>
  </main>

  <script>
    function switchMethod(m) {
      const qrisTab = document.getElementById('tabQrisBtn');
      const gopayTab = document.getElementById('tabGopayBtn');
      const viewQris = document.getElementById('viewQris');
      const viewGopay = document.getElementById('viewGopay');

      if (m === 'qris') {
        qrisTab.classList.add('active');
        gopayTab.classList.remove('active');
        viewQris.style.display = 'block';
        viewGopay.style.display = 'none';
      } else {
        gopayTab.classList.add('active');
        qrisTab.classList.remove('active');
        viewQris.style.display = 'none';
        viewGopay.style.display = 'block';
      }
    }

    // Countdown 09:59
    let sec = 9 * 60 + 59;
    const timerEl = document.getElementById('qrisTimer');
    const timerInterval = setInterval(() => {
      sec--;
      if (sec < 0) {
        clearInterval(timerInterval);
        timerEl.textContent = '00:00';
        return;
      }
      const m = String(Math.floor(sec / 60)).padStart(2, '0');
      const s = String(sec % 60).padStart(2, '0');
      timerEl.textContent = `${m}:${s}`;
    }, 1000);

    // Read subtotal from cart if available
    try {
      const raw = localStorage.getItem('maybeads_cart');
      if (raw) {
        const cart = JSON.parse(raw);
        const checked = cart.filter(c => c.checked !== false);
        if (checked.length > 0) {
          const sub = checked.reduce((acc, curr) => acc + ((curr.rawPrice || 24000) * (curr.qty || 1)), 0);
          const finalTotal = sub + 15000 + 2500 - 10000;
          document.getElementById('sumSubtotal').textContent = 'Rp. ' + sub.toLocaleString('id-ID') + ',00';
          document.getElementById('sumFinal').textContent = finalTotal.toLocaleString('id-ID') + ',00';
        }
      }
    } catch(e) {}
  </script>
</body>
</html>
