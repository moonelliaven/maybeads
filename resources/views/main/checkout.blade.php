<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Checkout & Pengiriman — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/main/dashboard.css'])

  <style>
    .checkout-container {
      max-width: 1200px;
      margin: 36px auto 100px;
      padding: 0 24px;
    }

    .checkout-grid {
      display: grid;
      grid-template-columns: 1.35fr 1fr;
      gap: 36px;
      align-items: flex-start;
    }

    .checkout-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 32px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .checkout-title {
      font-size: 20px;
      font-weight: 800;
      color: #141418;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #334155;
      margin-bottom: 6px;
    }

    .form-input, .form-textarea {
      width: 100%;
      padding: 10px 14px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      font-family: inherit;
      font-size: 14px;
      color: #1e293b;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
    }

    .form-input:focus, .form-textarea:focus {
      border-color: #244173;
      box-shadow: 0 0 0 3px rgba(36, 65, 115, 0.12);
    }

    .payment-options-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-top: 10px;
    }

    .payment-option-card {
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      padding: 16px;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      gap: 8px;
      transition: all 0.2s;
    }

    .payment-option-card:hover {
      border-color: #94a3b8;
    }

    .payment-option-card.selected {
      border-color: #244173;
      background: #f8fafc;
    }

    .payment-option-header {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      font-weight: 700;
      color: #141418;
    }

    .payment-option-desc {
      font-size: 12px;
      color: #64748b;
      line-height: 1.4;
    }

    /* Summary list */
    .summary-items-list {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-bottom: 24px;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 18px;
    }

    .summary-item-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .summary-item-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .summary-thumb {
      width: 44px;
      height: 44px;
      border-radius: 6px;
      object-fit: cover;
      background: #e2e8f0;
    }

    .summary-name {
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
    }

    .summary-qty {
      font-size: 12px;
      color: #64748b;
    }

    .summary-price {
      font-size: 13.5px;
      font-weight: 700;
      color: #141418;
    }

    .summary-calc-row {
      display: flex;
      justify-content: space-between;
      font-size: 13.5px;
      color: #64748b;
      margin-bottom: 8px;
    }

    .summary-calc-total {
      display: flex;
      justify-content: space-between;
      font-size: 18px;
      font-weight: 800;
      color: #244173;
      border-top: 2px dashed #e2e8f0;
      padding-top: 14px;
      margin-top: 14px;
    }

    .btn-submit-order {
      width: 100%;
      margin-top: 24px;
      padding: 14px 20px;
      background: #244173;
      color: #ffffff;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-submit-order:hover {
      background: #19315a;
      transform: translateY(-1px);
    }

    @media (max-width: 860px) {
      .checkout-grid {
        grid-template-columns: 1fr;
      }
      .payment-options-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="dashboard-page">

  <!-- Top Bar -->
  <header class="top-nav-bar">
    <div class="top-nav-container">
      <div class="top-nav-left">
        <a href="{{ route('cart.index') }}" class="back-home-link">
          <svg class="chevron-back" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Kembali ke Keranjang</span>
        </a>
      </div>
      <div class="top-nav-right">
        <a href="{{ route('contact') }}" class="top-link">
          <svg class="pin-icon" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          <span>Kontak</span>
        </a>
      </div>
    </div>
  </header>

  <!-- Hero Banner -->
  <section class="catalog-hero-section">
    <div class="hero-bg-layer">
      <img src="{{ asset('images/landing/banner-accessories.jpg') }}" alt="Banner Maybeads" class="hero-bg-image">
      <div class="hero-gradient-overlay"></div>
    </div>
    <div class="hero-container">
      <h1 class="hero-main-title">Temukan Gaya Favoritmu</h1>
      <p class="hero-main-desc">Selesaikan pesanan Anda dengan aman dan cepat.</p>
    </div>
  </section>

  <!-- Main Checkout Content -->
  <main class="checkout-container">
    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
      @csrf

      <input type="hidden" name="total_amount" id="formTotalAmount" value="98000">
      <input type="hidden" name="product_id" value="{{ $sampleProduct->id ?? 1 }}">
      <input type="hidden" name="quantity" id="formQuantity" value="1">
      <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="qris">

      <div class="checkout-grid">
        
        <!-- Left: Form Pengiriman & Pembayaran -->
        <div class="checkout-card">
          <h2 class="checkout-title">
            <svg viewBox="0 0 24 24" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <span>Informasi Pengiriman</span>
          </h2>

          <div class="form-group">
            <label class="form-label">Nama Lengkap Penerima</label>
            <input type="text" name="customer_name" class="form-input" required value="{{ Auth::user()?->name ?? 'Zaki Srianto' }}" placeholder="Contoh: Zaki Srianto">
          </div>

          <div class="form-group">
            <label class="form-label">Nomor WhatsApp / Telepon</label>
            <input type="text" name="customer_phone" class="form-input" required value="081234567890" placeholder="Contoh: 081234567890">
          </div>

          <div class="form-group">
            <label class="form-label">Alamat Lengkap Pengiriman</label>
            <textarea name="address" rows="3" class="form-textarea" required placeholder="Jl. Pangeran Suriansyah No. 12, Loktabat Utara, Banjarbaru, Kalsel">Jl. Pangeran Suriansyah, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjar Baru, Kalimantan Selatan 70714</textarea>
          </div>

          <div class="form-group">
            <label class="form-label">Catatan Tambahan (Opsional)</label>
            <input type="text" name="notes" class="form-input" placeholder="Warna kemasan kado, titip di satpam, dll.">
          </div>

          <h2 class="checkout-title" style="margin-top: 32px;">
            <svg viewBox="0 0 24 24" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
            <span>Metode Pembayaran</span>
          </h2>

          <div class="payment-options-grid">
            <div class="payment-option-card selected" data-method="qris">
              <div class="payment-option-header">
                <input type="radio" name="pay_radio" checked>
                <span>QRIS Instant</span>
              </div>
              <p class="payment-option-desc">Scan via GoPay, OVO, Dana, ShopeePay, BCA, dll.</p>
            </div>

            <div class="payment-option-card" data-method="transfer">
              <div class="payment-option-header">
                <input type="radio" name="pay_radio">
                <span>Transfer Bank</span>
              </div>
              <p class="payment-option-desc">BCA, Mandiri, BRI dengan verifikasi otomatis.</p>
            </div>
          </div>
        </div>

        <!-- Right: Ringkasan Pesanan -->
        <div class="checkout-card">
          <h2 class="checkout-title">
            <svg viewBox="0 0 24 24" style="width:22px;height:22px;fill:none;stroke:currentColor;stroke-width:2;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span>Ringkasan Belanja</span>
          </h2>

          <div class="summary-items-list" id="checkoutItemsContainer">
            <!-- Dynamically populated or sample item -->
            <div class="summary-item-row">
              <div class="summary-item-info">
                <img src="{{ asset('images/landing/prod-butterfly.jpg') }}" alt="Product" class="summary-thumb">
                <div>
                  <div class="summary-name">Sparkling Butterfly Set</div>
                  <div class="summary-qty">Qty: 1 pcs</div>
                </div>
              </div>
              <div class="summary-price">Rp 98.000</div>
            </div>
          </div>

          <div class="summary-calc-row">
            <span>Subtotal Produk</span>
            <span id="checkoutSubtotal">Rp 98.000</span>
          </div>

          <div class="summary-calc-row">
            <span>Biaya Pengiriman</span>
            <span style="color: #10b981; font-weight: 700;">GRATIS</span>
          </div>

          <div class="summary-calc-total">
            <span>Total Tagihan</span>
            <span id="checkoutTotal">Rp 98.000</span>
          </div>

          <button type="submit" class="btn-submit-order" id="btnSubmitOrder">
            Bayar Sekarang →
          </button>
        </div>

      </div>
    </form>
  </main>

  <!-- Footer -->
  <footer class="site-dark-footer">
    <div class="site-footer-container">
      <div class="site-footer-bottom">
        <div class="footer-bottom-left">Built for digital cyber-nomads.</div>
        <div class="footer-bottom-right">&copy; {{ date('Y') }} Maybeads. All rights reserved.</div>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Payment Option Selection
      const cards = document.querySelectorAll('.payment-option-card');
      const methodInput = document.getElementById('selectedPaymentMethod');

      cards.forEach(card => {
        card.addEventListener('click', () => {
          cards.forEach(c => {
            c.classList.remove('selected');
            c.querySelector('input[type="radio"]').checked = false;
          });
          card.classList.add('selected');
          card.querySelector('input[type="radio"]').checked = true;
          methodInput.value = card.dataset.method;
        });
      });

      // Load items from localStorage if available
      try {
        const raw = localStorage.getItem('maybeads_cart');
        const cart = raw ? JSON.parse(raw) : [];
        const checkedItems = cart.filter(i => i.checked !== false);

        if (checkedItems.length > 0) {
          const container = document.getElementById('checkoutItemsContainer');
          container.innerHTML = '';
          let sum = 0;
          let totalQty = 0;

          checkedItems.forEach(item => {
            const price = item.rawPrice || 98000;
            const q = item.qty || 1;
            sum += price * q;
            totalQty += q;

            const row = document.createElement('div');
            row.className = 'summary-item-row';
            row.innerHTML = `
              <div class="summary-item-info">
                <img src="${item.image || '/images/landing/prod-butterfly.jpg'}" alt="${item.name}" class="summary-thumb">
                <div>
                  <div class="summary-name">${item.name}</div>
                  <div class="summary-qty">Qty: ${q} pcs</div>
                </div>
              </div>
              <div class="summary-price">Rp ${(price * q).toLocaleString('id-ID')}</div>
            `;
            container.appendChild(row);
          });

          document.getElementById('checkoutSubtotal').textContent = 'Rp ' + sum.toLocaleString('id-ID');
          document.getElementById('checkoutTotal').textContent = 'Rp ' + sum.toLocaleString('id-ID');
          document.getElementById('formTotalAmount').value = sum;
          document.getElementById('formQuantity').value = totalQty;
        }
      } catch (e) {}
    });
  </script>
</body>
</html>
