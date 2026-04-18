<x-member-layout title="Daftar Materi">
    <section class="page-stack">
        <div class="page-hero">
            <span class="section-pill">Modul Belajar</span>
            <h1 class="page-title mt-4">Daftar Materi Pembelajaran</h1>
            <p class="page-hero-copy">
                Pilih materi yang ingin dipelajari. Video gratis bisa langsung diputar, sedangkan materi premium memerlukan unlock terlebih dahulu.
            </p>
        </div>

        @if ($latestUpdates->isNotEmpty())
            <div class="rich-card">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="meta-copy">Update Materi</p>
                        <h2 class="section-title mt-2">Update Terbaru</h2>
                    </div>
                    <a href="{{ route('member.updates') }}" class="card-link">Lihat semua</a>
                </div>

                <div class="mt-5 grid gap-3">
                    @foreach ($latestUpdates as $update)
                        <article class="feature-list-card">
                            <p class="meta-copy">{{ $update->material?->title ?? 'Materi umum' }}</p>
                            <h3 class="card-heading mt-3">{{ $update->title }}</h3>
                            <p class="body-copy mt-2">{{ \Illuminate\Support\Str::limit(strip_tags((string) $update->content), 140) }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid gap-4">
            @foreach ($materials as $material)
                <a href="{{ route('member.materials.show', $material) }}" class="rich-card transition hover:border-brand-400/25">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="card-title">{{ $material->title }}</h2>
                            <p class="body-copy mt-2">{{ $material->excerpt ?: 'Materi pembelajaran dengan video, PDF, dan update harian.' }}</p>
                        </div>
                        <span class="status-chip {{ $material->access_type === 'free' ? 'is-free' : 'is-paid' }}">
                            {{ $material->access_type === 'free' ? 'Gratis' : 'Berbayar' }}
                        </span>
                    </div>

                    <div class="mt-5 flex flex-wrap items-center gap-2 text-xs text-white/45">
                        <span class="inline-chip">{{ $material->mentor?->name ?? 'Mentor belum ditentukan' }}</span>
                        <span class="inline-chip">{{ $material->videos->count() }} video</span>
                        @if ($material->access_type === 'paid')
                            <span class="inline-chip border-brand-400/20 bg-brand-400/8 text-brand-200">Rp {{ number_format((float) $material->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="pagination-shell">
            {{ $materials->links() }}
        </div>
    </section>
</x-member-layout>
