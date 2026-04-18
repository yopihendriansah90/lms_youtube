# Member Portal Design Core

## Arah Visual
- Mobile-first dark learning portal
- Nuansa premium, tenang, dan fokus pada konten edukasi
- Aksen utama biru elektrik dengan pendukung cyan, mint, dan danger

## Token Warna
- `brand-500`: aksen utama tombol dan active state
- `brand-200`: meta text, eyebrow, dan navigasi aktif
- `ink-950` sampai `ink-700`: latar dasar, surface, dan layer konten
- `mint-400`: status gratis / sukses
- `danger-400`: status live / perhatian

## Skala Tipografi
- `display-heading`: judul hero paling dominan
- `page-title`: judul halaman
- `section-title`: judul section atau panel besar
- `card-title`: judul card utama
- `card-heading`: judul item/card sekunder
- `body-copy`: deskripsi utama
- `muted-copy`: deskripsi pendukung
- `meta-copy` / `eyebrow`: label kecil, kategori, dan informasi navigasional

## Komponen Inti
- `page-hero`: header standar tiap halaman
- `rich-card`: card utama untuk daftar dan panel isi
- `feature-list-card`: item dalam panel
- `info-item`: baris informasi ringkas
- `status-chip`: status gratis, premium, live
- `inline-chip`: metadata kecil seperti mentor, harga, jumlah video
- `field-shell`: input dan textarea
- `primary-btn` / `secondary-btn`: tombol aksi utama dan sekunder

## Aturan Konsistensi
- Hindari heading terlalu besar di luar hero
- Deskripsi minimum gunakan `body-copy`, bukan text abu-abu custom per halaman
- Gunakan `page-hero` untuk pembuka halaman member agar ritme halaman stabil
- Gunakan `status-chip` untuk status, bukan badge manual acak
- Gunakan `inline-chip` untuk metadata kecil agar bentuk pill konsisten
- Bottom navigation harus tetap punya target sentuh besar

## File Inti
- `resources/css/app.css`
- `resources/views/layouts/member.blade.php`
- `resources/views/member/*`
- `resources/views/auth/login.blade.php`
