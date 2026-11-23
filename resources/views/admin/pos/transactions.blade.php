<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Riwayat Transaksi Kasir — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/payment-index.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'pos_transactions'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    <!-- Header -->
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka navigasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard / Transaksi Kasir</span>
      </div>

      <div class="header-right">
        @php
          $sidebarPendingOrders = \App\Models\Order::whereRaw('LOWER(order_status) = ?', ['pending'])->count();
        @endphp
        <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          @if($sidebarPendingOrders > 0)
            <span class="notification-badge">{{ $sidebarPendingOrders }}</span>
          @endif
        </a>
      </div>
    </header>

    <!-- Main Content -->
    <main class="payment-content">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <a href="{{ route('admin.pos.index') }}">Menu Kasir</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">Riwayat Transaksi Kasir</span>
      </nav>

      {{-- Page Heading + Button POS + Search Row (Sejajar satu level horizontal) --}}
      <div class="payment-page-head">
        <div class="payment-head-left">
          <h1 class="payment-page-title">Riwayat Transaksi Kasir</h1>

          <a href="{{ route('admin.pos.index') }}" class="btn-pos-primary" title="Buka Mesin Kasir">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="export-icon">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            <span>Buka Mesin Kasir</span>
          </a>
        </div>

        {{-- Search Input (Satu level di kanan) --}}
        <form method="GET" action="{{ route('admin.pos.transactions') }}" class="payment-search-wrap">
          @if(request('filter'))
            <input type="hidden" name="filter" value="{{ request('filter') }}">
          @endif
          @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
          @endif
          @if(request('start_date'))
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
          @endif
          @if(request('end_date'))
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
          @endif
          @if(request('show_all'))
            <input type="hidden" name="show_all" value="{{ request('show_all') }}">
          @endif
          <span class="payment-search-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </span>
          <input type="text" name="search" class="payment-search-input" placeholder="Cari kode, kasir, pelanggan..." value="{{ request('search') }}" autocomplete="off">
        </form>
      </div>

      <!-- Stats Grid (4 Kotak Ringkasan Penjualan Kasir) -->
      <div class="payment-stats-grid">
        <div class="payment-stat-card">
          <div class="payment-stat-info">
            <span class="payment-stat-label">Total Omset Kasir</span>
            <span class="payment-stat-value val-navy">Rp {{ number_format($stats['all'] ?? 0, 0, ',', '.') }}</span>
            <span class="payment-stat-sub">{{ number_format($stats['all_count'] ?? 0) }} total transaksi</span>
          </div>
          <div class="payment-stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
          </div>
        </div>

        <div class="payment-stat-card">
          <div class="payment-stat-info">
            <span class="payment-stat-label">Hari Ini</span>
            <span class="payment-stat-value">Rp {{ number_format($stats['today'] ?? 0, 0, ',', '.') }}</span>
            <span class="payment-stat-sub">{{ number_format($stats['today_count'] ?? 0) }} transaksi selesai</span>
          </div>
          <div class="payment-stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 14 14"></polyline>
            </svg>
          </div>
        </div>

        <div class="payment-stat-card">
          <div class="payment-stat-info">
            <span class="payment-stat-label">Bulan Ini</span>
            <span class="payment-stat-value">Rp {{ number_format($stats['month'] ?? 0, 0, ',', '.') }}</span>
            <span class="payment-stat-sub">{{ number_format($stats['month_count'] ?? 0) }} transaksi selesai</span>
          </div>
          <div class="payment-stat-icon yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
          </div>
        </div>

        <div class="payment-stat-card">
          <div class="payment-stat-info">
            <span class="payment-stat-label">Total Transaksi</span>
            <span class="payment-stat-value">{{ number_format($stats['all_count'] ?? 0) }}</span>
            <span class="payment-stat-sub">Struk tercatat di sistem</span>
          </div>
          <div class="payment-stat-icon green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
        </div>
      </div>

      <!-- Filter Row: Tabs & Sortir -->
      <div class="payment-filter-row">
        <div class="filter-left-group">
          <span class="filter-sort-label">Filter:</span>
          <div class="filter-tabs">
            <a href="{{ route('admin.pos.transactions', array_merge(request()->query(), ['filter' => 'semua', 'page' => 1])) }}"
               class="filter-tab {{ request('filter', 'semua') === 'semua' ? 'active' : '' }}">
              Semua Transaksi
            </a>
            <a href="{{ route('admin.pos.transactions', array_merge(request()->query(), ['filter' => 'hari_ini', 'page' => 1])) }}"
               class="filter-tab {{ request('filter') === 'hari_ini' ? 'active' : '' }}">
              Hari Ini
            </a>
            <a href="{{ route('admin.pos.transactions', array_merge(request()->query(), ['filter' => 'bulan_ini', 'page' => 1])) }}"
               class="filter-tab {{ request('filter') === 'bulan_ini' ? 'active' : '' }}">
              Bulan Ini
            </a>
          </div>
        </div>

        <!-- Rentang Tanggal & Sortir Dropdown -->
        <form method="GET" action="{{ route('admin.pos.transactions') }}" class="filter-right-group">
          @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
          @endif
          @if(request('filter'))
            <input type="hidden" name="filter" value="{{ request('filter') }}">
          @endif
          @if(request('show_all'))
            <input type="hidden" name="show_all" value="{{ request('show_all') }}">
          @endif

          {{-- Rentang Tanggal --}}
          <div class="date-range-filter-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="sort-icon-svg" style="color: #244173;">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="this.form.submit()" title="Dari Tanggal">
            <span class="date-sep">s/d</span>
            <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="this.form.submit()" title="Sampai Tanggal">
            @if(request('start_date') || request('end_date'))
              <a href="{{ route('admin.pos.transactions', array_merge(request()->except(['start_date', 'end_date']), ['page' => 1])) }}" class="btn-date-reset" title="Hapus Filter Tanggal">&times;</a>
            @endif
          </div>

          <span class="filter-sort-label">
            Sortir:
          </span>
          <select name="sort" onchange="this.form.submit()" class="payment-sort-select">
            <option value="terbaru" {{ request('sort') === 'terbaru' || !request('sort') ? 'selected' : '' }}>Waktu Terbaru</option>
            <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Waktu Terlama</option>
            <option value="nominal_tinggi" {{ request('sort') === 'nominal_tinggi' ? 'selected' : '' }}>Nominal Tertinggi</option>
            <option value="nominal_rendah" {{ request('sort') === 'nominal_rendah' ? 'selected' : '' }}>Nominal Terendah</option>
          </select>
        </form>
      </div>

      <!-- Data Table Card -->
      <div class="payment-table-card">
        <div class="table-responsive-wrapper">
          <table class="payment-table">
            <thead>
              <tr>
                <th>Kode Transaksi</th>
                <th>Waktu</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Item Terjual</th>
                <th>Total Tagihan</th>
                <th>Metode</th>
                <th style="text-align: right; padding-right: 20px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($transactions as $t)
                @php
                  $cashierName = $t->cashier->name ?? 'Admin Kasir';
                  $customerName = $t->customer_name ?: 'Pelanggan Langsung';
                  $customerInitial = strtoupper(substr($customerName, 0, 1));
                  $methodUpper = strtoupper($t->payment_method ?? 'TUNAI');
                @endphp
                <tr>
                  <td>
                    <span class="col-id-text">{{ $t->transaction_code }}</span>
                  </td>
                  <td>
                    <span class="col-date-text">
                      {{ $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-' }}
                    </span>
                  </td>
                  <td>
                    <div class="user-name-text" style="font-size: 13px;">{{ $cashierName }}</div>
                  </td>
                  <td>
                    <div class="payment-user-cell">
                      <div class="payment-user-avatar">
                        {{ $customerInitial }}
                      </div>
                      <div class="payment-user-meta">
                        <div class="user-name-text">{{ $customerName }}</div>
                        <div class="user-email-text">In-Store Customer</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="badge-item-pill">
                      {{ $t->items->sum('quantity') }} pcs
                    </div>
                    <div class="badge-item-sub">
                      {{ $t->items->count() }} jenis produk
                    </div>
                  </td>
                  <td>
                    <span class="col-price-text">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</span>
                  </td>
                  <td>
                    <span class="payment-status-badge status-sukses">
                      <span class="badge-dot"></span>
                      {{ $methodUpper }}
                    </span>
                  </td>
                  <td style="text-align: right; padding-right: 20px;">
                    <div class="payment-actions-wrap">
                      <a href="{{ route('admin.pos.show', $t->id) }}" class="btn-action-receipt" title="Lihat Struk Transaksi">
                        Lihat Struk
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="payment-empty-row">
                    Belum ada riwayat transaksi kasir yang sesuai dengan filter atau kata kunci pencarian.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Footer Paginasi 10 Per Halaman Standar (Konsisten dengan Pesanan & Pembayaran) --}}
        <div class="payment-footer-row">
          <div class="footer-info-left">
            <a href="{{ request()->fullUrlWithQuery(['show_all' => 'true', 'page' => 1]) }}" class="btn-pill-all {{ request('show_all') === 'true' ? 'active' : '' }}">ALL</a>
            <a href="{{ request()->fullUrlWithQuery(['show_all' => null, 'page' => 1]) }}" class="link-show-less">Show Less</a>
            <span class="footer-sep">|</span>
            <span class="footer-count-text">
              Menampilkan {{ $transactions->count() }} transaksi dari {{ $transactions->total() }}
            </span>
          </div>

          @if(request('show_all') !== 'true' && $transactions->hasPages())
            <div class="pagination-pages" aria-label="Navigasi halaman">
              @if($transactions->onFirstPage())
                <span class="pag-btn disabled">&lt;</span>
              @else
                <a href="{{ $transactions->previousPageUrl() }}" class="pag-btn" aria-label="Halaman sebelumnya">&lt;</a>
              @endif

              @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="pag-btn {{ $page == $transactions->currentPage() ? 'active' : '' }}">{{ $page }}</a>
              @endforeach

              @if($transactions->hasMorePages())
                <a href="{{ $transactions->nextPageUrl() }}" class="pag-btn" aria-label="Halaman berikutnya">&gt;</a>
              @else
                <span class="pag-btn disabled">&gt;</span>
              @endif
            </div>
          @endif
        </div>
      </div>
    </main>
  </div>
</div>

</body>
</html>
