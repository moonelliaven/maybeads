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

  // Client-side filtering & search
  const searchInput = document.getElementById('orderSearchInput');
  const filterTabs = document.querySelectorAll('.filter-tab');
  const tableRows = document.querySelectorAll('#orderTableBody tr:not(.empty-row)');
  const emptyRow = document.getElementById('orderEmptyRow');
  const pagInfo = document.getElementById('paginationInfo');

  let currentTab = 'semua';
  let searchQuery = '';

  function applyFilters() {
    let visibleCount = 0;

    tableRows.forEach((row) => {
      const orderStatus = (row.getAttribute('data-status') || '').toLowerCase();
      const rowText = row.innerText.toLowerCase();

      // Tab matching
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

      // Search matching
      const matchesSearch = searchQuery === '' || rowText.includes(searchQuery);

      if (matchesTab && matchesSearch) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    if (emptyRow) {
      emptyRow.style.display = visibleCount === 0 ? '' : 'none';
    }

    if (pagInfo) {
      pagInfo.innerText = `Menampilkan ${visibleCount} pesanan dari ${tableRows.length}`;
    }
  }

  // Search event
  if (searchInput) {
    searchInput.addEventListener('input', function (e) {
      searchQuery = e.target.value.toLowerCase().trim();
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
      applyFilters();
    });
  });

  // Tombol ALL dan Show Less
  const btnAll = document.getElementById('btnPagAll');
  const btnLess = document.getElementById('btnPagLess');

  if (btnAll) {
    btnAll.addEventListener('click', function () {
      tableRows.forEach((r) => (r.style.display = ''));
      if (pagInfo) {
        pagInfo.innerText = `Menampilkan ${tableRows.length} pesanan dari ${tableRows.length}`;
      }
    });
  }

  if (btnLess) {
    btnLess.addEventListener('click', function () {
      applyFilters();
    });
  }
});

