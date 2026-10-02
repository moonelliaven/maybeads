<p align="center">
  <h1 align="center">Maybead.s — Handcrafted Beaded Jewelry & E-Commerce Platform</h1>
  <p align="center">
    <strong>Platform e-commerce modern, estetik, dan interaktif untuk aksesoris manik-manik buatan tangan (handcrafted beaded accessories) yang dilengkapi sistem manajemen dashboard toko terintegrasi.</strong>
  </p>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite 7">
  <img src="https://img.shields.io/badge/PostgreSQL-Supabase-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/SweetAlert2-11.x-D32F2F?style=for-the-badge" alt="SweetAlert2">
  <img src="https://img.shields.io/badge/Lenis-Smooth_Scroll-000000?style=for-the-badge" alt="Lenis">
</p>

---

## 📌 Daftar Isi
1. [Tentang Proyek](#-tentang-proyek)
2. [Fitur Utama](#-fitur-utama)
   - [Storefront / Halaman Pengunjung](#1-storefront--halaman-pengunjung)
   - [Sistem Autentikasi & Role](#2-sistem-autentikasi--role)
   - [Dashboard Administrator](#3-dashboard-administrator)
   - [Pengaturan Website & Akun](#4-pengaturan-website--akun)
3. [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
4. [Struktur Database](#-struktur-database)
5. [Struktur Direktori Proyek](#-struktur-direktori-proyek)
6. [Panduan Instalasi & Menjalankan Proyek](#-panduan-instalasi--menjalankan-proyek)
7. [Daftar Rute Utama](#-daftar-rute-utama)
8. [Akun Demo & Pengujian](#-akun-demo--pengujian)
9. [Kontribusi & Lisensi](#-kontribusi--lisensi)

---

## 📖 Tentang Proyek

**Maybeads** adalah aplikasi web e-commerce berbasis **Laravel 12** yang dirancang untuk menghadirkan pengalaman belanja online yang menyenangkan, interaktif, dan estetik bagi pecinta aksesoris manik-manik (cincin, gelang, kalung, anting, dan aksesoris rambut buatan tangan).

Aplikasi ini memadukan desain visual berdaya tarik tinggi (*playful*, *vibrant*, *sleek glassmorphism*) dengan arsitektur backend yang kokoh, dilengkapi panel administrasi lengkap untuk memantau performa penjualan, pesanan, dan konfigurasi toko.

---

## Kontributor

1. Hilyatul Aulia
2. M. Zaki Isrianto

---


## 🛠 Teknologi yang Digunakan

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | [Laravel 12.x](https://laravel.com/) (PHP ^8.2) |
| **Database ORM** | Eloquent ORM |
| **Database Engine** | PostgreSQL (Supabase) / MySQL / SQLite |
| **Frontend Tooling** | [Vite 7.x](https://vitejs.dev/) + `@tailwindcss/vite` |
| **Styling & CSS** | Custom Design System Vanilla CSS + Modern Flexbox/Grid |
| **Tipografi** | Google Fonts (*Playfair Display*, *Plus Jakarta Sans*, *Bricolage Grotesque*) |
| **Smooth Scroll** | [Lenis](https://github.com/darkroomengineering/lenis) |
| **Alerts & Popups** | [SweetAlert2](https://sweetalert2.github.io/) |
| **HTTP Client** | Axios |
| **Process Runner** | Concurrently |

---

## 🗄 Struktur Database

Entitas utama dalam aplikasi Maybeads dirancang secara relasional:

* **`users`**: Menyimpan kredensial pengguna, nama, email, password hash, dan `role` (`admin` / `user`).
* **`categories`**: Menyimpan daftar kategori aksesoris (`id`, `category_name`, `image`).
* **`products`**: Menyimpan katalog perhiasan (`id`, `category_id`, `product_name`, `price`, `stock`, `description`, `image`).
* **`orders`**: Menyimpan riwayat transaksi pembelian pelanggan.
* **`cart`**: Menyimpan keranjang belanja aktif pengguna.
* **`favorites`**: Menyimpan daftar keinginan (*wishlist*) pengguna.

---

## 📂 Struktur Direktori Proyek

```text
Maybeads/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php        # Autentikasi login, register, logout
│   │   │   ├── ProductController.php     # Manajemen produk
│   │   │   ├── CategoryController.php    # Manajemen kategori
│   │   │   ├── OrderController.php       # Manajemen pesanan
│   │   │   └── CartController.php        # Manajemen keranjang
│   │   └── Middleware/
│   │       └── AdminMiddleware.php       # Proteksi hak akses rute admin
│   └── Models/
│       ├── User.php                      # Model pengguna & helper isAdmin()
│       ├── Product.php                   # Model produk aksesoris
│       ├── Category.php                  # Model kategori
│       └── Order.php                     # Model pesanan
├── database/
│   └── migrations/                       # Skema migrasi tabel database
├── resources/
│   ├── css/
│   │   ├── admin/
│   │   │   ├── sidebar.css               # Styling sidebar & dropup menu footer
│   │   │   ├── dashboard.css             # Styling layout, metrik & tema SweetAlert
│   │   │   └── settings.css              # Styling halaman pengaturan & akun
│   │   ├── auth/                         # Styling login & register
│   │   └── landing/                      # Styling landing page & storefront
│   ├── js/
│   │   ├── admin/
│   │   │   └── dashboard.js              # Dropdown, toggle sidebar, SweetAlert logout
│   │   ├── auth/                         # Skrip autentikasi
│   │   └── landing/                      # Animasi magnet & interaksi landing page
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php       # Tampilan utama dashboard admin
│       │   ├── sidebar.blade.php         # Komponen sidebar admin terpadu
│       │   ├── settings.blade.php        # Halaman pengaturan website & akun
│       │   └── product/                  # Tampilan manajemen produk
│       ├── auth/                         # Tampilan login & register
│       └── landing/
│           └── home.blade.php            # Tampilan etalase utama (storefront)
├── routes/
│   └── web.php                           # Definisi seluruh rute aplikasi web
├── vite.config.js                        # Konfigurasi bundling Vite
└── composer.json                         # Dependensi PHP & skrip automasi
```

---

## 💻 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Sistem
Pastikan perangkat Anda telah terinstal:
* **PHP >= 8.2** (dengan ekstensi `pdo`, `mbstring`, `openssl`, `curl`)
* **Composer** (v2.x)
* **Node.js** (v18.x atau v20.x+) & **NPM**
* **Database**: PostgreSQL / MySQL / SQLite

### 2. Kloning Repository
```bash
git clone https://github.com/moonelliaven/new-maybeads.git
cd Maybeads
```

### 3. Instalasi Dependensi
```bash
# Instal dependensi PHP
composer install

# Instal dependensi Node.js
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Generate kunci aplikasi:
```bash
php artisan key:generate
```
Sesuaikan konfigurasi database pada `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-south-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.your_username
DB_PASSWORD=your_password
```
*(Atau gunakan SQLite dengan `DB_CONNECTION=sqlite`)*.

### 5. Jalankan Migrasi Database
```bash
php artisan migrate
```

### 6. Menjalankan Server Pengembangan
Anda dapat menjalankan server menggunakan satu perintah praktis:
```bash
composer run dev
```
Atau jalankan terminal terpisah:
```bash
# Terminal 1 (Asset Bundler)
npm run dev

# Terminal 2 (PHP Local Server)
php artisan serve
```
Akses aplikasi melalui browser di:
* **Storefront (Toko)**: `http://localhost:8000`
* **Admin Dashboard**: `http://localhost:8000/admin/dashboard`
* **Halaman Login**: `http://localhost:8000/login`

---

## 🧭 Daftar Rute Utama

| Method | URI | Nama Rute | Deskripsi |
| :--- | :--- | :--- | :--- |
| `GET` | `/` | `home` | Halaman beranda etalase toko Maybeads |
| `GET` | `/login` | `login` | Form login pengguna & admin |
| `POST` | `/login` | - | Proses verifikasi autentikasi |
| `GET` | `/register` | `register` | Form pendaftaran akun baru |
| `POST` | `/register` | - | Proses pendaftaran pengguna |
| `POST` / `GET` | `/logout` | `logout` | Keluar sesi pengguna / admin |
| `GET` | `/admin/dashboard` | `admin.dashboard` | Dashboard analitik admin |
| `GET` | `/admin/product` | `admin.product.index` | Daftar & manajemen produk |
| `GET` | `/admin/settings` | `admin.settings` | Halaman pengaturan website & akun |
| `GET` | `/admin/account` | `admin.account` | Rute akun (redirect ke `/admin/settings?tab=account`) |
| `GET` | `/admin/pengaturan` | `admin.pengaturan` | Alias redirect ke pengaturan |
| `GET` | `/admin/akun` | `admin.akun` | Alias redirect ke pengaturan akun |

---

## 👤 Akun Pengujian Default

Untuk masuk ke panel admin:
* **Email**: `hanzen@maybeads.com`
* **Role**: `admin`

---

## 📄 Lisensi
Aplikasi ini dikembangkan di bawah lisensi terbuka [MIT License](LICENSE).

<p align="center">
  Dibuat dengan ❤️ untuk <strong>Maybead.s</strong>
</p>
