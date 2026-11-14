/**
 * Admin Payment Method Management JavaScript - Maybead.s
 */

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import '../../css/common/sweetalert-theme.css';

window.Swal = Swal;

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

  // 2. Modal Add New Method
  const addModal = document.getElementById('addModalBackdrop');
  const btnOpenAdd = document.getElementById('btnOpenAddModal');
  const btnCloseAdd = document.getElementById('btnCloseAddModal');
  const btnCancelAdd = document.getElementById('btnCancelAddModal');

  function openAddModal() {
    if (addModal) addModal.style.display = 'flex';
  }
  function closeAddModal() {
    if (addModal) addModal.style.display = 'none';
  }

  if (btnOpenAdd) btnOpenAdd.addEventListener('click', openAddModal);
  if (btnCloseAdd) btnCloseAdd.addEventListener('click', closeAddModal);
  if (btnCancelAdd) btnCancelAdd.addEventListener('click', closeAddModal);

  // 3. Modal Edit Method
  const editModal = document.getElementById('editModalBackdrop');
  const editForm = document.getElementById('editMethodForm');
  const btnCloseEdit = document.getElementById('btnCloseEditModal');
  const btnCancelEdit = document.getElementById('btnCancelEditModal');

  const editName = document.getElementById('editName');
  const editCode = document.getElementById('editCode');
  const editType = document.getElementById('editType');
  const editAccountNumber = document.getElementById('editAccountNumber');
  const editAccountName = document.getElementById('editAccountName');
  const editPrice = document.getElementById('editPrice');
  const editStatus = document.getElementById('editStatus');
  const editDescription = document.getElementById('editDescription');

  function closeEditModal() {
    if (editModal) editModal.style.display = 'none';
  }

  if (btnCloseEdit) btnCloseEdit.addEventListener('click', closeEditModal);
  if (btnCancelEdit) btnCancelEdit.addEventListener('click', closeEditModal);

  // Attach click listener to each Edit button
  document.querySelectorAll('.btn-action-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name') || '';
      const code = this.getAttribute('data-code') || '';
      const type = this.getAttribute('data-type') || 'e-wallet';
      const accountNumber = this.getAttribute('data-account-number') || '';
      const accountName = this.getAttribute('data-account-name') || '';
      const price = this.getAttribute('data-price') || '0';
      const status = this.getAttribute('data-status') || 'active';
      const description = this.getAttribute('data-description') || '';

      if (editForm) {
        editForm.action = `/admin/payment-method/${id}`;
      }

      if (editName) editName.value = name;
      if (editCode) editCode.value = code;
      if (editType) editType.value = type;
      if (editAccountNumber) editAccountNumber.value = accountNumber;
      if (editAccountName) editAccountName.value = accountName;
      if (editPrice) editPrice.value = price;
      if (editStatus) editStatus.value = status;
      if (editDescription) editDescription.value = description;

      if (editModal) editModal.style.display = 'flex';
    });
  });

  // 4. Modal Delete Method
  const deleteModal = document.getElementById('deleteModalBackdrop');
  const deleteForm = document.getElementById('deleteMethodForm');
  const deleteNameText = document.getElementById('deleteMethodNameText');
  const btnCloseDelete = document.getElementById('btnCloseDeleteModal');
  const btnCancelDelete = document.getElementById('btnCancelDeleteModal');

  function closeDeleteModal() {
    if (deleteModal) deleteModal.style.display = 'none';
  }

  if (btnCloseDelete) btnCloseDelete.addEventListener('click', closeDeleteModal);
  if (btnCancelDelete) btnCancelDelete.addEventListener('click', closeDeleteModal);

  document.querySelectorAll('.btn-action-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name') || '';

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Hapus Metode',
          text: `Metode pembayaran "${name}" tidak dapat dikembalikan.`,
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
        }).then(function (result) {
          if (result.isConfirmed) {
            if (deleteForm) {
              deleteForm.action = `/admin/payment-method/${id}`;
              deleteForm.submit();
            }
          }
        });
      } else {
        if (deleteForm) {
          deleteForm.action = `/admin/payment-method/${id}`;
        }
        if (deleteNameText) {
          deleteNameText.innerText = name;
        }
        if (deleteModal) deleteModal.style.display = 'flex';
      }
    });
  });

  // 5. Close Modals on Backdrop Click
  window.addEventListener('click', function (e) {
    if (addModal && e.target === addModal) closeAddModal();
    if (editModal && e.target === editModal) closeEditModal();
    if (deleteModal && e.target === deleteModal) closeDeleteModal();
  });

  // 6. Close Modals on Escape Key
  window.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeAddModal();
      closeEditModal();
      closeDeleteModal();
    }
  });
});

