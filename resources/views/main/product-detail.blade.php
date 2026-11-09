@php
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
  $formattedPrice = 'Rp ' . number_format($product->price, 0, ',', '.');
  $productCode = 'IDP' . str_pad($product->id, 4, '0', STR_PAD_LEFT);
  $catName = $product->category_name;
  $desc = $product->description ?: 'Set perhiasan elegan dengan manik berkualitas tinggi, dirangkai dengan teliti untuk memberikan sentuhan manis pada setiap penampilan Anda.';

  // Variants from database
  $colors = $product->variants ? $product->variants->pluck('color')->filter()->unique()->values() : collect();
  $sizes  = $product->variants ? $product->variants->pluck('size')->filter()->unique()->values() : collect();
  $defaultVariantStr = $colors->first() ?: ($sizes->first() ?: 'Standard');

  // Secondary images if available
  $galleryImages = [];
  $galleryImages[] = $mainProductImg;
  if (!empty($product->secondary_images) && is_array($product->secondary_images)) {
      foreach ($product->secondary_images as $idx => $sImg) {
          $galleryImages[] = $resolveImg($sImg, $idx + 1);
      }
  }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $product->product_name }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/main/product-detail.css', 'resources/js/main/product-detail.js'])
</head>
<body class="product-detail-page">

  {{-- Hidden Data Store untuk JavaScript Cart --}}
  <div id="productDetailStore"
       data-id="{{ $product->id }}"
       data-code="{{ $productCode }}"
       data-name="{{ $product->product_name }}"
       data-price-formatted="{{ $formattedPrice }}"
       data-raw-price="{{ (int) $product->price }}"
       data-image="{{ $mainProductImg }}"
       data-category="{{ $catName }}"
       data-stock="{{ $product->stock }}"
       data-default-variant="{{ $defaultVariantStr }}"
       hidden>
  </div>

  {{-- 1. Top Bar (Dark Slate Header - Maybead.s) --}}
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
          <li><a href="{{ route('home') }}#kontak" class="top-menu-link">KONTAK</a></li>
          <li><a href="{{ route('main.dashboard') }}" class="top-menu-link active">PRODUK</a></li>
        </ul>
      </nav>
    </div>
  </header>

  {{-- 2. White Sub-Navbar --}}
  <nav class="sub-navbar-bar">
    <div class="sub-navbar-container">
      <div class="sub-nav-left">
        <a href="{{ route('main.dashboard') }}" class="sub-nav-brand">
          Maybeads
        </a>
        <span class="sub-nav-crumb">/ Detail Produk</span>
      </div>

      <div class="sub-nav-right">
        <a href="{{ route('cart.index') }}" class="action-pill-btn" title="Lihat Keranjang Belanja">
          <svg class="pill-icon" viewBox="0 0 24 24">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
          <span>Keranjang</span>
          <span class="cart-count-badge" id="cartCountBadge" style="{{ ($cartCount ?? 0) > 0 ? '' : 'display:none;' }}">
            {{ $cartCount ?? 0 }}
          </span>
        </a>

        <!-- Akun Button -->
        <div class="account-dropdown-wrapper" style="position: relative;">
          <a href="{{ route('login') }}" class="action-pill-btn" title="Menu Akun">
            <svg class="pill-icon" viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Akun</span>
            <svg class="chevron-down" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </a>
        </div>
      </div>
    </div>
  </nav>

  {{-- 3. Main Detail Container --}}
  <main class="detail-main-wrapper">

    {{-- Breadcrumb Trail --}}
    <div class="breadcrumb-trail">
      <a href="{{ route('home') }}">Beranda</a>
      <span class="sep">&gt;</span>
      <a href="{{ route('main.dashboard') }}">Katalog Produk</a>
      <span class="sep">&gt;</span>
      <a href="{{ route('main.dashboard', ['category' => $product->category_id]) }}">{{ $catName }}</a>
      <span class="sep">&gt;</span>
      <span class="active-crumb">{{ $product->product_name }}</span>
    </div>

    {{-- Product Showcase Card (Image Gallery + Info) --}}
    <section class="product-showcase-card">

      {{-- Left: Image Gallery Showcase --}}
      <div class="showcase-media-col">
        <div class="main-img-box">
          <span class="media-code-badge">{{ $productCode }}</span>
          <img src="{{ $mainProductImg }}"
               alt="{{ $product->product_name }}"
               id="mainProductImage"
               class="main-img-display">
        </div>

        @if(count($galleryImages) > 1)
          <div class="thumbnail-gallery-row">
            @foreach($galleryImages as $index => $gImg)
              <button type="button"
                      class="thumb-item-btn {{ $index === 0 ? 'active' : '' }}"
                      data-src="{{ $gImg }}"
                      aria-label="Lihat foto {{ $index + 1 }}">
                <img src="{{ $gImg }}" alt="Thumbnail {{ $index + 1 }}">
              </button>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Right: Product Details & Purchase Actions --}}
      <div class="showcase-info-col">
        <span class="product-cat-pill">{{ $catName }}</span>

        <h1 class="product-main-heading">{{ $product->product_name }}</h1>

        <div class="product-price-stock-row">
          <div class="product-main-price">{{ $formattedPrice }}</div>

          @if($product->stock > 0)
            <span class="stock-status-pill in-stock">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:14px;height:14px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
              Stok Tersedia ({{ $product->stock }} pcs)
            </span>
          @else
            <span class="stock-status-pill out-of-stock">
              Stok Habis
            </span>
          @endif
        </div>

        <div class="product-desc-box">
          {{ $desc }}
        </div>

        {{-- Dynamic Variants Section from Database --}}
        @if($colors->count() > 0 || $sizes->count() > 0)
          <div class="variants-selection-wrap">
            {{-- Pilihan Warna --}}
            @if($colors->count() > 0)
              <div>
                <div class="variant-group-title">
                  <span>PILIHAN WARNA:</span>
                </div>
                <div class="variant-options-row">
                  @foreach($colors as $idx => $color)
                    <button type="button"
                            class="variant-choice-pill {{ $idx === 0 ? 'selected' : '' }}"
                            data-variant-type="color"
                            data-variant-value="{{ $color }}">
                      {{ $color }}
                    </button>
                  @endforeach
                </div>
              </div>
            @endif

            {{-- Pilihan Ukuran --}}
            @if($sizes->count() > 0)
              <div>
                <div class="variant-group-title">
                  <span>PILIHAN UKURAN:</span>
                </div>
                <div class="variant-options-row">
                  @foreach($sizes as $idx => $size)
                    <button type="button"
                            class="variant-choice-pill {{ $idx === 0 ? 'selected' : '' }}"
                            data-variant-type="size"
                            data-variant-value="{{ $size }}">
                      {{ $size }}
                    </button>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endif

        {{-- Quantity Stepper & CTA Buttons --}}
        <div class="purchase-controls-row">
          <div class="qty-stepper-box">
            <button type="button" class="qty-btn" id="btnQtyMinus" aria-label="Kurangi jumlah">&minus;</button>
            <input type="number"
                   id="productQtyInput"
                   class="qty-input"
                   value="1"
                   min="1"
                   max="{{ max(1, (int)$product->stock) }}"
                   data-max-stock="{{ max(1, (int)$product->stock) }}"
                   readonly>
            <button type="button" class="qty-btn" id="btnQtyPlus" aria-label="Tambah jumlah">&plus;</button>
          </div>

          <button type="button"
                  class="btn-add-to-cart-large"
                  id="btnAddToCartMain"
                  {{ $product->stock <= 0 ? 'disabled' : '' }}>
            <svg viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Tambah ke Keranjang</span>
          </button>

          <button type="button"
                  class="btn-buy-now"
                  id="btnBuyNow"
                  {{ $product->stock <= 0 ? 'disabled' : '' }}>
            Beli Sekarang
          </button>
        </div>

        {{-- Quality Trust Badges --}}
        <div class="trust-features-grid">
          <div class="trust-feature-item">
            <div class="trust-feature-icon">
              <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
            </div>
            <div>
              <div class="trust-feature-title">100% Handmade</div>
              <div class="trust-feature-desc">Kualitas manik rapi</div>
            </div>
          </div>

          <div class="trust-feature-item">
            <div class="trust-feature-icon">
              <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            </div>
            <div>
              <div class="trust-feature-title">Pengiriman Aman</div>
              <div class="trust-feature-desc">Kemasan bubble wrap</div>
            </div>
          </div>

          <div class="trust-feature-item">
            <div class="trust-feature-icon">
              <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div>
              <div class="trust-feature-title">Garansi Sesuai</div>
              <div class="trust-feature-desc">Produk sesuai foto</div>
            </div>
          </div>
        </div>

      </div>
    </section>

    {{-- 4. Product Technical Specifications (Database Table) --}}
    <section class="product-specs-section">
      <h2 class="section-subheading">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
        </svg>
        Spesifikasi Produk
      </h2>

      <table class="specs-table">
        <tbody>
          <tr>
            <th>Kode Produk</th>
            <td>{{ $productCode }}</td>
          </tr>
          <tr>
            <th>Nama Produk</th>
            <td>{{ $product->product_name }}</td>
          </tr>
          <tr>
            <th>Kategori Utama</th>
            <td>{{ $catName }}</td>
          </tr>
          <tr>
            <th>Ketersediaan Stok</th>
            <td>{{ $product->stock }} unit tersedia</td>
          </tr>
          <tr>
            <th>Status Produk</th>
            <td>
              <span style="color: #16a34a; font-weight: 700;">
                {{ ucfirst($product->status ?? 'Aktif') }}
              </span>
            </td>
          </tr>
          @if($product->variants && $product->variants->count() > 0)
            <tr>
              <th>Varian Tersedia</th>
              <td>
                {{ $product->variants->map(fn($v) => trim(($v->color ?? '') . ' ' . ($v->size ?? '')))->filter()->implode(', ') }}
              </td>
            </tr>
          @endif
        </tbody>
      </table>
    </section>

    {{-- 5. Related Products Section (Rekomendasi Terkait) --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
      <section class="related-products-section">
        <h2 class="related-section-title">Produk Terkait Lainnya</h2>

        <div class="related-grid">
          @foreach($relatedProducts as $rIdx => $rProduct)
            @php
              $rPrice = 'Rp ' . number_format($rProduct->price, 0, ',', '.');
              $rImg   = $resolveImg($rProduct->image, $rProduct->id);
              $rCode  = 'IDP' . str_pad($rProduct->id, 4, '0', STR_PAD_LEFT);
              $rDesc  = $rProduct->description ?: 'Set perhiasan elegan dengan hiasan orisinil berkualitas tinggi.';
            @endphp
            <article class="product-card-item"
                     data-id="{{ $rProduct->id }}"
                     data-code="{{ $rCode }}"
                     data-name="{{ $rProduct->product_name }}"
                     data-category="{{ $rProduct->category_name }}"
                     data-price="{{ $rPrice }}"
                     data-image="{{ $rImg }}"
                     data-desc="{{ $rDesc }}"
                     style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; padding:14px;">
              <div style="position:relative; width:100%; height:200px; border-radius:8px; overflow:hidden; background:#f1f5f9; margin-bottom:12px;">
                <span style="position:absolute; top:8px; left:8px; background:rgba(0,0,0,0.6); color:#fff; font-size:10px; font-weight:700; padding:2px 8px; border-radius:4px;">
                  {{ $rCode }}
                </span>
                <img src="{{ $rImg }}" alt="{{ $rProduct->product_name }}" style="width:100%; height:100%; object-fit:cover;">
              </div>

              <div style="font-size:11px; font-weight:700; color:#23477d; text-transform:uppercase; margin-bottom:4px;">
                {{ $rProduct->category_name }}
              </div>
              <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:6px; line-height:1.3; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ $rProduct->product_name }}
              </h3>
              <div style="font-size:15px; font-weight:800; color:#23477d; margin-bottom:14px;">
                {{ $rPrice }}
              </div>

              <div style="display:flex; align-items:center; gap:8px; margin-top:auto;">
                <a href="{{ route('main.product.detail', $rProduct->id) }}"
                   style="flex:1; border:1px solid #d1d5db; background:#fff; border-radius:6px; padding:6px 10px; font-size:11.5px; font-weight:600; color:#1e293b; text-align:center;">
                  Detail
                </a>
                <button type="button"
                        class="btn-related-add-cart"
                        style="flex:1.2; border:1px solid #23477d; background:#23477d; color:#fff; border-radius:6px; padding:6px 10px; font-size:11.5px; font-weight:600; cursor:pointer; white-space:nowrap;">
                  + Keranjang
                </button>
              </div>
            </article>
          @endforeach
        </div>
      </section>
    @endif

  </main>

  {{-- 6. Site Footer --}}
  <footer class="catalog-site-footer">
    <div style="max-width:var(--max-w); margin:0 auto;">
      <p>&copy; {{ date('Y') }} Maybeads. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>

