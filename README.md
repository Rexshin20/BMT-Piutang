# BMT Cinde Barokah — Aplikasi Perhutangan

Aplikasi web sederhana untuk mencatat dan memantau **piutang (perhutangan)** BMT Cinde Barokah: kelola data peminjam, catat transaksi piutang, lihat daftar piutang, sampai ekspor laporan ke Excel dan PDF.

Dibangun dengan **Laravel 10**, **Blade**, dan **Tailwind CSS** (build lokal, tanpa CDN).

## Fitur

- **Login** — seluruh halaman aplikasi hanya bisa diakses setelah masuk.
- **Dashboard** — ringkasan piutang dan data terbaru.
- **Piutang** — daftar piutang dengan pencarian, pengurutan, dan paging (DataTables), plus ekspor **Excel** dan **PDF**.
- **Data Piutang** — catat piutang baru: peminjam, tanggal, barang, unit, dan harga. **Total dihitung otomatis** dari Harga × Unit, termasuk validasi ulang di server.
- **Data Peminjam** — tambah, ubah, dan hapus data peminjam, lengkap dengan ekspor Excel/PDF.
- **Pengaturan Akun** — ubah nama, email, dan password.
- **Pengaturan Tampilan** — pilih font serta tema terang/gelap/sistem.
- **Help Center** — kontak bantuan via WhatsApp.

## Teknologi

| Bagian | Keterangan |
| --- | --- |
| Framework | Laravel 10 (PHP 8.1+) |
| Tampilan | Blade + Tailwind CSS 3 |
| Tabel | DataTables 2 + Buttons (Excel/PDF) + pdfmake |
| Database | MySQL / MariaDB |

Aset pihak ketiga (DataTables, pdfmake, JSZip) disimpan lokal di `public/vendor`, jadi aplikasi tidak bergantung pada CDN.

## Kebutuhan

- PHP >= 8.1 beserta ekstensi standar Laravel
- Composer
- MySQL / MariaDB
- Node.js (opsional, hanya untuk membangun ulang CSS)

## Instalasi

```bash
git clone https://github.com/Rexshin20/BMT-Piutang.git
cd BMT-Piutang
composer install
cp .env.example .env
php artisan key:generate
```

Sesuaikan koneksi database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perhutangan
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan migrasi dan seeder, kemudian nyalakan server:

```bash
php artisan migrate --seed
php artisan serve
```

Buka http://127.0.0.1:8000.

## Akun Demo

| Email | Password |
| --- | --- |
| `bmt@gmail.com` | `12345678` |

## Build CSS

Tailwind di-build manual ke `public/css/app.css`. Setiap kali ada perubahan class di Blade atau `resources/css/app.css`, jalankan:

```bash
npx tailwindcss@3.4.17 -c tailwind.config.js -i resources/css/app.css -o public/css/app.css --minify
php artisan view:clear
```

## Struktur Singkat

```
app/Http/Controllers     Controller halaman & data
app/Models               Model Peminjam, Piutang, User
database/migrations      Skema tabel
database/seeders         Data awal (peminjam, piutang, akun demo)
resources/views          Semua halaman Blade
resources/css/app.css    Sumber Tailwind + komponen UI
public/css/app.css       Hasil build Tailwind
public/vendor            DataTables, Buttons, pdfmake, JSZip
public/images            Logo dan favicon
```

## Catatan

- File `.env` tidak disertakan di repository; buat dari `.env.example`.
- Field **Total** pada form Data Piutang bersifat read-only dan selalu dihitung dari `harga × unit`, baik di browser maupun di server.
- Format uang memakai pemisah ribuan Indonesia (contoh: `20.000`).
