/**
 * Admin Product Form Interactivity (Create, Edit, Show) - Maybead.s
 */

document.addEventListener('DOMContentLoaded', () => {

  /* =====================================================================
     1. SIDEBAR TOGGLE (Perkecil & Perbesar seperti di Dashboard)
  ===================================================================== */
  const sidebar          = document.getElementById('adminSidebar');
  const sidebarToggle    = document.getElementById('sidebarToggle');
  const backdrop         = document.getElementById('sidebarBackdrop');
  const sidebarFooter    = document.getElementById('sidebarFooter');
  const sidebarUserBadge = document.getElementById('sidebarUserBadge');
  const sidebarUserMenu  = document.getElementById('sidebarUserMenu');
  const dropdowns        = document.querySelectorAll('[data-dropdown]');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      if (window.innerWidth <= 992) {
        sidebar.classList.toggle('open');
        if (backdrop) {
          backdrop.classList.toggle('active');
        }
      } else {
        document.body.classList.toggle('sidebar-collapsed');
      }
    });
  }

  if (backdrop && sidebar) {
    backdrop.addEventListener('click', function () {
      sidebar.classList.remove('open');
      backdrop.classList.remove('active');
    });
  }

  window.addEventListener('resize', function () {
    if (window.innerWidth > 992 && sidebar) {
      sidebar.classList.remove('open');
      if (backdrop) backdrop.classList.remove('active');
    }
  });

  /* =====================================================================
     2. SIDEBAR USER MENU & HEADER DROPDOWNS
  ===================================================================== */
  function closeSidebarUserMenu() {
    if (sidebarUserMenu && sidebarFooter && sidebarUserBadge) {
      sidebarUserMenu.setAttribute('hidden', '');
      sidebarFooter.classList.remove('menu-open');
      sidebarUserBadge.setAttribute('aria-expanded', 'false');
    }
  }

  function openSidebarUserMenu() {
    if (sidebarUserMenu && sidebarFooter && sidebarUserBadge) {
      dropdowns.forEach(function (other) {
        const otherMenu = other.querySelector('.dropdown-menu');
        const otherTrigger = other.querySelector('[data-dropdown-trigger]');
        if (otherMenu) otherMenu.setAttribute('hidden', '');
        if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
      });

      sidebarUserMenu.removeAttribute('hidden');
      sidebarFooter.classList.add('menu-open');
      sidebarUserBadge.setAttribute('aria-expanded', 'true');
    }
  }

  function toggleSidebarUserMenu() {
    if (!sidebarUserMenu) return;
    const isOpen = !sidebarUserMenu.hasAttribute('hidden');
    if (isOpen) {
      closeSidebarUserMenu();
    } else {
      openSidebarUserMenu();
    }
  }

  if (sidebarUserBadge) {
    sidebarUserBadge.addEventListener('click', function (e) {
      e.stopPropagation();
      toggleSidebarUserMenu();
    });

    sidebarUserBadge.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        e.stopPropagation();
        toggleSidebarUserMenu();
      }
    });
  }

  if (sidebarUserMenu) {
    sidebarUserMenu.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  }

  dropdowns.forEach(function (drop) {
    const trigger = drop.querySelector('[data-dropdown-trigger]');
    const menu = drop.querySelector('.dropdown-menu');

    if (!trigger || !menu) return;

    trigger.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = !menu.hasAttribute('hidden');

      dropdowns.forEach(function (other) {
        if (other !== drop) {
          const otherMenu = other.querySelector('.dropdown-menu');
          const otherTrigger = other.querySelector('[data-dropdown-trigger]');
          if (otherMenu) otherMenu.setAttribute('hidden', '');
          if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
        }
      });
      closeSidebarUserMenu();

      if (isOpen) {
        menu.setAttribute('hidden', '');
        trigger.setAttribute('aria-expanded', 'false');
      } else {
        menu.removeAttribute('hidden');
        trigger.setAttribute('aria-expanded', 'true');
      }
    });
  });

  document.addEventListener('click', function (e) {
    dropdowns.forEach(function (drop) {
      const menu = drop.querySelector('.dropdown-menu');
      const trigger = drop.querySelector('[data-dropdown-trigger]');
      if (menu && !menu.hasAttribute('hidden') && !drop.contains(e.target)) {
        menu.setAttribute('hidden', '');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
      }
    });

    if (sidebarFooter && !sidebarFooter.contains(e.target)) {
      closeSidebarUserMenu();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      dropdowns.forEach(function (drop) {
        const menu = drop.querySelector('.dropdown-menu');
        const trigger = drop.querySelector('[data-dropdown-trigger]');
        if (menu && !menu.hasAttribute('hidden')) {
          menu.setAttribute('hidden', '');
          if (trigger) trigger.setAttribute('aria-expanded', 'false');
        }
      });
      closeSidebarUserMenu();
    }
  });

  // Notifikasi badge mark as read
  document.querySelectorAll('[data-mark-read]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('[data-notif-badge]').forEach(b => b.remove());
    });
  });

  /* =====================================================================
     3. FOTO PRODUK UTAMA (Upload & Drag-Drop Preview)
  ===================================================================== */
  const dropzone      = document.getElementById('uploadDropzone');
  const fileInput     = document.getElementById('productImageInput');
  const triggerBtn    = document.getElementById('btnUploadTrigger');
  const defaultPrompt = document.getElementById('dropzoneDefaultPrompt');
  const previewBox    = document.getElementById('imagePreviewContainer');
  const previewImg    = document.getElementById('imagePreviewImg');
  const removeBtn     = document.getElementById('btnRemovePreview');

  function showPreview(fileUrl) {
    if (!previewImg || !previewBox || !defaultPrompt) return;
    previewImg.src = fileUrl;
    previewBox.hidden = false;
    defaultPrompt.hidden = true;
  }

  function clearPreview() {
    if (!fileInput || !previewBox || !defaultPrompt || !previewImg) return;
    fileInput.value = '';
    previewImg.src = '';
    previewBox.hidden = true;
    defaultPrompt.hidden = false;
  }

  if (triggerBtn && fileInput) {
    triggerBtn.addEventListener('click', (e) => {
      e.preventDefault();
      fileInput.click();
    });
  }

  if (fileInput) {
    fileInput.addEventListener('change', () => {
      const file = fileInput.files?.[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          showPreview(e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  if (removeBtn) {
    removeBtn.addEventListener('click', (e) => {
      e.preventDefault();
      clearPreview();
    });
  }

  if (dropzone && fileInput) {
    ['dragenter', 'dragover'].forEach(evtName => {
      dropzone.addEventListener(evtName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add('dragover');
      });
    });

    ['dragleave', 'drop'].forEach(evtName => {
      dropzone.addEventListener(evtName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('dragover');
      });
    });

    dropzone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const file = dt?.files?.[0];
      if (file && file.type.startsWith('image/')) {
        fileInput.files = dt.files;
        const reader = new FileReader();
        reader.onload = (ev) => {
          showPreview(ev.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  /* =====================================================================
     4. FOTO PRODUK SECONDARY (Maksimal 5 Foto)
  ===================================================================== */
  const secondaryPhotosArea = document.getElementById('secondaryPhotosArea');
  const btnAddSecPhoto      = document.getElementById('btnAddSecondaryPhoto');
  const secFileInput        = document.getElementById('secondaryImageInput');
  const formElement         = document.querySelector('form');

  let secondaryFiles = []; // Array of File objects

  function renderSecondaryPreviews() {
    if (!secondaryPhotosArea) return;
    secondaryPhotosArea.innerHTML = '';

    secondaryFiles.forEach((file, index) => {
      const thumb = document.createElement('div');
      thumb.className = 'sec-photo-thumb';

      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.alt = 'Foto Tambahan ' + (index + 1);

      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'sec-photo-remove';
      removeBtn.innerHTML = '&times;';
      removeBtn.title = 'Hapus foto';
      removeBtn.addEventListener('click', () => {
        secondaryFiles.splice(index, 1);
        renderSecondaryPreviews();
      });

      thumb.appendChild(img);
      thumb.appendChild(removeBtn);
      secondaryPhotosArea.appendChild(thumb);
    });

    if (btnAddSecPhoto) {
      if (secondaryFiles.length >= 5) {
        btnAddSecPhoto.style.opacity = '0.5';
        btnAddSecPhoto.style.pointerEvents = 'none';
      } else {
        btnAddSecPhoto.style.opacity = '1';
        btnAddSecPhoto.style.pointerEvents = 'auto';
      }
    }
  }

  if (btnAddSecPhoto && secFileInput) {
    btnAddSecPhoto.addEventListener('click', (e) => {
      e.preventDefault();
      if (secondaryFiles.length >= 5) {
        alert('Maksimal 5 foto tambahan.');
        return;
      }
      secFileInput.click();
    });

    secFileInput.addEventListener('change', () => {
      if (!secFileInput.files) return;
      Array.from(secFileInput.files).forEach(file => {
        if (secondaryFiles.length < 5 && file.type.startsWith('image/')) {
          secondaryFiles.push(file);
        }
      });
      secFileInput.value = '';
      renderSecondaryPreviews();
    });
  }

  // Sebelum submit, masukkan secondaryFiles ke form
  if (formElement) {
    formElement.addEventListener('submit', (e) => {
      if (secondaryFiles.length > 0) {
        const dt = new DataTransfer();
        secondaryFiles.forEach(f => dt.items.add(f));

        // Buat atau gunakan hidden input secondary_images[]
        let realSecInput = document.getElementById('realSecondaryImagesInput');
        if (!realSecInput) {
          realSecInput = document.createElement('input');
          realSecInput.type = 'file';
          realSecInput.name = 'secondary_images[]';
          realSecInput.multiple = true;
          realSecInput.id = 'realSecondaryImagesInput';
          realSecInput.style.display = 'none';
          formElement.appendChild(realSecInput);
        }
        realSecInput.files = dt.files;
      }
    });
  }

  /* =====================================================================
     5. KATEGORI UTAMA & CUSTOM INPUT
  ===================================================================== */
  const categorySelect     = document.getElementById('category_id');
  const customCatInput     = document.getElementById('customCategoryInput');
  const btnAddSecondaryCat = document.getElementById('btnAddSecondaryCat');
  const secCatContainer    = document.getElementById('secondaryCategoriesContainer');

  const categoriesData = window.__categoriesJson || [];

  function handleCategoryChange(selectElem, customInputElem) {
    if (!selectElem || !customInputElem) return;
    if (selectElem.value === 'custom') {
      customInputElem.style.display = 'block';
      customInputElem.required = true;
      customInputElem.focus();
    } else {
      customInputElem.style.display = 'none';
      customInputElem.required = false;
      customInputElem.value = '';
    }
  }

  if (categorySelect && customCatInput) {
    categorySelect.addEventListener('change', () => {
      handleCategoryChange(categorySelect, customCatInput);
    });
    // Check initial state
    handleCategoryChange(categorySelect, customCatInput);
  }

  /* =====================================================================
     6. SECONDARY CATEGORIES (Maksimal 3)
  ===================================================================== */
  function updateAddSecondaryCatButton() {
    if (!btnAddSecondaryCat || !secCatContainer) return;
    const currentCount = secCatContainer.querySelectorAll('.sec-cat-row').length;
    if (currentCount >= 3) {
      btnAddSecondaryCat.style.opacity = '0.5';
      btnAddSecondaryCat.style.pointerEvents = 'none';
      btnAddSecondaryCat.title = 'Maksimal 3 kategori sekunder';
    } else {
      btnAddSecondaryCat.style.opacity = '1';
      btnAddSecondaryCat.style.pointerEvents = 'auto';
      btnAddSecondaryCat.title = 'Tambah kategori sekunder';
    }
  }

  function createSecondaryCategoryRow() {
    if (!secCatContainer) return;
    const currentCount = secCatContainer.querySelectorAll('.sec-cat-row').length;
    if (currentCount >= 3) {
      alert('Maksimal 3 kategori sekunder.');
      return;
    }

    const row = document.createElement('div');
    row.className = 'sec-cat-row';

    const mainWrap = document.createElement('div');
    mainWrap.className = 'sec-cat-main';

    const select = document.createElement('select');
    select.name = 'secondary_category_ids[]';
    select.className = 'form-control-select';

    let defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = 'Pilih Kategori Sekunder';
    select.appendChild(defaultOpt);

    categoriesData.forEach(cat => {
      let opt = document.createElement('option');
      opt.value = cat.id;
      opt.textContent = cat.category_name;
      select.appendChild(opt);
    });

    let customOpt = document.createElement('option');
    customOpt.value = 'custom';
    customOpt.textContent = '✏️ Custom (tulis sendiri)';
    select.appendChild(customOpt);

    const customInput = document.createElement('input');
    customInput.type = 'text';
    customInput.name = 'secondary_category_customs[]';
    customInput.className = 'form-control-input custom-cat-input';
    customInput.placeholder = 'Tulis nama kategori sekunder baru...';
    customInput.style.display = 'none';

    select.addEventListener('change', () => {
      handleCategoryChange(select, customInput);
    });

    mainWrap.appendChild(select);
    mainWrap.appendChild(customInput);

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'btn-remove-sec-cat';
    removeBtn.innerHTML = '&times;';
    removeBtn.title = 'Hapus kategori sekunder ini';
    removeBtn.addEventListener('click', () => {
      row.remove();
      updateAddSecondaryCatButton();
    });

    row.appendChild(mainWrap);
    row.appendChild(removeBtn);
    secCatContainer.appendChild(row);

    updateAddSecondaryCatButton();
  }

  if (btnAddSecondaryCat) {
    btnAddSecondaryCat.addEventListener('click', (e) => {
      e.preventDefault();
      createSecondaryCategoryRow();
    });
  }

  /* =====================================================================
     7. VARIAN PRODUK (Warna, Ukuran, Stok & Foto Varian)
  ===================================================================== */
  const btnAddVariant      = document.getElementById('btnAddVariant');
  const variantsContainer  = document.getElementById('variantsContainer');
  const duplicateAlert     = document.getElementById('variantDuplicateAlert');
  let variantIndexCounter  = 0;

  function updateTotalStockSum() {
    const stockInputs = document.querySelectorAll('.variant-stock-input');
    const mainStockInput = document.getElementById('stockInput');
    if (!mainStockInput) return;

    if (stockInputs.length > 0) {
      let sum = 0;
      stockInputs.forEach(input => {
        sum += parseInt(input.value, 10) || 0;
      });
      mainStockInput.value = sum;
    }
  }

  function checkDuplicateVariants() {
    const cards = document.querySelectorAll('.variant-item-card');
    const seen = new Map();
    let hasDuplicate = false;

    cards.forEach(card => {
      const colorInput = card.querySelector('.variant-color-input');
      const sizeInput  = card.querySelector('.variant-size-input');
      if (!colorInput || !sizeInput) return;

      const colorVal = colorInput.value.trim().toLowerCase();
      const sizeVal  = sizeInput.value.trim().toLowerCase();

      // Only check if at least one field has value
      if (colorVal || sizeVal) {
        const comboKey = colorVal + '___' + sizeVal;
        if (seen.has(comboKey)) {
          hasDuplicate = true;
          colorInput.style.borderColor = '#ef4444';
          sizeInput.style.borderColor = '#ef4444';
          const prevCard = seen.get(comboKey);
          prevCard.querySelector('.variant-color-input').style.borderColor = '#ef4444';
          prevCard.querySelector('.variant-size-input').style.borderColor = '#ef4444';
        } else {
          seen.set(comboKey, card);
          colorInput.style.borderColor = '';
          sizeInput.style.borderColor = '';
        }
      } else {
        colorInput.style.borderColor = '';
        sizeInput.style.borderColor = '';
      }
    });

    if (duplicateAlert) {
      duplicateAlert.style.display = hasDuplicate ? 'block' : 'none';
      if (hasDuplicate) {
        duplicateAlert.textContent = 'Perhatian: Terdapat kombinasi warna dan ukuran varian yang duplikat!';
      }
    }

    return hasDuplicate;
  }

  function createVariantCard(data = {}) {
    if (!variantsContainer) return;
    const idx = variantIndexCounter++;

    const card = document.createElement('div');
    card.className = 'variant-item-card';
    card.dataset.index = idx;

    // Existing ID & Image values (for Edit mode)
    const existingId = data.id || '';
    const existingImage = data.imageFilename || '';
    const existingImageUrl = data.imageUrl || '';
    const colorVal = data.color || '';
    const sizeVal = data.size || '';
    const stockVal = data.stock !== undefined ? data.stock : 0;

    card.innerHTML = `
      ${existingId ? `<input type="hidden" name="variants[${idx}][id]" value="${existingId}">` : ''}
      <input type="hidden" name="variants[${idx}][existing_image]" class="variant-existing-image-input" value="${existingImage}">
      <input type="file" name="variant_images[${idx}]" class="variant-file-input" accept="image/png,image/jpeg,image/webp" style="display:none;">

      <div class="variant-img-uploader" title="Klik untuk unggah foto varian (warna ini)">
        <div class="variant-img-placeholder" style="${existingImageUrl ? 'display:none;' : ''}">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
            <circle cx="12" cy="13" r="4"></circle>
          </svg>
          <span>Foto</span>
        </div>
        <img class="variant-img-preview" src="${existingImageUrl || ''}" alt="Foto Varian" style="${existingImageUrl ? 'display:block;' : 'display:none;'}">
        <button type="button" class="btn-clear-variant-img" title="Hapus foto varian" style="${existingImageUrl ? 'display:flex;' : 'display:none;'}">&times;</button>
      </div>

      <div class="variant-inputs-row">
        <div class="variant-field">
          <label class="variant-mini-label">Warna</label>
          <input type="text" name="variants[${idx}][color]" class="form-control-input variant-color-input"
                 placeholder="Cth: Pink, Ungu, Hitam" value="${escHtml(colorVal)}" required>
        </div>

        <div class="variant-field">
          <label class="variant-mini-label">Ukuran</label>
          <input type="text" name="variants[${idx}][size]" class="form-control-input variant-size-input"
                 placeholder="Cth: All Size, 16cm, M" value="${escHtml(sizeVal)}" required>
        </div>

        <div class="variant-field variant-field-stock">
          <label class="variant-mini-label">Stok</label>
          <input type="number" name="variants[${idx}][stock]" class="form-control-input variant-stock-input"
                 placeholder="0" min="0" value="${stockVal}" required>
        </div>
      </div>

      <button type="button" class="btn-remove-variant-row" title="Hapus varian ini">
        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3 6 5 6 21 6"></polyline>
          <path d="M19 6l-1 14H6L5 6"></path>
          <path d="M10 11v6"></path>
          <path d="M14 11v6"></path>
          <path d="M9 6V4h6v2"></path>
        </svg>
      </button>
    `;

    // Elements & event listeners inside card
    const uploaderBox    = card.querySelector('.variant-img-uploader');
    const fileInputElem  = card.querySelector('.variant-file-input');
    const previewImgElem = card.querySelector('.variant-img-preview');
    const placeholderElem= card.querySelector('.variant-img-placeholder');
    const clearImgBtn    = card.querySelector('.btn-clear-variant-img');
    const existingInput  = card.querySelector('.variant-existing-image-input');
    const colorInputElem = card.querySelector('.variant-color-input');
    const sizeInputElem  = card.querySelector('.variant-size-input');
    const stockInputElem = card.querySelector('.variant-stock-input');
    const removeRowBtn   = card.querySelector('.btn-remove-variant-row');

    // Trigger file chooser
    uploaderBox.addEventListener('click', (e) => {
      if (e.target === clearImgBtn) return;
      fileInputElem.click();
    });

    // Preview uploaded file
    fileInputElem.addEventListener('change', () => {
      const file = fileInputElem.files?.[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          previewImgElem.src = ev.target.result;
          previewImgElem.style.display = 'block';
          placeholderElem.style.display = 'none';
          clearImgBtn.style.display = 'flex';
        };
        reader.readAsDataURL(file);
      }
    });

    // Clear photo
    clearImgBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      fileInputElem.value = '';
      if (existingInput) existingInput.value = '';
      previewImgElem.src = '';
      previewImgElem.style.display = 'none';
      placeholderElem.style.display = 'flex';
      clearImgBtn.style.display = 'none';
    });

    // Duplicate check on input
    colorInputElem.addEventListener('input', checkDuplicateVariants);
    sizeInputElem.addEventListener('input', checkDuplicateVariants);

    // Stock update on input
    stockInputElem.addEventListener('input', updateTotalStockSum);

    // Remove row
    removeRowBtn.addEventListener('click', () => {
      card.remove();
      checkDuplicateVariants();
      updateTotalStockSum();
    });

    variantsContainer.appendChild(card);
    checkDuplicateVariants();
    updateTotalStockSum();
  }

  // Helper escHtml
  function escHtml(str) {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  if (btnAddVariant) {
    btnAddVariant.addEventListener('click', (e) => {
      e.preventDefault();
      createVariantCard();
      const lastCard = variantsContainer.lastElementChild;
      lastCard?.querySelector('.variant-color-input')?.focus();
    });
  }

  // Load existing variants if window.__existingVariants exists
  if (window.__existingVariants && Array.isArray(window.__existingVariants)) {
    window.__existingVariants.forEach(v => {
      createVariantCard(v);
    });
  }

  // Prevent form submit if duplicate variants exist
  if (formElement) {
    formElement.addEventListener('submit', (e) => {
      if (checkDuplicateVariants()) {
        e.preventDefault();
        alert('Terdapat kombinasi varian warna dan ukuran yang duplikat. Silakan ubah sebelum menyimpan.');
      }
    });
  }

  /* =====================================================================
     8. STOCK STEPPER (- & +)
  ===================================================================== */
  const stockInput = document.getElementById('stockInput');
  const decBtn     = document.getElementById('btnStockDec');
  const incBtn     = document.getElementById('btnStockInc');

  if (stockInput) {
    decBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      let val = parseInt(stockInput.value, 10) || 0;
      if (val > 0) stockInput.value = val - 1;
    });

    incBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      let val = parseInt(stockInput.value, 10) || 0;
      stockInput.value = val + 1;
    });
  }

  /* =====================================================================
     9. STATUS PRODUK TOGGLE
  ===================================================================== */
  const statusToggle = document.getElementById('statusProductToggle');
  const statusLabel  = document.getElementById('statusProductLabel');

  if (statusToggle && statusLabel) {
    statusToggle.addEventListener('change', () => {
      statusLabel.textContent = statusToggle.checked ? 'Aktif' : 'Nonaktif';
    });
  }

});
