<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Pesanan #{{ $order->formatted_id }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/order-detail.css', 'resources/js/admin/order-detail.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'pesanan'])

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
    <main class="order-detail-content">

      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <a href="{{ route('admin.order.index') }}">Pesanan</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">Detail Pesanan #{{ $order->formatted_id }}</span>
      </nav>

      {{-- Top Action Bar --}}
      <div class="detail-top-bar">
        <h1 class="detail-title">Detail Pesanan</h1>

        <div class="detail-top-actions">
          <a href="{{ route('admin.order.index') }}" class="btn-back-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali</span>
          </a>

          <button type="button" class="btn-print-invoice" id="btnPrintInvoice">
            <span>Cetak Invoice</span>
          </button>
        </div>
      </div>

      {{-- Grid 2 Kolom --}}
      <div class="order-detail-grid">

        {{-- Kolom Kiri: Daftar Produk & Total --}}
        <div class="card-box">
          <h2 class="box-heading">Daftar Produk</h2>

          <div class="items-table-wrap">
            <table class="items-table">
              <thead>
                <tr>
                  <th>Nama Produk</th>
                  <th>Harga Satuan</th>
                  <th>Jumlah</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($items as $item)
                  <tr>
                    <td>
                      <div class="product-info-cell">
                        <img src="{{ $item['image'] ?? asset('images/logo.svg') }}"
                             alt="{{ $item['name'] }}"
                             class="product-thumb"
                             onerror="this.src='{{ asset('images/logo.svg') }}'">
                        <div class="product-meta-texts">
                          <span class="product-title">{{ $item['name'] }}</span>
                          <span class="product-category-sub">{{ $item['category'] ?? 'Aksesoris' }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="cell-unit-price">
                      Rp {{ number_format($item['unit_price'], 2, ',', '.') }}
                    </td>
                    <td class="cell-quantity">
                      x {{ $item['quantity'] }}
                    </td>
                    <td class="cell-total-price">
                      Rp {{ number_format($item['total_price'], 2, ',', '.') }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          {{-- Ringkasan Total --}}
          <div class="summary-section">
            <h3 class="summary-heading">Total</h3>

            <div class="summary-line">
              <span>Subtotal</span>
              <span class="summary-val">Rp. {{ number_format($summary['subtotal'], 2, ',', '.') }}</span>
            </div>

            <div class="summary-line">
              <span>Biaya Pengantaran</span>
              <span class="summary-val">Rp. {{ number_format($summary['delivery_fee'], 2, ',', '.') }}</span>
            </div>

            <div class="summary-line">
              <span>Biaya Admin</span>
              <span class="summary-val">Rp. {{ number_format($summary['admin_fee'], 2, ',', '.') }}</span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-grand-total">
              <span class="grand-total-label">Harga Akhir</span>
              <span class="grand-total-val">{{ number_format($summary['grand_total'], 2, ',', '.') }}</span>
            </div>
          </div>
        </div>

        {{-- Kolom Kanan: 3 Side Cards --}}
        <div class="order-sidebar-col">

          {{-- Card 1: Order ID --}}
          <div class="side-card">
            <div class="side-card-label">Order ID</div>
            <div class="order-id-row">
              <span class="order-code-text" id="orderCodeText">{{ $order->order_code }}</span>
              <button type="button" class="btn-copy-code" id="btnCopyOrderId" title="Salin Order ID">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                  <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
              </button>
            </div>
            <div class="order-date-badge">
              Di Order: {{ $order->order_at ? $order->order_at->format('d M Y \a\t H:i') : '26 May 2026 at 14:32' }}
            </div>
          </div>

          {{-- Card 2: Informasi Pelanggan --}}
          <div class="side-card">
            <div class="side-card-label">Informasi Pelanggan</div>

            <div class="customer-profile-row">
              <div class="customer-avatar-large">
                @if (!empty($order->user->avatar ?? null))
                  <img src="{{ asset('storage/avatars/' . $order->user->avatar) }}" alt="{{ $order->user->name }}">
                @else
                  {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                @endif
              </div>
              <span class="customer-name-display">{{ $order->user->name ?? 'moonelliaven' }}</span>
            </div>

            <div class="customer-fields-list">
              <div class="customer-field-item">
                <span class="cust-label">Username</span>
                <span class="cust-value">&#64;{{ $order->user->name ?? 'moonelliaven' }}</span>
              </div>
              <div class="customer-field-item">
                <span class="cust-label">Email</span>
                <span class="cust-value">{{ $order->user->email ?? 'moonelliaven@gmail.com' }}</span>
              </div>
              <div class="customer-field-item">
                <span class="cust-label">No. Telepon</span>
                <span class="cust-value">{{ $order->user->phone ?? '0812-3456-7890' }}</span>
              </div>
            </div>
          </div>

          {{-- Card 3: Status Pesanan --}}
          <div class="side-card">
            <div class="side-card-label">Status Pesanan</div>

            <div class="timeline-list">
              @foreach ($timeline as $step)
                <div class="timeline-item">
                  <div class="timeline-left">
                    <span class="timeline-dot"></span>
                    <span class="timeline-title">{{ $step['title'] }}</span>
                  </div>
                  <span class="timeline-date">{{ $step['date'] }}</span>
                </div>
              @endforeach
            </div>

            <button type="button" class="btn-update-status" id="btnUpdateStatus">
              Update
            </button>
          </div>

        </div>

      </div>

    </main>
  </div>
</div>

</body>
</html>

