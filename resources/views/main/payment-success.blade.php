<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bukti Pembayaran — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css'])

  <style>
    .invoice-container {
      max-width: 680px;
      margin: 36px auto 100px;
      padding: 0 24px;
    }

    .invoice-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 40px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
      position: relative;
    }

    .invoice-badge-status {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      background: #ecfdf5;
      color: #059669;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.04em;
      margin-bottom: 16px;
    }

    .invoice-header-title {
      font-size: 24px;
      font-weight: 900;
      color: #141418;
      margin-bottom: 8px;
    }

    .invoice-header-desc {
      font-size: 13.5px;
      color: #64748b;
      margin-bottom: 28px;
    }

    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
    }

    .invoice-table tr {
      border-bottom: 1px solid #f1f5f9;
    }

    .invoice-table td {
      padding: 12px 0;
      font-size: 13.5px;
    }

    .invoice-table td.label-col {
      color: #64748b;
      width: 40%;
    }

    .invoice-table td.val-col {
      color: #141418;
      font-weight: 600;
      text-align: right;
    }

    .invoice-total-box {
      background: #f8fafc;
      border-radius: 10px;
      padding: 18px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .invoice-total-label {
      font-size: 14px;
      font-weight: 700;
      color: #334155;
    }

    .invoice-total-val {
      font-size: 22px;
      font-weight: 900;
      color: #244173;
    }

    .invoice-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .btn-print {
      padding: 12px 20px;
      background: #ffffff;
      color: #244173;
      border: 1.5px solid #244173;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-shop-again {
      padding: 12px 20px;
      background: #244173;
      color: #ffffff;
      border: 1.5px solid #244173;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 700;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @media print {
      body * { visibility: hidden; }
      .invoice-card, .invoice-card * { visibility: visible; }
      .invoice-card { position: absolute; left: 0; top: 0; width: 100%; border: none; box-shadow: none; }
      .invoice-actions { display: none; }
    }
  </style>
</head>
<body class="dashboard-page">

  <!-- Top Bar -->
  <header class="top-nav-bar">
    <div class="top-nav-container">
      <div class="top-nav-left">
        <a href="{{ route('main.dashboard') }}" class="back-home-link">
          <svg class="chevron-back" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Kembali ke Katalog</span>
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
      <p class="hero-main-desc">Terima kasih atas pesanan Anda! Pembayaran telah berhasil diproses.</p>
    </div>
  </section>

  <!-- Invoice Content -->
  <main class="invoice-container">
    <div class="invoice-card">
      <div class="invoice-badge-status">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:3;"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>PEMBAYARAN LUNAS</span>
      </div>

      <h1 class="invoice-header-title">Nota Bukti Transaksi</h1>
      <p class="invoice-header-desc">Pesanan Anda sedang dipersiapkan dan segera dikirimkan ke alamat Anda.</p>

      <table class="invoice-table">
        <tr>
          <td class="label-col">ID Pesanan</td>
          <td class="val-col">#MB-{{ $order['id'] ?? '8821' }}</td>
        </tr>
        <tr>
          <td class="label-col">Tanggal & Waktu</td>
          <td class="val-col">{{ $order['date'] ?? now()->format('d M Y, H:i') }} WIB</td>
        </tr>
        <tr>
          <td class="label-col">Metode Pembayaran</td>
          <td class="val-col">{{ strtoupper($order['payment_method'] ?? 'QRIS') }}</td>
        </tr>
        <tr>
          <td class="label-col">Nama Pembeli</td>
          <td class="val-col">{{ $order['name'] ?? (Auth::user()?->name ?? 'Zaki Srianto') }}</td>
        </tr>
        <tr>
          <td class="label-col">No. Telepon</td>
          <td class="val-col">{{ $order['phone'] ?? '081234567890' }}</td>
        </tr>
        <tr>
          <td class="label-col">Alamat Pengiriman</td>
          <td class="val-col">{{ $order['address'] ?? 'Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru' }}</td>
        </tr>
        <tr>
          <td class="label-col">Status Pengiriman</td>
          <td class="val-col" style="color: #2563eb;">Diproses / Dikemas</td>
        </tr>
      </table>

      <div class="invoice-total-box">
        <span class="invoice-total-label">Total Dibayar</span>
        <span class="invoice-total-val">Rp {{ number_format($order['amount'] ?? 98000, 0, ',', '.') }}</span>
      </div>

      <div class="invoice-actions">
        <button type="button" class="btn-print" onclick="window.print()">
          <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:none;stroke:currentColor;stroke-width:2;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
          <span>Cetak Nota</span>
        </button>

        <a href="{{ route('main.dashboard') }}" class="btn-shop-again">
          Belanja Lagi
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

</body>
</html>
