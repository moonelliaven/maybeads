<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Proses Pembayaran Transfer & E-Wallet — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background-color: #f8f9fa;
      color: #141418;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .top-dark-bar {
      background-color: #1a2232;
      color: #ffffff;
      padding: 14px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .brand-logo-text {
      font-size: 20px;
      font-weight: 800;
      letter-spacing: -0.5px;
      color: #ffffff;
      text-decoration: none;
    }
    .order-status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: #94a3b8;
    }
    .status-dot-blink {
      width: 8px;
      height: 8px;
      background-color: #3b82f6;
      border-radius: 50%;
      box-shadow: 0 0 8px rgba(59, 130, 246, 0.8);
      animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.4; transform: scale(0.85); }
    }

    .payment-layout-container {
      max-width: 1080px;
      width: 100%;
      margin: 32px auto;
      padding: 0 20px;
      display: flex;
      flex-direction: column;
      gap: 24px;
    }
    .page-title-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 16px;
    }
    .title-left h1 {
      font-size: 22px;
      font-weight: 800;
      color: #0f172a;
    }
    .title-left p {
      font-size: 13.5px;
      color: #64748b;
      margin-top: 4px;
    }

    .two-columns-grid {
      display: grid;
      grid-template-columns: 1.25fr 1fr;
      gap: 28px;
      align-items: start;
    }

    .payment-box-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .card-title-head {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* Bank Account Option Card */
    .bank-acc-item {
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 14px;
      background: #fafaf9;
      transition: all 0.15s ease;
    }
    .bank-acc-item:hover {
      border-color: #244173;
      background: #ffffff;
    }
    .bank-header-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
    }
    .bank-pill {
      font-size: 12px;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 6px;
      background: #244173;
      color: #ffffff;
    }
    .bank-owner {
      font-size: 12px;
      color: #64748b;
    }
    .acc-number-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #ffffff;
      border: 1px dashed #cbd5e1;
      padding: 10px 14px;
      border-radius: 8px;
    }
    .acc-digits {
      font-family: monospace;
      font-size: 17px;
      font-weight: 700;
      color: #0f172a;
      letter-spacing: 1px;
    }
    .btn-copy-acc {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      color: #2563eb;
      font-size: 12px;
      font-weight: 700;
      padding: 5px 12px;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-copy-acc:hover {
      background: #dbeafe;
    }

    /* Summary Details */
    .summary-heading {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 16px;
    }
    .summary-row-line {
      display: flex;
      justify-content: space-between;
      font-size: 13.5px;
      color: #64748b;
      margin-bottom: 12px;
    }
    .amount-val {
      font-weight: 700;
      color: #0f172a;
    }
    .summary-divider {
      height: 1px;
      background-color: #e2e8f0;
      margin: 16px 0;
    }
    .final-row-line {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .final-label {
      font-size: 14.5px;
      font-weight: 700;
      color: #0f172a;
    }
    .final-num {
      font-size: 20px;
      font-weight: 800;
      color: #0f172a;
    }

    /* Timer Gray Card */
    .timer-gray-card {
      background-color: #f1f5f9;
      border-radius: 10px;
      padding: 16px;
      text-align: center;
      margin-bottom: 18px;
    }
    .timer-caption {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
    }
    .timer-digits {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: 2px;
      margin: 6px 0;
      font-variant-numeric: tabular-nums;
    }
    .timer-footer {
      font-size: 11px;
      color: #94a3b8;
    }

    .status-strip-banner {
      background: #eff6ff;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
      border-radius: 8px;
      padding: 10px;
      text-align: center;
      font-size: 12.5px;
      font-weight: 700;
    }

    /* Bottom Buttons Bar */
    .bottom-buttons-bar {
      display: flex;
      justify-content: flex-end;
      gap: 14px;
      margin-top: 10px;
    }
    .btn-cancel-pay {
      background: #ffffff;
      border: 1.5px solid #cbd5e1;
      color: #475569;
      padding: 12px 24px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .btn-cancel-pay:hover {
      background: #f8fafc;
      color: #0f172a;
      border-color: #94a3b8;
    }
    .btn-already-paid {
      background: #244173;
      border: none;
      color: #ffffff;
      padding: 12px 28px;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      transition: background 0.15s ease;
    }
    .btn-already-paid:hover {
      background: #1b3259;
    }

    @media (max-width: 820px) {
      .two-columns-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <!-- Top Dark Bar -->
  <header class="top-dark-bar">
    <a href="{{ route('main.dashboard') }}" class="brand-logo-text">Maybead.s</a>
    <div class="order-status-badge">
      <span class="status-dot-blink"></span>
      <span>Menunggu Transfer (#{{ $order['id'] }})</span>
    </div>
  </header>

  <!-- Main Container -->
  <main class="payment-layout-container">
    <div class="page-title-row">
      <div class="title-left">
        <h1>Instruksi Pembayaran Transfer & E-Wallet</h1>
        <p>Silakan lakukan transfer sesuai total tagihan sebelum batas waktu berakhir.</p>
      </div>
    </div>

    <div class="two-columns-grid">

      <!-- Left Column: Daftar Rekening & E-Wallet -->
      <div class="payment-box-card">
        <h2 class="card-title-head">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          Rekening Pembayaran
        </h2>

        <!-- Bank BCA -->
        <div class="bank-acc-item">
          <div class="bank-header-row">
            <span class="bank-pill">BCA</span>
            <span class="bank-owner">a.n. PT Maybeads Aksesoris</span>
          </div>
          <div class="acc-number-row">
            <span class="acc-digits" id="accBca">894012938471</span>
            <button type="button" class="btn-copy-acc" onclick="copyAcc('accBca', 'Nomor Rekening BCA')">Salin Rekening</button>
          </div>
        </div>

        <!-- Bank Mandiri -->
        <div class="bank-acc-item">
          <div class="bank-header-row">
            <span class="bank-pill" style="background:#0284c7;">MANDIRI</span>
            <span class="bank-owner">a.n. PT Maybeads Aksesoris</span>
          </div>
          <div class="acc-number-row">
            <span class="acc-digits" id="accMandiri">1370019283741</span>
            <button type="button" class="btn-copy-acc" onclick="copyAcc('accMandiri', 'Nomor Rekening Mandiri')">Salin Rekening</button>
          </div>
        </div>

        <!-- E-Wallet GoPay / OVO -->
        <div class="bank-acc-item">
          <div class="bank-header-row">
            <span class="bank-pill" style="background:#059669;">E-WALLET (GoPay / ShopeePay)</span>
            <span class="bank-owner">a.n. Maybeads Official</span>
          </div>
          <div class="acc-number-row">
            <span class="acc-digits" id="accWallet">081234567890</span>
            <button type="button" class="btn-copy-acc" onclick="copyAcc('accWallet', 'Nomor E-Wallet')">Salin Nomor</button>
          </div>
        </div>

        <div style="font-size:12px; color:#64748b; line-height:1.5; margin-top:14px; background:#f8fafc; padding:12px; border-radius:8px;">
          💡 <strong>Petunjuk:</strong> Masukkan nomor pesanan <code>#{{ $order['id'] }}</code> pada berita transfer jika memungkinkan untuk memudahkan verifikasi otomatis.
        </div>
      </div>

      <!-- Right Column: Ringkasan Tagihan & Timer -->
      <div class="payment-box-card">
        <h2 class="summary-heading">Ringkasan Pembayaran</h2>

        <div class="summary-row-line">
          <span>Nomor Pesanan</span>
          <span class="amount-val">#{{ $order['id'] }}</span>
        </div>

        <div class="summary-row-line">
          <span>Metode Pembayaran</span>
          <span class="amount-val" style="color: #2563eb;">Transfer Bank / E-Wallet</span>
        </div>

        <div class="summary-row-line">
          <span>Subtotal Tagihan</span>
          <span class="amount-val">Rp {{ number_format($order['amount'], 0, ',', '.') }}</span>
        </div>

        <div class="summary-divider"></div>

        <div class="final-row-line">
          <span class="final-label">Total Tagihan</span>
          <span class="final-num">Rp {{ number_format($order['amount'], 0, ',', '.') }}</span>
        </div>

        <!-- Timer Box (10 Menit) -->
        <div class="timer-gray-card">
          <div class="timer-caption">Selesaikan Pembayaran Dalam:</div>
          <div class="timer-digits" id="liveTimer">10:00</div>
          <div class="timer-footer">Pesanan otomatis dibatalkan jika tidak dibayar dalam 10 menit</div>
        </div>

        <!-- Status Strip -->
        <div class="status-strip-banner" id="statusStrip">
          Menunggu Konfirmasi Pembayaran
        </div>
      </div>

    </div>

    <!-- Bottom Buttons -->
    <div class="bottom-buttons-bar">
      <button type="button" onclick="manualCancel()" class="btn-cancel-pay">Batalkan Pembayaran</button>
      <a href="{{ route('payment.success', $order['id']) }}" class="btn-already-paid">Saya Sudah Bayar</a>
    </div>
  </main>

  <script>
    let remainingSeconds = 600;
    const timerEl = document.getElementById('liveTimer');
    const statusStrip = document.getElementById('statusStrip');

    function updateTimer() {
      if (remainingSeconds <= 0) {
        timerEl.textContent = '00:00';
        timerEl.style.color = '#dc2626';
        if (statusStrip) {
          statusStrip.textContent = 'Waktu Habis — Pesanan Otomatis Dibatalkan';
          statusStrip.style.background = '#fef2f2';
          statusStrip.style.color = '#991b1b';
        }
        return;
      }
      const m = String(Math.floor(remainingSeconds / 60)).padStart(2, '0');
      const s = String(remainingSeconds % 60).padStart(2, '0');
      timerEl.textContent = `${m}:${s}`;
    }

    const timerInterval = setInterval(() => {
      if (remainingSeconds > 0) {
        remainingSeconds--;
        updateTimer();
      } else {
        clearInterval(timerInterval);
      }
    }, 1000);
    updateTimer();

    // Copy to clipboard helper
    function copyAcc(elementId, label) {
      const el = document.getElementById(elementId);
      if (!el) return;
      const text = el.textContent.trim();
      navigator.clipboard.writeText(text).then(() => {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: `${label} berhasil disalin!`,
            showConfirmButton: false,
            timer: 2000
          });
        } else {
          alert(`${label} berhasil disalin: ${text}`);
        }
      });
    }

    // Manual cancellation with pop-up confirmation
    function manualCancel() {
      const doCancelAndRedirect = () => {
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
  </script>
</body>
</html>

