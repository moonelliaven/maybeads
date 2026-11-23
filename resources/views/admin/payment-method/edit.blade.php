<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Edit Metode Pembayaran — {{ $paymentMethod->name }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/product-form.css', 'resources/js/admin/product-form.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'payment-method'])
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
        <span class="header-title">Admin Dashboard / Edit Metode Pembayaran</span>
      </div>
    </header>

    <main class="form-content-area" style="max-width: 760px;">
      <div class="form-top-nav">
        <a href="{{ route('admin.payment-method.index') }}" class="btn-back-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali ke Daftar Metode
        </a>

        <div class="form-breadcrumb">
          Dashboard <span>&gt;</span> Metode Pembayaran <span>&gt;</span> <strong class="active-crumb">Edit: {{ $paymentMethod->name }}</strong>
        </div>

        <h1 class="form-main-heading">Edit Metode Pembayaran</h1>
      </div>

      <div class="form-card" style="margin-top: 20px;">
        <form action="{{ route('admin.payment-method.update', $paymentMethod->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-field-group">
              <label for="name" class="form-label">Nama Metode & Provider *</label>
              <input type="text" id="name" name="name" class="form-control-input"
                     value="{{ old('name', $paymentMethod->name) }}" required>
              @error('name')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-field-group">
              <label for="code" class="form-label">Kode Unik / Slug *</label>
              <input type="text" id="code" name="code" class="form-control-input"
                     value="{{ old('code', $paymentMethod->code) }}" required>
              @error('code')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
            <div class="form-field-group">
              <label for="type" class="form-label">Kategori Tipe *</label>
              <select id="type" name="type" class="form-control-input" required>
                <option value="bank" {{ old('type', $paymentMethod->type) === 'bank' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="auto-link" {{ old('type', $paymentMethod->type) === 'auto-link' ? 'selected' : '' }}>Auto-Link</option>
                <option value="qris" {{ old('type', $paymentMethod->type) === 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="e-wallet" {{ old('type', $paymentMethod->type) === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
              </select>
              @error('type')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-field-group">
              <label for="price" class="form-label">Biaya Admin (Rp) *</label>
              <input type="number" id="price" name="price" class="form-control-input"
                     value="{{ old('price', (int)$paymentMethod->price) }}" min="0" required>
              @error('price')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
            <div class="form-field-group">
              <label for="account_number" class="form-label">No. Rekening / ID Merchant (Opsional)</label>
              <input type="text" id="account_number" name="account_number" class="form-control-input"
                     value="{{ old('account_number', $paymentMethod->account_number) }}">
              @error('account_number')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-field-group">
              <label for="account_name" class="form-label">Atas Nama (a/n) Rekening (Opsional)</label>
              <input type="text" id="account_name" name="account_name" class="form-control-input"
                     value="{{ old('account_name', $paymentMethod->account_name) }}">
              @error('account_name')
                <div class="form-error-msg">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form-field-group" style="margin-top: 16px;">
            <label for="status" class="form-label">Status *</label>
            <select id="status" name="status" class="form-control-input" required>
              <option value="active" {{ old('status', $paymentMethod->status) === 'active' ? 'selected' : '' }}>Aktif</option>
              <option value="non-active" {{ old('status', $paymentMethod->status) === 'non-active' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
            @error('status')
              <div class="form-error-msg">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-field-group" style="margin-top: 16px;">
            <label for="description" class="form-label">Keterangan / Instruksi Pembayaran</label>
            <textarea id="description" name="description" class="form-control-input" rows="4">{{ old('description', $paymentMethod->description) }}</textarea>
            @error('description')
              <div class="form-error-msg">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-actions-bar" style="margin-top: 24px;">
            <a href="{{ route('admin.payment-method.index') }}" class="btn-form-cancel">Batal</a>
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
