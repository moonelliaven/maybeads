<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Manajemen Pesanan — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'orders'])

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
        <span class="header-title">Admin Dashboard / Pesanan</span>
      </div>

      <div class="header-right">
        <div class="header-user-badge">
          <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
          <div class="header-user-info">
            <span class="header-user-name">{{ Auth::user()->name ?? 'Administrator' }}</span>
            <span class="header-user-role">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="page-container">
      @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
          {{ session('success') }}
        </div>
      @endif

      <div class="page-header">
        <div>
          <h1 class="page-title">Manajemen Pesanan</h1>
          <p class="page-subtitle">Pantau dan kelola seluruh pesanan pelanggan secara real-time</p>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Total Pesanan</span>
            <span class="stat-value">{{ number_format($stats['total'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Pending</span>
            <span class="stat-value">{{ number_format($stats['pending'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap yellow">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Diproses</span>
            <span class="stat-value">{{ number_format($stats['proses'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="23 4 23 10 17 10"></polyline>
              <polyline points="1 20 1 14 7 14"></polyline>
              <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Selesai</span>
            <span class="stat-value">{{ number_format($stats['selesai'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
          </div>
        </div>
      </div>

      <!-- Filter Toolbar -->
      <div class="toolbar-card">
        <form method="GET" action="{{ route('admin.order.index') }}" class="search-form">
          <div class="search-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="search" placeholder="Cari ID pesanan, nama, produk..." value="{{ request('search') }}">
          </div>
        </form>

        <form method="GET" action="{{ route('admin.order.index') }}" class="filter-group">
          @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
          @endif
          <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="semua" {{ request('status') === 'semua' || !request('status') ? 'selected' : '' }}>Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
          </select>
        </form>
      </div>

      <!-- Data Table -->
      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID Pesanan</th>
              <th>Pelanggan</th>
              <th>Produk</th>
              <th>Subtotal</th>
              <th>Status Pesanan</th>
              <th>Status Bayar</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($orders as $order)
              @php
                $orderClass = match($order->order_status) {
                    'pending'    => 'badge-pending',
                    'proses'     => 'badge-proses',
                    'selesai'    => 'badge-selesai',
                    default      => 'badge-dibatalkan'
                };
                $payClass = match($order->payment_status) {
                    'lunas'      => 'badge-selesai',
                    default      => 'badge-pending'
                };
              @endphp
              <tr>
                <td><strong>#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                <td>
                  <div style="font-weight: 600; color: #1e293b;">{{ $order->user->name ?? 'Guest / Kasir' }}</div>
                  <div style="font-size: 11.5px; color: #94a3b8;">{{ $order->user->email ?? '-' }}</div>
                </td>
                <td>
                  <div style="font-weight: 600;">{{ $order->product->product_name ?? 'Produk Dihapus' }}</div>
                  <div style="font-size: 11.5px; color: #64748b;">Jumlah: {{ $order->quantity }} pcs</div>
                </td>
                <td><strong style="color: var(--navy);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                <td>
                  <span class="badge {{ $orderClass }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($order->order_status) }}
                  </span>
                </td>
                <td>
                  <span class="badge {{ $payClass }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($order->payment_status) }}
                  </span>
                </td>
                <td style="font-size: 12.5px; color: #64748b;">
                  {{ $order->order_at ? $order->order_at->format('d M Y, H:i') : '-' }}
                </td>
                <td>
                  <div class="table-actions">
                    <a href="{{ route('admin.order.show', $order->id) }}" class="btn-action" title="Lihat Detail Pesanan">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.order.destroy', $order->id) }}" onsubmit="return confirm('Hapus pesanan ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-action danger" title="Hapus">
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
                <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                  Belum ada data pesanan yang sesuai filter.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if($orders->hasPages())
          <div class="pagination-wrap">
            {{ $orders->links() }}
          </div>
        @endif
      </div>
    </main>
  </div>
</div>

</body>
</html>
