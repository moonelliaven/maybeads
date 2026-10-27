<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Manajemen Produk — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/product-index.css', 'resources/js/admin/product-index.js'])
</head>
<body>

@php
  /* Serialisasi data untuk JS */
  $productsJson   = json_encode($products ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
  $categoriesJson = json_encode($categories ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
@endphp

{{-- Hidden data store untuk JS --}}
<div id="productDataStore"
     data-products="{{ $productsJson }}"
     data-categories="{{ $categoriesJson }}"
     hidden>
</div>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'product'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    {{-- ====== HEADER ====== --}}
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle"
                aria-label="Buka atau tutup navigasi" aria-controls="adminSidebar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard</span>
      </div>

      <div class="header-right">
        {{-- Notifikasi --}}
        <div class="dropdown" data-dropdown>
          <button type="button" class="notification-btn"
                  aria-label="Notifikasi" aria-haspopup="true" aria-expanded="false"
                  data-dropdown-trigger>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notification-badge" data-notif-badge>1</span>
          </button>
          <div class="dropdown-menu dropdown-menu-wide" role="menu" hidden>
            <div class="dropdown-head">
              <strong>Notifikasi</strong>
              <button type="button" class="link-btn" data-mark-read>Tandai dibaca</button>
            </div>
            <a href="/admin/orders?status=pending" class="notif-item" role="menuitem">
              <span class="status-dot orange"></span>
              <span>
                <span class="notif-title">Ada pesanan menunggu diproses</span>
                <span class="notif-sub">Periksa dan konfirmasi pesanan pending</span>
              </span>
            </a>
          </div>
        </div>

        {{-- Profil --}}
        <div class="dropdown" data-dropdown>
          <button type="button" class="header-user-badge"
                  aria-haspopup="true" aria-expanded="false" data-dropdown-trigger>
            <div class="header-user-avatar">
              {{ strtoupper(substr(Auth::user()->name ?? 'W', 0, 1)) }}
            </div>
            <div class="header-user-info">
              <span class="header-user-name">{{ Auth::user()->name ?? 'M. Waiz Fadhillah' }}</span>
              <span class="header-user-role">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
            </div>
            <div class="header-user-chevron">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </div>
          </button>
          <div class="dropdown-menu" role="menu" hidden>
            <a href="/" target="_blank" rel="noopener" class="dropdown-item" role="menuitem">Lihat toko</a>
            <form method="POST" action="/logout">
              @csrf
              <button type="submit" class="dropdown-item danger" role="menuitem">Keluar</button>
            </form>
          </div>
        </div>
      </div>
    </header>

    {{-- ====== MAIN CONTENT ====== --}}
    <main class="product-content">

      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current">Produk</span>
      </nav>

      {{-- Page heading + search --}}
      <div class="product-page-head">
        <h1 class="product-page-title">Manajemen Produk</h1>

        <div class="product-search-wrap">
          <span class="search-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </span>
          <input type="text"
                 id="productSearch"
                 class="product-search-input"
                 placeholder="Cari Produk"
                 autocomplete="off"
                 aria-label="Cari produk berdasarkan nama atau kategori">
        </div>
      </div>

      {{-- ====== TOOLBAR: Sortir + layout toggle + tambah ====== --}}
      <div class="product-toolbar">
        <span class="sortir-label">Sortir:</span>

        {{-- Category tabs (max 6 visible) --}}
        <div class="category-tabs" id="categoryTabs" role="tablist" aria-label="Filter kategori">
          {{-- Dirender oleh JS --}}
        </div>

        {{-- More categories dropdown (hidden jika ≤6 kategori) --}}
        <div class="cat-more-wrap" id="catMoreWrap" hidden>
          <button type="button" class="cat-more-btn" id="catMoreBtn" aria-haspopup="true" aria-expanded="false">
            Lainnya
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>
          <div class="cat-dropdown" id="catDropdown" hidden>
            <input type="text" class="cat-dropdown-search" id="catDropdownSearch"
                   placeholder="Cari kategori…" autocomplete="off">
            <div class="cat-dropdown-list" id="catDropdownList"></div>
          </div>
        </div>

        {{-- Layout toggle buttons --}}
        <div class="layout-toggle">
          {{-- Grid / Card --}}
          <button type="button" class="layout-btn" id="layoutGrid"
                  title="Tampilan kartu" aria-label="Tampilan kartu" aria-pressed="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="1"></rect>
              <rect x="14" y="3" width="7" height="7" rx="1"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1"></rect>
              <rect x="14" y="14" width="7" height="7" rx="1"></rect>
            </svg>
          </button>
          {{-- Table --}}
          <button type="button" class="layout-btn active" id="layoutTable"
                  title="Tampilan tabel" aria-label="Tampilan tabel" aria-pressed="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2"></rect>
              <line x1="3" y1="9" x2="21" y2="9"></line>
              <line x1="3" y1="15" x2="21" y2="15"></line>
              <line x1="9" y1="9" x2="9" y2="21"></line>
            </svg>
          </button>
        </div>

        {{-- Tambah produk --}}
        <a href="{{ route('admin.product.create') }}" class="btn-add-product" id="btnAddProduct">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          Tambah Produk
        </a>
      </div>

      {{-- ====== TABLE LAYOUT (default) ====== --}}
      <div class="product-table-wrap" id="productTableWrap">
        <table class="product-table" id="productTable">
          <thead>
            <tr>
              <th class="cb-col">
                <input type="checkbox" class="tbl-check" id="selectAll" aria-label="Pilih semua">
              </th>
              <th>ID</th>
              <th>Produk</th>
              <th>Kategori</th>
              <th>Foto</th>
              <th>Stok</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="productTableBody">
            {{-- Dirender oleh JS --}}
            <tr>
              <td colspan="7" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px">
                Memuat data…
              </td>
            </tr>
          </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination-bar" id="paginationBar">
          <div class="pagination-left">
            <button type="button" class="btn-all" id="btnPagAll">ALL</button>
            <button type="button" class="btn-show-less" id="btnPagLess">Show Less</button>
            <div class="pagination-sep"></div>
            <span class="pagination-info">Menampilkan — produk</span>
          </div>
          <div class="pagination-pages" id="paginationPages" aria-label="Navigasi halaman">
            {{-- Dirender oleh JS --}}
          </div>
        </div>
      </div>

      {{-- ====== GRID / CARD LAYOUT (hidden by default) ====== --}}
      <div class="product-grid" id="productGrid" hidden>
        {{-- Dirender oleh JS --}}
      </div>

    </main>
  </div>
</div>

</body>
</html>