<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Produk — {{ $product->product_name }}</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/product-form.css', 'resources/js/admin/product-form.js'])
  <style>
    .variant-badge-pill {
      display: inline-block;
      padding: 4px 10px;
      background: #eef3fb;
      color: #23477d;
      border-radius: 6px;
      font-size: 12.5px;
      font-weight: 600;
      margin-right: 6px;
      margin-bottom: 6px;
    }
  </style>
</head>
<body>

@php
  $existingImage = null;
  if (!empty($product->image)) {
      if (file_exists(storage_path('app/public/product/' . $product->image))) {
          $existingImage = asset('storage/product/' . $product->image);
      } elseif (file_exists(public_path('images/products/' . $product->image))) {
          $existingImage = asset('images/products/' . $product->image);
      } else {
          $existingImage = asset('storage/product/' . $product->image);
      }
  }

  $secCategories = [];
  if (!empty($product->secondary_categories) && is_array($product->secondary_categories)) {
      $secCategories = \App\Models\Category::whereIn('id', $product->secondary_categories)->pluck('category_name')->toArray();
  }
  $productVariants = $product->variants ?? [];
  $secImages = $product->secondary_images ?? [];
  $isActive = $product->status ?? true;
@endphp

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'product'])
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    {{-- Header Admin --}}
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

    {{-- Content Area --}}
    <main class="form-content-area">
      <div class="form-top-nav">
        <a href="{{ route('admin.product.index') }}" class="btn-back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali
        </a>

        <div class="form-breadcrumb">
          Dashboard <span>&gt;</span> Produk <span>&gt;</span> <strong class="active-crumb">Detail Produk</strong>
        </div>

        <h1 class="form-main-heading">Detail Produk</h1>
      </div>

      <div class="product-form-grid">
        {{-- Kolom Kiri: Foto Produk --}}
        <div class="form-card">
          <div class="form-section-title">Foto Produk Utama</div>

          <div class="upload-dropzone" style="background: #ffffff; border-style: solid;">
            @if ($existingImage)
              <div class="image-preview-container">
                <img class="image-preview-img" src="{{ $existingImage }}" alt="{{ $product->product_name }}">
              </div>
            @else
              <div class="dropzone-icon-wrap" style="color: #cbd5e1;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
              </div>
              <div class="dropzone-sub">Tidak ada foto utama</div>
            @endif
          </div>

          <hr class="form-card-divider">

          <div class="form-section-title">Foto Produk Tambahan</div>
          @if (!empty($secImages) && count($secImages) > 0)
            <div class="secondary-photos-grid">
              @foreach ($secImages as $idx => $sImg)
                @php
                  $sUrl = file_exists(storage_path('app/public/product/' . $sImg))
                          ? asset('storage/product/' . $sImg)
                          : (file_exists(public_path('product/' . $sImg)) ? asset('product/' . $sImg) : asset('storage/product/' . $sImg));
                @endphp
                <div class="sec-photo-thumb">
                  <img src="{{ $sUrl }}" alt="Foto Tambahan {{ $idx + 1 }}">
                </div>
              @endforeach
            </div>
          @else
            <div class="secondary-photo-text" style="color: #94a3b8;">
              Tidak ada foto tambahan.
            </div>
          @endif
        </div>

        {{-- Kolom Kanan: Informasi Produk --}}
        <div class="form-card">
          {{-- Nama Produk --}}
          <div class="form-field-group">
            <label class="form-label">Nama Produk</label>
            <div class="form-control-static">{{ $product->product_name }}</div>
          </div>

          {{-- Kategori --}}
          <div class="form-field-group">
            <label class="form-label">Kategori</label>
            <div class="form-control-static">
              <div>
                <strong>{{ $product->category?->category_name ?? 'Tanpa Kategori' }}</strong>
                @if (!empty($secCategories) && count($secCategories) > 0)
                  <span style="color: #64748b; font-size: 12.5px; margin-left: 6px;">
                    (Kategori Sekunder: {{ implode(', ', $secCategories) }})
                  </span>
                @endif
              </div>
            </div>
          </div>

          {{-- Harga & Stok --}}
          <div class="form-field-group">
            <div class="form-row-2col">
              <div>
                <label class="form-label">Harga</label>
                <div class="form-control-static">{{ str_starts_with($product->price, 'Rp') ? $product->price : 'Rp ' . $product->price }}</div>
              </div>

              <div>
                <label class="form-label">Stok</label>
                <div class="form-control-static">{{ $product->stock }} Unit</div>
              </div>
            </div>
          </div>

          {{-- Jenis / Varian --}}
          <div class="form-field-group">
            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
              <span>Varian Produk (Warna & Ukuran)</span>
              @if($product->variants && $product->variants->isNotEmpty())
                <span style="font-size: 12px; font-weight: 600; color: #23477d; background: #eef3fb; padding: 2px 8px; border-radius: 6px;">
                  Total: {{ $product->variants->count() }} Kombinasi
                </span>
              @endif
            </label>

            @if($product->variants && $product->variants->isNotEmpty())
              <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #ffffff;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                  <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                      <th style="padding: 10px 12px; font-weight: 600; color: #475569; width: 56px;">Foto</th>
                      <th style="padding: 10px 12px; font-weight: 600; color: #475569;">Warna</th>
                      <th style="padding: 10px 12px; font-weight: 600; color: #475569;">Ukuran</th>
                      <th style="padding: 10px 12px; font-weight: 600; color: #475569; text-align: right;">Stok</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($product->variants as $variant)
                      @php
                        $varImg = null;
                        if (!empty($variant->image)) {
                            if (file_exists(storage_path('app/public/product/' . $variant->image))) {
                                $varImg = asset('storage/product/' . $variant->image);
                            } elseif (file_exists(public_path('images/products/' . $variant->image))) {
                                $varImg = asset('images/products/' . $variant->image);
                            } else {
                                $varImg = asset('storage/product/' . $variant->image);
                            }
                        }
                      @endphp
                      <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 8px 12px;">
                          @if($varImg)
                            <img src="{{ $varImg }}" alt="{{ $variant->color }}" style="width: 36px; height: 36px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; display: block;">
                          @else
                            <div style="width: 36px; height: 36px; border-radius: 6px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 11px;">
                              -
                            </div>
                          @endif
                        </td>
                        <td style="padding: 8px 12px; font-weight: 600; color: #1e293b;">
                          {{ $variant->color }}
                        </td>
                        <td style="padding: 8px 12px; color: #475569;">
                          <span style="display: inline-block; padding: 2px 8px; background: #f1f5f9; border-radius: 4px; font-weight: 500;">
                            {{ $variant->size }}
                          </span>
                        </td>
                        <td style="padding: 8px 12px; font-weight: 600; color: #0f172a; text-align: right;">
                          {{ $variant->stock }} Unit
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="form-control-static" style="color: #64748b;">
                Standar (Tanpa Varian)
              </div>
            @endif
          </div>

          {{-- Deskripsi Produk --}}
          <div class="form-field-group">
            <label class="form-label">Deskripsi Produk</label>
            <div class="form-control-static" style="min-height: 80px; align-items: flex-start; white-space: pre-line;">
              {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
            </div>
          </div>

          {{-- Status Produk --}}
          <div class="form-field-group" style="margin-bottom: 0;">
            <label class="form-label">Status Produk</label>
            <div class="status-toggle-wrap">
              <span class="toggle-switch-track" style="background-color: {{ $isActive ? '#23477d' : '#cbd5e1' }}; cursor: default;">
                <span class="toggle-switch-thumb" style="transform: {{ $isActive ? 'translateX(20px)' : 'none' }};"></span>
              </span>
              <span class="status-toggle-label" style="cursor: default;">{{ $isActive ? 'Aktif' : 'Nonaktif' }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="form-actions-bar">
        <a href="{{ route('admin.product.index') }}" class="btn-form-cancel">Kembali</a>
        <a href="{{ route('admin.product.edit', $product->id) }}" class="btn-form-submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 1-2 2v14a2 2 0 0 1 2 2h14a2 2 0 0 1 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
          </svg>
          Edit Produk
        </a>
      </div>
    </main>
  </div>
</div>

</body>
</html>
