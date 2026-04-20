<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialSection;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DemoYoutubeMaterialVideoSeeder extends Seeder
{
    public function run(): void
    {
        $youtubePrimaryUrl = 'https://www.youtube.com/watch?v=qsFeDeHI9Yo';
        $youtubePrimaryId = 'qsFeDeHI9Yo';
        $youtubeSecondaryUrl = 'https://www.youtube.com/watch?v=u2YU-Aql1ps';
        $youtubeSecondaryId = 'u2YU-Aql1ps';

        $freeMaterial = Material::query()->where('slug', 'fondasi-konten-youtube')->first();
        $premiumMaterial = Material::query()->where('slug', 'advanced-youtube-monetization')->first();

        if (! $freeMaterial || ! $premiumMaterial) {
            $this->command?->warn('Materi demo belum tersedia. Jalankan DemoLmsContentSeeder terlebih dahulu.');

            return;
        }

        $freeSection = MaterialSection::query()->firstOrCreate(
            [
                'material_id' => $freeMaterial->id,
                'title' => 'Video Dummy Gratis',
            ],
            [
                'description' => 'Bagian demo untuk sampel video materi gratis dari YouTube.',
                'sort_order' => 2,
                'is_active' => true,
            ],
        );

        $premiumSection = MaterialSection::query()->firstOrCreate(
            [
                'material_id' => $premiumMaterial->id,
                'title' => 'Video Dummy Premium',
            ],
            [
                'description' => 'Bagian demo untuk sampel video materi premium dari YouTube.',
                'sort_order' => 2,
                'is_active' => true,
            ],
        );

        Video::query()->updateOrCreate(
            [
                'material_id' => $freeMaterial->id,
                'title' => 'Sample Video Dummy Gratis',
            ],
            [
                'section_id' => $freeSection->id,
                'youtube_url' => $youtubePrimaryUrl,
                'youtube_video_id' => $youtubePrimaryId,
                'description' => 'Video dummy gratis untuk contoh tampilan materi yang diambil dari YouTube.',
                'duration_in_seconds' => 600,
                'access_type' => 'free',
                'price' => 0,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'sort_order' => 2,
            ],
        );

        Video::query()->updateOrCreate(
            [
                'material_id' => $premiumMaterial->id,
                'title' => 'Sample Video Dummy Premium',
            ],
            [
                'section_id' => $premiumSection->id,
                'youtube_url' => $youtubeSecondaryUrl,
                'youtube_video_id' => $youtubeSecondaryId,
                'description' => 'Video dummy premium untuk contoh materi berbayar yang membutuhkan unlock akun.',
                'duration_in_seconds' => 600,
                'access_type' => 'paid',
                'price' => 99000,
                'is_published' => true,
                'published_at' => now()->subDay(),
                'sort_order' => 3,
            ],
        );
    }
}
