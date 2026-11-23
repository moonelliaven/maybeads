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

  $resolveImg = function ($imgName, $fallbackIdx = 0) use ($fallbacks) {
      if ($imgName && file_exists(storage_path('app/public/product/' . $imgName))) {
          return asset('storage/product/' . $imgName);
      }
      if ($imgName && file_exists(public_path('images/products/' . $imgName))) {
          return asset('images/products/' . $imgName);
      }
      if ($imgName && file_exists(public_path('images/landing/' . $imgName))) {
          return asset('images/landing/' . $imgName);
      }
      return asset('images/landing/' . $fallbacks[$fallbackIdx % count($fallbacks)]);
  };

  $mainProductImg = $resolveImg($product->image, $product->id);
  $rawPrice = (float)$product->price < 1000 ? (float)$product->price * 1000 : (float)$product->price;
  if ($rawPrice <= 0) $rawPrice = 89000;
  $formattedPrice = 'Rp ' . number_format($rawPrice, 0, ',', '.');
  $productCode = 'IDP' . str_pad($product->id, 4, '0', STR_PAD_LEFT);
  $catName = $product->category ? $product->category->category_name : 'Aksesoris';
  $desc = $product->description ?: 'Set perhiasan elegan dengan hiasan kristal berkualitas tinggi. Cocok dipakai ke pesta, acara formal, maupun harian.';

  // Variants from database
  $colors = $product->variants ? $product->variants->pluck('color')->filter()->unique()->values() : collect();
  $sizes  = $product->variants ? $product->variants->pluck('size')->filter()->unique()->values() : collect();
  if ($colors->isEmpty()) {
      $colors = collect(['Pink', 'Ungu', 'Putih']);
  }
  if ($sizes->isEmpty()) {
      $sizes = collect(['Kalung + Anting', 'Kalung', 'Anting']);
  }
  $defaultVariantStr = $colors->first() . ' - ' . $sizes->first();

  // Gallery images: Foto utama, varian warna, dan foto sekunder milik produk ini
  $galleryImages = [];
  $galleryImages[] = $mainProductImg;

  if ($product->variants) {
      foreach ($product->variants as $v) {
          if (!empty($v->image)) {
              $vu = $resolveImg($v->image, count($galleryImages));
              if (!in_array($vu, $galleryImages)) {
                  $galleryImages[] = $vu;
              }
          }
      }
  }

  $secImages = is_string($product->secondary_images) ? json_decode($product->secondary_images, true) : $product->secondary_images;
  if (!empty($secImages) && is_array($secImages)) {
      foreach ($secImages as $idx => $sImg) {
          if (!empty($sImg)) {
              $u = $resolveImg($sImg, count($galleryImages));
              if (!in_array($u, $galleryImages)) {
                  $galleryImages[] = $u;
              }
          }
      }
  }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $product->product_name }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css', 'resources/css/main/product-detail.css', 'resources/js/main/product-detail.js'])
  <script>
    window.isUserLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
  </script>
</head>
<body class="product-detail-page">

  <!-- ========================================================================
       1. TOP BAR (Dark Slate Header - Maybead.s)
       ======================================================================== -->
  <header class="top-nav-bar">
    <div class="top-nav-container">
      <div class="top-nav-left">
        <a href="{{ route('home') }}" class="top-nav-brand-logo">
          Maybead.s
        </a>
      </div>

      <nav class="top-nav-right" aria-label="Navigasi Utama">
        <ul class="top-nav-menu-links">
          <li><a href="{{ route('home') }}" class="top-menu-link">BERANDA</a></li>
          <li><a href="{{ route('contact') }}" class="top-menu-link">KONTAK</a></li>
          <li><a href="{{ route('main.dashboard') }}" class="top-menu-link active">PRODUK</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- ========================================================================
       2. HERO BANNER SECTION (Blue Textured Backdrop)
       ======================================================================== -->
  <section class="catalog-hero-section">
    <div class="hero-bg-layer">
      <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Aksesoris Manik Maybeads" class="hero-bg-image" loading="eager">
      <div class="hero-gradient-overlay"></div>
    </div>
    <div class="hero-container">
      <h1 class="hero-main-title">Temukan Gaya Favoritmu</h1>
      <p class="hero-main-desc">
        Jelajahi koleksi pilihan Maybeads dan temukan berbagai aksesori serta<br>
        produk unik yang melengkapi gayamu.
      </p>
    </div>
  </section>

  <!-- ========================================================================
       3. SUB-NAVBAR (White Bar with Logo, Breadcrumb, Cart, and Account)
       ======================================================================== -->
  <nav class="sub-navbar-bar" id="subNavbar">
    <div class="sub-navbar-container">
      <!-- Left: Brand Logo + Breadcrumb -->
      <div class="sub-nav-left">
        <a href="{{ route('main.dashboard') }}" class="sub-nav-brand">
          Maybeads
        </a>
        <span class="sub-nav-crumb">/ Detail Produk</span>
      </div>

      <!-- Right: Keranjang and Akun buttons -->
      <div class="sub-nav-right">
        <!-- Keranjang Button -->
        @auth
          <a href="{{ route('cart.index') }}" class="action-pill-btn" id="cartOpenBtn" title="Lihat Keranjang">
            <svg class="pill-icon" viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Keranjang</span>
            <span class="cart-count-badge {{ ($cartCount ?? 0) > 0 ? 'visible' : '' }}" id="cartCountBadge">{{ $cartCount ?? 0 }}</span>
          </a>
        @else
          <button type="button" class="action-pill-btn btn-trigger-login-prompt" title="Lihat Keranjang">
            <svg class="pill-icon" viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Keranjang</span>
          </button>
        @endauth

        <!-- Akun Button with dropdown -->
        <div class="account-dropdown-wrapper">
          <button type="button" class="action-pill-btn" id="accountDropdownBtn" aria-expanded="false" title="Menu Akun">
            <svg class="pill-icon" viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Akun</span>
            <svg class="chevron-down" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </button>

          <div class="account-menu-popover" id="accountMenuPopover">
            @auth
              <div class="acc-popover-header">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
              </div>
              <div class="acc-popover-divider"></div>
              @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="acc-popover-item">
                  <svg viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/></svg>
                  Admin Dashboard
                </a>
              @endif
              <a href="{{ route('account.settings') }}" class="acc-popover-item">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Pengaturan Akun
              </a>
              <a href="{{ route('orders.history') }}" class="acc-popover-item">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Riwayat Pesanan
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                <button type="submit" class="acc-popover-item text-danger">
                  <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                  Keluar
                </button>
              </form>
            @else
              <div class="acc-popover-header">
                <strong>Akun Pengguna</strong>
                <small>Silakan masuk atau daftar</small>
              </div>
              <div class="acc-popover-divider"></div>
              <a href="{{ route('login') }}" class="acc-popover-item">Masuk ke Akun</a>
              <a href="{{ route('register') }}" class="acc-popover-item">Daftar Akun Baru</a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- ========================================================================
       4. MAIN PRODUCT DETAIL CONTENT
       ======================================================================== -->
  <main class="detail-main-wrapper">

    <!-- Top Return CTA & Breadcrumb Bar -->
    <div class="detail-back-bar">
      <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('main.dashboard') }}" class="btn-back-to-home">
          <svg viewBox="0 0 24 24" style="width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2.5;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          <span>Kembali ke Katalog</span>
        </a>
        <a href="{{ route('home') }}" class="btn-back-outline">
          <svg viewBox="0 0 24 24" style="width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          <span>Beranda</span>
        </a>
      </div>

      <nav class="breadcrumb-trail" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="sep">&rsaquo;</span>
        <a href="{{ route('main.dashboard') }}">Katalog Produk</a>
        <span class="sep">&rsaquo;</span>
        <span class="active-crumb">{{ $product->product_name }}</span>
      </nav>
    </div>

    <!-- CARD 1: Main Product Showcase Card -->
    <section class="product-showcase-card detail-card-box"
             id="productDetailStore"
             data-id="{{ $product->id }}"
             data-product-id="{{ $product->id }}"
             data-name="{{ $product->product_name }}"
             data-product-name="{{ $product->product_name }}"
             data-raw-price="{{ $rawPrice }}"
             data-product-price="{{ $rawPrice }}"
             data-formatted-price="{{ $formattedPrice }}"
             data-price-formatted="{{ $formattedPrice }}"
             data-image="{{ $mainProductImg }}"
             data-product-img="{{ $mainProductImg }}"
             data-default-variant="{{ $defaultVariantStr }}">

      <!-- Left: Image & 5 Thumbnails -->
      <div class="showcase-media-col gallery-column">
        <div class="main-img-box main-image-display-frame">
          <span class="media-code-badge">{{ $productCode }}</span>
          <img src="{{ $mainProductImg }}"
               id="mainProductImage"
               class="main-img-display"
               alt="{{ $product->product_name }}">
        </div>

        <div class="thumbnail-gallery-row thumbs-horizontal-row">
          @foreach($galleryImages as $tIdx => $tImg)
            <button type="button"
                    class="thumb-item-btn thumb-select-btn {{ $tIdx === 0 ? 'active' : '' }}"
                    data-src="{{ $tImg }}"
                    aria-label="Thumbnail {{ $tIdx + 1 }}">
              <img src="{{ $tImg }}" alt="{{ $product->product_name }} {{ $tIdx + 1 }}">
            </button>
          @endforeach
        </div>
      </div>

        <!-- Right: Product Info & Actions -->
        <div class="info-details-column">
          <div class="product-cat-name">{{ $catName }}</div>
          <h2 class="product-title-text">{{ $product->product_name }}</h2>
          <div class="product-price-highlight">{{ $formattedPrice }}</div>
          <p class="product-desc-paragraph">{{ $desc }}</p>

          <!-- Pilihan Warna -->
          <div class="variant-block">
            <div class="variant-title-caption">Warna</div>
            <div class="variant-buttons-row">
              @foreach($colors as $cIdx => $color)
                @php
                  $varObj = $product->variants ? $product->variants->firstWhere('color', $color) : null;
                  $varImgUrl = ($varObj && !empty($varObj->image)) ? $resolveImg($varObj->image, 0) : null;
                @endphp
                <button type="button"
                        class="variant-choice-pill pill-variant-btn {{ $cIdx === 0 ? 'selected' : '' }}"
                        data-variant-type="color"
                        data-variant-value="{{ $color }}"
                        @if($varImgUrl) data-variant-img="{{ $varImgUrl }}" @endif>
                  {{ $color }}
                </button>
              @endforeach
            </div>
          </div>

          <!-- Pilihan Jenis -->
          <div class="variant-block" style="margin-top: 14px;">
            <div class="variant-title-caption">Pilihan Jenis:</div>
            <div class="variant-buttons-row">
              @foreach($sizes as $sIdx => $size)
                <button type="button"
                        class="variant-choice-pill pill-variant-btn {{ $sIdx === 0 ? 'selected' : '' }}"
                        data-variant-type="size"
                        data-variant-value="{{ $size }}">
                  {{ $size }}
                </button>
              @endforeach
            </div>
          </div>

          <!-- Stepper Quantity & Estimasi Harga -->
          <div class="stepper-quantity-row">
            <div class="stepper-quantity-wrap">
              <button type="button" class="stepper-arrow-btn" id="btnQtyMinus" aria-label="Kurangi">&minus;</button>
              <input type="number"
                     id="productQtyInput"
                     class="stepper-number-val"
                     value="1"
                     min="1"
                     max="{{ max(1, (int)$product->stock) }}"
                     data-max-stock="{{ max(1, (int)$product->stock) }}"
                     data-unit-price="{{ (float)$product->price }}"
                     readonly>
              <button type="button" class="stepper-arrow-btn" id="btnQtyPlus" aria-label="Tambah">&plus;</button>
            </div>

            <!-- Estimasi Harga Berdasarkan Quantity -->
            <div class="qty-estimation-wrap">
              <span class="qty-estimation-label">Estimasi Harga:</span>
              <span class="qty-estimation-val" id="productEstimatedPrice">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
          </div>

          <!-- Tombol Tambahkan ke Keranjang (Kebawah) -->
          <div class="add-to-cart-action-row">
            @auth
              <button type="button"
                      class="btn-cart-primary-block"
                      id="btnAddToCartMain"
                      {{ $product->stock <= 0 ? 'disabled' : '' }}>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="cart-btn-icon"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <span>Tambahkan ke Keranjang</span>
              </button>
            @else
              <button type="button"
                      class="btn-cart-primary-block btn-trigger-login-prompt">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="cart-btn-icon"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <span>Tambahkan ke Keranjang</span>
              </button>
            @endauth
          </div>

          <!-- Stock note -->
          <div class="stock-counter-caption">
            Stok Tersedia : {{ $product->stock > 0 ? $product->stock : 30 }}
          </div>
        </div>

      </div>
    </section>

    <!-- CARD 2: Spesifikasi Produk -->
    <section class="detail-card-box">
      <h3 class="card-inner-heading">Spesifikasi Produk</h3>
      <div class="specs-keyvalue-list">
        <div class="spec-pair-item">
          <span class="spec-prop-key">Kategori</span>
          <span class="spec-prop-val">{{ $catName }}</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Kode Produk</span>
          <span class="spec-prop-val">{{ $productCode }}</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Nama Produk</span>
          <span class="spec-prop-val">{{ $product->product_name }}</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Kualitas &amp; Pengerjaan</span>
          <span class="spec-prop-val">100% Original Handmade Beads</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Stok Tersedia</span>
          <span class="spec-prop-val">{{ $product->stock > 0 ? $product->stock : 30 }} pcs</span>
        </div>
      </div>
    </section>

    <!-- CARD 3: Ulasan dan Testimoni Pembeli -->
    <section class="detail-card-box">
      <h3 class="card-inner-heading">Ulasan dan Testimoni Pembeli</h3>
      @if(isset($testimonials) && $testimonials->count() > 0)
        <div class="reviews-container-grid">
          @foreach($testimonials as $testi)
            <div class="review-single-card">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <div style="font-weight: 700; font-size: 13px; color: #141418;">{{ $testi->user->name ?? 'Pelanggan' }}</div>
                <div style="display: flex; gap: 2px;">
                  @for($star = 1; $star <= 5; $star++)
                    <svg viewBox="0 0 24 24" fill="{{ $star <= $testi->rating ? '#f59e0b' : '#cbd5e1' }}" style="width:12px;height:12px;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                  @endfor
                </div>
              </div>
              <p style="font-size: 12px; color: #475569; margin: 0; line-height: 1.5;">&ldquo;{{ $testi->message }}&rdquo;</p>
              <div style="font-size: 10px; color: #94a3b8; margin-top: 8px; text-align: right;">{{ $testi->created_at ? $testi->created_at->diffForHumans() : 'Baru saja' }}</div>
            </div>
          @endforeach
        </div>
      @else
        <div class="empty-review-caption">Ulasan dan Testimoni Pembeli</div>
      @endif
    </section>

    <!-- CARD 4: Produk Terkait Lainnya (Mengikuti format card katalog utama) -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
      <section class="related-section-wrap">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
          <h3 class="related-section-header" style="margin: 0; font-size: 18px; font-weight: 800; color: #141418;">Produk Terkait Lainnya</h3>
          <a href="{{ route('main.dashboard') }}" style="font-size: 13px; font-weight: 700; color: #2b4570; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
            <span>Lihat Semua Katalog</span>
            <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </a>
        </div>

        <div class="products-grid-wrapper">
          @foreach($relatedProducts->take(4) as $rIdx => $rProduct)
            @php
              $rRawPrice = (float)$rProduct->price < 1000 ? (float)$rProduct->price * 1000 : (float)$rProduct->price;
              if ($rRawPrice <= 0) $rRawPrice = 18500;
              $rPriceFmt = number_format($rRawPrice, 0, ',', '.');
              $rStrikeFmt = number_format($rRawPrice * 1.15, 0, ',', '.');
              $rImg = $resolveImg($rProduct->image, $rIdx + 1);
              $rProductCode = 'IDP' . str_pad($rProduct->id, 4, '0', STR_PAD_LEFT);
              $rCatName = $rProduct->category ? $rProduct->category->category_name : 'Aksesoris';
            @endphp
            <article class="product-card-item"
                     data-id="{{ $rProduct->id }}"
                     data-code="{{ $rProductCode }}"
                     data-name="{{ $rProduct->product_name }}"
                     data-category="{{ $rCatName }}"
                     data-price="{{ $rRawPrice }}"
                     data-formatted-price="Rp {{ $rPriceFmt }}"
                     data-image="{{ $rImg }}">

              <!-- Image with Top-Left Best Seller Badge -->
              <div class="card-media-wrap">
                <span class="card-badge-bestseller badge-top3">
                  <svg viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                  BEST SELLER #{{ str_pad($rIdx + 1, 2, '0', STR_PAD_LEFT) }}
                </span>
                <img src="{{ $rImg }}"
                     alt="{{ $rProduct->product_name }}"
                     class="card-product-img"
                     loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('images/landing/prod-keychain.jpg') }}'">
              </div>

              <!-- Card Body -->
              <div class="card-info-wrap">
                <div class="card-cat-label">{{ $rCatName }}</div>
                <h3 class="card-product-title" title="{{ $rProduct->product_name }}">
                  {{ $rProduct->product_name }}
                </h3>

                <!-- Price Row -->
                <div class="card-price-row">
                  <span class="card-currency">Rp</span>
                  <span class="card-price-val">{{ $rPriceFmt }}</span>
                  <span class="card-price-original">Rp {{ $rStrikeFmt }}</span>
                </div>

                <!-- Bottom Actions Row: Detail & Cart Modal -->
                <div class="card-actions-row">
                  <a href="{{ route('product.detail', $rProduct->id) }}" class="btn-card-detail">
                    Detail
                  </a>

                  @auth
                    <button type="button"
                            class="btn-card-cart-icon btn-related-add-cart"
                            data-id="{{ $rProduct->id }}"
                            title="Tambah ke Keranjang"
                            aria-label="Tambah {{ $rProduct->product_name }} ke keranjang">
                      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </button>
                  @else
                    <button type="button"
                            class="btn-card-cart-icon btn-trigger-login-prompt"
                            title="Tambah ke Keranjang"
                            aria-label="Tambah {{ $rProduct->product_name }} ke keranjang">
                      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </button>
                  @endauth
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </section>
    @endif

  </main>

  <!-- ========================================================================
       5. FOOTER (Dark Navy Footer)
       ======================================================================== -->
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

  @include('partials.login-required-modal')
  @include('partials.user-logout-modal')

</body>
</html>
