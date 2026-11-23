<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Pending Order #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/order-index.css', 'resources/js/admin/dashboard.js'])

  <style>
    .pending-detail-container {
      padding: 28px 36px 60px;
      max-width: 1040px;
    }
    .pending-deadline-banner {
      background: #fff7ed;
      border: 1.5px solid #fed7aa;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }
    .detail-card-panel {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 24px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .detail-card-title {
      font-size: 15px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }
    .info-grid-2col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
    }
    .info-label {
      font-size: 12px;
      font-weight: 700;
      color: #64748b;
      margin-bottom: 4px;
      text-transform: uppercase;
    }
    .info-value {
      font-size: 14px;
      font-weight: 600;
      color: #0f172a;
    }
    .btn-action-accept {
      background: #16a34a;
      color: #ffffff;
      border: none;
      padding: 10px 22px;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.15s ease;
    }
    .btn-action-accept:hover {
      background: #15803d;
    }
    .btn-action-reject {
      background: #ffffff;
      color: #dc2626;
      border: 1.5px solid #fca5a5;
      padding: 9px 18px;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.15s ease;
    }
    .btn-action-reject:hover {
      background: #fee2e2;
    }
    .btn-back-pending {
      background: #ffffff;
      border: 1.5px solid #cbd5e1;
      color: #475569;
      padding: 9px 18px;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s;
    }
    .btn-back-pending:hover {
      background: #f8fafc;
      color: #0f172a;
    }
  </style>
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'pending_order'])
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka navigasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard / Pending Order / Detail</span>
      </div>
      <div class="header-right">
        <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          <span class="notification-badge">1</span>
        </a>
      </div>
    </header>

    <main class="pending-detail-container">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
          <a href="{{ route('admin.order.pending') }}" class="btn-back-pending" title="Kembali ke Daftar Pending Order">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <span>Kembali ke Pending Order</span>
          </a>
          <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
            Review Pesanan #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
          </h1>
        </div>

        @php
          $currentStatus = strtolower($order->order_status ?? 'pending');
          $isPending = $currentStatus === 'pending';
          $isCancelled = in_array($currentStatus, ['dibatalkan', 'batal', 'cancelled']);
        @endphp

        <div>
          @if ($isPending)
            <span style="background: #ffedd5; color: #ea580c; border: 1.5px solid #fed7aa; padding: 5px 14px; border-radius: 999px; font-size: 13px; font-weight: 800;">
              ⏳ STATUS PENDING
            </span>
          @elseif ($isCancelled)
            <span style="background: #f1f5f9; color: #475569; border: 1.5px solid #cbd5e1; padding: 5px 14px; border-radius: 999px; font-size: 13px; font-weight: 800;">
              ❌ PESANAN DIBATALKAN / DITOLAK
            </span>
          @else
            <span style="background: #ecfdf5; color: #15803d; border: 1.5px solid #bbf7d0; padding: 5px 14px; border-radius: 999px; font-size: 13px; font-weight: 800;">
              ✅ PESANAN DIPROSES
            </span>
          @endif
        </div>
      </div>

      {{-- Deadline Banner & Action Area --}}
      @if ($isPending)
        <div class="pending-deadline-banner">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: #fdba74; color: #7c2d12; display: flex; align-items: center; justify-content: center; font-size: 18px;">
              ⚠️
            </div>
            <div>
              <div style="font-size: 13.5px; font-weight: 700; color: #9a3412;">
                Batas Waktu Konfirmasi Toko: {{ $order->days_remaining ?? 3 }} Hari Lagi
              </div>
              <div style="font-size: 12px; color: #c2410c; margin-top: 2px;">
                Jika pesanan tidak diterima dalam 3 hari sejak checkout (sebelum {{ $order->deadline_date ? $order->deadline_date->format('d M Y H:i') : '-' }}), sistem akan otomatis membatalkannya.
              </div>
            </div>
          </div>

          <div style="display: flex; gap: 10px;">
            <form method="POST" action="{{ route('admin.order.reject', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak pesanan ini?');" style="margin: 0;">
              @csrf
              <button type="submit" class="btn-action-reject">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width: 15px; height: 15px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                <span>Tolak Orderan</span>
              </button>
            </form>

            <form method="POST" action="{{ route('admin.order.accept', $order->id) }}" style="margin: 0;">
              @csrf
              <button type="submit" class="btn-action-accept">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span>Terima Orderan</span>
              </button>
            </form>
          </div>
        </div>
      @elseif ($isCancelled)
        <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 16px;">
              ✕
            </div>
            <div>
              <div style="font-size: 14px; font-weight: 700; color: #334155;">
                Pesanan Ini Telah Ditolak / Dibatalkan
              </div>
              <div style="font-size: 12.5px; color: #64748b; margin-top: 2px;">
                Pesanan telah dibatalkan dan tersimpan di Riwayat Pending Order. Fitur terima atau tolak tidak lagi tersedia.
              </div>
            </div>
          </div>
          <span style="font-size: 12px; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 6px 12px; border-radius: 6px;">
            Status: Dibatalkan
          </span>
        </div>
      @else
        <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 16px;">
              ✓
            </div>
            <div>
              <div style="font-size: 14px; font-weight: 700; color: #166534;">
                Pesanan Telah Diterima (Diproses)
              </div>
              <div style="font-size: 12.5px; color: #15803d; margin-top: 2px;">
                Pesanan telah masuk ke data pesanan utama untuk dipersiapkan dan dikirim.
              </div>
            </div>
          </div>
          <a href="{{ route('admin.order.show', $order->id) }}" style="font-size: 12.5px; font-weight: 700; color: #166534; background: #ffffff; border: 1px solid #86efac; padding: 6px 14px; border-radius: 6px; text-decoration: none;">
            Buka di Data Pesanan Utama &rarr;
          </a>
        </div>
      @endif

      {{-- Card 1: Data Pelanggan & Alamat Pengiriman --}}
      <div class="detail-card-panel">
        <h2 class="detail-card-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          Informasi Pelanggan
        </h2>
        <div class="info-grid-2col">
          <div>
            <div class="info-label">Nama Pembeli</div>
            <div class="info-value">{{ $order->user->name ?? 'Pelanggan Maybeads' }}</div>
          </div>
          <div>
            <div class="info-label">Email</div>
            <div class="info-value">{{ $order->user->email ?? '-' }}</div>
          </div>
          <div>
            <div class="info-label">Nomor WhatsApp / Telepon</div>
            <div class="info-value">{{ $order->user->phone ?? '08xxxxxxxxxx' }}</div>
          </div>
          <div>
            <div class="info-label">Alamat Pengiriman</div>
            <div class="info-value">{{ $order->address ?: 'Ambil Sendiri di Store Maybeads Banjarbaru' }}</div>
          </div>
        </div>
      </div>

      {{-- Card 2: Rincian Produk yang Dipesan --}}
      <div class="detail-card-panel">
        <h2 class="detail-card-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          Produk Dipesan
        </h2>
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 16px;">
          <div style="display: flex; align-items: center; gap: 14px;">
            <img src="{{ $order->product?->image_url ?? $order->product?->image ?? asset('images/landing/prod-hairclip.jpg') }}" alt="" style="width: 56px; height: 56px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0;">
            <div>
              <div style="font-size: 14.5px; font-weight: 700; color: #0f172a;">{{ $order->product?->product_name ?? 'Produk Aksesoris' }}</div>
              <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                Kategori: {{ $order->product?->category?->category_name ?? 'Aksesoris Manik' }}
              </div>
            </div>
          </div>

          <div>
            <div class="info-label">Jumlah</div>
            <div class="info-value" style="font-size: 15px;">{{ $order->quantity }} pcs</div>
          </div>

          <div>
            <div class="info-label">Harga Satuan</div>
            <div class="info-value">Rp {{ number_format($order->product?->price ?? 0, 0, ',', '.') }}</div>
          </div>

          <div>
            <div class="info-label">Subtotal</div>
            <div class="info-value" style="font-size: 16px; font-weight: 800; color: #2563eb;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
          </div>
        </div>
      </div>

      {{-- Card 3: Detail Pembayaran & Status --}}
      <div class="detail-card-panel">
        <h2 class="detail-card-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
          Status Pembayaran
        </h2>
        <div class="info-grid-2col">
          <div>
            <div class="info-label">Metode Pembayaran</div>
            <div class="info-value">{{ strtoupper($order->payment?->payment_method ?? 'QRIS') }}</div>
          </div>
          <div>
            <div class="info-label">Status Tagihan</div>
            <div class="info-value" style="color: #16a34a; font-weight: 700;">
              ✓ {{ ucfirst($order->payment?->payment_status ?? 'Menunggu Verifikasi') }}
            </div>
          </div>
          <div>
            <div class="info-label">Waktu Checkout</div>
            <div class="info-value">{{ $order->order_at ? $order->order_at->format('d F Y, H:i:s') : ($order->created_at ? $order->created_at->format('d F Y, H:i:s') : '-') }}</div>
          </div>
          <div>
            <div class="info-label">Total Tagihan Dibayar</div>
            <div class="info-value" style="font-size: 18px; font-weight: 800; color: #0f172a;">
              Rp {{ number_format($order->subtotal, 0, ',', '.') }}
            </div>
          </div>
        </div>
      </div>

      {{-- Bottom Actions --}}
      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px;">
        <a href="{{ route('admin.order.pending') }}" class="btn-back-pending">Kembali</a>
        <form method="POST" action="{{ route('admin.order.reject', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak pesanan ini?');" style="margin: 0;">
          @csrf
          <button type="submit" class="btn-action-reject">Tolak Orderan</button>
        </form>
        <form method="POST" action="{{ route('admin.order.accept', $order->id) }}" style="margin: 0;">
          @csrf
          <button type="submit" class="btn-action-accept">Terima Orderan</button>
        </form>
      </div>
    </main>
  </div>
</div>

</body>
</html>

