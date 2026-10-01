<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>maybeads — aksesoris Y2K</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap" rel="stylesheet">

  @vite(['resources/css/lenis.css', 'resources/css/landing/home.css'])
</head>
<body>

  <!-- SVG Sprite -->
  <svg width="0" height="0" style="position: absolute;" aria-hidden="true">
    <defs>
      <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0" stop-color="#5b7cff" />
        <stop offset="1" stop-color="#1f3bff" />
      </linearGradient>
    </defs>
    <symbol id="smile" viewBox="0 0 100 100">
      <circle cx="50" cy="50" r="47" fill="#ffe94a" />
      <circle cx="35" cy="40" r="5" />
      <circle cx="65" cy="40" r="5" />
      <path d="M24 58q26 30 52 0" fill="none" stroke="#0a0a0f" stroke-width="4" stroke-linecap="round" />
    </symbol>
    <symbol id="flower" viewBox="0 0 100 100">
      <g fill="#ff9ee0">
        <circle cx="50" cy="20" r="17" />
        <circle cx="80" cy="42" r="17" />
        <circle cx="68" cy="78" r="17" />
        <circle cx="32" cy="78" r="17" />
        <circle cx="20" cy="42" r="17" />
      </g>
      <circle cx="50" cy="52" r="14" fill="#ffe94a" />
    </symbol>
    <symbol id="cursor" viewBox="0 0 24 32">
      <path d="M2 2h3v3h3v3h3v3h3v3h3v3h-5v3h3v6h-4v-6h-3v3h-3v3H2z" fill="#fff" stroke="#0a0a0f" stroke-width="1.5" />
    </symbol>
  </svg>

  <!-- Interactive Cursor Glow -->
  <div class="glow" id="glow"></div>

  <!-- Navigation Bar -->
  <nav id="nav">
    <div class="nav-in">
      <a class="logo" href="#home">maybeads*</a>
      <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="/product">Product</a></li>
        <li><a href="#contact">Contact</a></li>
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

  <!-- Hero / Banner -->
  <header class="hero" id="home">
    <div class="blob b1"></div>
    <div class="blob b2"></div>
    <div class="blob b3"></div>

    <div class="sticker s-blur bob" data-speed="-.25">
      <svg class="i"><use href="#smile" /></svg>
    </div>
    <div class="sticker s-smile bob" data-speed=".1" data-mouse="30">
      <svg class="i"><use href="#smile" /></svg>
    </div>
    <div class="sticker s-flower bob" data-speed="-.18" data-mouse="-24">
      <svg class="i"><use href="#flower" /></svg>
    </div>
    <div class="sticker s-cursor" data-speed="-.08" data-mouse="46">
      <svg class="i"><use href="#cursor" /></svg>
    </div>

    <p class="tag r">
      semoga kamu lolos<br>dari era yang membosankan.
    </p>

    <h1 class="title" id="title" aria-label="maybeads">
      <span class="ln" data-t="may-"></span>
      <span class="ln" data-t="bead.s"></span>
    </h1>

    <p class="tag l">
      aksesoris bertema Y2K<br>untuk kamu yang lahir terlambat.
    </p>

    <div class="cta-row">
      <a class="btn" href="/product" data-magnet>Lihat koleksi</a>
      <a class="btn ghost" href="#about" data-magnet>Tentang kami</a>
    </div>
  </header>

  <!-- Marquee Running Text 1 -->
  <div class="mq" aria-hidden="true">
    <div id="m1"></div>
  </div>

  <!-- Product Showcase (Horizontal Scroll) -->
  <section class="hs" id="product">
    <div class="hs-sticky">
      <h2 class="h split" data-split>Koleksi terbaru, geser terus.</h2>
      <div class="track" id="track">
        <article class="card">
          <div class="art" style="background: linear-gradient(135deg, #cfe6ff, #fff);" data-beads="#ff9ee0,#fff,#1f3bff"></div>
          <h3>Gelang Bubblegum</h3>
          <p>Manik akrilik pink dan putih, tali elastis.</p>
          <b>Rp 45.000</b>
        </article>

        <article class="card">
          <div class="art" style="background: linear-gradient(135deg, #fff3a6, #fff);" data-beads="#1f3bff,#cfe6ff,#fff"></div>
          <h3>Kalung Chrome Heart</h3>
          <p>Liontin hati metalik, rantai manik biru.</p>
          <b>Rp 79.000</b>
        </article>

        <article class="card">
          <div class="art" style="background: linear-gradient(135deg, #ffd0f0, #fff);" data-beads="#ffe94a,#fff,#0a0a0f"></div>
          <h3>Charm HP Cyber Cherry</h3>
          <p>Gantungan ponsel manik ceri dan bintang.</p>
          <b>Rp 35.000</b>
        </article>

        <article class="card">
          <div class="art" style="background: linear-gradient(135deg, #5b7cff, #1f3bff);" data-beads="#cfe6ff,#fff,#ff9ee0"></div>
          <h3>Jepit Kupu-kupu Set</h3>
          <p>Lima jepit rambut warna pastel.</p>
          <b>Rp 52.000</b>
        </article>

        <article class="card">
          <div class="art" style="background: linear-gradient(135deg, #e9ecf5, #fff);" data-beads="#0a0a0f,#1f3bff,#ffe94a"></div>
          <h3>Anklet Star Dust</h3>
          <p>Gelang kaki manik bintang, ukuran bebas.</p>
          <b>Rp 39.000</b>
        </article>

        <article class="card" style="display: grid; place-items: center; text-align: center;">
          <div>
            <h3>Masih banyak lagi</h3>
            <p style="margin-bottom: 18px;">Lihat semua aksesoris.</p>
            <a class="btn" href="/product" data-magnet>Buka Product</a>
          </div>
        </article>
      </div>
      <div class="bar">
        <div id="bar"></div>
      </div>
    </div>
  </section>

  <!-- Marquee Running Text 2 -->
  <div class="mq rev" aria-hidden="true" style="margin-top: 40px;">
    <div id="m2"></div>
  </div>

  <!-- About Section -->
  <section class="sec" id="about">
    <h2 class="h split" data-split>Dibuat kecil, gayanya besar.</h2>
    <div class="morph" id="morph">
      <svg class="morph-shape" viewBox="-110 -110 220 220" role="img" aria-label="Bentuk yang berubah dari lingkaran menjadi bintang saat discroll">
        <polygon id="shape" points="" />
        <circle cx="-28" cy="-12" r="9" fill="#fff" />
        <circle cx="28" cy="-12" r="9" fill="#fff" />
        <path d="M-34 18q34 34 68 0" fill="none" stroke="#fff" stroke-width="7" stroke-linecap="round" />
      </svg>
      <div class="steps">
        <div class="step rv">
          <h3>Dirakit tangan</h3>
          <p>Setiap aksesoris dirangkai satu per satu, jadi tidak ada dua yang benar-benar sama.</p>
          <div class="stats">
            <div>
              <strong data-count="1200">0</strong>
              <span>pesanan</span>
            </div>
            <div>
              <strong data-count="48">0</strong>
              <span>desain</span>
            </div>
            <div>
              <strong data-count="5">0</strong>
              <span>rating</span>
            </div>
          </div>
        </div>
        <div class="step rv">
          <h3>Palet yang tenang</h3>
          <p>Biru, hitam, dan putih sebagai dasar, dengan sedikit pink atau kuning supaya tetap playful.</p>
        </div>
        <div class="step rv">
          <h3>Kirim ke seluruh Indonesia</h3>
          <p>Dikemas rapi dan dikirim dalam 1–2 hari kerja.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="cta rv" id="contact">
    <div class="sticker bob" style="width: 84px; left: 6vw; top: 34px;">
      <svg class="i"><use href="#smile" /></svg>
    </div>
    <div class="sticker bob" style="width: 76px; right: 8vw; bottom: 34px;">
      <svg class="i"><use href="#flower" /></svg>
    </div>
    <h2>Hubungi kami,<br>atau ikuti drop berikutnya.</h2>
    <p>Tanya produk lewat hello@maybeads.id atau daftarkan email untuk info koleksi baru.</p>
    <form id="form">
      <input type="email" placeholder="email@kamu.com" aria-label="Email" required>
      <button class="btn" type="submit" data-magnet>Daftar</button>
    </form>
    <div class="ok" id="ok" role="status"></div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="f-grid">
      <div class="f-brand">
        <a class="logo" href="#home">maybeads*</a>
        <p>Toko aksesoris bertema Y2K. Dirakit tangan, dikirim ke seluruh Indonesia.</p>
        <div class="soc">
          <a href="#" aria-label="Instagram">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="5" />
              <circle cx="12" cy="12" r="4" />
              <circle cx="17.3" cy="6.7" r=".6" />
            </svg>
          </a>
          <a href="#" aria-label="TikTok">
            <svg viewBox="0 0 24 24">
              <path d="M14 3v11a3.5 3.5 0 1 1-3.5-3.5M14 3c.3 2.4 1.9 4 4.5 4.2" />
            </svg>
          </a>
        </div>
      </div>
      <div>
        <h4>Navigasi</h4>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="/product">Product</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4>Media sosial</h4>
        <ul>
          <li><a href="#">Instagram</a></li>
          <li><a href="#">TikTok</a></li>
          <li><a href="/privacy-policy">Privacy &amp; Policy</a></li>
        </ul>
      </div>
      <div>
        <h4>Metode pembayaran</h4>
        <div class="pay">
          <span class="q">QRIS</span>
          <span>GoPay</span>
          <span>OVO</span>
          <span>DANA</span>
          <span>ShopeePay</span>
        </div>
      </div>
    </div>
    <div class="f-bot">
      <span>© 2026 maybeads. Semua hak dilindungi.</span>
      <span>Dibuat dengan manik-manik &amp; kopi.</span>
    </div>
  </footer>

  <!-- Lenis Script -->
  <script src="{{ asset('vendor/lenis/lenis.min.js') }}"></script>
  <script>
    if (typeof Lenis === 'undefined') {
      document.write('<script src="https://unpkg.com/lenis@1.1.20/dist/lenis.min.js"><\/script>');
    }
  </script>

  <!-- Page Interactivity & Animations -->
  @vite(['resources/js/landing/home.js'])
</body>
</html>