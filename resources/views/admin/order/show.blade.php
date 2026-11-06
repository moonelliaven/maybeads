<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Pesanan #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'orders'])

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
        <span class="header-title">Admin Dashboard / Pesanan / Detail</span>
      </div>

      <div class="header-right">
        <div class="header-user-badge">
          <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
          <div class="header-user-info">
            <span class="header-user-name">{{ Auth::user()->name ?? 'Administrator' }}</span>
            <span class="header-user-role">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
          </div>
        </div>
      </div>
    </header>

    <main class="page-container">
      @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
          {{ session('success') }}
        </div>
      @endif

      <div class="page-header">
        <div>
          <a href="{{ route('admin.order.index') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: var(--navy); text-decoration: none; font-weight: 600; margin-bottom: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
              <line x1="19" y1="12" x2="5" y2="12"></line>
              <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Pesanan
          </a>
          <h1 class="page-title">Detail Pesanan #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
          <p class="page-subtitle">Dibuat pada {{ $order->order_at ? $order->order_at->format('d F Y, H:i') : '-' }} WIB</p>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Left: Item Pesanan & Alamat -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <!-- Item Card -->
          <div class="table-card" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">Item Produk</h3>
            <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
              <div style="display: flex; align-items: center; gap: 14px;">
                @if($order->product && $order->product->image)
                  <img src="{{ asset('storage/product/' . $order->product->image) }}" alt="" style="width: 56px; height: 56px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0;">
                @else
                  <div style="width: 56px; height: 56px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                      <circle cx="8.5" cy="8.5" r="1.5"></circle>
                      <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                  </div>
                @endif
                <div>
                  <h4 style="font-size: 14.5px; font-weight: 700; color: #0f172a;">{{ $order->product->product_name ?? 'Produk Dihapus' }}</h4>
                  <p style="font-size: 12.5px; color: #64748b;">Kategori: {{ $order->product->category->category_name ?? '-' }}</p>
                  <p style="font-size: 12.5px; color: #64748b;">Rp {{ number_format($order->product->price ?? 0, 0, ',', '.') }} × {{ $order->quantity }} pcs</p>
                </div>
              </div>
              <div style="font-size: 16px; font-weight: 800; color: var(--navy);">
                Rp {{ number_format($order->subtotal, 0, ',', '.') }}
              </div>
            </div>

            <div style="padding-top: 16px; display: flex; justify-content: flex-end;">
              <div style="width: 240px; display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: #64748b;">
                  <span>Subtotal</span>
                  <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; color: #64748b;">
                  <span>Ongkos Kirim</span>
                  <span>Rp 0 (Gratis)</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #0f172a; border-top: 1px solid #e2e8f0; padding-top: 8px;">
                  <span>Total Tagihan</span>
                  <span style="color: var(--navy);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Customer & Address Card -->
          <div class="table-card" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">Informasi Pembeli & Pengiriman</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div>
                <p style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px;">Nama Lengkap</p>
                <p style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ $order->user->name ?? 'Guest / Kasir Langsung' }}</p>
              </div>
              <div>
                <p style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px;">Email</p>
                <p style="font-size: 14px; color: #1e293b;">{{ $order->user->email ?? '-' }}</p>
              </div>
              <div style="grid-column: 1 / -1;">
                <p style="font-size: 12px; font-weight: 600; text-transform: uppercase; color: #94a3b8; margin-bottom: 4px;">Alamat Pengiriman</p>
                <p style="font-size: 14px; color: #334155; line-height: 1.5; background: #f8fafc; padding: 12px 14px; border-radius: 8px; border: 1px solid var(--line);">
                  {{ $order->address }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Status & Actions -->
        <div>
          <div class="table-card" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 18px;">Status & Pembayaran</h3>

            <form method="POST" action="{{ route('admin.order.update', $order->id) }}">
              @csrf
              @method('PUT')

              <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Status Pesanan</label>
                <select name="order_status" class="form-control">
                  <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="proses" {{ $order->order_status === 'proses' ? 'selected' : '' }}>Diproses (Pengiriman)</option>
                  <option value="selesai" {{ $order->order_status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                  <option value="dibatalkan" {{ $order->order_status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
              </div>

              <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Status Pembayaran</label>
                <select name="payment_status" class="form-control">
                  <option value="menunggu_pembayaran" {{ $order->payment_status === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                  <option value="lunas" {{ $order->payment_status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                  <option value="gagal" {{ $order->payment_status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
              </div>

              <div style="background: #f8fafc; border: 1px solid var(--line); border-radius: 8px; padding: 14px; margin-bottom: 20px; font-size: 12.5px; color: #64748b;">
                <p><strong>Metode:</strong> {{ $order->payment->payment_method ?? 'Belum memilih' }}</p>
                <p style="margin-top: 4px;"><strong>Dibayar Pada:</strong> {{ $order->payment && $order->payment->paid_at ? $order->payment->paid_at->format('d M Y, H:i') : '-' }}</p>
              </div>

              <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                Simpan Perubahan
              </button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

</body>
</html>
