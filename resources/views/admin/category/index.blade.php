<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Manajemen Kategori — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/category-index.css', 'resources/js/admin/category-index.js'])
</head>
<body>

@php
  /* Serialisasi data untuk JS */
  $categoriesJson = json_encode($categories ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
@endphp

{{-- Hidden data store untuk JS --}}
<div id="categoryDataStore"
     data-categories="{{ $categoriesJson }}"
     hidden>
</div>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'category'])

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
    <main class="category-content">

      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current">Kategori</span>
      </nav>

      {{-- Success Alert --}}
      @if (session('success'))
        <div class="alert-success" role="alert">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      {{-- Page Heading + Action Row (Tambah Data & Cari Kategori) --}}
      <div class="category-page-head">
        <h1 class="category-page-title">Manajemen Kategori</h1>

        <div class="category-head-actions">
          {{-- Tambah Data Button --}}
          <a href="{{ route('admin.category.create') }}" class="btn-add-category" id="btnAddCategory">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Data
          </a>

          {{-- Cari Kategori Input --}}
          <div class="category-search-wrap">
            <span class="search-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </span>
            <input type="text"
                   id="categorySearch"
                   class="category-search-input"
                   placeholder="Cari Kategori"
                   autocomplete="off"
                   aria-label="Cari kategori berdasarkan nama atau ID">
          </div>
        </div>
      </div>

      {{-- ====== TABLE LAYOUT ====== --}}
      <div class="category-table-wrap" id="categoryTableWrap">
        <table class="category-table" id="categoryTable">
          <thead>
            <tr>
              <th class="cb-col">
                <input type="checkbox" class="tbl-check" id="selectAll" aria-label="Pilih semua">
              </th>
              <th>ID</th>
              <th>Kategori</th>
              <th>Total Produk</th>
              <th>Stok</th>
              <th style="text-align: right; padding-right: 24px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="categoryTableBody">
            @forelse ($categories as $cat)
              <tr data-id="{{ $cat['id'] }}">
                <td class="cb-col">
                  <input type="checkbox" class="tbl-check" aria-label="Pilih {{ $cat['category_name'] }}">
                </td>
                <td class="col-id">{{ $cat['id'] }}</td>
                <td class="col-name">{{ $cat['category_name'] }}</td>
                <td class="col-count">{{ $cat['products_count'] }}</td>
                <td class="col-stock">{{ $cat['products_stock'] }}</td>
                <td class="col-action">
                  <div class="action-btns">
                    <a href="{{ route('admin.category.show', $cat['id']) }}" class="btn-detail">Detail</a>
                    <a href="{{ route('admin.category.edit', $cat['id']) }}" class="btn-icon-action" title="Edit">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                      </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.category.destroy', $cat['id']) }}" class="delete-form" style="display:inline">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn-icon-action danger" data-delete-id="{{ $cat['id'] }}" title="Hapus">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6l-1 14H6L5 6"></path>
                          <path d="M10 11v6"></path>
                          <path d="M14 11v6"></path>
                          <path d="M9 6V4h6v2"></path>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px">
                  Belum ada kategori yang ditambahkan.
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
            <span class="pagination-info">Menampilkan {{ count($categories) > 0 ? '1–' . min(8, count($categories)) : '0' }} kategori dari {{ count($categories) }}</span>
          </div>
          <div class="pagination-pages" id="paginationPages" aria-label="Navigasi halaman">
            {{-- Dirender oleh JS --}}
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

</body>
</html>
