<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Notifikasi Sistem — Maybeads Admin</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/dashboard.css', 'resources/js/admin/dashboard.js'])

  <style>
    .notif-container {
      padding: 28px 36px 60px;
      max-width: 900px;
    }
    .notif-card-item {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      text-decoration: none;
      transition: all 0.15s ease;
      box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .notif-card-item:hover {
      border-color: #2563eb;
      box-shadow: 0 4px 12px rgba(37,99,235,0.08);
      transform: translateY(-1px);
    }
    .notif-title {
      font-size: 14px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 3px;
    }
    .notif-desc {
      font-size: 12.5px;
      color: #64748b;
      line-height: 1.4;
      margin: 0;
    }
    .notif-time {
      font-size: 11.5px;
      color: #94a3b8;
      margin-top: 4px;
    }
    .notif-badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 700;
      white-space: nowrap;
    }
  </style>
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'notifications'])
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
        <span class="header-title">Admin Dashboard / Notifikasi</span>
      </div>
      <div class="header-right">
        <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi" title="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
          <span class="notification-badge">{{ $notifications->count() }}</span>
        </a>
      </div>
    </header>

    <main class="notif-container">
      <div style="margin-bottom: 24px;">
        <h1 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Notifikasi</h1>
        <p style="font-size: 13px; color: #64748b; margin: 0;">Pemberitahuan aktivitas pesanan, transaksi pembayaran, dan inventaris toko.</p>
      </div>

      <div class="notif-list-wrap">
        @forelse($notifications as $item)
          <a href="{{ $item['link'] }}" class="notif-card-item">
            <div style="flex: 1; min-width: 0;">
              <div class="notif-title">{{ $item['title'] }}</div>
              <p class="notif-desc">{{ $item['desc'] }}</p>
              <div class="notif-time">{{ $item['time'] }}</div>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
              <span class="notif-badge" style="background: {{ $item['badge_bg'] }}; color: {{ $item['badge_cl'] }};">
                {{ $item['badge'] }}
              </span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px; color: #94a3b8;"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </a>
        @empty
          <div style="background: #ffffff; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 48px 20px; text-align: center; color: #94a3b8;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 44px; height: 44px; margin: 0 auto 10px; display: block; opacity: 0.6;">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <div style="font-weight: 700; color: #475569; margin-bottom: 2px;">Tidak Ada Notifikasi</div>
            <p style="font-size: 12.5px; margin: 0;">Semua aktivitas transaksi dan toko berjalan normal.</p>
          </div>
        @endforelse
      </div>
    </main>
  </div>
</div>

</body>
</html>

