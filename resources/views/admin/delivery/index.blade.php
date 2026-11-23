<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Data Pengantaran — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/order-index.css', 'resources/js/admin/dashboard.js'])

  <style>
    .delivery-content {
      padding: 18px 40px 48px;
      max-width: 1560px;
      width: 100%;
    }
    .badge-coverage {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
    }
    .coverage-lokal {
      background: #eff6ff;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
    }
    .coverage-luar {
      background: #fdf4ff;
      color: #86198f;
      border: 1px solid #f5d0fe;
    }
    .courier-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 3px 10px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      color: #1e293b;
    }
    .resi-text {
      font-family: monospace;
      font-size: 11.5px;
      color: #64748b;
      margin-top: 3px;
    }
    .btn-action-view {
      padding: 5px 12px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #0f172a;
      font-size: 12px;
      font-weight: 600;
      border-radius: 6px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.15s ease;
    }
    .btn-action-view:hover {
      background: #f1f5f9;
      border-color: #94a3b8;
    }
    .btn-action-edit {
      padding: 5px 10px;
      border: 1px solid #93c5fd;
      background: #eff6ff;
      color: #1d4ed8;
      font-size: 12px;
      font-weight: 600;
      border-radius: 6px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.15s ease;
    }
    .btn-action-edit:hover {
      background: #dbeafe;
    }
    .delivery-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    .delivery-modal-card {
      background: #ffffff;
      border-radius: 12px;
      max-width: 440px;
      width: 90%;
      padding: 24px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'delivery'])

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
            <span class="notification-badge">{{ $stats['siap_kirim'] ?? 1 }}</span>
          </button>
        </div>
      </div>
    </header>

    {{-- Main Content --}}
    <main class="delivery-content">
      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">Data Pengantaran</span>
      </nav>

      {{-- Page Heading --}}
      <div class="order-page-head">
        <div>
          <h1 class="order-page-title" style="margin-bottom: 4px;">Data Pengantaran</h1>
          <p style="font-size: 13.5px; color: #64748b;">Pantauan pengiriman pesanan ke rumah pelanggan (J&T, JNE, Gojek, dan Kurir Lokal).</p>
        </div>
      </div>

      {{-- Flash Message --}}
      @if (session('success'))
        <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46; padding: 12px 18px; border-radius: 8px; margin-bottom: 16px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      {{-- Stat Cards Pengantaran --}}
      <div class="order-stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 20px;">
        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Total Pengantaran</span>
            <span class="stat-dot dot-blue"></span>
          </div>
          <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Siap Dikirim</span>
            <span class="stat-dot dot-orange"></span>
          </div>
          <div class="stat-number">{{ $stats['siap_kirim'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Dalam Perjalanan</span>
            <span class="stat-dot dot-yellow"></span>
          </div>
          <div class="stat-number">{{ $stats['dalam_perjalanan'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
          <div class="stat-header">
            <span class="stat-title">Terkirim / Selesai</span>
            <span class="stat-dot dot-green"></span>
          </div>
          <div class="stat-number">{{ $stats['terkirim'] ?? 0 }}</div>
        </div>
      </div>

      {{-- Filter Row: Status Tabs + Courier Selector + Search --}}
      <div class="order-filter-row" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 18px;">
        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
          <span class="filter-sort-label">Status:</span>
          <div class="filter-tabs" role="tablist">
            <a href="{{ route('admin.delivery.index', ['status' => 'semua', 'courier' => request('courier', 'semua')]) }}" class="filter-tab {{ ($statusFilter ?? 'semua') === 'semua' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.delivery.index', ['status' => 'siap_kirim', 'courier' => request('courier', 'semua')]) }}" class="filter-tab {{ ($statusFilter ?? '') === 'siap_kirim' ? 'active' : '' }}">Siap Kirim</a>
            <a href="{{ route('admin.delivery.index', ['status' => 'dalam_perjalanan', 'courier' => request('courier', 'semua')]) }}" class="filter-tab {{ ($statusFilter ?? '') === 'dalam_perjalanan' ? 'active' : '' }}">Dalam Perjalanan</a>
            <a href="{{ route('admin.delivery.index', ['status' => 'terkirim', 'courier' => request('courier', 'semua')]) }}" class="filter-tab {{ ($statusFilter ?? '') === 'terkirim' ? 'active' : '' }}">Terkirim</a>
          </div>

          <div style="margin-left: 10px;">
            <select onchange="window.location.href=this.value" style="padding: 6px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 12.5px; font-weight: 600; color: #1e293b; background: #fff; cursor: pointer;">
              <option value="{{ route('admin.delivery.index', ['status' => request('status', 'semua'), 'courier' => 'semua']) }}" {{ ($courierFilter ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Kurir / Ekspedisi</option>
              <option value="{{ route('admin.delivery.index', ['status' => request('status', 'semua'), 'courier' => 'j&t']) }}" {{ str_contains($courierFilter ?? '', 'j&t') ? 'selected' : '' }}>J&T Express</option>
              <option value="{{ route('admin.delivery.index', ['status' => request('status', 'semua'), 'courier' => 'jne']) }}" {{ str_contains($courierFilter ?? '', 'jne') ? 'selected' : '' }}>JNE Regular</option>
              <option value="{{ route('admin.delivery.index', ['status' => request('status', 'semua'), 'courier' => 'gojek']) }}" {{ str_contains($courierFilter ?? '', 'gojek') ? 'selected' : '' }}>Gojek Instant</option>
              <option value="{{ route('admin.delivery.index', ['status' => request('status', 'semua'), 'courier' => 'kurir toko']) }}" {{ str_contains($courierFilter ?? '', 'toko') ? 'selected' : '' }}>Kurir Internal Toko</option>
            </select>
          </div>
        </div>

        <form method="GET" action="{{ route('admin.delivery.index') }}" style="display: flex; gap: 8px;">
          <input type="hidden" name="status" value="{{ request('status', 'semua') }}">
          <input type="hidden" name="courier" value="{{ request('courier', 'semua') }}">
          <div class="order-search-wrap" style="max-width: 260px;">
            <span class="search-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 15px; height: 15px;">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
            </span>
            <input type="text" name="q" value="{{ $search ?? '' }}" class="order-search-input" placeholder="Cari ID/Penerima..." autocomplete="off">
          </div>
        </form>
      </div>

      {{-- Delivery Table --}}
      <div class="order-table-wrap">
        <table class="order-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Penerima & Kontak</th>
              <th>Alamat Tujuan</th>
              <th>Jangkauan</th>
              <th>Ekspedisi & Resi</th>
              <th>Status Pengantaran</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($deliveries as $d)
              @php
                $st = $d['delivery_status'];
                $pillCls = match($st) {
                  'terkirim'         => 'status-sukses',
                  'dalam_perjalanan' => 'status-proses',
                  'dibatalkan'       => 'status-dibatalkan',
                  default            => 'status-pending',
                };
                $pillText = match($st) {
                  'terkirim'         => 'Terkirim',
                  'dalam_perjalanan' => 'Dalam Perjalanan',
                  'dibatalkan'       => 'Dibatalkan',
                  default            => 'Siap Dikirim',
                };
              @endphp
              <tr>
                <td class="col-id"><strong>{{ $d['order_code'] }}</strong></td>
                <td>
                  <div style="font-weight: 600; color: #0f172a;">{{ $d['customer_name'] }}</div>
                  <div style="font-size: 11.5px; color: #64748b;">{{ $d['phone'] }}</div>
                </td>
                <td style="max-width: 280px; font-size: 12.5px; line-height: 1.4; color: #334155;">
                  {{ $d['address'] }}
                </td>
                <td>
                  <span class="badge-coverage {{ $d['coverage'] === 'Dalam Kota' ? 'coverage-lokal' : 'coverage-luar' }}">
                    {{ $d['coverage'] }}
                  </span>
                </td>
                <td>
                  <div class="courier-pill">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; color: #2563eb;"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    <span>{{ $d['courier_name'] }}</span>
                  </div>
                  <div class="resi-text">Resi: <strong>{{ $d['tracking_code'] }}</strong></div>
                </td>
                <td>
                  <span class="status-pill {{ $pillCls }}">
                    <span class="status-pill-dot"></span>
                    <span>{{ $pillText }}</span>
                  </span>
                </td>
                <td>
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <a href="{{ route('admin.order.detail', $d['order_id']) }}" class="btn-action-view" title="Lihat detail pesanan">
                      <span>Detail</span>
                    </a>
                    <button type="button" class="btn-action-edit" onclick="openDeliveryModal({{ $d['order_id'] }}, '{{ $d['order_code'] }}', '{{ $d['order_status'] }}')" title="Perbarui status pengantaran">
                      <span>Update</span>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8; font-size: 14px;">
                  Tidak ada data pesanan pengantaran pada filter ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

{{-- MODAL UPDATE PENGANTARAN --}}
<div class="delivery-modal-backdrop" id="deliveryModal">
  <div class="delivery-modal-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
      <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Perbarui Status Pengantaran</h3>
      <button type="button" onclick="closeDeliveryModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
    </div>

    <form id="deliveryUpdateForm" method="POST" action="">
      @csrf
      <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">
        Pilih status pengiriman terbaru untuk pesanan <strong id="deliveryModalOrderCode"></strong>:
      </p>

      <div style="margin-bottom: 18px;">
        <label for="deliveryStatusSelect" style="display: block; font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 6px;">Status Pengiriman</label>
        <select name="order_status" id="deliveryStatusSelect" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 13px; font-weight: 600; color: #0f172a;">
          <option value="Diproses">Diproses (Sedang Disiapkan / Menunggu Kurir)</option>
          <option value="Sedang Perjalanan">Sedang Perjalanan (Kurir Sedang Mengantar)</option>
          <option value="Selesai">Selesai (Paket Diterima Pelanggan)</option>
          <option value="Dibatalkan">Dibatalkan (Pengiriman Batal)</option>
        </select>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 8px;">
        <button type="button" onclick="closeDeliveryModal()" style="padding: 8px 14px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-size: 13px; font-weight: 600; border-radius: 6px; cursor: pointer;">Batal</button>
        <button type="submit" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; font-size: 13px; font-weight: 600; border-radius: 6px; cursor: pointer;">Simpan Status</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openDeliveryModal(orderId, orderCode, currentStatus) {
    document.getElementById('deliveryModalOrderCode').innerText = orderCode;
    document.getElementById('deliveryStatusSelect').value = currentStatus;
    document.getElementById('deliveryUpdateForm').action = '/admin/delivery/' + orderId + '/update-courier';
    document.getElementById('deliveryModal').style.display = 'flex';
  }

  function closeDeliveryModal() {
    document.getElementById('deliveryModal').style.display = 'none';
  }

  window.addEventListener('click', function(e) {
    const modal = document.getElementById('deliveryModal');
    if (e.target === modal) closeDeliveryModal();
  });
</script>

</body>
</html>
