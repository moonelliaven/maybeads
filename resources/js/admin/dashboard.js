import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// Expose SweetAlert globally
window.Swal = Swal;

/**
 * Admin Dashboard & Sidebar Interactivity - Maybead.s
 * Includes: Lenis Smooth Scroll, Sidebar Toggles, Dropdowns, Tabs, & Metrics
 */

(function () {
  'use strict';

  function initDashboard() {
    var reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ==========================================================================
       1. Lenis Smooth Scrolling
       ========================================================================== */
    var lenis = null;
    if (typeof Lenis !== 'undefined' && !reduce) {
      lenis = new Lenis({
        duration: 1.2,
        easing: function (t) {
          return Math.min(1, 1.001 - Math.pow(2, -10 * t));
        },
        orientation: 'vertical',
        gestureOrientation: 'vertical',
        smoothWheel: true,
        touchMultiplier: 1.5,
      });

      function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
      }
      requestAnimationFrame(raf);

      // Expose to window if needed
      window.__adminLenis = lenis;
    }

    /* ==========================================================================
       2. Sidebar Toggle (Desktop Collapse & Mobile Drawer)
       ========================================================================== */
    var sidebar = document.getElementById('adminSidebar');
    var toggleBtn = document.getElementById('sidebarToggle');
    var backdrop = document.getElementById('sidebarBackdrop');

    if (toggleBtn && sidebar) {
      toggleBtn.addEventListener('click', function () {
        if (window.innerWidth <= 992) {
          sidebar.classList.toggle('open');
          if (backdrop) {
            backdrop.classList.toggle('active');
          }
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

    /* ==========================================================================
       3. Dropdown Menus (Notifikasi, Profil Header & Menu Sidebar)
       ========================================================================== */
    var dropdowns = document.querySelectorAll('[data-dropdown]');
    var sidebarFooter = document.getElementById('sidebarFooter');
    var sidebarUserBadge = document.getElementById('sidebarUserBadge');
    var sidebarUserMenu = document.getElementById('sidebarUserMenu');

    function closeSidebarUserMenu() {
      if (sidebarUserMenu && sidebarFooter && sidebarUserBadge) {
        sidebarUserMenu.setAttribute('hidden', '');
        sidebarFooter.classList.remove('menu-open');
        sidebarUserBadge.setAttribute('aria-expanded', 'false');
      }
    }

    function openSidebarUserMenu() {
      if (sidebarUserMenu && sidebarFooter && sidebarUserBadge) {
        // Tutup dropdown header jika ada yang terbuka
        dropdowns.forEach(function (other) {
          var otherMenu = other.querySelector('.dropdown-menu');
          var otherTrigger = other.querySelector('[data-dropdown-trigger]');
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
      var isOpen = !sidebarUserMenu.hasAttribute('hidden');
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
      var trigger = drop.querySelector('[data-dropdown-trigger]');
      var menu = drop.querySelector('.dropdown-menu');

      if (!trigger || !menu) return;

      trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = !menu.hasAttribute('hidden');

        // Tutup dropdown lain yang terbuka & menu sidebar
        dropdowns.forEach(function (other) {
          if (other !== drop) {
            var otherMenu = other.querySelector('.dropdown-menu');
            var otherTrigger = other.querySelector('[data-dropdown-trigger]');
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

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
      dropdowns.forEach(function (drop) {
        var menu = drop.querySelector('.dropdown-menu');
        var trigger = drop.querySelector('[data-dropdown-trigger]');
        if (menu && !menu.hasAttribute('hidden') && !drop.contains(e.target)) {
          menu.setAttribute('hidden', '');
          if (trigger) trigger.setAttribute('aria-expanded', 'false');
        }
      });

      if (sidebarFooter && !sidebarFooter.contains(e.target)) {
        closeSidebarUserMenu();
      }
    });

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        dropdowns.forEach(function (drop) {
          var menu = drop.querySelector('.dropdown-menu');
          var trigger = drop.querySelector('[data-dropdown-trigger]');
          if (menu && !menu.hasAttribute('hidden')) {
            menu.setAttribute('hidden', '');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
          }
        });
        closeSidebarUserMenu();
      }
    });

    // Tandai notifikasi dibaca
    var markReadBtn = document.querySelector('[data-mark-read]');
    if (markReadBtn) {
      markReadBtn.addEventListener('click', function (e) {
        e.preventDefault();
        var notifBadge = document.querySelector('[data-notif-badge]');
        if (notifBadge) {
          notifBadge.style.display = 'none';
        }
      });
    }

    /* ==========================================================================
       4. Sembunyikan / Tampilkan Nominal (Eye Toggle)
       ========================================================================== */
    var eyeBtns = document.querySelectorAll('[data-toggle-amounts]');
    eyeBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var card = this.closest('[data-amount-card]');
        if (!card) return;
        var isHidden = card.classList.toggle('amounts-hidden');
        this.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
      });
    });

    /* ==========================================================================
       5. Filter Tab Pesanan Terakhir
       ========================================================================== */
    var tabs = document.querySelectorAll('.tab[data-filter]');
    var orderRows = document.querySelectorAll('#ordersList .order-row-item');
    var emptyState = document.getElementById('ordersEmpty');

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        this.classList.add('active');
        this.setAttribute('aria-selected', 'true');

        var filter = this.dataset.filter;
        var visibleCount = 0;

        orderRows.forEach(function (row) {
          var status = row.dataset.status;
          if (filter === 'all' || status === filter) {
            row.style.display = '';
            visibleCount++;
          } else {
            row.style.display = 'none';
          }
        });

        if (emptyState) {
          emptyState.hidden = visibleCount > 0;
        }
      });
    });

    /* ==========================================================================
       6. Jam & Salam Dinamis (Clock & Greeting)
       ========================================================================== */
    var clockEl = document.querySelector('[data-clock]');
    var greetingEl = document.querySelector('[data-greeting]');

    function updateClock() {
      var now = new Date();

      if (clockEl) {
        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        var months = [
          'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
          'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'
        ];
        var dayName = days[now.getDay()];
        var date = String(now.getDate()).padStart(2, '0');
        var monthName = months[now.getMonth()];
        var year = now.getFullYear();
        var hours = String(now.getHours()).padStart(2, '0');
        var mins = String(now.getMinutes()).padStart(2, '0');

        clockEl.textContent = dayName + ', ' + date + ' ' + monthName + ' ' + year + ' • ' + hours + ':' + mins;
      }

      if (greetingEl) {
        var h = now.getHours();
        var greeting = 'Selamat Datang';
        if (h >= 4 && h < 11) greeting = 'Selamat Pagi';
        else if (h >= 11 && h < 15) greeting = 'Selamat Siang';
        else if (h >= 15 && h < 18) greeting = 'Selamat Sore';
        else greeting = 'Selamat Malam';
        greetingEl.textContent = greeting;
      }
    }

    updateClock();
    setInterval(updateClock, 30000);

    /* ==========================================================================
       7. Count-Up Angka
       ========================================================================== */
    if (!reduce) {
      var countEls = document.querySelectorAll('[data-count]');
      countEls.forEach(function (el) {
        var target = parseInt(el.dataset.count, 10);
        if (isNaN(target) || target <= 0) return;

        var start = 0;
        var duration = 900;
        var startTime = null;

        function step(timestamp) {
          if (!startTime) startTime = timestamp;
          var progress = Math.min((timestamp - startTime) / duration, 1);
          // Easing ease-out quad
          var current = Math.floor(progress * (2 - progress) * target);
          el.textContent = current;
          if (progress < 1) {
            requestAnimationFrame(step);
          } else {
            el.textContent = target;
          }
        }
        requestAnimationFrame(step);
      });
    }

    /* ==========================================================================
       8. SweetAlert2: Konfirmasi Logout & Notifikasi Flash
       ========================================================================== */
    var logoutForms = document.querySelectorAll('form[action*="logout"], .sidebar-logout-form');
    logoutForms.forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari sistem admin?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Kembali',
            reverseButtons: true,
            customClass: {
              popup: 'admin-swal-popup'
            }
          }).then(function (result) {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        } else {
          if (confirm('Apakah Anda yakin ingin keluar dari sistem admin? Pilih Batal untuk Kembali.')) {
            form.submit();
          }
        }
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
  } else {
    initDashboard();
  }
})();
