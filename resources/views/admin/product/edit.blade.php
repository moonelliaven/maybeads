<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Produk — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/product-form.css', 'resources/js/admin/product-form.js'])
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

  $secCategories = $product->secondary_categories ?? [];
  $productVariants = $product->variants ?? collect();
  $existingVariantsJson = $product->variants->map(function ($v) {
      $imgUrl = '';
      if (!empty($v->image)) {
          if (file_exists(storage_path('app/public/product/' . $v->image))) {
              $imgUrl = asset('storage/product/' . $v->image);
          } elseif (file_exists(public_path('product/' . $v->image))) {
              $imgUrl = asset('product/' . $v->image);
          } else {
              $imgUrl = asset('storage/product/' . $v->image);
          }
      }
      return [
          'id'            => $v->id,
          'color'         => $v->color,
          'size'          => $v->size,
          'stock'         => $v->stock,
          'imageFilename' => $v->image,
          'imageUrl'      => $imgUrl,
      ];
  })->values();
  $secImages = $product->secondary_images ?? [];
  $statusVal = old('status', $product->status ?? 1);
@endphp

<script>
  window.__existingVariants = {!! json_encode($existingVariantsJson) !!};
</script>

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
          Dashboard <span>&gt;</span> Produk <span>&gt;</span> <strong class="active-crumb">Edit Produk</strong>
        </div>

        <h1 class="form-main-heading">Edit Produk</h1>
      </div>

      <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="product-form-grid">
          {{-- Kolom Kiri: Foto Produk --}}
          <div class="form-card">
            <div class="form-section-title">Foto Produk Utama</div>

            {{-- Dropzone Utama --}}
            <div class="upload-dropzone" id="uploadDropzone">
              <input type="file" id="productImageInput" name="image" accept="image/png,image/jpeg,image/webp" style="display:none">

              {{-- Default Prompt (jika belum ada gambar) --}}
              <div id="dropzoneDefaultPrompt" {{ $existingImage ? 'hidden' : '' }}>
                <div class="dropzone-icon-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="3" ry="3"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                  </svg>
                </div>
                <div class="dropzone-title">Unggah Foto Produk</div>
                <div class="dropzone-sub">PNG, JPG atau WEBP. Maksimal 5 MB</div>
                <button type="button" class="btn-upload-trigger" id="btnUploadTrigger">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                  </svg>
                  Pilih Foto
                </button>
              </div>

              {{-- Preview State --}}
              <div id="imagePreviewContainer" class="image-preview-container" {{ $existingImage ? '' : 'hidden' }}>
                <img id="imagePreviewImg" class="image-preview-img" src="{{ $existingImage ?? '' }}" alt="Foto Produk">
                <button type="button" class="btn-remove-preview" id="btnRemovePreview">Ganti Foto</button>
              </div>
            </div>
            @error('image')
              <div class="form-error-msg">{{ $message }}</div>
            @enderror

            <hr class="form-card-divider">

            {{-- Foto Tambahan (Opsional, Maks 5) --}}
            <div class="form-section-title">Foto Produk Tambahan (Opsional)</div>
            <div id="secondaryPhotosArea" class="secondary-photos-grid">
              @if (!empty($secImages) && is_array($secImages))
                @foreach ($secImages as $idx => $sImg)
                  @php
                    $sUrl = file_exists(storage_path('app/public/product/' . $sImg))
                            ? asset('storage/product/' . $sImg)
                            : (file_exists(public_path('product/' . $sImg)) ? asset('product/' . $sImg) : asset('storage/product/' . $sImg));
                  @endphp
                  <div class="sec-photo-thumb" id="existingSecThumb{{ $idx }}">
                    <img src="{{ $sUrl }}" alt="Foto Tambahan {{ $idx + 1 }}">
                    <button type="button" class="sec-photo-remove" title="Hapus foto ini" onclick="document.getElementById('existingSecThumb{{ $idx }}').remove(); document.getElementById('removeSecInput{{ $idx }}').value='{{ $idx }}';">&times;</button>
                    <input type="hidden" name="remove_secondary_images[]" id="removeSecInput{{ $idx }}" value="">
                  </div>
                @endforeach
              @endif
            </div>

            <div class="secondary-photo-row" style="margin-top: 12px;">
              <button type="button" class="btn-add-secondary-photo" id="btnAddSecondaryPhoto" title="Tambah foto">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Foto
              </button>
              <div class="secondary-photo-text">
                Tambahkan beberapa foto produk untuk menampilkan detail lebih lengkap
              </div>
            </div>
            <input type="file" id="secondaryImageInput" accept="image/png,image/jpeg,image/webp" multiple style="display:none">
            <div class="secondary-photo-limit">Maksimal 5 Foto</div>
          </div>

          {{-- Kolom Kanan: Informasi Produk --}}
          <div class="form-card">
            {{-- Nama Produk --}}
            <div class="form-field-group">
              <label for="product_name" class="form-label">Nama Produk</label>
              <input type="text" id="product_name" name="product_name"
                     class="form-control-input"
                     placeholder="Masukkan Nama Produk"
                     value="{{ old('product_name', $product->product_name) }}" required>
              @error('product_name')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>

            {{-- Kategori Utama & Custom --}}
            <div class="form-field-group">
              <label for="category_id" class="form-label">Kategori</label>
              <div class="category-select-row">
                <div class="category-select-main">
                  <select id="category_id" name="category_id" class="form-control-select" required>
                    <option value="" disabled>Pilih Kategori</option>
                    @foreach ($categories as $cat)
                      <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->category_name }}
                      </option>
                    @endforeach
                    <option value="custom" {{ old('category_id') === 'custom' ? 'selected' : '' }}>✏️ Custom (tulis sendiri)</option>
                  </select>
                  <input type="text" id="customCategoryInput" name="custom_category_name"
                         class="form-control-input custom-cat-input" placeholder="Tulis nama kategori baru..."
                         value="{{ old('custom_category_name') }}" style="display:none;">
                </div>
                <button type="button" class="btn-add-secondary-cat" id="btnAddSecondaryCat" title="Tambah kategori sekunder">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                </button>
              </div>
              @error('category_id')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror

              {{-- Secondary categories container (max 3) --}}
              <div id="secondaryCategoriesContainer" class="secondary-categories-list">
                @if (!empty($secCategories) && is_array($secCategories))
                  @foreach ($secCategories as $scId)
                    <div class="sec-cat-row">
                      <div class="sec-cat-main">
                        <select name="secondary_category_ids[]" class="form-control-select">
                          <option value="">Pilih Kategori Sekunder</option>
                          @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $scId == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                          @endforeach
                          <option value="custom">✏️ Custom (tulis sendiri)</option>
                        </select>
                        <input type="text" name="secondary_category_customs[]" class="form-control-input custom-cat-input" placeholder="Tulis nama kategori sekunder baru..." style="display:none;">
                      </div>
                      <button type="button" class="btn-remove-sec-cat" onclick="this.closest('.sec-cat-row').remove();">&times;</button>
                    </div>
                  @endforeach
                @endif
              </div>
            </div>

            {{-- Harga & Stok --}}
            <div class="form-field-group">
              <div class="form-row-2col">
                <div>
                  <label for="price" class="form-label">Harga</label>
                  <input type="text" id="price" name="price"
                         class="form-control-input"
                         placeholder="Rp 000,00"
                         value="{{ old('price', $product->price) }}" required>
                  @error('price')
                    <div class="form-error-msg">{{ $message }}</div>
                  @enderror
                </div>

                <div>
                  <label for="stockInput" class="form-label">Stok</label>
                  <div class="stock-stepper-wrap">
                    <button type="button" class="stock-stepper-btn" id="btnStockDec" aria-label="Kurangi stok">-</button>
                    <input type="number" id="stockInput" name="stock"
                           class="stock-stepper-input"
                           value="{{ old('stock', $product->stock) }}" min="0" required>
                    <button type="button" class="stock-stepper-btn" id="btnStockInc" aria-label="Tambah stok">+</button>
                  </div>
                  @error('stock')
                    <div class="form-error-msg">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            {{-- Varian Produk (Warna & Ukuran) --}}
            <div class="form-field-group">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                <div>
                  <label class="form-label" style="margin-bottom: 2px;">Varian Produk (Warna & Ukuran)</label>
                  <span style="font-size: 11.5px; color: #64748b;">Atur warna, ukuran, foto varian, dan stok untuk tiap varian.</span>
                </div>
                <button type="button" class="btn-add-type" id="btnAddVariant">
                  <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                  Tambah Varian
                </button>
              </div>

              <div id="variantsContainer" class="variants-list">
                {{-- JS renders existing and new variant cards here --}}
              </div>
              <div id="variantDuplicateAlert" class="variant-duplicate-alert" role="alert"></div>
              @error('variants')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>

            {{-- Deskripsi Produk --}}
            <div class="form-field-group">
              <label for="description" class="form-label">Deskripsi Produk</label>
              <textarea id="description" name="description"
                        class="form-control-textarea"
                        placeholder="Masukkan deskripsi produk...">{{ old('description', $product->description) }}</textarea>
              @error('description')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>

            {{-- Status Produk --}}
            <div class="form-field-group" style="margin-bottom: 0;">
              <label class="form-label">Status Produk</label>
              <label class="status-toggle-wrap" for="statusProductToggle">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" id="statusProductToggle" name="status" value="1" class="toggle-switch-input" {{ $statusVal ? 'checked' : '' }}>
                <span class="toggle-switch-track">
                  <span class="toggle-switch-thumb"></span>
                </span>
                <span class="status-toggle-label" id="statusProductLabel">{{ $statusVal ? 'Aktif' : 'Nonaktif' }}</span>
              </label>
            </div>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="form-actions-bar">
          <a href="{{ route('admin.product.index') }}" class="btn-form-cancel">Batal</a>
          <button type="submit" class="btn-form-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Simpan Perubahan
          </button>
        </div>
      </form>
    </main>
  </div>
</div>

<script>
  window.__categoriesJson = @json($categories);
</script>

</body>
</html>
