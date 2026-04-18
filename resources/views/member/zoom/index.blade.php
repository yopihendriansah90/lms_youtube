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

        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($zoomRecords as $record)
                <article class="overflow-hidden rounded-[28px] border border-white/8 bg-[linear-gradient(180deg,rgba(18,24,38,0.96),rgba(10,14,22,0.96))] shadow-[0_18px_55px_rgba(0,0,0,0.28)]">
                    <div class="flex aspect-video items-center justify-center bg-[linear-gradient(140deg,rgba(63,115,244,0.22),rgba(10,14,22,0.96))] text-5xl text-white/80">▶</div>
                    <div class="px-5 py-5 text-white">
                        <p class="card-heading">{{ $record->title }}</p>
                        <p class="muted-copy mt-2">{{ optional($record->recorded_at)->translatedFormat('d F Y') ?: 'Tanggal belum tersedia' }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-shell">
            {{ $zoomRecords->links() }}
        </div>
    </section>
</x-member-layout>
