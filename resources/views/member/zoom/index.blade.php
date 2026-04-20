<x-member-layout title="Rekaman Zoom">
    <section class="page-stack">
        <div class="section-header">
            <span class="section-pill">Rekaman Zoom</span>
            <h1 class="section-header-title">Rekaman Sesi Zoom</h1>
            <p class="section-header-copy">
                Semua sesi kelas, workshop, dan diskusi Zoom tersimpan rapi agar dapat ditonton ulang kapan saja dari area member.
            </p>
            <div class="section-header-indicator">
                <div class="section-header-indicator-dot"></div>
            </div>
        </div>

        @if ($activeZoomRecord)
            <div
                id="zoom-player"
                class="zoom-player-shell surface-card"
                data-zoom-parallax
            >
                <div class="zoom-player-orb"></div>
                <div class="zoom-player-grid">
                    <div class="zoom-player-stage">
                        @if ($activeZoomRecord->can_access && $activeZoomRecord->youtube_embed_id)
                            <div class="zoom-player-frame">
                                <iframe
                                    class="h-full w-full"
                                    src="https://www.youtube.com/embed/{{ $activeZoomRecord->youtube_embed_id }}?rel=0&modestbranding=1&autoplay={{ $shouldAutoplayActiveZoom ? '1' : '0' }}"
                                    title="{{ $activeZoomRecord->title }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        @elseif ($activeZoomRecord->thumbnail_url)
                            <div class="zoom-player-frame">
                                <img
                                    src="{{ $activeZoomRecord->thumbnail_url }}"
                                    alt="{{ $activeZoomRecord->title }}"
                                    class="h-full w-full object-cover"
                                    loading="eager"
                                >
                                <div class="zoom-player-overlay">
                                    <span class="status-chip is-paid">Akses Diperlukan</span>
                                    <h2 class="section-title mt-5">Rekaman Belum Terbuka</h2>
                                    <p class="body-copy mt-3 max-w-xl text-center">
                                        Rekaman Zoom ini termasuk konten premium. Akses akun perlu dibuka terlebih dahulu sebelum video dapat diputar.
                                    </p>
                                    @if ($activeZoomRecord->request_access_url)
                                        <a
                                            href="{{ $activeZoomRecord->request_access_url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="request-access-btn mt-5"
                                        >
                                            Minta Akses
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="zoom-player-frame zoom-player-placeholder">
                                <span>Video belum tersedia</span>
                            </div>
                        @endif
                    </div>

                    <div class="zoom-player-meta">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="status-chip {{ $activeZoomRecord->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                                {{ $activeZoomRecord->access_type === 'free' ? 'Gratis' : 'Premium' }}
                            </span>
                            <span class="inline-chip">Rekaman YouTube</span>
                            @if ($activeZoomRecord->recorded_at)
                                <span class="inline-chip">{{ $activeZoomRecord->recorded_at->translatedFormat('d F Y') }}</span>
                            @endif
                        </div>

                        <h2 class="zoom-player-title">{{ $activeZoomRecord->title }}</h2>
                        <p class="body-copy mt-3 max-w-3xl">
                            {{ $activeZoomRecord->description ?: 'Rekaman sesi Zoom ini tersedia untuk ditonton ulang langsung dari portal member.' }}
                        </p>

                        <div class="zoom-meta-grid">
                            <div class="content-mini-panel">
                                <p class="meta-copy">Tanggal Rekaman</p>
                                <p class="mt-2 text-sm font-semibold text-white">
                                    {{ optional($activeZoomRecord->recorded_at)->translatedFormat('d F Y') ?: 'Tanggal belum tersedia' }}
                                </p>
                            </div>
                            <div class="content-mini-panel">
                                <p class="meta-copy">Tipe Akses</p>
                                <p class="mt-2 text-sm font-semibold text-white">
                                    {{ $activeZoomRecord->access_type === 'free' ? 'Terbuka untuk semua member' : 'Mengikuti akses premium' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="rich-card">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="meta-copy">Arsip Rekaman</p>
                    <h2 class="section-title mt-2">Daftar Video Zoom</h2>
                </div>
                <span class="eyebrow">{{ $zoomRecords->total() }} video</span>
            </div>

            <div class="zoom-record-grid">
                @foreach ($zoomRecords as $record)
                    @php
                        $isActive = $activeZoomRecord?->is($record);
                    @endphp

                    <article class="group zoom-record-card {{ $isActive ? 'is-active' : '' }}">
                        <a
                            href="{{ route('member.zoom', ['watch' => $record->slug, 'autoplay' => 1, 'page' => $zoomRecords->currentPage()]) }}#zoom-player"
                            class="block"
                        >
                            <div class="zoom-record-media">
                                @if ($record->thumbnail_url)
                                    <img
                                        src="{{ $record->thumbnail_url }}"
                                        alt="{{ $record->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="zoom-record-placeholder">
                                        <span>Thumbnail YouTube</span>
                                    </div>
                                @endif

                                <div class="zoom-record-media-overlay">
                                    <span class="zoom-record-play">Putar</span>
                                    <span class="status-chip {{ $record->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                                        {{ $record->access_type === 'free' ? 'Gratis' : 'Premium' }}
                                    </span>
                                </div>
                            </div>

                            <div class="zoom-record-body">
                                <h3 class="card-heading">{{ $record->title }}</h3>
                                <div class="mt-3 flex flex-wrap gap-2 text-xs text-white/48">
                                    <span class="inline-chip">{{ optional($record->recorded_at)->translatedFormat('d M Y') ?: 'Tanggal belum tersedia' }}</span>
                                    <span class="inline-chip">YouTube Embed</span>
                                </div>
                                <p class="body-copy mt-3 line-clamp-3">
                                    {{ $record->description ?: 'Rekaman sesi Zoom ini dapat dibuka langsung dari portal member.' }}
                                </p>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="pagination-shell">
            {{ $zoomRecords->appends(['watch' => $activeZoomRecord?->slug])->links() }}
        </div>
    </section>
</x-member-layout>
