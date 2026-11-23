<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pengaturan Akun &mdash; Maybead.s</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    :root {
      --bg-cream: #fbf9f5;
      --card-white: #ffffff;
      --ink: #141418;
      --muted: #64748b;
      --navy: #244173;
      --navy-dark: #1b3259;
      --border-line: #e2e8f0;
      --font-serif: 'Playfair Display', Georgia, serif;
      --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: var(--font-sans);
      background-color: var(--bg-cream);
      color: var(--ink);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Top Navbar */
    .user-navbar {
      background: #ffffff;
      border-bottom: 1px solid var(--border-line);
      height: 64px;
      padding: 0 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .brand-link {
      font-family: var(--font-serif);
      font-size: 22px;
      font-weight: 800;
      color: var(--ink);
      text-decoration: none;
      letter-spacing: -0.02em;
    }
    .brand-dot { color: #f97316; }
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .btn-pill-nav {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 7px 14px;
      border-radius: 999px;
      border: 1.5px solid var(--border-line);
      background: #ffffff;
      color: var(--ink);
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.15s ease;
      position: relative;
    }
    .btn-pill-nav:hover {
      background: #f1f5f9;
      border-color: #cbd5e1;
    }
    .cart-badge-dot {
      background: #2563eb;
      color: #ffffff;
      font-size: 10.5px;
      font-weight: 800;
      padding: 1px 6px;
      border-radius: 999px;
      margin-left: 2px;
    }

    /* Main Settings Container */
    .settings-main {
      max-width: 1180px;
      width: 100%;
      margin: 32px auto 80px;
      padding: 0 20px;
      flex: 1;
    }

    .breadcrumb-row {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: var(--muted);
      margin-bottom: 20px;
    }
    .breadcrumb-row a { color: var(--muted); text-decoration: none; }
    .breadcrumb-row a:hover { color: var(--ink); }

    /* Layout 2 Kolom */
    .settings-grid-layout {
      display: grid;
      grid-template-columns: 280px 1fr;
      gap: 28px;
      align-items: start;
    }

    /* Sidebar Menu Tabs */
    .settings-nav-sidebar {
      background: #ffffff;
      border: 1px solid var(--border-line);
      border-radius: 16px;
      padding: 18px 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .user-profile-summary {
      padding: 12px 14px 18px;
      border-bottom: 1px solid var(--border-line);
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .user-avatar-lg {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      color: #ffffff;
      font-size: 18px;
      font-weight: 800;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .user-meta-name { font-size: 14.5px; font-weight: 700; color: var(--ink); }
    .user-meta-email { font-size: 12px; color: var(--muted); margin-top: 1px; }

    .nav-tab-btn {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 14px;
      border-radius: 10px;
      border: none;
      background: transparent;
      color: #475569;
      font-size: 13.5px;
      font-weight: 600;
      cursor: pointer;
      text-align: left;
      transition: all 0.15s ease;
      margin-bottom: 3px;
    }
    .nav-tab-btn:hover {
      background: #f8fafc;
      color: var(--ink);
    }
    .nav-tab-btn.active {
      background: #eff6ff;
      color: #2563eb;
      font-weight: 700;
    }
    .nav-tab-btn svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
    }

    /* Content Area Cards */
    .settings-content-card {
      background: #ffffff;
      border: 1px solid var(--border-line);
      border-radius: 16px;
      padding: 28px 32px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.02);
      display: none;
    }
    .settings-content-card.active {
      display: block;
    }

    .section-head-title {
      font-size: 18px;
      font-weight: 800;
      color: var(--ink);
      margin-bottom: 6px;
    }
    .section-head-desc {
      font-size: 13px;
      color: var(--muted);
      margin-bottom: 24px;
    }

    /* Form Inputs */
    .form-group-item {
      margin-bottom: 18px;
    }
    .form-label-title {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #334155;
      margin-bottom: 6px;
    }
    .form-input-text, .form-input-textarea {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid var(--border-line);
      border-radius: 8px;
      font-size: 13.5px;
      font-family: inherit;
      color: var(--ink);
      background: #ffffff;
      outline: none;
      transition: border-color 0.15s;
    }
    .form-input-text:focus, .form-input-textarea:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-submit-navy {
      background: var(--navy);
      color: #ffffff;
      border: none;
      padding: 11px 24px;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.15s;
    }
    .btn-submit-navy:hover {
      background: var(--navy-dark);
    }

    /* Order Card Item */
    .user-order-card {
      background: #f8fafc;
      border: 1px solid var(--border-line);
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }
    .badge-order-status {
      padding: 3px 9px;
      border-radius: 6px;
      font-size: 11.5px;
      font-weight: 700;
    }
    .status-pending { background: #ffedd5; color: #ea580c; border: 1px solid #fed7aa; }
    .status-proses { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .status-selesai { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .status-batal { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

    /* Notif Item */
    .notif-item-row {
      padding: 14px 16px;
      border-radius: 10px;
      background: #f8fafc;
      border: 1px solid var(--border-line);
      margin-bottom: 10px;
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }
    .notif-dot-blue {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #2563eb;
      margin-top: 5px;
      flex-shrink: 0;
    }

    @media (max-width: 820px) {
      .settings-grid-layout {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="user-navbar">
    <a href="{{ route('main.dashboard') }}" class="brand-link">
      Maybead<span class="brand-dot">.s</span>
    </a>

    <div class="nav-actions">
      <a href="{{ route('main.dashboard') }}" class="btn-pill-nav">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
        <span>Belanja</span>
      </a>

      <a href="{{ route('cart.index') }}" class="btn-pill-nav" id="navCartBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span>Keranjang</span>
        <span class="cart-badge-dot" id="cartCountBadge">{{ $cartCount ?? 0 }}</span>
      </a>

      <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <input type="hidden" name="redirect_to" value="{{ route('main.dashboard') }}">
        <button type="submit" class="btn-pill-nav" style="color: #dc2626; border-color: #fca5a5;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:15px;height:15px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span>Keluar</span>
        </button>
      </form>
    </div>
  </nav>

  <!-- Main Settings Container -->
  <main class="settings-main">
    <div class="breadcrumb-row">
      <a href="{{ route('main.dashboard') }}">Beranda</a>
      <span>&gt;</span>
      <span style="color: var(--ink); font-weight: 600;">Pengaturan Akun</span>
    </div>

    @if(session('success'))
      <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 13.5px; font-weight: 600;">
        ✓ {{ session('success') }}
      </div>
    @endif

    <div class="settings-grid-layout">
      
      <!-- Sidebar Nav Tabs -->
      <aside class="settings-nav-sidebar">
        <div class="user-profile-summary">
          <div class="user-avatar-lg">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
          <div style="min-width: 0; overflow: hidden;">
            <div class="user-meta-name" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $user->name }}</div>
            <div class="user-meta-email" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $user->email }}</div>
          </div>
        </div>

        <button type="button" class="nav-tab-btn active" onclick="switchSettingsTab('tabProfile', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>Profil Akun</span>
        </button>

        <button type="button" class="nav-tab-btn" onclick="switchSettingsTab('tabAddress', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>Daftar Alamat</span>
        </button>

        <button type="button" class="nav-tab-btn" onclick="switchSettingsTab('tabOrders', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          <span>Status Orderan ({{ $orders->count() }})</span>
        </button>

        <button type="button" class="nav-tab-btn" onclick="switchSettingsTab('tabCart', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          <span>Keranjang Saya ({{ $cartCount }})</span>
        </button>

        <button type="button" class="nav-tab-btn" onclick="switchSettingsTab('tabNotifications', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span>Notifikasi ({{ $notifications->count() }})</span>
        </button>
      </aside>

      <!-- Right Content Tabs -->
      <section class="settings-content-wrap">

        <!-- TAB 1: PROFIL AKUN -->
        <div class="settings-content-card active" id="tabProfile">
          <h2 class="section-head-title">Profil &amp; Informasi Akun</h2>
          <p class="section-head-desc">Kelola informasi data diri, email, dan keamanan kata sandi akun Maybead.s Anda.</p>

          <form method="POST" action="{{ route('account.settings.profile') }}">
            @csrf
            <div class="form-group-item">
              <label class="form-label-title" for="profileName">Nama Lengkap</label>
              <input type="text" name="name" id="profileName" class="form-input-text" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group-item">
              <label class="form-label-title">Alamat Email</label>
              <input type="email" class="form-input-text" value="{{ $user->email }}" disabled style="background: #f8fafc; color: #64748b; cursor: not-allowed;">
              <small style="color: #94a3b8; font-size: 11.5px; margin-top: 4px; display: block;">Alamat email digunakan sebagai tanda pengenal akun resmi Anda.</small>
            </div>

            <div class="form-group-item">
              <label class="form-label-title" for="profilePhone">Nomor WhatsApp / Telepon</label>
              <input type="text" name="phone" id="profilePhone" class="form-input-text" value="{{ old('phone', $user->phone ?? '08') }}" placeholder="08xxxxxxxxxx">
            </div>

            <div style="border-top: 1px dashed var(--border-line); margin: 24px 0; padding-top: 20px;">
              <h3 style="font-size: 15px; font-weight: 700; color: var(--ink); margin-bottom: 12px;">Ubah Kata Sandi (Opsional)</h3>
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div class="form-group-item">
                  <label class="form-label-title" for="password">Kata Sandi Baru</label>
                  <input type="password" name="password" id="password" class="form-input-text" placeholder="Minimal 6 karakter">
                </div>
                <div class="form-group-item">
                  <label class="form-label-title" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-input-text" placeholder="Ulangi kata sandi">
                </div>
              </div>
            </div>

            <button type="submit" class="btn-submit-navy">Simpan Perubahan Profil</button>
          </form>
        </div>

        <!-- TAB 2: DAFTAR & TAMBAH ALAMAT -->
        <div class="settings-content-card" id="tabAddress">
          <h2 class="section-head-title">Manajemen Alamat Pengiriman</h2>
          <p class="section-head-desc">Atur alamat pengiriman pesanan Anda untuk mempermudah proses checkout dan pengiriman kurir.</p>

          <div style="background: #f8fafc; border: 1.5px solid var(--border-line); border-radius: 12px; padding: 18px 20px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
              <span style="font-size: 12px; font-weight: 800; color: #16a34a; text-transform: uppercase; letter-spacing: 0.04em;">📍 Alamat Utama Saat Ini</span>
            </div>
            <div style="font-size: 14px; font-weight: 700; color: #0f172a;">{{ $user->name }} &bull; {{ $user->phone ?? '08xxxxxxxxxx' }}</div>
            <div style="font-size: 13.5px; color: #475569; margin-top: 4px; line-height: 1.5;">
              {{ $user->address ?: 'Belum ada alamat tersimpan. Silakan isi formulir di bawah ini.' }}
            </div>
          </div>

          <form method="POST" action="{{ route('account.settings.address') }}">
            @csrf
            <div class="form-group-item">
              <label class="form-label-title" for="addressTextarea">Tambah / Perbarui Alamat Lengkap:</label>
              <textarea name="address" id="addressTextarea" class="form-input-textarea" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos..." required>{{ old('address', $user->address) }}</textarea>
              <small style="color: #64748b; font-size: 11.5px; margin-top: 4px; display: block;">Pastikan alamat akurat agar pesanan dapat diantar oleh kurir J&amp;T, JNE, atau Gojek tanpa kendala.</small>
            </div>

            <button type="submit" class="btn-submit-navy">Simpan Alamat Pengiriman</button>
          </form>
        </div>

        <!-- TAB 3: STATUS ORDERAN USER -->
        <div class="settings-content-card" id="tabOrders">
          <h2 class="section-head-title">Status Pesanan Saya</h2>
          <p class="section-head-desc">Pantau perkembangan seluruh pesanan yang pernah Anda lakukan di Maybead.s.</p>

          @forelse($orders as $order)
            @php
              $stLower = strtolower($order->order_status ?? 'pending');
              $badgeClass = match($stLower) {
                'pending' => 'status-pending',
                'diproses', 'proses', 'sedang perjalanan', 'siap diambil' => 'status-proses',
                'selesai', 'sukses' => 'status-selesai',
                default => 'status-batal',
              };
            @endphp
            <div class="user-order-card">
              <div>
                <div style="display: flex; align-items: center; gap: 8px;">
                  <strong style="color: #0f172a; font-size: 14px;">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong>
                  <span class="badge-order-status {{ $badgeClass }}">{{ ucfirst($order->order_status) }}</span>
                </div>
                <div style="font-size: 13px; font-weight: 600; color: #334155; margin-top: 4px;">
                  {{ $order->product->product_name ?? 'Produk Aksesoris' }} ({{ $order->quantity }} pcs)
                </div>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                  Waktu: {{ $order->order_at ? $order->order_at->format('d M Y, H:i') : '-' }} &bull; Total: <strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong>
                </div>
              </div>

              <div>
                <a href="{{ route('order.progress', ['order_id' => $order->id]) }}" class="btn-pill-nav" style="font-size: 12px; padding: 6px 14px; background: #ffffff;">
                  Lihat Detail Status &rarr;
                </a>
              </div>
            </div>
          @empty
            <div style="text-align: center; padding: 48px 16px; color: #94a3b8; background: #f8fafc; border: 1.5px dashed var(--border-line); border-radius: 12px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 44px; height: 44px; margin: 0 auto 10px; opacity: 0.5;">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              <div style="font-weight: 700; color: #475569; margin-bottom: 4px;">Belum Ada Pesanan</div>
              <p style="font-size: 13px;">Jelajahi koleksi kami dan lakukan pesanan pertama Anda sekarang!</p>
              <a href="{{ route('main.dashboard') }}" class="btn-submit-navy" style="display: inline-block; margin-top: 14px; text-decoration: none;">Belanja Sekarang</a>
            </div>
          @endforelse
        </div>

        <!-- TAB 4: KERANJANG SAYA -->
        <div class="settings-content-card" id="tabCart">
          <h2 class="section-head-title">Keranjang Belanja</h2>
          <p class="section-head-desc">Daftar item perhiasan dan manik-manik yang saat ini ada di keranjang Anda.</p>

          @forelse($cartItems as $cItem)
            <div class="user-order-card">
              <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ $cItem->product?->image_url ?? $cItem->product?->image ?? asset('images/landing/prod-hairclip.jpg') }}" alt="" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border-line);">
                <div>
                  <div style="font-weight: 700; font-size: 13.5px; color: #0f172a;">{{ $cItem->product?->product_name ?? 'Produk Maybeads' }}</div>
                  <div style="font-size: 12px; color: #64748b;">{{ $cItem->quantity }} pcs &bull; Rp {{ number_format($cItem->product?->price ?? 0, 0, ',', '.') }}</div>
                </div>
              </div>

              <div>
                <a href="{{ route('cart.index') }}" class="btn-pill-nav" style="font-size: 12px; padding: 6px 14px;">
                  Buka di Keranjang
                </a>
              </div>
            </div>
          @empty
            <div style="text-align: center; padding: 48px 16px; color: #94a3b8; background: #f8fafc; border: 1.5px dashed var(--border-line); border-radius: 12px;">
              <p style="font-size: 13px; margin-bottom: 12px;">Keranjang Anda saat ini masih kosong.</p>
              <a href="{{ route('main.dashboard') }}" class="btn-submit-navy" style="text-decoration: none;">Cari Produk</a>
            </div>
          @endforelse

          @if($cartItems->count() > 0)
            <div style="margin-top: 20px;">
              <a href="{{ route('cart.index') }}" class="btn-submit-navy" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <span>Lanjut ke Halaman Keranjang Lengkap</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;"><polyline points="9 18 15 12 9 6"/></svg>
              </a>
            </div>
          @endif
        </div>

        <!-- TAB 5: NOTIFIKASI USER -->
        <div class="settings-content-card" id="tabNotifications">
          <h2 class="section-head-title">Notifikasi Pengguna</h2>
          <p class="section-head-desc">Pemberitahuan resmi mengenai status transaksi dan pesanan Anda.</p>

          @forelse($notifications as $notif)
            <div class="notif-item-row">
              <div class="notif-dot-blue"></div>
              <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                  <strong style="font-size: 13.5px; color: #0f172a;">{{ $notif['title'] }}</strong>
                  <span style="font-size: 11.5px; color: #94a3b8;">{{ $notif['time'] }}</span>
                </div>
                <p style="font-size: 13px; color: #475569; margin: 0; line-height: 1.45;">{{ $notif['message'] }}</p>
              </div>
            </div>
          @empty
            <div style="text-align: center; padding: 40px 16px; color: #94a3b8; background: #f8fafc; border-radius: 12px;">
              Tidak ada notifikasi baru saat ini.
            </div>
          @endforelse
        </div>

      </section>

    </div>
  </main>

  <script>
    function switchSettingsTab(tabId, btn) {
      document.querySelectorAll('.nav-tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.settings-content-card').forEach(c => c.classList.remove('active'));

      btn.classList.add('active');
      const target = document.getElementById(tabId);
      if (target) target.classList.add('active');
    }
  </script>

</body>
</html>

