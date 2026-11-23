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

  <style>
    .pos-layout {
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 20px;
      align-items: start;
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
    .qris-modal-box {
      max-width: 360px;
      background: #ffffff;
      border-radius: 16px;
      padding: 24px;
      text-align: center;
      margin: auto;
      box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
    }
    .qris-frame {
      background: #ffffff;
      border: 2px solid #ef4444;
      border-radius: 12px;
      padding: 16px;
      margin: 16px 0;
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
              <input type="text" id="posSearchInput" placeholder="Cari nama atau barcode produk..." oninput="filterPosProducts()" style="border: none; padding: 10px; width: 100%; outline: none; font-size: 13.5px;">
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

        <!-- Right: Checkout Cart Panel with Real-Time Cost Estimations & Payment Options -->
        <div class="pos-cart-panel">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <div>
              <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">Order Kasir Real-Time</h3>
              <span id="posItemCount" style="font-size: 12px; color: #64748b;">0 item dipilih</span>
            </div>
            <button type="button" onclick="clearCart()" style="background: none; border: none; color: #dc2626; font-size: 12px; font-weight: 600; cursor: pointer;">
              Kosongkan Keranjang
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
              <p style="margin: 0; font-size: 13px;">Klik produk di katalog untuk menambahkan ke transaksi kasir.</p>
            </div>
          </div>

          <!-- Estimasi Biaya Langsung (Diskon, Biaya Kemasan/Pajak) -->
          <div class="cost-estimate-card">
            <div style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px; text-transform: uppercase;">Estimasi Biaya Langsung:</div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px;">
              <div>
                <label style="font-size: 11px; color: #64748b; display: block; margin-bottom: 2px;">Potongan Diskon (Rp)</label>
                <input type="number" id="posDiscount" placeholder="0" min="0" value="0" oninput="updateEstimations()"
                       style="width: 100%; padding: 6px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 12.5px; box-sizing: border-box;">
              </div>
              <div>
                <label style="font-size: 11px; color: #64748b; display: block; margin-bottom: 2px;">Biaya Tambahan / PPN (Rp)</label>
                <input type="number" id="posTax" placeholder="0" min="0" value="0" oninput="updateEstimations()"
                       style="width: 100%; padding: 6px 8px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 12.5px; box-sizing: border-box;">
              </div>
            </div>

            <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #64748b; margin-bottom: 4px;">
              <span>Subtotal Produk:</span>
              <strong id="posSubtotalText" style="color: #0f172a;">Rp 0</strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #16a34a; margin-bottom: 4px;">
              <span>Diskon:</span>
              <strong id="posDiscountText">- Rp 0</strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #475569; margin-bottom: 6px;">
              <span>Biaya Tambahan:</span>
              <strong id="posTaxText">+ Rp 0</strong>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; color: #0f172a; border-top: 1.5px solid #cbd5e1; padding-top: 6px;">
              <span>Total Estimasi:</span>
              <span id="posTotalText" style="color: #2563eb;">Rp 0</span>
            </div>
          </div>

          <!-- Menu Pembayaran & Quick QRIS Trigger -->
          <div style="margin-bottom: 12px;">
            <label style="font-size: 11.5px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px; text-transform: uppercase;">
              Pilihan Metode Pembayaran
            </label>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
              <button type="button" class="payment-opt-btn active" data-method="cash" onclick="setPaymentMethod('cash', this)"
                      style="padding: 8px 4px; border-radius: 6px; border: 1.5px solid #2563eb; background: #eff6ff; color: #2563eb; font-size: 12px; font-weight: 700; cursor: pointer; text-align: center;">
                Tunai (Cash)
              </button>
              <button type="button" class="payment-opt-btn" data-method="qris" onclick="setPaymentMethod('qris', this)"
                      style="padding: 8px 4px; border-radius: 6px; border: 1.5px solid #cbd5e1; background: #fff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; text-align: center;">
                QRIS Instan
              </button>
              <button type="button" class="payment-opt-btn" data-method="debit" onclick="setPaymentMethod('debit', this)"
                      style="padding: 8px 4px; border-radius: 6px; border: 1.5px solid #cbd5e1; background: #fff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; text-align: center;">
                Debit / EDC
              </button>
            </div>
          </div>

          <!-- Cash Section (live change) -->
          <div id="cashInputSection" style="margin-bottom: 12px;">
            <label style="font-size: 11.5px; font-weight: 700; color: #475569; display: block; margin-bottom: 4px;">Uang Tunai Diterima (Rp)</label>
            <input type="number" id="posCashReceived" placeholder="0" min="0" oninput="calculateChange()"
                   style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1.5px solid #cbd5e1; font-size: 13.5px; font-weight: 700; box-sizing: border-box;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px; font-size: 13px;">
              <span style="color: #64748b;">Kembalian Kasir:</span>
              <strong id="posChangeText" style="color: #16a34a; font-size: 15px;">Rp 0</strong>
            </div>
          </div>

          <!-- Button Tampilkan QRIS Pelanggan -->
          <button type="button" id="btnShowCustomerQris" onclick="openQrisModal()"
                  style="display: none; width: 100%; background: #dc2626; color: #fff; border: none; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; margin-bottom: 10px; align-items: center; justify-content: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
              <rect x="3" y="3" width="7" height="7"></rect>
              <rect x="14" y="3" width="7" height="7"></rect>
              <rect x="14" y="14" width="7" height="7"></rect>
              <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            Tampilkan QRIS ke Layar Pelanggan
          </button>

          <!-- Submit Order Realtime Button -->
          <button type="button" onclick="submitCashierOrder()"
                  style="width: 100%; background: #0f172a; color: #fff; border: none; padding: 13px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Proses &amp; Simpan Transaksi Kasir
          </button>
        </div>

      </div>
    </main>
  </div>
</div>

{{-- MODAL DISPLAY QRIS UNTUK PELANGGAN --}}
<div class="modal-backdrop" id="customerQrisModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
  <div class="qris-modal-box">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
      <span style="font-size: 14px; font-weight: 800; color: #0f172a;">QRIS MAYBEADS STORE</span>
      <button type="button" onclick="closeQrisModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
    </div>
    <p style="font-size: 12px; color: #64748b; margin: 0;">Scan menggunakan BCA, GoPay, OVO, DANA, ShopeePay, atau m-Banking</p>

    <div class="qris-frame">
      <div style="font-size: 12px; font-weight: 800; color: #ef4444; margin-bottom: 8px; letter-spacing: 0.05em;">QRIS STANDAR PEMBAYARAN NASIONAL</div>
      {{-- SVG QRIS Realistis --}}
      <svg viewBox="0 0 200 200" style="width: 180px; height: 180px; margin: 0 auto; display: block;" xmlns="http://www.w3.org/2000/svg">
        <rect width="200" height="200" fill="#ffffff"/>
        <!-- Outer Box 1 -->
        <rect x="20" y="20" width="50" height="50" fill="#000000"/>
        <rect x="28" y="28" width="34" height="34" fill="#ffffff"/>
        <rect x="36" y="36" width="18" height="18" fill="#000000"/>
        <!-- Outer Box 2 -->
        <rect x="130" y="20" width="50" height="50" fill="#000000"/>
        <rect x="138" y="28" width="34" height="34" fill="#ffffff"/>
        <rect x="146" y="36" width="18" height="18" fill="#000000"/>
        <!-- Outer Box 3 -->
        <rect x="20" y="130" width="50" height="50" fill="#000000"/>
        <rect x="28" y="138" width="34" height="34" fill="#ffffff"/>
        <rect x="36" y="146" width="18" height="18" fill="#000000"/>
        <!-- Random pattern simulating real QR code -->
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
      <div style="font-size: 11px; color: #64748b; margin-top: 6px;">NMID: ID1029384756192</div>
    </div>

    <div style="background: #f8fafc; padding: 12px; border-radius: 8px; margin-bottom: 14px;">
      <div style="font-size: 11.5px; color: #64748b;">Total Tagihan Yang Harus Di-Scan:</div>
      <div id="qrisModalTotalText" style="font-size: 22px; font-weight: 800; color: #0f172a;">Rp 0</div>
    </div>

    <button type="button" onclick="closeQrisModal()" style="width: 100%; background: #0f172a; color: #fff; border: none; padding: 10px; border-radius: 8px; font-weight: 700; cursor: pointer;">
      Tutup QRIS &amp; Lanjutkan
    </button>
  </div>
</div>

<script>
  let cart = [];
  let currentCategory = '';
  let activePaymentMethod = 'cash';

  function addToCart(id, name, price, stock) {
    if (stock <= 0) {
      alert('Stok produk ' + name + ' sedang habis!');
      return;
    }

    const existing = cart.find(i => i.id === id);
    if (existing) {
      if (existing.qty >= stock) {
        alert('Jumlah melebihi stok yang tersedia (' + stock + ' pcs)');
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
      alert('Jumlah melebihi stok (' + item.stock + ' pcs)');
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
    const subtotal = getSubtotal();
    const discount = parseFloat(document.getElementById('posDiscount').value || 0);
    const tax = parseFloat(document.getElementById('posTax').value || 0);
    return Math.max(0, subtotal - discount + tax);
  }

  function updateEstimations() {
    const subtotal = getSubtotal();
    const discount = parseFloat(document.getElementById('posDiscount').value || 0);
    const tax = parseFloat(document.getElementById('posTax').value || 0);
    const grandTotal = Math.max(0, subtotal - discount + tax);

    document.getElementById('posSubtotalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('posDiscountText').innerText = '- Rp ' + discount.toLocaleString('id-ID');
    document.getElementById('posTaxText').innerText = '+ Rp ' + tax.toLocaleString('id-ID');
    document.getElementById('posTotalText').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    document.getElementById('qrisModalTotalText').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

    calculateChange();
  }

  function renderCart() {
    const container = document.getElementById('posCartItems');
    const itemCount = document.getElementById('posItemCount');

    if (cart.length === 0) {
      container.innerHTML = `
        <div style="text-align: center; color: #94a3b8; padding: 36px 10px;">
          <p style="margin: 0; font-size: 13px;">Klik produk di katalog untuk menambahkan ke transaksi kasir.</p>
        </div>
      `;
      itemCount.innerText = '0 item dipilih';
      updateEstimations();
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
    itemCount.innerText = totalItems + ' item dipilih';
    updateEstimations();
  }

  function setPaymentMethod(method, btn) {
    activePaymentMethod = method;
    document.querySelectorAll('.payment-opt-btn').forEach(b => {
      b.style.borderColor = '#cbd5e1';
      b.style.background = '#ffffff';
      b.style.color = '#475569';
    });
    btn.style.borderColor = '#2563eb';
    btn.style.background = '#eff6ff';
    btn.style.color = '#2563eb';

    const cashSection = document.getElementById('cashInputSection');
    const qrisBtn = document.getElementById('btnShowCustomerQris');

    if (method === 'cash') {
      cashSection.style.display = 'block';
      qrisBtn.style.display = 'none';
    } else if (method === 'qris') {
      cashSection.style.display = 'none';
      qrisBtn.style.display = 'flex';
      openQrisModal();
    } else {
      cashSection.style.display = 'none';
      qrisBtn.style.display = 'none';
    }
  }

  function calculateChange() {
    const grandTotal = getGrandTotal();
    const cash = parseFloat(document.getElementById('posCashReceived').value || 0);
    const change = Math.max(0, cash - grandTotal);
    document.getElementById('posChangeText').innerText = 'Rp ' + change.toLocaleString('id-ID');
  }

  function openQrisModal() {
    document.getElementById('qrisModalTotalText').innerText = 'Rp ' + getGrandTotal().toLocaleString('id-ID');
    document.getElementById('customerQrisModal').style.display = 'flex';
  }

  function closeQrisModal() {
    document.getElementById('customerQrisModal').style.display = 'none';
  }

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

  function submitCashierOrder() {
    if (cart.length === 0) {
      alert('Keranjang kasir masih kosong! Pilih minimal satu produk.');
      return;
    }

    const grandTotal = getGrandTotal();
    const cash = parseFloat(document.getElementById('posCashReceived').value || 0);

    if (activePaymentMethod === 'cash' && cash < grandTotal) {
      alert('Uang tunai yang diterima kurang dari total tagihan (Rp ' + grandTotal.toLocaleString('id-ID') + ')');
      return;
    }

    const payload = {
      customer_name: document.getElementById('posCustomerName').value || 'Pelanggan Toko',
      items: cart.map(i => ({ id: i.id, qty: i.qty })),
      payment_method: activePaymentMethod,
      discount_amount: parseFloat(document.getElementById('posDiscount').value || 0),
      tax_amount: parseFloat(document.getElementById('posTax').value || 0),
      cash_paid: activePaymentMethod === 'cash' ? cash : grandTotal,
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
        // Arahkan langsung ke halaman struk kasir
        window.location.href = '/admin/pos/transaction/' + data.transaction.id;
      } else {
        alert(data.message || 'Terjadi kesalahan saat memproses transaksi.');
      }
    })
    .catch(err => {
      alert('Gagal mengirim transaksi ke server. Periksa jaringan Anda.');
    });
  }
</script>

</body>
</html>