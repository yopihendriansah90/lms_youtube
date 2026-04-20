<x-member-layout :title="$material->title">
    @php
        $documentStats = [
            'total' => $material->pdfDocuments->count(),
            'open' => $material->pdfDocuments->where('can_access', true)->count(),
            'locked' => $material->pdfDocuments->where('can_access', false)->count(),
        ];
    @endphp

    <section class="page-stack">
        <div class="section-header">
            <div class="flex flex-wrap items-center gap-2 text-xs text-white/45">
                <a href="{{ route('member.materials') }}" class="text-brand-200">Materi</a>
                <span>/</span>
                <span>{{ $material->title }}</span>
            </div>
            <div class="mt-4 flex items-start justify-center gap-4">
                <div>
                    <span class="section-pill">{{ $material->access_type === 'free' ? 'Konten Gratis' : 'Konten Premium' }}</span>
                    <h1 class="section-header-title">{{ $material->title }}</h1>
                    <p class="section-header-copy">{{ $material->description ?: 'Materi belajar ini berisi video pembelajaran, dokumen pendukung, dan update rutin.' }}</p>
                </div>
            </div>
            <div class="section-header-indicator">
                <div class="section-header-indicator-dot"></div>
            </div>
        </div>

        @if ($material->cover_url)
            <div class="surface-card overflow-hidden">
                <div class="relative aspect-[16/7] min-h-[13rem] sm:min-h-[16rem]">
                    <img
                        src="{{ $material->cover_url }}"
                        alt="{{ $material->title }}"
                        class="h-full w-full object-cover"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(6,8,13,0.1),rgba(6,8,13,0.82))]"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 sm:p-7">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="status-chip {{ $material->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                                {{ $material->access_type === 'free' ? 'Gratis' : 'Premium' }}
                            </span>
                            <span class="inline-chip">{{ $material->videos->count() }} video</span>
                            <span class="inline-chip">{{ $documentStats['total'] }} PDF</span>
                        </div>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/72">
                            {{ $material->excerpt ?: 'Cover materi ini membantu member mengenali tema belajar sebelum masuk ke video dan dokumen pendukung.' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid gap-4 xl:grid-cols-[1.18fr_0.82fr] xl:items-start">
            <div class="space-y-4">
                <div id="video-player" class="video-player-stage surface-card overflow-hidden p-4">
                    <div class="video-player-orb"></div>
                    <div class="video-player-orb is-secondary"></div>
                    @if ($primaryVideo && $canAccessPrimaryVideo)
                        <div class="video-player-shell aspect-video overflow-hidden rounded-[24px] border border-white/8">
                            <iframe
                                class="h-full w-full"
                                src="https://www.youtube.com/embed/{{ $primaryVideo->youtube_video_id }}"
                                title="{{ $primaryVideo->title }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen
                            ></iframe>
                        </div>
                    @else
                        <div class="video-player-shell flex aspect-video flex-col items-center justify-center rounded-[24px] border border-white/8 bg-[radial-gradient(circle_at_top,rgba(63,115,244,0.12),transparent_42%),rgba(255,255,255,0.02)] px-6 text-center">
                            <div class="flex h-20 w-20 items-center justify-center rounded-full border border-white/12 bg-white/6 text-3xl text-white/75">🔒</div>
                            <h2 class="section-title mt-5">Video Terkunci</h2>
                            <p class="body-copy mt-2 max-w-md">
                                Video ini termasuk konten premium. Buka akses untuk menonton langsung dari portal member.
                            </p>
                            <div class="mt-5 flex flex-wrap justify-center gap-3">
                                @if ($materialRequestAccessUrl)
                                    <a href="{{ $materialRequestAccessUrl }}" target="_blank" rel="noopener noreferrer" class="request-access-btn">Minta Akses</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($primaryVideo)
                        <div class="feature-list-card mt-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="card-heading">{{ $primaryVideo->title }}</p>
                                    <p class="muted-copy mt-1">{{ $primaryVideo->description ?: 'Video utama materi ini siap dipelajari.' }}</p>
                                </div>
                                <span class="status-chip {{ $primaryVideo->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                                    {{ $primaryVideo->access_type === 'free' ? 'Gratis' : 'Premium' }}
                                </span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="inline-chip">{{ $primaryVideo->access_type === 'free' ? 'Video Gratis' : 'Video Premium' }}</span>
                                <span class="inline-chip">{{ $primaryVideo->can_access ? 'Sudah Bisa Ditonton' : 'Masih Terkunci' }}</span>
                            </div>
                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="content-mini-panel">
                                    <p class="meta-copy">Aksi Utama</p>
                                    <p class="mt-2 text-sm font-semibold text-white">
                                        {{ $primaryVideo->can_access ? 'Lanjutkan menonton video aktif.' : 'Video aktif ini memerlukan akses premium.' }}
                                    </p>
                                </div>
                                <div class="content-mini-panel">
                                    <p class="meta-copy">Status Akses</p>
                                    <p class="mt-2 text-sm font-semibold text-white">
                                        {{ $primaryVideo->can_access ? 'Akun Anda sudah bisa membuka video ini.' : 'Silakan unlock materi untuk membuka seluruh video penuh.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="rich-card">
                    <p class="meta-copy">Detail Materi</p>
                    <div class="detail-list mt-4">
                        <div class="detail-row">
                            <span>Mentor</span>
                            <span class="detail-value">{{ $material->mentor?->name ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span>Tipe Akses</span>
                            <span class="detail-value">{{ $material->access_type === 'free' ? 'Gratis' : 'Berbayar' }}</span>
                        </div>
                        <div class="detail-row">
                            <span>Harga</span>
                            <span class="detail-value">Rp {{ number_format((float) $material->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="rich-card">
                    <p class="meta-copy">Ringkasan Konten</p>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <div class="content-mini-panel">
                            <p class="text-[11px] font-semibold tracking-[0.14em] text-white/45 uppercase">Video</p>
                            <p class="mt-3 text-2xl font-bold tracking-[-0.04em] text-white">{{ $material->videos->count() }}</p>
                            <p class="mt-2 text-sm text-white/50">Video pembelajaran tersedia.</p>
                        </div>
                        <div class="content-mini-panel">
                            <p class="text-[11px] font-semibold tracking-[0.14em] text-white/45 uppercase">PDF Terbuka</p>
                            <p class="mt-3 text-2xl font-bold tracking-[-0.04em] text-white">{{ $documentStats['open'] }}</p>
                            <p class="mt-2 text-sm text-white/50">Dokumen siap dibuka dari akun ini.</p>
                        </div>
                    </div>
                </div>

                <div class="rich-card">
                    <p class="meta-copy">Dokumen PDF</p>
                    <div class="info-list mt-4">
                        @forelse ($material->pdfDocuments as $document)
                            <div class="document-card">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="card-heading text-[1.05rem]">{{ $document->title }}</p>
                                        <p class="mt-1 text-xs text-white/45">{{ $document->access_type === 'free' ? 'Gratis' : 'Premium' }}</p>
                                    </div>
                                    <span class="status-chip {{ $document->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                                        {{ $document->access_type === 'free' ? 'Gratis' : 'Premium' }}
                                    </span>
                                </div>
                                @if ($document->description)
                                    <p class="body-copy mt-3">{{ $document->description }}</p>
                                @endif
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-chip border-brand-400/20 bg-brand-400/8 text-brand-200">
                                        {{ $document->can_access ? 'Siap Dibuka' : 'Masih Terkunci' }}
                                    </span>
                                    @if ($document->file_name)
                                        <span class="inline-chip">{{ $document->file_name }}</span>
                                    @endif
                                </div>
                                <div class="document-meta-grid">
                                    <div class="content-mini-panel compact">
                                        <p class="meta-copy">Jenis Dokumen</p>
                                        <p class="mt-2 text-sm font-semibold text-white">{{ $document->access_type === 'free' ? 'Akses Gratis' : 'Akses Premium' }}</p>
                                    </div>
                                    <div class="content-mini-panel compact">
                                        <p class="meta-copy">Aksi Member</p>
                                        <p class="mt-2 text-sm font-semibold text-white">
                                            {{ $document->can_access ? 'Buka di tab baru atau simpan PDF ini.' : 'Minta akses untuk membuka file PDF ini.' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-3">
                                    @if ($document->download_url)
                                        <a href="{{ $document->download_url }}" target="_blank" rel="noopener noreferrer" class="primary-btn">Buka PDF</a>
                                    @else
                                        @if ($materialRequestAccessUrl)
                                            <a href="{{ $materialRequestAccessUrl }}" target="_blank" rel="noopener noreferrer" class="request-access-btn">Minta Akses</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-white/45">Belum ada dokumen PDF.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="rich-card xl:sticky xl:top-6">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="meta-copy">Daftar Video Materi</p>
                        <h2 class="section-title mt-2">Seluruh Video Dalam Tema Kelas Ini</h2>
                    </div>
                </div>

                <div class="grid gap-4">
                    @forelse ($material->videos as $video)
                        <article class="feature-list-card overflow-hidden">
                            <a
                                href="{{ route('member.materials.show', ['material' => $material, 'video' => $video->id]) }}#video-player"
                                class="block overflow-hidden rounded-[20px] border border-white/8 bg-white/[0.02] transition hover:border-brand-400/30 hover:brightness-110"
                        >
                            @if ($video->thumbnail_url)
                                <img
                                    src="{{ $video->thumbnail_url }}"
                                    alt="{{ $video->title }}"
                                    class="aspect-video h-auto w-full object-cover"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex aspect-video items-center justify-center bg-white/[0.02]">
                                    <p class="meta-copy">Thumbnail Video</p>
                                </div>
                            @endif
                        </a>
                        <div class="mt-4 flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="card-heading">{{ $video->title }}</p>
                                <p class="body-copy mt-1.5 line-clamp-2">{{ $video->description ?: 'Video pembelajaran untuk materi ini.' }}</p>
                            </div>
                            <span class="status-chip {{ $video->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                                {{ $video->access_type === 'free' ? 'Gratis' : 'Premium' }}
                            </span>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="inline-chip compact">{{ $video->access_type === 'free' ? 'Video Gratis' : 'Video Premium' }}</span>
                            <span class="inline-chip compact border-brand-400/20 bg-brand-400/8 text-brand-200">
                                {{ $video->can_access ? 'Sudah Terbuka' : 'Perlu Unlock' }}
                            </span>
                        </div>
                        <div class="mt-3 grid gap-2 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/8 bg-white/[0.015] px-3 py-2.5">
                                <p class="meta-copy compact">Jenis Video</p>
                                <p class="mt-1 text-[13px] leading-5 font-semibold text-white">{{ $video->access_type === 'free' ? 'Video ini terbuka untuk semua member.' : 'Video ini mengikuti akses premium materi.' }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/8 bg-white/[0.015] px-3 py-2.5">
                                <p class="meta-copy compact">Status Akun</p>
                                <p class="mt-1 text-[13px] leading-5 font-semibold text-white">{{ $video->can_access ? 'Bisa diputar sekarang.' : 'Konten ini masih terkunci.' }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('member.materials.show', ['material' => $material, 'video' => $video->id]) }}#video-player" class="primary-btn">
                                {{ $primaryVideo?->is($video) ? 'Video Sedang Diputar' : 'Lihat Video' }}
                            </a>
                            @unless ($video->can_access)
                                @if ($video->request_access_url)
                                    <a href="{{ $video->request_access_url }}" target="_blank" rel="noopener noreferrer" class="request-access-btn">Minta Akses</a>
                                @endif
                            @endunless
                        </div>
                        </article>
                    @empty
                        <p class="text-sm text-white/45">Belum ada video pembelajaran untuk materi ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </section>
</x-member-layout>
