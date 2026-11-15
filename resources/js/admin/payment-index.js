/* ==========================================================================
   Admin Payment Index Interactive Script - Maybeads
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Sidebar Toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  const adminSidebar = document.getElementById('adminSidebar');
  const sidebarBackdrop = document.getElementById('sidebarBackdrop');

  if (sidebarToggle && adminSidebar) {
    sidebarToggle.addEventListener('click', () => {
      adminSidebar.classList.toggle('open');
      if (sidebarBackdrop) sidebarBackdrop.classList.toggle('show');
    });
  }

  if (sidebarBackdrop) {
    sidebarBackdrop.addEventListener('click', () => {
      if (adminSidebar) adminSidebar.classList.remove('open');
      sidebarBackdrop.classList.remove('show');
    });
  }

  // 2. Select All Checkbox
  const checkAll = document.getElementById('checkAllPayments');
  const rowCheckboxes = document.querySelectorAll('.payment-row-cb');

  if (checkAll) {
    checkAll.addEventListener('change', () => {
      rowCheckboxes.forEach(cb => {
        cb.checked = checkAll.checked;
      });
    });
  }

  rowCheckboxes.forEach(cb => {
    cb.addEventListener('change', () => {
      const allChecked = Array.from(rowCheckboxes).every(c => c.checked);
      if (checkAll) checkAll.checked = allChecked;
    });
  });

  // 3. Search Input (Debounced or on Enter)
  const searchInput = document.getElementById('paymentSearchInput');
  if (searchInput) {
    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        const val = searchInput.value.trim();
        const url = new URL(window.location.href);
        if (val) {
          url.searchParams.set('search', val);
        } else {
          url.searchParams.delete('search');
        }
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
      }
    });
  }

  // 4. Detail Struk Modal
  const receiptModal = document.getElementById('receiptModalBackdrop');
  const btnCloseModal = document.getElementById('btnCloseReceiptModal');
  const btnDoneModal = document.getElementById('btnDoneReceiptModal');

  const metaId = document.getElementById('receiptMetaId');
  const metaUser = document.getElementById('receiptMetaUser');
  const metaAmount = document.getElementById('receiptMetaAmount');
  const metaMethod = document.getElementById('receiptMetaMethod');
  const metaDate = document.getElementById('receiptMetaDate');
  const metaStatus = document.getElementById('receiptMetaStatus');
  const receiptImgWrap = document.getElementById('receiptImgWrap');

  function openReceiptModal(data) {
    if (!receiptModal) return;

    if (metaId) metaId.textContent = data.id || '-';
    if (metaUser) metaUser.textContent = data.user || '-';
    if (metaAmount) metaAmount.textContent = data.amount || '-';
    if (metaMethod) metaMethod.textContent = data.method || '-';
    if (metaDate) metaDate.textContent = data.date || '-';
    if (metaStatus) metaStatus.textContent = data.status || '-';

    if (receiptImgWrap) {
      if (data.proof) {
        receiptImgWrap.innerHTML = `<img src="${data.proof}" alt="Bukti Pembayaran">`;
      } else {
        receiptImgWrap.innerHTML = `
          <div class="receipt-placeholder">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 36px; height: 36px;">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <circle cx="8.5" cy="8.5" r="1.5"></circle>
              <polyline points="21 15 16 10 5 21"></polyline>
            </svg>
            <span>Bukti transfer / struk pembayaran otomatis QRIS</span>
          </div>
        `;
      }
    }

    receiptModal.classList.add('show');
  }

  function closeReceiptModal() {
    if (receiptModal) receiptModal.classList.remove('show');
  }

  if (btnCloseModal) btnCloseModal.addEventListener('click', closeReceiptModal);
  if (btnDoneModal) btnDoneModal.addEventListener('click', closeReceiptModal);
  if (receiptModal) {
    receiptModal.addEventListener('click', (e) => {
      if (e.target === receiptModal) closeReceiptModal();
    });
  }

  document.querySelectorAll('.btn-detail-receipt').forEach(btn => {
    btn.addEventListener('click', () => {
      const data = {
        id: btn.dataset.id,
        user: btn.dataset.user,
        amount: btn.dataset.amount,
        method: btn.dataset.method,
        date: btn.dataset.date,
        status: btn.dataset.status,
        proof: btn.dataset.proof,
      };
      openReceiptModal(data);
    });
  });

  // 5. Delete Confirmation
  document.querySelectorAll('.form-delete-payment').forEach(form => {
    form.addEventListener('submit', (e) => {
      if (!confirm('Apakah Anda yakin ingin menghapus catatan data pembayaran ini?')) {
        e.preventDefault();
      }
    });
  });
});

