# LMS Blueprint

## Tujuan Sistem

Membangun LMS berbasis Laravel 12 dengan:

- frontend member berbahasa Indonesia
- tampilan dark theme dan mobile-first
- admin panel menggunakan Filament 4
- role & permission menggunakan Filament Shield
- upload file menggunakan Spatie Media Library
- video pembelajaran dari link YouTube yang di-embed ke website
- konten gratis dan konten terkunci berbayar

## Arsitektur Aplikasi

- `Frontend Member`
  - Blade + Tailwind CSS 4
  - fokus mobile-first
  - halaman publik dan halaman member
- `Admin Panel`
  - Filament 4 untuk CRUD, manajemen konten, member, mentor, transaksi, dan pengaturan unlock
- `Authorization Layer`
  - Spatie Permission + Filament Shield
  - role utama: `super_admin`, `admin`, `mentor`, `member`
- `Media Layer`
  - Spatie Media Library untuk PDF, thumbnail, dan aset pendukung
- `Content Access Layer`
  - service/policy untuk memastikan video atau materi hanya terbuka jika user memang punya akses

## Blueprint Database

### 1. users

Tabel inti autentikasi.

Kolom utama:

- `id`
- `name`
- `email`
- `password`
- `email_verified_at`
- `remember_token`
- `created_at`
- `updated_at`

Catatan:

- role tidak ditaruh di kolom `users.role`, tetapi dikelola lewat tabel Spatie Permission

### 2. member_profiles

Profil detail member.

Kolom utama:

- `id`
- `user_id`
- `phone`
- `avatar`
- `city`
- `province`
- `birth_date`
- `gender`
- `occupation`
- `bio`
- `is_active`
- `joined_at`
- `created_at`
- `updated_at`

### 3. mentor_profiles

Profil detail mentor.

Kolom utama:

- `id`
- `user_id`
- `display_name`
- `speciality`
- `photo`
- `short_bio`
- `full_bio`
- `instagram_url`
- `youtube_url`
- `whatsapp_number`
- `is_active`
- `created_at`
- `updated_at`

### 4. programs

Program atau kelas utama, misalnya batch atau kategori belajar.

Kolom utama:

- `id`
- `title`
- `slug`
- `subtitle`
- `description`
- `cover_image`
- `is_published`
- `sort_order`
- `created_at`
- `updated_at`

### 5. materials

Materi utama yang tampil pada daftar materi.

Kolom utama:

- `id`
- `program_id`
- `mentor_id`
- `title`
- `slug`
- `excerpt`
- `description`
- `thumbnail`
- `status`
- `visibility`
- `access_type` (`free`, `paid`)
- `price`
- `currency`
- `is_featured`
- `published_at`
- `sort_order`
- `created_at`
- `updated_at`

Catatan:

- `mentor_id` mengarah ke `users.id` atau dapat diubah ke `mentor_profiles.id`, tetapi saya sarankan tetap ke `users.id` agar integrasi auth lebih sederhana

### 6. material_sections

Bagian atau sub-bab di dalam satu materi.

Kolom utama:

- `id`
- `material_id`
- `title`
- `description`
- `sort_order`
- `is_active`
- `created_at`
- `updated_at`

### 7. videos

Menyimpan video YouTube yang akan di-embed.

Kolom utama:

- `id`
- `material_id`
- `section_id`
- `title`
- `youtube_url`
- `youtube_video_id`
- `description`
- `duration_in_seconds`
- `access_type` (`free`, `paid`)
- `price`
- `is_published`
- `published_at`
- `sort_order`
- `created_at`
- `updated_at`

Catatan:

- `youtube_video_id` wajib disimpan agar render embed stabil dan tidak bergantung parsing berulang
- akses terkunci sebaiknya bisa diatur per video, bukan hanya per materi

### 8. pdf_documents

Metadata dokumen PDF yang terkait materi.

Kolom utama:

- `id`
- `material_id`
- `title`
- `description`
- `access_type` (`free`, `paid`)
- `is_published`
- `sort_order`
- `created_at`
- `updated_at`

Catatan:

- file fisik dikelola di tabel `media` dari Spatie Media Library

### 9. material_updates

Update materi harian atau pengumuman konten baru.

Kolom utama:

- `id`
- `material_id`
- `title`
- `content`
- `update_type` (`video`, `pdf`, `info`)
- `is_published`
- `published_at`
- `created_at`
- `updated_at`

### 10. zoom_records

Rekaman sesi Zoom atau live class.

Kolom utama:

- `id`
- `program_id`
- `mentor_id`
- `title`
- `slug`
- `description`
- `zoom_recording_url`
- `youtube_url`
- `thumbnail`
- `recorded_at`
- `access_type` (`free`, `paid`)
- `price`
- `is_published`
- `sort_order`
- `created_at`
- `updated_at`

Catatan:

- bila rekaman dipindahkan ke YouTube private/unlisted, cukup pakai struktur URL yang sama seperti video edukasi

### 11. questions

Pertanyaan member untuk mentor.

Kolom utama:

- `id`
- `member_id`
- `mentor_id`
- `material_id`
- `subject`
- `question`
- `status` (`pending`, `answered`, `published`, `closed`)
- `is_public`
- `asked_at`
- `answered_at`
- `created_at`
- `updated_at`

### 12. question_answers

Jawaban mentor untuk pertanyaan.

Kolom utama:

- `id`
- `question_id`
- `mentor_id`
- `answer`
- `answer_video_url`
- `is_published`
- `published_at`
- `created_at`
- `updated_at`

### 13. orders

Header transaksi pembelian unlock konten.

Kolom utama:

- `id`
- `user_id`
- `order_code`
- `amount`
- `status` (`pending`, `paid`, `failed`, `expired`, `refunded`)
- `payment_method`
- `payment_provider`
- `payment_reference`
- `paid_at`
- `expired_at`
- `created_at`
- `updated_at`

### 14. order_items

Detail item yang dibeli.

Kolom utama:

- `id`
- `order_id`
- `purchasable_type`
- `purchasable_id`
- `item_name`
- `price`
- `qty`
- `subtotal`
- `created_at`
- `updated_at`

### 15. content_unlocks

Hak akses konten yang sudah dimiliki member.

Kolom utama:

- `id`
- `user_id`
- `unlockable_type`
- `unlockable_id`
- `order_id`
- `access_source` (`manual`, `payment`, `bonus`)
- `starts_at`
- `ends_at`
- `is_active`
- `created_at`
- `updated_at`

Catatan:

- tabel ini jadi sumber kebenaran akses konten berbayar
- jangan hanya mengandalkan status order

### 16. settings

Pengaturan global LMS.

Kolom utama:

- `id`
- `group`
- `key`
- `value`
- `type`
- `created_at`
- `updated_at`

Contoh isi:

- pengaturan warna brand
- teks hero dashboard
- metode unlock default
- kontak mentor support
- CTA pembayaran

## Daftar Model

Model yang akan dipakai:

- `User`
- `MemberProfile`
- `MentorProfile`
- `Program`
- `Material`
- `MaterialSection`
- `Video`
- `PdfDocument`
- `MaterialUpdate`
- `ZoomRecord`
- `Question`
- `QuestionAnswer`
- `Order`
- `OrderItem`
- `ContentUnlock`
- `Setting`

Model dari package:

- `Spatie\Permission\Models\Role`
- `Spatie\Permission\Models\Permission`
- `Spatie\MediaLibrary\MediaCollections\Models\Media`

## Role dan Hak Akses

### super_admin

- akses penuh ke seluruh admin panel
- kelola role dan permission
- kelola seluruh data LMS
- override unlock konten
- kelola transaksi dan pengaturan

### admin

- kelola member, mentor, materi, video, PDF, Zoom, dan Q&A
- tidak wajib bisa mengelola role inti jika ingin dibatasi

### mentor

- lihat materi yang ditugaskan
- jawab pertanyaan
- kelola update materi tertentu bila diperlukan

### member

- login ke portal member
- lihat konten gratis
- lihat konten berbayar yang sudah di-unlock
- kirim pertanyaan ke mentor

## Alur Akses User

### 1. Login

- user masuk melalui halaman login frontend
- sistem memverifikasi email dan password
- setelah login, user diarahkan ke dashboard member atau admin panel sesuai role

### 2. Akses Dashboard Member

- member melihat ringkasan materi terbaru, update terbaru, dan rekaman Zoom
- dashboard menampilkan konten yang memang berhak dia lihat

### 3. Buka Daftar Materi

- sistem memuat materi yang statusnya `published`
- setiap materi dan video menampilkan badge `Gratis` atau `Terkunci`

### 4. Akses Detail Materi

- jika video gratis, iframe YouTube langsung ditampilkan
- jika video berbayar dan user belum punya akses, tampilkan layar lock + tombol buka kunci
- jika sudah unlock, iframe YouTube bisa diputar langsung di website

### 5. Pembelian / Unlock Konten

- user memilih video atau materi terkunci
- sistem membuat `order`
- setelah pembayaran sukses atau diverifikasi admin, sistem membuat `content_unlocks`
- akses konten dibuka berdasarkan tabel tersebut

### 6. Q&A Mentor

- member mengirim pertanyaan
- admin atau mentor memproses
- jawaban bisa berupa teks atau link/video update
- jika dipublikasikan, bisa tampil di halaman update materi atau Q&A publik

### 7. Admin Panel

- super admin/admin login ke `/admin`
- CRUD master data dilakukan melalui resource Filament
- pengaturan lock/unlock, harga, publish status, dan relasi konten dikelola dari panel ini

## Urutan Implementasi yang Direkomendasikan

### Sprint 1

- rapikan `User` + role permission
- buat migration domain inti
- buat seeder role dan super admin
- buat layout frontend dark mobile-first

### Sprint 2

- buat modul program, materi, video, PDF
- buat admin resources Filament
- buat halaman daftar materi dan detail materi

### Sprint 3

- buat sistem lock/unlock
- buat transaksi manual terlebih dahulu
- buat dashboard member

### Sprint 4

- buat modul Zoom
- buat modul Q&A mentor
- test akses dan hardening

## Catatan Teknis Penting

- embed YouTube tetap harus divalidasi dan dibatasi hanya ke format URL yang sah
- PDF tidak disimpan manual di kolom string, tetapi lewat Media Library
- akses konten berbayar harus dicek di query, controller/service, dan view
- dark theme sebaiknya dibangun dengan design tokens sejak awal agar konsisten
- semua label UI frontend dan admin sebaiknya disiapkan dalam Bahasa Indonesia sejak fase awal
