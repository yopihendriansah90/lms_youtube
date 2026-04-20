<x-member-layout title="Room Zoom Meeting">
    <section class="page-stack">
        <div class="section-header">
            <span class="section-pill">Live Zoom Room</span>
            <h1 class="section-header-title">Room Zoom & Pertanyaan Live</h1>
            <p class="section-header-copy">
                Ikuti sesi Zoom yang sedang berlangsung, kirim pertanyaan ke mentor saat live, dan buka kembali riwayat pertanyaan per room dari satu halaman yang sama.
            </p>
            <div class="section-header-indicator">
                <div class="section-header-indicator-dot"></div>
            </div>
        </div>

        @if (session('status'))
            <div class="rich-card border border-brand-400/20 bg-brand-400/[0.07]">
                <p class="text-sm font-medium text-brand-100">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="rich-card border border-danger-400/30 bg-danger-400/[0.07]">
                <p class="text-sm font-semibold text-danger-400">Form pertanyaan belum bisa dikirim.</p>
                <ul class="mt-3 space-y-1 text-sm text-white/72">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($selectedRoom)
            @php
                $statusLabel = match ($selectedRoom->status) {
                    'live' => 'Sedang Berlangsung',
                    'scheduled' => 'Terjadwal',
                    default => 'Selesai',
                };

                $statusClass = match ($selectedRoom->status) {
                    'live' => 'is-live',
                    'scheduled' => 'is-paid',
                    default => 'is-free',
                };
            @endphp

            <div id="room-stage" class="grid gap-4 xl:grid-cols-[1.08fr_0.92fr]">
                <div class="rich-card room-stage-card" data-zoom-parallax>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="status-chip {{ $statusClass }}">{{ $statusLabel }}</span>
                        @if ($selectedRoom->program?->title)
                            <span class="inline-chip">{{ $selectedRoom->program->title }}</span>
                        @endif
                        @if ($selectedRoom->mentor?->name)
                            <span class="inline-chip">{{ $selectedRoom->mentor->name }}</span>
                        @endif
                    </div>

                    <h2 class="section-title mt-4">{{ $selectedRoom->title }}</h2>
                    <p class="body-copy mt-3">
                        {{ $selectedRoom->description ?: 'Room Zoom ini dipakai untuk sesi pembelajaran live dan pengumpulan pertanyaan dari member saat mentor sedang mengajar.' }}
                    </p>

                    <div class="room-detail-grid mt-5">
                        <div class="content-mini-panel">
                            <p class="meta-copy">Jadwal Sesi</p>
                            <p class="mt-2 text-sm font-semibold text-white">
                                {{ optional($selectedRoom->starts_at)->translatedFormat('d F Y, H:i') ?: 'Belum dijadwalkan' }}
                            </p>
                        </div>
                        <div class="content-mini-panel">
                            <p class="meta-copy">Meeting ID</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $selectedRoom->meeting_id ?: '-' }}</p>
                        </div>
                        <div class="content-mini-panel">
                            <p class="meta-copy">Passcode</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $selectedRoom->passcode ?: '-' }}</p>
                        </div>
                        <div class="content-mini-panel">
                            <p class="meta-copy">Total Pertanyaan</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ number_format($selectedRoom->questions_count) }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
                        @if ($selectedRoom->status === 'live')
                            <a href="{{ $selectedRoom->join_url }}" target="_blank" rel="noreferrer" class="primary-btn">
                                Join Zoom Sekarang
                            </a>
                        @endif
                        <a href="#room-questions" class="secondary-btn">
                            Lihat Riwayat Tanya
                        </a>
                    </div>
                </div>

                <div class="rich-card">
                    <p class="meta-copy">Kirim Pertanyaan</p>
                    <h2 class="section-title mt-2">Pertanyaan Untuk Mentor Live</h2>

                    @if ($canAskQuestion)
                        <form method="POST" action="{{ route('member.rooms.questions.store', $selectedRoom) }}" class="mt-5 space-y-4">
                            @csrf

                            <div>
                                <label class="form-label">Subjek Pertanyaan</label>
                                <input
                                    type="text"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    class="field-shell"
                                    placeholder="Opsional, misalnya: Bagian funnel penawaran"
                                >
                            </div>

                            <div>
                                <label class="form-label">Pertanyaan</label>
                                <textarea
                                    name="question"
                                    rows="6"
                                    class="field-shell"
                                    placeholder="Tuliskan pertanyaan Anda untuk sesi live ini..."
                                >{{ old('question') }}</textarea>
                                @error('question')
                                    <p class="mt-2 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="primary-btn w-full">Kirim Pertanyaan Live</button>
                        </form>
                    @else
                        <div class="room-question-state mt-5">
                            <span class="status-chip {{ $statusClass }}">{{ $statusLabel }}</span>
                            <p class="body-copy mt-4">
                                Form pertanyaan hanya aktif saat sesi Zoom sedang berlangsung. Riwayat pertanyaan untuk room ini tetap bisa dibaca di bawah.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div id="room-questions" class="rich-card">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="meta-copy">Riwayat Pertanyaan</p>
                        <h2 class="section-title mt-2">Pertanyaan Untuk Room Ini</h2>
                    </div>
                    <span class="eyebrow">{{ $selectedRoom->questions->count() }} pertanyaan terbaru</span>
                </div>

                <div class="space-y-4">
                    @forelse ($selectedRoom->questions as $question)
                        <article class="feature-list-card">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <h3 class="card-heading">{{ $question->subject ?: 'Pertanyaan tanpa subjek' }}</h3>
                                    <p class="mt-1 text-xs text-white/45">
                                        {{ $question->member?->name ?? 'Member' }} • {{ optional($question->asked_at)->translatedFormat('d M Y H:i') ?: '-' }}
                                    </p>
                                </div>
                                <span class="inline-chip">Sesi {{ $statusLabel }}</span>
                            </div>
                            <p class="body-copy mt-4">{{ $question->question }}</p>
                        </article>
                    @empty
                        <p class="text-sm text-white/45">Belum ada pertanyaan yang tercatat untuk room ini.</p>
                    @endforelse
                </div>
            </div>
        @endif

        @if ($zoomRooms->count())
            <div class="rich-card">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="meta-copy">Daftar Room</p>
                        <h2 class="section-title mt-2">Semua Sesi Zoom</h2>
                    </div>
                    <span class="eyebrow">{{ $zoomRooms->total() }} room</span>
                </div>

                <div class="room-list-grid">
                    @foreach ($zoomRooms as $room)
                        @php
                            $roomStatusLabel = match ($room->status) {
                                'live' => 'Sedang Berlangsung',
                                'scheduled' => 'Terjadwal',
                                default => 'Selesai',
                            };

                            $roomStatusClass = match ($room->status) {
                                'live' => 'is-live',
                                'scheduled' => 'is-paid',
                                default => 'is-free',
                            };
                        @endphp

                        <a
                            href="{{ route('member.rooms', ['room' => $room->slug]) }}#room-stage"
                            class="room-list-card {{ $selectedRoom?->is($room) ? 'is-active' : '' }}"
                            data-zoom-parallax
                        >
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="status-chip {{ $roomStatusClass }}">{{ $roomStatusLabel }}</span>
                                <span class="inline-chip">{{ optional($room->starts_at)->translatedFormat('d M Y') ?: 'Jadwal belum ada' }}</span>
                            </div>

                            <h3 class="card-heading mt-4">{{ $room->title }}</h3>
                            <p class="body-copy mt-3 line-clamp-3">
                                {{ $room->description ?: 'Room Zoom ini dipakai untuk sesi live dan pengumpulan pertanyaan member.' }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2 text-xs text-white/48">
                                @if ($room->mentor?->name)
                                    <span class="inline-chip">{{ $room->mentor->name }}</span>
                                @endif
                                <span class="inline-chip">{{ $room->questions_count }} pertanyaan</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pagination-shell">
                {{ $zoomRooms->appends(['room' => $selectedRoom?->slug])->links() }}
            </div>
        @else
            <div class="rich-card">
                <p class="meta-copy">Belum Ada Room</p>
                <h2 class="section-title mt-2">Sesi live belum tersedia</h2>
                <p class="body-copy mt-4">
                    Room Zoom yang sedang berlangsung atau yang akan datang akan muncul di halaman ini setelah dipublikasikan dari panel admin.
                </p>
            </div>
        @endif
    </section>
</x-member-layout>
