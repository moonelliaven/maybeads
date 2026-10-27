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
        <span class="header-title">Admin Dashboard</span>
      </div>
    </header>

    <main class="form-content-area" style="max-width: 680px;">
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
        <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
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
