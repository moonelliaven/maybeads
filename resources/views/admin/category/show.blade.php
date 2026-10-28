<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Kategori — {{ $category->category_name }}</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/product-form.css', 'resources/js/admin/product-form.js'])
  <style>
    .cat-stat-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }
    .cat-stat-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 16px;
    }
    .cat-stat-label {
      font-size: 12px;
      font-weight: 600;
      color: #64748b;
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }
    .cat-stat-value {
      font-size: 24px;
      font-weight: 800;
      color: #0f172a;
      font-variant-numeric: tabular-nums;
    }
    .cat-products-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
    }
    .cat-products-table th {
      background: #f1f5f9;
      padding: 10px 14px;
      font-size: 12px;
      font-weight: 700;
      color: #475569;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
    }
    .cat-products-table td {
      padding: 12px 14px;
      font-size: 13px;
      color: #1e293b;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    .cat-prod-thumb {
      width: 40px;
      height: 40px;
      border-radius: 6px;
      object-fit: cover;
      background: #e2e8f0;
    }
    .cat-prod-thumb-ph {
      width: 40px;
      height: 40px;
      border-radius: 6px;
      background: #eef2f7;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #94a3b8;
    }
  </style>
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'category'])
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard</span>
      </div>

      <div class="header-right">
        <div class="dropdown" data-dropdown>
          <button type="button" class="notification-btn" aria-label="Notifikasi" data-dropdown-trigger>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notif-badge-pill" data-notif-badge>1</span>
          </button>
        </div>
      </div>
    </header>

    <main class="form-content-area" style="max-width: 880px;">
      <div class="form-top-nav">
        <a href="{{ route('admin.category.index') }}" class="btn-back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali
        </a>

        <div class="form-breadcrumb">
          Dashboard <span>&gt;</span> Kategori <span>&gt;</span> <strong class="active-crumb">Detail Kategori</strong>
        </div>

        <h1 class="form-main-heading">{{ $category->category_name }}</h1>
      </div>

      <div class="cat-stat-grid">
        <div class="cat-stat-card">
          <div class="cat-stat-label">ID Kategori</div>
          <div class="cat-stat-value">#{{ $category->id }}</div>
        </div>
        <div class="cat-stat-card">
          <div class="cat-stat-label">Total Produk</div>
          <div class="cat-stat-value">{{ $category->products_count ?? 0 }}</div>
        </div>
        <div class="cat-stat-card">
          <div class="cat-stat-label">Total Stok</div>
          <div class="cat-stat-value">{{ $category->products_sum_stock ?? 0 }}</div>
        </div>
      </div>

      <div class="form-card" style="margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 14px;">
          Daftar Produk dalam Kategori Ini
        </h3>

        @if ($category->products && $category->products->count() > 0)
          <div style="overflow-x: auto;">
            <table class="cat-products-table">
              <thead>
                <tr>
                  <th style="width: 50px;">Foto</th>
                  <th>Nama Produk</th>
                  <th>Stok</th>
                  <th>Harga</th>
                  <th style="text-align: right;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($category->products as $p)
                  @php
                    $imgUrl = null;
                    if ($p->image) {
                      if (file_exists(storage_path('app/public/product/' . $p->image))) {
                        $imgUrl = asset('storage/product/' . $p->image);
                      } elseif (file_exists(public_path('product/' . $p->image))) {
                        $imgUrl = asset('product/' . $p->image);
                      } else {
                        $imgUrl = asset('storage/product/' . $p->image);
                      }
                    }
                  @endphp
                  <tr>
                    <td>
                      @if ($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $p->product_name }}" class="cat-prod-thumb">
                      @else
                        <div class="cat-prod-thumb-ph">
                          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                          </svg>
                        </div>
                      @endif
                    </td>
                    <td>
                      <strong>{{ $p->product_name }}</strong>
                    </td>
                    <td>{{ $p->stock }}</td>
                    <td>Rp {{ number_format((float) ($p->price ?? 0), 0, ',', '.') }}</td>
                    <td style="text-align: right;">
                      <a href="{{ route('admin.product.show', $p->id) }}" class="btn-detail" style="display:inline-flex;">Lihat Produk</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p style="color: #94a3b8; font-size: 13px; padding: 20px 0; text-align: center;">
            Belum ada produk yang terdaftar dalam kategori ini.
          </p>
        @endif
      </div>

      <div class="form-actions-bar">
        <a href="{{ route('admin.category.index') }}" class="btn-form-cancel">Kembali</a>
        <a href="{{ route('admin.category.edit', $category->id) }}" class="btn-form-submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          Edit Kategori
        </a>
      </div>
    </main>
  </div>
</div>

</body>
</html>
