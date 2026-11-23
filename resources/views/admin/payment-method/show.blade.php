<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Metode — {{ $paymentMethod->name }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])

  <style>
    .detail-container {
      max-width: 920px;
      margin: 0 auto;
    }
    .detail-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .detail-stat-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }
    .detail-stat-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 16px;
    }
    .detail-stat-lbl {
      font-size: 11.5px;
      font-weight: 700;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      margin-bottom: 6px;
    }
    .detail-stat-val {
      font-size: 20px;
      font-weight: 800;
      color: #0f172a;
    }
    .info-list-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }
    .info-field-box {
      padding: 12px 16px;
      background: #f8fafc;
      border-radius: 8px;
      border: 1px solid #f1f5f9;
    }
    .info-field-lbl {
      font-size: 11.5px;
      font-weight: 600;
      color: #94a3b8;
      margin-bottom: 4px;
      text-transform: uppercase;
    }
    .info-field-val {
      font-size: 14.5px;
      font-weight: 700;
      color: #1e293b;
    }
  </style>
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'payment-method'])
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard / Detail Metode Pembayaran</span>
      </div>
      @php
        $pendingBadgeCount = \App\Models\Order::where('order_status', 'pending')->count();
      @endphp
      <div class="header-right">
        <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          @if($pendingBadgeCount > 0)
            <span class="notification-badge">{{ $pendingBadgeCount > 99 ? '99+' : $pendingBadgeCount }}</span>
          @endif
        </a>
      </div>
    </header>

    <main class="page-container detail-container">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.payment-method.index') }}" class="btn-back-link" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: #475569; text-decoration: none;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali ke Daftar Metode
        </a>

        <div style="display: flex; gap: 10px;">
          <a href="{{ route('admin.payment-method.edit', $paymentMethod->id) }}" style="display: inline-flex; align-items: center; gap: 6px; background: #2563eb; color: #fff; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 15px; height: 15px;">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Edit Pengaturan
          </a>
        </div>
      </div>

      {{-- Stat Grid --}}
      <div class="detail-stat-grid">
        <div class="detail-stat-box">
          <div class="detail-stat-lbl">Total Omset Masuk</div>
          <div class="detail-stat-val" style="color: #15803d;">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="detail-stat-box">
          <div class="detail-stat-lbl">Transaksi Berhasil</div>
          <div class="detail-stat-val">{{ number_format($transactionCount ?? 0) }}</div>
        </div>
        <div class="detail-stat-box">
          <div class="detail-stat-lbl">Biaya Admin</div>
          <div class="detail-stat-val">
            @if($paymentMethod->price > 0)
              Rp {{ number_format($paymentMethod->price, 0, ',', '.') }}
            @else
              <span style="color: #16a34a;">Gratis</span>
            @endif
          </div>
        </div>
        <div class="detail-stat-box">
          <div class="detail-stat-lbl">Status Metode</div>
          <div class="detail-stat-val" style="font-size: 16px;">
            @if($paymentMethod->status === 'active')
              <span style="color: #16a34a; font-weight: 700;">● Aktif</span>
            @else
              <span style="color: #dc2626; font-weight: 700;">○ Non-Aktif</span>
            @endif
          </div>
        </div>
      </div>

      {{-- Detail Informasi Lengkap --}}
      <div class="detail-card">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 18px;">Informasi Lengkap Metode Pembayaran</h3>

        <div class="info-list-grid">
          <div class="info-field-box">
            <div class="info-field-lbl">Nama Metode & Provider</div>
            <div class="info-field-val">{{ $paymentMethod->name }}</div>
          </div>

          <div class="info-field-box">
            <div class="info-field-lbl">Kode Unik / Slug</div>
            <div class="info-field-val"><code>{{ $paymentMethod->code }}</code></div>
          </div>

          <div class="info-field-box">
            <div class="info-field-lbl">Kategori Tipe</div>
            <div class="info-field-val" style="text-transform: uppercase;">{{ $paymentMethod->type }}</div>
          </div>

          <div class="info-field-box">
            <div class="info-field-lbl">Biaya Transaksi / Admin</div>
            <div class="info-field-val">Rp {{ number_format($paymentMethod->price, 0, ',', '.') }}</div>
          </div>

          <div class="info-field-box">
            <div class="info-field-lbl">Nomor Rekening / Merchant ID</div>
            <div class="info-field-val">{{ $paymentMethod->account_number ?: 'Tidak ada / Otomatis' }}</div>
          </div>

          <div class="info-field-box">
            <div class="info-field-lbl">Atas Nama (a/n) Rekening</div>
            <div class="info-field-val">{{ $paymentMethod->account_name ?: '-' }}</div>
          </div>

          <div class="info-field-box" style="grid-column: span 2;">
            <div class="info-field-lbl">Keterangan / Panduan Pembayaran</div>
            <div class="info-field-val" style="font-weight: 500; font-size: 13.5px; line-height: 1.6; color: #334155;">
              {{ $paymentMethod->description ?: 'Tidak ada instruksi khusus.' }}
            </div>
          </div>
        </div>
      </div>

      {{-- Riwayat Transaksi Menggunakan Metode Ini --}}
      <div class="detail-card">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 14px;">Riwayat Transaksi Terbaru</h3>

        @if($paymentMethod->payments && $paymentMethod->payments->count() > 0)
          <div style="overflow-x: auto;">
            <table class="data-table" style="width: 100%;">
              <thead>
                <tr>
                  <th>ID Bayar</th>
                  <th>ID Pesanan</th>
                  <th>Pelanggan</th>
                  <th>Nominal</th>
                  <th>Status</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($paymentMethod->payments->take(10) as $p)
                  <tr>
                    <td><strong>#PAY-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>
                      <a href="{{ route('admin.order.show', $p->order_id) }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                        #ORD-{{ str_pad($p->order_id, 4, '0', STR_PAD_LEFT) }}
                      </a>
                    </td>
                    <td>{{ $p->order?->user?->name ?? 'Guest' }}</td>
                    <td><strong style="color: #0f172a;">Rp {{ number_format($p->amount, 0, ',', '.') }}</strong></td>
                    <td>
                      <span class="badge {{ strtolower($p->payment_status) === 'sukses' ? 'badge-selesai' : 'badge-pending' }}">
                        {{ $p->payment_status }}
                      </span>
                    </td>
                    <td style="font-size: 12.5px; color: #64748b;">
                      {{ $p->paid_at ? $p->paid_at->format('d M Y, H:i') : ($p->created_at ? $p->created_at->format('d M Y, H:i') : '-') }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div style="text-align: center; padding: 30px; color: #94a3b8; font-size: 13.5px;">
            Belum ada transaksi yang menggunakan metode ini.
          </div>
        @endif
      </div>
    </main>
  </div>
</div>

</body>
</html>
