<!DOCTYPE html>
<html lang="id">
@php
  $summary = $summary ?? [
    'subtotal'     => (float)($order->subtotal ?? 0),
    'delivery_fee' => (float)($order->delivery_fee ?? 0),
    'admin_fee'    => (float)($order->admin_fee ?? 0),
    'grand_total'  => (float)($order->grand_total ?? 0),
  ];
  $timeline = $timeline ?? [];
  $items = $items ?? collect();
@endphp
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Pesanan {{ $order->order_code }} — Maybeads</title>
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
        <a href="{{ route('admin.order.index') }}">Pesanan</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">Detail Pesanan {{ $order->order_code }}</span>
      </nav>

      {{-- Top Action Bar --}}
      <div class="detail-top-bar">
        <div>
          <h1 class="detail-title">Detail Pesanan</h1>
          <p class="detail-sub-meta">Pesanan dibuat pada {{ $order->order_at ? $order->order_at->format('d M Y, H:i') : '-' }} WITA</p>
        </div>

        <div class="detail-top-actions">
          <a href="{{ route('admin.order.index') }}" class="btn-back-outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            <span>Kembali</span>
          </a>

          <button type="button" class="btn-print-invoice" id="btnPrintInvoice">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="6 9 6 2 18 2 18 9"></polyline>
              <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
              <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Cetak Invoice</span>
          </button>
        </div>
      </div>

      {{-- Status & Cancellation Banner (Item 1) --}}
      @php
        $stLower = strtolower($order->order_status);
        $statusBadgeClass = 'status-badge-pending';
        $statusIcon = 'clock';

        if (in_array($stLower, ['proses', 'diproses'])) {
            $statusBadgeClass = 'status-badge-proses';
            $statusIcon = 'refresh';
        } elseif ($stLower === 'sedang perjalanan') {
            $statusBadgeClass = 'status-badge-perjalanan';
            $statusIcon = 'truck';
        } elseif ($stLower === 'siap diambil') {
            $statusBadgeClass = 'status-badge-ambil';
            $statusIcon = 'package';
        } elseif (in_array($stLower, ['selesai', 'sukses'])) {
            $statusBadgeClass = 'status-badge-selesai';
            $statusIcon = 'check';
        } elseif (in_array($stLower, ['dibatalkan', 'batal'])) {
            $statusBadgeClass = 'status-badge-batal';
            $statusIcon = 'x';
        }
      @endphp

      <div class="order-status-banner">
        <div class="status-banner-left">
          <div class="status-title-row">
            <span class="status-indicator-badge {{ $statusBadgeClass }}">
              @if ($statusIcon === 'check')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
              @elseif ($statusIcon === 'truck')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
              @elseif ($statusIcon === 'package')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
              @elseif ($statusIcon === 'refresh')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
              @elseif ($statusIcon === 'x')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
              @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              @endif
              {{ $order->order_status }}
            </span>

            <span class="banner-order-tag">ID Pesanan: <strong>{{ $order->order_code }}</strong></span>
            <span class="banner-pay-tag">ID Pembayaran: <strong>{{ $order->payment_id }}</strong></span>
          </div>

          {{-- 5-Day Expiration / Batas Waktu 5 Hari --}}
          <div class="status-deadline-info">
            <div class="deadline-row">
              <span class="deadline-label">Batas Waktu Penerimaan (5 Hari):</span>
              <span class="deadline-badge {{ $order->is_expired || in_array($stLower, ['dibatalkan', 'batal']) ? 'deadline-expired' : 'deadline-active' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="deadline-clock-icon">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                {{ $order->remaining_time_text }}
              </span>
            </div>
            <p class="deadline-note">
              Jatuh Tempo: <strong>{{ $order->deadline_at ? $order->deadline_at->format('d M Y, H:i') : '-' }}</strong>.
              Pesanan otomatis dibatalkan oleh sistem jika tidak diselesaikan dalam 5 hari dari waktu pemesanan.
            </p>
          </div>
        </div>

        {{-- Action Buttons in Banner --}}
        <div class="status-banner-actions">
          <button type="button" class="btn-change-status" id="btnOpenStatusModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            <span>Ubah Status</span>
          </button>

          @if ($order->can_cancel_by_admin)
            <button type="button" class="btn-cancel-order" id="btnOpenCancelModal">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
              </svg>
              <span>Batalkan Pesanan</span>
            </button>
          @else
            <div class="cancel-order-disabled" title="Pembatalan tidak tersedia (status sudah selesai/batal atau lewat batas 5 hari)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
              <span>Tidak Dapat Dibatalkan</span>
            </div>
          @endif
        </div>
      </div>

      {{-- Grid 2 Kolom --}}
      <div class="order-detail-grid">

        {{-- Kolom Kiri: Daftar Produk & Ringkasan Pembayaran/Pengambilan --}}
        <div class="order-main-col">

          {{-- 1. Daftar Produk --}}
          <div class="card-box">
            <div class="box-heading-row">
              <h2 class="box-heading">Daftar Produk yang Di-order</h2>
              <span class="badge-item-count">{{ $items->count() }} Produk</span>
            </div>

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
                        Rp {{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 2, ',', '.') }}
                      </td>
                      <td class="cell-quantity">
                        <span class="qty-badge">x {{ $item['quantity'] ?? $item['qty'] ?? 1 }}</span>
                      </td>
                      <td class="cell-total-price">
                        Rp {{ number_format($item['total_price'] ?? $item['subtotal'] ?? 0, 2, ',', '.') }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          {{-- 2. Detail Metode Pembayaran & Metode Pengambilan (Item 2 & 3) --}}
          <div class="order-methods-grid">

            {{-- Kartu Metode Pembayaran --}}
            <div class="method-card">
              <div class="method-card-header">
                <div class="method-icon-wrap pay-icon-wrap">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                  </svg>
                </div>
                <div>
                  <h3 class="method-title">Metode Pembayaran</h3>
                  <span class="method-name">{{ $order->payment_method_name }}</span>
                </div>
              </div>

              <div class="method-card-body">
                <div class="method-info-row">
                  <span class="method-info-lbl">ID Pembayaran</span>
                  <div class="copy-badge-wrap">
                    <span class="badge-payment-id" id="badgePaymentId">{{ $order->payment_id }}</span>
                    <button type="button" class="btn-mini-copy" id="btnCopyPaymentId" title="Salin ID Pembayaran">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    </button>
                  </div>
                </div>

                <div class="method-info-row">
                  <span class="method-info-lbl">Status Pembayaran</span>
                  @php
                    $payStatus = strtolower($order->payment_status ?? ($order->payment->payment_status ?? 'pending'));
                    $payBadgeCls = 'pay-status-pending';
                    if (in_array($payStatus, ['sukses', 'success', 'paid'])) $payBadgeCls = 'pay-status-sukses';
                    elseif (in_array($payStatus, ['dibatalkan', 'gagal', 'failed', 'batal'])) $payBadgeCls = 'pay-status-batal';
                    elseif (in_array($payStatus, ['proses', 'diproses'])) $payBadgeCls = 'pay-status-proses';
                  @endphp
                  <span class="badge-pay-status {{ $payBadgeCls }}">
                    {{ ucfirst($payStatus) }}
                  </span>
                </div>

                <div class="method-info-row">
                  <span class="method-info-lbl">Biaya Admin</span>
                  <span class="method-info-val">+ Rp {{ number_format($order->admin_fee, 2, ',', '.') }}</span>
                </div>
              </div>
            </div>

            {{-- Kartu Metode Pengambilan --}}
            <div class="method-card">
              <div class="method-card-header">
                <div class="method-icon-wrap delivery-icon-wrap">
                  @if ($order->is_delivery_to_home)
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="1" y="3" width="15" height="13"></rect>
                      <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                      <circle cx="5.5" cy="18.5" r="2.5"></circle>
                      <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                  @else
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                      <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                  @endif
                </div>
                <div>
                  <h3 class="method-title">Metode Pengambilan</h3>
                  <span class="method-name">{{ $order->delivery_method_name }}</span>
                </div>
              </div>

              <div class="method-card-body">
                <div class="method-info-row">
                  <span class="method-info-lbl">Jenis Layanan</span>
                  <span class="method-info-val font-semibold">
                    {{ $order->is_delivery_to_home ? 'Antar ke Tempat (Kurir)' : 'Ambil di Tempat (Self-Pickup)' }}
                  </span>
                </div>

                <div class="method-info-row">
                  <span class="method-info-lbl">Biaya Pengantaran</span>
                  <span class="method-info-val">+ Rp {{ number_format($order->delivery_fee, 2, ',', '.') }}</span>
                </div>

                <div class="method-info-row">
                  <span class="method-info-lbl">Wilayah</span>
                  <span class="method-info-val text-muted-sm">
                    {{ $order->is_delivery_to_home ? 'Banjarbaru & Sekitarnya' : 'Gerai Toko Maybeads' }}
                  </span>
                </div>
              </div>
            </div>

          </div>

          {{-- 3. Rincian Total Harga --}}
          <div class="card-box summary-card-box">
            <h3 class="summary-heading">Rincian Total Harga</h3>

            <div class="summary-line">
              <span class="summary-label">Subtotal Produk</span>
              <span class="summary-val">Rp {{ number_format($summary['subtotal'], 2, ',', '.') }}</span>
            </div>

            <div class="summary-line">
              <span class="summary-label">Biaya Pengantaran ({{ $order->delivery_method_name }})</span>
              <span class="summary-val">Rp {{ number_format($summary['delivery_fee'], 2, ',', '.') }}</span>
            </div>

            <div class="summary-line">
              <span class="summary-label">Biaya Admin ({{ $order->payment_method_name }})</span>
              <span class="summary-val">Rp {{ number_format($summary['admin_fee'], 2, ',', '.') }}</span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-grand-total">
              <div>
                <span class="grand-total-label">Total Harga Akhir</span>
                <span class="grand-total-subtext">Sudah termasuk biaya layanan & pengantaran</span>
              </div>
              <span class="grand-total-val">Rp {{ number_format($summary['grand_total'], 2, ',', '.') }}</span>
            </div>
          </div>

        </div>

        {{-- Kolom Kanan: 4 Side Cards (Item 3 & 4) --}}
        <div class="order-sidebar-col">

          {{-- Card 1: ID Pesanan & ID Pembayaran --}}
          <div class="side-card">
            <div class="side-card-label">Identitas Transaksi</div>

            <div class="order-id-row">
              <div>
                <span class="meta-label-sm">Order ID</span>
                <div class="code-copy-row">
                  <span class="order-code-text" id="orderCodeText">{{ $order->order_code }}</span>
                  <button type="button" class="btn-copy-code" id="btnCopyOrderId" title="Salin Order ID">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                      <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="order-id-row" style="margin-top: 10px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
              <div>
                <span class="meta-label-sm">ID Pembayaran</span>
                <div class="code-copy-row">
                  <span class="payment-code-text" id="sidePaymentCodeText">{{ $order->payment_id }}</span>
                  <button type="button" class="btn-copy-code" id="btnCopySidePaymentId" title="Salin ID Pembayaran">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                      <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="order-date-badge">
              Di-order: {{ $order->order_at ? $order->order_at->format('d M Y \a\t H:i') : '26 May 2026 at 14:32' }}
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
              <div>
                <span class="customer-name-display">{{ $order->user->name ?? 'Pengguna' }}</span>
                <span class="customer-role-badge">Customer</span>
              </div>
            </div>

            <div class="customer-fields-list">
              <div class="customer-field-item">
                <span class="cust-label">Username</span>
                <span class="cust-value">&#64;{{ $order->user->name ?? 'user' }}</span>
              </div>
              <div class="customer-field-item">
                <span class="cust-label">Email</span>
                <span class="cust-value">{{ $order->user->email ?? '-' }}</span>
              </div>
              <div class="customer-field-item">
                <span class="cust-label">No. Telepon</span>
                <span class="cust-value">{{ $order->user->phone ?? '0812-3456-7890' }}</span>
              </div>
            </div>
          </div>

          {{-- Card 3: Alamat & Google Maps Preview (Item 4) --}}
          <div class="side-card">
            <div class="side-card-label">
              @if ($order->is_delivery_to_home)
                <span class="label-with-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="side-lbl-icon">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  Alamat Pengantaran (Antar ke Rumah)
                </span>
              @else
                <span class="label-with-icon">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="side-lbl-icon">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                  </svg>
                  Lokasi Pengambilan (Toko)
                </span>
              @endif
            </div>

            @if ($order->is_delivery_to_home)
              {{-- Alamat Pengantaran Pelanggan --}}
              <div class="address-preview-wrap">
                <div class="address-recipient-info">
                  <strong>{{ $order->user->name ?? 'Penerima' }}</strong>
                  <span>{{ $order->user->phone ?? '0812-3456-7890' }}</span>
                </div>
                <div class="address-text-box">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="addr-pin-icon">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <p class="address-detail-text">
                    {{ $order->address ?: 'Jl. Mistar Cokrokusumo No. 45, Banjarbaru, Kalimantan Selatan' }}
                  </p>
                </div>

                {{-- Google Maps Embed Preview --}}
                @php
                  $mapsQuery = urlencode($order->address ?: 'Banjarbaru Kalimantan Selatan');
                  $mapsUrl = "https://maps.google.com/maps?q={$mapsQuery}&t=&z=15&ie=UTF8&iwloc=&output=embed";
                  $mapsExternalUrl = "https://www.google.com/maps/search/?api=1&query={$mapsQuery}";
                @endphp
                <div class="gmaps-preview-box">
                  <iframe
                    src="{{ $mapsUrl }}"
                    class="gmaps-iframe"
                    loading="lazy"
                    title="Preview Lokasi Google Maps"
                    referrerpolicy="no-referrer-when-downgrade">
                  </iframe>
                </div>

                <a href="{{ $mapsExternalUrl }}" target="_blank" rel="noopener noreferrer" class="btn-gmaps-link">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <span>Buka di Google Maps</span>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="external-icon">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                  </svg>
                </a>
              </div>
            @else
              {{-- Ambil Ditempat --}}
              <div class="address-preview-wrap">
                <div class="store-pickup-info">
                  <span class="store-name-badge">Gerai Toko Maybeads</span>
                  <p class="store-address-text">
                    Jl. Ahmad Yani Km. 34, Loktabat Selatan, Kec. Banjarbaru Selatan, Kota Banjarbaru, Kalimantan Selatan 70714
                  </p>
                  <span class="store-hour-info">Jam Buka Pengambilan: 09.00 - 21.00 WITA</span>
                </div>

                @php
                  $storeMapsQuery = urlencode('Jl. Ahmad Yani Km. 34 Banjarbaru Kalimantan Selatan');
                  $storeMapsUrl = "https://maps.google.com/maps?q={$storeMapsQuery}&t=&z=15&ie=UTF8&iwloc=&output=embed";
                  $storeExternalUrl = "https://www.google.com/maps/search/?api=1&query={$storeMapsQuery}";
                @endphp
                <div class="gmaps-preview-box">
                  <iframe
                    src="{{ $storeMapsUrl }}"
                    class="gmaps-iframe"
                    loading="lazy"
                    title="Lokasi Toko Maybeads"
                    referrerpolicy="no-referrer-when-downgrade">
                  </iframe>
                </div>

                <a href="{{ $storeExternalUrl }}" target="_blank" rel="noopener noreferrer" class="btn-gmaps-link">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <span>Lihat Lokasi Toko di Maps</span>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="external-icon">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                  </svg>
                </a>
              </div>
            @endif
          </div>

          {{-- Card 4: Status Timeline Card --}}
          <div class="side-card">
            <div class="side-card-label">Perjalanan Status Pesanan</div>

            <div class="timeline-list">
              @foreach ($timeline as $step)
                <div class="timeline-item {{ $step['active'] ? 'item-active' : '' }} {{ ($step['is_batal'] ?? false) ? 'item-batal' : '' }}">
                  <div class="timeline-left">
                    <span class="timeline-dot"></span>
                    <span class="timeline-title">{{ $step['title'] }}</span>
                  </div>
                  <span class="timeline-date">{{ $step['date'] }}</span>
                </div>
              @endforeach
            </div>

            <button type="button" class="btn-update-status" id="btnUpdateStatusSidebar">
              Update Status
            </button>
          </div>

        </div>

      </div>

    </main>
  </div>
</div>

{{-- MODAL 1: Update Status Pesanan --}}
<div class="modal-backdrop" id="statusModalBackdrop" style="display: none;">
  <div class="modal-card">
    <div class="modal-header">
      <h3 class="modal-title">Perbarui Status Pesanan</h3>
      <button type="button" class="btn-modal-close" id="btnCloseStatusModal">&times;</button>
    </div>

    <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
      @csrf
      @method('PATCH')

      <div class="modal-body">
        <p class="modal-desc">
          Pilih status terbaru untuk pesanan <strong>{{ $order->order_code }}</strong>.
        </p>

        <div class="form-group">
          <label for="orderStatusSelect" class="form-label">Status Pesanan</label>
          <select name="order_status" id="orderStatusSelect" class="form-select">
            <option value="Pending" {{ $order->order_status === 'Pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
            <option value="Diproses" {{ in_array($order->order_status, ['Diproses', 'Proses']) ? 'selected' : '' }}>Diproses (Sedang Disiapkan)</option>
            <option value="Sedang Perjalanan" {{ $order->order_status === 'Sedang Perjalanan' ? 'selected' : '' }}>Sedang Perjalanan (Dalam Pengantaran)</option>
            <option value="Siap Diambil" {{ $order->order_status === 'Siap Diambil' ? 'selected' : '' }}>Siap Diambil (Toko)</option>
            <option value="Selesai" {{ in_array($order->order_status, ['Selesai', 'Sukses']) ? 'selected' : '' }}>Selesai (Pesanan Selesai)</option>
            <option value="Dibatalkan" {{ $order->order_status === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan (Pesanan Dibatalkan)</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-secondary" id="btnCancelStatusModal">Batal</button>
        <button type="submit" class="btn-primary">Simpan Status</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL 2: Konfirmasi Batalkan Pesanan oleh Admin --}}
@if ($order->can_cancel_by_admin)
<div class="modal-backdrop" id="cancelModalBackdrop" style="display: none;">
  <div class="modal-card modal-card-danger">
    <div class="modal-header">
      <h3 class="modal-title text-danger">Batalkan Pesanan Ini?</h3>
      <button type="button" class="btn-modal-close" id="btnCloseCancelModal">&times;</button>
    </div>

    <form action="{{ route('admin.order.cancel', $order->id) }}" method="POST">
      @csrf

      <div class="modal-body">
        <div class="danger-alert-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="danger-icon">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
          </svg>
          <div>
            <strong>Perhatian: Tindakan Ini Akan Membatalkan Pesanan</strong>
            <p>Pesanan <strong>{{ $order->order_code }}</strong> (ID Pembayaran: <strong>{{ $order->payment_id }}</strong>) akan ditandai sebagai <em>Dibatalkan</em>. Masih dalam batas waktu pembatalan 5 hari.</p>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-secondary" id="btnCancelCancelModal">Kembali</button>
        <button type="submit" class="btn-danger">Ya, Batalkan Pesanan</button>
      </div>
    </form>
  </div>
</div>
@endif

</body>
</html>
