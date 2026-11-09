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

  $imgFor = function ($p, $i) use ($fallbacks) {
      if ($p->image && file_exists(storage_path('app/public/product/' . $p->image))) {
          return asset('storage/product/' . $p->image);
      }
      if ($p->image && file_exists(public_path('images/products/' . $p->image))) {
          return asset('images/products/' . $p->image);
      }
      if ($p->image && file_exists(public_path('images/landing/' . $p->image))) {
          return asset('images/landing/' . $p->image);
      }
      return asset('images/landing/' . $fallbacks[$i % count($fallbacks)]);
  };

  $currentSort     = request('sort', 'terbaru');
  $currentCategory = request('category', '');
  $currentColor    = request('color', '');
  $currentSearch   = request('search', '');

  $sortOptions = [
    'terbaru'  => 'Terbaru',
    'terlama'  => 'Terlama',
    'termurah' => 'Dari Termurah',
    'termahal' => 'Dari Termahal',
    'nama_az'  => 'Nama A-Z',
    'nama_za'  => 'Nama Z-A',
  ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Produk Lengkap — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <!-- Google Fonts: Inter / Plus Jakarta Sans & Playfair Display -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css', 'resources/js/main/dashboard.js'])
</head>
<body class="dashboard-page">

  <!-- ========================================================================
       1. TOP BAR (Dark Slate Header)
       ======================================================================== -->
  <header class="top-nav-bar">
    <div class="top-nav-container">
      <div class="top-nav-left">
        <a href="{{ route('home') }}" class="back-home-link">
          <svg class="chevron-back" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Kembali ke Utama</span>
        </a>
      </div>

      <div class="top-nav-right">
        <a href="#kontak" class="top-link">
          <svg class="pin-icon" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span>Kontak</span>
        </a>
        <span class="top-pipe">|</span>
        @auth
          <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('main.dashboard') }}" class="top-link user-name">
            {{ Auth::user()->name }}
          </a>
          <span class="top-pipe">|</span>
          <form method="POST" action="{{ route('logout') }}" class="inline-logout-form">
            @csrf
            <button type="submit" class="top-link-btn">Keluar</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="top-link">Masuk</a>
          <span class="top-pipe">|</span>
          <a href="{{ route('register') }}" class="top-link">Daftar</a>
        @endauth
      </div>
    </div>
  </header>


  <!-- ========================================================================
       2. HERO BANNER SECTION (Blue Gradient with Beaded Jewelry Backdrop)
       ======================================================================== -->
  <section class="catalog-hero-section">
    <div class="hero-bg-layer">
      <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Aksesoris Manik Maybeads" class="hero-bg-image" loading="eager">
      <div class="hero-gradient-overlay"></div>
    </div>
    <div class="hero-container">
      <h1 class="hero-main-title">Temukan Gaya Favoritmu</h1>
      <p class="hero-main-desc">
        Jelajahi koleksi pilihan Maybeads dan temukan berbagai aksesori serta produk unik yang melengkapi gayamu.
      </p>
    </div>
  </section>


  <!-- ========================================================================
       3. SUB-NAVBAR (White Bar with Filter button, Logo, Cart, and Account)
       ======================================================================== -->
  <nav class="sub-navbar-bar" id="subNavbar">
    <div class="sub-navbar-container">
      <!-- Left: Filter button + Brand Logo + Breadcrumb -->
      <div class="sub-nav-left">
        <button type="button" class="btn-filter-pill" id="filterToggleBtn" aria-label="Toggle Filter Urutan Produk">
          <span>Filter</span>
          <svg class="filter-arrow" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </button>

        <a href="{{ route('main.dashboard') }}" class="sub-nav-brand">
          Maybeads
        </a>

        <span class="sub-nav-crumb">/ Katalog Belanja</span>
      </div>

      <!-- Right: Keranjang and Akun buttons -->
      <div class="sub-nav-right">
        <!-- Keranjang Button with Pop-up (No Blur, Exact Match Mockup) -->
        <div class="cart-dropdown-wrapper" id="cartDropdownWrapper">
          <button type="button" class="action-pill-btn" id="cartOpenBtn" aria-expanded="false" title="Lihat Keranjang">
            <svg class="pill-icon" viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>Keranjang</span>
            <span class="cart-count-badge {{ ($cartCount ?? 0) > 0 ? 'visible' : '' }}" id="cartCountBadge">{{ $cartCount ?? 0 }}</span>
          </button>

          <!-- Cart Pop-up Card -->
          <div class="cart-popup-card" id="cartPopupCard" role="dialog" aria-label="Keranjang Belanja">
            <div class="cart-popup-header">
              <div class="cart-popup-title">
                <svg viewBox="0 0 24 24" class="cart-popup-icon"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                <span>Keranjang Belanja</span>
              </div>
              <button type="button" class="btn-close-cart-popup" id="btnCloseCartPopup" aria-label="Tutup Keranjang">&times;</button>
            </div>

            <div class="cart-popup-divider"></div>

            <div class="cart-popup-items-list" id="cartPopupItemsList">
              <!-- Dynamically populated via JS, max 5 items -->
            </div>

            <div class="cart-popup-footer">
              <div class="cart-popup-note" id="cartPopupNote">
                Menampilkan 5 Produk Terakhir Ditambahkan
              </div>
              <a href="{{ route('cart.index') }}" class="btn-cart-popup-detail" id="btnCartPopupDetail">
                <span>Lihat Detail</span>
                <svg viewBox="0 0 24 24" class="btn-arrow-icon"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
              </a>
            </div>
          </div>
        </div>

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
              <a href="{{ route('orders.history') }}" class="acc-popover-item">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Riwayat Pesanan
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
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
       4. MAIN BODY CONTAINER (Left Sidebar + Right Product Catalog)
       ======================================================================== -->
  <main class="catalog-main-layout">
    <div class="catalog-main-container">

      <!-- ====== LEFT SIDEBAR FILTER CARD ====== -->
      <aside class="sidebar-filter-panel" id="sidebarFilterPanel">
        <div class="filter-card-box">
          <form id="filterForm" method="GET" action="{{ url()->current() }}">
            <!-- Hidden inputs to preserve query params -->
            <input type="hidden" name="sort" id="inputSort" value="{{ $currentSort }}">
            <input type="hidden" name="category" id="inputCategory" value="{{ $currentCategory }}">
            <input type="hidden" name="color" id="inputColor" value="{{ $currentColor }}">
            @if($currentSearch)
              <input type="hidden" name="search" id="inputSearch" value="{{ $currentSearch }}">
            @endif

            <!-- 1. URUTAN PRODUK (Sort by Date / Price / Name) -->
            <div class="filter-block">
              <h4 class="filter-block-title">URUTAN PRODUK</h4>
              <div class="sort-options-list">
                @foreach($sortOptions as $key => $label)
                  <button type="button"
                          class="sort-radio-option {{ $currentSort === $key ? 'selected' : '' }}"
                          data-sort="{{ $key }}">
                    <span class="custom-radio">
                      <span class="radio-inner-dot"></span>
                    </span>
                    <span class="option-label">{{ $label }}</span>
                  </button>
                @endforeach
              </div>
            </div>

            <!-- Divider -->
            <div class="filter-block-divider"></div>

            <!-- 2. URUTAN PRODUK (Categories from DB with count & expand) -->
            <div class="filter-block">
              <h4 class="filter-block-title">URUTAN PRODUK</h4>
              <div class="category-options-list" id="categoryOptionsList">
                <!-- Semua Kategori -->
                <button type="button"
                        class="category-option-item {{ !$currentCategory ? 'selected' : '' }}"
                        data-category="">
                  <div class="cat-label-wrap">
                    <span class="cat-bullet-dot">•</span>
                    <span class="cat-title">Semua</span>
                  </div>
                  <span class="cat-pill-badge">Semua</span>
                </button>

                <!-- Dynamic Categories -->
                @foreach($categories as $index => $cat)
                  <button type="button"
                          class="category-option-item {{ $currentCategory == $cat->id ? 'selected' : '' }} {{ $index >= 3 ? 'cat-extra-item is-hidden' : '' }}"
                          data-category="{{ $cat->id }}">
                    <div class="cat-label-wrap">
                      <span class="cat-bullet-dot">•</span>
                      <span class="cat-title">{{ $cat->category_name }}</span>
                    </div>
                    <span class="cat-count-badge">{{ $cat->products_count }}</span>
                  </button>
                @endforeach
              </div>

              @if($categories->count() > 3)
                <button type="button" class="btn-expand-outline" id="btnToggleCategories">
                  <span id="textExpandCategories">Lihat lebih</span>
                  <svg class="chevron-expand" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
              @endif
            </div>

            <!-- Divider -->
            <div class="filter-block-divider"></div>

            <!-- 3. PILIHAN WARNA (Color Pill buttons) -->
            <div class="filter-block">
              <h4 class="filter-block-title">PILIHAN WARNA</h4>
              <div class="color-options-wrap">
                <!-- Row 1: Merah & Biru -->
                <div class="color-row">
                  <button type="button"
                          class="color-pill-btn {{ strtolower($currentColor) === 'merah' ? 'selected' : '' }}"
                          data-color="Merah"
                          title="Warna Merah">
                    <span class="color-swatch-dot dot-red"></span>
                    <span>Merah</span>
                  </button>

                  <button type="button"
                          class="color-pill-btn {{ strtolower($currentColor) === 'biru' ? 'selected' : '' }}"
                          data-color="Biru"
                          title="Warna Biru">
                    <span class="color-swatch-dot dot-blue"></span>
                    <span>Biru</span>
                  </button>
                </div>

                <!-- Row 2: Monokrom -->
                <div class="color-row">
                  <button type="button"
                          class="color-pill-btn {{ strtolower($currentColor) === 'monokrom' ? 'selected' : '' }}"
                          data-color="Monokrom"
                          title="Warna Monokrom">
                    <span class="color-swatch-dot dot-monochrome"></span>
                    <span>Monokrom</span>
                  </button>
                </div>

                <!-- Extra colors from variants -->
                @if(isset($colors) && $colors->count())
                  <div class="extra-colors-wrap is-hidden" id="extraColorsWrap">
                    @foreach($colors as $color)
                      @php $cLow = strtolower($color); @endphp
                      @if(!in_array($cLow, ['merah', 'biru', 'monokrom', 'black', 'hitam']))
                        <button type="button"
                                class="color-pill-btn {{ $currentColor === $color ? 'selected' : '' }}"
                                data-color="{{ $color }}"
                                title="Warna {{ $color }}">
                          <span class="color-swatch-dot dot-default"></span>
                          <span>{{ $color }}</span>
                        </button>
                      @endif
                    @endforeach
                  </div>

                  <button type="button" class="btn-expand-outline" id="btnToggleColors">
                    <span id="textExpandColors">Lainnya</span>
                    <svg class="chevron-expand" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                  </button>
                @endif
              </div>
            </div>

            @if($currentCategory || $currentColor || $currentSearch || $currentSort !== 'terbaru')
              <div class="reset-filter-row">
                <a href="{{ route('main.dashboard') }}" class="btn-clear-all-filter">
                  &times; Reset Filter
                </a>
              </div>
            @endif
          </form>
        </div>
      </aside>

      <!-- ====== RIGHT CATALOG CONTENT ====== -->
      <section class="catalog-content-panel">

        <!-- Header: Breadcrumb & Title on Left, Search Bar on Right -->
        <div class="catalog-top-header">
          <div class="top-header-left">
            <div class="header-breadcrumbs">
              <span>Dashboard</span>
              <span class="bc-sep">›</span>
              <span class="bc-active">Produk</span>
            </div>
            <h2 class="header-main-title">Produk Lengkap</h2>
          </div>

          <div class="top-header-right">
            <form method="GET" action="{{ url()->current() }}" class="catalog-search-form" id="mainSearchForm">
              @if($currentSort && $currentSort !== 'terbaru')
                <input type="hidden" name="sort" value="{{ $currentSort }}">
              @endif
              @if($currentCategory)
                <input type="hidden" name="category" value="{{ $currentCategory }}">
              @endif
              @if($currentColor)
                <input type="hidden" name="color" value="{{ $currentColor }}">
              @endif

              <div class="search-input-container">
                <svg class="search-lens-icon" viewBox="0 0 24 24">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text"
                       name="search"
                       class="search-box-input"
                       placeholder="Cari Produk ..."
                       value="{{ $currentSearch }}"
                       autocomplete="off">
                <button type="submit" class="btn-search-navy">
                  Cari
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- 4-Column Product Grid -->
        <div class="products-grid-wrapper" id="productsGridWrapper">
          @forelse($products as $index => $product)
            @php
              $formattedPrice = 'Rp ' . number_format($product->price, 0, ',', '.');
              $productImg = $imgFor($product, $index);
              $productCode = str_pad($product->id, 4, '0', STR_PAD_LEFT);
              $categoryName = $product->category_name;
              $desc = $product->description ?: 'Set perhiasan elegan dengan hiasan orisinil berkualitas tinggi. Cocok digunakan untuk melengkapi penampilan sehari-hari.';
              $colorList = $product->variants ? $product->variants->pluck('color')->filter()->unique()->implode(', ') : '';
              if (!$colorList) {
                $colorList = 'Biru, Kuning';
              }
            @endphp

            <article class="product-card-item"
                     data-id="{{ $product->id }}"
                     data-code="{{ $productCode }}"
                     data-name="{{ $product->product_name }}"
                     data-category="{{ $categoryName }}"
                     data-price="{{ $formattedPrice }}"
                     data-image="{{ $productImg }}"
                     data-desc="{{ $desc }}"
                     data-stock="{{ $product->stock }}"
                     data-color="{{ $colorList }}"
                     data-edit-url="{{ route('admin.product.edit', $product->id) }}"
                     data-delete-url="{{ route('admin.product.destroy', $product->id) }}">

              <!-- Image with Top-Left Code Badge -->
              <div class="card-media-wrap">
                <span class="card-code-badge">{{ $productCode }}</span>
                <img src="{{ $productImg }}"
                     alt="{{ $product->product_name }}"
                     class="card-product-img"
                     loading="lazy">
              </div>

              <!-- Card Body -->
              <div class="card-info-wrap">
                <div class="card-cat-label">{{ $categoryName }}</div>
                <h3 class="card-product-title" title="{{ $product->product_name }}">
                  {{ $product->product_name }}
                </h3>
                <p class="card-product-desc" title="{{ $desc }}">
                  {{ $desc }}
                </p>

                <!-- Bottom Actions Row: Detail | Edit | Delete -->
                <div class="card-actions-row">
                  <a href="{{ route('product.detail', $product->id) }}" class="btn-card-detail" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                    Detail
                  </a>

                  <a href="{{ route('admin.product.edit', $product->id) }}"
                     class="btn-card-icon btn-edit-product"
                     title="Edit Produk (Admin)">
                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                  </a>

                  <button type="button"
                          class="btn-card-icon btn-delete-product"
                          data-action="delete"
                          data-delete-url="{{ route('admin.product.destroy', $product->id) }}"
                          data-name="{{ $product->product_name }}"
                          title="Hapus Produk (Admin)">
                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </button>
                </div>
              </div>
            </article>
          @empty
            <div class="no-products-box">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
              <h3>Tidak Ada Produk yang Ditemukan</h3>
              <p>Coba gunakan kata kunci pencarian lain atau reset filter pilihan Anda.</p>
              <a href="{{ route('main.dashboard') }}" class="btn-reset-catalog">Tampilkan Semua Produk</a>
            </div>
          @endforelse
        </div>

        <!-- Catalog Footer: Showing Count & Pagination -->
        <div class="catalog-pagination-row">
          <div class="catalog-items-counter">
            Menampilkan {{ $products->count() }} Produk dari {{ $products->total() }}
          </div>

          @if($products->hasPages())
            <nav class="custom-catalog-pagination" aria-label="Navigasi Halaman">
              {{-- Previous Link --}}
              @if($products->onFirstPage())
                <span class="pag-btn disabled">&lsaquo;</span>
              @else
                <a href="{{ $products->previousPageUrl() }}" class="pag-btn">&lsaquo;</a>
              @endif

              {{-- Page Numbers --}}
              @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                  <span class="pag-btn current">{{ $page }}</span>
                @else
                  <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
                @endif
              @endforeach

              {{-- Next Link --}}
              @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="pag-btn">&rsaquo;</a>
              @else
                <span class="pag-btn disabled">&rsaquo;</span>
              @endif
            </nav>
          @endif
        </div>

      </section>
    </div>
  </main>


  <!-- ========================================================================
       5. FOOTER (5 Columns Dark Navy Footer)
       ======================================================================== -->
  <footer class="site-dark-footer" id="kontak">
    <div class="site-footer-container">
      <div class="site-footer-columns">

        <!-- Column 1: Brand Info -->
        <div class="footer-col-item brand-item">
          <h3 class="footer-brand-title">Maybeads</h3>
          <p class="footer-brand-tagline">
            Menyajikan busana nostalgia tahun 2000-an yang otentik dengan sentuhan streetwear modern dan energi futuristik.
          </p>
        </div>

        <!-- Column 2: TAUTAN -->
        <div class="footer-col-item">
          <h4 class="footer-section-title">TAUTAN</h4>
          <ul class="footer-links-list">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('main.dashboard') }}">Produk</a></li>
            <li><a href="{{ route('home') }}#kontak">Kontak</a></li>
          </ul>
        </div>

        <!-- Column 3: KEBIJAKAN -->
        <div class="footer-col-item">
          <h4 class="footer-section-title">KEBIJAKAN</h4>
          <ul class="footer-links-list">
            <li><a href="#">Kebijakan Privasi</a></li>
            <li><a href="#">Ketentuan Layanan</a></li>
            <li><a href="#">Pengembalian &amp; Penukaran</a></li>
            <li><a href="#">Informasi Pengiriman</a></li>
          </ul>
        </div>

        <!-- Column 4: SOSIAL MEDIA -->
        <div class="footer-col-item">
          <h4 class="footer-section-title">SOSIAL MEDIA</h4>
          <ul class="footer-links-list social-media-links">
            <li>
              <a href="https://instagram.com" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                <span>Instagram</span>
              </a>
            </li>
            <li>
              <a href="https://tiktok.com" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>
                <span>Tiktok</span>
              </a>
            </li>
          </ul>
        </div>

        <!-- Column 5: PEMBAYARAN -->
        <div class="footer-col-item">
          <h4 class="footer-section-title">PEMBAYARAN</h4>
          <ul class="footer-links-list payment-methods-list">
            <li>
              <span class="payment-method-item">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>QRIS</span>
              </span>
            </li>
            <li>
              <span class="payment-method-item">
                <svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                <span>Gopay</span>
              </span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Footer Bottom Copyright Bar -->
      <div class="site-footer-bottom">
        <div class="footer-bottom-left">
          Built for digital cyber-nomads.
        </div>
        <div class="footer-bottom-right">
          &copy; {{ date('Y') }} Maybeads. All rights reserved.
        </div>
      </div>
    </div>
  </footer>


  <!-- ========================================================================
       MODAL 1: DETAIL / QUICK VIEW MODAL
       ======================================================================== -->
  <div class="modal-backdrop" id="detailModalBackdrop">
    <div class="product-detail-modal" id="productDetailModal" role="dialog" aria-modal="true">
      <button type="button" class="btn-close-modal" id="btnDetailClose" aria-label="Tutup Detail">&times;</button>
      <div class="modal-inner-grid">
        <div class="modal-image-col">
          <img src="" alt="" id="modalProductImage" class="modal-large-img">
        </div>
        <div class="modal-info-col">
          <div class="modal-tags-row">
            <span class="modal-badge-cat" id="modalCategoryTag">Kategori</span>
            <span class="modal-badge-id" id="modalCodeTag">#0001</span>
          </div>
          <h2 class="modal-title-text" id="modalProductName">Nama Produk</h2>
          <div class="modal-price-text" id="modalProductPrice">Rp 0</div>
          <p class="modal-desc-text" id="modalProductDesc">Deskripsi produk...</p>

          <div class="modal-bottom-actions">
            <button type="button" class="btn-modal-add-cart" id="modalAddToCartBtn">
              <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              <span>Tambah ke Keranjang</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>




  <!-- Hidden Form for Admin Delete -->
  <form id="deleteProductForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
  </form>

</body>
</html>