/* ==========================================================================
   Main Product Catalog Interactive Script - Maybeads
   Handles Filter Toggle, Sorting, Modal, Cart Drawer, and Popover
   ========================================================================== */

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import '../../css/common/sweetalert-theme.css';

window.Swal = Swal;

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
  const filterForm      = document.getElementById('filterForm');
  const selectSort      = document.getElementById('selectSort');
  const selectCategory  = document.getElementById('selectCategory');
  const inputSort       = document.getElementById('inputSort');
  const inputCategory   = document.getElementById('inputCategory');

  // Mobile Dropdown Sort Change
  if (selectSort && filterForm) {
    selectSort.addEventListener('change', () => {
      if (inputSort) inputSort.value = selectSort.value;
      filterForm.submit();
    });
  }

  // Mobile Dropdown Category Change
  if (selectCategory && filterForm) {
    selectCategory.addEventListener('change', () => {
      if (inputCategory) inputCategory.value = selectCategory.value;
      filterForm.submit();
    });
  }

  // Desktop Sort Radio Options
  document.querySelectorAll('.sort-radio-option[data-sort]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (inputSort && filterForm) {
        inputSort.value = btn.dataset.sort;
        filterForm.submit();
      }
    });
  });

  // Desktop Category Options
  document.querySelectorAll('.category-option-item[data-category]').forEach(btn => {
    btn.addEventListener('click', () => {
      if (inputCategory && filterForm) {
        inputCategory.value = btn.dataset.category;
        filterForm.submit();
      }
    });
  });

  // Expand Categories Toggle (Desktop)
  const btnToggleCategories = document.getElementById('btnToggleCategories');
  if (btnToggleCategories) {
    btnToggleCategories.addEventListener('click', () => {
      const extraItems = document.querySelectorAll('.cat-extra-item');
      const isExpanded = btnToggleCategories.classList.toggle('expanded');
      extraItems.forEach(item => item.classList.toggle('is-hidden', !isExpanded));
      const toggleText = btnToggleCategories.querySelector('.toggle-text');
      if (toggleText) {
        toggleText.textContent = isExpanded ? 'Tutup' : 'Lihat lebih banyak';
      }
    });
  }


  /* ==========================================================================
     3. CART LOGIC (Database Synchronized)
     ========================================================================== */
  const cartOpenBtn    = document.getElementById('cartOpenBtn');
  const cartCountBadge = document.getElementById('cartCountBadge');
  const csrfToken      = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  function updateCartBadgeUI(totalCount) {
    const count = parseInt(totalCount, 10) || 0;
    if (cartCountBadge) {
      cartCountBadge.textContent = count;
      cartCountBadge.classList.toggle('visible', count > 0);
      cartCountBadge.style.display = count > 0 ? 'inline-flex' : 'none';
    }
  }

  // Helper: Add product to cart via database API
  function addItemToCart(data, qty = 1, redirect = true) {
    if (!data || !data.id) return;
    if (window.isUserLoggedIn === false) {
      openLoginPromptModal();
      return;
    }

    fetch('/cart/add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        product_id: data.id,
        quantity: qty
      })
    })
    .then(res => res.json())
    .then(resData => {
      if (resData.success) {
        updateCartBadgeUI(resData.cartCount);
        if (redirect) {
          const targetCartUrl = cartOpenBtn ? cartOpenBtn.getAttribute('href') : '/cart';
          window.location.href = targetCartUrl;
        } else {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              position: 'top',
              backdrop: false,
              icon: 'success',
              title: 'berhasil menambahkan produk ke keranjang',
              showConfirmButton: false,
              timer: 1600,
              customClass: {
                popup: 'swal-compact-top-popup',
                title: 'swal-compact-top-title'
              }
            });
          }
        }
      } else if (resData.need_login) {
        openLoginPromptModal();
      } else {
        alert(resData.message || 'Gagal menambahkan produk ke keranjang.');
      }
    })
    .catch(() => {
      alert('Terjadi kesalahan jaringan.');
    });
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

  // Submit trigger (Tambahkan ke Keranjang via API Database)
  if (btnCartModalSubmit) {
    btnCartModalSubmit.addEventListener('click', () => {
      if (window.isUserLoggedIn === false) {
        closeCartModal();
        openLoginPromptModal();
        return;
      }

      if (!currentModalProduct) return;

      const prodName = currentModalProduct.name || 'Produk';
      const prodId = currentModalProduct.id;
      const qty = currentModalQty;

      btnCartModalSubmit.disabled = true;

      fetch('/cart/add', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          product_id: prodId,
          quantity: qty
        })
      })
      .then(res => res.json())
      .then(resData => {
        if (resData.success) {
          updateCartBadgeUI(resData.cartCount);
          closeCartModal();

          if (typeof Swal !== 'undefined') {
            Swal.fire({
              position: 'top',
              backdrop: false,
              icon: 'success',
              title: 'berhasil menambahkan produk ke keranjang',
              showConfirmButton: false,
              timer: 1600,
              customClass: {
                popup: 'swal-compact-top-popup',
                title: 'swal-compact-top-title'
              }
            });
          } else {
            alert('berhasil menambahkan produk ke keranjang');
          }
        } else if (resData.need_login) {
          closeCartModal();
          openLoginPromptModal();
        } else {
          alert(resData.message || 'Gagal menambahkan produk.');
        }
      })
      .catch(() => {
        alert('Terjadi kesalahan jaringan.');
      })
      .finally(() => {
        btnCartModalSubmit.disabled = false;
      });
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
     7. POP UP LOGIN DIPERLUKAN ("Silahkan login terlebih dahulu" - Kembali / Login)
     ========================================================================== */
  const loginPromptModal = document.getElementById('loginPromptModal');
  const btnCancelLoginPrompt = document.getElementById('btnCancelLoginPrompt');

  function openLoginPromptModal() {
    if (typeof window.openLoginRequiredModal === 'function') {
      window.openLoginRequiredModal(window.location.href);
      return;
    }

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Silahkan Login Terlebih Dahulu',
        text: 'Anda perlu masuk ke akun untuk dapat menggunakan fitur ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#244173',
        cancelButtonColor: '#5c5656',
        confirmButtonText: 'Login',
        cancelButtonText: 'Kembali',
        reverseButtons: true,
        customClass: {
          popup: 'maybeads-swal-popup',
          confirmButton: 'swal-btn-navy'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '/login';
        }
      });
      return;
    }

    if (loginPromptModal) {
      loginPromptModal.removeAttribute('hidden');
      document.body.style.overflow = 'hidden';
    } else {
      if (confirm('Silahkan login terlebih dahulu\n\nKlik OK untuk Login, atau Cancel untuk Kembali.')) {
        window.location.href = '/login';
      }
    }
  }

  function closeLoginPromptModal() {
    if (loginPromptModal) {
      loginPromptModal.setAttribute('hidden', '');
      document.body.style.overflow = '';
    }
  }

  if (btnCancelLoginPrompt) {
    btnCancelLoginPrompt.addEventListener('click', closeLoginPromptModal);
  }

  if (loginPromptModal) {
    loginPromptModal.addEventListener('click', (e) => {
      if (e.target === loginPromptModal) {
        closeLoginPromptModal();
      }
    });
  }

  // Delegasi klik semua tombol bertanda .btn-trigger-login-prompt
  document.querySelectorAll('.btn-trigger-login-prompt').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      openLoginPromptModal();
    });
  });

  /* ==========================================================================
     8. ESC KEY LISTENER (Closes Modals)
     ========================================================================== */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeCartModal();
      closeLoginPromptModal();
      if (accountMenuPopover) accountMenuPopover.classList.remove('show');
    }
  });

});
