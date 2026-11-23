/**
 * Admin Order Index JavaScript - Maybead.s
 */

document.addEventListener('DOMContentLoaded', function () {
  // Sidebar toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('adminSidebar');
  const backdrop = document.getElementById('sidebarBackdrop');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      document.body.classList.toggle('sidebar-collapsed');
      sidebar.classList.toggle('open');
      if (backdrop) backdrop.classList.toggle('active');
    });
  }

  if (backdrop && sidebar) {
    backdrop.addEventListener('click', function () {
      sidebar.classList.remove('open');
      backdrop.classList.remove('active');
    });
  }

  // User dropdown menu in sidebar
  const userBadge = document.getElementById('sidebarUserBadge');
  const userMenu = document.getElementById('sidebarUserMenu');
  if (userBadge && userMenu) {
    userBadge.addEventListener('click', function (e) {
      e.stopPropagation();
      const isHidden = userMenu.hasAttribute('hidden');
      if (isHidden) {
        userMenu.removeAttribute('hidden');
        userBadge.setAttribute('aria-expanded', 'true');
      } else {
        userMenu.setAttribute('hidden', '');
        userBadge.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('click', function (e) {
      if (!userBadge.contains(e.target) && !userMenu.contains(e.target)) {
        userMenu.setAttribute('hidden', '');
        userBadge.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Select all checkboxes
  const selectAll = document.getElementById('selectAll');
  const rowCheckboxes = document.querySelectorAll('.row-cb');

  if (selectAll) {
    selectAll.addEventListener('change', function () {
      rowCheckboxes.forEach((cb) => {
        cb.checked = selectAll.checked;
      });
    });

    rowCheckboxes.forEach((cb) => {
      cb.addEventListener('change', function () {
        const allChecked = Array.from(rowCheckboxes).every((c) => c.checked);
        selectAll.checked = allChecked;
      });
    });
  }

  // Client-side filtering & search with dynamic 10-per-page pagination and calendar date range sorting
  const searchInput = document.getElementById('orderSearchInput');
  const filterTabs = document.querySelectorAll('.filter-tab');
  const dateSort = document.getElementById('orderDateSort');
  const startDateInput = document.getElementById('orderStartDate');
  const endDateInput = document.getElementById('orderEndDate');
  const resetDateBtn = document.getElementById('btnResetDateFilter');
  const tableRows = Array.from(document.querySelectorAll('#orderTableBody tr:not(.empty-row)'));
  const emptyRow = document.getElementById('orderEmptyRow');
  const pagInfo = document.getElementById('paginationInfo');
  const paginationPages = document.querySelector('.pagination-pages');
  const tableBody = document.getElementById('orderTableBody');

  let currentTab = 'semua';
  let currentDateSort = 'terbaru';
  let startDate = '';
  let endDate = '';
  let searchQuery = '';
  let currentPage = 1;
  let pageSize = 10;
  let isAllMode = false;

  if (dateSort) {
    dateSort.addEventListener('change', function () {
      currentDateSort = this.value;
      currentPage = 1;
      applyFilters();
    });
  }

  function handleDateFilterChange() {
    startDate = startDateInput ? startDateInput.value : '';
    endDate = endDateInput ? endDateInput.value : '';
    if (resetDateBtn) {
      resetDateBtn.style.display = (startDate || endDate) ? 'inline-block' : 'none';
    }
    currentPage = 1;
    applyFilters();
  }

  if (startDateInput) startDateInput.addEventListener('change', handleDateFilterChange);
  if (endDateInput) endDateInput.addEventListener('change', handleDateFilterChange);
  if (resetDateBtn) {
    resetDateBtn.addEventListener('click', function () {
      if (startDateInput) startDateInput.value = '';
      if (endDateInput) endDateInput.value = '';
      startDate = '';
      endDate = '';
      resetDateBtn.style.display = 'none';
      currentPage = 1;
      applyFilters();
    });
  }

  function applyFilters() {
    // 1. Filter rows by tab, search & date range
    let matchingRows = tableRows.filter((row) => {
      const orderStatus = (row.getAttribute('data-status') || '').toLowerCase();
      const rowDate = (row.getAttribute('data-date') || '');
      const rowText = row.innerText.toLowerCase();

      let matchesTab = false;
      if (currentTab === 'semua') {
        matchesTab = true;
      } else if (currentTab === 'process' || currentTab === 'proses') {
        matchesTab = ['proses', 'process', 'diproses'].includes(orderStatus);
      } else if (currentTab === 'selesai') {
        matchesTab = ['selesai', 'sukses', 'success'].includes(orderStatus);
      } else if (currentTab === 'pending') {
        matchesTab = orderStatus === 'pending';
      } else if (currentTab === 'dibatalkan') {
        matchesTab = ['dibatalkan', 'batal', 'cancelled'].includes(orderStatus);
      }

      const matchesSearch = searchQuery === '' || rowText.includes(searchQuery);

      // Filter rentang tanggal kalender (dari tanggal ke tanggal berapa)
      let matchesDate = true;
      if (startDate && rowDate && rowDate < startDate) {
        matchesDate = false;
      }
      if (endDate && rowDate && rowDate > endDate) {
        matchesDate = false;
      }

      return matchesTab && matchesSearch && matchesDate;
    });

    // 1b. Sort by date (terbaru / terlama)
    matchingRows.sort((a, b) => {
      const timeA = parseInt(a.getAttribute('data-created-at') || 0, 10);
      const timeB = parseInt(b.getAttribute('data-created-at') || 0, 10);
      return currentDateSort === 'terbaru' ? (timeB - timeA) : (timeA - timeB);
    });

    // Re-append sorted rows to tbody
    if (tableBody) {
      matchingRows.forEach((r) => tableBody.appendChild(r));
    }

    const totalMatching = matchingRows.length;
    const totalPages = Math.max(1, Math.ceil(totalMatching / pageSize));

    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    // 2. Hide all rows first
    tableRows.forEach((r) => (r.style.display = 'none'));

    // 3. Slice and display active page rows
    let visibleRows = matchingRows;
    if (!isAllMode) {
      const startIdx = (currentPage - 1) * pageSize;
      const endIdx = startIdx + pageSize;
      visibleRows = matchingRows.slice(startIdx, endIdx);
    }

    visibleRows.forEach((r) => (r.style.display = ''));

    // 4. Update empty row state
    if (emptyRow) {
      emptyRow.style.display = totalMatching === 0 ? '' : 'none';
    }

    // 5. Update info text
    if (pagInfo) {
      const startCount = totalMatching === 0 ? 0 : (isAllMode ? 1 : (currentPage - 1) * pageSize + 1);
      const endCount = isAllMode ? totalMatching : Math.min(currentPage * pageSize, totalMatching);
      pagInfo.innerText = `Menampilkan ${startCount}–${endCount} pesanan dari ${totalMatching}`;
    }

    // 6. Render pagination page controls
    renderPaginationControls(totalPages);
  }

  function renderPaginationControls(totalPages) {
    if (!paginationPages) return;

    // Jika SHOW ALL aktif atau hanya ada 1 halaman, sembunyikan toggle angka halaman 1,2,3
    if (isAllMode || totalPages <= 1) {
      paginationPages.innerHTML = '';
      paginationPages.style.display = 'none';
      return;
    }

    paginationPages.style.display = 'flex';
    let html = '';

    // Tombol Prev
    html += `<button type="button" class="pag-btn prev-page-btn" ${currentPage === 1 ? 'disabled' : ''} aria-label="Halaman sebelumnya">&lt;</button>`;

    for (let p = 1; p <= totalPages; p++) {
      if (totalPages > 7) {
        if (p > 2 && p < totalPages - 1 && Math.abs(p - currentPage) > 1) {
          if (p === 3 || p === totalPages - 2) {
            html += `<span style="font-size: 11px; color: #94a3b8; padding: 0 4px;">...</span>`;
          }
          continue;
        }
      }
      html += `<button type="button" class="pag-btn num-page-btn ${p === currentPage ? 'active' : ''}" data-page="${p}">${p}</button>`;
    }

    // Tombol Next
    html += `<button type="button" class="pag-btn next-page-btn" ${currentPage === totalPages ? 'disabled' : ''} aria-label="Halaman berikutnya">&gt;</button>`;

    paginationPages.innerHTML = html;

    // Attach click events
    paginationPages.querySelectorAll('.num-page-btn').forEach((btn) => {
      btn.addEventListener('click', function () {
        currentPage = parseInt(this.getAttribute('data-page'), 10);
        applyFilters();
      });
    });

    const prevBtn = paginationPages.querySelector('.prev-page-btn');
    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        if (currentPage > 1) {
          currentPage--;
          applyFilters();
        }
      });
    }

    const nextBtn = paginationPages.querySelector('.next-page-btn');
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        if (currentPage < totalPages) {
          currentPage++;
          applyFilters();
        }
      });
    }
  }

  // Search event
  if (searchInput) {
    searchInput.addEventListener('input', function (e) {
      searchQuery = e.target.value.toLowerCase().trim();
      currentPage = 1;
      applyFilters();
    });
  }

  // Tab click events
  filterTabs.forEach((tab) => {
    tab.addEventListener('click', function (e) {
      e.preventDefault();
      filterTabs.forEach((t) => t.classList.remove('active'));
      this.classList.add('active');
      currentTab = (this.getAttribute('data-tab') || 'semua').toLowerCase();
      currentPage = 1;
      applyFilters();
    });
  });

  // Tombol ALL dan Show Less
  const btnAll = document.getElementById('btnPagAll');
  const btnLess = document.getElementById('btnPagLess');

  if (btnAll) {
    btnAll.addEventListener('click', function () {
      isAllMode = true;
      btnAll.classList.add('active');
      btnLess?.classList.remove('active');
      applyFilters();
    });
  }

  if (btnLess) {
    btnLess.addEventListener('click', function () {
      isAllMode = false;
      currentPage = 1;
      btnLess?.classList.add('active');
      btnAll?.classList.remove('active');
      applyFilters();
    });
  }

  // Inisialisasi awal pagination
  applyFilters();
});

