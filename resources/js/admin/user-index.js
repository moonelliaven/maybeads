/* ==========================================================================
   User Index JS - Maybead.s
   ========================================================================== */

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import '../../css/common/sweetalert-theme.css';

window.Swal = Swal;

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
     2. SIDEBAR USER MENU & DROPDOWNS
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

  document.addEventListener('click', function () {
    closeSidebarUserMenu();
  });

  /* =====================================================================
     3. DATA STORE & STATE
  ===================================================================== */
  const dataStoreEl = document.getElementById('userDataStore');
  let rawUsers = [];

  if (dataStoreEl) {
    try {
      rawUsers = JSON.parse(dataStoreEl.getAttribute('data-users') || '[]');
    } catch (e) {
      console.error('Gagal parse data-users:', e);
      rawUsers = [];
    }
  }

  const tableBody       = document.getElementById('userTableBody');
  const searchInput     = document.getElementById('userSearchInput');
  const filterTabs      = document.querySelectorAll('.filter-tab');
  const selectAllCb     = document.getElementById('selectAll');
  const paginationBar   = document.getElementById('paginationBar');
  const paginationPages = document.getElementById('paginationPages');
  const paginationInfo  = document.getElementById('paginationInfo');
  const btnPagAll       = document.getElementById('btnPagAll');
  const btnPagLess      = document.getElementById('btnPagLess');

  let activeTab    = 'semua';
  let searchQuery  = '';
  let currentPage  = 1;
  let itemsPerPage = 8;
  let isAllMode    = false;

  /* Helper Escape HTML */
  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  /* Filter Users */
  function getFilteredUsers() {
    return rawUsers.filter(user => {
      // Search filter
      if (searchQuery.trim() !== '') {
        const q = searchQuery.toLowerCase().trim();
        const nameMatch = (user.name || '').toLowerCase().includes(q);
        const emailMatch = (user.email || '').toLowerCase().includes(q);
        const idMatch = (user.formatted_id || '').toLowerCase().includes(q) || String(user.id).includes(q);
        const addressMatch = (user.address || '').toLowerCase().includes(q);
        const roleMatch = (user.role || '').toLowerCase().includes(q);

        return nameMatch || emailMatch || idMatch || addressMatch || roleMatch;
      }

      return true;
    });
  }

  /* Render Table Rows */
  function renderTable() {
    if (!tableBody) return;

    const filtered = getFilteredUsers();
    const totalItems = filtered.length;

    let displayItems = filtered;
    let totalPages = 1;

    if (!isAllMode) {
      totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
      if (currentPage > totalPages) currentPage = totalPages;
      const startIndex = (currentPage - 1) * itemsPerPage;
      displayItems = filtered.slice(startIndex, startIndex + itemsPerPage);
    } else {
      currentPage = 1;
      totalPages = 1;
    }

    if (displayItems.length === 0) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="7" class="user-empty">
            Tidak ada data user yang sesuai.
          </td>
        </tr>
      `;
    } else {
      tableBody.innerHTML = displayItems.map(user => {
        const avatarInitial = (user.name || 'U').charAt(0).toUpperCase();
        const avatarColors = ['#2563eb', '#7c3aed', '#059669', '#ea580c', '#db2777', '#0891b2'];
        const colorIndex = (user.id || 1) % avatarColors.length;
        const bgColor = avatarColors[colorIndex];

        return `
          <tr data-id="${user.id}">
            <td class="cb-col">
              <input type="checkbox" class="tbl-check row-cb" data-id="${user.id}" aria-label="Pilih ${escapeHtml(user.name)}">
            </td>
            <td class="col-id">${escapeHtml(user.formatted_id || 'IDP' + String(user.id).padStart(4, '0'))}</td>
            <td class="col-user">
              <div class="user-meta-cell">
                <div class="user-cell-avatar" style="background-color: ${bgColor}">
                  ${avatarInitial}
                </div>
                <span class="user-cell-name">${escapeHtml(user.name)}</span>
              </div>
            </td>
            <td class="col-email">${escapeHtml(user.email)}</td>
            <td class="col-address" title="${escapeHtml(user.address)}">${escapeHtml(user.address)}</td>
            <td class="col-role">${escapeHtml(user.role || 'User')}</td>
            <td class="col-action">
              <div class="action-btns">
                <a href="/admin/user/detail/${user.id}" class="btn-detail-struk">Detail</a>
                <button type="button" class="btn-delete-icon" data-delete-id="${user.id}" data-name="${escapeHtml(user.name)}" title="Hapus User">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6l-1 14H6L5 6"></path>
                    <path d="M10 11v6"></path>
                    <path d="M14 11v6"></path>
                    <path d="M9 6V4h6v2"></path>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    // Update Pagination Bar
    if (paginationInfo) {
      const shownCount = displayItems.length;
      paginationInfo.textContent = `Menampilkan ${shownCount} user dari ${totalItems}`;
    }

    renderPaginationControls(totalPages);
    syncSelectAll();
  }

  /* Render Pagination Controls */
  function renderPaginationControls(totalPages) {
    if (!paginationPages) return;

    if (isAllMode || totalPages <= 1) {
      paginationPages.innerHTML = '';
      return;
    }

    let html = `
      <button type="button" class="page-btn prev-btn" ${currentPage === 1 ? 'disabled' : ''} aria-label="Sebelumnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
      </button>
    `;

    for (let p = 1; p <= totalPages; p++) {
      if (totalPages > 7) {
        if (p > 2 && p < totalPages - 1 && Math.abs(p - currentPage) > 1) {
          if (p === 3 || p === totalPages - 2) {
            html += `<span style="padding: 0 4px; color: #94a3b8;">...</span>`;
          }
          continue;
        }
      }

      html += `
        <button type="button" class="page-btn ${p === currentPage ? 'active' : ''}" data-page="${p}">
          ${p}
        </button>
      `;
    }

    html += `
      <button type="button" class="page-btn next-btn" ${currentPage === totalPages ? 'disabled' : ''} aria-label="Berikutnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
      </button>
    `;

    paginationPages.innerHTML = html;
  }

  /* Pagination Event Delegation */
  if (paginationPages) {
    paginationPages.addEventListener('click', e => {
      const target = e.target.closest('.page-btn');
      if (!target || target.disabled) return;

      if (target.classList.contains('prev-btn')) {
        if (currentPage > 1) {
          currentPage--;
          renderTable();
        }
      } else if (target.classList.contains('next-btn')) {
        currentPage++;
        renderTable();
      } else if (target.dataset.page) {
        currentPage = parseInt(target.dataset.page, 10);
        renderTable();
      }
    });
  }

  /* "ALL" button click */
  if (btnPagAll) {
    btnPagAll.addEventListener('click', () => {
      isAllMode = true;
      renderTable();
    });
  }

  /* "Show Less" button click */
  if (btnPagLess) {
    btnPagLess.addEventListener('click', () => {
      isAllMode = false;
      currentPage = 1;
      renderTable();
    });
  }

  /* Search Input Event */
  if (searchInput) {
    let debounceTimer;
    searchInput.addEventListener('input', e => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        searchQuery = e.target.value;
        currentPage = 1;
        renderTable();
      }, 150);
    });
  }

  /* Filter Tabs Event */
  filterTabs.forEach(tab => {
    tab.addEventListener('click', function () {
      filterTabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
      activeTab = this.dataset.tab || 'semua';
      currentPage = 1;
      renderTable();
    });
  });

  /* Select All Checkbox */
  if (selectAllCb) {
    selectAllCb.addEventListener('change', function () {
      const rowCheckboxes = document.querySelectorAll('.row-cb');
      rowCheckboxes.forEach(cb => {
        cb.checked = selectAllCb.checked;
      });
    });
  }

  function syncSelectAll() {
    if (!selectAllCb) return;
    const rowCheckboxes = document.querySelectorAll('.row-cb');
    if (rowCheckboxes.length === 0) {
      selectAllCb.checked = false;
      selectAllCb.indeterminate = false;
      return;
    }
    const checkedCount = Array.from(rowCheckboxes).filter(cb => cb.checked).length;
    selectAllCb.checked = checkedCount === rowCheckboxes.length;
    selectAllCb.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
  }

  if (tableBody) {
    tableBody.addEventListener('change', e => {
      if (e.target.classList.contains('row-cb')) {
        syncSelectAll();
      }
    });
  }

  /* =====================================================================
     4. DELETE USER WITH SWEETALERT2
  ===================================================================== */
  if (tableBody) {
    tableBody.addEventListener('click', e => {
      const deleteBtn = e.target.closest('.btn-delete-icon');
      if (!deleteBtn) return;

      const userId = deleteBtn.dataset.deleteId;
      const userName = deleteBtn.dataset.name || 'User ini';

      Swal.fire({
        title: 'Hapus User',
        text: 'User yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d9383a',
        cancelButtonColor: '#5c5656',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Kembali',
        reverseButtons: true,
        customClass: {
          popup: 'maybeads-swal-popup'
        }
      }).then(result => {
        if (result.isConfirmed) {
          // Submit delete form
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = `/admin/user/${userId}`;

          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
          const csrfInput = document.createElement('input');
          csrfInput.type = 'hidden';
          csrfInput.name = '_token';
          csrfInput.value = csrfToken || '';

          const methodInput = document.createElement('input');
          methodInput.type = 'hidden';
          methodInput.name = '_method';
          methodInput.value = 'DELETE';

          form.appendChild(csrfInput);
          form.appendChild(methodInput);
          document.body.appendChild(form);
          form.submit();
        }
      });
    });
  }

  // Initial render
  renderTable();

});

