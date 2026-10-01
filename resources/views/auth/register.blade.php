<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Daftar — maybeads</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap" rel="stylesheet">

  @vite(['resources/css/lenis.css', 'resources/css/auth/register.css'])
</head>
<body>

  <svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="smile" viewBox="0 0 100 100">
      <circle cx="50" cy="50" r="47" fill="#ffe94a" /><circle cx="35" cy="40" r="5" /><circle cx="65" cy="40" r="5" />
      <path d="M24 58q26 30 52 0" fill="none" stroke="#0a0a0f" stroke-width="4" stroke-linecap="round" />
    </symbol>
    <symbol id="flower" viewBox="0 0 100 100">
      <g fill="#ff9ee0"><circle cx="50" cy="20" r="17" /><circle cx="80" cy="42" r="17" /><circle cx="68" cy="78" r="17" /><circle cx="32" cy="78" r="17" /><circle cx="20" cy="42" r="17" /></g>
      <circle cx="50" cy="52" r="14" fill="#ffe94a" />
    </symbol>
    <symbol id="cursor" viewBox="0 0 24 32">
      <path d="M2 2h3v3h3v3h3v3h3v3h3v3h-5v3h3v6h-4v-6h-3v3h-3v3H2z" fill="#fff" stroke="#0a0a0f" stroke-width="1.5" />
    </symbol>
  </svg>

  <nav id="nav">
    <div class="nav-in">
      <a class="logo" href="/#home">maybeads*</a>
      <ul>
        <li><a href="/#home">Home</a></li>
        <li><a href="/#about">About</a></li>
        <li><a href="/product">Product</a></li>
        <li><a href="/#contact">Contact</a></li>
      </ul>
      <div class="nav-actions">
        <a class="btn-login" href="/login" data-magnet>
          <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" /><path d="M5 20a7 7 0 0 1 14 0" /></svg>
          Login
        </a>
        <a class="btn" href="/product" data-magnet>Belanja</a>
      </div>
    </div>
  </nav>

  <main class="page">
    <div class="blob b1"></div>
    <div class="blob b2"></div>
    <div class="blob b3"></div>

    <div class="sticker s-smile bob" data-speed=".1" data-mouse="30"><svg class="i"><use href="#smile" /></svg></div>
    <div class="sticker s-flower bob" data-speed="-.18" data-mouse="-24"><svg class="i"><use href="#flower" /></svg></div>
    <div class="sticker s-cursor" data-mouse="46"><svg class="i"><use href="#cursor" /></svg></div>

    <div class="wrap">
      <div class="big" id="big" aria-hidden="true" data-t="maybeads"></div>

      <section class="card">
        <h1>Buat akun baru.</h1>
        <p class="sub">Gabung dan mulai koleksi aksesoris Y2K impianmu.</p>

        <form method="POST" action="{{ route('register') }}" id="form" novalidate>
          @csrf

          <div class="f">
            <label for="name">Nama lengkap</label>
            <div class="inp @error('name') err @enderror">
              <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nama kamu" autocomplete="name" required autofocus>
            </div>
            @error('name')<p class="msg">{{ $message }}</p>@enderror
          </div>

          <div class="f">
            <label for="email">Email</label>
            <div class="inp @error('email') err @enderror">
              <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="email@kamu.com" autocomplete="email" required>
            </div>
            @error('email')<p class="msg">{{ $message }}</p>@enderror
          </div>

          <div class="f">
            <label for="password">Kata sandi</label>
            <div class="inp @error('password') err @enderror">
              <input id="password" name="password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
              <button class="eye" type="button" id="eye" aria-label="Tampilkan kata sandi" aria-pressed="false">Lihat</button>
            </div>
            @error('password')<p class="msg">{{ $message }}</p>@enderror
          </div>

          <div class="f">
            <label for="password_confirmation">Konfirmasi kata sandi</label>
            <div class="inp">
              <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi kata sandi" autocomplete="new-password" required>
              <button class="eye" type="button" id="eye2" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false">Lihat</button>
            </div>
          </div>

          <button class="btn submit" type="submit" data-magnet>Daftar sekarang</button>
        </form>

        <div class="or" aria-hidden="true">atau</div>
        <a class="google" href="#">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path fill="#4285F4" d="M22.5 12.2c0-.8-.1-1.5-.2-2.2H12v4.2h5.9a5 5 0 0 1-2.2 3.3v2.7h3.5c2.1-1.9 3.3-4.7 3.3-8z"/>
            <path fill="#34A853" d="M12 23c3 0 5.4-1 7.2-2.7l-3.5-2.7c-1 .7-2.2 1-3.7 1-2.8 0-5.2-1.9-6-4.5H2.4v2.800A11 11 0 0 0 12 23z"/>
            <path fill="#FBBC05" d="M6 14.100a6.600 6.600 0 0 1 0-4.200V7.100H2.400a11 11 0 0 0 0 9.800L6 14.100z"/>
            <path fill="#EA4335" d="M12 5.400c1.600 0 3 .6 4.200 1.600l3.100-3.100A11 11 0 0 0 2.400 7.100L6 9.900c.8-2.600 3.200-4.500 6-4.500z"/>
          </svg>
          Daftar dengan Google
        </a>

        <p class="alt">Sudah punya akun? <a href="/login">Masuk di sini</a></p>
      </section>

      <a class="back" href="/">Kembali ke beranda</a>
    </div>
  </main>

  <!-- Lenis Script -->
  <script src="{{ asset('vendor/lenis/lenis.min.js') }}"></script>
  <script>if(typeof Lenis==='undefined'){document.write('<script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"><\/script>');}</script>

  @vite(['resources/js/auth/register.js'])
</body>
</html>
