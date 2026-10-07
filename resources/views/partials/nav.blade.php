{{-- Header utama (dipakai di landing, login, register) --}}
@php
  // Di halaman landing pakai anchor lokal, di halaman lain arahkan ke landing
  $navBase = request()->routeIs('home') ? '' : url('/');
@endphp
<nav class="nav" id="nav">
  <div class="nav-in">
    <a class="logo" href="{{ $navBase }}#home">Maybead<span>.</span>s</a>

    <ul class="nav-links">
      <li><a href="{{ $navBase }}#home">Beranda</a></li>
      <li><a href="{{ $navBase }}#kontak">Kontak</a></li>
      <li>
        <a href="{{ $navBase }}#produk" class="nav-ext">Produk
          <svg viewBox="0 0 24 24"><path d="M7 17 17 7M8 7h9v9"/></svg>
        </a>
      </li>
    </ul>

    <div class="nav-actions">

    <!-- auth action -->
      @auth
          <!-- Admin OAuth -->
        @if(Auth::user()->isAdmin())
          <a class="btn btn-outline btn-sm" href="{{ route('admin.dashboard') }}">
            <svg viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/></svg>
            Dashboard
          </a>

          <!-- Auth User OAuth -->
        @else
          <span class="nav-user">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
            {{ Auth::user()->name }}
          </span>
        @endif
        <!-- if theres no login session -->
        <form method="POST" action="{{ route('logout') }}" class="inline-form">
          @csrf
          <!-- already session added or you logout -->
          <button type="submit" class="btn btn-ghost-red btn-sm">Keluar</button>
        </form>

      @else
      <!-- if theres no session -->
        @if(request()->routeIs('login'))
          <a class="btn btn-outline btn-sm" href="{{ route('register') }}" id="nav-register">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 20a7 7 0 0 1 14 0"/><path d="M19 4v4M17 6h4"/></svg>
            Daftar
          </a>
        @else
          <a class="btn btn-outline btn-sm" href="{{ route('login') }}" id="nav-login">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
            Login
          </a>
        @endif
      @endauth
      <a class="btn btn-primary btn-sm" href="{{ $navBase }}#produk" id="nav-order">Pesan Sekarang</a>
    </div>
  </div>
</nav>
