/* ==========================================================================
   Category Index JS - Maybead.s
   ========================================================================== */

'use strict';

document.addEventListener('DOMContentLoaded', () => {

  /* =====================================================================
     1. SIDEBAR TOGGLE & BACKDROP
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
        if (backdrop) backdrop.classList.toggle('active');
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

  // Notifikasi mark read
  document.querySelectorAll('[data-mark-read]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('[data-notif-badge]').forEach(b => b.remove());
    });
  });

  /* =====================================================================
     3. DATA CATEGORY
  ===================================================================== */
  const categoriesRaw = document.getElementById('categoryDataStore')?.dataset?.categories || '[]';
  let allCategories   = [];

  try {
    allCategories = JSON.parse(categoriesRaw);
  } catch(e) { console.warn('Category data parse error', e); }

  /* =====================================================================
     4. STATE
  ===================================================================== */
  let state = {
    search:  '',
    page:    1,
    perPage: 8,
  };

  /* =====================================================================
     5. RENDER HELPERS
  ===================================================================== */
  function editUrl(id) { return `/admin/category/${id}/edit`; }
  function detailUrl(id) { return `/admin/category/${id}`; }

  function deleteFormHtml(id) {
    return `<form method="POST" action="/admin/category/${id}" class="delete-form" style="display:inline">
      <input type="hidden" name="_token" value="${window.__csrf || ''}">
      <input type="hidden" name="_method" value="DELETE">
      <button type="button" class="btn-icon-action danger" data-delete-id="${id}" title="Hapus">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
        </svg>
      </button>
    </form>`;
  }

  function escHtml(str) {
    return String(str ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // Expose CSRF token
  window.__csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  /* =====================================================================
     6. FILTER + PAGINATION
  ===================================================================== */
  function filteredCategories() {
    const q = state.search.toLowerCase().trim();
    if (!q) return allCategories;
    return allCategories.filter(c => {
      const nameMatch = (c.category_name || '').toLowerCase().includes(q);
      const idMatch   = String(c.id).includes(q);
      return nameMatch || idMatch;
    });
  }

  function paginatedCategories(filtered) {
    const start = (state.page - 1) * state.perPage;
    return filtered.slice(start, start + state.perPage);
  }

  /* =====================================================================
     7. RENDER TABLE
  ===================================================================== */
  const tableBody = document.getElementById('categoryTableBody');

  function renderTableRows(categories) {
    if (!categories.length) return renderEmpty();
    return categories.map(cat => `
      <tr data-id="${cat.id}">
        <td class="cb-col">
          <input type="checkbox" class="tbl-check" aria-label="Pilih ${escHtml(cat.category_name)}">
        </td>
        <td class="col-id">${cat.id}</td>
        <td class="col-name">${escHtml(cat.category_name)}</td>
        <td class="col-count">${cat.products_count ?? 0}</td>
        <td class="col-stock">${cat.products_stock ?? 0}</td>
        <td class="col-action">
          <div class="action-btns">
            <a href="${detailUrl(cat.id)}" class="btn-detail">Detail</a>
            <a href="${editUrl(cat.id)}" class="btn-icon-action" title="Edit">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
            </a>
            ${deleteFormHtml(cat.id)}
          </div>
        </td>
      </tr>
    `).join('');
  }

  function renderEmpty() {
    return `<tr>
      <td colspan="6">
        <div class="category-empty">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          <div class="category-empty-title">Kategori tidak ditemukan</div>
          <div class="category-empty-sub">Coba ubah kata kunci pencarian Anda</div>
        </div>
      </td>
    </tr>`;
  }

  /* =====================================================================
     8. RENDER PAGINATION
  ===================================================================== */
  function renderPagination(total) {
    const totalPages = Math.max(1, Math.ceil(total / state.perPage));
    const start = total === 0 ? 0 : (state.page - 1) * state.perPage + 1;
    const end   = Math.min(state.page * state.perPage, total);

    const el = document.getElementById('paginationBar');
    if (!el) return;

    const infoEl = el.querySelector('.pagination-info');
    if (infoEl) infoEl.textContent = `Menampilkan ${start}–${end} kategori dari ${total}`;

    const pagesEl = el.querySelector('.pagination-pages');
    if (!pagesEl) return;

    let pages = [];
    // Previous
    pages.push(`<button class="page-btn" data-page="${state.page - 1}" ${state.page <= 1 ? 'disabled' : ''} aria-label="Sebelumnya">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>`);

    // Numbers
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
     9. MAIN RENDER
  ===================================================================== */
  function render() {
    const filtered  = filteredCategories();
    const paginated = paginatedCategories(filtered);

    if (tableBody) {
      tableBody.innerHTML = renderTableRows(paginated);
    }

    renderPagination(filtered.length);
    bindDeleteButtons();

    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
  }

  /* =====================================================================
     10. DELETE CONFIRMATION
  ===================================================================== */
  function bindDeleteButtons() {
    document.querySelectorAll('[data-delete-id]').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.deleteId;
        if (confirm('Apakah Anda yakin ingin menghapus kategori ini? Produk yang terkait mungkin terpengaruh.')) {
          const form = btn.closest('form');
          if (form) form.submit();
        }
      });
    });
  }

  /* =====================================================================
     11. SEARCH
  ===================================================================== */
  const searchInput = document.getElementById('categorySearch');
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
     12. SELECT ALL CHECKBOX
  ===================================================================== */
  const selectAll = document.getElementById('selectAll');
  selectAll?.addEventListener('change', () => {
    document.querySelectorAll('.tbl-check').forEach(cb => {
      cb.checked = selectAll.checked;
    });
  });

  /* =====================================================================
     13. PAGINATION BAR — ALL / SHOW LESS
  ===================================================================== */
  document.getElementById('btnPagAll')?.addEventListener('click', () => {
    state.perPage = allCategories.length || 999;
    state.page    = 1;
    render();
  });

  document.getElementById('btnPagLess')?.addEventListener('click', () => {
    state.perPage = 8;
    state.page    = 1;
    render();
  });

  /* =====================================================================
     14. INITIAL RENDER
  ===================================================================== */
  render();

});
