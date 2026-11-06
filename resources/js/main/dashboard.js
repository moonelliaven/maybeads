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
  const inputColor    = document.getElementById('inputColor');

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

  // Color Pills
  document.querySelectorAll('.color-pill-btn[data-color]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (inputColor && filterForm) {
        // Toggle if already selected
        inputColor.value = (inputColor.value.toLowerCase() === btn.dataset.color.toLowerCase()) ? '' : btn.dataset.color;
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

  // Expand Colors Toggle
  const btnToggleColors  = document.getElementById('btnToggleColors');
  const extraColorsWrap  = document.getElementById('extraColorsWrap');
  const textExpandColors = document.getElementById('textExpandColors');
  if (btnToggleColors && extraColorsWrap) {
    btnToggleColors.addEventListener('click', () => {
      const isExpanded = btnToggleColors.classList.toggle('expanded');
      extraColorsWrap.classList.toggle('is-hidden', !isExpanded);
      if (textExpandColors) {
        textExpandColors.textContent = isExpanded ? 'Tutup' : 'Lainnya';
      }
    });
  }


  /* ==========================================================================
     3. DETAIL / QUICK VIEW MODAL
     ========================================================================== */
  const detailModalBackdrop = document.getElementById('detailModalBackdrop');
  const btnDetailClose      = document.getElementById('btnDetailClose');
  const modalProductImage   = document.getElementById('modalProductImage');
  const modalCategoryTag    = document.getElementById('modalCategoryTag');
  const modalCodeTag        = document.getElementById('modalCodeTag');
  const modalProductName    = document.getElementById('modalProductName');
  const modalProductPrice   = document.getElementById('modalProductPrice');
  const modalProductDesc    = document.getElementById('modalProductDesc');
  const modalAddToCartBtn   = document.getElementById('modalAddToCartBtn');

  let currentDetailProduct = null;

  document.querySelectorAll('.product-card-item').forEach(card => {
    const detailBtn = card.querySelector('[data-action="detail"]');
    if (detailBtn) {
      detailBtn.addEventListener('click', () => {
        const data = card.dataset;
        currentDetailProduct = data;

        if (modalProductImage) modalProductImage.src = data.image || '';
        if (modalCategoryTag) modalCategoryTag.textContent = data.category || 'Aksesoris';
        if (modalCodeTag) modalCodeTag.textContent = '#' + (data.code || '0001');
        if (modalProductName) modalProductName.textContent = data.name || '';
        if (modalProductPrice) modalProductPrice.textContent = data.price || 'Rp 0';
        if (modalProductDesc) modalProductDesc.textContent = data.desc || '';

        if (detailModalBackdrop) {
          detailModalBackdrop.classList.add('show');
          document.body.style.overflow = 'hidden';
        }
      });
    }
  });

  function closeDetailModal() {
    if (detailModalBackdrop) {
      detailModalBackdrop.classList.remove('show');
      document.body.style.overflow = '';
    }
  }

  if (btnDetailClose) btnDetailClose.addEventListener('click', closeDetailModal);
  if (detailModalBackdrop) {
    detailModalBackdrop.addEventListener('click', (e) => {
      if (e.target === detailModalBackdrop) closeDetailModal();
    });
  }


  /* ==========================================================================
     4. DELETE PRODUCT ACTION (Admin confirmation)
     ========================================================================== */
  const deleteProductForm = document.getElementById('deleteProductForm');

  document.querySelectorAll('.btn-delete-product').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const deleteUrl = btn.dataset.deleteUrl;
      const productName = btn.dataset.name || 'produk ini';

      if (!deleteUrl || !deleteProductForm) return;

      const confirmDelete = window.confirm(`Apakah Anda yakin ingin menghapus "${productName}"? Tindakan ini tidak dapat dibatalkan.`);
      if (confirmDelete) {
        deleteProductForm.action = deleteUrl;
        deleteProductForm.submit();
      }
    });
  });


  /* ==========================================================================
     5. CART POP-UP CARD (No Blur, Clean Pop-Up, Max 5 Recent Items)
     ========================================================================== */
  const cartOpenBtn          = document.getElementById('cartOpenBtn');
  const cartDropdownWrapper  = document.getElementById('cartDropdownWrapper');
  const cartPopupCard        = document.getElementById('cartPopupCard');
  const btnCloseCartPopup    = document.getElementById('btnCloseCartPopup');
  const cartPopupItemsList   = document.getElementById('cartPopupItemsList');
  const cartPopupNote        = document.getElementById('cartPopupNote');
  const cartCountBadge       = document.getElementById('cartCountBadge');

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

    if (cartPopupItemsList) {
      if (localCart.length === 0) {
        cartPopupItemsList.innerHTML = `
          <div class="cart-popup-empty">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <p>Keranjang belanja masih kosong.</p>
          </div>
        `;
        if (cartPopupNote) cartPopupNote.textContent = 'Keranjang Belanja Kosong';
      } else {
        // Show max 5 most recently added products
        const recentItems = [...localCart]
          .sort((a, b) => (b.addedAt || 0) - (a.addedAt || 0))
          .slice(0, 5);

        cartPopupItemsList.innerHTML = recentItems.map((item) => `
          <div class="cart-popup-item">
            <img src="${item.image || '/images/landing/prod-hairclip.jpg'}" alt="${item.name}" class="cart-popup-thumb">
            <div class="cart-popup-info">
              <h4 class="cart-popup-name">${item.name}</h4>
              <div class="cart-popup-meta">Warna: ${item.color || 'Biru, Kuning'}</div>
              <div class="cart-popup-meta">Jumlah: ${item.qty || 1}</div>
              <div class="cart-popup-price">${item.price || ('Rp ' + (item.rawPrice || 0).toLocaleString('id-ID'))}</div>
            </div>
          </div>
        `).join('');

        if (cartPopupNote) {
          cartPopupNote.textContent = recentItems.length >= 5
            ? 'Menampilkan 5 Produk Terakhir Ditambahkan'
            : `Menampilkan ${recentItems.length} Produk Terakhir Ditambahkan`;
        }
      }
    }
  }

  updateCartUI();

  function openCartPopup() {
    if (cartPopupCard) {
      updateCartUI();
      cartPopupCard.classList.add('show');
      if (cartOpenBtn) cartOpenBtn.setAttribute('aria-expanded', 'true');
    }
  }

  function closeCartPopup() {
    if (cartPopupCard) {
      cartPopupCard.classList.remove('show');
      if (cartOpenBtn) cartOpenBtn.setAttribute('aria-expanded', 'false');
    }
  }

  function toggleCartPopup() {
    if (cartPopupCard) {
      const isShowing = cartPopupCard.classList.contains('show');
      if (isShowing) {
        closeCartPopup();
      } else {
        openCartPopup();
      }
    }
  }

  if (cartOpenBtn) {
    cartOpenBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleCartPopup();
    });
  }

  if (btnCloseCartPopup) {
    btnCloseCartPopup.addEventListener('click', (e) => {
      e.stopPropagation();
      closeCartPopup();
    });
  }

  // Click outside to dismiss pop-up (No background blur)
  document.addEventListener('click', (e) => {
    if (cartPopupCard && cartPopupCard.classList.contains('show')) {
      if (cartDropdownWrapper && !cartDropdownWrapper.contains(e.target)) {
        closeCartPopup();
      }
    }
  });

  // Add to cart from modal
  if (modalAddToCartBtn) {
    modalAddToCartBtn.addEventListener('click', () => {
      if (currentDetailProduct) {
        const rawNum = parseInt((currentDetailProduct.price || '').replace(/[^0-9]/g, ''), 10) || 50000;
        const existing = localCart.find(it => it.id === currentDetailProduct.id);
        if (existing) {
          existing.qty = (existing.qty || 1) + 1;
          existing.addedAt = Date.now();
        } else {
          localCart.unshift({
            id: currentDetailProduct.id,
            name: currentDetailProduct.name,
            price: currentDetailProduct.price,
            rawPrice: rawNum,
            image: currentDetailProduct.image,
            color: currentDetailProduct.color || 'Biru, Kuning',
            qty: 1,
            addedAt: Date.now()
          });
        }
        localStorage.setItem('maybeads_cart', JSON.stringify(localCart));
        updateCartUI();
        closeDetailModal();
        openCartPopup();
      }
    });
  }


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
      closeDetailModal();
      closeCartPopup();
      if (accountMenuPopover) accountMenuPopover.classList.remove('show');
    }
  });

});
