<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Manajemen Pembayaran — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/payment-index.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'pembayaran'])

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
        <span class="header-title">Admin Dashboard / Pembayaran</span>
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
        <span class="breadcrumb-current">Riwayat Pembayaran</span>
      </nav>

      @if(session('success'))
        <div class="payment-alert-success">
          {{ session('success') }}
        </div>
      @endif

      {{-- Page Header & Search Row (Sejajar satu level persis seperti Halaman Pesanan) --}}
      <div class="payment-page-head">
        <div class="payment-head-left">
          <h1 class="payment-page-title">Riwayat Pembayaran</h1>

          <a href="{{ route('admin.payment.export', ['status' => request('status', 'semua')]) }}" class="btn-export-payments">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="export-icon">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Ekspor CSV</span>
          </a>
        </div>

        {{-- Search Input (Satu level di kanan) --}}
        <form method="GET" action="{{ route('admin.payment.index') }}" class="payment-search-wrap">
          @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
          @endif
          @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
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
          <input type="text" name="search" class="payment-search-input" placeholder="Cari ID pembayaran, pesanan, nama..." value="{{ request('search') }}" autocomplete="off">
        </form>
      </div>

      <!-- Stats Grid (4 Kotak Indikator) -->
      <div class="payment-stats-grid">
        <div class="payment-stat-card">
          <div class="payment-stat-info">
            <span class="payment-stat-label">Total Pemasukan</span>
            <span class="payment-stat-value val-navy">Rp {{ number_format($stats['total_income'] ?? 0, 0, ',', '.') }}</span>
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
            <span class="payment-stat-label">Pembayaran Berhasil</span>
            <span class="payment-stat-value">{{ number_format($stats['sukses'] ?? 0) }}</span>
          </div>
          <div class="payment-stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
        </div>

        <div class="payment-stat-card">
          <div class="payment-stat-info">
            <span class="payment-stat-label">Menunggu Konfirmasi</span>
            <span class="payment-stat-value">{{ number_format($stats['pending'] ?? 0) }}</span>
          </div>
          <div class="payment-stat-icon yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
          </div>
        </div>

        <div class="payment-stat-card">
          <div class="payment-stat-info">
            <span class="payment-stat-label">Gagal / Dibatalkan</span>
            <span class="payment-stat-value">{{ number_format($stats['gagal'] ?? 0) }}</span>
          </div>
          <div class="payment-stat-icon red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="15" y1="9" x2="9" y2="15"></line>
              <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
          </div>
        </div>
      </div>

      <!-- Filter Row (Tabs Status di Kiri & Sortir Waktu di Kanan) -->
      <div class="payment-filter-row">
        <div class="filter-left-group">
          <span class="filter-sort-label">Status:</span>
          <div class="filter-tabs">
            <a href="{{ route('admin.payment.index', array_merge(request()->query(), ['status' => 'semua', 'page' => 1])) }}"
               class="filter-tab {{ request('status', 'semua') === 'semua' ? 'active' : '' }}">
              Semua
            </a>
            <a href="{{ route('admin.payment.index', array_merge(request()->query(), ['status' => 'sukses', 'page' => 1])) }}"
               class="filter-tab {{ request('status') === 'sukses' ? 'active' : '' }}">
              Sukses
            </a>
            <a href="{{ route('admin.payment.index', array_merge(request()->query(), ['status' => 'pending', 'page' => 1])) }}"
               class="filter-tab {{ request('status') === 'pending' ? 'active' : '' }}">
              Pending
            </a>
            <a href="{{ route('admin.payment.index', array_merge(request()->query(), ['status' => 'gagal', 'page' => 1])) }}"
               class="filter-tab {{ request('status') === 'gagal' ? 'active' : '' }}">
              Gagal / Batal
            </a>
          </div>
        </div>

        <!-- Sortir Waktu & Nominal -->
        <form method="GET" action="{{ route('admin.payment.index') }}" class="filter-right-group">
          @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
          @endif
          @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
          @endif
          @if(request('show_all'))
            <input type="hidden" name="show_all" value="{{ request('show_all') }}">
          @endif
          
          <span class="filter-sort-label">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="sort-icon-svg">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
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
                <th>ID Pembayaran</th>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Metode</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Waktu Bayar</th>
                <th style="text-align: right; padding-right: 20px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($payments as $payment)
                @php
                  $rawStatus = strtolower($payment->payment_status ?? 'pending');
                  $statusClass = match($rawStatus) {
                      'sukses', 'paid', 'lunas', 'berhasil' => 'status-sukses',
                      'pending', 'menunggu'                  => 'status-pending',
                      'proses', 'diproses'                   => 'status-proses',
                      'dibatalkan', 'cancelled'              => 'status-dibatalkan',
                      default                                => 'status-gagal'
                  };
                  $userName = $payment->order->user->name ?? 'Pelanggan Kasir';
                  $userEmail = $payment->order->user->email ?? '-';
                  $avatarInitial = strtoupper(substr($userName, 0, 1));
                @endphp
                <tr>
                  <td>
                    <span class="col-id-text">#PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</span>
                  </td>
                  <td>
                    <a href="{{ route('admin.order.show', $payment->order_id) }}" class="col-order-link">
                      #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                    </a>
                  </td>
                  <td>
                    <div class="payment-user-cell">
                      <div class="payment-user-avatar">
                        {{ $avatarInitial }}
                      </div>
                      <div class="payment-user-meta">
                        <div class="user-name-text">{{ $userName }}</div>
                        <div class="user-email-text">{{ $userEmail }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="col-method-text">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="method-icon-svg">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                      </svg>
                      {{ $payment->payment_method }}
                    </span>
                  </td>
                  <td>
                    <span class="col-price-text">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                  </td>
                  <td>
                    <span class="payment-status-badge {{ $statusClass }}">
                      <span class="badge-dot"></span>
                      {{ ucfirst($payment->payment_status) }}
                    </span>
                  </td>
                  <td>
                    <span class="col-date-text">
                      {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : ($payment->created_at ? $payment->created_at->format('d/m/Y H:i') : '-') }}
                    </span>
                  </td>
                  <td style="text-align: right; padding-right: 20px;">
                    <div class="payment-actions-wrap">
                      <!-- Tombol Lihat Struk (Serasi dengan Detail di Halaman Pesanan) -->
                      <a href="{{ route('admin.payment.show', $payment->id) }}" class="btn-action-receipt" title="Lihat Struk Pembayaran">
                        Lihat Struk
                      </a>

                      <!-- Form konfirmasi cepat status -->
                      <form method="POST" action="{{ route('admin.payment.update', $payment->id) }}" class="payment-inline-form">
                        @csrf
                        @method('PUT')
                        @if($payment->payment_status !== 'sukses')
                          <input type="hidden" name="payment_status" value="sukses">
                          <button type="submit" class="btn-action-icon btn-action-success" title="Konfirmasi Lunas">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                          </button>
                        @else
                          <input type="hidden" name="payment_status" value="pending">
                          <button type="submit" class="btn-action-icon btn-action-pending" title="Ubah ke Pending">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <circle cx="12" cy="12" r="10"></circle>
                              <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                          </button>
                        @endif
                      </form>

                      <!-- Hapus -->
                      <form method="POST" action="{{ route('admin.payment.destroy', $payment->id) }}" onsubmit="return confirm('Hapus riwayat pembayaran ini?')" class="payment-inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action-icon btn-action-delete" title="Hapus Riwayat">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="payment-empty-row">
                    Belum ada data pembayaran yang sesuai filter pencarian.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Footer Paginasi 10 Per Halaman Standar (Konsisten dengan Pesanan) --}}
        <div class="payment-footer-row">
          <div class="footer-info-left">
            <a href="{{ request()->fullUrlWithQuery(['show_all' => 'true', 'page' => 1]) }}" class="btn-pill-all {{ request('show_all') === 'true' ? 'active' : '' }}">ALL</a>
            <a href="{{ request()->fullUrlWithQuery(['show_all' => null, 'page' => 1]) }}" class="link-show-less">Show Less</a>
            <span class="footer-sep">|</span>
            <span class="footer-count-text">
              Menampilkan {{ $payments->count() }} transaksi dari {{ $totalPayments }}
            </span>
          </div>

          @if(request('show_all') !== 'true' && $payments->hasPages())
            <div class="pagination-pages" aria-label="Navigasi halaman">
              @if($payments->onFirstPage())
                <span class="pag-btn disabled">&lt;</span>
              @else
                <a href="{{ $payments->previousPageUrl() }}" class="pag-btn" aria-label="Halaman sebelumnya">&lt;</a>
              @endif

              @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="pag-btn {{ $page == $payments->currentPage() ? 'active' : '' }}">{{ $page }}</a>
              @endforeach

              @if($payments->hasMorePages())
                <a href="{{ $payments->nextPageUrl() }}" class="pag-btn" aria-label="Halaman berikutnya">&gt;</a>
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
