<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/lenis.css', 'resources/css/admin/dashboard.css', 'resources/css/admin/settings.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

@php
  $currentTab = $tab ?? request('tab', 'website');
@endphp

  <div class="admin-layout">
    @include('admin.sidebar', ['active' => 'settings'])

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="admin-main">
      <!-- Header -->
      <header class="admin-header">
        <div class="header-left">
          <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka atau tutup navigasi" aria-controls="adminSidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
          <span class="header-title">Pengaturan</span>
        </div>

        <div class="header-right">
          <!-- Profil -->
          <div class="dropdown" data-dropdown>
            <button type="button" class="header-user-badge" aria-haspopup="true" aria-expanded="false" data-dropdown-trigger>
              <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
              <div class="header-user-info">
                <span class="header-user-name">{{ Auth::user()->name ?? 'Administrator' }}</span>
                <span class="header-user-role">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
              </div>
              <div class="header-user-chevron">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
              </div>
            </button>
            <div class="dropdown-menu" role="menu" hidden>
              <a href="/" target="_blank" rel="noopener" class="dropdown-item" role="menuitem">Lihat Toko</a>
              <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="dropdown-item danger" role="menuitem">Keluar</button>
              </form>
            </div>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="settings-container">
        <!-- Judul Halaman -->
        <div class="page-head" style="margin-bottom: 8px;">
          <div>
            <div class="breadcrumb-label">Admin Dashboard &bull; Konfigurasi</div>
            <h1 class="page-title">Pengaturan & Akun</h1>
            <p class="welcome-text">Kelola preferensi toko Maybeads serta pengaturan profil dan keamanan akun Anda.</p>
          </div>
        </div>

        <!-- Navigasi Tab Pengaturan Website & Akun -->
        <div class="settings-tabs-wrapper" role="tablist" aria-label="Tab Pengaturan">
          <button type="button" class="settings-tab-btn {{ $currentTab !== 'account' ? 'active' : '' }}" role="tab" id="tabBtnWebsite" data-tab-target="panelWebsite" aria-selected="{{ $currentTab !== 'account' ? 'true' : 'false' }}" aria-controls="panelWebsite">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="2" y1="12" x2="22" y2="12"></line>
              <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
            </svg>
            <span>Pengaturan Website</span>
          </button>

          <button type="button" class="settings-tab-btn {{ $currentTab === 'account' ? 'active' : '' }}" role="tab" id="tabBtnAccount" data-tab-target="panelAccount" aria-selected="{{ $currentTab === 'account' ? 'true' : 'false' }}" aria-controls="panelAccount">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>Akun & Keamanan</span>
          </button>
        </div>

        <!-- ==========================================
             PANEL 1: PENGATURAN WEBSITE
             ========================================== -->
        <div class="settings-panel {{ $currentTab !== 'account' ? 'active' : '' }}" id="panelWebsite" role="tabpanel" aria-labelledby="tabBtnWebsite">
          <form id="formWebsiteSettings" onsubmit="handleSaveSettings(event, 'Pengaturan Website berhasil diperbarui!')">
            <div class="settings-grid">
              <!-- Kartu Informasi Toko -->
              <div class="settings-card">
                <div class="settings-card-header">
                  <div class="settings-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                      <line x1="3" y1="9" x2="21" y2="9"></line>
                      <line x1="9" y1="21" x2="9" y2="9"></line>
                    </svg>
                  </div>
                  <div>
                    <h3 class="settings-card-title">Identitas Website & Toko</h3>
                    <p class="settings-card-desc">Informasi dasar yang tampil pada halaman toko utama Maybeads.</p>
                  </div>
                </div>
                <div class="settings-card-body">
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label" for="siteName">Nama Toko / Brand</label>
                      <input type="text" class="form-input" id="siteName" name="site_name" value="Maybead.s" required>
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="siteTagline">Slogan / Tagline</label>
                      <input type="text" class="form-input" id="siteTagline" name="site_tagline" value="Handcrafted Beads & Aesthetic Jewelry">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="siteDesc">Deskripsi Singkat Toko</label>
                    <textarea class="form-textarea" id="siteDesc" name="site_desc" rows="3">Koleksi aksesoris manik-manik buatan tangan (handcrafted) berkualitas tinggi, trendi, dan estetik untuk menemani hari-harimu.</textarea>
                    <div class="form-hint">Akan digunakan untuk ringkasan di footer dan metadata SEO.</div>
                  </div>
                </div>
              </div>

              <!-- Kartu Kontak & Operasional -->
              <div class="settings-card">
                <div class="settings-card-header">
                  <div class="settings-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                  </div>
                  <div>
                    <h3 class="settings-card-title">Kontak & Jalur Pemesanan</h3>
                    <p class="settings-card-desc">Nomor WhatsApp dan tautan media sosial untuk konfirmasi pesanan pelanggan.</p>
                  </div>
                </div>
                <div class="settings-card-body">
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label" for="waHotline">WhatsApp Customer Service</label>
                      <input type="text" class="form-input" id="waHotline" name="wa_hotline" value="+62 812-3456-7890">
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="supportEmail">Email Support</label>
                      <input type="email" class="form-input" id="supportEmail" name="support_email" value="contact@maybeads.com">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label" for="instagramUrl">Akun Instagram</label>
                      <input type="text" class="form-input" id="instagramUrl" name="instagram_url" value="https://instagram.com/maybead.s">
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="tiktokUrl">Akun TikTok</label>
                      <input type="text" class="form-input" id="tiktokUrl" name="tiktok_url" value="https://tiktok.com/@maybead.s">
                    </div>
                  </div>

                  <div class="toggle-item" style="margin-top: 10px;">
                    <div class="toggle-info">
                      <h4>Status Toko (Online)</h4>
                      <p>Jika dimatikan, halaman checkout sementara akan menampilkan pesan bahwa toko sedang libur.</p>
                    </div>
                    <label class="switch" aria-label="Toggle status toko">
                      <input type="checkbox" name="store_active" checked>
                      <span class="slider"></span>
                    </label>
                  </div>
                </div>
                <div class="settings-card-footer">
                  <button type="submit" class="btn-primary">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                      <polyline points="17 21 17 13 7 13 7 21"></polyline>
                      <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span>Simpan Pengaturan Website</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- ==========================================
             PANEL 2: PENGATURAN AKUN
             ========================================== -->
        <div class="settings-panel {{ $currentTab === 'account' ? 'active' : '' }}" id="panelAccount" role="tabpanel" aria-labelledby="tabBtnAccount">
          <form id="formAccountSettings" onsubmit="handleSaveSettings(event, 'Data profil dan keamanan akun berhasil disimpan!')">
            <div class="settings-grid">
              <!-- Kartu Informasi Profil -->
              <div class="settings-card">
                <div class="settings-card-header">
                  <div class="settings-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                      <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                  </div>
                  <div>
                    <h3 class="settings-card-title">Profil Administrator</h3>
                    <p class="settings-card-desc">Informasi identitas akun yang digunakan untuk mengelola dashboard Maybeads.</p>
                  </div>
                </div>
                <div class="settings-card-body">
                  <div class="account-profile-header">
                    <div class="account-avatar-large">
                      {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="account-profile-meta">
                      <h3>{{ Auth::user()->name ?? 'Administrator' }}</h3>
                      <p>{{ Auth::user()->email ?? 'admin@maybeads.com' }}</p>
                      <div class="role-badge">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
                      </div>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label" for="userName">Nama Lengkap</label>
                      <input type="text" class="form-input" id="userName" name="name" value="{{ Auth::user()->name ?? 'Administrator' }}" required>
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="userEmail">Alamat Email</label>
                      <input type="email" class="form-input" id="userEmail" name="email" value="{{ Auth::user()->email ?? 'admin@maybeads.com' }}" required>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Kartu Keamanan & Password -->
              <div class="settings-card">
                <div class="settings-card-header">
                  <div class="settings-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                      <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                  </div>
                  <div>
                    <h3 class="settings-card-title">Keamanan & Kata Sandi</h3>
                    <p class="settings-card-desc">Perbarui kata sandi akun secara berkala untuk menjaga keamanan akses.</p>
                  </div>
                </div>
                <div class="settings-card-body">
                  <div class="form-row">
                    <div class="form-group">
                      <label class="form-label" for="currentPassword">Kata Sandi Saat Ini</label>
                      <input type="password" class="form-input" id="currentPassword" name="current_password" placeholder="Masukkan kata sandi lama">
                    </div>
                    <div class="form-group">
                      <label class="form-label" for="newPassword">Kata Sandi Baru</label>
                      <input type="password" class="form-input" id="newPassword" name="new_password" placeholder="Minimal 8 karakter">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="confirmPassword">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" class="form-input" id="confirmPassword" name="new_password_confirmation" placeholder="Ulangi kata sandi baru">
                  </div>
                </div>
                <div class="settings-card-footer">
                  <button type="submit" class="btn-primary">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                      <polyline points="17 21 17 13 7 13 7 21"></polyline>
                      <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    <span>Simpan Perubahan Akun</span>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <script>
    // Tab Switching Logic with URL sync
    document.addEventListener('DOMContentLoaded', function () {
      var tabBtns = document.querySelectorAll('.settings-tab-btn');
      var panels = document.querySelectorAll('.settings-panel');

      tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var targetId = this.dataset.tabTarget;
          var tabType = targetId === 'panelAccount' ? 'account' : 'website';

          // Update active state tab buttons
          tabBtns.forEach(function (b) {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
          });
          this.classList.add('active');
          this.setAttribute('aria-selected', 'true');

          // Update panels
          panels.forEach(function (p) {
            p.classList.remove('active');
          });
          var targetPanel = document.getElementById(targetId);
          if (targetPanel) {
            targetPanel.classList.add('active');
          }

          // Sinkronisasi query param di URL tanpa reload halaman
          var url = new URL(window.location.href);
          url.searchParams.set('tab', tabType);
          window.history.replaceState({}, '', url.toString());
        });
      });
    });

    // Handle form save demonstration with SweetAlert
    function handleSaveSettings(event, message) {
      event.preventDefault();
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: message,
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          customClass: {
            popup: 'admin-swal-popup'
          }
        });
      } else {
        alert(message);
      }
    }
  </script>
</body>
</html>
