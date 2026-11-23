/* ==========================================================================
   Product Detail JS - Maybeads
   ========================================================================== */

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import '../../css/common/sweetalert-theme.css';

window.Swal = Swal;

'use strict';

document.addEventListener('DOMContentLoaded', () => {

  /* 1. Thumbnail Switcher */
  const mainImg = document.getElementById('mainProductImage');
  const thumbs  = document.querySelectorAll('.thumb-item-btn');

  thumbs.forEach(btn => {
    btn.addEventListener('click', () => {
      thumbs.forEach(t => t.classList.remove('active'));
      btn.classList.add('active');
      const targetSrc = btn.dataset.src;
      if (mainImg && targetSrc) {
        mainImg.src = targetSrc;
      }

      // Sync active variant color pill if this thumbnail matches a variant image
      const matchingColorPill = document.querySelector(`.variant-choice-pill[data-variant-type="color"][data-variant-img="${targetSrc}"]`);
      if (matchingColorPill) {
        colorPills.forEach(p => p.classList.remove('selected'));
        matchingColorPill.classList.add('selected');
        selectedColor = matchingColorPill.dataset.variantValue || '';
      }
    });
  });

  /* 2. Variant Choice Pills */
  let selectedColor = '';
  let selectedSize  = '';

  const colorPills = document.querySelectorAll('.variant-choice-pill[data-variant-type="color"]');
  colorPills.forEach(pill => {
    pill.addEventListener('click', () => {
      colorPills.forEach(p => p.classList.remove('selected'));
      pill.classList.add('selected');
      selectedColor = pill.dataset.variantValue || '';

      // Switch main image to variant image if available
      const variantImg = pill.dataset.variantImg;
      if (mainImg && variantImg) {
        mainImg.src = variantImg;
        // Sync thumbnail active state
        thumbs.forEach(t => {
          if (t.dataset.src === variantImg) {
            thumbs.forEach(other => other.classList.remove('active'));
            t.classList.add('active');
          }
        });
      }
    });
  });

  const sizePills = document.querySelectorAll('.variant-choice-pill[data-variant-type="size"]');
  sizePills.forEach(pill => {
    pill.addEventListener('click', () => {
      sizePills.forEach(p => p.classList.remove('selected'));
      pill.classList.add('selected');
      selectedSize = pill.dataset.variantValue || '';
    });
  });

  // Select initial if available
  const initialColor = document.querySelector('.variant-choice-pill[data-variant-type="color"].selected');
  if (initialColor) selectedColor = initialColor.dataset.variantValue || '';

  const initialSize = document.querySelector('.variant-choice-pill[data-variant-type="size"].selected');
  if (initialSize) selectedSize = initialSize.dataset.variantValue || '';

  /* 3. Quantity Stepper & Price Estimation */
  const qtyInput = document.getElementById('productQtyInput');
  const btnMinus = document.getElementById('btnQtyMinus');
  const btnPlus  = document.getElementById('btnQtyPlus');
  const maxStock = parseInt(qtyInput?.dataset.maxStock || '999', 10);
  const estimatedPriceEl = document.getElementById('productEstimatedPrice');

  function updatePriceEstimation() {
    if (!qtyInput || !estimatedPriceEl) return;
    const qty = parseInt(qtyInput.value, 10) || 1;
    const unitPrice = parseFloat(qtyInput.dataset.unitPrice || '0') || 0;
    const estimatedTotal = qty * unitPrice;
    estimatedPriceEl.innerText = 'Rp ' + estimatedTotal.toLocaleString('id-ID');
  }

  if (btnMinus && qtyInput) {
    btnMinus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      if (val > 1) {
        qtyInput.value = val - 1;
        updatePriceEstimation();
      }
    });
  }

  if (btnPlus && qtyInput) {
    btnPlus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      if (val < maxStock) {
        qtyInput.value = val + 1;
        updatePriceEstimation();
      }
    });
  }

  if (qtyInput) {
    qtyInput.addEventListener('change', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      if (val < 1) val = 1;
      if (val > maxStock) val = maxStock;
      qtyInput.value = val;
      updatePriceEstimation();
    });
  }

  // Initial calculation on load
  updatePriceEstimation();

  /* 4. Cart Storage & Helpers */
  const cartCountBadge = document.getElementById('cartCountBadge');
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
        text: 'Anda perlu masuk ke akun untuk dapat menambahkan produk ke keranjang belanja.',
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

  // Trigger pop up login untuk semua elemen berkelas .btn-trigger-login-prompt
  document.querySelectorAll('.btn-trigger-login-prompt').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      openLoginPromptModal();
    });
  });

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  function updateCartBadgeUI(totalCount) {
    const count = parseInt(totalCount, 10) || 0;
    if (cartCountBadge) {
      cartCountBadge.textContent = count;
      cartCountBadge.classList.toggle('visible', count > 0);
      cartCountBadge.style.display = count > 0 ? 'inline-flex' : 'none';
    }
  }

  function addProductToCartApi(productId, qty = 1, callback = null) {
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
        product_id: productId,
        quantity: qty
      })
    })
    .then(res => res.json())
    .then(resData => {
      if (resData.success) {
        updateCartBadgeUI(resData.cartCount);
        if (typeof callback === 'function') {
          callback(true, resData);
        }
      } else if (resData.need_login) {
        openLoginPromptModal();
        if (typeof callback === 'function') callback(false, resData);
      } else {
        alert(resData.message || 'Gagal menambahkan produk ke keranjang.');
        if (typeof callback === 'function') callback(false, resData);
      }
    })
    .catch(() => {
      alert('Terjadi kesalahan jaringan.');
      if (typeof callback === 'function') callback(false);
    });
  }

  /* 5. Add To Cart Button */
  const btnAddToCart = document.getElementById('btnAddToCartMain');
  if (btnAddToCart) {
    btnAddToCart.addEventListener('click', () => {
      if (window.isUserLoggedIn === false) {
        openLoginPromptModal();
        return;
      }
      const detailBox = document.getElementById('productDetailStore') || document.getElementById('productDetailCard');
      const prodId = detailBox?.dataset?.id || detailBox?.dataset?.productId;
      const qty = parseInt(qtyInput?.value, 10) || 1;

      btnAddToCart.disabled = true;
      addProductToCartApi(prodId, qty, (success) => {
        btnAddToCart.disabled = false;
        if (success) {
          const origText = btnAddToCart.innerHTML;
          btnAddToCart.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:17px;height:17px;">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>Berhasil Ditambahkan!</span>
          `;
          btnAddToCart.style.backgroundColor = '#16a34a';

          setTimeout(() => {
            btnAddToCart.innerHTML = origText;
            btnAddToCart.style.backgroundColor = '';
          }, 1600);
        }
      });
    });
  }

  /* 6. Buy Now Button */
  const btnBuyNow = document.getElementById('btnBuyNow');
  if (btnBuyNow) {
    btnBuyNow.addEventListener('click', () => {
      if (window.isUserLoggedIn === false) {
        openLoginPromptModal();
        return;
      }
      const detailBox = document.getElementById('productDetailStore') || document.getElementById('productDetailCard');
      const prodId = detailBox?.dataset?.id || detailBox?.dataset?.productId;
      const qty = parseInt(qtyInput?.value, 10) || 1;

      btnBuyNow.disabled = true;
      addProductToCartApi(prodId, qty, (success) => {
        btnBuyNow.disabled = false;
        if (success) {
          window.location.href = '/cart';
        }
      });
    });
  }

  /* 7. Account Menu Dropdown Popover */
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

  /* 8. Related products Add to Cart button delegation */
  document.querySelectorAll('.btn-related-add-cart, .btn-quick-cart, .btn-card-cart-icon').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      if (window.isUserLoggedIn === false) {
        openLoginPromptModal();
        return;
      }
      const card = btn.closest('.product-card-item, .related-product-card');
      if (!card) return;
      const data = card.dataset;
      const id = btn.dataset.id || data.id || '1';

      btn.disabled = true;
      addProductToCartApi(id, 1, (success) => {
        btn.disabled = false;
        if (success) {
          const origHtml = btn.innerHTML;
          btn.innerHTML = `<span style="font-size:11px; font-weight:700; color:#16a34a;">✓</span>`;
          setTimeout(() => {
            btn.innerHTML = origHtml;
          }, 1200);
        }
      });
    });
  });

  /* 9. ESC Key Listener */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeLoginPromptModal();
      if (accountMenuPopover) {
        accountMenuPopover.classList.remove('show');
        if (accountDropdownBtn) accountDropdownBtn.setAttribute('aria-expanded', 'false');
      }
    }
  });

});

