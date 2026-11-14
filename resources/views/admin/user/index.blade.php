<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Data User — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/user-index.css', 'resources/js/admin/user-index.js'])
</head>
<body>

@php
  $usersJson = json_encode($users ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
@endphp

{{-- Hidden data store untuk JavaScript --}}
<div id="userDataStore"
     data-users="{{ $usersJson }}"
     hidden>
</div>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'user'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    {{-- ====== HEADER ====== --}}
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle"
                aria-label="Toggle navigasi" aria-controls="adminSidebar">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard</span>
      </div>

      <div class="header-right">
        {{-- Notifikasi --}}
        <div class="dropdown" data-dropdown>
          <button type="button" class="notification-btn" aria-label="Notifikasi" data-dropdown-trigger>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notification-badge" data-notif-badge>1</span>
          </button>
        </div>
      </div>
    </header>

    {{-- ====== MAIN CONTENT ====== --}}
    <main class="user-content">

      {{-- Breadcrumb --}}
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">&gt;</span>
        <span class="breadcrumb-current">User</span>
      </nav>

      {{-- Success & Error Alerts --}}
      @if (session('success'))
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
              });
            }
          });
        </script>
      @endif

      @if (session('error'))
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonColor: '#23477d'
              });
            }
          });
        </script>
      @endif

      {{-- Page Heading + Search Bar --}}
      <div class="user-page-head">
        <h1 class="user-page-title">Data User</h1>

        <div class="user-search-wrap">
          <span class="search-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </span>
          <input type="text"
                 id="userSearchInput"
                 class="user-search-input"
                 placeholder="Cari Berdasarkan ..."
                 autocomplete="off"
                 aria-label="Cari data user">
        </div>
      </div>

      {{-- ====== TABLE LAYOUT ====== --}}
      <div class="user-table-wrap">
        <table class="user-table" id="userTable">
          <thead>
            <tr>
              <th class="cb-col">
                <input type="checkbox" class="tbl-check" id="selectAll" aria-label="Pilih semua user">
              </th>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Alamat</th>
              <th>Role</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="userTableBody">
            @forelse ($users as $user)
              @php
                $avatarInitial = strtoupper(substr($user['name'] ?? 'U', 0, 1));
                $colors = ['#2563eb', '#7c3aed', '#059669', '#ea580c', '#db2777', '#0891b2'];
                $bgColor = $colors[($user['id'] ?? 1) % count($colors)];
              @endphp
              <tr data-id="{{ $user['id'] }}">
                <td class="cb-col">
                  <input type="checkbox" class="tbl-check row-cb" data-id="{{ $user['id'] }}" aria-label="Pilih {{ $user['name'] }}">
                </td>
                <td class="col-id">{{ $user['formatted_id'] ?? ('IDP' . str_pad($user['id'], 4, '0', STR_PAD_LEFT)) }}</td>
                <td class="col-user">
                  <div class="user-meta-cell">
                    <div class="user-cell-avatar" style="background-color: {{ $bgColor }}">
                      {{ $avatarInitial }}
                    </div>
                    <span class="user-cell-name">{{ $user['name'] }}</span>
                  </div>
                </td>
                <td class="col-email">{{ $user['email'] }}</td>
                <td class="col-address" title="{{ $user['address'] }}">{{ $user['address'] }}</td>
                <td class="col-role">{{ $user['role'] ?? 'User' }}</td>
                <td class="col-action">
                  <div class="action-btns">
                    <a href="{{ route('admin.user.detail', $user['id']) }}" class="btn-detail-struk">Detail</a>
                    <button type="button" class="btn-delete-icon" data-delete-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}" title="Hapus User">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                        <path d="M9 6V4h6v2"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="user-empty">
                  Belum ada data user.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        {{-- Pagination --}}
        <div class="pagination-bar" id="paginationBar">
          <div class="pagination-left">
            <button type="button" class="btn-all" id="btnPagAll">ALL</button>
            <button type="button" class="btn-show-less" id="btnPagLess">Show Less</button>
            <div class="pagination-sep"></div>
            <span class="pagination-info" id="paginationInfo">
              Menampilkan {{ count($users) > 0 ? min(8, count($users)) : 0 }} user dari {{ count($users) }}
            </span>
          </div>
          <div class="pagination-pages" id="paginationPages" aria-label="Navigasi halaman">
            {{-- Dirender oleh JavaScript --}}
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

</body>
</html>

