<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hubungi Kami — Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  <meta name="description" content="Hubungi Maybeads: info kontak media sosial, alamat toko fisik di Banjarbaru, dan jam operasional.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  @vite(['resources/css/landing/contact.css'])
</head>
<body>

  <!-- Navigation -->
  @include('partials.nav')

  <main class="contact-main">
    <div class="contact-container">
      
      <!-- Top Row: Social Links (Left) & Heading (Right) -->
      <div class="contact-top">
        <div class="contact-socials">
          <a href="https://instagram.com" target="_blank" rel="noopener" class="contact-link">Instagram</a>
          <a href="https://tiktok.com" target="_blank" rel="noopener" class="contact-link">Tiktok</a>
          <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="contact-link">Whatsapp (Admin)</a>
        </div>
        
        <h1 class="contact-heading">HUBUNGI KAMI</h1>
      </div>

      <!-- Map Embed Container -->
      <div class="contact-map-wrapper">
        <iframe 
          src="https://maps.google.com/maps?q=Jl.+Pangeran+Suriansyah,+Loktabat+Utara,+Kec.+Banjarbaru+Utara,+Kota+Banjar+Baru,+Kalimantan+Selatan+70714&t=&z=16&ie=UTF8&iwloc=&output=embed" 
          width="100%" 
          height="100%" 
          style="border:0;" 
          allowfullscreen="" 
          loading="lazy" 
          referrerpolicy="no-referrer-when-downgrade"
          title="Lokasi Toko Maybeads">
        </iframe>
      </div>

      <!-- Bottom Info Row: Address (Left) & Opening Hours (Right) -->
      <div class="contact-info">
        <div class="contact-address">
          <p>HR7M+92W, Jl. Pangeran Suriansyah, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjar Baru, Kalimantan Selatan 70714</p>
        </div>

        <div class="contact-hours">
          <span class="hours-label">Buka Jam:</span>
          <span class="hours-val">Senin-Minggu: 13.00-22.00</span>
        </div>
      </div>

    </div>
  </main>

  <!-- Footer -->
  <footer class="footer" id="kontak">
    <div class="f-grid">
      <div class="f-brand">
        <a class="f-logo" href="{{ url('/') }}#home">Maybead<span>.</span>s</a>
        <p>Menyajikan busana nostalgia tahun 2000-an yang otentik dengan sentuhan streetwear modern dan energi futuristik.</p>
      </div>

      <div class="f-col">
        <h4>TAUTAN</h4>
        <ul>
          <li><a href="{{ url('/') }}#home">Home</a></li>
          <li><a href="{{ route('main.dashboard') }}#produk">Produk</a></li>
          <li><a href="{{ route('contact') }}">Kontak</a></li>
        </ul>
      </div>

      <div class="f-col">
        <h4>KEBIJAKAN</h4>
        <ul>
          <li><a href="#">Kebijakan Privasi</a></li>
          <li><a href="#">Ketentuan Layanan</a></li>
          <li><a href="#">Pengembalian &amp; Penukaran</a></li>
          <li><a href="#">Informasi Pengiriman</a></li>
        </ul>
      </div>

      <div class="f-col">
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

</body>
</html>
