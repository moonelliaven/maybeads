<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Kategori — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/product-form.css', 'resources/js/admin/product-form.js'])
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
        <span class="header-title">Admin Dashboard / Edit Kategori</span>
      </div>

      @php
        $pendingBadgeCount = \App\Models\Order::where('order_status', 'pending')->count();
      @endphp
      <div class="header-right">
        <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          @if($pendingBadgeCount > 0)
            <span class="notif-badge-pill">{{ $pendingBadgeCount > 99 ? '99+' : $pendingBadgeCount }}</span>
          @endif
        </a>
      </div>
    </header>

    <main class="form-content-area" style="max-width: 680px;">

      {{-- Validation Error Alert (SweetAlert2) --}}
      @if ($errors->any())
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonColor: '#23477d',
                customClass: { popup: 'admin-swal-popup' }
              });
            }
          });
        </script>
      @endif
      <div class="form-top-nav">
        <a href="{{ route('admin.category.index') }}" class="btn-back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali
        </a>

        <div class="form-breadcrumb">
          Dashboard <span>&gt;</span> Kategori <span>&gt;</span> <strong class="active-crumb">Edit Kategori</strong>
        </div>

        <h1 class="form-main-heading">Edit Kategori</h1>
      </div>

      <div class="form-card">
        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="form-field-group">
            <label for="category_name" class="form-label">Nama Kategori</label>
            <input type="text" id="category_name" name="category_name" class="form-control-input"
                   placeholder="Contoh: Gelang, Kalung, Cincin..." value="{{ old('category_name', $category->category_name) }}" required>
            @error('category_name')
              <div class="form-error-msg">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-field-group" style="margin-top: 20px;">
            <label for="image" class="form-label">Gambar Kategori</label>
            @if($category->image)
              <div style="margin-bottom: 10px;">
                <img id="catImagePreview" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->category_name }}" style="max-width: 140px; max-height: 140px; border-radius: 8px; object-fit: cover; border: 1.5px solid #cbd5e1; display: block;">
              </div>
            @else
              <div style="margin-bottom: 10px;">
                <img id="catImagePreview" src="" alt="Preview Kategori" style="display: none; max-width: 140px; max-height: 140px; border-radius: 8px; object-fit: cover; border: 1.5px solid #cbd5e1;">
              </div>
            @endif
            <input type="file" id="image" name="image" class="form-control-input" accept="image/*"
                   style="padding: 9px 12px; background: #fff;"
                   onchange="if(this.files && this.files[0]) {
                     const reader = new FileReader();
                     reader.onload = e => {
                       const p = document.getElementById('catImagePreview');
                       p.src = e.target.result;
                       p.style.display = 'block';
                     };
                     reader.readAsDataURL(this.files[0]);
                   }">
            <span style="font-size: 11.5px; color: #64748b; margin-top: 4px; display: block;">Kosongkan jika tidak ingin mengubah gambar kategori.</span>
            @error('image')
              <div class="form-error-msg">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-actions-bar" style="margin-top: 24px;">
            <a href="{{ route('admin.category.index') }}" class="btn-form-cancel">Batal</a>
            <button type="submit" class="btn-form-submit">
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>

</body>
</html>
