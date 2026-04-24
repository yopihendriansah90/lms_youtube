<?php

namespace App\Http\Controllers;

use App\Jobs\SendZoomRoomQuestionNotificationJob;
use App\Models\ContentUnlock;
use App\Models\Material;
use App\Models\MaterialUpdate;
use App\Models\MentorProfile;
use App\Models\PdfDocument;
use App\Models\User;
use App\Models\Video;
use App\Models\ZoomRecord;
use App\Models\ZoomRoom;
use App\Models\ZoomRoomQuestion;
use App\Support\PortalSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MemberPortalController extends Controller
{
    public function publicHome(Request $request): View
    {
        $heroVideoUrl = PortalSettings::get('portal.hero_video_url');
        $heroVideoId = PortalSettings::youtubeVideoId($heroVideoUrl);

        $heroVideo = Video::query()
            ->with('material')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->first();

        $latestZoomRecords = ZoomRecord::query()
            ->where('is_published', true)
            ->latest('recorded_at')
            ->limit(1)
            ->get();

        $liveZoomRoom = ZoomRoom::query()
            ->where('is_published', true)
            ->where('status', 'live')
            ->orderByDesc('starts_at')
            ->first();

        $upcomingZoomRooms = ZoomRoom::query()
            ->where('is_published', true)
            ->where('status', 'scheduled')
            ->orderBy('starts_at')
            ->limit(2)
            ->get();

        $mentors = MentorProfile::query()
            ->with('user')
            ->where('is_active', true)
            ->orderBy('display_name')
            ->limit(2)
            ->get();

        $activeMeeting = [
            'title' => $liveZoomRoom?->title ?: PortalSettings::get('portal.active_meeting_title', 'Workshop React Hooks - Batch 12'),
            'schedule' => $liveZoomRoom?->starts_at?->translatedFormat('l, d F Y') ?: PortalSettings::get('portal.active_meeting_schedule', 'Kamis, 23 Januari 2025'),
            'time' => $liveZoomRoom?->starts_at
                ? $liveZoomRoom->starts_at->format('H:i') . ' WIB'
                : PortalSettings::get('portal.active_meeting_time', '14:00 - 16:00 WIB'),
            'status' => $liveZoomRoom ? 'Sedang Berlangsung' : PortalSettings::get('portal.active_meeting_status', 'Sedang Berlangsung'),
        ];

        $upcomingMeetings = $upcomingZoomRooms->map(function (ZoomRoom $room): array {
            return [
                'day' => $room->starts_at?->translatedFormat('D') ?? 'Jadwal',
                'time' => $room->starts_at?->format('H:i').' WIB' ?? '-',
                'title' => $room->title,
            ];
        });

        $featuredRoomCard = $liveZoomRoom
            ?: ZoomRoom::query()
                ->where('is_published', true)
                ->where('status', 'scheduled')
                ->orderBy('starts_at')
                ->first();

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
                'badge' => $newUpdatesCount > 0 ? $newUpdatesCount.' baru' : null,
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
                'title' => $featuredRoomCard?->title ?? 'Jadwal Zoom Berikutnya',
                'description' => $featuredRoomCard
                    ? collect([
                        $featuredRoomCard->status === 'live' ? 'Sedang berlangsung sekarang.' : 'Sesi terdekat yang siap diikuti member.',
                        $featuredRoomCard->starts_at?->translatedFormat('d M Y') ?: null,
                        $featuredRoomCard->starts_at ? $featuredRoomCard->starts_at->format('H:i') . ' WIB' : null,
                    ])->filter()->join(' ')
                    : 'Pantau sesi live terbaru atau jadwal Zoom berikutnya dari dashboard.',
                'action' => $featuredRoomCard?->status === 'live' ? 'Masuk ke Room' : 'Lihat Jadwal',
                'href' => $featuredRoomCard
                    ? route('member.rooms', ['room' => $featuredRoomCard->slug])
                    : route('member.rooms'),
                'icon' => 'heroicon-o-signal',
                'accent' => 'from-mint-400/18 via-mint-400/6 to-transparent',
                'iconWrap' => 'bg-mint-400/14 text-mint-300',
                'badge' => $featuredRoomCard
                    ? ($featuredRoomCard->status === 'live' ? 'Live' : 'Segera')
                    : null,
            ],
        ]);

        return view('member.dashboard', [
            'user' => $request->user(),
            'isPublicHome' => true,
            'heroVideo' => $heroVideo,
            'homeBadge' => PortalSettings::get('portal.home_badge'),
            'homeTitle' => PortalSettings::get('portal.hero_title', 'Selamat Datang di Alfaruq WFA'),
            'homeDescription' => PortalSettings::get('portal.hero_description', 'Akses materi pelatihan, sesi tanya jawab, dan rekaman pertemuan Anda dalam satu ruang kerja digital yang terintegrasi.'),
            'heroVideoEmbedId' => $heroVideoId ?: $heroVideo?->youtube_video_id,
            'heroVideoHeading' => PortalSettings::get('portal.hero_video_heading', 'Video Penjelasan'),
            'heroVideoCaption' => PortalSettings::get('portal.hero_video_caption', $heroVideo?->material?->title ?? 'Tempat Video'),
            'latestZoomRecord' => $latestZoomRecords->first(),
            'activeMeeting' => $activeMeeting,
            'upcomingMeetings' => $upcomingMeetings,
            'mentors' => $mentors,
            'menuCards' => $menuCards,
            'stats' => [
                'materials' => Material::query()->where('status', 'published')->count(),
                'zoomRecords' => ZoomRecord::query()->where('is_published', true)->count(),
                'questions' => ZoomRoomQuestion::query()->count(),
            ],
        ]);
    }

    public function dashboard(Request $request): View
    {
        $user = $request->user();

        $heroVideoUrl = PortalSettings::get('portal.hero_video_url');
        $heroVideoId = PortalSettings::youtubeVideoId($heroVideoUrl);

        $heroVideo = Video::query()
            ->with('material')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->first();

        $latestZoomRecords = ZoomRecord::query()
            ->where('is_published', true)
            ->latest('recorded_at')
            ->limit(1)
            ->get();

        $liveZoomRoom = ZoomRoom::query()
            ->where('is_published', true)
            ->where('status', 'live')
            ->orderByDesc('starts_at')
            ->first();

        $upcomingZoomRooms = ZoomRoom::query()
            ->where('is_published', true)
            ->where('status', 'scheduled')
            ->orderBy('starts_at')
            ->limit(2)
            ->get();

        $mentors = MentorProfile::query()
            ->with('user')
            ->where('is_active', true)
            ->orderBy('display_name')
            ->limit(2)
            ->get();

        $activeMeeting = [
            'title' => $liveZoomRoom?->title ?: PortalSettings::get('portal.active_meeting_title', 'Workshop React Hooks - Batch 12'),
            'schedule' => $liveZoomRoom?->starts_at?->translatedFormat('l, d F Y') ?: PortalSettings::get('portal.active_meeting_schedule', 'Kamis, 23 Januari 2025'),
            'time' => $liveZoomRoom?->starts_at
                ? $liveZoomRoom->starts_at->format('H:i') . ' WIB'
                : PortalSettings::get('portal.active_meeting_time', '14:00 - 16:00 WIB'),
            'status' => $liveZoomRoom ? 'Sedang Berlangsung' : PortalSettings::get('portal.active_meeting_status', 'Sedang Berlangsung'),
        ];

        $upcomingMeetings = $upcomingZoomRooms->map(function (ZoomRoom $room): array {
            return [
                'day' => $room->starts_at?->translatedFormat('D') ?? 'Jadwal',
                'time' => $room->starts_at?->format('H:i').' WIB' ?? '-',
                'title' => $room->title,
            ];
        });

        $featuredRoomCard = $liveZoomRoom
            ?: ZoomRoom::query()
                ->where('is_published', true)
                ->where('status', 'scheduled')
                ->orderBy('starts_at')
                ->first();

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
                'badge' => $newUpdatesCount > 0 ? $newUpdatesCount.' baru' : null,
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
                'title' => $featuredRoomCard?->title ?? 'Jadwal Zoom Berikutnya',
                'description' => $featuredRoomCard
                    ? collect([
                        $featuredRoomCard->status === 'live' ? 'Sedang berlangsung sekarang.' : 'Sesi terdekat yang siap diikuti member.',
                        $featuredRoomCard->starts_at?->translatedFormat('d M Y') ?: null,
                        $featuredRoomCard->starts_at ? $featuredRoomCard->starts_at->format('H:i') . ' WIB' : null,
                    ])->filter()->join(' ')
                    : 'Pantau sesi live terbaru atau jadwal Zoom berikutnya dari dashboard.',
                'action' => $featuredRoomCard?->status === 'live' ? 'Masuk ke Room' : 'Lihat Jadwal',
                'href' => $featuredRoomCard
                    ? route('member.rooms', ['room' => $featuredRoomCard->slug])
                    : route('member.rooms'),
                'icon' => 'heroicon-o-signal',
                'accent' => 'from-mint-400/18 via-mint-400/6 to-transparent',
                'iconWrap' => 'bg-mint-400/14 text-mint-300',
                'badge' => $featuredRoomCard
                    ? ($featuredRoomCard->status === 'live' ? 'Live' : 'Segera')
                    : null,
            ],
        ]);

        return view('member.dashboard', [
            'user' => $user,
            'isPublicHome' => false,
            'heroVideo' => $heroVideo,
            'homeBadge' => PortalSettings::get('portal.home_badge'),
            'homeTitle' => PortalSettings::get('portal.hero_title', 'Selamat Datang di Alfaruq WFA'),
            'homeDescription' => PortalSettings::get('portal.hero_description', 'Akses materi pelatihan, sesi tanya jawab, dan rekaman pertemuan Anda dalam satu ruang kerja digital yang terintegrasi.'),
            'heroVideoEmbedId' => $heroVideoId ?: $heroVideo?->youtube_video_id,
            'heroVideoHeading' => PortalSettings::get('portal.hero_video_heading', 'Video Penjelasan'),
            'heroVideoCaption' => PortalSettings::get('portal.hero_video_caption', $heroVideo?->material?->title ?? 'Tempat Video'),
            'latestZoomRecord' => $latestZoomRecords->first(),
            'activeMeeting' => $activeMeeting,
            'upcomingMeetings' => $upcomingMeetings,
            'mentors' => $mentors,
            'menuCards' => $menuCards,
            'stats' => [
                'materials' => Material::query()->where('status', 'published')->count(),
                'zoomRecords' => ZoomRecord::query()->where('is_published', true)->count(),
                'questions' => ZoomRoomQuestion::query()->where('member_id', $user->id)->count(),
            ],
        ]);
    }

    public function rooms(Request $request): View
    {
        $selectedSlug = $request->string('room')->toString();

        $roomOrderSql = "CASE status WHEN 'live' THEN 0 WHEN 'scheduled' THEN 1 ELSE 2 END";

        $selectedRoom = ZoomRoom::query()
            ->with([
                'program',
                'mentor',
                'questions' => fn ($query) => $query
                    ->with('member')
                    ->latest('asked_at')
                    ->latest('id')
                    ->limit(12),
            ])
            ->withCount('questions')
            ->where('is_published', true)
            ->when(
                filled($selectedSlug),
                fn ($query) => $query->where('slug', $selectedSlug),
                fn ($query) => $query->orderByRaw($roomOrderSql)->orderByDesc('starts_at')->orderBy('sort_order')
            )
            ->first();

        $zoomRooms = ZoomRoom::query()
            ->with(['program', 'mentor'])
            ->withCount('questions')
            ->where('is_published', true)
            ->orderByRaw($roomOrderSql)
            ->orderByDesc('starts_at')
            ->orderBy('sort_order')
            ->paginate(6);

        $selectedRoom ??= $zoomRooms->first();

        return view('member.rooms.index', [
            'selectedRoom' => $selectedRoom,
            'zoomRooms' => $zoomRooms,
            'canAskQuestion' => $selectedRoom?->isLive() ?? false,
        ]);
    }

    public function storeZoomRoomQuestion(Request $request, ZoomRoom $zoomRoom): RedirectResponse
    {
        abort_unless($zoomRoom->is_published, 404);

        if (! $zoomRoom->isLive()) {
            return redirect()
                ->route('member.rooms', ['room' => $zoomRoom->slug])
                ->with('status', 'Pertanyaan hanya bisa dikirim saat sesi Zoom sedang berlangsung.');
        }

        $validated = $request->validate([
            'subject' => ['nullable', 'string', 'max:255'],
            'question' => ['required', 'string', 'min:10'],
        ], [
            'question.required' => 'Pertanyaan wajib diisi.',
            'question.min' => 'Pertanyaan minimal 10 karakter.',
        ]);

        $zoomRoomQuestion = ZoomRoomQuestion::query()->create([
            'zoom_room_id' => $zoomRoom->id,
            'member_id' => $request->user()->id,
            'subject' => blank($validated['subject'] ?? null) ? null : $validated['subject'],
            'question' => $validated['question'],
            'asked_at' => now(),
        ]);

        SendZoomRoomQuestionNotificationJob::dispatch($zoomRoomQuestion->id)->afterCommit();

        return redirect()
            ->route('member.rooms', ['room' => $zoomRoom->slug])
            ->with('status', 'Pertanyaan berhasil dikirim ke mentor untuk sesi live ini.');
    }

    public function materials(Request $request): View
    {
        $user = $request->user();

        $featuredMaterial = Material::query()
            ->with(['mentor', 'videos'])
            ->where('status', 'published')
            ->whereHas('videos', fn ($query) => $query->where('is_published', true))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->first();

        $featuredPrimaryVideo = $featuredMaterial?->videos
            ->where('is_published', true)
            ->sortBy('sort_order')
            ->first();

        $featuredCanAccess = $featuredPrimaryVideo
            ? $this->userCanAccessContent($user, $featuredPrimaryVideo)
            : false;

        $materials = Material::query()
            ->with([
                'mentor',
                'videos' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order'),
            ])
            ->where('status', 'published')
            ->whereHas('videos', fn ($query) => $query->where('is_published', true))
            ->when($featuredMaterial, fn ($query) => $query->whereKeyNot($featuredMaterial->getKey()))
            ->orderBy('sort_order')
            ->paginate(8)
            ->through(function (Material $material) use ($user) {
                $primaryVideo = $material->videos->first();

                $material->setRelation('primary_video', $primaryVideo);
                $material->setAttribute('can_access_primary_video', $primaryVideo
                    ? $this->userCanAccessContent($user, $primaryVideo)
                    : false);

                return $material;
            });

        return view('member.materials.index', [
            'materials' => $materials,
            'featuredMaterial' => $featuredMaterial,
            'featuredPrimaryVideo' => $featuredPrimaryVideo,
            'featuredCanAccess' => $featuredCanAccess,
            'materialStats' => [
                'total_materials' => Material::query()->where('status', 'published')->count(),
                'total_videos' => Video::query()->where('is_published', true)->count(),
                'new_updates' => MaterialUpdate::query()
                    ->where('is_published', true)
                    ->whereDate('published_at', '>=', now()->subDays(7))
                    ->count(),
            ],
            'user' => $request->user(),
        ]);
    }

    public function showMaterial(Request $request, Material $material): View
    {
        $adminWhatsAppUrl = PortalSettings::whatsappUrl(
            $this->buildAccessRequestMessage(
                memberName: $request->user()->name,
                materialTitle: $material->title,
            )
        );

        $material->load([
            'mentor',
            'videos' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order'),
            'pdfDocuments' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order'),
        ]);

        $canAccessMaterial = $this->userCanAccessContent($request->user(), $material);
        $selectedVideoId = $request->integer('video');

        $videos = $material->videos->map(function (Video $video) use ($request, $material): Video {
            $canAccessVideo = $this->userCanAccessContent($request->user(), $video);

            $video->setAttribute('can_access', $canAccessVideo);
            $video->setAttribute('thumbnail_url', $video->youtube_video_id
                ? "https://img.youtube.com/vi/{$video->youtube_video_id}/hqdefault.jpg"
                : null);
            $video->setAttribute('request_access_url', $canAccessVideo
                ? null
                : PortalSettings::whatsappUrl(
                    $this->buildAccessRequestMessage(
                        memberName: $request->user()->name,
                        materialTitle: $material->title,
                        videoTitle: $video->title,
                    )
                ));

            return $video;
        });

        $material->setRelation('videos', $videos);

        $pdfDocuments = $material->pdfDocuments->map(function (PdfDocument $document) use ($request, $material): PdfDocument {
            $canAccessDocument = $this->userCanAccessContent($request->user(), $document);
            $media = $document->getFirstMedia('documents');

            $document->setAttribute('access_type', 'free');
            $document->setAttribute('can_access', $canAccessDocument);
            $document->setAttribute('download_url', $canAccessDocument && $media
                ? route('member.materials.documents.show', ['material' => $material, 'document' => $document])
                : null);
            $document->setAttribute('file_name', $media ? $document->downloadFileName() : null);

            return $document;
        });

        $material->setRelation('pdfDocuments', $pdfDocuments);

        $primaryVideo = $videos->firstWhere('id', $selectedVideoId) ?: $videos->first();
        $canAccessPrimaryVideo = $primaryVideo?->can_access ?? false;

        return view('member.materials.show', [
            'material' => $material,
            'primaryVideo' => $primaryVideo,
            'canAccessMaterial' => $canAccessMaterial,
            'canAccessPrimaryVideo' => $canAccessPrimaryVideo,
            'materialRequestAccessUrl' => $adminWhatsAppUrl,
        ]);
    }

    public function showPdfDocument(Request $request, Material $material, PdfDocument $document): BinaryFileResponse|RedirectResponse
    {
        abort_unless($document->material_id === $material->id, 404);
        abort_unless($document->is_published, 404);

        if (! $this->userCanAccessContent($request->user(), $document)) {
            return redirect()
                ->route('member.materials.show', $material)
                ->with('status', 'Dokumen ini masih terkunci. Silakan buka akses materi premium terlebih dahulu.');
        }

        $media = $document->getFirstMedia('documents');

        abort_unless($media !== null, 404);

        return response()->file(
            $media->getPath(),
            [
                'Content-Disposition' => 'inline; filename="'.$document->downloadFileName().'"',
            ]
        );
    }

    public function zoomRecords(Request $request): View
    {
        $selectedSlug = $request->string('watch')->toString();
        $shouldAutoplay = $request->boolean('autoplay');

        $activeZoomRecord = ZoomRecord::query()
            ->where('is_published', true)
            ->when(
                filled($selectedSlug),
                fn ($query) => $query->where('slug', $selectedSlug),
                fn ($query) => $query->orderByDesc('recorded_at')->orderBy('sort_order')
            )
            ->first();

        $zoomRecords = ZoomRecord::query()
            ->where('is_published', true)
            ->orderByDesc('recorded_at')
            ->orderBy('sort_order')
            ->paginate(9)
            ->through(function (ZoomRecord $record): ZoomRecord {
                $record->setAttribute('youtube_embed_id', $record->youtube_video_id ?: PortalSettings::youtubeVideoId($record->youtube_url));
                $record->setAttribute('thumbnail_url', $record->thumbnail_url);

                return $record;
            });

        $activeZoomRecord ??= $zoomRecords->first();

        if ($activeZoomRecord) {
            $activeZoomRecord->setAttribute('youtube_embed_id', $activeZoomRecord->youtube_video_id ?: PortalSettings::youtubeVideoId($activeZoomRecord->youtube_url));
            $activeZoomRecord->setAttribute('thumbnail_url', $activeZoomRecord->thumbnail_url);
            $activeZoomRecord->setAttribute('can_access', $this->userCanAccessContent($request->user(), $activeZoomRecord));
            $activeZoomRecord->setAttribute('request_access_url', $activeZoomRecord->can_access
                ? null
                : PortalSettings::whatsappUrl(
                    $this->buildZoomAccessRequestMessage(
                        memberName: $request->user()->name,
                        zoomTitle: $activeZoomRecord->title,
                    )
                ));
        }

        return view('member.zoom.index', [
            'zoomRecords' => $zoomRecords,
            'activeZoomRecord' => $activeZoomRecord,
            'shouldAutoplayActiveZoom' => $shouldAutoplay,
            'user' => $request->user(),
        ]);
    }

    protected function userCanAccessContent(User $user, Material|Video|ZoomRecord|PdfDocument $content): bool
    {
        if ($user->hasAnyRole(['super_admin', 'admin', 'mentor'])) {
            return true;
        }

        if ($content instanceof PdfDocument) {
            return true;
        }

        $accessType = $content->access_type ?? 'free';

        if ($accessType === 'free') {
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

                if ($content instanceof PdfDocument) {
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

    protected function buildAccessRequestMessage(
        string $memberName,
        string $materialTitle,
        ?string $videoTitle = null,
    ): string {
        $message = [
            'Halo admin, saya ingin meminta akses ke materi premium.',
            '',
            'Nama member: '.$memberName,
            'Materi: '.$materialTitle,
        ];

        if (filled($videoTitle)) {
            $message[] = 'Video: '.$videoTitle;
        }

        $message[] = '';
        $message[] = 'Mohon dibantu untuk proses aksesnya. Terima kasih.';

        return Str::of(implode("\n", $message))->trim()->toString();
    }

    protected function buildZoomAccessRequestMessage(
        string $memberName,
        string $zoomTitle,
    ): string {
        $message = [
            'Halo admin, saya ingin meminta akses ke rekaman Zoom.',
            '',
            'Nama member: '.$memberName,
            'Rekaman Zoom: '.$zoomTitle,
            '',
            'Mohon dibantu untuk proses aksesnya. Terima kasih.',
        ];

        return Str::of(implode("\n", $message))->trim()->toString();
    }
}
