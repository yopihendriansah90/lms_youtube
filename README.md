# LMS YouTube

Portal LMS berbasis Laravel 12 dengan:

- Filament 4 untuk admin panel
- Filament Shield untuk role & permission
- Filament Spatie Media Library untuk manajemen file
- Frontend member mobile-first dengan tema dark

## Dokumentasi Visual

Panduan penggunaan utama sudah disiapkan dalam format Markdown yang bisa langsung dibaca di GitHub:

- [Panduan Member Portal](docs/member-guide.md)
- [Panduan Admin Panel](docs/admin-guide.md)

Folder screenshot:

- [Screenshot Member](docs/screenshots/member)
- [Screenshot Admin](docs/screenshots/admin)

Screenshot dibuat dari data demo lokal pada 20 April 2026, jadi jumlah data dan isi tabel bisa berbeda jika database aktifmu berbeda.
Halaman yang mengembalikan `403 Forbidden` tidak di-screenshot dan tidak dimasukkan ke panduan penggunaan.

## Requirement

- PHP `8.2+`
- Composer
- Node.js `18+`
- NPM
- SQLite atau MySQL

## Setup Cepat

### 1. Clone project

```bash
git clone <url-repository> lms
cd lms
```

### 2. Install dependency backend dan frontend

```bash
composer install
npm install
```

### 3. Buat file environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Atur database

Default project ini paling mudah dijalankan dengan `SQLite`.

Buat file database:

```bash
touch database/database.sqlite
```

Pastikan `.env` berisi:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/ke/project/database/database.sqlite
```

Jika ingin pakai MySQL, ubah bagian ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan migration dan seeder

```bash
php artisan migrate
php artisan db:seed
```

### 6. Buat symbolic link storage

Ini wajib supaya file upload dan media bisa tampil di frontend.

```bash
php artisan storage:link
```

### 7. Jalankan project

Untuk development:

```bash
php artisan serve
npm run dev
```

Atau build asset production:

```bash
npm run build
```

## Akses Project

### Admin panel

```text
http://127.0.0.1:8000/admin
```

### Member portal

```text
http://127.0.0.1:8000/login
```

## Akun Demo

Seeder project ini membuat akun berikut:

### Super Admin

- Email: `superadmin@mail.com`
- Password: `superadmin`

### Admin

- Email: `admin@mail.com`
- Password: `admin123`

### Mentor

- Email: `mentor@mail.com`
- Password: `mentor123`

### Member

- Email: `member1@mail.com`
- Password: `member123`

- Email: `member2@mail.com`
- Password: `member123`

- Email: `member3@mail.com`
- Password: `member123`

## Struktur Admin

Resource utama yang dipakai:

- `Setting Web`
- `Room Zoom`
- `Pertanyaan Zoom`
- `Rekaman Zoom`
- `Pembayaran Premium`

Alur input konten:

1. Atur `Setting Web`
2. Gunakan dashboard admin untuk memantau `Ringkasan Operasional`, `Aksi Cepat Admin` dengan tombol `Buka Menu`, pertanyaan live terbaru, dan pembayaran premium terbaru
3. Siapkan atau perbarui `Room Zoom`
4. Pantau `Pertanyaan Zoom` selama sesi live
5. Arsipkan sesi ke `Rekaman Zoom`
6. Verifikasi akses di `Pembayaran Premium`

## Struktur Member

Halaman utama member yang aktif saat ini:

- `Login`
- `Beranda`
- `Materi`
- `Detail Materi`
- `Room Zoom`
- `Rekaman Zoom`

Alur member yang disarankan:

1. Login ke portal member.
2. Buka `Materi` untuk belajar dari video dan PDF.
3. Gunakan `Room Zoom` saat sesi live berlangsung.
4. Kirim pertanyaan ke mentor hanya saat room berstatus `live`.
5. Gunakan `Rekaman Zoom` untuk menonton ulang sesi yang sudah selesai.

## Seeder Dummy Video

Seeder dummy memakai dua link YouTube berikut:

- `https://www.youtube.com/watch?v=qsFeDeHI9Yo`
- `https://www.youtube.com/watch?v=u2YU-Aql1ps`

## Perintah Berguna

```bash
php artisan optimize:clear
php artisan view:cache
php artisan route:list
php artisan db:seed --class=DemoLmsContentSeeder
php artisan db:seed --class=DemoYoutubeMaterialVideoSeeder
python3 scripts/capture_docs_screenshots.py
```

## Catatan

- Jika asset atau CSS tidak berubah, jalankan ulang `npm run build` atau `npm run dev`
- Jika media tidak tampil, cek `php artisan storage:link`
- Jika role/permission bermasalah, jalankan ulang `php artisan db:seed`
