<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Transaksi Kasir (POS) — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
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
        <span class="header-title">Admin Dashboard / Transaksi Kasir</span>
      </div>

      <div class="header-right">
        <div class="header-user-badge">
          <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
          <div class="header-user-info">
            <span class="header-user-name">{{ Auth::user()->name ?? 'Kasir Administrator' }}</span>
            <span class="header-user-role">Kasir Aktif</span>
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
            <div class="search-input-wrap" style="flex: 1;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input type="text" id="posSearchInput" placeholder="Cari produk kasir..." oninput="filterPosProducts()">
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
                   data-id="{{ $product->id }}"
                   data-name="{{ $product->product_name }}"
                   data-price="{{ (float) $product->price }}"
                   data-stock="{{ $product->stock }}"
                   data-cat="{{ $product->category_id }}"
                   onclick="addToCart({{ $product->id }}, '{{ addslashes($product->product_name) }}', {{ (float)$product->price }}, {{ $product->stock }})">
                @if($product->image)
                  <img src="{{ asset('storage/product/' . $product->image) }}" class="pos-product-img" alt="{{ $product->product_name }}">
                @else
                  <div class="pos-product-img" style="display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 32px; height: 32px;">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                      <circle cx="8.5" cy="8.5" r="1.5"></circle>
                      <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                  </div>
                @endif
                <div class="pos-product-name">{{ $product->product_name }}</div>
                <div class="pos-product-cat">{{ $product->category->category_name ?? '-' }}</div>
                <div class="pos-product-footer">
                  <div class="pos-product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                  <div class="pos-product-stock">Stok: {{ $product->stock }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Right: Checkout Cart Panel -->
        <div class="pos-cart-panel">
          <div class="pos-cart-header">
            <div>
              <div class="pos-cart-title">Ringkasan Kasir</div>
              <div style="font-size: 11.5px; color: #94a3b8;" id="posItemCount">0 item dipilih</div>
            </div>
            <button type="button" class="btn-secondary btn-sm" onclick="clearCart()" style="color: #ef4444;">Kosongkan</button>
          </div>

          <div class="pos-cart-items" id="posCartItems">
            <div style="text-align: center; color: #94a3b8; margin: auto; padding: 40px 10px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px; margin-bottom: 8px; opacity: 0.5;">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
              </svg>
              <p>Klik produk di sebelah kiri untuk menambahkan ke struk.</p>
            </div>
          </div>

          <div class="pos-cart-summary">
            <div class="summary-row">
              <span>Subtotal</span>
              <strong id="posSubtotalText">Rp 0</strong>
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
              <label class="form-label" style="font-size: 11.5px;">Metode Pembayaran</label>
              <select id="posPaymentMethod" class="form-control" style="padding: 7px 10px; font-size: 13px;" onchange="toggleCashInput()">
                <option value="Tunai">Tunai (Cash)</option>
                <option value="QRIS">QRIS</option>
                <option value="Transfer Bank (BCA)">Transfer BCA</option>
                <option value="E-Wallet">E-Wallet</option>
              </select>
            </div>

            <div class="form-group" id="cashInputGroup" style="margin-bottom: 12px;">
              <label class="form-label" style="font-size: 11.5px;">Nominal Uang Diterima</label>
              <input type="number" id="posCashReceived" class="form-control" style="padding: 7px 10px; font-size: 13px;" placeholder="0" oninput="calculateChange()">
              <div style="font-size: 12px; margin-top: 4px; display: flex; justify-content: space-between; color: #16a34a; font-weight: 600;">
                <span>Kembalian:</span>
                <span id="posChangeText">Rp 0</span>
              </div>
            </div>

            <div class="summary-row total">
              <span>Total Tagihan</span>
              <span id="posTotalText">Rp 0</span>
            </div>

            <button type="button" class="btn-primary" style="width: 100%; justify-content: center; padding: 12px;" onclick="processPosCheckout()">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              Selesaikan Transaksi
            </button>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Modal Struk Cetak Kasir -->
<div class="modal-backdrop" id="receiptModal" style="display: none;">
  <div class="modal-content" style="max-width: 360px;">
    <div class="modal-header">
      <h3 class="modal-title" style="font-size: 15px;">Struk Transaksi</h3>
      <button type="button" class="modal-close" onclick="closeReceiptModal()">✕</button>
    </div>
    <div class="modal-body" id="receiptPrintArea" style="font-family: monospace; font-size: 12px; line-height: 1.4; color: #000; padding: 20px;">
      <div style="text-align: center; margin-bottom: 14px;">
        <h2 style="font-size: 16px; font-weight: 800; margin-bottom: 2px;">MAYBEADS STORE</h2>
        <p style="font-size: 11px;">Aksesoris & Manik Unik</p>
        <p style="font-size: 10px; color: #555;">------------------------------------------</p>
      </div>
      <div id="receiptContent">
        <!-- JS Populated -->
      </div>
      <p style="text-align: center; font-size: 10px; color: #555; margin-top: 10px;">------------------------------------------</p>
      <p style="text-align: center; font-size: 11px; margin-top: 6px;">Terima kasih telah berbelanja di Maybeads!</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn-secondary btn-sm" onclick="closeReceiptModal()">Tutup</button>
      <button type="button" class="btn-primary btn-sm" onclick="printReceipt()">Cetak Struk</button>
    </div>
  </div>
</div>

<script>
  let cart = [];
  let currentCategory = '';

  function addToCart(id, name, price, stock) {
    if (stock <= 0) {
      alert('Stok produk ini sedang habis!');
      return;
    }

    const existing = cart.find(item => item.id === id);
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

  function renderCart() {
    const container = document.getElementById('posCartItems');
    const itemCount = document.getElementById('posItemCount');
    const subtotalText = document.getElementById('posSubtotalText');
    const totalText = document.getElementById('posTotalText');

    if (cart.length === 0) {
      container.innerHTML = `
        <div style="text-align: center; color: #94a3b8; margin: auto; padding: 40px 10px;">
          <p>Klik produk di sebelah kiri untuk menambahkan ke struk.</p>
        </div>
      `;
      itemCount.innerText = '0 item dipilih';
      subtotalText.innerText = 'Rp 0';
      totalText.innerText = 'Rp 0';
      calculateChange();
      return;
    }

    let subtotal = 0;
    let totalItems = 0;
    let html = '';

    cart.forEach(item => {
      const lineTotal = item.price * item.qty;
      subtotal += lineTotal;
      totalItems += item.qty;

      html += `
        <div class="pos-cart-item">
          <div style="flex: 1; padding-right: 10px;">
            <div style="font-weight: 700; font-size: 13px; color: #1e293b;">${item.name}</div>
            <div style="font-size: 11.5px; color: #64748b;">Rp ${item.price.toLocaleString('id-ID')} / pcs</div>
          </div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <button type="button" class="btn-action" style="width: 26px; height: 26px;" onclick="changeQty(${item.id}, -1)">-</button>
            <span style="font-weight: 700; font-size: 13px; min-width: 18px; text-align: center;">${item.qty}</span>
            <button type="button" class="btn-action" style="width: 26px; height: 26px;" onclick="changeQty(${item.id}, 1)">+</button>
            <strong style="font-size: 13px; min-width: 75px; text-align: right; color: var(--navy);">Rp ${lineTotal.toLocaleString('id-ID')}</strong>
            <button type="button" class="btn-action danger" style="width: 26px; height: 26px;" onclick="removeFromCart(${item.id})">✕</button>
          </div>
        </div>
      `;
    });

    container.innerHTML = html;
    itemCount.innerText = totalItems + ' item dipilih';
    subtotalText.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    totalText.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    calculateChange();
  }

  function toggleCashInput() {
    const method = document.getElementById('posPaymentMethod').value;
    const group = document.getElementById('cashInputGroup');
    group.style.display = (method === 'Tunai') ? 'block' : 'none';
  }

  function calculateChange() {
    const subtotal = cart.reduce((acc, cur) => acc + (cur.price * cur.qty), 0);
    const cash = parseFloat(document.getElementById('posCashReceived').value || 0);
    const change = Math.max(0, cash - subtotal);
    document.getElementById('posChangeText').innerText = 'Rp ' + change.toLocaleString('id-ID');
  }

  function filterPosProducts() {
    const search = document.getElementById('posSearchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.pos-product-card');

    cards.forEach(card => {
      const name = card.getAttribute('data-name').toLowerCase();
      const cat = card.getAttribute('data-cat');

      const matchesSearch = name.includes(search);
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

  function processPosCheckout() {
    if (cart.length === 0) {
      alert('Pilih minimal satu produk untuk melakukan transaksi!');
      return;
    }

    const subtotal = cart.reduce((acc, cur) => acc + (cur.price * cur.qty), 0);
    const method = document.getElementById('posPaymentMethod').value;
    const cash = parseFloat(document.getElementById('posCashReceived').value || 0);

    if (method === 'Tunai' && cash < subtotal) {
      alert('Nominal uang diterima kurang dari total tagihan!');
      return;
    }

    const payload = {
      items: cart.map(i => ({ id: i.id, qty: i.qty })),
      payment_method: method,
      paid_amount: method === 'Tunai' ? cash : subtotal
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
      if (data.success) {
        showReceipt(cart, subtotal, method, cash);
        clearCart();
      } else {
        alert(data.message || 'Terjadi kesalahan saat menyimpan transaksi.');
      }
    })
    .catch(err => {
      alert('Gagal memproses transaksi. Periksa koneksi atau server.');
    });
  }

  function showReceipt(items, total, method, cash) {
    const change = Math.max(0, cash - total);
    let html = `
      <div style="margin-bottom: 8px;">
        <div style="display:flex; justify-content:space-between;"><span>Waktu:</span><span>${new Date().toLocaleString('id-ID')}</span></div>
        <div style="display:flex; justify-content:space-between;"><span>Kasir:</span><span>{{ Auth::user()->name ?? 'Admin' }}</span></div>
        <div style="display:flex; justify-content:space-between;"><span>Metode:</span><span>${method}</span></div>
      </div>
      <div style="border-top: 1px dashed #ccc; padding-top: 8px; margin-bottom: 8px;">
    `;

    items.forEach(i => {
      html += `
        <div style="display:flex; justify-content:space-between;">
          <span>${i.name} × ${i.qty}</span>
          <span>Rp ${(i.price * i.qty).toLocaleString('id-ID')}</span>
        </div>
      `;
    });

    html += `
      </div>
      <div style="border-top: 1px dashed #ccc; padding-top: 8px;">
        <div style="display:flex; justify-content:space-between; font-weight:bold;"><span>TOTAL:</span><span>Rp ${total.toLocaleString('id-ID')}</span></div>
        ${method === 'Tunai' ? `
          <div style="display:flex; justify-content:space-between;"><span>Bayar:</span><span>Rp ${cash.toLocaleString('id-ID')}</span></div>
          <div style="display:flex; justify-content:space-between;"><span>Kembali:</span><span>Rp ${change.toLocaleString('id-ID')}</span></div>
        ` : ''}
      </div>
    `;

    document.getElementById('receiptContent').innerHTML = html;
    document.getElementById('receiptModal').style.display = 'flex';
  }

  function closeReceiptModal() {
    document.getElementById('receiptModal').style.display = 'none';
  }

  function printReceipt() {
    window.print();
  }
</script>

</body>
</html>
