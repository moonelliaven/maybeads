@php
  $products   = $products   ?? collect();
  $categories = $categories ?? collect();
  $fallbacks  = ['prod-hairclip.jpg', 'prod-ring.jpg', 'prod-butterfly.jpg', 'prod-keychain.jpg'];
  $imgFor = function ($p, $i) use ($fallbacks) {
      if ($p->image && file_exists(public_path('images/products/' . $p->image))) {
          return asset('images/products/' . $p->image);
      }
      return asset('images/landing/' . $fallbacks[$i % count($fallbacks)]);
  };
  $total = $products->count();
  $marquee = ['Pengiriman ke seluruh Indonesia', 'Gaya Y2K asli', 'Produk terbaru', 'Admin fast respon', 'Stylish dan keren'];
  $reviews = [
      ['name' => 'Joseph Manulang',     'mail' => 'joshmnlg@gmail.com',   'text' => 'Skatel nya beautiful banget, detailnya rapi dan kokoh dipakai harian.', 'tag' => 'Sabuk', 'var' => 'Hitam',  'av' => '#3b4f96'],
      ['name' => 'Ahmed Ghani Mengal',  'mail' => 'ahmedgm@gmail.com',    'text' => 'Kalungnya persis kayak di foto, warnanya cakep dan pengiriman cepat.',   'tag' => 'Kalung', 'var' => 'Biru',  'av' => '#e0524f'],
      ['name' => 'Daniel Tauber',       'mail' => 'danieltaub@gmail.com', 'text' => 'Admin fast respon, packing aman. Bakal order lagi buat kado pacar.',     'tag' => 'Kalung', 'var' => 'Biru',  'av' => '#8a6b4f'],
      ['name' => 'Christo Gruz Garcia', 'mail' => 'chrisgruz@gmail.com',  'text' => 'Vibe Y2K-nya dapet banget, dipakai nongkrong langsung ditanyain temen.', 'tag' => 'Kalung', 'var' => 'Biru',  'av' => '#4f8a5b'],
  ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maybeads — Aksesoris Y2K Impianmu</title>
  <meta name="description" content="Maybeads: aksesoris Y2K berkualitas tinggi — cincin, kalung, gelang, dan aksesori krom. Dikirim ke seluruh Indonesia.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@100..125,500..900&family=Fraunces:opsz,wght@9..144,700;9..144,900&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <script>document.documentElement.classList.add('js');</script>
  @vite(['resources/css/lenis.css', 'resources/css/landing/home.css'])
</head>
<body>

  <!-- Navigation -->
  @include('partials.nav')

  <main>
    <!-- Hero -->
    <header class="hero" id="home">
      <div class="hero-copy">
        <h1 class="hero-title">
          <span class="line"><span>Tampil beda dengan</span></span>
          <span class="line"><span>aksesoris Y2K</span></span>
          <span class="line"><span>impianmu</span></span>
        </h1>
        <p class="hero-desc" data-fade>
          Aksesoris Y2K berkualitas tinggi yang dipilih secara cermat berpadu dengan siluet kontemporer
          yang presisi. Cincin yang berani, kalung tengkorak, dan aksesori krom lainnya, dan budaya drop
          yang menjadi ciri khas.
        </p>
        <div class="hero-cta" data-fade>
          <a class="btn btn-primary" href="#produk" id="hero-order">Pesan Sekarang</a>
          <a class="btn btn-outline" href="#produk" id="hero-new">Produk Terbaru <span class="blink">!!!</span></a>
        </div>
      </div>

      <div class="hero-gallery">
        <figure class="hg hg-main" data-reveal>
          <img src="{{ asset('images/landing/hero-main.jpg') }}" alt="Gelang manik bening dengan charm bintang krom" data-parallax="-6">
        </figure>
        <figure class="hg hg-top" data-reveal>
          <img src="{{ asset('images/landing/hero-cross.jpg') }}" alt="Kalung rantai perak dengan liontin salib" data-parallax="-4">
        </figure>
        <figure class="hg hg-bot" data-reveal>
          <img src="{{ asset('images/landing/hero-butterfly.jpg') }}" alt="Gelang manik biru dengan charm kupu-kupu" data-parallax="-4">
        </figure>
        <span class="spark sp1" aria-hidden="true">✦</span>
        <span class="spark sp2" aria-hidden="true">✧</span>
      </div>
    </header>

    <!-- Marquee strip -->
    <div class="strip" aria-hidden="true">
      <div class="strip-track" id="strip">
        @for($r = 0; $r < 4; $r++)
          @foreach($marquee as $m)
            <span class="strip-item">{{ $m }}</span>
          @endforeach
        @endfor
      </div>
    </div>

    <!-- Best Seller -->
    <section class="section best" id="produk">
      <h2 class="sec-title split">
        <span class="line"><span>Produk</span></span>
        <span class="line"><span><em class="c-navy">Yang</em> Best <em class="c-coral">Seller</em></span></span>
      </h2>

      <div class="best-bar">
        <p class="sec-sub" data-fade>Produk kami yang bisa memperkeren penampilan anda dan terlihat lebih stylish dan keren</p>
        <div class="tabs" role="tablist" id="tabs" data-fade>
          <span class="tab-pill" id="tab-pill"></span>
          <button class="tab active" role="tab" data-cat="all" id="tab-all">Semua ({{ $total }})</button>
          @foreach($categories->take(4) as $cat)
            <button class="tab" role="tab" data-cat="{{ $cat->id }}" id="tab-{{ $cat->id }}">{{ $cat->category_name }} ({{ $cat->products_count }})</button>
          @endforeach
        </div>
      </div>

      <div class="cards" id="cards">
        @forelse($products as $i => $p)
          <article class="card" data-cat="{{ $p->category_id }}">
            <div class="card-media">
              <span class="badge">Best Seller</span>
              <img src="{{ $imgFor($p, $i) }}" alt="{{ $p->product_name }}" loading="lazy">
            </div>
            <div class="card-body">
              <h3>{{ $p->product_name }}</h3>
              <p>{{ \Illuminate\Support\Str::limit($p->description, 60) }}</p>
              <strong>Rp {{ $p->price }}</strong>
            </div>
          </article>
        @empty
          @foreach($fallbacks as $i => $f)
            <article class="card" data-cat="all">
              <div class="card-media">
                <span class="badge">Best Seller</span>
                <img src="{{ asset('images/landing/' . $f) }}" alt="Produk Maybeads" loading="lazy">
              </div>
              <div class="card-body">
                <h3>Produk Maybeads</h3>
                <p>Aksesoris Y2K pilihan, segera hadir.</p>
                <strong>Rp 80.000</strong>
              </div>
            </article>
          @endforeach
        @endforelse
      </div>

      <div class="center" data-fade>
        <a class="btn btn-outline btn-arrow" href="/product" id="see-all">
          Lihat semua produk kami [{{ $total }}]
          <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </section>

    <!-- Reviews -->
    <section class="section reviews" id="ulasan">
      <div class="rev-head">
        <div>
          <h2 class="sec-title split">
            <span class="line"><span>Ulasan customer</span></span>
            <span class="line"><span>terhadap <em class="c-navy">Maybeads</em></span></span>
          </h2>
          <p class="sec-sub" data-fade>Pendapat-pendapat customer tentang barang barang yang di beli di maybeads</p>
        </div>
        <div class="rating" data-fade>
          <div>
            <strong class="rating-num" id="rating" data-to="4.7">0,0</strong>
            <div class="stars">★★★★★</div>
          </div>
          <span class="rating-label">Rating Overall</span>
        </div>
      </div>

      <div class="rev-grid" id="rev-grid">
        @foreach($reviews as $rv)
          <article class="rev">
            <span class="avatar" style="--av: {{ $rv['av'] }}">{{ strtoupper(substr($rv['name'], 0, 1)) }}</span>
            <h3>{{ $rv['name'] }}</h3>
            <small>{{ $rv['mail'] }}</small>
            <div class="stars">★★★★★</div>
            <p>{{ $rv['text'] }}</p>
            <b>{{ $rv['tag'] }}: {{ $rv['var'] }}</b>
          </article>
        @endforeach
      </div>
    </section>

    <!-- CTA band -->
    <section class="band" id="cta">
      <div class="band-bg" id="band-bg"></div>
      <a href="#produk" class="band-title" id="band-link">
        <span class="line"><span>Ready to start a new style?</span></span>
        <span class="line"><span>Order now! <svg viewBox="0 0 24 24"><path d="M7 17 17 7M8 7h9v9"/></svg></span></span>
      </a>
    </section>
  </main>

  <!-- Footer -->
  <footer class="footer" id="kontak">
    <div class="f-grid">
      <div class="f-brand" data-fade>
        <h4 class="f-logo">Maybeads</h4>
        <p>Menyajikan busana nostalgia tahun 2000-an yang otentik dengan sentuhan streetwear modern dan energi futuristik.</p>
        <a href="#" class="f-soc" aria-label="Instagram">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r=".6"/></svg>
          Instagram
        </a>
        <a href="#" class="f-soc" aria-label="TikTok">
          <svg viewBox="0 0 24 24"><path d="M14 3v11a3.5 3.5 0 1 1-3.5-3.5M14 3c.3 2.4 1.9 4 4.5 4.2"/></svg>
          Tiktok
        </a>
      </div>
      <div data-fade>
        <h4>Tautan</h4>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#produk">Produk</a></li>
          <li><a href="#kontak">Kontak</a></li>
        </ul>
      </div>
      <div data-fade>
        <h4>Kebijakan</h4>
        <ul>
          <li><a href="/privacy-policy">Kebijakan Privasi</a></li>
          <li><a href="#">Ketentuan Layanan</a></li>
          <li><a href="#">Pengembalian &amp; Penukaran</a></li>
          <li><a href="#">Informasi Pengiriman</a></li>
        </ul>
      </div>
    </div>
    <p class="f-copy">© {{ date('Y') }} Maybeads. All rights reserved. Built for digital cyber nomads.</p>
  </footer>

  @vite(['resources/js/landing/home.js'])
</body>
</html>