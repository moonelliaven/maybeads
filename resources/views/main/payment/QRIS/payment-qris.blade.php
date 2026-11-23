<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Proses Pembayaran QRIS — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/qrcode.min.js') }}"></script>

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
      margin-bottom: 20px;
    }

    /* Rounded box for pure QR Code Matrix */
    .qr-code-display-box {
      border: 1.5px solid #dcdfe4;
      border-radius: 16px;
      background: #ffffff;
      padding: 16px;
      display: flex;
      justify-content: center;
      align-items: center;
      max-width: 270px;
      margin: 0 auto;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
      position: relative;
      user-select: none;
    }

    .qr-code-matrix-img {
      width: 100%;
      max-width: 235px;
      height: auto;
      aspect-ratio: 1 / 1;
      display: block;
      image-rendering: auto;
    }

    .qris-official-logo-box {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 18px;
      margin-bottom: 6px;
    }

    .qris-official-logo {
      height: 38px;
      width: auto;
      max-width: 170px;
      display: block;
    }

    .qris-brand-sub {
      font-size: 11px;
      color: #64748b;
      text-align: center;
      margin-top: 6px;
      line-height: 1.5;
      max-width: 300px;
      margin-left: auto;
      margin-right: auto;
    }

    .qris-action-row {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }

    .btn-refresh-qris {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      flex: 1;
      padding: 10px 14px;
      background: #ffffff;
      color: #0f172a;
      border: 1.5px solid #0f172a;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s ease;
    }

    .btn-refresh-qris:hover {
      background: #f1f5f9;
    }

    .btn-download-black {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      flex: 1;
      padding: 10px 14px;
      background: #0f172a;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.15s ease;
    }

    .btn-download-black:hover {
      background: #1e293b;
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
      font-size: 12px;
      color: #475569;
      margin-bottom: 12px;
    }

    .summary-row-line .amount-val {
      font-weight: 600;
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
      font-size: 18px;
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
      font-variant-numeric: tabular-nums;
    }

    .timer-footer {
      font-size: 10px;
      color: #64748b;
    }

    .status-strip-banner {
      background: #eee6dc;
      color: #6b533e;
      border-radius: 6px;
      padding: 10px;
      text-align: center;
      font-size: 11.5px;
      font-weight: 700;
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
      padding: 10px 24px;
      border-radius: 8px;
      border: 1.5px solid #cbd5e1;
      background: #ffffff;
      color: #141418;
      font-size: 12px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
    }

    .btn-already-paid {
      padding: 10px 26px;
      border-radius: 8px;
      border: none;
      background: #2b4570;
      color: #ffffff;
      font-size: 12px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: background 0.15s;
    }

    .btn-already-paid:hover {
      background: #1e3355;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
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
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <main class="payment-process-main">
    <a href="{{ route('checkout') }}" class="breadcrumb-text">&lt; Dashboard / Keranjang / Order</a>
    <h1 class="page-heading-title">Proses Pembayaran QRIS</h1>

    <div class="payment-grid-layout">
      
      <!-- Sisi Kiri: Scan QRIS (Auto-Generated dari API Midtrans) -->
      <div class="payment-box-card">
        <div class="card-head-title">Scan QRIS</div>
        <div class="card-head-sub">Pindai kode QR dinamis di bawah untuk menyelesaikan pembayaran</div>

        <!-- Box Kode QR Murni (Tanpa pop-up foto ketika diklik) -->
        <div class="qr-code-display-box" id="qrDisplayBox" style="position: relative; min-height: 270px; display: flex; align-items: center; justify-content: center; background: #ffffff; border-radius: 14px; overflow: hidden;">
          <div id="qrcodeCanvas" style="display: flex; align-items: center; justify-content: center; padding: 12px; background: #ffffff;"></div>
          <img src="{{ $qrisData['qr_url'] ?? asset('images/payment/qris-code.svg') }}"
               alt="QRIS Auto Generated by Midtrans"
               class="qr-code-matrix-img"
               id="qrisImgPreview"
               style="display: none; max-width: 250px; max-height: 250px;">

          <!-- Overlay Kedaluwarsa Otomatis (10 Menit) -->
          <div id="qrExpiredOverlay" style="display: {{ ($isExpired ?? false) ? 'flex' : 'none' }}; position: absolute; inset: 0; background: rgba(255,255,255,0.95); border-radius: 14px; flex-direction: column; align-items: center; justify-content: center; padding: 18px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="width: 44px; height: 44px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; margin-bottom: 8px;">
              &times;
            </div>
            <strong style="color: #991b1b; font-size: 13.5px;">QRIS Kedaluwarsa</strong>
            <p style="font-size: 11px; color: #64748b; margin-top: 6px; line-height: 1.45;">Batas waktu 10 menit telah habis dan pesanan otomatis dibatalkan. Klik Refresh QRIS untuk membuat transaksi baru.</p>
          </div>
        </div>

        <!-- Logo QRIS Resmi Nasional (Menggantikan Teks Plain QRIS) -->
        <div class="qris-official-logo-box">
          <img src="{{ asset('images/payment/qris-logo.svg') }}" alt="QRIS Standar Pembayaran Nasional" class="qris-official-logo">
        </div>
        <div class="qris-brand-sub">
          Mendukung ShopeePay, GoPay, DANA, OVO, LinkAja, BCA, Mandiri, BRI, BNI &amp; m-Banking se-Indonesia
        </div>

        <!-- Tombol Aksi: Refresh QRIS & Unduh QR -->
        <div class="qris-action-row">
          <button type="button" class="btn-refresh-qris" id="btnRefreshQris" onclick="handleRefreshQris()" title="Klik untuk auto-generate ulang QRIS dari Midtrans">
            <svg id="refreshSpinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;">
              <polyline points="23 4 23 10 17 10"></polyline>
              <polyline points="1 20 1 14 7 14"></polyline>
              <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
            <span>Refresh QRIS</span>
          </button>

          <button type="button" class="btn-download-black" id="btnDownloadQris" onclick="downloadQrisImage()" title="Unduh gambar QRIS ke perangkat">
            <svg id="downloadIcon" viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.2;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            <span id="downloadBtnText">Unduh QR</span>
          </button>
        </div>

        <!-- Fitur Salin URL Foto QRIS / Simulator Midtrans -->
        <div style="margin-top: 12px; display: flex; flex-direction: column; gap: 8px;">
          <button type="button" onclick="copySimulatorUrl()" id="btnCopySimulatorUrl" 
                  data-url="{{ $qrisData['simulator_url'] ?? ($qrisData['qr_url'] ?? '') }}"
                  title="Salin URL resmi HTTPS untuk dicoba di Midtrans QRIS Simulator"
                  style="width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 7px; background: #eff6ff; border: 1.5px solid #bfdbfe; color: #1d4ed8; padding: 8px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.15s ease;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;">
              <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
              <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>
            <span id="copySimulatorText">Salin URL Simulator (HTTPS)</span>
          </button>

        </div>
      </div>

      <!-- Sisi Kanan: Ringkasan Pembayaran & Timer 10 Menit -->
      <div class="payment-box-card">
        <h2 class="summary-heading">Ringkasan Pembayaran</h2>

        <div class="summary-row-line">
          <span>Nomor Pesanan</span>
          <span class="amount-val">#{{ $order['id'] }}</span>
        </div>

        <div class="summary-row-line">
          <span>Metode Pembayaran</span>
          <span class="amount-val" style="color: #2563eb;">QRIS Auto-Generate (Midtrans)</span>
        </div>

        <div class="summary-row-line">
          <span>Subtotal Produk</span>
          <span class="amount-val">Rp {{ number_format($order['amount'], 0, ',', '.') }}</span>
        </div>

        <div class="summary-row-line">
          <span>Biaya Admin (QRIS)</span>
          <span class="amount-val" style="color: #16a34a;">Gratis</span>
        </div>

        <div class="summary-divider"></div>

        <div class="final-row-line">
          <span class="final-label">Total Tagihan</span>
          <span class="final-num" id="sumFinal">Rp {{ number_format($order['amount'], 0, ',', '.') }}</span>
        </div>

        <!-- Timer Box (Batas 10 Menit Otomatis Dibatalkan) -->
        <div class="timer-gray-card">
          <div class="timer-caption">Selesaikan Pembayaran Dalam:</div>
          <div class="timer-digits" id="liveTimer">10:00</div>
          <div class="timer-footer">Pesanan akan otomatis dibatalkan jika tidak dibayar dalam 10 menit</div>
        </div>

        <!-- Status Strip -->
        <div class="status-strip-banner" id="statusStrip">
          @if($isExpired ?? false)
            Pesanan Dibatalkan (Waktu Habis)
          @else
            Menunggu Pembayaran
          @endif
        </div>
      </div>

    </div>

    <!-- Bottom Buttons -->
    <div class="bottom-buttons-bar" id="bottomButtonsBar">
      @if($isExpired ?? false)
        <a href="{{ route('checkout') }}" class="btn-already-paid">Buat Pesanan Baru</a>
      @else
        <button type="button" onclick="manualCancel()" class="btn-cancel-pay">Batalkan Pembayaran</button>
        <a href="{{ route('payment.success', $order['id']) }}" class="btn-already-paid">Saya Sudah Bayar</a>
      @endif
    </div>
  </main>

  <script>
    const orderId = @json($order['id']);
    let remainingSeconds = {{ (int) ($remainingSeconds ?? 600) }};
    let isExpired = {{ ($isExpired ?? false) ? 'true' : 'false' }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const timerEl = document.getElementById('liveTimer');
    const statusStrip = document.getElementById('statusStrip');
    const qrOverlay = document.getElementById('qrExpiredOverlay');
    const bottomBar = document.getElementById('bottomButtonsBar');

    function updateTimerDisplay() {
      if (remainingSeconds <= 0 || isExpired) {
        timerEl.textContent = '00:00';
        handleAutoExpired();
        return;
      }
      const m = String(Math.floor(remainingSeconds / 60)).padStart(2, '0');
      const s = String(remainingSeconds % 60).padStart(2, '0');
      timerEl.textContent = `${m}:${s}`;
    }

    function handleAutoExpired() {
      isExpired = true;
      timerEl.textContent = '00:00';
      timerEl.style.color = '#dc2626';

      if (qrOverlay) qrOverlay.style.display = 'flex';

      if (statusStrip) {
        statusStrip.textContent = 'Waktu Habis — Pesanan Otomatis Dibatalkan';
        statusStrip.style.background = '#fef2f2';
        statusStrip.style.color = '#991b1b';
      }

      if (bottomBar) {
        bottomBar.innerHTML = `
          <button type="button" onclick="handleRefreshQris()" class="btn-cancel-pay" style="border-color: #0f172a; font-weight: 700;">Refresh QRIS Baru</button>
          <a href="{{ route('checkout') }}" class="btn-already-paid">Buat Pesanan Baru</a>
        `;
      }

      // Kirim trigger pembatalan ke server
      fetch(`/payment/qris/cancel/${orderId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      }).catch(() => {});
    }

    let timerInterval = setInterval(() => {
      if (remainingSeconds > 0 && !isExpired) {
        remainingSeconds--;
        updateTimerDisplay();
      } else {
        clearInterval(timerInterval);
        handleAutoExpired();
      }
    }, 1000);

    updateTimerDisplay();

    // Inisialisasi Render QRIS Client-Side (Instant 0 Detik & Bebas Hambatan Jaringan / CORS)
    let currentQrString = @json($qrisData['qr_string'] ?? '');

    function renderClientQr(str) {
      const container = document.getElementById('qrcodeCanvas');
      const imgFallback = document.getElementById('qrisImgPreview');
      if (!container) return;

      if (typeof QRCode !== 'undefined' && str) {
        container.innerHTML = '';
        try {
          new QRCode(container, {
            text: str,
            width: 240,
            height: 240,
            colorDark: "#0f172a",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
          });

          if (imgFallback) {
            imgFallback.style.display = 'none';
          }
          container.style.display = 'flex';

          setTimeout(() => {
            const canvas = container.querySelector('canvas');
            if (canvas && imgFallback) {
              try {
                imgFallback.src = canvas.toDataURL('image/png');
              } catch(e) {}
            }
          }, 150);
          return;
        } catch(err) {
          console.warn('QRCode JS error:', err);
        }
      }

      // Fallback jika QRCode JS belum siap
      if (imgFallback) {
        container.style.display = 'none';
        imgFallback.style.display = 'block';
      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => renderClientQr(currentQrString));
    } else {
      renderClientQr(currentQrString);
    }

    // 1. Fitur Refresh QRIS (Auto-Generate Ulang dari Midtrans API)
    function handleRefreshQris() {
      const btn = document.getElementById('btnRefreshQris');
      const spinner = document.getElementById('refreshSpinner');
      const img = document.getElementById('qrisImgPreview');
      const dlBtn = document.getElementById('btnDownloadQris');

      if (btn) btn.disabled = true;
      if (spinner) spinner.style.animation = 'spin 0.8s linear infinite';

      fetch(`/payment/qris/refresh/${orderId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          if (data.qr_string) {
            currentQrString = data.qr_string;
            renderClientQr(data.qr_string);
          } else if (data.qr_url) {
            img.src = data.qr_url;
            img.style.display = 'block';
            document.getElementById('qrcodeCanvas').style.display = 'none';
          }

          if (dlBtn && data.qr_url) dlBtn.href = data.qr_url;

          const simBtn = document.getElementById('btnCopySimulatorUrl');
          const imgBtn = document.getElementById('btnCopyImageUrl');
          const strBtn = document.getElementById('btnCopyString');
          if (simBtn) simBtn.setAttribute('data-url', data.simulator_url || data.qr_url);
          if (imgBtn) imgBtn.setAttribute('data-url', data.image_url || data.qr_url);
          if (strBtn) strBtn.setAttribute('data-string', data.qr_string || '');

          // Catatan: Refresh QRIS hanya berlaku untuk QRIS itu sendiri, tidak mengulang timer pesanan yang berjalan
          if (typeof data.remaining_seconds !== 'undefined' && data.remaining_seconds !== null) {
            remainingSeconds = Math.min(remainingSeconds, data.remaining_seconds);
            updateTimerDisplay();
          }
        } else {
          alert('Gagal merefresh QRIS: ' + (data.message || 'Terjadi kesalahan.'));
        }
      })
      .catch(err => {
        alert('Gagal menghubungi server untuk refresh QRIS.');
      })
      .finally(() => {
        if (btn) btn.disabled = false;
        if (spinner) spinner.style.animation = '';
      });
    }

    // 2. Fitur Unduh QRIS (Client-side Instant Canvas & Same-Origin Fallback)
    function downloadQrisImage() {
      const img = document.getElementById('qrisImgPreview');
      const btn = document.getElementById('btnDownloadQris');
      const btnText = document.getElementById('downloadBtnText');

      if (btnText) btnText.textContent = 'Mengunduh...';
      if (btn) btn.disabled = true;

      // Strategi 0: Ambil langsung dari Canvas QRCode lokal jika ada (100% cepat & tanpa CORS issue)
      const qrCanvas = document.querySelector('#qrcodeCanvas canvas');
      if (qrCanvas) {
        try {
          const dataUrl = qrCanvas.toDataURL('image/png');
          const link = document.createElement('a');
          link.href = dataUrl;
          link.download = `QRIS-Maybeads-${orderId}.png`;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);

          if (btnText) btnText.textContent = 'Unduh QR';
          if (btn) btn.disabled = false;
          return;
        } catch(canvasErr) {
          console.warn('Local canvas download error:', canvasErr);
        }
      }

      if (!img || !img.src) {
        alert('Gambar QRIS belum tersedia.');
        if (btnText) btnText.textContent = 'Unduh QR';
        if (btn) btn.disabled = false;
        return;
      }

      // Strategi 1: Render via Canvas fallback
      try {
        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth || 320;
        canvas.height = img.naturalHeight || 320;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = `QRIS-Maybeads-${orderId}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        if (btnText) btnText.textContent = 'Unduh QR';
        if (btn) btn.disabled = false;
        return;
      } catch (canvasErr) {
        console.warn('Canvas export skipped, using server proxy...', canvasErr);
      }

      // Strategi 2: Server-side proxy dengan Content-Disposition attachment
      const proxyDownloadUrl = `{{ route('payment.qris.download', $order['id']) }}?url=${encodeURIComponent(img.src)}`;

      fetch(proxyDownloadUrl)
        .then(response => {
          if (!response.ok) throw new Error('Proxy error');
          return response.blob();
        })
        .then(blob => {
          const blobUrl = window.URL.createObjectURL(blob);
          const link = document.createElement('a');
          link.href = blobUrl;
          link.download = `QRIS-Maybeads-${orderId}.png`;
          document.body.appendChild(link);
          link.click();
          setTimeout(() => {
            document.body.removeChild(link);
            window.URL.revokeObjectURL(blobUrl);
          }, 200);
        })
        .catch(() => {
          // Fallback 3: Gunakan iframe atau direct window location
          const iframe = document.createElement('iframe');
          iframe.style.display = 'none';
          iframe.src = proxyDownloadUrl;
          document.body.appendChild(iframe);
          setTimeout(() => {
            document.body.removeChild(iframe);
          }, 3000);
        })
        .finally(() => {
          setTimeout(() => {
            if (btnText) btnText.textContent = 'Unduh QR';
            if (btn) btn.disabled = false;
          }, 600);
        });
    }

    // Pembatalan manual oleh pengguna dengan pop up konfirmasi
    function manualCancel() {
      const doCancelAndRedirect = () => {
        handleAutoExpired();
        window.location.href = '{{ route("checkout") }}';
      };

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Batalkan Pembayaran?',
          text: 'Anda akan dikembalikan ke rincian pesanan checkout sebelum pembayaran.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d9383a',
          cancelButtonColor: '#64748b',
          confirmButtonText: 'Ya, Batalkan',
          cancelButtonText: 'Lanjutkan Bayar',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            doCancelAndRedirect();
          }
        });
      } else {
        if (confirm('Batalkan pembayaran dan kembali ke detail data pesanan sebelum bayar?')) {
          doCancelAndRedirect();
        }
      }
    }

    // Helper Salin Teks ke Clipboard
    function copyTextToClipboard(text, targetTextEl, successMsg, originalMsg) {
      if (!text) {
        alert('Data belum tersedia untuk disalin.');
        return;
      }

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
          if (targetTextEl) {
            targetTextEl.textContent = successMsg;
            setTimeout(() => { targetTextEl.textContent = originalMsg; }, 2500);
          }
        }).catch(() => {
          promptCopyFallback(text, targetTextEl, successMsg, originalMsg);
        });
      } else {
        promptCopyFallback(text, targetTextEl, successMsg, originalMsg);
      }
    }

    function promptCopyFallback(text, targetTextEl, successMsg, originalMsg) {
      const textarea = document.createElement('textarea');
      textarea.value = text;
      textarea.style.position = 'fixed';
      textarea.style.opacity = '0';
      document.body.appendChild(textarea);
      textarea.select();
      try {
        document.execCommand('copy');
        if (targetTextEl) {
          targetTextEl.textContent = successMsg;
          setTimeout(() => { targetTextEl.textContent = originalMsg; }, 2500);
        }
      } catch (err) {
        prompt('Salin teks berikut:', text);
      }
      document.body.removeChild(textarea);
    }

    // 1. Salin URL Resmi untuk Midtrans QRIS Simulator (HTTPS)
    function copySimulatorUrl() {
      const btn = document.getElementById('btnCopySimulatorUrl');
      const textEl = document.getElementById('copySimulatorText');
      const url = btn ? btn.getAttribute('data-url') : '';
      copyTextToClipboard(url, textEl, '✓ URL Simulator Tersalin!', 'Salin URL Simulator (HTTPS)');
    }

    // 2. Salin URL Foto Langsung (.PNG)
    function copyQrisImageUrl() {
      const btn = document.getElementById('btnCopyImageUrl');
      const textEl = document.getElementById('copyImageText');
      const url = btn ? btn.getAttribute('data-url') : (document.getElementById('qrisImgPreview')?.src || '');
      copyTextToClipboard(url, textEl, '✓ URL Foto Tersalin!', 'Salin URL Foto (.PNG)');
    }

    // 3. Salin Payload String QRIS
    function copyQrisString() {
      const btn = document.getElementById('btnCopyString');
      const textEl = document.getElementById('copyStringText');
      const str = btn ? btn.getAttribute('data-string') : '';
      copyTextToClipboard(str, textEl, '✓ String QR Tersalin!', 'Salin String QR');
    }

    // Fallback kompatibilitas nama fungsi lama
    function copyQrisUrl() {
      copySimulatorUrl();
    }
  </script>

</body>
</html>
