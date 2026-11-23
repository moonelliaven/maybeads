<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Restore &amp; Pemulihan Data — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'restore'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka navigasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard / Restore Data</span>
      </div>

      <div class="header-right">
        <div class="dropdown" data-dropdown>
          <button type="button" class="notification-btn" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notification-badge">1</span>
          </button>
        </div>
      </div>
    </header>

    <main class="page-container">
      @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
          {{ session('success') }}
        </div>
      @endif

      <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 14px;">
        <div>
          <h1 class="page-title">Penyimpanan Dihapus (Restore)</h1>
          <p class="page-subtitle">Data produk dan kategori yang dihapus akan tersimpan selama <strong>30 hari</strong> sebelum terhapus permanen secara otomatis. Item di sini tidak tampil kepada pengguna di toko.</p>
        </div>
      </div>

      {{-- Tab Navigasi: Produk vs Kategori --}}
      <div style="display: flex; gap: 8px; border-bottom: 1px solid #e2e8f0; margin-bottom: 24px;">
        <a href="{{ route('admin.restore.index', ['tab' => 'product']) }}"
           style="padding: 10px 20px; font-size: 13.5px; font-weight: 700; text-decoration: none; border-bottom: 2px solid {{ $activeTab === 'product' ? '#2563eb' : 'transparent' }}; color: {{ $activeTab === 'product' ? '#2563eb' : '#64748b' }};">
          Produk Terhapus ({{ count($trashedProducts) }})
        </a>
        <a href="{{ route('admin.restore.index', ['tab' => 'category']) }}"
           style="padding: 10px 20px; font-size: 13.5px; font-weight: 700; text-decoration: none; border-bottom: 2px solid {{ $activeTab === 'category' ? '#2563eb' : 'transparent' }}; color: {{ $activeTab === 'category' ? '#2563eb' : '#64748b' }};">
          Kategori Terhapus ({{ count($trashedCategories) }})
        </a>
      </div>

      {{-- Tabel Konten --}}
      @if($activeTab === 'product')
        <div class="table-card">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok Terakhir</th>
                <th>Waktu Dihapus</th>
                <th>Sisa Waktu</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($trashedProducts as $p)
                <tr>
                  <td><strong>#{{ $p['id'] }}</strong></td>
                  <td>
                    <div style="font-weight: 700; color: #0f172a;">{{ $p['name'] }}</div>
                  </td>
                  <td>
                    <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: #475569;">
                      {{ $p['category_name'] }}
                    </span>
                  </td>
                  <td><strong>Rp {{ number_format($p['price'], 0, ',', '.') }}</strong></td>
                  <td>{{ $p['stock'] }} pcs</td>
                  <td style="font-size: 12.5px; color: #64748b;">{{ $p['deleted_at'] }}</td>
                  <td>
                    <span style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; font-size: 12.5px; color: {{ $p['days_left'] <= 5 ? '#ef4444' : '#d97706' }};">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                      </svg>
                      {{ $p['days_left'] }} hari lagi
                    </span>
                  </td>
                  <td>
                    <div style="display: flex; gap: 8px;">
                      {{-- Pulihkan --}}
                      <form method="POST" action="{{ route('admin.restore.product', $p['id']) }}" onsubmit="return confirm('Pulihkan produk ini ke etalase toko?')">
                        @csrf
                        <button type="submit" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; font-weight: 700; font-size: 12px; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 13px; height: 13px;"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                          Pulihkan
                        </button>
                      </form>

                      {{-- Hapus Permanen --}}
                      <form method="POST" action="{{ route('admin.restore.product.force', $p['id']) }}" onsubmit="return confirm('Hapus produk ini secara permanen dari database? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-weight: 700; font-size: 12px; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                          Hapus Permanen
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" style="text-align: center; padding: 48px; color: #94a3b8;">
                    Tidak ada produk di penyimpanan sampah.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      @else
        <div class="table-card">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Waktu Dihapus</th>
                <th>Sisa Waktu Simpan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($trashedCategories as $c)
                <tr>
                  <td><strong>#{{ $c['id'] }}</strong></td>
                  <td>
                    <div style="font-weight: 700; color: #0f172a;">{{ $c['name'] }}</div>
                  </td>
                  <td style="font-size: 12.5px; color: #64748b;">{{ $c['deleted_at'] }}</td>
                  <td>
                    <span style="display: inline-flex; align-items: center; gap: 4px; font-weight: 700; font-size: 12.5px; color: {{ $c['days_left'] <= 5 ? '#ef4444' : '#d97706' }};">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                      </svg>
                      {{ $c['days_left'] }} hari lagi
                    </span>
                  </td>
                  <td>
                    <div style="display: flex; gap: 8px;">
                      <form method="POST" action="{{ route('admin.restore.category', $c['id']) }}" onsubmit="return confirm('Pulihkan kategori ini?')">
                        @csrf
                        <button type="submit" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; font-weight: 700; font-size: 12px; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 13px; height: 13px;"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>
                          Pulihkan
                        </button>
                      </form>

                      <form method="POST" action="{{ route('admin.restore.category.force', $c['id']) }}" onsubmit="return confirm('Hapus kategori ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-weight: 700; font-size: 12px; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                          Hapus Permanen
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" style="text-align: center; padding: 48px; color: #94a3b8;">
                    Tidak ada kategori di penyimpanan sampah.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      @endif
    </main>
  </div>
</div>

</body>
</html>

