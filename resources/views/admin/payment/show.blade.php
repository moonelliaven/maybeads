<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Struk Pembayaran #PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])

  <style>
    .receipt-container {
      max-width: 820px;
      margin: 0 auto;
    }
    .receipt-paper {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
    }
    .receipt-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 2px dashed #e2e8f0;
      padding-bottom: 24px;
      margin-bottom: 24px;
    }
    .receipt-brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 26px;
      font-weight: 800;
      color: #1e293b;
      margin-bottom: 4px;
    }
    .receipt-meta-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-bottom: 24px;
      background: #f8fafc;
      padding: 20px;
      border-radius: 8px;
      border: 1px solid #f1f5f9;
    }
    .receipt-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid #f1f5f9;
      font-size: 14px;
    }
    .receipt-row:last-child {
      border-bottom: none;
    }
    .receipt-total-row {
      display: flex;
      justify-content: space-between;
      padding: 16px 0;
      border-top: 2px solid #0f172a;
      border-bottom: 2px solid #0f172a;
      font-size: 18px;
      font-weight: 800;
      color: #0f172a;
      margin: 20px 0;
    }
    .proof-img-box {
      border: 1.5px solid #cbd5e1;
      border-radius: 10px;
      overflow: hidden;
      max-width: 380px;
      background: #f8fafc;
      text-align: center;
      padding: 12px;
    }
    .proof-img-box img {
      width: 100%;
      height: auto;
      border-radius: 6px;
      display: block;
      cursor: zoom-in;
    }
    @media print {
      body * {
        visibility: hidden;
      }
      .admin-sidebar, .admin-header, .receipt-nav-bar, .receipt-actions-footer {
        display: none !important;
      }
      .receipt-paper, .receipt-paper * {
        visibility: visible;
      }
      .receipt-paper {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
        padding: 0;
      }
    }
  </style>
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'payments'])
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
        <span class="header-title">Admin Dashboard / Detail Struk</span>
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

    <main class="page-container receipt-container">
      {{-- Navigasi Atas --}}
      <div class="receipt-nav-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('admin.payment.index') }}" class="btn-back-link" style="display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: #475569; text-decoration: none;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
          </svg>
          Kembali ke Riwayat Pembayaran
        </a>

        <div style="display: flex; gap: 10px;">
          <button type="button" onclick="window.print()" style="display: inline-flex; align-items: center; gap: 8px; background: #0f172a; color: #fff; padding: 8px 16px; border-radius: 8px; border: none; font-size: 13px; font-weight: 600; cursor: pointer;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
              <polyline points="6 9 6 2 18 2 18 9"></polyline>
              <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
              <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Cetak Struk
          </button>
        </div>
      </div>

      {{-- Paper Struk --}}
      <div class="receipt-paper">
        <div class="receipt-header">
          <div>
            <div class="receipt-brand-name">MAYBEADS.</div>
            <div style="font-size: 12.5px; color: #64748b;">Aksesoris Manik & Handmade Jewelry</div>
            <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Bukti Resmi Transaksi Pembayaran</div>
          </div>
          <div style="text-align: right;">
            <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Nomor Pembayaran</div>
            <div style="font-size: 20px; font-weight: 800; color: #1e293b;">#PAY-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div style="margin-top: 6px;">
              @php
                $st = strtolower($payment->payment_status);
                $badgeBg = in_array($st, ['sukses', 'paid', 'lunas', 'berhasil']) ? '#dcfce7' : (in_array($st, ['pending', 'proses']) ? '#fef9c3' : '#fee2e2');
                $badgeCol = in_array($st, ['sukses', 'paid', 'lunas', 'berhasil']) ? '#15803d' : (in_array($st, ['pending', 'proses']) ? '#a16207' : '#b91c1c');
              @endphp
              <span style="display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; background: {{ $badgeBg }}; color: {{ $badgeCol }}; text-transform: uppercase;">
                {{ $payment->payment_status }}
              </span>
            </div>
          </div>
        </div>

        <div class="receipt-meta-grid">
          <div>
            <div style="font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Data Pelanggan</div>
            <div style="font-size: 14px; font-weight: 700; color: #1e293b;">{{ $payment->order?->user?->name ?? 'Guest / Pelanggan Kasir' }}</div>
            <div style="font-size: 13px; color: #64748b; margin-top: 2px;">{{ $payment->order?->user?->email ?? '-' }}</div>
            <div style="font-size: 13px; color: #64748b; margin-top: 2px;">{{ $payment->order?->user?->phone ?? '-' }}</div>
          </div>
          <div>
            <div style="font-size: 11.5px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Rincian Transaksi</div>
            <div style="font-size: 13px; color: #475569;">
              <strong>ID Pesanan:</strong> 
              <a href="{{ route('admin.order.show', $payment->order_id) }}" style="color: #2563eb; font-weight: 600; text-decoration: none;">
                #ORD-{{ str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
              </a>
            </div>
            <div style="font-size: 13px; color: #475569; margin-top: 4px;">
              <strong>Waktu Bayar:</strong> {{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i:s') : ($payment->created_at ? $payment->created_at->format('d M Y, H:i:s') : '-') }}
            </div>
            <div style="font-size: 13px; color: #475569; margin-top: 4px;">
              <strong>Metode:</strong> {{ $payment->payment_method }}
            </div>
          </div>
        </div>

        {{-- Detail Order Item --}}
        <div style="margin-bottom: 24px;">
          <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.03em;">Item Pembelian</h4>
          @if($payment->order)
            <div class="receipt-row">
              <div>
                <strong>{{ $payment->order->product->product_name ?? 'Produk Maybeads' }}</strong>
                <div style="font-size: 12px; color: #64748b;">Jumlah: {{ $payment->order->quantity ?? 1 }} pcs</div>
              </div>
              <div style="font-weight: 700; color: #1e293b;">
                Rp {{ number_format($payment->order->total_price ?? $payment->amount, 0, ',', '.') }}
              </div>
            </div>
          @endif

          <div class="receipt-total-row">
            <span>TOTAL DIBAYAR</span>
            <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
          </div>
        </div>

        {{-- Bukti Pembayaran / Struk Foto --}}
        @if($payment->bukti_pembayaran)
          <div style="margin-top: 24px; padding-top: 20px; border-top: 1px dashed #cbd5e1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
              <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0;">Bukti Transfer / Struk yang Diunggah:</h4>
              <button type="button" onclick="copyProofUrl('{{ asset('storage/' . $payment->bukti_pembayaran) }}')" id="btnCopyProof" style="display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                <span id="copyProofText">Salin URL Foto</span>
              </button>
            </div>
            <div class="proof-img-box">
              <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" title="Klik untuk memperbesar">
                <img src="{{ asset('storage/' . $payment->bukti_pembayaran) }}" alt="Bukti Pembayaran">
              </a>
              <div style="font-size: 11.5px; color: #64748b; margin-top: 8px;">Klik gambar untuk melihat resolusi penuh</div>
            </div>
          </div>
        @endif

        {{-- Status Update Actions di Footer Struk --}}
        <div class="receipt-actions-footer" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
          <div style="font-size: 12.5px; color: #64748b;">
            Status Pembayaran Saat Ini: <strong>{{ ucfirst($payment->payment_status) }}</strong>
          </div>
          <div style="display: flex; gap: 8px;">
            <form method="POST" action="{{ route('admin.payment.update', $payment->id) }}">
              @csrf
              @method('PUT')
              @if(strtolower($payment->payment_status) !== 'sukses')
                <input type="hidden" name="payment_status" value="sukses">
                <button type="submit" style="background: #16a34a; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                  Tandai Sebagai Lunas (Sukses)
                </button>
              @else
                <input type="hidden" name="payment_status" value="pending">
                <button type="submit" style="background: #ca8a04; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">
                  Ubah Kembali ke Pending
                </button>
              @endif
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
  function copyProofUrl(url) {
    const btnText = document.getElementById('copyProofText');
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(url).then(() => {
        if (btnText) {
          btnText.textContent = '✓ Tersalin!';
          setTimeout(() => { btnText.textContent = 'Salin URL Foto'; }, 2000);
        }
      }).catch(() => {
        prompt('Salin URL Foto Bukti:', url);
      });
    } else {
      prompt('Salin URL Foto Bukti:', url);
    }
  }
</script>

</body>
</html>
