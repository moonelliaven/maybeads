/* ==========================================================================
   User Detail JS - Maybead.s
   ========================================================================== */

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

'use strict';

document.addEventListener('DOMContentLoaded', () => {

  /* Sidebar toggle & backdrop */
  const sidebar       = document.getElementById('adminSidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const backdrop      = document.getElementById('sidebarBackdrop');

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

  /* Delete User button */
  const deleteBtn = document.getElementById('btnDeleteUser');
  if (deleteBtn) {
    deleteBtn.addEventListener('click', () => {
      const userId   = deleteBtn.dataset.userId;
      const userName = deleteBtn.dataset.userName || 'User ini';

      Swal.fire({
        title: 'Hapus User?',
        text: `Apakah Anda yakin ingin menghapus akun "${userName}"? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus Akun!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
      }).then(result => {
        if (result.isConfirmed) {
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

});

