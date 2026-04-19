<x-member-layout title="Daftar Materi">
    <section class="materials-page-stack">
        <div class="materials-page-header">
            <h1 class="materials-page-title">Racikan Pembelajaran Alfaruq WFA</h1>
        </div>

        @if ($featuredMaterial && $featuredPrimaryVideo)
            <a href="{{ route('member.materials.show', $featuredMaterial) }}" class="materials-hero-frame block">
                <div class="overflow-hidden rounded-[24px] border border-white/8 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.02),transparent_52%),rgba(255,255,255,0.01)] shadow-[0_20px_40px_rgba(0,0,0,0.22)]">
                    @if ($featuredMaterial->cover_url)
                        <div class="materials-hero-media relative">
                            <img
                                src="{{ $featuredMaterial->cover_url }}"
                                alt="{{ $featuredMaterial->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(6,8,13,0.12),rgba(6,8,13,0.74))]"></div>
                            <div class="absolute inset-x-0 bottom-0 p-6 sm:p-7">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="status-chip {{ $featuredMaterial->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                                        {{ $featuredMaterial->access_type === 'free' ? 'Gratis' : 'Premium' }}
                                    </span>
                                    @if ($featuredPrimaryVideo->access_type === 'free')
                                        <span class="inline-chip">Video pembuka gratis</span>
                                    @endif
                                </div>
                                <h2 class="materials-card-title mt-4 text-left">{{ $featuredMaterial->title }}</h2>
                                <p class="body-copy mt-3 max-w-2xl text-left">{{ $featuredMaterial->excerpt ?: 'Materi pilihan untuk mulai belajar dari cover materi utama.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="materials-hero-lock flex flex-col items-center justify-center text-center">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full border border-white/12 bg-white/[0.06] text-white/80 sm:h-18 sm:w-18">
                                <span class="text-xs font-semibold tracking-[0.18em] uppercase">{{ $featuredPrimaryVideo->access_type === 'paid' ? 'Lock' : 'Play' }}</span>
                            </div>
                            <p class="mt-5 text-[11px] font-semibold tracking-[0.18em] text-white/62 uppercase">Mulai Belajar Sekarang</p>
                        </div>
                    @endif
                </div>
            </a>
        @endif

        <div class="materials-mobile-grid">
            @if ($featuredMaterial)
                <a href="{{ route('member.materials.show', $featuredMaterial) }}" class="materials-mobile-card text-center transition hover:border-white/14 hover:bg-white/[0.03]">
                    <div class="materials-card-media mb-3">
                        @if ($featuredMaterial->cover_url)
                            <img
                                src="{{ $featuredMaterial->cover_url }}"
                                alt="{{ $featuredMaterial->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        @elseif ($featuredPrimaryVideo?->youtube_video_id)
                            <img
                                src="https://img.youtube.com/vi/{{ $featuredPrimaryVideo->youtube_video_id }}/hqdefault.jpg"
                                alt="{{ $featuredMaterial->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full items-center justify-center">
                                <p class="meta-copy">Video Placeholder</p>
                            </div>
                        @endif
                    </div>
                    <h2 class="materials-card-title">{{ $featuredMaterial->title }}</h2>
                    <p class="materials-card-action">
                        {{ $featuredCanAccess ? 'Akses Materi' : 'Buka Kunci Materi' }}
                    </p>
                </a>
            @endif
            @foreach ($materials as $material)
                <a href="{{ route('member.materials.show', $material) }}" class="materials-mobile-card text-center transition hover:border-white/14 hover:bg-white/[0.03]">
                    <div class="materials-card-media mb-3">
                        @if ($material->cover_url)
                            <img
                                src="{{ $material->cover_url }}"
                                alt="{{ $material->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        @elseif ($material->primary_video?->youtube_video_id)
                            <img
                                src="https://img.youtube.com/vi/{{ $material->primary_video->youtube_video_id }}/hqdefault.jpg"
                                alt="{{ $material->title }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full items-center justify-center">
                                <p class="meta-copy">Video Placeholder</p>
                            </div>
                        @endif
                    </div>
                    <h2 class="materials-card-title">{{ $material->title }}</h2>
                    <div class="mt-2 flex justify-center">
                        <span class="status-chip {{ $material->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                            {{ $material->access_type === 'free' ? 'Gratis' : 'Premium' }}
                        </span>
                    </div>
                    <p class="materials-card-action">
                        {{ $material->can_access_primary_video ? 'Akses Materi' : 'Buka Kunci Materi' }}
                    </p>
                </a>
            @endforeach
        </div>

        <div class="pagination-shell">
            {{ $materials->links() }}
        </div>
    </section>
</x-member-layout>
