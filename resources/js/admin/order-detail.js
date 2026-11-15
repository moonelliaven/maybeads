/**
 * Admin Order Detail JavaScript - Maybead.s
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Sidebar toggle
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

  // 2. Helper for clipboard copy
  function setupCopyButton(btnId, targetId) {
    const btn = document.getElementById(btnId);
    const target = document.getElementById(targetId);

    if (btn && target) {
      btn.addEventListener('click', function () {
        const text = target.innerText.trim();
        navigator.clipboard.writeText(text).then(() => {
          const originalHtml = btn.innerHTML;
          btn.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          `;
          setTimeout(() => {
            btn.innerHTML = originalHtml;
          }, 1800);
        });
      });
    }
  }

  setupCopyButton('btnCopyOrderId', 'orderCodeText');
  setupCopyButton('btnCopyPaymentId', 'badgePaymentId');
  setupCopyButton('btnCopySidePaymentId', 'sidePaymentCodeText');

  // 3. Print Invoice
  const btnPrint = document.getElementById('btnPrintInvoice');
  if (btnPrint) {
    btnPrint.addEventListener('click', function () {
      window.print();
    });
  }

  // 4. Modal Update Status
  const statusModal = document.getElementById('statusModalBackdrop');
  const btnOpenStatus = document.getElementById('btnOpenStatusModal');
  const btnOpenStatusSidebar = document.getElementById('btnUpdateStatusSidebar');
  const btnCloseStatus = document.getElementById('btnCloseStatusModal');
  const btnCancelStatus = document.getElementById('btnCancelStatusModal');

  function openStatusModal() {
    if (statusModal) statusModal.style.display = 'flex';
  }

  function closeStatusModal() {
    if (statusModal) statusModal.style.display = 'none';
  }

  if (btnOpenStatus) btnOpenStatus.addEventListener('click', openStatusModal);
  if (btnOpenStatusSidebar) btnOpenStatusSidebar.addEventListener('click', openStatusModal);
  if (btnCloseStatus) btnCloseStatus.addEventListener('click', closeStatusModal);
  if (btnCancelStatus) btnCancelStatus.addEventListener('click', closeStatusModal);

  // 5. Modal Batalkan Pesanan
  const cancelModal = document.getElementById('cancelModalBackdrop');
  const btnOpenCancel = document.getElementById('btnOpenCancelModal');
  const btnCloseCancel = document.getElementById('btnCloseCancelModal');
  const btnCancelCancel = document.getElementById('btnCancelCancelModal');

  function openCancelModal() {
    if (cancelModal) cancelModal.style.display = 'flex';
  }

  function closeCancelModal() {
    if (cancelModal) cancelModal.style.display = 'none';
  }

  if (btnOpenCancel) btnOpenCancel.addEventListener('click', openCancelModal);
  if (btnCloseCancel) btnCloseCancel.addEventListener('click', closeCancelModal);
  if (btnCancelCancel) btnCancelCancel.addEventListener('click', closeCancelModal);

  // Close modals on clicking backdrop
  window.addEventListener('click', function (e) {
    if (statusModal && e.target === statusModal) closeStatusModal();
    if (cancelModal && e.target === cancelModal) closeCancelModal();
  });

  // Close on Escape key
  window.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeStatusModal();
      closeCancelModal();
    }
  });
});
