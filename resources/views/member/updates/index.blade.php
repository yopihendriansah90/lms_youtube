<x-member-layout title="Update Materi">
    <section class="page-stack">
        <div class="section-header">
            <span class="section-pill">Daily Learning Updates</span>
            <h1 class="section-header-title">Update Materi Terbaru</h1>
            <p class="section-header-copy">
                Ikuti perkembangan materi harian, video tambahan, dan pengumuman penting agar perjalanan belajar Anda selalu terarah.
            </p>
            <div class="section-header-indicator">
                <div class="section-header-indicator-dot"></div>
            </div>
        </div>

        <div class="grid gap-4">
            @foreach ($updates as $update)
                <article class="rich-card overflow-hidden">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="meta-copy">{{ $update->material?->title ?? 'Materi umum' }}</p>
                            <h2 class="section-title mt-2">{{ $update->title }}</h2>
                            <p class="body-copy mt-4">{{ \Illuminate\Support\Str::limit(strip_tags((string) $update->content), 260) }}</p>
                        </div>
                        <div class="hidden rounded-3xl border border-white/8 bg-white/[0.02] px-4 py-3 text-right text-xs text-white/45 sm:block">
                            {{ optional($update->published_at)->translatedFormat('d M Y') ?: '-' }}
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-shell">
            {{ $updates->links() }}
        </div>
    </section>
</x-member-layout>
