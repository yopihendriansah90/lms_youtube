# Panduan Setup Project LMS YouTube

Dokumen ini dipakai setelah project selesai di-clone dari GitHub.

## 1. Clone repository

```bash
git clone <url-repository> lms
cd lms
```

## 2. Install dependency

```bash
composer install
npm install
```

## 3. Siapkan environment

```bash
cp .env.example .env
php artisan key:generate
```

## 4. Siapkan database

### Opsi termudah: SQLite

```bash
touch database/database.sqlite
```

Lalu ubah `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/ke/project/database/database.sqlite
```

### Opsi lain: MySQL

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

## 5. Jalankan migration dan seeder

```bash
php artisan migrate
php artisan db:seed
```

Seeder akan membuat:

- role dasar
- akun demo
- konten LMS demo
- video dummy YouTube

## 6. Aktifkan storage publik

```bash
php artisan storage:link
```

## 7. Jalankan project

### Mode development

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

### Mode build

```bash
npm run build
```

## 8. URL akses

### Admin

```text
/admin
```

### Login member

```text
/login
```

## 9. Akun demo

### Super admin

- `superadmin@mail.com` / `superadmin`

### Admin

- `admin@mail.com` / `admin123`

### Mentor

- `mentor@mail.com` / `mentor123`

### Member

- `member1@mail.com` / `member123`
- `member2@mail.com` / `member123`
- `member3@mail.com` / `member123`

## 10. Alur input konten di admin

1. Buat `Kelas Materi`
2. Buat `Materi`
3. Tambahkan `Video Materi`
4. Atur video menjadi `Gratis` atau `Berbayar`
5. Tambahkan `Dokumen PDF`
6. Tambahkan `Update Materi`

## 11. Troubleshooting

### CSS / asset tidak update

```bash
npm run build
php artisan optimize:clear
```

### File upload / foto / media tidak tampil

```bash
php artisan storage:link
```

### Seeder ingin dijalankan ulang

```bash
php artisan db:seed --class=DemoLmsContentSeeder
php artisan db:seed --class=DemoYoutubeMaterialVideoSeeder
```
