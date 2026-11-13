<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail User — {{ $user->name }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/user-detail.css', 'resources/js/admin/user-detail.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'user'])

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

    {{-- Content --}}
    <main class="user-detail-content">
      <div class="detail-top-nav">
        <a href="{{ route('admin.user.index') }}" class="btn-back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali ke Data User
        </a>

        <nav class="breadcrumb" aria-label="Breadcrumb">
          <a href="{{ route('admin.dashboard') }}">Dashboard</a>
          <span class="breadcrumb-sep">&gt;</span>
          <a href="{{ route('admin.user.index') }}">User</a>
          <span class="breadcrumb-sep">&gt;</span>
          <span class="breadcrumb-current">Detail User</span>
        </nav>

        <h1 class="detail-heading">Detail Data User</h1>
      </div>

      <div class="detail-grid">
        {{-- Profile Card (Kiri) --}}
        <div class="profile-card">
          @php
            $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
          @endphp
          <div class="profile-avatar-large">
            {{ $initial }}
          </div>

          <h2 class="profile-name">{{ $user->name }}</h2>
          <span class="profile-id-badge">{{ $user->formatted_id ?? ('IDP' . str_pad($user->id, 4, '0', STR_PAD_LEFT)) }}</span>

          <span class="role-badge {{ strtolower($user->role) === 'admin' ? 'admin' : 'user' }}">
            {{ ucfirst($user->role ?? 'User') }}
          </span>

          <div class="profile-divider"></div>

          <div class="profile-meta-list">
            <div class="profile-meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
              <div>
                <div style="font-size: 11px; color: #94a3b8;">Email</div>
                <div class="profile-meta-value">{{ $user->email }}</div>
              </div>
            </div>

            <div class="profile-meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
              <div>
                <div style="font-size: 11px; color: #94a3b8;">Telepon</div>
                <div class="profile-meta-value">{{ $user->phone }}</div>
              </div>
            </div>

            <div class="profile-meta-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
              <div>
                <div style="font-size: 11px; color: #94a3b8;">Tanggal Bergabung</div>
                <div class="profile-meta-value">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</div>
              </div>
            </div>
          </div>

          <button type="button"
                  id="btnDeleteUser"
                  class="btn-delete-profile"
                  data-user-id="{{ $user->id }}"
                  data-user-name="{{ $user->name }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;">
              <polyline points="3 6 5 6 21 6"></polyline>
              <path d="M19 6l-1 14H6L5 6"></path>
              <path d="M10 11v6"></path>
              <path d="M14 11v6"></path>
              <path d="M9 6V4h6v2"></path>
            </svg>
            Hapus Pengguna
          </button>
        </div>

        {{-- Detail Panels (Kanan) --}}
        <div class="detail-panels">
          {{-- Panel 1: Data Lengkap --}}
          <div class="panel-card">
            <h3 class="panel-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              Informasi Pengguna
            </h3>

            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-val">{{ $user->name }}</span>
              </div>

              <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-val">{{ $user->email }}</span>
              </div>

              <div class="info-item">
                <span class="info-label">Nomor WhatsApp / Telepon</span>
                <span class="info-val">{{ $user->phone }}</span>
              </div>

              <div class="info-item">
                <span class="info-label">Peran (Role)</span>
                <span class="info-val">{{ ucfirst($user->role ?? 'User') }}</span>
              </div>

              <div class="info-item full-width">
                <span class="info-label">Alamat Pengiriman Utama</span>
                <span class="info-val">{{ $user->address }}</span>
              </div>

              <div class="info-item">
                <span class="info-label">Terdaftar Pada</span>
                <span class="info-val">{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</span>
              </div>

              <div class="info-item">
                <span class="info-label">Terakhir Diperbarui</span>
                <span class="info-val">{{ $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-' }}</span>
              </div>
            </div>
          </div>

          {{-- Panel 2: Statistik Aktivitas --}}
          <div class="panel-card">
            <h3 class="panel-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="20" x2="12" y2="10"></line>
                <line x1="18" y1="20" x2="18" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="16"></line>
              </svg>
              Ringkasan Transaksi & Pesanan
            </h3>

            <div class="stats-grid">
              <div class="stat-box">
                <div class="stat-box-label">Total Pesanan</div>
                <div class="stat-box-num">{{ $stats['total_orders'] ?? 0 }}</div>
              </div>

              <div class="stat-box">
                <div class="stat-box-label">Total Belanja</div>
                <div class="stat-box-num">Rp {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</div>
              </div>

              <div class="stat-box">
                <div class="stat-box-label">Status Akun</div>
                <div class="stat-box-num" style="font-size: 16px; color: #16a34a; font-weight: 700;">
                  Aktif &amp; Terverifikasi
                </div>
              </div>
            </div>
          </div>

          {{-- Panel 3: Riwayat Pesanan --}}
          <div class="panel-card">
            <h3 class="panel-title">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
              </svg>
              Riwayat Pesanan Terbaru
            </h3>

            @if (!empty($orders) && count($orders) > 0)
              <table class="orders-table">
                <thead>
                  <tr>
                    <th>ID Pesanan</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status Pesanan</th>
                    <th>Pembayaran</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($orders as $order)
                    <tr>
                      <td>#{{ $order->id }}</td>
                      <td>{{ $order->order_at ? $order->order_at->format('d M Y') : '-' }}</td>
                      <td>{{ $order->product->product_name ?? 'Produk #' . $order->product_id }}</td>
                      <td>{{ $order->quantity }} pcs</td>
                      <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                      <td>
                        <span class="status-pill {{ $order->order_status === 'selesai' ? 'success' : 'pending' }}">
                          {{ ucfirst($order->order_status ?? 'Pending') }}
                        </span>
                      </td>
                      <td>
                        <span class="status-pill {{ $order->payment_status === 'lunas' ? 'success' : 'pending' }}">
                          {{ ucfirst($order->payment_status ?? 'Pending') }}
                        </span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <div class="empty-orders">
                Pengguna ini belum pernah melakukan pemesanan produk.
              </div>
            @endif
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

</body>
</html>

