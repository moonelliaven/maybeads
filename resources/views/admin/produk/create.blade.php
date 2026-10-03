<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tambah Produk — Admin Maybead.s</title>

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
                  <span class="notif-title">Tambah Produk Baru</span>
                  <span class="notif-sub">Lengkapi data untuk menambahkan produk ke katalog</span>
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
            <span class="breadcrumb-current">Tambah Produk</span>
          </nav>

          <!-- Judul Halaman -->
          <h1 class="form-page-title">Tambah Produk</h1>

          <!-- Form Tambah Produk -->
          <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="product-form-grid">
              
              <!-- KARTU KIRI: Foto Produk -->
              <div class="product-form-card">
                <div class="form-card-title">Foto Produk Utama</div>

                <!-- Preview Foto Utama -->
                <div class="main-photo-box" id="mainPhotoBox" onclick="document.getElementById('mainPhotoInput').click();">
                  <img id="mainPhotoPreview" src="{{ asset('images/products/keychain-01.jpg') }}" alt="Preview Foto Utama">
                  <div class="photo-overlay-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                      <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                    <span>Pilih / Ganti Foto</span>
                  </div>
                </div>

                <input type="file" name="image_file" id="mainPhotoInput" accept="image/*" style="display: none;">
                <input type="hidden" name="image" id="mainPhotoHidden" value="keychain-01.jpg">

                <hr class="form-divider">

                <div class="form-card-title">Foto Produk Tambahan (Opsional)</div>

                <div class="additional-photos-row" id="additionalPhotosRow">
                  <!-- Tombol Tambah Foto Dashed -->
                  <div class="btn-upload-additional" onclick="document.getElementById('additionalPhotoInput').click();">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Foto</span>
                  </div>

                  <p class="additional-photo-text">
                    Tambahkan beberapa foto produk untuk menampilkan detail lebih lengkap
                  </p>
                </div>

                <input type="file" name="additional_images[]" id="additionalPhotoInput" accept="image/*" multiple style="display: none;">

                <div class="additional-note">Maksimal 5 Foto</div>
              </div>

              <!-- KARTU KANAN: Detail Informasi Produk -->
              <div class="product-form-card">
                <!-- Nama Produk -->
                <div class="form-input-group">
                  <label class="form-input-label" for="productNameInput">Nama Produk</label>
                  <input 
                    type="text" 
                    name="product_name" 
                    id="productNameInput" 
                    class="form-input-control" 
                    placeholder="Keychain" 
                    value="{{ old('product_name', 'Keychain') }}" 
                    required
                  >
                </div>

                <!-- Kategori -->
                <div class="form-input-group">
                  <label class="form-input-label" for="categorySelect">Kategori</label>
                  <select name="category_id" id="categorySelect" class="form-input-control form-select-control" required>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}" {{ $category->category_name === 'Gantungan' ? 'selected' : '' }}>
                        {{ $category->category_name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <!-- Harga & Stok (Dua Kolom) -->
                <div class="form-row-two-col">
                  <!-- Harga -->
                  <div>
                    <label class="form-input-label" for="priceInput">Harga</label>
                    <div class="input-with-prefix">
                      <span class="input-prefix-tag">Rp</span>
                      <input 
                        type="text" 
                        name="price" 
                        id="priceInput" 
                        class="form-input-control" 
                        placeholder="20.000,00" 
                        value="{{ old('price', '20.000,00') }}" 
                        required
                      >
                    </div>
                  </div>

                  <!-- Stok -->
                  <div>
                    <label class="form-input-label" for="stockInput">Stok</label>
                    <input 
                      type="number" 
                      name="stock" 
                      id="stockInput" 
                      class="form-input-control" 
                      placeholder="40" 
                      value="{{ old('stock', 40) }}" 
                      min="0" 
                      required
                    >
                  </div>
                </div>

                <!-- Jenis / Variants -->
                <div class="form-input-group">
                  <label class="form-input-label">Jenis</label>
                  <div class="variants-row" id="variantsRow">
                    <button type="button" class="btn-add-tag" id="btnAddVariant">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                      </svg>
                      <span>Tambah</span>
                    </button>

                    <!-- Default Variant Tags -->
                    <div class="variant-tag-item">
                      <span>Hitam</span>
                      <button type="button" class="tag-remove-btn" onclick="removeVariant(this)">&times;</button>
                    </div>
                    <div class="variant-tag-item">
                      <span>Silver</span>
                      <button type="button" class="tag-remove-btn" onclick="removeVariant(this)">&times;</button>
                    </div>
                    <div class="variant-tag-item">
                      <span>Pink</span>
                      <button type="button" class="tag-remove-btn" onclick="removeVariant(this)">&times;</button>
                    </div>
                  </div>
                  <input type="hidden" name="variants" id="variantsInput" value="Hitam, Silver, Pink">
                </div>

                <!-- Deskripsi Produk -->
                <div class="form-input-group">
                  <label class="form-input-label" for="descriptionInput">Deskripsi Produk</label>
                  <textarea 
                    name="description" 
                    id="descriptionInput" 
                    class="form-input-control form-textarea" 
                    placeholder="Keychain Y2K dengan konsep simple-modern"
                  >{{ old('description', 'Keychain Y2K dengan konsep simple-modern') }}</textarea>
                </div>

                <!-- Status Produk (Toggle Switch) -->
                <div class="form-input-group" style="margin-bottom: 0;">
                  <label class="form-input-label">Status Produk</label>
                  <div class="toggle-wrapper">
                    <label class="switch-toggle">
                      <input type="checkbox" name="status" id="statusToggle" value="1" checked>
                      <span class="switch-slider"></span>
                    </label>
                    <span class="switch-label-text" id="statusLabel">Aktif</span>
                  </div>
                </div>

              </div>

            </div>

            <!-- Tombol Bawah Aksi (Batal & Simpan) -->
            <div class="form-bottom-actions">
              <a href="{{ route('admin.product.index') }}" class="btn-form-cancel">Batal</a>
              <button type="submit" class="btn-form-save">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                  <polyline points="17 21 17 13 7 13 7 21"></polyline>
                  <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                <span>Simpan Perubahan</span>
              </button>
            </div>

          </form>

        </div>
      </main>
    </div>
  </div>

  <script>
    // 1. Preview Foto Utama
    const mainPhotoInput = document.getElementById('mainPhotoInput');
    const mainPhotoPreview = document.getElementById('mainPhotoPreview');

    if (mainPhotoInput) {
      mainPhotoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            mainPhotoPreview.src = e.target.result;
          };
          reader.readAsDataURL(this.files[0]);
        }
      });
    }

    // 2. Preview Foto Tambahan
    const additionalPhotoInput = document.getElementById('additionalPhotoInput');
    const additionalPhotosRow = document.getElementById('additionalPhotosRow');

    if (additionalPhotoInput) {
      additionalPhotoInput.addEventListener('change', function () {
        if (this.files) {
          Array.from(this.files).slice(0, 5).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
              const thumb = document.createElement('div');
              thumb.className = 'additional-preview-thumb';
              thumb.innerHTML = `
                <img src="${e.target.result}" alt="Foto Tambahan">
                <button type="button" class="btn-remove-thumb" onclick="this.parentElement.remove()">&times;</button>
              `;
              additionalPhotosRow.insertBefore(thumb, additionalPhotosRow.lastElementChild);
            };
            reader.readAsDataURL(file);
          });
        }
      });
    }

    // 3. Status Switch Toggle Label
    const statusToggle = document.getElementById('statusToggle');
    const statusLabel = document.getElementById('statusLabel');

    if (statusToggle && statusLabel) {
      statusToggle.addEventListener('change', function () {
        statusLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
      });
    }

    // 4. Manajemen Tag Jenis (Variants)
    const btnAddVariant = document.getElementById('btnAddVariant');
    const variantsRow = document.getElementById('variantsRow');
    const variantsInput = document.getElementById('variantsInput');

    function updateVariantsHiddenInput() {
      const tags = Array.from(document.querySelectorAll('.variant-tag-item span')).map(el => el.textContent.trim());
      variantsInput.value = tags.join(', ');
    }

    function removeVariant(btn) {
      btn.parentElement.remove();
      updateVariantsHiddenInput();
    }

    if (btnAddVariant) {
      btnAddVariant.addEventListener('click', function () {
        const val = prompt('Masukkan jenis/warna/varian baru (contoh: Ungu):');
        if (val && val.trim() !== '') {
          const tag = document.createElement('div');
          tag.className = 'variant-tag-item';
          tag.innerHTML = `
            <span>${val.trim()}</span>
            <button type="button" class="tag-remove-btn" onclick="removeVariant(this)">&times;</button>
          `;
          variantsRow.appendChild(tag);
          updateVariantsHiddenInput();
        }
      });
    }
  </script>
</body>
</html>
