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

  @vite(['resources/css/admin/product-index.css', 'resources/js/admin/product-form.js'])
  <style>
    .cat-table-wrap {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      overflow: hidden;
      margin-top: 20px;
    }
    .cat-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    .cat-table th {
      background: #f8fafc;
      padding: 14px 20px;
      font-size: 12.5px;
      font-weight: 700;
      color: #475569;
      border-bottom: 1px solid #e2e8f0;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }
    .cat-table td {
      padding: 14px 20px;
      font-size: 13.5px;
      color: #1e293b;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    .cat-table tr:hover td {
      background-color: #fafbfc;
    }
    .cat-badge {
      display: inline-block;
      padding: 4px 10px;
      background: #eef3fb;
      color: #23477d;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
    }
    .cat-action-btns {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .btn-cat-action {
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: all 0.2s;
    }
    .btn-cat-edit {
      background: #f1f5f9;
      color: #334155;
      border: 1px solid #cbd5e1;
    }
    .btn-cat-edit:hover { background: #e2e8f0; }
    .btn-cat-delete {
      background: #fee2e2;
      color: #dc2626;
      border: 1px solid #fecaca;
    }
    .btn-cat-delete:hover { background: #fca5a5; }
    .top-action-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 18px;
    }
    .btn-add-cat-primary {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #23477d;
      color: #ffffff;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s;
    }
    .btn-add-cat-primary:hover { background: #1c3863; }
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
            <span class="notification-badge" data-notif-badge>1</span>
          </button>
        </div>
      </div>
    </header>

    <main class="product-content-area" style="padding: 28px 40px 60px;">
      <div class="top-nav-bar">
        <div class="breadcrumb">
          Dashboard <span>&gt;</span> <strong class="active">Kategori</strong>
        </div>
      </div>

      @if (session('success'))
        <div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 8px; margin-top: 14px; font-size: 13.5px; font-weight: 600;">
          {{ session('success') }}
        </div>
      @endif

      <div class="top-action-bar">
        <div>
          <h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Manajemen Kategori</h1>
          <p style="font-size: 13px; color: #64748b;">Kelola daftar kategori produk Maybeads</p>
        </div>
        <a href="{{ route('admin.category.create') }}" class="btn-add-cat-primary">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          Tambah Kategori
        </a>
      </div>

      <div class="cat-table-wrap">
        <table class="cat-table">
          <thead>
            <tr>
              <th style="width: 60px;">No</th>
              <th>Nama Kategori</th>
              <th>Total Produk</th>
              <th>Dibuat</th>
              <th style="width: 140px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($categories as $index => $cat)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  <strong>{{ $cat->category_name }}</strong>
                </td>
                <td>
                  <span class="cat-badge">{{ $cat->products_count ?? $cat->products()->count() }} Produk</span>
                </td>
                <td style="color: #64748b; font-size: 12.5px;">
                  {{ $cat->created_at ? \Carbon\Carbon::parse($cat->created_at)->format('d M Y') : '-' }}
                </td>
                <td style="text-align: center;">
                  <div class="cat-action-btns" style="justify-content: center;">
                    <a href="{{ route('admin.category.edit', $cat->id) }}" class="btn-cat-action btn-cat-edit">Edit</a>
                    <form action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')" style="margin:0;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-cat-action btn-cat-delete">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                  Belum ada kategori yang ditambahkan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

</body>
</html>
