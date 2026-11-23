<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Semua Pesanan — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/order-index.css', 'resources/js/admin/order-index.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'pesanan'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    {{-- ====== HEADER ====== --}}
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle"
                aria-label="Toggle navigasi" aria-controls="adminSidebar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard</span>
      </div>

      <div class="header-right">
        {{-- Notifikasi --}}
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

    {{-- ====== MAIN CONTENT ====== --}}
    <main class="order-content">

      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">{{ ($statusFilter ?? '') === 'pending' ? 'Pending Order' : 'Pesanan' }}</span>
      </nav>

      {{-- Page Heading + Ekspor CSV Sejajar --}}
      <div class="order-page-head">
        <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
          <h1 class="order-page-title">{{ ($statusFilter ?? '') === 'pending' ? 'Pending Order' : 'Semua Pesanan' }}</h1>

          <a href="{{ route('admin.order.export', ['status' => request('status', 'semua')]) }}" class="btn-export-orders" style="display: inline-flex; align-items: center; gap: 7px; background: #ffffff; border: 1.5px solid #cbd5e1; color: #1e293b; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.15s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 15px; height: 15px; color: #2563eb;">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
              <polyline points="7 10 12 15 17 10"></polyline>
              <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Ekspor CSV</span>
          </a>
        </div>
      </div>

      {{-- Stat Cards (5 Kotak Statistik — Bagian Pemasukan Dihapus) --}}
      <div class="order-stats-grid" style="grid-template-columns: repeat(5, 1fr);">
        {{-- 1. Total Pesanan --}}
        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Total Pesanan</span>
            <span class="stat-dot dot-blue"></span>
          </div>
          <div class="stat-number">
            {{ str_pad((string)($stats['total'] ?? 0), 3, '0', STR_PAD_LEFT) }}
          </div>
        </div>

        {{-- 2. Pending --}}
        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Pending</span>
            <span class="stat-dot dot-orange"></span>
          </div>
          <div class="stat-number">
            {{ $stats['pending'] ?? 0 }}
          </div>
        </div>

        {{-- 3. Process --}}
        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Process</span>
            <span class="stat-dot dot-yellow"></span>
          </div>
          <div class="stat-number">
            {{ $stats['process'] ?? 0 }}
          </div>
        </div>

        {{-- 4. Selesai --}}
        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Selesai</span>
            <span class="stat-dot dot-green"></span>
          </div>
          <div class="stat-number">
            {{ $stats['selesai'] ?? 0 }}
          </div>
        </div>

        {{-- 5. Dibatalkan --}}
        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Dibatalkan</span>
            <span class="stat-dot" style="background-color: #94a3b8;"></span>
          </div>
          <div class="stat-number">
            {{ $stats['dibatalkan'] ?? 0 }}
          </div>
        </div>
      </div>

      {{-- Filter Tabs (Sortir) & Search Bar Sejajar --}}
      <div class="order-filter-row" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
          <span class="filter-sort-label">Sortir:</span>
          <div class="filter-tabs" role="tablist">
            <button type="button" class="filter-tab {{ ($statusFilter ?? 'semua') === 'semua' ? 'active' : '' }}" data-tab="semua">Semua</button>
            <button type="button" class="filter-tab {{ ($statusFilter ?? '') === 'pending' ? 'active' : '' }}" data-tab="pending">Pending</button>
            <button type="button" class="filter-tab {{ in_array($statusFilter ?? '', ['process', 'proses']) ? 'active' : '' }}" data-tab="process">Process</button>
            <button type="button" class="filter-tab {{ ($statusFilter ?? '') === 'selesai' ? 'active' : '' }}" data-tab="selesai">Selesai</button>
            <button type="button" class="filter-tab {{ ($statusFilter ?? '') === 'dibatalkan' ? 'active' : '' }}" data-tab="dibatalkan">Dibatalkan</button>
          </div>
        </div>

        <div class="order-search-wrap" style="max-width: 300px; width: 100%;">
          <span class="search-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </span>
          <input type="text"
                 id="orderSearchInput"
                 class="order-search-input"
                 value="{{ $search ?? '' }}"
                 placeholder="Cari Pesanan..."
                 autocomplete="off"
                 aria-label="Cari data pesanan">
        </div>
      </div>

      {{-- ====== TABLE LAYOUT ====== --}}
      <div class="order-table-wrap">
        <table class="order-table" id="orderTable">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Username</th>
              <th>Jenis Pengantaran</th>
              <th>Total Harga</th>
              <th>Status Pembayaran</th>
              <th>Status Orderan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="orderTableBody">
            @forelse ($orders as $order)
              @php
                $avatarInitial = strtoupper(substr($order->user->name ?? 'U', 0, 1));
                $colors = ['#2563eb', '#7c3aed', '#059669', '#ea580c', '#db2777', '#0891b2', '#4b5563'];
                $bgColor = $colors[($order->user_id ?? 1) % count($colors)];

                // Helper class status
                $payStatus = strtolower($order->payment_status ?? 'pending');
                $payClass = match($payStatus) {
                  'sukses', 'success', 'paid' => 'status-sukses',
                  'proses', 'process'         => 'status-proses',
                  'dibatalkan', 'cancelled'   => 'status-dibatalkan',
                  'gagal', 'failed'           => 'status-gagal',
                  default                     => 'status-pending',
                };

                $ordStatus = strtolower($order->order_status ?? 'pending');
                $ordClass = match($ordStatus) {
                  'sukses', 'selesai', 'success' => 'status-sukses',
                  'proses', 'process', 'diproses' => 'status-proses',
                  'dibatalkan', 'cancelled'      => 'status-dibatalkan',
                  default                        => 'status-pending',
                };
              @endphp
              <tr data-id="{{ $order->id }}" data-status="{{ strtolower($order->order_status) }}">
                <td class="col-id">{{ $order->formatted_id }}</td>
                <td class="col-user">
                  <div class="user-meta-cell">
                    <div class="user-cell-avatar" style="background-color: {{ $bgColor }}">
                      @if (!empty($order->user->avatar ?? null))
                        <img src="{{ asset('storage/avatars/' . $order->user->avatar) }}" alt="{{ $order->user->name }}">
                      @else
                        {{ $avatarInitial }}
                      @endif
                    </div>
                    <span class="user-cell-name">{{ $order->user->name ?? 'User' }}</span>
                  </div>
                </td>
                <td class="col-delivery">{{ $order->delivery_type }}</td>
                <td class="col-price">{{ number_format($order->subtotal, 2, ',', '.') }}</td>
                <td class="col-payment-status">
                  <span class="status-pill {{ $payClass }}">
                    <span class="status-pill-dot"></span>
                    {{ ucfirst($order->payment_status ?? 'Pending') }}
                  </span>
                </td>
                <td class="col-order-status">
                  <span class="status-pill {{ $ordClass }}">
                    <span class="status-pill-dot"></span>
                    {{ ucfirst($order->order_status ?? 'Pending') }}
                  </span>
                </td>
                <td class="col-action">
                  <div class="action-btns">
                    <a href="{{ route('admin.order.show', $order->id) }}" class="btn-detail">Detail</a>
                  </div>
                </td>
              </tr>
            @empty
              <tr id="orderEmptyRow" class="empty-row">
                <td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">
                  Belum ada data pesanan.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        {{-- Pagination Bar --}}
        <div class="pagination-bar">
          <div class="pagination-left">
            <button type="button" class="btn-pag-all" id="btnPagAll">ALL</button>
            <button type="button" class="btn-pag-less" id="btnPagLess">Show Less</button>
            <div class="pag-sep"></div>
            <span class="pag-info" id="paginationInfo">
              Menampilkan {{ count($orders) > 0 ? min(8, count($orders)) : 0 }} produk dari {{ count($orders) }}
            </span>
          </div>
          <div class="pagination-pages" aria-label="Navigasi halaman">
            <button type="button" class="pag-btn" aria-label="Halaman sebelumnya">&lt;</button>
            <button type="button" class="pag-btn active">1</button>
            <button type="button" class="pag-btn">2</button>
            <button type="button" class="pag-btn">3</button>
            <button type="button" class="pag-btn">4</button>
            <span style="font-size: 11px; color: #94a3b8; padding: 0 4px;">...</span>
            <button type="button" class="pag-btn" aria-label="Halaman berikutnya">&gt;</button>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

</body>
</html>

