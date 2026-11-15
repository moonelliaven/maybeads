import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import '../../css/common/sweetalert-theme.css';

window.Swal = Swal;

/**
 * Standard Helper untuk Pop Up SweetAlert Maybeads
 * Mengikuti template kartu dengan icon lingkaran amber/kuning, teks judul dan subtitle,
 * tombol "Kembali" (abu-abu #5c5656) di sebelah kiri, dan tombol aksi di sebelah kanan.
 */
export const MaybeadsAlert = {
  /**
   * Konfirmasi Hapus Produk / Kategori / User / Metode / Item
   */
  confirmDelete({
    title = 'Hapus Produk',
    text = 'Produk yang dihapus tidak dapat dikembalikan.',
    confirmButtonText = 'Ya, Hapus',
    cancelButtonText = 'Kembali',
    onConfirm = null,
    onCancel = null
  } = {}) {
    return Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      showCancelButton: true,
      reverseButtons: true, // Kembali di kiri, Ya Hapus di kanan
      confirmButtonText: confirmButtonText,
      cancelButtonText: cancelButtonText,
      confirmButtonColor: '#d9383a',
      cancelButtonColor: '#5c5656',
      customClass: {
        popup: 'maybeads-swal-popup'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        if (typeof onConfirm === 'function') onConfirm();
      } else if (result.isDismissed) {
        if (typeof onCancel === 'function') onCancel();
      }
      return result;
    });
  },

  /**
   * Konfirmasi Keluar / Logout
   */
  confirmLogout({
    title = 'Keluar dari Akun Anda',
    text = 'Apakah Anda yakin ingin keluar dari Maybeads?',
    confirmButtonText = 'Ya, Keluar',
    cancelButtonText = 'Kembali',
    onConfirm = null
  } = {}) {
    return Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonText: confirmButtonText,
      cancelButtonText: cancelButtonText,
      confirmButtonColor: '#d9383a',
      cancelButtonColor: '#5c5656',
      customClass: {
        popup: 'maybeads-swal-popup'
      }
    }).then((result) => {
      if (result.isConfirmed && typeof onConfirm === 'function') {
        onConfirm();
      }
      return result;
    });
  },

  /**
   * Pop up Ketika User Memerlukan Sesi Login (cth: Tambah ke Keranjang saat belum login)
   */
  requireLogin({
    title = 'Silahkan Login Terlebih Dahulu',
    text = 'Anda perlu masuk ke akun untuk dapat menggunakan fitur ini.',
    confirmButtonText = 'Login',
    cancelButtonText = 'Kembali',
    loginUrl = '/login',
    onConfirm = null
  } = {}) {
    return Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      showCancelButton: true,
      reverseButtons: true,
      confirmButtonText: confirmButtonText,
      cancelButtonText: cancelButtonText,
      confirmButtonColor: '#244173',
      cancelButtonColor: '#5c5656',
      customClass: {
        popup: 'maybeads-swal-popup',
        confirmButton: 'swal-btn-navy'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        if (typeof onConfirm === 'function') {
          onConfirm();
        } else {
          window.location.href = loginUrl;
        }
      }
      return result;
    });
  },

  /**
   * Notifikasi Sukses / Berhasil
   */
  success({
    title = 'Berhasil!',
    text = 'Aksi telah berhasil dilakukan.',
    confirmButtonText = 'Selesai'
  } = {}) {
    return Swal.fire({
      icon: 'success',
      title: title,
      text: text,
      confirmButtonText: confirmButtonText,
      confirmButtonColor: '#244173',
      customClass: {
        popup: 'maybeads-swal-popup',
        confirmButton: 'swal-btn-navy'
      }
    });
  },

  /**
   * Notifikasi Error / Gagal
   */
  error({
    title = 'Gagal!',
    text = 'Terjadi kesalahan saat memproses data.',
    confirmButtonText: confirmButtonText = 'Kembali'
  } = {}) {
    return Swal.fire({
      icon: 'error',
      title: title,
      text: text,
      confirmButtonText: confirmButtonText,
      confirmButtonColor: '#5c5656',
      customClass: {
        popup: 'maybeads-swal-popup'
      }
    });
  }
};

window.MaybeadsAlert = MaybeadsAlert;
export default MaybeadsAlert;

