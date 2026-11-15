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

  /* 3. Quantity Stepper */
  const qtyInput = document.getElementById('productQtyInput');
  const btnMinus = document.getElementById('btnQtyMinus');
  const btnPlus  = document.getElementById('btnQtyPlus');
  const maxStock = parseInt(qtyInput?.dataset.maxStock || '999', 10);

  if (btnMinus && qtyInput) {
    btnMinus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      if (val > 1) {
        qtyInput.value = val - 1;
      }
    });
  }

  if (btnPlus && qtyInput) {
    btnPlus.addEventListener('click', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      if (val < maxStock) {
        qtyInput.value = val + 1;
      }
    });
  }

  if (qtyInput) {
    qtyInput.addEventListener('change', () => {
      let val = parseInt(qtyInput.value, 10) || 1;
      if (val < 1) val = 1;
      if (val > maxStock) val = maxStock;
      qtyInput.value = val;
    });
  }

  /* 4. Cart Storage & Helpers */
  const cartCountBadge = document.getElementById('cartCountBadge');
  const loginPromptModal = document.getElementById('loginPromptModal');
  const btnCancelLoginPrompt = document.getElementById('btnCancelLoginPrompt');

  function openLoginPromptModal() {
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

  function getLocalCart() {
    try {
      return JSON.parse(localStorage.getItem('maybeads_cart') || '[]');
    } catch (e) {
      return [];
    }
  }

  function saveLocalCart(cart) {
    localStorage.setItem('maybeads_cart', JSON.stringify(cart));
    updateCartCountBadge();
  }

  function updateCartCountBadge() {
    if (!cartCountBadge) return;
    const cart = getLocalCart();
    const count = cart.reduce((sum, it) => sum + (it.qty || 1), 0);
    cartCountBadge.textContent = count;
    cartCountBadge.style.display = count > 0 ? 'inline-flex' : 'none';
  }

  updateCartCountBadge();

  function addCurrentProductToCart() {
    if (window.isUserLoggedIn === false) {
      openLoginPromptModal();
      return false;
    }

    const detailBox = document.getElementById('productDetailStore');
    if (!detailBox) return;

    const data = detailBox.dataset;
    const qty  = parseInt(qtyInput?.value, 10) || 1;
    const rawPrice = parseInt(data.rawPrice, 10) || 0;

    let variantLabel = [];
    if (selectedColor) variantLabel.push(selectedColor);
    if (selectedSize) variantLabel.push(selectedSize);
    const variantStr = variantLabel.length ? variantLabel.join(', ') : (data.defaultVariant || 'Standard');

    const cart = getLocalCart();
    const existing = cart.find(it => String(it.id) === String(data.id) && (it.color || '') === variantStr);

    if (existing) {
      existing.qty = (existing.qty || 1) + qty;
      existing.addedAt = Date.now();
    } else {
      cart.unshift({
        id: data.id,
        name: data.name,
        price: data.priceFormatted,
        rawPrice: rawPrice,
        image: data.image,
        color: variantStr,
        qty: qty,
        checked: true,
        addedAt: Date.now()
      });
    }

    saveLocalCart(cart);
  }

  /* 5. Add To Cart Button */
  const btnAddToCart = document.getElementById('btnAddToCartMain');
  if (btnAddToCart) {
    btnAddToCart.addEventListener('click', () => {
      if (window.isUserLoggedIn === false) {
        openLoginPromptModal();
        return;
      }
      addCurrentProductToCart();

      // UI Feedback on button
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
      addCurrentProductToCart();
      window.location.href = '/cart';
    });
  }

  /* 7. Related products Add to Cart button delegation */
  document.querySelectorAll('.btn-related-add-cart').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      if (window.isUserLoggedIn === false) {
        openLoginPromptModal();
        return;
      }
      const card = btn.closest('.product-card-item');
      if (!card) return;
      const data = card.dataset;
      const rawPrice = parseInt((data.price || '').replace(/[^0-9]/g, ''), 10) || 50000;

      const cart = getLocalCart();
      const existing = cart.find(it => String(it.id) === String(data.id));

      if (existing) {
        existing.qty = (existing.qty || 1) + 1;
        existing.addedAt = Date.now();
      } else {
        cart.unshift({
          id: data.id,
          name: data.name,
          price: data.price,
          rawPrice: rawPrice,
          image: data.image,
          color: data.color || 'Biru, Kuning',
          qty: 1,
          checked: true,
          addedAt: Date.now()
        });
      }

      saveLocalCart(cart);

      btn.textContent = '✓ Ditambahkan';
      setTimeout(() => {
        btn.textContent = '+ Keranjang';
      }, 1200);
    });
  });

  /* 8. ESC Key Listener */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeLoginPromptModal();
    }
  });

});

