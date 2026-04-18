<x-member-layout title="Data Mentor">
    <section class="page-stack">
        <div class="page-hero">
            <span class="section-pill">Mentor Support</span>
            <h1 class="page-title mt-4">Data Mentor</h1>
            <p class="page-hero-copy">
                Kenali mentor aktif yang mendampingi materi, diskusi, dan sesi pembelajaran di program ini.
            </p>
        </div>

        <div class="grid gap-4">
            @foreach ($mentors as $mentor)
                <article class="rich-card">
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/16 text-xl font-bold text-brand-200">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($mentor->display_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="card-heading">{{ $mentor->display_name }}</h2>
                            <p class="mt-1 text-sm text-brand-200">{{ $mentor->speciality ?: 'Mentor utama kelas' }}</p>
                            <p class="body-copy mt-3">{{ $mentor->short_bio ?: 'Mentor siap membantu materi, diskusi strategi, dan evaluasi pembelajaran.' }}</p>

                            <div class="mt-4 flex flex-wrap gap-2 text-xs text-white/48">
                                @if ($mentor->user?->email)
                                    <span class="inline-chip">{{ $mentor->user->email }}</span>
                                @endif
                                @if ($mentor->whatsapp_number)
                                    <span class="inline-chip">{{ $mentor->whatsapp_number }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</x-member-layout>
