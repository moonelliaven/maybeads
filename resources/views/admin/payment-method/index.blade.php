<!DOCTYPE html>
<html lang="id">
@php
  $methods = $methods ?? collect();
  $stats = $stats ?? [
    'total_revenue'         => 0,
    'total_transactions'    => 0,
    'bank_revenue'          => 0,
    'bank_trx_count'        => 0,
    'autolink_revenue'      => 0,
    'autolink_trx_count'    => 0,
    'qris_revenue'          => 0,
    'qris_trx_count'        => 0,
    'ewallet_revenue'       => 0,
    'ewallet_trx_count'     => 0,
    'active_methods_count'  => 0,
    'total_methods_count'   => 0,
    'avg_admin_fee'         => 0,
  ];
  $activeTab = $activeTab ?? 'semua';
  $search = $search ?? '';
  $statusFilter = $statusFilter ?? 'semua';
@endphp
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Metode Pembayaran (Payment Method Management) — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/payment-method.css', 'resources/js/admin/payment-method.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'payment-method'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    {{-- Header --}}
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

    {{-- Main Content --}}
    <main class="payment-method-content">

      {{-- Flash Message Alerts --}}
      @if (session('success'))
        <div class="alert alert-success" role="alert">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-error" role="alert">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-section">Management</span>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">Metode Pembayaran</span>
      </nav>

      {{-- Section Info Box: Perbedaan Menu Utama vs Management --}}
      <div class="scope-info-banner">
        <div class="scope-icon-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
          </svg>
        </div>
        <div class="scope-text-wrap">
          <div class="scope-title">Struktur Navigasi & Pemisahan Fitur Pembayaran:</div>
          <div class="scope-desc">
            <span class="scope-pill scope-pill-main">Menu Utama &gt; Pembayaran</span> : Khusus memuat <strong>Data Riwayat Transaksi & Bukti Pembayaran Struk</strong> dari pelanggan.
            <br>
            <span class="scope-pill scope-pill-mgmt">Management &gt; Metode Pembayaran</span> : Khusus <strong>Konfigurasi Pengaturan Metode Pembayaran</strong> dari admin (Transfer Bank, Auto-Link, QRIS, E-Wallet), menentukan biaya admin, serta memantau akumulasi total harga & transaksi per wallet.
          </div>
        </div>
      </div>

      {{-- Top Header Row --}}
      <div class="content-top-bar">
        <div>
          <h1 class="page-title">Metode Pembayaran</h1>
          <p class="page-subtitle">Kelola metode transaksi (Bank, Auto-Link, QRIS, E-Wallet), tentukan biaya admin, dan pantau akumulasi omset per wallet.</p>
        </div>

        <div class="top-action-buttons">
          <button type="button" class="btn-primary-add" id="btnOpenAddModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Metode Baru</span>
          </button>
        </div>
      </div>

      {{-- KPI Statistik Akumulasi Harga & Transaksi --}}
      <div class="stats-overview-grid">

        {{-- Card 1: Total Akumulasi Keseluruhan --}}
        <div class="stat-kpi-card highlight-card">
          <div class="stat-kpi-head">
            <span class="stat-kpi-label">Total Akumulasi Transaksi</span>
            <div class="stat-kpi-icon icon-gold">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
            </div>
          </div>
          <div class="stat-kpi-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
          <div class="stat-kpi-sub">
            <span class="sub-badge-count">{{ $stats['total_transactions'] }} Transaksi Sukses</span>
            <span class="sub-text-muted">dari seluruh metode</span>
          </div>
        </div>

        {{-- Card 2: Akumulasi QRIS --}}
        <div class="stat-kpi-card">
          <div class="stat-kpi-head">
            <span class="stat-kpi-label">Akumulasi QRIS</span>
            <div class="stat-kpi-icon icon-orange">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
              </svg>
            </div>
          </div>
          <div class="stat-kpi-value">Rp {{ number_format($stats['qris_revenue'], 0, ',', '.') }}</div>
          <div class="stat-kpi-sub">
            <span class="sub-badge-count">{{ $stats['qris_trx_count'] }} Transaksi</span>
            <span class="sub-text-muted">Scan QR instan</span>
          </div>
        </div>

        {{-- Card 3: Akumulasi Transfer Bank --}}
        <div class="stat-kpi-card">
          <div class="stat-kpi-head">
            <span class="stat-kpi-label">Akumulasi Transfer Bank</span>
            <div class="stat-kpi-icon icon-blue">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 21h18"></path>
                <path d="M3 10h18"></path>
                <path d="M5 6l7-3 7 3"></path>
                <path d="M4 10v11"></path>
                <path d="M20 10v11"></path>
                <path d="M8 14v4"></path>
                <path d="M12 14v4"></path>
                <path d="M16 14v4"></path>
              </svg>
            </div>
          </div>
          <div class="stat-kpi-value">Rp {{ number_format($stats['bank_revenue'], 0, ',', '.') }}</div>
          <div class="stat-kpi-sub">
            <span class="sub-badge-count">{{ $stats['bank_trx_count'] }} Transaksi</span>
            <span class="sub-text-muted">BCA, Mandiri, BRI, dll.</span>
          </div>
        </div>

        {{-- Card 4: Akumulasi Auto-Link --}}
        <div class="stat-kpi-card">
          <div class="stat-kpi-head">
            <span class="stat-kpi-label">Akumulasi Auto-Link</span>
            <div class="stat-kpi-icon icon-purple">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
              </svg>
            </div>
          </div>
          <div class="stat-kpi-value">Rp {{ number_format($stats['autolink_revenue'], 0, ',', '.') }}</div>
          <div class="stat-kpi-sub">
            <span class="sub-badge-count">{{ $stats['autolink_trx_count'] }} Transaksi</span>
            <span class="sub-text-muted">Debit Otomatis Terhubung</span>
          </div>
        </div>

        {{-- Card 5: Akumulasi E-Wallet --}}
        <div class="stat-kpi-card">
          <div class="stat-kpi-head">
            <span class="stat-kpi-label">Akumulasi E-Wallet</span>
            <div class="stat-kpi-icon icon-green">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                <line x1="2" y1="10" x2="22" y2="10"></line>
              </svg>
            </div>
          </div>
          <div class="stat-kpi-value">Rp {{ number_format($stats['ewallet_revenue'], 0, ',', '.') }}</div>
          <div class="stat-kpi-sub">
            <span class="sub-badge-count">{{ $stats['ewallet_trx_count'] }} Transaksi</span>
            <span class="sub-text-muted">GoPay, OVO, DANA, dll.</span>
          </div>
        </div>

      </div>

      {{-- Filters & Tabs Bar --}}
      <div class="filter-controls-wrap">

        {{-- Tabs Berdasarkan Tipe Metode --}}
        <div class="filter-tabs-list">
          <a href="{{ route('admin.payment-method.index', ['tab' => 'semua', 'q' => $search, 'status' => $statusFilter]) }}"
             class="tab-btn {{ $activeTab === 'semua' ? 'active' : '' }}">
            Semua Metode
            <span class="tab-badge">{{ $stats['total_methods_count'] }}</span>
          </a>
          <a href="{{ route('admin.payment-method.index', ['tab' => 'bank', 'q' => $search, 'status' => $statusFilter]) }}"
             class="tab-btn {{ $activeTab === 'bank' ? 'active' : '' }}">
            Transfer Bank
          </a>
          <a href="{{ route('admin.payment-method.index', ['tab' => 'auto-link', 'q' => $search, 'status' => $statusFilter]) }}"
             class="tab-btn {{ $activeTab === 'auto-link' ? 'active' : '' }}">
            Auto-Link
          </a>
          <a href="{{ route('admin.payment-method.index', ['tab' => 'qris', 'q' => $search, 'status' => $statusFilter]) }}"
             class="tab-btn {{ $activeTab === 'qris' ? 'active' : '' }}">
            QRIS
          </a>
          <a href="{{ route('admin.payment-method.index', ['tab' => 'e-wallet', 'q' => $search, 'status' => $statusFilter]) }}"
             class="tab-btn {{ $activeTab === 'e-wallet' ? 'active' : '' }}">
            E-Wallet
          </a>
        </div>

        {{-- Form Pencarian & Filter Status --}}
        <form method="GET" action="{{ route('admin.payment-method.index') }}" class="search-filter-form">
          <input type="hidden" name="tab" value="{{ $activeTab }}">

          <div class="status-select-wrap">
            <select name="status" class="filter-select" onchange="this.form.submit()">
              <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Status</option>
              <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif</option>
              <option value="non-active" {{ $statusFilter === 'non-active' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
          </div>

          <div class="search-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="search-icon">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text"
                   name="q"
                   value="{{ $search }}"
                   placeholder="Cari nama metode, kode, rekening..."
                   class="search-input">
            @if ($search)
              <a href="{{ route('admin.payment-method.index', ['tab' => $activeTab, 'status' => $statusFilter]) }}" class="clear-search-btn" title="Hapus pencarian">&times;</a>
            @endif
          </div>
        </form>

      </div>

      {{-- Table Card: List Payment Methods --}}
      <div class="table-card-box">
        <div class="table-card-header">
          <div>
            <h2 class="table-card-title">Daftar Metode Pembayaran Tersedia</h2>
            <p class="table-card-desc">Menampilkan {{ $methods->count() }} metode pembayaran terdaftar.</p>
          </div>
          <div class="table-header-meta">
            <span class="active-summary-badge">{{ $stats['active_methods_count'] }} Aktif dari {{ $stats['total_methods_count'] }} Metode</span>
          </div>
        </div>

        <div class="table-responsive">
          <table class="payment-methods-table">
            <thead>
              <tr>
                <th>Metode & Provider</th>
                <th>Tipe Kategori</th>
                <th>No. Rekening / Akun / Merchant</th>
                <th>Biaya Admin</th>
                <th>Akumulasi Harga Masuk</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($methods as $item)
                @php
                  $typeClass = match(strtolower($item->type)) {
                      'bank'      => 'type-pill-bank',
                      'auto-link' => 'type-pill-autolink',
                      'qris'      => 'type-pill-qris',
                      'e-wallet'  => 'type-pill-ewallet',
                      default     => 'type-pill-ewallet',
                  };
                @endphp
                <tr>

                  {{-- Metode & Provider --}}
                  <td>
                    <div class="method-brand-cell">
                      <div class="method-brand-icon {{ $typeClass }}-icon">
                        @if ($item->type === 'bank')
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 21h18"></path>
                            <path d="M3 10h18"></path>
                            <path d="M5 6l7-3 7 3"></path>
                            <path d="M4 10v11"></path>
                            <path d="M20 10v11"></path>
                          </svg>
                        @elseif ($item->type === 'auto-link')
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                          </svg>
                        @elseif ($item->type === 'qris')
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                          </svg>
                        @else
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                          </svg>
                        @endif
                      </div>
                      <div class="method-brand-info">
                        <span class="method-title-text">{{ $item->name }}</span>
                        <div class="code-copy-inline">
                          <span class="code-slug-tag">code: {{ $item->code }}</span>
                          <span class="method-desc-snippet">{{ Str::limit($item->description, 36) }}</span>
                        </div>
                      </div>
                    </div>
                  </td>

                  {{-- Tipe Kategori --}}
                  <td>
                    <span class="type-pill {{ $typeClass }}">
                      {{ $item->type_label }}
                    </span>
                  </td>

                  {{-- No Rekening / Akun / Merchant --}}
                  <td>
                    @if ($item->account_number)
                      <div class="account-cell-info">
                        <span class="account-num-text">{{ $item->account_number }}</span>
                        <span class="account-name-text">a/n {{ $item->account_name ?: '-' }}</span>
                      </div>
                    @else
                      <span class="text-muted-dash">-</span>
                    @endif
                  </td>

                  {{-- Biaya Admin --}}
                  <td>
                    <div class="admin-fee-badge-wrap">
                      @if ($item->price > 0)
                        <span class="admin-fee-val">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                      @else
                        <span class="admin-fee-free">Gratis (Rp 0)</span>
                      @endif
                    </div>
                  </td>

                  {{-- Akumulasi Harga Masuk & Transaksi --}}
                  <td>
                    <div class="accumulated-cell">
                      <span class="accumulated-amount">Rp {{ number_format($item->total_accumulated, 0, ',', '.') }}</span>
                      <span class="accumulated-count">{{ $item->transactions_count }} transaksi sukses</span>
                    </div>
                  </td>

                  {{-- Status --}}
                  <td>
                    <form action="{{ route('admin.payment-method.toggleStatus', $item->id) }}" method="POST" class="status-toggle-form">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                              class="status-toggle-btn {{ $item->status === 'active' ? 'is-active' : 'is-inactive' }}"
                              title="Klik untuk {{ $item->status === 'active' ? 'menonaktifkan' : 'mengaktifkan' }}">
                        <span class="toggle-dot"></span>
                        <span class="toggle-text">{{ $item->status === 'active' ? 'Aktif' : 'Non-Aktif' }}</span>
                      </button>
                    </form>
                  </td>

                  {{-- Aksi --}}
                  <td style="text-align: right;">
                    <div class="action-buttons-cell">
                      <button type="button"
                              class="btn-action-edit"
                              data-id="{{ $item->id }}"
                              data-name="{{ $item->name }}"
                              data-code="{{ $item->code }}"
                              data-type="{{ $item->type }}"
                              data-account-number="{{ $item->account_number }}"
                              data-account-name="{{ $item->account_name }}"
                              data-price="{{ $item->price }}"
                              data-status="{{ $item->status }}"
                              data-description="{{ $item->description }}"
                              title="Edit Pengaturan Metode">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit</span>
                      </button>

                      <button type="button"
                              class="btn-action-delete"
                              data-id="{{ $item->id }}"
                              data-name="{{ $item->name }}"
                              title="Hapus Metode">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="3 6 5 6 21 6"></polyline>
                          <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                      </button>
                    </div>
                  </td>

                </tr>
              @empty
                <tr>
                  <td colspan="7">
                    <div class="empty-table-state">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                      </svg>
                      <p class="empty-title">Tidak ada metode pembayaran ditemukan.</p>
                      <p class="empty-desc">Coba sesuaikan kata kunci pencarian atau tambah metode baru melalui tombol di atas.</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

{{-- MODAL 1: Tambah Metode Pembayaran Baru --}}
<div class="modal-backdrop" id="addModalBackdrop" style="display: none;">
  <div class="modal-card">
    <div class="modal-header">
      <h3 class="modal-title">Tambah Metode Pembayaran Baru</h3>
      <button type="button" class="btn-modal-close" id="btnCloseAddModal">&times;</button>
    </div>

    <form action="{{ route('admin.payment-method.store') }}" method="POST">
      @csrf

      <div class="modal-body">
        <p class="modal-desc">
          Konfigurasi opsi pembayaran baru yang dapat dipilih pembeli (Bank, Auto-Link, QRIS, atau E-Wallet).
        </p>

        {{-- Kategori Tipe --}}
        <div class="form-row-2">
          <div class="form-group">
            <label for="addType" class="form-label">Tipe / Kategori <span class="required">*</span></label>
            <select name="type" id="addType" class="form-select" required>
              <option value="bank">Transfer Bank (BCA, Mandiri, BRI, dll.)</option>
              <option value="auto-link">Auto-Link (Debit Terhubung)</option>
              <option value="qris">QRIS (Scan QR Dinamis / Merchant)</option>
              <option value="e-wallet" selected>E-Wallet (GoPay, OVO, DANA, ShopeePay)</option>
            </select>
          </div>

          <div class="form-group">
            <label for="addStatus" class="form-label">Status Awal <span class="required">*</span></label>
            <select name="status" id="addStatus" class="form-select" required>
              <option value="active" selected>Aktif (Bisa Dipilih)</option>
              <option value="non-active">Non-Aktif (Dinonaktifkan)</option>
            </select>
          </div>
        </div>

        {{-- Nama Metode & Kode Unik --}}
        <div class="form-row-2">
          <div class="form-group">
            <label for="addName" class="form-label">Nama Metode <span class="required">*</span></label>
            <input type="text" name="name" id="addName" class="form-input" placeholder="contoh: Transfer Bank BCA" required>
          </div>

          <div class="form-group">
            <label for="addCode" class="form-label">Kode Unik / Slug <span class="required">*</span></label>
            <input type="text" name="code" id="addCode" class="form-input" placeholder="contoh: bca, qris_dinamis" required>
          </div>
        </div>

        {{-- No Rekening & A/n Pemilik --}}
        <div class="form-row-2">
          <div class="form-group">
            <label for="addAccountNumber" class="form-label">No. Rekening / No. HP / Merchant ID</label>
            <input type="text" name="account_number" id="addAccountNumber" class="form-input" placeholder="contoh: 7820192837 atau 0812...">
          </div>

          <div class="form-group">
            <label for="addAccountName" class="form-label">Atas Nama Pemilik Akun</label>
            <input type="text" name="account_name" id="addAccountName" class="form-input" placeholder="contoh: CV Maybeads Indonesia">
          </div>
        </div>

        {{-- Biaya Admin --}}
        <div class="form-group">
          <label for="addPrice" class="form-label">Biaya Admin (Rupiah) <span class="required">*</span></label>
          <div class="input-prefix-group">
            <span class="input-prefix">Rp</span>
            <input type="number" name="price" id="addPrice" class="form-input" value="500" min="0" step="100" required>
          </div>
          <span class="form-hint">Tentukan biaya admin tambahan saat checkout (misal Rp 500 atau isi 0 jika gratis).</span>
        </div>

        {{-- Deskripsi / Petunjuk --}}
        <div class="form-group">
          <label for="addDescription" class="form-label">Deskripsi / Petunjuk Pembayaran</label>
          <textarea name="description" id="addDescription" rows="2" class="form-textarea" placeholder="contoh: Transfer ke rekening resmi BCA, pembayaran diproses otomatis..."></textarea>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn-modal-secondary" id="btnCancelAddModal">Batal</button>
        <button type="submit" class="btn-modal-primary">Simpan Metode Baru</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL 2: Edit Metode Pembayaran --}}
<div class="modal-backdrop" id="editModalBackdrop" style="display: none;">
  <div class="modal-card">
    <div class="modal-header">
      <h3 class="modal-title">Edit Metode Pembayaran</h3>
      <button type="button" class="btn-modal-close" id="btnCloseEditModal">&times;</button>
    </div>

    <form id="editMethodForm" method="POST" action="">
      @csrf
      @method('PATCH')

      <div class="modal-body">
        <p class="modal-desc">
          Perbarui pengaturan metode, nomor rekening akun, dan penentuan biaya admin.
        </p>

        {{-- Kategori Tipe --}}
        <div class="form-row-2">
          <div class="form-group">
            <label for="editType" class="form-label">Tipe / Kategori <span class="required">*</span></label>
            <select name="type" id="editType" class="form-select" required>
              <option value="bank">Transfer Bank</option>
              <option value="auto-link">Auto-Link (Debit Terhubung)</option>
              <option value="qris">QRIS</option>
              <option value="e-wallet">E-Wallet</option>
            </select>
          </div>

          <div class="form-group">
            <label for="editStatus" class="form-label">Status <span class="required">*</span></label>
            <select name="status" id="editStatus" class="form-select" required>
              <option value="active">Aktif</option>
              <option value="non-active">Non-Aktif</option>
            </select>
          </div>
        </div>

        {{-- Nama Metode & Kode Unik --}}
        <div class="form-row-2">
          <div class="form-group">
            <label for="editName" class="form-label">Nama Metode <span class="required">*</span></label>
            <input type="text" name="name" id="editName" class="form-input" required>
          </div>

          <div class="form-group">
            <label for="editCode" class="form-label">Kode Unik / Slug <span class="required">*</span></label>
            <input type="text" name="code" id="editCode" class="form-input" required>
          </div>
        </div>

        {{-- No Rekening & A/n Pemilik --}}
        <div class="form-row-2">
          <div class="form-group">
            <label for="editAccountNumber" class="form-label">No. Rekening / No. HP / Merchant ID</label>
            <input type="text" name="account_number" id="editAccountNumber" class="form-input">
          </div>

          <div class="form-group">
            <label for="editAccountName" class="form-label">Atas Nama Pemilik Akun</label>
            <input type="text" name="account_name" id="editAccountName" class="form-input">
          </div>
        </div>

        {{-- Biaya Admin --}}
        <div class="form-group">
          <label for="editPrice" class="form-label">Biaya Admin (Rupiah) <span class="required">*</span></label>
          <div class="input-prefix-group">
            <span class="input-prefix">Rp</span>
            <input type="number" name="price" id="editPrice" class="form-input" min="0" step="100" required>
          </div>
          <span class="form-hint">Menentukan biaya admin per transaksi yang menggunakan metode ini.</span>
        </div>

        {{-- Deskripsi / Petunjuk --}}
        <div class="form-group">
          <label for="editDescription" class="form-label">Deskripsi / Petunjuk</label>
          <textarea name="description" id="editDescription" rows="2" class="form-textarea"></textarea>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn-modal-secondary" id="btnCancelEditModal">Batal</button>
        <button type="submit" class="btn-modal-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL 3: Konfirmasi Hapus --}}
<div class="modal-backdrop" id="deleteModalBackdrop" style="display: none;">
  <div class="modal-card modal-card-danger">
    <div class="modal-header">
      <h3 class="modal-title text-danger">Hapus Metode Pembayaran?</h3>
      <button type="button" class="btn-modal-close" id="btnCloseDeleteModal">&times;</button>
    </div>

    <form id="deleteMethodForm" method="POST" action="">
      @csrf
      @method('DELETE')

      <div class="modal-body">
        <div class="danger-alert-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="danger-icon">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
          </svg>
          <div>
            <strong>Peringatan Penghapusan</strong>
            <p>Metode pembayaran <strong id="deleteMethodNameText">...</strong> akan dihapus permanen dari sistem.</p>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-modal-secondary" id="btnCancelDeleteModal">Batal</button>
        <button type="submit" class="btn-modal-danger">Ya, Hapus Metode</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>

