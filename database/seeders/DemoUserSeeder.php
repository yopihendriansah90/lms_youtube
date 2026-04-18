<?php

namespace Database\Seeders;

use App\Models\MemberProfile;
use App\Models\MentorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('roles')) {
            $this->command?->warn('Tabel roles belum tersedia. Seeder demo user dilewati.');

            return;
        }

        $guardName = config('auth.defaults.guard', 'web');

        $requiredRoles = collect(['admin', 'mentor', 'member'])
            ->every(fn (string $roleName): bool => Role::query()
                ->where('name', $roleName)
                ->where('guard_name', $guardName)
                ->exists());

        if (! $requiredRoles) {
            $this->command?->warn('Role dasar belum lengkap. Seeder demo user dilewati.');

            return;
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin LMS',
                'password' => 'admin123',
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles(['admin']);

        $mentor = User::query()->updateOrCreate(
            ['email' => 'mentor@mail.com'],
            [
                'name' => 'Faruq Mentor',
                'password' => 'mentor123',
                'email_verified_at' => now(),
            ],
        );
        $mentor->syncRoles(['mentor']);

        MentorProfile::query()->updateOrCreate(
            ['user_id' => $mentor->id],
            [
                'display_name' => 'Mentor Faruq',
                'speciality' => 'Strategi konten YouTube dan pembelajaran digital',
                'short_bio' => 'Mentor utama untuk strategi konten, monetisasi, dan optimasi pembelajaran.',
                'full_bio' => 'Berpengalaman mendampingi member dalam strategi pembuatan konten, struktur kelas online, dan optimasi materi digital.',
                'instagram_url' => 'https://instagram.com/mentor.faruq',
                'youtube_url' => 'https://youtube.com/@alfaruqwfa',
                'whatsapp_number' => '081234567890',
                'is_active' => true,
            ],
        );

        $members = [
            [
                'email' => 'member1@mail.com',
                'name' => 'Member Demo Satu',
                'password' => 'member123',
                'profile' => [
                    'phone' => '081111111111',
                    'city' => 'Jakarta',
                    'province' => 'DKI Jakarta',
                    'birth_date' => '1998-05-12',
                    'gender' => 'male',
                    'occupation' => 'Content Creator',
                    'bio' => 'Fokus belajar monetisasi channel dan pengemasan materi digital.',
                    'is_active' => true,
                    'joined_at' => now()->subDays(30),
                ],
            ],
            [
                'email' => 'member2@mail.com',
                'name' => 'Member Demo Dua',
                'password' => 'member123',
                'profile' => [
                    'phone' => '082222222222',
                    'city' => 'Bandung',
                    'province' => 'Jawa Barat',
                    'birth_date' => '1996-11-03',
                    'gender' => 'female',
                    'occupation' => 'Digital Marketer',
                    'bio' => 'Sedang fokus belajar struktur konten, funnel, dan update materi harian.',
                    'is_active' => true,
                    'joined_at' => now()->subDays(14),
                ],
            ],
            [
                'email' => 'member3@mail.com',
                'name' => 'Member Demo Tiga',
                'password' => 'member123',
                'profile' => [
                    'phone' => '083333333333',
                    'city' => 'Surabaya',
                    'province' => 'Jawa Timur',
                    'birth_date' => '2000-02-19',
                    'gender' => 'male',
                    'occupation' => 'Video Editor',
                    'bio' => 'Mengikuti kelas untuk memperkuat workflow produksi video edukasi.',
                    'is_active' => true,
                    'joined_at' => now()->subDays(7),
                ],
            ],
        ];

        foreach ($members as $memberData) {
            $member = User::query()->updateOrCreate(
                ['email' => $memberData['email']],
                [
                    'name' => $memberData['name'],
                    'password' => $memberData['password'],
                    'email_verified_at' => now(),
                ],
            );

            $member->syncRoles(['member']);

            MemberProfile::query()->updateOrCreate(
                ['user_id' => $member->id],
                $memberData['profile'],
            );
        }
    }
}
