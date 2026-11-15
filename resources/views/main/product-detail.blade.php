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
  $desc = $product->description ?: 'Set perhiasan elegan dengan manik berkualitas tinggi, dirangkai dengan teliti untuk memberikan sentuhan manis pada setiap penampilan Anda.';

  // Variants from database
  $colors = $product->variants ? $product->variants->pluck('color')->filter()->unique()->values() : collect();
  $sizes  = $product->variants ? $product->variants->pluck('size')->filter()->unique()->values() : collect();
  if ($colors->isEmpty()) {
      $colors = collect(['Silver Chrome', 'Black Onyx', 'Pastel Pink']);
  }
  if ($sizes->isEmpty()) {
      $sizes = collect(['Standard', 'Large (Custom)']);
  }
  $defaultVariantStr = $colors->first() ?: ($sizes->first() ?: 'Standard');

  // Secondary images if available, with rich fallbacks so gallery is always full
  $galleryImages = [];
  $galleryImages[] = $mainProductImg;
  $secImages = is_string($product->secondary_images) ? json_decode($product->secondary_images, true) : $product->secondary_images;
  if (!empty($secImages) && is_array($secImages)) {
      foreach ($secImages as $idx => $sImg) {
          $galleryImages[] = $resolveImg($sImg, $idx + 1);
      }
  }
  if (count($galleryImages) < 3) {
      $extraFallbacks = ['prod-ring.jpg', 'prod-butterfly.jpg', 'prod-keychain.jpg', 'prod-hairclip.jpg'];
      foreach ($extraFallbacks as $fb) {
          if (count($galleryImages) >= 3) break;
          $u = $resolveImg($fb, count($galleryImages));
          if (!in_array($u, $galleryImages)) {
              $galleryImages[] = $u;
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

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/main/product-detail.css', 'resources/js/main/product-detail.js'])
</head>
<body class="product-detail-page">

  {{-- 1. Top Navbar (Maybead.s Brand & Links) --}}
  <header class="detail-top-navbar">
    <div class="top-nav-inner">
      <a href="{{ route('home') }}" class="brand-title">
        Maybead.s
      </a>

      <nav class="top-nav-links" aria-label="Main Navigation">
        <a href="{{ route('home') }}" class="nav-link">BERANDA</a>
        <a href="{{ route('contact') }}" class="nav-link">KONTAK</a>
        <a href="{{ route('main.dashboard') }}" class="nav-link active">PRODUK</a>
      </nav>
    </div>
  </header>

  {{-- 2. Sub-Navbar --}}
  <nav class="detail-sub-navbar">
    <div class="sub-nav-inner">
      <div class="sub-nav-left">
        <a href="{{ route('main.dashboard') }}" class="sub-brand">Maybeads</a>
        <span class="sub-crumb">/ Detail Produk</span>
      </div>

      <div class="sub-nav-right">
        @auth
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
        @else
          <button type="button" class="action-pill-btn btn-trigger-login-prompt" title="Lihat Keranjang Belanja">
            <svg class="pill-icon" viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Keranjang</span>
          </button>
        @endauth
      </div>
    </div>
  </nav>

  {{-- 3. Main Detail Container --}}
  <main class="detail-main-wrapper">

    {{-- Tombol Kembali & Breadcrumb --}}
    <div class="detail-back-bar" style="margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
      <a href="{{ route('main.dashboard') }}" class="btn-back-to-home" style="display: inline-flex; align-items: center; gap: 8px; background: #ffffff; border: 1.5px solid #23477d; color: #23477d; font-weight: 700; font-size: 13px; padding: 7px 18px; border-radius: 9999px; text-decoration: none; transition: all 0.2s ease; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 15px; height: 15px;">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
        <span>Kembali ke Halaman Utama</span>
      </a>

      {{-- Breadcrumb Trail --}}
      <div class="breadcrumb-trail" style="margin: 0;">
        <a href="{{ route('home') }}">Beranda</a>
        <span class="sep">&gt;</span>
        <a href="{{ route('main.dashboard') }}">Katalog Produk</a>
        <span class="sep">&gt;</span>
        <a href="{{ route('main.dashboard', ['category' => $product->category_id]) }}">{{ $catName }}</a>
        <span class="sep">&gt;</span>
        <span class="active-crumb">{{ $product->product_name }}</span>
      </div>
    </div>

    {{-- Product Showcase Grid --}}
    <section class="product-showcase-grid"
             id="productDetailCard"
             data-product-id="{{ $product->id }}"
             data-product-name="{{ $product->product_name }}"
             data-product-price="{{ $rawPrice }}"
             data-formatted-price="{{ $formattedPrice }}"
             data-product-img="{{ $mainProductImg }}"
             data-default-variant="{{ $defaultVariantStr }}">

      {{-- Left: Gallery --}}
      <div class="showcase-gallery-col">
        <div class="main-preview-frame">
          <img src="{{ $mainProductImg }}"
               alt="{{ $product->product_name }}"
               id="mainProductImage"
               class="main-preview-img">
        </div>

        @if(count($galleryImages) > 1)
          <div class="gallery-thumbs-row">
            @foreach($galleryImages as $tIdx => $tImg)
              <button type="button"
                      class="thumb-item-btn {{ $tIdx === 0 ? 'active' : '' }}"
                      data-src="{{ $tImg }}"
                      aria-label="Thumbnail {{ $tIdx + 1 }}">
                <img src="{{ $tImg }}" alt="{{ $product->product_name }} {{ $tIdx + 1 }}">
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

          @auth
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
          @else
            <button type="button"
                    class="btn-add-to-cart-large btn-trigger-login-prompt"
                    title="Tambah ke Keranjang">
              <svg viewBox="0 0 24 24">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
              </svg>
              <span>Tambah ke Keranjang</span>
            </button>

            <button type="button"
                    class="btn-buy-now btn-trigger-login-prompt"
                    title="Beli Sekarang">
              Beli Sekarang
            </button>
          @endauth
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

    {{-- 5. Testimoni Pembeli (Ulasan Asli Dari Pengguna) --}}
    <section class="product-testimonials-section" style="margin-top: 36px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
        <div>
          <h2 class="section-subheading" style="margin: 0 0 6px 0; display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" style="width:20px;height:20px;">
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            <span>Ulasan &amp; Testimoni Pembeli</span>
          </h2>
          <p style="font-size: 13px; color: #64748b; margin: 0;">Pengalaman asli dari pelanggan yang telah memesan produk ini</p>
        </div>

        <div style="display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0;">
          <div style="font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1;">
            {{ number_format($product->avg_rating, 1, ',', '.') }}
          </div>
          <div style="display: flex; flex-direction: column;">
            <div style="display: flex; gap: 2px;">
              @for($s = 1; $s <= 5; $s++)
                <svg viewBox="0 0 24 24" fill="{{ $s <= round($product->avg_rating) ? '#f59e0b' : '#cbd5e1' }}" stroke="none" style="width:14px;height:14px;">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
              @endfor
            </div>
            <span style="font-size: 11px; color: #64748b; font-weight: 600;">
              Total {{ $product->review_count }} Ulasan
            </span>
          </div>
        </div>
      </div>

      @if(isset($testimonials) && $testimonials->count() > 0)
        <div class="testimonials-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
          @foreach($testimonials as $testi)
            @php
              $initial = strtoupper(substr($testi->user->name ?? 'U', 0, 1));
              $colorsList = ['#2563eb', '#7c3aed', '#059669', '#ea580c', '#db2777', '#0891b2'];
              $avatarBg = $colorsList[($testi->user_id ?? 1) % count($colorsList)];
            @endphp
            <div class="testi-card-box" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; justify-content: space-between;">
              <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $avatarBg }}; color: #ffffff; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                      {{ $initial }}
                    </div>
                    <div>
                      <div style="font-size: 13.5px; font-weight: 700; color: #0f172a; line-height: 1.2;">
                        {{ $testi->user->name ?? 'Pelanggan' }}
                      </div>
                      <span style="font-size: 11px; color: #059669; font-weight: 600; display: inline-flex; align-items: center; gap: 3px;">
                        ✓ Pembeli Terverifikasi
                      </span>
                    </div>
                  </div>

                  <div style="display: flex; gap: 1px;">
                    @for($star = 1; $star <= 5; $star++)
                      <svg viewBox="0 0 24 24" fill="{{ $star <= $testi->rating ? '#f59e0b' : '#cbd5e1' }}" stroke="none" style="width:13px;height:13px;">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                      </svg>
                    @endfor
                  </div>
                </div>

                <p style="font-size: 13.5px; color: #334155; line-height: 1.55; margin: 0; font-style: italic;">
                  &ldquo;{{ $testi->message }}&rdquo;
                </p>
              </div>

              <div style="margin-top: 14px; padding-top: 8px; border-top: 1px solid #edf2f7; font-size: 11px; color: #94a3b8; text-align: right;">
                {{ $testi->created_at ? $testi->created_at->diffForHumans() : 'Baru saja' }}
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div style="text-align: center; padding: 32px 16px; color: #64748b;">
          <p style="margin: 0; font-size: 14px;">Belum ada ulasan khusus untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
        </div>
      @endif
    </section>

    {{-- 6. Related Products Section (Rekomendasi Terkait) --}}
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
      <section class="related-products-section" style="margin-top: 36px;">
        <h2 class="related-section-title">Produk Terkait Lainnya</h2>

        <div class="related-grid">
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
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @endif

  </main>

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

  <!-- ========================================================================
       MODAL: POP UP LOGIN DIPERLUKAN (Guest Restriction)
       ======================================================================== -->
  <div class="login-prompt-backdrop" id="loginPromptModal" role="dialog" aria-modal="true" aria-labelledby="loginPromptTitle" hidden>
    <div class="login-prompt-box">
      <div class="login-prompt-icon-wrap">!</div>
      <h3 class="login-prompt-title" id="loginPromptTitle">Silahkan Login Terlebih Dahulu</h3>
      <p class="login-prompt-desc">Anda perlu masuk ke akun untuk dapat menggunakan fitur ini.</p>
      <div class="login-prompt-actions">
        <button type="button" class="btn-prompt-cancel" id="btnCancelLoginPrompt">Kembali</button>
        <a href="{{ route('login') }}" class="btn-prompt-login" id="btnProceedLogin">Login Sekarang</a>
      </div>
    </div>
  </div>

  @include('partials.user-logout-modal')

</body>
</html>
