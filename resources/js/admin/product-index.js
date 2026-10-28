/* ==========================================================================
   Product Index JS - Maybead.s
   ========================================================================== */

'use strict';

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

  // Tutup dropdown saat klik di luar
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

  // Tutup dengan tombol Escape
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

  // Tandai dibaca (notifikasi)
  document.querySelectorAll('[data-mark-read]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('[data-notif-badge]').forEach(b => b.remove());
    });
  });

  /* =====================================================================
     4. PRODUCT DATA — semua produk dari server (data-products attribute)
  ===================================================================== */
  const productsRaw  = document.getElementById('productDataStore')?.dataset?.products || '[]';
  const categoriesRaw = document.getElementById('productDataStore')?.dataset?.categories || '[]';
  let allProducts    = [];
  let allCategories  = [];

  try {
    allProducts   = JSON.parse(productsRaw);
    allCategories = JSON.parse(categoriesRaw);
  } catch(e) { console.warn('Product data parse error', e); }

  /* =====================================================================
     5. STATE
  ===================================================================== */
  let state = {
    search:   '',
    category: 'all',   // 'all' or category id string
    layout:   'table', // 'table' | 'grid'
    page:     1,
    perPage:  8,
  };

  /* =====================================================================
     6. RENDER HELPERS
  ===================================================================== */
  function formatPrice(val) {
    const num = parseFloat(val) || 0;
    return 'Rp ' + num.toLocaleString('id-ID');
  }

  function getProductImageUrl(imgPath) {
    if (!imgPath) return '';
    if (imgPath.startsWith('http://') || imgPath.startsWith('https://') || imgPath.startsWith('/')) {
      return imgPath;
    }
    if (imgPath.startsWith('product/')) {
      return `/storage/${imgPath}`;
    }
    return `/storage/product/${imgPath}`;
  }

  function thumbHtml(imgPath) {
    const src = getProductImageUrl(imgPath);
    if (src) {
      return `<img src="${src}" alt="foto" class="product-thumb" loading="lazy" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'product-thumb-placeholder\\'><svg viewBox=\\'0 0 24 24\\' fill=\\'none\\' stroke=\\'currentColor\\' stroke-width=\\'1.5\\' stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\'><rect x=\\'3\\' y=\\'3\\' width=\\'18\\' height=\\'18\\' rx=\\'2\\'/><circle cx=\\'8.5\\' cy=\\'8.5\\' r=\\'1.5\\'/><polyline points=\\'21 15 16 10 5 21\\'/></svg></div>';">`;
    }
    return `<div class="product-thumb-placeholder">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="18" height="18" rx="2"/>
        <circle cx="8.5" cy="8.5" r="1.5"/>
        <polyline points="21 15 16 10 5 21"/>
      </svg>
    </div>`;
  }

  function stockBadge(stock) {
    const low = parseInt(stock, 10) <= 5;
    return `<span class="${low ? 'stock-low' : ''}">${stock}</span>`;
  }

  function editUrl(id) { return `/admin/product/${id}/edit`; }
  function deleteFormHtml(id) {
    return `<form method="POST" action="/admin/product/${id}" class="delete-form" style="display:inline">
      <input type="hidden" name="_token" value="${window.__csrf || ''}">
      <input type="hidden" name="_method" value="DELETE">
      <button type="button" class="btn-icon-action danger" data-delete-id="${id}" title="Hapus">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
        </svg>
      </button>
    </form>`;
  }

  /* =====================================================================
     7. FILTER + PAGINATION
  ===================================================================== */
  function filteredProducts() {
    const q = state.search.toLowerCase().trim();
    return allProducts.filter(p => {
      // Filter kategori
      const catMatch = state.category === 'all' || String(p.category_id) === String(state.category);
      if (!catMatch) return false;
      // Filter pencarian (nama produk atau nama kategori)
      if (!q) return true;
      const nameMatch = (p.product_name || '').toLowerCase().includes(q);
      const catName   = (p.category_name || '').toLowerCase();
      const catNameMatch = catName.includes(q);
      return nameMatch || catNameMatch;
    });
  }

  function paginatedProducts(filtered) {
    const start = (state.page - 1) * state.perPage;
    return filtered.slice(start, start + state.perPage);
  }

  /* =====================================================================
     8. RENDER TABLE
  ===================================================================== */
  function renderTableRows(products) {
    if (!products.length) return renderEmpty();
    return products.map((p, i) => {
      const isActive = p.status !== false && p.status !== 0 && p.status !== '0';
      const statusText = isActive ? 'Aktif' : 'Nonaktif';
      return `
      <tr data-id="${p.id}">
        <td class="cb-col"><input type="checkbox" class="tbl-check" aria-label="Pilih ${escHtml(p.product_name)}"></td>
        <td class="col-id">${p.id}</td>
        <td class="col-name">${escHtml(p.product_name)}</td>
        <td class="col-category"><span class="cat-badge">${escHtml(p.category_name || 'Tanpa Kategori')}</span></td>
        <td class="col-photo">${thumbHtml(p.image)}</td>
        <td class="col-stock">${stockBadge(p.stock)}</td>
        <td class="col-status ${isActive ? 'status-active' : 'status-inactive'}">${statusText}</td>
        <td class="col-action">
          <div class="action-btns">
            <a href="/admin/product/${p.id}" class="btn-detail">Detail</a>
            <a href="${editUrl(p.id)}" class="btn-icon-action" title="Edit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
            </a>
            ${deleteFormHtml(p.id)}
          </div>
        </td>
      </tr>
    `;
    }).join('');
  }

  /* =====================================================================
     9. RENDER GRID CARDS
  ===================================================================== */
  function renderGridCards(products) {
    if (!products.length) return renderEmpty();
    return products.map(p => {
      const idPadded = String(p.id).padStart(4, '0');
      const imgSrc  = getProductImageUrl(p.image);
      const imgHtml = imgSrc
        ? `<img src="${imgSrc}" alt="${escHtml(p.product_name)}" loading="lazy">`
        : `<div class="img-placeholder">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
            </svg>
           </div>`;
      const desc = escHtml(p.description || '');
      const isActive = p.status !== false && p.status !== 0 && p.status !== '0';
      const statusText = isActive ? 'Aktif' : 'Nonaktif';

      return `
        <div class="product-card" data-id="${p.id}">
          <div class="product-card-img">
            <span class="product-card-id-badge">${idPadded}</span>
            <span class="product-card-status-badge ${isActive ? 'active' : 'inactive'}">${statusText}</span>
            ${imgHtml}
          </div>
          <div class="product-card-body">
            <div class="product-card-cat-label">${escHtml(p.category_name || 'Tanpa Kategori')}</div>
            <div class="product-card-name">${escHtml(p.product_name)}</div>
            <div class="product-card-desc">${desc || '<span style="color:#c4cdd8;font-style:italic">Tidak ada deskripsi</span>'}</div>
            <div class="product-card-actions">
              <a href="/admin/product/${p.id}" class="btn-detail">Detail</a>
              <a href="${editUrl(p.id)}" class="btn-icon-action" title="Edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </a>
              ${deleteFormHtml(p.id)}
            </div>
          </div>
        </div>
      `;
    }).join('');
  }


  function renderEmpty() {
    return `<div class="product-empty" style="grid-column:1/-1">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <div class="product-empty-title">Produk tidak ditemukan</div>
      <div class="product-empty-sub">Coba ubah kata kunci atau filter kategori</div>
    </div>`;
  }

  /* =====================================================================
     10. RENDER PAGINATION
  ===================================================================== */
  function renderPagination(total) {
    const totalPages = Math.max(1, Math.ceil(total / state.perPage));
    const start = (state.page - 1) * state.perPage + 1;
    const end   = Math.min(state.page * state.perPage, total);

    const el = document.getElementById('paginationBar');
    if (!el) return;

    const infoEl = el.querySelector('.pagination-info');
    if (infoEl) infoEl.textContent = `Menampilkan ${total === 0 ? 0 : start}–${end} produk dari ${total}`;

    const pagesEl = el.querySelector('.pagination-pages');
    if (!pagesEl) return;

    // Buat tombol halaman
    let pages = [];
    // Previous
    pages.push(`<button class="page-btn" data-page="${state.page - 1}" ${state.page <= 1 ? 'disabled' : ''} aria-label="Sebelumnya">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>`);

    // Tampilkan nomor halaman dengan ellipsis
    const range = buildPageRange(state.page, totalPages);
    range.forEach(r => {
      if (r === '...') {
        pages.push(`<span style="padding:0 4px;color:var(--muted);font-size:12px">…</span>`);
      } else {
        pages.push(`<button class="page-btn ${r === state.page ? 'active' : ''}" data-page="${r}">${r}</button>`);
      }
    });

    // Next
    pages.push(`<button class="page-btn" data-page="${state.page + 1}" ${state.page >= totalPages ? 'disabled' : ''} aria-label="Selanjutnya">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>`);

    pagesEl.innerHTML = pages.join('');

    // Events
    pagesEl.querySelectorAll('.page-btn[data-page]').forEach(btn => {
      btn.addEventListener('click', () => {
        const p = parseInt(btn.dataset.page, 10);
        if (!isNaN(p) && p >= 1 && p <= totalPages) {
          state.page = p;
          render();
        }
      });
    });
  }

  function buildPageRange(current, total) {
    if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
    const pages = [];
    if (current <= 4) {
      pages.push(1, 2, 3, 4, 5, '...', total);
    } else if (current >= total - 3) {
      pages.push(1, '...', total - 4, total - 3, total - 2, total - 1, total);
    } else {
      pages.push(1, '...', current - 1, current, current + 1, '...', total);
    }
    return pages;
  }

  /* =====================================================================
     11. MAIN RENDER
  ===================================================================== */
  const tableBody   = document.getElementById('productTableBody');
  const gridWrap    = document.getElementById('productGrid');
  const tableWrap   = document.getElementById('productTableWrap');

  function render() {
    const filtered  = filteredProducts();
    const paginated = paginatedProducts(filtered);

    if (state.layout === 'table') {
      if (tableWrap) tableWrap.hidden = false;
      if (gridWrap)  gridWrap.hidden  = true;
      if (tableBody) {
        if (paginated.length === 0) {
          tableBody.innerHTML = `<tr><td colspan="8">${renderEmpty()}</td></tr>`;
        } else {
          tableBody.innerHTML = renderTableRows(paginated);
        }
      }
    } else {
      if (tableWrap) tableWrap.hidden = true;
      if (gridWrap) {
        gridWrap.hidden = false;
        gridWrap.innerHTML = renderGridCards(paginated);
      }
    }

    renderPagination(filtered.length);
    bindDeleteButtons();

    // Update select-all checkbox
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
  }

  /* =====================================================================
     12. DELETE CONFIRMATION
  ===================================================================== */
  function bindDeleteButtons() {
    document.querySelectorAll('[data-delete-id]').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.deleteId;
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
          const form = btn.closest('form');
          if (form) form.submit();
        }
      });
    });
  }

  /* =====================================================================
     13. SEARCH
  ===================================================================== */
  const searchInput = document.getElementById('productSearch');
  if (searchInput) {
    let debounceTimer;
    searchInput.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        state.search = searchInput.value;
        state.page   = 1;
        render();
      }, 250);
    });
  }

  /* =====================================================================
     14. CATEGORY FILTER TABS
  ===================================================================== */
  const MAX_VISIBLE_CATS = 6; // include "Semua"

  function buildCategoryTabs() {
    const tabsWrap = document.getElementById('categoryTabs');
    const moreWrap = document.getElementById('catMoreWrap');
    if (!tabsWrap) return;

    const visibleCats  = allCategories.slice(0, MAX_VISIBLE_CATS - 1); // -1 for "Semua"
    const hiddenCats   = allCategories.slice(MAX_VISIBLE_CATS - 1);

    // Build visible tabs
    const allTab = document.createElement('button');
    allTab.className = 'cat-tab ' + (state.category === 'all' ? 'active' : '');
    allTab.dataset.catId = 'all';
    allTab.textContent = 'Semua';
    tabsWrap.innerHTML = '';
    tabsWrap.appendChild(allTab);

    visibleCats.forEach(cat => {
      const btn = document.createElement('button');
      btn.className = 'cat-tab ' + (String(state.category) === String(cat.id) ? 'active' : '');
      btn.dataset.catId = cat.id;
      btn.textContent = cat.category_name;
      tabsWrap.appendChild(btn);
    });

    // Tab events
    tabsWrap.querySelectorAll('.cat-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        state.category = tab.dataset.catId;
        state.page     = 1;
        tabsWrap.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        // Juga update dropdown jika ada
        document.querySelectorAll('.cat-dropdown-item').forEach(i => {
          i.classList.toggle('active', String(i.dataset.catId) === String(state.category));
        });
        render();
      });
    });

    // More dropdown
    if (!moreWrap) return;

    if (hiddenCats.length === 0) {
      moreWrap.hidden = true;
      return;
    }
    moreWrap.hidden = false;

    const moreBtn     = document.getElementById('catMoreBtn');
    const dropdown    = document.getElementById('catDropdown');
    const dropSearch  = document.getElementById('catDropdownSearch');
    const dropList    = document.getElementById('catDropdownList');
    if (!moreBtn || !dropdown || !dropList) return;

    function renderDropdownList(filter = '') {
      const filtered = hiddenCats.filter(c =>
        c.category_name.toLowerCase().includes(filter.toLowerCase())
      );
      if (!filtered.length) {
        dropList.innerHTML = `<div style="font-size:12px;color:var(--muted);padding:8px 10px">Tidak ada kategori</div>`;
        return;
      }
      dropList.innerHTML = filtered.map(cat => `
        <button class="cat-dropdown-item ${String(state.category) === String(cat.id) ? 'active' : ''}" data-cat-id="${cat.id}">
          ${escHtml(cat.category_name)}
        </button>
      `).join('');
      dropList.querySelectorAll('.cat-dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
          state.category = item.dataset.catId;
          state.page     = 1;
          tabsWrap.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
          renderDropdownList(dropSearch?.value || '');
          dropdown.hidden = true;
          moreBtn.classList.remove('active');
          render();
        });
      });
    }

    moreBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = !dropdown.hidden;
      dropdown.hidden = isOpen;
      moreBtn.classList.toggle('active', !isOpen);
      if (!isOpen && dropSearch) {
        dropSearch.value = '';
        renderDropdownList('');
        setTimeout(() => dropSearch.focus(), 50);
      }
    });

    dropSearch?.addEventListener('input', () => {
      renderDropdownList(dropSearch.value);
    });

    document.addEventListener('click', (e) => {
      if (!moreWrap.contains(e.target)) {
        dropdown.hidden = true;
        moreBtn.classList.remove('active');
      }
    });

    renderDropdownList('');
  }

  buildCategoryTabs();

  /* =====================================================================
     15. LAYOUT TOGGLE
  ===================================================================== */
  const btnGrid  = document.getElementById('layoutGrid');
  const btnTable = document.getElementById('layoutTable');

  btnGrid?.addEventListener('click', () => {
    state.layout = 'grid';
    btnGrid.classList.add('active');
    btnTable?.classList.remove('active');
    render();
  });

  btnTable?.addEventListener('click', () => {
    state.layout = 'table';
    btnTable.classList.add('active');
    btnGrid?.classList.remove('active');
    render();
  });

  /* =====================================================================
     16. SELECT ALL CHECKBOX
  ===================================================================== */
  const selectAll = document.getElementById('selectAll');
  selectAll?.addEventListener('change', () => {
    document.querySelectorAll('.tbl-check').forEach(cb => {
      cb.checked = selectAll.checked;
    });
  });

  /* =====================================================================
     17. PAGINATION BAR — ALL / SHOW LESS
  ===================================================================== */
  document.getElementById('btnPagAll')?.addEventListener('click', () => {
    state.perPage = allProducts.length || 999;
    state.page    = 1;
    render();
  });

  document.getElementById('btnPagLess')?.addEventListener('click', () => {
    state.perPage = 8;
    state.page    = 1;
    render();
  });

  /* =====================================================================
     18. UTILITY
  ===================================================================== */
  function escHtml(str) {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // Expose CSRF token for delete forms
  window.__csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  /* =====================================================================
     19. INIT
  ===================================================================== */
  render();

});
