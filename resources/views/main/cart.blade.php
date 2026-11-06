<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Data Lengkap Keranjang — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css'])

  <style>
    /* ==========================================================================
       CART DETAIL PAGE - Exact Match to Design Reference
       ========================================================================== */
    body.cart-detail-page {
      background-color: #f3f4f7;
      color: #18181b;
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .cart-main-container {
      max-width: 1240px;
      width: 100%;
      margin: 36px auto 140px;
      padding: 0 24px;
      flex: 1;
    }

    /* Top Bar on Header */
    .cart-top-bar {
      background-color: #1f2735;
      height: 40px;
      color: #cbd5e1;
      font-size: 12px;
      display: flex;
      align-items: center;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .cart-top-container {
      max-width: 1240px;
      width: 100%;
      margin: 0 auto;
      padding: 0 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .cart-top-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #cbd5e1;
      font-size: 12px;
      text-decoration: none;
    }
    .cart-top-link:hover { color: #ffffff; }

    /* Page Header: Breadcrumb + Title + Search */
    .cart-header-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 24px;
      gap: 20px;
      flex-wrap: wrap;
    }
    .cart-header-left {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .cart-back-breadcrumb {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 13px;
      font-weight: 500;
      color: #64748b;
      text-decoration: none;
      transition: color 0.15s;
    }
    .cart-back-breadcrumb:hover {
      color: #0f172a;
    }
    .cart-header-title {
      font-size: 22px;
      font-weight: 700;
      color: #141418;
      letter-spacing: -0.2px;
      margin: 0;
    }

    /* Search Bar Pill */
    .cart-search-box {
      position: relative;
      width: 290px;
      max-width: 100%;
    }
    .cart-search-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 16px;
      height: 16px;
      stroke: #94a3b8;
      stroke-width: 2;
      fill: none;
      pointer-events: none;
    }
    .cart-search-input {
      width: 100%;
      padding: 8px 16px 8px 38px;
      background: #ffffff;
      border: 1px solid #d1d5db;
      border-radius: 999px;
      font-family: inherit;
      font-size: 12.5px;
      color: #1e293b;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .cart-search-input:focus {
      border-color: #244173;
      box-shadow: 0 0 0 3px rgba(36, 65, 115, 0.12);
    }
    .cart-search-input::placeholder {
      color: #94a3b8;
    }

    /* Table Card */
    .cart-table-card {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
    }

    /* Table Grid Columns: Checkbox (36px), Produk (1fr), Total (150px), Harga Akhir (180px), Aksi (90px) */
    .cart-grid-thead {
      background-color: #2a2c32;
      color: #ffffff;
      padding: 14px 24px;
      display: grid;
      grid-template-columns: 36px 1fr 150px 180px 80px;
      align-items: center;
      gap: 16px;
      font-size: 13.5px;
      font-weight: 600;
    }
    .cart-grid-thead .col-check {
      display: flex;
      align-items: center;
    }
    .cart-grid-thead .col-total {
      text-align: center;
    }
    .cart-grid-thead .col-price {
      text-align: left;
      padding-left: 12px;
    }
    .cart-grid-thead .col-action {
      text-align: right;
    }

    /* Table Body Rows */
    .cart-table-tbody {
      background: #ffffff;
    }
    .cart-grid-row {
      padding: 18px 24px;
      display: grid;
      grid-template-columns: 36px 1fr 150px 180px 80px;
      align-items: center;
      gap: 16px;
      border-bottom: 1px solid #eaecee;
      transition: background 0.15s;
    }
    .cart-grid-row:last-child {
      border-bottom: none;
    }
    .cart-grid-row:hover {
      background: #fafbfc;
    }

    /* Checkbox */
    .custom-cart-checkbox {
      width: 17px;
      height: 17px;
      border-radius: 4px;
      border: 1.5px solid #94a3b8;
      cursor: pointer;
      accent-color: #18181b;
      margin: 0;
    }

    /* Product Info Column */
    .cart-product-cell {
      display: flex;
      align-items: center;
      gap: 18px;
      min-width: 0;
    }
    .cart-thumb-img {
      width: 90px;
      height: 64px;
      min-width: 90px;
      border-radius: 8px;
      object-fit: cover;
      background: #e2e8f0;
      border: 1px solid #e2e8f0;
    }
    .cart-info-texts {
      display: flex;
      flex-direction: column;
      gap: 3px;
      min-width: 0;
    }
    .cart-item-title {
      font-size: 14px;
      font-weight: 700;
      color: #141418;
      line-height: 1.35;
      margin: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .cart-item-variant {
      font-size: 12px;
      color: #71717a;
    }

    /* Total Stepper Column */
    .cart-stepper-cell {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .stepper-pill-box {
      display: inline-flex;
      align-items: center;
      background: #ebeef2;
      border-radius: 6px;
      padding: 3px 6px;
      gap: 12px;
    }
    .stepper-btn {
      background: none;
      border: none;
      font-size: 16px;
      font-weight: 700;
      color: #475569;
      width: 22px;
      height: 22px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.15s;
    }
    .stepper-btn:hover {
      color: #0f172a;
    }
    .stepper-value {
      font-size: 13px;
      font-weight: 700;
      color: #141418;
      min-width: 18px;
      text-align: center;
    }

    /* Price Column */
    .cart-price-cell {
      font-size: 14.5px;
      font-weight: 700;
      color: #141418;
      padding-left: 12px;
    }

    /* Action Icons Column */
    .cart-action-cell {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 14px;
    }
    .btn-action-icon {
      background: none;
      border: none;
      color: #475569;
      cursor: pointer;
      padding: 4px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: color 0.15s;
    }
    .btn-action-icon svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }
    .btn-action-delete:hover {
      color: #ef4444;
    }
    .btn-action-edit:hover {
      color: #2563eb;
    }

    /* Empty state */
    .cart-empty-message {
      text-align: center;
      padding: 60px 20px;
      color: #64748b;
    }
    .cart-empty-message svg {
      width: 48px;
      height: 48px;
      stroke: #94a3b8;
      stroke-width: 1.5;
      fill: none;
      margin-bottom: 12px;
    }
    .cart-empty-message h3 {
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 6px;
    }

    /* ==========================================================================
       Sticky Bottom Bar (Pilih Semua, Hapus, Tambah Produk | Subtotal & Checkout)
       ========================================================================== */
    .cart-bottom-sticky-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 50;
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      box-shadow: 0 -4px 18px rgba(0, 0, 0, 0.04);
      padding: 14px 24px;
    }
    .cart-bottom-container {
      max-width: 1240px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }
    .cart-bottom-left {
      display: flex;
      align-items: center;
      gap: 24px;
      font-size: 13px;
      font-weight: 600;
    }
    .label-select-all {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: #64748b;
      font-weight: 600;
      user-select: none;
    }
    .label-select-all input {
      width: 16px;
      height: 16px;
      cursor: pointer;
      accent-color: #18181b;
    }
    .btn-delete-selected {
      color: #dc2626;
      background: none;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-size: 13px;
      font-weight: 600;
      transition: color 0.15s;
    }
    .btn-delete-selected:hover {
      color: #b91c1c;
      text-decoration: underline;
    }
    .btn-add-more-products {
      color: #475569;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: color 0.15s;
    }
    .btn-add-more-products:hover {
      color: #0f172a;
    }

    .cart-bottom-right {
      display: flex;
      align-items: center;
      gap: 24px;
    }
    .cart-subtotal-display {
      font-size: 15px;
      font-weight: 600;
      color: #1e293b;
    }
    .cart-subtotal-display strong {
      font-size: 18px;
      font-weight: 800;
      color: #0f172a;
      margin-left: 4px;
    }
    .btn-checkout-action {
      background: #244173;
      color: #ffffff;
      padding: 11px 26px;
      border-radius: 6px;
      font-family: inherit;
      font-size: 13.5px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.15s, transform 0.15s;
    }
    .btn-checkout-action:hover {
      background: #19315a;
      transform: translateY(-1px);
    }
    .btn-checkout-action svg {
      width: 16px;
      height: 16px;
      stroke: currentColor;
      stroke-width: 2.2;
      fill: none;
    }

    /* Mini Edit Modal */
    .edit-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      z-index: 100;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .edit-modal-backdrop.show {
      display: flex;
    }
    .edit-modal-box {
      background: #ffffff;
      border-radius: 8px;
      width: 100%;
      max-width: 420px;
      padding: 24px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    .edit-modal-title {
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 16px;
    }
    .edit-form-group {
      margin-bottom: 14px;
    }
    .edit-form-group label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 4px;
    }
    .edit-form-input {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      font-size: 13px;
      outline: none;
      font-family: inherit;
    }
    .edit-modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }
    .btn-modal-cancel {
      padding: 8px 16px;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      background: #f8fafc;
      font-weight: 600;
      font-size: 12.5px;
      cursor: pointer;
    }
    .btn-modal-save {
      padding: 8px 18px;
      background: #244173;
      color: #ffffff;
      border: none;
      border-radius: 6px;
      font-weight: 700;
      font-size: 12.5px;
      cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 860px) {
      .cart-grid-thead {
        display: none;
      }
      .cart-grid-row {
        grid-template-columns: 30px 1fr;
        gap: 14px;
      }
      .cart-stepper-cell, .cart-price-cell, .cart-action-cell {
        grid-column: 2 / -1;
        justify-content: flex-start;
        padding-left: 0;
      }
      .cart-action-cell {
        justify-content: flex-start;
      }
      .cart-bottom-container {
        flex-direction: column;
        align-items: stretch;
      }
      .cart-bottom-left {
        justify-content: space-between;
      }
      .cart-bottom-right {
        justify-content: space-between;
      }
    }
  </style>
</head>
<body class="cart-detail-page">

  <!-- Top bar (Dark Slate Header matching design) -->
  <header class="cart-top-bar">
    <div class="cart-top-container">
      <div class="cart-top-left">
        <a href="{{ route('home') }}" class="cart-top-link">
          <svg style="width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2.2;" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Kembali ke Utama</span>
        </a>
      </div>

      <div class="cart-top-right">
        <a href="{{ route('home') }}#kontak" class="cart-top-link">
          <svg style="width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2;" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span>Kontak</span>
        </a>
        <span style="color: rgba(255, 255, 255, 0.25); margin: 0 10px;">|</span>
        @auth
          <span style="font-weight: 600; color: #ffffff;">{{ Auth::user()->name }}</span>
        @else
          <a href="{{ route('login') }}" class="cart-top-link">Masuk</a>
          <span style="color: rgba(255, 255, 255, 0.25); margin: 0 10px;">|</span>
          <a href="{{ route('register') }}" class="cart-top-link">Daftar</a>
        @endauth
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="cart-main-container">
    <!-- Header: < Dashboard & Search -->
    <div class="cart-header-row">
      <div class="cart-header-left">
        <a href="{{ route('main.dashboard') }}" class="cart-back-breadcrumb">
          <span>&lt; Dashboard</span>
        </a>
        <h1 class="cart-header-title">Data Lengkap Keranjang</h1>
      </div>

      <!-- Search input pill -->
      <div class="cart-search-box">
        <svg class="cart-search-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="cartSearchInput" class="cart-search-input" placeholder="Cari Berdasarkan ...">
      </div>
    </div>

    <!-- Cart Table Card -->
    <div class="cart-table-card">
      <!-- Dark Table Header -->
      <div class="cart-grid-thead">
        <div class="col-check">
          <input type="checkbox" id="headerSelectAll" class="custom-cart-checkbox" title="Pilih Semua">
        </div>
        <div>Nama Produk</div>
        <div class="col-total">Total</div>
        <div class="col-price">Harga Akhir</div>
        <div class="col-action">Aksi</div>
      </div>

      <!-- Table Body Rows -->
      <div class="cart-table-tbody" id="cartTableRowsContainer">
        <!-- Rendered via JavaScript -->
      </div>
    </div>
  </main>

  <!-- Sticky Bottom Checkout Bar -->
  <footer class="cart-bottom-sticky-bar">
    <div class="cart-bottom-container">
      <div class="cart-bottom-left">
        <label class="label-select-all">
          <input type="checkbox" id="footerSelectAll" class="custom-cart-checkbox">
          <span id="footerSelectedCountText">(0) Pilih Semua</span>
        </label>
        <button type="button" class="btn-delete-selected" id="btnDeleteSelected">Hapus</button>
        <a href="{{ route('main.dashboard') }}" class="btn-add-more-products">+ Tambah Produk</a>
      </div>

      <div class="cart-bottom-right">
        <div class="cart-subtotal-display">
          Subtotal: <strong id="cartSubtotalText">Rp. 0</strong>
        </div>
        <a href="#" class="btn-checkout-action" id="btnCheckoutAction">
          <span>Checkout</span>
          <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </footer>

  <!-- Mini Edit Variant Modal -->
  <div class="edit-modal-backdrop" id="editModalBackdrop">
    <div class="edit-modal-box">
      <h3 class="edit-modal-title">Edit Varian Produk</h3>
      <input type="hidden" id="editItemIndex">
      <div class="edit-form-group">
        <label for="editItemName">Nama Produk</label>
        <input type="text" id="editItemName" class="edit-form-input" readonly style="background: #f1f5f9;">
      </div>
      <div class="edit-form-group">
        <label for="editItemVariant">Jenis / Varian</label>
        <input type="text" id="editItemVariant" class="edit-form-input" placeholder="e.g. Merah, XL">
      </div>
      <div class="edit-form-group">
        <label for="editItemQty">Jumlah</label>
        <input type="number" id="editItemQty" class="edit-form-input" min="1" value="1">
      </div>
      <div class="edit-modal-actions">
        <button type="button" class="btn-modal-cancel" id="btnEditCancel">Batal</button>
        <button type="button" class="btn-modal-save" id="btnEditSave">Simpan Perubahan</button>
      </div>
    </div>
  </div>

  <!-- Interactive Cart Management Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Default sample items matching mockup exactly
      const sampleDefaultItems = [
        {
          id: 101,
          name: 'Nama Produk nama prodk yang sangat keren',
          color: 'Merah, XL',
          qty: 3,
          price: 'Rp 24.000',
          rawPrice: 24000,
          image: '/images/products/sample-hub.jpg',
          checked: false,
          addedAt: Date.now() - 4000
        },
        {
          id: 102,
          name: 'Nama Produk nama prodk yang sangat keren',
          color: 'Merah, XL',
          qty: 3,
          price: 'Rp 24.000',
          rawPrice: 24000,
          image: '/images/products/sample-hub.jpg',
          checked: true,
          addedAt: Date.now() - 3000
        },
        {
          id: 103,
          name: 'Nama Produk nama prodk yang sangat keren',
          color: 'Merah, XL',
          qty: 3,
          price: 'Rp 24.000',
          rawPrice: 24000,
          image: '/images/products/sample-hub.jpg',
          checked: true,
          addedAt: Date.now() - 2000
        },
        {
          id: 104,
          name: 'Nama Produk nama prodk yang sangat keren',
          color: 'Merah, XL',
          qty: 3,
          price: 'Rp 24.000',
          rawPrice: 24000,
          image: '/images/products/sample-hub.jpg',
          checked: false,
          addedAt: Date.now() - 1000
        }
      ];

      let rawCart = localStorage.getItem('maybeads_cart');
      let cart = rawCart !== null ? JSON.parse(rawCart) : sampleDefaultItems;

      // Ensure every item has a checked state (default to true if not set)
      cart = cart.map((it, idx) => {
        if (typeof it.checked === 'undefined') {
          // If using sample items, match screenshot: items 1 & 2 checked, 0 & 3 unchecked
          it.checked = (idx === 1 || idx === 2);
        }
        if (!it.rawPrice) {
          it.rawPrice = parseInt((it.price || '').replace(/[^0-9]/g, ''), 10) || 24000;
        }
        return it;
      });

      if (rawCart === null) {
        localStorage.setItem('maybeads_cart', JSON.stringify(cart));
      }

      const rowsContainer      = document.getElementById('cartTableRowsContainer');
      const headerSelectAll    = document.getElementById('headerSelectAll');
      const footerSelectAll    = document.getElementById('footerSelectAll');
      const selectedCountText  = document.getElementById('footerSelectedCountText');
      const subtotalText       = document.getElementById('cartSubtotalText');
      const btnDeleteSelected  = document.getElementById('btnDeleteSelected');
      const cartSearchInput    = document.getElementById('cartSearchInput');
      const btnCheckoutAction  = document.getElementById('btnCheckoutAction');

      // Edit Modal Elements
      const editModalBackdrop  = document.getElementById('editModalBackdrop');
      const editItemIndex      = document.getElementById('editItemIndex');
      const editItemName       = document.getElementById('editItemName');
      const editItemVariant    = document.getElementById('editItemVariant');
      const editItemQty        = document.getElementById('editItemQty');
      const btnEditCancel      = document.getElementById('btnEditCancel');
      const btnEditSave        = document.getElementById('btnEditSave');

      let currentSearchQuery = '';

      function saveCart() {
        localStorage.setItem('maybeads_cart', JSON.stringify(cart));
      }

      function updateCalculations() {
        const checkedItems = cart.filter(it => it.checked);
        const checkedCount = checkedItems.length;
        const allChecked   = cart.length > 0 && checkedCount === cart.length;

        if (headerSelectAll) headerSelectAll.checked = allChecked;
        if (footerSelectAll) footerSelectAll.checked = allChecked;

        if (selectedCountText) {
          selectedCountText.textContent = `(${checkedCount}) Pilih Semua`;
        }

        // Subtotal = sum of (rawPrice * qty) for checked items
        const subtotal = checkedItems.reduce((sum, it) => sum + ((it.rawPrice || 24000) * (it.qty || 1)), 0);
        if (subtotalText) {
          subtotalText.textContent = 'Rp. ' + subtotal.toLocaleString('id-ID');
        }
      }

      function renderCartRows() {
        if (!rowsContainer) return;

        let filtered = cart.map((item, originalIdx) => ({ item, originalIdx }));
        if (currentSearchQuery) {
          const q = currentSearchQuery.toLowerCase();
          filtered = filtered.filter(({ item }) =>
            (item.name || '').toLowerCase().includes(q) ||
            (item.color || '').toLowerCase().includes(q)
          );
        }

        if (filtered.length === 0) {
          rowsContainer.innerHTML = `
            <div class="cart-empty-message">
              <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              <h3>${cart.length === 0 ? 'Keranjang Anda Kosong' : 'Tidak Ada Produk yang Cocok'}</h3>
              <p>${cart.length === 0 ? 'Silakan tambahkan produk menarik dari katalog Maybeads.' : 'Coba gunakan kata kunci pencarian lainnya.'}</p>
            </div>
          `;
          updateCalculations();
          return;
        }

        rowsContainer.innerHTML = filtered.map(({ item, originalIdx }) => {
          const rawPrice = item.rawPrice || 24000;
          const qty = item.qty || 1;
          const displayPrice = 'Rp ' + rawPrice.toLocaleString('id-ID');
          const variantText = item.color || 'Merah, XL';
          const imgSrc = item.image || '/images/products/sample-hub.jpg';

          return `
            <div class="cart-grid-row" data-idx="${originalIdx}">
              <!-- Checkbox -->
              <div class="col-check">
                <input type="checkbox"
                       class="custom-cart-checkbox row-checkbox"
                       data-idx="${originalIdx}"
                       ${item.checked ? 'checked' : ''}>
              </div>

              <!-- Product Info -->
              <div class="cart-product-cell">
                <img src="${imgSrc}" alt="${item.name}" class="cart-thumb-img">
                <div class="cart-info-texts">
                  <h4 class="cart-item-title" title="${item.name}">${item.name}</h4>
                  <span class="cart-item-variant">Jenis: ${variantText}</span>
                </div>
              </div>

              <!-- Stepper -->
              <div class="cart-stepper-cell">
                <div class="stepper-pill-box">
                  <button type="button" class="stepper-btn btn-minus" data-idx="${originalIdx}">-</button>
                  <span class="stepper-value">${qty}</span>
                  <button type="button" class="stepper-btn btn-plus" data-idx="${originalIdx}">+</button>
                </div>
              </div>

              <!-- Price -->
              <div class="cart-price-cell">
                ${displayPrice}
              </div>

              <!-- Action Icons (Trash & Edit) -->
              <div class="cart-action-cell">
                <button type="button" class="btn-action-icon btn-action-delete" data-idx="${originalIdx}" title="Hapus Produk">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
                <button type="button" class="btn-action-icon btn-action-edit" data-idx="${originalIdx}" title="Edit Varian">
                  <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
              </div>
            </div>
          `;
        }).join('');

        updateCalculations();
        attachRowEventListeners();
      }

      function attachRowEventListeners() {
        // Row Checkboxes
        rowsContainer.querySelectorAll('.row-checkbox').forEach(chk => {
          chk.addEventListener('change', () => {
            const idx = parseInt(chk.dataset.idx, 10);
            cart[idx].checked = chk.checked;
            saveCart();
            updateCalculations();
          });
        });

        // Minus button
        rowsContainer.querySelectorAll('.btn-minus').forEach(btn => {
          btn.addEventListener('click', () => {
            const idx = parseInt(btn.dataset.idx, 10);
            if (cart[idx].qty > 1) {
              cart[idx].qty--;
            } else {
              const confirmRemove = confirm('Hapus produk ini dari keranjang?');
              if (confirmRemove) {
                cart.splice(idx, 1);
              }
            }
            saveCart();
            renderCartRows();
          });
        });

        // Plus button
        rowsContainer.querySelectorAll('.btn-plus').forEach(btn => {
          btn.addEventListener('click', () => {
            const idx = parseInt(btn.dataset.idx, 10);
            cart[idx].qty = (cart[idx].qty || 1) + 1;
            saveCart();
            renderCartRows();
          });
        });

        // Delete single item
        rowsContainer.querySelectorAll('.btn-action-delete').forEach(btn => {
          btn.addEventListener('click', () => {
            const idx = parseInt(btn.dataset.idx, 10);
            cart.splice(idx, 1);
            saveCart();
            renderCartRows();
          });
        });

        // Edit variant modal
        rowsContainer.querySelectorAll('.btn-action-edit').forEach(btn => {
          btn.addEventListener('click', () => {
            const idx = parseInt(btn.dataset.idx, 10);
            const item = cart[idx];
            if (!item) return;

            editItemIndex.value = idx;
            editItemName.value = item.name;
            editItemVariant.value = item.color || 'Merah, XL';
            editItemQty.value = item.qty || 1;
            editModalBackdrop.classList.add('show');
          });
        });
      }

      // Select All Toggle (Header & Footer)
      function setAllSelection(checked) {
        cart.forEach(it => (it.checked = checked));
        saveCart();
        renderCartRows();
      }

      if (headerSelectAll) {
        headerSelectAll.addEventListener('change', () => {
          setAllSelection(headerSelectAll.checked);
        });
      }

      if (footerSelectAll) {
        footerSelectAll.addEventListener('change', () => {
          setAllSelection(footerSelectAll.checked);
        });
      }

      // Delete Selected Items
      if (btnDeleteSelected) {
        btnDeleteSelected.addEventListener('click', () => {
          const checkedCount = cart.filter(it => it.checked).length;
          if (checkedCount === 0) {
            alert('Silakan pilih produk yang ingin dihapus terlebih dahulu.');
            return;
          }
          if (confirm(`Hapus ${checkedCount} produk yang dipilih dari keranjang?`)) {
            cart = cart.filter(it => !it.checked);
            saveCart();
            renderCartRows();
          }
        });
      }

      // Search Filter
      if (cartSearchInput) {
        cartSearchInput.addEventListener('input', (e) => {
          currentSearchQuery = e.target.value.trim();
          renderCartRows();
        });
      }

      // Edit Modal Save / Cancel
      if (btnEditCancel) {
        btnEditCancel.addEventListener('click', () => {
          editModalBackdrop.classList.remove('show');
        });
      }
      if (editModalBackdrop) {
        editModalBackdrop.addEventListener('click', (e) => {
          if (e.target === editModalBackdrop) {
            editModalBackdrop.classList.remove('show');
          }
        });
      }
      if (btnEditSave) {
        btnEditSave.addEventListener('click', () => {
          const idx = parseInt(editItemIndex.value, 10);
          if (cart[idx]) {
            cart[idx].color = editItemVariant.value.trim() || 'Merah, XL';
            const qtyVal = parseInt(editItemQty.value, 10);
            cart[idx].qty = qtyVal > 0 ? qtyVal : 1;
            saveCart();
            editModalBackdrop.classList.remove('show');
            renderCartRows();
          }
        });
      }

      // Checkout Button
      if (btnCheckoutAction) {
        btnCheckoutAction.addEventListener('click', (e) => {
          e.preventDefault();
          const checkedItems = cart.filter(it => it.checked);
          if (checkedItems.length === 0) {
            alert('Silakan pilih setidaknya 1 produk untuk checkout.');
            return;
          }
          const subtotal = checkedItems.reduce((sum, it) => sum + ((it.rawPrice || 24000) * (it.qty || 1)), 0);
          alert(`Melanjutkan ke proses pembayaran...\nTotal Item: ${checkedItems.length}\nSubtotal: Rp. ${subtotal.toLocaleString('id-ID')}`);
        });
      }

      // Initial Render
      renderCartRows();
    });
  </script>
</body>
</html>
