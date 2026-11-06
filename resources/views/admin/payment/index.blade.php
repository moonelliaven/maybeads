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

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'payments'])

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
          <h1 class="page-title">Riwayat Pembayaran</h1>
          <p class="page-subtitle">Kelola dan verifikasi transaksi keuangan pelanggan</p>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Total Pemasukan</span>
            <span class="stat-value" style="font-size: 21px; color: var(--navy);">Rp {{ number_format($stats['total_income'] ?? 0, 0, ',', '.') }}</span>
          </div>
          <div class="stat-icon-wrap green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Pembayaran Berhasil</span>
            <span class="stat-value">{{ number_format($stats['sukses'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Menunggu Konfirmasi</span>
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
            <span class="stat-label">Gagal / Dibatalkan</span>
            <span class="stat-value">{{ number_format($stats['gagal'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap red">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="15" y1="9" x2="9" y2="15"></line>
              <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
          </div>
        </div>
      </div>

      <!-- Filter Toolbar -->
      <div class="toolbar-card">
        <form method="GET" action="{{ route('admin.payment.index') }}" class="search-form">
          <div class="search-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="search" placeholder="Cari ID transaksi, nama, metode..." value="{{ request('search') }}">
          </div>
        </form>

        <form method="GET" action="{{ route('admin.payment.index') }}" class="filter-group">
          @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
          @endif
          <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="semua" {{ request('status') === 'semua' || !request('status') ? 'selected' : '' }}>Semua Status</option>
            <option value="sukses" {{ request('status') === 'sukses' ? 'selected' : '' }}>Sukses / Berhasil</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending / Menunggu</option>
            <option value="gagal" {{ request('status') === 'gagal' ? 'selected' : '' }}>Gagal / Batal</option>
          </select>
        </form>
      </div>

      <!-- Data Table -->
      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID Pembayaran</th>
              <th>ID Pesanan</th>
              <th>Pelanggan</th>
              <th>Metode</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th>Waktu Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($payments as $payment)
              @php
                $statusClass = match($payment->payment_status) {
                    'sukses'  => 'badge-selesai',
                    'pending' => 'badge-pending',
                    default   => 'badge-gagal'
                };
              @endphp
              <tr>
                <td><strong>#PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                <td>
                  <a href="{{ route('admin.order.show', $payment->order_id) }}" style="color: var(--navy); font-weight: 600; text-decoration: none;">
                    #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                  </a>
                </td>
                <td>
                  <div style="font-weight: 600; color: #1e293b;">{{ $payment->order->user->name ?? 'Guest / Kasir' }}</div>
                  <div style="font-size: 11.5px; color: #94a3b8;">{{ $payment->order->user->email ?? '-' }}</div>
                </td>
                <td>
                  <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: #64748b;">
                      <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                      <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                    {{ $payment->payment_method }}
                  </span>
                </td>
                <td><strong style="color: var(--navy);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                <td>
                  <span class="badge {{ $statusClass }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($payment->payment_status) }}
                  </span>
                </td>
                <td style="font-size: 12.5px; color: #64748b;">
                  {{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : '-' }}
                </td>
                <td>
                  <div class="table-actions">
                    <!-- Form konfirmasi cepat / update status -->
                    <form method="POST" action="{{ route('admin.payment.update', $payment->id) }}" style="margin: 0;">
                      @csrf
                      @method('PUT')
                      @if($payment->payment_status !== 'sukses')
                        <input type="hidden" name="payment_status" value="sukses">
                        <button type="submit" class="btn-action" title="Konfirmasi Lunas" style="color: #16a34a; border-color: #bbf7d0;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                          </svg>
                        </button>
                      @else
                        <input type="hidden" name="payment_status" value="pending">
                        <button type="submit" class="btn-action" title="Ubah ke Pending" style="color: #ca8a04;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                          </svg>
                        </button>
                      @endif
                    </form>

                    <form method="POST" action="{{ route('admin.payment.destroy', $payment->id) }}" onsubmit="return confirm('Hapus riwayat pembayaran ini?')">
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
                  Belum ada data pembayaran yang sesuai filter.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if($payments->hasPages())
          <div class="pagination-wrap">
            {{ $payments->links() }}
          </div>
        @endif
      </div>
    </main>
  </div>
</div>

</body>
</html>
