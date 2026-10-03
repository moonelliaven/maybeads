<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Manajemen Produk — Admin Maybead.s</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/lenis.css', 'resources/css/admin/dashboard.css', 'resources/css/admin/product.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

  <div class="admin-layout">
    @include('admin.sidebar', ['active' => 'product'])

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="admin-main">
      <!-- Header -->
      <header class="admin-header">
        <div class="header-left">
          <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka atau tutup navigasi" aria-controls="adminSidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
          <span class="header-title">Admin Dashboard</span>
        </div>

        <div class="header-right">
          <!-- Notifikasi -->
          <div class="dropdown" data-dropdown>
            <button type="button" class="notification-btn" aria-label="Notifikasi" aria-haspopup="true" aria-expanded="false" data-dropdown-trigger>
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
              <a href="/admin/product" class="notif-item" role="menuitem">
                <span class="status-dot green"></span>
                <span>
                  <span class="notif-title">{{ $totalProducts }} Produk aktif</span>
                  <span class="notif-sub">Katalog produk manik Maybead.s tersimpan di database</span>
                </span>
              </a>
            </div>
          </div>

          <!-- Profil Pengguna -->
          <div class="dropdown" data-dropdown>
            <button type="button" class="header-user-badge" aria-haspopup="true" aria-expanded="false" data-dropdown-trigger>
              <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}</div>
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
              <a href="/" target="_blank" rel="noopener" class="dropdown-item" role="menuitem">Lihat Toko</a>
              <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="dropdown-item danger" role="menuitem">Keluar</button>
              </form>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="dashboard-content" style="padding: 0;">
        <div class="product-management-wrapper">
          
          <!-- Breadcrumb -->
          <nav class="product-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-separator">&gt;</span>
            <span class="breadcrumb-current">Produk</span>
          </nav>

          <!-- Title and Search Row -->
          <div class="product-header-row">
            <h1 class="product-page-title">Manajemen Produk</h1>

            <!-- Search Bar -->
            <div class="product-search-wrapper">
              <form method="GET" action="{{ route('admin.product.index') }}" class="product-search-form" id="productSearchForm">
                @if(request('category') && request('category') !== 'Semua')
                  <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('per_page'))
                  <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input 
                  type="text" 
                  name="search" 
                  id="searchInput" 
                  class="product-search-input" 
                  placeholder="Cari Produk" 
                  value="{{ $search ?? '' }}"
                  autocomplete="off"
                >
                @if(!empty($search))
                  <a href="{{ route('admin.product.index', array_filter(['category' => request('category'), 'per_page' => request('per_page')])) }}" class="search-clear-btn" title="Hapus pencarian">&times;</a>
                @endif
              </form>
            </div>
          </div>

          <!-- Filter & Action Toolbar -->
          <div class="product-toolbar-row">
            <!-- Filter Tabs -->
            <div class="product-filter-group">
              <span class="filter-label">Sortir:</span>
              <ul class="filter-tabs-list" role="tablist">
                @php
                  $currentCategory = $categoryFilter ?? request('category', 'Semua');
                  $categoriesList = ['Semua', 'Kalung', 'Anting', 'Gelang', 'Cincin', 'Aksesoris Rambut'];
                @endphp

                @foreach($categoriesList as $cat)
                  @php
                    $isActive = ($currentCategory === $cat) || ($cat === 'Semua' && (empty($currentCategory) || $currentCategory === 'Semua'));
                    $queryParam = array_filter([
                      'category' => $cat === 'Semua' ? null : $cat,
                      'search' => request('search'),
                      'per_page' => request('per_page')
                    ]);
                  @endphp
                  <li>
                    <a 
                      href="{{ route('admin.product.index', $queryParam) }}" 
                      class="filter-tab-link {{ $isActive ? 'active' : '' }}"
                      role="tab"
                      aria-selected="{{ $isActive ? 'true' : 'false' }}"
                    >
                      {{ $cat }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>

            <!-- Toolbar Actions: View Toggle + Add Product -->
            <div class="product-actions-group">
              <!-- Grid / Table View Toggles -->
              <div class="view-mode-buttons" role="group" aria-label="Tampilan">
                <button type="button" class="btn-view-mode" id="btnViewGrid" title="Tampilan Grid">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                  </svg>
                </button>
                <button type="button" class="btn-view-mode active" id="btnViewTable" title="Tampilan Tabel">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                    <line x1="3" y1="9" x2="21" y2="9"></line>
                    <line x1="3" y1="15" x2="21" y2="15"></line>
                    <line x1="9" y1="3" x2="9" y2="21"></line>
                  </svg>
                </button>
              </div>

              <!-- Button Tambah Produk -->
              <a href="{{ route('admin.product.create') }}" class="btn-add-product">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Produk</span>
              </a>
            </div>
          </div>

          <!-- Product Table Card -->
          <div class="product-table-card" id="tableViewContainer">
            <div class="table-responsive">
              <table class="product-data-table" id="productDataTable">
                <thead>
                  <tr>
                    <th class="col-checkbox">
                      <label class="custom-checkbox-label" title="Pilih Semua">
                        <input type="checkbox" class="custom-checkbox thead-checkbox" id="selectAllCheckbox">
                      </label>
                    </th>
                    <th class="col-id">ID</th>
                    <th class="col-product">Produk</th>
                    <th class="col-category">Kategori</th>
                    <th class="col-photo">Foto</th>
                    <th class="col-stock">Stok</th>
                    <th class="col-action">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($products as $product)
                    <tr data-product-id="{{ $product->id }}">
                      <td class="col-checkbox">
                        <label class="custom-checkbox-label">
                          <input type="checkbox" class="custom-checkbox row-checkbox" value="{{ $product->id }}">
                        </label>
                      </td>
                      <td class="col-id">{{ $product->id }}</td>
                      <td class="col-product">{{ $product->product_name }}</td>
                      <td class="col-category">{{ $product->category->category_name ?? '-' }}</td>
                      <td class="col-photo">
                        <div class="photo-box" title="{{ $product->product_name }}">
                          @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->product_name }}" loading="lazy">
                          @else
                            <svg class="placeholder-bead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                              <circle cx="12" cy="12" r="8"></circle>
                              <path d="M12 2v4"></path>
                              <path d="M12 18v4"></path>
                              <path d="M4.93 4.93l2.83 2.83"></path>
                              <path d="M16.24 16.24l2.83 2.83"></path>
                            </svg>
                          @endif
                        </div>
                      </td>
                      <td class="col-stock">{{ $product->stock }}</td>
                      <td class="col-action">
                        <div class="action-buttons-wrap">
                          <!-- Detail Button -->
                          <a 
                            href="{{ route('admin.product.show', $product->id) }}" 
                            class="btn-table-detail"
                          >
                            Detail
                          </a>

                          <!-- Edit Button -->
                          <a 
                            href="{{ route('admin.product.edit', $product->id) }}" 
                            class="btn-table-icon btn-edit" 
                            title="Edit Produk"
                          >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                          </a>

                          <!-- Delete Button -->
                          <button 
                            type="button" 
                            class="btn-table-icon btn-delete" 
                            title="Hapus Produk"
                            data-btn-delete
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->product_name }}"
                          >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="3 6 5 6 21 6"></polyline>
                              <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                              <line x1="10" y1="11" x2="10" y2="17"></line>
                              <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7">
                        <div class="table-empty-state">
                          <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                          </svg>
                          <h3 class="empty-state-title">Tidak ada produk ditemukan</h3>
                          <p class="empty-state-desc">
                            @if(!empty($search))
                              Pencarian untuk "<strong>{{ $search }}</strong>" tidak menghasilkan produk. Coba kata kunci lain atau reset filter.
                            @else
                              Belum ada data produk pada kategori ini.
                            @endif
                          </p>
                          <a href="{{ route('admin.product.index') }}" class="btn-secondary" style="display: inline-block;">Reset Filter</a>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <!-- Alternative Grid View -->
            <div class="products-grid-view" id="gridViewContainer" style="display: none;">
              @forelse($products as $product)
                <div class="product-card-item">
                  <div class="product-card-thumb">
                    @if($product->image && file_exists(public_path('images/products/' . $product->image)))
                      <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->product_name }}" loading="lazy">
                    @else
                      <svg class="placeholder-bead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width: 42px; height: 42px;">
                        <circle cx="12" cy="12" r="8"></circle>
                        <path d="M12 2v4"></path>
                        <path d="M12 18v4"></path>
                        <path d="M4.93 4.93l2.83 2.83"></path>
                        <path d="M16.24 16.24l2.83 2.83"></path>
                      </svg>
                    @endif
                  </div>
                  <div class="product-card-body">
                    <span class="card-category-tag">{{ $product->category->category_name ?? '-' }}</span>
                    <h4 class="card-product-name">{{ $product->product_name }}</h4>
                    <div class="card-product-price">Rp {{ $product->price }}</div>
                    <div class="card-product-stock">Stok: <strong>{{ $product->stock }}</strong></div>
                    <div class="card-product-actions">
                      <a 
                        href="{{ route('admin.product.show', $product->id) }}" 
                        class="btn-table-detail"
                      >
                        Detail
                      </a>
                      <div class="action-buttons-wrap">
                        <a 
                          href="{{ route('admin.product.edit', $product->id) }}" 
                          class="btn-table-icon btn-edit" 
                          title="Edit Produk"
                        >
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                          </svg>
                        </a>
                        <button 
                          type="button" 
                          class="btn-table-icon btn-delete" 
                          data-btn-delete
                          data-id="{{ $product->id }}"
                          data-name="{{ $product->product_name }}"
                        >
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div style="grid-column: 1 / -1;">
                  <div class="table-empty-state">
                    <h3 class="empty-state-title">Tidak ada produk ditemukan</h3>
                    <p class="empty-state-desc">Belum ada produk untuk kriteria filter ini.</p>
                  </div>
                </div>
              @endforelse
            </div>

            <!-- Table Footer & Pagination -->
            <div class="product-table-footer">
              <!-- Left side: ALL button, Show Less, separator, count -->
              <div class="footer-info-group">
                <a 
                  href="{{ route('admin.product.index', array_filter(['category' => request('category'), 'search' => request('search'), 'per_page' => 'all'])) }}" 
                  class="badge-all {{ request('per_page') === 'all' ? 'active' : '' }}"
                  title="Tampilkan semua produk tanpa paginasi"
                >
                  ALL
                </a>

                @if(request('per_page') === 'all')
                  <a 
                    href="{{ route('admin.product.index', array_filter(['category' => request('category'), 'search' => request('search')])) }}" 
                    class="btn-show-less"
                  >
                    Show Less
                  </a>
                @else
                  <span class="btn-show-less" style="color: #94a3b8; cursor: default;">Show Less</span>
                @endif

                <span class="footer-separator">|</span>

                <span class="product-count-text">
                  Menampilkan {{ $products->count() }} produk dari {{ $filteredCount }}
                </span>
              </div>

              <!-- Right side: Pagination -->
              <div class="pagination-controls">
                @if ($products->hasPages())
                  {{-- Previous Page Link --}}
                  @if ($products->onFirstPage())
                    <span class="page-btn disabled" aria-disabled="true">&lt;</span>
                  @else
                    <a href="{{ $products->previousPageUrl() }}" class="page-btn" rel="prev">&lt;</a>
                  @endif

                  {{-- Pagination Elements --}}
                  @foreach ($products->links()->elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                      <span class="page-ellipsis">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                      @foreach ($element as $page => $url)
                        @if ($page == $products->currentPage())
                          <span class="page-btn active" aria-current="page">{{ $page }}</span>
                        @else
                          <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                      @endforeach
                    @endif
                  @endforeach

                  {{-- Next Page Link --}}
                  @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="page-btn" rel="next">&gt;</a>
                  @else
                    <span class="page-btn disabled" aria-disabled="true">&gt;</span>
                  @endif
                @else
                  <span class="page-btn disabled">&lt;</span>
                  <span class="page-btn active">1</span>
                  <span class="page-btn disabled">&gt;</span>
                @endif
              </div>
            </div>

          </div>

        </div>
      </main>
    </div>
  </div>

  <!-- =========================================================================
       MODAL 1: Tambah Produk
       ========================================================================= -->
  <div class="modal-backdrop" id="modalAddProduct" role="dialog" aria-modal="true" aria-labelledby="modalAddTitle">
    <div class="modal-dialog">
      <div class="modal-header">
        <h3 class="modal-title" id="modalAddTitle">Tambah Produk Baru</h3>
        <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
      </div>
      <form action="{{ route('admin.product.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label" for="addProductName">Nama Produk *</label>
            <input type="text" name="product_name" id="addProductName" class="form-control" placeholder="Contoh: Daisy Pearl Necklace" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="addCategoryId">Kategori *</label>
              <select name="category_id" id="addCategoryId" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="addPrice">Harga (Rp) *</label>
              <input type="text" name="price" id="addPrice" class="form-control" placeholder="Contoh: 45.000" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="addStock">Jumlah Stok *</label>
              <input type="number" name="stock" id="addStock" class="form-control" placeholder="0" min="0" required>
            </div>

            <div class="form-group">
              <label class="form-label" for="addImage">Nama File Foto (Opsional)</label>
              <input type="text" name="image" id="addImage" class="form-control" placeholder="Contoh: bead-01.jpg" maxlength="20">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="addDescription">Deskripsi Produk</label>
            <textarea name="description" id="addDescription" class="form-control" placeholder="Keterangan bahan manik, ukuran, dll..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-secondary" data-modal-close>Batal</button>
          <button type="submit" class="btn-primary">Simpan Produk</button>
        </div>
      </form>
    </div>
  </div>

  <!-- =========================================================================
       MODAL 2: Detail Produk
       ========================================================================= -->
  <div class="modal-backdrop" id="modalDetailProduct" role="dialog" aria-modal="true" aria-labelledby="modalDetailTitle">
    <div class="modal-dialog">
      <div class="modal-header">
        <h3 class="modal-title" id="modalDetailTitle">Detail Produk</h3>
        <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
      </div>
      <div class="modal-body">
        <div class="detail-preview-header">
          <div class="detail-thumb-large" id="detailThumbBox">
            <svg class="placeholder-bead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="width: 36px; height: 36px; color: #64748b;">
              <circle cx="12" cy="12" r="8"></circle>
              <path d="M12 2v4"></path>
              <path d="M12 18v4"></path>
              <path d="M4.93 4.93l2.83 2.83"></path>
              <path d="M16.24 16.24l2.83 2.83"></path>
            </svg>
          </div>
          <div>
            <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 4px;" id="detailProductName">-</h4>
            <span style="font-size: 12px; font-weight: 600; color: #2563eb; background: #e0e7ff; padding: 3px 10px; border-radius: 9999px; text-transform: uppercase;" id="detailProductCategory">-</span>
          </div>
        </div>

        <div class="detail-meta-list">
          <div class="detail-meta-item">
            <div class="detail-meta-label">ID Produk</div>
            <div class="detail-meta-val" id="detailProductId">-</div>
          </div>
          <div class="detail-meta-item">
            <div class="detail-meta-label">Harga</div>
            <div class="detail-meta-val" id="detailProductPrice">-</div>
          </div>
          <div class="detail-meta-item">
            <div class="detail-meta-label">Stok Tersedia</div>
            <div class="detail-meta-val" id="detailProductStock">-</div>
          </div>
          <div class="detail-meta-item">
            <div class="detail-meta-label">Dibuat Pada</div>
            <div class="detail-meta-val" id="detailProductCreated" style="font-size: 13.5px; font-weight: 500;">-</div>
          </div>
        </div>

        <div class="detail-description-box">
          <div class="detail-meta-label" style="margin-bottom: 6px;">Deskripsi</div>
          <p class="detail-desc-text" id="detailProductDesc">-</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" data-modal-close>Tutup</button>
      </div>
    </div>
  </div>

  <!-- =========================================================================
       MODAL 3: Edit Produk
       ========================================================================= -->
  <div class="modal-backdrop" id="modalEditProduct" role="dialog" aria-modal="true" aria-labelledby="modalEditTitle">
    <div class="modal-dialog">
      <div class="modal-header">
        <h3 class="modal-title" id="modalEditTitle">Edit Produk</h3>
        <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
      </div>
      <form id="editProductForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label" for="editProductName">Nama Produk *</label>
            <input type="text" name="product_name" id="editProductName" class="form-control" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="editCategoryId">Kategori *</label>
              <select name="category_id" id="editCategoryId" class="form-control" required>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="editPrice">Harga (Rp) *</label>
              <input type="text" name="price" id="editPrice" class="form-control" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="editStock">Jumlah Stok *</label>
              <input type="number" name="stock" id="editStock" class="form-control" min="0" required>
            </div>

            <div class="form-group">
              <label class="form-label" for="editImage">Nama File Foto (Opsional)</label>
              <input type="text" name="image" id="editImage" class="form-control" maxlength="20">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="editDescription">Deskripsi Produk</label>
            <textarea name="description" id="editDescription" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-secondary" data-modal-close>Batal</button>
          <button type="submit" class="btn-primary">Perbarui Produk</button>
        </div>
      </form>
    </div>
  </div>

  <!-- =========================================================================
       MODAL 4: Hapus Produk Confirmation
       ========================================================================= -->
  <div class="modal-backdrop" id="modalDeleteProduct" role="dialog" aria-modal="true" aria-labelledby="modalDeleteTitle">
    <div class="modal-dialog" style="max-width: 440px;">
      <div class="modal-header">
        <h3 class="modal-title" id="modalDeleteTitle" style="color: #dc2626;">Hapus Produk</h3>
        <button type="button" class="modal-close-btn" data-modal-close>&times;</button>
      </div>
      <form id="deleteProductForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p style="font-size: 14.5px; color: #475569; line-height: 1.5;">
            Apakah Anda yakin ingin menghapus produk <strong id="deleteProductName" style="color: #0f172a;"></strong>?
          </p>
          <p style="font-size: 13px; color: #94a3b8; margin-top: 8px;">
            Tindakan ini tidak dapat dibatalkan dan produk akan dihapus secara permanen dari database.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-secondary" data-modal-close>Batal</button>
          <button type="submit" class="btn-danger">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript Interactivity for Product Management -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // 1. Select All Checkbox
      const selectAll = document.getElementById('selectAllCheckbox');
      const rowCheckboxes = document.querySelectorAll('.row-checkbox');

      if (selectAll) {
        selectAll.addEventListener('change', function () {
          rowCheckboxes.forEach(cb => {
            cb.checked = selectAll.checked;
          });
        });

        rowCheckboxes.forEach(cb => {
          cb.addEventListener('change', function () {
            const allChecked = Array.from(rowCheckboxes).every(c => c.checked);
            const someChecked = Array.from(rowCheckboxes).some(c => c.checked);
            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
          });
        });
      }

      // 2. View Mode Toggle (Table vs Grid)
      const btnViewGrid = document.getElementById('btnViewGrid');
      const btnViewTable = document.getElementById('btnViewTable');
      const tableViewContainer = document.querySelector('.table-responsive');
      const gridViewContainer = document.getElementById('gridViewContainer');

      const savedMode = localStorage.getItem('maybeads_product_view') || 'table';
      setViewMode(savedMode);

      if (btnViewGrid && btnViewTable) {
        btnViewGrid.addEventListener('click', function () {
          setViewMode('grid');
          localStorage.setItem('maybeads_product_view', 'grid');
        });

        btnViewTable.addEventListener('click', function () {
          setViewMode('table');
          localStorage.setItem('maybeads_product_view', 'table');
        });
      }

      function setViewMode(mode) {
        if (mode === 'grid') {
          if (tableViewContainer) tableViewContainer.style.display = 'none';
          if (gridViewContainer) gridViewContainer.style.display = 'grid';
          if (btnViewGrid) btnViewGrid.classList.add('active');
          if (btnViewTable) btnViewTable.classList.remove('active');
        } else {
          if (tableViewContainer) tableViewContainer.style.display = 'block';
          if (gridViewContainer) gridViewContainer.style.display = 'none';
          if (btnViewTable) btnViewTable.classList.add('active');
          if (btnViewGrid) btnViewGrid.classList.remove('active');
        }
      }

      // 3. Modals Management
      function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.add('open');
          document.body.style.overflow = 'hidden';
        }
      }

      function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
          modal.classList.remove('open');
          document.body.style.overflow = '';
        }
      }

      // Close modal on backdrop click or close buttons
      document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', function (e) {
          if (e.target === modal) {
            modal.classList.remove('open');
            document.body.style.overflow = '';
          }
        });
      });

      document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', function () {
          const modal = btn.closest('.modal-backdrop');
          if (modal) {
            modal.classList.remove('open');
            document.body.style.overflow = '';
          }
        });
      });

      // Tambah Produk
      const btnOpenAddModal = document.getElementById('btnOpenAddModal');
      if (btnOpenAddModal) {
        btnOpenAddModal.addEventListener('click', function () {
          openModal('modalAddProduct');
        });
      }

      // Check if URL has ?action=create
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('action') === 'create') {
        openModal('modalAddProduct');
      }

      // Detail Produk
      document.querySelectorAll('[data-btn-detail]').forEach(btn => {
        btn.addEventListener('click', function () {
          document.getElementById('detailProductId').textContent = '#' + btn.getAttribute('data-id');
          document.getElementById('detailProductName').textContent = btn.getAttribute('data-name');
          document.getElementById('detailProductCategory').textContent = btn.getAttribute('data-category');
          document.getElementById('detailProductPrice').textContent = 'Rp ' + btn.getAttribute('data-price');
          document.getElementById('detailProductStock').textContent = btn.getAttribute('data-stock') + ' pcs';
          document.getElementById('detailProductCreated').textContent = btn.getAttribute('data-created');
          document.getElementById('detailProductDesc').textContent = btn.getAttribute('data-description');

          openModal('modalDetailProduct');
        });
      });

      // Edit Produk
      document.querySelectorAll('[data-btn-edit]').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = btn.getAttribute('data-id');
          const name = btn.getAttribute('data-name');
          const categoryId = btn.getAttribute('data-category-id');
          const price = btn.getAttribute('data-price');
          const stock = btn.getAttribute('data-stock');
          const desc = btn.getAttribute('data-description');
          const img = btn.getAttribute('data-image');

          const form = document.getElementById('editProductForm');
          form.action = '/admin/product/' + id;

          document.getElementById('editProductName').value = name;
          document.getElementById('editCategoryId').value = categoryId;
          document.getElementById('editPrice').value = price;
          document.getElementById('editStock').value = stock;
          document.getElementById('editDescription').value = desc;
          document.getElementById('editImage').value = img || '';

          openModal('modalEditProduct');
        });
      });

      // Delete Produk
      document.querySelectorAll('[data-btn-delete]').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = btn.getAttribute('data-id');
          const name = btn.getAttribute('data-name');

          const form = document.getElementById('deleteProductForm');
          form.action = '/admin/product/' + id;
          document.getElementById('deleteProductName').textContent = name;

          openModal('modalDeleteProduct');
        });
      });

      // SweetAlert Notification for session success
      @if(session('success'))
        if (window.Swal) {
          window.Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
          });
        }
      @endif
    });
  </script>
</body>
</html>
