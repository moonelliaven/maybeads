/* ==========================================================================
   Main Product Catalog Interactive Script - Maybead.s
   Matches Admin theme standards & interactive capabilities
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

  /* ==========================================================================
     1. SORTIR SIDEBAR TOGGLE ("Tampilkan Sortir" dari Kiri ke Kanan)
     ========================================================================== */
  const sidebar       = document.getElementById('sortirSidebar');
  const toggleBtn     = document.getElementById('sortirToggleBtn');
  const toggleText    = document.getElementById('toggleSortirText');
  const closeBtn      = document.getElementById('sidebarCloseBtn');
  const backdrop      = document.getElementById('filterBackdrop');

  // Check saved desktop state from localStorage (default: open)
  const isDesktop = () => window.innerWidth > 900;
  const savedState = localStorage.getItem('maybeads_sortir_collapsed');

  if (isDesktop() && savedState === 'true' && sidebar) {
    sidebar.classList.add('collapsed');
    if (toggleBtn) {
      toggleBtn.classList.remove('active');
      toggleBtn.setAttribute('aria-expanded', 'false');
    }
    if (toggleText) toggleText.textContent = 'Tampilkan Sortir';
  } else if (toggleText && isDesktop()) {
    toggleText.textContent = 'Sembunyikan Sortir';
  }

  function toggleSidebar() {
    if (!sidebar) return;

    if (isDesktop()) {
      const isNowCollapsed = sidebar.classList.toggle('collapsed');
      localStorage.setItem('maybeads_sortir_collapsed', isNowCollapsed ? 'true' : 'false');

      if (toggleBtn) {
        toggleBtn.classList.toggle('active', !isNowCollapsed);
        toggleBtn.setAttribute('aria-expanded', !isNowCollapsed);
      }

      if (toggleText) {
        toggleText.textContent = isNowCollapsed ? 'Tampilkan Sortir' : 'Sembunyikan Sortir';
      }
    } else {
      // Mobile slide-out drawer
      const isOpen = sidebar.classList.toggle('open-mobile');
      if (backdrop) backdrop.classList.toggle('active', isOpen);
      document.body.style.overflow = isOpen ? 'hidden' : '';
    }
  }

  function closeMobileSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove('open-mobile');
    if (backdrop) backdrop.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
  if (closeBtn) closeBtn.addEventListener('click', () => {
    if (isDesktop()) {
      sidebar.classList.add('collapsed');
      localStorage.setItem('maybeads_sortir_collapsed', 'true');
      if (toggleBtn) {
        toggleBtn.classList.remove('active');
        toggleBtn.setAttribute('aria-expanded', 'false');
      }
      if (toggleText) toggleText.textContent = 'Tampilkan Sortir';
    } else {
      closeMobileSidebar();
    }
  });

  if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);

  // Resize listener
  window.addEventListener('resize', () => {
    if (isDesktop() && sidebar && sidebar.classList.contains('open-mobile')) {
      closeMobileSidebar();
    }
  });


  /* ==========================================================================
     2. FILTER & SORTIR FORM ACTIONS
     ========================================================================== */
  const filterForm = document.getElementById('filterForm');

  if (filterForm) {
    // 1. Sort Options
    filterForm.querySelectorAll('[data-sort]').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById('formInputSort');
        if (input) input.value = btn.dataset.sort;
        filterForm.submit();
      });
    });

    // 2. Category Filter (Sidebar)
    filterForm.querySelectorAll('[data-category]').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById('formInputCategory');
        if (input) input.value = btn.dataset.category;
        filterForm.submit();
      });
    });

    // 3. Color Filter
    filterForm.querySelectorAll('[data-color]').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById('formInputColor');
        if (input) {
          // Toggle if already selected
          input.value = (input.value === btn.dataset.color) ? '' : btn.dataset.color;
        }
        filterForm.submit();
      });
    });

    // 4. Size Filter
    filterForm.querySelectorAll('[data-size]').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = document.getElementById('formInputSize');
        if (input) {
          // Toggle if already selected
          input.value = (input.value === btn.dataset.size) ? '' : btn.dataset.size;
        }
        filterForm.submit();
      });
    });

    // 5. Reset Filter Button
    const resetBtn = document.getElementById('filterResetBtn');
    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        window.location.href = window.location.pathname;
      });
    }
  }

  // 6. Quick Category Pills (Above Products)
  document.querySelectorAll('.quick-pill[data-cat]').forEach(pill => {
    pill.addEventListener('click', () => {
      const url = new URL(window.location.href);
      const catVal = pill.dataset.cat;
      if (catVal) {
        url.searchParams.set('category', catVal);
      } else {
        url.searchParams.delete('category');
      }
      url.searchParams.delete('page');
      window.location.href = url.toString();
    });
  });

  // 7. Remove Active Chip Buttons
  document.querySelectorAll('.btn-remove-chip[data-remove]').forEach(btn => {
    btn.addEventListener('click', () => {
      const paramToRemove = btn.dataset.remove;
      const url = new URL(window.location.href);
      url.searchParams.delete(paramToRemove);
      url.searchParams.delete('page');
      window.location.href = url.toString();
    });
  });

  // 8. Search Clear Button
  const searchClearBtn = document.getElementById('searchClearBtn');
  if (searchClearBtn) {
    searchClearBtn.addEventListener('click', () => {
      const url = new URL(window.location.href);
      url.searchParams.delete('search');
      url.searchParams.delete('page');
      window.location.href = url.toString();
    });
  }


  /* ==========================================================================
     3. TOP HEADER LANGUAGE SELECTOR
     ========================================================================== */
  const langToggleBtn = document.getElementById('langToggleBtn');
  const langMenu      = document.getElementById('langMenu');
  const currentLang   = document.getElementById('currentLangText');

  if (langToggleBtn && langMenu) {
    langToggleBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isShow = langMenu.classList.toggle('show');
      langToggleBtn.setAttribute('aria-expanded', isShow);
    });

    langMenu.querySelectorAll('.lang-item').forEach(item => {
      item.addEventListener('click', () => {
        const lang = item.dataset.lang;
        if (currentLang) currentLang.textContent = lang;
        langMenu.querySelectorAll('.lang-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        langMenu.classList.remove('show');
        showToast('Bahasa Diubah', `Bahasa berhasil disetel ke ${lang === 'ID' ? 'Bahasa Indonesia' : 'English'}.`);
      });
    });

    document.addEventListener('click', (e) => {
      if (!langToggleBtn.contains(e.target) && !langMenu.contains(e.target)) {
        langMenu.classList.remove('show');
        langToggleBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }


  /* ==========================================================================
     4. NAVBAR USER MENU DROPDOWN
     ========================================================================== */
  const userMenuBtn = document.getElementById('userMenuBtn');
  const userMenu    = document.getElementById('userMenu');

  if (userMenuBtn && userMenu) {
    userMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isShow = userMenu.classList.toggle('show');
      userMenuBtn.setAttribute('aria-expanded', isShow);
    });

    document.addEventListener('click', (e) => {
      if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target)) {
        userMenu.classList.remove('show');
        userMenuBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }


  /* ==========================================================================
     5. CART SYSTEM & DRAWER (Keranjang Belanja)
     ========================================================================== */
  const cartOpenBtn    = document.getElementById('cartOpenBtn');
  const cartCloseBtn   = document.getElementById('cartCloseBtn');
  const cartDrawer     = document.getElementById('cartDrawer');
  const cartOverlay    = document.getElementById('cartOverlay');
  const cartItemsList  = document.getElementById('cartItemsList');
  const cartCountBadge = document.getElementById('cartCountBadge');
  const cartSubtotal   = document.getElementById('cartSubtotalText');
  const btnCheckout    = document.getElementById('btnCheckout');

  // Simple In-Memory / LocalStorage Cart
  let cart = JSON.parse(localStorage.getItem('maybeads_cart_items') || '[]');

  function saveCart() {
    localStorage.setItem('maybeads_cart_items', JSON.stringify(cart));
    updateCartUI();
  }

  function updateCartUI() {
    const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
    if (cartCountBadge) cartCountBadge.textContent = totalQty;

    if (!cartItemsList) return;

    if (cart.length === 0) {
      cartItemsList.innerHTML = `
        <div class="cart-empty-message">
          <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
          <h4>Keranjang Anda Kosong</h4>
          <p>Jelajahi produk di atas dan tambahkan aksesoris Y2K favorit Anda.</p>
        </div>
      `;
      if (cartSubtotal) cartSubtotal.textContent = 'Rp 0';
      return;
    }

    let subtotal = 0;
    cartItemsList.innerHTML = cart.map((item, idx) => {
      subtotal += item.price * item.qty;
      const formattedItemPrice = 'Rp ' + Number(item.price).toLocaleString('id-ID');
      return `
        <div class="cart-item-card" data-idx="${idx}">
          <img src="${item.img}" alt="${item.name}" class="cart-item-img">
          <div class="cart-item-info">
            <span class="cart-item-name">${item.name}</span>
            <span class="cart-item-price">${formattedItemPrice}</span>
            <div class="cart-item-qty-row">
              <span class="cart-item-qty">Jumlah: <strong>${item.qty}</strong></span>
              <button type="button" class="cart-item-remove" data-remove-idx="${idx}">Hapus</button>
            </div>
          </div>
        </div>
      `;
    }).join('');

    if (cartSubtotal) {
      cartSubtotal.textContent = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
    }

    // Attach remove listeners
    cartItemsList.querySelectorAll('[data-remove-idx]').forEach(btn => {
      btn.addEventListener('click', () => {
        const idx = parseInt(btn.dataset.removeIdx);
        cart.splice(idx, 1);
        saveCart();
        showToast('Produk Dihapus', 'Item telah dihapus dari keranjang.');
      });
    });
  }

  function openCart() {
    if (cartDrawer) cartDrawer.classList.add('open');
    if (cartOverlay) cartOverlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeCart() {
    if (cartDrawer) cartDrawer.classList.remove('open');
    if (cartOverlay) cartOverlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  if (cartOpenBtn) cartOpenBtn.addEventListener('click', openCart);
  if (cartCloseBtn) cartCloseBtn.addEventListener('click', closeCart);
  if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

  if (btnCheckout) {
    btnCheckout.addEventListener('click', () => {
      if (cart.length === 0) {
        showToast('Keranjang Kosong', 'Pilih produk terlebih dahulu sebelum checkout.');
        return;
      }
      showToast('Menuju Checkout', 'Fitur checkout payment gateway terintegrasi siap memproses pesanan.');
    });
  }

  // Initial cart UI
  updateCartUI();

  // Add to cart from Product Cards
  document.querySelectorAll('.product-card').forEach(card => {
    const addBtn = card.querySelector('.btn-add-cart');
    if (addBtn) {
      addBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const id    = card.dataset.id;
        const name  = card.dataset.name;
        const img   = card.dataset.image;
        const price = parseInt(card.dataset.price.replace(/[^0-9]/g, '')) || 0;

        const existing = cart.find(i => i.id === id);
        if (existing) {
          existing.qty += 1;
        } else {
          cart.push({ id, name, img, price, qty: 1 });
        }

        saveCart();
        showToast('Berhasil Ditambahkan! ✦', `${name} masuk ke keranjang belanja.`);
      });
    }
  });


  /* ==========================================================================
     6. QUICK VIEW MODAL
     ========================================================================== */
  const quickModal    = document.getElementById('quickViewModal');
  const quickBackdrop = document.getElementById('quickViewBackdrop');
  const modalCloseBtn = document.getElementById('modalCloseBtn');

  const mImg       = document.getElementById('modalImg');
  const mCat       = document.getElementById('modalCategory');
  const mId        = document.getElementById('modalId');
  const mTitle     = document.getElementById('modalTitle');
  const mPrice     = document.getElementById('modalPrice');
  const mStock     = document.getElementById('modalStock');
  const mDesc      = document.getElementById('modalDesc');
  const mColorList = document.getElementById('modalColorList');
  const mSizeList  = document.getElementById('modalSizeList');
  const mColorRow  = document.getElementById('modalColorRow');
  const mSizeRow   = document.getElementById('modalSizeRow');
  const mQtyInput  = document.getElementById('modalQtyInput');
  const mQtyMinus  = document.getElementById('modalQtyMinus');
  const mQtyPlus   = document.getElementById('modalQtyPlus');
  const mAddToCart = document.getElementById('modalAddToCartBtn');

  let currentModalProduct = null;

  function openQuickView(card) {
    currentModalProduct = {
      id: card.dataset.id,
      name: card.dataset.name,
      price: card.dataset.price,
      numPrice: parseInt(card.dataset.price.replace(/[^0-9]/g, '')) || 0,
      image: card.dataset.image,
      category: card.dataset.category,
      stock: parseInt(card.dataset.stock) || 0,
      desc: card.dataset.desc,
      colors: card.dataset.colors ? card.dataset.colors.split(',').map(s => s.trim()).filter(Boolean) : [],
      sizes: card.dataset.sizes ? card.dataset.sizes.split(',').map(s => s.trim()).filter(Boolean) : [],
    };

    if (mImg) mImg.src = currentModalProduct.image;
    if (mCat) mCat.textContent = currentModalProduct.category;
    if (mId) mId.textContent = '#' + String(currentModalProduct.id).padStart(4, '0');
    if (mTitle) mTitle.textContent = currentModalProduct.name;
    if (mPrice) mPrice.textContent = currentModalProduct.price;
    if (mDesc) mDesc.textContent = currentModalProduct.desc;

    if (mStock) {
      if (currentModalProduct.stock > 5) {
        mStock.textContent = `Stok Tersedia (${currentModalProduct.stock})`;
        mStock.className = 'modal-stock-badge in';
      } else if (currentModalProduct.stock > 0) {
        mStock.textContent = `Sisa Terbatas (${currentModalProduct.stock})`;
        mStock.className = 'modal-stock-badge low';
      } else {
        mStock.textContent = 'Stok Habis';
        mStock.className = 'modal-stock-badge out';
      }
    }

    // Colors
    if (mColorList && mColorRow) {
      if (currentModalProduct.colors.length > 0) {
        mColorRow.style.display = 'block';
        mColorList.innerHTML = currentModalProduct.colors.map((c, i) =>
          `<button type="button" class="modal-tag ${i === 0 ? 'active' : ''}">${c}</button>`
        ).join('');

        mColorList.querySelectorAll('.modal-tag').forEach(tag => {
          tag.addEventListener('click', () => {
            mColorList.querySelectorAll('.modal-tag').forEach(t => t.classList.remove('active'));
            tag.classList.add('active');
          });
        });
      } else {
        mColorRow.style.display = 'none';
      }
    }

    // Sizes
    if (mSizeList && mSizeRow) {
      if (currentModalProduct.sizes.length > 0) {
        mSizeRow.style.display = 'block';
        mSizeList.innerHTML = currentModalProduct.sizes.map((s, i) =>
          `<button type="button" class="modal-tag ${i === 0 ? 'active' : ''}">${s}</button>`
        ).join('');

        mSizeList.querySelectorAll('.modal-tag').forEach(tag => {
          tag.addEventListener('click', () => {
            mSizeList.querySelectorAll('.modal-tag').forEach(t => t.classList.remove('active'));
            tag.classList.add('active');
          });
        });
      } else {
        mSizeRow.style.display = 'none';
      }
    }

    if (mQtyInput) mQtyInput.value = 1;

    if (quickBackdrop) quickBackdrop.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeQuickView() {
    if (quickBackdrop) quickBackdrop.classList.remove('show');
    document.body.style.overflow = '';
  }

  // Bind Quick View triggers
  document.querySelectorAll('.product-card').forEach(card => {
    const qvBtn = card.querySelector('.btn-quick-view');
    const media = card.querySelector('.product-card-media');

    if (qvBtn) {
      qvBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        openQuickView(card);
      });
    }

    if (media) {
      media.addEventListener('click', (e) => {
        if (!e.target.closest('.card-hover-actions')) {
          openQuickView(card);
        }
      });
    }
  });

  if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeQuickView);
  if (quickBackdrop) {
    quickBackdrop.addEventListener('click', (e) => {
      if (e.target === quickBackdrop) closeQuickView();
    });
  }

  // Modal Qty
  if (mQtyMinus && mQtyInput) {
    mQtyMinus.addEventListener('click', () => {
      let val = parseInt(mQtyInput.value) || 1;
      if (val > 1) mQtyInput.value = val - 1;
    });
  }

  if (mQtyPlus && mQtyInput) {
    mQtyPlus.addEventListener('click', () => {
      let val = parseInt(mQtyInput.value) || 1;
      if (val < 99) mQtyInput.value = val + 1;
    });
  }

  // Modal Add to Cart
  if (mAddToCart) {
    mAddToCart.addEventListener('click', () => {
      if (!currentModalProduct) return;
      const qty = parseInt(mQtyInput?.value) || 1;

      const existing = cart.find(i => i.id === currentModalProduct.id);
      if (existing) {
        existing.qty += qty;
      } else {
        cart.push({
          id: currentModalProduct.id,
          name: currentModalProduct.name,
          img: currentModalProduct.image,
          price: currentModalProduct.numPrice,
          qty: qty
        });
      }

      saveCart();
      closeQuickView();
      showToast('Berhasil Ditambahkan! ✦', `${qty}x ${currentModalProduct.name} ditambahkan ke keranjang.`);
    });
  }

  // ESC key to close modal & drawer
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeQuickView();
      closeCart();
      closeMobileSidebar();
      if (userMenu) userMenu.classList.remove('show');
      if (langMenu) langMenu.classList.remove('show');
    }
  });


  /* ==========================================================================
     7. TOAST NOTIFICATION UTILITY
     ========================================================================== */
  const toast       = document.getElementById('toastNotification');
  const toastTitle   = document.getElementById('toastTitle');
  const toastMessage = document.getElementById('toastMessage');
  let toastTimer    = null;

  function showToast(title, message) {
    if (!toast) return;

    if (toastTitle) toastTitle.textContent = title;
    if (toastMessage) toastMessage.textContent = message;

    toast.classList.add('show');

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toast.classList.remove('show');
    }, 3200);
  }

});
