<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Struk Kasir {{ $transaction->transaction_code }} — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css'])

  <style>
    .pos-receipt-page {
      max-width: 440px;
      margin: 30px auto;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 28px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .receipt-dashed {
      border-top: 1.5px dashed #cbd5e1;
      margin: 16px 0;
    }
    .pos-item-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      font-size: 13.5px;
    }
    @media print {
      body * {
        visibility: hidden;
      }
      .pos-receipt-page, .pos-receipt-page * {
        visibility: visible;
      }
      .pos-receipt-page {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 100%;
        border: none;
        box-shadow: none;
        padding: 0;
      }
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body style="background: #f1f5f9; padding: 20px;">

  <div class="pos-receipt-page">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;" class="no-print">
      <a href="{{ route('admin.pos.index') }}" style="font-size: 12.5px; font-weight: 600; color: #475569; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
        &larr; Kembali ke Kasir
      </a>
      <button type="button" onclick="window.print()" style="background: #0f172a; color: #fff; border: none; padding: 6px 14px; border-radius: 6px; font-size: 12.5px; font-weight: 600; cursor: pointer;">
        Cetak Struk
      </button>
    </div>

    <div style="text-align: center; margin-bottom: 18px;">
      <h2 style="font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 800; color: #0f172a; margin: 0;">MAYBEADS.</h2>
      <p style="font-size: 12px; color: #64748b; margin: 2px 0 0;">Store Resmi Aksesoris & Manik-Manik</p>
      <p style="font-size: 11px; color: #94a3b8; margin: 2px 0 0;">Transaksi Kasir Toko (In-Store POS)</p>
    </div>

    <div class="receipt-dashed"></div>

    <div style="font-size: 12.5px; color: #475569; line-height: 1.6;">
      <div style="display: flex; justify-content: space-between;">
        <span>No. Transaksi:</span>
        <strong>{{ $transaction->transaction_code }}</strong>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Waktu:</span>
        <span>{{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i') : '-' }}</span>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Kasir:</span>
        <span>{{ $transaction->cashier->name ?? 'Admin Kasir' }}</span>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Pelanggan:</span>
        <span>{{ $transaction->customer_name ?: 'Pelanggan Langsung' }}</span>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span>Metode:</span>
        <strong style="text-transform: uppercase;">{{ $transaction->payment_method }}</strong>
      </div>
    </div>

    <div class="receipt-dashed"></div>

    <div style="margin: 12px 0;">
      @foreach($transaction->items as $item)
        <div class="pos-item-row">
          <div>
            <div style="font-weight: 700; color: #1e293b;">{{ $item->product_name }}</div>
            <div style="font-size: 11.5px; color: #64748b;">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
          </div>
          <div style="font-weight: 700; color: #0f172a;">
            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
          </div>
        </div>
      @endforeach
    </div>

    <div class="receipt-dashed"></div>

    <div style="font-size: 13px; color: #475569; line-height: 1.8;">
      <div style="display: flex; justify-content: space-between;">
        <span>Subtotal:</span>
        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
      </div>
      @if($transaction->discount_amount > 0)
        <div style="display: flex; justify-content: space-between; color: #16a34a;">
          <span>Potongan Diskon:</span>
          <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
      @endif
      @if($transaction->tax_amount > 0)
        <div style="display: flex; justify-content: space-between;">
          <span>Biaya Tambahan / Pajak:</span>
          <span>+ Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
        </div>
      @endif
      <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #0f172a; margin-top: 4px; padding-top: 4px; border-top: 1px solid #e2e8f0;">
        <span>TOTAL:</span>
        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
      </div>
      @if(strtolower($transaction->payment_method) === 'cash' || strtolower($transaction->payment_method) === 'tunai')
        <div style="display: flex; justify-content: space-between; margin-top: 4px;">
          <span>Uang Diterima:</span>
          <span>Rp {{ number_format($transaction->cash_paid, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-weight: 700; color: #16a34a;">
          <span>Kembalian:</span>
          <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
      @endif
    </div>

    <div class="receipt-dashed"></div>

    <div style="text-align: center; color: #94a3b8; font-size: 11.5px; line-height: 1.5;">
      <p style="margin: 0; font-weight: 600; color: #64748b;">Terima kasih atas kunjungan Anda!</p>
      <p style="margin: 2px 0 0;">Barang yang sudah dibeli tidak dapat ditukar kecuali perjanjian sebelumnya.</p>
    </div>
  </div>

</body>
</html>
