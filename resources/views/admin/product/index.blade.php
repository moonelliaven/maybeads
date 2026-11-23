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

{{-- Data store untuk JS --}}
<div id="productDataStore"
     data-products='@json($products)'
     data-categories='@json($categories)'
     hidden>
</div>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'product'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    {{-- ====== HEADER ====== --}}
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigasi" aria-controls="adminSidebar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard</span>
      </div>

      <div class="header-right">
        @php
          $sidebarPendingOrders = \App\Models\Order::whereRaw('LOWER(order_status) = ?', ['pending'])->count();
        @endphp
        <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          @if($sidebarPendingOrders > 0)
            <span class="notification-badge">{{ $sidebarPendingOrders }}</span>
          @endif
        </a>
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

      {{-- Success Alert (SweetAlert2 - Top Center No Backdrop) --}}
      @if (session('success'))
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                position: 'top',
                backdrop: false,
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2500,
                customClass: { popup: 'admin-swal-popup' }
              });
            }
          });
        </script>
      @endif

      @if (session('error'))
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                position: 'top',
                backdrop: false,
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonColor: '#23477d',
                customClass: { popup: 'admin-swal-popup' }
              });
            }
          });
        </script>
      @endif

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

        {{-- Category tabs slider with mini arrows (max 5 visible at once) --}}
        <div class="category-slider-wrapper" id="catSliderWrapper">
          <button type="button" class="cat-nav-arrow" id="catNavPrev" title="Kategori sebelumnya" aria-label="Geser kiri">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>

          <div class="category-tabs-scroll" id="categoryTabsScroll">
            <div class="category-tabs" id="categoryTabs" role="tablist" aria-label="Filter kategori">
              {{-- Dirender secara dinamis oleh JS --}}
            </div>
          </div>

          <button type="button" class="cat-nav-arrow" id="catNavNext" title="Kategori berikutnya" aria-label="Geser kanan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </button>
        </div>

        {{-- More categories dropdown --}}
        <div class="cat-more-wrap" id="catMoreWrap" hidden>
          <button type="button" class="cat-more-btn" id="catMoreBtn" aria-haspopup="true" aria-expanded="false">
            Lainnya
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>
          <div class="cat-dropdown" id="catDropdown" hidden>
            <input type="text" class="cat-dropdown-search" id="catDropdownSearch" placeholder="Cari kategori…" autocomplete="off">
            <div class="cat-dropdown-list" id="catDropdownList"></div>
          </div>
        </div>

        {{-- Layout toggle buttons --}}
        <div class="layout-toggle">
          <button type="button" class="layout-btn" id="layoutGrid" title="Tampilan kartu" aria-label="Tampilan kartu" aria-pressed="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7" rx="1"></rect>
              <rect x="14" y="3" width="7" height="7" rx="1"></rect>
              <rect x="3" y="14" width="7" height="7" rx="1"></rect>
              <rect x="14" y="14" width="7" height="7" rx="1"></rect>
            </svg>
          </button>
          <button type="button" class="layout-btn active" id="layoutTable" title="Tampilan tabel" aria-label="Tampilan tabel" aria-pressed="true">
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
              <th>ID</th>
              <th>Produk</th>
              <th>Kategori</th>
              <th>Foto</th>
              <th>Stok</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="productTableBody">
            @forelse ($products as $p)
              <tr data-id="{{ $p->id }}">
                <td class="col-id">{{ $p->id }}</td>
                <td class="col-name">{{ $p->product_name }}</td>
                <td class="col-category">
                  <span class="cat-badge">{{ $p->category_name }}</span>
                </td>
                <td class="col-photo">
                  @php
                    $imgSrc = $p->image ? (Str::startsWith($p->image, ['http://', 'https://', '/']) ? $p->image : asset('storage/product/' . $p->image)) : null;
                  @endphp
                  @if ($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $p->product_name }}" class="product-thumb" loading="lazy">
                  @else
                    <div class="product-thumb-placeholder">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                  @endif
                </td>
                <td class="col-status">
                  @php
                    $isActive = !($p->status === false || $p->status === 0 || $p->status === 'tidak aktif' || $p->status === '0');
                  @endphp
                  <span class="status-indicator {{ $isActive ? 'active' : 'inactive' }}">
                    <span class="status-dot"></span>
                    <span>{{ $isActive ? 'Aktif' : 'Tidak Aktif' }}</span>
                  </span>
                </td>
                <td class="col-action">
                  <div class="action-btns">
                    <a href="{{ route('admin.product.show', $p->id) }}" class="btn-detail">Detail</a>
                    <a href="{{ route('admin.product.edit', $p->id) }}" class="btn-icon-action" title="Edit">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.product.destroy', $p->id) }}" class="delete-form" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn-icon-action danger" data-delete-id="{{ $p->id }}" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <polyline points="3 6 5 6 21 6"/>
                          <path d="M19 6l-1 14H6L5 6"/>
                          <path d="M10 11v6"/>
                          <path d="M14 11v6"/>
                          <path d="M9 6V4h6v2"/>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px">
                  Belum ada produk yang ditambahkan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination-bar" id="paginationBar">
          <div class="pagination-left">
            <button type="button" class="btn-all" id="btnPagAll">ALL</button>
            <button type="button" class="btn-show-less" id="btnPagLess">Show Less</button>
            <div class="pagination-sep"></div>
            <span class="pagination-info">Menampilkan {{ count($products) > 0 ? '1–' . min(8, count($products)) : '0' }} produk dari {{ count($products) }}</span>
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