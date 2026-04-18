<?php

namespace Database\Seeders;

use App\Models\ContentUnlock;
use App\Models\Material;
use App\Models\MaterialSection;
use App\Models\MaterialUpdate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PdfDocument;
use App\Models\Program;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\Setting;
use App\Models\User;
use App\Models\Video;
use App\Models\ZoomRecord;
use Illuminate\Database\Seeder;

class DemoLmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $mentor = User::query()->where('email', 'mentor@mail.com')->first();
        $memberOne = User::query()->where('email', 'member1@mail.com')->first();
        $memberTwo = User::query()->where('email', 'member2@mail.com')->first();
        $youtubePrimaryUrl = 'https://www.youtube.com/watch?v=qsFeDeHI9Yo';
        $youtubePrimaryId = 'qsFeDeHI9Yo';
        $youtubeSecondaryUrl = 'https://www.youtube.com/watch?v=u2YU-Aql1ps';
        $youtubeSecondaryId = 'u2YU-Aql1ps';

        if (! $mentor || ! $memberOne || ! $memberTwo) {
            $this->command?->warn('User demo belum lengkap. Seeder konten LMS dilewati.');

            return;
        }

        $program = Program::query()->updateOrCreate(
            ['slug' => 'youtube-academy-batch-12'],
            [
                'title' => 'YouTube Academy Batch 12',
                'subtitle' => 'Racikan pembelajaran untuk membangun channel yang konsisten dan terarah.',
                'description' => 'Program utama untuk strategi konten, produksi video, optimasi channel, dan distribusi materi digital.',
                'cover_image' => 'programs/covers/demo-youtube-academy.jpg',
                'is_published' => true,
                'sort_order' => 1,
            ],
        );

        $freeMaterial = Material::query()->updateOrCreate(
            ['slug' => 'fondasi-konten-youtube'],
            [
                'program_id' => $program->id,
                'mentor_id' => $mentor->id,
                'title' => 'Fondasi Konten YouTube',
                'excerpt' => 'Materi pengantar untuk memahami positioning channel, target audiens, dan struktur konten mingguan.',
                'description' => 'Di materi ini member mempelajari dasar strategi channel, cara menentukan audience, dan framework produksi konten yang berulang.',
                'thumbnail' => 'materials/thumbnails/fondasi-konten-youtube.jpg',
                'status' => 'published',
                'visibility' => 'members',
                'access_type' => 'free',
                'price' => 0,
                'currency' => 'IDR',
                'is_featured' => true,
                'published_at' => now()->subDays(10),
                'sort_order' => 1,
            ],
        );

        $premiumMaterial = Material::query()->updateOrCreate(
            ['slug' => 'advanced-youtube-monetization'],
            [
                'program_id' => $program->id,
                'mentor_id' => $mentor->id,
                'title' => 'Advanced YouTube Monetization',
                'excerpt' => 'Materi premium untuk strategi monetisasi, penawaran produk, dan distribusi konten berlapis.',
                'description' => 'Materi ini membahas monetisasi channel, penentuan penawaran, strategi bundling konten, dan cara mengunci materi premium.',
                'thumbnail' => 'materials/thumbnails/advanced-youtube-monetization.jpg',
                'status' => 'published',
                'visibility' => 'members',
                'access_type' => 'paid',
                'price' => 149000,
                'currency' => 'IDR',
                'is_featured' => true,
                'published_at' => now()->subDays(6),
                'sort_order' => 2,
            ],
        );

        $freeSection = MaterialSection::query()->updateOrCreate(
            ['material_id' => $freeMaterial->id, 'title' => 'Mulai Belajar Sekarang'],
            [
                'description' => 'Sesi pembuka untuk menyiapkan fondasi channel dan kerangka produksi mingguan.',
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        $premiumSection = MaterialSection::query()->updateOrCreate(
            ['material_id' => $premiumMaterial->id, 'title' => 'Strategi Unlock Konten'],
            [
                'description' => 'Sesi monetisasi lanjutan untuk produk digital dan konten premium.',
                'sort_order' => 1,
                'is_active' => true,
            ],
        );

        $freeVideo = Video::query()->updateOrCreate(
            ['material_id' => $freeMaterial->id, 'title' => 'Pengenalan Strategi Channel'],
            [
                'section_id' => $freeSection->id,
                'title' => 'Pengenalan Strategi Channel',
                'youtube_url' => $youtubePrimaryUrl,
                'youtube_video_id' => $youtubePrimaryId,
                'description' => 'Video pembuka untuk memahami arah channel, value proposition, dan ritme upload konten.',
                'duration_in_seconds' => 630,
                'access_type' => 'free',
                'price' => 0,
                'is_preview' => true,
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'sort_order' => 1,
            ],
        );
        $freeVideo->forceFill([
            'youtube_url' => $youtubePrimaryUrl,
            'youtube_video_id' => $youtubePrimaryId,
        ])->save();

        $premiumPreviewVideo = Video::query()->updateOrCreate(
            ['material_id' => $premiumMaterial->id, 'title' => 'Preview Monetisasi Premium'],
            [
                'section_id' => $premiumSection->id,
                'title' => 'Preview Monetisasi Premium',
                'youtube_url' => $youtubeSecondaryUrl,
                'youtube_video_id' => $youtubeSecondaryId,
                'description' => 'Preview gratis untuk melihat garis besar strategi monetisasi sebelum unlock penuh.',
                'duration_in_seconds' => 420,
                'access_type' => 'paid',
                'price' => 0,
                'is_preview' => true,
                'is_published' => true,
                'published_at' => now()->subDays(6),
                'sort_order' => 1,
            ],
        );
        $premiumPreviewVideo->forceFill([
            'youtube_url' => $youtubeSecondaryUrl,
            'youtube_video_id' => $youtubeSecondaryId,
        ])->save();

        $premiumLockedVideo = Video::query()->updateOrCreate(
            ['material_id' => $premiumMaterial->id, 'title' => 'Framework Produk dan Penawaran'],
            [
                'section_id' => $premiumSection->id,
                'title' => 'Framework Produk dan Penawaran',
                'youtube_url' => $youtubeSecondaryUrl,
                'youtube_video_id' => $youtubeSecondaryId,
                'description' => 'Video utama premium yang mengupas mapping produk, pricing, dan skema unlock konten.',
                'duration_in_seconds' => 980,
                'access_type' => 'paid',
                'price' => 149000,
                'is_preview' => false,
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'sort_order' => 2,
            ],
        );
        $premiumLockedVideo->forceFill([
            'youtube_url' => $youtubeSecondaryUrl,
            'youtube_video_id' => $youtubeSecondaryId,
        ])->save();

        $freePdf = PdfDocument::query()->updateOrCreate(
            ['material_id' => $freeMaterial->id, 'title' => 'Worksheet Fondasi Channel'],
            [
                'description' => 'Worksheet untuk merumuskan niche, target audiens, dan pilar konten.',
                'access_type' => 'free',
                'is_published' => true,
                'sort_order' => 1,
            ],
        );
        $this->syncDemoPdf($freePdf, 'Worksheet Fondasi Channel');

        $premiumPdf = PdfDocument::query()->updateOrCreate(
            ['material_id' => $premiumMaterial->id, 'title' => 'Template Monetisasi Premium'],
            [
                'description' => 'Template penawaran, bonus, dan skema unlock untuk konten berbayar.',
                'access_type' => 'paid',
                'is_published' => true,
                'sort_order' => 1,
            ],
        );
        $this->syncDemoPdf($premiumPdf, 'Template Monetisasi Premium');

        MaterialUpdate::query()->updateOrCreate(
            ['material_id' => $freeMaterial->id, 'title' => 'Update video harian: brainstorming konten'],
            [
                'content' => 'Update harian berisi pola brainstorming konten mingguan dan cara memilih topik yang layak direkam lebih dulu.',
                'update_type' => 'video',
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
        );

        MaterialUpdate::query()->updateOrCreate(
            ['material_id' => $premiumMaterial->id, 'title' => 'Update premium: struktur penawaran kelas'],
            [
                'content' => 'Penambahan materi premium untuk memetakan lead magnet, offer utama, dan sistem bundling konten LMS.',
                'update_type' => 'info',
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
        );

        ZoomRecord::query()->updateOrCreate(
            ['slug' => 'weekly-strategy-alignment-q3'],
            [
                'program_id' => $program->id,
                'mentor_id' => $mentor->id,
                'title' => 'Weekly Strategy Alignment Q3',
                'description' => 'Sesi rekaman Zoom untuk alignment strategi konten dan evaluasi progres member.',
                'zoom_recording_url' => 'https://zoom.us/rec/share/demo-weekly-strategy-alignment',
                'youtube_url' => $youtubePrimaryUrl,
                'thumbnail' => 'zoom/weekly-strategy-alignment-q3.jpg',
                'recorded_at' => now()->subDays(4),
                'access_type' => 'free',
                'price' => 0,
                'is_published' => true,
                'sort_order' => 1,
            ],
        );

        $premiumZoom = ZoomRecord::query()->updateOrCreate(
            ['slug' => 'technical-workshop-architecture-review'],
            [
                'program_id' => $program->id,
                'mentor_id' => $mentor->id,
                'title' => 'Technical Workshop: Architecture Review',
                'description' => 'Sesi Zoom premium yang membahas arsitektur produk LMS, unlock flow, dan strategi konten lanjutan.',
                'zoom_recording_url' => 'https://zoom.us/rec/share/demo-architecture-review',
                'youtube_url' => $youtubeSecondaryUrl,
                'thumbnail' => 'zoom/technical-workshop-architecture-review.jpg',
                'recorded_at' => now()->subDays(2),
                'access_type' => 'paid',
                'price' => 99000,
                'is_published' => true,
                'sort_order' => 2,
            ],
        );

        $question = Question::query()->updateOrCreate(
            [
                'member_id' => $memberOne->id,
                'subject' => 'Bagaimana cara menyusun alur materi premium?',
            ],
            [
                'mentor_id' => $mentor->id,
                'material_id' => $premiumMaterial->id,
                'question' => 'Saya ingin tahu urutan terbaik untuk menampilkan preview, video utama premium, dan file PDF agar member lebih mudah paham dan tertarik unlock.',
                'status' => 'answered',
                'is_public' => true,
                'asked_at' => now()->subDays(2),
                'answered_at' => now()->subDay(),
            ],
        );

        QuestionAnswer::query()->updateOrCreate(
            ['question_id' => $question->id],
            [
                'mentor_id' => $mentor->id,
                'answer' => 'Mulai dari preview gratis, lanjutkan dengan hasil yang ingin dicapai member, lalu arahkan ke unlock video utama dan PDF kerja. Urutan ini menjaga konteks dan meningkatkan konversi.',
                'answer_video_url' => $youtubeSecondaryUrl,
                'is_published' => true,
                'published_at' => now()->subDay(),
            ],
        );

        $order = Order::query()->updateOrCreate(
            ['order_code' => 'ORD-DEMO-0001'],
            [
                'user_id' => $memberOne->id,
                'amount' => 149000,
                'status' => 'paid',
                'payment_method' => 'manual_transfer',
                'payment_provider' => 'manual',
                'payment_reference' => 'DEMO-REF-0001',
                'paid_at' => now()->subDays(2),
                'expired_at' => now()->addDays(5),
            ],
        );

        OrderItem::query()->updateOrCreate(
            [
                'order_id' => $order->id,
                'purchasable_type' => Material::class,
                'purchasable_id' => $premiumMaterial->id,
            ],
            [
                'item_name' => $premiumMaterial->title,
                'price' => 149000,
                'qty' => 1,
                'subtotal' => 149000,
            ],
        );

        ContentUnlock::query()->updateOrCreate(
            [
                'user_id' => $memberOne->id,
                'unlockable_type' => Material::class,
                'unlockable_id' => $premiumMaterial->id,
            ],
            [
                'order_id' => $order->id,
                'access_source' => 'payment',
                'starts_at' => now()->subDays(2),
                'ends_at' => null,
                'is_active' => true,
            ],
        );

        ContentUnlock::query()->updateOrCreate(
            [
                'user_id' => $memberOne->id,
                'unlockable_type' => ZoomRecord::class,
                'unlockable_id' => $premiumZoom->id,
            ],
            [
                'order_id' => null,
                'access_source' => 'bonus',
                'starts_at' => now()->subDay(),
                'ends_at' => null,
                'is_active' => true,
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.home_badge'],
            [
                'group' => 'portal',
                'value' => 'Dashboard Aktif',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.hero_title'],
            [
                'group' => 'portal',
                'value' => 'Selamat Datang di Alfaruq WFA',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.hero_description'],
            [
                'group' => 'portal',
                'value' => 'Akses materi pelatihan, sesi tanya jawab, dan rekaman pertemuan Anda dalam satu ruang kerja digital yang terintegrasi.',
                'type' => 'textarea',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.hero_video_url'],
            [
                'group' => 'portal',
                'value' => $youtubePrimaryUrl,
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.hero_video_heading'],
            [
                'group' => 'portal',
                'value' => 'Video Penjelasan',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.hero_video_caption'],
            [
                'group' => 'portal',
                'value' => 'Fondasi Konten YouTube',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.active_meeting_status'],
            [
                'group' => 'portal',
                'value' => 'Sedang Berlangsung',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.active_meeting_title'],
            [
                'group' => 'portal',
                'value' => 'Workshop React Hooks - Batch 12',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.active_meeting_schedule'],
            [
                'group' => 'portal',
                'value' => 'Kamis, 23 Januari 2025',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.active_meeting_time'],
            [
                'group' => 'portal',
                'value' => '14:00 - 16:00 WIB',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.support_whatsapp'],
            [
                'group' => 'portal',
                'value' => '081234567890',
                'type' => 'text',
            ],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'portal.payment_note'],
            [
                'group' => 'portal',
                'value' => 'Flow payment otomatis akan ditambahkan belakangan. Saat ini unlock demo menggunakan core order dan content unlock yang sudah tersedia.',
                'type' => 'textarea',
            ],
        );

        // Tambahkan satu pertanyaan lain yang masih pending untuk demo dashboard mentor/admin.
        Question::query()->updateOrCreate(
            [
                'member_id' => $memberTwo->id,
                'subject' => 'Apakah PDF premium bisa dibuka terpisah dari video?',
            ],
            [
                'mentor_id' => $mentor->id,
                'material_id' => $premiumMaterial->id,
                'question' => 'Saya ingin tahu apakah nanti sistem unlock bisa dibedakan antara PDF dan video premium atau harus satu paket materi.',
                'status' => 'pending',
                'is_public' => false,
                'asked_at' => now()->subHours(12),
                'answered_at' => null,
            ],
        );
    }

    protected function syncDemoPdf(PdfDocument $document, string $title): void
    {
        $document->clearMediaCollection('documents');

        $document->addMediaFromString($this->makePdfContents($title))
            ->usingFileName(str($title)->slug() . '.pdf')
            ->usingName($title)
            ->toMediaCollection('documents');
    }

    protected function makePdfContents(string $title): string
    {
        $safeTitle = substr(preg_replace('/[^A-Za-z0-9 ]/', '', $title) ?: 'Demo PDF', 0, 40);
        $stream = "BT /F1 24 Tf 72 720 Td ({$safeTitle}) Tj ET";

        return "%PDF-1.4\n"
            . "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n"
            . "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n"
            . "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n"
            . '4 0 obj << /Length ' . strlen($stream) . " >> stream\n{$stream}\nendstream endobj\n"
            . "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n"
            . "xref\n0 6\n0000000000 65535 f \n"
            . "0000000010 00000 n \n0000000063 00000 n \n0000000122 00000 n \n0000000248 00000 n \n0000000358 00000 n \n"
            . "trailer << /Root 1 0 R /Size 6 >>\nstartxref\n428\n%%EOF";
    }
}
