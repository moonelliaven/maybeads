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
  $currentSize     = request('size', '');
  $currentSearch   = request('search', '');

  $hasFilters = $currentCategory || $currentColor || $currentSize || $currentSearch;

  $sortLabels = [
    'terbaru'  => 'Terbaru',
    'terlama'  => 'Terlama',
    'termurah' => 'Harga Termurah',
    'termahal' => 'Harga Termahal',
    'nama_az'  => 'Nama A - Z',
    'nama_za'  => 'Nama Z - A',
  ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Katalog Produk Lengkap — Maybead.s</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  <meta name="description" content="Koleksi lengkap aksesoris Y2K Maybead.s: cincin chrome, kalung perak, gelang manik, dan gantungan kunci estetik langsung dari database.">

  <!-- Google Fonts: Plus Jakarta Sans & Playfair Display (Matching Admin Dashboard) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css', 'resources/js/main/dashboard.js'])
</head>
<body class="catalog-page">

  <!-- ========================================================================
       1. HEADER KECIL (Fitur Bahasa, Kembali ke Utama, Kontak, Auth)
       ======================================================================== -->
  <header class="top-header" id="topHeader">
    <div class="top-header-inner">
      <div class="top-header-left">
        <a href="{{ route('home') }}" class="top-header-link" title="Kembali ke Beranda Utama">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Kembali ke Utama</span>
        </a>
        <span class="top-header-divider"></span>
        <span class="top-header-badge">
          <span class="sparkle">✦</span>
          Pengiriman ke Seluruh Indonesia • Bebas Ongkir Min. Rp 150.000
        </span>
      </div>

      <div class="top-header-right">
        <!-- Language Selector -->
        <div class="lang-dropdown-wrapper">
          <button type="button" class="lang-selector-btn" id="langToggleBtn" aria-expanded="false" title="Pilih Bahasa">
            <svg class="icon-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
            <span id="currentLangText">ID</span>
            <svg class="icon-caret" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </button>
          <div class="lang-dropdown-menu" id="langMenu">
            <button type="button" class="lang-item active" data-lang="ID">
              <span class="flag-icon">🇮🇩</span> Bahasa Indonesia (ID)
            </button>
            <button type="button" class="lang-item" data-lang="EN">
              <span class="flag-icon">🇬🇧</span> English (EN)
            </button>
          </div>
        </div>

        <span class="top-header-divider"></span>
        <a href="#kontak" class="top-header-link">
          <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span>Kontak</span>
        </a>

        <span class="top-header-divider"></span>

        <!-- User Authentication status -->
        @auth
          <div class="top-header-user">
            <span class="top-user-pill">
              <span class="status-dot"></span>
              {{ Auth::user()->name }}
              @if(Auth::user()->isAdmin())
                <span class="role-badge">Admin</span>
              @endif
            </span>
          </div>
        @else
          <a href="{{ route('login') }}" class="top-header-link">Masuk</a>
          <span class="top-header-divider"></span>
          <a href="{{ route('register') }}" class="top-header-link font-semibold">Daftar</a>
        @endauth
      </div>
    </div>
  </header>


  <!-- ========================================================================
       2. BANNER PROMOSI (Foto + Teks Promosi Simple Mengenai Website Ini)
       ======================================================================== -->
  <section class="promo-banner" id="promoBanner">
    <div class="promo-banner-bg" style="background-image: url('{{ asset('images/landing/hero-main.jpg') }}');"></div>
    <div class="promo-banner-overlay"></div>
    <div class="promo-banner-container">
      <div class="promo-banner-content">
        <div class="promo-pill-tag">
          <span class="sparkle">✦</span>
          <span>MAYBEAD.S OFFICIAL STORE • Y2K CONTEMPORARY</span>
          <span class="sparkle">✦</span>
        </div>
        <h1 class="promo-heading">
          Eksplorasi Aksesoris <em>Y2K</em> Impianmu
        </h1>
        <p class="promo-description">
          Koleksi busana nostalgia era 2000-an berpadu sentuhan streetwear kontemporer presisi. Dari cincin gotik chrome, kalung perak, jepit rambut unik, hingga gantungan kunci aesthetic untuk melengkapi persona harianmu.
        </p>
        <div class="promo-perks">
          <div class="perk-item">
            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
            <span>100% Produk Original</span>
          </div>
          <div class="perk-item">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Stok Database Real-Time</span>
          </div>
          <div class="perk-item">
            <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            <span>Pengiriman Cepat & Aman</span>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ========================================================================
       3. NAVBAR SIMPLE (Hanya Menu Keranjang dan User Saja)
       ======================================================================== -->
  <nav class="main-navbar" id="mainNavbar">
    <div class="main-navbar-container">
      <!-- Brand Logo / Identity -->
      <div class="navbar-left">
        <a href="{{ route('home') }}" class="brand-logo" title="Maybead.s">
          Maybead<span>.</span>s
        </a>
        <div class="navbar-breadcrumb">
          <span class="bc-sep">/</span>
          <span class="bc-current">Katalog Belanja</span>
        </div>
      </div>

      <!-- Center Announcement or Simple Pill -->
      <div class="navbar-center">
        <div class="navbar-indicator">
          <span class="pulse-indicator"></span>
          <span>Menampilkan {{ $products->total() }} Produk Siap Kirim</span>
        </div>
      </div>

      <!-- Right Action Menu: HANYA Keranjang dan User Saja Sesuai Permintaan -->
      <div class="navbar-actions">
        <!-- 1. Menu Keranjang -->
        <button type="button" class="navbar-btn btn-cart" id="cartOpenBtn" title="Buka Keranjang Belanja">
          <div class="icon-wrap">
            <svg viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="cart-badge-count" id="cartCountBadge">{{ $cartCount ?? 0 }}</span>
          </div>
          <span class="btn-label">Keranjang</span>
        </button>

        <!-- 2. Menu User -->
        <div class="user-menu-wrapper">
          @auth
            <button type="button" class="navbar-btn btn-user" id="userMenuBtn" aria-expanded="false" title="Menu Akun Saya">
              <div class="user-avatar-initial">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </div>
              <span class="btn-label">{{ explode(' ', trim(Auth::user()->name))[0] }}</span>
              <svg class="icon-caret" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>
            <div class="user-dropdown-menu" id="userMenu">
              <div class="user-dropdown-header">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
                <span class="badge-role">{{ ucfirst(Auth::user()->role) }}</span>
              </div>
              <div class="user-dropdown-divider"></div>
              @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="user-dropdown-item">
                  <svg viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/></svg>
                  Admin Dashboard
                </a>
              @endif
              <a href="#pesanan" class="user-dropdown-item" id="openOrdersBtn">
                <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Pesanan Saya
              </a>
              <div class="user-dropdown-divider"></div>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="user-dropdown-item text-danger">
                  <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                  Keluar
                </button>
              </form>
            </div>
          @else
            <button type="button" class="navbar-btn btn-user" id="userMenuBtn" aria-expanded="false" title="Menu Pengguna">
              <div class="user-avatar-guest">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              </div>
              <span class="btn-label">Akun</span>
              <svg class="icon-caret" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>
            <div class="user-dropdown-menu" id="userMenu">
              <div class="user-dropdown-header">
                <strong>Selamat Datang!</strong>
                <small>Silakan masuk atau daftar akun</small>
              </div>
              <div class="user-dropdown-divider"></div>
              <a href="{{ route('login') }}" class="user-dropdown-item">
                <svg viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk ke Akun
              </a>
              <a href="{{ route('register') }}" class="user-dropdown-item">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 20a7 7 0 0 1 14 0"/><path d="M19 4v4M17 6h4"/></svg>
                Daftar Baru
              </a>
            </div>
          @endauth
        </div>
      </div>
    </div>
  </nav>


  <!-- ========================================================================
       4. KONTEN UTAMA (Sidebar Sortir di Kiri + Tampilan Produk di Kanan)
       ======================================================================== -->
  <main class="catalog-main" id="catalogMain">
    <div class="catalog-wrapper">

      <!-- Backdrop for mobile sidebar toggle -->
      <div class="filter-backdrop" id="filterBackdrop"></div>

      <!-- ====== FILTER & SORTIR SIDEBAR ====== -->
      <aside class="sortir-sidebar" id="sortirSidebar" aria-label="Sidebar Filter & Sortir">
        <form id="filterForm" method="GET" action="{{ url()->current() }}">
          <!-- Hidden inputs to retain state -->
          <input type="hidden" name="sort" id="formInputSort" value="{{ $currentSort }}">
          <input type="hidden" name="category" id="formInputCategory" value="{{ $currentCategory }}">
          <input type="hidden" name="color" id="formInputColor" value="{{ $currentColor }}">
          <input type="hidden" name="size" id="formInputSize" value="{{ $currentSize }}">
          @if($currentSearch)
            <input type="hidden" name="search" id="formInputSearch" value="{{ $currentSearch }}">
          @endif

          <!-- Sidebar Header Inside Card -->
          <div class="sidebar-card-header">
            <div class="sidebar-card-title">
              <svg viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              <span>Filter & Sortir</span>
            </div>
            <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" title="Tutup Sortir">
              <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
          </div>

          <div class="sidebar-scrollable">
            <!-- 1. Urutkan (Sortir Terbaru, Terlama, Termurah, Termahal, dll.) -->
            <div class="filter-group">
              <div class="filter-group-header">
                <span class="group-title">Urutkan Produk</span>
                <span class="group-desc">Pilih urutan</span>
              </div>
              <div class="filter-options-list">
                @foreach($sortLabels as $key => $label)
                  <button type="button"
                          class="filter-radio-item {{ $currentSort === $key ? 'selected' : '' }}"
                          data-sort="{{ $key }}">
                    <span class="radio-indicator"></span>
                    <span class="radio-label">{{ $label }}</span>
                    @if($key === 'terbaru')
                      <span class="mini-pill">Default</span>
                    @endif
                  </button>
                @endforeach
              </div>
            </div>

            <div class="filter-group-divider"></div>

            <!-- 2. Kategori (Dari Database) -->
            <div class="filter-group">
              <div class="filter-group-header">
                <span class="group-title">Kategori</span>
                <span class="group-count">Database ({{ $categories->count() }})</span>
              </div>
              <div class="filter-options-list">
                <!-- Semua Kategori -->
                <button type="button"
                        class="filter-category-item {{ !$currentCategory ? 'selected' : '' }}"
                        data-category="">
                  <div class="cat-left">
                    <span class="cat-bullet"></span>
                    <span class="cat-name">Semua Kategori</span>
                  </div>
                  <span class="cat-count-badge">{{ $totalProducts ?? 0 }}</span>
                </button>

                @foreach($categories as $cat)
                  <button type="button"
                          class="filter-category-item {{ $currentCategory == $cat->id ? 'selected' : '' }}"
                          data-category="{{ $cat->id }}">
                    <div class="cat-left">
                      <span class="cat-bullet"></span>
                      <span class="cat-name">{{ $cat->category_name }}</span>
                    </div>
                    <span class="cat-count-badge">{{ $cat->products_count }}</span>
                  </button>
                @endforeach
              </div>
            </div>

            <div class="filter-group-divider"></div>

            <!-- 3. Warna (Dari Database Product Variants) -->
            @if(isset($colors) && $colors->count())
              <div class="filter-group">
                <div class="filter-group-header">
                  <span class="group-title">Pilihan Warna</span>
                  <span class="group-count">Varian</span>
                </div>
                <div class="color-swatches-grid">
                  @foreach($colors as $color)
                    @php
                      $cLower = strtolower($color);
                      $swatchBg = '#cbd5e1';
                      if (str_contains($cLower, 'chrome') || str_contains($cLower, 'silver')) $swatchBg = 'linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%)';
                      elseif (str_contains($cLower, 'hitam') || str_contains($cLower, 'black')) $swatchBg = '#0f172a';
                      elseif (str_contains($cLower, 'biru') || str_contains($cLower, 'blue')) $swatchBg = '#3b82f6';
                      elseif (str_contains($cLower, 'pink') || str_contains($cLower, 'merah muda')) $swatchBg = '#ec4899';
                      elseif (str_contains($cLower, 'putih') || str_contains($cLower, 'clear')) $swatchBg = '#ffffff';
                      elseif (str_contains($cLower, 'emas') || str_contains($cLower, 'gold')) $swatchBg = '#eab308';
                    @endphp
                    <button type="button"
                            class="color-chip-btn {{ $currentColor === $color ? 'selected' : '' }}"
                            data-color="{{ $color }}"
                            title="Filter warna {{ $color }}">
                      <span class="color-dot" style="background: {{ $swatchBg }};"></span>
                      <span class="color-name">{{ $color }}</span>
                      @if($currentColor === $color)
                        <svg class="check-icon" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                      @endif
                    </button>
                  @endforeach
                </div>
              </div>
              <div class="filter-group-divider"></div>
            @endif

            <!-- 4. Ukuran (Dari Database Product Variants) -->
            @if(isset($sizes) && $sizes->count())
              <div class="filter-group">
                <div class="filter-group-header">
                  <span class="group-title">Ukuran</span>
                  <span class="group-count">Varian</span>
                </div>
                <div class="size-chips-wrap">
                  @foreach($sizes as $size)
                    <button type="button"
                            class="size-chip-btn {{ $currentSize === $size ? 'selected' : '' }}"
                            data-size="{{ $size }}">
                      {{ $size }}
                    </button>
                  @endforeach
                </div>
              </div>
              <div class="filter-group-divider"></div>
            @endif

            <!-- Reset Filter Button -->
            @if($hasFilters || $currentSort !== 'terbaru')
              <div class="filter-reset-wrap">
                <button type="button" class="filter-reset-btn" id="filterResetBtn">
                  <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                  <span>Reset Semua Filter</span>
                </button>
              </div>
            @endif
          </div>
        </form>
      </aside>

      <!-- ====== KONTEN PRODUK (DI KANAN) ====== -->
      <section class="products-display-section" id="productsSection">

        <!-- Navbar Di Atasnya: "TAMPILKAN SORTIR" Toggle Bar -->
        <div class="sortir-toggle-bar">
          <div class="toggle-left">
            <button type="button" class="btn-toggle-sortir active" id="sortirToggleBtn" aria-expanded="true">
              <svg class="icon-sliders" viewBox="0 0 24 24">
                <line x1="4" y1="21" x2="4" y2="14"></line>
                <line x1="4" y1="10" x2="4" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12" y2="3"></line>
                <line x1="20" y1="21" x2="20" y2="16"></line>
                <line x1="20" y1="12" x2="20" y2="3"></line>
                <line x1="1" y1="14" x2="7" y2="14"></line>
                <line x1="9" y1="8" x2="15" y2="8"></line>
                <line x1="17" y1="16" x2="23" y2="16"></line>
              </svg>
              <span class="toggle-text" id="toggleSortirText">Tampilkan Sortir</span>
              <svg class="icon-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>

            <!-- Active filter chips pills in toggle bar -->
            <div class="active-chips-summary">
              @if($currentCategory)
                @php $activeCatName = $categories->firstWhere('id', $currentCategory)?->category_name ?? 'Kategori'; @endphp
                <span class="summary-chip">
                  <span>Kategori: <strong>{{ $activeCatName }}</strong></span>
                  <button type="button" class="btn-remove-chip" data-remove="category">&times;</button>
                </span>
              @endif

              @if($currentColor)
                <span class="summary-chip">
                  <span>Warna: <strong>{{ $currentColor }}</strong></span>
                  <button type="button" class="btn-remove-chip" data-remove="color">&times;</button>
                </span>
              @endif

              @if($currentSize)
                <span class="summary-chip">
                  <span>Ukuran: <strong>{{ $currentSize }}</strong></span>
                  <button type="button" class="btn-remove-chip" data-remove="size">&times;</button>
                </span>
              @endif

              @if($currentSearch)
                <span class="summary-chip highlight">
                  <span>Cari: "<strong>{{ $currentSearch }}</strong>"</span>
                  <button type="button" class="btn-remove-chip" data-remove="search">&times;</button>
                </span>
              @endif
            </div>
          </div>

          <div class="toggle-right">
            <span class="showing-count-text">
              Menampilkan <strong>{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</strong> dari <strong>{{ $products->total() }}</strong> produk
            </span>
          </div>
        </div>

        <!-- HEADER KONTEN PRODUK (Teks 'Produk Lengkap' di Kiri & Search di Kanan) -->
        <div class="catalog-header-bar">
          <div class="catalog-title-wrapper">
            <div class="title-main-row">
              <h2 class="section-title">Produk Lengkap</h2>
              <span class="total-badge">{{ $products->total() }} Item</span>
            </div>
            <p class="section-subtitle">Aksesoris orisinil Y2K Maybead.s langsung dari data inventaris aktif kami.</p>

            <!-- Quick Category Filter Pills (Sesuai wireframe 4 baris di bawah judul) -->
            <div class="quick-category-pills">
              <button type="button"
                      class="quick-pill {{ !$currentCategory ? 'active' : '' }}"
                      data-cat="">
                Semua ({{ $totalProducts ?? 0 }})
              </button>
              @foreach($categories as $cat)
                <button type="button"
                        class="quick-pill {{ $currentCategory == $cat->id ? 'active' : '' }}"
                        data-cat="{{ $cat->id }}">
                  {{ $cat->category_name }} ({{ $cat->products_count }})
                </button>
              @endforeach
            </div>
          </div>

          <!-- FITUR SEARCH DI KANAN (Mencakup ID Produk, Nama, Kategori, dan Semacamnya) -->
          <div class="catalog-search-wrapper">
            <form method="GET" action="{{ url()->current() }}" class="catalog-search-form" id="searchForm">
              <!-- Retain filters on search -->
              @if($currentSort && $currentSort !== 'terbaru')
                <input type="hidden" name="sort" value="{{ $currentSort }}">
              @endif
              @if($currentCategory)
                <input type="hidden" name="category" value="{{ $currentCategory }}">
              @endif
              @if($currentColor)
                <input type="hidden" name="color" value="{{ $currentColor }}">
              @endif
              @if($currentSize)
                <input type="hidden" name="size" value="{{ $currentSize }}">
              @endif

              <div class="search-input-box">
                <svg class="search-icon" viewBox="0 0 24 24">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>

                <input type="text"
                       name="search"
                       id="searchInputField"
                       class="search-input"
                       placeholder="Cari ID (#001), nama, kategori..."
                       value="{{ $currentSearch }}"
                       autocomplete="off">

                @if($currentSearch)
                  <button type="button" class="search-clear-btn" id="searchClearBtn" title="Hapus Pencarian">&times;</button>
                @endif

                <button type="submit" class="search-submit-btn">
                  <span>Cari</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- ====== PRODUCT GRID (MAKSIMAL 5x4 = 20 PRODUK) ====== -->
        <div class="products-grid-container" id="productsGrid">
          @forelse($products as $index => $product)
            @php
              $formattedPrice = 'Rp ' . number_format($product->price, 0, ',', '.');
              $productImg = $imgFor($product, $index);
              $isNew = $product->created_at && $product->created_at->diffInDays(now()) <= 14;
              $variantColors = $product->variants->pluck('color')->filter()->unique()->values();
              $variantSizes  = $product->variants->pluck('size')->filter()->unique()->values();
            @endphp

            <article class="product-card"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->product_name }}"
                     data-price="{{ $formattedPrice }}"
                     data-category="{{ $product->category_name }}"
                     data-stock="{{ $product->stock }}"
                     data-image="{{ $productImg }}"
                     data-desc="{{ $product->description ?? 'Aksesoris Y2K orisinil Maybead.s berkualitas premium.' }}"
                     data-colors="{{ $variantColors->join(', ') }}"
                     data-sizes="{{ $variantSizes->join(', ') }}">

              <!-- Card Media & Badges -->
              <div class="product-card-media">
                <div class="card-badges-row">
                  @if($isNew)
                    <span class="badge-tag new">Baru</span>
                  @endif
                  @if($product->stock <= 0)
                    <span class="badge-tag out">Habis</span>
                  @elseif($product->stock <= 5)
                    <span class="badge-tag low">Sisa {{ $product->stock }}</span>
                  @endif
                </div>

                <img src="{{ $productImg }}"
                     alt="{{ $product->product_name }}"
                     class="product-image"
                     loading="lazy">

                <!-- Hover Overlay with Quick Action Buttons -->
                <div class="card-hover-actions">
                  <button type="button" class="btn-card-action btn-quick-view" title="Lihat Detail Produk">
                    <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    <span>Quick View</span>
                  </button>
                  <button type="button" class="btn-card-action btn-add-cart" title="Tambah ke Keranjang">
                    <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span>+ Keranjang</span>
                  </button>
                </div>
              </div>

              <!-- Card Information -->
              <div class="product-card-body">
                <div class="card-meta-top">
                  <span class="card-category-pill">{{ $product->category_name }}</span>
                  <span class="card-id-pill" title="ID Produk">#{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>

                <h3 class="product-title" title="{{ $product->product_name }}">
                  {{ $product->product_name }}
                </h3>

                @if($variantColors->count() > 0)
                  <div class="card-variants-preview">
                    <span class="variants-label">Warna:</span>
                    <div class="variant-dots-row">
                      @foreach($variantColors->take(4) as $col)
                        @php
                          $cCol = strtolower($col);
                          $bCol = '#94a3b8';
                          if (str_contains($cCol, 'chrome') || str_contains($cCol, 'silver')) $bCol = '#cbd5e1';
                          elseif (str_contains($cCol, 'hitam')) $bCol = '#1e293b';
                          elseif (str_contains($cCol, 'biru')) $bCol = '#3b82f6';
                          elseif (str_contains($cCol, 'pink')) $bCol = '#ec4899';
                        @endphp
                        <span class="mini-dot" style="background: {{ $bCol }};" title="{{ $col }}"></span>
                      @endforeach
                      @if($variantColors->count() > 4)
                        <span class="more-variants">+{{ $variantColors->count() - 4 }}</span>
                      @endif
                    </div>
                  </div>
                @endif

                <div class="card-footer-price">
                  <div class="price-wrap">
                    <span class="price-val">{{ $formattedPrice }}</span>
                  </div>
                  <div class="stock-indicator {{ $product->stock > 5 ? 'in' : ($product->stock > 0 ? 'low' : 'out') }}">
                    <span class="stock-dot"></span>
                    <span class="stock-text">
                      @if($product->stock > 5)
                        Stok {{ $product->stock }}
                      @elseif($product->stock > 0)
                        Sisa {{ $product->stock }}
                      @else
                        Habis
                      @endif
                    </span>
                  </div>
                </div>
              </div>
            </article>
          @empty
            <!-- EMPTY STATE -->
            <div class="catalog-empty-state">
              <div class="empty-icon-wrap">
                <svg viewBox="0 0 24 24">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
              </div>
              <h3 class="empty-title">Tidak Ada Produk yang Cocok</h3>
              <p class="empty-desc">
                Pencarian <strong>"{{ $currentSearch }}"</strong> dengan filter terpilih tidak menghasilkan produk. Cobalah ubah kata kunci atau reset filter.
              </p>
              <div class="empty-actions">
                <a href="{{ url()->current() }}" class="btn-empty-reset">
                  <svg viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                  Tampilkan Semua Produk
                </a>
              </div>
            </div>
          @endforelse
        </div>

        <!-- ====== PAGINATION (Sesuai Wireframe: · ■ ■ ■ ■ ·) ====== -->
        @if($products->hasPages())
          <div class="pagination-wrapper">
            <nav class="wireframe-pagination" aria-label="Navigasi Halaman Produk">
              {{-- Tombol Sebelumnya --}}
              @if($products->onFirstPage())
                <span class="page-arrow disabled" aria-disabled="true">
                  <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </span>
              @else
                <a href="{{ $products->previousPageUrl() }}" class="page-arrow" title="Halaman Sebelumnya">
                  <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </a>
              @endif

              <span class="pagination-dot-sep">·</span>

              {{-- Angka / Blok Halaman --}}
              @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                  <span class="page-block current" aria-current="page">{{ $page }}</span>
                @else
                  <a href="{{ $url }}" class="page-block">{{ $page }}</a>
                @endif
              @endforeach

              <span class="pagination-dot-sep">·</span>

              {{-- Tombol Selanjutnya --}}
              @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="page-arrow" title="Halaman Selanjutnya">
                  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
              @else
                <span class="page-arrow disabled" aria-disabled="true">
                  <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
              @endif
            </nav>
          </div>
        @endif

      </section>
    </div>
  </main>


  <!-- ========================================================================
       5. MODAL QUICK VIEW (Untuk Tinjau Cepat Produk)
       ======================================================================== -->
  <div class="modal-backdrop" id="quickViewBackdrop">
    <div class="quick-view-modal" id="quickViewModal" role="dialog" aria-modal="true">
      <button type="button" class="modal-close-btn" id="modalCloseBtn" aria-label="Tutup Modal">&times;</button>
      <div class="modal-grid">
        <div class="modal-media-col">
          <img src="" alt="" id="modalImg" class="modal-product-img">
        </div>
        <div class="modal-info-col">
          <div class="modal-meta-top">
            <span class="modal-cat-tag" id="modalCategory">Kategori</span>
            <span class="modal-id-tag" id="modalId">#0000</span>
          </div>
          <h2 class="modal-title" id="modalTitle">Nama Produk</h2>
          <div class="modal-price-row">
            <span class="modal-price" id="modalPrice">Rp 0</span>
            <span class="modal-stock-badge" id="modalStock">Tersedia</span>
          </div>
          <p class="modal-desc" id="modalDesc">Deskripsi produk...</p>

          <div class="modal-variants-section" id="modalVariantsWrap">
            <div class="modal-variant-row" id="modalColorRow">
              <span class="variant-title">Pilihan Warna:</span>
              <div class="variant-tags-list" id="modalColorList"></div>
            </div>
            <div class="modal-variant-row" id="modalSizeRow">
              <span class="variant-title">Pilihan Ukuran:</span>
              <div class="variant-tags-list" id="modalSizeList"></div>
            </div>
          </div>

          <div class="modal-actions-row">
            <div class="modal-qty-control">
              <button type="button" class="btn-qty-minus" id="modalQtyMinus">-</button>
              <input type="number" id="modalQtyInput" value="1" min="1" max="99" readonly>
              <button type="button" class="btn-qty-plus" id="modalQtyPlus">+</button>
            </div>
            <button type="button" class="btn-modal-add" id="modalAddToCartBtn">
              <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              <span>Tambah ke Keranjang</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- ========================================================================
       6. SLIDE-OUT KERANJANG BELANJA (Cart Drawer)
       ======================================================================== -->
  <div class="cart-drawer-overlay" id="cartOverlay"></div>
  <aside class="cart-drawer" id="cartDrawer" aria-label="Keranjang Belanja">
    <div class="cart-drawer-header">
      <div class="cart-header-title">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        <span>Keranjang Belanja</span>
      </div>
      <button type="button" class="cart-close-btn" id="cartCloseBtn">&times;</button>
    </div>

    <div class="cart-drawer-body" id="cartItemsList">
      <div class="cart-empty-message">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        <h4>Keranjang Anda Kosong</h4>
        <p>Jelajahi produk di atas dan tambahkan aksesoris Y2K favorit Anda.</p>
      </div>
    </div>

    <div class="cart-drawer-footer">
      <div class="cart-summary-row">
        <span>Subtotal</span>
        <strong id="cartSubtotalText">Rp 0</strong>
      </div>
      <p class="cart-shipping-note">Ongkos kirim & diskon dihitung saat checkout.</p>
      <button type="button" class="btn-checkout-now" id="btnCheckout">
        <span>Lanjut ke Pembayaran</span>
        <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
      </button>
    </div>
  </aside>


  <!-- ========================================================================
       7. TOAST NOTIFIKASI
       ======================================================================== -->
  <div class="toast-notification" id="toastNotification" role="alert">
    <div class="toast-icon">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div>
    <div class="toast-content">
      <strong id="toastTitle">Berhasil Ditambahkan!</strong>
      <p id="toastMessage">Produk telah masuk ke keranjang belanja.</p>
    </div>
  </div>


  <!-- ========================================================================
       8. FOOTER (Sama Seperti Landing Page, Desain Tema Admin & Estetik Y2K)
       ======================================================================== -->
  <footer class="main-footer" id="kontak">
    <div class="footer-container">
      <div class="footer-grid">
        <!-- Col 1: Brand Info -->
        <div class="footer-col brand-col">
          <h3 class="footer-brand-title">Maybead<span>.</span>s</h3>
          <p class="footer-brand-desc">
            Menyajikan aksesoris nostalgia tahun 2000-an otentik berpadu streetwear kontemporer dan estetika cyber krom. Dibuat dengan presisi untuk generasi digital nomads.
          </p>
          <div class="footer-socials">
            <a href="https://instagram.com" target="_blank" rel="noopener" class="footer-soc-link" aria-label="Instagram">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.3" cy="6.7" r=".6"></circle></svg>
              <span>Instagram</span>
            </a>
            <a href="https://tiktok.com" target="_blank" rel="noopener" class="footer-soc-link" aria-label="TikTok">
              <svg viewBox="0 0 24 24"><path d="M14 3v11a3.5 3.5 0 1 1-3.5-3.5M14 3c.3 2.4 1.9 4 4.5 4.2"></path></svg>
              <span>TikTok</span>
            </a>
            <a href="https://wa.me" target="_blank" rel="noopener" class="footer-soc-link" aria-label="WhatsApp">
              <svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
              <span>WhatsApp</span>
            </a>
          </div>
        </div>

        <!-- Col 2: Tautan Cepat -->
        <div class="footer-col">
          <h4 class="footer-col-heading">Tautan Navigasi</h4>
          <ul class="footer-links-list">
            <li><a href="{{ route('home') }}">Beranda Utama</a></li>
            <li><a href="{{ route('main.dashboard') }}">Semua Produk</a></li>
            <li><a href="{{ route('home') }}#produk">Koleksi Pilihan</a></li>
            <li><a href="{{ route('home') }}#kontak">Hubungi Admin</a></li>
          </ul>
        </div>

        <!-- Col 3: Kebijakan & Privasi -->
        <div class="footer-col">
          <h4 class="footer-col-heading">Kebijakan Belanja</h4>
          <ul class="footer-links-list">
            <li><a href="#kebijakan">Kebijakan Privasi</a></li>
            <li><a href="#ketentuan">Ketentuan Layanan</a></li>
            <li><a href="#retur">Pengembalian & Penukaran</a></li>
            <li><a href="#pengiriman">Informasi Ekspedisi</a></li>
          </ul>
        </div>

        <!-- Col 4: Hubungi Kami -->
        <div class="footer-col">
          <h4 class="footer-col-heading">Hubungi Kami</h4>
          <ul class="footer-links-list contact-list">
            <li>
              <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
              <span>maybeads@gmail.com</span>
            </li>
            <li>
              <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
              <span>+62 812-3456-7890</span>
            </li>
            <li>
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              <span>Senin - Sabtu: 09:00 - 21:00 WIB</span>
            </li>
            <li>
              <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
              <span>Jakarta & Seluruh Indonesia</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom-bar">
        <p class="copyright-text">
          &copy; {{ date('Y') }} <strong>Maybead.s</strong>. All rights reserved. Crafted for contemporary cyber nomads.
        </p>
        <div class="footer-badges">
          <span class="safe-badge">✦ SSL Encrypted Safe Checkout</span>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>