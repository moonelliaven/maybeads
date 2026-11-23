<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Kasir Toko (POS Real-Time) — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .pos-layout {
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 20px;
      align-items: start;
    }
    @media (max-width: 1024px) {
      .pos-layout {
        grid-template-columns: 1fr;
      }
    }
    .pos-product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 14px;
      max-height: calc(100vh - 220px);
      overflow-y: auto;
      padding-right: 6px;
    }
    .pos-product-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      flex-direction: column;
    }
    .pos-product-card:hover {
      border-color: #2563eb;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37,99,235,0.08);
    }
    .pos-product-img {
      width: 100%;
      height: 120px;
      border-radius: 6px;
      object-fit: cover;
      background: #f8fafc;
      margin-bottom: 8px;
    }
    .pos-categories {
      display: flex;
      gap: 8px;
      overflow-x: auto;
      padding-bottom: 8px;
      margin-bottom: 14px;
    }
    .category-pill {
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 999px;
      padding: 6px 14px;
      font-size: 12.5px;
      font-weight: 600;
      color: #475569;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.15s ease;
    }
    .category-pill.active {
      background: #0f172a;
      border-color: #0f172a;
      color: #ffffff;
    }
    .pos-cart-panel {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 18px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.04);
      display: flex;
      flex-direction: column;
      max-height: calc(100vh - 120px);
    }
    .pos-cart-items {
      flex: 1;
      overflow-y: auto;
      max-height: 280px;
      margin: 12px 0;
      padding-right: 4px;
      border-top: 1px solid #f1f5f9;
      border-bottom: 1px solid #f1f5f9;
    }
    .pos-cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px dashed #f1f5f9;
    }
    .cost-estimate-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 12px;
      margin-bottom: 12px;
    }

    /* Modal Backdrop with Blur & Animation */
    .pos-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.65);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 16px;
      overflow-y: auto;
    }
    .pos-modal-box {
      background: #ffffff;
      border-radius: 18px;
      width: 100%;
      max-width: 480px;
      padding: 24px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      position: relative;
      animation: posModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    @keyframes posModalPop {
      0% {
        opacity: 0;
        transform: scale(0.92) translateY(12px);
      }
      100% {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    /* Calculator Styles */
    .calc-screen {
      background: #0f172a;
      color: #38bdf8;
      border-radius: 12px;
      padding: 16px;
      text-align: right;
      margin-bottom: 14px;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);
    }
    .calc-screen-label {
      font-size: 11.5px;
      color: #94a3b8;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 4px;
      display: block;
    }
    .calc-screen-val {
      font-size: 28px;
      font-weight: 800;
      letter-spacing: 0.02em;
      line-height: 1.1;
      word-break: break-all;
    }
    .calc-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 8px;
      margin-bottom: 14px;
    }
    .calc-btn {
      background: #f1f5f9;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 700;
      color: #1e293b;
      padding: 12px;
      cursor: pointer;
      transition: all 0.15s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      user-select: none;
    }
    .calc-btn:hover {
      background: #e2e8f0;
      border-color: #cbd5e1;
      transform: translateY(-1px);
    }
    .calc-btn:active {
      transform: translateY(1px);
      background: #cbd5e1;
    }
    .calc-btn.op {
      background: #e0e7ff;
      color: #4338ca;
      border-color: #c7d2fe;
    }
    .calc-btn.clear {
      background: #fee2e2;
      color: #dc2626;
      border-color: #fecaca;
    }

    .quick-chip {
      background: #f8fafc;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 5px 8px;
      font-size: 11.5px;
      font-weight: 700;
      color: #475569;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .quick-chip:hover {
      background: #2563eb;
      color: #fff;
      border-color: #2563eb;
    }

    .qris-frame-inner {
      background: #ffffff;
      border: 2px solid #ef4444;
      border-radius: 12px;
      padding: 12px;
      margin: 12px 0;
      text-align: center;
    }

    .btn-calc-launcher {
      width: 100%;
      background: #ffffff;
      color: #0f172a;
      border: 1.5px solid #cbd5e1;
      padding: 11px;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 8px;
      transition: all 0.15s ease;
    }
    .btn-calc-launcher:hover {
      border-color: #0f172a;
      background: #f8fafc;
      transform: translateY(-1px);
    }
  </style>
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'pos'])

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
        <span class="header-title">Admin Dashboard / Transaksi Kasir Toko</span>
      </div>

      <div class="header-right">
        <div style="display: flex; align-items: center; gap: 12px;">
          <a href="{{ route('admin.pos.transactions') }}" style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 1.5px solid #cbd5e1; color: #1e293b; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 15px; height: 15px; color: #2563eb;">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
              <polyline points="14 2 14 8 20 8"></polyline>
              <line x1="16" y1="13" x2="8" y2="13"></line>
              <line x1="16" y1="17" x2="8" y2="17"></line>
            </svg>
            <span>Riwayat Transaksi Kasir</span>
          </a>

          <a href="{{ route('admin.notifications') }}" class="notification-btn" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notification-badge">1</span>
          </a>
        </div>
      </div>
    </header>

    <main class="page-container" style="padding-bottom: 20px;">
      <div class="pos-layout">

        <!-- Left: Product Catalog -->
        <div class="pos-catalog">
          <!-- Filter & Search Toolbar -->
          <div style="display: flex; gap: 12px; margin-bottom: 14px;">
            <div class="search-input-wrap" style="flex: 1; background: #fff; border: 1.5px solid #cbd5e1; border-radius: 8px; display: flex; align-items: center; padding: 0 12px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color: #64748b;">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input type="text" id="posSearchInput" placeholder="Cari nama produk kasir..." oninput="filterPosProducts()" style="border: none; padding: 10px; width: 100%; outline: none; font-size: 13.5px;">
            </div>
          </div>

          <!-- Category Pills -->
          <div class="pos-categories" id="posCategoryPills">
            <button type="button" class="category-pill active" onclick="setCategoryFilter('', this)">Semua Kategori</button>
            @foreach($categories as $cat)
              <button type="button" class="category-pill" onclick="setCategoryFilter('{{ $cat->id }}', this)">{{ $cat->category_name }}</button>
            @endforeach
          </div>

          <!-- Product Grid -->
          <div class="pos-product-grid" id="posProductGrid">
            @foreach($products as $product)
              <div class="pos-product-card"
                   data-id="{{ $product['id'] }}"
                   data-name="{{ $product['name'] }}"
                   data-price="{{ $product['price'] }}"
                   data-stock="{{ $product['stock'] }}"
                   data-cat="{{ $product['category_id'] }}"
                   onclick="addToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $product['price'] }}, {{ $product['stock'] }})">
                <img src="{{ $product['image'] }}" class="pos-product-img" alt="{{ $product['name'] }}" onerror="this.src='/images/logo.svg'">
                <div style="font-weight: 700; font-size: 13.5px; color: #0f172a; margin-bottom: 2px; line-height: 1.3;">{{ $product['name'] }}</div>
                <div style="font-size: 11.5px; color: #64748b; margin-bottom: 8px;">{{ $product['category'] }}</div>
                <div style="margin-top: auto; display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-weight: 800; color: #2563eb; font-size: 13.5px;">Rp {{ number_format($product['price'], 0, ',', '.') }}</span>
                  <span style="font-size: 11.5px; padding: 2px 6px; border-radius: 4px; background: {{ $product['stock'] > 5 ? '#f0fdf4' : '#fef2f2' }}; color: {{ $product['stock'] > 5 ? '#166534' : '#991b1b' }}; font-weight: 600;">
                    Stok: {{ $product['stock'] }}
                  </span>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Right: Cart & Actions Panel -->
        <div class="pos-cart-panel">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div>
              <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">Produk Dipilih</h3>
              <span id="posItemCount" style="font-size: 12px; color: #64748b;">0 item dipilih</span>
            </div>
            <button type="button" onclick="clearCart()" style="background: none; border: none; color: #dc2626; font-size: 12px; font-weight: 600; cursor: pointer;">
              Kosongkan
            </button>
          </div>

          <!-- Customer Name Field -->
          <div style="margin-bottom: 10px;">
            <input type="text" id="posCustomerName" placeholder="Nama Pelanggan (Opsional)" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 12.5px; box-sizing: border-box;">
          </div>

          <!-- Cart Items Container -->
          <div class="pos-cart-items" id="posCartItems">
            <div style="text-align: center; color: #94a3b8; padding: 36px 10px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 44px; height: 44px; margin: 0 auto 8px; opacity: 0.4;">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
              </svg>
              <p style="margin: 0; font-size: 13px;">Klik produk di katalog untuk menambahkan ke keranjang kasir.</p>
            </div>
          </div>

          <!-- Ringkasan Total & Estimasi Harga -->
          <div class="cost-estimate-card">
            <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #64748b; margin-bottom: 5px;">
              <span>Total Produk:</span>
              <strong id="posTotalCountText" style="color: #0f172a;">0 produk</strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #64748b; margin-bottom: 6px;">
              <span>Total Harga:</span>
              <strong id="posSubtotalText" style="color: #0f172a;">Rp 0</strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; color: #0f172a; border-top: 1.5px solid #cbd5e1; padding-top: 8px;">
              <span>Estimasi Harga:</span>
              <span id="posTotalText" style="color: #2563eb;">Rp 0</span>
            </div>
          </div>

          <!-- Tombol Lanjut ke Pembayaran -->
          <button type="button" id="btnGoToPayment" onclick="openPaymentModal()"
                  style="width: 100%; background: #2563eb; color: #ffffff; border: none; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 4px rgba(37,99,235,0.2);">
            <span>Lanjut ke Pembayaran</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </button>

          <!-- Button Ketik Harga Manual (Kalkulator) -->
          <button type="button" class="btn-calc-launcher" onclick="openCalculatorModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: #0f172a;">
              <rect x="4" y="2" width="16" height="20" rx="2"></rect>
              <line x1="8" y1="6" x2="16" y2="6"></line>
              <line x1="16" y1="14" x2="16" y2="18"></line>
              <path d="M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M8 18h.01M12 18h.01"></path>
            </svg>
            <span>Ketik Harga (Kalkulator)</span>
          </button>
        </div>

      </div>
    </main>
  </div>
</div>

<!-- ========================================== -->
<!-- 1. POP UP MODAL PEMBAYARAN KASIR (BLUR BG) -->
<!-- ========================================== -->
<div class="pos-modal-backdrop" id="posPaymentModal">
  <div class="pos-modal-box">
    <!-- Header Modal -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
      <div>
        <h3 style="margin: 0; font-size: 17px; font-weight: 800; color: #0f172a;">Pembayaran Kasir</h3>
        <span style="font-size: 12px; color: #64748b;">Selesaikan transaksi dan pilih metode pembayaran</span>
      </div>
      <button type="button" onclick="closePaymentModal()" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; line-height: 1;">&times;</button>
    </div>

    <!-- Total Tagihan Card -->
    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin-bottom: 16px;">
      <div style="display: flex; justify-content: space-between; align-items: baseline;">
        <span style="font-size: 12.5px; font-weight: 600; color: #64748b;">Total Tagihan:</span>
        <span id="modalPaymentItemCount" style="font-size: 12px; color: #64748b;">0 item</span>
      </div>
      <div id="modalPaymentTotalText" style="font-size: 26px; font-weight: 800; color: #2563eb; margin-top: 4px;">Rp 0</div>
    </div>

    <!-- Pilih Metode Pembayaran -->
    <div style="margin-bottom: 16px;">
      <label style="font-size: 11.5px; font-weight: 700; color: #475569; display: block; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em;">
        Pilih Metode Pembayaran
      </label>
      <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
        <button type="button" class="pay-method-btn active" id="btnMethodCash" onclick="selectPaymentModalMethod('cash')"
                style="padding: 10px 6px; border-radius: 10px; border: 1.5px solid #2563eb; background: #eff6ff; color: #2563eb; font-size: 12.5px; font-weight: 700; cursor: pointer; text-align: center;">
          Tunai (Cash)
        </button>
        <button type="button" class="pay-method-btn" id="btnMethodQris" onclick="selectPaymentModalMethod('qris')"
                style="padding: 10px 6px; border-radius: 10px; border: 1.5px solid #cbd5e1; background: #fff; color: #475569; font-size: 12.5px; font-weight: 700; cursor: pointer; text-align: center;">
          QRIS Instan
        </button>
        <button type="button" class="pay-method-btn" id="btnMethodDebit" onclick="selectPaymentModalMethod('debit')"
                style="padding: 10px 6px; border-radius: 10px; border: 1.5px solid #cbd5e1; background: #fff; color: #475569; font-size: 12.5px; font-weight: 700; cursor: pointer; text-align: center;">
          Debit / EDC
        </button>
      </div>
    </div>

    <!-- Detail Tunai (Cash) -->
    <div id="modalCashSection" style="margin-bottom: 16px;">
      <label style="font-size: 12px; font-weight: 700; color: #334155; display: block; margin-bottom: 6px;">Uang Tunai Diterima (Rp)</label>
      <input type="number" id="modalCashReceived" placeholder="0" min="0" oninput="calculateModalChange()"
             style="width: 100%; padding: 10px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 16px; font-weight: 700; box-sizing: border-box; outline: none;">
      
      <!-- Quick Cash Chips -->
      <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px;">
        <button type="button" class="quick-chip" onclick="setQuickCash('pas')">Uang Pas</button>
        <button type="button" class="quick-chip" onclick="setQuickCash(20000)">Rp 20.000</button>
        <button type="button" class="quick-chip" onclick="setQuickCash(50000)">Rp 50.000</button>
        <button type="button" class="quick-chip" onclick="setQuickCash(100000)">Rp 100.000</button>
        <button type="button" class="quick-chip" onclick="setQuickCash(200000)">Rp 200.000</button>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; padding: 10px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
        <span style="color: #166534; font-size: 13px; font-weight: 600;">Kembalian:</span>
        <strong id="modalCashChangeText" style="color: #15803d; font-size: 16px; font-weight: 800;">Rp 0</strong>
      </div>
    </div>

    <!-- Detail QRIS -->
    <div id="modalQrisSection" style="display: none; margin-bottom: 16px;">
      <div class="qris-frame-inner">
        <div style="font-size: 11.5px; font-weight: 800; color: #ef4444; margin-bottom: 6px; letter-spacing: 0.05em;">QRIS MAYBEADS STORE</div>
        <svg viewBox="0 0 200 200" style="width: 160px; height: 160px; margin: 0 auto; display: block;" xmlns="http://www.w3.org/2000/svg">
          <rect width="200" height="200" fill="#ffffff"/>
          <rect x="20" y="20" width="50" height="50" fill="#000000"/>
          <rect x="28" y="28" width="34" height="34" fill="#ffffff"/>
          <rect x="36" y="36" width="18" height="18" fill="#000000"/>
          <rect x="130" y="20" width="50" height="50" fill="#000000"/>
          <rect x="138" y="28" width="34" height="34" fill="#ffffff"/>
          <rect x="146" y="36" width="18" height="18" fill="#000000"/>
          <rect x="20" y="130" width="50" height="50" fill="#000000"/>
          <rect x="28" y="138" width="34" height="34" fill="#ffffff"/>
          <rect x="36" y="146" width="18" height="18" fill="#000000"/>
          <rect x="80" y="25" width="10" height="10" fill="#000"/>
          <rect x="100" y="25" width="15" height="10" fill="#000"/>
          <rect x="85" y="45" width="25" height="10" fill="#000"/>
          <rect x="25" y="85" width="15" height="15" fill="#000"/>
          <rect x="50" y="80" width="20" height="10" fill="#000"/>
          <rect x="80" y="75" width="40" height="40" fill="#000"/>
          <rect x="90" y="85" width="20" height="20" fill="#fff"/>
          <rect x="130" y="85" width="20" height="10" fill="#000"/>
          <rect x="160" y="80" width="15" height="25" fill="#000"/>
          <rect x="80" y="130" width="15" height="35" fill="#000"/>
          <rect x="105" y="145" width="25" height="20" fill="#000"/>
          <rect x="140" y="130" width="35" height="15" fill="#000"/>
          <rect x="155" y="155" width="20" height="25" fill="#000"/>
        </svg>
        <div style="font-size: 11px; color: #64748b; margin-top: 4px;">NMID: ID1029384756192 — Scan via BCA / GoPay / OVO / DANA</div>
      </div>
    </div>

    <!-- Detail Debit / EDC -->
    <div id="modalDebitSection" style="display: none; margin-bottom: 16px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 10px; padding: 14px; text-align: center;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width: 32px; height: 32px; color: #2563eb; margin: 0 auto 6px;">
        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
        <line x1="2" y1="10" x2="22" y2="10"></line>
      </svg>
      <div style="font-size: 13px; font-weight: 700; color: #1e293b;">Mesin EDC Kasir</div>
      <p style="font-size: 12px; color: #64748b; margin: 4px 0 0;">Silakan gesek atau tap kartu pelanggan pada mesin EDC.</p>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 10px; margin-top: 20px;">
      <button type="button" onclick="closePaymentModal()"
              style="flex: 1; background: #f1f5f9; border: 1.5px solid #cbd5e1; color: #475569; padding: 12px; border-radius: 8px; font-size: 13.5px; font-weight: 700; cursor: pointer;">
        Batal
      </button>
      <button type="button" id="btnSubmitPayment" onclick="processCartCheckout()"
              style="flex: 2; background: #16a34a; color: #ffffff; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(22,163,74,0.25);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>Sudah Bayar</span>
      </button>
    </div>
  </div>
</div>

<!-- ============================================== -->
<!-- 2. POP UP MODAL KALKULATOR "KETIK HARGA" (BLUR) -->
<!-- ============================================== -->
<div class="pos-modal-backdrop" id="posCalculatorModal">
  <div class="pos-modal-box" style="max-width: 440px;">

    <!-- TAHAP 1: INPUT NOMINAL & PILIH METODE -->
    <div id="calcStepInput">
      <!-- Header Modal -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
        <div>
          <h3 style="margin: 0; font-size: 17px; font-weight: 800; color: #0f172a;">Ketik Harga (Kalkulator)</h3>
          <span style="font-size: 12px; color: #64748b;">Input nominal transaksi langsung tanpa memilih item katalog</span>
        </div>
        <button type="button" onclick="closeCalculatorModal()" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; line-height: 1;">&times;</button>
      </div>

      <!-- Digital Display -->
      <div class="calc-screen">
        <span class="calc-screen-label">Nominal Transaksi (Rp)</span>
        <div class="calc-screen-val" id="calcDisplayVal">Rp 0</div>
      </div>

      <!-- Catatan Tambahan (Opsional) -->
      <div style="margin-bottom: 12px;">
        <input type="text" id="calcItemName" placeholder="Keterangan transaksi (cth: Jasa Custom, Aksesoris Unik)"
               style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 12.5px; box-sizing: border-box; outline: none;">
      </div>

      <!-- Quick Add Pills -->
      <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 12px;">
        <button type="button" class="quick-chip" onclick="calcAddAmount(5000)">+5.000</button>
        <button type="button" class="quick-chip" onclick="calcAddAmount(10000)">+10.000</button>
        <button type="button" class="quick-chip" onclick="calcAddAmount(20000)">+20.000</button>
        <button type="button" class="quick-chip" onclick="calcAddAmount(50000)">+50.000</button>
        <button type="button" class="quick-chip" onclick="calcAddAmount(100000)">+100.000</button>
      </div>

      <!-- Calculator Keypad Grid -->
      <div class="calc-grid">
        <button type="button" class="calc-btn" onclick="calcPressNum('7')">7</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('8')">8</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('9')">9</button>
        <button type="button" class="calc-btn clear" onclick="calcClear()">C</button>

        <button type="button" class="calc-btn" onclick="calcPressNum('4')">4</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('5')">5</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('6')">6</button>
        <button type="button" class="calc-btn op" onclick="calcBackspace()">⌫</button>

        <button type="button" class="calc-btn" onclick="calcPressNum('1')">1</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('2')">2</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('3')">3</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('000')">000</button>

        <button type="button" class="calc-btn" onclick="calcPressNum('0')" style="grid-column: span 2;">0</button>
        <button type="button" class="calc-btn" onclick="calcPressNum('00')" style="grid-column: span 2;">00</button>
      </div>

      <!-- Metode Pembayaran Kalkulator -->
      <div style="margin-bottom: 14px;">
        <label style="font-size: 11.5px; font-weight: 700; color: #475569; display: block; margin-bottom: 6px; text-transform: uppercase;">
          Metode Pembayaran
        </label>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
          <button type="button" class="calc-method-btn active" id="btnCalcMethodCash" onclick="selectCalcPaymentMethod('cash')"
                  style="padding: 8px 4px; border-radius: 8px; border: 1.5px solid #2563eb; background: #eff6ff; color: #2563eb; font-size: 12px; font-weight: 700; cursor: pointer; text-align: center;">
            Tunai
          </button>
          <button type="button" class="calc-method-btn" id="btnCalcMethodQris" onclick="selectCalcPaymentMethod('qris')"
                  style="padding: 8px 4px; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #fff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; text-align: center;">
            QRIS
          </button>
          <button type="button" class="calc-method-btn" id="btnCalcMethodDebit" onclick="selectCalcPaymentMethod('debit')"
                  style="padding: 8px 4px; border-radius: 8px; border: 1.5px solid #cbd5e1; background: #fff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; text-align: center;">
            Debit
          </button>
        </div>
      </div>

      <!-- Action Buttons (Lanjut ke Pembayaran) -->
      <div style="display: flex; gap: 8px; margin-top: 14px;">
        <button type="button" onclick="closeCalculatorModal()"
                style="flex: 1; background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 11px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">
          Batal
        </button>
        <button type="button" onclick="goToCalcPaymentStep()"
                style="flex: 2; background: #2563eb; color: #ffffff; border: none; padding: 11px; border-radius: 8px; font-size: 13.5px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
          <span>Lanjut ke Pembayaran &rarr;</span>
        </button>
      </div>
    </div>

    <!-- TAHAP 2: REVIEW PEMBAYARAN (QRIS AUTO-GENERATE / TUNAI / DEBIT) -->
    <div id="calcStepPayment" style="display: none;">
      <!-- Header Modal Step 2 -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <button type="button" onclick="backToCalcInput()" style="background: none; border: none; cursor: pointer; color: #64748b; padding: 4px;" title="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
          </button>
          <div>
            <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;" id="calcStep2Title">Konfirmasi Pembayaran</h3>
            <span style="font-size: 12px; color: #64748b;" id="calcStep2Subtitle">Periksa nominal dan metode pembayaran</span>
          </div>
        </div>
        <button type="button" onclick="closeCalculatorModal()" style="background: none; border: none; font-size: 24px; color: #64748b; cursor: pointer; line-height: 1;">&times;</button>
      </div>

      <!-- Ringkasan Tagihan -->
      <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 12.5px; font-weight: 600; color: #64748b;">Total Tagihan:</span>
        <strong id="calcPaymentDueText" style="font-size: 18px; font-weight: 800; color: #0f172a;">Rp 0</strong>
      </div>

      <!-- 1. Tampilan Auto Generate QRIS -->
      <div id="calcQrisView" style="display: none; text-align: center; margin-bottom: 16px;">
        <div class="qris-frame-inner" style="border: 2px dashed #93c5fd; background: #eff6ff; border-radius: 12px; padding: 16px;">
          <div style="font-size: 11px; font-weight: 800; color: #ef4444; margin-bottom: 8px; letter-spacing: 0.05em;">QRIS MAYBEADS STORE</div>
          <svg viewBox="0 0 200 200" style="width: 170px; height: 170px; margin: 0 auto; display: block; background: #fff; padding: 6px; border-radius: 8px; border: 1px solid #bfdbfe;" xmlns="http://www.w3.org/2000/svg">
            <rect width="200" height="200" fill="#ffffff"/>
            <rect x="20" y="20" width="50" height="50" fill="#000000"/>
            <rect x="28" y="28" width="34" height="34" fill="#ffffff"/>
            <rect x="36" y="36" width="18" height="18" fill="#000000"/>
            <rect x="130" y="20" width="50" height="50" fill="#000000"/>
            <rect x="138" y="28" width="34" height="34" fill="#ffffff"/>
            <rect x="146" y="36" width="18" height="18" fill="#000000"/>
            <rect x="20" y="130" width="50" height="50" fill="#000000"/>
            <rect x="28" y="138" width="34" height="34" fill="#ffffff"/>
            <rect x="36" y="146" width="18" height="18" fill="#000000"/>
            <rect x="80" y="25" width="10" height="10" fill="#000"/>
            <rect x="100" y="25" width="15" height="10" fill="#000"/>
            <rect x="85" y="45" width="25" height="10" fill="#000"/>
            <rect x="25" y="85" width="15" height="15" fill="#000"/>
            <rect x="50" y="80" width="20" height="10" fill="#000"/>
            <rect x="80" y="75" width="40" height="40" fill="#000"/>
            <rect x="90" y="85" width="20" height="20" fill="#fff"/>
            <rect x="130" y="85" width="20" height="10" fill="#000"/>
            <rect x="160" y="80" width="15" height="25" fill="#000"/>
            <rect x="80" y="130" width="15" height="35" fill="#000"/>
            <rect x="105" y="145" width="25" height="20" fill="#000"/>
            <rect x="140" y="130" width="35" height="15" fill="#000"/>
            <rect x="155" y="155" width="20" height="25" fill="#000"/>
          </svg>
          <div style="margin-top: 10px;">
            <div style="display: inline-flex; align-items: center; gap: 6px; background: #fff7ed; border: 1.5px solid #fed7aa; color: #c2410c; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">
              <span style="width: 7px; height: 7px; border-radius: 50%; background: #ea580c;"></span>
              Menunggu Pembayaran Pelanggan...
            </div>
          </div>
          <div style="font-size: 11.5px; color: #64748b; margin-top: 6px;">NMID: ID1029384756192 — Scan via BCA / GoPay / OVO / DANA</div>
        </div>
      </div>

      <!-- 2. Tampilan Pembayaran Tunai -->
      <div id="calcCashView" style="display: none; margin-bottom: 16px;">
        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 10px;">
          <div style="flex: 1;">
            <label style="font-size: 11.5px; font-weight: 700; color: #64748b; display: block; margin-bottom: 4px;">Uang Diterima (Rp):</label>
            <input type="number" id="calcCashReceivedStep2" placeholder="0" oninput="calculateCalcChangeStep2()"
                   style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 15px; font-weight: 700; box-sizing: border-box; outline: none;">
          </div>
          <div style="flex: 1; padding: 10px 14px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
            <div style="font-size: 11.5px; color: #166534; font-weight: 600;">Kembalian:</div>
            <strong id="calcChangeTextStep2" style="color: #15803d; font-size: 16px;">Rp 0</strong>
          </div>
        </div>
      </div>

      <!-- 3. Tampilan Pembayaran Debit -->
      <div id="calcDebitView" style="display: none; margin-bottom: 16px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 18px; text-align: center;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width: 36px; height: 36px; color: #2563eb; margin: 0 auto 6px;">
          <rect x="2" y="5" width="20" height="14" rx="2"></rect>
          <line x1="2" y1="10" x2="22" y2="10"></line>
        </svg>
        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;">Mesin EDC Kasir</div>
        <p style="font-size: 12px; color: #64748b; margin: 4px 0 0;">Silakan lakukan gesek atau tap kartu pelanggan pada mesin EDC.</p>
      </div>

      <!-- DUA TOMBOL: BATALKAN & SUDAH BAYAR -->
      <div style="display: flex; gap: 10px; margin-top: 16px;">
        <button type="button" onclick="backToCalcInput()"
                style="flex: 1; background: #f1f5f9; border: 1.5px solid #cbd5e1; color: #475569; padding: 12px; border-radius: 8px; font-size: 13.5px; font-weight: 700; cursor: pointer;">
          Batalkan
        </button>
        <button type="button" onclick="submitCalculatorOrder()"
                style="flex: 2; background: #16a34a; color: #ffffff; border: none; padding: 12px; border-radius: 8px; font-size: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(22,163,74,0.25);">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 17px; height: 17px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span>Sudah Bayar</span>
        </button>
      </div>
    </div>

  </div>
</div>

<script>
  let cart = [];
  let currentCategory = '';
  let activeModalMethod = 'cash';
  let activeCalcMethod = 'cash';
  let calcRawNumber = '0';

  // --- Cart Management ---
  function addToCart(id, name, price, stock) {
    if (stock <= 0) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Stok Habis',
          text: `Stok produk ${name} sedang habis!`,
          customClass: { popup: 'admin-swal-popup' }
        });
      } else {
        alert('Stok produk ' + name + ' sedang habis!');
      }
      return;
    }

    const existing = cart.find(i => i.id === id);
    if (existing) {
      if (existing.qty >= stock) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: 'Batas Stok',
            text: `Jumlah melebihi stok yang tersedia (${stock} pcs)`,
            customClass: { popup: 'admin-swal-popup' }
          });
        } else {
          alert('Jumlah melebihi stok (' + stock + ' pcs)');
        }
        return;
      }
      existing.qty++;
    } else {
      cart.push({ id, name, price, qty: 1, stock });
    }
    renderCart();
  }

  function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    item.qty += delta;
    if (item.qty > item.stock) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Batas Stok',
          text: `Jumlah melebihi stok (${item.stock} pcs)`,
          customClass: { popup: 'admin-swal-popup' }
        });
      }
      item.qty = item.stock;
    }
    if (item.qty <= 0) {
      cart = cart.filter(i => i.id !== id);
    }
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
  }

  function clearCart() {
    cart = [];
    renderCart();
  }

  function getSubtotal() {
    return cart.reduce((acc, cur) => acc + (cur.price * cur.qty), 0);
  }

  function getGrandTotal() {
    return getSubtotal();
  }

  function renderCart() {
    const container = document.getElementById('posCartItems');
    const itemCount = document.getElementById('posItemCount');
    const totalCountText = document.getElementById('posTotalCountText');
    const subtotalEl = document.getElementById('posSubtotalText');
    const totalEl = document.getElementById('posTotalText');

    if (cart.length === 0) {
      container.innerHTML = `
        <div style="text-align: center; color: #94a3b8; padding: 36px 10px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 44px; height: 44px; margin: 0 auto 8px; opacity: 0.4;">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
          <p style="margin: 0; font-size: 13px;">Klik produk di katalog untuk menambahkan ke keranjang kasir.</p>
        </div>
      `;
      if (itemCount) itemCount.innerText = '0 item dipilih';
      if (totalCountText) totalCountText.innerText = '0 produk';
      if (subtotalEl) subtotalEl.innerText = 'Rp 0';
      if (totalEl) totalEl.innerText = 'Rp 0';
      return;
    }

    let totalItems = 0;
    let html = '';

    cart.forEach(item => {
      const lineTotal = item.price * item.qty;
      totalItems += item.qty;

      html += `
        <div class="pos-cart-item">
          <div style="flex: 1; padding-right: 8px;">
            <div style="font-weight: 700; font-size: 13px; color: #0f172a;">${item.name}</div>
            <div style="font-size: 11.5px; color: #64748b;">Rp ${item.price.toLocaleString('id-ID')} × ${item.qty}</div>
          </div>
          <div style="display: flex; align-items: center; gap: 6px;">
            <button type="button" style="width: 24px; height: 24px; border: 1px solid #cbd5e1; background:#fff; border-radius:4px; font-weight:700; cursor:pointer;" onclick="changeQty(${item.id}, -1)">-</button>
            <span style="font-weight: 700; font-size: 12.5px; min-width: 16px; text-align: center;">${item.qty}</span>
            <button type="button" style="width: 24px; height: 24px; border: 1px solid #cbd5e1; background:#fff; border-radius:4px; font-weight:700; cursor:pointer;" onclick="changeQty(${item.id}, 1)">+</button>
            <strong style="font-size: 12.5px; min-width: 70px; text-align: right; color: #0f172a;">Rp ${lineTotal.toLocaleString('id-ID')}</strong>
            <button type="button" style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 0 4px;" onclick="removeFromCart(${item.id})">&times;</button>
          </div>
        </div>
      `;
    });

    container.innerHTML = html;
    if (itemCount) itemCount.innerText = totalItems + ' item dipilih';
    if (totalCountText) totalCountText.innerText = totalItems + ' produk';

    const grandTotal = getGrandTotal();
    if (subtotalEl) subtotalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    if (totalEl) totalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
  }

  // --- Filter Products ---
  function filterPosProducts() {
    const q = document.getElementById('posSearchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.pos-product-card');

    cards.forEach(card => {
      const name = card.getAttribute('data-name').toLowerCase();
      const cat = card.getAttribute('data-cat');
      const matchesSearch = name.includes(q);
      const matchesCat = !currentCategory || cat === currentCategory;
      card.style.display = (matchesSearch && matchesCat) ? 'flex' : 'none';
    });
  }

  function setCategoryFilter(catId, btn) {
    currentCategory = catId;
    document.querySelectorAll('#posCategoryPills .category-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterPosProducts();
  }

  // --- POP UP 1: Payment Modal Workflow ---
  function openPaymentModal() {
    if (cart.length === 0) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Keranjang Kosong',
          text: 'Pilih minimal satu produk sebelum lanjut ke pembayaran!',
          customClass: { popup: 'admin-swal-popup' }
        });
      } else {
        alert('Keranjang kasir masih kosong! Pilih minimal satu produk.');
      }
      return;
    }

    const total = getGrandTotal();
    const totalItems = cart.reduce((acc, c) => acc + c.qty, 0);

    document.getElementById('modalPaymentTotalText').innerText = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('modalPaymentItemCount').innerText = totalItems + ' item produk';

    // Reset cash input
    const cashInput = document.getElementById('modalCashReceived');
    if (cashInput) cashInput.value = '';
    calculateModalChange();

    selectPaymentModalMethod('cash');
    document.getElementById('posPaymentModal').style.display = 'flex';
  }

  function closePaymentModal() {
    document.getElementById('posPaymentModal').style.display = 'none';
  }

  function selectPaymentModalMethod(method) {
    activeModalMethod = method;
    ['btnMethodCash', 'btnMethodQris', 'btnMethodDebit'].forEach(id => {
      const btn = document.getElementById(id);
      if (btn) {
        btn.style.borderColor = '#cbd5e1';
        btn.style.background = '#ffffff';
        btn.style.color = '#475569';
      }
    });

    const activeBtn = method === 'cash' ? document.getElementById('btnMethodCash') :
                     (method === 'qris' ? document.getElementById('btnMethodQris') : document.getElementById('btnMethodDebit'));
    if (activeBtn) {
      activeBtn.style.borderColor = '#2563eb';
      activeBtn.style.background = '#eff6ff';
      activeBtn.style.color = '#2563eb';
    }

    document.getElementById('modalCashSection').style.display = method === 'cash' ? 'block' : 'none';
    document.getElementById('modalQrisSection').style.display = method === 'qris' ? 'block' : 'none';
    document.getElementById('modalDebitSection').style.display = method === 'debit' ? 'block' : 'none';
  }

  function setQuickCash(val) {
    const total = getGrandTotal();
    const cashInput = document.getElementById('modalCashReceived');
    if (val === 'pas') {
      cashInput.value = total;
    } else {
      cashInput.value = val;
    }
    calculateModalChange();
  }

  function calculateModalChange() {
    const total = getGrandTotal();
    const cash = parseFloat(document.getElementById('modalCashReceived').value || 0);
    const change = Math.max(0, cash - total);
    document.getElementById('modalCashChangeText').innerText = 'Rp ' + change.toLocaleString('id-ID');
  }

  function processCartCheckout() {
    const grandTotal = getGrandTotal();
    const cash = parseFloat(document.getElementById('modalCashReceived').value || 0);

    if (activeModalMethod === 'cash' && cash < grandTotal) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Nominal Kurang',
          text: 'Uang tunai yang diterima kurang dari total tagihan (Rp ' + grandTotal.toLocaleString('id-ID') + ')',
          customClass: { popup: 'admin-swal-popup' }
        });
      } else {
        alert('Uang tunai yang diterima kurang dari total tagihan (Rp ' + grandTotal.toLocaleString('id-ID') + ')');
      }
      return;
    }

    const payload = {
      customer_name: document.getElementById('posCustomerName').value || 'Pelanggan Toko',
      items: cart.map(i => ({ id: i.id, qty: i.qty })),
      payment_method: activeModalMethod,
      discount_amount: 0,
      tax_amount: 0,
      cash_paid: activeModalMethod === 'cash' ? cash : grandTotal,
    };

    const submitBtn = document.getElementById('btnSubmitPayment');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span>Memproses...</span>`;

    fetch('{{ route('admin.pos.checkout') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Sudah Bayar</span>`;

      if (data.success && data.transaction) {
        closePaymentModal();
        clearCart();

        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Pembayaran Sudah Berhasil!',
            html: `
              <div style="font-size: 13.5px; color: #475569; margin-top: 8px;">
                Kode Transaksi: <strong>${data.transaction.transaction_code}</strong><br>
                Total: <strong style="color: #16a34a;">Rp ${parseFloat(data.transaction.total_amount).toLocaleString('id-ID')}</strong><br>
                Kembalian: <strong>Rp ${parseFloat(data.transaction.change_amount || 0).toLocaleString('id-ID')}</strong>
              </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Cetak Struk Transaksi',
            cancelButtonText: 'Transaksi Baru',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            customClass: { popup: 'admin-swal-popup' }
          }).then((result) => {
            if (result.isConfirmed) {
              window.open('/admin/pos/transaction/' + data.transaction.id + '?auto_print=1', '_blank');
            }
          });
        } else {
          alert('Pembayaran sudah berhasil! Transaksi #' + data.transaction.transaction_code);
          window.location.reload();
        }
      } else {
        alert(data.message || 'Terjadi kesalahan saat memproses transaksi.');
      }
    })
    .catch(err => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Sudah Bayar</span>`;
      alert('Gagal mengirim transaksi ke server. Periksa jaringan Anda.');
    });
  }

  // --- POP UP 2: Calculator "Ketik Harga" Workflow ---
  function openCalculatorModal() {
    calcRawNumber = '0';
    updateCalcDisplay();
    document.getElementById('calcItemName').value = '';
    selectCalcPaymentMethod('cash');
    backToCalcInput();
    document.getElementById('posCalculatorModal').style.display = 'flex';
  }

  function closeCalculatorModal() {
    document.getElementById('posCalculatorModal').style.display = 'none';
    backToCalcInput();
  }

  function backToCalcInput() {
    const s1 = document.getElementById('calcStepInput');
    const s2 = document.getElementById('calcStepPayment');
    if (s1) s1.style.display = 'block';
    if (s2) s2.style.display = 'none';
  }

  function goToCalcPaymentStep() {
    const total = parseInt(calcRawNumber, 10) || 0;
    if (total <= 0) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Nominal Kosong',
          text: 'Silakan ketik harga nominal transaksi di kalkulator terlebih dahulu!',
          customClass: { popup: 'admin-swal-popup' }
        });
      } else {
        alert('Silakan ketik harga nominal transaksi terlebih dahulu!');
      }
      return;
    }

    // Pindah ke step 2
    document.getElementById('calcStepInput').style.display = 'none';
    document.getElementById('calcStepPayment').style.display = 'block';

    // Update info tagihan
    document.getElementById('calcPaymentDueText').innerText = 'Rp ' + total.toLocaleString('id-ID');

    // Tampilkan view sesuai metode yang dipilih
    const qrisView = document.getElementById('calcQrisView');
    const cashView = document.getElementById('calcCashView');
    const debitView = document.getElementById('calcDebitView');
    const step2Title = document.getElementById('calcStep2Title');
    const step2Subtitle = document.getElementById('calcStep2Subtitle');

    if (activeCalcMethod === 'qris') {
      qrisView.style.display = 'block';
      cashView.style.display = 'none';
      debitView.style.display = 'none';
      step2Title.innerText = 'Pembayaran QRIS';
      step2Subtitle.innerText = 'Scan QRIS untuk menyelesaikan transaksi';
    } else if (activeCalcMethod === 'cash') {
      qrisView.style.display = 'none';
      cashView.style.display = 'block';
      debitView.style.display = 'none';
      step2Title.innerText = 'Pembayaran Tunai';
      step2Subtitle.innerText = 'Masukkan nominal uang tunai yang diterima';
      const cashInput2 = document.getElementById('calcCashReceivedStep2');
      if (cashInput2) {
        cashInput2.value = total;
        calculateCalcChangeStep2();
      }
    } else {
      qrisView.style.display = 'none';
      cashView.style.display = 'none';
      debitView.style.display = 'block';
      step2Title.innerText = 'Pembayaran Debit / EDC';
      step2Subtitle.innerText = 'Lakukan transaksi melalui mesin EDC';
    }
  }

  function calculateCalcChangeStep2() {
    const total = parseInt(calcRawNumber, 10) || 0;
    const cash = parseFloat(document.getElementById('calcCashReceivedStep2')?.value || 0);
    const change = Math.max(0, cash - total);
    const changeEl = document.getElementById('calcChangeTextStep2');
    if (changeEl) changeEl.innerText = 'Rp ' + change.toLocaleString('id-ID');
  }

  function updateCalcDisplay() {
    const num = parseInt(calcRawNumber, 10) || 0;
    document.getElementById('calcDisplayVal').innerText = 'Rp ' + num.toLocaleString('id-ID');
  }

  function calcPressNum(val) {
    if (calcRawNumber === '0') {
      calcRawNumber = val === '000' || val === '00' ? '0' : val;
    } else {
      if (calcRawNumber.length < 9) {
        calcRawNumber += val;
      }
    }
    updateCalcDisplay();
  }

  function calcClear() {
    calcRawNumber = '0';
    updateCalcDisplay();
  }

  function calcBackspace() {
    if (calcRawNumber.length <= 1) {
      calcRawNumber = '0';
    } else {
      calcRawNumber = calcRawNumber.slice(0, -1);
    }
    updateCalcDisplay();
  }

  function calcAddAmount(val) {
    const current = parseInt(calcRawNumber, 10) || 0;
    calcRawNumber = (current + val).toString();
    updateCalcDisplay();
  }

  function selectCalcPaymentMethod(method) {
    activeCalcMethod = method;
    ['btnCalcMethodCash', 'btnCalcMethodQris', 'btnCalcMethodDebit'].forEach(id => {
      const btn = document.getElementById(id);
      if (btn) {
        btn.style.borderColor = '#cbd5e1';
        btn.style.background = '#ffffff';
        btn.style.color = '#475569';
      }
    });

    const activeBtn = method === 'cash' ? document.getElementById('btnCalcMethodCash') :
                     (method === 'qris' ? document.getElementById('btnCalcMethodQris') : document.getElementById('btnCalcMethodDebit'));
    if (activeBtn) {
      activeBtn.style.borderColor = '#2563eb';
      activeBtn.style.background = '#eff6ff';
      activeBtn.style.color = '#2563eb';
    }
  }

  function submitCalculatorOrder() {
    const total = parseInt(calcRawNumber, 10) || 0;
    if (total <= 0) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Nominal Kosong',
          text: 'Silakan ketik harga nominal transaksi di kalkulator terlebih dahulu!',
          customClass: { popup: 'admin-swal-popup' }
        });
      } else {
        alert('Silakan ketik harga nominal transaksi terlebih dahulu!');
      }
      return;
    }

    const cash = activeCalcMethod === 'cash'
      ? parseFloat(document.getElementById('calcCashReceivedStep2')?.value || 0)
      : total;

    if (activeCalcMethod === 'cash' && cash < total) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Uang Kurang',
          text: 'Uang tunai diterima kurang dari nominal tagihan (Rp ' + total.toLocaleString('id-ID') + ')',
          customClass: { popup: 'admin-swal-popup' }
        });
      } else {
        alert('Uang tunai diterima kurang dari nominal tagihan (Rp ' + total.toLocaleString('id-ID') + ')');
      }
      return;
    }

    const customName = document.getElementById('calcItemName').value.trim() || 'Penjualan Manual Kasir';

    const payload = {
      customer_name: 'Pelanggan Kasir (Manual)',
      items: [
        {
          id: null,
          name: customName,
          price: total,
          qty: 1
        }
      ],
      payment_method: activeCalcMethod,
      discount_amount: 0,
      tax_amount: 0,
      cash_paid: activeCalcMethod === 'cash' ? cash : total,
      notes: 'Transaksi kalkulator: ' + customName
    };

    fetch('{{ route('admin.pos.checkout') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.transaction) {
        closeCalculatorModal();

        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Pembayaran Sudah Berhasil!',
            html: `
              <div style="font-size: 13.5px; color: #475569; margin-top: 8px;">
                Kode Transaksi: <strong>${data.transaction.transaction_code}</strong><br>
                Nominal: <strong style="color: #16a34a;">Rp ${parseFloat(data.transaction.total_amount).toLocaleString('id-ID')}</strong><br>
                Kembalian: <strong>Rp ${parseFloat(data.transaction.change_amount || 0).toLocaleString('id-ID')}</strong>
              </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Cetak Struk Transaksi',
            cancelButtonText: 'Transaksi Baru',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            customClass: { popup: 'admin-swal-popup' }
          }).then((result) => {
            if (result.isConfirmed) {
              window.open('/admin/pos/transaction/' + data.transaction.id + '?auto_print=1', '_blank');
            }
          });
        } else {
          alert('Pembayaran sudah berhasil!');
          window.location.reload();
        }
      } else {
        alert(data.message || 'Terjadi kesalahan saat memproses transaksi.');
      }
    })
    .catch(err => {
      alert('Gagal mengirim transaksi ke server. Periksa koneksi Anda.');
    });
  }
</script>

</body>
</html>
