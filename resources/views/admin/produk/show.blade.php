<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Produk — {{ $product->product_name }}</title>

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
                  <span class="notif-title">Detail Produk</span>
                  <span class="notif-sub">Melihat informasi produk {{ $product->product_name }}</span>
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
      <main class="dashboard-content" style="padding: 28px 36px 48px;">
        <div class="product-form-container">

          <!-- Tombol Kembali -->
          <a href="{{ route('admin.product.index') }}" class="product-back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali</span>
          </a>

          <!-- Breadcrumb -->
          <nav class="product-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span class="breadcrumb-separator">&gt;</span>
            <a href="{{ route('admin.product.index') }}">Produk</a>
            <span class="breadcrumb-separator">&gt;</span>
            <span class="breadcrumb-current">Detail Produk</span>
          </nav>

          <!-- Judul Halaman -->
          <h1 class="form-page-title">Detail Produk</h1>

          <div class="product-form-grid">
            
            <!-- KARTU KIRI: Foto Produk -->
            <div class="product-form-card">
              <div class="form-card-title">Foto Produk Utama</div>

              <!-- Tampilan Foto Utama -->
              <div class="main-photo-box" style="cursor: default;">
                @php
                  $imgPath = $product->image && file_exists(public_path('images/products/' . $product->image))
                    ? asset('images/products/' . $product->image)
                    : asset('images/products/keychain-01.jpg');
                @endphp
                <img src="{{ $imgPath }}" alt="{{ $product->product_name }}">
              </div>

              <hr class="form-divider">

              <div class="form-card-title">Foto Produk Tambahan</div>

              @php
                $extraImages = !empty($product->additional_images) ? json_decode($product->additional_images, true) : [];
              @endphp

              <div class="additional-photos-row">
                @if(!empty($extraImages))
                  @foreach($extraImages as $extraImg)
                    <div class="additional-preview-thumb">
                      <img src="{{ asset('images/products/' . $extraImg) }}" alt="Foto Tambahan">
                    </div>
                  @endforeach
                @else
                  <p class="additional-photo-text" style="max-width: 100%;">
                    Tidak ada foto tambahan untuk produk ini.
                  </p>
                @endif
              </div>
            </div>

            <!-- KARTU KANAN: Detail Informasi Produk -->
            <div class="product-form-card">
              <!-- Nama Produk -->
              <div class="form-input-group">
                <label class="form-input-label">Nama Produk</label>
                <input 
                  type="text" 
                  class="form-input-control" 
                  value="{{ $product->product_name }}" 
                  readonly 
                  style="background: #f8fafc;"
                >
              </div>

              <!-- Kategori -->
              <div class="form-input-group">
                <label class="form-input-label">Kategori</label>
                <input 
                  type="text" 
                  class="form-input-control" 
                  value="{{ $product->category->category_name ?? '-' }}" 
                  readonly 
                  style="background: #f8fafc;"
                >
              </div>

              <!-- Harga & Stok (Dua Kolom) -->
              <div class="form-row-two-col">
                <!-- Harga -->
                <div>
                  <label class="form-input-label">Harga</label>
                  <div class="input-with-prefix">
                    <span class="input-prefix-tag">Rp</span>
                    <input 
                      type="text" 
                      class="form-input-control" 
                      value="{{ $product->price }}" 
                      readonly 
                      style="background: #f8fafc;"
                    >
                  </div>
                </div>

                <!-- Stok -->
                <div>
                  <label class="form-input-label">Stok</label>
                  <input 
                    type="text" 
                    class="form-input-control" 
                    value="{{ $product->stock }} pcs" 
                    readonly 
                    style="background: #f8fafc;"
                  >
                </div>
              </div>

              <!-- Jenis / Variants -->
              <div class="form-input-group">
                <label class="form-input-label">Jenis</label>
                <div class="variants-row">
                  @php
                    $rawVariants = $product->variants ?: 'Hitam, Silver, Pink';
                    $variantsList = array_map('trim', explode(',', $rawVariants));
                  @endphp

                  @foreach($variantsList as $vTag)
                    @if(!empty($vTag))
                      <div class="variant-tag-item">
                        <span>{{ $vTag }}</span>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>

              <!-- Deskripsi Produk -->
              <div class="form-input-group">
                <label class="form-input-label">Deskripsi Produk</label>
                <textarea 
                  class="form-input-control form-textarea" 
                  readonly 
                  style="background: #f8fafc;"
                >{{ $product->description ?? 'Tidak ada deskripsi.' }}</textarea>
              </div>

              <!-- Status Produk (Toggle Switch) -->
              <div class="form-input-group" style="margin-bottom: 0;">
                <label class="form-input-label">Status Produk</label>
                <div class="toggle-wrapper">
                  <label class="switch-toggle" style="pointer-events: none;">
                    <input type="checkbox" {{ ($product->status ?? true) ? 'checked' : '' }} disabled>
                    <span class="switch-slider"></span>
                  </label>
                  <span class="switch-label-text">{{ ($product->status ?? true) ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
              </div>

            </div>

          </div>

          <!-- Tombol Bawah Aksi (Kembali & Edit Produk) -->
          <div class="form-bottom-actions">
            <a href="{{ route('admin.product.index') }}" class="btn-form-cancel">Kembali</a>
            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn-form-save" style="text-decoration: none;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
              <span>Edit Produk</span>
            </a>
          </div>

        </div>
      </main>
    </div>
  </div>
</body>
</html>
