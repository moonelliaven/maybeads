<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Manajemen Pengguna — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  @vite(['resources/css/admin/management.css', 'resources/js/admin/dashboard.js'])
</head>
<body>

<div class="admin-layout">
  @include('admin.sidebar', ['active' => 'users'])

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="admin-main">
    <header class="admin-header">
      <div class="header-left">
        <button type="button" class="btn-sidebar-toggle" id="sidebarToggle" aria-label="Buka navigasi">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </button>
        <span class="header-title">Admin Dashboard / Pengguna</span>
      </div>

      <div class="header-right">
        <div class="header-user-badge">
          <div class="header-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
          <div class="header-user-info">
            <span class="header-user-name">{{ Auth::user()->name ?? 'Administrator' }}</span>
            <span class="header-user-role">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span>
          </div>
        </div>
      </div>
    </header>

    <main class="page-container">
      @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px; font-weight: 500;">
          {{ session('error') }}
        </div>
      @endif

      @if(isset($errors) && $errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 13.5px;">
          <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="page-header">
        <div>
          <h1 class="page-title">Manajemen User</h1>
          <p class="page-subtitle">Kelola akun administrator, kasir, dan pelanggan terdaftar</p>
        </div>
        <div class="page-actions">
          <button type="button" class="btn-primary" onclick="openCreateUserModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
              <line x1="12" y1="5" x2="12" y2="19"></line>
              <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah User
          </button>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Total Pengguna</span>
            <span class="stat-value">{{ number_format($stats['total'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Administrator</span>
            <span class="stat-value">{{ number_format($stats['admin'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap purple">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
            </svg>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-info">
            <span class="stat-label">Regular User</span>
            <span class="stat-value">{{ number_format($stats['user'] ?? 0) }}</span>
          </div>
          <div class="stat-icon-wrap green">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </div>
        </div>
      </div>

      <!-- Filter Toolbar -->
      <div class="toolbar-card">
        <form method="GET" action="{{ route('admin.user.index') }}" class="search-form">
          <div class="search-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="search" placeholder="Cari nama atau email pengguna..." value="{{ request('search') }}">
          </div>
        </form>

        <form method="GET" action="{{ route('admin.user.index') }}" class="filter-group">
          @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
          @endif
          <select name="role" class="filter-select" onchange="this.form.submit()">
            <option value="semua" {{ request('role') === 'semua' || !request('role') ? 'selected' : '' }}>Semua Role</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
          </select>
        </form>
      </div>

      <!-- Data Table -->
      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Pengguna</th>
              <th>Email</th>
              <th>Role</th>
              <th>Terdaftar Pada</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr>
                <td><strong>#USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                <td>
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                      <div style="font-weight: 700; color: #1e293b;">{{ $user->name }}</div>
                      @if($user->id === auth()->id())
                        <span style="font-size: 10.5px; background: #e0f2fe; color: #0284c7; padding: 2px 6px; border-radius: 4px; font-weight: 700;">Akun Anda</span>
                      @endif
                    </div>
                  </div>
                </td>
                <td style="color: #475569;">{{ $user->email }}</td>
                <td>
                  <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                    <span class="badge-dot"></span>
                    {{ ucfirst($user->role) }}
                  </span>
                </td>
                <td style="font-size: 12.5px; color: #64748b;">
                  {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                </td>
                <td>
                  <div class="table-actions">
                    <button type="button" class="btn-action" title="Edit Pengguna" onclick='openEditUserModal(@json($user))'>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                      </svg>
                    </button>

                    @if($user->id !== auth()->id())
                      <form method="POST" action="{{ route('admin.user.destroy', $user->id) }}" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-action danger" title="Hapus">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                          </svg>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                  Tidak ada pengguna yang cocok dengan pencarian.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>

        @if($users->hasPages())
          <div class="pagination-wrap">
            {{ $users->links() }}
          </div>
        @endif
      </div>
    </main>
  </div>
</div>

<!-- Modal Tambah User -->
<div class="modal-backdrop" id="createUserModal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h3 class="modal-title">Tambah Pengguna Baru</h3>
      <button type="button" class="modal-close" onclick="closeCreateUserModal()">✕</button>
    </div>
    <form method="POST" action="{{ route('admin.user.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso">
        </div>
        <div class="form-group">
          <label class="form-label">Alamat Email</label>
          <input type="email" name="email" class="form-control" required placeholder="budi@example.com">
        </div>
        <div class="form-group">
          <label class="form-label">Password (Minimal 8 Karakter)</label>
          <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>
        <div class="form-group">
          <label class="form-label">Role Akses</label>
          <select name="role" class="form-control" required>
            <option value="user">User (Pelanggan)</option>
            <option value="admin">Administrator</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeCreateUserModal()">Batal</button>
        <button type="submit" class="btn-primary">Simpan User</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit User -->
<div class="modal-backdrop" id="editUserModal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h3 class="modal-title">Edit Pengguna</h3>
      <button type="button" class="modal-close" onclick="closeEditUserModal()">✕</button>
    </div>
    <form id="editUserForm" method="POST" action="">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" id="editUserName" name="name" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Alamat Email</label>
          <input type="email" id="editUserEmail" name="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label class="form-label">Ganti Password (Kosongkan jika tidak diganti)</label>
          <input type="password" name="password" class="form-control" placeholder="Biarkan kosong untuk mempertahankan">
        </div>
        <div class="form-group">
          <label class="form-label">Role Akses</label>
          <select id="editUserRole" name="role" class="form-control" required>
            <option value="user">User (Pelanggan)</option>
            <option value="admin">Administrator</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeEditUserModal()">Batal</button>
        <button type="submit" class="btn-primary">Update User</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'flex';
  }
  function closeCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'none';
  }
  function openEditUserModal(user) {
    document.getElementById('editUserForm').action = '/admin/users/' + user.id;
    document.getElementById('editUserName').value = user.name;
    document.getElementById('editUserEmail').value = user.email;
    document.getElementById('editUserRole').value = user.role;
    document.getElementById('editUserModal').style.display = 'flex';
  }
  function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
  }
</script>

</body>
</html>
