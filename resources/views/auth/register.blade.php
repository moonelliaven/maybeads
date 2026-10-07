<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Daftar — Maybeads</title>
  <meta name="description" content="Buat akun Maybeads dan mulai koleksi aksesoris Y2K impianmu.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700;9..144,900&family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  @vite(['resources/css/lenis.css', 'resources/css/auth/register.css'])
</head>
<body>

  <!-- Navigation (sama dengan landing page) -->
  @include('partials.nav')

  <main class="auth-page">
    <section class="auth-card is-register">

      <!-- Kolom form -->
      <div class="auth-form">
        <div class="auth-inner">
          <a class="auth-brand" href="{{ url('/') }}">Maybead<span>.</span>s</a>
          <h1 class="auth-title">Buat Akun Baru</h1>
          <p class="auth-sub">Gabung dan mulai koleksi aksesoris Y2K impianmu</p>

          <form method="POST" action="{{ route('register') }}" id="form" class="auth-fields" novalidate>
            @csrf

            <div class="f">
              <label for="name">Nama lengkap</label>
              <div class="inp @error('name') err @enderror">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Masukkan nama kamu" autocomplete="name" required autofocus>
              </div>
              @error('name')<p class="msg">{{ $message }}</p>@enderror
            </div>

            <div class="f">
              <label for="email">Email</label>
              <div class="inp @error('email') err @enderror">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="email@kamu.com" autocomplete="email" required>
              </div>
              @error('email')<p class="msg">{{ $message }}</p>@enderror
            </div>

            <div class="f">
              <label for="password">Kata sandi</label>
              <div class="inp @error('password') err @enderror">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/><circle cx="12" cy="16" r="1"/></svg>
                <input id="password" name="password" type="password" placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                <button class="eye" type="button" id="eye" aria-label="Tampilkan kata sandi" aria-pressed="false">
                  <svg class="ic-show" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                  <svg class="ic-hide" viewBox="0 0 24 24"><path d="M3 3l18 18"/><path d="M10.6 5.1A10.4 10.4 0 0 1 12 5c6.5 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.6 6.6A17 17 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                </button>
              </div>
              @error('password')<p class="msg">{{ $message }}</p>@enderror
            </div>

            <div class="f">
              <label for="password_confirmation">Konfirmasi kata sandi</label>
              <div class="inp">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/><path d="m9.5 16 1.8 1.8 3.2-3.3"/></svg>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi kata sandi" autocomplete="new-password" required>
                <button class="eye" type="button" id="eye2" aria-label="Tampilkan konfirmasi kata sandi" aria-pressed="false">
                  <svg class="ic-show" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                  <svg class="ic-hide" viewBox="0 0 24 24"><path d="M3 3l18 18"/><path d="M10.6 5.1A10.4 10.4 0 0 1 12 5c6.5 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.6 6.6A17 17 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                </button>
              </div>
            </div>

            <button class="submit" type="submit" id="register-submit">Daftar</button>
          </form>

          <div class="or" aria-hidden="true">atau</div>

          <a class="google" href="{{ Route::has('auth.google') ? route('auth.google') : '#' }}" id="register-google">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path fill="#4285F4" d="M22.5 12.2c0-.8-.1-1.5-.2-2.2H12v4.2h5.9a5 5 0 0 1-2.2 3.3v2.7h3.5c2.1-1.9 3.3-4.7 3.3-8z"/>
              <path fill="#34A853" d="M12 23c3 0 5.4-1 7.2-2.7l-3.5-2.7c-1 .7-2.2 1-3.7 1-2.8 0-5.2-1.9-6-4.5H2.4v2.8A11 11 0 0 0 12 23z"/>
              <path fill="#FBBC05" d="M6 14.1a6.6 6.6 0 0 1 0-4.2V7.1H2.4a11 11 0 0 0 0 9.8L6 14.1z"/>
              <path fill="#EA4335" d="M12 5.4c1.6 0 3 .6 4.2 1.6l3.1-3.1A11 11 0 0 0 2.4 7.1L6 9.9c.8-2.6 3.2-4.5 6-4.5z"/>
            </svg>
            Daftar dengan Google
          </a>

          <p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>

          <ul class="auth-foot">
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li><a href="#">Ketentuan</a></li>
            <li><a href="#">Privasi</a></li>
          </ul>
        </div>
      </div>

      <!-- Kolom ilustrasi -->
      <div class="auth-art" aria-hidden="true">
        <img src="{{ asset('images/auth/auth-illustration.jpg') }}" alt="" loading="eager">
      </div>
    </section>
  </main>

  <!-- Lenis Script -->
  <script src="{{ asset('vendor/lenis/lenis.min.js') }}"></script>
  <script>if(typeof Lenis==='undefined'){document.write('<script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"><\/script>');}</script>

  @vite(['resources/js/auth/register.js'])
</body>
</html>
