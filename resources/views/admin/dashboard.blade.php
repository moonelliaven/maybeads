<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/lenis.css', 'resources/css/admin/dashboard.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

@php
  /*
   | Data di bawah ini adalah contoh. Kirim dari controller dengan nama yang sama
   | ($stats, $orders, $income, $cashier, $transactions) dan blok ini otomatis dilewati.
   */
  $stats = $stats ?? ['total' => 207, 'selesai' => 28, 'proses' => 27, 'pending' => 5];

  $orders = $orders ?? [
    ['user' => 'moonelliaven', 'time' => '2026-12-26 14:02', 'status' => 'pending'],
    ['user' => 'rarasati',     'time' => '2026-12-26 13:40', 'status' => 'proses'],
    ['user' => 'dimasaji',     'time' => '2026-12-26 11:15', 'status' => 'selesai'],
    ['user' => 'nadiaputri',   'time' => '2026-12-25 19:22', 'status' => 'pending'],
    ['user' => 'fikrirz',      'time' => '2026-12-25 16:08', 'status' => 'proses'],
    ['user' => 'salsabila',    'time' => '2026-12-25 10:47', 'status' => 'selesai'],
    ['user' => 'moonelliaven', 'time' => '2026-12-24 21:30', 'status' => 'selesai'],
  ];

  $income  = $income  ?? ['total' => 207000, 'qris' => 75000, 'ewallet' => 132000];
  $cashier = $cashier ?? ['total' => 207000, 'qris' => 75000, 'ewallet' => 132000];

  $transactions = $transactions ?? [
    ['user' => 'moonelliaven', 'time' => '2026-12-26 14:02', 'status' => 'sukses'],
    ['user' => 'moonelliaven', 'time' => '2026-12-26 14:02', 'status' => 'sukses'],
  ];

  $statusDot = ['pending' => 'orange', 'proses' => 'yellow', 'selesai' => 'green', 'sukses' => 'green'];
  $pct = fn ($part, $whole) => $whole > 0 ? round($part / $whole * 100, 1) : 0;
  $totalOrders = max($stats['total'], 1);
@endphp

  <div class="admin-layout">
    @include('admin.sidebar', ['active' => 'dashboard'])

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
              <a href="/admin/orders?status=pending" class="notif-item" role="menuitem">
                <span class="status-dot orange"></span>
                <span>
                  <span class="notif-title">{{ $stats['pending'] }} pesanan menunggu diproses</span>
                  <span class="notif-sub">Periksa dan konfirmasi pesanan pending</span>
                </span>
              </a>
            </div>
          </div>

          <!-- Profil -->
          <div class="dropdown" data-dropdown>
            <button type="button" class="header-user-badge" aria-haspopup="true" aria-expanded="false" data-dropdown-trigger>
              <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'W', 0, 1)) }}</div>
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
              <a href="/" target="_blank" rel="noopener" class="dropdown-item" role="menuitem">Lihat toko</a>
              <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="dropdown-item danger" role="menuitem">Keluar</button>
              </form>
            </div>
          </div>
        </div>
      </header>

      <main class="dashboard-content">
        <!-- Judul halaman -->
        <div class="page-head">
          <div>
            <div class="breadcrumb-label">Admin Dashboard</div>
            <h1 class="page-title">Dashboard</h1>
            <p class="welcome-text"><span data-greeting>Selamat Datang</span>, {{ Auth::user()->name ? explode(' ', trim(Auth::user()->name))[0] : 'Waiz' }}.</p>
          </div>
          <div class="date-chip" aria-live="off">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span data-clock>—</span>
          </div>
        </div>

        <hr class="section-divider">

        <!-- Akses Cepat -->
        <h2 class="section-subtitle">Akses Cepat</h2>
        <div class="quick-access-grid">
          <!-- Produk -->
          <div class="dashboard-card">
            <h3 class="card-heading">Produk</h3>
            <a href="/admin/product/create" class="action-link">
              <span class="action-link-plus">+</span>
              <span>Tambah Produk</span>
            </a>
            <a href="/admin/category/create" class="action-link">
              <span class="action-link-plus">+</span>
              <span>Tambah Kategori</span>
            </a>
          </div>

          <!-- Pesanan -->
          <div class="dashboard-card">
            <h3 class="card-heading">Pesanan</h3>
            <div class="order-total">
              <span class="order-total-num" data-count="{{ $stats['total'] }}">{{ $stats['total'] }}</span>
              <span class="order-total-label">Total pesanan</span>
            </div>

            <div class="seg-bar" data-bar role="img" aria-label="Proporsi status pesanan">
              <span class="seg green"  style="--w: {{ $pct($stats['selesai'], $totalOrders) }}%"></span>
              <span class="seg yellow" style="--w: {{ $pct($stats['proses'],  $totalOrders) }}%"></span>
              <span class="seg orange" style="--w: {{ $pct($stats['pending'], $totalOrders) }}%"></span>
            </div>

            <div class="status-counts-grid">
              <a href="/admin/orders?status=selesai" class="status-badge-item">
                <span class="status-dot green"></span>
                <span><b data-count="{{ $stats['selesai'] }}">{{ $stats['selesai'] }}</b> Selesai</span>
              </a>
              <a href="/admin/orders?status=proses" class="status-badge-item">
                <span class="status-dot yellow"></span>
                <span><b data-count="{{ $stats['proses'] }}">{{ $stats['proses'] }}</b> Proses</span>
              </a>
              <a href="/admin/orders?status=pending" class="status-badge-item">
                <span class="status-dot orange"></span>
                <span><b data-count="{{ $stats['pending'] }}">{{ $stats['pending'] }}</b> Pending</span>
              </a>
            </div>
          </div>

          <!-- Menu Kasir -->
          <div class="dashboard-card card-cashier">
            <h3 class="card-heading">Menu Kasir</h3>
            <p class="cashier-text">Catat penjualan langsung di toko, lengkap dengan QRIS dan e-wallet.</p>
            <a href="/admin/pos" class="btn-cashier-mode">
              <span>Masuki Mode Kasir</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
              </svg>
            </a>
          </div>
        </div>

        <!-- Ringkasan Data -->
        <h2 class="section-subtitle">Ringkasan Data</h2>
        <div class="data-summary-grid">
          <!-- Pesanan terakhir -->
          <div class="dashboard-card orders-list-card">
            <div class="card-head">
              <h3 class="card-heading">Data Pesanan Terakhir</h3>
              <a href="/admin/orders" class="link-btn">Lihat semua</a>
            </div>

            <div class="tabs" role="tablist" aria-label="Filter status pesanan">
              <button type="button" class="tab active" role="tab" aria-selected="true" data-filter="all">Semua</button>
              <button type="button" class="tab" role="tab" aria-selected="false" data-filter="pending">Pending</button>
              <button type="button" class="tab" role="tab" aria-selected="false" data-filter="proses">Proses</button>
              <button type="button" class="tab" role="tab" aria-selected="false" data-filter="selesai">Selesai</button>
            </div>

            <div class="orders-scroll" id="ordersList" data-lenis-prevent>
              @foreach ($orders as $order)
                <a href="/admin/orders" class="order-row-item" data-status="{{ $order['status'] }}">
                  <div class="order-user-info">
                    <div class="order-avatar-mini {{ $statusDot[$order['status']] ?? 'orange' }}">{{ strtoupper(substr($order['user'], 0, 1)) }}</div>
                    <div class="order-user-text">
                      <span class="order-username">{{ $order['user'] }}</span>
                      <span class="order-timestamp">{{ $order['time'] }}</span>
                    </div>
                  </div>
                  <div class="order-status-badge {{ $statusDot[$order['status']] ?? 'orange' }}">
                    <span class="status-dot {{ $statusDot[$order['status']] ?? 'orange' }}"></span>
                    <span class="order-status-label">{{ $order['status'] }}</span>
                  </div>
                </a>
              @endforeach
              <p class="empty-state" id="ordersEmpty" hidden>Belum ada pesanan dengan status ini.</p>
            </div>
          </div>

          <!-- Metrik -->
          <div class="metrics-column">
            <div class="metrics-row-grid">
              @foreach ([['Total Pemasukan', $income], ['Riwayat Transaksi: Kasir', $cashier]] as [$title, $data])
                <div class="dashboard-card" data-amount-card>
                  <div class="card-head">
                    <h3 class="card-heading">{{ $title }}</h3>
                    <button type="button" class="icon-btn" data-toggle-amounts aria-pressed="false" aria-label="Sembunyikan nominal">
                      <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                      </svg>
                      <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                        <line x1="1" y1="1" x2="23" y2="23"></line>
                      </svg>
                    </button>
                  </div>

                  <div class="metric-data-row highlight">
                    <span class="metric-label">Keseluruhan</span>
                    <span class="metric-value large amount" data-money="{{ $data['total'] }}">Rp {{ number_format($data['total'], 2, ',', '.') }}</span>
                  </div>

                  <div class="split-bar" data-bar role="img" aria-label="Porsi QRIS dan e-wallet">
                    <span class="seg blue"   style="--w: {{ $pct($data['qris'], max($data['total'], 1)) }}%"></span>
                    <span class="seg violet" style="--w: {{ $pct($data['ewallet'], max($data['total'], 1)) }}%"></span>
                  </div>

                  <div class="metric-data-row">
                    <span class="metric-label sub"><i class="legend blue"></i>QRIS</span>
                    <span class="metric-value amount" data-money="{{ $data['qris'] }}">Rp {{ number_format($data['qris'], 2, ',', '.') }}</span>
                  </div>
                  <div class="metric-data-row">
                    <span class="metric-label sub"><i class="legend violet"></i>E-Wallet</span>
                    <span class="metric-value amount" data-money="{{ $data['ewallet'] }}">Rp {{ number_format($data['ewallet'], 2, ',', '.') }}</span>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Riwayat transaksi -->
            <div class="dashboard-card">
              <div class="card-head">
                <h3 class="card-heading">Riwayat Transaksi</h3>
                <a href="/admin/payments" class="link-btn">Lihat semua</a>
              </div>

              @forelse ($transactions as $trx)
                <a href="/admin/payments" class="order-row-item">
                  <div class="order-user-info">
                    <div class="order-avatar-mini green">{{ strtoupper(substr($trx['user'], 0, 1)) }}</div>
                    <div class="order-user-text">
                      <span class="order-username">{{ $trx['user'] }}</span>
                      <span class="order-timestamp">{{ $trx['time'] }}</span>
                    </div>
                  </div>
                  <div class="order-status-badge green">
                    <span class="status-dot green"></span>
                    <span class="order-status-label">{{ $trx['status'] }}</span>
                  </div>
                </a>
              @empty
                <p class="empty-state">Belum ada transaksi hari ini.</p>
              @endforelse
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Lenis Script -->
  <script src="{{ asset('vendor/lenis/lenis.min.js') }}"></script>
  <script>if(typeof Lenis==='undefined'){document.write('<script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"><\/script>');}</script>
</body>
</html>