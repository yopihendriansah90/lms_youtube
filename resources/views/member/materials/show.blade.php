<x-member-layout :title="$material->title">
    <section class="page-stack">
        <div class="page-hero">
            <div class="flex flex-wrap items-center gap-2 text-xs text-white/45">
                <a href="{{ route('member.materials') }}" class="text-brand-200">Materi</a>
                <span>/</span>
                <span>{{ $material->title }}</span>
            </div>
            <div class="mt-4 flex items-start justify-between gap-4">
                <div>
                    <span class="section-pill">{{ $material->access_type === 'free' ? 'Konten Gratis' : 'Konten Premium' }}</span>
                    <h1 class="page-title mt-4">{{ $material->title }}</h1>
                    <p class="page-hero-copy">{{ $material->description ?: 'Materi belajar ini berisi video pembelajaran, dokumen pendukung, dan update rutin.' }}</p>
                </div>
            </div>
        </div>

        <div class="panel-grid">
            <div class="surface-card overflow-hidden p-4">
                @if ($primaryVideo && $canAccessPrimaryVideo)
                    <div class="aspect-video overflow-hidden rounded-[24px] border border-white/8">
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
                    <div class="flex aspect-video flex-col items-center justify-center rounded-[24px] border border-white/8 bg-[radial-gradient(circle_at_top,rgba(63,115,244,0.12),transparent_42%),rgba(255,255,255,0.02)] px-6 text-center">
                        <div class="flex h-20 w-20 items-center justify-center rounded-full border border-white/12 bg-white/6 text-3xl text-white/75">🔒</div>
                        <h2 class="section-title mt-5">Video Terkunci</h2>
                        <p class="body-copy mt-2 max-w-md">
                            Video ini termasuk konten premium. Buka akses untuk menonton langsung dari portal member.
                        </p>
                        <div class="mt-5 flex flex-wrap justify-center gap-3">
                            <button class="primary-btn">Buka Kunci Sekarang</button>
                            <a href="{{ route('member.questions') }}" class="secondary-btn">Tanya Mentor</a>
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
                    </div>
                @endif
            </div>

            <div class="space-y-4">
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
                    <p class="meta-copy">Dokumen PDF</p>
                    <div class="info-list mt-4">
                        @forelse ($material->pdfDocuments as $document)
                            <div class="info-item">
                                <p class="text-sm font-semibold text-white">{{ $document->title }}</p>
                                <p class="mt-1 text-xs text-white/45">{{ $document->access_type === 'free' ? 'Gratis' : 'Premium' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-white/45">Belum ada dokumen PDF.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="rich-card">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="meta-copy">Update Materi</p>
                    <h2 class="section-title mt-2">Perkembangan Terbaru</h2>
                </div>
            </div>

            <div class="grid gap-4">
                @forelse ($material->updates as $update)
                    <article class="feature-list-card">
                        <p class="card-heading">{{ $update->title }}</p>
                        <p class="body-copy mt-2">{{ \Illuminate\Support\Str::limit(strip_tags((string) $update->content), 180) }}</p>
                    </article>
                @empty
                    <p class="text-sm text-white/45">Belum ada update terbaru untuk materi ini.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-member-layout>
