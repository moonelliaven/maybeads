<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    .payment-process-main {
      max-width: 1080px;
      width: 100%;
      margin: 28px auto 60px;
      padding: 0 24px;
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

    /* Grid Layout: Left & Right Cards */
    .payment-grid-layout {
      display: grid;
      grid-template-columns: 0.9fr 1.15fr;
      gap: 28px;
      align-items: start;
    }

    .payment-box-card {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 12px;
      padding: 28px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
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

    /* QRIS View */
    .card-head-title {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 4px;
    }

    .card-head-sub {
      font-size: 11px;
      color: #64748b;
      margin-bottom: 18px;
    }

    .qr-frame-box {
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 18px 16px;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 18px;
      background: #fafafb;
    }

    .qris-merchant-badge {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 12px;
    }

    .qris-merchant-name {
      font-size: 16px;
      font-weight: 800;
      color: #141418;
      text-transform: lowercase;
      letter-spacing: -0.01em;
    }

    .qris-nmid-text {
      font-size: 11px;
      color: #64748b;
      font-weight: 600;
      letter-spacing: 0.04em;
      margin-top: 2px;
    }

    .qr-image-wrapper {
      position: relative;
      width: 100%;
      max-width: 270px;
      background: #ffffff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
      border: 1px solid #e2e8f0;
      margin-bottom: 14px;
      cursor: zoom-in;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .qr-image-wrapper:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .qr-poster-img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: contain;
    }

    .qr-zoom-hint {
      position: absolute;
      bottom: 8px;
      right: 8px;
      background: rgba(20, 20, 24, 0.75);
      backdrop-filter: blur(4px);
      color: #ffffff;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 9.5px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 4px;
      pointer-events: none;
    }

    .qr-title-bold {
      font-size: 13.5px;
      font-weight: 800;
      color: #141418;
      margin-top: 4px;
      letter-spacing: 0.04em;
    }

    .qr-desc-small {
      font-size: 10.5px;
      color: #64748b;
      margin-top: 4px;
      text-align: center;
      line-height: 1.45;
      max-width: 290px;
    }

    .qris-copy-nmid-row {
      margin-top: 12px;
      padding: 6px 14px;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 11px;
      color: #475569;
    }

    .btn-copy-nmid {
      background: #f1f5f9;
      border: 1px solid #cbd5e1;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 10.5px;
      font-weight: 700;
      color: #1e293b;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: all 0.15s ease;
    }

    .btn-copy-nmid:hover {
      background: #e2e8f0;
      border-color: #94a3b8;
    }

    .btn-download-black {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 11px 16px;
      background: #141418;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.15s ease, transform 0.1s ease;
    }

    .btn-download-black:hover {
      background: #27272a;
      transform: translateY(-1px);
    }

    /* Modal Zoom QRIS */
    .qr-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(4px);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .qr-modal-content {
      position: relative;
      background: #ffffff;
      border-radius: 12px;
      padding: 24px 20px 18px;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .btn-close-qr-modal {
      position: absolute;
      top: 10px;
      right: 14px;
      background: none;
      border: none;
      font-size: 24px;
      color: #64748b;
      cursor: pointer;
      line-height: 1;
    }

    .btn-close-qr-modal:hover {
      color: #0f172a;
    }

    .qr-modal-full-img {
      width: 100%;
      max-width: 340px;
      height: auto;
      border-radius: 8px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .qr-modal-caption {
      margin-top: 14px;
      text-align: center;
      font-size: 12px;
      color: #334155;
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

    /* Right Card: Summary */
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

    .summary-divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 16px 0;
    }

    .final-row-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .final-label {
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
    }

    .final-num {
      font-size: 17px;
      font-weight: 800;
      color: #141418;
    }

    .timer-gray-card {
      background: #f1f3f7;
      border-radius: 8px;
      padding: 18px;
      text-align: center;
      margin-bottom: 14px;
    }

    .timer-caption {
      font-size: 11px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }

    .timer-digits {
      font-size: 36px;
      font-weight: 800;
      color: #141418;
      line-height: 1.1;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .timer-footer {
      font-size: 9.5px;
      color: #64748b;
    }

    .status-strip-banner {
      background: #fdf5eb;
      color: #b45309;
      border-radius: 6px;
      padding: 10px;
      text-align: center;
      font-size: 11px;
      font-weight: 600;
    }

    /* Bottom Bar */
    .bottom-buttons-bar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 16px;
      margin-top: 36px;
    }

    .btn-cancel-pay {
      padding: 9px 24px;
      border-radius: 8px;
      border: 1.5px solid #cbd5e1;
      background: #ffffff;
      color: #141418;
      font-size: 11.5px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
    }

    .btn-already-paid {
      padding: 9px 26px;
      border-radius: 8px;
      border: none;
      background: #2b4570;
      color: #ffffff;
      font-size: 11.5px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: background 0.15s;
    }

    .btn-already-paid:hover {
      background: #1e3355;
    }

    @media (max-width: 800px) {
      .payment-grid-layout {
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
  <main class="payment-process-main">
    <a href="{{ route('checkout') }}" class="breadcrumb-text">&lt; Dashboard / Keranjang / Order</a>
    <h1 class="page-heading-title">Proses Pembayaran</h1>

    <div class="payment-grid-layout">
      
      <!-- Sisi Kiri: Scan QRIS / Gopay Tab -->
      <div class="payment-box-card">
        <div class="method-switch-tabs">
          <button type="button" class="method-tab-btn active" id="tabQrisBtn" onclick="switchMethod('qris')">QRIS</button>
          <button type="button" class="method-tab-btn" id="tabGopayBtn" onclick="switchMethod('gopay')">Gopay</button>
        </div>

        <!-- View 1: QRIS -->
        <div id="viewQris">
          <div class="card-head-title">Scan QRIS</div>
          <div class="card-head-sub">Scan kode QR di bawah untuk pembayaran</div>

          <div class="qr-frame-box">
            <div class="qris-merchant-badge">
              <span class="qris-merchant-name">maybeads</span>
              <span class="qris-nmid-text">NMID: ID1025444490185 &bull; A01</span>
            </div>

            <div class="qr-image-wrapper" onclick="openQrModal()" title="Klik untuk memperbesar QRIS">
              <img src="{{ asset('images/payment/qris-maybeads.jpg') }}"
                   alt="QRIS maybeads NMID ID1025444490185"
                   class="qr-poster-img"
                   id="qrisImgPreview">
              <div class="qr-zoom-hint">
                <svg viewBox="0 0 24 24" style="width:13px;height:13px;fill:none;stroke:currentColor;stroke-width:2;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                <span>Perbesar</span>
              </div>
            </div>

            <div class="qr-title-bold">SATU QRIS UNTUK SEMUA</div>
            <div class="qr-desc-small">
              Bisa dipakai oleh semua orang melalui: BCA, Mandiri, BRI, BNI, GoPay, OVO, DANA, ShopeePay, LinkAja, AstraPay, Seabank, dan seluruh aplikasi m-banking / e-wallet berlogo QRIS.
            </div>

            <div class="qris-copy-nmid-row">
              <span class="nmid-label">NMID: <strong>ID1025444490185</strong></span>
              <button type="button" class="btn-copy-nmid" onclick="copyNmid('ID1025444490185')" title="Salin NMID">
                <svg viewBox="0 0 24 24" style="width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                <span id="copyNmidText">Salin</span>
              </button>
            </div>
          </div>

          <a href="{{ asset('images/payment/qris-maybeads.jpg') }}" download="QRIS-Maybeads-ID1025444490185.jpg" class="btn-download-black" id="btnDownloadQris">
            <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            <span>Unduh QRIS (Bisa Dipakai Semua Orang)</span>
          </a>
        </div>

        <!-- View 2: Gopay -->
        <div id="viewGopay" class="gopay-view-box">
          <div class="card-head-title" style="text-align: left;">Pembayaran Gopay</div>
          <div class="card-head-sub" style="text-align: left;">Klik link untuk redict</div>

          <button type="button" class="btn-gopay-connect" onclick="window.open('https://gopay.co.id', '_blank')">
            <span>Klik Untuk Menghubungkan</span>
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.5;"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
          </button>
        </div>

      </div>

      <!-- Sisi Kanan: Ringkasan Pembayaran -->
      <div class="payment-box-card">
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

        <div class="summary-divider"></div>

        <div class="final-row-line">
          <span class="final-label">Harga Akhir</span>
          <span class="final-num" id="sumFinal">127.500,00</span>
        </div>

        <!-- Timer Box -->
        <div class="timer-gray-card">
          <div class="timer-caption">Selesaikan Pembayaran Dalam</div>
          <div class="timer-digits" id="liveTimer">09:59</div>
          <div class="timer-footer">Kode QR akan kedaluarsa setelah waktu habis</div>
        </div>

        <!-- Status Strip -->
        <div class="status-strip-banner">
          Menunggu Pembayaran
        </div>
      </div>

    </div>

    <!-- Bottom Buttons -->
    <div class="bottom-buttons-bar">
      <a href="{{ route('checkout') }}" class="btn-cancel-pay">Batalkan Pembayaran</a>
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
    const timerEl = document.getElementById('liveTimer');
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

    // Read cart subtotal
    try {
      const savedSub = localStorage.getItem('maybeads_subtotal');
      if (savedSub && !isNaN(parseInt(savedSub, 10))) {
        const sub = parseInt(savedSub, 10);
        const finalTotal = sub + 15000 + 2500 - 10000;
        document.getElementById('sumSubtotal').textContent = 'Rp. ' + sub.toLocaleString('id-ID') + ',00';
        document.getElementById('sumFinal').textContent = finalTotal.toLocaleString('id-ID') + ',00';
      }
    } catch(e) {}
    // Modal Zoom QRIS
    function openQrModal() {
      document.getElementById('qrModalBackdrop').style.display = 'flex';
    }
    function closeQrModal() {
      document.getElementById('qrModalBackdrop').style.display = 'none';
    }

    // Salin NMID
    function copyNmid(val) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(val).then(() => {
          const t = document.getElementById('copyNmidText');
          if (t) {
            t.textContent = 'Tersalin!';
            setTimeout(() => { t.textContent = 'Salin'; }, 2000);
          }
        }).catch(() => {
          prompt('NMID maybeads:', val);
        });
      } else {
        prompt('NMID maybeads:', val);
      }
    }
  </script>

  <!-- Modal Zoom QRIS Full Preview -->
  <div class="qr-modal-backdrop" id="qrModalBackdrop" onclick="closeQrModal()">
    <div class="qr-modal-content" onclick="event.stopPropagation()">
      <button type="button" class="btn-close-qr-modal" onclick="closeQrModal()">&times;</button>
      <img src="{{ asset('images/payment/qris-maybeads.jpg') }}" alt="QRIS Maybeads Full" class="qr-modal-full-img">
      <div class="qr-modal-caption">
        <strong>maybeads</strong> &bull; NMID: ID1025444490185 &bull; A01<br>
        <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: inline-block;">
          SATU QRIS UNTUK SEMUA &bull; Bisa di-scan dengan seluruh aplikasi bank & e-wallet berlogo QRIS
        </span>
      </div>
    </div>
  </div>
</body>
</html>
