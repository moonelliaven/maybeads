<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Data Pesanan — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
      background-color: #f8f9fa;
      color: #141418;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Top Dark Bar */
    .top-dark-bar {
      background-color: #1a2232;
      color: #ffffff;
      padding: 14px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .top-brand-logo {
      font-size: 19px;
      font-weight: 800;
      color: #ffffff;
      text-decoration: none;
      letter-spacing: -0.01em;
    }

    .top-menu-links {
      display: flex;
      gap: 28px;
      list-style: none;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
    }

    .top-menu-links a {
      color: #cbd5e1;
      text-decoration: none;
      transition: color 0.15s;
    }

    .top-menu-links a:hover {
      color: #ffffff;
    }

    /* Sub Navbar */
    .sub-nav-bar {
      background: #ffffff;
      border-bottom: 1px solid #e5e7eb;
      padding: 12px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .sub-nav-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #355389;
      font-size: 18px;
      font-weight: 800;
    }

    .sub-nav-crumb {
      font-size: 11px;
      color: #94a3b8;
      font-weight: 500;
    }

    .sub-nav-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sub-btn-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 999px;
      border: 1px solid #cbd5e1;
      background: #ffffff;
      color: #334155;
      font-size: 11.5px;
      font-weight: 600;
      text-decoration: none;
    }

    /* Main Container */
    .order-checkout-main {
      max-width: 1120px;
      width: 100%;
      margin: 28px auto 140px;
      padding: 0 24px;
      flex: 1;
    }

    .breadcrumb-text {
      font-size: 11px;
      color: #94a3b8;
      margin-bottom: 4px;
    }

    .page-title-main {
      font-size: 18px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 24px;
    }

    /* Two Columns Layout */
    .checkout-two-cols {
      display: grid;
      grid-template-columns: 1.45fr 1fr;
      gap: 24px;
      align-items: start;
    }

    .order-box-card {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 12px;
      padding: 24px 28px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .box-card-title {
      font-size: 14.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 16px;
    }

    /* Product Table in Left Card */
    .items-table-header {
      background-color: #2b2d33;
      color: #ffffff;
      border-radius: 6px;
      padding: 10px 16px;
      display: grid;
      grid-template-columns: 2fr 1.2fr 0.8fr 1.2fr;
      font-size: 11px;
      font-weight: 700;
      margin-bottom: 12px;
    }

    .items-table-row {
      display: grid;
      grid-template-columns: 2fr 1.2fr 0.8fr 1.2fr;
      align-items: center;
      padding: 12px 16px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 11px;
    }

    .prod-cell {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .prod-thumb-box {
      width: 38px;
      height: 38px;
      border-radius: 6px;
      background: #d4d4d8;
      object-fit: cover;
    }

    .prod-name-title {
      font-size: 11.5px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 2px;
    }

    .prod-cat-sub {
      font-size: 9.5px;
      color: #94a3b8;
    }

    /* Total Harga Summary in Left Card */
    .total-calc-wrap {
      margin-top: 24px;
    }

    .calc-row-item {
      display: flex;
      justify-content: space-between;
      font-size: 11.5px;
      color: #475569;
      margin-bottom: 10px;
    }

    .calc-row-item span:last-child {
      color: #141418;
      font-weight: 600;
    }

    .calc-divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 14px 0;
    }

    .final-row-item {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      font-weight: 800;
      color: #141418;
    }

    /* Right Card: Pengambilan & Pembayaran */
    .radio-option-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12px;
      margin-bottom: 12px;
      cursor: pointer;
    }

    .radio-left-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
      color: #141418;
    }

    .radio-left-label input {
      accent-color: #141418;
      cursor: pointer;
    }

    .radio-cost-text {
      color: #64748b;
      font-size: 11px;
    }

    .address-input-wrapper {
      position: relative;
      margin-top: 10px;
      margin-bottom: 20px;
    }

    .address-field {
      width: 100%;
      padding: 9px 120px 9px 12px;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      font-size: 11.5px;
      color: #141418;
      outline: none;
    }

    .btn-default-addr {
      position: absolute;
      right: 4px;
      top: 4px;
      bottom: 4px;
      padding: 0 10px;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 4px;
      font-size: 10px;
      font-weight: 600;
      color: #475569;
      cursor: pointer;
    }

    .section-divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 20px 0;
    }

    /* Sticky Bottom Bar */
    .sticky-checkout-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      padding: 14px 40px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 20px;
      z-index: 100;
      box-shadow: 0 -4px 12px rgba(0,0,0,0.03);
    }

    .total-footer-label {
      font-size: 13.5px;
      color: #141418;
    }

    .total-footer-val {
      font-size: 16px;
      font-weight: 800;
      color: #141418;
      margin-right: 12px;
    }

    .btn-footer-cancel {
      padding: 9px 24px;
      border: 1.5px solid #cbd5e1;
      background: #ffffff;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      color: #141418;
      text-decoration: none;
      cursor: pointer;
    }

    .btn-footer-proceed {
      padding: 9px 24px;
      border: none;
      background: #2b4570;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 700;
      color: #ffffff;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      transition: background 0.15s;
    }

    .btn-footer-proceed:hover {
      background: #1e3355;
    }

    @media (max-width: 860px) {
      .checkout-two-cols {
        grid-template-columns: 1fr;
      }
      .top-dark-bar, .sub-nav-bar, .sticky-checkout-footer {
        padding: 12px 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Top Dark Header -->
  <header class="top-dark-bar">
    <a href="{{ route('home') }}" class="top-brand-logo">Maybead.s</a>
    <ul class="top-menu-links">
      <li><a href="{{ route('home') }}">BERANDA</a></li>
      <li><a href="{{ route('contact') }}">KONTAK</a></li>
      <li><a href="{{ route('main.dashboard') }}">PRODUK</a></li>
    </ul>
  </header>

  <!-- Sub Navbar -->
  <div class="sub-nav-bar">
    <div class="sub-nav-brand">
      <span>Maybeads</span>
      <span class="sub-nav-crumb">/ Katalog Belanja</span>
    </div>
    <div class="sub-nav-actions">
      <a href="{{ route('cart.index') }}" class="sub-btn-pill">
        <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
        <span>Keranjang</span>
      </a>
      <a href="{{ route('orders.history') }}" class="sub-btn-pill">
        <svg viewBox="0 0 24 24" style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        <span>Akun</span>
        <svg viewBox="0 0 24 24" style="width:10px;height:10px;stroke:currentColor;fill:none;stroke-width:2.5;"><polyline points="6 9 12 15 18 9"></polyline></svg>
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <main class="order-checkout-main">
    <div class="breadcrumb-text">&lt; Dashboard / Keranjang</div>
    <h1 class="page-title-main">Detail Data Pesanan</h1>

    <div class="checkout-two-cols">
      
      <!-- Kotak Kiri: Daftar Produk & Total Harga -->
      <div class="order-box-card">
        <h2 class="box-card-title">Daftar Produk</h2>

        <div class="items-table-header">
          <span>Nama Produk</span>
          <span>Harga Satuan</span>
          <span>Jumlah</span>
          <span style="text-align: right;">Total</span>
        </div>

        <div id="checkoutItemsList">
          <!-- Item 1 -->
          <div class="items-table-row">
            <div class="prod-cell">
              <div class="prod-thumb-box"></div>
              <div>
                <div class="prod-name-title">Deskripsi Nama Produk</div>
                <div class="prod-cat-sub">Nama Kategori</div>
              </div>
            </div>
            <div>Rp 42.000,00</div>
            <div>x 1</div>
            <div style="text-align: right; font-weight: 600;">Rp 42.000,00</div>
          </div>

          <!-- Item 2 -->
          <div class="items-table-row">
            <div class="prod-cell">
              <div class="prod-thumb-box"></div>
              <div>
                <div class="prod-name-title">Deskripsi Nama Produk</div>
                <div class="prod-cat-sub">Nama Kategori</div>
              </div>
            </div>
            <div>Rp 42.000,00</div>
            <div>x 1</div>
            <div style="text-align: right; font-weight: 600;">Rp 42.000,00</div>
          </div>

          <!-- Item 3 -->
          <div class="items-table-row">
            <div class="prod-cell">
              <div class="prod-thumb-box"></div>
              <div>
                <div class="prod-name-title">Deskripsi Nama Produk</div>
                <div class="prod-cat-sub">Nama Kategori</div>
              </div>
            </div>
            <div>Rp 42.000,00</div>
            <div>x 1</div>
            <div style="text-align: right; font-weight: 600;">Rp 42.000,00</div>
          </div>
        </div>

        <!-- Total Harga Summary -->
        <div class="total-calc-wrap">
          <h2 class="box-card-title" style="margin-bottom: 12px;">Total Harga</h2>

          <div class="calc-row-item">
            <span>Subtotal</span>
            <span id="subtotalVal">Rp. 42.000,00</span>
          </div>

          <div class="calc-row-item">
            <span>Biaya Pengantaran</span>
            <span>Rp. 42.000,00</span>
          </div>

          <div class="calc-row-item">
            <span>Biaya Admin</span>
            <span>Rp. 42.000,00</span>
          </div>

          <div class="calc-row-item">
            <span>Pengambilan</span>
            <span>Rp. 42.000,00</span>
          </div>

          <div class="calc-divider"></div>

          <div class="final-row-item">
            <span>Harga Akhir</span>
            <span id="finalPriceVal">42.000,00</span>
          </div>
        </div>

      </div>

      <!-- Kotak Kanan: Pilihan Pengambilan & Pembayaran -->
      <div class="order-box-card">
        <h2 class="box-card-title">Pilih Jenis Pengambilan:</h2>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pickup_type" value="pickup" checked>
            <span>Ambil Ditempat</span>
          </span>
          <span class="radio-cost-text">+ Rp 1.000</span>
        </label>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pickup_type" value="delivery">
            <span>Diantar</span>
          </span>
          <span class="radio-cost-text">Menghitung...</span>
        </label>

        <div class="address-input-wrapper">
          <input type="text" class="address-field" placeholder="Isi Alamat..." value="Jl. Pangeran Suriansyah, Loktabat Utara, Banjarbaru">
          <button type="button" class="btn-default-addr">Alamat Default</button>
        </div>

        <div class="section-divider"></div>

        <h2 class="box-card-title">Pilih Jenis Pembayaran</h2>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pay_type" value="qris" checked>
            <span>QRIS</span>
          </span>
          <span class="radio-cost-text">+ Rp 200</span>
        </label>

        <label class="radio-option-item">
          <span class="radio-left-label">
            <input type="radio" name="pay_type" value="ewallet">
            <span>E-Wallet</span>
          </span>
          <span class="radio-cost-text">+ Rp 200</span>
        </label>

      </div>

    </div>
  </main>

  <!-- Sticky Bottom Bar -->
  <footer class="sticky-checkout-footer">
    <div class="total-footer-label">Total</div>
    <div class="total-footer-val" id="footerTotalDisplay">Rp. 48.000</div>
    <a href="{{ route('cart.index') }}" class="btn-footer-cancel">Batal</a>
    <a href="{{ route('payment.qris') }}" class="btn-footer-proceed" id="btnGoToPayment">
      <span>Lanjut Ke Pembayaran</span>
      <svg viewBox="0 0 24 24" style="width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5;"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
    </a>
  </footer>

  <script>
    // Read cart items from localStorage if available to populate
    try {
      const raw = localStorage.getItem('maybeads_cart');
      if (raw) {
        const cart = JSON.parse(raw);
        const checked = cart.filter(c => c.checked !== false);
        if (checked.length > 0) {
          const container = document.getElementById('checkoutItemsList');
          container.innerHTML = '';
          let sum = 0;

          checked.forEach(item => {
            const price = item.rawPrice || 42000;
            const q = item.qty || 1;
            const lineTotal = price * q;
            sum += lineTotal;

            const row = document.createElement('div');
            row.className = 'items-table-row';
            row.innerHTML = `
              <div class="prod-cell">
                <img src="${item.image || '/images/products/sample-hub.jpg'}" alt="${item.name}" class="prod-thumb-box">
                <div>
                  <div class="prod-name-title">${item.name}</div>
                  <div class="prod-cat-sub">Aksesoris</div>
                </div>
              </div>
              <div>Rp ${price.toLocaleString('id-ID')},00</div>
              <div>x ${q}</div>
              <div style="text-align: right; font-weight: 600;">Rp ${lineTotal.toLocaleString('id-ID')},00</div>
            `;
            container.appendChild(row);
          });

          document.getElementById('subtotalVal').textContent = 'Rp. ' + sum.toLocaleString('id-ID') + ',00';
          document.getElementById('finalPriceVal').textContent = sum.toLocaleString('id-ID') + ',00';
          document.getElementById('footerTotalDisplay').textContent = 'Rp. ' + (sum + 1000 + 200).toLocaleString('id-ID');
        }
      }
    } catch(e) {}
  </script>
</body>
</html>
