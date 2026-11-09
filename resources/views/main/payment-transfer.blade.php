<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transfer Bank — Maybeads</title>
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
    }

    .bank-account-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 18px;
      margin-bottom: 16px;
    }

    .bank-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .bank-name {
      font-size: 15px;
      font-weight: 800;
      color: #1e293b;
    }

    .btn-copy-acc {
      padding: 4px 12px;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      color: #244173;
      cursor: pointer;
    }

    .acc-number {
      font-size: 20px;
      font-weight: 900;
      color: #244173;
      letter-spacing: 1.5px;
      margin-bottom: 4px;
    }

    .acc-holder {
      font-size: 12.5px;
      color: #64748b;
    }

    .transfer-notes {
      font-size: 12px;
      color: #64748b;
      line-height: 1.6;
      margin-top: 14px;
    }

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
      <p class="hero-main-desc">Silakan lakukan transfer ke salah satu rekening resmi Maybeads berikut.</p>
    </div>
  </section>

  <!-- Payment Content -->
  <main class="payment-container">
    <div class="payment-grid">
      
      <!-- Left: Bank Accounts -->
      <div class="payment-card">
        <h3 class="card-title">Pilihan Rekening Bank</h3>
        
        <!-- BCA -->
        <div class="bank-account-box">
          <div class="bank-header">
            <span class="bank-name">BANK CENTRAL ASIA (BCA)</span>
            <button type="button" class="btn-copy-acc" onclick="navigator.clipboard.writeText('883019283741'); alert('Nomor rekening BCA disalin!');">Salin</button>
          </div>
          <div class="acc-number">8830 1928 3741</div>
          <div class="acc-holder">a/n MAYBEADS STORE INDONESIA</div>
        </div>

        <!-- Mandiri -->
        <div class="bank-account-box">
          <div class="bank-header">
            <span class="bank-name">BANK MANDIRI</span>
            <button type="button" class="btn-copy-acc" onclick="navigator.clipboard.writeText('1370019284721'); alert('Nomor rekening Mandiri disalin!');">Salin</button>
          </div>
          <div class="acc-number">1370 0192 8472 1</div>
          <div class="acc-holder">a/n MAYBEADS STORE INDONESIA</div>
        </div>

        <p class="transfer-notes">
          *Pastikan nominal transfer sesuai dengan total tagihan hingga 3 digit terakhir untuk memudahkan verifikasi otomatis.
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
          <strong>Transfer Bank</strong>
        </div>
        <div class="bill-row">
          <span>Kode Unik</span>
          <span style="color: #244173; font-weight: 700;">+0</span>
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
          <span>Saya Sudah Transfer</span>
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

    try {
      localStorage.removeItem('maybeads_cart');
    } catch(e) {}
  </script>
</body>
</html>

