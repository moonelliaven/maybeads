@php
  use App\Models\Category;
  use App\Models\Product;

  $fallbacks = [
      'prod-hairclip.jpg',
      'prod-ring.jpg',
      'prod-butterfly.jpg',
      'prod-keychain.jpg',
      'hero-main.jpg',
      'hero-cross.jpg',
      'hero-butterfly.jpg',
      'keychain-01.jpg',
  ];

  $mainImg = null;
  if ($product->image && file_exists(storage_path('app/public/product/' . $product->image))) {
      $mainImg = asset('storage/product/' . $product->image);
  } elseif ($product->image && file_exists(public_path('images/products/' . $product->image))) {
      $mainImg = asset('images/products/' . $product->image);
  } elseif ($product->image && file_exists(public_path('images/landing/' . $product->image))) {
      $mainImg = asset('images/landing/' . $product->image);
  } else {
      $mainImg = asset('images/landing/' . $fallbacks[$product->id % count($fallbacks)]);
  }

  $prodName = $product->product_name ?: 'Set Kalung dan Anting Pink';
  $rawPrice = (float) $product->price < 1000 ? (float) $product->price * 1000 : (float) $product->price;
  if ($rawPrice <= 0) $rawPrice = 89000;
  $prodPrice = 'Rp ' . number_format($rawPrice, 0, ',', '.');
  $categoryName = $product->category ? $product->category->category_name : 'Aksesoris';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $prodName }} — Maybeads</title>
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

    /* Hero Banner Motif */
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

    /* Container */
    .detail-container {
      max-width: 1120px;
      width: 100%;
      margin: 24px auto 60px;
      padding: 0 24px;
      flex: 1;
    }

    .breadcrumb-text {
      font-size: 11px;
      color: #94a3b8;
      margin-bottom: 4px;
    }

    .page-title-main {
      font-size: 18px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 24px;
    }

    /* Card 1: Main Product Box */
    .product-main-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 36px 40px;
      display: grid;
      grid-template-columns: 0.95fr 1.15fr;
      gap: 44px;
      margin-bottom: 24px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .gallery-wrap {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .gallery-main-img-box {
      width: 100%;
      aspect-ratio: 1 / 1;
      border-radius: 8px;
      overflow: hidden;
      background: #000000;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .gallery-main-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .gallery-thumbs-row {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 8px;
    }

    .gallery-thumb-item {
      aspect-ratio: 1 / 1;
      border-radius: 4px;
      overflow: hidden;
      background: #000000;
      border: 1.5px solid transparent;
      cursor: pointer;
      opacity: 0.75;
      transition: all 0.15s;
    }

    .gallery-thumb-item.active,
    .gallery-thumb-item:hover {
      border-color: #355389;
      opacity: 1;
    }

    .gallery-thumb-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Info Right */
    .info-wrap {
      display: flex;
      flex-direction: column;
    }

    .info-cat-label {
      font-size: 11px;
      color: #94a3b8;
      margin-bottom: 4px;
    }

    .info-title-text {
      font-size: 20px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 12px;
    }

    .info-price-text {
      font-size: 22px;
      font-weight: 800;
      color: #2b4570;
      margin-bottom: 14px;
    }

    .info-desc-paragraph {
      font-size: 11.5px;
      line-height: 1.6;
      color: #64748b;
      margin-bottom: 20px;
      max-width: 480px;
    }

    .section-label-bold {
      font-size: 11.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 8px;
    }

    .pills-row {
      display: flex;
      gap: 8px;
      margin-bottom: 16px;
      flex-wrap: wrap;
    }

    .pill-btn {
      padding: 6px 14px;
      border-radius: 6px;
      border: 1px solid #dcdfe4;
      background: #ffffff;
      color: #334155;
      font-size: 11.5px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.15s;
    }

    .pill-btn:hover {
      border-color: #94a3b8;
    }

    .pill-btn.active {
      background: #2b4570;
      color: #ffffff;
      border-color: #2b4570;
    }

    /* Actions Row */
    .detail-actions-bar {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 6px;
      margin-bottom: 14px;
      flex-wrap: wrap;
    }

    .stepper-box {
      display: inline-flex;
      align-items: center;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 2px 6px;
      gap: 10px;
    }

    .stepper-btn {
      background: none;
      border: none;
      font-size: 14px;
      font-weight: 700;
      color: #475569;
      cursor: pointer;
      width: 20px;
      height: 24px;
    }

    .stepper-val {
      font-size: 12px;
      font-weight: 700;
      min-width: 16px;
      text-align: center;
    }

    .btn-add-cart-outline {
      padding: 8px 16px;
      border-radius: 6px;
      border: 1px solid #2b4570;
      background: #ffffff;
      color: #2b4570;
      font-size: 11.5px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.15s;
    }

    .btn-add-cart-outline:hover {
      background: #f0f4fa;
    }

    .btn-buy-now-solid {
      padding: 8px 18px;
      border-radius: 6px;
      border: 1px solid #2b4570;
      background: #2b4570;
      color: #ffffff;
      font-size: 11.5px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s;
    }

    .btn-buy-now-solid:hover {
      background: #1e3355;
    }

    .stock-available-text {
      font-size: 11px;
      color: #64748b;
    }

    /* Card 2: Spesifikasi Produk */
    .section-card-box {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 28px 36px;
      margin-bottom: 24px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .section-card-title {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 18px;
    }

    .spec-table-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      font-size: 11.5px;
      color: #475569;
      max-width: 280px;
    }

    .spec-table-row span:last-child {
      color: #141418;
    }

    /* Card 3: Ulasan */
    .review-placeholder-text {
      font-size: 11.5px;
      color: #64748b;
    }

    /* Card 4: Produk Terkait */
    .related-title {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 18px;
    }

    .related-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
    }

    .rel-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      padding: 12px;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    .rel-badge {
      position: absolute;
      top: 18px;
      left: 18px;
      z-index: 2;
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      padding: 2px 6px;
      font-size: 8px;
      font-weight: 700;
      color: #475569;
      text-transform: uppercase;
    }

    .rel-img-box {
      width: 100%;
      aspect-ratio: 1 / 1;
      border-radius: 8px;
      overflow: hidden;
      background: linear-gradient(180deg, #d8dee8 0%, #b8c4d4 100%);
      margin-bottom: 10px;
    }

    .rel-cat-text {
      font-size: 9.5px;
      color: #94a3b8;
      margin-bottom: 2px;
    }

    .rel-name-text {
      font-size: 11.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 8px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .rel-price-row {
      display: flex;
      align-items: baseline;
      gap: 6px;
      margin-bottom: 12px;
    }

    .rel-price-bold {
      font-size: 13.5px;
      font-weight: 800;
      color: #141418;
    }

    .rel-price-strike {
      font-size: 9.5px;
      color: #94a3b8;
      text-decoration: line-through;
    }

    .rel-actions-row {
      display: flex;
      gap: 8px;
      margin-top: auto;
    }

    .btn-rel-detail {
      flex: 1;
      padding: 5px 0;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #141418;
      font-size: 10.5px;
      font-weight: 600;
      text-align: center;
      text-decoration: none;
    }

    .btn-rel-cart {
      padding: 5px 10px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #141418;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Footer */
    .site-dark-footer {
      background-color: #1a2232;
      color: #cbd5e1;
      padding: 48px 40px 24px;
      margin-top: auto;
    }

    .site-footer-columns {
      max-width: 1120px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
      gap: 32px;
      margin-bottom: 36px;
    }

    .footer-brand-title {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 10px;
    }

    .footer-brand-tagline {
      font-size: 11.5px;
      line-height: 1.6;
      color: #94a3b8;
    }

    .footer-section-title {
      font-size: 11.5px;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: 0.05em;
      margin-bottom: 12px;
      text-transform: uppercase;
    }

    .footer-links-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 8px;
      font-size: 11px;
    }

    .footer-links-list a {
      color: #94a3b8;
      text-decoration: none;
    }

    .footer-links-list a:hover {
      color: #ffffff;
    }

    .site-footer-bottom {
      max-width: 1120px;
      margin: 0 auto;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      padding-top: 18px;
      display: flex;
      justify-content: space-between;
      font-size: 10.5px;
      color: #64748b;
    }

    /* Modal Tambah Produk ke Keranjang (Persis Foto 8) */
    .quick-add-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      z-index: 999;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .quick-add-modal-backdrop.show {
      display: flex;
    }

    .quick-add-modal-card {
      background: #ffffff;
      border-radius: 12px;
      max-width: 500px;
      width: 100%;
      padding: 28px 32px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-head-title {
      font-size: 17px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 20px;
    }

    .modal-product-summary {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 24px;
    }

    .modal-prod-thumb {
      width: 70px;
      height: 70px;
      border-radius: 8px;
      background: #d4d4d8;
      object-fit: cover;
    }

    .modal-prod-name {
      font-size: 15px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 6px;
    }

    .modal-prod-price {
      font-size: 18px;
      font-weight: 800;
      color: #141418;
    }

    .modal-options-two-cols {
      display: grid;
      grid-template-columns: 1fr 1px 1.2fr;
      gap: 16px;
      align-items: center;
      margin-bottom: 20px;
    }

    .modal-v-divider {
      background: #e2e8f0;
      height: 100%;
      width: 1px;
    }

    .modal-col-title {
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 10px;
    }

    .modal-pills-wrap {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .modal-pill-btn {
      padding: 6px 14px;
      border-radius: 6px;
      border: 1px solid #e4e4e7;
      background: #f4f4f5;
      color: #18181b;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
    }

    .modal-pill-btn.active {
      background: #e4e4e7;
      border-color: #d4d4d8;
    }

    .modal-qty-block {
      margin-bottom: 24px;
    }

    .modal-qty-stepper {
      display: inline-flex;
      align-items: center;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 2px 8px;
      gap: 12px;
      margin-top: 6px;
    }

    .modal-total-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .modal-total-label {
      font-size: 16px;
      font-weight: 800;
      color: #141418;
    }

    .modal-total-val {
      font-size: 19px;
      font-weight: 800;
      color: #141418;
    }

    .modal-actions-buttons {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .btn-modal-cancel {
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #141418;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
    }

    .btn-modal-submit {
      padding: 10px;
      border-radius: 8px;
      border: none;
      background: #374f7c;
      color: #ffffff;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
    }

    .btn-modal-submit:hover {
      background: #273b61;
    }

    @media (max-width: 860px) {
      .product-main-card {
        grid-template-columns: 1fr;
      }
      .related-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .site-footer-columns {
        grid-template-columns: 1fr;
      }
      .top-dark-bar, .sub-nav-bar, .hero-banner-wrap {
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
    <div class="hero-content-box">
      <h1 class="hero-title-text">Temukan Gaya Favoritmu</h1>
      <p class="hero-subtitle-text">Jelajahi koleksi pilihan Maybeads dan temukan berbagai aksesori serta produk unik yang melengkapi gayamu.</p>
    </div>
  </div>

  <!-- Sub Navbar -->
  <div class="sub-nav-bar">
    <div class="sub-nav-brand">
      <span>Maybead.s</span>
      <span class="sub-nav-crumb">Detail Produk</span>
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

  <!-- Container -->
  <main class="detail-container">
    <div class="breadcrumb-text">Dashboard &gt; Produk</div>
    <h2 class="page-title-main">Detail Produk</h2>

    <!-- Card 1: Main Product Box -->
    <div class="product-main-card">
      
      <!-- Gallery Left -->
      <div class="gallery-wrap">
        <div class="gallery-main-img-box">
          <img src="{{ $mainImg }}" alt="{{ $prodName }}" id="mainProductPhoto" class="gallery-main-img">
        </div>
        <div class="gallery-thumbs-row">
          <div class="gallery-thumb-item active" data-src="{{ $mainImg }}">
            <img src="{{ $mainImg }}" alt="Thumb 1">
          </div>
          <div class="gallery-thumb-item" data-src="{{ asset('images/landing/prod-butterfly.jpg') }}">
            <img src="{{ asset('images/landing/prod-butterfly.jpg') }}" alt="Thumb 2">
          </div>
          <div class="gallery-thumb-item" data-src="{{ asset('images/landing/prod-ring.jpg') }}">
            <img src="{{ asset('images/landing/prod-ring.jpg') }}" alt="Thumb 3">
          </div>
          <div class="gallery-thumb-item" data-src="{{ asset('images/landing/prod-hairclip.jpg') }}">
            <img src="{{ asset('images/landing/prod-hairclip.jpg') }}" alt="Thumb 4">
          </div>
          <div class="gallery-thumb-item" data-src="{{ asset('images/landing/prod-keychain.jpg') }}">
            <img src="{{ asset('images/landing/prod-keychain.jpg') }}" alt="Thumb 5">
          </div>
        </div>
      </div>

      <!-- Info Right -->
      <div class="info-wrap">
        <span class="info-cat-label">{{ $categoryName }}</span>
        <h1 class="info-title-text">{{ $prodName }}</h1>
        <div class="info-price-text">{{ $prodPrice }}</div>
        <p class="info-desc-paragraph">
          {{ $product->description ?: 'Set perhiasan elegan dengan hiasan kristal berwarna pink yang berkilau. Cocok dipakai ke pesta, acara formal, maupun harian.' }}
        </p>

        <!-- Warna -->
        <div class="section-label-bold">Warna</div>
        <div class="pills-row" id="colorPills">
          <button type="button" class="pill-btn active">Pink</button>
          <button type="button" class="pill-btn">Ungu</button>
          <button type="button" class="pill-btn">Putih</button>
        </div>

        <!-- Pilihan Jenis -->
        <div class="section-label-bold">Pilihan Jenis:</div>
        <div class="pills-row" id="typePills">
          <button type="button" class="pill-btn active">Kalung + Anting</button>
          <button type="button" class="pill-btn">Kalung</button>
          <button type="button" class="pill-btn">Anting</button>
        </div>

        <!-- Actions -->
        <div class="detail-actions-bar">
          <div class="stepper-box">
            <button type="button" class="stepper-btn" id="btnMinus">-</button>
            <span class="stepper-val" id="qtyVal">1</span>
            <button type="button" class="stepper-btn" id="btnPlus">+</button>
          </div>

          <button type="button" class="btn-add-cart-outline" id="btnOpenAddModal">
            Tambahkan ke keranjang
          </button>

          <a href="{{ route('checkout') }}" class="btn-buy-now-solid" id="btnDirectBuy">
            Pesan Sekarang
          </a>
        </div>

        <div class="stock-available-text">Stok Tersedia : {{ $product->stock ?? 30 }}</div>
      </div>

    </div>

    <!-- Card 2: Spesifikasi Produk -->
    <div class="section-card-box">
      <h3 class="section-card-title">Spesifikasi Produk</h3>
      <div class="spec-table-row">
        <span>Deskripsi</span>
        <span>Kode Produk</span>
      </div>
      <div class="spec-table-row">
        <span>Deskripsi</span>
        <span>Kode Produk</span>
      </div>
      <div class="spec-table-row">
        <span>Deskripsi</span>
        <span>Kode Produk</span>
      </div>
      <div class="spec-table-row">
        <span>Deskripsi</span>
        <span>Kode Produk</span>
      </div>
      <div class="spec-table-row">
        <span>Deskripsi</span>
        <span>Kode Produk</span>
      </div>
    </div>

    <!-- Card 3: Ulasan dan Testimoni Pembeli -->
    <div class="section-card-box">
      <h3 class="section-card-title">Ulasan dan Testimoni Pembeli</h3>
      <p class="review-placeholder-text">Ulasan dan Testimoni Pembeli</p>
    </div>

    <!-- Card 4: Produk Terkait Lainnya -->
    <div style="margin-top: 32px;">
      <h3 class="related-title">Produk Terkait Lainnya</h3>
      <div class="related-grid">
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
          @foreach($relatedProducts as $rIdx => $rProduct)
            @php
              $rRawPrice = (float)$rProduct->price < 1000 ? (float)$rProduct->price * 1000 : (float)$rProduct->price;
              if ($rRawPrice <= 0) $rRawPrice = 18500;
              $rPriceFmt = number_format($rRawPrice, 0, ',', '.');
              $rStrikeFmt = number_format($rRawPrice * 1.15, 0, ',', '.');
              $rImg = asset('images/landing/' . $fallbacks[($rIdx + 2) % count($fallbacks)]);
              if ($rProduct->image && file_exists(storage_path('app/public/product/' . $rProduct->image))) {
                  $rImg = asset('storage/product/' . $rProduct->image);
              } elseif ($rProduct->image && file_exists(public_path('images/products/' . $rProduct->image))) {
                  $rImg = asset('images/products/' . $rProduct->image);
              } elseif ($rProduct->image && file_exists(public_path('images/landing/' . $rProduct->image))) {
                  $rImg = asset('images/landing/' . $rProduct->image);
              }
            @endphp
            <div class="rel-card">
              <span class="rel-badge">BEST SELLER #{{ str_pad($rIdx + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <div class="rel-img-box">
                <img src="{{ $rImg }}" alt="{{ $rProduct->product_name }}" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div class="rel-cat-text">{{ $rProduct->category ? $rProduct->category->category_name : 'Kategori' }}</div>
              <div class="rel-name-text">{{ $rProduct->product_name }}</div>
              <div class="rel-price-row">
                <span class="rel-price-bold">Rp{{ $rPriceFmt }}</span>
                <span class="rel-price-strike">Rp {{ $rStrikeFmt }}</span>
              </div>
              <div class="rel-actions-row">
                <a href="{{ route('product.detail', $rProduct->id) }}" class="btn-rel-detail">Detail</a>
                <button type="button" class="btn-rel-cart" onclick="openQuickModal('{{ addslashes($rProduct->product_name) }}', '{{ $rPriceFmt }}', {{ $rRawPrice }})">
                  <svg viewBox="0 0 24 24" style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </button>
              </div>
            </div>
          @endforeach
        @else
          @for($i = 1; $i <= 4; $i++)
            <div class="rel-card">
              <span class="rel-badge">BEST SELLER #0{{ $i }}</span>
              <div class="rel-img-box"></div>
              <div class="rel-cat-text">Kategori</div>
              <div class="rel-name-text">Nama Produk Yang Keren</div>
              <div class="rel-price-row">
                <span class="rel-price-bold">Rp18,500</span>
                <span class="rel-price-strike">Rp 20.000</span>
              </div>
              <div class="rel-actions-row">
                <a href="{{ route('product.detail', $product->id) }}" class="btn-rel-detail">Detail</a>
                <button type="button" class="btn-rel-cart" onclick="openQuickModal('Nama Produk Yang Keren', '18.500', 18500)">
                  <svg viewBox="0 0 24 24" style="width:12px;height:12px;fill:none;stroke:currentColor;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </button>
              </div>
            </div>
          @endfor
        @endif
      </div>
    </div>

  </main>

  <!-- Modal Tambah Produk ke Keranjang (Persis Foto 8) -->
  <div class="quick-add-modal-backdrop" id="quickAddModal">
    <div class="quick-add-modal-card">
      <h3 class="modal-head-title">Tambah Produk ke Keranjang</h3>

      <div class="modal-product-summary">
        <img src="{{ $mainImg }}" alt="Product" class="modal-prod-thumb" id="modalThumbImg">
        <div>
          <div class="modal-prod-name" id="modalProdTitle">{{ $prodName }}</div>
          <div class="modal-prod-price" id="modalProdPrice">{{ $prodPrice }}</div>
        </div>
      </div>

      <!-- Dua Kolom Varian: Jenis & Warna -->
      <div class="modal-options-two-cols">
        <div>
          <div class="modal-col-title">Jenis</div>
          <div class="modal-pills-wrap">
            <button type="button" class="modal-pill-btn active">XL</button>
            <button type="button" class="modal-pill-btn">L</button>
            <button type="button" class="modal-pill-btn">S</button>
          </div>
        </div>

        <div class="modal-v-divider"></div>

        <div>
          <div class="modal-col-title">Warna</div>
          <div class="modal-pills-wrap">
            <button type="button" class="modal-pill-btn active">Merah</button>
            <button type="button" class="modal-pill-btn">Biru</button>
            <button type="button" class="modal-pill-btn">Hijau</button>
          </div>
        </div>
      </div>

      <!-- Jumlah Stepper -->
      <div class="modal-qty-block">
        <div class="modal-col-title">Jumlah</div>
        <div class="modal-qty-stepper">
          <button type="button" class="stepper-btn" id="modalMinus">-</button>
          <span class="stepper-val" id="modalQtyVal">1</span>
          <button type="button" class="stepper-btn" id="modalPlus">+</button>
        </div>
      </div>

      <!-- Total -->
      <div class="modal-total-bar">
        <span class="modal-total-label">Total</span>
        <span class="modal-total-val" id="modalTotalVal">125.000</span>
      </div>

      <!-- Modal Action Buttons -->
      <div class="modal-actions-buttons">
        <button type="button" class="btn-modal-cancel" id="btnModalCancel">Batal</button>
        <button type="button" class="btn-modal-submit" id="btnModalSubmit">Tambahkan</button>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="site-dark-footer">
    <div class="site-footer-columns">
      <div>
        <h3 class="footer-brand-title">Maybeads</h3>
        <p class="footer-brand-tagline">Menyajikan busana nostalgia tahun 2000-an yang otentik dengan sentuhan streetwear modern dan energi futuristik.</p>
      </div>
      <div>
        <h4 class="footer-section-title">TAUTAN</h4>
        <ul class="footer-links-list">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('main.dashboard') }}">Produk</a></li>
          <li><a href="{{ route('contact') }}">Kontak</a></li>
        </ul>
      </div>
      <div>
        <h4 class="footer-section-title">KEBIJAKAN</h4>
        <ul class="footer-links-list">
          <li><a href="#">Kebijakan Privasi</a></li>
          <li><a href="#">Ketentuan Layanan</a></li>
          <li><a href="#">Pengembalian &amp; Penukaran</a></li>
          <li><a href="#">Informasi Pengiriman</a></li>
        </ul>
      </div>
      <div>
        <h4 class="footer-section-title">SOSIAL MEDIA</h4>
        <ul class="footer-links-list">
          <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
          <li><a href="https://tiktok.com" target="_blank">Tiktok</a></li>
        </ul>
      </div>
      <div>
        <h4 class="footer-section-title">PEMBAYARAN</h4>
        <ul class="footer-links-list">
          <li><span>QRIS</span></li>
          <li><span>Gopay</span></li>
        </ul>
      </div>
    </div>
    <div class="site-footer-bottom">
      <div>Built for digital cyber-nomads.</div>
      <div>&copy; 2024 Maybeads. All rights reserved.</div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Gallery Thumbs
      const thumbs = document.querySelectorAll('.gallery-thumb-item');
      const mainPhoto = document.getElementById('mainProductPhoto');
      thumbs.forEach(t => {
        t.addEventListener('click', () => {
          thumbs.forEach(x => x.classList.remove('active'));
          t.classList.add('active');
          if (mainPhoto) mainPhoto.src = t.dataset.src;
        });
      });

      // Pills Toggle
      document.querySelectorAll('#colorPills .pill-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          document.querySelectorAll('#colorPills .pill-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
        });
      });

      document.querySelectorAll('#typePills .pill-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          document.querySelectorAll('#typePills .pill-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
        });
      });

      // Detail Stepper
      let count = 1;
      const qtyEl = document.getElementById('qtyVal');
      document.getElementById('btnMinus').addEventListener('click', () => {
        if (count > 1) { count--; qtyEl.textContent = count; }
      });
      document.getElementById('btnPlus').addEventListener('click', () => {
        count++; qtyEl.textContent = count;
      });

      // Quick Add Modal (Foto 8)
      const modal = document.getElementById('quickAddModal');
      const btnOpenAdd = document.getElementById('btnOpenAddModal');
      const btnCancel = document.getElementById('btnModalCancel');
      const btnSubmit = document.getElementById('btnModalSubmit');

      window.openQuickModal = function(name, displayPrice, rawPrice) {
        document.getElementById('modalProdTitle').textContent = name;
        document.getElementById('modalProdPrice').textContent = 'Rp ' + displayPrice;
        document.getElementById('modalTotalVal').textContent = (rawPrice || 125000).toLocaleString('id-ID');
        modal.classList.add('show');
      };

      if (btnOpenAdd) {
        btnOpenAdd.addEventListener('click', () => {
          openQuickModal("{{ addslashes($prodName) }}", "{{ $prodPrice }}", {{ (int)($product->price ?: 89000) }});
        });
      }

      if (btnCancel) {
        btnCancel.addEventListener('click', () => modal.classList.remove('show'));
      }

      // Modal Stepper
      let mQty = 1;
      const mQtyEl = document.getElementById('modalQtyVal');
      const mTotalEl = document.getElementById('modalTotalVal');
      const baseUnit = 125000;

      document.getElementById('modalMinus').addEventListener('click', () => {
        if (mQty > 1) {
          mQty--;
          mQtyEl.textContent = mQty;
          mTotalEl.textContent = (baseUnit * mQty).toLocaleString('id-ID');
        }
      });
      document.getElementById('modalPlus').addEventListener('click', () => {
        mQty++;
        mQtyEl.textContent = mQty;
        mTotalEl.textContent = (baseUnit * mQty).toLocaleString('id-ID');
      });

      if (btnSubmit) {
        btnSubmit.addEventListener('click', () => {
          // Simpan ke cart
          try {
            const raw = localStorage.getItem('maybeads_cart');
            const cart = raw ? JSON.parse(raw) : [];
            cart.push({
              id: {{ $product->id }},
              name: "{{ addslashes($prodName) }}",
              color: "Pink, Standar",
              qty: mQty,
              price: "Rp 89.000",
              rawPrice: 89000,
              image: "{{ $mainImg }}",
              checked: true,
              addedAt: Date.now()
            });
            localStorage.setItem('maybeads_cart', JSON.stringify(cart));
          } catch(e) {}
          modal.classList.remove('show');
          alert('Produk berhasil ditambahkan ke keranjang!');
        });
      }
    });
  </script>
</body>
</html>
