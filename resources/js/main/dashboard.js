/* ==========================================================================
   Main Product Catalog Interactive Script - Maybeads
   Handles Filter Toggle, Sorting, Modal, Cart Drawer, and Popover
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

  /* ==========================================================================
     1. SIDEBAR FILTER TOGGLE ("Filter <" Button)
     ========================================================================== */
  const filterToggleBtn = document.getElementById('filterToggleBtn');
  const sidebarPanel    = document.getElementById('sidebarFilterPanel');

  if (filterToggleBtn && sidebarPanel) {
    // Read state from localStorage
    const isCollapsedSaved = localStorage.getItem('maybeads_filter_collapsed') === 'true';
    if (isCollapsedSaved && window.innerWidth > 900) {
      sidebarPanel.classList.add('is-collapsed');
      filterToggleBtn.classList.add('collapsed');
    }

    filterToggleBtn.addEventListener('click', () => {
      const isNowCollapsed = sidebarPanel.classList.toggle('is-collapsed');
      filterToggleBtn.classList.toggle('collapsed', isNowCollapsed);
      localStorage.setItem('maybeads_filter_collapsed', isNowCollapsed ? 'true' : 'false');
    });
  }


  /* ==========================================================================
     2. FILTER & SORT ACTIONS (Form Submissions)
     ========================================================================== */
  const filterForm    = document.getElementById('filterForm');
  const inputSort     = document.getElementById('inputSort');
  const inputCategory = document.getElementById('inputCategory');

  // Sort Radio Options
  document.querySelectorAll('.sort-radio-option[data-sort]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (inputSort && filterForm) {
        inputSort.value = btn.dataset.sort;
        filterForm.submit();
      }
    });
  });

  // Category Options
  document.querySelectorAll('.category-option-item[data-category]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (inputCategory && filterForm) {
        inputCategory.value = btn.dataset.category;
        filterForm.submit();
      }
    });
  });

  // Expand Categories Toggle
  const btnToggleCategories  = document.getElementById('btnToggleCategories');
  const textExpandCategories = document.getElementById('textExpandCategories');
  if (btnToggleCategories) {
    btnToggleCategories.addEventListener('click', () => {
      const extraItems = document.querySelectorAll('.cat-extra-item');
      const isExpanded = btnToggleCategories.classList.toggle('expanded');
      extraItems.forEach(item => item.classList.toggle('is-hidden', !isExpanded));
      if (textExpandCategories) {
        textExpandCategories.textContent = isExpanded ? 'Tutup' : 'Lihat lebih';
      }
    });
  }


  /* ==========================================================================
     3. CART LOGIC (Direct Navigation to Cart Detail)
     ========================================================================== */
  const cartOpenBtn    = document.getElementById('cartOpenBtn');
  const cartCountBadge = document.getElementById('cartCountBadge');

  // Default sample items matching mockup if empty
  const defaultSampleCart = [
    {
      id: 991,
      name: 'Nama Produk',
      color: 'Biru, Kuning',
      qty: 3,
      price: 'Rp 92.000',
      rawPrice: 92000,
      image: '/images/landing/prod-hairclip.jpg',
      addedAt: Date.now() - 4000
    },
    {
      id: 992,
      name: 'Nama Produk',
      color: 'Biru, Kuning',
      qty: 3,
      price: 'Rp 92.000',
      rawPrice: 92000,
      image: '/images/landing/prod-ring.jpg',
      addedAt: Date.now() - 3000
    },
    {
      id: 993,
      name: 'Nama Produk',
      color: 'Biru, Kuning',
      qty: 3,
      price: 'Rp 92.000',
      rawPrice: 92000,
      image: '/images/landing/prod-butterfly.jpg',
      addedAt: Date.now() - 2000
    },
    {
      id: 994,
      name: 'Nama Produk',
      color: 'Biru, Kuning',
      qty: 3,
      price: 'Rp 92.000',
      rawPrice: 92000,
      image: '/images/landing/prod-keychain.jpg',
      addedAt: Date.now() - 1000
    }
  ];

  let rawCart = localStorage.getItem('maybeads_cart');
  let localCart = rawCart !== null ? JSON.parse(rawCart) : defaultSampleCart;
  if (rawCart === null) {
    localStorage.setItem('maybeads_cart', JSON.stringify(localCart));
  }

  function updateCartUI() {
    const totalItems = localCart.reduce((sum, item) => sum + (item.qty || 1), 0);
    if (cartCountBadge) {
      cartCountBadge.textContent = totalItems;
      cartCountBadge.classList.toggle('visible', totalItems > 0);
    }
  }

  updateCartUI();

  // Helper: Add product to cart and redirect directly to cart detail
  function addItemToCart(data) {
    if (!data) return;
    const rawNum = parseInt((data.price || '').replace(/[^0-9]/g, ''), 10) || 50000;
    const existing = localCart.find(it => String(it.id) === String(data.id));
    if (existing) {
      existing.qty = (existing.qty || 1) + 1;
      existing.addedAt = Date.now();
    } else {
      localCart.unshift({
        id: data.id,
        name: data.name,
        price: data.price,
        rawPrice: rawNum,
        image: data.image,
        color: data.color || 'Biru, Kuning',
        qty: 1,
        addedAt: Date.now()
      });
    }
    localStorage.setItem('maybeads_cart', JSON.stringify(localCart));
    updateCartUI();

    // Redirect langsung ke halaman detail keranjang
    const targetCartUrl = cartOpenBtn ? cartOpenBtn.getAttribute('href') : '/cart';
    window.location.href = targetCartUrl;
  }


  /* ==========================================================================
     4. MODAL POP-UP TAMBAH PRODUK KE KERANJANG
     ========================================================================== */
  const cartModalBackdrop     = document.getElementById('cartModalBackdrop');
  const btnCartModalClose     = document.getElementById('btnCartModalClose');
  const btnCartModalCancel    = document.getElementById('btnCartModalCancel');
  const btnCartModalSubmit    = document.getElementById('btnCartModalSubmit');

  const cartModalProductImg   = document.getElementById('cartModalProductImg');
  const cartModalProductName   = document.getElementById('cartModalProductName');
  const cartModalProductCat   = document.getElementById('cartModalProductCat');
  const cartModalProductPrice = document.getElementById('cartModalProductPrice');
  const cartModalColorGroup   = document.getElementById('cartModalColorGroup');
  const cartModalColorChips   = document.getElementById('cartModalColorChips');
  const cartModalSizeGroup    = document.getElementById('cartModalSizeGroup');
  const cartModalSizeChips    = document.getElementById('cartModalSizeChips');

  const btnQtyMinus           = document.getElementById('btnQtyMinus');
  const btnQtyPlus            = document.getElementById('btnQtyPlus');
  const inputQtyVal           = document.getElementById('inputQtyVal');
  const cartModalTotalVal     = document.getElementById('cartModalTotalVal');

  let currentModalProduct = null;
  let currentModalQty     = 1;
  let selectedColor       = '';
  let selectedSize        = '';

  function updateModalTotal() {
    if (!currentModalProduct) return;
    const unitPrice = parseFloat(currentModalProduct.price) || 0;
    const total = unitPrice * currentModalQty;
    if (cartModalTotalVal) {
      cartModalTotalVal.textContent = 'Rp ' + Math.round(total).toLocaleString('id-ID');
    }
  }

  function openCartModal(data) {
    if (!cartModalBackdrop || !data) return;
    currentModalProduct = data;
    currentModalQty = 1;

    // Set Foto, Nama, Kategori, Harga
    if (cartModalProductImg) {
      cartModalProductImg.src = data.image || '/images/landing/prod-hairclip.jpg';
      cartModalProductImg.alt = data.name || 'Produk';
    }
    if (cartModalProductName) cartModalProductName.textContent = data.name || '';
    if (cartModalProductCat)  cartModalProductCat.textContent = data.category || 'Aksesoris';
    
    const unitPrice = parseFloat(data.price) || 0;
    if (cartModalProductPrice) {
      cartModalProductPrice.textContent = 'Rp ' + Math.round(unitPrice).toLocaleString('id-ID');
    }

    if (inputQtyVal) inputQtyVal.value = currentModalQty;

    // Parse varian dari database
    let variants = [];
    try {
      variants = data.variants ? JSON.parse(data.variants) : [];
    } catch (e) {
      variants = [];
    }

    // Ambil daftar warna & ukuran unik
    let colors = [...new Set(variants.map(v => v.color).filter(Boolean))];
    let sizes  = [...new Set(variants.map(v => v.size).filter(Boolean))];

    if (colors.length === 0) colors = ['Default'];
    if (sizes.length === 0)  sizes  = ['All Size'];

    selectedColor = colors[0];
    selectedSize  = sizes[0];

    // Render Warna Chips
    if (cartModalColorChips) {
      cartModalColorChips.innerHTML = colors.map((c, i) => `
        <button type="button" class="cart-chip-item ${i === 0 ? 'active' : ''}" data-type="color" data-val="${c}">
          ${c}
        </button>
      `).join('');

      cartModalColorChips.querySelectorAll('.cart-chip-item').forEach(btn => {
        btn.addEventListener('click', () => {
          cartModalColorChips.querySelectorAll('.cart-chip-item').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          selectedColor = btn.dataset.val;
        });
      });
    }

    // Render Ukuran Chips
    if (cartModalSizeChips) {
      cartModalSizeChips.innerHTML = sizes.map((s, i) => `
        <button type="button" class="cart-chip-item ${i === 0 ? 'active' : ''}" data-type="size" data-val="${s}">
          ${s}
        </button>
      `).join('');

      cartModalSizeChips.querySelectorAll('.cart-chip-item').forEach(btn => {
        btn.addEventListener('click', () => {
          cartModalSizeChips.querySelectorAll('.cart-chip-item').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          selectedSize = btn.dataset.val;
        });
      });
    }

    updateModalTotal();

    cartModalBackdrop.removeAttribute('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeCartModal() {
    if (cartModalBackdrop) {
      cartModalBackdrop.setAttribute('hidden', '');
      document.body.style.overflow = '';
      currentModalProduct = null;
    }
  }

  // Stepper events
  if (btnQtyMinus) {
    btnQtyMinus.addEventListener('click', () => {
      if (currentModalQty > 1) {
        currentModalQty--;
        if (inputQtyVal) inputQtyVal.value = currentModalQty;
        updateModalTotal();
      }
    });
  }

  if (btnQtyPlus) {
    btnQtyPlus.addEventListener('click', () => {
      currentModalQty++;
      if (inputQtyVal) inputQtyVal.value = currentModalQty;
      updateModalTotal();
    });
  }

  // Close triggers
  if (btnCartModalClose)  btnCartModalClose.addEventListener('click', closeCartModal);
  if (btnCartModalCancel) btnCartModalCancel.addEventListener('click', closeCartModal);

  if (cartModalBackdrop) {
    cartModalBackdrop.addEventListener('click', (e) => {
      if (e.target === cartModalBackdrop) closeCartModal();
    });
  }

  // Submit trigger (Tambahkan ke Keranjang)
  if (btnCartModalSubmit) {
    btnCartModalSubmit.addEventListener('click', () => {
      if (!currentModalProduct) return;

      const rawUnitPrice = parseFloat(currentModalProduct.price) || 0;
      const formattedUnitPrice = 'Rp ' + Math.round(rawUnitPrice).toLocaleString('id-ID');

      // Cek apakah item serupa (id, warna, ukuran) sudah ada di keranjang
      const existing = localCart.find(it =>
        String(it.id) === String(currentModalProduct.id) &&
        it.color === selectedColor &&
        it.size === selectedSize
      );

      if (existing) {
        existing.qty = (existing.qty || 1) + currentModalQty;
        existing.addedAt = Date.now();
      } else {
        localCart.unshift({
          id: currentModalProduct.id,
          name: currentModalProduct.name,
          price: formattedUnitPrice,
          rawPrice: rawUnitPrice,
          image: currentModalProduct.image,
          color: selectedColor,
          size: selectedSize,
          qty: currentModalQty,
          addedAt: Date.now()
        });
      }

      localStorage.setItem('maybeads_cart', JSON.stringify(localCart));
      updateCartUI();
      closeCartModal();

      // Feedback toast jika SweetAlert2 tersedia, atau feedback notification
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: `"${currentModalProduct.name}" berhasil ditambahkan ke keranjang!`,
          showConfirmButton: false,
          timer: 2500,
          timerProgressBar: true
        });
      } else {
        alert(`"${currentModalProduct.name}" berhasil ditambahkan ke keranjang!`);
      }
    });
  }


  /* ==========================================================================
     5. CARD ACTIONS (Detail Link & Open Cart Modal)
     ========================================================================== */
  document.querySelectorAll('.product-card-item').forEach(card => {
    // Button Keranjang pada Card Produk (Membuka Pop Up Cart Modal)
    const cartBtn = card.querySelector('[data-action="open-cart-modal"]');
    if (cartBtn) {
      cartBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        openCartModal(card.dataset);
      });
    }
  });


  /* ==========================================================================
     6. USER ACCOUNT MENU DROPDOWN POPOVER
     ========================================================================== */
  const accountDropdownBtn = document.getElementById('accountDropdownBtn');
  const accountMenuPopover = document.getElementById('accountMenuPopover');

  if (accountDropdownBtn && accountMenuPopover) {
    accountDropdownBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = accountMenuPopover.classList.toggle('show');
      accountDropdownBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', (e) => {
      if (!accountMenuPopover.contains(e.target) && !accountDropdownBtn.contains(e.target)) {
        accountMenuPopover.classList.remove('show');
        accountDropdownBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }


  /* ==========================================================================
     7. ESC KEY LISTENER (Closes Modals)
     ========================================================================== */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeCartModal();
      if (accountMenuPopover) accountMenuPopover.classList.remove('show');
    }
  });

});
