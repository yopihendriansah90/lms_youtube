<x-member-layout title="Beranda Member">
    <section class="page-stack">
        <div class="px-1 pt-2 text-center lg:px-0 lg:pt-4">
            @if (filled($homeBadge))
                <span class="section-pill">{{ $homeBadge }}</span>
            @endif
            <h1 class="display-heading mx-auto mt-5 max-w-[13ch] lg:max-w-[26ch] lg:text-[4.4rem] lg:leading-[0.92]">{{ $homeTitle }}</h1>
            <p class="body-copy mx-auto mt-4 max-w-[30ch] lg:max-w-[70ch] lg:text-[1.05rem] lg:leading-8">{{ $homeDescription }}</p>
            <div class="mx-auto mt-6 flex h-9 w-5 items-start justify-center rounded-full border border-white/25 p-[3px]">
                <div class="h-3 w-1 rounded-full bg-white/70"></div>
            </div>
        </div>

        <div class="desktop-stats">
            <div class="stat-card">
                <p class="meta-copy">Total Materi</p>
                <p class="stat-value">{{ $stats['materials'] }}</p>
                <p class="supporting-copy mt-3">Modul aktif yang siap dipelajari dari dashboard member.</p>
            </div>
            <div class="stat-card">
                <p class="meta-copy">Rekaman Zoom</p>
                <p class="stat-value">{{ $stats['zoomRecords'] }}</p>
                <p class="supporting-copy mt-3">Arsip sesi kelas dan workshop yang sudah tersedia.</p>
            </div>
            <div class="stat-card">
                <p class="meta-copy">Pertanyaan Anda</p>
                <p class="stat-value">{{ $stats['questions'] }}</p>
                <p class="supporting-copy mt-3">Riwayat diskusi member yang sudah pernah Anda kirim.</p>
            </div>
        </div>

        <div>
            <div class="hero-stage hero-stage-expanded">
                <div class="absolute -right-12 top-16 h-32 w-32 rounded-full bg-brand-500/12 blur-3xl"></div>
                <div class="absolute left-6 top-6 h-14 w-14 rounded-full border border-white/8 bg-white/[0.03]"></div>
                <div class="relative">
                    <div class="flex min-w-0 items-center justify-between gap-4">
                        <span class="hero-kicker">Program Utama</span>
                        <span class="shrink-0 text-xs font-semibold tracking-[0.16em] text-brand-200 uppercase">Mulai Belajar</span>
                    </div>

                    <div class="hero-video-shell hero-stage-video">
                            @if ($heroVideo)
                                <div class="h-full w-full">
                                    <iframe
                                        class="h-full w-full"
                                        src="https://www.youtube.com/embed/{{ $heroVideoEmbedId }}"
                                        title="{{ $heroVideo->title }}"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                            @else
                                <div class="flex aspect-video items-center justify-center text-white/60">Video Placeholder</div>
                            @endif
                    </div>

                    <div class="px-1 pb-1 pt-5">
                        <p class="eyebrow">Video Penjelasan</p>
                        <h2 class="section-title mt-3 max-w-full lg:text-[2.15rem] lg:leading-[1] xl:text-[2.35rem]">{{ $heroVideoHeading }}</h2>
                        <p class="body-copy mt-3 max-w-[28ch] lg:max-w-[52ch] lg:text-[1rem] lg:leading-8">{{ $heroVideoCaption }}</p>
                        <div class="desktop-hero-copy mt-6">
                            <div class="inline-flex items-center gap-2 rounded-full border border-white/8 bg-white/[0.03] px-4 py-2 text-sm font-semibold text-white/72">
                                <span>Portal pembelajaran</span>
                                <span class="text-brand-200">terintegrasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-card-slider">
            @foreach ($menuCards as $card)
                <a href="{{ $card['href'] }}" class="dashboard-card compact min-h-[15.5rem]">
                    <div class="pointer-events-none absolute -right-8 top-5 h-24 w-24 rounded-full bg-gradient-to-br {{ $card['accent'] }} blur-sm"></div>
                    <div class="pointer-events-none absolute inset-x-4 bottom-0 h-px bg-gradient-to-r from-transparent via-white/8 to-transparent"></div>

                    <div class="relative">
                        <div class="mb-6 flex items-start justify-between gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-[14px] shadow-[inset_0_1px_0_rgba(255,255,255,0.06)] {{ $card['iconWrap'] }}">
                                <x-dynamic-component :component="$card['icon']" class="h-5 w-5" />
                            </div>

                            @if (! empty($card['badge']))
                                <span class="rounded-full bg-violet-500/18 px-2.5 py-1 text-[10px] font-semibold tracking-[0.08em] text-violet-200">{{ $card['badge'] }}</span>
                            @endif
                        </div>

                        <p class="meta-copy">Akses Menu</p>
                        <h3 class="mini-card-title max-w-full">{{ $card['title'] }}</h3>
                        <p class="body-copy mt-3 max-w-[24ch]">{{ $card['description'] }}</p>
                        <div class="mt-7 inline-flex items-center gap-2 text-[13px] font-semibold tracking-[-0.02em] text-white">
                            <span>{{ $card['action'] }}</span>
                            <span>→</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="split-showcase">
            <div class="rich-card">
                <div class="flex min-w-0 items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="meta-copy">Room Zoom Meeting</p>
                        <h2 class="card-title mt-2 max-w-full whitespace-normal md:whitespace-nowrap lg:text-[1.55rem] xl:text-[1.7rem]">
                            Sesi Berlangsung & Jadwal Terdekat
                        </h2>
                    </div>
                    <a href="{{ route('member.rooms') }}" class="card-link shrink-0 pt-7">Lihat</a>
                </div>

                <div class="live-panel mt-5">
                    <span class="status-chip is-live">Sedang Berlangsung</span>
                    <h3 class="card-heading mt-4 break-words">{{ $activeMeeting['title'] }}</h3>
                    <p class="supporting-copy mt-2 break-words">{{ $activeMeeting['schedule'] }} • {{ $activeMeeting['time'] }}</p>
                </div>

                <div class="mt-4 grid gap-3">
                    @foreach ($upcomingMeetings as $meeting)
                        <div class="info-item">
                            <div class="flex items-center justify-between gap-3">
                                <span class="eyebrow">{{ $meeting['day'] }}</span>
                                <span class="text-xs text-white/45">{{ $meeting['time'] }}</span>
                            </div>
                            <p class="mt-3 break-words text-sm font-semibold text-white">{{ $meeting['title'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rich-card">
                <div class="flex min-w-0 items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="meta-copy">Profil Mentor</p>
                        <h2 class="card-title mt-2 max-w-full whitespace-normal lg:text-[1.55rem] xl:text-[1.7rem]">
                            Mentor Aktif
                        </h2>
                    </div>
                </div>

                <div class="mt-5 grid gap-4">
                    @forelse ($mentors as $mentor)
                        <article class="feature-list-card">
                            <div class="flex items-center gap-4">
                                <div class="mentor-avatar shrink-0">
                                    @if ($mentor->photo_url)
                                        <img
                                            src="{{ $mentor->photo_url }}"
                                            alt="{{ $mentor->display_name }}"
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                        >
                                    @else
                                        <span>{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($mentor->display_name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="card-heading">{{ $mentor->display_name }}</h3>
                                    <p class="body-copy mt-2">{{ $mentor->speciality ?: 'Mentor utama kelas' }}</p>
                                </div>
                            </div>

                            @if ($mentor->bio)
                                <p class="body-copy mt-4 line-clamp-3">{{ $mentor->bio }}</p>
                            @endif
                        </article>
                    @empty
                        <div class="feature-list-card">
                            <p class="body-copy">Profil mentor aktif belum tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="desktop-footer">
            <p>Alfaruq WFA Learning Portal</p>
            <div class="flex items-center gap-4">
                <span>Materi digital terstruktur</span>
                <span>Rekaman kelas terarsip</span>
                <span>Support mentor aktif</span>
            </div>
        </div>
    </section>
</x-member-layout>
