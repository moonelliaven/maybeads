@php
  use App\Models\Category;
  use App\Models\Product;

  try {
      $categories = $categories ?? Category::withCount('products')->get();
      $products   = $products   ?? Product::with('category')->latest('id')->get();
  } catch (\Throwable $e) {
      $categories = $categories ?? collect();
      $products   = $products   ?? collect();
  }

  $total = $products->count();

  // 4 Featured Best Seller products to match the exact reference image
  $bestSellers = [
      [
          'name'     => 'Y2K Star Metal Hairpin',
          'category' => 'Aksesoris',
          'cat_key'  => 'topi',
          'price'    => 'Rp 89.999',
          'image'    => asset('images/landing/prod-hairclip.jpg'),
      ],
      [
          'name'     => 'Celestial Moon & Star Ring',
          'category' => 'Aksesoris',
          'cat_key'  => 'cincin',
          'price'    => 'Rp 129.000',
          'image'    => asset('images/landing/prod-ring.jpg'),
      ],
      [
          'name'     => 'Sparkling Butterfly Necklace & Earrings Set',
          'category' => 'Aksesoris',
          'cat_key'  => 'kalung',
          'price'    => 'Rp 98.000',
          'image'    => asset('images/landing/prod-butterfly.jpg'),
      ],
      [
          'name'     => 'Rockstar Electric Guitar Charm Keychain',
          'category' => 'Aksesoris',
          'cat_key'  => 'gelang',
          'price'    => 'Rp 59.000',
          'image'    => asset('images/landing/prod-keychain.jpg'),
      ],
  ];

  // 4 Customer Reviews matching the exact reference image
  $reviews = [
      [
          'name'   => 'JOSEPH MANULANG',
          'mail'   => 'Josephm.k@gmail.com',
          'text'   => 'Barang dengan kualitas terbaik yang pernah saya coba.',
          'tag'    => 'Sabuk: Hitam',
          'avatar' => asset('images/avatars/avatar-joseph.jpg'),
      ],
      [
          'name'   => 'AHMED GHANI MENGAL',
          'mail'   => 'AhmedGM2@gmail.com',
          'text'   => 'Kalungnya persis kayak di foto,warnanya cakep dan pengirimannya cepat.',
          'tag'    => 'Cincin:Emas',
          'avatar' => asset('images/avatars/avatar-ahmed.jpg'),
      ],
      [
          'name'   => 'DANIEL TAUBER',
          'mail'   => 'DaberT.M@gmail.com',
          'text'   => 'Admin fast respon,packing aman. Bakal order lagi buat kado pacar.',
          'tag'    => 'Gantungan Kunci',
          'avatar' => asset('images/avatars/avatar-daniel.jpg'),
      ],
      [
          'name'   => 'CHRISTO GRUZ GARCIA',
          'mail'   => 'Chruscal1@gmail.com',
          'text'   => 'Vibe Y2K-nya dapet banget,dipakai nongkrong langsung ditanyain temen.',
          'tag'    => 'Kalung: Biru',
          'avatar' => asset('images/avatars/avatar-christo.jpg'),
      ],
  ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maybeads — Aksesoris Y2K Impianmu</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  <meta name="description" content="Maybeads: aksesoris Y2K berkualitas tinggi — cincin, kalung, gelang, dan aksesori krom. Dikirim ke seluruh Indonesia.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@100..125,500..900&family=Fraunces:opsz,wght@9..144,700;9..144,900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <script>document.documentElement.classList.add('js');</script>
  @vite(['resources/css/lenis.css', 'resources/css/landing/home.css'])
</head>
<body>

  <!-- Navigation -->
  @include('partials.nav')

  <main>
    <!-- Hero Section -->
    <header class="hero" id="home">
      <div class="hero-copy">
        <h1 class="hero-title">
          <span class="line"><span>TAMPIL BEDA DENGAN</span></span>
          <span class="line"><span>AKSESORIS Y2K IMPIANMU</span></span>
        </h1>
        <p class="hero-desc" data-fade>
          Siluet modern yang tajam bertemu dengan rilisan terbatas aksesoris krom Y2K. Karakter kuat dari cincin berani dan kalung tengkorak pilihan.
        </p>
        <div class="hero-cta" data-fade>
          <a class="btn btn-primary" href="#produk" id="hero-order">PESAN SEKARANG</a>
          <a class="btn btn-outline" href="#produk" id="hero-new">PRODUK TERBARU</a>
        </div>
      </div>

      <div class="hero-gallery">
        <figure class="hg hg-main" data-reveal>
          <img src="{{ asset('images/landing/hero-main.jpg') }}" alt="Aksesoris sabuk krom bintang Y2K" data-parallax="-6">
        </figure>
        <figure class="hg hg-top" data-reveal>
          <img src="{{ asset('images/landing/hero-cross.jpg') }}" alt="Kacamata rantai krom liontin bintang Y2K" data-parallax="-4">
        </figure>
        <figure class="hg hg-bot" data-reveal>
          <img src="{{ asset('images/landing/hero-butterfly.jpg') }}" alt="Gelang manik biru kristal Y2K" data-parallax="-4">
        </figure>
      </div>
    </header>

    <!-- Best Seller Section -->
    <section class="section best" id="produk">
      <h2 class="sec-title split">
        <span class="line"><span>PRODUK</span></span>
        <span class="line"><span><em class="c-navy">YANG</em> <em class="c-coral">BEST SELLER</em></span></span>
      </h2>

      <div class="best-bar">
        <p class="sec-sub" data-fade>Produk kami yang bisa memperkeren penampilan anda dan terlihat lebih stylish dan keren</p>
        <div class="tabs" role="tablist" id="tabs" data-fade>
          <span class="tab-pill" id="tab-pill"></span>
          <button class="tab active" role="tab" data-cat="all" id="tab-all">SEMUA</button>
          <button class="tab" role="tab" data-cat="topi" id="tab-topi">TOPI</button>
          <button class="tab" role="tab" data-cat="kalung" id="tab-kalung">KALUNG</button>
          <button class="tab" role="tab" data-cat="cincin" id="tab-cincin">CINCIN</button>
          <button class="tab" role="tab" data-cat="gelang" id="tab-gelang">GELANG</button>
        </div>
      </div>

      <div class="cards" id="cards">
        @foreach($bestSellers as $b)
          <article class="card" data-cat="{{ $b['cat_key'] }}">
            <div class="card-media">
              <span class="badge">BEST SELLER</span>
              <img src="{{ $b['image'] }}" alt="{{ $b['name'] }}" loading="lazy">
            </div>
            <div class="card-body">
              <span class="card-category">{{ $b['category'] }}</span>
              <h3 class="card-title">{{ $b['name'] }}</h3>
              <div class="card-price">{{ $b['price'] }}</div>
            </div>
          </article>
        @endforeach
      </div>

      <div class="center" data-fade>
        <a class="btn btn-outline btn-see-all" href="{{ route('main.dashboard') }}#produk" id="see-all">
          LIHAT SEMUA PRODUK KAMI →
        </a>
      </div>
    </section>

    <!-- Reviews Section -->
    <section class="section reviews" id="ulasan">
      <div class="rev-head">
        <div>
          <h2 class="sec-title split">
            <span class="line"><span>ULASAN CUSTOMER</span></span>
            <span class="line"><span>TERHADAP <em class="c-navy">MAYBEADS</em></span></span>
          </h2>
          <p class="sec-sub" data-fade>Pendapat-pendapat customer tentang barang barang yang di beli di maybeads</p>
        </div>
        <div class="rating-block" data-fade>
          <div class="rating-left">
            <span class="rating-num" id="rating" data-to="4.7">4,7</span>
            <div class="stars">★★★★★</div>
          </div>
          <div class="rating-right">
            <span class="rating-label">RATING<br>OVERALL</span>
          </div>
        </div>
      </div>

      <div class="rev-grid" id="rev-grid">
        @foreach($reviews as $rv)
          <article class="rev">
            <div class="rev-avatar-wrap">
              <img src="{{ $rv['avatar'] }}" alt="{{ $rv['name'] }}" class="rev-avatar-img">
            </div>
            <h3 class="rev-name">{{ $rv['name'] }}</h3>
            <span class="rev-email">{{ $rv['mail'] }}</span>
            <div class="stars">★★★★★</div>
            <p class="rev-text">{{ $rv['text'] }}</p>
            <div class="rev-tag">{{ $rv['tag'] }}</div>
          </article>
        @endforeach
      </div>
    </section>

    <!-- CTA band -->
    <section class="band" id="cta">
      <div class="band-content">
        <h2 class="band-heading">
          READY TO START A NEW STYLE?<br>
          ORDER NOW!
        </h2>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="footer" id="kontak">
    <div class="f-grid">
      <div class="f-brand" data-fade>
        <a class="f-logo" href="#home">Maybead<span>.</span>s</a>
        <p>Menyajikan busana nostalgia tahun 2000-an yang otentik dengan sentuhan streetwear modern dan energi futuristik.</p>
      </div>

      <div data-fade class="f-col">
        <h4>TAUTAN</h4>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="{{ route('main.dashboard') }}#produk">Produk</a></li>
          <li><a href="{{ route('contact') }}">Kontak</a></li>
        </ul>
      </div>

      <div data-fade class="f-col">
        <h4>KEBIJAKAN</h4>
        <ul>
          <li><a href="/privacy-policy">Kebijakan Privasi</a></li>
          <li><a href="#">Ketentuan Layanan</a></li>
          <li><a href="#">Pengembalian &amp; Penukaran</a></li>
          <li><a href="#">Informasi Pengiriman</a></li>
        </ul>
      </div>

      <div data-fade class="f-col">
        <h4>SOSIAL MEDIA</h4>
        <ul class="f-soc-list">
          <li>
            <a href="#" class="f-soc-link" aria-label="Instagram">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r=".6"/></svg>
              <span>Instagram</span>
            </a>
          </li>
          <li>
            <a href="#" class="f-soc-link" aria-label="TikTok">
              <svg viewBox="0 0 24 24"><path d="M14 3v11a3.5 3.5 0 1 1-3.5-3.5M14 3c.3 2.4 1.9 4 4.5 4.2"/></svg>
              <span>Tiktok</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="f-bottom">
      <p class="f-copy">© 2024 Maybeads. All rights reserved. Built for digital cyber-nomads.</p>
    </div>
  </footer>

  @vite(['resources/js/landing/home.js'])
</body>
</html>