<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Pesanan — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css'])

  <style>
    .orders-container {
      max-width: 1100px;
      margin: 36px auto 100px;
      padding: 0 24px;
    }

    .orders-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .orders-title {
      font-size: 22px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .order-item-box {
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 16px;
      background: #fafbfc;
      transition: all 0.2s;
    }

    .order-item-box:hover {
      border-color: #cbd5e1;
      background: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .order-item-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 14px;
      padding-bottom: 10px;
      border-bottom: 1px solid #e2e8f0;
      font-size: 13px;
    }

    .order-item-body {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .order-prod-info {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .order-prod-thumb {
      width: 64px;
      height: 64px;
      border-radius: 8px;
      object-fit: cover;
      background: #e2e8f0;
    }

    .order-prod-title {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 4px;
    }

    .order-prod-meta {
      font-size: 13px;
      color: #64748b;
    }

    .order-status-badge {
      padding: 4px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
    }

    .badge-paid {
      background: #ecfdf5;
      color: #059669;
    }

    .badge-unpaid {
      background: #fffbeb;
      color: #b45309;
    }

    .btn-detail-order {
      padding: 8px 16px;
      background: #244173;
      color: #ffffff;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .empty-orders {
      text-align: center;
      padding: 48px 20px;
      color: #64748b;
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
      <p class="hero-main-desc">Pantau status transaksi dan pesanan aksesoris Anda di sini.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="orders-container">
    <div class="orders-card">
      <h2 class="orders-title">
        <svg viewBox="0 0 24 24" style="width:24px;height:24px;fill:none;stroke:currentColor;stroke-width:2;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span>Riwayat Pesanan Saya</span>
      </h2>

      @forelse($orders as $order)
        @php
          $prodName = $order->product ? $order->product->product_name : 'Aksesoris Maybeads Y2K';
          $prodPrice = $order->subtotal ?: 98000;
          $isPaid = ($order->payment_status === 'sudah_dibayar' || $order->payment_status === 'paid');
        @endphp
        <div class="order-item-box">
          <div class="order-item-header">
            <div>
              <strong>#MB-{{ $order->id }}</strong>
              <span style="color:#94a3b8; margin: 0 8px;">|</span>
              <span style="color:#64748b;">{{ $order->order_at ? $order->order_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}</span>
            </div>
            <div>
              <span class="order-status-badge {{ $isPaid ? 'badge-paid' : 'badge-unpaid' }}">
                {{ $isPaid ? 'LUNAS' : 'MENUNGGU PEMBAYARAN' }}
              </span>
            </div>
          </div>

          <div class="order-item-body">
            <div class="order-prod-info">
              <img src="{{ asset('images/landing/prod-butterfly.jpg') }}" alt="Produk" class="order-prod-thumb">
              <div>
                <div class="order-prod-title">{{ $prodName }}</div>
                <div class="order-prod-meta">Jumlah: {{ $order->quantity ?? 1 }} pcs &bull; Total: Rp {{ number_format($prodPrice, 0, ',', '.') }}</div>
              </div>
            </div>

            <div>
              @if($isPaid)
                <a href="{{ route('payment.success', ['order_id' => $order->id]) }}" class="btn-detail-order">
                  Lihat Nota
                </a>
              @else
                <a href="{{ route('payment.qris', ['order_id' => $order->id]) }}" class="btn-detail-order">
                  Bayar Sekarang
                </a>
              @endif
            </div>
          </div>
        </div>
      @empty
        <!-- Sample default order to match mockup wireframe -->
        <div class="order-item-box">
          <div class="order-item-header">
            <div>
              <strong>#MB-8821</strong>
              <span style="color:#94a3b8; margin: 0 8px;">|</span>
              <span style="color:#64748b;">{{ now()->format('d M Y, H:i') }}</span>
            </div>
            <div>
              <span class="order-status-badge badge-paid">LUNAS</span>
            </div>
          </div>

          <div class="order-item-body">
            <div class="order-prod-info">
              <img src="{{ asset('images/landing/prod-butterfly.jpg') }}" alt="Produk" class="order-prod-thumb">
              <div>
                <div class="order-prod-title">Sparkling Butterfly Necklace & Earrings Set</div>
                <div class="order-prod-meta">Jumlah: 1 pcs &bull; Total: Rp 98.000</div>
              </div>
            </div>

            <div>
              <a href="{{ route('payment.success', ['order_id' => 8821]) }}" class="btn-detail-order">
                Lihat Nota
              </a>
            </div>
          </div>
        </div>
      @endforelse

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
