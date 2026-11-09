<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pesanan — Maybeads</title>
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
      background-color: #ffffff;
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
      padding: 30px 40px 35px;
      background: linear-gradient(135deg, #1e293b 0%, #293b59 50%, #355389 100%);
      overflow: hidden;
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

    .hero-content-box {
      position: relative;
      z-index: 2;
      max-width: 800px;
    }

    .hero-title-text {
      font-size: 26px;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 6px;
    }

    .hero-subtitle-text {
      font-size: 12px;
      color: #cbd5e1;
      line-height: 1.5;
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
    .order-detail-main {
      padding: 28px 40px 60px;
      flex: 1;
    }

    .breadcrumb-text {
      font-size: 11px;
      color: #94a3b8;
      margin-bottom: 4px;
    }

    .order-title-big {
      font-size: 18px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 30px;
    }

    .order-number-row {
      display: flex;
      align-items: baseline;
      gap: 8px;
      font-size: 12px;
      color: #64748b;
    }

    .order-number-bold {
      font-size: 15px;
      font-weight: 800;
      color: #141418;
    }

    .btn-copy-code {
      font-size: 11px;
      color: #3b82f6;
      text-decoration: none;
      cursor: pointer;
      margin-left: 4px;
    }

    .btn-copy-code:hover {
      text-decoration: underline;
    }

    @media (max-width: 800px) {
      .top-dark-bar, .sub-nav-bar, .hero-banner-wrap, .order-detail-main {
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

  <!-- Hero Banner -->
  <div class="hero-banner-wrap">
    <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Banner" class="hero-banner-bg">
    <div class="hero-content-box">
      <h1 class="hero-title-text">Temukan Gaya Favoritmu</h1>
      <p class="hero-subtitle-text">Jelajahi koleksi pilihan Maybeads dan temukan berbagai aksesori serta produk unik yang melengkapi gayamu.</p>
    </div>
  </div>

  <!-- Sub Navbar -->
  <div class="sub-nav-bar">
    <div class="sub-nav-brand">
      <span>Maybaed.s</span>
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
  <main class="order-detail-main">
    <div class="breadcrumb-text">Dashboard &gt; Pesanan</div>
    <h2 class="order-title-big">Detail Pesanan</h2>

    <div class="order-number-row">
      <span>No. pesanan</span>
      <span class="order-number-bold">MB-20261103-0042</span>
      <a href="#" class="btn-copy-code" onclick="navigator.clipboard.writeText('MB-20261103-0042'); alert('No. pesanan disalin!'); return false;">salin</a>
    </div>
  </main>

</body>
</html>

