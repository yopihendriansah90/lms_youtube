<x-member-layout title="Tanya Jawab Mentor">
    <section class="page-stack">
        <div class="section-header">
            <span class="section-pill">Mentor Support</span>
            <h1 class="section-header-title">Tanya Jawab Mentor Faruq</h1>
            <p class="section-header-copy">
                Ajukan pertanyaan seputar materi, strategi belajar, atau kendala teknis. Semua pertanyaan akan masuk ke dashboard mentor untuk ditindaklanjuti.
            </p>
            <div class="section-header-indicator">
                <div class="section-header-indicator-dot"></div>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-[0.95fr_1.05fr]">
            <div class="rich-card">
                <h2 class="section-title">Form Pertanyaan</h2>
                <form method="POST" action="{{ route('member.questions.store') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="form-label">Pilih Mentor</label>
                        <select name="mentor_id" class="field-shell">
                            <option value="">Pilih mentor tujuan</option>
                            @foreach ($mentors as $mentor)
                                <option value="{{ $mentor->user_id }}">{{ $mentor->display_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Terkait Materi</label>
                        <select name="material_id" class="field-shell">
                            <option value="">Pilih materi</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Subjek</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="field-shell" placeholder="Contoh: Strategi memahami materi 2">
                        @error('subject')
                            <p class="mt-2 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Pertanyaan</label>
                        <textarea name="question" rows="6" class="field-shell" placeholder="Tuliskan pertanyaan Anda secara detail...">{{ old('question') }}</textarea>
                        @error('question')
                            <p class="mt-2 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="primary-btn w-full">Kirim Pertanyaan</button>
                </form>
            </div>

            <div class="rich-card">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="section-title">Riwayat Tanya Jawab</h2>
                    <span class="eyebrow">{{ $questions->total() }} pertanyaan</span>
                </div>

                <div class="space-y-4">
                    @forelse ($questions as $question)
                        <article class="feature-list-card">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="card-heading">{{ $question->subject }}</h3>
                                    <p class="mt-1 text-xs text-white/45">{{ $question->material?->title ?? 'Materi umum' }} • {{ optional($question->asked_at)->translatedFormat('d M Y H:i') }}</p>
                                </div>
                                <span class="status-chip {{ $question->status === 'answered' ? 'is-free' : 'is-paid' }}">
                                    {{ $question->status === 'answered' ? 'Dijawab' : 'Diproses' }}
                                </span>
                            </div>
                            <p class="body-copy mt-4">{{ $question->question }}</p>

                            @if ($question->answers->isNotEmpty())
                                <div class="mt-4 rounded-2xl border border-brand-400/15 bg-brand-400/8 p-4">
                                    <p class="eyebrow">Jawaban Mentor</p>
                                    <p class="body-copy mt-2 text-white/74">{{ $question->answers->first()->answer }}</p>
                                </div>
                            @endif
                        </article>
                    @empty
                        <p class="text-sm text-white/45">Belum ada pertanyaan yang pernah Anda kirim.</p>
                    @endforelse
                </div>

                <div class="pagination-shell mt-5">
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </section>
</x-member-layout>
