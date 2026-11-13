/**
 * Admin Order Detail JavaScript - Maybead.s
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

  // Copy Order ID
  const btnCopy = document.getElementById('btnCopyOrderId');
  const orderCode = document.getElementById('orderCodeText');

  if (btnCopy && orderCode) {
    btnCopy.addEventListener('click', function () {
      const textToCopy = orderCode.innerText.trim();
      navigator.clipboard.writeText(textToCopy).then(() => {
        const originalHtml = btnCopy.innerHTML;
        btnCopy.innerHTML = `
          <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;">
            <polyline points="20 6 9 17 4 12"></polyline>
          </svg>
        `;
        setTimeout(() => {
          btnCopy.innerHTML = originalHtml;
        }, 2000);
      });
    });
  }

  // Print Invoice
  const btnPrint = document.getElementById('btnPrintInvoice');
  if (btnPrint) {
    btnPrint.addEventListener('click', function () {
      window.print();
    });
  }

  // Update button alert / trigger
  const btnUpdate = document.getElementById('btnUpdateStatus');
  if (btnUpdate) {
    btnUpdate.addEventListener('click', function () {
      alert('Fitur pembaruan status pesanan siap diintegrasikan sesuai alur bisnis.');
    });
  }
});

