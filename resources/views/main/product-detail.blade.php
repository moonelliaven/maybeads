@php
  use App\Models\Category;
  use App\Models\Product;

  $fallbacks = [
      'prod-butterfly.jpg',
      'prod-ring.jpg',
      'prod-hairclip.jpg',
      'prod-keychain.jpg',
      'hero-main.jpg',
      'hero-cross.jpg',
  ];

  $mainImg = asset('images/landing/prod-butterfly.jpg');
  if ($product->image && file_exists(storage_path('app/public/product/' . $product->image))) {
      $mainImg = asset('storage/product/' . $product->image);
  } elseif ($product->image && file_exists(public_path('images/products/' . $product->image))) {
      $mainImg = asset('images/products/' . $product->image);
  } elseif ($product->image && file_exists(public_path('images/landing/' . $product->image))) {
      $mainImg = asset('images/landing/' . $product->image);
  }

  $formattedPrice = 'Rp ' . number_format($product->price, 0, ',', '.');
  $categoryName = $product->category ? $product->category->category_name : 'Aksesoris';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $product->product_name }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css'])

  <style>
    .detail-page-container {
      max-width: 1200px;
      margin: 36px auto 100px;
      padding: 0 24px;
    }

    .detail-grid-layout {
      display: grid;
      grid-template-columns: 1fr 1.15fr;
      gap: 48px;
      background: #ffffff;
      padding: 36px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
    }

    /* Left: Gallery */
    .detail-gallery {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .gallery-main-wrap {
      width: 100%;
      aspect-ratio: 1 / 1;
      border-radius: 10px;
      overflow: hidden;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
    }

    .gallery-main-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .gallery-main-wrap:hover .gallery-main-img {
      transform: scale(1.03);
    }

    .gallery-thumbs-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }

    .gallery-thumb-item {
      aspect-ratio: 1 / 1;
      border-radius: 8px;
      overflow: hidden;
      border: 2px solid transparent;
      cursor: pointer;
      background: #f8fafc;
      transition: border-color 0.2s, transform 0.2s;
    }

    .gallery-thumb-item.active {
      border-color: #244173;
    }

    .gallery-thumb-item:hover {
      transform: translateY(-2px);
    }

    .gallery-thumb-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Right: Product Info */
    .detail-info {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .detail-breadcrumbs {
      font-size: 13px;
      color: #64748b;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .detail-breadcrumbs a {
      color: #64748b;
      text-decoration: none;
    }

    .detail-breadcrumbs a:hover {
      color: #244173;
    }

    .detail-title {
      font-size: 26px;
      font-weight: 800;
      color: #141418;
      line-height: 1.25;
      margin: 0;
    }

    .detail-price {
      font-size: 24px;
      font-weight: 800;
      color: #244173;
      margin: 0;
    }

    .detail-variants-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .variants-title {
      font-size: 13px;
      font-weight: 700;
      color: #334155;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }

    .variants-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .variant-pill {
      padding: 6px 16px;
      border-radius: 999px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      cursor: pointer;
      transition: all 0.2s;
    }

    .variant-pill:hover,
    .variant-pill.active {
      background: #244173;
      color: #ffffff;
      border-color: #244173;
    }

    /* Stepper */
    .detail-qty-group {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-top: 6px;
    }

    .detail-stepper {
      display: inline-flex;
      align-items: center;
      background: #f1f5f9;
      border-radius: 8px;
      padding: 4px 8px;
      gap: 16px;
      border: 1px solid #e2e8f0;
    }

    .detail-stepper-btn {
      background: none;
      border: none;
      font-size: 18px;
      font-weight: 700;
      color: #334155;
      cursor: pointer;
      width: 28px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .detail-stepper-val {
      font-size: 15px;
      font-weight: 700;
      min-width: 24px;
      text-align: center;
    }

    /* Action Buttons */
    .detail-actions-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-top: 10px;
    }

    .btn-buy-now {
      padding: 13px 20px;
      background: #244173;
      color: #ffffff;
      border: 1px solid #244173;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-buy-now:hover {
      background: #19315a;
      transform: translateY(-1px);
    }

    .btn-add-cart {
      padding: 13px 20px;
      background: #ffffff;
      color: #244173;
      border: 1.5px solid #244173;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-add-cart:hover {
      background: #f0f4fa;
      transform: translateY(-1px);
    }

    /* Product Tabs / Description */
    .detail-tabs-section {
      margin-top: 14px;
      border-top: 1px solid #e2e8f0;
      padding-top: 18px;
    }

    .detail-desc-text {
      font-size: 14px;
      line-height: 1.7;
      color: #475569;
    }

    /* Added to Cart Success Modal */
    .cart-success-modal {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }

    .cart-success-modal.show {
      display: flex;
    }

    .cart-success-card {
      background: #ffffff;
      border-radius: 12px;
      max-width: 420px;
      width: 100%;
      padding: 28px 24px;
      text-align: center;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      animation: popIn 0.25s ease-out;
    }

    @keyframes popIn {
      from { transform: scale(0.92); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .success-icon-wrap {
      width: 56px;
      height: 56px;
      background: #ecfdf5;
      color: #10b981;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
    }

    .success-icon-wrap svg {
      width: 28px;
      height: 28px;
    }

    .success-modal-title {
      font-size: 18px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 8px;
    }

    .success-modal-desc {
      font-size: 13.5px;
      color: #64748b;
      margin-bottom: 24px;
    }

    .success-modal-actions {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .btn-modal-continue {
      padding: 10px 16px;
      background: #f1f5f9;
      color: #334155;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
    }

    .btn-modal-to-cart {
      padding: 10px 16px;
      background: #244173;
      color: #ffffff;
      border: 1px solid #244173;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @media (max-width: 860px) {
      .detail-grid-layout {
        grid-template-columns: 1fr;
        gap: 32px;
        padding: 24px;
      }
      .detail-actions-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="dashboard-page">

  <!-- 1. Top Bar -->
  <header class="top-nav-bar">
    <div class="top-nav-container">
      <div class="top-nav-left">
        <a href="{{ route('main.dashboard') }}" class="back-home-link">
          <svg class="chevron-back" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Kembali ke Katalog</span>
        </a>
      </div>

      <div class="top-nav-right">
        <a href="{{ route('contact') }}" class="top-link">
          <svg class="pin-icon" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span>Kontak</span>
        </a>
        <span class="top-pipe">|</span>
        @auth
          <span class="top-link user-name">{{ Auth::user()->name }}</span>
        @else
          <a href="{{ route('login') }}" class="top-link">Masuk</a>
        @endauth
      </div>
    </div>
  </header>

  <!-- 2. Hero Banner (Temukan Gaya Favoritmu) -->
  <section class="catalog-hero-section">
    <div class="hero-bg-layer">
      <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Banner Maybeads" class="hero-bg-image">
      <div class="hero-gradient-overlay"></div>
    </div>
    <div class="hero-container">
      <h1 class="hero-main-title">Temukan Gaya Favoritmu</h1>
      <p class="hero-main-desc">
        Jelajahi koleksi pilihan Maybeads dan temukan berbagai aksesori serta produk unik yang melengkapi gayamu.
      </p>
    </div>
  </section>

  <!-- 3. Sub Navbar -->
  <nav class="sub-navbar-bar">
    <div class="sub-navbar-container">
      <div class="sub-nav-left">
        <a href="{{ route('main.dashboard') }}" class="sub-nav-brand">Maybeads</a>
        <span class="sub-nav-crumb">/ Detail Produk</span>
      </div>

      <div class="sub-nav-right">
        <a href="{{ route('cart.index') }}" class="action-pill-btn">
          <svg class="pill-icon" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
          <span>Keranjang</span>
          <span class="cart-count-badge visible" id="detailCartCount">{{ $cartCount ?? 0 }}</span>
        </a>
      </div>
    </div>
  </nav>

  <!-- 4. Detail Product Main Content -->
  <main class="detail-page-container">
    <div class="detail-grid-layout">
      
      <!-- Left: Gallery -->
      <div class="detail-gallery">
        <div class="gallery-main-wrap">
          <img src="{{ $mainImg }}" alt="{{ $product->product_name }}" id="currentGalleryMain" class="gallery-main-img">
        </div>
        <div class="gallery-thumbs-row">
          <div class="gallery-thumb-item active" data-src="{{ $mainImg }}">
            <img src="{{ $mainImg }}" alt="Thumb 1" class="gallery-thumb-img">
          </div>
          <div class="gallery-thumb-item" data-src="{{ asset('images/landing/prod-ring.jpg') }}">
            <img src="{{ asset('images/landing/prod-ring.jpg') }}" alt="Thumb 2" class="gallery-thumb-img">
          </div>
          <div class="gallery-thumb-item" data-src="{{ asset('images/landing/prod-hairclip.jpg') }}">
            <img src="{{ asset('images/landing/prod-hairclip.jpg') }}" alt="Thumb 3" class="gallery-thumb-img">
          </div>
        </div>
      </div>

      <!-- Right: Product Info -->
      <div class="detail-info">
        <div class="detail-breadcrumbs">
          <a href="{{ route('main.dashboard') }}">Produk</a>
          <span>›</span>
          <span>{{ $categoryName }}</span>
        </div>

        <h1 class="detail-title">{{ $product->product_name }}</h1>
        <div class="detail-price">{{ $formattedPrice }}</div>

        <!-- Variants: Warna -->
        <div class="detail-variants-group">
          <span class="variants-title">Pilihan Warna:</span>
          <div class="variants-pills" id="colorPills">
            <button type="button" class="variant-pill active">Silver / Chrome</button>
            <button type="button" class="variant-pill">Biru Kristal</button>
            <button type="button" class="variant-pill">Hitam Monokrom</button>
          </div>
        </div>

        <!-- Variants: Ukuran -->
        <div class="detail-variants-group">
          <span class="variants-title">Ukuran / Varian:</span>
          <div class="variants-pills" id="sizePills">
            <button type="button" class="variant-pill active">Standard (All Size)</button>
            <button type="button" class="variant-pill">Adjustable</button>
          </div>
        </div>

        <!-- Quantity Stepper -->
        <div class="detail-qty-group">
          <span class="variants-title">Jumlah:</span>
          <div class="detail-stepper">
            <button type="button" class="detail-stepper-btn" id="btnQtyMinus">−</button>
            <span class="detail-stepper-val" id="detailQtyVal">1</span>
            <button type="button" class="detail-stepper-btn" id="btnQtyPlus">+</button>
          </div>
          <span style="font-size: 13px; color: #64748b;">Tersedia: {{ $product->stock ?? 25 }} pcs</span>
        </div>

        <!-- Action Buttons: Beli Sekarang & Tambah ke Keranjang -->
        <div class="detail-actions-row">
          <button type="button" class="btn-buy-now" id="btnBuyNowAction">
            Beli Sekarang
          </button>
          <button type="button" class="btn-add-cart" id="btnAddToCartAction">
            <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span>Tambah ke Keranjang</span>
          </button>
        </div>

        <!-- Tabs / Description -->
        <div class="detail-tabs-section">
          <h4 class="variants-title" style="margin-bottom: 8px;">Deskripsi Produk</h4>
          <p class="detail-desc-text">
            {{ $product->description ?: 'Set aksesoris Y2K orisinil Maybeads dengan paduan elemen krom futuristik dan sentuhan streetwear modern. Diproduksi dengan presisi tinggi dan bahan hypoallergenic yang nyaman digunakan sepanjang hari.' }}
          </p>
        </div>

      </div>

    </div>
  </main>

  <!-- 5. Added to Cart Success Modal -->
  <div class="cart-success-modal" id="cartSuccessModal">
    <div class="cart-success-card">
      <div class="success-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
      </div>
      <h3 class="success-modal-title">Berhasil Ditambahkan!</h3>
      <p class="success-modal-desc">Produk <strong>{{ $product->product_name }}</strong> telah masuk ke keranjang belanja Anda.</p>
      <div class="success-modal-actions">
        <button type="button" class="btn-modal-continue" id="btnModalContinue">Lanjut Belanja</button>
        <a href="{{ route('cart.index') }}" class="btn-modal-to-cart">Lihat Keranjang</a>
      </div>
    </div>
  </div>

  <!-- 6. Footer -->
  <footer class="site-dark-footer">
    <div class="site-footer-container">
      <div class="site-footer-columns">
        <div class="footer-col-item brand-item">
          <h3 class="footer-brand-title">Maybeads</h3>
          <p class="footer-brand-tagline">Menyajikan busana nostalgia tahun 2000-an yang otentik dengan sentuhan streetwear modern dan energi futuristik.</p>
        </div>
        <div class="footer-col-item">
          <h4 class="footer-section-title">TAUTAN</h4>
          <ul class="footer-links-list">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('main.dashboard') }}">Produk</a></li>
            <li><a href="{{ route('contact') }}">Kontak</a></li>
          </ul>
        </div>
        <div class="footer-col-item">
          <h4 class="footer-section-title">KEBIJAKAN</h4>
          <ul class="footer-links-list">
            <li><a href="#">Kebijakan Privasi</a></li>
            <li><a href="#">Ketentuan Layanan</a></li>
          </ul>
        </div>
        <div class="footer-col-item">
          <h4 class="footer-section-title">PEMBAYARAN</h4>
          <ul class="footer-links-list payment-methods-list">
            <li><span class="payment-method-item">QRIS</span></li>
            <li><span class="payment-method-item">Transfer Bank</span></li>
          </ul>
        </div>
      </div>
      <div class="site-footer-bottom">
        <div class="footer-bottom-left">Built for digital cyber-nomads.</div>
        <div class="footer-bottom-right">&copy; {{ date('Y') }} Maybeads. All rights reserved.</div>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Gallery Thumbnails Click
      const thumbs = document.querySelectorAll('.gallery-thumb-item');
      const mainImg = document.getElementById('currentGalleryMain');
      thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
          thumbs.forEach(t => t.classList.remove('active'));
          thumb.classList.add('active');
          if (mainImg) mainImg.src = thumb.dataset.src;
        });
      });

      // Variant selection
      document.querySelectorAll('#colorPills .variant-pill').forEach(p => {
        p.addEventListener('click', () => {
          document.querySelectorAll('#colorPills .variant-pill').forEach(x => x.classList.remove('active'));
          p.classList.add('active');
        });
      });

      document.querySelectorAll('#sizePills .variant-pill').forEach(p => {
        p.addEventListener('click', () => {
          document.querySelectorAll('#sizePills .variant-pill').forEach(x => x.classList.remove('active'));
          p.classList.add('active');
        });
      });

      // Stepper
      let qty = 1;
      const valEl = document.getElementById('detailQtyVal');
      document.getElementById('btnQtyMinus').addEventListener('click', () => {
        if (qty > 1) { qty--; valEl.textContent = qty; }
      });
      document.getElementById('btnQtyPlus').addEventListener('click', () => {
        qty++; valEl.textContent = qty;
      });

      // Add to Cart
      const btnAddCart = document.getElementById('btnAddToCartAction');
      const modal = document.getElementById('cartSuccessModal');
      const btnContinue = document.getElementById('btnModalContinue');
      const cartCountEl = document.getElementById('detailCartCount');

      function saveToLocalStorage(productData) {
        let cart = [];
        try {
          const raw = localStorage.getItem('maybeads_cart');
          cart = raw ? JSON.parse(raw) : [];
          if (!Array.isArray(cart)) {
            cart = [];
          }
        } catch (e) {
          cart = [];
        }

        const existing = cart.find(i => Number(i.id) === Number(productData.id));
        if (existing) {
          existing.qty = Number(existing.qty || 0) + Number(productData.qty || 1);
          existing.checked = true;
          existing.rawPrice = Number(existing.rawPrice || productData.rawPrice || 0);
          existing.price = productData.price || existing.price;
          existing.color = productData.color || existing.color;
          existing.image = productData.image || existing.image;
        } else {
          cart.push({
            ...productData,
            qty: Number(productData.qty || 1),
            checked: true,
            addedAt: Date.now()
          });
        }

        localStorage.setItem('maybeads_cart', JSON.stringify(cart));
        const totalQty = cart.reduce((acc, curr) => acc + (Number(curr.qty) || 0), 0);
        if (cartCountEl) cartCountEl.textContent = totalQty;
      }

      if (btnAddCart) {
        btnAddCart.addEventListener('click', () => {
          saveToLocalStorage({
            id: {{ $product->id }},
            name: "{{ addslashes($product->product_name) }}",
            color: "Chrome, Standard",
            qty: qty,
            price: "{{ $formattedPrice }}",
            rawPrice: {{ (int) $product->price }},
            image: "{{ $mainImg }}",
            checked: true,
            addedAt: Date.now()
          });
          if (modal) modal.classList.add('show');
        });
      }

      if (btnContinue && modal) {
        btnContinue.addEventListener('click', () => modal.classList.remove('show'));
      }

      // Buy Now: redirect straight to checkout
      const btnBuyNow = document.getElementById('btnBuyNowAction');
      if (btnBuyNow) {
        btnBuyNow.addEventListener('click', () => {
          saveToLocalStorage({
            id: {{ $product->id }},
            name: "{{ addslashes($product->product_name) }}",
            color: "Chrome, Standard",
            qty: qty,
            price: "{{ $formattedPrice }}",
            rawPrice: {{ (int) $product->price }},
            image: "{{ $mainImg }}",
            checked: true,
            addedAt: Date.now()
          });
          window.location.href = "{{ route('checkout') }}";
        });
      }
    });
  </script>
</body>
</html>

