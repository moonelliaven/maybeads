<!DOCTYPE html>
<html lang="id">
@php
  $methods = $methods ?? collect();
  $stats = $stats ?? [
    'total_revenue_all' => 0,
    'month_revenue_all' => 0,
    'selected_month'    => date('n'),
    'selected_year'     => date('Y'),
    'active_count'      => 0,
    'total_count'       => 0,
  ];
  $search = $search ?? '';
  $statusFilter = $statusFilter ?? 'semua';
  $typeFilter = $typeFilter ?? 'semua';
  $selectedMonth = $selectedMonth ?? date('n');
  $selectedYear = $selectedYear ?? date('Y');
  $sortBy = $sortBy ?? 'default';

  $monthNames = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
  ];
@endphp
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Metode Pembayaran — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/payment-method.css'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'payment-method'])

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
        <span class="header-title">Admin Dashboard / Metode Pembayaran</span>
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
    <main class="payment-method-content">

      {{-- Flash Message Alerts --}}
      @if (session('success'))
        <div class="alert alert-success" role="alert" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-error" role="alert" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
          {{ session('error') }}
        </div>
      @endif

      {{-- Top Header Row --}}
      <div class="content-top-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
        <div>
          <h1 class="page-title" style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Metode Pembayaran</h1>
          <p class="page-subtitle" style="font-size: 13.5px; color: #64748b;">Kelola konfigurasi channel pembayaran, biaya admin, serta pantau akumulasi omset.</p>
        </div>

        <div class="top-action-buttons">
          <a href="{{ route('admin.payment-method.create') }}" class="btn-primary-add" style="display: inline-flex; align-items: center; gap: 8px; background: #2563eb; color: #fff; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 13.5px; text-decoration: none; box-shadow: 0 2px 4px rgba(37,99,235,0.2);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Metode Baru</span>
          </a>
        </div>
      </div>

      {{-- KPI Statistik: Akumulasi Keseluruhan & Pemasukan Bulan Terpilih --}}
      <div class="stats-overview-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">

        {{-- Card 1: Total Akumulasi Keseluruhan --}}
        <div class="stat-kpi-card highlight-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em;">Akumulasi Keseluruhan</span>
            <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
              </svg>
            </div>
          </div>
          <div style="font-size: 24px; font-weight: 800; color: #0f172a;">Rp {{ number_format($stats['total_revenue_all'], 0, ',', '.') }}</div>
          <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Total omset lunas seluruh waktu</div>
        </div>

        {{-- Card 2: Pemasukan Bulan Ini (Dengan Pilihan Bulan) --}}
        <div class="stat-kpi-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em;">
              Pemasukan {{ $monthNames[$selectedMonth] ?? 'Bulan Ini' }} {{ $selectedYear }}
            </span>
            <div style="width: 36px; height: 36px; border-radius: 8px; background: #dcfce7; display: flex; align-items: center; justify-content: center; color: #16a34a;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
            </div>
          </div>
          <div style="font-size: 24px; font-weight: 800; color: #15803d;">Rp {{ number_format($stats['month_revenue_all'], 0, ',', '.') }}</div>
          <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Berdasarkan periode bulan yang dipilih</div>
        </div>

        {{-- Card 3: Status Metode --}}
        <div class="stat-kpi-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em;">Metode Aktif</span>
            <div style="width: 36px; height: 36px; border-radius: 8px; background: #dbeafe; display: flex; align-items: center; justify-content: center; color: #2563eb;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
            </div>
          </div>
          <div style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ $stats['active_count'] }} <span style="font-size: 14px; font-weight: 600; color: #64748b;">/ {{ $stats['total_count'] }} Metode</span></div>
          <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Siap digunakan oleh pelanggan</div>
        </div>

      </div>

      {{-- Filter Controls Bar: Bulan, Tahun, Sortir, Pencarian --}}
      <div class="filter-controls-wrap" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
        <form method="GET" action="{{ route('admin.payment-method.index') }}" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; justify-content: space-between;">
          <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            {{-- Filter Bulan --}}
            <select name="month" class="filter-select" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13px; font-weight: 600; color: #334155; background: #fff;">
              @foreach($monthNames as $num => $name)
                <option value="{{ $num }}" {{ (int)$selectedMonth === $num ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>

            {{-- Filter Tahun --}}
            <select name="year" class="filter-select" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13px; font-weight: 600; color: #334155; background: #fff;">
              @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                <option value="{{ $y }}" {{ (int)$selectedYear === $y ? 'selected' : '' }}>{{ $y }}</option>
              @endfor
            </select>

            {{-- Sortir Berdasarkan Pemasukan Per Bulan --}}
            <select name="sort" class="filter-select" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13px; font-weight: 600; color: #334155; background: #fff;">
              <option value="default" {{ $sortBy === 'default' ? 'selected' : '' }}>Sortir: Default (ID)</option>
              <option value="month_income_desc" {{ $sortBy === 'month_income_desc' ? 'selected' : '' }}>Pemasukan Bulan Ini Tertinggi</option>
              <option value="total_income_desc" {{ $sortBy === 'total_income_desc' ? 'selected' : '' }}>Total Pemasukan Tertinggi</option>
              <option value="fee_desc" {{ $sortBy === 'fee_desc' ? 'selected' : '' }}>Biaya Admin Tertinggi</option>
              <option value="name_asc" {{ $sortBy === 'name_asc' ? 'selected' : '' }}>Nama Metode (A-Z)</option>
            </select>

            {{-- Filter Status --}}
            <select name="status" class="filter-select" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13px; font-weight: 600; color: #334155; background: #fff;">
              <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Status</option>
              <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif Saja</option>
              <option value="non-active" {{ $statusFilter === 'non-active' ? 'selected' : '' }}>Non-Aktif Saja</option>
            </select>
          </div>

          <div style="display: flex; gap: 8px; align-items: center;">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari metode pembayaran..."
                   style="padding: 8px 14px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13px; min-width: 200px;">
            <button type="submit" style="background: #0f172a; color: #fff; border: none; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
              Cari
            </button>
          </div>
        </form>
      </div>

      {{-- Table Card: Field Sesuai Requirement --}}
      {{-- Requirement 10: "di dalam table, setidaknya hanya tampilkan header table (nama field), lalu dibawahnya terdapat data, dan datanya dengan fieldnya hanya menampilkan metode dan provider, tipe/kategori, biaya admin, dan status. untuk data yang tersisa, hanya tampilkan di detail" --}}
      <div class="table-card-box" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div style="overflow-x: auto;">
          <table class="payment-methods-table" style="width: 100%; border-collapse: collapse;">
            <thead>
              <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0;">
                <th style="padding: 14px 20px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; text-align: left; letter-spacing: 0.03em;">Metode &amp; Provider</th>
                <th style="padding: 14px 20px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; text-align: left; letter-spacing: 0.03em;">Tipe / Kategori</th>
                <th style="padding: 14px 20px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; text-align: left; letter-spacing: 0.03em;">Biaya Admin</th>
                <th style="padding: 14px 20px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; text-align: left; letter-spacing: 0.03em;">Status</th>
                <th style="padding: 14px 20px; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; text-align: right; letter-spacing: 0.03em;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($methods as $item)
                @php
                  $typeClass = match(strtolower($item->type)) {
                      'bank'      => 'type-pill-bank',
                      'auto-link' => 'type-pill-autolink',
                      'qris'      => 'type-pill-qris',
                      'e-wallet'  => 'type-pill-ewallet',
                      default     => 'type-pill-ewallet',
                  };
                @endphp
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  {{-- 1. Metode & Provider --}}
                  <td style="padding: 14px 20px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                      <div style="width: 38px; height: 38px; border-radius: 8px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #334155;">
                        {{ strtoupper(substr($item->code ?: $item->name, 0, 2)) }}
                      </div>
                      <div>
                        <div style="font-weight: 700; font-size: 14px; color: #0f172a;">{{ $item->name }}</div>
                        <div style="font-size: 11.5px; color: #94a3b8;">Kode: <code>{{ $item->code }}</code></div>
                      </div>
                    </div>
                  </td>

                  {{-- 2. Tipe / Kategori --}}
                  <td style="padding: 14px 20px;">
                    <span class="type-pill {{ $typeClass }}" style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                      {{ $item->type }}
                    </span>
                  </td>

                  {{-- 3. Biaya Admin --}}
                  <td style="padding: 14px 20px;">
                    @if ($item->price > 0)
                      <span style="font-weight: 700; color: #0f172a;">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    @else
                      <span style="color: #16a34a; font-weight: 700; font-size: 13px;">Gratis (Rp 0)</span>
                    @endif
                  </td>

                  {{-- 4. Status --}}
                  <td style="padding: 14px 20px;">
                    <form action="{{ route('admin.payment-method.toggleStatus', $item->id) }}" method="POST" style="margin: 0;">
                      @csrf
                      @method('PATCH')
                      <button type="submit"
                              style="cursor: pointer; border: 1.5px solid {{ $item->status === 'active' ? '#bbf7d0' : '#fecaca' }}; background: {{ $item->status === 'active' ? '#f0fdf4' : '#fef2f2' }}; color: {{ $item->status === 'active' ? '#15803d' : '#b91c1c' }}; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;"
                              title="Klik untuk ubah status">
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $item->status === 'active' ? '#16a34a' : '#dc2626' }};"></span>
                        {{ $item->status === 'active' ? 'Aktif' : 'Non-Aktif' }}
                      </button>
                    </form>
                  </td>

                  {{-- 5. Aksi: Detail, Edit, Hapus (Dedicated Pages, Bukan Pop Up) --}}
                  <td style="padding: 14px 20px; text-align: right;">
                    <div style="display: inline-flex; align-items: center; gap: 8px;">
                      {{-- Detail Button (Halaman Khusus) --}}
                      <a href="{{ route('admin.payment-method.show', $item->id) }}"
                         style="background: #f1f5f9; color: #334155; padding: 6px 12px; border-radius: 6px; font-size: 12.5px; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1;"
                         title="Lihat Detail Lengkap">
                        Detail
                      </a>

                      {{-- Edit Button (Halaman Khusus) --}}
                      <a href="{{ route('admin.payment-method.edit', $item->id) }}"
                         style="background: #2563eb; color: #fff; padding: 6px 12px; border-radius: 6px; font-size: 12.5px; font-weight: 600; text-decoration: none;"
                         title="Edit Metode">
                        Edit
                      </a>

                      {{-- Hapus --}}
                      <form action="{{ route('admin.payment-method.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus metode pembayaran ini?')" style="margin: 0; display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="background: transparent; border: 1px solid #fecaca; color: #dc2626; padding: 5px 8px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center;"
                                title="Hapus Metode">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
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
                  <td colspan="5" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                    <div style="font-size: 15px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Tidak ada metode pembayaran ditemukan.</div>
                    <div style="font-size: 13px;">Coba ubah kata kunci pencarian atau filter.</div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </main>
  </div>
</div>

</body>
</html>