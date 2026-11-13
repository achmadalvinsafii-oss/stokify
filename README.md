# STOKIFY
### Smart Inventory Management

Stokify adalah aplikasi web untuk membantu UMKM mengelola persediaan barang dengan lebih mudah dan teratur.

## Fitur Aplikasi

- Login dan logout pengguna.
- Dashboard ringkasan stok.
- Pengelolaan data produk.
- Pencatatan barang masuk.
- Pencatatan barang keluar.
- Laporan stok barang.
- Peringatan stok minimum.
- Pencarian dan filter produk.
- Pemisahan data antar akun pengguna.
- Tampilan responsif untuk laptop dan HP Android.

## Teknologi yang Digunakan

- PHP
- Laravel 12
- SQLite
- Blade
- HTML dan CSS
- JavaScript
- Vite

## Persyaratan Instalasi

- PHP 8.2 atau lebih baru.
- Composer.
- Node.js dan NPM.
- Web browser.

## Cara Instalasi

1. Ekstrak file ZIP source code Stokify.
2. Buka terminal pada folder proyek.
3. Jalankan perintah:

```bash
composer install
```

4. Salin `.env.example` menjadi `.env`.
5. Pastikan konfigurasi database menggunakan SQLite.
6. Siapkan file database SQLite kosong pada folder `database` dengan nama `database.sqlite`.
7. Jalankan:

```bash
php artisan key:generate
php artisan migrate
```

8. Instal dan bangun aset frontend:

```bash
npm install
npm run build
```

9. Jalankan aplikasi:

```bash
php artisan serve
```

10. Buka browser dan akses:

`http://127.0.0.1:8000`

Buat akun melalui fitur pendaftaran jika tersedia.

## Pengujian

Aplikasi telah melalui pengujian fitur utama, termasuk pengelolaan produk, transaksi barang masuk dan keluar, laporan stok, peringatan stok minimum, serta pemisahan data antar pengguna.

## Pengembang

Proyek ini dibuat sebagai tugas perkuliahan untuk membantu UMKM dalam pengelolaan persediaan barang.

**Nama Aplikasi:** Stokify  
**Tagline:** Smart Inventory Management