<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pending Order — Maybeads Admin</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/order-index.css', 'resources/js/admin/dashboard.js'])

  <style>
    .pending-card-melonjong {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 18px 24px;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
      transition: all 0.2s ease;
      flex-wrap: wrap;
    }
    .pending-card-melonjong:hover {
      border-color: #cbd5e1;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transform: translateY(-1px);
    }
    .user-avatar-circle {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: #eff6ff;
      color: #2563eb;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 15px;
      flex-shrink: 0;
      border: 1.5px solid #dbeafe;
    }
    .days-badge-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
    }
    .days-badge-urgent {
      background: #fee2e2;
      color: #dc2626;
      border: 1px solid #fca5a5;
    }
    .days-badge-warning {
      background: #fef3c7;
      color: #d97706;
      border: 1px solid #fde68a;
    }
    .days-badge-normal {
      background: #ffedd5;
      color: #ea580c;
      border: 1px solid #fed7aa;
    }
    .btn-action-accept {
      background: #16a34a;
      color: #ffffff;
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s ease;
    }
    .btn-action-accept:hover {
      background: #15803d;
    }
    .btn-action-reject {
      background: #ffffff;
      color: #dc2626;
      border: 1.5px solid #fca5a5;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.15s ease;
    }
    .btn-action-reject:hover {
      background: #fee2e2;
      border-color: #ef4444;
    }
    .btn-action-detail {
      background: #f8fafc;
      color: #334155;
      border: 1px solid #cbd5e1;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12.5px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.15s ease;
    }
    .btn-action-detail:hover {
      background: #f1f5f9;
      color: #0f172a;
    }
  </style>
</head>
<body>

<div class="admin-layout">
  {{-- Sidebar --}}
  @include('admin.sidebar', ['active' => 'pending_order'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    {{-- Header --}}
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka navigasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard / Pending Order</span>
      </div>

      <div class="header-right">
        <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          @if($pendingOrders->count() > 0)
            <span class="notification-badge">{{ $pendingOrders->count() }}</span>
          @endif
        </a>
      </div>
    </header>

    {{-- Main Content --}}
    <main class="order-content">
      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">Pending Order</span>
      </nav>

      {{-- Success & Error alerts --}}
      @if (session('success'))
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                position: 'top',
                backdrop: false,
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2500,
                customClass: { popup: 'admin-swal-popup' }
              });
            }
          });
        </script>
      @endif

      {{-- Title Row: "Pending Order" & Card "Max pending 3 hari" Sejajar Satu Level --}}
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
        <h1 class="order-page-title" style="margin: 0; font-size: 26px; font-weight: 800; color: #0f172a;">Pending Order</h1>

        <div style="background: #fffbeb; border: 1.5px solid #fef3c7; border-radius: 8px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
          <svg viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.2" style="width: 16px; height: 16px;">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
          <span style="font-size: 13px; font-weight: 700; color: #92400e;">
            Max pending 3 hari
          </span>
        </div>
      </div>

      {{-- DAFTAR CARD MELONJONG PENDING ORDER --}}
      <div class="pending-orders-container" style="margin-bottom: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
          <h2 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0; text-transform: uppercase; letter-spacing: 0.02em;">
            Pesanan Pending Aktif ({{ $pendingOrders->count() }})
          </h2>
        </div>

        @forelse($pendingOrders as $order)
          @php
            $initial = strtoupper(substr($order->user->name ?? 'G', 0, 1));
            $daysBadgeClass = $order->days_remaining <= 0 ? 'days-badge-urgent' : ($order->days_remaining === 1 ? 'days-badge-warning' : 'days-badge-normal');
            $daysText = $order->days_remaining <= 0 ? '🚨 Kedaluwarsa Hari Ini' : "⏳ Sisa {$order->days_remaining} Hari Lagi";
          @endphp
          <div class="pending-card-melonjong">
            {{-- Bagian 1: Data Pengguna --}}
            <div style="display: flex; align-items: center; gap: 14px; min-width: 250px;">
              <div class="user-avatar-circle">{{ $initial }}</div>
              <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-weight: 800; color: #0f172a; font-size: 14.5px;">{{ $order->user->name ?? 'Pelanggan' }}</span>
                  <span style="font-size: 11px; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">
                    #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                  </span>
                </div>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $order->user->email ?? '-' }} &bull; {{ $order->user->phone ?? '08xxxxxxxx' }}</div>
                <div style="font-size: 11.5px; color: #94a3b8; margin-top: 3px; max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                  📍 {{ $order->address ?: 'Ambil Ditempat' }}
                </div>
              </div>
            </div>

            {{-- Bagian 2: Ringkasan Produk & Status Pending --}}
            <div style="min-width: 180px;">
              <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;">
                {{ $order->product->product_name ?? 'Produk Aksesoris' }}
              </div>
              <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                {{ $order->quantity }} pcs &bull; <strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong>
              </div>
              <div style="margin-top: 5px;">
                <span class="badge-status-dot badge-proses" style="background: #ffedd5; color: #ea580c; border: 1px solid #fed7aa; padding: 2px 8px; border-radius: 6px; font-size: 11.5px; font-weight: 700;">
                  ● Pending (Menunggu)
                </span>
              </div>
            </div>

            {{-- Bagian 3: Countdown Sisa Hari --}}
            <div style="text-align: right; min-width: 160px;">
              <div class="days-badge-pill {{ $daysBadgeClass }}">
                {{ $daysText }}
              </div>
              <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">
                Batas: {{ $order->deadline_date ? $order->deadline_date->format('d M Y H:i') : '-' }}
              </div>
            </div>

            {{-- Bagian 4: Aksi Admin (Lihat Detail, Terima, Tolak) --}}
            <div style="display: flex; align-items: center; gap: 8px;">
              <a href="{{ route('admin.order.pendingDetail', $order->id) }}" class="btn-action-detail" title="Lihat detail pesanan khusus pending order">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span>Detail</span>
              </a>

              {{-- Tombol Tolak Orderan --}}
              <form method="POST" action="{{ route('admin.order.reject', $order->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak pesanan #ORD-{{ $order->id }}?');" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-action-reject" title="Tolak orderan ini">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width: 14px; height: 14px;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                  <span>Tolak</span>
                </button>
              </form>

              {{-- Tombol Terima Orderan --}}
              <form method="POST" action="{{ route('admin.order.accept', $order->id) }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-action-accept" title="Terima orderan dan alihkan ke pesanan utama">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px;">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                  <span>Terima Orderan</span>
                </button>
              </form>
            </div>
          </div>
        @empty
          <div style="background: #ffffff; border: 1.5px dashed #cbd5e1; border-radius: 14px; padding: 48px 24px; text-align: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" style="width: 48px; height: 48px; margin: 0 auto 12px; display: block;">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 14 14"></polyline>
            </svg>
            <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Tidak Ada Pesanan Pending</h3>
            <p style="font-size: 13px; color: #64748b; margin: 0;">Semua pesanan yang masuk telah diterima atau dialihkan ke antrean pesanan utama.</p>
          </div>
        @endforelse
      </div>

      {{-- RIWAYAT PENDING ORDER (Dibatalkan Otomatis / Ditolak) --}}
      <div class="pending-history-section">
        <h2 style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.02em;">
          Riwayat Pending Order (Dibatalkan / Ditolak)
        </h2>

        @forelse($historyOrders as $hOrder)
          <div class="pending-card-melonjong" style="opacity: 0.85; background: #f8fafc;">
            <div style="display: flex; align-items: center; gap: 14px; min-width: 240px;">
              <div class="user-avatar-circle" style="background: #f1f5f9; color: #64748b; border-color: #e2e8f0;">
                {{ strtoupper(substr($hOrder->user->name ?? 'G', 0, 1)) }}
              </div>
              <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-weight: 700; color: #334155; font-size: 14px;">{{ $hOrder->user->name ?? 'Pelanggan' }}</span>
                  <span style="font-size: 11px; font-weight: 600; color: #64748b; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;">
                    #ORD-{{ str_pad($hOrder->id, 4, '0', STR_PAD_LEFT) }}
                  </span>
                </div>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $hOrder->user->email ?? '-' }}</div>
              </div>
            </div>

            <div style="min-width: 180px;">
              <div style="font-size: 13px; font-weight: 600; color: #475569;">
                {{ $hOrder->product->product_name ?? 'Produk Aksesoris' }}
              </div>
              <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                {{ $hOrder->quantity }} pcs &bull; Rp {{ number_format($hOrder->subtotal, 0, ',', '.') }}
              </div>
            </div>

            <div>
              <span style="background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; padding: 3px 9px; border-radius: 6px; font-size: 11.5px; font-weight: 700;">
                Dibatalkan / Ditolak
              </span>
              <div style="font-size: 11px; color: #94a3b8; margin-top: 3px;">
                Waktu: {{ $hOrder->order_at ? $hOrder->order_at->format('d M Y H:i') : '-' }}
              </div>
            </div>

            <div>
              <a href="{{ route('admin.order.pendingDetail', $hOrder->id) }}" class="btn-action-detail">
                Lihat Detail
              </a>
            </div>
          </div>
        @empty
          <div style="font-size: 13px; color: #94a3b8; padding: 18px; text-align: center; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px;">
            Belum ada riwayat pesanan yang dibatalkan.
          </div>
        @endforelse
      </div>

    </main>
  </div>
</div>

</body>
</html>

