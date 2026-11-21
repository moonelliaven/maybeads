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

  // 5 Gallery images for thumbnail showcase matching design
  $galleryImages = [];
  $galleryImages[] = $mainProductImg;
  $secImages = is_string($product->secondary_images) ? json_decode($product->secondary_images, true) : $product->secondary_images;
  if (!empty($secImages) && is_array($secImages)) {
      foreach ($secImages as $idx => $sImg) {
          $galleryImages[] = $resolveImg($sImg, $idx + 1);
      }
  }
  $extraFallbacks = ['prod-ring.jpg', 'prod-butterfly.jpg', 'prod-keychain.jpg', 'prod-hairclip.jpg', 'hero-main.jpg', 'keychain-01.jpg'];
  foreach ($extraFallbacks as $fb) {
      if (count($galleryImages) >= 5) break;
      $u = $resolveImg($fb, count($galleryImages));
      if (!in_array($u, $galleryImages)) {
          $galleryImages[] = $u;
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
          Maybead.s
        </a>
        <span class="sub-nav-crumb" style="font-size: 11px; color: #94a3b8; font-weight: 500;">Detail Produk</span>
      </div>

      <!-- Right: Keranjang and Akun buttons -->
      <div class="sub-nav-right">
        @auth
          <a href="{{ route('cart.index') }}" class="sub-btn-pill">
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span>Keranjang</span>
            @if(($cartCount ?? 0) > 0)
              <span class="cart-count-badge" id="cartCountBadge" style="background:#2b4570; color:#fff; font-size:10px; font-weight:700; padding:1px 6px; border-radius:999px;">{{ $cartCount }}</span>
            @endif
          </a>
          <a href="{{ route('orders.history') }}" class="sub-btn-pill">
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Akun</span>
            <svg viewBox="0 0 24 24" style="width:10px;height:10px;stroke:currentColor;fill:none;stroke-width:2.5;"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>
        @else
          <button type="button" class="sub-btn-pill btn-trigger-login-prompt">
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span>Keranjang</span>
          </button>
          <a href="{{ route('login') }}" class="sub-btn-pill">
            <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Akun</span>
            <svg viewBox="0 0 24 24" style="width:10px;height:10px;stroke:currentColor;fill:none;stroke-width:2.5;"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>
        @endauth
      </div>
    </div>
  </nav>

  <!-- ========================================================================
       4. MAIN PRODUCT DETAIL CONTENT
       ======================================================================== -->
  <main class="detail-main-wrapper">

    <!-- Breadcrumb Heading -->
    <div class="detail-header-block">
      <div class="detail-crumb-line"><a href="{{ route('main.dashboard') }}" class="btn-back-link" style="color:inherit;text-decoration:none;">Dashboard</a> &gt; <a href="{{ route('main.dashboard') }}#produk" class="btn-back-link" style="color:inherit;text-decoration:none;">Produk</a></div>
      <h1 class="detail-page-title">Detail Produk</h1>
    </div>

    <!-- CARD 1: Main Product Showcase Card -->
    <section class="detail-card-box"
             id="productDetailCard"
             data-product-id="{{ $product->id }}"
             data-product-name="{{ $product->product_name }}"
             data-product-price="{{ $rawPrice }}"
             data-formatted-price="{{ $formattedPrice }}"
             data-product-img="{{ $mainProductImg }}"
             data-default-variant="{{ $defaultVariantStr }}">

      <div class="showcase-grid-layout">
        
        <!-- Left: Image & 5 Thumbnails -->
        <div class="gallery-column">
          <div class="main-image-display-frame">
            <img src="{{ $mainProductImg }}"
                 id="mainProductImage"
                 alt="{{ $product->product_name }}">
          </div>

          <div class="thumbs-horizontal-row">
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
                <button type="button"
                        class="variant-choice-pill pill-variant-btn {{ $cIdx === 0 ? 'selected' : '' }}"
                        data-variant-type="color"
                        data-variant-value="{{ $color }}">
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

          <!-- Stepper, Tambah Keranjang, Pesan Sekarang -->
          <div class="purchase-action-group">
            <div class="stepper-quantity-wrap">
              <button type="button" class="stepper-arrow-btn" id="btnQtyMinus" aria-label="Kurangi">&minus;</button>
              <input type="number"
                     id="productQtyInput"
                     class="stepper-number-val"
                     value="1"
                     min="1"
                     max="{{ max(1, (int)$product->stock) }}"
                     data-max-stock="{{ max(1, (int)$product->stock) }}"
                     readonly>
              <button type="button" class="stepper-arrow-btn" id="btnQtyPlus" aria-label="Tambah">&plus;</button>
            </div>

            @auth
              <button type="button"
                      class="btn-cart-outline-style"
                      id="btnAddToCartMain"
                      {{ $product->stock <= 0 ? 'disabled' : '' }}>
                Tambahkan ke keranjang
              </button>

              <button type="button"
                      class="btn-buy-solid-style"
                      id="btnBuyNow"
                      {{ $product->stock <= 0 ? 'disabled' : '' }}>
                Pesan Sekarang
              </button>
            @else
              <button type="button"
                      class="btn-cart-outline-style btn-trigger-login-prompt">
                Tambahkan ke keranjang
              </button>

              <button type="button"
                      class="btn-buy-solid-style btn-trigger-login-prompt">
                Pesan Sekarang
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
          <span class="spec-prop-key">Deskripsi</span>
          <span class="spec-prop-val">{{ $catName }}</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Deskripsi</span>
          <span class="spec-prop-val">{{ $productCode }}</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Deskripsi</span>
          <span class="spec-prop-val">{{ $product->product_name }}</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Deskripsi</span>
          <span class="spec-prop-val">100% Original Handmade</span>
        </div>
        <div class="spec-pair-item">
          <span class="spec-prop-key">Deskripsi</span>
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

    <!-- CARD 4: Produk Terkait Lainnya -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
      <section class="related-section-wrap">
        <h3 class="related-section-header">Produk Terkait Lainnya</h3>

        <div class="related-cards-grid">
          @foreach($relatedProducts->take(4) as $rIdx => $rProduct)
            @php
              $rRawPrice = (float)$rProduct->price < 1000 ? (float)$rProduct->price * 1000 : (float)$rProduct->price;
              if ($rRawPrice <= 0) $rRawPrice = 18500;
              $rPriceFmt = number_format($rRawPrice, 0, ',', '.');
              $rStrikeFmt = number_format($rRawPrice * 1.15, 0, ',', '.');
              $rImg = $resolveImg($rProduct->image, $rIdx + 1);
            @endphp
            <div class="related-product-card">
              <span class="rel-seller-badge">BEST SELLER #01</span>
              <div class="rel-image-box">
                <img src="{{ $rImg }}" alt="{{ $rProduct->product_name }}">
              </div>
              <div class="rel-category-text">{{ $rProduct->category ? $rProduct->category->category_name : 'Kategori' }}</div>
              <div class="rel-title-text" title="{{ $rProduct->product_name }}">{{ $rProduct->product_name }}</div>
              <div class="rel-price-row">
                <span class="rel-current-price">Rp{{ $rPriceFmt }}</span>
                <span class="rel-old-price">Rp {{ $rStrikeFmt }}</span>
              </div>
              <div class="rel-actions-row">
                <a href="{{ route('product.detail', $rProduct->id) }}" class="btn-card-detail">Detail</a>
                <button type="button" class="btn-card-cart btn-quick-cart {{ Auth::check() ? '' : 'btn-trigger-login-prompt' }}" data-id="{{ $rProduct->id }}" title="Tambah ke keranjang">
                  <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </button>
              </div>
            </div>
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
