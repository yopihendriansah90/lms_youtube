<?php

namespace App\Http\Controllers;

use App\Models\ContentUnlock;
use App\Models\Material;
use App\Models\MaterialUpdate;
use App\Models\MentorProfile;
use App\Models\Question;
use App\Models\User;
use App\Models\Video;
use App\Models\ZoomRecord;
use App\Support\PortalSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MemberPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        $heroVideoUrl = PortalSettings::get('portal.hero_video_url');
        $heroVideoId = PortalSettings::youtubeVideoId($heroVideoUrl);

        $heroVideo = Video::query()
            ->with('material')
            ->where('is_published', true)
            ->orderByDesc('is_preview')
            ->orderBy('sort_order')
            ->first();

        $latestZoomRecords = ZoomRecord::query()
            ->where('is_published', true)
            ->latest('recorded_at')
            ->limit(1)
            ->get();

        $mentors = MentorProfile::query()
            ->with('user')
            ->where('is_active', true)
            ->limit(2)
            ->get();

        $activeMeeting = [
            'title' => PortalSettings::get('portal.active_meeting_title', 'Workshop React Hooks - Batch 12'),
            'schedule' => PortalSettings::get('portal.active_meeting_schedule', 'Kamis, 23 Januari 2025'),
            'time' => PortalSettings::get('portal.active_meeting_time', '14:00 - 16:00 WIB'),
            'status' => PortalSettings::get('portal.active_meeting_status', 'Sedang Berlangsung'),
        ];

        $upcomingMeetings = collect([
            [
                'day' => 'Besok',
                'time' => '10:00 WIB',
                'title' => 'Database Design Basics',
            ],
            [
                'day' => 'Jumat',
                'time' => '14:00 WIB',
                'title' => 'API Integration Workshop',
            ],
        ]);

        $newUpdatesCount = MaterialUpdate::query()
            ->where('is_published', true)
            ->whereDate('published_at', '>=', now()->subDays(7))
            ->count();

        $menuCards = collect([
            [
                'title' => 'Dashboard Materi',
                'description' => 'Akses modul pembelajaran lengkap dan kurikulum pelatihan terbaru.',
                'action' => 'Buka Materi',
                'href' => route('member.materials'),
                'icon' => 'heroicon-o-book-open',
                'accent' => 'from-brand-500/20 via-brand-500/6 to-transparent',
                'iconWrap' => 'bg-brand-500/16 text-brand-200',
                'badge' => $newUpdatesCount > 0 ? $newUpdatesCount . ' baru' : null,
            ],
            [
                'title' => 'Tanya Jawab',
                'description' => 'Diskusikan pertanyaan Anda langsung dengan mentor dan komunitas.',
                'action' => 'Mulai Diskusi',
                'href' => route('member.questions'),
                'icon' => 'heroicon-o-chat-bubble-left-right',
                'accent' => 'from-mint-400/20 via-mint-400/6 to-transparent',
                'iconWrap' => 'bg-mint-400/14 text-mint-400',
            ],
            [
                'title' => 'Room Zoom Meeting',
                'description' => 'Pantau sesi live yang sedang berlangsung dan jadwal Zoom berikutnya.',
                'action' => 'Lihat Room',
                'href' => route('member.rooms'),
                'icon' => 'heroicon-o-video-camera',
                'accent' => 'from-amber-400/18 via-amber-400/6 to-transparent',
                'iconWrap' => 'bg-amber-400/14 text-amber-300',
            ],
            [
                'title' => 'Rekaman Zoom',
                'description' => 'Buka kembali sesi meeting yang sudah berlangsung dari arsip kelas.',
                'action' => 'Lihat Rekaman',
                'href' => route('member.zoom'),
                'icon' => 'heroicon-o-play-circle',
                'accent' => 'from-cyan-400/20 via-cyan-400/6 to-transparent',
                'iconWrap' => 'bg-cyan-400/14 text-cyan-300',
            ],
            [
                'title' => 'Data Mentor',
                'description' => 'Lihat mentor aktif, bidang keahlian, dan jalur pendampingan belajar.',
                'action' => 'Lihat Mentor',
                'href' => route('member.mentors'),
                'icon' => 'heroicon-o-academic-cap',
                'accent' => 'from-fuchsia-400/18 via-fuchsia-400/6 to-transparent',
                'iconWrap' => 'bg-fuchsia-400/14 text-fuchsia-300',
            ],
        ]);

        return view('member.dashboard', [
            'user' => $user,
            'heroVideo' => $heroVideo,
            'homeBadge' => PortalSettings::get('portal.home_badge'),
            'homeTitle' => PortalSettings::get('portal.hero_title', 'Selamat Datang di Alfaruq WFA'),
            'homeDescription' => PortalSettings::get('portal.hero_description', 'Akses materi pelatihan, sesi tanya jawab, dan rekaman pertemuan Anda dalam satu ruang kerja digital yang terintegrasi.'),
            'heroVideoEmbedId' => $heroVideoId ?: $heroVideo?->youtube_video_id,
            'heroVideoHeading' => PortalSettings::get('portal.hero_video_heading', 'Video Penjelasan'),
            'heroVideoCaption' => PortalSettings::get('portal.hero_video_caption', $heroVideo?->material?->title ?? 'Tempat Video'),
            'latestZoomRecord' => $latestZoomRecords->first(),
            'mentors' => $mentors,
            'activeMeeting' => $activeMeeting,
            'upcomingMeetings' => $upcomingMeetings,
            'menuCards' => $menuCards,
            'stats' => [
                'materials' => Material::query()->where('status', 'published')->count(),
                'zoomRecords' => ZoomRecord::query()->where('is_published', true)->count(),
                'questions' => Question::query()->where('member_id', $user->id)->count(),
            ],
        ]);
    }

    public function rooms(): View
    {
        return view('member.rooms.index', [
            'activeMeeting' => [
                'title' => 'Workshop React Hooks - Batch 12',
                'description' => 'Sesi live untuk membahas ritme produksi video dan struktur kelas minggu ini.',
                'schedule' => 'Kamis, 23 Januari 2025',
                'time' => '14:00 - 16:00 WIB',
                'meeting_id' => '123 456 7890',
                'password' => 'ReactHooks2025',
                'join_url' => 'https://zoom.us/j/1234567890',
            ],
            'upcomingMeetings' => collect([
                [
                    'day' => 'Besok',
                    'time' => '10:00 WIB',
                    'title' => 'Database Design Basics',
                    'description' => 'Bedah struktur database untuk LMS dan area member.',
                ],
                [
                    'day' => 'Jumat',
                    'time' => '14:00 WIB',
                    'title' => 'API Integration Workshop',
                    'description' => 'Integrasi API untuk unlock, notifikasi, dan distribusi materi.',
                ],
            ]),
        ]);
    }

    public function mentors(): View
    {
        return view('member.mentors.index', [
            'mentors' => MentorProfile::query()
                ->with('user')
                ->where('is_active', true)
                ->orderBy('display_name')
                ->get(),
        ]);
    }

    public function materials(Request $request): View
    {
        return view('member.materials.index', [
            'materials' => Material::query()
                ->with(['mentor', 'videos'])
                ->where('status', 'published')
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->paginate(8),
            'latestUpdates' => MaterialUpdate::query()
                ->with('material')
                ->where('is_published', true)
                ->latest('published_at')
                ->limit(3)
                ->get(),
            'user' => $request->user(),
        ]);
    }

    public function showMaterial(Request $request, Material $material): View
    {
        $material->load([
            'mentor',
            'videos' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order'),
            'pdfDocuments' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order'),
            'updates' => fn ($query) => $query->where('is_published', true)->latest('published_at'),
        ]);

        $primaryVideo = $material->videos->first();
        $canAccessMaterial = $this->userCanAccessContent($request->user(), $material);
        $canAccessPrimaryVideo = $primaryVideo
            ? $this->userCanAccessContent($request->user(), $primaryVideo)
            : false;

        return view('member.materials.show', [
            'material' => $material,
            'primaryVideo' => $primaryVideo,
            'canAccessMaterial' => $canAccessMaterial,
            'canAccessPrimaryVideo' => $canAccessPrimaryVideo,
        ]);
    }

    public function zoomRecords(Request $request): View
    {
        return view('member.zoom.index', [
            'zoomRecords' => ZoomRecord::query()
                ->where('is_published', true)
                ->orderByDesc('recorded_at')
                ->paginate(8),
            'user' => $request->user(),
        ]);
    }

    public function questions(Request $request): View
    {
        return view('member.questions.index', [
            'questions' => Question::query()
                ->with(['mentor', 'material', 'answers'])
                ->where('member_id', $request->user()->id)
                ->latest()
                ->paginate(10),
            'mentors' => MentorProfile::query()
                ->with('user')
                ->where('is_active', true)
                ->get(),
            'materials' => Material::query()
                ->where('status', 'published')
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function storeQuestion(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mentor_id' => ['nullable', 'exists:users,id'],
            'material_id' => ['nullable', 'exists:materials,id'],
            'subject' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string', 'min:10'],
        ], [
            'subject.required' => 'Subjek pertanyaan wajib diisi.',
            'question.required' => 'Pertanyaan wajib diisi.',
            'question.min' => 'Pertanyaan minimal 10 karakter.',
        ]);

        Question::query()->create([
            ...$validated,
            'member_id' => $request->user()->id,
            'status' => 'pending',
            'is_public' => false,
            'asked_at' => now(),
        ]);

        return back()->with('status', 'Pertanyaan berhasil dikirim ke mentor.');
    }

    public function updates(): View
    {
        return view('member.updates.index', [
            'updates' => MaterialUpdate::query()
                ->with('material')
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(10),
        ]);
    }

    protected function userCanAccessContent(User $user, Material|Video|ZoomRecord $content): bool
    {
        if ($user->hasAnyRole(['super_admin', 'admin', 'mentor'])) {
            return true;
        }

        $accessType = $content->access_type ?? 'free';

        if ($accessType === 'free') {
            return true;
        }

        if ($content instanceof Video && $content->is_preview) {
            return true;
        }

        $now = Carbon::now();

        $unlockExists = ContentUnlock::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where(function ($query) use ($content): void {
                $query
                    ->where(function ($nested) use ($content): void {
                        $nested
                            ->where('unlockable_type', $content::class)
                            ->where('unlockable_id', $content->id);
                    });

                if ($content instanceof Video) {
                    $query->orWhere(function ($nested) use ($content): void {
                        $nested
                            ->where('unlockable_type', Material::class)
                            ->where('unlockable_id', $content->material_id);
                    });
                }
            })
            ->where(function ($query) use ($now): void {
                $query
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now): void {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            })
            ->exists();

        return $unlockExists;
    }
}
