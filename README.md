# LMS YouTube

Portal LMS berbasis Laravel 12 dengan:

- Filament 4 untuk admin panel
- Filament Shield untuk role & permission
- Filament Spatie Media Library untuk manajemen file
- Frontend member mobile-first dengan tema dark

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

- `Kelas Materi`
- `Materi`
- `Video Materi`
- `Dokumen PDF`
- `Update Materi`
- `Member Profiles`
- `Mentor Profiles`

Alur input konten:

1. Buat `Kelas Materi`
2. Buat `Materi`
3. Buat `Video Materi` dan hubungkan ke materi
4. Atur tiap video sebagai `free` atau `paid`
5. Upload dokumen PDF dan update materi bila perlu

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
```

## Catatan

- Jika asset atau CSS tidak berubah, jalankan ulang `npm run build` atau `npm run dev`
- Jika media tidak tampil, cek `php artisan storage:link`
- Jika role/permission bermasalah, jalankan ulang `php artisan db:seed`

