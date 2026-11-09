<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran QRIS — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css'])

  <style>
    .payment-container {
      max-width: 980px;
      margin: 36px auto 100px;
      padding: 0 24px;
    }

    .payment-grid {
      display: grid;
      grid-template-columns: 1fr 1.15fr;
      gap: 32px;
      align-items: stretch;
    }

    .payment-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
      display: flex;
      flex-direction: column;
    }

    .card-title {
      font-size: 18px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 20px;
      text-align: center;
    }

    .qr-box-wrap {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background: #f8fafc;
      border-radius: 12px;
      border: 1px dashed #cbd5e1;
      margin-bottom: 18px;
    }

    .qr-svg {
      width: 220px;
      height: 220px;
    }

    .qr-instructions {
      font-size: 12.5px;
      color: #64748b;
      line-height: 1.5;
      text-align: center;
    }

    /* Right Card: Order Summary & Timer */
    .bill-row {
      display: flex;
      justify-content: space-between;
      font-size: 13.5px;
      color: #64748b;
      margin-bottom: 10px;
    }

    .bill-row strong {
      color: #141418;
    }

    .bill-total-row {
      display: flex;
      justify-content: space-between;
      font-size: 18px;
      font-weight: 800;
      color: #244173;
      border-top: 1px solid #e2e8f0;
      padding-top: 14px;
      margin-top: 14px;
      margin-bottom: 24px;
    }

    /* Big Countdown Timer Box */
    .timer-banner-box {
      background: #fffbeb;
      border: 1.5px solid #fef3c7;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      margin-top: auto;
      margin-bottom: 20px;
    }

    .timer-caption {
      font-size: 12px;
      font-weight: 700;
      color: #b45309;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 6px;
    }

    .timer-digits {
      font-size: 38px;
      font-weight: 900;
      color: #92400e;
      letter-spacing: 2px;
      line-height: 1;
    }

    .btn-confirm-pay {
      width: 100%;
      padding: 14px 20px;
      background: #244173;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 800;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-confirm-pay:hover {
      background: #19315a;
      transform: translateY(-1px);
    }

    @media (max-width: 768px) {
      .payment-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="dashboard-page">

  <!-- Top Bar -->
  <header class="top-nav-bar">
    <div class="top-nav-container">
      <div class="top-nav-left">
        <a href="{{ route('checkout') }}" class="back-home-link">
          <svg class="chevron-back" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Kembali ke Checkout</span>
        </a>
      </div>
    </div>
  </header>

  <!-- Hero Banner -->
  <section class="catalog-hero-section">
    <div class="hero-bg-layer">
      <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Banner Maybeads" class="hero-bg-image">
      <div class="hero-gradient-overlay"></div>
    </div>
    <div class="hero-container">
      <h1 class="hero-main-title">Temukan Gaya Favoritmu</h1>
      <p class="hero-main-desc">Scan QRIS menggunakan aplikasi e-wallet atau mobile banking favorit Anda.</p>
    </div>
  </section>

  <!-- Payment Content -->
  <main class="payment-container">
    <div class="payment-grid">
      
      <!-- Left: QR Code Box -->
      <div class="payment-card">
        <h3 class="card-title">Scan Kode QRIS</h3>
        
        <div class="qr-box-wrap">
          <!-- Vector QR Code Pattern -->
          <svg class="qr-svg" viewBox="0 0 160 160" fill="none">
            <rect width="160" height="160" fill="#ffffff" rx="8"/>
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
            <!-- Matrix Data Patterns -->
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
        </div>

        <p class="qr-instructions">
          Buka aplikasi pembayaran Anda (BCA Mobile, GoPay, OVO, ShopeePay, Dana, dll) lalu scan kode di atas.
        </p>
      </div>

      <!-- Right: Summary & Countdown -->
      <div class="payment-card">
        <h3 class="card-title" style="text-align: left;">Rincian Tagihan</h3>

        <div class="bill-row">
          <span>Nomor Pesanan</span>
          <strong>#MB-{{ $order['id'] ?? '8821' }}</strong>
        </div>
        <div class="bill-row">
          <span>Waktu Pemesanan</span>
          <span>{{ $order['date'] ?? now()->format('d M Y, H:i') }}</span>
        </div>
        <div class="bill-row">
          <span>Metode Pembayaran</span>
          <strong>QRIS Instant (NMID: ID102008)</strong>
        </div>
        <div class="bill-row">
          <span>Biaya Layanan</span>
          <span style="color: #10b981; font-weight: 700;">GRATIS</span>
        </div>

        <div class="bill-total-row">
          <span>Total Pembayaran</span>
          <span>Rp {{ number_format($order['amount'] ?? 98000, 0, ',', '.') }}</span>
        </div>

        <!-- Big 09:59 Countdown Timer Box -->
        <div class="timer-banner-box">
          <div class="timer-caption">Sisa Waktu Pembayaran</div>
          <div class="timer-digits" id="timerDisplay">09:59</div>
        </div>

        <a href="{{ route('payment.success', ['order_id' => $order['id'] ?? 8821]) }}" class="btn-confirm-pay" id="btnConfirmPay">
          <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>Saya Sudah Membayar</span>
        </a>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="site-dark-footer">
    <div class="site-footer-container">
      <div class="site-footer-bottom">
        <div class="footer-bottom-left">Built for digital cyber-nomads.</div>
        <div class="footer-bottom-right">&copy; {{ date('Y') }} Maybeads. All rights reserved.</div>
      </div>
    </div>
  </footer>

  <script>
    // Live countdown timer from 09:59
    let duration = 9 * 60 + 59;
    const timerDisplay = document.getElementById('timerDisplay');
    const interval = setInterval(() => {
      duration--;
      if (duration < 0) {
        clearInterval(interval);
        timerDisplay.textContent = '00:00';
        return;
      }
      const m = String(Math.floor(duration / 60)).padStart(2, '0');
      const s = String(duration % 60).padStart(2, '0');
      timerDisplay.textContent = `${m}:${s}`;
    }, 1000);

    // Clear cart on payment
    try {
      localStorage.removeItem('maybeads_cart');
    } catch(e) {}
  </script>
</body>
</html>
