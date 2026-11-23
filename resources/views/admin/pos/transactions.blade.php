<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Riwayat Transaksi Kasir — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'pos_transactions'])
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
        <span class="header-title">Admin Dashboard / Riwayat Kasir</span>
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

    <main class="page-container" style="padding: 24px 36px 60px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
          <h1 class="page-title" style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Riwayat Transaksi Kasir</h1>
          <p class="page-subtitle" style="font-size: 13.5px; color: #64748b;">Pantauan transaksi offline dan in-store dari mesin kasir toko.</p>
        </div>
        <div>
          <a href="{{ route('admin.pos.index') }}" style="display: inline-flex; align-items: center; gap: 8px; background: #2563eb; color: #fff; padding: 10px 18px; border-radius: 8px; font-size: 13.5px; font-weight: 600; text-decoration: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
              <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
              <line x1="8" y1="21" x2="16" y2="21"></line>
              <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
            Buka Mesin Kasir (POS)
          </a>
        </div>
      </div>

      {{-- 3 Kartu Ringkasan Penjualan Kasir --}}
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 22px;">
        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px;">
          <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Hari Ini</div>
          <div style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 4px 0;">Rp {{ number_format($stats['today'] ?? 0, 0, ',', '.') }}</div>
          <div style="font-size: 12px; color: #94a3b8;">{{ $stats['today_count'] ?? 0 }} transaksi selesai</div>
        </div>

        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px;">
          <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Bulan Ini</div>
          <div style="font-size: 22px; font-weight: 800; color: #2563eb; margin: 4px 0;">Rp {{ number_format($stats['month'] ?? 0, 0, ',', '.') }}</div>
          <div style="font-size: 12px; color: #94a3b8;">{{ $stats['month_count'] ?? 0 }} transaksi selesai</div>
        </div>

        <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px 20px;">
          <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Keseluruhan</div>
          <div style="font-size: 22px; font-weight: 800; color: #059669; margin: 4px 0;">Rp {{ number_format($stats['all'] ?? 0, 0, ',', '.') }}</div>
          <div style="font-size: 12px; color: #94a3b8;">{{ $stats['all_count'] ?? 0 }} total transaksi</div>
        </div>
      </div>

      {{-- Sortir Filter Tabs --}}
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
        <span style="font-size: 13px; font-weight: 700; color: #475569;">Sortir Riwayat:</span>
        <div style="display: inline-flex; background: #f1f5f9; padding: 4px; border-radius: 8px; gap: 4px;">
          <a href="{{ route('admin.pos.transactions', ['filter' => 'semua']) }}" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; text-decoration: none; {{ ($filter ?? 'semua') === 'semua' ? 'background: #ffffff; color: #0f172a; box-shadow: 0 1px 2px rgba(0,0,0,0.05);' : 'color: #64748b;' }}">Semua Transaksi</a>
          <a href="{{ route('admin.pos.transactions', ['filter' => 'hari_ini']) }}" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; text-decoration: none; {{ ($filter ?? '') === 'hari_ini' ? 'background: #ffffff; color: #0f172a; box-shadow: 0 1px 2px rgba(0,0,0,0.05);' : 'color: #64748b;' }}">Hari Ini</a>
          <a href="{{ route('admin.pos.transactions', ['filter' => 'bulan_ini']) }}" style="padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 700; text-decoration: none; {{ ($filter ?? '') === 'bulan_ini' ? 'background: #ffffff; color: #0f172a; box-shadow: 0 1px 2px rgba(0,0,0,0.05);' : 'color: #64748b;' }}">Bulan Ini</a>
        </div>
      </div>

      <div class="table-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <table class="data-table" style="width: 100%;">
          <thead>
            <tr>
              <th>Kode Transaksi</th>
              <th>Waktu</th>
              <th>Kasir</th>
              <th>Pelanggan</th>
              <th>Items</th>
              <th>Total</th>
              <th>Metode</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transactions as $t)
              <tr>
                <td><strong>{{ $t->transaction_code }}</strong></td>
                <td style="font-size: 12.5px; color: #64748b;">{{ $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $t->cashier->name ?? 'Admin Kasir' }}</td>
                <td>{{ $t->customer_name ?: 'Pelanggan Langsung' }}</td>
                <td>
                  <span style="font-weight: 600;">{{ $t->items->sum('quantity') }} pcs</span>
                  <div style="font-size: 11px; color: #94a3b8;">{{ $t->items->count() }} jenis produk</div>
                </td>
                <td><strong style="color: #0f172a;">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</strong></td>
                <td>
                  <span class="badge" style="background: #eff6ff; color: #1e40af; font-weight: 700; text-transform: uppercase;">
                    {{ $t->payment_method }}
                  </span>
                </td>
                <td>
                  <a href="{{ route('admin.pos.show', $t->id) }}" style="background: #f1f5f9; color: #1e293b; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1;">
                    Lihat Struk
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                  Belum ada transaksi kasir yang tercatat.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if($transactions->hasPages())
          <div style="padding: 16px;">
            {{ $transactions->links() }}
          </div>
        @endif
      </div>
    </main>
  </div>
</div>

</body>
</html>
